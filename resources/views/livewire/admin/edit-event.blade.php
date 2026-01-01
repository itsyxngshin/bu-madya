<div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
    
    {{-- Header --}}
    <div class="flex items-center gap-4 mb-6">
        <a href="{{ route('admin.events.index') }}" class="text-gray-400 hover:text-red-600 transition font-bold text-sm">
            &larr; Cancel
        </a>
        <h2 class="font-bold text-2xl text-gray-800">Edit Event</h2>
    </div>

    <div class="md:grid md:grid-cols-3 md:gap-6">
        
        {{-- LEFT COLUMN: Details --}}
        <div class="md:col-span-2 space-y-6">
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                <h3 class="text-lg font-bold text-gray-900 mb-6">Event Details</h3>
                
                {{-- Title --}}
                <div class="mb-4">
                    <label class="block text-sm font-bold text-gray-700 mb-1">Event Title</label>
                    <input wire:model.live="title" type="text" class="w-full rounded-lg border-gray-200 focus:ring-red-500 focus:border-red-500 transition font-bold text-lg">
                    @error('title') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                {{-- Slug Input --}}
                <div class="mb-6">
                    <label class="block text-sm font-bold text-gray-700 mb-1">Slug (URL)</label>
                    <div class="flex rounded-lg shadow-sm">
                        <span class="inline-flex items-center px-3 rounded-l-lg border border-r-0 border-gray-200 bg-gray-50 text-gray-500 text-xs font-bold uppercase tracking-wider">
                            /events/
                        </span>
                        <input wire:model="slug" type="text" class="flex-1 min-w-0 block w-full px-3 py-2 rounded-none rounded-r-lg border-gray-200 focus:ring-red-500 focus:border-red-500 text-sm text-gray-600 bg-white">
                    </div>
                    @error('slug') <span class="text-red-500 text-xs font-bold">{{ $message }}</span> @enderror
                </div>

                {{-- MARKDOWN EDITOR --}}
                <div class="mb-6">
                    <label class="block text-sm font-bold text-gray-700 mb-2">Description</label>
                    
                    <div class="relative border border-gray-200 rounded-xl overflow-hidden focus-within:ring-2 focus-within:ring-red-500 focus-within:border-transparent transition bg-white"
                         x-data="{ 
                            insert(start, end) {
                                let el = $refs.editor;
                                if(!el) return;
                                let text = el.value;
                                let s = el.selectionStart;
                                let e = el.selectionEnd;
                                el.value = text.substring(0, s) + start + text.substring(s, e) + end + text.substring(e);
                                el.dispatchEvent(new Event('input'));
                                setTimeout(() => { el.focus(); el.setSelectionRange(s + start.length, e + start.length); }, 50);
                            }
                         }"
                         x-on:photo-inserted.window="insert('\n<figure>\n  <img src=\'' + $event.detail.url + '\' alt=\'Image\'>\n  <figcaption>Caption...</figcaption>\n</figure>\n', '');"
                    >
                        {{-- Hidden File Input for Toolbar --}}
                        <input type="file" wire:model="photo_upload" class="hidden" x-ref="photoUploader" accept="image/*">

                        {{-- TOOLBAR --}}
                        <div class="flex items-center gap-1 bg-gray-50 border-b border-gray-200 p-2 overflow-x-auto">
                            <button @click="insert('**', '**')" class="p-1.5 text-gray-500 hover:text-gray-900 hover:bg-gray-200 rounded transition font-bold text-xs w-8" title="Bold">B</button>
                            <button @click="insert('*', '*')" class="p-1.5 text-gray-500 hover:text-gray-900 hover:bg-gray-200 rounded transition italic text-xs w-8" title="Italic">I</button>
                            <button @click="insert('~~', '~~')" class="p-1.5 text-gray-500 hover:text-gray-900 hover:bg-gray-200 rounded transition line-through text-xs w-8" title="Strike">S</button>
                            <div class="w-px h-4 bg-gray-300 mx-1"></div>
                            <button @click="insert('### ', '')" class="p-1.5 text-gray-500 hover:text-gray-900 hover:bg-gray-200 rounded text-xs font-bold w-8">H3</button>
                            <button @click="insert('> ', '')" class="p-1.5 text-gray-500 hover:text-gray-900 hover:bg-gray-200 rounded w-8" title="Quote">&ldquo;</button>
                            <button @click="insert('[', '](http://)')" class="p-1.5 text-gray-500 hover:text-gray-900 hover:bg-gray-200 rounded transition w-8 flex justify-center" title="Link">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path></svg>
                            </button>
                            <div class="w-px h-4 bg-gray-300 mx-1"></div>
                            <button @click="insert('- ', '')" class="p-1.5 text-gray-500 hover:text-gray-900 hover:bg-gray-200 rounded transition w-8 flex justify-center" title="Bullet List"><svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg></button>
                            <button @click="insert('1. ', '')" class="p-1.5 text-gray-500 hover:text-gray-900 hover:bg-gray-200 rounded transition w-8 flex justify-center" title="Numbered List"><svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h12M7 12h12M7 17h12M3 7h.01M3 12h.01M3 17h.01"></path></svg></button>
                            <div class="w-px h-4 bg-gray-300 mx-1"></div>
                            <button @click="$refs.photoUploader.click()" class="p-1.5 hover:bg-gray-200 rounded text-xs font-bold relative group w-8 flex justify-center text-gray-500 hover:text-red-600 transition" title="Insert Image">
                                <span wire:loading.remove wire:target="photo_upload"><svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg></span>
                                <span wire:loading wire:target="photo_upload" class="animate-spin block w-3 h-3 border-2 border-gray-300 border-t-red-600 rounded-full"></span>
                            </button>
                        </div>

                        <textarea x-ref="editor" wire:model.live="description" rows="15" class="w-full text-sm leading-relaxed text-gray-700 bg-transparent border-none p-4 focus:ring-0 resize-y font-sans placeholder-gray-300" placeholder="Start writing..."></textarea>
                    </div>
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
                            
                            {{-- Image Preview Logic: New Upload OR Existing DB Image --}}
                            @if ($cover_image)
                                {{-- Case 1: New Upload --}}
                                <div class="absolute inset-0 z-10 w-full h-full bg-white">
                                    <img src="{{ $cover_image->temporaryUrl() }}" class="w-full h-full object-cover opacity-90 group-hover:opacity-100 transition">
                                    <div class="absolute inset-0 bg-black/50 flex flex-col items-center justify-center opacity-0 group-hover:opacity-100 transition duration-300 backdrop-blur-sm">
                                        <p class="text-white text-xs font-bold uppercase tracking-widest">New Image Selected</p>
                                    </div>
                                </div>
                            @elseif($event->cover_image)
                                {{-- Case 2: Existing Database Image --}}
                                <div class="absolute inset-0 z-10 w-full h-full bg-white">
                                    <img src="{{ asset('storage/'.$event->cover_image) }}" class="w-full h-full object-cover opacity-90 group-hover:opacity-100 transition">
                                    <div class="absolute inset-0 bg-black/50 flex flex-col items-center justify-center opacity-0 group-hover:opacity-100 transition duration-300 backdrop-blur-sm">
                                        <svg class="w-8 h-8 text-white mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                                        <p class="text-white text-xs font-bold uppercase tracking-widest">Click to Change</p>
                                    </div>
                                </div>
                            @endif

                            {{-- Default State (Hidden if image exists) --}}
                            <div class="flex flex-col items-center justify-center pt-5 pb-6 text-center px-4 {{ ($cover_image || $event->cover_image) ? 'hidden' : '' }}" :class="{ 'opacity-0': isUploading }">
                                <div class="p-3 bg-white rounded-full shadow-sm mb-3 group-hover:scale-110 transition duration-300">
                                    <svg class="w-8 h-8 text-gray-400 group-hover:text-red-500 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                </div>
                                <p class="mb-1 text-sm text-gray-500 group-hover:text-gray-700 font-medium">
                                    <span class="font-bold text-red-600">Click to upload</span> or drag and drop
                                </p>
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

        {{-- RIGHT COLUMN: Settings & QR --}}
        <div class="md:col-span-1 space-y-6">
            
            {{-- Registration & Pro QR (Client Side) --}}
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100"
                x-data="{ 
                    link: @entangle('registration_link'),
                    qrObject: null,
                    generateQR() {
                        // Wait for next tick to ensure DOM element exists
                        this.$nextTick(() => {
                            const container = this.$refs.qrcodeContainer;
                            if (!container) return;

                            // Clear previous QR
                            container.innerHTML = '';

                            if (!this.link) return;

                            // Generate New QR
                            try {
                                this.qrObject = new QRCode(container, {
                                    text: this.link,
                                    width: 180,
                                    height: 180,
                                    colorDark : '#d90429',
                                    colorLight : '#ffffff',
                                    correctLevel : QRCode.CorrectLevel.H,
                                    // Remove logo momentarily if it causes issues, or ensure path is correct
                                    logo: '{{ asset('images/official_logo.png') }}', 
                                    logoWidth: 50,
                                    logoHeight: 50,
                                    dotScale: 0.8
                                });
                            } catch (e) {
                                console.error('QR Generation Failed', e);
                            }
                        });
                    }
                }" 
                x-init="generateQR(); $watch('link', () => generateQR())"
                >

                <h3 class="text-sm font-bold text-gray-900 uppercase tracking-widest mb-4">Registration</h3>
                
                <div class="mb-4">
                    <label class="block text-xs font-bold text-gray-500 mb-1">Target URL</label>
                    <input x-model="link" type="url" class="w-full rounded-lg border-gray-200 text-sm focus:ring-red-500 transition" placeholder="https://...">
                </div>

                <div class="bg-gray-50 p-6 rounded-xl border border-gray-200 flex flex-col items-center text-center">
                    {{-- QR Container --}}
                    <div class="bg-white p-3 rounded-2xl shadow-md mb-3" x-show="link" x-cloak>
                        <div x-ref="qrcodeContainer"></div>
                    </div>
                    
                    {{-- Empty State --}}
                    <div x-show="!link" class="h-40 flex items-center justify-center text-gray-400 text-xs italic" x-cloak>
                        Paste a link above to generate<br>your custom QR code.
                    </div>

                    {{-- Download Button --}}
                    <div x-show="link" class="mt-2" x-cloak>
                        <p class="text-[10px] font-bold text-gray-400 uppercase">Live Preview</p>
                        <button @click="qrObject._el.querySelector('img').src && ((link = document.createElement('a')), (link.href = qrObject._el.querySelector('img').src), (link.download = 'event_qr.png'), link.click())" 
                                type="button" 
                                class="mt-3 text-xs flex items-center gap-1 text-red-600 font-bold hover:underline transition">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                            Download PNG
                        </button>
                    </div>
                </div>

                <div class="mt-4">
                    <label class="block text-xs font-bold text-gray-500 mb-1">Button Label</label>
                    <input wire:model="registration_button_text" type="text" class="w-full rounded-lg border-gray-200 text-sm focus:ring-red-500">
                </div>
            </div>

            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                <h3 class="text-sm font-bold text-gray-900 uppercase tracking-widest mb-4">Schedule</h3>
                <div class="space-y-3">
                    <div>
                        <label class="block text-xs font-bold text-gray-500 mb-1">Start Date</label>
                        <input wire:model="start_date" type="datetime-local" class="w-full rounded-lg border-gray-200 text-sm focus:ring-red-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 mb-1">End Date</label>
                        <input wire:model="end_date" type="datetime-local" class="w-full rounded-lg border-gray-200 text-sm focus:ring-red-500">
                    </div>
                    <div class="flex items-center gap-2 pt-2">
                        <input type="checkbox" wire:model="is_active" class="rounded text-red-600 focus:ring-red-500">
                        <span class="text-sm font-bold text-gray-700">Publish Event</span>
                    </div>
                </div>
                <button wire:click="update" class="w-full mt-6 bg-red-600 text-white font-bold py-3 rounded-xl hover:bg-red-700 transition shadow-lg">
                    Update Event
                </button>
            </div>
        </div>
    </div>
</div>