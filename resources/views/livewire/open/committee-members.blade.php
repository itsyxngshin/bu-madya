<div class="min-h-screen bg-stone-50 font-sans text-gray-900">
    
    {{-- 1. NAVBAR (Consistent) --}}
    <nav class="fixed top-0 left-0 w-full z-50 bg-white/90 backdrop-blur-md border-b border-gray-200 h-16 flex items-center justify-between px-6 transition-all duration-300">
        <a href="{{ route('open.committees') }}" class="group flex items-center gap-2 text-xs font-bold uppercase tracking-widest text-gray-500 hover:text-red-600 transition">
            <div class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center group-hover:bg-red-50 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </div>
            <span class="hidden md:inline">Back to Committees</span>
        </a>

        <span class="font-heading font-black text-lg tracking-tighter text-gray-900">
            Committee <span class="text-red-600">Profile</span>
        </span>

        <div class="w-8"></div> {{-- Spacer for balance --}}
    </nav>

    {{-- 2. HERO HEADER --}}
    <header class="relative pt-32 pb-16 px-6 bg-white border-b border-gray-200">
        <div class="max-w-5xl mx-auto text-center">
            
            {{-- Icon --}}
            <div class="w-16 h-16 mx-auto bg-gradient-to-br from-red-600 to-yellow-500 rounded-2xl flex items-center justify-center text-white shadow-lg mb-6 transform -rotate-3">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $committee['icon'] }}"></path></svg>
            </div>

            <h1 class="font-heading text-4xl md:text-5xl font-black text-gray-900 leading-tight mb-4">
                {{ $committee['name'] }}
            </h1>
            
            <p class="text-gray-500 max-w-2xl mx-auto leading-relaxed text-sm md:text-base">
                {{ $committee['description'] }}
            </p>
        </div>
    </header>

    <div class="max-w-7xl mx-auto px-6 py-12">
        
        {{-- 3. LEADERSHIP SECTION --}}
        <div class="mb-16">
            <h3 class="font-bold text-gray-900 uppercase tracking-widest text-xs border-b border-gray-200 pb-2 mb-8 text-center">
                Committee Leadership
            </h3>

            <div class="flex flex-wrap justify-center gap-8">
                @foreach($committee['heads'] as $head)
                <div class="bg-white p-6 rounded-3xl shadow-lg border border-gray-100 flex items-center gap-5 w-full md:w-auto md:min-w-[350px] hover:-translate-y-1 transition duration-300">
                    <div class="w-20 h-20 rounded-full p-1 bg-gradient-to-br from-yellow-400 to-red-600">
                        <div class="w-full h-full rounded-full overflow-hidden bg-white">
                            <img src="{{ $head['img'] ?? 'https://ui-avatars.com/api/?name='.urlencode($head['name']).'&background=random&color=fff' }}" 
                                 class="w-full h-full object-cover">
                        </div>
                    </div>
                    <div>
                        <h4 class="font-heading font-bold text-lg text-gray-900">{{ $head['name'] }}</h4>
                        <p class="text-xs font-bold text-red-600 uppercase tracking-wider mb-1">{{ $head['role'] }}</p>
                        <p class="text-xs text-gray-500">{{ $head['course'] }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        {{-- 4. MEMBERS SEARCH BAR --}}
        <div class="flex flex-col md:flex-row justify-between items-end mb-8 gap-4">
            <div>
                <h3 class="font-heading font-bold text-2xl text-gray-900">Official Members</h3>
                <p class="text-sm text-gray-500">Active roster for A.Y. 2025-2026</p>
            </div>
            
            <div class="relative w-full md:w-72">
                <input wire:model.live="search" 
                       type="text" 
                       placeholder="Search member..." 
                       class="w-full pl-10 pr-4 py-2 bg-white border border-gray-200 rounded-full text-sm focus:ring-2 focus:ring-red-500 focus:border-red-500 transition shadow-sm">
                <svg class="absolute left-3.5 top-2.5 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>
        </div>

        {{-- 5. MEMBERS GRID --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @forelse($members as $member)
            <div class="group bg-white/60 backdrop-blur-sm border border-white/60 p-5 rounded-2xl shadow-sm hover:shadow-md hover:bg-white transition duration-300 flex items-start gap-4">
                
                {{-- Avatar --}}
                <div class="w-12 h-12 rounded-xl bg-gray-100 overflow-hidden shrink-0 group-hover:ring-2 ring-red-100 transition">
                    <img src="{{ $member['img'] ?? 'https://ui-avatars.com/api/?name='.urlencode($member['name']).'&background=random&color=fff&size=128' }}" 
                         class="w-full h-full object-cover">
                </div>

                {{-- Info --}}
                <div class="flex-grow min-w-0">
                    <h5 class="font-bold text-gray-900 text-sm truncate group-hover:text-red-700 transition">{{ $member['name'] }}</h5>
                    <p class="text-[10px] text-gray-500 font-bold uppercase tracking-wider mb-1 truncate">{{ $member['course'] }}</p>
                    
                    <div class="flex items-center gap-2 text-[10px] text-gray-500">
                        <span class="bg-gray-100 px-2 py-0.5 rounded font-bold text-gray-600">{{ $member['college'] }}</span>
                        <span class="truncate border-l border-gray-300 pl-2">{{ $member['year'] }}</span>
                    </div>
                </div>

            </div>
            @empty
            <div class="col-span-full py-12 text-center text-gray-400">
                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3">
                    <svg class="w-8 h-8 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                </div>
                No members found matching "{{ $search }}"
            </div>
            @endforelse
        </div>

    </div>

    {{-- FOOTER --}}
    <footer class="bg-white border-t border-gray-200 py-8 px-6 text-center text-xs text-gray-500">
        &copy; 2025 BU MADYA. All Rights Reserved.
    </footer>
</div>