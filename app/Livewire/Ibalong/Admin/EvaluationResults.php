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

    public function mount($slug)
    {
        // Enforce RBAC
        $role = auth('ibalong')->user()->role_id ?? 0;
        if (!in_array($role, [1, 2, 4])) {
            abort(403, 'ACCESS DENIED: Command Center clearance required to view telemetry data.');
        }

        // Query the correct Ibalong model using the slug from the URL
        $this->evaluation = IbalongEvaluation::with([
            'questions' => fn($q) => $q->orderBy('order', 'asc'),
            'responses.answers',
            'responses.user',
            'responses.team'
        ])->where('slug', $slug)->firstOrFail();

        $this->processData();
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
