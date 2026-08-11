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
        if ($this->hasScored) {
            session()->flash('error', 'SYSTEM LOCK: Your evaluation is already recorded and cannot be altered.');
            return;
        }

        $rules = [];
        foreach ($this->submission->quest->criteria as $crit) {
            // UPGRADED: Changed from 'required' to 'nullable' to support Specialized Judges
            $rules["scores.{$crit->id}"] = "nullable|numeric|min:0|max:{$crit->max_score}";
            $rules["feedback.{$crit->id}"] = "nullable|string";
        }

        $this->validate($rules);

        $judge_id = auth('ibalong')->id();
        $scoredAnything = false;

        foreach ($this->submission->quest->criteria as $crit) {
            $scoreValue = $this->scores[$crit->id] ?? null;

            // Only record the score if the judge actually inputted a number
            if ($scoreValue !== null && $scoreValue !== '') {
                IbalongQuestScore::updateOrCreate(
                    [
                        'submission_id' => $this->submission->id,
                        'judge_id' => $judge_id,
                        'criteria_id' => $crit->id,
                    ],
                    [
                        'score' => $scoreValue,
                        'feedback' => $this->feedback[$crit->id] ?? null,
                    ]
                );
                $scoredAnything = true;
            }
        }

        // Security check: Make sure they didn't just submit a completely blank form
        if (!$scoredAnything) {
            session()->flash('error', 'SYSTEM REJECT: You must input at least one score to lock an evaluation.');
            return;
        }

        $this->submission->update(['status' => 'reviewed']);
        $this->hasScored = true;

        session()->flash('success', 'The Weighing is complete. Scores securely locked.');
    }

    public function render()
    {
        return view('livewire.ibalong.judge.scoring-deck')->layout('layouts.dashboard');
    }
}
