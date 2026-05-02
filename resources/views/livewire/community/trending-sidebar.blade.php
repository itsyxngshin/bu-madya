<div class="bg-white rounded-[1.5rem] shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] border border-gray-50 p-5 mb-6 transition-shadow hover:shadow-md">
    <h3 class="font-black text-gray-900 mb-4 text-[15px] flex items-center gap-2">
        <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
        Trending at BU
    </h3>
    
    <div class="space-y-4">
        @forelse($trendingTags as $tag)
            <a href="#" class="flex items-center justify-between group block">
                <div>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest group-hover:text-red-500 transition-colors">Trending Topic</p>
                    <p class="text-[14px] font-bold text-gray-800 group-hover:text-red-600 transition-colors">#{{ $tag->name }}</p>
                </div>
                <span class="w-8 h-8 rounded-full bg-gray-50 flex items-center justify-center text-[11px] font-bold text-gray-500 group-hover:bg-red-50 group-hover:text-red-600 transition-colors border border-gray-100">
                    {{ $tag->posts_count }}
                </span>
            </a>
        @empty
            <div class="text-center py-4">
                <p class="text-[12px] font-medium text-gray-400">No trending topics this week.</p>
            </div>
        @endforelse
    </div>
</div>