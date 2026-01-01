<div class="min-h-screen bg-stone-50 font-sans text-gray-900 relative overflow-x-hidden">

    {{-- SEO Meta Tags (Keep as is) --}}
    @section('meta_title', $event->title) 
    @section('meta_description', Str::limit(strip_tags($event->description), 150)) 
    @section('meta_image', $event->cover_image ? (Str::startsWith($event->cover_image, 'http') ? $event->cover_image : asset('storage/' . $event->cover_image)) : asset('images/official_logo.png'))

    {{-- 1. BACKGROUND BLOBS (Keep as is) --}}
    <div class="fixed inset-0 z-0 overflow-hidden pointer-events-none">
        <div class="absolute top-0 left-0 w-full h-full bg-gray-50/80"></div>
        <div class="absolute top-[-10%] right-[-10%] w-[500px] h-[500px] bg-red-200 rounded-full mix-blend-multiply filter blur-3xl opacity-40 animate-blob"></div>
        <div class="absolute top-[20%] left-[-10%] w-[500px] h-[500px] bg-yellow-200 rounded-full mix-blend-multiply filter blur-3xl opacity-40 animate-blob animation-delay-2000"></div>
        <div class="absolute bottom-[-10%] right-[20%] w-[500px] h-[500px] bg-green-200 rounded-full mix-blend-multiply filter blur-3xl opacity-40 animate-blob animation-delay-4000"></div>
    </div>

    {{-- 2. MAIN CONTENT --}}
    <div class="relative z-10">

        {{-- HERO SECTION (RESTRUCTURED) --}}
        <header class="relative pt-32 pb-12 px-6 max-w-7xl mx-auto">
            
            {{-- A. Back Button --}}
            <div class="mb-8">
                <a href="{{ route('events.index') }}" class="inline-flex items-center gap-2 text-xs font-bold text-gray-400 hover:text-red-600 uppercase tracking-widest transition">
                    &larr; Back to Events
                </a>
            </div>

            {{-- B. Panoramic Cover Image --}}
            <div class="relative w-full h-128 md:h-96 lg:h-[500px] rounded-[2.5rem] overflow-hidden shadow-2xl border-4 border-white bg-gray-200 group mb-12">
                @if($event->cover_image)
                    <img src="{{ Str::startsWith($event->cover_image, 'http') ? $event->cover_image : asset('storage/'.$event->cover_image) }}" 
                         class="w-full h-full object-cover transform group-hover:scale-105 transition duration-1000">
                @else
                    <div class="flex items-center justify-center h-full text-gray-400 font-bold bg-gray-100 flex-col">
                        <span class="text-6xl mb-4">📅</span>
                        <span class="tracking-widest opacity-50">BU MADYA EVENT</span>
                    </div>
                @endif
                {{-- Gradient Overlay --}}
                <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent pointer-events-none"></div>
                
                {{-- Status Badge (Overlaid on Image) --}}
                <div class="absolute top-6 right-6">
                    @if($event->isOpen())
                        <span class="px-4 py-2 bg-white/90 backdrop-blur text-green-700 text-xs font-black uppercase tracking-widest rounded-full flex items-center gap-2 shadow-lg">
                            <span class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></span> Registration Open
                        </span>
                    @else
                        <span class="px-4 py-2 bg-gray-900/90 backdrop-blur text-white text-xs font-black uppercase tracking-widest rounded-full flex items-center gap-2 shadow-lg">
                            <span class="w-2 h-2 bg-gray-500 rounded-full"></span> Closed
                        </span>
                    @endif
                </div>
            </div>

            {{-- C. Centered Title & Primary Info --}}
            <div class="max-w-4xl mx-auto text-center">
                <h1 class="font-heading text-4xl md:text-6xl lg:text-7xl font-black text-gray-900 leading-tight mb-8 drop-shadow-sm">
                    {{ $event->title }}
                </h1>

                <div class="flex flex-wrap justify-center gap-4 text-stone-600 mb-8">
                    {{-- START BLOCK --}}
                    <div class="flex items-center gap-4 bg-white px-6 py-3 rounded-2xl shadow-sm border border-gray-100 min-w-[200px]">
                        {{-- Icon --}}
                        <div class="w-10 h-10 rounded-full bg-green-50 text-green-600 flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        </div>
                        
                        {{-- Details --}}
                        <div class="text-left">
                            <p class="text-[10px] font-bold uppercase text-gray-400 tracking-wider mb-0.5">Starts</p>
                            <div class="flex flex-col leading-tight">
                                <span class="font-bold text-gray-900 text-sm">
                                    {{ $event->start_date ? $event->start_date->format('F d, Y') : 'TBA' }}
                                </span>
                                <span class="text-xs font-medium text-gray-500">
                                    {{ $event->start_date ? $event->start_date->format('h:i A') : '' }}
                                </span>
                            </div>
                        </div>
                    </div>

                    {{-- END BLOCK (Only show if end_date exists) --}}
                    @if($event->end_date)
                    <div class="flex items-center gap-4 bg-white px-6 py-3 rounded-2xl shadow-sm border border-gray-100 min-w-[200px]">
                        {{-- Icon --}}
                        <div class="w-10 h-10 rounded-full bg-red-50 text-red-600 flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>

                        {{-- Details --}}
                        <div class="text-left">
                            <p class="text-[10px] font-bold uppercase text-gray-400 tracking-wider mb-0.5">Ends</p>
                            <div class="flex flex-col leading-tight">
                                <span class="font-bold text-gray-900 text-sm">
                                    {{ $event->end_date->format('F d, Y') }}
                                </span>
                                <span class="text-xs font-medium text-gray-500">
                                    {{ $event->end_date->format('h:i A') }}
                                </span>
                            </div>
                        </div>
                    </div>
                    @endif

                </div>

                {{-- Primary CTA Button --}}
                @if($event->isOpen() && $event->registration_link)
                    <a href="{{ $event->registration_link }}" target="_blank" class="inline-flex items-center gap-2 px-8 py-4 bg-red-600 hover:bg-red-700 text-white font-bold uppercase tracking-wider rounded-xl shadow-xl hover:shadow-red-500/30 transition-all transform hover:-translate-y-1">
                        <span>{{ $event->registration_button_text ?? 'Register Now' }}</span>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                    </a>
                @endif
            </div>
        </header>

        {{-- MAIN CONTENT GRID --}}
        <div class="max-w-7xl mx-auto px-6 pb-24 grid lg:grid-cols-12 gap-12">
            
            {{-- LEFT COLUMN: Sidebar (QR & Extra Info) --}}
            <aside class="lg:col-span-4 space-y-8 order-2">
                {{-- QR Card with Alpine.js Handler --}}
                @if($event->isOpen() && $event->registration_link)
                <div 
                    x-data="{
                        showQr: false,
                        generate() {
                            this.showQr = true;
                            // Wait for Alpine to render the div before drawing
                            this.$nextTick(() => {
                                const container = this.$refs.qrTarget;
                                // Only generate if empty to prevent duplicates
                                if (container.innerHTML === '') {
                                    try {
                                        new QRCode(container, {
                                            text: '{{ $event->registration_link }}',
                                            width: 220, 
                                            height: 220,
                                            colorDark : '#1f2937', 
                                            colorLight : '#ffffff',
                                            correctLevel : QRCode.CorrectLevel.H,
                                            dotScale: 0.8
                                        });
                                    } catch(e) { console.error(e); }
                                }
                            });
                        }
                    }"
                    class="bg-white/80 backdrop-blur-md p-6 rounded-3xl shadow-sm border border-gray-100 text-center sticky top-24"
                >
                    <h3 class="font-bold text-gray-400 uppercase tracking-widest text-[10px] mb-4">Scan to Register</h3>
                    
                    {{-- QR Container --}}
                    <div class="flex flex-col items-center justify-center min-h-[140px] mb-4">
                        
                        {{-- Target Div (Hidden until clicked) --}}
                        {{-- wire:ignore prevents Livewire from deleting the canvas --}}
                        <div x-show="showQr" x-ref="qrTarget" wire:ignore class="p-2 bg-white rounded-xl shadow-inner border border-gray-100"></div>

                        {{-- Trigger Button (Hidden after click) --}}
                        <button x-show="!showQr" @click="generate()" class="flex flex-col items-center gap-2 group">
                            <div class="w-16 h-16 bg-gray-100 rounded-2xl flex items-center justify-center group-hover:bg-red-50 group-hover:scale-110 transition duration-300 shadow-sm border border-gray-200">
                                <svg class="w-8 h-8 text-gray-400 group-hover:text-red-500 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path></svg>
                            </div>
                            <span class="text-xs font-bold text-gray-500 group-hover:text-red-600 uppercase tracking-wide">Show QR Code</span>
                        </button>

                    </div>

                    <div class="flex justify-center gap-2">
                        <button onclick="navigator.clipboard.writeText('{{ $event->registration_link }}'); alert('Link copied!');" class="text-xs font-bold text-blue-600 bg-blue-50 px-3 py-1 rounded-lg hover:bg-blue-100 transition">
                            Copy Link
                        </button>
                    </div>
                </div>
                @endif
            </aside>

            {{-- RIGHT COLUMN: Description --}}
            <main class="lg:col-span-8 order-1">
                <div class="bg-white/60 backdrop-blur-sm p-8 md:p-12 rounded-[2rem] border border-white/50 shadow-sm">
                    <h3 class="font-bold text-gray-900 uppercase tracking-widest text-sm border-b border-gray-200 pb-4 mb-6">About this Event</h3>
                    <div class="prose prose-lg prose-stone max-w-none 
                        prose-headings:font-heading prose-headings:font-black prose-headings:text-gray-900 
                        prose-a:text-red-600 hover:prose-a:text-red-700
                        prose-img:rounded-3xl prose-img:shadow-xl prose-img:w-full">
                        
                        {!! Str::markdown($event->description) !!}
                        
                    </div>
                </div>
            </main>
        </div>
    </div>
</div>

{{-- QR SCRIPT (Same as before) --}}
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/easyqrcodejs@4.5.0/dist/easy.qrcode.min.js"></script>
@endpush