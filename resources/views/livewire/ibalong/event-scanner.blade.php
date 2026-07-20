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
                <p class="text-xs font-bold text-gray-500 uppercase mt-1">Center the QR code in the frame or upload an image file.</p>
            </div>

            {{-- The Div where the JS Library injects the camera/upload UI --}}
            <div id="qr-reader" class="w-full mx-auto overflow-hidden border-4 border-dashed border-gray-300 dark:border-gray-700 p-2" wire:ignore></div>

            {{-- Dynamic Alert Messages --}}
            @if($lastScanStatus)
                <div class="mt-8 p-6 border-4 border-iba-black dark:border-iba-light text-center {{ $lastScanStatus === 'success' ? 'bg-iba-green text-white shadow-[6px_6px_0_0_#131011] dark:shadow-[6px_6px_0_0_#FFFBF7]' : 'bg-iba-red text-white shadow-[6px_6px_0_0_#131011] dark:shadow-[6px_6px_0_0_#FFFBF7]' }}">
                    <h3 class="font-pixel text-lg sm:text-xl uppercase tracking-widest mb-2">{{ $lastScanMessage }}</h3>

                    @if($scannedRegistrant)
                        <div class="bg-white/20 p-4 mt-4 inline-block text-left">
                            <p class="font-bold text-sm uppercase border-b-2 border-white/30 pb-1 mb-1">Passholder Details</p>
                            <p class="text-lg font-black uppercase">{{ $scannedRegistrant->name }}</p>
                            <p class="text-xs font-bold uppercase tracking-wider">{{ $scannedRegistrant->role }}</p>
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
            // Wait for DOM to be ready
            setTimeout(() => {
                const html5QrCode = new Html5Qrcode("qr-reader");

                // Audio beep for successful scan
                const beep = new Audio('https://www.soundjay.com/buttons/sounds/button-09.mp3');

                const qrCodeSuccessCallback = (decodedText, decodedResult) => {
                    // Pause scanning to prevent spamming the server
                    html5QrCode.pause();
                    beep.play().catch(e => console.log('Audio disabled by browser'));

                    // Send to Livewire component
                    @this.processScan(decodedText).then(() => {
                        // Resume scanning after 2 seconds automatically
                        setTimeout(() => {
                            html5QrCode.resume();
                        }, 2000);
                    });
                };

                const config = { fps: 10, qrbox: { width: 250, height: 250 } };

                // Start the camera (prefer rear camera for mobiles)
                html5QrCode.start({ facingMode: "environment" }, config, qrCodeSuccessCallback)
                .catch((err) => {
                    // Fallback to basic file upload UI if camera is denied/missing
                    console.log("Camera access failed, falling back to basic scanner.", err);
                    const fileScanner = new Html5QrcodeScanner("qr-reader", { fps: 10, qrbox: 250 });
                    fileScanner.render(qrCodeSuccessCallback);
                });
            }, 500);
        });
    </script>

    <style>
        /* Neo-Brutalist overrides for the injected QR library UI */
        #qr-reader img { margin: 0 auto; }
        #qr-reader__dashboard_section_csr button {
            background-color: #0095AC !important;
            color: white !important;
            border: 2px solid #131011 !important;
            font-weight: bold !important;
            text-transform: uppercase !important;
            padding: 8px 16px !important;
            cursor: pointer !important;
            box-shadow: 2px 2px 0 0 #131011 !important;
            border-radius: 0 !important;
        }
        #qr-reader__dashboard_section_csr span a { display: none !important; /* Hide "Scan an Image File" text */ }
    </style>
</div>
