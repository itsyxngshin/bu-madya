<div class="max-w-7xl mx-auto space-y-8 pb-24">

    {{-- Header Section --}}
    <div class="bg-iba-black border-4 border-iba-black shadow-[8px_8px_0_0_#FF8623] p-6 flex flex-col gap-4 text-white">

        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <div class="flex items-center gap-3 mb-2">
                    <h1 class="text-2xl font-black uppercase tracking-widest text-white">Official Tabulation Board</h1>
                    <span class="bg-iba-orange text-iba-black text-[10px] font-black uppercase px-2 py-1">Target Base: {{ $dynamicMaxScore }} Pts</span>
                </div>
                <p class="text-sm font-bold text-gray-400 uppercase tracking-widest">{{ $quest->title }}</p>
            </div>

            <a href="{{ route('ibalong.admin.quests.index') }}" class="bg-transparent text-white text-xs font-black uppercase px-6 py-3 border-2 border-white hover:bg-white hover:text-iba-black transition-colors">
                &larr; Return to Roster
            </a>
        </div>

        {{-- Dynamic Dual Filters --}}
        <div class="pt-4 border-t-2 border-dashed border-gray-600 flex flex-col sm:flex-row sm:items-center gap-3">
            <div class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto">
                {{-- Group Filter --}}
                <select wire:model.live="selectedGroup" class="border-2 border-white bg-iba-black text-white p-2 text-xs font-black uppercase cursor-pointer hover:bg-white hover:text-iba-black focus:outline-none transition-colors w-full sm:w-auto">
                    <option value="all" class="text-iba-black">All Matrices (Combined)</option>
                    @foreach($availableGroups as $group)
                        <option value="{{ $group }}" class="text-iba-black">{{ $group }}</option>
                    @endforeach
                </select>

                {{-- Judge Filter --}}
                <select wire:model.live="selectedJudge" class="border-2 border-white bg-iba-black text-white p-2 text-xs font-black uppercase cursor-pointer hover:bg-white hover:text-iba-black focus:outline-none transition-colors w-full sm:w-auto">
                    <option value="all" class="text-iba-black">All Assigned Judges</option>
                    @foreach($availableJudges as $judge)
                        <option value="{{ $judge }}" class="text-iba-black">{{ $judge }}'s Scorecard</option>
                    @endforeach
                </select>
            </div>

            <div wire:loading wire:target="selectedJudge, selectedGroup" class="text-[10px] font-black text-iba-orange uppercase animate-pulse">
                Recalculating Rankings...
            </div>
        </div>
    </div>

    {{-- Categorized Leaderboards --}}
    @forelse($categorizedLeaderboard as $category => $rankings)
        <div class="bg-white border-4 border-iba-black shadow-[8px_8px_0_0_#131011] overflow-hidden mb-8 animate-fade-in-up">

            {{-- Division Banner --}}
            <div class="bg-iba-black text-white px-6 py-4 flex items-center justify-between border-b-4 border-iba-black">
                <h2 class="text-lg font-black uppercase tracking-widest">{{ $category }} Division</h2>
                <span class="bg-white text-iba-black text-[10px] font-black uppercase px-3 py-1 border-2 border-transparent">{{ count($rankings) }} Cohorts</span>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y-4 divide-iba-black">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-6 py-4 text-center text-xs font-black uppercase tracking-widest text-iba-black w-24">Rank</th>
                            <th class="px-6 py-4 text-left text-xs font-black uppercase tracking-widest text-iba-black">Cohort / Team</th>
                            <th class="px-6 py-4 text-left text-xs font-black uppercase tracking-widest text-iba-black">Evaluation Breakdown</th>
                            <th class="px-6 py-4 text-center text-xs font-black uppercase tracking-widest text-iba-black">Rank Metric</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y-2 divide-gray-200 bg-white">
                        @forelse($rankings as $index => $row)
                            @php
                                $rank = $index + 1;
                                $rankColor = match($rank) {
                                    1 => 'bg-iba-orange text-iba-black border-iba-black',
                                    2 => 'bg-gray-300 text-iba-black border-iba-black',
                                    3 => 'bg-[#CD7F32] text-white border-iba-black',
                                    4 => 'bg-[#CD7F32] text-white border-iba-black',
                                    default => 'bg-gray-50 text-gray-600 border-gray-300'
                                };
                            @endphp
                            <tr class="hover:bg-orange-50 transition-colors {{ $rank === 1 ? 'bg-orange-50/30' : '' }}">

                                {{-- Rank --}}
                                <td class="px-6 py-4 text-center align-top">
                                    <div class="inline-flex items-center justify-center w-10 h-10 border-2 font-black text-lg shadow-[2px_2px_0_0_#131011] {{ $rankColor }}">
                                        {{ $rank }}
                                    </div>
                                </td>

                                {{-- Team Info --}}
                                <td class="px-6 py-4 align-top">
                                    <div class="text-sm font-black text-iba-black uppercase">{{ $row['team_name'] }}</div>
                                    <div class="text-[10px] text-gray-500 font-bold uppercase mt-1">ID: {{ $row['ticket_code'] }}</div>
                                </td>

                                {{-- Dynamic Contextual Breakdown --}}
                                <td class="px-6 py-4 align-top">
                                    @if($row['total_judges'] > 0)
                                        <div class="flex flex-col gap-1 min-w-[200px]">

                                            {{-- Scenario 1: ALL JUDGES & ALL GROUPS (Default Overview) --}}
                                            @if($selectedJudge === 'all' && $selectedGroup === 'all')
                                                @foreach($row['group_averages'] as $groupName => $data)
                                                    @if($data['average'] > 0)
                                                        <div class="flex justify-between items-center text-[10px] font-black uppercase border-b border-gray-200 pb-1 last:border-0">
                                                            <span class="text-gray-500 mr-3 truncate" title="{{ $groupName }}">{{ \Illuminate\Support\Str::limit($groupName, 20) }}:</span>
                                                            <span class="text-iba-teal shrink-0">{{ number_format($data['average'], 2) }} <span class="text-gray-400">/ {{ $data['max'] }}</span></span>
                                                        </div>
                                                    @endif
                                                @endforeach

                                            {{-- Scenario 2: SPECIFIC JUDGE & ALL GROUPS --}}
                                            @elseif($selectedJudge !== 'all' && $selectedGroup === 'all')
                                                @if(isset($row['judge_group_scores'][$selectedJudge]))
                                                    @foreach($row['judge_group_scores'][$selectedJudge] as $groupName => $score)
                                                        <div class="flex justify-between items-center text-[10px] font-black uppercase border-b border-gray-200 pb-1 last:border-0">
                                                            <span class="text-gray-500 mr-3 truncate" title="{{ $groupName }}">{{ \Illuminate\Support\Str::limit($groupName, 20) }}:</span>
                                                            <span class="text-iba-orange shrink-0">{{ number_format($score, 2) }} <span class="text-gray-400">Pts</span></span>
                                                        </div>
                                                    @endforeach
                                                @else
                                                    <span class="text-[10px] font-black text-gray-400 uppercase italic">Judge bypassed this cohort.</span>
                                                @endif

                                            {{-- Scenario 3: ALL JUDGES & SPECIFIC GROUP --}}
                                            @elseif($selectedJudge === 'all' && $selectedGroup !== 'all')
                                                @php $hasJudgesForGroup = false; @endphp
                                                @foreach($row['judge_group_scores'] as $judgeName => $groupScores)
                                                    @if(isset($groupScores[$selectedGroup]))
                                                        @php $hasJudgesForGroup = true; @endphp
                                                        <div class="flex justify-between items-center text-[10px] font-black uppercase border-b border-gray-200 pb-1 last:border-0">
                                                            <span class="text-gray-500 mr-3 truncate" title="{{ $judgeName }}">{{ \Illuminate\Support\Str::limit($judgeName, 20) }}:</span>
                                                            <span class="text-iba-teal shrink-0">{{ number_format($groupScores[$selectedGroup], 2) }} <span class="text-gray-400">/ {{ $dynamicMaxScore }}</span></span>
                                                        </div>
                                                    @endif
                                                @endforeach
                                                @if(!$hasJudgesForGroup)
                                                     <span class="text-[10px] font-black text-gray-400 uppercase italic">No assessments logged for this matrix.</span>
                                                @endif

                                            {{-- Scenario 4: SPECIFIC JUDGE & SPECIFIC GROUP --}}
                                            @elseif($selectedJudge !== 'all' && $selectedGroup !== 'all')
                                                @if(isset($row['judge_group_scores'][$selectedJudge][$selectedGroup]))
                                                    <div class="border bg-iba-teal text-white border-iba-black p-2 flex flex-col min-w-[120px] transition-all max-w-[200px]">
                                                        <span class="text-[9px] font-black uppercase text-white truncate" title="{{ $selectedJudge }}">{{ $selectedJudge }}</span>
                                                        <span class="text-sm font-black">{{ number_format($row['judge_group_scores'][$selectedJudge][$selectedGroup], 2) }} <span class="text-[9px] text-teal-100">/ {{ $dynamicMaxScore }} Pts</span></span>
                                                    </div>
                                                @else
                                                    <span class="text-[10px] font-black text-gray-400 uppercase italic">Judge bypassed this matrix.</span>
                                                @endif

                                            @endif

                                        </div>
                                    @else
                                        <span class="text-xs font-bold text-gray-400 italic">No evaluations submitted yet.</span>
                                    @endif
                                </td>

                                {{-- Rank Metric Display --}}
                                <td class="px-6 py-4 text-center align-top">
                                    @if($row['total_judges'] > 0)
                                        <div class="text-xl font-black text-iba-black">{{ number_format($row['sort_score'], 2) }}</div>
                                        <div class="text-[9px] font-bold text-gray-500 uppercase mt-1">
                                            @if($selectedJudge === 'all' && $selectedGroup === 'all') Final Combined
                                            @elseif($selectedJudge === 'all' && $selectedGroup !== 'all') Group Average
                                            @elseif($selectedJudge !== 'all' && $selectedGroup === 'all') Judge Total
                                            @else Judge Matrix Score @endif
                                        </div>
                                    @else
                                        <span class="text-xs font-black text-iba-red uppercase border-b-2 border-iba-red">Pending</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center border-t-4 border-dashed border-gray-300 bg-gray-50">
                                    <p class="text-sm font-black text-gray-500 uppercase tracking-widest">No data available for this division.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @empty
        <div class="bg-gray-50 border-4 border-dashed border-iba-black p-12 text-center">
            <p class="text-sm font-black text-gray-500 uppercase tracking-widest">No evaluated cohorts are available for ranking at this time.</p>
        </div>
    @endforelse
</div>
