<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8 lg:py-12 animate-fade-in-up">

    {{-- Breadcrumb Navigation --}}
    <div class="mb-6">
        <a href="{{ route('activities.index') }}" class="inline-flex items-center gap-2 text-[10px] sm:text-xs font-bold text-gray-500 uppercase tracking-widest hover:text-red-600 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg> Back to Feed
        </a>
    </div>

    <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden">

        {{-- Hero Photo Gallery Container --}}
        <div class="w-full relative bg-gray-900 overflow-hidden flex" style="max-height: 500px;">
            @if(!empty($activity->highlight_photos) && count($activity->highlight_photos) > 0)
                <div class="w-full h-full snap-x snap-mandatory flex overflow-x-auto custom-scrollbar">
                    @foreach($activity->highlight_photos as $photo)
                        <div class="shrink-0 w-full h-[300px] sm:h-[400px] md:h-[500px] snap-center relative">
                            <img src="{{ asset('storage/' . $photo) }}" class="w-full h-full object-cover opacity-90" alt="Highlight Photo">
                            <div class="absolute inset-0 bg-gradient-to-t from-gray-900 via-gray-900/40 to-transparent"></div>
                        </div>
                    @endforeach
                </div>

                @if(count($activity->highlight_photos) > 1)
                    <div class="absolute bottom-6 inset-x-0 flex justify-center pointer-events-none">
                        <span class="bg-black/50 backdrop-blur-md text-white text-[9px] font-black uppercase tracking-widest px-3 py-1 rounded-full shadow-lg border border-white/20">
                            Swipe for {{ count($activity->highlight_photos) }} Photos
                        </span>
                    </div>
                @endif
            @else
                <div class="w-full h-[250px] md:h-[350px] bg-gradient-to-br from-gray-800 to-gray-900 flex items-center justify-center relative">
                    <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-10"></div>
                    <svg class="w-16 h-16 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                </div>
            @endif

            {{-- Floating Title Card --}}
            <div class="absolute bottom-0 inset-x-0 p-6 md:p-10 z-10 pointer-events-none">
                <div class="flex flex-wrap items-center gap-2 mb-3">
                    <span class="px-2.5 py-1 text-[9px] font-black uppercase tracking-widest rounded-lg bg-white/20 backdrop-blur-md text-white border border-white/30">
                        {{ $activity->status }}
                    </span>
                    <span class="px-2.5 py-1 text-[9px] font-black uppercase tracking-widest rounded-lg bg-red-600 text-white shadow-sm">
                        {{ $activity->nature_of_activity }}
                    </span>
                </div>
                <h1 class="text-3xl md:text-5xl font-black text-white leading-tight drop-shadow-lg mb-2">{{ $activity->title }}</h1>
                <p class="text-gray-300 text-sm md:text-base font-medium flex items-center gap-2 drop-shadow-md">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    {{ $activity->start_date->format('F d, Y') }}
                    @if($activity->end_date && $activity->end_date != $activity->start_date)
                        - {{ $activity->end_date->format('F d, Y') }}
                    @endif
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
</div>
