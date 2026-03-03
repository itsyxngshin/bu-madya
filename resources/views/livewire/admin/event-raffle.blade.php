<div class="min-h-screen bg-gray-900 text-white p-6 md:p-12 font-sans relative overflow-hidden flex flex-col">
    
    {{-- Header --}}
    <div class="flex justify-between items-center mb-12 relative z-10">
        <a href="{{ route('admin.events.index') }}" class="text-xs font-bold text-gray-400 hover:text-white uppercase tracking-widest flex items-center gap-2 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Exit Raffle
        </a>
        <div class="text-right">
            <h1 class="text-xl font-black text-white uppercase tracking-widest">{{ $event->title }}</h1>
            <p class="text-xs text-red-500 font-bold uppercase tracking-widest mt-1">Live Electronic Raffle</p>
        </div>
    </div>

    {{-- Main Raffle Interface --}}
    <div class="flex-1 flex flex-col items-center justify-center relative z-10"
         x-data="raffleSystem(@js($eligibleAttendees))"
         x-init="$watch('attendees', value => console.log('Pool updated'))"
    >
        
        {{-- The Slot Machine Display --}}
        <div class="bg-gray-800/50 backdrop-blur-xl border border-gray-700 p-8 md:p-16 rounded-[3rem] shadow-2xl text-center w-full max-w-4xl mb-12 relative overflow-hidden">
            
            {{-- Decorative glow --}}
            <div class="absolute top-[-50%] left-[-10%] w-[500px] h-[500px] bg-red-600/20 rounded-full mix-blend-screen filter blur-[100px] pointer-events-none"></div>

            <p class="text-sm font-bold text-gray-400 uppercase tracking-widest mb-6" x-text="spinning ? 'Selecting Winner...' : 'Grand Prize Winner'"></p>
            
            <div class="min-h-[120px] flex flex-col items-center justify-center">
                <h2 class="text-4xl md:text-7xl font-black text-white leading-tight mb-4 transition-all" 
                    x-text="currentDisplay"
                    :class="{'scale-110 text-red-500 drop-shadow-[0_0_30px_rgba(239,68,68,0.8)]' : winner && !spinning, 'opacity-50 blur-[1px]' : spinning}">
                </h2>
                
                <p class="text-lg md:text-xl font-bold text-gray-400" 
                   x-show="winner && !spinning" 
                   x-transition.duration.500ms
                   x-text="winnerDetails"
                   style="display: none;">
                </p>

                <div x-show="winner && !spinning" 
                     x-transition.delay.1000ms
                     style="display: none;" 
                     class="mt-8">
                    <button @click="revoke()" class="text-[10px] md:text-xs font-bold text-gray-500 hover:text-red-400 uppercase tracking-widest border border-gray-700 hover:border-red-500 px-4 py-2 rounded-lg transition-all">
                        Not Present? (Revoke & Clear)
                    </button>
                </div>
            </div>
        </div>

        {{-- Controls & Stats --}}
        <div class="flex flex-col items-center gap-6">
            <button @click="spin()" 
                    :disabled="spinning || attendees.length === 0"
                    class="bg-red-600 text-white px-12 py-5 rounded-2xl font-black text-2xl uppercase tracking-widest shadow-[0_0_40px_rgba(239,68,68,0.4)] hover:bg-red-500 hover:scale-105 transition-all disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:scale-100">
                <span x-text="spinning ? 'Spinning...' : 'Draw Winner'"></span>
            </button>

            <p class="text-sm font-bold text-gray-500">
                <span x-text="attendees.length"></span> Eligible Attendees in Pool
            </p>
        </div>

    </div>

    {{-- Winner History Sidebar (Absolute on large screens) --}}
    @if(count($winnerList) > 0)
        <div class="fixed right-0 top-0 bottom-0 w-80 bg-gray-900/95 backdrop-blur-xl border-l border-gray-800 p-6 overflow-y-auto hidden xl:block z-50 animate-fade-in-right">
            <h3 class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-6 border-b border-gray-800 pb-4">Previous Winners</h3>
            <div class="space-y-4">
                @foreach($winnerList as $index => $pastWinner)
                    <div class="bg-gray-800 p-4 rounded-xl border border-gray-700 animate-fade-in-down" style="animation-delay: {{ $index * 100 }}ms">
                        <p class="font-bold text-white">{{ $pastWinner['name'] }}</p>
                        <p class="text-[10px] text-gray-400 uppercase tracking-widest mt-1">{{ $pastWinner['classification'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>

{{-- Canvas Confetti Library & Alpine Logic --}}
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('raffleSystem', (initialAttendees) => ({
            attendees: initialAttendees,
            spinning: false,
            currentDisplay: 'Ready to Draw!',
            winnerDetails: '',
            winner: false,
            currentWinnerTicket: null,

            // Keep Alpine's data in sync with Livewire's data when the pool shrinks
            init() {
                this.$watch('$wire.eligibleAttendees', value => {
                    this.attendees = value;
                });
            },

            spin() {
                if (this.attendees.length === 0) {
                    alert('No eligible attendees left to draw!');
                    return;
                }

                this.spinning = true;
                this.winner = false;
                this.winnerDetails = '';
                this.currentWinnerTicket = null; // Reset
                
                let duration = 3500; 
                let interval = 60; 
                let elapsed = 0;

                let timer = setInterval(() => {
                    let randomIndex = Math.floor(Math.random() * this.attendees.length);
                    this.currentDisplay = this.attendees[randomIndex].name;
                    elapsed += interval;

                    if (elapsed > duration * 0.7) {
                        interval += 20; 
                    }

                    if (elapsed >= duration) {
                        clearInterval(timer);
                        this.finalizeWinner(this.attendees[randomIndex]);
                    }
                }, interval);
            }, 

            finalizeWinner(selectedWinner) { 
                this.spinning = false;
                this.winner = true;
                this.currentDisplay = selectedWinner.name;
                this.winnerDetails = `${selectedWinner.classification} • Ticket: ${selectedWinner.ticket_code}`;
                this.currentWinnerTicket = selectedWinner.ticket_code; 
            
                // Trigger Confetti
                this.fireConfetti();

                // Tell Livewire to record this winner so they are removed from the next draw
                this.$wire.recordWinner(selectedWinner.id);
            },

            revoke() {
                if(confirm('Are you sure they are absent? This will remove them from the winners list.')) {
                    // Tell Livewire to wipe them from the sidebar
                    this.$wire.revokeWinner(this.currentWinnerTicket);
                    
                    // Reset the screen for the next draw
                    this.winner = false;
                    this.currentDisplay = 'Ready to Draw!';
                    this.winnerDetails = '';
                    this.currentWinnerTicket = null;
                }
            },

            fireConfetti() {
                var duration = 3000;
                var end = Date.now() + duration;

                (function frame() {
                    confetti({
                        particleCount: 5,
                        angle: 60,
                        spread: 55,
                        origin: { x: 0 },
                        colors: ['#ef4444', '#f97316', '#ffffff'] // BU MADYA Colors
                    });
                    confetti({
                        particleCount: 5,
                        angle: 120,
                        spread: 55,
                        origin: { x: 1 },
                        colors: ['#ef4444', '#f97316', '#ffffff']
                    });

                    if (Date.now() < end) {
                        requestAnimationFrame(frame);
                    }
                }());
            }
        }));
    });
</script>
@endpush