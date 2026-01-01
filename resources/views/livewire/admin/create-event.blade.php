<div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
    <div class="md:grid md:grid-cols-3 md:gap-6">
        
        {{-- LEFT COLUMN: Event Details --}}
        <div class="md:col-span-2 space-y-6">
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                <h3 class="text-lg font-bold text-gray-900 mb-6">Event Details</h3>
                
                {{-- Title --}}
                <div class="mb-4">
                    <label class="block text-sm font-bold text-gray-700 mb-1">Event Title</label>
                    <input wire:model.live="title" type="text" class="w-full rounded-lg border-gray-200 focus:ring-red-500 focus:border-red-500 transition">
                    @error('title') <span class="text-red-500 text-xs font-bold">{{ $message }}</span> @enderror
                </div>

                {{-- Description --}}
                <div class="mb-6">
                    <label class="block text-sm font-bold text-gray-700 mb-1">Description</label>
                    <textarea wire:model="description" rows="10" class="w-full rounded-lg border-gray-200 focus:ring-red-500 focus:border-red-500 transition" placeholder="Event mechanics, details, etc..."></textarea>
                </div>

                {{-- DRAG AND DROP IMAGE UPLOAD --}}
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Cover Poster</label>
                    
                    <div x-data="{ isDropping: false, isUploading: false, progress: 0 }"
                         x-on:livewire-upload-start="isUploading = true"
                         x-on:livewire-upload-finish="isUploading = false"
                         x-on:livewire-upload-error="isUploading = false"
                         x-on:livewire-upload-progress="progress = $event.detail.progress">
                        
                        <label class="relative flex flex-col items-center justify-center w-full h-64 border-2 border-dashed rounded-xl cursor-pointer transition-all duration-300 ease-in-out group overflow-hidden"
                               :class="{ 'border-red-500 bg-red-50 scale-[1.02] shadow-xl': isDropping, 'border-gray-300 bg-gray-50 hover:bg-white hover:border-red-400 hover:shadow-md': !isDropping }"
                               x-on:dragover.prevent="isDropping = true"
                               x-on:dragleave.prevent="isDropping = false"
                               x-on:drop.prevent="isDropping = false">
                            
                            {{-- Image Preview (If Exists) --}}
                            @if ($cover_image)
                                <div class="absolute inset-0 z-10 w-full h-full bg-white">
                                    <img src="{{ $cover_image->temporaryUrl() }}" class="w-full h-full object-cover opacity-90 group-hover:opacity-100 transition">
                                    {{-- Change Overlay --}}
                                    <div class="absolute inset-0 bg-black/50 flex flex-col items-center justify-center opacity-0 group-hover:opacity-100 transition duration-300 backdrop-blur-sm">
                                        <svg class="w-8 h-8 text-white mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                                        <p class="text-white text-xs font-bold uppercase tracking-widest">Click to Change</p>
                                    </div>
                                </div>
                            @endif

                            {{-- Default State (No Image) --}}
                            <div class="flex flex-col items-center justify-center pt-5 pb-6 text-center px-4" :class="{ 'opacity-0': isUploading }">
                                <div class="p-3 bg-white rounded-full shadow-sm mb-3 group-hover:scale-110 transition duration-300">
                                    <svg class="w-8 h-8 text-gray-400 group-hover:text-red-500 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                </div>
                                <p class="mb-1 text-sm text-gray-500 group-hover:text-gray-700 font-medium">
                                    <span class="font-bold text-red-600">Click to upload</span> or drag and drop
                                </p>
                                <p class="text-xs text-gray-400">PNG, JPG, or JPEG (Max. 2MB)</p>
                            </div>

                            {{-- Loading Bar --}}
                            <div x-show="isUploading" class="absolute inset-0 z-20 flex flex-col items-center justify-center bg-white/90 backdrop-blur-sm">
                                <svg class="animate-spin w-10 h-10 text-red-600 mb-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                <div class="w-48 h-2 bg-gray-200 rounded-full overflow-hidden">
                                    <div class="h-full bg-red-600 transition-all duration-300" :style="'width: ' + progress + '%'"></div>
                                </div>
                                <p class="text-xs font-bold text-red-600 mt-2">Uploading... <span x-text="progress + '%'"></span></p>
                            </div>

                            <input type="file" wire:model="cover_image" class="hidden" accept="image/png, image/jpeg, image/jpg" />
                        </label>
                    </div>
                    @error('cover_image') <span class="text-red-500 text-xs font-bold mt-2 block">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>

        {{-- RIGHT COLUMN: Settings & QR Code --}}
        <div class="md:col-span-1 space-y-6">
            
            {{-- Registration Link & Pro QR --}}
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100" 
                 x-data="{ 
                    link: @entangle('registration_link'),
                    qrObject: null,
                    generateQR() {
                        if (!this.link) {
                             this.$refs.qrcodeContainer.innerHTML = '';
                             return;
                        }
                        
                        this.$refs.qrcodeContainer.innerHTML = '';

                        var options = {
                            text: this.link,
                            width: 180, height: 180,
                            colorDark : '#d90429', colorLight : '#ffffff',
                            correctLevel : QRCode.CorrectLevel.H,
                            logo: '{{ asset('images/official_logo.png') }}',
                            logoWidth: 50, logoHeight: 50,
                            dotScale: 0.8
                        };

                        this.qrObject = new QRCode(this.$refs.qrcodeContainer, options);
                    }
                 }" 
                 x-init="$watch('link', () => generateQR())"
                 x-effect="generateQR()">

                <h3 class="text-sm font-bold text-gray-900 uppercase tracking-widest mb-4">Registration</h3>
                
                <div class="mb-4">
                    <label class="block text-xs font-bold text-gray-500 mb-1">Target URL</label>
                    <input x-model="link" type="url" placeholder="https://..." class="w-full rounded-lg border-gray-200 text-sm focus:ring-red-500 focus:border-red-500 transition">
                    @error('registration_link') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>

                {{-- Live QR Preview --}}
                <div class="bg-gray-50 p-6 rounded-xl border border-gray-200 flex flex-col items-center text-center">
                    <div class="bg-white p-3 rounded-2xl shadow-md mb-3" x-show="link">
                        <div x-ref="qrcodeContainer"></div>
                    </div>
                    
                    <div x-show="!link" class="h-40 flex items-center justify-center text-gray-400 text-xs italic">
                        Paste a link above to generate<br>your custom QR code.
                    </div>

                    <div x-show="link" class="mt-2">
                        <p class="text-[10px] font-bold text-gray-400 uppercase">Live Preview</p>
                        <button @click="qrObject.download('event_qr.png')" type="button" class="mt-3 text-xs flex items-center gap-1 text-red-600 font-bold hover:underline transition">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                            Download PNG
                        </button>
                    </div>
                </div>

                <div class="mt-4">
                    <label class="block text-xs font-bold text-gray-500 mb-1">Button Label</label>
                    <input wire:model="registration_button_text" type="text" class="w-full rounded-lg border-gray-200 text-sm focus:ring-red-500 focus:border-red-500">
                </div>
            </div>

            {{-- Timing --}}
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                <h3 class="text-sm font-bold text-gray-900 uppercase tracking-widest mb-4">Schedule</h3>
                
                <div class="space-y-3">
                    <div>
                        <label class="block text-xs font-bold text-gray-500 mb-1">Start Date</label>
                        <input wire:model="start_date" type="datetime-local" class="w-full rounded-lg border-gray-200 text-sm focus:ring-red-500 focus:border-red-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 mb-1">End Date</label>
                        <input wire:model="end_date" type="datetime-local" class="w-full rounded-lg border-gray-200 text-sm focus:ring-red-500 focus:border-red-500">
                    </div>
                    <div class="flex items-center gap-2 pt-2">
                        <input type="checkbox" wire:model="is_active" class="rounded text-red-600 focus:ring-red-500 cursor-pointer">
                        <span class="text-sm font-bold text-gray-700">Publish immediately</span>
                    </div>
                </div>

                <button wire:click="save" class="w-full mt-6 bg-red-600 text-white font-bold py-3 rounded-xl hover:bg-red-700 transition shadow-lg transform hover:-translate-y-0.5">
                    Create Event
                </button>
            </div>

        </div>
    </div>
</div>
