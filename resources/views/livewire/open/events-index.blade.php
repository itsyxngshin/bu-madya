<div class="min-h-screen bg-stone-50 font-sans text-gray-900 relative selection:bg-red-200 selection:text-red-900 overflow-hidden">

    {{-- 1. BACKGROUND TEXTURE & BLOBS (Global) --}}
    <div class="fixed inset-0 z-0 pointer-events-none">
        {{-- Noise Grain --}}
        <div class="absolute inset-0 opacity-[0.03]" 
             style="background-image: url('data:image/svg+xml,%3Csvg viewBox=%220 0 200 200%22 xmlns=%22http://www.w3.org/2000/svg%22%3E%3Cfilter id=%22noiseFilter%22%3E%3CfeTurbulence type=%22fractalNoise%22 baseFrequency=%220.65%22 numOctaves=%223%22 stitchTiles=%22stitch%22/%3E%3C/filter%3E%3Crect width=%22100%25%22 height=%22100%25%22 filter=%22url(%23noiseFilter)%22/%3E%3C/svg%3E');">
        </div>
        {{-- Subtle Background Blobs --}}
        <div class="absolute top-0 right-0 w-[800px] h-[800px] bg-red-100/50 rounded-full blur-[150px] -translate-y-1/2 translate-x-1/3 mix-blend-multiply"></div>
        <div class="absolute bottom-0 left-0 w-[600px] h-[600px] bg-yellow-100/40 rounded-full blur-[150px] translate-y-1/3 -translate-x-1/4 mix-blend-multiply"></div>
    </div>

    {{-- 2. HERO SECTION --}}
    <header class="relative w-full h-[50vh] min-h-[450px] bg-gray-900 overflow-hidden group relative z-10">
        
        {{-- Abstract Pattern --}}
        <div class="absolute inset-0 w-full h-full bg-gray-800 opacity-40" 
             style="background-image: radial-gradient(#444 1px, transparent 1px); background-size: 30px 30px;">
        </div>

        {{-- SIGNATURE HERO BLOBS --}}
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
             <div class="absolute top-1/2 left-1/3 w-[600px] h-[600px] bg-red-600/30 rounded-full blur-[120px] -translate-y-1/2 -translate-x-1/2 mix-blend-overlay animate-pulse-slow"></div>
             <div class="absolute bottom-0 right-1/4 w-[500px] h-[500px] bg-yellow-500/20 rounded-full blur-[100px] translate-y-1/4 translate-x-1/2 mix-blend-overlay"></div>
        </div>
        
        {{-- Gradient Overlay --}}
        <div class="absolute inset-0 bg-gradient-to-t from-stone-900 via-stone-900/80 to-transparent"></div>

        {{-- Hero Content --}}
        <div class="absolute bottom-0 left-0 w-full z-20 px-6 pb-16 pt-32 bg-gradient-to-t from-stone-900 to-transparent">
            <div class="max-w-7xl mx-auto text-center md:text-left relative">
                <span class="text-yellow-400 font-bold tracking-widest uppercase text-xs mb-3 block relative pl-12 before:absolute before:left-0 before:top-1/2 before:-translate-y-1/2 before:w-8 before:h-px before:bg-yellow-400/50">
                    Updates & Stories
                </span>
                <h1 class="font-heading font-black text-5xl md:text-7xl lg:text-8xl text-white leading-none mb-6 drop-shadow-2xl">
                    News<span class="text-transparent bg-clip-text bg-gradient-to-r from-red-500 to-orange-500 relative z-10">room</span>
                </h1>
                
                {{-- Write Button (Floating Absolute on Desktop) --}}
                @auth
                    @if(in_array(Auth::user()?->role?->role_name, ['director', 'member']))
                    <div class="md:absolute md:bottom-4 md:right-0 mt-8 md:mt-0">
                        <a href="{{ route('news.create') }}" 
                           class="inline-flex items-center gap-3 px-8 py-4 bg-white text-gray-900 rounded-full font-black text-xs uppercase tracking-widest hover:bg-yellow-400 hover:scale-105 transition shadow-lg hover:shadow-yellow-400/20">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                            Write New Story
                        </a>
                    </div>
                    @endif
                @endauth
            </div>
        </div>
    </header>

    {{-- 3. CONTROLS (Search & Filter) --}}
    <div class="sticky top-20 z-40 bg-stone-50/80 backdrop-blur-lg border-b border-gray-200/50 py-4 shadow-sm">
        <div class="max-w-7xl mx-auto px-6 flex flex-col md:flex-row justify-between items-center gap-4">
            
            {{-- Categories --}}
            <div class="flex gap-2 overflow-x-auto pb-2 md:pb-0 max-w-full no-scrollbar mask-linear-fade">
                @foreach($categories as $cat)
                <button wire:click="setCategory('{{ $cat }}')"
                        class="whitespace-nowrap px-5 py-2.5 text-xs font-bold uppercase tracking-wider rounded-full transition-all border 
                        {{ $category === $cat ? 'bg-gray-900 text-white border-gray-900 shadow-md transform scale-105' : 'bg-white text-gray-600 border-gray-200 hover:border-gray-300 hover:bg-gray-50' }}">
                    {{ $cat }}
                </button>
                @endforeach
            </div>

            {{-- Search Bar --}}
            <div class="relative w-full md:w-80 group">
                <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search stories..." 
                       class="w-full pl-11 pr-4 py-3 rounded-full border-gray-200 bg-white/80 text-sm focus:ring-red-500 focus:border-red-500 shadow-sm group-hover:shadow-md transition">
                <svg class="w-5 h-5 text-gray-400 absolute left-4 top-3 group-hover:text-red-500 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>
        </div>
    </div>

    {{-- 4. MAIN CONTENT --}}
    <div class="relative z-20 max-w-7xl mx-auto px-6 py-16">
        
        {{-- FEATURED ARTICLE --}}
        @if($featured && !$search && !$category)
        <div class="mb-20 relative group">
            <div class="relative h-[550px] w-full rounded-[2.5rem] overflow-hidden shadow-2xl ring-1 ring-black/5">
                {{-- Image --}}
                <img src="{{ $featured->cover_img ? (Str::startsWith($featured->cover_img, 'http') ? $featured->cover_img : asset('storage/'.$featured->cover_img)) : 'https://images.unsplash.com/photo-1524178232363-1fb2b075b655?q=80&w=2070' }}" 
                     class="absolute inset-0 w-full h-full object-cover transition duration-1000 group-hover:scale-105">
                
                {{-- Gradient --}}
                <div class="absolute inset-0 bg-gradient-to-t from-gray-950 via-gray-900/50 to-transparent"></div>
                
                {{-- Content --}}
                <div class="absolute bottom-0 left-0 p-10 md:p-16 w-full md:w-3/4 lg:w-2/3">
                    <div class="flex items-center gap-4 mb-6">
                        <span class="px-4 py-1.5 bg-red-600 text-white text-[10px] font-black uppercase tracking-widest rounded-full shadow-lg shadow-red-600/20">Top Story</span>
                        <div class="flex items-center gap-1.5 bg-black/30 backdrop-blur-md px-3 py-1.5 rounded-full border border-white/10">
                            <svg class="w-3.5 h-3.5 text-yellow-400 fill-current" viewBox="0 0 20 20"><path d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z"/></svg>
                            <span class="text-[10px] font-bold text-white">{{ $featured->votes_count }} Votes</span>
                        </div>
                    </div>
                    
                    <a href="{{ route('news.show', $featured->slug) }}" class="block group/title">
                        <h2 class="font-heading text-4xl md:text-5xl lg:text-6xl font-black text-white mb-8 leading-[1.1] group-hover/title:text-red-200 transition duration-300 drop-shadow-lg">
                            {{ $featured->title }}
                        </h2>
                    </a>
                    
                    <a href="{{ route('news.show', $featured->slug) }}" class="inline-flex items-center gap-3 px-8 py-4 bg-white text-gray-900 rounded-2xl font-black uppercase tracking-widest text-xs hover:bg-gray-100 transition shadow-xl hover:shadow-white/20 hover:-translate-y-1">
                        Read Full Story &rarr;
                    </a>
                </div>
            </div>
            {{-- Featured Blob --}}
            <div class="absolute -z-10 -bottom-12 -right-12 w-72 h-72 bg-red-500 rounded-full blur-[120px] opacity-30 mix-blend-multiply group-hover:opacity-50 transition duration-700"></div>
        </div>
        @endif

        {{-- NEWS GRID --}}
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-x-8 gap-y-12">
            @forelse($news as $article)
            <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 overflow-hidden group hover:shadow-2xl hover:-translate-y-2 transition-all duration-500 flex flex-col h-full relative">
                
                {{-- Director Status Badge --}}
                @if(auth()->check() && in_array(Auth::user()?->role?->role_name, ['administrator', 'director']) && $article->status !== 'active')
                    <div class="absolute top-4 right-4 z-20 px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider shadow-md border backdrop-blur-md
                        {{ $article->status === 'draft' ? 'bg-gray-100/90 border-gray-200 text-gray-600' : 'bg-yellow-50/90 border-yellow-200 text-yellow-700' }}">
                        {{ $article->status === 'draft' ? 'Draft' : 'In Review' }}
                    </div>
                @endif

                {{-- Image Thumb --}}
                <div class="relative h-72 overflow-hidden bg-gray-200">
                    <a href="{{ route('news.show', $article->slug) }}">
                        <img src="{{ $article->cover_img ? (Str::startsWith($article->cover_img, 'http') ? $article->cover_img : asset('storage/'.$article->cover_img)) : 'https://source.unsplash.com/random/400x300?sig='.$article->id }}" 
                             class="w-full h-full object-cover group-hover:scale-110 transition duration-700 ease-out {{ $article->status !== 'active' ? 'grayscale opacity-70' : '' }}">
                    </a>
                    
                    {{-- Category Badge --}}
                    <div class="absolute top-4 left-4 z-10">
                        <span class="bg-white/90 backdrop-blur px-3 py-1.5 rounded-full text-[10px] font-black uppercase tracking-wider shadow-sm text-gray-900 border border-white/50">
                            {{ $article->category->name ?? 'Update' }}
                        </span>
                    </div>

                    {{-- Gradient Overlay --}}
                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent opacity-80 group-hover:opacity-60 transition"></div>

                     {{-- Date & Votes (Over Image for modern look) --}}
                    <div class="absolute bottom-0 left-0 w-full p-6 flex items-center justify-between text-white/80 z-20">
                        <span class="text-[10px] font-bold uppercase tracking-widest">{{ $article->published_at ? $article->published_at->format('M d, Y') : 'Unpublished' }}</span>
                        <div class="flex items-center gap-1.5 bg-black/30 backdrop-blur-sm px-2 py-1 rounded-full">
                            <svg class="w-3 h-3 fill-red-500" viewBox="0 0 20 20"><path d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z"/></svg>
                            <span class="text-[10px] font-bold">{{ $article->votes_count }}</span>
                        </div>
                    </div>
                </div>

                {{-- Content --}}
                <div class="p-8 flex-1 flex flex-col relative">
                     {{-- Card Blob Hover --}}
                     <div class="absolute top-1/2 right-0 w-32 h-32 bg-red-100 rounded-full blur-[80px] opacity-0 group-hover:opacity-100 transition duration-500 translate-x-1/2 -translate-y-1/2 pointer-events-none mix-blend-multiply"></div>

                    <a href="{{ route('news.show', $article->slug) }}" class="block mb-4 relative z-10">
                        <h3 class="font-heading font-black text-2xl text-gray-900 leading-[1.1] group-hover:text-red-600 transition-colors duration-300 line-clamp-2">
                            {{ $article->title }}
                        </h3>
                    </a>

                    <p class="text-sm text-gray-500 line-clamp-3 mb-8 flex-1 leading-relaxed relative z-10">
                        {{ $article->summary ?? Str::limit(strip_tags(Str::markdown($article->content)), 120) }}
                    </p>

                    <a href="{{ route('news.show', $article->slug) }}" class="relative z-10 w-full py-4 rounded-xl border-2 border-gray-100 text-center text-xs font-bold uppercase tracking-widest text-gray-400 group-hover:border-gray-900 group-hover:bg-gray-900 group-hover:text-white transition-all duration-300 shadow-sm">
                        Read Story
                    </a>
                </div>
            </div>
            @empty
            <div class="col-span-full py-24 bg-white rounded-[2.5rem] border-2 border-dashed border-gray-200 text-center relative overflow-hidden">
                 <div class="absolute inset-0 opacity-[0.03] pointer-events-none" style="background-image: url('data:image/svg+xml,%3Csvg width=%2220%22 height=%2220%22 xmlns=%22http://www.w3.org/2000/svg%22%3E%3Cpath d=%22M0 0h20v20H0z%22 fill=%22%23000%22 fill-opacity=%22.05%22/%3E%3Cpath d=%22M0 0h10v10H0zM10 10h10v10H10z%22 fill=%22%23fff%22/%3E%3C/svg%3E');"></div>
                <div class="inline-flex p-6 bg-gray-50 rounded-full text-gray-300 mb-6 relative z-10">
                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path></svg>
                </div>
                <h3 class="text-2xl font-black text-gray-900 mb-2 relative z-10">No stories found</h3>
                <p class="text-gray-500 text-sm relative z-10">We couldn't find anything matching your criteria.</p>
            </div>
            @endforelse
        </div>

        <div class="mt-20">
            {{ $news->links() }}
        </div>
    </div>
</div>