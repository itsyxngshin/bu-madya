<div class="relative mb-6 z-[60]" x-data="{ searchOpen: false }">

    {{-- Search Input Box --}}
    <div class="flex items-center w-full bg-white border border-gray-100 rounded-[1.25rem] px-4 py-3 shadow-sm focus-within:border-red-500 focus-within:ring-1 focus-within:ring-red-500 transition-all">
        <svg class="w-4 h-4 text-gray-400 shrink-0 mr-3 transition-colors focus-within:text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>

        <input wire:model.live.debounce.300ms="query"
               @focus="searchOpen = true"
               @click.away="searchOpen = false"
               type="text"
               placeholder="Search community..."
               class="w-full bg-transparent text-[13px] outline-none placeholder-gray-400 font-medium border-none p-0 focus:ring-0">

        {{-- Tiny loading spinner that only shows when fetching --}}
        <div wire:loading wire:target="query" class="w-3.5 h-3.5 border-2 border-red-500 border-t-transparent rounded-full animate-spin shrink-0 ml-2"></div>
    </div>

    {{-- Live Search Results Dropdown --}}
    @if(strlen($query) >= 2)
        <div x-show="searchOpen" x-transition.opacity class="absolute top-full left-0 right-0 mt-2 bg-white rounded-[1.25rem] shadow-[0_8px_30px_rgb(0,0,0,0.12)] border border-gray-100 overflow-hidden py-2" style="display: none;">

            @forelse($results as $post)
                <a href="{{ route('community.posts.show', $post->slug) }}" class="block px-4 py-2.5 hover:bg-gray-50 transition-colors group">
                    <p class="text-[12px] font-black text-gray-900 group-hover:text-red-600 transition-colors truncate">
                        {{ $post->title ?: 'Post Update' }}
                    </p>
                    <p class="text-[10px] text-gray-500 truncate mt-0.5">
                        {{ Str::limit(strip_tags($post->content), 45) }}
                    </p>
                </a>
            @empty
                <div class="px-4 py-4 text-[11px] font-bold text-gray-400 text-center">
                    No results found for "<span class="text-gray-700">{{ $query }}</span>"
                </div>
            @endforelse

        </div>
    @endif
</div>
