<?php

namespace App\Livewire\Ibalong\Admin;

use Livewire\Component;
use App\Models\IbalongQuest;
use App\Models\IbalongQuestSubmission;
use Illuminate\Support\Str;

class QuestResultsBoard extends Component
{
    public $quest_id;
    public $quest;
    public $categorizedLeaderboard = [];
    public $allSubmissionsData = [];
    public $rankingsMap = [];

    // Scoring Limits
    public $maxPossibleScore = 0;
    public $dynamicMaxScore = 0;

    // Filter States
    public $selectedJudge = 'all';
    public $availableJudges = [];

    public $selectedGroup = 'all';
    public $availableGroups = [];
    public $groupCriteriaMap = [];

    public $divisionMode = 'categorized';

    public function mount($quest_id)
    {
        $user = auth('ibalong')->user() ?? auth()->user();
        $role = $user->role_id ?? 0;

        if (!in_array($role, [1, 2, 4])) {
            abort(403, 'ACCESS DENIED: Only the Admin Command may view tabulated results.');
        }

        $this->quest_id = $quest_id;
        $this->calculateLeaderboard();
    }

    public function updatedSelectedJudge() { $this->calculateLeaderboard(); }
    public function updatedSelectedGroup() { $this->calculateLeaderboard(); }
    public function updatedDivisionMode() { $this->calculateLeaderboard(); }

