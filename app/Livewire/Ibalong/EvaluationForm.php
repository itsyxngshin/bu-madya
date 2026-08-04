<?php

namespace App\Livewire\Ibalong;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\IbalongEvaluation;
use App\Models\IbalongEvaluationResponse;

class EvaluationForm extends Component
{
    use WithFileUploads;

    public IbalongEvaluation $evaluation;
    
    // Data Storage
    public $answers = [];
    public $files = [];
    public $isSubmitted = false;

    // Pagination & Flow Control State
    public $currentPage = 0;
    public $totalPages = 1;
    public $pageMap = [];      // Stores question IDs for each page index
    public $sectionMap = [];   // Maps a Section ID to a page index
    public $pageBreaks = [];   // Maps a page index to its explicit page break jump
    public $history = [];      // Tracks the path taken so the "Back" button works

    public function mount($slug)
    {
        $this->evaluation = IbalongEvaluation::with(['questions' => function($q) {
            $q->orderBy('order', 'asc');
        }])->where('slug', $slug)->firstOrFail();

        if (!$this->evaluation->is_active) {
            abort(403, 'SYSTEM HALT: This form is currently offline.');
        }

        if ($this->evaluation->access_level === 'teams_only' && !isset(auth('ibalong')->user()->registration)) {
            abort(403, 'CLEARANCE DENIED: This evaluation is strictly restricted to registered cohorts.');
        }

        $this->initializePagination();
    }

    private function initializePagination()
    {
        $page = 0;
        foreach ($this->evaluation->questions as $q) {
            if ($q->type === 'page_break') {
                // Register the explicit jump directive for this page break
                $this->pageBreaks[$page] = $q->options[0]['jump'] ?? null;
                $page++;
            } else {
                $this->pageMap[$page][] = $q->id;
                
                if ($q->type === 'section') {
                    $this->sectionMap[$q->id] = $page;
                }
                if ($q->type === 'checkbox') {
                    $this->answers[$q->id] = [];
                }
            }
        }
        $this->totalPages = $page + 1;
    }

    public function validateCurrentPage()
    {
        $rules = [];
        $currentQIds = $this->pageMap[$this->currentPage] ?? [];
        $currentQuestions = $this->evaluation->questions->whereIn('id', $currentQIds);

        foreach ($currentQuestions as $q) {
            if (in_array($q->type, ['section', 'page_break'])) continue;

            $baseRule = $q->is_required ? 'required' : 'nullable';

            if ($q->type === 'file') {
                $rules["files.{$q->id}"] = "$baseRule|file|max:5120"; // 5MB Limit
            } elseif ($q->type === 'checkbox') {
                $rules["answers.{$q->id}"] = "$baseRule|array";
            } else {
                $rules["answers.{$q->id}"] = "$baseRule";
            }
        }

        if (!empty($rules)) {
            $this->validate($rules, [
                'required' => 'This field is required to proceed.',
                'array' => 'Invalid selection.',
                'file' => 'Must be a valid file under 5MB.'
            ]);
        }
    }

    public function nextPage()
    {
        // 1. Validate the current page before allowing the jump
        $this->validateCurrentPage();

        // 2. Add current page to history for the "Back" button
        $this->history[] = $this->currentPage;

        // 3. Calculate the target destination
        $jumpTarget = null;
        $currentQIds = $this->pageMap[$this->currentPage] ?? [];
        $currentQuestions = $this->evaluation->questions->whereIn('id', $currentQIds);

        // A. Check if a radio/dropdown option triggers a jump
        foreach ($currentQuestions as $q) {
            if (in_array($q->type, ['radio', 'dropdown']) && !empty($this->answers[$q->id])) {
                $answer = $this->answers[$q->id];
                foreach ($q->options as $opt) {
                    $optText = is_array($opt) ? ($opt['text'] ?? '') : $opt;
                    if ($optText === $answer && !empty($opt['jump'])) {
                        $jumpTarget = $opt['jump'];
                    }
                }
            }
        }

        // B. Fallback to the Page Break's default jump
        if (!$jumpTarget) {
            $jumpTarget = $this->pageBreaks[$this->currentPage] ?? null;
        }

        // 4. Execute the Flow Logic
        if ($jumpTarget === 'submit' || $this->currentPage >= ($this->totalPages - 1)) {
            // Check edge case: A specific section jump on the final page
            if ($jumpTarget !== 'submit' && $jumpTarget !== null && isset($this->sectionMap[$jumpTarget])) {
                $this->currentPage = $this->sectionMap[$jumpTarget];
            } else {
                $this->submit();
                return;
            }
        } else {
            // Jump to a specific section or proceed linearly
            if ($jumpTarget && isset($this->sectionMap[$jumpTarget])) {
                $this->currentPage = $this->sectionMap[$jumpTarget];
            } else {
                $this->currentPage++;
            }
        }
    }

    public function previousPage()
    {
        if (count($this->history) > 0) {
            $this->currentPage = array_pop($this->history);
        }
    }

    public function submit()
    {
        // NOTE: We do not call full validation here, because conditional jumps 
        // may have bypassed 'required' fields on other pages.

        $response = IbalongEvaluationResponse::create([
            'evaluation_id' => $this->evaluation->id,
            'user_id' => auth('ibalong')->id(),
            'team_id' => auth('ibalong')->user()->registration->id ?? null,
        ]);

        foreach ($this->evaluation->questions as $q) {
            if (in_array($q->type, ['section', 'page_break'])) continue;

            $val = null;

            if ($q->type === 'file' && isset($this->files[$q->id])) {
                $file = $this->files[$q->id];
                $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $sanitizedName = preg_replace('/[^a-zA-Z0-9_]/', '_', $originalName) ?: 'upload';
                $finalName = $sanitizedName . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                
                $val = $file->storeAs("evaluations/responses/{$response->id}", $finalName, 'public');
            } elseif ($q->type === 'checkbox') {
                // Only save if it's not empty, ignoring untouched arrays
                $val = !empty($this->answers[$q->id]) ? json_encode($this->answers[$q->id]) : null;
            } else {
                $val = $this->answers[$q->id] ?? null;
            }

            if ($val !== null && $val !== '' && $val !== '[]') {
                $response->answers()->create([
                    'question_id' => $q->id,
                    'answer_value' => $val,
                ]);
            }
        }

        $this->isSubmitted = true;
    }

    public function render()
    {
        return view('livewire.ibalong.evaluation-form')->layout('layouts.dashboard');
    }
}