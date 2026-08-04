<div class="max-w-7xl mx-auto space-y-8 pb-24">

    {{-- Header Section --}}
    <div class="bg-iba-black border-4 border-iba-black shadow-[8px_8px_0_0_#FF8623] p-6 flex flex-col md:flex-row justify-between items-start md:items-center gap-4 text-white">
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
                            <th class="px-6 py-4 text-center text-xs font-black uppercase tracking-widest text-iba-black">Final Average</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y-2 divide-gray-200 bg-white">
                        @forelse($rankings as $index => $row)
                            @php
                                $rank = $index + 1;
                                $rankColor = match($rank) {
                                    1 => 'bg-iba-orange text-iba-black border-iba-black',
                                    2 => 'bg-gray-300 text-iba-black border-iba-black',
                                    3 => 'bg-[#CD7F32] text-white border-iba-black', // Bronze
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
                                                <div class="border border-iba-black bg-white p-2 flex flex-col min-w-[120px] shadow-[2px_2px_0_0_#131011]">
                                                    <span class="text-[9px] font-black uppercase text-gray-500 truncate">{{ $judgeName }}</span>
                                                    <span class="text-sm font-black text-iba-teal">{{ $total }} <span class="text-[9px] text-gray-400">/ {{ $maxPossibleScore }}</span></span>
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <span class="text-xs font-bold text-gray-400 italic">No evaluations submitted yet.</span>
                                    @endif
                                </td>

                                {{-- Final Average --}}
                                <td class="px-6 py-4 text-center">
                                    @if($row['total_judges'] > 0)
                                        <div class="text-xl font-black text-iba-black">{{ number_format($row['average_score'], 2) }}</div>
                                        <div class="text-[9px] font-bold text-gray-500 uppercase mt-1">Based on {{ $row['total_judges'] }} Judge(s)</div>
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