    public function calculateLeaderboard()
    {
        $this->quest = IbalongQuest::with('criteria')->findOrFail($this->quest_id);
        $this->maxPossibleScore = $this->quest->criteria->sum('max_score');

        // 1. Determine Evaluation Groups & Map Criteria
        $critNameLookup = $this->quest->criteria->pluck('name', 'id')->toArray();
        $groups = $this->quest->criteria->groupBy(function($c) {
            return !empty($c->evaluation_group) ? $c->evaluation_group : 'Main Scoring Matrix';
        });

        $this->availableGroups = array_keys($groups->toArray());
        $this->groupCriteriaMap = [];
        foreach ($groups as $groupName => $crits) {
            $this->groupCriteriaMap[$groupName] = $crits->pluck('name', 'id')->toArray();
        }

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
            $judgeGroupScores = [];
            $judgeCriteriaScores = [];
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
                    $judgeTotalsAll[$judgeName] += $judgeTotal;
                    $judgeGroupScores[$judgeName][$groupName] = $judgeTotal;

                    foreach ($judgeScores as $scoreModel) {
                        $cName = $critNameLookup[$scoreModel->criteria_id] ?? 'Unknown Criteria';
                        $judgeCriteriaScores[$judgeName][$groupName][$cName] = $scoreModel->score;
                    }
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
                'judge_criteria_scores' => $judgeCriteriaScores,
                'total_judges' => $totalJudgesOverall,
                'group_averages' => $groupAverages,
                'final_score' => round($totalFinalScore, 2),
                'sort_score' => $sortScore,
                'status' => $sub->status,
            ];
        }

        $this->availableJudges = array_values($judgesList);
        $this->allSubmissionsData = $results;

        // 5. Pre-Calculate Cumulative Ranks
        $resultsColl = collect($results);
        $rankMap = [];

        foreach($resultsColl->sortByDesc('final_score')->values() as $index => $row) {
            $rankMap[$row['submission_id']]['global_overall'] = $index + 1;
        }
        foreach($resultsColl->groupBy('category') as $cat => $catRows) {
            foreach($catRows->sortByDesc('final_score')->values() as $index => $row) {
                $rankMap[$row['submission_id']]['division_overall'] = $index + 1;
            }
        }
        foreach($this->availableGroups as $groupName) {
            foreach($resultsColl->sortByDesc(function($item) use ($groupName) {
                return $item['group_averages'][$groupName]['average'] ?? 0;
            })->values() as $index => $row) {
                $rankMap[$row['submission_id']]['global_matrices'][$groupName] = $index + 1;
            }
            foreach($resultsColl->groupBy('category') as $cat => $catRows) {
                foreach($catRows->sortByDesc(function($item) use ($groupName) {
                    return $item['group_averages'][$groupName]['average'] ?? 0;
                })->values() as $index => $row) {
                    $rankMap[$row['submission_id']]['division_matrices'][$groupName] = $index + 1;
                }
            }
        }
        $this->rankingsMap = $rankMap;

        // 6. Apply UI Filters for the Live View
        if ($this->divisionMode === 'unified') {
            $grouped = collect(['Unified Global' => $resultsColl]);
        } else {
            $grouped = $resultsColl->groupBy('category');
        }

        $this->categorizedLeaderboard = $grouped->map(function ($group) {
            return $group->sortByDesc('sort_score')->values()->toArray();
        })->toArray();
    }

    // --- STANDARD EXPORTS ---
    // --- UPGRADED: MULTI-TAB EXCEL EXPORT ENGINE ---
    public function exportToExcel()
    {
        $modeName = $this->divisionMode === 'unified' ? 'unified_global' : 'categorized';
        $fileName = 'tabulation_master_' . $modeName . '_' . Str::slug($this->quest->title) . '_' . date('Y-m-d_H-i-s') . '.xls';

        // 1. Initialize MS Excel XML Format
        $xml = '<?xml version="1.0"?>' . "\n";
        $xml .= '<?mso-application progid="Excel.Sheet"?>' . "\n";
        $xml .= '<Workbook xmlns="urn:schemas-microsoft-com:office:spreadsheet" xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns:ss="urn:schemas-microsoft-com:office:spreadsheet" xmlns:html="http://www.w3.org/TR/REC-html40">' . "\n";

        // Setup Bold Styling for Headers
        $xml .= '<Styles><Style ss:ID="s1"><Font ss:Bold="1"/></Style></Styles>' . "\n";

        // --- TAB 1: MASTER LEADERBOARD ---
        $xml .= '<Worksheet ss:Name="Master Leaderboard"><Table>' . "\n";

        $xml .= '<Row>';
        $headers = [];
        if ($this->divisionMode === 'unified') {
            $headers = ['UI Filter Rank', 'Original Division', 'Team Name', 'Ticket Code'];
        } else {
            $headers = ['Division', 'UI Filter Rank', 'Team Name', 'Ticket Code'];
        }
        $headers[] = 'Global Overall Rank';
        $headers[] = 'Division Overall Rank';
        $headers[] = 'FINAL SCORE METRIC';
        $headers[] = 'STATUS';

        foreach ($headers as $h) {
            $xml .= '<Cell ss:StyleID="s1"><Data ss:Type="String">'.htmlspecialchars($h).'</Data></Cell>';
        }
        $xml .= '</Row>' . "\n";

        foreach ($this->categorizedLeaderboard as $category => $rankings) {
            foreach ($rankings as $index => $row) {
                $rank = $index + 1;
                $subId = $row['submission_id'];

                $xml .= '<Row>';
                if ($this->divisionMode === 'unified') {
                    $xml .= '<Cell><Data ss:Type="Number">'.$rank.'</Data></Cell>';
                    $xml .= '<Cell><Data ss:Type="String">'.htmlspecialchars($row['category']).'</Data></Cell>';
                } else {
                    $xml .= '<Cell><Data ss:Type="String">'.htmlspecialchars($category).'</Data></Cell>';
                    $xml .= '<Cell><Data ss:Type="Number">'.$rank.'</Data></Cell>';
                }

                $xml .= '<Cell><Data ss:Type="String">'.htmlspecialchars($row['team_name']).'</Data></Cell>';
                $xml .= '<Cell><Data ss:Type="String">'.htmlspecialchars($row['ticket_code']).'</Data></Cell>';

                $xml .= '<Cell><Data ss:Type="String">'.($this->rankingsMap[$subId]['global_overall'] ?? '-').'</Data></Cell>';
                $xml .= '<Cell><Data ss:Type="String">'.($this->rankingsMap[$subId]['division_overall'] ?? '-').'</Data></Cell>';
                $xml .= '<Cell><Data ss:Type="Number">'.$row['sort_score'].'</Data></Cell>';
                $xml .= '<Cell><Data ss:Type="String">'.strtoupper($row['status']).'</Data></Cell>';

                $xml .= '</Row>' . "\n";
            }
        }
        $xml .= '</Table></Worksheet>' . "\n";

        // --- TABS 2-N: INDIVIDUAL JUDGE SCORE TABS ---
        foreach ($this->availableJudges as $judge) {

            // Excel dictates sheet names must be <= 31 characters and cannot contain special symbols
            $cleanJudgeName = preg_replace('/[\[\]\*\/\?\:\\\\]/', '', $judge);
            $sheetName = substr(htmlspecialchars($cleanJudgeName), 0, 31);

            $xml .= '<Worksheet ss:Name="'.$sheetName.'"><Table>' . "\n";

            // Build Judge Sheet Headers
            $xml .= '<Row>';
            $jHeaders = ['Team Name', 'Ticket Code'];
            foreach ($this->groupCriteriaMap as $groupName => $criteriaList) {
                foreach ($criteriaList as $cId => $cName) {
                    $jHeaders[] = strtoupper($groupName) . ' [' . strtoupper($cName) . ']';
                }
                $jHeaders[] = strtoupper($groupName) . ' (SUBTOTAL)';
            }
            $jHeaders[] = 'JUDGE GRAND TOTAL';

            foreach ($jHeaders as $jh) {
                $xml .= '<Cell ss:StyleID="s1"><Data ss:Type="String">'.htmlspecialchars($jh).'</Data></Cell>';
            }
            $xml .= '</Row>' . "\n";

            // Populate Judge Sheet Rows
            foreach ($this->categorizedLeaderboard as $category => $rankings) {
                foreach ($rankings as $row) {
                    $xml .= '<Row>';
                    $xml .= '<Cell><Data ss:Type="String">'.htmlspecialchars($row['team_name']).'</Data></Cell>';
                    $xml .= '<Cell><Data ss:Type="String">'.htmlspecialchars($row['ticket_code']).'</Data></Cell>';

                    foreach ($this->groupCriteriaMap as $groupName => $criteriaList) {
                        foreach ($criteriaList as $cId => $cName) {
                            $val = $row['judge_criteria_scores'][$judge][$groupName][$cName] ?? '-';
                            $type = is_numeric($val) ? 'Number' : 'String';
                            $xml .= '<Cell><Data ss:Type="'.$type.'">'.$val.'</Data></Cell>';
                        }
                        $subTotal = $row['judge_group_scores'][$judge][$groupName] ?? '-';
                        $type = is_numeric($subTotal) ? 'Number' : 'String';
                        $xml .= '<Cell ss:StyleID="s1"><Data ss:Type="'.$type.'">'.$subTotal.'</Data></Cell>';
                    }

                    $grandTotal = $row['judge_totals'][$judge] ?? '-';
                    $type = is_numeric($grandTotal) ? 'Number' : 'String';
                    $xml .= '<Cell ss:StyleID="s1"><Data ss:Type="'.$type.'">'.$grandTotal.'</Data></Cell>';

                    $xml .= '</Row>' . "\n";
                }
            }

            $xml .= '</Table></Worksheet>' . "\n";
        }

        $xml .= '</Workbook>';

        // Output as Native Excel Document
        return response()->streamDownload(function () use ($xml) {
            echo $xml;
        }, $fileName, [
            'Content-Type' => 'application/vnd.ms-excel',
            'Content-Disposition' => 'attachment; filename="'.$fileName.'"'
        ]);
    }

    public function exportToWord()
    {
        $modeName = $this->divisionMode === 'unified' ? 'unified_global' : 'categorized';
        $fileName = 'official_report_master_' . $modeName . '_' . Str::slug($this->quest->title) . '_' . date('Y-m-d_H-i-s') . '.doc';
        $allData = collect($this->allSubmissionsData);

        $html = "<html><head><meta charset='utf-8'><title>{$this->quest->title} - Tabulation</title></head><body style='font-family: Arial, sans-serif;'>";

        // --- MAIN PAGE: ACTIVE UI FILTER LEADERBOARD ---
        $html .= "<h1>{$this->quest->title} - Active Filter Tabulation</h1>";
        $html .= "<p><strong>Leaderboard Mode:</strong> " . ($this->divisionMode === 'unified' ? 'Unified Global Ranking' : 'Categorized by Division') . "<br/>";
        $html .= "<strong>Group Filter:</strong> " . ($this->selectedGroup === 'all' ? 'All Matrices (Combined)' : $this->selectedGroup) . "<br/>";
        $html .= "<strong>Judge Filter:</strong> " . ($this->selectedJudge === 'all' ? 'All Assigned Judges' : $this->selectedJudge) . "<br/>";
        $html .= "<strong>Target Base:</strong> {$this->dynamicMaxScore} Pts</p><hr/>";

        foreach ($this->categorizedLeaderboard as $category => $rankings) {
            if ($this->divisionMode === 'unified') {
                $html .= "<h2>Global Unified Leaderboard (Filtered)</h2>";
            } else {
                $html .= "<h2>{$category} Division - Leaderboard (Filtered)</h2>";
            }

            $html .= "<table border='1' cellpadding='8' cellspacing='0' style='width: 100%; border-collapse: collapse; margin-bottom: 30px;'>";
            $html .= "<tr style='background-color: #f3f4f6;'>";
            $html .= "<th style='text-align: center; width: 10%;'>Rank</th>";
            $html .= "<th style='text-align: left; width: 40%;'>Cohort / Team</th>";
            if ($this->divisionMode === 'unified') {
                $html .= "<th style='text-align: left; width: 20%;'>Original Division</th>";
            }
            $html .= "<th style='text-align: left; width: 15%;'>Ticket Code</th>";
            $html .= "<th style='text-align: center; width: 15%;'>Score Metric</th>";
            $html .= "</tr>";

            foreach ($rankings as $index => $row) {
                $rank = $index + 1;
                $html .= "<tr>";
                $html .= "<td style='text-align: center; font-weight: bold;'>{$rank}</td>";
                $html .= "<td><strong>" . htmlspecialchars($row['team_name']) . "</strong></td>";
                if ($this->divisionMode === 'unified') {
                    $html .= "<td>" . htmlspecialchars($row['category']) . "</td>";
                }
                $html .= "<td>" . htmlspecialchars($row['ticket_code']) . "</td>";
                $html .= "<td style='text-align: center; font-size: 16px;'><strong>{$row['sort_score']}</strong></td>";
                $html .= "</tr>";
            }
            $html .= "</table>";
        }

        // --- APPENDIX A: CUMULATIVE OVERALL RANKINGS (UNFILTERED) ---
        $html .= "<h1 style='page-break-before: always;'>Appendix A: Cumulative Overall Ranks</h1>";
        $html .= "<p>Unfiltered global and divisional rankings based on total cumulative scores across all matrices and all judges.</p><hr/>";

        $html .= "<h2 style='color: #4b5563;'>Global Overall Leaderboard</h2>";
        $html .= "<table border='1' cellpadding='6' cellspacing='0' style='width: 100%; border-collapse: collapse; margin-bottom: 25px; font-size: 12px;'>";
        $html .= "<tr style='background-color: #e5e7eb;'><th>Rank</th><th>Team</th><th>Division</th><th>Final Score</th></tr>";
        foreach ($allData->sortByDesc('final_score')->values() as $index => $row) {
            $html .= "<tr><td style='text-align: center;'>".($index + 1)."</td><td><strong>{$row['team_name']}</strong></td><td>{$row['category']}</td><td style='text-align: center;'>{$row['final_score']}</td></tr>";
        }
        $html .= "</table>";

        foreach ($allData->groupBy('category') as $cat => $catRows) {
            $html .= "<h3 style='color: #4b5563;'>Division Overall: {$cat}</h3>";
            $html .= "<table border='1' cellpadding='6' cellspacing='0' style='width: 100%; border-collapse: collapse; margin-bottom: 25px; font-size: 12px;'>";
            $html .= "<tr style='background-color: #e5e7eb;'><th>Rank</th><th>Team</th><th>Final Score</th></tr>";
            foreach ($catRows->sortByDesc('final_score')->values() as $index => $row) {
                $html .= "<tr><td style='text-align: center;'>".($index + 1)."</td><td><strong>{$row['team_name']}</strong></td><td style='text-align: center;'>{$row['final_score']}</td></tr>";
            }
            $html .= "</table>";
        }

        // --- APPENDIX B: MATRIX-SPECIFIC LEADERBOARDS ---
        $html .= "<h1 style='page-break-before: always;'>Appendix B: Matrix-Specific Ranks</h1>";
        $html .= "<p>Unfiltered rankings isolated purely by individual evaluation matrices.</p><hr/>";

        foreach ($this->availableGroups as $group) {
            $html .= "<h2 style='color: #0095AC; margin-top: 20px; border-bottom: 2px solid #0095AC; padding-bottom: 5px;'>MATRIX: " . htmlspecialchars($group) . "</h2>";

            $html .= "<h3>Global Ranking - {$group}</h3>";
            $html .= "<table border='1' cellpadding='6' cellspacing='0' style='width: 100%; border-collapse: collapse; margin-bottom: 15px; font-size: 12px;'>";
            $html .= "<tr style='background-color: #e0f2fe;'><th>Rank</th><th>Team</th><th>Division</th><th>Matrix Score</th></tr>";
            $globalMatrixRows = $allData->sortByDesc(function($item) use ($group) { return $item['group_averages'][$group]['average'] ?? 0; })->values();
            foreach ($globalMatrixRows as $index => $row) {
                $score = number_format($row['group_averages'][$group]['average'] ?? 0, 2);
                $html .= "<tr><td style='text-align: center;'>".($index + 1)."</td><td><strong>{$row['team_name']}</strong></td><td>{$row['category']}</td><td style='text-align: center;'>{$score}</td></tr>";
            }
            $html .= "</table>";

            foreach ($allData->groupBy('category') as $cat => $catRows) {
                $html .= "<h4 style='color: #4b5563;'>{$cat} Division - {$group}</h4>";
                $html .= "<table border='1' cellpadding='6' cellspacing='0' style='width: 100%; border-collapse: collapse; margin-bottom: 20px; font-size: 11px;'>";
                $html .= "<tr style='background-color: #f3f4f6;'><th>Rank</th><th>Team</th><th>Matrix Score</th></tr>";
                $divMatrixRows = $catRows->sortByDesc(function($item) use ($group) { return $item['group_averages'][$group]['average'] ?? 0; })->values();
                foreach ($divMatrixRows as $index => $row) {
                    $score = number_format($row['group_averages'][$group]['average'] ?? 0, 2);
                    $html .= "<tr><td style='text-align: center;'>".($index + 1)."</td><td><strong>{$row['team_name']}</strong></td><td style='text-align: center;'>{$score}</td></tr>";
                }
                $html .= "</table>";
            }
        }

        $html .= "</body></html>";

        return response()->streamDownload(function () use ($html) {
            echo $html;
        }, $fileName, [
            'Content-Type' => 'application/msword',
            'Content-Disposition' => 'attachment; filename="'.$fileName.'"'
        ]);
    }

    // --- NEW: JUDGE SIGNATURE SCORECARD ENGINE ---
    public function exportJudgeScorecardsToWord()
    {
        // 1. Determine which judges to export based on the UI dropdown
        $judgesToExport = $this->selectedJudge === 'all' ? $this->availableJudges : [$this->selectedJudge];

        if (empty($judgesToExport)) {
            session()->flash('error', 'No evaluations found for the selected judge.');
            return;
        }

        $judgeNameForFile = $this->selectedJudge === 'all' ? 'All_Judges' : Str::slug($this->selectedJudge);
        $fileName = 'official_scorecard_' . $judgeNameForFile . '_' . Str::slug($this->quest->title) . '_' . date('Y-m-d_H-i-s') . '.doc';

        $html = "<html><head><meta charset='utf-8'><title>Judge Scorecards</title></head><body style='font-family: Arial, sans-serif;'>";

        $allData = collect($this->allSubmissionsData);

        // 2. Loop through each judge and generate their personal page
        foreach ($judgesToExport as $index => $judge) {

            // Add a clean page break between judges (ignored on the very first loop)
            if ($index > 0) {
                $html .= "<div style='page-break-before: always;'></div>";
            }

            // Header Section
            $html .= "<div style='text-align: center; margin-bottom: 25px;'>";
            $html .= "<h2 style='margin-bottom: 5px; color: #111827;'>OFFICIAL EVALUATOR SCORECARD</h2>";
            $html .= "<h3 style='margin-top: 0; color: #4b5563;'>" . htmlspecialchars($this->quest->title) . "</h3>";
            $html .= "</div>";

            $html .= "<table style='width: 100%; margin-bottom: 10px; font-size: 14px;'>";
            $html .= "<tr>";
            $html .= "<td style='width: 60%;'><strong>Evaluator Name:</strong> " . htmlspecialchars($judge) . "</td>";
            $html .= "<td style='width: 40%; text-align: right;'><strong>Date Generated:</strong> " . date('F d, Y') . "</td>";
            $html .= "</tr>";
            $html .= "</table><hr style='border: 1px solid #000; margin-bottom: 20px;'/>";

            // Data Section: Loop through Categories and Groups
            foreach ($allData->groupBy('category') as $category => $teams) {
                $html .= "<h3 style='background-color: #f3f4f6; padding: 8px; border: 1px solid #d1d5db;'>Division: {$category}</h3>";

                foreach ($this->availableGroups as $group) {
                    $criteriaList = $this->groupCriteriaMap[$group] ?? [];
                    if(empty($criteriaList)) continue;

                    $html .= "<h4 style='margin-bottom: 5px; margin-top: 15px; color: #000;'>Matrix: {$group}</h4>";
                    $html .= "<table border='1' cellpadding='6' cellspacing='0' style='width: 100%; border-collapse: collapse; margin-bottom: 20px; font-size: 12px;'>";

                    // Table Header
                    $html .= "<tr style='background-color: #e5e7eb;'>";
                    $html .= "<th style='text-align: left; width: 25%;'>Cohort / Team</th>";
                    foreach ($criteriaList as $cId => $cName) {
                        $html .= "<th style='text-align: center;'>" . htmlspecialchars($cName) . "</th>";
                    }
                    $html .= "<th style='text-align: center; background-color: #d1d5db; width: 15%;'>Subtotal</th>";
                    $html .= "</tr>";

                    // Table Rows
                    foreach ($teams->sortBy('team_name') as $row) {
                        $html .= "<tr>";
                        $html .= "<td><strong>" . htmlspecialchars($row['team_name']) . "</strong></td>";

                        foreach ($criteriaList as $cId => $cName) {
                            $score = $row['judge_criteria_scores'][$judge][$group][$cName] ?? '-';
                            $html .= "<td style='text-align: center;'>{$score}</td>";
                        }

                        $subtotal = $row['judge_group_scores'][$judge][$group] ?? '-';
                        $html .= "<td style='text-align: center; font-weight: bold;'>{$subtotal}</td>";
                        $html .= "</tr>";
                    }
                    $html .= "</table>";
                }
            }

            // Signature Block (Positioned cleanly at the bottom)
            $html .= "<br/><br/><br/>";
            $html .= "<table style='width: 100%; margin-top: 40px;'>";
            $html .= "<tr>";
            $html .= "<td style='width: 40%; text-align: center; border-top: 1px solid #000; padding-top: 8px;'>";
            $html .= "<strong>" . htmlspecialchars($judge) . "</strong><br/>Signature of Evaluator";
            $html .= "</td>";
            $html .= "<td style='width: 20%;'></td>";
            $html .= "<td style='width: 40%; text-align: center; border-top: 1px solid #000; padding-top: 8px;'>";
            $html .= "Date Signed";
            $html .= "</td>";
            $html .= "</tr>";
            $html .= "</table>";
        }

        $html .= "</body></html>";

        return response()->streamDownload(function () use ($html) {
            echo $html;
        }, $fileName, [
            'Content-Type' => 'application/msword',
            'Content-Disposition' => 'attachment; filename="'.$fileName.'"'
        ]);
    }

    public function render()
    {
        return view('livewire.ibalong.admin.quest-results-board')->layout('layouts.dashboard');
    }
}
