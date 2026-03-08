@section('meta_title', '[EVENT]' . $event->title)
@section('meta_description', $event->description ? Str::limit(strip_tags($event->description), 160) : 'Join us for an unforgettable experience at our upcoming event! Discover inspiring speakers, engaging activities, and valuable networking opportunities. Don\'t miss out on this chance to connect and grow. Register now!')
@php
    // 1. Determine the image URL using PHP logic
    $ogImage = $event->cover_image
        ? (Str::startsWith($event->cover_image, 'http') ? $event->cover_image : asset('storage/' . $event->cover_image))
        : asset('images/MADYA Web Logo1.png');
@endphp

{{-- 2. Pass the CLEAN variable to the layout --}}
@section('meta_image', $ogImage)

<div class="min-h-screen bg-stone-50 font-sans text-gray-900 relative overflow-x-hidden">
    {{-- 1. BACKGROUND BLOBS (Keep as is) --}}
    <div class="fixed inset-0 z-0 overflow-hidden pointer-events-none">
        <div class="absolute top-0 left-0 w-full h-full bg-gray-50/80"></div>
        <div class="absolute top-[-10%] right-[-10%] w-[500px] h-[500px] bg-red-200 rounded-full mix-blend-multiply filter blur-3xl opacity-40 animate-blob"></div>
        <div class="absolute top-[20%] left-[-10%] w-[500px] h-[500px] bg-yellow-200 rounded-full mix-blend-multiply filter blur-3xl opacity-40 animate-blob animation-delay-2000"></div>
        <div class="absolute bottom-[-10%] right-[20%] w-[500px] h-[500px] bg-green-200 rounded-full mix-blend-multiply filter blur-3xl opacity-40 animate-blob animation-delay-4000"></div>
    </div>

                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                 
    {{-- 2. MAIN CONTENT --}}
    <div class="relative z-10">

        {{-- HERO SECTION --}}
        <header class="relative pt-24 md:pt-32 pb-8 md:pb-12 px-6 max-w-7xl mx-auto">

            {{-- A. Back Button --}}
            <div class="mb-6 md:mb-8">
                <a href="{{ route('events.index') }}" class="inline-flex items-center gap-2 text-[10px] md:text-xs font-bold text-gray-400 hover:text-red-600 uppercase tracking-widest transition">
                    &larr; Back to Events
                </a>
            </div>

            {{-- B. Panoramic Cover Image --}}
            <div class="relative w-full h-64 md:h-96 lg:h-[500px] rounded-2xl md:rounded-[2.5rem] overflow-hidden shadow-2xl border-2 md:border-4 border-white bg-gray-200 group mb-8 md:mb-12">
                @if($event->cover_image)
                    <img src="{{ Str::startsWith($event->cover_image, 'http') ? $event->cover_image : asset('storage/'.$event->cover_image) }}"
                         class="w-full h-full object-cover transform group-hover:scale-105 transition duration-1000">
                @else
                    <div class="flex items-center justify-center h-full text-gray-400 font-bold bg-gray-100 flex-col">
                        <span class="text-4xl md:text-6xl mb-2 md:mb-4">📅</span>
                        <span class="tracking-widest opacity-50 text-xs md:text-base">BU MADYA EVENT</span>
                    </div>
                @endif
                <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent pointer-events-none"></div>

                <div class="absolute top-4 right-4 md:top-6 md:right-6">
                    @if($event->isOpen())
                        <span class="px-3 py-1.5 md:px-4 md:py-2 bg-white/90 backdrop-blur text-green-700 text-[10px] md:text-xs font-black uppercase tracking-widest rounded-full flex items-center gap-2 shadow-lg">
                            <span class="w-1.5 h-1.5 md:w-2 md:h-2 bg-green-500 rounded-full animate-pulse"></span> Open
                        </span>
                    @else
                        <span class="px-3 py-1.5 md:px-4 md:py-2 bg-gray-900/90 backdrop-blur text-white text-[10px] md:text-xs font-black uppercase tracking-widest rounded-full flex items-center gap-2 shadow-lg">
                            <span class="w-1.5 h-1.5 md:w-2 md:h-2 bg-gray-500 rounded-full"></span> Closed
                        </span>
                    @endif
                </div>
            </div>

            {{-- C. Centered Title & Primary Info --}}
            <div class="max-w-4xl mx-auto text-center">
                {{-- UPDATED: Smaller font size on mobile (text-3xl) --}}
                <h1 class="font-heading text-3xl md:text-6xl lg:text-7xl font-black text-gray-900 leading-tight mb-6 md:mb-8 drop-shadow-sm">
                    {{ $event->title }}
                </h1>

                <div class="flex flex-wrap justify-center gap-3 md:gap-4 text-stone-600 mb-6 md:mb-8">
                    {{-- START BLOCK --}}
                    <div class="flex items-center gap-3 md:gap-4 bg-white px-4 py-2 md:px-6 md:py-3 rounded-xl md:rounded-2xl shadow-sm border border-gray-100 min-w-[160px] md:min-w-[200px]">
                        <div class="w-8 h-8 md:w-10 md:h-10 rounded-full bg-green-50 text-green-600 flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4 md:w-5 md:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        </div>
                        <div class="text-left">
                            <p class="text-[8px] md:text-[10px] font-bold uppercase text-gray-400 tracking-wider mb-0.5">Starts</p>
                            <div class="flex flex-col leading-tight">
                                <span class="font-bold text-gray-900 text-xs md:text-sm">
                                    {{ $event->start_date ? $event->start_date->format('F d, Y') : 'TBA' }}
                                </span>
                                <span class="text-[10px] md:text-xs font-medium text-gray-500">
                                    {{ $event->start_date ? $event->start_date->format('h:i A') : '' }}
                                </span>
                            </div>
                        </div>
                    </div>

                    {{-- END BLOCK --}}
                    @if($event->end_date)
                    <div class="flex items-center gap-3 md:gap-4 bg-white px-4 py-2 md:px-6 md:py-3 rounded-xl md:rounded-2xl shadow-sm border border-gray-100 min-w-[160px] md:min-w-[200px]">
                        <div class="w-8 h-8 md:w-10 md:h-10 rounded-full bg-red-50 text-red-600 flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4 md:w-5 md:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <div class="text-left">
                            <p class="text-[8px] md:text-[10px] font-bold uppercase text-gray-400 tracking-wider mb-0.5">Ends</p>
                            <div class="flex flex-col leading-tight">
                                <span class="font-bold text-gray-900 text-xs md:text-sm">
                                    {{ $event->end_date->format('F d, Y') }}
                                </span>
                                <span class="text-[10px] md:text-xs font-medium text-gray-500">
                                    {{ $event->end_date->format('h:i A') }}
                                </span>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>

                {{-- Primary CTA Button --}}
                @if($event->isOpen())
                    {{-- 1. NEW: Internal Luma-Style RSVP --}}
                    @if($event->is_internal_rsvp)
                        <a href="{{ route('events.rsvp', $event->slug) }}" class="inline-flex items-center gap-2 px-6 py-3 md:px-8 md:py-4 bg-red-600 hover:bg-red-700 text-white font-bold uppercase tracking-wider rounded-xl shadow-xl hover:shadow-red-500/30 transition-all transform hover:-translate-y-1 text-xs md:text-sm">
                            <span>Get Ticket</span>
                            <svg class="w-4 h-4 md:w-5 md:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path></svg>
                        </a>

                    {{-- 2. OLD: External Link --}}
                    @elseif($event->registration_link)
                        <a href="{{ $event->registration_link }}" target="_blank" class="inline-flex items-center gap-2 px-6 py-3 md:px-8 md:py-4 bg-red-600 hover:bg-red-700 text-white font-bold uppercase tracking-wider rounded-xl shadow-xl hover:shadow-red-500/30 transition-all transform hover:-translate-y-1 text-xs md:text-sm">
                            <span>{{ $event->registration_button_text ?? 'Register Now' }}</span>
                            <svg class="w-4 h-4 md:w-5 md:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                        </a>
                    @endif
                @endif
            </div>
        </header>

        {{-- MAIN CONTENT GRID --}}
        <div class="max-w-7xl mx-auto px-6 pb-24 grid lg:grid-cols-12 gap-8 md:gap-12">

            {{-- LEFT COLUMN: Sidebar --}}
            <aside class="lg:col-span-4 space-y-8 order-2">

                {{-- Only show if the event is open AND it has some form of registration enabled --}}
                @if($event->isOpen() && ($event->registration_link || $event->is_internal_rsvp))

                @php
                    // Determine the correct target URL based on the mode
                    $targetUrl = $event->is_internal_rsvp
                        ? route('events.rsvp', $event->slug)
                        : $event->registration_link;
                @endphp

                <div
                    x-data="{
                        showQr: false,
                        targetUrl: '{{ $targetUrl }}',
                        generate() {
                            this.showQr = true;
                            this.$nextTick(() => {
                                const container = this.$refs.qrTarget;
                                if (container.innerHTML === '') {
                                    try {
                                        new QRCode(container, {
                                            text: this.targetUrl,
                                            width: 200,
                                            height: 200,
                                            colorDark : '#1f2937',
                                            colorLight : '#ffffff',
                                            correctLevel : QRCode.CorrectLevel.H,
                                            dotScale: 0.8
                                        });
                                    } catch(e) { console.error(e); }
                                }
                            });
                        },
                        copyLink() {
                            navigator.clipboard.writeText(this.targetUrl);
                            alert('Link copied to clipboard!');
                        }
                    }"
                    class="bg-white/80 backdrop-blur-md p-6 rounded-3xl shadow-sm border border-gray-100 text-center sticky top-24"
                >
                    <h3 class="font-bold text-gray-400 uppercase tracking-widest text-[10px] mb-4">Scan to Register</h3>

                    <div class="flex flex-col items-center justify-center min-h-[140px] mb-4">
                        <div x-show="showQr" x-ref="qrTarget" wire:ignore class="p-2 bg-white rounded-xl shadow-inner border border-gray-100"></div>

                        <button x-show="!showQr" @click="generate()" class="flex flex-col items-center gap-2 group">
                            <div class="w-14 h-14 md:w-16 md:h-16 bg-gray-100 rounded-2xl flex items-center justify-center group-hover:bg-red-50 group-hover:scale-110 transition duration-300 shadow-sm border border-gray-200">
                                <svg class="w-6 h-6 md:w-8 md:h-8 text-gray-400 group-hover:text-red-500 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path></svg>
                            </div>
                            <span class="text-[10px] md:text-xs font-bold text-gray-500 group-hover:text-red-600 uppercase tracking-wide">Show QR Code</span>
                        </button>
                    </div>

                    <div class="flex justify-center gap-2">
                        <button @click="copyLink()" class="text-[10px] md:text-xs font-bold text-blue-600 bg-blue-50 px-3 py-1 rounded-lg hover:bg-blue-100 transition">
                            Copy Link
                        </button>
                    </div>
                </div>
                @endif
            </aside>

            {{-- RIGHT COLUMN: Description --}}
            <main class="lg:col-span-8 order-1">
                <div class="bg-white/60 backdrop-blur-sm p-6 md:p-12 rounded-[1.5rem] md:rounded-[2rem] border border-white/50 shadow-sm">
                    <h3 class="font-bold text-gray-900 uppercase tracking-widest text-[10px] md:text-sm border-b border-gray-200 pb-3 md:pb-4 mb-4 md:mb-6">About this Event</h3>

                    {{-- UPDATED: prose-sm on mobile, prose-lg on desktop --}}
                    <div class="prose prose-sm md:prose-lg prose-stone max-w-none
                        prose-headings:font-heading prose-headings:font-black prose-headings:text-gray-900
                        prose-a:text-red-600 hover:prose-a:text-red-700
                        prose-img:rounded-2xl md:prose-img:rounded-3xl prose-img:shadow-xl prose-img:w-full">

                        {!! Str::markdown($event->description) !!}

                    </div>
                </div>
            </main>
        </div>
    </div>
    <footer class="bg-gray-900 text-white pt-20 pb-10 border-t-8 border-red-600 relative z-20">
        <div class="max-w-[1800px] w-[95%] mx-auto px-6 grid md:grid-cols-4 gap-12 mb-16">
            
            <div class="col-span-1 md:col-span-2">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 bg-red-600 text-white rounded-full flex items-center justify-center shadow-[0_0_15px_rgba(220,38,38,0.5)]">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.879 16.121A3 3 0 1012.015 11L11 14H9c0 .768.293 1.536.879 2.121z"></path></svg>
                    </div>
                    <span class="font-heading font-bold text-2xl tracking-tight">BU MADYA</span>
                </div>
                <p class="text-gray-400 leading-relaxed max-w-sm mb-6 text-sm">
                    The Bicol University - Movement for the Advancement of Youth-led Advocacy is a duly-accredited University Based Organization in Bicol University committed to service and reaching communities through advocacy.
                </p>
                
                {{-- Social Media Links --}}
                <div class="flex space-x-4">
                    {{-- Facebook --}}
                    <a href="https://www.facebook.com/BUMadya" class="w-10 h-10 rounded-full bg-gray-800 flex items-center justify-center hover:bg-blue-600 hover:text-white text-gray-400 transition">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                    </a>

                    {{-- Instagram --}}
                    <a href="https://www.instagram.com/bu_madya" class="w-10 h-10 rounded-full bg-gray-800 flex items-center justify-center hover:bg-pink-600 hover:text-white text-gray-400 transition">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                    </a>

                    {{-- X (Twitter) --}}
                    <a href="https://www.x.com/bu_madya" class="w-10 h-10 rounded-full bg-gray-800 flex items-center justify-center hover:bg-black hover:text-white text-gray-400 transition">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                    </a>
                </div>
            </div>
            
            <ul class="space-y-3 text-gray-400 text-sm">
                    <li><a href="{{ route('about') }}" class="hover:text-white hover:translate-x-1 transition inline-block">About BU MADYA</a></li>
                    <li><a href="{{ route('open.directory') }}" class="hover:text-white hover:translate-x-1 transition inline-block">Our Officers</a></li>
                    <li><a href="{{ route('transparency.index') }}" class="hover:text-white hover:translate-x-1 transition inline-block">Transparency Board</a></li>
                    <li class="pt-2 mt-2 border-t border-gray-800">
                        <a href="{{ route('privacy') }}" class="text-xs text-gray-500 hover:text-white hover:translate-x-1 transition inline-block">Privacy Policy</a>
                    </li>
            </ul>

            <div>
                <h4 class="font-bold text-lg mb-6 text-green-500 uppercase tracking-widest text-xs">Live Stats</h4>
                <div class="bg-gray-800 p-6 rounded-2xl border border-gray-700 shadow-inner">
                    <span class="block text-[10px] uppercase tracking-widest text-gray-500 mb-2">Total Visitors</span>
                    <div class="text-4xl font-mono text-yellow-400 tracking-widest">
                        {{ str_pad($visitorCount ?? 0, 7, '0', STR_PAD_LEFT) }}
                    </div>
                </div>
            </div>
        </div>
        <div class="border-t border-gray-800 pt-8 text-center text-gray-600 text-xs uppercase tracking-widest">
            &copy; {{ date('Y') }} BU MADYA. All Rights Reserved.
        </div>
    </footer>
</div>

{{-- QR SCRIPT (Same as before) --}}
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/easyqrcodejs@4.5.0/dist/easy.qrcode.min.js"></script>
@endpush
