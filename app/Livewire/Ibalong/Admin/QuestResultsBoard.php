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

    // Scoring Limits
    public $maxPossibleScore = 0;
    public $dynamicMaxScore = 0;

    // Filter States
    public $selectedJudge = 'all';
    public $availableJudges = [];

    public $selectedGroup = 'all';
    public $availableGroups = [];

    public function mount($quest_id)
    {
        $role = auth('ibalong')->user()->role_id ?? 0;

        if (!in_array($role, [1, 2, 4])) {
            abort(403, 'ACCESS DENIED: Only the Admin Command may view tabulated results.');
        }

        $this->quest_id = $quest_id;
        $this->calculateLeaderboard();
    }

    // Livewire lifecycle hooks: Recalculate instantly when filters change
    public function updatedSelectedJudge() { $this->calculateLeaderboard(); }
    public function updatedSelectedGroup() { $this->calculateLeaderboard(); }

    public function calculateLeaderboard()
    {
        $this->quest = IbalongQuest::with('criteria')->findOrFail($this->quest_id);
        $this->maxPossibleScore = $this->quest->criteria->sum('max_score');

        // 1. Determine Evaluation Groups
        $groups = $this->quest->criteria->groupBy(function($c) {
            return !empty($c->evaluation_group) ? $c->evaluation_group : 'Main Scoring Matrix';
        });
        $this->availableGroups = array_keys($groups->toArray());

        // 2. Set dynamic max score based on selected group filter
        if ($this->selectedGroup === 'all') {
            $this->dynamicMaxScore = $this->maxPossibleScore;
        } else {
            $this->dynamicMaxScore = $groups[$this->selectedGroup]->sum('max_score');
        }

        $submissions = IbalongQuestSubmission::with(['team', 'scores.judge'])
            ->where('quest_id', $this->quest_id)
            ->whereIn('status', ['reviewing', 'reviewed', 'submitted'])
            ->get();

        $results = [];
        $judgesList = [];

        foreach ($submissions as $sub) {
            $groupAverages = [];
            $judgeTotalsAll = [];
            $judgeGroupScores = []; // Tracks how much Judge X scored Group Y
            $totalFinalScore = 0;

            // 3. Tabulate matrix metrics
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

                    $judgeName = $judgeScores->first()->judge->name ?? 'Unknown Judge';
                    if (!isset($judgeTotalsAll[$judgeName])) {
                        $judgeTotalsAll[$judgeName] = 0;
                        $judgesList[$judgeName] = $judgeName;
                    }
                    // Add to judge's grand total
                    $judgeTotalsAll[$judgeName] += $judgeTotal;
                    // Log judge's score for this specific group
                    $judgeGroupScores[$judgeName][$groupName] = $judgeTotal;
                }

                $groupAvg = $judgeCount > 0 ? ($sumOfJudgeTotals / $judgeCount) : 0;
                $groupAverages[$groupName] = [
                    'average' => $groupAvg,
                    'max' => $critsInGroup->sum('max_score')
                ];

                $totalFinalScore += $groupAvg;
            }

            $totalJudgesOverall = count($judgeTotalsAll);
            $category = $sub->team->category ?? 'General Classification';

            // 4. Core Sorting Logic based on Filters
            $sortScore = 0;
            if ($this->selectedJudge === 'all' && $this->selectedGroup === 'all') {
                $sortScore = round($totalFinalScore, 2);
            } elseif ($this->selectedJudge === 'all' && $this->selectedGroup !== 'all') {
                $sortScore = round($groupAverages[$this->selectedGroup]['average'] ?? 0, 2);
            } elseif ($this->selectedJudge !== 'all' && $this->selectedGroup === 'all') {
                $sortScore = $judgeTotalsAll[$this->selectedJudge] ?? 0;
            } elseif ($this->selectedJudge !== 'all' && $this->selectedGroup !== 'all') {
                $sortScore = $judgeGroupScores[$this->selectedJudge][$this->selectedGroup] ?? 0;
            }

            $results[] = [
                'submission_id' => $sub->id,
                'team_name' => $sub->team->team_name ?? 'Unknown Cohort',
                'ticket_code' => $sub->team->ticket_code ?? 'N/A',
                'category' => $category,
                'judge_totals' => $judgeTotalsAll,
                'judge_group_scores' => $judgeGroupScores,
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
