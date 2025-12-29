<div>
    {{-- HERO HEADER (Remains the same as your code) --}}
    <header class="relative h-[350px] flex items-center justify-center text-white overflow-hidden rounded-3xl shadow-xl mx-6 -mt-20 z-10">
        <div class="absolute inset-0 z-0">
            <img src="https://images.unsplash.com/photo-1504711434969-e33886168f5c?q=80&w=2070" class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-r from-red-900/90 to-yellow-700/80 mix-blend-multiply"></div>
        </div>
        <div class="relative z-10 text-center px-4 mt-16">
            <h2 class="text-yellow-300 font-bold tracking-[0.3em] text-xs uppercase mb-2">Updates & Stories</h2>
            <h1 class="font-heading text-3xl md:text-5xl font-black uppercase tracking-tight mb-2 drop-shadow-lg">Newsroom</h1>
            
            @auth
                {{-- Only show "Write" button if authorized (e.g., Director) --}}
                @if(in_array(Auth::user()?->role?->role_name, ['director', 'member']))
                <div class="animate-fade-in-up">
                    <a href="{{ route('news.create') }}" 
                    class="inline-flex items-center gap-2 px-6 py-3 bg-white text-red-600 rounded-full font-bold text-xs uppercase tracking-widest hover:bg-yellow-400 hover:text-green-900 transition shadow-lg transform hover:-translate-y-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                        Write New Story
                    </a>
                </div>
            @endif
            @endauth
        </div>
    </header>

    {{-- MAIN CONTENT --}}
    <div class="relative min-h-screen px-6 pb-24 mt-12 max-w-[1800px] w-[95%] mx-auto">
        
        {{-- SEARCH & FILTER --}}
        <div class="relative z-10 flex flex-col md:flex-row justify-between items-center mb-12 gap-4">
            <div class="flex gap-2 overflow-x-auto pb-2 max-w-full">
                @foreach($categories as $cat)
                <button wire:click="setCategory('{{ $cat }}')"
                        class="whitespace-nowrap px-4 py-1.5 rounded-full text-xs font-bold shadow-md transition-all
                               {{ $category === $cat ? 'bg-red-600 text-white' : 'bg-white/60 text-gray-700 hover:bg-white' }}">
                    {{ $cat }}
                </button>
                @endforeach
            </div>

            <div class="relative w-full md:w-80">
                <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search news..." 
                       class="w-full pl-10 pr-4 py-2 rounded-full border-none bg-white/80 backdrop-blur-sm shadow-sm focus:ring-2 focus:ring-yellow-400 text-sm">
                <svg class="absolute left-3.5 top-2.5 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>
        </div>

        {{-- FEATURED ARTICLE (Remains same) --}}
        @if($featured && !$search && !$category) {{-- Only show featured on home view --}}
        <div class="relative z-10 mb-12 animate-fade-in-up">
            <div class="group relative rounded-3xl overflow-hidden shadow-2xl h-[400px] md:h-[500px]">
                <img src="{{ $featured->cover_img ? (Str::startsWith($featured->cover_img, 'https') ? $featured->cover_img : asset('storage/'.$featured->cover_img)) : 'https://images.unsplash.com/photo-1524178232363-1fb2b075b655?q=80&w=2070' }}" 
                     class="absolute inset-0 w-full h-full object-cover transition duration-700 group-hover:scale-105">
                <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/40 to-transparent"></div>
                <div class="absolute bottom-0 left-0 p-8 md:p-12 w-full md:w-2/3">
                    <div class="flex items-center gap-3 mb-3">
                        <span class="px-3 py-1 bg-yellow-400 text-green-900 text-[10px] font-black uppercase tracking-wider rounded-md">Top Story</span>
                        <span class="px-2 py-1 bg-white/20 backdrop-blur text-white text-[10px] font-bold rounded flex items-center gap-1">
                            <svg class="w-3 h-3 text-red-400 fill-current" viewBox="0 0 20 20"><path d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z"/></svg>
                            {{ $featured->votes_count }} Votes
                        </span>
                    </div>
                    <h2 class="font-heading text-3xl md:text-5xl font-bold text-white mb-4 leading-tight">{{ $featured->title }}</h2>
                    <a href="{{ route('news.show', $featured->slug) }}" class="inline-flex items-center gap-2 text-white font-bold hover:text-yellow-400 transition">Read Full Story &rarr;</a>
                </div>
            </div>
        </div>
        @endif

        {{-- NEWS GRID --}}
        <div class="relative z-10 grid md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($news as $article)
            <article class="group bg-white/60 backdrop-blur-md border border-white/60 rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl hover:-translate-y-2 transition duration-300 flex flex-col relative">
                
                {{-- Director Status Badge (Visible only to Directors) --}}
                @if(auth()->check() && auth()->user()->role === 'Director' && $article->status !== 'active')
                    <div class="absolute top-4 right-4 z-20 px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider shadow-sm
                        {{ $article->status === 'draft' ? 'bg-gray-200 text-gray-600' : 'bg-amber-100 text-amber-700 animate-pulse' }}">
                        {{ $article->status === 'draft' ? 'Draft' : 'In Review' }}
                    </div>
                @endif

                {{-- Thumbnail --}}
                <div class="h-48 overflow-hidden relative">
                    <img src="{{ $article->cover_img ? (Str::startsWith($article->cover_img, 'https') ? $article->cover_img : asset('storage/'.$article->cover_img)) : 'https://source.unsplash.com/random/400x300?sig='.$article->id }}" 
                         class="w-full h-full object-cover group-hover:scale-110 transition duration-700 {{ $article->status !== 'active' ? 'grayscale opacity-70' : '' }}">
                    
                    <div class="absolute top-4 left-4 bg-white/90 backdrop-blur text-gray-800 text-[10px] font-bold px-3 py-1 rounded-full uppercase tracking-wider shadow-sm">
                        {{ $article->category->name ?? 'Update' }}
                    </div>
                </div>

                {{-- Body --}}
                <div class="p-6 flex flex-col flex-grow">
                    <div class="flex items-center justify-between text-[10px] text-gray-400 mb-3">
                        <span class="flex items-center gap-1">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            {{ $article->published_at ? $article->published_at->format('M d, Y') : 'Unpublished' }}
                        </span>
                        <span class="flex items-center gap-1 text-red-500 font-bold">
                            <svg class="w-3 h-3 fill-current" viewBox="0 0 20 20"><path d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z"/></svg>
                            {{ $article->votes_count }}
                        </span>
                    </div>

                    <h3 class="font-heading font-bold text-xl text-gray-900 mb-3 leading-tight group-hover:text-red-600 transition">
                        {{ $article->title }}
                    </h3>
                    
                    <p class="text-sm text-gray-600 line-clamp-3 mb-6 flex-grow">
                        {{ $article->summary ?? Str::limit(strip_tags(Str::markdown($article->content)), 100) }}
                    </p>
                    
                    <a href="{{ route('news.show', $article->slug) }}" class="inline-flex items-center text-xs font-bold text-red-600 hover:text-red-800 uppercase tracking-widest">
                        {{ $article->status === 'active' ? 'Read Article' : 'Preview Article' }} <span class="ml-2 group-hover:translate-x-1 transition">&rarr;</span>
                    </a>
                </div>
            </article>
            @empty
            <div class="col-span-3 py-10 flex flex-col items-center justify-center text-gray-500 opacity-70">
                <svg class="w-16 h-16 mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <p>No stories found.</p>
            </div>
            @endforelse
        </div>

        <div class="mt-12">
            {{ $news->links() }}
        </div>
    </div>
</div>