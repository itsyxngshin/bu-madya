<?php

namespace App\Livewire\Open;

use Livewire\Component;
use App\Models\Evaluation;
use App\Models\EvaluationResponse;
use App\Models\EvaluationAnswer;
use Illuminate\Support\Facades\Auth;
use Livewire\WithFileUploads;
use Livewire\Attributes\Layout;

#[Layout('layouts.madya-template')]
class EvaluationForm extends Component
{
    use WithFileUploads;

    public Evaluation $evaluation;
    public $project_id = null;
    public $answers = []; 
    public $isSubmitted = false;
    public $connect_account = false; 
    public $currentPage = 0;

    protected $queryString = ['project_id'];

    // --- AUTOSAVE LOGIC: Generate a unique key for this user/guest ---
    private function getDraftKey()
    {
        $identifier = Auth::check() ? 'user_' . Auth::id() : 'ip_' . request()->ip();
        return 'evaluation_draft_' . $this->evaluation->id . '_' . $identifier;
    }

    public function mount(Evaluation $evaluation)
    {
        $this->evaluation = $evaluation;
        
        if (request()->has('project_id')) {
            $this->project_id = request()->query('project_id');
        }

        // 1. Initialize empty answers based on type
        foreach($evaluation->questions as $q) {
            $this->answers[$q->id] = ($q->type === 'checkbox') ? [] : '';
        }

        // 2. [NEW] RESTORE AUTOSAVED DRAFT
        $draft = cache()->get($this->getDraftKey());
        if ($draft) {
            foreach ($draft as $qId => $val) {
                // We only restore if the question still exists on the form
                if (isset($this->answers[$qId])) {
                    $this->answers[$qId] = $val;
                }
            }
        }
    }

    // --- [NEW] AUTOSAVE LOGIC: Triggers automatically when ANY answer changes ---
    public function updatedAnswers()
    {
        // Filter out file uploads (files cannot be safely cached this way, they must be re-uploaded)
        $cacheableAnswers = collect($this->answers)->filter(function ($value) {
            return !($value instanceof \Illuminate\Http\UploadedFile);
        })->toArray();

        // Save to cache for 7 days
        cache()->put($this->getDraftKey(), $cacheableAnswers, now()->addDays(7));
        
        // Dispatch event to show a subtle "Saved" checkmark on the frontend
        $this->dispatch('draft-autosaved');
    }

    // --- DYNAMIC PAGE BUILDER (Respects Skip Logic) ---
    private function getPages()
    {
        // ... (Keep your exact existing getPages() logic here) ...
        $pages = [];
        $pageIndex = 0;
        $skipping = false;
        $targetSectionId = null;

        $sortedQuestions = $this->evaluation->questions->sortBy('order');

        foreach ($sortedQuestions as $question) {
            if ($skipping && $question->type === 'section' && $question->id == $targetSectionId) {
                $skipping = false;
                $targetSectionId = null;
            }

            if (!$skipping) {
                if ($question->type === 'page_break') {
                    $pageIndex++;
                } else {
                    $pages[$pageIndex][] = $question;
                }

                if (isset($this->answers[$question->id]) && $question->type === 'radio') {
                    $selectedAnswer = $this->answers[$question->id];
                    if (is_array($question->options)) {
                        foreach ($question->options as $opt) {
                            $optText = is_array($opt) ? ($opt['text'] ?? '') : $opt;
                            $jumpTarget = is_array($opt) ? ($opt['jump'] ?? null) : null;

                            if ($optText == $selectedAnswer && !empty($jumpTarget)) {
                                $skipping = true;
                                $targetSectionId = ($jumpTarget === 'submit') ? 9999999 : $jumpTarget;
                            }
                        }
                    }
                }
            }
        }
        return array_values(array_filter($pages)); 
    }

    // --- NAVIGATION ---
    public function nextPage()
    {
        $pages = $this->getPages();
        $this->validateCurrentPage($pages[$this->currentPage] ?? []);

        if ($this->currentPage < count($pages) - 1) {
            $this->currentPage++;
            $this->dispatch('scroll-to-top'); 
        }
    }

    public function previousPage()
    {
        if ($this->currentPage > 0) {
            $this->currentPage--;
            $this->dispatch('scroll-to-top');
        }
    }

    private function validateCurrentPage($currentQuestions)
    {
        $rules = [];
        foreach ($currentQuestions as $q) {
            if ($q->is_required && $q->type !== 'section') {
                $rules["answers.{$q->id}"] = 'required';
            }
        }
        if (!empty($rules)) {
            $this->validate($rules, ['required' => 'This question is required.']);
        }
    }

    // --- SUBMISSION ---
    public function submit()
    {
        $pages = $this->getPages();
        $this->validateCurrentPage($pages[$this->currentPage] ?? []);

        $userId = ($this->connect_account && Auth::check()) ? Auth::id() : null;

        $response = EvaluationResponse::create([
            'evaluation_id' => $this->evaluation->id,
            'user_id' => $userId,
            'ip_address' => request()->ip(),
        ]);

        $visibleQuestionIds = collect($pages)->flatten()->pluck('id')->toArray();

        foreach ($this->answers as $questionId => $value) {
            if (!in_array($questionId, $visibleQuestionIds)) continue; 
            if ($value === '' || $value === null || $value === []) continue;

            $finalValue = $value;

            if ($value instanceof \Illuminate\Http\UploadedFile) {
                $finalValue = $value->store('evaluation-uploads', 'public');
            } elseif (is_array($value)) {
                $finalValue = json_encode($value);
            }

            EvaluationAnswer::create([
                'evaluation_response_id' => $response->id,
                'evaluation_question_id' => $questionId,
                'answer_value' => $finalValue
            ]);
        }

        // [NEW] CLEAR THE DRAFT CACHE UPON SUCCESSFUL SUBMIT
        cache()->forget($this->getDraftKey());

        $this->isSubmitted = true;
        $this->dispatch('scroll-to-top'); 
    }

    public function render()
    {
        $pages = $this->getPages();
        $totalPages = count($pages);
        $progress = $totalPages > 0 ? round((($this->currentPage + 1) / $totalPages) * 100) : 0;

        return view('livewire.open.evaluation-form', [
            'pages' => $pages,
            'totalPages' => $totalPages,
            'progress' => $progress
        ]);
    }
}