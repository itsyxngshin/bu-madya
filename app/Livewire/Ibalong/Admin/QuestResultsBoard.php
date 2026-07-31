<?php

namespace App\Livewire\Ibalong\Admin;

use Livewire\Component;
use App\Models\IbalongQuest;
use App\Models\IbalongQuestSubmission;

class QuestResultsBoard extends Component
{
    public $quest;
    public $leaderboard = [];
    public $maxPossibleScore = 0;

    public function mount($quest_id)
    {
        $role = auth('ibalong')->user()->role_id ?? 0;
        
        // RBAC: Only Admins & Directors can view the master tabulation
        if (!in_array($role, [1, 2, 4])) {
            abort(403, 'ACCESS DENIED: Only the Admin Command may view tabulated results.');
        }

        $this->quest = IbalongQuest::with('criteria')->findOrFail($quest_id);
        $this->maxPossibleScore = $this->quest->criteria->sum('max_score');

        // Fetch submissions that have actually reached the judging phase
        $submissions = IbalongQuestSubmission::with(['team', 'scores.judge'])
            ->where('quest_id', $quest_id)
            ->whereIn('status', ['reviewing', 'reviewed', 'submitted'])
            ->get();

        $results = [];

        foreach ($submissions as $sub) {
            $judgeTotals = [];
            
            // Group the scores by the judge who cast them
            $scoresByJudge = $sub->scores->groupBy('judge_id');

            foreach ($scoresByJudge as $judgeId => $scores) {
                // We use a fallback just in case a judge account was deleted
                $judgeName = $scores->first()->judge->name ?? 'Unknown Judge';
                $judgeTotals[$judgeName] = $scores->sum('score');
            }

            $totalJudges = count($judgeTotals);
            $average = $totalJudges > 0 ? array_sum($judgeTotals) / $totalJudges : 0;

            $results[] = [
                'submission_id' => $sub->id,
                'team_name' => $sub->team->team_name ?? 'Unknown Cohort',
                'ticket_code' => $sub->team->ticket_code ?? 'N/A',
                'judge_totals' => $judgeTotals,
                'total_judges' => $totalJudges,
                'average_score' => round($average, 2),
                'status' => $sub->status,
            ];
        }

        // Sort the array descending to create the Leaderboard ranking
        usort($results, fn($a, $b) => $b['average_score'] <=> $a['average_score']);
        
        $this->leaderboard = $results;
    }

    public function render()
    {
        return view('livewire.ibalong.admin.quest-results-board')->layout('layouts.dashboard');
    }
}