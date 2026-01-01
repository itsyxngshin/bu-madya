<div class="min-h-screen bg-stone-50 font-sans text-gray-900 relative selection:bg-red-200 selection:text-red-900">

    {{-- SEO --}}
    @section('meta_title', $event->title)
    @section('meta_description', Str::limit(strip_tags($event->description), 150))

    {{-- 1. BACKGROUND TEXTURE (Global Grain) --}}
    <div class="fixed inset-0 z-0 pointer-events-none opacity-[0.03]" 
         style="background-image: url('data:image/svg+xml,%3Csvg viewBox=%220 0 200 200%22 xmlns=%22http://www.w3.org/2000/svg%22%3E%3Cfilter id=%22noiseFilter%22%3E%3CfeTurbulence type=%22fractalNoise%22 baseFrequency=%220.65%22 numOctaves=%223%22 stitchTiles=%22stitch%22/%3E%3C/filter%3E%3Crect width=%22100%25%22 height=%22100%25%22 filter=%22url(%23noiseFilter)%22/%3E%3C/svg%3E');">
    </div>

    {{-- 2. HERO COVER SECTION --}}
    <header class="relative w-full h-[60vh] min-h-[500px] bg-gray-900 overflow-hidden group">
        
        {{-- Cover Image --}}
        @if($event->cover_image)
            <img src="{{ asset('storage/'.$event->cover_image) }}" class="absolute inset-0 w-full h-full object-cover transform group-hover:scale-105 transition duration-[2000ms] ease-out opacity-80">
        @else
            {{-- Fallback Abstract Pattern --}}
            <div class="absolute inset-0 w-full h-full bg-gray-800 opacity-50" style="background-image: radial-gradient(#333 1px, transparent 1px); background-size: 30px 30px;"></div>
        @endif

        {{-- Gradient Overlay --}}
        <div class="absolute inset-0 bg-gradient-to-t from-stone-900 via-stone-900/60 to-transparent"></div>
        
        {{-- Navigation (Floating) --}}
        <div class="absolute top-24 left-0 right-0 z-20 px-6 max-w-7xl mx-auto flex justify-between items-start">
            <a href="{{ route('events.index') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-black/30 backdrop-blur-md border border-white/10 text-white/80 hover:text-white hover:bg-black/50 transition text-xs font-bold uppercase tracking-widest">
                &larr; All Events
            </a>

            {{-- ADMIN EDIT BUTTON (Uses Slug) --}}
            @auth
                @if(Auth::user()->role && in_array(Auth::user()->role->role_name, ['administrator', 'director']))
                    <a href="{{ route('admin.events.edit', $event->slug) }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-yellow-400 text-yellow-900 hover:bg-yellow-300 transition text-xs font-bold uppercase tracking-widest shadow-lg">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                        Edit Event
                    </a>
                @endif
            @endauth
        </div>

        {{-- Hero Content --}}
        <div class="absolute bottom-0 left-0 w-full z-10 px-6 pb-12 sm:pb-16 pt-32 bg-gradient-to-t from-stone-900 to-transparent">
            <div class="max-w-7xl mx-auto">
                {{-- Badges --}}
                <div class="flex flex-wrap items-center gap-3 mb-6">
                    @if($event->isOpen())
                        <span class="px-3 py-1 bg-green-500/20 backdrop-blur-md border border-green-500/30 text-green-300 text-[10px] font-black uppercase tracking-widest rounded-full flex items-center gap-2 shadow-sm">
                            <span class="w-2 h-2 bg-green-400 rounded-full animate-pulse shadow-[0_0_10px_rgba(74,222,128,0.5)]"></span> Open
                        </span>
                    @else
                        <span class="px-3 py-1 bg-red-500/20 backdrop-blur-md border border-red-500/30 text-red-300 text-[10px] font-black uppercase tracking-widest rounded-full flex items-center gap-2">
                            <span class="w-2 h-2 bg-red-400 rounded-full"></span> Closed
                        </span>
                    @endif
                    <span class="text-white/60 text-[10px] font-bold uppercase tracking-widest pl-2 border-l border-white/20">
                        Event / Campaign
                    </span>
                </div>

                {{-- Title --}}
                <h1 class="font-heading font-black text-4xl md:text-6xl lg:text-7xl text-white leading-[1.1] mb-6 drop-shadow-xl max-w-4xl">
                    {{ $event->title }}
                </h1>

                {{-- Quick Info --}}
                <div class="flex flex-wrap gap-8 text-white/80">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-white/10 backdrop-blur border border-white/10 flex items-center justify-center text-yellow-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold uppercase text-white/40 tracking-wider">Date</p>
                            <p class="font-bold text-sm text-white">
                                {{ $event->start_date ? $event->start_date->format('F d, Y') : 'TBA' }}
                            </p>
                        </div>
                    </div>
                    {{-- Desktop Primary CTA --}}
                    @if($event->isOpen() && $event->registration_link)
                    <div class="hidden md:flex items-center">
                        <a href="{{ $event->registration_link }}" target="_blank" class="group relative px-8 py-3 bg-red-600 hover:bg-red-700 text-white font-bold uppercase tracking-wider rounded-xl shadow-lg hover:shadow-red-600/40 transition-all transform hover:-translate-y-1 overflow-hidden">
                            <span class="relative z-10 flex items-center gap-2">
                                {{ $event->registration_button_text ?? 'Register Now' }}
                                <svg class="w-4 h-4 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                            </span>
                            <div class="absolute inset-0 bg-gradient-to-r from-red-600 via-orange-500 to-red-600 opacity-0 group-hover:opacity-100 transition duration-300"></div>
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </header>

    {{-- 3. MAIN CONTENT GRID --}}
    <div class="relative z-10 max-w-7xl mx-auto px-6 py-16 grid lg:grid-cols-12 gap-12">
        
        {{-- LEFT COLUMN: Details Sidebar --}}
        <aside class="lg:col-span-4 space-y-8 order-2 lg:order-1">
            
            {{-- Info Card --}}
            <div class="bg-white p-8 rounded-[2rem] shadow-xl shadow-gray-200/50 border border-gray-100 relative overflow-hidden group">
                {{-- Decorative Blob --}}
                <div class="absolute -top-10 -right-10 w-32 h-32 bg-yellow-50 rounded-full blur-2xl group-hover:bg-yellow-100 transition duration-700"></div>
                
                <h3 class="font-bold text-gray-900 uppercase tracking-widest text-xs border-b border-gray-100 pb-4 mb-6 relative z-10">
                    Event Details
                </h3>
                
                <ul class="space-y-6 relative z-10">
                    <li class="flex items-start gap-4">
                        <div class="mt-1 text-gray-300"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg></div>
                        <div>
                            <span class="block text-[10px] font-bold text-gray-400 uppercase tracking-wide">Time</span>
                            <span class="text-sm font-bold text-gray-800">
                                @if($event->start_date && $event->end_date)
                                    {{ $event->start_date->format('h:i A') }} - {{ $event->end_date->format('h:i A') }}
                                @elseif($event->start_date)
                                    Starts @ {{ $event->start_date->format('h:i A') }}
                                @else
                                    See details
                                @endif
                            </span>
                        </div>
                    </li>
                    <li class="flex items-start gap-4">
                        <div class="mt-1 text-gray-300"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg></div>
                        <div>
                            <span class="block text-[10px] font-bold text-gray-400 uppercase tracking-wide">Status</span>
                            <span class="text-sm font-bold {{ $event->isOpen() ? 'text-green-600' : 'text-red-600' }}">
                                {{ $event->isOpen() ? 'Open for Registration' : 'Registration Closed' }}
                            </span>
                        </div>
                    </li>
                </ul>

                {{-- Sidebar CTA --}}
                @if($event->isOpen() && $event->registration_link)
                <div class="mt-8 pt-6 border-t border-gray-100">
                    <a href="{{ $event->registration_link }}" target="_blank" class="w-full flex items-center justify-center gap-2 px-4 py-4 bg-gray-900 hover:bg-black text-white text-xs font-bold uppercase tracking-widest rounded-xl transition-all shadow-lg hover:shadow-xl hover:-translate-y-0.5">
                        {{ $event->registration_button_text ?? 'Register Now' }}
                    </a>
                </div>
                @endif
            </div>

            {{-- Share via QR Card --}}
            <div class="bg-white p-8 rounded-[2rem] shadow-sm border border-gray-100 text-center relative overflow-hidden">
                <h3 class="font-bold text-gray-400 uppercase tracking-widest text-[10px] mb-6">Share Event</h3>
                
                <div class="flex justify-center mb-6 relative z-10">
                    <div id="share-qr" class="p-3 bg-white rounded-2xl shadow-[inset_0_2px_4px_rgba(0,0,0,0.06)] border border-gray-100"></div>
                </div>
                
                <button onclick="navigator.clipboard.writeText('{{ url()->current() }}'); this.innerText = 'Copied!';" class="text-[10px] font-bold text-blue-600 bg-blue-50 px-4 py-2 rounded-lg hover:bg-blue-100 transition uppercase tracking-wide">
                    Copy Link
                </button>
            </div>

        </aside>

        {{-- RIGHT COLUMN: Main Narrative --}}
        <main class="lg:col-span-8 space-y-12 order-1 lg:order-2">
            
            {{-- Content --}}
            <div class="prose prose-lg prose-stone max-w-none 
                prose-headings:font-heading prose-headings:font-black prose-headings:text-gray-900 
                prose-p:text-gray-600 prose-p:leading-relaxed 
                prose-a:text-red-600 prose-a:font-bold hover:prose-a:text-red-700
                prose-img:rounded-3xl prose-img:shadow-lg prose-img:border prose-img:border-gray-100">
                {!! $event->description !!}
            </div>

            {{-- Bottom Call to Action --}}
            @if($event->isOpen() && $event->registration_link)
            <div class="mt-16 bg-gradient-to-br from-gray-900 to-gray-800 text-white rounded-[2.5rem] p-10 md:p-16 text-center relative overflow-hidden shadow-2xl">
                {{-- Decorative Blobs --}}
                <div class="absolute top-0 left-0 w-64 h-64 bg-red-600 rounded-full blur-[120px] opacity-30 -ml-20 -mt-20"></div>
                <div class="absolute bottom-0 right-0 w-64 h-64 bg-yellow-500 rounded-full blur-[120px] opacity-20 -mr-20 -mb-20"></div>
                
                <div class="relative z-10">
                    <h3 class="font-heading text-3xl md:text-4xl font-black mb-6">Ready to Join?</h3>
                    <p class="text-white/70 mb-10 max-w-xl mx-auto text-lg leading-relaxed">
                        Don't miss this opportunity. Secure your spot and be part of the movement today.
                    </p>
                    <a href="{{ $event->registration_link }}" target="_blank" class="inline-flex items-center gap-3 px-10 py-5 bg-white text-gray-900 font-black uppercase tracking-widest rounded-2xl hover:scale-105 transition-all shadow-xl hover:shadow-white/20">
                        {{ $event->registration_button_text ?? 'Register Now' }}
                    </a>
                </div>
            </div>
            @endif

        </main>
    </div>

    {{-- STICKY MOBILE CTA BAR (Bottom) --}}
    @if($event->isOpen() && $event->registration_link)
    <div class="fixed bottom-0 left-0 right-0 p-4 bg-white/90 backdrop-blur-lg border-t border-gray-200 shadow-[0_-10px_40px_-15px_rgba(0,0,0,0.1)] md:hidden z-50">
        <a href="{{ $event->registration_link }}" target="_blank" class="block w-full text-center px-6 py-4 bg-gradient-to-r from-red-600 to-red-500 text-white font-black uppercase tracking-widest rounded-xl shadow-lg active:scale-95 transition-transform">
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
                width: 140,
                height: 140,
                colorDark : "#1f2937",
                colorLight : "#ffffff",
                correctLevel : QRCode.CorrectLevel.H,
                logo: "{{ asset('images/official_logo.png') }}",
                logoWidth: 35,
                logoHeight: 35,
                dotScale: 0.8,
                quietZone: 5,
            });
        }
    }
</script>
@endpush