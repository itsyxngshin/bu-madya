@section('meta_title', '[ACTIVITY] ' . $activity->title)
@section('meta_description', $activity->description ? Str::limit(strip_tags($activity->description), 160) : 'Explore our latest youth-led activities, partnerships, and advocacy-driven initiatives contributing to sustainable development.')

@php
    // Safely grab the first highlight photo, or fall back to the default logo
    $ogImage = (!empty($activity->highlight_photos) && count($activity->highlight_photos) > 0)
        ? (Str::startsWith($activity->highlight_photos[0], 'http') ? $activity->highlight_photos[0] : asset('storage/' . $activity->highlight_photos[0]))
        : asset('images/MADYA Web Logo1.png');
@endphp

@section('meta_image', $ogImage)

<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8 lg:py-12 animate-fade-in-up">

    {{-- Breadcrumb Navigation --}}
    <div class="mb-6">
        <a href="{{ route('activities.index') }}" class="inline-flex items-center gap-2 text-[10px] sm:text-xs font-bold text-gray-500 uppercase tracking-widest hover:text-red-600 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg> Back to Feed
        </a>
    </div>

    <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden">

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
                <h1 class="text-4xl font-black text-white leading-tight drop-shadow-lg mb-2">
                    {{ $activity->title }}
                </h1>
                <p class="text-gray-300 text-base font-medium flex items-center gap-2 drop-shadow-md">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    {{ $activity->start_date->format('F d, Y') }}
                </p>
            </div>
            
            {{-- Mobile Title Card (Visible only on Mobile) --}}
            {{-- Updated: bg-gray-900 and text-white for high contrast --}}
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
        <div class="p-6 md:p-10 grid grid-cols-1 lg:grid-cols-3 gap-10">

            {{-- Left/Main Column: Description & Participants --}}
            <div class="lg:col-span-2 space-y-10">

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

            {{-- Right Sidebar: Metadata & SDG --}}
            <div class="space-y-6">

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
                                        {{ $sdg->number }}
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
</div>
