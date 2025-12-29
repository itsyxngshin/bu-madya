<div class="min-h-screen bg-gray-50 font-sans text-gray-900 pb-20 relative overflow-hidden">
    
    {{-- ROUNDTABLE BACKGROUND ACCENT --}}
    <div class="fixed top-0 left-1/2 -translate-x-1/2 w-[800px] h-[800px] bg-gray-200/50 rounded-full blur-3xl -z-10 pointer-events-none opacity-50"></div>

    {{-- HEADER: THE HALL --}}
    <div class="bg-gray-900 text-white pt-12 pb-24 px-6 rounded-b-[3rem] shadow-2xl relative overflow-hidden">
        {{-- Decorative Circle --}}
        <div class="absolute -top-24 -right-24 w-96 h-96 bg-gradient-to-br from-red-600 to-transparent rounded-full opacity-20 blur-2xl"></div>
        <div class="absolute top-10 -left-10 w-48 h-48 bg-yellow-500 rounded-full mix-blend-overlay filter blur-3xl opacity-10"></div>

        <div class="max-w-xl mx-auto text-center relative z-10">
            <div class="inline-flex items-center gap-2 bg-white/5 backdrop-blur-sm border border-white/10 px-4 py-1.5 rounded-full mb-6 shadow-inner">
                <span class="relative flex h-2 w-2">
                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-yellow-400 opacity-75"></span>
                  <span class="relative inline-flex rounded-full h-2 w-2 bg-yellow-500"></span>
                </span>
                <span class="text-[10px] font-bold uppercase tracking-widest text-gray-300">Open Forum</span>
            </div>
            
            <h1 class="font-heading font-black text-4xl md:text-6xl mb-4 tracking-tight leading-none">
                The <span class="text-transparent bg-clip-text bg-gradient-to-r from-red-500 to-yellow-500">Roundtable</span>
            </h1>
            
            <p class="text-gray-400 text-sm leading-relaxed max-w-sm mx-auto">
                Pull up a chair. A space for students to gather, debate, and shape the advocacy.
            </p>
        </div>
    </div>

    {{-- MAIN CONTENT: THE TABLES --}}
    <div class="max-w-3xl mx-auto px-4 -mt-16 relative z-20">
        
        {{-- TOOLBAR (Optimized for Mobile) --}}
        {{-- Changed: flex-col to flex-row to put them side-by-side on mobile --}}
        <div class="bg-white/80 backdrop-blur-xl p-2 md:p-3 rounded-[2rem] shadow-xl border border-white/50 mb-8 flex flex-row gap-2">
            
            {{-- Search --}}
            <div class="relative w-full group">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400 group-focus-within:text-red-500 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                </div>
                {{-- Changed: py-2.5 for mobile, py-3.5 for desktop to reduce height --}}
                <input wire:model.live.debounce="search" type="text" placeholder="Find a table discussion..." 
                       class="w-full pl-10 md:pl-12 pr-4 py-2.5 md:py-3.5 bg-gray-50 border-transparent rounded-[1.5rem] text-xs md:text-sm font-medium focus:bg-white focus:border-red-100 focus:ring-4 focus:ring-red-50 transition-all placeholder-gray-400">
            </div>
            
            {{-- Start Topic Button --}}
            {{-- Changed: w-auto to prevent full width, hidden text on mobile --}}
            <button wire:click="$set('isCreateModalOpen', true)" 
                    class="w-auto px-4 md:px-8 py-2.5 md:py-3.5 bg-gray-900 text-white font-bold text-xs uppercase tracking-widest rounded-[1.5rem] hover:bg-red-600 hover:shadow-lg hover:-translate-y-0.5 transition-all flex items-center justify-center gap-3 shrink-0 group">
                <span class="hidden md:inline">New Topic</span>
                <div class="bg-white/20 rounded-full p-1 group-hover:bg-white/30 transition">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                </div>
            </button>
        </div>

        {{-- TOPICS LIST --}}
        <div class="space-y-5">
            @forelse($topics as $topic)
            <div class="group relative bg-white rounded-[2.5rem] p-1 shadow-sm hover:shadow-xl border border-gray-100 transition-all duration-300 hover:-translate-y-1">
                
                {{-- Inner Card Body --}}
                <div class="bg-white rounded-[2rem] p-6 relative overflow-hidden z-10 h-full flex flex-col justify-between">
                    
                    {{-- Pinned Badge --}}
                    @if($topic->is_pinned)
                        <div class="absolute -top-3 -right-3">
                            <div class="bg-yellow-400 text-yellow-900 text-[10px] font-black uppercase tracking-wider py-2 px-6 rounded-bl-3xl shadow-sm flex items-center gap-1 pt-4 pr-5">
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path d="M5 4a2 2 0 012-2h6a2 2 0 012 2v14l-5-2.5L5 18V4z"></path></svg>
                                Pinned
                            </div>
                        </div>
                    @endif

                    <div class="flex gap-5">
                        {{-- Avatar (The Host) --}}
                        <div class="flex flex-col items-center gap-2 shrink-0">
                            <div class="p-1 rounded-full border-2 border-dashed border-red-200 group-hover:border-red-500 transition-colors duration-500">
                                <img src="{{ $reply->user->profile_photo_path 
                                            ? asset('storage/' . $reply->user->profile_photo_path) 
                                            : 'https://ui-avatars.com/api/?name=' . urlencode($reply->user->name) . '&color=7F9CF5&background=EBF4FF' }}" 
                                    alt="{{ $reply->user->name }}"
                                    class="w-12 h-12 rounded-full object-cover"> 
                            </div>
                            <span class="text-[9px] font-bold uppercase text-gray-400 tracking-wider">Host</span>
                        </div>

                        {{-- Content --}}
                        <div class="flex-1 pt-1">
                            <a href="{{ route('roundtable.show', $topic->id) }}" class="block">
                                <h3 class="font-heading font-black text-lg md:text-xl text-gray-900 leading-tight mb-2 group-hover:text-red-600 transition-colors">
                                    {{ $topic->headline }}
                                </h3>
                            </a>
                            <p class="text-sm text-gray-500 leading-relaxed line-clamp-2 mb-4">
                                {{ Str::limit($topic->content, 140) }}
                            </p>

                            {{-- Footer Meta --}}
                            <div class="flex items-center justify-between border-t border-gray-100 pt-4">
                                <div class="flex items-center gap-4 text-xs font-bold text-gray-400">
                                    <span class="flex items-center gap-1.5">
                                        <svg class="w-4 h-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        {{ $topic->created_at->diffForHumans(null, true, true) }}
                                    </span>
                                </div>

                                {{-- "Seats" Badge --}}
                                <div class="flex items-center gap-2">
                                    <div class="flex -space-x-2 mr-2">
                                        {{-- Simulate seats occupied --}}
                                        @if($topic->roundtable_replies_count > 0)
                                            <div class="w-6 h-6 rounded-full bg-gray-200 border-2 border-white flex items-center justify-center text-[8px] font-bold text-gray-500">
                                                +{{ $topic->roundtable_replies_count }}
                                            </div>
                                        @endif
                                    </div>
                                    <span class="px-3 py-1 bg-gray-50 rounded-full text-xs font-bold text-gray-600 group-hover:bg-red-50 group-hover:text-red-600 transition-colors">
                                        {{ $topic->roundtable_replies_count }} {{ Str::plural('Seat', $topic->roundtable_replies_count) }} Taken
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="text-center py-20">
                <div class="inline-block p-4 rounded-full bg-gray-100 text-gray-400 mb-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                </div>
                <p class="text-gray-500 font-medium">It's quiet here. Start a conversation!</p>
            </div>
            @endforelse
        </div>

        <div class="mt-6">
            {{ $topics->links() }}
        </div>
    </div>

    {{-- CREATE MODAL --}}
    @if($isCreateModalOpen)
    <div class="fixed inset-0 z-50 flex items-center justify-center px-4 bg-gray-900/60 backdrop-blur-sm">
        <div class="bg-white w-full max-w-lg rounded-2xl shadow-2xl p-6 md:p-8 animate-fade-in-up">
            <h2 class="font-heading font-black text-2xl mb-6">Start a Topic</h2>
            
            <div class="space-y-4">
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Topic Title</label>
                    <input wire:model="headline" type="text" class="w-full border-gray-200 rounded-xl text-sm focus:ring-red-500" placeholder="What's on your mind?">
                    @error('headline') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Content</label>
                    <textarea wire:model="content" rows="5" class="w-full border-gray-200 rounded-xl text-sm focus:ring-red-500" placeholder="Elaborate on your topic..."></textarea>
                    @error('content') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="flex justify-end gap-3 mt-8">
                <button wire:click="$set('isCreateModalOpen', false)" class="px-4 py-2 text-sm font-bold text-gray-500 hover:bg-gray-100 rounded-lg">Cancel</button>
                <button wire:click="createTopic" class="px-6 py-2 bg-red-600 text-white text-sm font-bold rounded-lg hover:bg-red-700 shadow-md">Post Topic</button>
            </div>
        </div>
    </div>
    @endif
</div>