<div>
    {{-- HERO HEADER (With Impact Stats) --}}
    <header class="relative h-[400px] flex items-center justify-center text-white overflow-hidden rounded-3xl shadow-xl mx-6 -mt-20 z-10">
        <div class="absolute inset-0 z-0">
            {{-- Use a static background for the header, or a featured project image --}}
            <img src="{{ asset('images/IMG_4095.JPG') }}"
                class="w-full h-full object-cover" alt="Projects Background">
            <div class="absolute inset-0 bg-gradient-to-r from-green-900/90 to-blue-900/80 mix-blend-multiply"></div>
        </div>

        {{-- ... (Header Content remains mostly the same, stats can be dynamic later if you calculate them in the component) ... --}}
        
        <div class="relative z-10 text-center px-4 mt-16">
            <h2 class="text-yellow-300 font-bold tracking-[0.3em] text-xs uppercase mb-2">Our Initiatives</h2>
            <h1 class="font-heading text-3xl md:text-5xl font-black uppercase tracking-tight mb-4 drop-shadow-lg">
                Projects & Engagements
            </h1>
            {{-- ... --}}
            @auth
                @if(in_array(Auth::user()->role->role_name ?? '', ['administrator', 'director', 'member']))
                    <div class="mt-8">
                        {{-- Make sure this route exists in your web.php --}}
                        <a href="{{ route('projects.create') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-white/20 backdrop-blur-md border border-white/40 rounded-full text-white text-xs font-bold uppercase tracking-widest hover:bg-white hover:text-red-600 transition shadow-lg">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                            Add Project
                        </a>
                    </div>
                @endif
            @endauth
        </div>
    </header>

    {{-- MAIN CONTENT --}}
    <div class="relative min-h-screen px-6 pb-24 mt-12 max-w-[1800px] w-[95%] mx-auto">
        
        {{-- Background Blobs --}}
        <div class="absolute inset-0 z-0 overflow-hidden pointer-events-none">
            <div class="absolute top-40 left-0 w-96 h-96 bg-blue-100 rounded-full mix-blend-multiply filter blur-3xl opacity-50"></div>
            <div class="absolute bottom-40 right-0 w-96 h-96 bg-green-100 rounded-full mix-blend-multiply filter blur-3xl opacity-50"></div>
        </div>

        {{-- FILTER BAR --}}
        <div class="relative z-10 mb-12 bg-white/40 backdrop-blur-md border border-white/50 rounded-2xl p-4 shadow-sm max-w-4xl mx-auto">
            <div class="flex flex-col md:flex-row gap-4 items-center justify-center">
                
                {{-- Label --}}
                <div class="flex items-center gap-2 text-gray-500 uppercase text-[10px] font-bold tracking-widest mr-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                    Filter By:
                </div>

                {{-- 1. Category Dropdown --}}
                <div class="relative w-full md:w-64 group">
                    <select wire:model.live="category" 
                        class="w-full bg-white/80 border-0 rounded-xl px-4 py-2.5 text-sm font-bold text-gray-700 focus:ring-2 focus:ring-red-500 shadow-sm cursor-pointer hover:bg-white transition appearance-none">
                        <option value="All">All Categories</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat }}">{{ $cat }}</option>
                        @endforeach
                    </select>
                    <div class="absolute inset-y-0 right-0 flex items-center px-3 pointer-events-none text-gray-500">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </div>
                </div>

                {{-- 2. Academic Year Dropdown (FIXED) --}}
                <div class="relative w-full md:w-48 group">
                    <select wire:model.live="academicYearId" 
                        class="w-full bg-white/80 border-0 rounded-xl px-4 py-2.5 text-sm font-bold text-gray-700 focus:ring-2 focus:ring-red-500 shadow-sm cursor-pointer hover:bg-white transition appearance-none">
                        <option value="All">All Years</option>
                        {{-- Iterate through the relationship models --}}
                        @foreach($this->academicYears as $ay)
                            {{-- Assuming 'year' is the name of the column e.g. "2024-2025" --}}
                            <option value="{{ $ay->id }}">{{ $ay->name }}</option>
                        @endforeach
                    </select>
                    <div class="absolute inset-y-0 right-0 flex items-center px-3 pointer-events-none text-gray-500">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </div>
                </div>

            </div>
        </div>

        {{-- PROJECTS GRID --}}
        <div class="relative z-10 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-8">
            
            @forelse($projects as $project)
            {{-- 
            WRAPPER:
            - Mobile: 'flex-row' (Side by Side) & 'h-32' (Fixed short height)
            - Desktop: 'md:flex-col' (Stacked) & 'md:h-auto' (Let content dictate height)
            --}}
            <div class="group bg-white rounded-2xl md:rounded-3xl overflow-hidden shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 border border-gray-100 flex flex-row md:flex-col h-32 md:h-auto">
                
                {{-- 
                IMAGE AREA:
                - Mobile: 'w-32' (Fixed width thumbnail)
                - Desktop: 'md:w-full md:h-56' (Full width banner image)
                --}}
                <div class="relative w-32 md:w-full h-full md:h-56 shrink-0 overflow-hidden">
                    <img src="{{ $project->cover_img ? asset('storage/' . $project->cover_img) : 'https://ui-avatars.com/api/?name='.urlencode($project->title).'&background=random' }}" 
                        class="w-full h-full object-cover group-hover:scale-105 transition duration-500"
                        alt="{{ $project->title }}">
                    
                    {{-- Dark Gradient (Desktop Only) --}}
                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent opacity-0 md:opacity-100 transition-opacity"></div>

                    {{-- Status Badge (Top Right) --}}
                    <div class="absolute top-2 left-2 md:top-4 md:right-4 md:left-auto">
                        {{-- Mobile: Simple Dot --}}
                        <div class="md:hidden w-2.5 h-2.5 rounded-full border border-white shadow-sm
                            {{ $project->status === 'Completed' ? 'bg-green-500' : ($project->status === 'Ongoing' ? 'bg-red-500 animate-pulse' : 'bg-yellow-400') }}">
                        </div>

                        {{-- Desktop: Full Badge --}}
                        <span class="hidden md:inline-flex px-3 py-1 text-[10px] font-bold uppercase tracking-widest rounded-full shadow-sm items-center gap-1 bg-white/90 backdrop-blur-sm
                            {{ $project->status === 'Completed' ? 'text-green-700' : ($project->status === 'Ongoing' ? 'text-red-600' : 'text-yellow-600') }}">
                            @if($project->status === 'Ongoing') <span class="w-1.5 h-1.5 rounded-full bg-red-500 animate-pulse"></span> @endif
                            {{ $project->status }}
                        </span>
                    </div>

                    {{-- Category Badge (Desktop Only - Bottom Left) --}}
                    <div class="absolute bottom-4 left-4 hidden md:block">
                        <span class="px-3 py-1 bg-black/50 backdrop-blur-md border border-white/20 text-white text-[10px] font-bold uppercase tracking-wider rounded-lg">
                            {{ $project->category?->name ?? 'General' }}
                        </span>
                    </div>
                </div>

                {{-- 
                CONTENT AREA:
                - Mobile: Compact padding, no description
                - Desktop: Spacious padding, full description
                --}}
                <div class="flex-1 p-3 md:p-6 flex flex-col justify-between bg-white">
                    <div>
                        {{-- Date / Meta --}}
                        <div class="flex items-center gap-2 mb-1 md:mb-3 text-[10px] md:text-xs font-medium text-gray-400">
                            <div class="flex items-center gap-1">
                                <svg class="w-3 h-3 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                <span>{{ $project->implementation_date ? $project->implementation_date->format('M d, Y') : 'TBA' }}</span>
                            </div>
                            @if($project->academicYear)
                                <span class="hidden md:inline-block px-1.5 py-0.5 rounded bg-gray-100 text-gray-600 font-bold">A.Y. {{ $project->academicYear->year }}</span>
                            @endif
                        </div>

                        {{-- Title --}}
                        <h3 class="font-heading font-bold text-sm md:text-xl text-gray-900 leading-tight md:leading-snug mb-1 md:mb-3 line-clamp-2 group-hover:text-red-600 transition-colors">
                            {{ $project->title }}
                        </h3>

                        {{-- Description (Hidden on Mobile) --}}
                        <p class="hidden md:block text-sm text-gray-500 leading-relaxed line-clamp-3 mb-4">
                            {{ Str::limit($project->description, 100) }}
                        </p>
                    </div>

                    {{-- Footer / Impact --}}
                    <div class="flex items-center justify-between mt-auto pt-2 border-t border-gray-50 md:border-none">
                        <div class="flex flex-col">
                            <span class="text-[8px] md:text-[10px] text-gray-400 font-bold uppercase tracking-wider">Beneficiaries</span>
                            <span class="text-xs md:text-sm font-bold text-gray-800 truncate max-w-[100px] md:max-w-none">
                                {{ $project->beneficiaries ?? 'Community' }}
                            </span>
                        </div>
                        
                        <a href="{{ route('projects.show', $project->slug ?? $project->id) }}" 
                        class="w-8 h-8 md:w-10 md:h-10 rounded-full bg-gray-50 flex items-center justify-center text-gray-400 group-hover:bg-red-600 group-hover:text-white transition-colors duration-300">
                            <svg class="w-4 h-4 md:w-5 md:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                        </a>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-span-1 md:col-span-3 text-center py-20 bg-white/50 rounded-3xl border border-dashed border-gray-300">
                <div class="inline-block p-4 rounded-full bg-gray-100 text-gray-400 mb-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                </div>
                <h3 class="font-bold text-gray-900 text-lg">No Projects Found</h3>
                <p class="text-sm text-gray-500 mt-1">Try adjusting your filters.</p>
            </div>
            @endforelse

        </div>
        <div class="mt-12 relative z-10">
            {{ $projects->links() }} 
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