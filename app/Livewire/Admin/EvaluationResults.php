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
        $this->evaluation->load(['questions.answers']);

        foreach ($this->evaluation->questions as $question) {
            
            $totalResponses = $question->answers->count();
            
            if ($totalResponses === 0) {
                $this->stats[$question->id] = [
                    'count' => 0, 'average' => 0, 'breakdown' => []
                ];
                continue;
            }

            // --- HELPER: Extract Labels safely ---
            // Handles both old format ("Yes") and new format (["text" => "Yes", "jump" => ...])
            $flatOptions = collect($question->options)->map(function($opt) {
                return is_array($opt) ? ($opt['text'] ?? '') : $opt;
            })->toArray();


            // A. LIKERT LOGIC (Usually simple strings, but good to be safe)
            if ($question->type === 'likert') {
                $sum = 0;
                $counts = array_fill(0, count($flatOptions), 0);

                foreach ($question->answers as $answer) {
                    // Match answer against the flattened labels
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
            
            // B. RADIO LOGIC (Single Choice)
            elseif ($question->type === 'radio') {
                // [FIX] Use flatOptions for keys to avoid Array-to-String error
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

            // C. CHECKBOX LOGIC (Multi Choice)
            elseif ($question->type === 'checkbox') {
                // [FIX] Use flatOptions for keys
                $counts = array_fill_keys($flatOptions, 0);

                foreach ($question->answers as $answer) {
                    $selections = json_decode($answer->answer_value, true);

                    if (is_array($selections)) {
                        foreach ($selections as $selected) {
                            // Ensure the selected option exists in our keys before counting
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
        return view('livewire.admin.evaluation-results');
    }
} 