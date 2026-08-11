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

        // 1. Organize criteria into Evaluation Groups
        $groups = $this->quest->criteria->groupBy(function($c) {
            return !empty($c->evaluation_group) ? $c->evaluation_group : 'Main Scoring Matrix';
        });

        $results = [];
        $judgesList = [];

        foreach ($submissions as $sub) {
            $groupAverages = [];
            $judgeTotalsAll = [];
            $totalFinalScore = 0;

            // 2. Fractional Tabulation: Calculate average per group independently
            foreach ($groups as $groupName => $critsInGroup) {
                $critIds = $critsInGroup->pluck('id')->toArray();
                $scoresInGroup = $sub->scores->whereIn('criteria_id', $critIds);
                $scoresByJudgeInGroup = $scoresInGroup->groupBy('judge_id');

                $sumOfJudgeTotals = 0;
                $judgeCount = 0;

                foreach ($scoresByJudgeInGroup as $judgeId => $judgeScores) {
                    $judgeTotal = $judgeScores->sum('score');
                    $sumOfJudgeTotals += $judgeTotal;
                    $judgeCount++;

                    // Track overall judge totals across all groups they graded for the dropdown
                    $judgeName = $judgeScores->first()->judge->name ?? 'Unknown Judge';
                    if (!isset($judgeTotalsAll[$judgeName])) {
                        $judgeTotalsAll[$judgeName] = 0;
                        $judgesList[$judgeName] = $judgeName;
                    }
                    $judgeTotalsAll[$judgeName] += $judgeTotal;
                }

                // Group Average = Sum of judge scores for this group / Number of judges who graded this group
                $groupAvg = $judgeCount > 0 ? ($sumOfJudgeTotals / $judgeCount) : 0;
                $groupAverages[$groupName] = [
                    'average' => $groupAvg,
                    'max' => $critsInGroup->sum('max_score')
                ];

                // Final Score = Sum of Group Averages
                $totalFinalScore += $groupAvg;
            }

            $totalJudgesOverall = count($judgeTotalsAll);
            $category = $sub->team->category ?? 'General Classification';

            // Determine the sorting metric based on the active filter
            $sortScore = 0;
            if ($this->selectedJudge === 'all') {
                $sortScore = round($totalFinalScore, 2);
            } else {
                $sortScore = $judgeTotalsAll[$this->selectedJudge] ?? 0;
            }

            $results[] = [
                'submission_id' => $sub->id,
                'team_name' => $sub->team->team_name ?? 'Unknown Cohort',
                'ticket_code' => $sub->team->ticket_code ?? 'N/A',
                'category' => $category,
                'judge_totals' => $judgeTotalsAll,
                'total_judges' => $totalJudgesOverall,
                'group_averages' => $groupAverages,
                'final_score' => round($totalFinalScore, 2),
                'sort_score' => $sortScore,
                'status' => $sub->status,
            ];
        }

        $this->availableJudges = array_values($judgesList);

        $collection = collect($results);
        $grouped = $collection->groupBy('category');

        $this->categorizedLeaderboard = $grouped->map(function ($group) {
            return $group->sortByDesc('sort_score')->values()->toArray();
        })->toArray();
    }

    public function render()
    {
        return view('livewire.ibalong.admin.quest-results-board')->layout('layouts.dashboard');
    }
}
