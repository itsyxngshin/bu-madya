<div class="max-w-2xl mx-auto py-8 md:py-12 px-4 sm:px-6 font-sans pb-32 animate-fade-in-up">

    {{-- FEED HEADER --}}
    <div class="mb-8">
        <h1 class="text-3xl md:text-4xl font-black text-gray-900 tracking-tight flex items-center gap-3">
            BU MADYA <span class="text-blue-600">Community</span>
        </h1>
        <p class="text-sm text-gray-500 font-medium mt-1">Advocacy, updates, and student-led initiatives.</p>
    </div>

    {{-- "WHAT'S ON YOUR MIND?" - QUICK CREATE BOX --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-4 mb-6">
        <div class="flex gap-3">
            <div class="w-10 h-10 rounded-full bg-blue-100 text-blue-600 font-black flex items-center justify-center shrink-0 border border-blue-200">
                {{ auth()->check() ? substr(auth()->user()->name, 0, 1) : '?' }}
            </div>
            <a href="{{ route('community.posts.create') }}" class="flex-1 bg-gray-100 hover:bg-gray-200 transition-colors rounded-full px-5 flex items-center text-gray-500 text-sm font-medium cursor-pointer">
                What's happening in your advocacy, {{ auth()->check() ? explode(' ', auth()->user()->name)[0] : 'Advocate' }}?
            </a>
        </div>
        <div class="border-t border-gray-100 mt-4 pt-3 flex gap-2">
            <a href="{{ route('community.posts.create') }}" class="flex items-center gap-2 px-4 py-2 hover:bg-gray-50 rounded-xl transition-colors text-gray-600 font-bold text-xs">
                <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                Attach Media
            </a>
        </div>
    </div>

    {{-- CATEGORY FILTER PILLS (Horizontal Scroll) --}}
    <div class="flex overflow-x-auto hide-scrollbar items-center gap-2 mb-6 pb-2">
        <button wire:click="clearCategory" class="shrink-0 px-4 py-2 rounded-full text-xs font-black uppercase tracking-wider transition-all {{ is_null($activeCategoryId) ? 'bg-gray-900 text-white shadow-md' : 'bg-white border border-gray-200 text-gray-600 hover:bg-gray-50' }}">
            All Updates
        </button>
        @foreach($categories as $category)
            <button wire:click="setCategory({{ $category->id }})" class="shrink-0 px-4 py-2 rounded-full text-xs font-black uppercase tracking-wider transition-all {{ $activeCategoryId === $category->id ? 'bg-blue-600 text-white shadow-md' : 'bg-white border border-gray-200 text-gray-600 hover:bg-gray-50' }}">
                {{ $category->name }}
            </button>
        @endforeach
    </div>

    {{-- THE SOCIAL FEED --}}
    <div class="space-y-6">
        @forelse($posts as $post)
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                
                {{-- 1. Post Header (Author Info) --}}
                <div class="p-4 flex items-start justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-blue-50 text-blue-600 font-black flex items-center justify-center border border-blue-100 shrink-0">
                            {{ substr($post->author->name ?? 'A', 0, 1) }}
                        </div>
                        <div>
                            <p class="font-black text-gray-900 text-sm leading-tight flex items-center gap-1.5">
                                {{ $post->author->name ?? 'Unknown Author' }}
                                @if($post->is_featured)
                                    <svg class="w-3.5 h-3.5 text-yellow-500" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                @endif
                            </p>
                            <div class="flex items-center gap-1.5 text-[11px] font-bold text-gray-500 mt-0.5">
                                <span>{{ $post->published_at ? $post->published_at->diffForHumans() : 'Draft' }}</span>
                                @if($post->category)
                                    <span>•</span>
                                    <span class="uppercase tracking-wider text-blue-600">{{ $post->category->name }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    {{-- Options Menu (Optional) --}}
                    <button class="text-gray-400 hover:text-gray-600 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 12h.01M12 12h.01M19 12h.01M6 12a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0z"></path></svg>
                    </button>
                </div>

                {{-- 2. Post Body & Excerpt --}}
                <div class="px-4 pb-3">
                    <h3 class="font-black text-gray-900 text-lg mb-1">{{ $post->title }}</h3>
                    <p class="text-sm text-gray-800 leading-relaxed whitespace-pre-line line-clamp-4">
                        {{ $post->excerpt ?? Str::limit(strip_tags($post->content), 200) }}
                    </p>
                    <a href="{{ route('community.posts.show', $post->slug) }}" class="text-blue-600 font-bold text-sm hover:underline mt-1 inline-block">See more</a>
                </div>

                {{-- 3. Edge-to-Edge Media --}}
                @if($post->cover_image_path)
                    <a href="{{ route('community.posts.show', $post->slug) }}" class="block w-full max-h-96 overflow-hidden bg-gray-100">
                        <img src="{{ asset('storage/'.$post->cover_image_path) }}" class="w-full h-full object-cover">
                    </a>
                @endif

                {{-- Inline Interaction Component --}}
                <livewire:community.feed-interaction :post="$post" :key="'interact-'.$post->id" />

            </div>
        @empty
            <div class="text-center py-20 bg-white rounded-2xl border border-dashed border-gray-200">
                <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4 border border-gray-100">
                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9.5a2.5 2.5 0 00-2.5-2.5H15"></path></svg>
                </div>
                <h3 class="text-lg font-black text-gray-900">No updates yet</h3>
                <p class="text-sm text-gray-500 mt-1">Check back later or be the first to start a conversation.</p>
                <button wire:click="clearCategory" class="mt-4 text-xs font-black text-blue-600 uppercase tracking-widest hover:underline">Clear Filters</button>
            </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    <div class="mt-8">
        {{ $posts->links() }}
    </div>

    <style>
        /* Hides the scrollbar for the category pills but keeps them scrollable on mobile */
        .hide-scrollbar::-webkit-scrollbar { display: none; }
        .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</div>