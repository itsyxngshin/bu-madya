@section('meta_title', $project->title)
@section('meta_description', $project->description ?? Str::limit(strip_tags($project->description), 150))
@php
    $ogImage = $project->cover_img
        ? (Str::startsWith($project->cover_img, 'http') ? $project->cover_img : asset('storage/' . $project->cover_img))
        : asset('images/default_news.jpg');
@endphp
@section('meta_image', $ogImage)

<div class="min-h-screen bg-stone-50 font-sans text-gray-900">

    {{-- 1. NAVIGATION BAR --}}
    <div class="w-full bg-white border-b border-gray-200 h-14 md:h-16 flex items-center justify-between px-4 md:px-6 shadow-sm relative z-30">

            {{-- A. Left: Back Link --}}
            <a href="{{ route('projects.index') }}" class="group flex items-center gap-2 text-[10px] md:text-xs font-bold uppercase tracking-widest text-gray-500 hover:text-red-600 transition">
                <div class="w-6 h-6 md:w-8 md:h-8 rounded-full bg-gray-100 flex items-center justify-center group-hover:bg-red-50 transition">
                    <svg class="w-3 h-3 md:w-4 md:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                </div>
                <span class="hidden sm:inline">Back to Projects</span>
            </a>

            {{-- B. Center: Title (Hidden on small mobile) --}}
            <span class="font-heading font-black text-sm md:text-lg tracking-tighter text-gray-900 hidden sm:block">
                Project <span class="text-red-600">Spotlight</span>
            </span>

            {{-- C. Right: Edit Button & Status --}}
            <div class="flex items-center gap-2 md:gap-4">

                @auth
                    <a href="{{ route('projects.edit', $project->slug) }}"
                    class="group flex items-center gap-2 text-[10px] md:text-xs font-bold uppercase tracking-widest text-gray-400 hover:text-blue-600 transition">
                        <span class="hidden md:inline">Edit</span>
                        <div class="w-6 h-6 md:w-8 md:h-8 rounded-full bg-white border border-gray-200 flex items-center justify-center group-hover:border-blue-200 group-hover:bg-blue-50 transition">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                        </div>
                    </a>
                @endauth

                <div class="h-3 md:h-4 w-px bg-gray-200"></div>

                {{-- STATUS BADGE --}}
                @if($project->status === 'Completed')
                    <span class="px-2 py-0.5 md:px-3 md:py-1 bg-green-100 text-green-700 text-[9px] md:text-[10px] font-black uppercase tracking-widest rounded-full border border-green-200 shadow-sm flex items-center gap-1">
                        <svg class="w-2.5 h-2.5 md:w-3 md:h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg> <span class="hidden sm:inline">Completed</span>
                    </span>
                @elseif($project->status === 'Ongoing')
                    <span class="px-2 py-0.5 md:px-3 md:py-1 bg-red-100 text-red-600 text-[9px] md:text-[10px] font-black uppercase tracking-widest rounded-full border border-red-200 shadow-sm flex items-center gap-1 animate-pulse">
                        <span class="w-1.5 h-1.5 md:w-2 md:h-2 bg-red-500 rounded-full"></span> Ongoing
                    </span>
                @else
                    <span class="px-2 py-0.5 md:px-3 md:py-1 bg-yellow-100 text-yellow-800 text-[9px] md:text-[10px] font-black uppercase tracking-widest rounded-full border border-yellow-200 shadow-sm">
                        Upcoming
                    </span>
                @endif
            </div>
    </div>

    {{-- 2. HERO SECTION --}}
    <header class="relative pt-8 md:pt-32 pb-8 md:pb-16 px-4 md:px-6 max-w-7xl mx-auto">
        <div class="grid lg:grid-cols-2 gap-8 md:gap-12 items-center">

            {{-- TEXT CONTENT --}}
            <div class="order-2 lg:order-1">
                <div class="mb-4 md:mb-6 flex items-center gap-2 md:gap-3">
                     <span class="w-6 md:w-10 h-1 bg-gradient-to-r from-red-600 to-yellow-500 rounded-full"></span>
                     <span class="text-[10px] md:text-xs font-bold text-gray-500 uppercase tracking-widest">
                         {{ $project->category?->name ?? 'Uncategorized' }}
                     </span>
                </div>

                <h1 class="font-heading text-3xl md:text-6xl font-black text-gray-900 leading-tight mb-4 md:mb-6">
                    {{ $project->title }}
                </h1>

                {{-- Description (Responsive Prose) --}}
                <div class="text-sm md:text-lg text-gray-600 leading-relaxed font-sans mb-6 md:mb-8 border-l-4 border-yellow-400 pl-4 md:pl-6 whitespace-pre-line">
                    "{{ $project->description }}"
                </div>

                {{-- Impact Stats (Grid Adjustment) --}}
                @if(!empty($project->impact_stats))
                <div class="grid grid-cols-3 gap-2 md:gap-4 border-t border-gray-200 pt-6 md:pt-8">
                    @foreach($project->impact_stats as $stat)
                        @if(!empty($stat['value']))
                        <div class="text-center md:text-left">
                            <span class="block text-lg md:text-2xl font-black text-gray-900">{{ $stat['value'] }}</span>
                            <span class="text-[8px] md:text-[10px] uppercase tracking-wider text-gray-400 font-bold block">{{ $stat['label'] }}</span>
                        </div>
                        @endif
                    @endforeach
                </div>
                @endif
            </div>

            {{-- IMAGE --}}
            <div class="order-1 lg:order-2 relative group">
                <div class="absolute inset-0 bg-red-600 rounded-2xl md:rounded-[2.5rem] rotate-2 md:rotate-3 opacity-20 group-hover:rotate-3 md:group-hover:rotate-6 transition duration-500"></div>
                <div class="relative overflow-hidden rounded-2xl md:rounded-[2.5rem] shadow-xl md:shadow-2xl aspect-[4/3] border-2 md:border-4 border-white bg-gray-200">
                    @if($project->cover_img)
                        <img src="{{ Str::startsWith($project->cover_img, 'http') ? $project->cover_img : asset('storage/' . $project->cover_img) }}"
                             class="w-full h-full object-cover transform group-hover:scale-105 transition duration-700">
                    @else
                        <div class="flex items-center justify-center h-full text-gray-400 font-bold bg-gray-100 text-xs md:text-base">NO IMAGE</div>
                    @endif
                    <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent"></div>

                    {{-- Location Tag --}}
                    <div class="absolute bottom-4 left-4 md:bottom-6 md:left-6 bg-white/90 backdrop-blur px-3 py-1.5 md:px-4 md:py-2 rounded-lg md:rounded-xl flex items-center gap-2 shadow-lg">
                        <svg class="w-3 h-3 md:w-4 md:h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        <div>
                            <p class="text-[8px] md:text-[10px] text-gray-400 font-bold uppercase leading-none">Location</p>
                            <p class="text-[10px] md:text-xs font-bold text-gray-900 leading-tight">{{ $project->location ?? 'Various' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    {{-- 3. DETAILS & GALLERY GRID --}}
    <div class="max-w-7xl mx-auto px-4 md:px-6 pb-12 md:pb-24 grid lg:grid-cols-12 gap-8 md:gap-12">

        {{-- LEFT SIDEBAR --}}
        <aside class="lg:col-span-4 space-y-6 md:space-y-8">

            {{-- 1. QUICK INFO CARD --}}
            <div class="bg-white p-5 md:p-6 rounded-2xl md:rounded-3xl shadow-sm border border-gray-100 relative overflow-hidden">
                <div class="absolute top-0 right-0 bg-yellow-400 text-yellow-900 text-[8px] md:text-[9px] font-black uppercase px-3 py-1.5 rounded-bl-xl shadow-sm z-10">
                    AY {{ $project->academicYear->name ?? 'TBA' }}
                </div>

                <h3 class="font-bold text-gray-900 uppercase tracking-widest text-[10px] md:text-xs border-b border-gray-100 pb-3 mb-5">
                    Project Overview
                </h3>

                {{-- Date & Status Row --}}
                <div class="grid grid-cols-2 gap-4 mb-6 bg-gray-50 p-4 rounded-2xl border border-gray-100">
                    <div>
                        <span class="block text-[9px] font-bold text-gray-400 uppercase tracking-widest mb-1">Date</span>
                        <span class="text-xs font-black text-gray-800">
                            {{ $project->implementation_date ? $project->implementation_date->format('M d, Y') : 'TBA' }}
                        </span>
                    </div>
                    <div>
                        <span class="block text-[9px] font-bold text-gray-400 uppercase tracking-widest mb-1">Status</span>
                        <span class="text-xs font-black {{ $project->status === 'Completed' ? 'text-green-600' : ($project->status === 'Ongoing' ? 'text-red-600' : 'text-yellow-600') }}">
                            {{ $project->status }}
                        </span>
                    </div>
                </div>

                {{-- Proponents (Facilitators) Card Grid --}}
                <div>
                    <span class="flex items-center gap-2 text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-3">
                        <div class="w-5 h-5 rounded-full bg-red-50 text-red-600 flex items-center justify-center">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        </div>
                        Lead Proponents
                    </span>
                    
                    <div class="grid grid-cols-1 gap-3">
                        @foreach($project->proponents as $proponent)
                            @php
                                // Safely resolve the photo path. Update 'profile_photo_path' if your User model uses 'avatar' or 'photo' instead.
                                $avatarPath = $proponent->profile_photo_path ?? null;
                                $avatarUrl = $avatarPath && !Str::startsWith($avatarPath, 'http') 
                                    ? asset('storage/' . $avatarPath) 
                                    : ($avatarPath ?: 'https://ui-avatars.com/api/?name='.urlencode($proponent->name).'&background=fef2f2&color=dc2626&bold=true');
                            @endphp
                            
                            {{-- Changed to items-start so if a name is very long, the avatar stays at the top --}}
                            <div class="flex items-start gap-3 p-3 bg-white border border-gray-200 rounded-2xl hover:border-red-200 hover:shadow-md transition-all group cursor-default">
                                <img src="{{ $avatarUrl }}" class="w-10 h-10 rounded-xl object-cover shadow-sm group-hover:scale-105 transition-transform shrink-0" alt="{{ $proponent->name }}">
                                <div class="pt-0.5">
                                    {{-- Removed leading-none and added break-words to allow full names to wrap --}}
                                    <p class="text-xs font-black text-gray-900 leading-snug break-words">
                                        {{ $project->title == 'Evaluation Results' ? 'Administrator' : $proponent->name }}
                                    </p>
                                    <p class="text-[9px] font-bold text-red-500 uppercase tracking-widest mt-1">Facilitator</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- 2. PARTNERS CARD (Updated to use projectLinkages) --}}
            @if($project->projectLinkages && $project->projectLinkages->isNotEmpty())
            <div class="bg-white p-5 md:p-6 rounded-2xl md:rounded-3xl shadow-sm border border-gray-100">
                <h3 class="font-bold text-gray-900 uppercase tracking-widest text-[10px] md:text-xs border-b border-gray-100 pb-3 mb-5 flex items-center gap-2">
                    <div class="w-5 h-5 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                    </div>
                    In Partnership With
                </h3>
                
                <div class="grid grid-cols-1 gap-3">
                    @foreach($project->projectLinkages as $projLink)
                        @php
                            // Extract the actual Linkage/Partner model from the pivot relationship
                            $linkage = $projLink->linkage;
                            
                            if(!$linkage) continue; // Safety check in case of orphaned relationships

                            // Update this condition if your DB uses a different column to denote 'official' status
                            $isOfficial = isset($linkage->is_official) ? $linkage->is_official : false;

                            // Update 'logo' to 'logo_path' or 'image' if that's what your Linkage model uses
                            $logoPath = $linkage->logo ?? $linkage->logo_path ?? null;
                            $partnerLogo = $logoPath && !Str::startsWith($logoPath, 'http')
                                ? asset('storage/' . $logoPath) 
                                : ($logoPath ?: ($isOfficial 
                                    ? 'https://ui-avatars.com/api/?name='.urlencode($linkage->name).'&background=eff6ff&color=2563eb&bold=true'
                                    : 'https://ui-avatars.com/api/?name='.urlencode($linkage->name).'&background=f3f4f6&color=4b5563&bold=true'));
                        @endphp
                        
                        {{-- Items-start ensures the logo stays pinned to the top if the name spans 3 lines --}}
                        <div class="flex items-start gap-3 p-3 rounded-2xl border transition-all group cursor-default
                                    {{ $isOfficial ? 'border-blue-100 bg-blue-50/30 hover:bg-blue-50 hover:border-blue-300' : 'border-gray-100 bg-gray-50 hover:bg-white hover:border-gray-300 hover:shadow-sm' }}">
                            
                            <div class="w-12 h-12 rounded-xl shrink-0 bg-white border border-gray-100 p-0.5 shadow-sm overflow-hidden flex items-center justify-center">
                                <img src="{{ $partnerLogo }}" class="w-full h-full object-cover rounded-lg group-hover:scale-110 transition-transform" alt="{{ $linkage->name }}">
                            </div>
                            
                            <div class="flex-1 min-w-0 pt-0.5">
                                {{-- Removed truncate, added break-words --}}
                                <p class="text-xs font-black text-gray-900 leading-snug break-words">
                                    {{ $linkage->name }}
                                </p>
                                <p class="text-[9px] font-bold uppercase tracking-widest mt-1.5 flex items-center gap-1 {{ $isOfficial ? 'text-blue-600' : 'text-gray-500' }}">
                                    @if($isOfficial)
                                        <svg class="w-2.5 h-2.5" fill="currentColor" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        Official Partner
                                    @else
                                        Organization Partner
                                    @endif
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- 3. SDGs CARD --}}
            @if($project->sdgs->count() > 0)
            <div class="bg-white p-5 md:p-6 rounded-2xl md:rounded-3xl shadow-sm border border-gray-100">
                <h3 class="font-bold text-gray-900 uppercase tracking-widest text-[10px] md:text-xs border-b border-gray-100 pb-3 mb-4">
                    Targeted SDGs
                </h3>
                <div class="flex flex-col gap-2">
                    @foreach($project->sdgs as $sdg)
                    <div class="flex items-center gap-2 md:gap-3">
                        <div class="w-8 h-8 md:w-10 md:h-10 rounded-lg text-white font-black text-sm md:text-lg flex items-center justify-center shadow-sm"
                             style="background-color: {{ $sdg->color_hex }}">
                            {{ $sdg->number }}
                        </div>
                        <span class="text-[10px] md:text-xs font-bold text-gray-700 uppercase tracking-wide leading-tight">
                            {{ $sdg->name }}
                        </span>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- 4. EVALUATION ACTION CARD --}}
            @if($project->evaluation)
                <div class="mt-8 bg-gradient-to-br from-red-50 to-orange-50 rounded-[2rem] p-6 border border-orange-100 relative overflow-hidden group">
                    <div class="absolute -right-6 -top-6 w-24 h-24 bg-orange-100 rounded-full opacity-50 group-hover:scale-150 transition duration-500"></div>
                    <div class="relative z-10">
                        <div class="flex items-center justify-between mb-1">
                            <h3 class="font-black text-gray-900 text-lg">Project Feedback</h3>
                            @if($project->evaluation->is_active)
                                <span class="relative flex h-3 w-3">
                                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                                  <span class="relative inline-flex rounded-full h-3 w-3 bg-green-500"></span>
                                </span>
                            @endif
                        </div>
                        <p class="text-sm text-gray-600 mb-5 mt-2">
                            @if($project->evaluation->is_active)
                                Please take a moment to evaluate the <strong>{{ $project->title }}</strong> project.
                            @else
                                The evaluation period for this project has officially closed.
                            @endif
                        </p>

                        <div class="flex flex-wrap gap-2">
                            {{-- The Public Form Link --}}
                            @if($project->evaluation->is_active)
                                <a href="{{ route('evaluations.show', $project->evaluation->slug) }}?project_id={{ $project->id }}"
                                   class="inline-flex items-center gap-2 px-5 py-2.5 bg-gray-900 text-white font-bold rounded-xl shadow-md hover:bg-orange-600 hover:-translate-y-0.5 transition duration-300 text-[10px] md:text-xs uppercase tracking-wider">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    Take Evaluation
                                </a>
                            @endif

                            {{-- The Admin Results Link --}}
                            @if($canViewEvaluationResults)
                                <a href="{{ route('admin.evaluations.results', $project->evaluation->slug) }}"
                                   class="inline-flex items-center gap-2 px-4 py-2.5 bg-white border-2 border-orange-200 text-orange-700 font-bold rounded-xl shadow-sm hover:bg-orange-50 hover:border-orange-300 hover:-translate-y-0.5 transition duration-300 text-[10px] md:text-xs uppercase tracking-wider">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                                    View Analytics
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            @endif

        </aside>

        {{-- RIGHT CONTENT --}}
        <main class="lg:col-span-8 space-y-8 md:space-y-12">

            {{-- 4. OBJECTIVES SECTION --}}
            @if($project->objectives->isNotEmpty())
            <div class="mt-8 md:mt-12 bg-gradient-to-br from-gray-900 to-gray-800 text-white p-6 md:p-8 rounded-2xl md:rounded-[2rem] shadow-lg relative overflow-hidden">
                <div class="absolute top-0 right-0 w-32 md:w-48 h-32 md:h-48 bg-white/10 rounded-full blur-3xl -mr-10 -mt-10"></div>

                <h3 class="font-bold uppercase tracking-widest text-[10px] md:text-sm mb-4 md:mb-6 text-yellow-400 relative z-10 flex items-center gap-2">
                    <span class="w-1.5 h-1.5 md:w-2 md:h-2 bg-yellow-400 rounded-full"></span>
                    Project Objectives
                </h3>

                <ul class="space-y-3 md:space-y-4 relative z-10">
                    @foreach($project->objectives as $obj)
                    <li class="flex items-start gap-3 group">
                        <div class="w-5 h-5 md:w-6 md:h-6 rounded-full bg-white/10 flex items-center justify-center shrink-0 group-hover:bg-green-500 transition-colors duration-300">
                            <svg class="w-2.5 h-2.5 md:w-3 md:h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                        </div>
                        <span class="text-gray-200 text-xs md:text-base leading-relaxed group-hover:text-white transition-colors">
                            {{ $obj->objective }}
                        </span>
                    </li>
                    @endforeach
                </ul>
            </div>
            @endif

            {{-- 4.5 PUBLIC EVALUATION RESULTS --}}
            @if($project->evaluation && $totalResponses > 0 && $overallRating > 0)
                <div class="mt-8 md:mt-12 bg-white rounded-2xl md:rounded-[2rem] p-6 md:p-8 border border-gray-100 shadow-xl shadow-gray-200/50"
                     x-data="{ showChartDrawer: false, chartInitialized: false }"
                     x-init="$watch('showChartDrawer', value => {
                         if(value && !chartInitialized) {
                             initEvaluationChart();
                             chartInitialized = true;
                         }
                     })">

                    <div class="flex items-center gap-3 mb-6 md:mb-8">
                        <div class="p-1.5 md:p-2 bg-orange-50 rounded-lg text-orange-600">
                            <svg class="w-5 h-5 md:w-6 md:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path></svg>
                        </div>
                        <div class="flex-1 flex justify-between items-center">
                            <div>
                                <h3 class="font-heading text-lg md:text-xl font-black text-gray-900 leading-tight">Community Feedback</h3>
                                <p class="text-[10px] md:text-sm text-gray-500 font-medium">Based on {{ $totalResponses }} official evaluations</p>
                            </div>
                            {{-- Chart Drawer Toggle Button --}}
                            <button @click="showChartDrawer = !showChartDrawer" class="hidden sm:flex items-center gap-2 px-4 py-2 bg-gray-50 hover:bg-gray-100 text-gray-700 rounded-xl text-xs font-bold uppercase tracking-widest transition-colors border border-gray-200">
                                <svg class="w-4 h-4 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"></path></svg>
                                <span x-text="showChartDrawer ? 'Hide Chart' : 'View Chart'"></span>
                            </button>
                        </div>
                    </div>

                    {{-- TOP ROW: Massive Score Display Card --}}
                    <div class="bg-gradient-to-br from-orange-50 to-red-50 border border-orange-100 rounded-2xl p-6 md:p-8 flex flex-col md:flex-row items-center justify-between gap-6 mb-8">
                        <div class="text-center md:text-left">
                            <h4 class="text-orange-900 font-black text-xl md:text-2xl mb-1">Overall Satisfaction</h4>
                            <p class="text-sm font-bold text-orange-700 bg-orange-200/50 inline-block px-4 py-1.5 rounded-full border border-orange-200 mt-2 md:mt-1">
                                {{ $overallRating >= 4.5 ? 'Excellent' : ($overallRating >= 4.0 ? 'Very Good' : ($overallRating >= 3.0 ? 'Good' : 'Needs Improvement')) }} Experience
                            </p>
                        </div>

                        <div class="flex items-center gap-4 md:gap-6">
                            <div class="flex flex-col items-end">
                                <div class="flex text-orange-500 mb-1">
                                    @for($i=1; $i<=5; $i++)
                                        <svg class="w-6 h-6 md:w-8 md:h-8 {{ $i <= round($overallRating) ? 'fill-current' : 'text-orange-200' }}" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                    @endfor
                                </div>
                                <span class="text-[10px] font-bold text-orange-600/80 uppercase tracking-widest">Out of 5.0</span>
                            </div>
                            <div class="text-6xl md:text-7xl font-black text-orange-600 leading-none drop-shadow-sm">
                                {{ number_format($overallRating, 1) }}
                            </div>
                        </div>
                    </div>

                    {{-- MOBILE TOGGLE BUTTON --}}
                    <button @click="showChartDrawer = !showChartDrawer" class="sm:hidden w-full mb-8 flex items-center justify-center gap-2 px-4 py-3 bg-gray-50 hover:bg-gray-100 text-gray-700 rounded-xl text-xs font-bold uppercase tracking-widest transition-colors border border-gray-200">
                        <svg class="w-4 h-4 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"></path></svg>
                        <span x-text="showChartDrawer ? 'Hide Interactive Chart' : 'View Interactive Chart'"></span>
                    </button>

                    {{-- INTERACTIVE CHART DRAWER (Alpine Collapse) --}}
                    <div x-show="showChartDrawer" x-collapse x-cloak>
                        <div class="mb-10 bg-gray-50 rounded-2xl border border-gray-200 p-4 md:p-6 relative">
                            <h4 class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-4 text-center">Performance Radar</h4>
                            <div class="relative h-[300px] md:h-[400px] w-full flex justify-center">
                                <canvas id="evaluationRadarChart"></canvas>
                            </div>
                        </div>
                    </div>

                    {{-- BOTTOM ROW: The Sectioned Evaluation Breakdown --}}
                    <div>
                        <h4 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-6 shrink-0">Detailed Evaluation Breakdown</h4>

                        <div class="space-y-10 max-h-[400px] md:max-h-[500px] overflow-y-auto custom-scrollbar pr-2 sm:pr-4">
                            @foreach($groupedLikertResults as $section)
                                <div>
                                    {{-- Section Header --}}
                                    <div class="flex justify-between items-end border-b-2 border-gray-100 pb-2 mb-5">
                                        <h5 class="text-sm font-black text-gray-800 uppercase tracking-widest">{{ $section['title'] }}</h5>
                                        <span class="text-xs font-bold text-gray-500 bg-gray-100 px-2 py-1 rounded">Score: {{ number_format($section['section_average'], 1) }}</span>
                                    </div>

                                    {{-- Section Criteria --}}
                                    <div class="space-y-4">
                                        @foreach($section['criteria'] as $criteria)
                                            @php
                                                $percent = ($criteria['score'] / 5) * 100;
                                                if ($criteria['score'] >= 4.5) $barColor = 'bg-green-500';
                                                elseif ($criteria['score'] >= 3.5) $barColor = 'bg-blue-500';
                                                elseif ($criteria['score'] >= 2.5) $barColor = 'bg-yellow-400';
                                                else $barColor = 'bg-red-500';
                                            @endphp
                                            <div class="w-full block group/bar">
                                                <div class="flex justify-between items-end gap-4 mb-1.5 w-full">
                                                    <div class="text-xs sm:text-sm font-bold text-gray-700 leading-snug group-hover/bar:text-orange-600 transition-colors">
                                                        {{ html_entity_decode($criteria['label']) }}
                                                    </div>
                                                    <div class="text-sm font-black text-gray-900 shrink-0">
                                                        {{ number_format($criteria['score'], 1) }}
                                                    </div>
                                                </div>
                                                <div class="w-full bg-gray-100 h-2 sm:h-2.5 rounded-full overflow-hidden">
                                                    <div class="{{ $barColor }} h-full rounded-full transition-all duration-1000 ease-out" style="width: {{ $percent }}%"></div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                </div>
            @endif

            {{-- 5. GALLERY --}}
            @if($project->galleries->count() > 0)
                <div class="mt-8 md:mt-12 bg-white rounded-2xl md:rounded-3xl p-6 md:p-8 border border-gray-100 shadow-xl shadow-gray-200/50">

                    <div class="flex items-center gap-3 mb-6 md:mb-8">
                        <div class="p-1.5 md:p-2 bg-red-50 rounded-lg text-red-600">
                            <svg class="w-5 h-5 md:w-6 md:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        </div>
                        <div>
                            <h3 class="font-heading text-lg md:text-xl font-black text-gray-900 leading-tight">Project Gallery</h3>
                            <p class="text-[10px] md:text-sm text-gray-500 font-medium">{{ $project->galleries->count() }} Photos available</p>
                        </div>
                    </div>

                    {{-- LIGHTBOX COMPONENT --}}
                    <div x-data="{
                            lightboxOpen: false,
                            activeImage: '',
                            activeTitle: '',
                            activeDesc: '',
                            openLightbox(img, title, desc) {
                                this.activeImage = img;
                                this.activeTitle = title;
                                this.activeDesc = desc;
                                this.lightboxOpen = true;
                                document.body.style.overflow = 'hidden';
                            },
                            closeLightbox() {
                                this.lightboxOpen = false;
                                document.body.style.overflow = '';
                            }
                        }"
                        @keydown.escape.window="closeLightbox()"
                    >

                        {{-- THUMBNAIL GRID (2 cols on mobile) --}}
                        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3 md:gap-4">
                            @foreach($project->galleries as $photo)
                                <div class="group relative aspect-square overflow-hidden rounded-xl md:rounded-2xl cursor-pointer bg-gray-100 ring-1 ring-black/5 hover:ring-red-500/50 hover:shadow-lg transition-all duration-300"
                                     @click="openLightbox('{{ asset('storage/'.$photo->image_path) }}', '{{ addslashes($photo->title) }}', '{{ addslashes($photo->description) }}')">

                                    <img src="{{ asset('storage/'.$photo->image_path) }}"
                                         loading="lazy"
                                         class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-700 ease-in-out"
                                         alt="{{ $photo->title }}">

                                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex flex-col justify-end p-3 md:p-4">
                                        @if($photo->title)
                                            <p class="text-white text-[10px] md:text-xs font-bold line-clamp-1">{{ $photo->title }}</p>
                                        @endif
                                        <p class="text-white/70 text-[8px] md:text-[10px] uppercase tracking-wider font-bold mt-1 flex items-center gap-1">
                                            View
                                        </p>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        {{-- LIGHTBOX MODAL --}}
                        <template x-teleport="body">
                            <div x-show="lightboxOpen"
                                style="display: none;"
                                class="fixed inset-0 z-[100] flex items-center justify-center bg-black/95 backdrop-blur-md"
                                x-transition:enter="transition ease-out duration-300"
                                x-transition:enter-start="opacity-0"
                                x-transition:enter-end="opacity-100"
                                x-transition:leave="transition ease-in duration-200"
                                x-transition:leave-start="opacity-100"
                                x-transition:leave-end="opacity-0">

                                <button @click="closeLightbox()" class="absolute top-4 right-4 md:top-6 md:right-6 text-white/50 hover:text-white bg-white/10 hover:bg-white/20 rounded-full p-2 transition z-50">
                                    <svg class="w-6 h-6 md:w-8 md:h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                </button>

                                <div class="relative w-full max-w-6xl h-full flex flex-col items-center justify-center p-4 md:p-10" @click.outside="closeLightbox()">
                                    <img :src="activeImage" class="max-w-full max-h-[80vh] object-contain rounded shadow-2xl animate-in zoom-in-95 duration-300">

                                    <div class="absolute bottom-6 left-0 right-0 text-center pointer-events-none" x-show="activeTitle || activeDesc">
                                        <div class="inline-block bg-black/60 backdrop-blur-md px-4 py-3 md:px-6 md:py-4 rounded-xl md:rounded-2xl max-w-2xl mx-4 pointer-events-auto text-left">
                                            <h4 x-show="activeTitle" x-text="activeTitle" class="text-white font-bold text-sm md:text-lg mb-1"></h4>
                                            <p x-show="activeDesc" x-text="activeDesc" class="text-gray-300 text-xs md:text-sm leading-relaxed"></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            @endif

        </main>
    </div>

    {{-- FOOTER --}}
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
        <div class="border-t border-gray-800 pt-8 text-center text-gray-600 text-xs uppercase tracking-widest">
            &copy; {{ date('Y') }} BU MADYA. All Rights Reserved.
        </div>
    </footer>


