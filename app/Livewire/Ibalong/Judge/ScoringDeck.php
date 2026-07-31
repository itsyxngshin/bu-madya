<?php

namespace App\Livewire\Ibalong\Judge;

use Livewire\Component;
use App\Models\IbalongQuestSubmission;
use App\Models\IbalongQuestScore;

class ScoringDeck extends Component
{
    public $submission;
    public $scores = [];
    public $feedback = [];

    public function mount($submission_id)
    {
        // Load the submission with ALL nested data required for judging
        $this->submission = IbalongQuestSubmission::with([
            'team', 
            'quest.criteria', 
            'quest.tasks', 
            'answers'
        ])->findOrFail($submission_id);

        $judge_id = auth('ibalong')->id();

        // Pre-fill existing scores if the judge is returning to an evaluation
        $existingScores = IbalongQuestScore::where('submission_id', $this->submission->id)
                            ->where('judge_id', $judge_id)
                            ->get();

        foreach ($existingScores as $score) {
            $this->scores[$score->criteria_id] = $score->score;
            $this->feedback[$score->criteria_id] = $score->feedback;
        }
    }

    public function lockScores()
    {
        $rules = [];
        foreach ($this->submission->quest->criteria as $crit) {
            $rules["scores.{$crit->id}"] = "required|numeric|min:0|max:{$crit->max_score}";
            $rules["feedback.{$crit->id}"] = "nullable|string";
        }

        $this->validate($rules);

        $judge_id = auth('ibalong')->id();

        foreach ($this->submission->quest->criteria as $crit) {
            IbalongQuestScore::updateOrCreate(
                [
                    'submission_id' => $this->submission->id,
                    'judge_id' => $judge_id,
                    'criteria_id' => $crit->id,
                ],
                [
                    'score' => $this->scores[$crit->id],
                    'feedback' => $this->feedback[$crit->id] ?? null,
                ]
            );
        }

        // Mark as reviewed (You could also base this on whether all judges have scored)
        $this->submission->update(['status' => 'reviewed']);

        session()->flash('success', 'The Weighing is complete. Scores securely locked.');
    }

    public function render()
    {
        return view('livewire.ibalong.judge.scoring-deck')->layout('layouts.dashboard');
    }
}