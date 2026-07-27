<div class="bg-iba-light dark:bg-iba-black min-h-screen py-10 px-4 transition-colors duration-300">
    <div class="max-w-3xl mx-auto">

        {{-- Terminal Header --}}
        <div class="bg-iba-black dark:bg-iba-light text-white dark:text-iba-black p-4 border-4 border-iba-black dark:border-iba-light flex justify-between items-center shadow-[6px_6px_0_0_#0095AC]">
            <div>
                <h1 class="font-pixel text-xs sm:text-sm uppercase tracking-widest">TERMINAL: SCANNER</h1>
                <p class="font-bold text-[10px] sm:text-xs mt-1">{{ $event->title }}</p>
            </div>
            <div class="flex items-center gap-2">
                <span class="w-3 h-3 rounded-full bg-iba-green animate-pulse"></span>
                <span class="font-bold text-xs uppercase tracking-wider">ONLINE</span>
            </div>
        </div>

        {{-- Main Scanner Box --}}
        <div class="mt-8 bg-white dark:bg-[#1A1617] border-4 border-iba-black dark:border-iba-light shadow-[8px_8px_0_0_#131011] dark:shadow-[8px_8px_0_0_#FFFBF7] p-4 sm:p-8">

            <div class="text-center mb-6">
                <h2 class="text-xl font-black text-iba-black dark:text-white uppercase">Scan Boarding Pass</h2>
                <p class="text-xs font-bold text-gray-500 uppercase mt-1">Select camera to scan or upload a QR image file.</p>
            </div>

            {{-- The Div where the JS Library injects the camera/upload UI --}}
            <div id="qr-reader" class="w-full mx-auto overflow-hidden border-4 border-dashed border-gray-300 dark:border-gray-700 p-4 bg-gray-50 dark:bg-gray-900" wire:ignore></div>

            {{-- Manual Entry Fallback --}}
            <div class="mt-6 flex flex-col sm:flex-row gap-4 border-t-4 border-dashed border-gray-300 dark:border-gray-700 pt-6">
                <input type="text" wire:model="manualTicketCode" wire:keydown.enter="submitManualCode" placeholder="Enter Ticket Code (e.g., HOI-XXXXX)" class="flex-1 border-4 border-iba-black dark:border-iba-light p-3 text-sm bg-white dark:bg-gray-900 focus:outline-none focus:border-iba-teal text-iba-black dark:text-white font-bold uppercase tracking-widest text-center sm:text-left">

                <button wire:click="submitManualCode" class="bg-iba-orange text-iba-black font-black px-8 py-3 text-sm uppercase border-4 border-iba-black dark:border-iba-light shadow-[4px_4px_0_0_#131011] dark:shadow-[4px_4px_0_0_#FFFBF7] hover:translate-y-0.5 hover:shadow-none transition-all flex items-center justify-center gap-2">
                    <span wire:loading.remove wire:target="submitManualCode">Verify Code</span>
                    <span wire:loading wire:target="submitManualCode">Processing...</span>
                </button>
            </div>
            @error('manualTicketCode') <span class="text-iba-red text-xs font-bold block mt-2 uppercase text-center sm:text-left">⚠ {{ $message }}</span> @enderror

            {{-- Dynamic Alert Messages --}}
            @if($lastScanStatus)
                <div class="mt-8 p-6 border-4 border-iba-black dark:border-iba-light text-center {{ $lastScanStatus === 'success' ? 'bg-iba-green text-white shadow-[6px_6px_0_0_#131011] dark:shadow-[6px_6px_0_0_#FFFBF7]' : 'bg-iba-red text-white shadow-[6px_6px_0_0_#131011] dark:shadow-[6px_6px_0_0_#FFFBF7]' }} animate-fade-in-up">
                    <h3 class="font-pixel text-lg sm:text-xl uppercase tracking-widest mb-2">{{ $lastScanMessage }}</h3>

                    @if($scannedRegistrant)
                        <div class="bg-white/20 p-4 mt-4 inline-block text-left">
                            <p class="font-bold text-sm uppercase border-b-2 border-white/30 pb-1 mb-1">Passholder Details</p>
                            <p class="text-lg font-black uppercase">{{ $scannedRegistrant->name }}</p>
                            <p class="text-xs font-bold uppercase tracking-wider">{{ $scannedRegistrant->role }}</p>
                            <p class="text-[10px] font-bold uppercase tracking-wider text-white/70 mt-2">Code: {{ $scannedRegistrant->ticket_code }}</p>
                        </div>
                    @endif
                </div>
            @endif

        </div>
    </div>

    {{-- Include the HTML5-QRCode Library --}}
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>

    <script>
        document.addEventListener('livewire:initialized', () => {
            setTimeout(() => {
                const beep = new Audio('https://www.soundjay.com/buttons/sounds/button-09.mp3');

                const html5QrcodeScanner = new Html5QrcodeScanner(
                    "qr-reader",
                    { fps: 10, qrbox: { width: 250, height: 250 } },
                    false
                );

                const qrCodeSuccessCallback = (decodedText, decodedResult) => {
                    html5QrcodeScanner.pause(true);
                    beep.play().catch(e => console.log('Audio disabled by browser'));

                    @this.processScan(decodedText).then(() => {
                        setTimeout(() => {
                            html5QrcodeScanner.resume();
                        }, 2500);
                    });
                };

                html5QrcodeScanner.render(qrCodeSuccessCallback);
            }, 500);
        });
    </script>

    <style>
        /* Neo-Brutalist overrides for the injected QR library UI */
        #qr-reader {
            border: none !important;
        }

        #qr-reader img { margin: 0 auto; }

        #qr-reader button {
            background-color: #0095AC !important;
            color: white !important;
            border: 2px solid #131011 !important;
            font-weight: 900 !important;
            text-transform: uppercase !important;
            padding: 10px 20px !important;
            cursor: pointer !important;
            box-shadow: 3px 3px 0 0 #131011 !important;
            border-radius: 0 !important;
            margin: 10px 5px !important;
            transition: all 0.2s;
        }

        #qr-reader button:hover {
            transform: translateY(2px);
            box-shadow: none !important;
        }

        #qr-reader__dashboard_section_swaplink {
            color: #FF8623 !important;
            font-weight: 900 !important;
            text-transform: uppercase !important;
            text-decoration: none !important;
            letter-spacing: 1px !important;
            display: inline-block !important;
            margin: 15px 0 !important;
            border-bottom: 2px dashed #FF8623 !important;
            transition: color 0.2s;
        }

        #qr-reader__dashboard_section_swaplink:hover {
            color: #CF452C !important;
            border-color: #CF452C !important;
        }

        #qr-reader__dashboard_section_fsr input[type="file"] {
            background: #ffffff !important;
            border: 2px solid #131011 !important;
            padding: 8px !important;
            font-weight: bold !important;
            margin-top: 10px !important;
            width: 100%;
            max-width: 300px;
            color: #131011 !important;
            cursor: pointer;
        }

        #qr-reader__dashboard_section_csr span {
            font-family: ui-sans-serif, system-ui, -apple-system, sans-serif !important;
            font-weight: bold !important;
            color: #4b5563 !important;
        }
    </style>
</div>
