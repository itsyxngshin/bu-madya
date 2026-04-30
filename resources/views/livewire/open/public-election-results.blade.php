<style>
    .hover-glow-tricolor { transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
    .hover-glow-tricolor:hover { z-index: 10; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 0 20px -2px rgba(251, 191, 36, 0.5), -12px 0 20px -5px rgba(239, 68, 68, 0.4), 12px 0 20px -5px rgba(34, 197, 94, 0.4); }
</style>

<div class="max-w-5xl mx-auto py-8 md:py-12 px-4 sm:px-6 font-sans pb-32">

    @php
        // Determine if this specific election uses a Party/Slate system
        $hasParties = $election->parties && $election->parties->count() > 0;
    @endphp

    {{-- ELECTION HEADER --}}
    <div class="bg-white rounded-[2.5rem] shadow-lg border border-gray-200 overflow-hidden mb-8">
    
    @if($election->cover_photo_path)
        <img src="{{ asset('storage/'.$election->cover_photo_path) }}" class="w-full h-auto block">
        @else
            <div class="w-full h-32 md:h-48 bg-gradient-to-r from-red-600 via-yellow-400 to-green-600 relative overflow-hidden">
                <div class="absolute inset-0 opacity-20 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')]"></div>
                <div class="absolute -bottom-16 left-1/2 -translate-x-1/2 w-96 h-96 bg-white/20 rounded-full blur-3xl pointer-events-none"></div>
            </div>
        @endif
        
        <div class="p-6 md:p-10 text-center relative">
            <p class="text-[10px] font-black text-gray-500 uppercase tracking-[0.3em] mb-2">Official Electoral Return</p>
            <h1 class="text-3xl md:text-5xl font-black text-gray-900 tracking-tighter leading-tight mb-4 break-words">{{ $election->title }}</h1>
            
            @if($election->description)
                <p class="text-sm md:text-base text-gray-600 font-medium leading-relaxed mb-6 max-w-3xl mx-auto">{{ $election->description }}</p>
            @endif
            
            <div wire:poll.10s class="inline-flex items-center gap-2 bg-gray-50 border border-gray-200 px-4 py-2 rounded-xl shadow-sm">
                <div class="w-2 h-2 rounded-full {{ $isReleased ? 'bg-green-500' : 'bg-orange-500 animate-pulse' }}"></div>
                <span class="text-[10px] md:text-xs font-bold text-gray-700 uppercase tracking-wider">Total Turnout: <span class="text-gray-900 font-black">{{ number_format($totalTurnout ?? 0) }} Voters</span></span>
            </div>
        </div>
    </div>

    {{-- STATE 1: PRE-RELEASE (COUNTDOWN & CANDIDATE ROSTER) --}}
    @if(!$isReleased)
        <div class="bg-gray-900 text-white rounded-[2rem] p-8 md:p-10 text-center mb-12 shadow-2xl relative overflow-hidden isolate" x-data="countdownTimer('{{ $election->results_release ? $election->results_release->format('Y-m-d\TH:i:s') : null }}')">
            <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-orange-400 via-orange-500 to-orange-600"></div>
            <h2 class="text-[10px] md:text-sm font-bold text-orange-400 uppercase tracking-widest mb-4 md:mb-6">Official Results Drop In</h2>

            <template x-if="targetDate">
                <div class="flex justify-center gap-3 sm:gap-6 font-mono">
                    <div class="flex flex-col"><span class="text-4xl sm:text-6xl font-black" x-text="days"></span><span class="text-[9px] md:text-[10px] uppercase text-gray-400 mt-1">Days</span></div><div class="text-4xl sm:text-6xl font-black text-gray-600">:</div>
                    <div class="flex flex-col"><span class="text-4xl sm:text-6xl font-black" x-text="hours"></span><span class="text-[9px] md:text-[10px] uppercase text-gray-400 mt-1">Hours</span></div><div class="text-4xl sm:text-6xl font-black text-gray-600">:</div>
                    <div class="flex flex-col"><span class="text-4xl sm:text-6xl font-black" x-text="minutes"></span><span class="text-[9px] md:text-[10px] uppercase text-gray-400 mt-1">Mins</span></div><div class="text-4xl sm:text-6xl font-black text-gray-600">:</div>
                    <div class="flex flex-col"><span class="text-4xl sm:text-6xl font-black text-orange-500" x-text="seconds"></span><span class="text-[9px] md:text-[10px] uppercase text-gray-400 mt-1">Secs</span></div>
                </div>
            </template>
            <template x-if="!targetDate"><div class="text-2xl md:text-3xl font-black text-gray-300">To Be Announced</div></template>
        </div>

        <div class="text-center mb-8">
            <h3 class="text-2xl md:text-3xl font-black text-gray-900 tracking-tight">Official Candidate Roster</h3>
            <p class="text-gray-500 text-sm mt-1">The candidates currently approved for this election.</p>
        </div>

        @if(!$isCandidacyClosed)
            <div class="max-w-3xl mx-auto mb-8 bg-blue-50 border border-blue-200 rounded-2xl p-5 flex items-start gap-4 shadow-sm">
                <div class="w-10 h-10 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                </div>
                <div>
                    <h4 class="text-sm font-black text-blue-900">Candidacy Filing is Ongoing</h4>
                    <p class="text-xs font-medium text-blue-700 mt-1 leading-relaxed">To ensure fairness and prevent premature campaigning, the identities of approved candidates are strictly classified until the official filing period concludes.</p>
                </div>
            </div>
        @endif

        <div class="space-y-6 md:space-y-8">
            @foreach($positions as $position)
                <div class="bg-white rounded-3xl shadow-sm border border-gray-200 overflow-hidden flex flex-col">
                    <div class="h-2 w-full bg-gradient-to-r from-red-600 via-yellow-400 to-green-600"></div>
                    <div class="bg-gray-50 border-b-[3px] border-dashed border-gray-200 px-6 py-5">
                        <h4 class="text-base md:text-lg font-black text-gray-900 uppercase tracking-tight font-serif">{{ $position->title }}</h4>
                    </div>

                    <div class="p-6 md:p-8">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @forelse($position->candidates as $candidate)
                                @if($isCandidacyClosed)
                                    <a href="{{ route('candidate.profile', $candidate->id) }}" target="_blank" class="relative flex items-center gap-4 p-4 rounded-2xl bg-gray-50 border border-gray-100 hover-glow-tricolor hover:-translate-y-0.5 transition-all duration-300 group block min-w-0">
                                        <div class="w-14 h-14 rounded-full overflow-hidden border-2 border-white shadow-sm shrink-0 bg-white group-hover:ring-2 group-hover:ring-orange-500 transition-all">
                                            @if($candidate->profile_photo_path)
                                                <img src="{{ asset('storage/'.$candidate->profile_photo_path) }}" class="w-full h-full object-cover">
                                            @else
                                                <div class="w-full h-full bg-gray-200 flex items-center justify-center text-gray-500 font-black text-xl">{{ substr($candidate->display_name ?? optional($candidate->user)->name ?? '?', 0, 1) }}</div>
                                            @endif
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="font-black text-gray-900 leading-snug text-base group-hover:text-orange-600 transition-colors break-words">
                                                {{ $candidate->display_name ?? optional($candidate->user)->name ?? 'Unknown' }}
                                            </p>
                                            
                                            {{-- Pre-Release Party Display --}}
                                            @php
                                                if ($hasParties) {
                                                    $partyName = optional($candidate->party)->name ?? 'Independent';
                                                    $partyColor = optional($candidate->party)->color ?? '#6b7280';
                                                }
                                            @endphp
                                            
                                            <p class="text-[10px] md:text-xs font-bold text-gray-500 uppercase tracking-wider mt-1 leading-tight">
                                                @if($hasParties)
                                                    <span style="color: {{ $partyColor }};" class="font-black">{{ $partyName }}</span> <span class="hidden sm:inline">•</span><br class="block sm:hidden">
                                                @endif
                                                <span class="text-gray-700">{{ $candidate->college->name ?? 'N/A' }}</span> <span class="hidden sm:inline">•</span><br class="block sm:hidden"> 
                                                {{ $candidate->program }}
                                            </p>
                                        </div>
                                    </a>
                                @else
                                    <div class="relative flex items-center gap-4 p-4 rounded-2xl bg-gray-50/50 border border-gray-200 border-dashed block min-w-0 opacity-80">
                                        <div class="w-14 h-14 rounded-full border-2 border-gray-200 shadow-sm shrink-0 bg-gray-100 flex items-center justify-center text-gray-400">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="font-black text-gray-600 leading-tight text-base truncate">Classified Applicant</p>
                                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mt-1 truncate">Identity locked until filing ends</p>
                                        </div>
                                    </div>
                                @endif
                            @empty
                                <p class="text-gray-400 text-sm font-bold col-span-full">No candidates filed for this position yet.</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <script>
            document.addEventListener('alpine:init', () => {
                Alpine.data('countdownTimer', (dateString) => ({
                    targetDate: dateString ? new Date(dateString).getTime() : null,
                    days: '00', hours: '00', minutes: '00', seconds: '00',
                    init() { if (!this.targetDate) return; this.update(); setInterval(() => this.update(), 1000); },
                    update() {
                        let now = new Date().getTime(); let distance = this.targetDate - now;
                        if (distance < 0) { this.days = '00'; this.hours = '00'; this.minutes = '00'; this.seconds = '00'; this.$wire.checkReleaseStatus(); return; }
                        this.days = String(Math.floor(distance / (1000 * 60 * 60 * 24))).padStart(2, '0');
                        this.hours = String(Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60))).padStart(2, '0');
                        this.minutes = String(Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60))).padStart(2, '0');
                        this.seconds = String(Math.floor((distance % (1000 * 60)) / 1000)).padStart(2, '0');
                    }
                }));
            });
        </script>

    {{-- STATE 2: POST-RELEASE (LIVE OFFICIAL RESULTS) --}}
    @else
        <div wire:poll.5s class="space-y-8 md:space-y-12 animate-fade-in-up">
            <div class="bg-green-50 border border-green-200 rounded-2xl p-4 text-center mb-6 shadow-sm">
                <span class="text-green-800 font-black text-xs md:text-sm uppercase tracking-widest flex items-center justify-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-green-600 animate-ping"></span>
                    Official Results are Live
                </span>
            </div>

            @foreach($positions as $position)
                @php
                    $totalPositionVotes = $position->candidates->sum('votes_count');
                    $maxPossibleVotes = $totalTurnout * $position->max_winners;
                    $abstainVotes = max(0, $maxPossibleVotes - $totalPositionVotes);
                @endphp

                <div class="bg-white rounded-[2rem] shadow-[0_4px_20px_rgba(0,0,0,0.03)] border border-gray-200 flex flex-col overflow-hidden">
                    
                    <div class="h-2 w-full bg-gradient-to-r from-red-600 via-yellow-400 to-green-600"></div>

                    <div class="bg-gray-50 border-b-[3px] border-dashed border-gray-200 px-6 md:px-8 py-5 flex flex-col md:flex-row md:items-center justify-between gap-3">
                        <div>
                            <h2 class="text-xl md:text-2xl font-black text-gray-900 uppercase tracking-tight font-serif">{{ $position->title }}</h2>
                            <p class="text-[10px] md:text-xs font-bold text-gray-500 mt-1 uppercase tracking-wider">{{ number_format($totalPositionVotes) }} valid votes cast</p>
                        </div>
                        <span class="text-[10px] md:text-xs font-bold text-gray-900 bg-white border border-gray-200 shadow-sm px-3 py-1.5 rounded-lg w-max uppercase tracking-wider">Electing {{ $position->max_winners }} Seat(s)</span>
                    </div>

                    <div class="p-6 md:p-8 flex-1 flex flex-col">
                        <div class="space-y-4 flex-1">
                            @forelse($position->candidates as $index => $candidate)
                                @php
                                    $percentage = $maxPossibleVotes > 0 ? ($candidate->votes_count / $maxPossibleVotes) * 100 : 0;
                                    $isWinner = $index < $position->max_winners && $candidate->votes_count > 0;
                                    $candidateName = $candidate->display_name ?? optional($candidate->user)->name ?? 'Unknown';
                                    
                                    // DYNAMIC PARTY LOGIC
                                    if ($hasParties) {
                                        $partyName = optional($candidate->party)->name ?? 'Independent';
                                        $partyColor = optional($candidate->party)->color ?? '#6b7280'; // Slate Gray
                                    } else {
                                        $partyName = null;
                                        $partyColor = '#22c55e'; // Default Green
                                    }
                                    $partyBg = $partyColor . '1A'; // 10% Opacity Background
                                @endphp

                                <a href="{{ route('candidate.profile', $candidate->id) }}" target="_blank" 
                                   style="--party-color: {{ $partyColor }}; --party-bg: {{ $partyBg }};"
                                   class="relative block bg-gray-50 rounded-2xl p-4 md:p-5 border transition-all duration-300 overflow-hidden group hover-glow-tricolor
                                          {{ $isWinner ? 'border-[color:var(--party-color)] bg-[color:var(--party-bg)] shadow-sm transform -translate-y-0.5' : 'border-gray-200 hover:border-[color:var(--party-color)] hover:shadow-sm' }}">

                                    {{-- Progress Bar --}}
                                    <div class="absolute top-0 left-0 bottom-0 opacity-15 transition-all duration-1000 ease-out" 
                                         style="width: {{ $percentage }}%; background-color: var(--party-color);"></div>

                                    <div class="relative z-10 flex items-center gap-3 md:gap-5">

                                        {{-- BALLOT OVAL (Rank Number) --}}
                                        <div class="w-10 flex justify-center shrink-0">
                                            <div class="w-8 h-5 md:w-9 md:h-6 rounded-full border-2 flex items-center justify-center text-[10px] md:text-xs font-black transition-colors
                                                        {{ $isWinner ? 'border-[color:var(--party-color)] bg-[color:var(--party-color)] text-white shadow-inner' : 'border-gray-400 text-gray-500 bg-white group-hover:border-[color:var(--party-color)]' }}">
                                                {{ $index + 1 }}
                                            </div>
                                        </div>

                                        {{-- Avatar --}}
                                        <div class="w-12 h-12 md:w-16 md:h-16 rounded-full overflow-hidden border-[3px] shadow-sm shrink-0 bg-white transition-colors
                                                    {{ $isWinner ? 'border-[color:var(--party-color)]' : 'border-white group-hover:border-[color:var(--party-color)]' }}">
                                            @if($candidate->profile_photo_path)
                                                <img src="{{ asset('storage/'.$candidate->profile_photo_path) }}" class="w-full h-full object-cover">
                                            @else
                                                <div class="w-full h-full bg-gray-100 flex items-center justify-center font-black text-[color:var(--party-color)] text-xl">{{ substr($candidateName, 0, 1) }}</div>
                                            @endif
                                        </div>

                                        {{-- Info --}}
                                        <div class="flex-1 min-w-0 py-1">
                                            <h3 class="font-black text-gray-900 text-base md:text-lg leading-snug break-words group-hover:text-[color:var(--party-color)] transition-colors flex flex-wrap items-center gap-1.5">
                                                {{ $candidateName }}
                                                @if($isWinner)
                                                    <svg class="w-4 h-4 text-[color:var(--party-color)] shrink-0 inline-block" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                                @endif
                                            </h3>

                                            <p class="text-[10px] md:text-xs text-gray-500 font-bold uppercase tracking-wider mt-1.5 leading-snug break-words">
                                                @if($hasParties)
                                                    <span style="color: var(--party-color);" class="font-black">{{ $partyName }}</span> <span class="hidden sm:inline">•</span><br class="block sm:hidden">
                                                @endif
                                                <span class="text-gray-700">{{ $candidate->college->name ?? 'N/A' }}</span> <span class="hidden sm:inline">•</span><br class="block sm:hidden"> 
                                                {{ $candidate->program }}
                                            </p>
                                        </div>

                                        {{-- Stats Block --}}
                                        <div class="text-right shrink-0 pl-2">
                                            <div class="font-black text-xl md:text-2xl leading-none transition-colors {{ $isWinner ? 'text-[color:var(--party-color)]' : 'text-gray-900' }}">
                                                {{ number_format($candidate->votes_count) }}
                                            </div>
                                            <div class="text-[9px] md:text-[11px] font-bold text-gray-400 uppercase tracking-widest mt-1.5">{{ number_format($percentage, 1) }}%</div>
                                        </div>
                                    </div>
                                </a>
                            @empty
                                <div class="text-center text-gray-400 font-bold py-6 text-sm bg-gray-50 rounded-2xl border border-dashed border-gray-200">No approved candidates for this position.</div>
                            @endforelse
                        </div>

                        {{-- THE ABSTAIN BLOCK --}}
                        @php
                            $abstainPercentage = $maxPossibleVotes > 0 ? ($abstainVotes / $maxPossibleVotes) * 100 : 0;
                            $abstainLabel = $position->max_winners > 1 ? 'Abstain & Undervotes' : 'Abstentions';
                        @endphp

                        <div class="mt-6 relative bg-gray-50 rounded-2xl p-4 md:p-5 border border-dashed border-gray-300 overflow-hidden">
                            <div class="absolute top-0 left-0 bottom-0 bg-gray-200/50 transition-all duration-1000 ease-out" style="width: {{ $abstainPercentage }}%;"></div>
                            <div class="relative z-10 flex items-center justify-between gap-4">
                                <div class="flex items-center gap-3 md:gap-4 pl-[44px] md:pl-[60px]">
                                    <div class="w-8 h-8 md:w-10 md:h-10 rounded-full bg-gray-200 text-gray-500 flex items-center justify-center shrink-0 border border-white">
                                        <svg class="w-4 h-4 md:w-5 md:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    </div>
                                    <div class="min-w-0">
                                        <p class="font-bold text-gray-600 text-sm md:text-base break-words leading-tight">{{ $abstainLabel }}</p>
                                        <p class="text-[9px] md:text-[10px] font-bold text-gray-400 uppercase mt-1 tracking-wider leading-snug">Unused Ballot Slots</p>
                                    </div>
                                </div>
                                <div class="text-right shrink-0">
                                    <div class="font-black text-lg md:text-xl text-gray-500 leading-none">{{ number_format($abstainVotes) }}</div>
                                    <div class="text-[9px] md:text-[11px] font-bold text-gray-400 uppercase tracking-widest mt-1.5">{{ number_format($abstainPercentage, 1) }}%</div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>