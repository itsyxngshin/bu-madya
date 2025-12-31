@section('meta_title', $project->title) 
@section('meta_description', $project->description ?? Str::limit(strip_tags($project->description), 150))  
@php
    // 1. Determine the image URL using PHP logic
    $ogImage = $project->cover_img 
        ? (Str::startsWith($project->cover_img, 'http') ? $project->cover_img : asset('storage/' . $project->cover_img))
        : asset('images/default_news.jpg');
@endphp
@section('meta_image', $ogImage)

<div class="min-h-screen bg-stone-50 font-sans text-gray-900">
    
    {{-- 1. NAVIGATION BAR --}}
    <div class="w-full bg-white border-b border-gray-200 h-16 flex items-center justify-between px-6 shadow-sm relative z-30">
            
            {{-- A. Left: Back Link --}}
            <a href="{{ route('projects.index') }}" class="group flex items-center gap-2 text-xs font-bold uppercase tracking-widest text-gray-500 hover:text-red-600 transition">
                <div class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center group-hover:bg-red-50 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                </div>
                <span class="hidden md:inline">Back to Projects</span>
            </a>

            {{-- B. Center: Title --}}
            <span class="font-heading font-black text-lg tracking-tighter text-gray-900 hidden md:block">
                Project <span class="text-red-600">Spotlight</span>
            </span>

            {{-- C. Right: Edit Button & Status --}}
            <div class="flex items-center gap-4">
                
                {{-- [NEW] EDIT BUTTON --}}
                {{-- You can wrap this in @auth / @can('update', $project) if needed --}}
                @auth
                    <a href="{{ route('projects.edit', $project->slug) }}" 
                    class="group flex items-center gap-2 text-xs font-bold uppercase tracking-widest text-gray-400 hover:text-blue-600 transition">
                        <span class="hidden md:inline">Edit</span>
                        <div class="w-8 h-8 rounded-full bg-white border border-gray-200 flex items-center justify-center group-hover:border-blue-200 group-hover:bg-blue-50 transition">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                        </div>
                    </a>
                @endauth

                {{-- DIVIDER --}}
                <div class="h-4 w-px bg-gray-200"></div>

                {{-- STATUS BADGE (Existing) --}}
                @if($project->status === 'Completed')
                    <span class="px-3 py-1 bg-green-100 text-green-700 text-[10px] font-black uppercase tracking-widest rounded-full border border-green-200 shadow-sm flex items-center gap-1">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg> Completed
                    </span>
                @elseif($project->status === 'Ongoing')
                    <span class="px-3 py-1 bg-red-100 text-red-600 text-[10px] font-black uppercase tracking-widest rounded-full border border-red-200 shadow-sm flex items-center gap-1 animate-pulse">
                        <span class="w-2 h-2 bg-red-500 rounded-full"></span> Ongoing
                    </span>
                @else
                    <span class="px-3 py-1 bg-yellow-100 text-yellow-800 text-[10px] font-black uppercase tracking-widest rounded-full border border-yellow-200 shadow-sm">
                        Upcoming
                    </span>
                @endif
            </div>
    </div>

    {{-- 2. HERO SECTION --}}
    <header class="relative pt-32 pb-16 px-6 max-w-7xl mx-auto">
        <div class="grid lg:grid-cols-2 gap-12 items-center">
            <div class="order-2 lg:order-1">
                <div class="mb-6 flex items-center gap-3">
                     <span class="w-10 h-1 bg-gradient-to-r from-red-600 to-yellow-500 rounded-full"></span>
                     <span class="text-xs font-bold text-gray-500 uppercase tracking-widest">
                         {{ $project->category?->name ?? 'Uncategorized' }}
                     </span>
                </div>
                
                <h1 class="font-heading text-4xl md:text-6xl font-black text-gray-900 leading-[1.1] mb-6">
                    {{ $project->title }}
                </h1>
                
                <p class="text-lg text-gray-600 leading-relaxed font-sans mb-8 border-l-4 border-yellow-400 pl-6 whitespace-pre-line">
                    "{{ $project->description }}"
                </p>

                @if(!empty($project->impact_stats))
                <div class="grid grid-cols-3 gap-4 border-t border-gray-200 pt-8">
                    @foreach($project->impact_stats as $stat)
                        @if(!empty($stat['value']))
                        <div>
                            <span class="block text-2xl font-black text-gray-900">{{ $stat['value'] }}</span>
                            <span class="text-[10px] uppercase tracking-wider text-gray-400 font-bold">{{ $stat['label'] }}</span>
                        </div>
                        @endif
                    @endforeach
                </div>
                @endif
            </div>

            <div class="order-1 lg:order-2 relative group">
                <div class="absolute inset-0 bg-red-600 rounded-[2.5rem] rotate-3 opacity-20 group-hover:rotate-6 transition duration-500"></div>
                <div class="relative overflow-hidden rounded-[2.5rem] shadow-2xl aspect-[4/3] border-4 border-white bg-gray-200">
                    @if($project->cover_img)
                        <img src="{{ Str::startsWith($project->cover_img, 'http') ? $project->cover_img : asset('storage/' . $project->cover_img) }}" 
                             class="w-full h-full object-cover transform group-hover:scale-105 transition duration-700">
                    @else
                        <div class="flex items-center justify-center h-full text-gray-400 font-bold bg-gray-100">NO IMAGE</div>
                    @endif
                    <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent"></div>
                    
                    <div class="absolute bottom-6 left-6 bg-white/90 backdrop-blur px-4 py-2 rounded-xl flex items-center gap-2 shadow-lg">
                        <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        <div>
                            <p class="text-[10px] text-gray-400 font-bold uppercase">Location</p>
                            <p class="text-xs font-bold text-gray-900">{{ $project->location ?? 'Various Locations' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    {{-- 3. DETAILS & GALLERY GRID --}}
    <div class="max-w-7xl mx-auto px-6 pb-24 grid lg:grid-cols-12 gap-12">
        
        {{-- LEFT SIDEBAR --}}
        <aside class="lg:col-span-4 space-y-8">
            
            {{-- 1. QUICK INFO CARD --}}
            <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 relative overflow-hidden">
                {{-- [NEW] Academic Year Badge --}}
                <div class="absolute top-0 right-0 bg-yellow-400 text-yellow-900 text-[9px] font-black uppercase px-2 py-1 rounded-bl-lg">
                    AY {{ $project->academicYear->name ?? 'TBA' }}
                </div>

                <h3 class="font-bold text-gray-900 uppercase tracking-widest text-xs border-b border-gray-100 pb-3 mb-4">
                    Project Details
                </h3>
                
                <ul class="space-y-4">
                    {{-- Proponents (Updated Loop) --}}
                    <li class="flex items-start gap-3">
                        <div class="w-8 h-8 rounded-full bg-red-50 text-red-600 flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        </div>
                        <div>
                            <span class="block text-[10px] font-bold text-gray-400 uppercase">Lead Proponents</span>
                            <div class="flex flex-wrap gap-1 mt-1">
                                @foreach($project->proponents as $proponent)
                                    <span class="text-xs font-bold text-gray-800 bg-gray-100 px-2 py-1 rounded-md border border-gray-200">
                                        {{ $proponent->name }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    </li>

                    {{-- Date --}}
                    <li class="flex items-start gap-3">
                        <div class="w-8 h-8 rounded-full bg-gray-50 text-gray-500 flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        </div>
                        <div>
                            <span class="block text-[10px] font-bold text-gray-400 uppercase">Implementation Date</span>
                            <span class="text-sm font-bold text-gray-800">
                                {{ $project->implementation_date ? $project->implementation_date->format('F d, Y') : 'TBA' }}
                            </span>
                        </div>
                    </li>

                    {{-- Status --}}
                    <li class="flex items-start gap-3">
                        <div class="w-8 h-8 rounded-full bg-yellow-50 text-yellow-600 flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <div>
                            <span class="block text-[10px] font-bold text-gray-400 uppercase">Current Status</span>
                            <span class="text-sm font-bold text-gray-800">{{ $project->status }}</span>
                        </div>
                    </li>
                </ul>
            </div>

            {{-- 2. PARTNERS CARD (Corrected for Hybrid Pivot) --}}
            @if($project->partners_list->isNotEmpty())
            <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100">
                <h3 class="font-bold text-gray-900 uppercase tracking-widest text-xs border-b border-gray-100 pb-3 mb-4">
                    In Partnership With
                </h3>
                <div class="flex flex-wrap gap-2">
                    {{-- Loop through the Accessor Array: ['name', 'role', 'is_official'] --}}
                    @foreach($project->partners_list as $partner)
                    <div class="inline-flex items-center rounded-lg border overflow-hidden {{ $partner['is_official'] ? 'border-blue-100 bg-blue-50' : 'border-gray-200 bg-gray-50' }}">
                        {{-- Icon --}}
                        <div class="px-2 py-1 {{ $partner['is_official'] ? 'bg-blue-100 text-blue-600' : 'bg-gray-200 text-gray-500' }}">
                            @if($partner['is_official'])
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                            @else
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            @endif
                        </div>
                        
                        {{-- Name --}}
                        <span class="px-2 py-1 text-xs font-bold text-gray-700">
                            {{ $partner['name'] }}
                        </span>

                        {{-- Role --}}
                        @if(!empty($partner['role']))
                        <span class="px-2 py-1 bg-white text-[9px] font-bold uppercase text-gray-400 border-l border-gray-100 tracking-wider">
                            {{ $partner['role'] }}
                        </span>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- 3. SDGs CARD --}}
            @if($project->sdgs->count() > 0)
            <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100">
                <h3 class="font-bold text-gray-900 uppercase tracking-widest text-xs border-b border-gray-100 pb-3 mb-4">
                    Targeted SDGs
                </h3>
                <div class="flex flex-col gap-2">
                    @foreach($project->sdgs as $sdg)
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-lg text-white font-black text-lg flex items-center justify-center shadow-sm"
                             style="background-color: {{ $sdg->color_hex }}">
                            {{ $sdg->number }}
                        </div>
                        <span class="text-xs font-bold text-gray-700 uppercase tracking-wide">
                            {{ $sdg->name }}
                        </span>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

        </aside>

        {{-- RIGHT CONTENT --}}
        <main class="lg:col-span-8 space-y-12">
            
            {{-- 4. OBJECTIVES SECTION (Fixed for Preview) --}}
            @if(count($objectives) > 0 && !empty($objectives[0]))
            <div class="bg-gradient-to-br from-gray-900 to-gray-800 text-white p-8 rounded-[2rem] shadow-lg relative overflow-hidden">
                {{-- Decorative Blob --}}
                <div class="absolute top-0 right-0 w-48 h-48 bg-white/10 rounded-full blur-3xl -mr-10 -mt-10"></div>
                
                {{-- Header --}}
                <h3 class="font-bold uppercase tracking-widest text-sm mb-6 text-yellow-400 relative z-10 flex items-center gap-2">
                    <span class="w-2 h-2 bg-yellow-400 rounded-full"></span>
                    Project Objectives
                </h3>
                
                {{-- List --}}
                <ul class="space-y-4 relative z-10">
                    @foreach($objectives as $obj)
                        {{-- Check if the line is not empty --}}
                        @if(!empty($obj)) 
                        <li class="flex items-start gap-3 group">
                            {{-- Icon --}}
                            <div class="w-6 h-6 rounded-full bg-white/10 flex items-center justify-center shrink-0 group-hover:bg-green-500 transition-colors duration-300">
                                <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                            
                            {{-- Text --}}
                            <span class="text-gray-200 text-sm md:text-base leading-relaxed group-hover:text-white transition-colors">
                                {{-- FIX: Output the string directly, not as an object property --}}
                                {{ $obj }}
                            </span>
                        </li>
                        @endif
                    @endforeach
                </ul>
            </div>
            @endif

            @if($project->galleries->count() > 0)
                <div class="mt-12 bg-white rounded-3xl p-8 border border-gray-100 shadow-xl shadow-gray-200/50">
                    
                    {{-- Header --}}
                    <div class="flex items-center gap-3 mb-8">
                        <div class="p-2 bg-red-50 rounded-lg text-red-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        </div>
                        <div>
                            <h3 class="font-heading text-xl font-black text-gray-900 leading-tight">Project Gallery</h3>
                            <p class="text-sm text-gray-500 font-medium">{{ $project->galleries->count() }} Photos available</p>
                        </div>
                    </div>

                    {{-- ALPINE.JS LIGHTBOX COMPONENT --}}
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
                                document.body.style.overflow = 'hidden'; // Lock scroll
                            },
                            closeLightbox() {
                                this.lightboxOpen = false;
                                document.body.style.overflow = ''; // Unlock scroll
                            }
                        }"
                        @keydown.escape.window="closeLightbox()"
                    >
                        
                        {{-- 1. THUMBNAIL GRID --}}
                        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                            @foreach($project->galleries as $photo)
                                <div class="group relative aspect-square overflow-hidden rounded-2xl cursor-pointer bg-gray-100 ring-1 ring-black/5 hover:ring-red-500/50 hover:shadow-lg transition-all duration-300"
                                    @click="openLightbox('{{ asset('storage/'.$photo->image_path) }}', '{{ addslashes($photo->title) }}', '{{ addslashes($photo->description) }}')">
                                    
                                    {{-- Image --}}
                                    <img src="{{ asset('storage/'.$photo->image_path) }}" 
                                        loading="lazy"
                                        class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-700 ease-in-out"
                                        alt="{{ $photo->title }}">
                                    
                                    {{-- Gradient Overlay (Visible on Hover) --}}
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex flex-col justify-end p-4">
                                        @if($photo->title)
                                            <p class="text-white text-xs font-bold line-clamp-1">{{ $photo->title }}</p>
                                        @endif
                                        <p class="text-white/70 text-[10px] uppercase tracking-wider font-bold mt-1 flex items-center gap-1">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                            View Photo
                                        </p>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        {{-- 2. FULL SCREEN LIGHTBOX MODAL --}}
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

                                {{-- Close Button --}}
                                <button @click="closeLightbox()" class="absolute top-6 right-6 text-white/50 hover:text-white bg-white/10 hover:bg-white/20 rounded-full p-2 transition z-50">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                </button>

                                {{-- Content Container --}}
                                <div class="relative w-full max-w-6xl h-full flex flex-col items-center justify-center p-4 md:p-10" @click.outside="closeLightbox()">
                                    
                                    {{-- The Image --}}
                                    <img :src="activeImage" 
                                        class="max-w-full max-h-[85vh] object-contain rounded shadow-2xl animate-in zoom-in-95 duration-300">
                                    
                                    {{-- The Caption --}}
                                    <div class="absolute bottom-6 left-0 right-0 text-center pointer-events-none" x-show="activeTitle || activeDesc">
                                        <div class="inline-block bg-black/60 backdrop-blur-md px-6 py-4 rounded-2xl max-w-2xl mx-4 pointer-events-auto text-left">
                                            <h4 x-show="activeTitle" x-text="activeTitle" class="text-white font-bold text-lg mb-1"></h4>
                                            <p x-show="activeDesc" x-text="activeDesc" class="text-gray-300 text-sm leading-relaxed"></p>
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
    <footer class="bg-white border-t border-gray-200 py-12">
        <div class="max-w-7xl mx-auto px-6 text-center text-xs text-gray-500">
            &copy; 2025 BU MADYA. All Rights Reserved.
        </div>
    </footer>
</div>