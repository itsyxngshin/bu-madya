<div class="min-h-screen bg-gray-900 text-white p-6 md:p-12 font-sans relative overflow-hidden flex flex-col"
     x-data="raffleSystem(@js($eligibleAttendees), '{{ $raffleMode }}')"
     x-init="$watch('attendees', value => updateWheelSegments());"
     @raffle-mode-changed.window="mode = $event.detail.mode; if(mode === 'wheel') setTimeout(() => initWheel(), 100);"
>

    {{-- Header --}}
    <div class="flex justify-between items-center mb-8 relative z-10">
        @php
            $user = auth()->user();
            $canAccessAdmin = false;
            $backRoute = route('admin.events.index'); // Default route for System Admins

            if ($user) {
                $role = strtolower($user->role?->role_name ?? '');

                // 1. System Administrators & Directors (Full Access)
                if (in_array($role, ['administrator', 'director'])) {
                    $canAccessAdmin = true;
                }
                // 2. Event Owner (Organization) or Collaborators
                elseif ($role === 'organization' && $event->user_id === $user->id) {
                    $canAccessAdmin = true;
                    $backRoute = route('partner.events.index'); // Redirect to Partner Dashboard
                }
            }
        @endphp

        @if($canAccessAdmin)
            <a href="{{ $backRoute }}" class="text-xs font-bold text-gray-400 hover:text-white uppercase tracking-widest flex items-center gap-2 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Exit Raffle
            </a>
        @endif

        <div class="text-right">
            <h1 class="text-xl font-black text-white uppercase tracking-widest">{{ $event->title }}</h1>
            <p class="text-xs text-red-500 font-bold uppercase tracking-widest mt-1">Live Electronic Raffle</p>
        </div>
    </div>

    {{-- Mode Toggle --}}
    <div class="flex justify-center mb-8 relative z-10">
        <div class="bg-gray-800 p-1.5 rounded-xl border border-gray-700 flex gap-1">
            <button wire:click="switchMode('slot')" class="px-6 py-2 rounded-lg text-[10px] font-black uppercase tracking-widest transition-all" :class="mode === 'slot' ? 'bg-red-600 text-white shadow-md' : 'text-gray-400 hover:text-white'">
                Slot Machine
            </button>
            <button wire:click="switchMode('wheel')" class="px-6 py-2 rounded-lg text-[10px] font-black uppercase tracking-widest transition-all" :class="mode === 'wheel' ? 'bg-yellow-500 text-black shadow-md' : 'text-gray-400 hover:text-white'">
                Spin Wheel
            </button>
        </div>
    </div>

    {{-- Main Raffle Interface --}}
    <div class="flex-1 flex flex-col items-center justify-center relative z-10 w-full max-w-4xl mx-auto">

        {{-- MODE 1: SLOT MACHINE --}}
        <div x-show="mode === 'slot'" class="w-full text-center mb-8" x-transition>
            <div class="bg-gray-800/50 backdrop-blur-xl border border-gray-700 p-8 md:p-16 rounded-[3rem] shadow-2xl relative overflow-hidden">
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
                </div>
            </div>
        </div>

        {{-- MODE 2: SPIN WHEEL --}}
        <div x-show="mode === 'wheel'" class="w-full flex justify-center mb-8 relative" x-transition style="display: none;">
            {{-- Winner Popup Overlay --}}
            <div x-show="winner && !spinning"
                 x-transition.duration.500ms
                 class="absolute inset-0 z-20 flex flex-col items-center justify-center bg-gray-900/90 backdrop-blur-md rounded-[3rem] border border-gray-700 p-8 shadow-2xl text-center"
                 style="display: none;">
                <p class="text-sm font-bold text-gray-400 uppercase tracking-widest mb-4">Winner Selected!</p>
                <h2 class="text-4xl md:text-6xl font-black text-yellow-400 drop-shadow-[0_0_20px_rgba(234,179,8,0.5)] mb-4" x-text="currentDisplay"></h2>
                <p class="text-lg font-bold text-gray-300 mb-8" x-text="winnerDetails"></p>
                <button @click="resetWheel()" class="bg-gray-800 text-white border border-gray-600 px-6 py-3 rounded-xl font-bold uppercase tracking-widest hover:bg-gray-700 transition-colors text-xs">
                    Clear & Spin Again
                </button>
            </div>

            {{-- The Canvas Wheel --}}
            <div class="relative w-[400px] h-[400px] md:w-[500px] md:h-[500px] bg-gray-800 rounded-full p-2 shadow-[0_0_50px_rgba(0,0,0,0.5)] border-8 border-gray-700">
                {{-- Stopper Pointer --}}
                <div class="absolute -top-4 left-1/2 -translate-x-1/2 z-10 text-red-500 drop-shadow-md">
                    <svg class="w-12 h-12 rotate-180" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2L2 22h20L12 2z"/></svg>
                </div>

                {{-- Inner Glow --}}
                <div class="absolute inset-0 rounded-full shadow-[inset_0_0_40px_rgba(0,0,0,0.8)] z-0 pointer-events-none"></div>

                {{-- We wrap the canvas to safely constrain it --}}
                <div class="w-full h-full rounded-full overflow-hidden flex items-center justify-center bg-gray-900">
                    <canvas id="canvasWheel" width="480" height="480" class="w-full h-full object-contain" :class="{'opacity-50 blur-[2px] transition-all': attendees.length === 0}"></canvas>
                </div>

                {{-- Center Hub --}}
                <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-16 h-16 bg-gray-900 border-4 border-gray-700 rounded-full shadow-[0_0_15px_rgba(0,0,0,0.5)] z-10 flex items-center justify-center">
                    <div class="w-4 h-4 bg-red-500 rounded-full"></div>
                </div>
            </div>
        </div>

        {{-- Global Controls & Stats --}}
        <div class="flex flex-col items-center gap-6 mt-4 relative z-10 w-full">
            <button @click="spin()"
                    :disabled="spinning || attendees.length === 0"
                    class="text-white px-12 py-5 rounded-2xl font-black text-xl md:text-2xl uppercase tracking-widest hover:scale-105 transition-all disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:scale-100 shadow-xl"
                    :class="mode === 'slot' ? 'bg-red-600 shadow-[0_0_40px_rgba(239,68,68,0.4)] hover:bg-red-500' : 'bg-yellow-600 shadow-[0_0_40px_rgba(202,138,4,0.4)] hover:bg-yellow-500'">
                <span x-text="spinning ? 'Spinning...' : 'Draw Winner'"></span>
            </button>

            <div class="flex flex-col items-center gap-2">
                <p class="text-sm font-bold text-gray-400">
                    <span x-text="attendees.length" class="text-white"></span> Eligible Attendees in Pool
                </p>
                <div x-show="winner && !spinning" style="display: none;" class="mt-2">
                    <button @click="revoke()" class="text-[10px] md:text-xs font-bold text-gray-500 hover:text-red-400 uppercase tracking-widest transition-all hover:underline">
                        Not Present? (Revoke & Redraw)
                    </button>
                </div>
            </div>
        </div>

    </div>

    {{-- Winner History Sidebar --}}
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

