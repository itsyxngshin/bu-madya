<div class="min-h-screen bg-gray-50 font-sans text-gray-900 pb-20 relative overflow-x-hidden">
    
    {{-- 1. BACKGROUND BLOBS (Splash of Colors) --}}
    <div class="fixed inset-0 z-0 overflow-hidden pointer-events-none">
        <div class="absolute top-[-10%] left-[-10%] w-[500px] h-[500px] bg-red-200 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob"></div>
        <div class="absolute top-[-10%] right-[-10%] w-[500px] h-[500px] bg-yellow-200 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob animation-delay-2000"></div>
        <div class="absolute bottom-[-10%] left-[20%] w-[500px] h-[500px] bg-pink-200 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob animation-delay-4000"></div>
    </div>

    {{-- HEADER: THE HALL --}}
    <div class="bg-gray-900 text-white pt-12 pb-32 md:pb-40 px-6 rounded-b-[3rem] shadow-2xl relative overflow-hidden z-10">
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
    <div class="max-w-3xl mx-auto px-4 -mt-16 md:-mt-24 relative z-20">
        
        {{-- TOOLBAR (Floating) --}}
        <div class="bg-white/80 backdrop-blur-xl p-3 md:p-4 rounded-[2rem] shadow-xl border border-white/50 mb-8 flex flex-col md:flex-row gap-3">
            
            {{-- Search --}}
            <div class="relative w-full group">
                <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400 group-focus-within:text-red-500 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                </div>
                <input wire:model.live.debounce="search" type="text" placeholder="Find a discussion..." 
                       class="w-full pl-12 pr-4 py-3.5 bg-gray-50 border-transparent rounded-[1.5rem] text-sm font-medium focus:bg-white focus:border-red-100 focus:ring-4 focus:ring-red-50 transition-all placeholder-gray-400 shadow-inner">
            </div>
            
            {{-- Start Topic Button --}}
            <button wire:click="$set('isCreateModalOpen', true)" 
                    class="w-full md:w-auto px-8 py-3.5 bg-gray-900 text-white font-bold text-xs uppercase tracking-widest rounded-[1.5rem] hover:bg-red-600 hover:shadow-lg hover:-translate-y-0.5 transition-all flex items-center justify-center gap-3 shrink-0 group">
                <span>New Topic</span>
                <div class="bg-white/20 rounded-full p-1 group-hover:bg-white/30 transition">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                </div>
            </button>
        </div>

        {{-- TOPICS LIST --}}
        <div class="space-y-5">
            @forelse($topics as $topic)
            <div class="group relative bg-white/90 backdrop-blur-md rounded-[2.5rem] p-1 shadow-sm hover:shadow-xl border border-white/60 transition-all duration-300 hover:-translate-y-1">
                
                {{-- Inner Card Body --}}
                <div class="bg-white/60 rounded-[2rem] p-6 relative overflow-hidden z-10 h-full flex flex-col justify-between">
                    
                    {{-- Pinned Badge --}}
                    @if($topic->is_pinned)
                        <div class="absolute -top-3 -right-3">
                            <div class="bg-yellow-400 text-yellow-900 text-[10px] font-black uppercase tracking-wider py-2 px-6 rounded-bl-3xl shadow-sm flex items-center gap-1 pt-4 pr-5">
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path d="M5 4a2 2 0 012-2h6a2 2 0 012 2v14l-5-2.5L5 18V4z"></path></svg>
                                Pinned
                            </div>
                        </div>
                    @endif

                    <div class="flex gap-4 md:gap-5">
                        {{-- Avatar (The Host) --}}
                        <div class="flex flex-col items-center gap-2 shrink-0">
                            <div class="p-1 rounded-full border-2 border-dashed border-red-200 group-hover:border-red-500 transition-colors duration-500">
                                <img src="{{ asset($topic->user->profile_photo_path) }}" class="w-10 h-10 md:w-12 md:h-12 rounded-full object-cover">
                            </div>
                            <span class="text-[8px] md:text-[9px] font-bold uppercase text-gray-400 tracking-wider">Host</span>
                        </div>

                        {{-- Content --}}
                        <div class="flex-1 pt-1 min-w-0"> {{-- min-w-0 prevents text overflow issues --}}
                            <a href="{{ route('roundtable.show', $topic->id) }}" class="block">
                                <h3 class="font-heading font-black text-lg md:text-xl text-gray-900 leading-tight mb-2 group-hover:text-red-600 transition-colors truncate">
                                    {{ $topic->headline }}
                                </h3>
                            </a>
                            <p class="text-xs md:text-sm text-gray-500 leading-relaxed line-clamp-2 mb-4">
                                {{ Str::limit($topic->content, 140) }}
                            </p>

                            {{-- Footer Meta --}}
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between border-t border-gray-200/50 pt-4 gap-3">
                                <div class="flex items-center gap-4 text-xs font-bold text-gray-400">
                                    <span class="flex items-center gap-1.5">
                                        <svg class="w-3.5 h-3.5 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        {{ $topic->created_at->diffForHumans(null, true, true) }}
                                    </span>
                                </div>

                                {{-- "Seats" Badge --}}
                                <div class="flex items-center gap-2 self-start sm:self-auto">
                                    <div class="flex -space-x-2 mr-2">
                                        @if($topic->roundtable_replies_count > 0)
                                            <div class="w-6 h-6 rounded-full bg-gray-200 border-2 border-white flex items-center justify-center text-[8px] font-bold text-gray-500">
                                                +{{ $topic->roundtable_replies_count }}
                                            </div>
                                        @endif
                                    </div>
                                    <span class="px-3 py-1 bg-gray-50 rounded-full text-[10px] md:text-xs font-bold text-gray-600 group-hover:bg-red-50 group-hover:text-red-600 transition-colors">
                                        {{ $topic->roundtable_replies_count }} {{ Str::plural('Seat', $topic->roundtable_replies_count) }} Taken
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="text-center py-24 px-6 bg-white/50 backdrop-blur-sm rounded-[2.5rem] border border-white shadow-sm">
                <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6 animate-pulse">
                    <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                </div>
                <h3 class="font-heading font-bold text-gray-900 text-lg mb-2">The Hall is Empty</h3>
                <p class="text-gray-500 text-sm mb-6">Be the first to pull up a chair and start a discussion.</p>
                <button wire:click="$set('isCreateModalOpen', true)" class="text-red-600 font-bold text-sm hover:underline">Start a Topic now &rarr;</button>
            </div>
            @endforelse
        </div>

        <div class="mt-8 px-2">
            {{ $topics->links() }}
        </div>
    </div>

    {{-- CREATE MODAL (Mobile Optimized) --}}
    @if($isCreateModalOpen)
    <div class="fixed inset-0 z-50 flex items-end md:items-center justify-center justify-items-center bg-gray-900/60 backdrop-blur-md"
         x-data x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
        
        {{-- Slide up from bottom on mobile, Fade center on desktop --}}
        <div class="bg-white w-full md:max-w-lg rounded-t-[2.5rem] md:rounded-[2.5rem] shadow-2xl p-8 relative overflow-hidden h-[85vh] md:h-auto flex flex-col"
             x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-full md:translate-y-4 md:scale-95" x-transition:enter-end="opacity-100 translate-y-0 md:scale-100">
            
            {{-- Decoration --}}
            <div class="absolute top-0 left-0 w-full h-2 bg-gradient-to-r from-red-500 to-yellow-500"></div>
            {{-- Mobile Pull Indicator --}}
            <div class="absolute top-4 left-1/2 -translate-x-1/2 w-12 h-1.5 bg-gray-200 rounded-full md:hidden"></div>

            <div class="text-center mb-8 mt-4 md:mt-0 shrink-0">
                <h2 class="font-heading font-black text-3xl text-gray-900 mb-2">Open a Table</h2>
                <p class="text-xs text-gray-500 uppercase tracking-widest font-bold">Start a new discussion</p>
            </div>
            
            <div class="space-y-5 flex-1 overflow-y-auto">
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase mb-1 ml-2">Title</label>
                    <input wire:model="headline" type="text" class="w-full bg-gray-50 border-gray-200 rounded-2xl px-5 py-3.5 text-sm font-bold focus:ring-2 focus:ring-red-500 focus:bg-white transition" placeholder="Topic Title (e.g. Campus Safety)">
                    @error('headline') <span class="text-red-500 text-xs ml-2 font-bold">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase mb-1 ml-2">Discussion</label>
                    <textarea wire:model="content" rows="8" class="w-full bg-gray-50 border-gray-200 rounded-2xl px-5 py-3.5 text-sm focus:ring-2 focus:ring-red-500 focus:bg-white transition resize-none" placeholder="What's on your mind? Elaborate here..."></textarea>
                    @error('content') <span class="text-red-500 text-xs ml-2 font-bold">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="flex justify-between items-center gap-4 mt-6 pt-6 border-t border-gray-100 shrink-0 pb-6 md:pb-0">
                <button wire:click="$set('isCreateModalOpen', false)" class="text-gray-400 font-bold text-xs uppercase tracking-widest hover:text-gray-600 transition">Cancel</button>
                <button wire:click="createTopic" class="px-8 py-3 bg-gray-900 text-white text-xs font-bold uppercase tracking-widest rounded-xl hover:bg-red-600 hover:shadow-lg transition transform hover:-translate-y-0.5 w-full md:w-auto">Post Topic</button>
            </div>
        </div>
    </div>
    @endif
</div>