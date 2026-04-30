<style>
    /* Bulletproof Dynamic CSS Variables for Admin Panel */
    .dyn-card, .dyn-avatar, .dyn-text, .dyn-score { transition: all 0.3s ease; }

    .dyn-card.is-winner { border-color: var(--party-color) !important; background-color: var(--party-bg) !important; }
    .dyn-avatar.is-winner { border-color: var(--party-color) !important; }
    .dyn-text.is-winner { color: var(--party-color) !important; }
    .dyn-score.is-winner { color: var(--party-color) !important; }
</style>

<div class="max-w-7xl mx-auto py-8 px-4 font-sans pb-32">

    @php
        // Determine if this specific election uses a Party/Slate system
        $hasParties = $election->parties && $election->parties->count() > 0;
    @endphp

    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div>
            <h1 class="text-3xl font-black text-gray-900 tracking-tight flex items-center gap-3">
                <a href="{{ route('admin.elections.index') }}" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                </a>
                Live Analytics & Results
            </h1>
            <p class="text-gray-500 font-medium ml-9">Monitoring votes for: <span class="font-bold">{{ $election->title }}</span></p>
        </div>

        <div class="flex items-center gap-3">
            <a href="{{ route('elections.public-results', $election->slug) }}" target="_blank" class="px-5 py-2.5 bg-orange-100 text-orange-700 font-bold rounded-xl hover:bg-orange-200 transition-colors flex items-center gap-2 text-sm border border-orange-200">
                View Public Portal <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
            </a>
        </div>
    </div>

    <div wire:poll.5s class="space-y-8 animate-fade-in-up">

        <div class="bg-gray-900 text-white rounded-[2rem] p-8 shadow-xl flex flex-col md:flex-row items-center justify-between gap-6 relative overflow-hidden">
            <div class="absolute inset-0 opacity-10 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')]"></div>

            <div class="relative z-10 flex items-center gap-4">
                <div class="w-14 h-14 rounded-full bg-white/10 flex items-center justify-center backdrop-blur-sm border border-white/20">
                    <span class="w-3 h-3 rounded-full bg-green-400 animate-ping absolute"></span>
                    <span class="w-3 h-3 rounded-full bg-green-400 relative"></span>
                </div>
                <div>
                    <h2 class="text-xs uppercase font-black tracking-widest text-gray-400 mb-1">Official Voter Turnout</h2>
                    <p class="text-4xl font-black leading-none">{{ number_format($totalTurnout) }} <span class="text-lg text-gray-400 font-bold">Ballots Cast</span></p>
                </div>
            </div>

            <div class="relative z-10 bg-white/10 backdrop-blur-sm border border-white/10 px-6 py-3 rounded-2xl text-center">
                <p class="text-[10px] uppercase font-bold tracking-widest text-gray-300">Status</p>
                <p class="text-sm font-black {{ now() > $election->voting_end ? 'text-red-400' : 'text-green-400' }}">
                    {{ now() > $election->voting_end ? 'POLLS CLOSED' : 'VOTING ACTIVE' }}
                </p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            @foreach($positions as $position)
                @php
                    $totalPositionVotes = $position->candidates->sum('votes_count');
                    $maxPossibleVotes = $totalTurnout * $position->max_winners;
                    $abstainVotes = max(0, $maxPossibleVotes - $totalPositionVotes);
                @endphp

                <div class="bg-white rounded-[2rem] p-6 md:p-8 shadow-sm border border-gray-200 flex flex-col">

                    <div class="flex items-center justify-between mb-6 pb-4 border-b border-gray-100">
                        <div>
                            <h3 class="text-xl font-black text-gray-900 leading-tight">{{ $position->title }}</h3>
                            <p class="text-[10px] font-bold text-gray-500 uppercase tracking-wider mt-1">Electing {{ $position->max_winners }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Valid Votes</p>
                            <p class="text-lg font-black text-blue-600 leading-none mt-0.5">{{ number_format($totalPositionVotes) }}</p>
                        </div>
                    </div>

                    <div class="space-y-3 flex-1">
                        @forelse($position->candidates as $index => $candidate)
                            @php
                                $percentage = $maxPossibleVotes > 0 ? ($candidate->votes_count / $maxPossibleVotes) * 100 : 0;
                                $isWinner = $index < $position->max_winners && $candidate->votes_count > 0;
                                $candidateName = $candidate->display_name ?? optional($candidate->user)->name ?? 'Unknown Candidate';

                                // DYNAMIC PARTY LOGIC
                                if ($hasParties) {
                                    $partyName = optional($candidate->party)->name ?? 'Independent';
                                    $partyColor = optional($candidate->party)->color ?? '#16a34a'; // Default to Tailwind Green-600 for winners without parties
                                } else {
                                    $partyName = null;
                                    $partyColor = '#16a34a'; // Default Green
                                }
                                $partyBg = $partyColor . '1A'; // 10% Opacity Background
                            @endphp

                            <div style="--party-color: {{ $partyColor }}; --party-bg: {{ $partyBg }};"
                                 class="dyn-card relative bg-gray-50 rounded-2xl p-4 border overflow-hidden transition-all duration-300 {{ $isWinner ? 'is-winner shadow-sm transform -translate-y-0.5' : 'border-gray-100' }}">

                                {{-- Progress Bar --}}
                                <div class="absolute top-0 left-0 bottom-0 opacity-15 transition-all duration-1000 ease-out"
                                     style="width: {{ $percentage }}%; background-color: var(--party-color);"></div>

                                <div class="relative z-10 flex items-center gap-4">
                                    {{-- Avatar --}}
                                    <div class="dyn-avatar w-10 h-10 rounded-full overflow-hidden border-2 shadow-sm shrink-0 bg-white {{ $isWinner ? 'is-winner' : 'border-white' }}">
                                        @if($candidate->profile_photo_path)
                                            <img src="{{ asset('storage/'.$candidate->profile_photo_path) }}" class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full bg-gray-100 flex items-center justify-center font-black text-[color:var(--party-color)] text-sm">{{ substr($candidateName, 0, 1) }}</div>
                                        @endif
                                    </div>

                                    {{-- Info --}}
                                    <div class="flex-1 min-w-0">
                                        <p class="dyn-text font-bold text-sm truncate flex items-center gap-1 {{ $isWinner ? 'is-winner' : 'text-gray-900' }}">
                                            {{ $candidateName }}
                                            @if($isWinner) <svg class="w-4 h-4 text-[color:var(--party-color)] shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg> @endif
                                        </p>

                                        @if($hasParties)
                                            <p style="color: var(--party-color);" class="text-[9px] font-black uppercase tracking-widest mt-0.5 truncate">{{ $partyName }}</p>
                                        @endif

                                        <p class="text-[9px] md:text-[10px] font-bold text-gray-500 uppercase {{ $hasParties ? 'mt-0' : 'mt-0.5' }} truncate">
                                            {{ $candidate->college->name ?? 'N/A' }} • {{ $candidate->program }}
                                        </p>
                                    </div>

                                    {{-- Stats --}}
                                    <div class="text-right shrink-0">
                                        <div class="dyn-score font-black text-lg leading-none {{ $isWinner ? 'is-winner' : 'text-gray-700' }}">{{ number_format($candidate->votes_count) }}</div>
                                        <div class="text-[9px] font-bold text-gray-400 uppercase mt-0.5">{{ number_format($percentage, 1) }}%</div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-6 border border-dashed border-gray-200 rounded-2xl bg-gray-50"><p class="text-xs font-bold text-gray-400">No candidates on ballot.</p></div>
                        @endforelse
                    </div>

                    @php
                        $abstainPercentage = $maxPossibleVotes > 0 ? ($abstainVotes / $maxPossibleVotes) * 100 : 0;
                        $abstainLabel = $position->max_winners > 1 ? 'Abstain & Undervotes' : 'Abstentions';
                    @endphp

                    <div class="mt-4 relative bg-gray-50 rounded-2xl p-4 border border-dashed border-gray-300 overflow-hidden">
                        <div class="absolute top-0 left-0 bottom-0 bg-gray-200/50 transition-all duration-1000 ease-out" style="width: {{ $abstainPercentage }}%;"></div>
                        <div class="relative z-10 flex items-center justify-between gap-4">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-gray-200 text-gray-500 flex items-center justify-center shrink-0">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                </div>
                                <div class="min-w-0">
                                    <p class="font-bold text-gray-600 text-sm truncate">{{ $abstainLabel }}</p>
                                    <p class="text-[9px] font-bold text-gray-400 uppercase mt-0.5 truncate">Unused Ballot Slots</p>
                                </div>
                            </div>
                            <div class="text-right shrink-0">
                                <div class="font-black text-lg text-gray-500 leading-none">{{ number_format($abstainVotes) }}</div>
                                <div class="text-[9px] font-bold text-gray-400 uppercase mt-0.5">{{ number_format($abstainPercentage, 1) }}%</div>
                            </div>
                        </div>
                    </div>

                </div>
            @endforeach
        </div>
    </div>
</div>