@push('scripts')
{{-- Include Libraries --}}
<script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>
{{-- Note: Winwheel requires GSAP for the smooth spinning animations --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
<script src="https://cdn.jsdelivr.net/gh/zarocknz/javascript-winwheel/Winwheel.min.js"></script>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('raffleSystem', (initialAttendees, currentMode) => ({
            attendees: initialAttendees,
            mode: currentMode,
            spinning: false,
            currentDisplay: 'Ready to Draw!',
            winnerDetails: '',
            winner: false,
            currentWinnerTicket: null,
            theWheel: null, // Holds the Winwheel instance

            // BU MADYA Brand Colors for the wheel segments
            wheelColors: ['#ef4444', '#f97316', '#eab308', '#22c55e', '#3b82f6', '#8b5cf6', '#ec4899', '#14b8a6', '#64748b', '#f43f5e'],

            init() {
                this.$watch('$wire.eligibleAttendees', value => {
                    this.attendees = value;
                });

                // Initialize wheel on load if mode is wheel
                if(this.mode === 'wheel') {
                    setTimeout(() => { this.initWheel(); }, 200);
                }
            },

            // --- WHEEL LOGIC ---
            initWheel() {
                if (this.attendees.length === 0) return;

                // Dynamically build segments from attendee pool (limit to 30 for visual clarity if pool is huge)
                let visualPool = [...this.attendees];
                // Shuffle array to mix up colors/placements
                visualPool = visualPool.sort(() => 0.5 - Math.random());

                // If more than 40 attendees, the wheel slices get too small to read text.
                // We'll still use them, but cap the rendered text or just show initials.
                let maxVisuals = Math.min(visualPool.length, 60);
                let segments = [];

                for(let i = 0; i < maxVisuals; i++) {
                    let textLabel = visualPool[i].name;
                    // Truncate long names to fit on the wheel slice
                    if (maxVisuals > 20 && textLabel.length > 15) { textLabel = textLabel.substring(0, 12) + '...'; }

                    segments.push({
                        'fillStyle': this.wheelColors[i % this.wheelColors.length],
                        'text': textLabel,
                        'textFillStyle': '#ffffff',
                        // Store the real object data in the segment so we can retrieve it when it stops
                        'attendeeData': visualPool[i]
                    });
                }

                // If pool is very small (1-3), duplicate segments so the wheel looks full
                if(segments.length > 0 && segments.length < 6) {
                    let originalLength = segments.length;
                    for (let i = 0; i < (8 - originalLength); i++) {
                         segments.push(segments[i % originalLength]);
                    }
                }

                this.theWheel = new Winwheel({
                    'canvasId': 'canvasWheel',
                    'numSegments': segments.length,
                    'segments': segments,
                    'outerRadius': 230,
                    'innerRadius': 40, // Creates a donut hole in the center
                    'textFontSize': maxVisuals > 30 ? 10 : 14,
                    'textOrientation': 'horizontal',
                    'textAlignment': 'outer',
                    'textMargin': 15,
                    'lineWidth': 2,
                    'strokeStyle': '#1f2937', // Dark gray borders
                    'animation': {
                        'type': 'spinToStop',
                        'duration': 5, // 5 seconds
                        'spins': 8,    // 8 full rotations before stopping
                        'callbackFinished': (indicatedSegment) => {
                            this.finalizeWinner(indicatedSegment.attendeeData);
                        }
                    }
                });

                // Fix canvas blurriness on high-DPI screens
                let canvas = document.getElementById('canvasWheel');
                if(canvas) {
                    let ctx = canvas.getContext('2d');
                    ctx.imageSmoothingEnabled = true;
                }
            },

            updateWheelSegments() {
                if (this.mode === 'wheel' && !this.spinning && !this.winner) {
                    this.initWheel();
                }
            },

            resetWheel() {
                this.winner = false;
                this.currentDisplay = 'Ready to Draw!';
                this.winnerDetails = '';
                this.currentWinnerTicket = null;

                if (this.theWheel) {
                    this.theWheel.stopAnimation(false);
                    this.theWheel.rotationAngle = 0;
                    // Redraw to clear the selection highlight
                    this.initWheel();
                }
            },

            // --- MASTER SPIN TRIGGER ---
            spin() {
                if (this.attendees.length === 0) {
                    alert('No eligible attendees left to draw!');
                    return;
                }

                this.spinning = true;
                this.winner = false;
                this.winnerDetails = '';
                this.currentWinnerTicket = null;

                if (this.mode === 'slot') {
                    this.spinSlot();
                } else {
                    if(this.theWheel) {
                        this.theWheel.startAnimation();
                    }
                }
            },

            // --- SLOT LOGIC ---
            spinSlot() {
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

            // --- SHARED WINNER LOGIC ---
            finalizeWinner(selectedWinner) {
                this.spinning = false;
                this.winner = true;
                this.currentDisplay = selectedWinner.name;
                this.winnerDetails = `${selectedWinner.classification} • Ticket: ${selectedWinner.ticket_code}`;
                this.currentWinnerTicket = selectedWinner.ticket_code;

                this.fireConfetti();

                // Let livewire know so it burns the ticket
                this.$wire.recordWinner(selectedWinner.id);
            },

            revoke() {
                if(confirm('Are you sure they are absent? This will remove them from the winners list.')) {
                    this.$wire.revokeWinner(this.currentWinnerTicket);

                    if (this.mode === 'wheel') {
                        this.resetWheel();
                    } else {
                        this.winner = false;
                        this.currentDisplay = 'Ready to Draw!';
                        this.winnerDetails = '';
                        this.currentWinnerTicket = null;
                    }
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
                        colors: ['#ef4444', '#f97316', '#ffffff']
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
