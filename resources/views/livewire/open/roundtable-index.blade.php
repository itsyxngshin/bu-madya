<div class="min-h-screen bg-gray-100 font-sans text-gray-900 pb-20 relative overflow-x-hidden">
    
    {{-- 1. BACKGROUND ATMOSPHERE --}}
    <div class="fixed inset-0 z-0 overflow-hidden pointer-events-none">
        <div class="absolute top-[-10%] left-[-10%] w-[500px] h-[500px] bg-red-200/40 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob"></div>
        <div class="absolute top-[-10%] right-[-10%] w-[500px] h-[500px] bg-yellow-200/40 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob animation-delay-2000"></div>
    </div>

    {{-- 2. HEADER (Dark, Heavy, & Sticky) --}}
    <div class="sticky top-0 z-30 bg-gray-900 border-b border-gray-800 shadow-xl">
        <div class="max-w-5xl mx-auto px-4 py-4 md:py-5">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                
                {{-- Title Section --}}
                <div class="flex items-center gap-3">
                    {{-- Icon Box --}}
                    <div class="bg-gradient-to-br from-red-600 to-red-700 text-white p-2 rounded-xl shadow-lg shadow-red-900/50 shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    </div>
                    
                    {{-- Text --}}
                    <div>
                        <h1 class="font-heading font-black text-xl md:text-2xl leading-none text-white tracking-tight">The Roundtable</h1>
                        <p class="text-[10px] md:text-xs text-gray-400 font-bold uppercase tracking-widest mt-1">Community Open Forum</p>
                    </div>
                </div>

                {{-- Action Bar (Mobile Optimized) --}}
                <div class="flex gap-2 w-full md:w-auto">
                    {{-- Search Input --}}
                    <div class="relative flex-1 md:w-72 group">
                        <input wire:model.live.debounce="search" type="text" placeholder="Search discussions..." 
                            class="w-full pl-9 pr-4 py-2.5 bg-gray-800/50 border border-gray-700 text-gray-200 rounded-lg text-sm focus:bg-gray-800 focus:border-red-500 focus:ring-1 focus:ring-red-500 transition-all placeholder-gray-500">
                        <svg class="w-4 h-4 text-gray-500 group-focus-within:text-red-500 absolute left-3 top-3 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>

                    {{-- Create Button --}}
                    <button wire:click="$set('isCreateModalOpen', true)" 
                        class="bg-red-600 hover:bg-red-500 text-white px-4 py-2.5 rounded-lg text-sm font-bold shadow-lg shadow-red-900/20 transition-all active:scale-95 flex items-center gap-2 shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        <span class="hidden sm:inline">New Topic</span>
                        <span class="sm:hidden">New</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- 3. FEED CONTENT --}}
    <div class="max-w-5xl mx-auto px-4 pt-6 relative z-10">
        
        <div class="flex flex-col gap-3">
            @forelse($topics as $topic)
                <a href="{{ route('roundtable.show', $topic->id) }}" class="block group">
                    <div class="bg-white rounded-lg border transition-all duration-200 hover:border-gray-400 hover:shadow-lg flex overflow-hidden
                        {{ $topic->is_pinned ? 'border-yellow-300 bg-yellow-50/30' : 'border-gray-200' }}">
                        
                        {{-- LEFT RAIL (Engagement Metrics - Kept slim for mobile) --}}
                        <div class="w-10 md:w-16 bg-gray-50/50 border-r border-gray-100 flex flex-col items-center justify-center p-2 gap-0.5 md:gap-1 shrink-0">
                            <svg class="w-4 h-4 md:w-5 md:h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path></svg>
                            <span class="text-[10px] md:text-xs font-bold {{ $topic->roundtable_replies_count > 0 ? 'text-gray-900' : 'text-gray-400' }}">
                                {{ $topic->roundtable_replies_count }}
                            </span>
                        </div>

                        {{-- MAIN CONTENT --}}
                        <div class="flex-1 p-3 md:p-4 min-w-0"> {{-- min-w-0 ensures text truncates correctly --}}
                            
                            {{-- Meta Header --}}
                            <div class="flex flex-wrap items-center gap-x-2 gap-y-1 mb-2 text-xs text-gray-500">
                                @if($topic->is_pinned)
                                    <span class="text-[9px] font-black uppercase tracking-wider text-yellow-700 bg-yellow-100 px-1.5 py-0.5 rounded border border-yellow-200 flex items-center gap-1">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path d="M5 4a2 2 0 012-2h6a2 2 0 012 2v14l-5-2.5L5 18V4z"></path></svg>
                                        Pinned
                                    </span>
                                @endif
                                
                                <div class="flex items-center gap-1.5">
                                    <img src="{{ asset($topic->user->profile_photo_path) }}" class="w-4 h-4 rounded-full object-cover ring-1 ring-gray-200">
                                    <span class="font-bold text-gray-700 truncate max-w-[100px]">{{ $topic->user->name }}</span>
                                </div>
                                <span class="text-gray-300">•</span>
                                <span class="whitespace-nowrap">{{ $topic->created_at->diffForHumans(null, true) }}</span>
                            </div>

                            {{-- Headline --}}
                            <h3 class="font-heading font-bold text-base md:text-lg text-gray-900 leading-tight mb-1.5 group-hover:text-red-600 transition-colors line-clamp-2">
                                {{ $topic->headline }}
                            </h3>

                            {{-- Preview Text --}}
                            <p class="text-xs md:text-sm text-gray-500 line-clamp-2 leading-relaxed">
                                {{ Str::limit($topic->content, 180) }}
                            </p>
                        </div>

                        {{-- RIGHT RAIL (Arrow - Desktop Only) --}}
                        <div class="hidden md:flex items-center justify-center px-4 text-gray-300 bg-gray-50/30">
                            <svg class="w-6 h-6 group-hover:text-red-500 group-hover:translate-x-1 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </div>
                    </div>
                </a>
            @empty
                {{-- Empty State (No changes needed, but styled for dark consistency) --}}
                <div class="bg-white rounded-xl p-8 md:p-12 text-center border border-dashed border-gray-300">
                    <div class="inline-block p-4 rounded-full bg-gray-50 text-gray-400 mb-4">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"></path></svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900">It's quiet here.</h3>
                    <p class="text-gray-500 text-sm mt-1 mb-6">Start the conversation.</p>
                    <button wire:click="$set('isCreateModalOpen', true)" class="text-red-600 font-bold text-sm hover:underline">Start a Topic &rarr;</button>
                </div>
            @endforelse

            <div class="mt-4 pb-12">
                {{ $topics->links() }}
            </div>
        </div>
    </div>

    {{-- 4. CREATE MODAL (Kept same logic, just minor style check) --}}
    @if($isCreateModalOpen)
        <div class="fixed inset-0 z-50 flex items-end md:items-center justify-center px-4 pb-4 md:pb-0 bg-gray-900/70 backdrop-blur-sm transition-opacity">
            <div class="bg-white w-full max-w-xl rounded-2xl shadow-2xl p-6 animate-fade-in-up border border-gray-100 relative">
                {{-- Close Button --}}
                <button wire:click="$set('isCreateModalOpen', false)" class="absolute top-4 right-4 text-gray-400 hover:text-gray-900 p-2">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>

                <h2 class="font-heading font-black text-2xl mb-1 text-gray-900">New Topic</h2>
                <p class="text-sm text-gray-500 mb-6">What's on your mind?</p>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wide mb-2">Title</label>
                        <input wire:model="headline" type="text" 
                            class="w-full border-gray-200 bg-gray-50 rounded-lg py-3 px-4 text-sm font-bold focus:bg-white focus:border-red-500 focus:ring-red-500 transition-all placeholder-gray-400" 
                            placeholder="An interesting headline...">
                        @error('headline') <span class="text-red-500 text-xs font-bold mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wide mb-2">Discussion</label>
                        <textarea wire:model="content" rows="6" 
                            class="w-full border-gray-200 bg-gray-50 rounded-lg py-3 px-4 text-sm focus:bg-white focus:border-red-500 focus:ring-red-500 transition-all placeholder-gray-400 resize-none" 
                            placeholder="Elaborate on your topic..."></textarea>
                        @error('content') <span class="text-red-500 text-xs font-bold mt-1 block">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="flex justify-end gap-3 mt-6 pt-4 border-t border-gray-100">
                    <button wire:click="$set('isCreateModalOpen', false)" class="px-5 py-2.5 text-xs font-bold uppercase tracking-wide text-gray-500 hover:bg-gray-100 rounded-lg transition-colors">Cancel</button>
                    <button wire:click="createTopic" class="px-6 py-2.5 bg-gray-900 text-white text-xs font-bold uppercase tracking-wide rounded-lg hover:bg-red-600 shadow-lg hover:shadow-red-200 transition-all">Post Topic</button>
                </div>
            </div>
        </div>
    @endif
</div>