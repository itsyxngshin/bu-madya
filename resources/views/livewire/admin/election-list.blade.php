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
                <input wire:model.live.debounce.300ms="search" type="text" class="w-full bg-white border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-orange-500 focus:border-orange-500 block pl-10 p-2.5 shadow-sm" placeholder="Search elections...">
            </div>
            
            <a href="{{ route('admin.elections.manage') }}" class="px-5 py-2.5 bg-gray-900 hover:bg-orange-600 text-white font-bold rounded-xl shadow-md transition-colors whitespace-nowrap flex items-center gap-2">
                <span>+</span> New Election
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
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($elections as $election)
            <div class="bg-white rounded-3xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow group flex flex-col">
                
                {{-- Cover Photo & Badge --}}
                <div class="relative h-40 bg-gray-100">
                    @if($election->cover_photo_path)
                        <img src="{{ asset('storage/'.$election->cover_photo_path) }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full bg-gradient-to-br from-gray-800 to-gray-900"></div>
                    @endif
                    
                    <div class="absolute top-4 left-4">
                        @if(now() < $election->voting_start)
                            <span class="px-3 py-1 bg-white/90 backdrop-blur-sm text-gray-800 text-[10px] uppercase font-black tracking-widest rounded-full shadow-sm">Upcoming</span>
                        @elseif(now() > $election->voting_end)
                            <span class="px-3 py-1 bg-gray-900/90 backdrop-blur-sm text-white text-[10px] uppercase font-black tracking-widest rounded-full shadow-sm">Completed</span>
                        @else
                            <span class="px-3 py-1 bg-green-500/90 backdrop-blur-sm text-white text-[10px] uppercase font-black tracking-widest rounded-full shadow-sm flex items-center gap-1.5">
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
                    <h3 class="text-lg font-black text-gray-900 tracking-tight leading-tight mb-1">{{ $election->title }}</h3>
                    <p class="text-xs text-gray-500 mb-4 line-clamp-2">{{ $election->description ?: 'No description provided.' }}</p>
                    
                    {{-- Stats --}}
                    <div class="flex gap-4 mt-auto mb-6">
                        <div>
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Candidates</p>
                            <p class="text-lg font-black text-gray-800">{{ $election->candidates_count }}</p>
                        </div>
                        <div class="w-px bg-gray-200"></div>
                        <div>
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Turnout</p>
                            <p class="text-lg font-black text-gray-800">{{ $election->voter_logs_count }}</p>
                        </div>
                    </div>
                    
                    {{-- Actions --}}
                    <div class="grid grid-cols-2 gap-2 mt-auto">
                        <a href="{{ route('admin.elections.edit', $election->slug) }}" class="text-center px-4 py-2 bg-gray-50 hover:bg-gray-100 text-gray-700 font-bold text-xs rounded-xl transition border border-gray-200">Edit Settings</a>
                        
                        {{-- PASSED SLUG HERE --}}
                        <a href="{{ route('admin.elections.vetting', $election->slug) }}" class="text-center px-4 py-2 bg-blue-50 hover:bg-blue-100 text-blue-700 font-bold text-xs rounded-xl transition border border-blue-200">Vet Candidates</a>
                        
                        {{-- PASSED SLUG HERE --}}
                        <a href="{{ route('admin.elections.results', $election->slug) }}" class="text-center px-4 py-2 bg-green-50 hover:bg-green-100 text-green-700 font-bold text-xs rounded-xl transition border border-green-200">Live Analytics</a>
                        
                        <a href="{{ route('elections.public-results', $election->slug) }}" target="_blank" class="text-center px-4 py-2 bg-orange-50 hover:bg-orange-100 text-orange-700 font-bold text-xs rounded-xl transition border border-orange-200">Public Page ↗</a>
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
    
    <div class="mt-6">
        {{ $elections->links() }}
    </div>

</div>