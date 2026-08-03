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

    // NEW: Property to track if the judge has already submitted
    public $hasScored = false;

    public function mount($submission_id)
    {
        $this->submission = IbalongQuestSubmission::with([
            'team',
            'quest.criteria',
            'quest.tasks',
            'answers'
        ])->findOrFail($submission_id);

        $judge_id = auth('ibalong')->id();

        $existingScores = IbalongQuestScore::where('submission_id', $this->submission->id)
                            ->where('judge_id', $judge_id)
                            ->get();

        // If records exist, lock the terminal
        if ($existingScores->count() > 0) {
            $this->hasScored = true;
        }

        foreach ($existingScores as $score) {
            $this->scores[$score->criteria_id] = $score->score;
            $this->feedback[$score->criteria_id] = $score->feedback;
        }
    }

    public function lockScores()
    {
        // Prevent double submission if they try to bypass the UI
        if ($this->hasScored) {
            session()->flash('error', 'SYSTEM LOCK: Your evaluation is already recorded and cannot be altered.');
            return;
        }

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

        $this->submission->update(['status' => 'reviewed']);

        // Lock the terminal for this judge after successful save
        $this->hasScored = true;

        session()->flash('success', 'The Weighing is complete. Scores securely locked.');
    }

    public function render()
    {
        return view('livewire.ibalong.judge.scoring-deck')->layout('layouts.dashboard');
    }
}
