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
        $this->submission = IbalongQuestSubmission::with([
            'team',
            'quest.criteria',
            'quest.tasks',
            'answers'
        ])->findOrFail($submission_id);

        $judge_id = auth('ibalong')->id();

        // Load existing scores if the judge is returning to modify them
        $existingScores = IbalongQuestScore::where('submission_id', $this->submission->id)
                            ->where('judge_id', $judge_id)
                            ->get();

        foreach ($existingScores as $score) {
            $this->scores[$score->criteria_id] = $score->score;
            $this->feedback[$score->criteria_id] = $score->feedback;
        }
    }

    public function saveScores()
    {
        $rules = [];
        foreach ($this->submission->quest->criteria as $crit) {
            $rules["scores.{$crit->id}"] = "nullable|numeric|min:0|max:{$crit->max_score}";
            $rules["feedback.{$crit->id}"] = "nullable|string";
        }

        $this->validate($rules);

        $judge_id = auth('ibalong')->id();
        $scoredCount = 0;

        foreach ($this->submission->quest->criteria as $crit) {
            $scoreValue = $this->scores[$crit->id] ?? null;

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
                $scoredCount++;
            } else {
                // If the judge clears an input box they previously saved, delete the record
                IbalongQuestScore::where('submission_id', $this->submission->id)
                    ->where('judge_id', $judge_id)
                    ->where('criteria_id', $crit->id)
                    ->delete();
            }
        }

        // Dynamically update submission status based on completion
        $totalCriteria = $this->submission->quest->criteria->count();
        if ($scoredCount >= $totalCriteria) {
            $this->submission->update(['status' => 'reviewed']);
        } elseif ($scoredCount > 0) {
            $this->submission->update(['status' => 'reviewing']);
        }

        session()->flash('success', 'Evaluation progress successfully saved. You may return to modify these scores at any time.');
    }

    public function render()
    {
        return view('livewire.ibalong.judge.scoring-deck')->layout('layouts.dashboard');
    }
}
