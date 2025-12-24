<div>
    
    {{-- STICKY NAVBAR --}}
    <nav 
        x-data="{ scrolled: false }" 
        @scroll.window="scrolled = (window.pageYOffset > 20)"
        class="sticky top-0 z-30 transition-all duration-300 w-full"
        :class="scrolled ? 'bg-white/90 backdrop-blur-md shadow-md py-2' : 'bg-transparent py-4'"
    >
        <div class="px-6 flex justify-between items-center">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center shadow-lg overflow-hidden p-1">
                   <img src="{{ asset('images/official_logo.png') }}" alt="Logo" class="w-full h-full object-contain">
                </div>
                <span class="font-bold text-lg tracking-tight transition-colors duration-300"
                      :class="scrolled ? 'text-gray-800' : 'text-gray-800'">
                    BU MADYA
                </span>
            </div>
            <div class="hidden md:flex items-center space-x-6 text-sm font-semibold tracking-wide">
                {{-- Add your links here --}}
            </div>
        </div>
    </nav>

    {{-- HERO HEADER (With Impact Stats) --}}
    <header class="relative h-[400px] flex items-center justify-center text-white overflow-hidden rounded-3xl shadow-xl mx-6 -mt-20 z-10">
        <div class="absolute inset-0 z-0">
            <img src="https://images.unsplash.com/photo-1559027615-cd4628902d4a?q=80&w=2074&auto=format&fit=crop" 
                 class="w-full h-full object-cover" alt="Projects Background">
            <div class="absolute inset-0 bg-gradient-to-r from-green-900/90 to-blue-900/80 mix-blend-multiply"></div>
        </div>

        <div class="relative z-10 text-center px-4 mt-16">
            <h2 class="text-yellow-300 font-bold tracking-[0.3em] text-xs uppercase mb-2">Our Initiatives</h2>
            <h1 class="font-heading text-3xl md:text-5xl font-black uppercase tracking-tight mb-4 drop-shadow-lg">
                Projects & Engagements
            </h1>
            <p class="text-sm md:text-base text-gray-100 font-light max-w-xl mx-auto italic mb-8">
                Translating advocacy into tangible action for community development.
            </p>
            

            {{-- Impact Stats Row --}}
            <div class="inline-flex flex-wrap justify-center gap-4 md:gap-8 bg-white/10 backdrop-blur-md px-8 py-4 rounded-2xl border border-white/20">
                <div class="text-center">
                    <span class="block text-2xl font-black text-white">12</span>
                    <span class="text-[10px] uppercase tracking-wider text-gray-200">Active Projects</span>
                </div>
                <div class="hidden md:block w-px bg-white/20"></div>
                <div class="text-center">
                    <span class="block text-2xl font-black text-yellow-400">500+</span>
                    <span class="text-[10px] uppercase tracking-wider text-gray-200">Beneficiaries</span>
                </div>
                <div class="hidden md:block w-px bg-white/20"></div>
                <div class="text-center">
                    <span class="block text-2xl font-black text-green-400">5</span>
                    <span class="text-[10px] uppercase tracking-wider text-gray-200">Partner LGUs</span>
                </div>
            </div>

            <div class="mt-8">
                <a href="{{ route('projects.create') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-white/20 backdrop-blur-md border border-white/40 rounded-full text-white text-xs font-bold uppercase tracking-widest hover:bg-white hover:text-red-600 transition shadow-lg">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Add Project
                </a>
            </div>
        </div>
    </header>

    {{-- MAIN CONTENT --}}
    <div class="relative min-h-screen px-6 pb-24 mt-12 max-w-[1800px] w-[95%] mx-auto">
        
        {{-- Background Blobs --}}
        <div class="absolute inset-0 z-0 overflow-hidden pointer-events-none">
            <div class="absolute top-40 left-0 w-96 h-96 bg-blue-100 rounded-full mix-blend-multiply filter blur-3xl opacity-50"></div>
            <div class="absolute bottom-40 right-0 w-96 h-96 bg-green-100 rounded-full mix-blend-multiply filter blur-3xl opacity-50"></div>
        </div>

        {{-- LIVEWIRE FILTER BAR --}}
        <div class="relative z-10 flex flex-wrap justify-center gap-3 mb-12">
            @foreach(['All', 'Community Outreach', 'Capacity Building', 'Environmental', 'Policy Advocacy', 'Partnership'] as $filter)
            <button wire:click="setCategory('{{ $filter }}')"
                    class="px-5 py-2 rounded-full text-xs font-bold uppercase tracking-wider transition-all duration-300 border border-white/40 backdrop-blur-sm
                           {{ $category === $filter 
                                ? 'bg-red-600 text-white shadow-lg scale-105' 
                                : 'bg-white/60 text-gray-600 hover:bg-white hover:text-red-500' }}">
                {{ $filter }}
            </button>
            @endforeach
        </div>

        {{-- PROJECTS GRID --}}
        <div class="relative z-10 grid md:grid-cols-2 lg:grid-cols-3 gap-8">
            
            @forelse($projects as $project)
            <div class="group bg-white/60 backdrop-blur-md border border-white/60 rounded-3xl overflow-hidden shadow-lg hover:shadow-2xl hover:-translate-y-2 transition duration-300 flex flex-col h-full">
                
                {{-- Image Header --}}
                <div class="h-56 overflow-hidden relative">
                    <img src="{{ $project['img'] }}" class="w-full h-full object-cover group-hover:scale-110 transition duration-700">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                    
                    {{-- Status Badge --}}
                    <div class="absolute top-4 right-4">
                        @if($project['status'] === 'Completed')
                            <span class="px-3 py-1 bg-green-500 text-white text-[10px] font-bold uppercase tracking-widest rounded-full shadow-sm flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg> Completed
                            </span>
                        @elseif($project['status'] === 'Ongoing')
                            <span class="px-3 py-1 bg-red-600 text-white text-[10px] font-bold uppercase tracking-widest rounded-full shadow-sm flex items-center gap-1 animate-pulse">
                                <span class="w-2 h-2 bg-white rounded-full"></span> Ongoing
                            </span>
                        @else
                            <span class="px-3 py-1 bg-yellow-400 text-green-900 text-[10px] font-bold uppercase tracking-widest rounded-full shadow-sm">
                                Upcoming
                            </span>
                        @endif
                    </div>

                    {{-- Category Badge --}}
                    <div class="absolute bottom-4 left-4">
                        <span class="px-3 py-1 bg-white/20 backdrop-blur-md border border-white/30 text-white text-[10px] font-bold uppercase tracking-wider rounded-lg">
                            {{ $project['cat'] }}
                        </span>
                    </div>
                </div>

                {{-- Card Content --}}
                <div class="p-6 flex flex-col flex-grow">
                    <h3 class="font-heading font-bold text-xl text-gray-900 mb-2 leading-tight group-hover:text-red-600 transition">
                        {{ $project['title'] }}
                    </h3>
                    
                    {{-- Meta Data Row --}}
                    <div class="flex items-center gap-4 text-xs text-gray-500 mb-4 border-b border-gray-200/50 pb-4">
                        <div class="flex items-center gap-1">
                            <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            {{ $project['date'] }}
                        </div>
                        <div class="flex items-center gap-1">
                            <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            {{ $project['location'] }}
                        </div>
                    </div>

                    <p class="text-sm text-gray-600 mb-6 flex-grow leading-relaxed">
                        {{ $project['desc'] }}
                    </p>

                    {{-- Impact & Action Footer --}}
                    <div class="flex items-center justify-between mt-auto pt-2">
                        <div class="flex flex-col">
                            <span class="text-[10px] text-gray-400 font-bold uppercase">Impact</span>
                            <span class="text-sm font-bold text-green-600">{{ $project['beneficiaries'] }}</span>
                        </div>
                        
                        {{-- Link to details page in future --}}
                        <button class="w-10 h-10 rounded-full border border-gray-200 flex items-center justify-center text-gray-400 group-hover:bg-red-600 group-hover:text-white group-hover:border-red-600 transition duration-300 shadow-sm">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                        </button>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-span-3 text-center py-20">
                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4 text-gray-400">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <h3 class="font-bold text-gray-800">No Projects Found</h3>
                <p class="text-sm text-gray-500">There are no projects listed under the "{{ $category }}" category yet.</p>
            </div>
            @endforelse

        </div>

    </div>

    {{-- FOOTER --}}
    <footer class="mt-20 border-t border-gray-200 py-8 px-6 text-center text-xs text-gray-500 bg-white">
        &copy; 2025 BU MADYA. All Rights Reserved.
    </footer>

</div>