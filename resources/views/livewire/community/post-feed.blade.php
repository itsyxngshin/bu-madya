<div class="max-w-7xl mx-auto py-8 md:py-12 px-4 sm:px-6 font-sans pb-32 animate-fade-in-up">

    {{-- FEED HEADER & CALL TO ACTION --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-end mb-10 gap-6">
        <div>
            <p class="text-[10px] font-black text-blue-600 uppercase tracking-[0.3em] mb-2">BU MADYA • Voices</p>
            <h1 class="text-4xl md:text-5xl font-black text-gray-900 tracking-tighter leading-tight">Community Feed</h1>
            <p class="text-sm md:text-base text-gray-500 font-medium mt-3 max-w-xl leading-relaxed">The central hub for advocacy updates, statements, and student-led initiatives across Bicol University.</p>
        </div>

        <div class="shrink-0">
            <a href="{{ route('community.posts.create') }}" class="inline-flex items-center gap-2 px-6 py-3.5 bg-gray-900 hover:bg-blue-600 text-white text-sm font-black uppercase tracking-widest rounded-2xl shadow-lg transition-all transform active:scale-95 group">
                <svg class="w-5 h-5 group-hover:rotate-90 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"></path></svg>
                Write a Post
            </a>
        </div>
    </div>

    {{-- CATEGORY FILTER PILLS --}}
    <div class="flex flex-wrap items-center gap-2 mb-8 border-b border-gray-100 pb-6">
        <button wire:click="clearCategory"
                class="px-4 py-2 rounded-full text-xs font-black uppercase tracking-wider transition-all {{ is_null($activeCategoryId) ? 'bg-gray-900 text-white shadow-md' : 'bg-white border border-gray-200 text-gray-500 hover:bg-gray-50 hover:text-gray-900' }}">
            All Updates
        </button>

        @foreach($categories as $category)
            <button wire:click="setCategory({{ $category->id }})"
                    class="px-4 py-2 rounded-full text-xs font-black uppercase tracking-wider transition-all {{ $activeCategoryId === $category->id ? 'bg-blue-600 text-white shadow-md' : 'bg-white border border-gray-200 text-gray-500 hover:bg-gray-50 hover:text-blue-600' }}">
                {{ $category->name }}
            </button>
        @endforeach
    </div>

    {{-- POSTS GRID --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-8">
        @forelse($posts as $post)
            <a href="{{ route('community.posts.show', $post->slug) }}" class="flex flex-col bg-white rounded-[2rem] border border-gray-100 shadow-sm hover:shadow-xl hover:border-blue-100 transition-all duration-300 group overflow-hidden transform hover:-translate-y-1">

                {{-- Cover Image --}}
                <div class="relative h-48 md:h-56 w-full bg-gray-100 overflow-hidden shrink-0">
                    @if($post->cover_image_path)
                        <img src="{{ asset('storage/'.$post->cover_image_path) }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    @else
                        {{-- Fallback Pattern --}}
                        <div class="absolute inset-0 bg-gradient-to-br from-blue-50 to-gray-100"></div>
                        <div class="absolute inset-0 opacity-10 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')]"></div>
                        <div class="absolute inset-0 flex items-center justify-center">
                            <svg class="w-12 h-12 text-blue-200" fill="currentColor" viewBox="0 0 24 24"><path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 16H5V5h14v14zm-5.04-6.71l-2.75 3.54-1.96-2.36L6.5 17h11l-3.54-4.71z"/></svg>
                        </div>
                    @endif

                    {{-- Badges --}}
                    <div class="absolute top-4 left-4 flex flex-col gap-2">
                        @if($post->is_featured)
                            <span class="bg-yellow-400 text-yellow-900 text-[9px] font-black uppercase tracking-widest px-3 py-1.5 rounded-lg shadow-sm flex items-center gap-1.5 w-max">
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                Featured
                            </span>
                        @endif

                        @if($post->category)
                            <span class="bg-white/90 backdrop-blur-sm text-gray-900 text-[9px] font-black uppercase tracking-widest px-3 py-1.5 rounded-lg shadow-sm w-max border border-white/20">
                                {{ $post->category->name }}
                            </span>
                        @endif
                    </div>
                </div>

                {{-- Card Content --}}
                <div class="p-6 md:p-8 flex-1 flex flex-col">
                    <h3 class="text-xl font-black text-gray-900 leading-tight mb-3 group-hover:text-blue-600 transition-colors line-clamp-2">
                        {{ $post->title }}
                    </h3>

                    <p class="text-sm text-gray-500 font-medium leading-relaxed mb-6 line-clamp-3 flex-1">
                        {{ $post->excerpt ?? Str::limit(strip_tags($post->content), 120) }}
                    </p>

                    {{-- Footer: Author & Meta --}}
                    <div class="mt-auto pt-4 border-t border-gray-100 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-blue-50 text-blue-600 font-black flex items-center justify-center text-xs shrink-0 border border-blue-100">
                                {{ substr($post->author->name ?? 'A', 0, 1) }}
                            </div>
                            <div>
                                <p class="text-xs font-bold text-gray-900 leading-none">{{ $post->author->name ?? 'Unknown' }}</p>
                                <p class="text-[9px] font-bold text-gray-400 uppercase tracking-wider mt-1">{{ $post->published_at ? $post->published_at->format('M d, Y') : 'Draft' }}</p>
                            </div>
                        </div>

                        {{-- Engagement Stats --}}
                        <div class="flex items-center gap-3 text-gray-400">
                            <div class="flex items-center gap-1.5">
                                <span class="text-xs">{{ $post->elements_count > 0 ? '🔥' : '✨' }}</span>
                                <span class="text-xs font-bold">{{ $post->elements_count }}</span>
                            </div>
                            <div class="flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                                <span class="text-xs font-bold">{{ $post->comments_count }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        @empty
            <div class="col-span-full py-20 text-center bg-gray-50 rounded-[2.5rem] border border-dashed border-gray-200">
                <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center mx-auto mb-4 shadow-sm border border-gray-100">
                    <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9.5a2.5 2.5 0 00-2.5-2.5H15"></path></svg>
                </div>
                <h3 class="text-lg font-black text-gray-900">No posts found</h3>
                <p class="text-sm text-gray-500 mt-1">There are no updates in this category right now.</p>
                <button wire:click="clearCategory" class="mt-4 text-xs font-black text-blue-600 uppercase tracking-widest hover:underline">View All Posts</button>
            </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    <div class="mt-12">
        {{ $posts->links() }}
    </div>

</div>
