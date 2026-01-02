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
                <div class="absolute top-0 right-0 bg-yellow-400 text-yellow-900 text-[8px] md:text-[9px] font-black uppercase px-2 py-1 rounded-bl-lg">
                    AY {{ $project->academicYear->name ?? 'TBA' }}
                </div>

                <h3 class="font-bold text-gray-900 uppercase tracking-widest text-[10px] md:text-xs border-b border-gray-100 pb-3 mb-4">
                    Project Details
                </h3>
                
                <ul class="space-y-3 md:space-y-4">
                    {{-- Proponents --}}
                    <li class="flex items-start gap-3">
                        <div class="w-6 h-6 md:w-8 md:h-8 rounded-full bg-red-50 text-red-600 flex items-center justify-center shrink-0">
                            <svg class="w-3 h-3 md:w-4 md:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        </div>
                        <div>
                            <span class="block text-[8px] md:text-[10px] font-bold text-gray-400 uppercase">Lead Proponents</span>
                            <div class="flex flex-wrap gap-1 mt-1">
                                @foreach($project->proponents as $proponent)
                                    <span class="text-[10px] md:text-xs font-bold text-gray-800 bg-gray-100 px-1.5 py-0.5 md:px-2 md:py-1 rounded-md border border-gray-200">
                                        {{ $proponent->name }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    </li>

                    {{-- Date --}}
                    <li class="flex items-start gap-3">
                        <div class="w-6 h-6 md:w-8 md:h-8 rounded-full bg-gray-50 text-gray-500 flex items-center justify-center shrink-0">
                            <svg class="w-3 h-3 md:w-4 md:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        </div>
                        <div>
                            <span class="block text-[8px] md:text-[10px] font-bold text-gray-400 uppercase">Implementation Date</span>
                            <span class="text-xs md:text-sm font-bold text-gray-800">
                                {{ $project->implementation_date ? $project->implementation_date->format('F d, Y') : 'TBA' }}
                            </span>
                        </div>
                    </li>

                    {{-- Status --}}
                    <li class="flex items-start gap-3">
                        <div class="w-6 h-6 md:w-8 md:h-8 rounded-full bg-yellow-50 text-yellow-600 flex items-center justify-center shrink-0">
                            <svg class="w-3 h-3 md:w-4 md:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <div>
                            <span class="block text-[8px] md:text-[10px] font-bold text-gray-400 uppercase">Current Status</span>
                            <span class="text-xs md:text-sm font-bold text-gray-800">{{ $project->status }}</span>
                        </div>
                    </li>
                </ul>
            </div>

            {{-- 2. PARTNERS CARD --}}
            @if($project->partners_list->isNotEmpty())
            <div class="bg-white p-5 md:p-6 rounded-2xl md:rounded-3xl shadow-sm border border-gray-100">
                <h3 class="font-bold text-gray-900 uppercase tracking-widest text-[10px] md:text-xs border-b border-gray-100 pb-3 mb-4">
                    In Partnership With
                </h3>
                <div class="flex flex-wrap gap-2">
                    @foreach($project->partners_list as $partner)
                    <div class="inline-flex items-center rounded-lg border overflow-hidden {{ $partner['is_official'] ? 'border-blue-100 bg-blue-50' : 'border-gray-200 bg-gray-50' }}">
                        <div class="px-1.5 py-0.5 md:px-2 md:py-1 {{ $partner['is_official'] ? 'bg-blue-100 text-blue-600' : 'bg-gray-200 text-gray-500' }}">
                            <svg class="w-2.5 h-2.5 md:w-3 md:h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                        </div>
                        <span class="px-1.5 py-0.5 md:px-2 md:py-1 text-[10px] md:text-xs font-bold text-gray-700">
                            {{ $partner['name'] }}
                        </span>
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
    <footer class="bg-white border-t border-gray-200 py-8 md:py-12">
        <div class="max-w-7xl mx-auto px-6 text-center text-[10px] md:text-xs text-gray-500">
            &copy; 2025 BU MADYA. All Rights Reserved.
        </div>
    </footer>
</div>