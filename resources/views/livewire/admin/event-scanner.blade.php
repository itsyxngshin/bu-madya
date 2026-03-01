<div class="max-w-md mx-auto min-h-screen bg-gray-50 pb-20">

    {{-- Header --}}
    <div class="bg-gray-900 text-white p-6 rounded-b-3xl shadow-lg">
        <a href="{{ route('admin.events.index') }}" class="text-xs font-bold text-gray-400 hover:text-white uppercase tracking-widest flex items-center gap-2 mb-4">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Back to Events
        </a>
        <h1 class="text-2xl font-black leading-tight mb-2">{{ $event->title }}</h1>

        {{-- Progress Bar --}}
        <div class="mt-4">
            <div class="flex justify-between text-xs font-bold text-gray-400 mb-1">
                <span>Check-ins</span>
                <span>{{ $this->stats['attended'] }} / {{ $this->stats['total'] }} ({{ $this->stats['percentage'] }}%)</span>
            </div>
            <div class="w-full h-2 bg-gray-800 rounded-full overflow-hidden">
                <div class="h-full bg-green-500 rounded-full transition-all duration-500" style="width: {{ $this->stats['percentage'] }}%"></div>
            </div>
        </div>
    </div>

    <div class="p-6 space-y-6">

        {{-- CAMERA SCANNER CONTAINER --}}
        <div class="bg-white p-2 rounded-3xl shadow-sm border border-gray-200 overflow-hidden" wire:ignore>
            {{-- This div is where the html5-qrcode library will inject the camera feed --}}
            <div id="reader" class="rounded-2xl overflow-hidden bg-black aspect-square w-full"></div>
        </div>

        {{-- DYNAMIC FEEDBACK ALERT --}}
        @if($scanStatus)
            <div class="p-6 rounded-2xl shadow-sm border border-l-8 text-center animate-bounce-short
                {{ $scanStatus === 'success' ? 'bg-green-50 border-green-500 text-green-800' : '' }}
                {{ $scanStatus === 'warning' ? 'bg-yellow-50 border-yellow-500 text-yellow-800' : '' }}
                {{ $scanStatus === 'error' ? 'bg-red-50 border-red-500 text-red-800' : '' }}"
            >
                <div class="text-xs font-bold uppercase tracking-widest mb-1 opacity-70">
                    {{ $scanStatus === 'success' ? 'Verified' : ($scanStatus === 'warning' ? 'Duplicate' : 'Error') }}
                </div>
                <div class="text-lg font-black">{{ $scanMessage }}</div>
                @if($lastScannedName)
                    <div class="text-sm font-bold mt-2 opacity-90">{{ $lastScannedName }}</div>
                @endif
            </div>
        @else
            <div class="text-center p-6 text-gray-400 text-sm font-bold border-2 border-dashed border-gray-200 rounded-2xl">
                Ready to scan. Point camera at a ticket QR code.
            </div>
        @endif

        {{-- MANUAL ENTRY FALLBACK --}}
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-200">
            <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Manual Check-in</label>
            <form wire:submit.prevent="manualCheckIn" class="flex gap-2">
                <input type="text" wire:model="manualCode" placeholder="e.g. BUMADYA-XYZ123" class="w-full rounded-xl border-gray-200 text-sm uppercase">
                <button type="submit" class="bg-gray-900 text-white px-4 rounded-xl font-bold hover:bg-gray-800 transition">Verify</button>
            </form>
        </div>

    </div>
</div>

{{-- SCRIPT INJECTIONS --}}
@push('scripts')
{{-- Include HTML5 QR Code Library --}}
<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>

<script>
    document.addEventListener('livewire:initialized', () => {

        let html5QrcodeScanner = new Html5QrcodeScanner(
            "reader",
            { fps: 10, qrbox: {width: 250, height: 250}, aspectRatio: 1.0 },
            /* verbose= */ false
        );

        html5QrcodeScanner.render(onScanSuccess, onScanFailure);

        function onScanSuccess(decodedText, decodedResult) {
            // 1. Pause scanner to prevent rapid-fire multiple scans
            html5QrcodeScanner.pause();

            // 2. Send the code to Livewire to process
            @this.processScan(decodedText).then(() => {
                // 3. Wait 2.5 seconds, then resume scanning automatically
                setTimeout(() => {
                    html5QrcodeScanner.resume();
                    // Clear the UI message by setting status to null
                    @this.set('scanStatus', null);
                }, 2500);
            });
        }

        function onScanFailure(error) {
            // Ignore background scan failures (it fails every frame it doesn't see a QR)
        }

        // --- AUDIO FEEDBACK LOGIC ---
        // Creates a distinct "Beep" or "Buzzer" sound based on the server response
        Livewire.on('play-sound', (event) => {
            const type = event.type;
            const audioCtx = new (window.AudioContext || window.webkitAudioContext)();
            const oscillator = audioCtx.createOscillator();
            const gainNode = audioCtx.createGain();

            oscillator.connect(gainNode);
            gainNode.connect(audioCtx.destination);

            if (type === 'success') {
                // High happy beep
                oscillator.type = 'sine';
                oscillator.frequency.setValueAtTime(800, audioCtx.currentTime);
                gainNode.gain.setValueAtTime(0.1, audioCtx.currentTime);
                oscillator.start();
                oscillator.stop(audioCtx.currentTime + 0.15);
            } else {
                // Low error buzzer
                oscillator.type = 'sawtooth';
                oscillator.frequency.setValueAtTime(150, audioCtx.currentTime);
                gainNode.gain.setValueAtTime(0.1, audioCtx.currentTime);
                oscillator.start();
                oscillator.stop(audioCtx.currentTime + 0.3);
            }
        });
    });
</script>

<style>
    /* CSS to clean up the default library's ugly UI */
    #reader { border: none !important; }
    #reader__dashboard_section_csr span { color: white !important; }
    #reader__dashboard_section_swaplink { color: #f97316 !important; text-decoration: none !important; font-weight: bold;}
    #reader button { background: #1f2937; color: white; border-radius: 8px; padding: 8px 16px; border: none; font-weight: bold; margin-top: 10px; cursor: pointer; }
    .animate-bounce-short { animation: bounce 0.5s ease-in-out 1; }
</style>
@endpush
