<?php

namespace App\Livewire\Ibalong\Admin;

use Livewire\Component;
use App\Models\IbalongQuest;
use App\Models\IbalongQuestSubmission;

class QuestResultsBoard extends Component
{
    public $quest_id;
    public $quest;
    public $categorizedLeaderboard = [];
    public $maxPossibleScore = 0;

    // Filter State
    public $selectedJudge = 'all';
    public $availableJudges = [];

    public function mount($quest_id)
    {
        $role = auth('ibalong')->user()->role_id ?? 0;

        if (!in_array($role, [1, 2, 4])) {
            abort(403, 'ACCESS DENIED: Only the Admin Command may view tabulated results.');
        }

        $this->quest_id = $quest_id;
        $this->calculateLeaderboard();
    }

    // Livewire lifecycle hook: Runs automatically when $selectedJudge changes
    public function updatedSelectedJudge()
    {
        $this->calculateLeaderboard();
    }

    public function calculateLeaderboard()
    {
        $this->quest = IbalongQuest::with('criteria')->findOrFail($this->quest_id);
        $this->maxPossibleScore = $this->quest->criteria->sum('max_score');

        $submissions = IbalongQuestSubmission::with(['team', 'scores.judge'])
            ->where('quest_id', $this->quest_id)
            ->whereIn('status', ['reviewing', 'reviewed', 'submitted'])
            ->get();

        $results = [];
        $judgesList = [];

        foreach ($submissions as $sub) {
            $judgeTotals = [];
            $scoresByJudge = $sub->scores->groupBy('judge_id');

            foreach ($scoresByJudge as $judgeId => $scores) {
                $judgeName = $scores->first()->judge->name ?? 'Unknown Judge';
                $judgeTotals[$judgeName] = $scores->sum('score');
                $judgesList[$judgeName] = $judgeName; // Collect unique judge names
            }

            $totalJudges = count($judgeTotals);
            $average = $totalJudges > 0 ? array_sum($judgeTotals) / $totalJudges : 0;
            $category = $sub->team->category ?? 'General Classification';

            // Determine the sorting metric based on the active filter
            $sortScore = 0;
            if ($this->selectedJudge === 'all') {
                $sortScore = round($average, 2);
            } else {
                $sortScore = $judgeTotals[$this->selectedJudge] ?? 0;
            }

            $results[] = [
                'submission_id' => $sub->id,
                'team_name' => $sub->team->team_name ?? 'Unknown Cohort',
                'ticket_code' => $sub->team->ticket_code ?? 'N/A',
                'category' => $category,
                'judge_totals' => $judgeTotals,
                'total_judges' => $totalJudges,
                'average_score' => round($average, 2),
                'sort_score' => $sortScore,
                'status' => $sub->status,
            ];
        }

        // Store unique judges for the dropdown
        $this->availableJudges = array_values($judgesList);

        $collection = collect($results);
        $grouped = $collection->groupBy('category');

        // Sort dynamically based on the target metric (sort_score)
        $this->categorizedLeaderboard = $grouped->map(function ($group) {
            return $group->sortByDesc('sort_score')->values()->toArray();
        })->toArray();
    }

    public function render()
    {
        return view('livewire.ibalong.admin.quest-results-board')->layout('layouts.dashboard');
    }
}
