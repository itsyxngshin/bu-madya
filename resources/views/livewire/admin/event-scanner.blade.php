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
<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>

<script>
    document.addEventListener('livewire:initialized', () => {
        let isProcessing = false; 

        let html5QrcodeScanner = new Html5QrcodeScanner(
            "reader", 
            { 
                fps: 10, 
                qrbox: {width: 250, height: 250}, 
                aspectRatio: 1.0,
                // Explicitly allow both Camera and File Image Upload
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
            if (isProcessing) return; 
            isProcessing = true;
            
            $wire.processScan(decodedText).then(() => {
                setTimeout(() => {
                    isProcessing = false;
                    $wire.set('scanStatus', null); 
                }, 3000); // Wait 3 seconds so the operator can read the ID card
            }).catch(error => {
                console.error("Scan error:", error);
                isProcessing = false;
            });
        }

        function onScanFailure(error) {
            // Background read failures are ignored
        }

        Livewire.on('play-sound', (event) => {
            let type = 'success';
            if (Array.isArray(event) && event.length > 0) {
                type = event[0].type || event[0];
            } else if (event && event.type) {
                type = event.type;
            }

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
                console.warn('Audio feedback requires user interaction first.');
            }
        });
    });
</script>

<style>
    /* ==============================================================
       HEAVY CUSTOMIZATION OF HTML5-QRCODE DEFAULT UI
       Turns ugly default elements into sleek Tailwind components
       ============================================================== */
    
    #reader { border: none !important; }
    
    /* Hide the default generic text */
    #reader__dashboard_section_csr span, 
    #reader__dashboard_section_csfa span { color: transparent !important; }
    
    /* Top Tabs: Switch between Camera and Image File */
    #reader__dashboard_section_swaplink { 
        display: block;
        background-color: #f3f4f6;
        color: #4b5563 !important; 
        text-decoration: none !important; 
        font-weight: bold;
        text-transform: uppercase;
        letter-spacing: 1px;
        font-size: 10px;
        padding: 12px;
        border-radius: 12px;
        margin-bottom: 16px;
        text-align: center;
        transition: all 0.3s;
    }
    #reader__dashboard_section_swaplink:hover {
        background-color: #e5e7eb;
        color: #111827 !important;
    }

    /* Camera Selection Dropdown */
    #reader__camera_selection {
        width: 100%;
        padding: 12px;
        border-radius: 12px;
        border: 1px solid #e5e7eb;
        background-color: white;
        color: #111827;
        font-size: 14px;
        font-weight: 600;
        margin-bottom: 12px;
        appearance: auto;
    }

    /* Image File Upload Input */
    #reader__filescan_input {
        width: 100%;
        padding: 10px;
        background: #f9fafb;
        border: 2px dashed #d1d5db;
        border-radius: 12px;
        margin-bottom: 12px;
        font-size: 12px;
        color: #6b7280;
    }

    /* Start/Stop Camera Buttons */
    #reader button { 
        width: 100%;
        background: #111827; 
        color: white; 
        border-radius: 12px; 
        padding: 14px 16px; 
        border: none; 
        font-weight: 800; 
        font-size: 14px;
        text-transform: uppercase;
        letter-spacing: 1px;
        cursor: pointer; 
        transition: background 0.3s;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    }
    #reader button:hover { background: #030712; }
    
    /* Specifically style the Stop Scanning button */
    #reader__dashboard_section_csr button:nth-of-type(2) {
        background: #ef4444;
        margin-top: 8px;
    }

    .animate-bounce-short { animation: bounce 0.5s ease-in-out 1; }
</style>
@endpush