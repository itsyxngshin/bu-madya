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

        {{-- CAMERA SCANNER CONTAINER (Increased padding for breathing room) --}}
        <div class="bg-white p-4 sm:p-5 rounded-3xl shadow-sm border border-gray-200 overflow-hidden" wire:ignore>
            <div id="reader" class="w-full"></div>
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

    let qrboxFunction = function(viewfinderWidth, viewFinderHeight) {
        let minEdgePercentage = 0.7;
        let minEdgeSize = Math.min(viewfinderWidth, viewFinderHeight);
        let qrboxSize = Math.floor(minEdgeSize * minEdgePercentage);
        return { width: qrboxSize, height: qrboxSize };
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
        if (isProcessing) return;
        isProcessing = true;

        $wire.processScan(decodedText).then(() => {
            setTimeout(() => {
                isProcessing = false;
                $wire.set('scanStatus', null);
            }, 3000);
        }).catch(error => {
            isProcessing = false;
        });
    }

    function onScanFailure(error) {
        // Ignore standard frame scan failures
    }

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

    Livewire.on('play-success-sound', () => playTone('success'));
    Livewire.on('play-error-sound', () => playTone('error'));
</script>
@endscript

<style>
    /* ==============================================================
       ULTRA-MODERN HTML5-QRCODE UI OVERRIDES
       ============================================================== */

    /* Main container cleanup */
    #reader { border: none !important; background: transparent !important; }

    /* Hide ugly default descriptive texts injected by the library */
    #reader__dashboard_section_csr > span,
    #reader__dashboard_section_csfa > span,
    #reader__dashboard_section_csr > div > span {
        display: none !important;
    }

    /* 1. THE TOGGLE SWITCH (Camera vs File) */
    #reader__dashboard_section_swaplink {
        display: inline-block;
        background-color: #f3f4f6; /* bg-gray-100 */
        color: #4b5563 !important; /* text-gray-600 */
        text-decoration: none !important;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 1px;
        font-size: 10px;
        padding: 10px 20px;
        border-radius: 9999px; /* pill shape */
        margin: 0 auto 16px auto;
        text-align: center;
        transition: all 0.2s;
        border: 1px solid #e5e7eb;
    }
    #reader__dashboard_section_swaplink:hover {
        background-color: #e5e7eb;
        color: #111827 !important;
    }

    /* 2. THE CAMERA DROPDOWN SELECTION */
    #reader__camera_selection {
        width: 100%;
        padding: 14px 16px;
        border-radius: 12px;
        border: 2px solid #f3f4f6;
        background-color: #f9fafb;
        color: #111827;
        font-size: 14px;
        font-weight: 700;
        margin-bottom: 16px;
        appearance: none; /* Removes default OS styling */
        outline: none;
        cursor: pointer;
        transition: all 0.2s;
        /* Custom SVG Dropdown Arrow */
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M5 8l5 5 5-5'/%3e%3c/svg%3e");
        background-position: right 12px center;
        background-repeat: no-repeat;
        background-size: 1.5em 1.5em;
    }
    #reader__camera_selection:focus, #reader__camera_selection:hover {
        border-color: #d1d5db;
        background-color: #ffffff;
    }

    /* 3. THE FILE UPLOAD BIN (Dropzone style) */
    #reader__filescan_input {
        display: block;
        width: 100%;
        padding: 24px 16px;
        background: #f9fafb;
        border: 2px dashed #cbd5e1;
        border-radius: 16px;
        margin-bottom: 16px;
        font-size: 13px;
        font-weight: 600;
        color: #6b7280;
        text-align: center;
        cursor: pointer;
        transition: all 0.2s;
    }
    #reader__filescan_input:hover {
        border-color: #9ca3af;
        background: #f3f4f6;
    }

    /* 3b. The actual "Choose File" button inside the input */
    #reader__filescan_input::file-selector-button {
        background-color: #111827;
        color: white;
        border: none;
        padding: 8px 16px;
        border-radius: 8px;
        font-weight: 800;
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 1px;
        cursor: pointer;
        margin-right: 12px;
        transition: background-color 0.2s;
    }
    #reader__filescan_input::file-selector-button:hover {
        background-color: #374151;
    }

    /* 4. GENERIC / START SCAN BUTTON */
    #reader button {
        width: 100%;
        background: #10b981; /* Default to Start Green */
        color: white;
        border-radius: 12px;
        padding: 14px 16px;
        border: none;
        font-weight: 800;
        font-size: 14px;
        text-transform: uppercase;
        letter-spacing: 1px;
        cursor: pointer;
        transition: all 0.2s;
        box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);
    }
    #reader button:hover { background: #059669; transform: translateY(-1px); }

    /* 5. TARGET SPECIFIC BUTTONS (Stop Scanning & File Action) */
    #html5-qrcode-button-camera-stop {
        background: #ef4444 !important; /* Stop Red */
        margin-top: 12px;
    }
    #html5-qrcode-button-camera-stop:hover { background: #dc2626 !important; }

    #html5-qrcode-button-file-selection {
        background: #111827 !important; /* Scan Image Black */
    }
    #html5-qrcode-button-file-selection:hover { background: #030712 !important; }

    /* The video viewfinder rounding */
    #reader__scan_region {
        border-radius: 16px;
        overflow: hidden;
        background-color: black;
    }
    #reader__scan_region video {
        border-radius: 16px;
    }

    .animate-bounce-short { animation: bounce 0.5s ease-in-out 1; }
</style>
@endpush
