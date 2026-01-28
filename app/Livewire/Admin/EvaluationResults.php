<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Evaluation;
use App\Models\EvaluationQuestion;
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
            
            // Skip if no responses to avoid division by zero
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
                $counts = array_fill(0, count($question->options), 0); // Initialize counts [0,0,0,0,0]

                foreach ($question->answers as $answer) {
                    // Find which option index this answer matches (e.g., "Strongly Agree" might be index 4)
                    $index = array_search($answer->answer_value, $question->options);
                    
                    if ($index !== false) {
                        $weight = $index + 1; // Index 0 = 1 point, Index 4 = 5 points
                        $sum += $weight;
                        $counts[$index]++;
                    }
                }

                $this->stats[$question->id] = [
                    'count' => $totalResponses,
                    'average' => round($sum / $totalResponses, 2), // The "Weighted Mean"
                    'breakdown' => $counts // Counts per choice
                ];
            } 
            
            // B. RADIO / CHOICE LOGIC
            elseif ($question->type === 'radio') {
                $counts = array_fill_keys($question->options, 0); // ['Yes' => 0, 'No' => 0]

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
            
            // C. TEXT / FILE (Just counts)
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