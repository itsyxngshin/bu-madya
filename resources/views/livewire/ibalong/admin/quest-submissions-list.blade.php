<div class="max-w-7xl mx-auto space-y-6 pb-24">

    {{-- Header Section --}}
    <div class="bg-white border-4 border-iba-black shadow-[8px_8px_0_0_#0095AC] p-6 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <div class="flex items-center gap-3 mb-2">
                <h1 class="text-2xl font-black uppercase tracking-widest text-iba-black">Submission Manifest</h1>
                <span class="bg-iba-black text-white text-[10px] font-black uppercase px-2 py-1">{{ $submissions->count() }} Records</span>
            </div>
            <p class="text-sm font-bold text-iba-teal uppercase tracking-widest">{{ $quest->title }}</p>
        </div>

        <a href="{{ route('ibalong.admin.quests.index') }}" class="bg-gray-100 text-iba-black text-xs font-black uppercase px-6 py-3 border-2 border-iba-black shadow-[3px_3px_0_0_#131011] hover:translate-y-0.5 hover:shadow-none transition-all">
            &larr; Return to Roster
        </a>
    </div>

    {{-- Submissions Data Table --}}
    <div class="bg-white border-4 border-iba-black shadow-[8px_8px_0_0_#131011] overflow-x-auto">
        <table class="min-w-full divide-y-4 divide-iba-black">
            <thead class="bg-iba-black text-white">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-black uppercase tracking-widest">Cohort / Team</th>
                    <th class="px-6 py-4 text-center text-xs font-black uppercase tracking-widest">Status</th>
                    <th class="px-6 py-4 text-center text-xs font-black uppercase tracking-widest">Timestamp</th>
                    <th class="px-6 py-4 text-center text-xs font-black uppercase tracking-widest">Council Grading</th>
                    <th class="px-6 py-4 text-right text-xs font-black uppercase tracking-widest">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y-2 divide-gray-200 bg-white">
                @php
                    $maxPossibleScore = $quest->criteria->sum('max_score');
                @endphp

                @forelse($submissions as $sub)
                    <tr class="hover:bg-gray-50 transition-colors">

                        {{-- Team Name --}}
                        <td class="px-6 py-4">
                            <div class="text-sm font-black text-iba-black uppercase">{{ $sub->team->team_name ?? 'Unknown Cohort' }}</div>
                            <div class="text-[10px] text-gray-500 font-bold uppercase mt-1">ID: {{ $sub->team->ticket_code ?? 'N/A' }}</div>
                        </td>

                        {{-- Submission Status --}}
                        <td class="px-6 py-4 text-center">
                            @php
                                $statusColors = [
                                    'draft' => 'border-gray-400 text-gray-500 bg-gray-100',
                                    'submitted' => 'border-iba-orange text-iba-orange bg-orange-50',
                                    'reviewing' => 'border-iba-teal text-iba-teal bg-teal-50',
                                    'reviewed' => 'border-iba-green text-iba-green bg-green-50',
                                ];
                                $colorClass = $statusColors[$sub->status] ?? $statusColors['draft'];
                            @endphp
                            <span class="px-3 py-1 text-[10px] font-black uppercase border-2 {{ $colorClass }}">
                                {{ $sub->status }}
                            </span>
                        </td>

                        {{-- Timestamp --}}
                        <td class="px-6 py-4 text-center">
                            @if($sub->submitted_at)
                                <div class="text-xs font-bold text-gray-800 uppercase">{{ $sub->submitted_at->format('M d, Y') }}</div>
                                <div class="text-[10px] font-bold text-gray-500 uppercase mt-1">{{ $sub->submitted_at->format('h:i A') }}</div>
                            @else
                                <span class="text-xs font-bold text-gray-400 italic">Not transmitted</span>
                            @endif
                        </td>

                        {{-- Grading Progress / Total Score --}}
                        <td class="px-6 py-4 text-center">
                            @if($sub->status === 'draft')
                                <span class="text-xs font-bold text-gray-400">-</span>
                            @else
                                @php
                                    // Calculate total score given by the current logged-in judge
                                    $myScore = $sub->scores->where('judge_id', auth('ibalong')->id())->sum('score');
                                    $hasGraded = $sub->scores->where('judge_id', auth('ibalong')->id())->count() > 0;
                                @endphp

                                @if($hasGraded)
                                    <div class="text-sm font-black text-iba-teal">{{ $myScore }} <span class="text-[10px] text-gray-500">/ {{ $maxPossibleScore }}</span></div>
                                    <div class="text-[9px] font-bold text-gray-500 uppercase mt-1">Your Eval Complete</div>
                                @else
                                    <span class="text-[10px] font-black text-iba-red uppercase border-b-2 border-iba-red">Awaiting Eval</span>
                                @endif
                            @endif
                        </td>

                        {{-- Action Button --}}
                        <td class="px-6 py-4 text-right">
                            <div class="flex flex-col items-end gap-1.5">
                                @if($sub->status === 'draft')
                                    <button disabled class="bg-gray-200 text-gray-400 text-[10px] font-black uppercase px-4 py-2 border-2 border-gray-300 cursor-not-allowed w-full max-w-[130px]">
                                        Unavailable
                                    </button>
                                @else
                                    <a href="{{ route('ibalong.admin.quests.weighing', $sub->id) }}" class="inline-block bg-iba-orange text-iba-black text-[10px] font-black uppercase px-4 py-2 border-2 border-iba-black shadow-[2px_2px_0_0_#131011] hover:translate-y-0.5 hover:shadow-none transition-all w-full max-w-[130px] text-center">
                                        Weigh Gift
                                    </a>
                                @endif

                                @if(in_array(auth('ibalong')->user()->role_id, [1, 2]))
                                    <a href="{{ route('ibalong.admin.quests.override', $sub->id) }}" class="inline-block bg-iba-red text-white text-[10px] font-black uppercase px-4 py-1.5 border-2 border-iba-black hover:bg-red-800 transition-colors w-full max-w-[130px] text-center">
                                        Override
                                    </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center border-t-4 border-dashed border-gray-300 bg-gray-50">
                            <p class="text-sm font-black text-gray-500 uppercase tracking-widest">No cohorts have initiated this quest yet.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
