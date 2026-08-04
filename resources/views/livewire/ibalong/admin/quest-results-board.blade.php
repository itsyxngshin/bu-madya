<div class="max-w-7xl mx-auto space-y-8 pb-24">

    {{-- Header Section --}}
    <div class="bg-iba-black border-4 border-iba-black shadow-[8px_8px_0_0_#FF8623] p-6 flex flex-col gap-4 text-white">

        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <div class="flex items-center gap-3 mb-2">
                    <h1 class="text-2xl font-black uppercase tracking-widest text-white">Official Tabulation Board</h1>
                    <span class="bg-iba-orange text-iba-black text-[10px] font-black uppercase px-2 py-1">Max Score: {{ $maxPossibleScore }}</span>
                </div>
                <p class="text-sm font-bold text-gray-400 uppercase tracking-widest">{{ $quest->title }}</p>
            </div>

            <a href="{{ route('ibalong.admin.quests.index') }}" class="bg-transparent text-white text-xs font-black uppercase px-6 py-3 border-2 border-white hover:bg-white hover:text-iba-black transition-colors">
                &larr; Return to Roster
            </a>
        </div>

        {{-- Dynamic Judge Filter --}}
        <div class="pt-4 border-t-2 border-dashed border-gray-600 flex flex-col sm:flex-row sm:items-center gap-3">
            <label class="text-xs font-black uppercase text-gray-400 tracking-widest">Tabulation Filter:</label>
            <select wire:model.live="selectedJudge" class="border-2 border-white bg-iba-black text-white p-2 text-xs font-black uppercase cursor-pointer hover:bg-white hover:text-iba-black focus:outline-none transition-colors w-full max-w-xs">
                <option value="all" class="text-iba-black">Overall Final Average</option>
                @foreach($availableJudges as $judge)
                    <option value="{{ $judge }}" class="text-iba-black">{{ $judge }}'s Scorecard</option>
                @endforeach
            </select>

            <div wire:loading wire:target="selectedJudge" class="text-[10px] font-black text-iba-orange uppercase animate-pulse">
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
                            <th class="px-6 py-4 text-left text-xs font-black uppercase tracking-widest text-iba-black">Council Evaluations</th>
                            <th class="px-6 py-4 text-center text-xs font-black uppercase tracking-widest text-iba-black">
                                {{ $selectedJudge === 'all' ? 'Final Average' : 'Judge Score' }}
                            </th>
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
                                <td class="px-6 py-4 text-center">
                                    <div class="inline-flex items-center justify-center w-10 h-10 border-2 font-black text-lg shadow-[2px_2px_0_0_#131011] {{ $rankColor }}">
                                        {{ $rank }}
                                    </div>
                                </td>

                                {{-- Team Info --}}
                                <td class="px-6 py-4">
                                    <div class="text-sm font-black text-iba-black uppercase">{{ $row['team_name'] }}</div>
                                    <div class="text-[10px] text-gray-500 font-bold uppercase mt-1">ID: {{ $row['ticket_code'] }}</div>
                                </td>

                                {{-- Breakdown of Judges' Scores --}}
                                <td class="px-6 py-4">
                                    @if($row['total_judges'] > 0)
                                        <div class="flex flex-wrap gap-2">
                                            @foreach($row['judge_totals'] as $judgeName => $total)
                                                {{-- Highlight the selected judge's block if filtering --}}
                                                @php
                                                    $isTargetJudge = $selectedJudge === $judgeName;
                                                    $blockStyle = $isTargetJudge ? 'bg-iba-teal text-white border-iba-black shadow-[2px_2px_0_0_#131011]' : 'bg-white border-iba-black text-iba-black shadow-[2px_2px_0_0_#131011] opacity-60';
                                                    if ($selectedJudge === 'all') $blockStyle = 'bg-white border-iba-black text-iba-black shadow-[2px_2px_0_0_#131011]';
                                                @endphp
                                                <div class="border {{ $blockStyle }} p-2 flex flex-col min-w-[120px] transition-all">
                                                    <span class="text-[9px] font-black uppercase {{ $isTargetJudge ? 'text-white' : 'text-gray-500' }} truncate">{{ $judgeName }}</span>
                                                    <span class="text-sm font-black">{{ $total }} <span class="text-[9px] {{ $isTargetJudge ? 'text-teal-100' : 'text-gray-400' }}">/ {{ $maxPossibleScore }}</span></span>
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <span class="text-xs font-bold text-gray-400 italic">No evaluations submitted yet.</span>
                                    @endif
                                </td>

                                {{-- Dynamic Metric Display --}}
                                <td class="px-6 py-4 text-center">
                                    @if($selectedJudge === 'all')
                                        @if($row['total_judges'] > 0)
                                            <div class="text-xl font-black text-iba-black">{{ number_format($row['average_score'], 2) }}</div>
                                            <div class="text-[9px] font-bold text-gray-500 uppercase mt-1">Based on {{ $row['total_judges'] }} Judge(s)</div>
                                        @else
                                            <span class="text-xs font-black text-iba-red uppercase border-b-2 border-iba-red">Pending</span>
                                        @endif
                                    @else
                                        @if(isset($row['judge_totals'][$selectedJudge]))
                                            <div class="text-xl font-black text-iba-teal">{{ number_format($row['judge_totals'][$selectedJudge], 2) }}</div>
                                            <div class="text-[9px] font-bold text-gray-500 uppercase mt-1">Granted Score</div>
                                        @else
                                            <span class="text-[10px] font-black text-gray-400 uppercase italic">Not Assessed</span>
                                        @endif
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
