<div class="min-h-screen bg-stone-50 font-sans text-gray-900 pb-20">

    {{-- SEO --}}
    @section('meta_title', $event->title) 
    @section('meta_description', Str::limit(strip_tags($event->description), 150)) 
    @section('meta_image', $event->cover_image ? asset('storage/'.$event->cover_image) : asset('images/official_logo.png'))

    {{-- 1. HERO SECTION (Full Width Cinematic) --}}
    <div class="relative w-full h-[60vh] min-h-[450px] bg-gray-900 group overflow-hidden">
        
        {{-- Cover Image --}}
        @if($event->cover_image)
            <img src="{{ Str::startsWith($event->cover_image, 'http') ? $event->cover_image : asset('storage/'.$event->cover_image) }}" 
                 class="absolute inset-0 w-full h-full object-cover opacity-60 group-hover:scale-105 transition duration-[2000ms] ease-out">
        @else
            <div class="absolute inset-0 w-full h-full bg-gradient-to-br from-gray-800 to-gray-900 opacity-60"></div>
        @endif
        
        <div class="absolute inset-0 bg-gradient-to-t from-stone-900 via-transparent to-stone-900/50"></div>

        {{-- Navigation --}}
        <div class="absolute top-0 left-0 right-0 p-6 flex justify-between items-start z-20">
            <a href="{{ route('events.index') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-black/20 backdrop-blur-md border border-white/20 text-white/90 hover:bg-black/40 transition text-xs font-bold uppercase tracking-widest">
                &larr; Back
            </a>
            @auth
                @if(Auth::user()->role && in_array(Auth::user()->role->role_name, ['administrator', 'director']))
                    <a href="{{ route('admin.events.edit', $event->id) }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-yellow-400 text-yellow-900 hover:bg-yellow-300 transition text-xs font-bold uppercase tracking-widest shadow-lg">
                        Edit
                    </a>
                @endif
            @endauth
        </div>

        {{-- Hero Content --}}
        <div class="absolute bottom-0 left-0 w-full p-6 md:p-12 z-20">
            <div class="max-w-7xl mx-auto">
                <div class="mb-4">
                    @if($event->isOpen())
                        <span class="inline-flex items-center gap-2 px-3 py-1 bg-green-500/80 backdrop-blur text-white text-[10px] font-black uppercase tracking-widest rounded-full shadow-lg border border-green-400/50">
                            <span class="w-2 h-2 bg-white rounded-full animate-pulse"></span> Registration Open
                        </span>
                    @else
                        <span class="inline-flex items-center gap-2 px-3 py-1 bg-red-600/80 backdrop-blur text-white text-[10px] font-black uppercase tracking-widest rounded-full shadow-lg">
                            <span class="w-2 h-2 bg-white rounded-full"></span> Closed
                        </span>
                    @endif
                </div>

                <h1 class="font-heading font-black text-4xl md:text-6xl lg:text-7xl text-white leading-tight mb-4 drop-shadow-xl max-w-4xl">
                    {{ $event->title }}
                </h1>

                {{-- Hero Date (Simplified) --}}
                <div class="flex flex-wrap items-center gap-6 text-white/90 text-sm font-bold">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        <span>{{ $event->start_date ? $event->start_date->format('F d, Y') : 'TBA' }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- 2. MAIN CONTENT --}}
    <div class="max-w-7xl mx-auto px-6 py-12 grid lg:grid-cols-12 gap-12 -mt-12 relative z-30">
        
        {{-- LEFT SIDEBAR --}}
        <aside class="lg:col-span-4 space-y-6">
            
            {{-- Details Card --}}
            <div class="bg-white p-6 rounded-3xl shadow-xl border border-gray-100">
                <h3 class="font-bold text-gray-900 uppercase tracking-widest text-xs border-b border-gray-100 pb-3 mb-4">
                    Event Details
                </h3>

                <ul class="space-y-4 mb-6">
                    {{-- Start Time --}}
                    <li class="flex items-start gap-4">
                        <div class="mt-1 text-gray-400"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg></div>
                        <div>
                            <span class="block text-[10px] font-bold text-gray-400 uppercase">Starts</span>
                            <span class="text-sm font-bold text-gray-800">
                                {{ $event->start_date ? $event->start_date->format('M d, Y • h:i A') : 'TBA' }}
                            </span>
                        </div>
                    </li>

                    {{-- End Time --}}
                    @if($event->end_date)
                    <li class="flex items-start gap-4">
                        <div class="mt-1 text-gray-400"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg></div>
                        <div>
                            <span class="block text-[10px] font-bold text-gray-400 uppercase">Ends</span>
                            <span class="text-sm font-bold text-gray-800">
                                {{ $event->end_date->format('M d, Y • h:i A') }}
                            </span>
                        </div>
                    </li>
                    @endif
                </ul>

                @if($event->isOpen() && $event->registration_link)
                    <a href="{{ $event->registration_link }}" target="_blank" class="w-full flex items-center justify-center gap-2 px-4 py-4 bg-red-600 hover:bg-red-700 text-white text-sm font-bold uppercase tracking-widest rounded-xl transition-all shadow-lg hover:shadow-red-500/30 mb-6 group">
                        {{ $event->registration_button_text ?? 'Register Now' }}
                        <svg class="w-4 h-4 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </a>
                
                    {{-- QR Code (Registration) --}}
                    <div class="text-center pt-4 border-t border-gray-100">
                        <p class="text-[10px] font-bold text-gray-400 uppercase mb-3">Scan to Register</p>
                        <div class="flex justify-center mb-3">
                            <div id="share-qr" class="bg-white rounded-xl p-2 border border-gray-100 w-[140px] h-[140px] flex items-center justify-center"></div>
                        </div>
                        <button onclick="navigator.clipboard.writeText('{{ $event->registration_link }}'); alert('Registration Link Copied!');" class="text-xs font-bold text-blue-600 bg-blue-50 px-3 py-1 rounded-lg hover:bg-blue-100 transition">
                            Copy Link
                        </button>
                    </div>
                @endif
            </div>
        </aside>

        {{-- RIGHT CONTENT --}}
        <main class="lg:col-span-8 space-y-12 pt-4">
            <div class="prose prose-lg prose-stone max-w-none 
                prose-headings:font-heading prose-headings:font-black prose-headings:text-gray-900 
                prose-p:text-gray-600 prose-p:leading-relaxed 
                prose-a:text-red-600 prose-a:font-bold hover:prose-a:text-red-700
                prose-img:rounded-2xl prose-img:shadow-lg prose-img:w-full prose-img:object-cover">
                
                {!! Str::markdown($event->description) !!}
                
            </div>
        </main>
    </div>

    {{-- STICKY MOBILE CTA --}}
    @if($event->isOpen() && $event->registration_link)
    <div class="fixed bottom-0 left-0 right-0 p-4 bg-white/90 backdrop-blur-lg border-t border-gray-200 shadow-2xl md:hidden z-50">
        <a href="{{ $event->registration_link }}" target="_blank" class="block w-full text-center px-6 py-4 bg-red-600 text-white font-bold uppercase rounded-xl shadow-lg active:scale-95 transition-transform">
            {{ $event->registration_button_text ?? 'Register Now' }}
        </a>
    </div>
    @endif

</div>

{{-- SCRIPT: QR Code --}}
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/easyqrcodejs@4.5.0/dist/easy.qrcode.min.js"></script>
<script>
    document.addEventListener('livewire:navigated', initShareQR);
    window.addEventListener('load', initShareQR);

    function initShareQR() {
        var container = document.getElementById('share-qr');
        // 1. Check if container exists (only exists if registration link exists)
        // 2. Check if empty to prevent duplicates
        if(container && container.innerHTML.trim() === '') {
            
            // 3. IMPORTANT: Use the REGISTRATION LINK, not the current URL
            var qrText = "{{ $event->registration_link }}";

            // Fallback if link is somehow empty
            if(!qrText) return;

            try {
                new QRCode(container, {
                    text: qrText,
                    width: 120, height: 120,
                    colorDark : "#1f2937", colorLight : "#ffffff",
                    correctLevel : QRCode.CorrectLevel.H,
                    dotScale: 0.8,
                    // Try adding logo, fallback handles errors internally in library usually, 
                });
            } catch(e) {
                console.error("QR Generation Error:", e);
            }
        }
    }
</script>
@endpush 