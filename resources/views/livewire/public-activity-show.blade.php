@section('meta_title', '[ACTIVITY] ' . $activity->title)
@section('meta_description', $activity->description ? Str::limit(strip_tags($activity->description), 160) : 'Explore our latest youth-led activities, partnerships, and advocacy-driven initiatives contributing to sustainable development.')

@php
    // Safely grab the first highlight photo, or fall back to the default logo
    $ogImage = (!empty($activity->highlight_photos) && count($activity->highlight_photos) > 0)
        ? (Str::startsWith($activity->highlight_photos[0], 'http') ? $activity->highlight_photos[0] : asset('storage/' . $activity->highlight_photos[0]))
        : asset('images/MADYA Web Logo1.png');
@endphp

@section('meta_image', $ogImage)
<div>
    {{-- 1. MAIN CONTENT COMPONENT --}}
    <main class="w-full px-4 sm:px-6 lg:px-8 py-8 lg:py-12 animate-fade-in-up">

        {{-- Breadcrumb Navigation (Centered via max-w) --}}
        <div class="mb-6 max-w-[1800px] mx-auto">
            <a href="{{ route('activities.index') }}" class="inline-flex items-center gap-2 text-[10px] sm:text-xs font-bold text-gray-500 uppercase tracking-widest hover:text-red-600 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg> Back to Feed
            </a>
        </div>

        {{-- Activity Card (Full Width) --}}
        <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden w-full">

            {{-- Hero Photo Gallery Container --}}
            <div class="w-full relative bg-gray-900 overflow-hidden" style="max-height: 500px;">
                @if(!empty($activity->highlight_photos) && count($activity->highlight_photos) > 0)

                    {{-- ALPINE CAROUSEL WRAPPER --}}
                    <div x-data="{ activeSlide: 0, slides: {{ count($activity->highlight_photos) }} }" class="w-full h-full relative group">

                        {{-- Images Track --}}
                        <div class="w-full h-full flex transition-transform duration-500 ease-out"
                            :style="'transform: translateX(-' + (activeSlide * 100) + '%)'">
                            @foreach($activity->highlight_photos as $photo)
                                <div class="shrink-0 w-full h-[300px] sm:h-[400px] md:h-[500px] relative">
                                    <img src="{{ asset('storage/' . $photo) }}" class="w-full h-full object-cover opacity-90" alt="Highlight Photo">
                                    <div class="absolute inset-0 bg-gradient-to-t from-gray-900 via-gray-900/40 to-transparent"></div>
                                </div>
                            @endforeach
                        </div>

                        @if(count($activity->highlight_photos) > 1)
                            {{-- Desktop Navigation Arrows (Visible on Hover) --}}
                            <button @click="activeSlide = activeSlide === 0 ? slides - 1 : activeSlide - 1"
                                    class="absolute left-4 sm:left-6 top-1/2 -translate-y-1/2 w-10 h-10 bg-black/40 hover:bg-red-600 text-white rounded-full flex items-center justify-center backdrop-blur-sm transition-all opacity-0 group-hover:opacity-100 z-20 shadow-lg">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                            </button>

                            <button @click="activeSlide = activeSlide === slides - 1 ? 0 : activeSlide + 1"
                                    class="absolute right-4 sm:right-6 top-1/2 -translate-y-1/2 w-10 h-10 bg-black/40 hover:bg-red-600 text-white rounded-full flex items-center justify-center backdrop-blur-sm transition-all opacity-0 group-hover:opacity-100 z-20 shadow-lg">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                            </button>

                            {{-- Pagination Dots --}}
                            <div class="absolute top-6 right-6 flex gap-1.5 z-20">
                                <template x-for="i in slides" :key="i">
                                    <button @click="activeSlide = i - 1"
                                            :class="activeSlide === i - 1 ? 'w-6 bg-red-600 shadow-[0_0_8px_rgba(220,38,38,0.8)]' : 'w-2 bg-white/50 hover:bg-white/90'"
                                            class="h-2 rounded-full transition-all duration-300"></button>
                                </template>
                            </div>
                        @endif
                    </div>

                @else
                    <div class="w-full h-[250px] md:h-[350px] bg-gradient-to-br from-gray-800 to-gray-900 flex items-center justify-center relative">
                        <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-10"></div>
                        <svg class="w-16 h-16 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    </div>
                @endif

                {{-- Floating Title Card (Visible only on Desktop) --}}
                <div class="absolute bottom-0 inset-x-0 p-10 z-10 pointer-events-none hidden md:block">
                    <div class="flex flex-wrap items-center gap-2 mb-3">
                        <span class="px-2.5 py-1 text-[9px] font-black uppercase tracking-widest rounded-lg bg-white/20 backdrop-blur-md text-white border border-white/30">
                            {{ $activity->status }}
                        </span>
                        <span class="px-2.5 py-1 text-[9px] font-black uppercase tracking-widest rounded-lg bg-red-600 text-white shadow-sm">
                            {{ $activity->nature_of_activity }}
                        </span>
                    </div>
                    <h1 class="text-2xl md:text-4xl font-black text-white leading-tight drop-shadow-lg mb-2">
                        {{ $activity->title }}
                    </h1>
                    <p class="text-gray-300 text-base font-medium flex items-center gap-2 drop-shadow-md">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        {{ $activity->start_date->format('F d, Y') }}
                        @if($activity->end_date && $activity->end_date != $activity->start_date)
                            - {{ $activity->end_date->format('F d, Y') }}
                        @endif
                    </p>
                </div>

                {{-- Mobile Title Card (Visible only on Mobile) --}}
                <div class="md:hidden px-6 pt-4 pb-6 bg-gray-900">
                    <div class="flex flex-wrap items-center gap-2 mb-3">
                        <span class="px-2.5 py-1 text-[9px] font-black uppercase tracking-widest rounded-lg bg-white/10 text-white border border-white/20">
                            {{ $activity->status }}
                        </span>
                        <span class="px-2.5 py-1 text-[9px] font-black uppercase tracking-widest rounded-lg bg-red-600 text-white">
                            {{ $activity->nature_of_activity }}
                        </span>
                    </div>
                    <h1 class="text-2xl font-black text-white leading-tight mb-3">
                        {{ $activity->title }}
                    </h1>
                    <p class="text-gray-300 text-xs font-bold flex items-center gap-2 mb-4">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        {{ $activity->start_date->format('F d, Y') }}
                    </p>
                </div>
            </div>

            {{-- Grid Layout for Content --}}
            <div class="p-6 md:p-10 lg:p-16 grid grid-cols-1 lg:grid-cols-12 gap-12">

                {{-- Left/Main Column: Description & Participants --}}
                <div class="lg:col-span-8 space-y-10">
                    <div class="max-w-4xl space-y-10"> {{-- Constrain text width for readability --}}

                        {{-- Activity Overview --}}
                        <div>
                            <h3 class="text-xs font-black text-gray-400 uppercase tracking-widest mb-4 border-b border-gray-100 pb-2">Activity Overview</h3>
                            <div class="prose prose-sm md:prose-base max-w-none text-gray-700 leading-relaxed whitespace-pre-line">
                                {{ $activity->description ?? 'Detailed description for this activity is currently being updated.' }}
                            </div>
                        </div>

                        {{-- Lead Organization Field (If External Partner) --}}
                        @if($activity->lead_organization)
                            <div class="bg-blue-50/50 rounded-2xl p-6 border border-blue-100">
                                <h4 class="text-[10px] font-black text-blue-500 uppercase tracking-widest mb-1">Lead Coordinating Entity</h4>
                                <p class="text-sm font-bold text-gray-900">{{ $activity->lead_organization }}</p>
                            </div>
                        @endif

                        {{-- Focals & Participants Grid --}}
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-8 pt-6 border-t border-gray-100">

                            {{-- FOCALS --}}
                            @if($activity->focals->count() > 0)
                                <div>
                                    <h3 class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-4">Lead Focal Persons</h3>
                                    <ul class="space-y-3">
                                        @foreach($activity->focals as $focal)
                                            @php $fallback = 'https://ui-avatars.com/api/?name='.urlencode($focal->name).'&background=fff7ed&color=c2410c&bold=true'; @endphp
                                            <li class="flex items-center gap-3">
                                                <img src="{{ $focal->avatar ?? $fallback }}" onerror="this.onerror=null; this.src='{{ $fallback }}';" class="w-8 h-8 rounded-full border border-gray-200 shadow-sm object-cover">
                                                <div>
                                                    <p class="text-xs font-bold text-gray-900">{{ $focal->name }}</p>
                                                </div>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            {{-- PARTICIPANTS --}}
                            @if($activity->participants->count() > 0)
                                <div>
                                    <h3 class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-4">Delegates / Participants</h3>
                                    <div class="flex flex-wrap gap-2">
                                        @foreach($activity->participants as $participant)
                                            @php $fallback = 'https://ui-avatars.com/api/?name='.urlencode($participant->name).'&background=f8fafc&color=334155&bold=true'; @endphp
                                            <div class="flex items-center gap-1.5 bg-gray-50 border border-gray-100 pr-3 rounded-full shadow-sm">
                                                <img src="{{ $participant->avatar ?? $fallback }}" onerror="this.onerror=null; this.src='{{ $fallback }}';" class="w-6 h-6 rounded-full object-cover">
                                                <span class="text-[10px] font-bold text-gray-700">{{ $participant->name }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Right Sidebar: Metadata & SDG --}}
                <div class="lg:col-span-4 space-y-6">

                    {{-- Host Org Card --}}
                    <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-sm flex flex-col items-center text-center">
                        <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-3">Initiated By</p>
                        @php
                            $orgFallback = 'https://ui-avatars.com/api/?name='.urlencode($activity->user->name).'&background=eff6ff&color=2563eb&bold=true';
                            $orgAvatar = $activity->user->avatar ?? $orgFallback;
                        @endphp
                        <img src="{{ $orgAvatar }}" onerror="this.onerror=null; this.src='{{ $orgFallback }}';" class="w-16 h-16 rounded-full border border-gray-200 shadow-sm mb-3">
                        <h4 class="text-sm font-black text-gray-900">{{ $activity->user->name }}</h4>
                        <p class="text-[10px] font-medium text-gray-500 mt-1">{{ $activity->user->email }}</p>
                    </div>

                    {{-- SDG Cards --}}
                    @if($activity->sdgs->count() > 0)
                        <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-sm">
                            <h4 class="font-bold text-gray-900 uppercase tracking-widest text-[10px] border-b border-gray-100 pb-3 mb-4">
                                Sustainable Development Goals
                            </h4>

                            <div class="space-y-3">
                                @foreach($activity->sdgs as $sdg)
                                    {{-- Horizontal Pill Design --}}
                                    <div class="flex items-stretch rounded-xl border overflow-hidden shadow-sm transition-transform hover:-translate-y-0.5"
                                        style="background-color: {{ $sdg->color_hex }}15; border-color: {{ $sdg->color_hex }}40;">

                                        {{-- Left Side: Colored Number Block --}}
                                        <div class="w-14 shrink-0 flex items-center justify-center text-white font-black text-lg"
                                            style="background-color: {{ $sdg->color_hex }}">
                                            {{ $sdg->goal_number }}
                                        </div>

                                        {{-- Right Side: Tinted Text Block --}}
                                        <div class="flex items-center px-4 py-3 flex-1">
                                            <span class="text-[10px] font-black uppercase tracking-wider leading-tight"
                                                style="color: {{ $sdg->color_hex }}">
                                                {{ $sdg->name }}
                                            </span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </main>

    {{-- 2. FOOTER COMPONENT (Sibling to Main, spans full width) --}}
    <footer class="w-full bg-gray-900 text-white pt-20 pb-10 border-t-8 border-red-600 relative z-20">

        {{-- INNER CONTAINER: Max-width constraint --}}
        <div class="max-w-[1800px] w-[95%] mx-auto px-6">

            <div class="grid md:grid-cols-4 gap-12 mb-16">
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
                        <a href="https://www.facebook.com/BUMadya" class="w-10 h-10 rounded-full bg-gray-800 flex items-center justify-center hover:bg-blue-600 hover:text-white text-gray-400 transition"><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg></a>
                        <a href="https://www.instagram.com/bu_madya" class="w-10 h-10 rounded-full bg-gray-800 flex items-center justify-center hover:bg-pink-600 hover:text-white text-gray-400 transition"><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg></a>
                        <a href="https://www.x.com/bu_madya" class="w-10 h-10 rounded-full bg-gray-800 flex items-center justify-center hover:bg-black hover:text-white text-gray-400 transition"><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg></a>
                    </div>
                </div>

                <div>
                    <h4 class="font-bold text-lg mb-6 text-red-500 uppercase tracking-widest text-xs">Quick Links</h4>
                    <ul class="space-y-3 text-gray-400 text-sm">
                        <li><a href="{{ route('about') }}" class="hover:text-white hover:translate-x-1 transition inline-block">About BU MADYA</a></li>
                        <li><a href="{{ route('open.directory') }}" class="hover:text-white hover:translate-x-1 transition inline-block">Our Officers</a></li>
                        <li><a href="{{ route('transparency.index') }}" class="hover:text-white hover:translate-x-1 transition inline-block">Transparency Board</a></li>
                    </ul>
                </div>

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

            {{-- COPYRIGHT SECTION: Full width span --}}
            <div class="border-t border-gray-800 pt-8 text-center text-gray-600 text-xs uppercase tracking-widest">
                &copy; {{ date('Y') }} BU MADYA. All Rights Reserved.
            </div>
        </div>
    </footer>
</div>
