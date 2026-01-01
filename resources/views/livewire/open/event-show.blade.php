
@section('meta_title', $event->title) 
@section('meta_description', Str::limit(strip_tags($event->description), 150)) 
@php
    // 1. Determine the image URL using PHP logic
    $ogImage = $event->cover_image
        ? (Str::startsWith($event->cover_image, 'http') ? $event->cover_image : asset('storage/' . $event->cover_image))
        : asset('images/official_logo.png');
@endphp

{{-- 2. Pass the CLEAN variable to the layout --}}
@section('meta_image', $ogImage)

<div class="min-h-screen bg-stone-50 font-sans text-gray-900">

    {{-- SEO Meta Tags (Optional: You can use a package like 'artesaos/seotools' later) --}}


    {{-- 1. HERO SECTION --}}
    <header class="relative pt-32 pb-16 px-6 max-w-7xl mx-auto">
        <div class="grid lg:grid-cols-2 gap-12 items-center">
            
            {{-- Text Content --}}
            <div class="order-2 lg:order-1">
                {{-- Status Badge --}}
                <div class="mb-6 flex items-center gap-3">
                    @if($event->isOpen())
                        <span class="px-3 py-1 bg-green-100 text-green-700 text-xs font-black uppercase tracking-widest rounded-full flex items-center gap-2 shadow-sm border border-green-200">
                            <span class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></span> Registration Open
                        </span>
                    @else
                        <span class="px-3 py-1 bg-gray-100 text-gray-500 text-xs font-black uppercase tracking-widest rounded-full flex items-center gap-2 border border-gray-200">
                            <span class="w-2 h-2 bg-gray-400 rounded-full"></span> Closed
                        </span>
                    @endif
                    
                    <a href="{{ route('events.index') }}" class="text-xs font-bold text-gray-400 hover:text-red-600 uppercase tracking-widest transition">
                        &larr; Back to Events
                    </a>
                </div>
                
                <h1 class="font-heading text-4xl md:text-6xl font-black text-gray-900 leading-[1.1] mb-6">
                    {{ $event->title }}
                </h1>
                
                {{-- Date & Venue Quick Look --}}
                <div class="flex flex-wrap gap-6 text-stone-600 mb-8">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-red-50 text-red-600 flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold uppercase text-gray-400 tracking-wider">Date</p>
                            <p class="font-bold text-sm text-gray-900">
                                {{ $event->start_date ? $event->start_date->format('F d, Y') : 'TBA' }}
                            </p>
                        </div>
                    </div>

                    {{-- Primary CTA (Desktop) --}}
                    @if($event->isOpen() && $event->registration_link)
                    <div class="hidden md:block pl-6 border-l border-gray-200">
                        <a href="{{ $event->registration_link }}" target="_blank" class="inline-flex items-center gap-2 px-6 py-3 bg-red-600 hover:bg-red-700 text-white font-bold uppercase tracking-wider rounded-xl shadow-lg hover:shadow-red-500/30 transition-all transform hover:-translate-y-1">
                            <span>{{ $event->registration_button_text ?? 'Register Now' }}</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                        </a>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Cover Image --}}
            <div class="order-1 lg:order-2 relative group">
                <div class="absolute inset-0 bg-yellow-400 rounded-[2.5rem] rotate-3 opacity-20 group-hover:rotate-6 transition duration-500"></div>
                <div class="relative overflow-hidden rounded-[2.5rem] shadow-2xl aspect-video border-4 border-white bg-gray-200">
                    @if($event->cover_image)
                        <img src="{{ asset('storage/'.$event->cover_image) }}" class="w-full h-full object-cover transform group-hover:scale-105 transition duration-700">
                    @else
                        <div class="flex items-center justify-center h-full text-gray-400 font-bold bg-gray-100 flex-col">
                            <span class="text-4xl mb-2">📅</span>
                            <span>BU MADYA EVENT</span>
                        </div>
                    @endif
                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent pointer-events-none"></div>
                </div>
            </div>
        </div>
    </header>

    {{-- 2. MAIN CONTENT GRID --}}
    <div class="max-w-7xl mx-auto px-6 pb-24 grid lg:grid-cols-12 gap-12">
        
        {{-- LEFT COLUMN: Details Sidebar --}}
        <aside class="lg:col-span-4 space-y-8 order-2 lg:order-1">
            
            {{-- Event Details Card --}}
            <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-20 h-20 bg-red-50 rounded-full -mr-10 -mt-10"></div>
                
                <h3 class="font-bold text-gray-900 uppercase tracking-widest text-xs border-b border-gray-100 pb-3 mb-4 relative z-10">
                    Event Details
                </h3>
                
                <ul class="space-y-5 relative z-10">
                    {{-- Schedule --}}
                    <li class="flex items-start gap-4">
                        <div class="mt-1 text-gray-400"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg></div>
                        <div>
                            <span class="block text-[10px] font-bold text-gray-400 uppercase">Time & Duration</span>
                            <span class="text-sm font-bold text-gray-800">
                                @if($event->start_date && $event->end_date)
                                    {{ $event->start_date->format('h:i A') }} - {{ $event->end_date->format('h:i A') }}
                                @elseif($event->start_date)
                                    Starts at {{ $event->start_date->format('h:i A') }}
                                @else
                                    See details
                                @endif
                            </span>
                        </div>
                    </li>

                    {{-- Status --}}
                    <li class="flex items-start gap-4">
                        <div class="mt-1 text-gray-400"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg></div>
                        <div>
                            <span class="block text-[10px] font-bold text-gray-400 uppercase">Status</span>
                            <span class="text-sm font-bold {{ $event->isOpen() ? 'text-green-600' : 'text-red-600' }}">
                                {{ $event->isOpen() ? 'Accepting Responses' : 'Closed' }}
                            </span>
                        </div>
                    </li>
                </ul>

                {{-- Sidebar CTA --}}
                @if($event->isOpen() && $event->registration_link)
                <div class="mt-8 pt-6 border-t border-gray-100">
                    <a href="{{ $event->registration_link }}" target="_blank" class="w-full flex items-center justify-center gap-2 px-4 py-3 bg-gray-900 hover:bg-black text-white text-xs font-bold uppercase tracking-widest rounded-xl transition-all shadow-md hover:shadow-lg">
                        {{ $event->registration_button_text ?? 'Register Now' }}
                    </a>
                    <p class="text-center text-[10px] text-gray-400 mt-2 font-medium">
                        Opens in a new tab
                    </p>
                </div>
                @endif
            </div>

            {{-- Share via QR Card --}}
            <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 text-center">
                <h3 class="font-bold text-gray-400 uppercase tracking-widest text-[10px] mb-4">Share Event</h3>
                
                <div class="flex justify-center mb-4">
                    {{-- The container for the JS QR Code --}}
                    <div id="share-qr" class="p-2 bg-white rounded-xl shadow-inner border border-gray-100"></div>
                </div>
                
                <div class="flex justify-center gap-2">
                    <button onclick="navigator.clipboard.writeText('{{ url()->current() }}'); alert('Link copied to clipboard!');" class="text-xs font-bold text-blue-600 bg-blue-50 px-3 py-1 rounded-lg hover:bg-blue-100 transition">
                        Copy Link
                    </button>
                </div>
            </div>

        </aside>

        {{-- RIGHT COLUMN: Main Narrative --}}
        <main class="lg:col-span-8 space-y-12 order-1 lg:order-2">
            
            {{-- Content --}}
            <div class="prose prose-lg prose-stone max-w-none prose-headings:font-heading prose-headings:font-black prose-a:text-red-600 hover:prose-a:text-red-700">
                {!! $event->description !!}
            </div>

            {{-- Bottom Call to Action (Repeat) --}}
            @if($event->isOpen() && $event->registration_link)
            <div class="mt-12 bg-gradient-to-br from-gray-900 to-gray-800 text-white rounded-[2rem] p-8 md:p-12 text-center relative overflow-hidden shadow-2xl">
                {{-- Decor --}}
                <div class="absolute top-0 left-0 w-64 h-64 bg-red-600 rounded-full blur-[100px] opacity-20 -ml-20 -mt-20"></div>
                <div class="absolute bottom-0 right-0 w-64 h-64 bg-yellow-500 rounded-full blur-[100px] opacity-20 -mr-20 -mb-20"></div>
                
                <div class="relative z-10">
                    <h3 class="font-heading text-2xl md:text-3xl font-black mb-4">Don't Miss Out!</h3>
                    <p class="text-gray-300 mb-8 max-w-xl mx-auto">
                        Opportunities like this don't last forever. Join us and be part of the movement.
                    </p>
                    <a href="{{ $event->registration_link }}" target="_blank" class="inline-flex items-center gap-3 px-8 py-4 bg-white text-gray-900 font-black uppercase tracking-widest rounded-xl hover:bg-gray-100 hover:scale-105 transition-all shadow-lg">
                        {{ $event->registration_button_text ?? 'Register Now' }}
                    </a>
                </div>
            </div>
            @endif

        </main>
    </div>

    {{-- STICKY MOBILE CTA BAR (Bottom) --}}
    @if($event->isOpen() && $event->registration_link)
    <div class="fixed bottom-0 left-0 right-0 p-4 bg-white border-t border-gray-200 shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.1)] md:hidden z-50">
        <a href="{{ $event->registration_link }}" target="_blank" class="block w-full text-center px-6 py-3 bg-red-600 text-white font-bold uppercase rounded-lg shadow-md active:bg-red-700">
            {{ $event->registration_button_text ?? 'Register Now' }}
        </a>
    </div>
    @endif

</div>

{{-- SCRIPT: Generate Public Share QR Code --}}
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/easyqrcodejs@4.5.0/dist/easy.qrcode.min.js"></script>
<script>
    document.addEventListener('livewire:navigated', () => {
        initShareQR(); 
    });
    
    // Also run on initial load
    window.addEventListener('load', initShareQR);

    function initShareQR() {
        var container = document.getElementById('share-qr');
        // Prevent duplicate QRs if function runs twice
        if(container && container.innerHTML === '') {
            new QRCode(container, {
                text: "{{ route('events.show', $event->slug) }}",
                width: 120,
                height: 120,
                colorDark : "#1f2937",
                colorLight : "#ffffff",
                correctLevel : QRCode.CorrectLevel.H,
                logo: "{{ asset('images/official_logo.png') }}",
                logoWidth: 30,
                logoHeight: 30,
                dotScale: 0.8 // Modern rounded dots
            });
        }
    }
</script>
@endpush