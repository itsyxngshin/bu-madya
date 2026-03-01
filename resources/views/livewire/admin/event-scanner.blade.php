<div class="max-w-md mx-auto min-h-screen bg-gray-50 pb-20">

    {{-- Header --}}
    <div class="bg-gray-900 text-white p-6 rounded-b-3xl shadow-lg">
        @if(auth()->check() && in_array(auth()->user()->role?->role_name, ['administrator', 'director']))
            <a href="{{ route('admin.events.index') }}" class="text-xs font-bold text-gray-400 hover:text-white uppercase tracking-widest flex items-center gap-2 mb-4 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Back to Admin Dashboard
            </a>
        @endif
        <h1 class="text-2xl font-black leading-tight mb-2">{{ $event->title }}</h1>
        <p class="text-xs text-red-400 font-bold uppercase tracking-widest">Official Check-in Scanner</p>

        {{-- Progress Bar --}}
        <div class="mt-6">
            <div class="flex justify-between text-xs font-bold text-gray-400 mb-2">
                <span>Check-ins</span>
                <span>{{ $stats['attended'] }} / {{ $stats['total'] }} ({{ $stats['percentage'] }}%)</span>
            </div>
            <div class="w-full h-2 bg-gray-800 rounded-full overflow-hidden">
                <div class="h-full bg-green-500 rounded-full transition-all duration-500" style="width: {{ $stats['percentage'] }}%"></div>
            </div>
        </div>
    </div>

    <div class="p-6 space-y-6">

        {{-- CAMERA SCANNER CONTAINER --}}
        <div class="bg-white p-3 rounded-3xl shadow-sm border border-gray-200 overflow-hidden" wire:ignore>
            <div id="reader" class="rounded-2xl overflow-hidden bg-black w-full"></div>
        </div>

        {{-- DYNAMIC FEEDBACK / ATTENDEE ID CARD --}}
        @if($scanStatus)
            <div class="rounded-3xl shadow-lg overflow-hidden animate-bounce-short border-2 
                {{ $scanStatus === 'success' ? 'bg-white border-green-500' : '' }}
                {{ $scanStatus === 'warning' ? 'bg-white border-yellow-500' : '' }}
                {{ $scanStatus === 'error' ? 'bg-red-50 border-red-500 text-red-800' : '' }}"
            >
                {{-- Status Header --}}
                <div class="p-3 text-center text-white font-black uppercase tracking-widest text-sm
                    {{ $scanStatus === 'success' ? 'bg-green-500' : '' }}
                    {{ $scanStatus === 'warning' ? 'bg-yellow-500' : '' }}
                    {{ $scanStatus === 'error' ? 'bg-red-500' : '' }}">
                    {{ $scanMessage }}
                </div>

                {{-- Rich Attendee Details --}}
                @if($lastScannedData)
                    <div class="p-6 text-center">
                        <span class="inline-block px-3 py-1 bg-gray-100 text-gray-600 text-[10px] font-bold rounded-full uppercase tracking-widest mb-3">
                            {{ $lastScannedData['classification'] }}
                        </span>
                        <h2 class="text-2xl font-black text-gray-900 leading-tight mb-1">
                            {{ $lastScannedData['name'] }}
                        </h2>
                        @if($lastScannedData['details'])
                            <p class="text-sm font-bold text-gray-500 mb-4">{{ $lastScannedData['details'] }}</p>
                        @endif
                        
                        <div class="inline-block bg-gray-50 border border-gray-200 rounded-lg px-4 py-2 mt-2">
                            <p class="text-[10px] text-gray-400 uppercase font-bold tracking-widest mb-0.5">Ticket Code</p>
                            <p class="text-xs font-mono font-bold text-gray-900">{{ $lastScannedData['ticket_code'] }}</p>
                        </div>
                    </div>
                @endif
            </div>
        @else
            <div class="text-center p-6 text-gray-400 text-sm font-bold border-2 border-dashed border-gray-200 rounded-3xl bg-white">
                Ready to scan. Point camera at a ticket or upload a QR image.
            </div>
        @endif

        {{-- MANUAL ENTRY FALLBACK --}}
        <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-200">
            <label class="block text-xs font-bold text-gray-500 uppercase mb-3">Manual Check-in</label>
            <form wire:submit.prevent="manualCheckIn" class="flex gap-2">
                <input type="text" wire:model="manualCode" placeholder="e.g. BUMADYA-XYZ123" class="w-full rounded-xl border-gray-200 text-sm uppercase focus:ring-red-500">
                <button type="submit" class="bg-gray-900 text-white px-5 rounded-xl font-bold hover:bg-gray-800 transition">Verify</button>
            </form>
        </div>

    </div>
</div>

@push('scripts')
{{-- 1. Load the Library globally --}}
<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>

