<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Evaluation;
use Livewire\Attributes\Layout;

#[Layout('layouts.madya-admin-deck')]
class EvaluationResults extends Component
{
    public Evaluation $evaluation;
    public $stats = [];

    public function mount(Evaluation $evaluation)
    {
        $this->evaluation = $evaluation;
        $this->calculateStats();
    }

    public function calculateStats()
    {
        // Eager load for performance
        $this->evaluation->load(['questions.answers']);

        foreach ($this->evaluation->questions as $question) {
            
            $totalResponses = $question->answers->count();
            
            // Initialize empty stat structure
            if ($totalResponses === 0) {
                $this->stats[$question->id] = [
                    'count' => 0,
                    'average' => 0,
                    'breakdown' => []
                ];
                continue;
            }

            // A. LIKERT LOGIC
            if ($question->type === 'likert') {
                $sum = 0;
                $counts = array_fill(0, count($question->options), 0);

                foreach ($question->answers as $answer) {
                    $index = array_search($answer->answer_value, $question->options);
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
            
            // B. RADIO LOGIC (Single Choice)
            elseif ($question->type === 'radio') {
                $counts = array_fill_keys($question->options, 0);

                foreach ($question->answers as $answer) {
                    if (isset($counts[$answer->answer_value])) {
                        $counts[$answer->answer_value]++;
                    }
                }

                $this->stats[$question->id] = [
                    'count' => $totalResponses,
                    'breakdown' => $counts
                ];
            }

            // C. [NEW] CHECKBOX LOGIC (Multi Choice)
            elseif ($question->type === 'checkbox') {
                // Initialize counts for all options to 0
                $counts = array_fill_keys($question->options, 0);

                foreach ($question->answers as $answer) {
                    // 1. Decode JSON string: '["Option A","Option B"]' -> ['Option A', 'Option B']
                    $selectedOptions = json_decode($answer->answer_value, true);

                    if (is_array($selectedOptions)) {
                        foreach ($selectedOptions as $selected) {
                            // Increment count for each selected option
                            if (isset($counts[$selected])) {
                                $counts[$selected]++;
                            }
                        }
                    }
                }

                $this->stats[$question->id] = [
                    'count' => $totalResponses, // Total people who answered
                    'breakdown' => $counts // Counts per option
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
        return view('livewire.admin.evaluation-results');
    }
} 