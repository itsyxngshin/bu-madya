<div class="min-h-screen font-sans text-gray-900">
    
    {{-- PAGE HEADER --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="font-heading font-black text-2xl text-gray-800 tracking-tight">
                News & <span class="text-red-600">Updates</span>
            </h1>
            <p class="text-xs text-gray-500 font-bold uppercase tracking-widest mt-1">
                Manage Articles & Announcements
            </p>
        </div>

        <a href="{{ route('news.create') }}" 
           class="px-5 py-2.5 bg-gradient-to-r from-red-600 to-red-700 text-white text-xs font-bold uppercase rounded-xl shadow-lg hover:shadow-xl hover:scale-105 transition flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
            <span>Write Article</span>
        </a>
    </div>

    {{-- TOOLBAR --}}
    <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100 mb-6 flex flex-col md:flex-row gap-4 items-center justify-between">
        
        {{-- Search --}}
        <div class="relative w-full md:w-96">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
            </div>
            <input wire:model.live.debounce.300ms="search" type="text" 
                   class="block w-full pl-10 pr-3 py-2 border border-gray-200 rounded-xl leading-5 bg-gray-50 placeholder-gray-400 focus:outline-none focus:bg-white focus:ring-1 focus:ring-red-500 focus:border-red-500 sm:text-sm transition duration-150 ease-in-out" 
                   placeholder="Search by title or author...">
        </div>

        {{-- Filters --}}
        <div class="flex items-center gap-2 w-full md:w-auto">
            
            {{-- Category Filter (Dynamic) --}}
            <select wire:model.live="categoryFilter" class="block w-1/2 md:w-40 pl-3 pr-8 py-2 text-xs font-bold border-gray-200 focus:outline-none focus:ring-red-500 focus:border-red-500 rounded-xl bg-gray-50">
                <option value="">All Categories</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat }}">{{ $cat }}</option>
                @endforeach
            </select>

            {{-- Status Filter --}}
            <select wire:model.live="statusFilter" class="block w-1/2 md:w-32 pl-3 pr-8 py-2 text-xs font-bold border-gray-200 focus:outline-none focus:ring-red-500 focus:border-red-500 rounded-xl bg-gray-50">
                <option value="">All Status</option>
                <option value="Published">Published</option>
                <option value="Draft">Draft</option>
            </select>
        </div>
    </div>

    {{-- ARTICLES LIST --}}
    <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 overflow-hidden">
        
        {{-- Table Header --}}
        <div class="grid grid-cols-12 gap-4 px-8 py-4 bg-gray-50 border-b border-gray-100 text-[10px] font-bold text-gray-400 uppercase tracking-widest">
            <div class="col-span-6 md:col-span-5">Article Details</div>
            <div class="hidden md:block md:col-span-3">Author & Category</div>
            <div class="hidden md:block md:col-span-2">Status</div>
            <div class="col-span-6 md:col-span-2 text-right">Actions</div>
        </div>

        <div class="divide-y divide-gray-100">
            @forelse($articles as $article)
            <div class="grid grid-cols-12 gap-4 px-8 py-5 items-center hover:bg-stone-50 transition group">
                
                {{-- 1. Info --}}
                <div class="col-span-6 md:col-span-5 flex items-start gap-4">
                    {{-- Thumbnail --}}
                    <div class="w-16 h-12 rounded-lg bg-gray-200 shrink-0 overflow-hidden border border-gray-100">
                        @if($article->cover_photo)
                             {{-- Handle both uploaded storage paths and external URLs --}}
                            <img src="{{ Str::startsWith($article->cover_photo, 'http') ? $article->cover_photo : asset('storage/' . $article->cover_photo) }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-gray-400 font-bold text-[8px] bg-gray-100">NO IMG</div>
                        @endif
                    </div>

                    <div>
                        <h3 class="text-sm font-bold text-gray-900 leading-tight group-hover:text-red-600 transition line-clamp-1">
                            {{ $article->title }}
                        </h3>
                        <p class="text-[10px] text-gray-500 mt-1 line-clamp-2 leading-relaxed">
                            {{ $article->summary ?? Str::limit(strip_tags($article->content), 80) }}
                        </p>
                    </div>
                </div>

                {{-- 2. Author & Category --}}
                <div class="hidden md:block md:col-span-3">
                    <div class="flex items-center gap-2 mb-1">
                        <div class="w-5 h-5 rounded-full bg-red-100 text-red-600 flex items-center justify-center text-[9px] font-bold">
                            {{ substr($article->author ?? 'A', 0, 1) }}
                        </div>
                        <span class="text-xs font-bold text-gray-700">{{ $article->author }}</span>
                    </div>
                    <span class="text-[9px] font-bold text-gray-400 uppercase bg-gray-100 px-1.5 py-0.5 rounded tracking-wide">
                        {{ $article->category }}
                    </span>
                </div>

                {{-- 3. Status --}}
                <div class="hidden md:block md:col-span-2">
                    @if($article->status === 'Published')
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold bg-green-50 text-green-700 border border-green-100 uppercase tracking-wide">
                            <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> Published
                        </span>
                        <p class="text-[9px] text-gray-400 mt-1 pl-1">
                            {{ $article->created_at->format('M d, Y') }}
                        </p>
                    @else
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold bg-gray-100 text-gray-600 border border-gray-200 uppercase tracking-wide">
                            <span class="w-1.5 h-1.5 rounded-full bg-gray-400"></span> Draft
                        </span>
                        <p class="text-[9px] text-gray-400 mt-1 pl-1">Last edited {{ $article->updated_at->diffForHumans() }}</p>
                    @endif
                </div>

                {{-- 4. Actions --}}
                <div class="col-span-6 md:col-span-2 flex items-center justify-end gap-2">
                    {{-- Edit Button (Uses route parameter) --}}
                    <a href="{{ route('news.edit', $article->id) }}" class="p-2 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition" title="Edit">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                    </a>
                    
                    <button wire:click="deleteArticle({{ $article->id }})" 
                            wire:confirm="Are you sure you want to delete this article? This cannot be undone."
                            class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition" 
                            title="Delete">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                    </button>
                </div>

            </div>
            @empty
            <div class="px-6 py-12 text-center">
                <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4 text-gray-300">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path></svg>
                </div>
                <h3 class="text-sm font-bold text-gray-900">No Articles Found</h3>
                <p class="text-xs text-gray-500 mt-1">Try changing your search terms or write a new article.</p>
            </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        @if($articles->hasPages())
        <div class="bg-gray-50 px-6 py-4 border-t border-gray-100">
            {{ $articles->links() }}
        </div>
        @endif

    </div>
</div>