{{-- 2. Use Livewire 3's @script directive to bind $wire securely --}}
@script
<script>
    let isProcessing = false; 

    // Make the qrbox slightly dynamic for better mobile scanning
    let qrboxFunction = function(viewfinderWidth, viewFinderHeight) {
        let minEdgePercentage = 0.7; // 70% of the screen
        let minEdgeSize = Math.min(viewfinderWidth, viewFinderHeight);
        let qrboxSize = Math.floor(minEdgeSize * minEdgePercentage);
        return {
            width: qrboxSize,
            height: qrboxSize
        };
    }

    let html5QrcodeScanner = new Html5QrcodeScanner(
        "reader", 
        { 
            fps: 10, 
            qrbox: qrboxFunction, 
            aspectRatio: 1.0,
            supportedScanTypes: [
                Html5QrcodeScanType.SCAN_TYPE_CAMERA,
                Html5QrcodeScanType.SCAN_TYPE_FILE
            ],
            rememberLastUsedCamera: true
        }, 
        false
    );

    html5QrcodeScanner.render(onScanSuccess, onScanFailure);

    function onScanSuccess(decodedText, decodedResult) {
        // Stop if we are already processing a ticket
        if (isProcessing) return; 
        isProcessing = true;
        
        console.log("QR Code read successfully:", decodedText); // For debugging
        
        // Safely call the backend using the bound $wire object
        $wire.processScan(decodedText).then(() => {
            // Wait 3 seconds so the operator can verify the ID card
            setTimeout(() => {
                isProcessing = false;
                $wire.set('scanStatus', null); 
            }, 3000); 
        }).catch(error => {
            console.error("Livewire Server Error:", error);
            isProcessing = false;
        });
    }

    function onScanFailure(error) {
        // Ignore standard frame scan failures
    }

    // --- BULLETPROOF AUDIO FEEDBACK LOGIC ---
    function playTone(type) {
        try {
            const audioCtx = new (window.AudioContext || window.webkitAudioContext)();
            const oscillator = audioCtx.createOscillator();
            const gainNode = audioCtx.createGain();

            oscillator.connect(gainNode);
            gainNode.connect(audioCtx.destination);

            if (type === 'success') {
                oscillator.type = 'sine';
                oscillator.frequency.setValueAtTime(800, audioCtx.currentTime);
                gainNode.gain.setValueAtTime(0.1, audioCtx.currentTime);
                oscillator.start();
                oscillator.stop(audioCtx.currentTime + 0.15);
            } else {
                oscillator.type = 'sawtooth';
                oscillator.frequency.setValueAtTime(150, audioCtx.currentTime);
                gainNode.gain.setValueAtTime(0.1, audioCtx.currentTime);
                oscillator.start();
                oscillator.stop(audioCtx.currentTime + 0.3);
            }
        } catch (e) {
            console.warn('Browser prevented audio playback.');
        }
    }

    // Listen for the separate events dispatched from the backend
    Livewire.on('play-success-sound', () => playTone('success'));
    Livewire.on('play-error-sound', () => playTone('error'));
</script>
@endscript

<style>
    /* ==============================================================
       CUSTOM HTML5-QRCODE UI TO MATCH TAILWIND DESIGN
       ============================================================== */
    #reader { border: none !important; }
    
    #reader__dashboard_section_csr span, 
    #reader__dashboard_section_csfa span { color: transparent !important; }
    
    #reader__dashboard_section_swaplink { 
        display: block; background-color: #f3f4f6; color: #4b5563 !important; 
        text-decoration: none !important; font-weight: bold; text-transform: uppercase;
        letter-spacing: 1px; font-size: 10px; padding: 12px; border-radius: 12px;
        margin-bottom: 16px; text-align: center; transition: all 0.3s;
    }
    #reader__dashboard_section_swaplink:hover { background-color: #e5e7eb; color: #111827 !important; }

    #reader__camera_selection {
        width: 100%; padding: 12px; border-radius: 12px; border: 1px solid #e5e7eb;
        background-color: white; color: #111827; font-size: 14px; font-weight: 600;
        margin-bottom: 12px; appearance: auto;
    }

    #reader__filescan_input {
        width: 100%; padding: 10px; background: #f9fafb; border: 2px dashed #d1d5db;
        border-radius: 12px; margin-bottom: 12px; font-size: 12px; color: #6b7280;
    }

    #reader button { 
        width: 100%; background: #111827; color: white; border-radius: 12px; 
        padding: 14px 16px; border: none; font-weight: 800; font-size: 14px;
        text-transform: uppercase; letter-spacing: 1px; cursor: pointer; 
        transition: background 0.3s; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);
    }
    #reader button:hover { background: #030712; }
    
    #reader__dashboard_section_csr button:nth-of-type(2) { background: #ef4444; margin-top: 8px; }
    .animate-bounce-short { animation: bounce 0.5s ease-in-out 1; }
</style>
@endpush