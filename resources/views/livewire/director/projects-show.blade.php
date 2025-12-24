<div class="min-h-screen bg-stone-50 font-sans text-gray-900">
    
    {{-- 1. NAVIGATION BAR (Sticky) --}}
    <div class="fixed top-0 left-0 w-full z-50 bg-white/90 backdrop-blur-md border-b border-gray-200 h-16 flex items-center justify-between px-6 transition-all duration-300">
        <a href="{{ route('projects.index') }}" class="group flex items-center gap-2 text-xs font-bold uppercase tracking-widest text-gray-500 hover:text-red-600 transition">
            <div class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center group-hover:bg-red-50 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </div>
            <span class="hidden md:inline">Back to Projects</span>
        </a>

        <span class="font-heading font-black text-lg tracking-tighter text-gray-900">
            Project <span class="text-red-600">Spotlight</span>
        </span>

        {{-- Status Badge --}}
        <div class="flex items-center gap-2">
            @if($project['status'] === 'Completed')
                <span class="px-3 py-1 bg-green-100 text-green-700 text-[10px] font-black uppercase tracking-widest rounded-full border border-green-200 shadow-sm flex items-center gap-1">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg> 
                    Completed
                </span>
            @elseif($project['status'] === 'Ongoing')
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
            
            {{-- Text Content --}}
            <div class="order-2 lg:order-1">
                <div class="mb-6 flex items-center gap-3">
                     <span class="w-10 h-1 bg-gradient-to-r from-red-600 to-yellow-500 rounded-full"></span>
                     <span class="text-xs font-bold text-gray-500 uppercase tracking-widest">{{ $project['cat'] }}</span>
                </div>
                
                <h1 class="font-heading text-4xl md:text-6xl font-black text-gray-900 leading-[1.1] mb-6">
                    {{ $project['title'] }}
                </h1>
                
                <p class="text-lg text-gray-600 leading-relaxed font-serif mb-8 border-l-4 border-yellow-400 pl-6">
                    "{{ $project['description'] }}"
                </p>

                {{-- Impact Stats --}}
                <div class="grid grid-cols-3 gap-4 border-t border-gray-200 pt-8">
                    @foreach($project['impact_stats'] as $stat)
                    <div>
                        <span class="block text-2xl font-black text-gray-900">{{ $stat['value'] }}</span>
                        <span class="text-[10px] uppercase tracking-wider text-gray-400 font-bold">{{ $stat['label'] }}</span>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Main Image --}}
            <div class="order-1 lg:order-2 relative group">
                <div class="absolute inset-0 bg-red-600 rounded-[2.5rem] rotate-3 opacity-20 group-hover:rotate-6 transition duration-500"></div>
                <div class="relative overflow-hidden rounded-[2.5rem] shadow-2xl aspect-[4/3] border-4 border-white">
                    <img src="{{ $project['img'] }}" class="w-full h-full object-cover transform group-hover:scale-105 transition duration-700">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent"></div>
                    
                    {{-- Floating Location Badge --}}
                    <div class="absolute bottom-6 left-6 bg-white/90 backdrop-blur px-4 py-2 rounded-xl flex items-center gap-2 shadow-lg">
                        <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        <div>
                            <p class="text-[10px] text-gray-400 font-bold uppercase">Location</p>
                            <p class="text-xs font-bold text-gray-900">{{ $project['location'] }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    {{-- 3. DETAILS & GALLERY GRID --}}
    <div class="max-w-7xl mx-auto px-6 pb-24 grid lg:grid-cols-12 gap-12">
        
        {{-- LEFT SIDEBAR: Project Meta --}}
        <aside class="lg:col-span-4 space-y-8">
            
            {{-- 1. QUICK INFO CARD (Updated with Proponent) --}}
            <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100">
                <h3 class="font-bold text-gray-900 uppercase tracking-widest text-xs border-b border-gray-100 pb-3 mb-4">
                    Project Details
                </h3>
                
                <ul class="space-y-4">
                    {{-- Proponent --}}
                    <li class="flex items-start gap-3">
                        <div class="w-8 h-8 rounded-full bg-red-50 text-red-600 flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        </div>
                        <div>
                            <span class="block text-[10px] font-bold text-gray-400 uppercase">Lead Proponent</span>
                            <span class="text-sm font-bold text-gray-800">{{ $project['proponent'] }}</span>
                        </div>
                    </li>

                    {{-- Date --}}
                    <li class="flex items-start gap-3">
                        <div class="w-8 h-8 rounded-full bg-gray-50 text-gray-500 flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        </div>
                        <div>
                            <span class="block text-[10px] font-bold text-gray-400 uppercase">Implementation Date</span>
                            <span class="text-sm font-bold text-gray-800">{{ $project['date'] }}</span>
                        </div>
                    </li>

                    {{-- Status --}}
                    <li class="flex items-start gap-3">
                        <div class="w-8 h-8 rounded-full bg-yellow-50 text-yellow-600 flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <div>
                            <span class="block text-[10px] font-bold text-gray-400 uppercase">Current Status</span>
                            <span class="text-sm font-bold text-gray-800">{{ $project['status'] }}</span>
                        </div>
                    </li>
                </ul>
            </div>

            {{-- 2. PARTNERS CARD (New) --}}
            <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100">
                <h3 class="font-bold text-gray-900 uppercase tracking-widest text-xs border-b border-gray-100 pb-3 mb-4">
                    In Partnership With
                </h3>
                <div class="flex flex-wrap gap-2">
                    @foreach($project['partners'] as $partner)
                    <span class="px-3 py-1 bg-gray-50 text-gray-600 text-xs font-bold rounded-lg border border-gray-200">
                        {{ $partner }}
                    </span>
                    @endforeach
                </div>
            </div>

            {{-- 3. SDGs CARD (New) --}}
            <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100">
                <h3 class="font-bold text-gray-900 uppercase tracking-widest text-xs border-b border-gray-100 pb-3 mb-4">
                    Targeted SDGs
                </h3>
                <div class="flex flex-col gap-2">
                    @foreach($project['sdgs'] as $sdg)
                    <div class="flex items-center gap-3">
                        {{-- SDG Colored Box --}}
                        <div class="w-10 h-10 {{ $sdg['color'] }} rounded-lg text-white font-black text-lg flex items-center justify-center shadow-sm">
                            {{ $sdg['id'] }}
                        </div>
                        <span class="text-xs font-bold text-gray-700 uppercase tracking-wide">
                            {{ $sdg['label'] }}
                        </span>
                    </div>
                    @endforeach
                </div>
            </div>

        </aside>

        {{-- RIGHT CONTENT --}}
        <main class="lg:col-span-8 space-y-12">
            
            {{-- 4. OBJECTIVES SECTION (Updated Design) --}}
            <div class="bg-gradient-to-br from-gray-900 to-gray-800 text-white p-8 rounded-[2rem] shadow-lg relative overflow-hidden">
                <div class="absolute top-0 right-0 w-48 h-48 bg-white/10 rounded-full blur-3xl -mr-10 -mt-10"></div>
                
                <h3 class="font-bold uppercase tracking-widest text-sm mb-6 text-yellow-400 relative z-10 flex items-center gap-2">
                    <span class="w-2 h-2 bg-yellow-400 rounded-full"></span>
                    Project Objectives
                </h3>
                
                <ul class="space-y-4 relative z-10">
                    @foreach($project['objectives'] as $obj)
                    <li class="flex items-start gap-3 group">
                        <div class="w-6 h-6 rounded-full bg-white/10 flex items-center justify-center shrink-0 group-hover:bg-green-500 transition-colors duration-300">
                            <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                        </div>
                        <span class="text-gray-200 text-sm md:text-base leading-relaxed group-hover:text-white transition-colors">
                            {{ $obj }}
                        </span>
                    </li>
                    @endforeach
                </ul>
            </div>

            {{-- Gallery Section (Keep existing code) --}}
            <div>
                 <h3 class="font-bold text-gray-900 uppercase tracking-widest text-xs border-b-2 border-green-500 w-16 pb-2 mb-6">Gallery</h3>
                 {{-- ... gallery code ... --}}
            </div>

        </main>
    </div>

    {{-- FOOTER --}}
    <footer class="bg-white border-t border-gray-200 py-12">
        <div class="max-w-7xl mx-auto px-6 text-center text-xs text-gray-500">
            &copy; 2025 BU MADYA. All Rights Reserved.
        </div>
    </footer>
</div>
