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
                
                <div class="mb-4">
                    <label class="block text-sm font-bold text-gray-700 mb-1">Event Title</label>
                    <input wire:model="title" type="text" class="w-full rounded-lg border-gray-200 focus:ring-red-500">
                    @error('title') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-bold text-gray-700 mb-1">Description</label>
                    <textarea wire:model="description" rows="10" class="w-full rounded-lg border-gray-200 focus:ring-red-500"></textarea>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Cover Poster</label>
                    <input type="file" wire:model="cover_image" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-red-50 file:text-red-700 hover:file:bg-red-100">
                    
                    {{-- Image Logic: Show New (Temporary) OR Old (Database) --}}
                    @if ($cover_image)
                        <div class="mt-4 relative inline-block">
                            <img src="{{ $cover_image->temporaryUrl() }}" class="h-48 w-full object-cover rounded-xl border-2 border-green-400">
                            <span class="absolute top-2 right-2 bg-green-500 text-white text-xs px-2 py-1 rounded">New</span>
                        </div>
                    @elseif ($old_cover_image)
                        <div class="mt-4 relative inline-block">
                            <img src="{{ asset('storage/'.$old_cover_image) }}" class="h-48 w-full object-cover rounded-xl border border-gray-200">
                            <span class="absolute top-2 right-2 bg-gray-800 text-white text-xs px-2 py-1 rounded">Current</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- RIGHT COLUMN: Settings & QR --}}
        <div class="md:col-span-1 space-y-6">
            
            {{-- Registration & Pro QR (Client Side) --}}
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100"
                 {{-- QR Logic --}}
                 x-data="{ 
                    link: @entangle('registration_link'),
                    qrObject: null,
                    generateQR() {
                        if (!this.link) { 
                            this.$refs.qrcodeContainer.innerHTML = '';
                            return; 
                        }
                        this.$refs.qrcodeContainer.innerHTML = '';
                        new QRCode(this.$refs.qrcodeContainer, {
                            text: this.link,
                            width: 180, height: 180,
                            colorDark : '#d90429', colorLight : '#ffffff',
                            correctLevel : QRCode.CorrectLevel.H,
                            logo: '{{ asset('images/official_logo.png') }}',
                            logoWidth: 50, logoHeight: 50,
                            dotScale: 0.8
                        });
                    }
                 }" 
                 x-init="generateQR(); $watch('link', () => generateQR())"
                 >

                <h3 class="text-sm font-bold text-gray-900 uppercase tracking-widest mb-4">Registration</h3>
                
                <div class="mb-4">
                    <label class="block text-xs font-bold text-gray-500 mb-1">Target URL</label>
                    <input x-model="link" type="url" class="w-full rounded-lg border-gray-200 text-sm focus:ring-red-500 transition">
                </div>

                <div class="bg-gray-50 p-6 rounded-xl border border-gray-200 flex flex-col items-center text-center">
                    <div class="bg-white p-3 rounded-2xl shadow-md mb-3" x-show="link">
                        <div x-ref="qrcodeContainer"></div>
                    </div>
                    
                    <div x-show="!link" class="h-20 flex items-center justify-center text-gray-400 text-xs italic">
                        No link provided
                    </div>
                </div>

                <div class="mt-4">
                    <label class="block text-xs font-bold text-gray-500 mb-1">Button Label</label>
                    <input wire:model="registration_button_text" type="text" class="w-full rounded-lg border-gray-200 text-sm focus:ring-red-500">
                </div>
            </div>

            {{-- Schedule & Publish --}}
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

{{-- Ensure Script is Loaded --}}
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/easyqrcodejs@4.5.0/dist/easy.qrcode.min.js"></script>
@endpush