<div class="max-w-5xl mx-auto py-12 px-4 sm:px-6 font-sans">

    {{-- ELECTION HEADER --}}
    <div class="bg-white rounded-[2rem] shadow-sm border border-gray-200 overflow-hidden mb-8">
        @if($election->cover_photo_path)
            <img src="{{ asset('storage/'.$election->cover_photo_path) }}" class="w-full h-48 md:h-64 object-cover">
        @else
            <div class="w-full h-12 bg-gray-900"></div>
        @endif
        <div class="p-8 text-center relative">
            <h1 class="text-3xl md:text-4xl font-black text-gray-900 tracking-tight">{{ $election->title }}</h1>
            @if($election->description)
                <p class="text-gray-500 mt-3 max-w-2xl mx-auto">{{ $election->description }}</p>
            @endif

            {{-- LIVE TURNOUT BADGE (Updates every 10 seconds) --}}
            <div wire:poll.10s class="mt-6 inline-flex items-center gap-2 bg-gray-50 border border-gray-200 px-4 py-2 rounded-full shadow-sm">
                <div class="w-2 h-2 rounded-full {{ $isReleased ? 'bg-green-500' : 'bg-orange-500 animate-pulse' }}"></div>
                <span class="text-xs font-bold text-gray-700 uppercase tracking-wider">
                    Total Turnout: <span class="text-gray-900 font-black">{{ number_format($totalTurnout) }} Voters</span>
                </span>
            </div>
        </div>
    </div>

    {{-- STATE 1: PRE-RELEASE (COUNTDOWN & CANDIDATE ROSTER) --}}
    @if(!$isReleased)
        <div class="bg-gray-900 text-white rounded-[2rem] p-10 text-center mb-12 shadow-2xl relative overflow-hidden isolate"
             x-data="countdownTimer('{{ $election->results_release ? $election->results_release->format('Y-m-d\TH:i:s') : null }}')">
            
            <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-orange-400 via-orange-500 to-orange-600"></div>

            <h2 class="text-sm font-bold text-orange-400 uppercase tracking-widest mb-4">Official Results Drop In</h2>
            
            <template x-if="targetDate">
                <div class="flex justify-center gap-4 sm:gap-6 font-mono">
                    <div class="flex flex-col"><span class="text-4xl sm:text-6xl font-black" x-text="days"></span><span class="text-[10px] uppercase text-gray-400 mt-1">Days</span></div>
                    <div class="text-4xl sm:text-6xl font-black text-gray-600">:</div>
                    <div class="flex flex-col"><span class="text-4xl sm:text-6xl font-black" x-text="hours"></span><span class="text-[10px] uppercase text-gray-400 mt-1">Hours</span></div>
                    <div class="text-4xl sm:text-6xl font-black text-gray-600">:</div>
                    <div class="flex flex-col"><span class="text-4xl sm:text-6xl font-black" x-text="minutes"></span><span class="text-[10px] uppercase text-gray-400 mt-1">Mins</span></div>
                    <div class="text-4xl sm:text-6xl font-black text-gray-600">:</div>
                    <div class="flex flex-col"><span class="text-4xl sm:text-6xl font-black text-orange-500" x-text="seconds"></span><span class="text-[10px] uppercase text-gray-400 mt-1">Secs</span></div>
                </div>
            </template>

            <template x-if="!targetDate">
                <div class="text-3xl font-black text-gray-300">To Be Announced</div>
            </template>
        </div>

        <div class="text-center mb-8">
            <h3 class="text-2xl font-black text-gray-900 tracking-tight">Official Candidate Roster</h3>
            <p class="text-gray-500 text-sm mt-1">The candidates currently on the ballot for this election.</p>
        </div>

        <div class="space-y-8">
            @foreach($positions as $position)
                <div class="bg-white rounded-3xl p-6 md:p-8 shadow-sm border border-gray-200">
                    <h4 class="text-lg font-black text-gray-900 uppercase tracking-tight border-b border-gray-100 pb-3 mb-6">{{ $position->title }}</h4>
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                        @forelse($position->candidates as $candidate)
                            <div class="flex items-center gap-4 p-4 rounded-2xl bg-gray-50 border border-gray-100 hover:border-gray-300 transition-colors">
                                @if($candidate->profile_photo_path)
                                    <img src="{{ asset('storage/'.$candidate->profile_photo_path) }}" class="w-14 h-14 rounded-full object-cover shadow-sm">
                                @else
                                    <div class="w-14 h-14 rounded-full bg-gray-200 text-gray-500 flex items-center justify-center font-bold text-lg">{{ substr($candidate->user->name ?? '?', 0, 1) }}</div>
                                @endif
                                <div>
                                    <p class="font-black text-gray-900 leading-tight">{{ $candidate->user->name ?? 'Unknown' }}</p>
                                    <p class="text-[10px] font-bold text-gray-500 uppercase tracking-wider">{{ $candidate->program }}</p>
                                </div>
                            </div>
                        @empty
                            <p class="text-gray-400 text-sm font-bold col-span-full">No candidates filed for this position.</p>
                        @endforelse
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Alpine Logic for the Countdown --}}
        <script>
            document.addEventListener('alpine:init', () => {
                Alpine.data('countdownTimer', (dateString) => ({
                    targetDate: dateString ? new Date(dateString).getTime() : null,
                    days: '00', hours: '00', minutes: '00', seconds: '00',
                    init() {
                        if (!this.targetDate) return;
                        this.update();
                        setInterval(() => this.update(), 1000);
                    },
                    update() {
                        let now = new Date().getTime();
                        let distance = this.targetDate - now;
                        
                        if (distance < 0) {
                            // TIMER HIT ZERO! Ping Livewire to unlock the results!
                            this.days = '00'; this.hours = '00'; this.minutes = '00'; this.seconds = '00';
                            this.$wire.checkReleaseStatus(); 
                            return;
                        }
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
        {{-- REAL-TIME POLLING: This refreshes the component every 5 seconds to show new votes! --}}
        <div wire:poll.5s class="space-y-8 animate-fade-in-up">
            <div class="bg-green-50 border border-green-200 rounded-2xl p-4 text-center mb-6 shadow-sm">
                <span class="text-green-800 font-black text-sm uppercase tracking-widest flex items-center justify-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-green-600 animate-ping"></span>
                    Official Results are Live
                </span>
            </div>

            @foreach($positions as $position)
                @php
                    $totalPositionVotes = $position->candidates->sum('votes_count');
                @endphp

                <div class="bg-white rounded-[2rem] shadow-sm border border-gray-200 overflow-hidden">
                    <div class="bg-gray-50/80 border-b border-gray-200 p-6">
                        <h3 class="text-xl font-black text-gray-900 uppercase tracking-tight">{{ $position->title }}</h3>
                        <p class="text-xs font-bold text-gray-500 mt-1">{{ number_format($totalPositionVotes) }} total votes cast • Top {{ $position->max_winners }} elected</p>
                    </div>
                    
                    <div class="p-6 md:p-8 space-y-6">
                        @forelse($position->candidates as $index => $candidate)
                            @php
                                $percentage = $totalPositionVotes > 0 ? ($candidate->votes_count / $totalPositionVotes) * 100 : 0;
                                $isWinning = $index < $position->max_winners && $candidate->votes_count > 0;
                            @endphp

                            <div class="relative">
                                <div class="flex items-center justify-between mb-3">
                                    <div class="flex items-center gap-4">
                                        @if($candidate->profile_photo_path)
                                            <img src="{{ asset('storage/'.$candidate->profile_photo_path) }}" class="w-12 h-12 rounded-full object-cover shadow-sm border border-gray-200">
                                        @else
                                            <div class="w-12 h-12 rounded-full bg-gray-100 text-gray-500 flex items-center justify-center font-bold text-lg">{{ substr($candidate->user->name ?? '?', 0, 1) }}</div>
                                        @endif
                                        
                                        <div>
                                            <p class="font-black text-gray-900 text-lg flex items-center gap-2">
                                                {{ $candidate->user->name ?? 'Unknown' }}
                                                @if($isWinning)
                                                    <svg class="w-5 h-5 text-yellow-500" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                                @endif
                                            </p>
                                            <p class="text-[10px] font-bold text-gray-500 uppercase tracking-wider">{{ $candidate->program }}</p>
                                        </div>
                                    </div>
                                    
                                    <div class="text-right">
                                        <p class="text-2xl font-black {{ $isWinning ? 'text-green-600' : 'text-gray-900' }} transition-all duration-500">{{ number_format($candidate->votes_count) }}</p>
                                        <p class="text-xs font-bold text-gray-400">{{ number_format($percentage, 1) }}%</p>
                                    </div>
                                </div>

                                <div class="w-full bg-gray-100 rounded-full h-4 overflow-hidden shadow-inner relative">
                                    <div class="absolute top-0 left-0 bottom-0 transition-all duration-1000 ease-out rounded-full {{ $isWinning ? 'bg-gradient-to-r from-green-400 to-green-500' : 'bg-gray-400' }}" style="width: {{ $percentage }}%;"></div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center text-gray-400 font-bold py-4 text-sm">No approved candidates for this position.</div>
                        @endforelse
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>