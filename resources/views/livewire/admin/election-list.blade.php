<div class="max-w-7xl mx-auto py-8 px-4 font-sans pb-32">
    
    {{-- HEADER & SEARCH --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div>
            <h1 class="text-3xl font-black text-gray-900 tracking-tight">Elections Overview</h1>
            <p class="text-gray-500 font-medium">Manage and monitor all your electoral events.</p>
        </div>
        
        <div class="flex items-center gap-3 w-full md:w-auto">
            <div class="relative w-full md:w-64">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
                <input wire:model.live.debounce.300ms="search" type="text" class="w-full bg-white border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-orange-500 focus:border-orange-500 block pl-10 p-2.5 shadow-sm transition-colors" placeholder="Search elections...">
            </div>
            
            <a href="{{ route('admin.elections.manage') }}" class="px-5 py-2.5 bg-gray-900 hover:bg-orange-600 text-white font-bold rounded-xl shadow-md transition-colors whitespace-nowrap flex items-center gap-2">
                <span class="text-lg leading-none">+</span> New Election
            </a>
        </div>
    </div>

    {{-- FLASH MESSAGES --}}
    @if (session()->has('success'))
        <div class="mb-6 bg-green-50 text-green-700 p-4 rounded-xl border border-green-200 font-bold flex items-center gap-2 animate-fade-in-up">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            {{ session('success') }}
        </div>
    @endif
    @if (session()->has('error'))
        <div class="mb-6 bg-red-50 text-red-700 p-4 rounded-xl border border-red-200 font-bold flex items-center gap-2 animate-fade-in-up">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
            {{ session('error') }}
        </div>
    @endif

    {{-- ELECTION GRID --}}
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
        @forelse($elections as $election)
            {{-- ENHANCED CARD: Added hover lift and shadow boost --}}
            <div class="bg-white rounded-[2rem] shadow-sm border border-gray-200 overflow-hidden hover:shadow-xl hover:-translate-y-1 transition-all duration-300 group flex flex-col">
                
                {{-- Cover Photo & Badge --}}
                <div class="relative h-44 bg-gray-100 overflow-hidden">
                    @if($election->cover_photo_path)
                        <img src="{{ asset('storage/'.$election->cover_photo_path) }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    @else
                        <div class="w-full h-full bg-gradient-to-br from-gray-800 to-gray-900 group-hover:scale-105 transition-transform duration-500"></div>
                    @endif
                    
                    {{-- Gradient Overlay for better text readability --}}
                    <div class="absolute inset-0 bg-gradient-to-t from-black/40 to-transparent"></div>
                    
                    <div class="absolute top-4 left-4">
                        @if(now() < $election->voting_start)
                            <span class="px-3 py-1.5 bg-white/90 backdrop-blur-md text-gray-800 text-[10px] uppercase font-black tracking-widest rounded-full shadow-sm">Upcoming</span>
                        @elseif(now() > $election->voting_end)
                            <span class="px-3 py-1.5 bg-gray-900/90 backdrop-blur-md text-white text-[10px] uppercase font-black tracking-widest rounded-full shadow-sm">Completed</span>
                        @else
                            <span class="px-3 py-1.5 bg-green-500/90 backdrop-blur-md text-white text-[10px] uppercase font-black tracking-widest rounded-full shadow-sm flex items-center gap-1.5">
                                <span class="w-1.5 h-1.5 bg-white rounded-full animate-pulse"></span> Live
                            </span>
                        @endif
                    </div>

                    <div class="absolute top-4 right-4">
                        <button wire:click="deleteElection({{ $election->id }})" wire:confirm="Are you sure you want to delete this election?" class="w-8 h-8 bg-white/90 hover:bg-red-50 text-gray-400 hover:text-red-600 rounded-full flex items-center justify-center backdrop-blur-sm transition-colors shadow-sm opacity-0 group-hover:opacity-100">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        </button>
                    </div>
                </div>

                {{-- Content --}}
                <div class="p-6 flex-1 flex flex-col">
                    <h3 class="text-xl font-black text-gray-900 tracking-tight leading-tight mb-2 group-hover:text-orange-600 transition-colors">{{ $election->title }}</h3>
                    <p class="text-xs font-medium text-gray-500 mb-6 line-clamp-2 leading-relaxed">{{ $election->description ?: 'No description provided.' }}</p>
                    
                    {{-- ENHANCED STATS --}}
                    <div class="flex items-center gap-6 mt-auto mb-6 bg-gray-50 p-4 rounded-2xl border border-gray-100">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center shrink-0">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                            </div>
                            <div>
                                <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Candidates</p>
                                <p class="text-base font-black text-gray-800 leading-none mt-0.5">{{ $election->candidates_count }}</p>
                            </div>
                        </div>
                        <div class="w-px h-8 bg-gray-200"></div>
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-green-100 text-green-600 flex items-center justify-center shrink-0">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                            <div>
                                <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Turnout</p>
                                <p class="text-base font-black text-gray-800 leading-none mt-0.5">{{ $election->voter_logs_count }}</p>
                            </div>
                        </div>
                    </div>
                    
                    {{-- RESTRUCTURED ACTIONS GRID --}}
                    <div class="flex flex-col gap-2">
                        {{-- Admin Actions Row 1 --}}
                        <div class="grid grid-cols-2 gap-2">
                            <a href="{{ route('admin.elections.edit', $election->slug) }}" class="flex items-center justify-center gap-1.5 px-3 py-2.5 bg-gray-50 hover:bg-gray-100 text-gray-700 font-bold text-[11px] uppercase tracking-wider rounded-xl transition-colors border border-gray-200">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                Settings
                            </a>
                            <a href="{{ route('admin.elections.vetting', $election->slug) }}" class="flex items-center justify-center gap-1.5 px-3 py-2.5 bg-yellow-50 hover:bg-yellow-100 text-yellow-700 font-bold text-[11px] uppercase tracking-wider rounded-xl transition-colors border border-yellow-200">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                                Vetting
                            </a>
                        </div>
                        
                        {{-- Admin Actions Row 2 --}}
                        <div class="grid grid-cols-2 gap-2">
                            <a href="{{ route('admin.elections.results', $election->slug) }}" class="flex items-center justify-center gap-1.5 px-3 py-2.5 bg-green-50 hover:bg-green-100 text-green-700 font-bold text-[11px] uppercase tracking-wider rounded-xl transition-colors border border-green-200">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"></path></svg>
                                Analytics
                            </a>
                            {{-- NEW: Voter Logs --}}
                            <a href="{{ route('admin.elections.logs', $election->slug) }}" class="flex items-center justify-center gap-1.5 px-3 py-2.5 bg-red-50 hover:bg-red-100 text-red-700 font-bold text-[11px] uppercase tracking-wider rounded-xl transition-colors border border-red-200">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                Voter Logs
                            </a>
                        </div>

                        {{-- Public Action Row --}}
                        <a href="{{ route('elections.public-results', $election->slug) }}" target="_blank" class="w-full mt-1 flex items-center justify-center gap-2 px-4 py-3 bg-orange-500 hover:bg-orange-600 text-white font-black text-xs uppercase tracking-widest rounded-xl transition-colors shadow-sm">
                            Public Portal
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full bg-white rounded-3xl p-12 text-center border border-gray-200">
                <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                </div>
                <h3 class="text-xl font-black text-gray-900 mb-2">No Elections Found</h3>
                <p class="text-gray-500 mb-6">You haven't created any electoral events yet.</p>
                <a href="{{ route('admin.elections.manage') }}" class="inline-block px-6 py-3 bg-gray-900 text-white font-bold rounded-xl shadow-lg hover:bg-orange-600 transition">Create First Election</a>
            </div>
        @endforelse
    </div>
    
    <div class="mt-8">
        {{ $elections->links() }}
    </div>

</div>