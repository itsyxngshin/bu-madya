<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Evaluation;
use App\Models\EvaluationResponse;
use Livewire\Attributes\Layout;

#[Layout('layouts.madya-admin-deck')]
class EvaluationResults extends Component
{
    public Evaluation $evaluation;
    public $stats = [];

    // [NEW] Tab & Individual Response Tracking
    public $tab = 'summary'; // 'summary' or 'individual'
    public $currentIndex = 0;

    public function mount(Evaluation $evaluation)
    {
        $this->evaluation = $evaluation;
        $this->calculateStats();
    }

    // [NEW] Switch Tabs
    public function setTab($tabName)
    {
        $this->tab = $tabName;
        $this->currentIndex = 0; // Reset index when switching
    }

    // [NEW] Pagination for Individual Responses
    public function nextResponse()
    {
        $total = $this->evaluation->responses()->count();
        if ($this->currentIndex < $total - 1) {
            $this->currentIndex++;
        }
    }

    public function previousResponse()
    {
        if ($this->currentIndex > 0) {
            $this->currentIndex--;
        }
    }

    public function calculateStats()
    {
        // Load questions in order
        $this->evaluation->load(['questions' => function ($query) {
            $query->orderBy('order');
        }, 'questions.answers']);

        foreach ($this->evaluation->questions as $question) {
            
            // Skip structural elements early
            if (in_array($question->type, ['section', 'page_break'])) {
                continue;
            }

            $totalResponses = $question->answers->count();
            
            if ($totalResponses === 0) {
                $this->stats[$question->id] = [
                    'count' => 0, 'average' => 0, 'breakdown' => []
                ];
                continue;
            }

            // Extract Labels safely
            $flatOptions = collect($question->options)->map(function($opt) {
                return is_array($opt) ? ($opt['text'] ?? '') : $opt;
            })->toArray();

            // A. LIKERT LOGIC
            if ($question->type === 'likert') {
                $sum = 0;
                $counts = array_fill(0, count($flatOptions), 0);

                foreach ($question->answers as $answer) {
                    $index = array_search($answer->answer_value, $flatOptions);
                    if ($index !== false) {
                        $sum += ($index + 1);
                        $counts[$index]++;
                    }
                }

                $this->stats[$question->id] = [
                    'count' => $totalResponses,
                    'average' => round($sum / $totalResponses, 2),
                    'breakdown' => $counts
                ];
            } 
            
            // B. RADIO & DROPDOWN LOGIC
            elseif (in_array($question->type, ['radio', 'dropdown'])) {
                $counts = array_fill_keys($flatOptions, 0);

                foreach ($question->answers as $answer) {
                    $val = $answer->answer_value;
                    if (isset($counts[$val])) {
                        $counts[$val]++;
                    }
                }

                $this->stats[$question->id] = [
                    'count' => $totalResponses,
                    'breakdown' => $counts
                ];
            }

            // C. CHECKBOX LOGIC
            elseif ($question->type === 'checkbox') {
                $counts = array_fill_keys($flatOptions, 0);

                foreach ($question->answers as $answer) {
                    $selections = json_decode($answer->answer_value, true);

                    if (is_array($selections)) {
                        foreach ($selections as $selected) {
                            if (array_key_exists($selected, $counts)) {
                                $counts[$selected]++;
                            }
                        }
                    }
                }

                $this->stats[$question->id] = [
                    'count' => $totalResponses,
                    'breakdown' => $counts
                ];
            }
            
            // D. TEXT / FILE
            else {
                $this->stats[$question->id] = [
                    'count' => $totalResponses,
                ];
            }
        }
    }

    public function render()
    {
        $totalResponsesCount = $this->evaluation->responses()->count();
        $currentResponse = null;

        // [NEW] Fetch the specific individual response if on the 'individual' tab
        if ($this->tab === 'individual' && $totalResponsesCount > 0) {
            $currentResponse = EvaluationResponse::with(['answers', 'user'])
                ->where('evaluation_id', $this->evaluation->id)
                ->orderBy('created_at')
                ->skip($this->currentIndex)
                ->first();
        }

        return view('livewire.admin.evaluation-results', [
            'totalResponsesCount' => $totalResponsesCount,
            'currentResponse' => $currentResponse,
        ]);
    }
}