</div>
@push('scripts')
    {{-- Include Chart.js --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        const groupedResults = @json($groupedLikertResults ?? []);

        function initEvaluationChart() {
            const canvas = document.getElementById('evaluationRadarChart');
            if(!canvas) return;

            const ctx = canvas.getContext('2d');

            // Extract labels and data
            const labels = [];
            const dataScores = [];

            groupedResults.forEach(section => {
                // Shorten long labels to keep the chart clean
                let title = section.title;
                if(title.length > 20) title = title.substring(0, 20) + '...';
                labels.push(title);
                dataScores.push(section.section_average);
            });

            // CREATE A PREMIUM RADIAL GRADIENT FILL
            // Center is slightly transparent orange, fading out to a richer orange
            const gradientFill = ctx.createRadialGradient(
                canvas.width / 2, canvas.height / 2, 0,
                canvas.width / 2, canvas.height / 2, canvas.width / 2
            );
            gradientFill.addColorStop(0, 'rgba(234, 88, 12, 0.4)');   // Orange-500, 40%
            gradientFill.addColorStop(1, 'rgba(220, 38, 38, 0.05)');  // Red-600, 5%

            new Chart(ctx, {
                type: 'radar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Section Average',
                        data: dataScores,
                        backgroundColor: gradientFill,
                        borderColor: '#EA580C', // Solid Orange-500
                        borderWidth: 3,
                        // Make the lines curve smoothly instead of sharp geometric angles
                        tension: 0.3,

                        // Beautiful glowing points
                        pointBackgroundColor: '#FFFFFF',
                        pointBorderColor: '#EA580C',
                        pointBorderWidth: 3,
                        pointRadius: 5,
                        pointHoverRadius: 8,
                        pointHoverBackgroundColor: '#DC2626', // Red on hover
                        pointHoverBorderColor: '#FFFFFF',
                        pointHoverBorderWidth: 3,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    // Premium entry animation
                    animation: {
                        duration: 2000,
                        easing: 'easeOutQuart'
                    },
                    scales: {
                        r: {
                            // Circular grid instead of a spiderweb polygon
                            circular: true,
                            angleLines: {
                                color: 'rgba(0, 0, 0, 0.05)',
                                lineWidth: 1
                            },
                            grid: {
                                color: 'rgba(0, 0, 0, 0.05)',
                                circular: true,
                                lineWidth: 1.5,
                                borderDash: [5, 5] // Dashed grid lines look more modern
                            },
                            pointLabels: {
                                font: {
                                    family: "'Inter', sans-serif",
                                    size: 11,
                                    weight: '800' // Extra bold for readability
                                },
                                color: '#4B5563', // Gray-600
                                padding: 15
                            },
                            ticks: {
                                min: 0,
                                max: 5,
                                stepSize: 1,
                                display: true,
                                // Hide the background behind the numbers
                                backdropColor: 'transparent',
                                color: '#9CA3AF', // Gray-400
                                font: {
                                    size: 10,
                                    weight: 'bold',
                                    family: "'Inter', sans-serif"
                                },
                                z: 10
                            }
                        }
                    },
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: 'rgba(17, 24, 39, 0.95)', // Dark gray/black
                            titleFont: { family: "'Inter', sans-serif", size: 14, weight: 'bold' },
                            bodyFont: { family: "'Inter', sans-serif", size: 13 },
                            padding: 16,
                            cornerRadius: 12,
                            displayColors: false,
                            callbacks: {
                                label: function(context) {
                                    // Add a star emoji and format the number
                                    return '⭐ Average Score: ' + context.parsed.r.toFixed(2) + ' / 5.00';
                                }
                            }
                        }
                    }
                }
            });
        }
    </script>
@endpush
