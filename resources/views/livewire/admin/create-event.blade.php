<div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
    <div class="md:grid md:grid-cols-3 md:gap-6">
        
        {{-- LEFT COLUMN: Event Details --}}
        <div class="md:col-span-2 space-y-6">
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                <h3 class="text-lg font-bold text-gray-900 mb-6">Event Details</h3>
                
                {{-- Title --}}
                <div class="mb-4">
                    <label class="block text-sm font-bold text-gray-700 mb-1">Event Title</label>
                    <input wire:model.live="title" type="text" class="w-full rounded-lg border-gray-200 focus:ring-red-500">
                    @error('title') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                {{-- Rich Text Editor (Trix or Simple Textarea for now) --}}
                <div class="mb-4">
                    <label class="block text-sm font-bold text-gray-700 mb-1">Description</label>
                    <textarea wire:model="description" rows="10" class="w-full rounded-lg border-gray-200 focus:ring-red-500" placeholder="Event mechanics, details, etc..."></textarea>
                </div>

                {{-- Image Upload --}}
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Cover Poster</label>
                    <input type="file" wire:model="cover_image" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-red-50 file:text-red-700 hover:file:bg-red-100">
                    @if ($cover_image)
                        <img src="{{ $cover_image->temporaryUrl() }}" class="mt-4 h-48 w-full object-cover rounded-xl">
                    @endif
                </div>
            </div>
        </div>

        {{-- RIGHT COLUMN: Settings & QR Code --}}
        <div class="md:col-span-1 space-y-6">
            
            {{-- Registration Link & QR --}}
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                <h3 class="text-sm font-bold text-gray-900 uppercase tracking-widest mb-4">Registration</h3>
                
                {{-- Link Input --}}
                <div class="mb-4">
                    <label class="block text-xs font-bold text-gray-500 mb-1">Target URL (Google Form/Link)</label>
                    <input wire:model.live="registration_link" type="url" placeholder="https://forms.google.com/..." class="w-full rounded-lg border-gray-200 text-sm focus:ring-red-500">
                    @error('registration_link') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                {{-- Registration Link & Pro QR --}}
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100" 
                    x-data="{ 
                        link: @entangle('registration_link'),
                        qrObject: null,
                        generateQR() {
                            if (!this.link) return;
                            
                            // Clear previous QR
                            this.$refs.qrcodeContainer.innerHTML = '';

                            // Options for a Beautiful QR
                            var options = {
                                text: this.link,
                                width: 180,
                                height: 180,
                                colorDark : '#d90429', // Your Brand Red
                                colorLight : '#ffffff',
                                correctLevel : QRCode.CorrectLevel.H, // High error correction for logos
                                
                                // LOGO CONFIGURATION
                                logo: '{{ asset('images/official_logo.png') }}', // Your Logo Path
                                logoWidth: 50,
                                logoHeight: 50,
                                logoBackgroundColor: '#ffffff',
                                logoBackgroundTransparent: false,

                                // VISUAL STYLE
                                dotScale: 0.8, // Makes dots slightly rounded/smaller for a modern look
                                quietZone: 10,
                                title: 'SCAN ME', // Optional Title
                                titleFont: 'bold 12px Arial',
                                titleColor: '#d90429',
                                titleBackgroundColor: '#ffffff',
                                titleHeight: 30,
                                titleTop: 10
                            };

                            // Generate
                            this.qrObject = new QRCode(this.$refs.qrcodeContainer, options);
                        }
                    }" 
                    x-init="$watch('link', () => generateQR())"
                    x-effect="generateQR()">

                    <h3 class="text-sm font-bold text-gray-900 uppercase tracking-widest mb-4">Registration</h3>
                    
                    {{-- Link Input --}}
                    <div class="mb-4">
                        <label class="block text-xs font-bold text-gray-500 mb-1">Target URL</label>
                        <input x-model="link" type="url" placeholder="https://..." class="w-full rounded-lg border-gray-200 text-sm focus:ring-red-500 transition">
                    </div>

                    {{-- Live QR Preview --}}
                    <div class="bg-gray-50 p-6 rounded-xl border border-gray-200 flex flex-col items-center text-center">
                        
                        {{-- The QR Container --}}
                        <div class="bg-white p-3 rounded-2xl shadow-md mb-3" x-show="link">
                            <div x-ref="qrcodeContainer"></div>
                        </div>
                        
                        {{-- Empty State --}}
                        <div x-show="!link" class="h-40 flex items-center justify-center text-gray-400 text-xs italic">
                            Paste a link above to generate<br>your custom QR code.
                        </div>

                        <div x-show="link" class="mt-2">
                            <p class="text-[10px] font-bold text-gray-400 uppercase">Live Preview</p>
                            <button @click="qrObject.download('event_qr.png')" type="button" class="mt-3 text-xs flex items-center gap-1 text-red-600 font-bold hover:underline">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                Download PNG
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Button Text --}}
                <div class="mt-4">
                    <label class="block text-xs font-bold text-gray-500 mb-1">Button Label</label>
                    <input wire:model="registration_button_text" type="text" class="w-full rounded-lg border-gray-200 text-sm focus:ring-red-500">
                </div>
            </div>

            {{-- Timing --}}
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                <h3 class="text-sm font-bold text-gray-900 uppercase tracking-widest mb-4">Schedule</h3>
                
                <div class="space-y-3">
                    <div>
                        <label class="block text-xs font-bold text-gray-500 mb-1">Start Date</label>
                        <input wire:model="start_date" type="datetime-local" class="w-full rounded-lg border-gray-200 text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 mb-1">End Date</label>
                        <input wire:model="end_date" type="datetime-local" class="w-full rounded-lg border-gray-200 text-sm">
                    </div>
                    <div class="flex items-center gap-2 pt-2">
                        <input type="checkbox" wire:model="is_active" class="rounded text-red-600 focus:ring-red-500">
                        <span class="text-sm font-bold text-gray-700">Publish immediately</span>
                    </div>
                </div>

                <button wire:click="save" class="w-full mt-6 bg-red-600 text-white font-bold py-3 rounded-xl hover:bg-red-700 transition shadow-lg">
                    Create Event
                </button>
            </div>

        </div>
    </div>
</div>