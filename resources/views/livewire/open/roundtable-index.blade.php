<div class="min-h-screen bg-gray-100 font-sans text-gray-900 pb-20 relative overflow-x-hidden">
    
    {{-- 1. BACKGROUND ATMOSPHERE (Consistent with Show Page) --}}
    <div class="fixed inset-0 z-0 overflow-hidden pointer-events-none">
        <div class="absolute top-[-10%] left-[-10%] w-[500px] h-[500px] bg-red-200/40 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob"></div>
        <div class="absolute top-[-10%] right-[-10%] w-[500px] h-[500px] bg-yellow-200/40 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob animation-delay-2000"></div>
    </div>

    {{-- 2. HEADER & CONTROLS --}}
    <div class="sticky top-0 z-30 bg-gray-100/95 backdrop-blur-md border-b border-white/50 shadow-sm">
        <div class="max-w-5xl mx-auto px-4 py-4">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                
                {{-- Title --}}
                <div class="flex items-center gap-3">
                    <div class="bg-gradient-to-br from-red-600 to-red-500 text-white p-2.5 rounded-xl shadow-lg shadow-red-200">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    </div>
                    <div>
                        <h1 class="font-heading font-black text-2xl leading-none text-gray-900">The Roundtable</h1>
                        <p class="text-xs text-gray-500 font-bold uppercase tracking-wider mt-0.5">Community Open Forum</p>
                    </div>
                </div>

                {{-- Action Bar --}}
                <div class="flex gap-2 w-full md:w-auto">
                    {{-- Search --}}
                    <div class="relative flex-1 md:w-64">
                        <input wire:model.live.debounce="search" type="text" placeholder="Search discussions..." 
                            class="w-full pl-9 pr-4 py-2 bg-white border border-gray-200 rounded-lg text-sm focus:border-red-300 focus:ring-red-200 focus:ring-opacity-50 transition-all placeholder-gray-400">
                        <svg class="w-4 h-4 text-gray-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>

                    {{-- Create Button --}}
                    <button wire:click="$set('isCreateModalOpen', true)" 
                        class="bg-gray-900 hover:bg-red-600 text-white px-4 py-2 rounded-lg text-sm font-bold shadow-md transition-all flex items-center gap-2 shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        <span class="hidden sm:inline">New Topic</span>
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
                    <div class="bg-white rounded-lg border transition-all duration-200 hover:border-gray-300 hover:shadow-md flex overflow-hidden
                        {{ $topic->is_pinned ? 'border-yellow-200 bg-yellow-50/10' : 'border-gray-200' }}">
                        
                        {{-- LEFT RAIL (Engagement Metrics) --}}
                        <div class="w-12 md:w-16 bg-gray-50/50 border-r border-gray-100 flex flex-col items-center justify-center p-2 gap-1 shrink-0">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path></svg>
                            <span class="text-xs font-bold {{ $topic->roundtable_replies_count > 0 ? 'text-gray-900' : 'text-gray-400' }}">
                                {{ $topic->roundtable_replies_count }}
                            </span>
                        </div>

                        {{-- MAIN CONTENT --}}
                        <div class="flex-1 p-3 md:p-4">
                            {{-- Meta Header --}}
                            <div class="flex items-center gap-2 mb-2 text-xs text-gray-500">
                                @if($topic->is_pinned)
                                    <span class="text-[10px] font-black uppercase tracking-wider text-yellow-700 bg-yellow-100 px-1.5 py-0.5 rounded border border-yellow-200 flex items-center gap-1">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path d="M5 4a2 2 0 012-2h6a2 2 0 012 2v14l-5-2.5L5 18V4z"></path></svg>
                                        Pinned
                                    </span>
                                    <span class="text-gray-300">•</span>
                                @endif
                                
                                <img src="{{ asset($topic->user->profile_photo_path) }}" class="w-4 h-4 rounded-full object-cover">
                                <span class="font-bold text-gray-700 hover:underline">{{ $topic->user->name }}</span>
                                <span>posted {{ $topic->created_at->diffForHumans() }}</span>
                            </div>

                            {{-- Headline --}}
                            <h3 class="font-heading font-bold text-lg text-gray-900 leading-tight mb-2 group-hover:text-red-600 transition-colors">
                                {{ $topic->headline }}
                            </h3>

                            {{-- Preview Text --}}
                            <p class="text-sm text-gray-500 line-clamp-2 leading-relaxed">
                                {{ Str::limit($topic->content, 180) }}
                            </p>

                            {{-- Mobile Only Footer (Since left rail is small on mobile) --}}
                            <div class="mt-3 md:hidden flex items-center gap-4 text-xs font-bold text-gray-400">
                                <span class="flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path></svg>
                                    {{ $topic->roundtable_replies_count }} Comments
                                </span>
                            </div>
                        </div>

                        {{-- RIGHT RAIL (Thumbnail/Arrow - Visual Cues) --}}
                        <div class="hidden md:flex items-center justify-center px-4 text-gray-300">
                            <svg class="w-6 h-6 group-hover:text-red-400 group-hover:translate-x-1 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </div>
                    </div>
                </a>
            @empty
                <div class="bg-white rounded-xl p-12 text-center border border-dashed border-gray-300">
                    <div class="inline-block p-4 rounded-full bg-gray-50 text-gray-400 mb-4">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"></path></svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900">No topics found</h3>
                    <p class="text-gray-500 text-sm mt-1 mb-6">Be the first to start a conversation in the hall.</p>
                    <button wire:click="$set('isCreateModalOpen', true)" class="text-red-600 font-bold text-sm hover:underline">Start a Topic &rarr;</button>
                </div>
            @endforelse

            <div class="mt-4">
                {{ $topics->links() }}
            </div>
        </div>
    </div>

    {{-- 4. CREATE MODAL (Updated to match the clean aesthetic) --}}
    @if($isCreateModalOpen)
        <div class="fixed inset-0 z-50 flex items-center justify-center px-4 bg-gray-900/60 backdrop-blur-sm transition-opacity">
            <div class="bg-white w-full max-w-xl rounded-2xl shadow-2xl p-6 md:p-8 animate-fade-in-up border border-gray-100 relative">
                
                {{-- Close Button --}}
                <button wire:click="$set('isCreateModalOpen', false)" class="absolute top-4 right-4 text-gray-400 hover:text-gray-900">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>

                <h2 class="font-heading font-black text-2xl mb-1 text-gray-900">Create a Topic</h2>
                <p class="text-sm text-gray-500 mb-6">Share your thoughts with the roundtable.</p>
                
                <div class="space-y-5">
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
                            placeholder="What's on your mind?"></textarea>
                        @error('content') <span class="text-red-500 text-xs font-bold mt-1 block">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="flex justify-end gap-3 mt-8 pt-4 border-t border-gray-100">
                    <button wire:click="$set('isCreateModalOpen', false)" class="px-5 py-2.5 text-xs font-bold uppercase tracking-wide text-gray-500 hover:bg-gray-100 rounded-lg transition-colors">Cancel</button>
                    <button wire:click="createTopic" class="px-6 py-2.5 bg-gray-900 text-white text-xs font-bold uppercase tracking-wide rounded-lg hover:bg-red-600 shadow-lg hover:shadow-red-200 transition-all transform active:scale-95">Post Topic</button>
                </div>
            </div>
        </div>
    @endif
</div>