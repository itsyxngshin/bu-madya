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
        // Eager load questions with their answers
        $this->evaluation->load(['questions.answers']);

        foreach ($this->evaluation->questions as $question) {
            
            $totalResponses = $question->answers->count();
            
            // Skip if no responses
            if ($totalResponses === 0) {
                $this->stats[$question->id] = [
                    'count' => 0,
                    'average' => 0,
                    'breakdown' => []
                ];
                continue;
            }

            // A. LIKERT SCALE LOGIC
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
            
            // B. RADIO LOGIC
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

            // C. [NEW] CHECKBOX LOGIC (THIS WAS MISSING)
            elseif ($question->type === 'checkbox') {
                // Initialize counts for all options to 0
                $counts = array_fill_keys($question->options, 0);

                foreach ($question->answers as $answer) {
                    // Decode the JSON array: '["Option A", "Option B"]'
                    $selections = json_decode($answer->answer_value, true);

                    // Safety check: ensure it is an array before looping
                    if (is_array($selections)) {
                        foreach ($selections as $selected) {
                            if (isset($counts[$selected])) {
                                $counts[$selected]++;
                            }
                        }
                    }
                }

                $this->stats[$question->id] = [
                    'count' => $totalResponses,
                    'breakdown' => $counts // This key is required by the view!
                ];
            }
            
            // D. TEXT / FILE (List View)
            else {
                $this->stats[$question->id] = [
                    'count' => $totalResponses,
                    // No 'breakdown' needed here as the view loops raw answers
                ];
            }
        }
    }

    public function render()
    {
        return view('livewire.admin.evaluation-results');
    }
}