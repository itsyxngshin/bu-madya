<?php

namespace App\Livewire\Ibalong\Admin;

use Livewire\Component;
use App\Models\IbalongEvaluation;
use Illuminate\Support\Facades\Storage;

class EvaluationResults extends Component
{
    public $evaluation;
    public $tallies = [];
    public $textResponses = [];

    public function mount(Evaluation $evaluation)
    {
        // 1. Assign the property FIRST before doing anything else
        $this->evaluation = $evaluation;
        
        $user = auth()->user();

        // 2. Now it is safe to read $this->evaluation
        $isCollaborator = $this->evaluation->exists ? $this->evaluation->collaborators()->where('user_id', $user->id)->exists() : false;

        // Block if not Admin, not Creator, AND not Collaborator
        if ($this->evaluation->exists &&
            $user->role?->role_name !== 'administrator' &&
            $this->evaluation->created_by !== $user->id &&
            !$isCollaborator) {
            abort(403, 'You do not have permission to access this evaluation.');
        }

        $this->calculateStats();
    }

    private function processData()
    {
        foreach ($this->evaluation->questions as $question) {
            if (in_array($question->type, ['section', 'page_break'])) continue;

            if (in_array($question->type, ['radio', 'dropdown', 'checkbox', 'likert'])) {
                // Initialize counts
                $counts = [];
                foreach ($this->evaluation->responses as $response) {
                    $answer = $response->answers->where('question_id', $question->id)->first();
                    if ($answer) {
                        $val = $answer->answer_value;
                        if (is_array($val)) { // For Checkboxes
                            foreach ($val as $v) {
                                $counts[$v] = ($counts[$v] ?? 0) + 1;
                            }
                        } else {
                            $counts[$val] = ($counts[$val] ?? 0) + 1;
                        }
                    }
                }
                $this->tallies[$question->id] = $counts;
            } else {
                // Collect Text, Textarea, and Files
                $texts = [];
                foreach ($this->evaluation->responses as $response) {
                    $answer = $response->answers->where('question_id', $question->id)->first();
                    if ($answer && !empty($answer->answer_value)) {
                        $texts[] = [
                            'user' => $response->user->name ?? 'Anonymous',
                            'team' => $response->team->team_name ?? 'No Affiliation',
                            'value' => $answer->answer_value,
                            'date' => $answer->created_at->format('M d, y H:i')
                        ];
                    }
                }
                $this->textResponses[$question->id] = $texts;
            }
        }
    }

    public function render()
    {
        return view('livewire.ibalong.admin.evaluation-results')->layout('layouts.dashboard');
    }
}
