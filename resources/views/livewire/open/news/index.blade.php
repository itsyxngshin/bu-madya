<div>
    <nav 
        x-data="{ scrolled: false }" 
        @scroll.window="scrolled = (window.pageYOffset > 20)"
        class="sticky top-0 z-30 transition-all duration-300 w-full"
        :class="scrolled ? 'bg-white/90 backdrop-blur-md shadow-md py-2' : 'bg-transparent py-4'"
    >
        <div class="px-6 flex justify-between items-center">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center shadow-lg overflow-hidden p-1">
                   <img src="{{ asset('images/official_logo.png') }}" alt="Logo" class="w-full h-full object-contain">
                </div>
                <span class="font-bold text-lg tracking-tight transition-colors duration-300"
                      :class="scrolled ? 'text-gray-800' : 'text-gray-800'">
                    BU MADYA
                </span>
            </div>
            {{-- Mobile menu button would go here --}}
            <div class="hidden md:flex items-center space-x-6 text-sm font-semibold tracking-wide">
               {{-- Links... --}}
            </div>
        </div>
    </nav>

    {{-- HERO HEADER --}}
    <header class="relative h-[350px] flex items-center justify-center text-white overflow-hidden rounded-3xl shadow-xl mx-6 -mt-20 z-10">
        <div class="absolute inset-0 z-0">
            <img src="https://images.unsplash.com/photo-1504711434969-e33886168f5c?q=80&w=2070&auto=format&fit=crop" 
                 class="w-full h-full object-cover" alt="News Background">
            <div class="absolute inset-0 bg-gradient-to-r from-red-900/90 to-yellow-700/80 mix-blend-multiply"></div>
        </div>
        <div class="relative z-10 text-center px-4 mt-16">
            <h2 class="text-yellow-300 font-bold tracking-[0.3em] text-xs uppercase mb-2">Updates & Stories</h2>
            <h1 class="font-heading text-3xl md:text-5xl font-black uppercase tracking-tight mb-2 drop-shadow-lg">
                Newsroom
            </h1>
        </div>
    </header>

    {{-- MAIN CONTENT --}}
    <div class="relative min-h-screen px-6 pb-24 mt-12 max-w-[1800px] w-[95%] mx-auto">
        
        {{-- Background Blobs --}}
        <div class="absolute inset-0 z-0 overflow-hidden pointer-events-none">
            <div class="absolute top-40 left-0 w-96 h-96 bg-red-100 rounded-full mix-blend-multiply filter blur-3xl opacity-50"></div>
            <div class="absolute bottom-40 right-0 w-96 h-96 bg-yellow-100 rounded-full mix-blend-multiply filter blur-3xl opacity-50"></div>
        </div>

        {{-- SEARCH & FILTER (Livewire Powered) --}}
        <div class="relative z-10 flex flex-col md:flex-row justify-between items-center mb-12 gap-4">
            
            {{-- Category Buttons --}}
            <div class="flex gap-2 overflow-x-auto pb-2">
                @foreach(['All Stories', 'Environment', 'Education', 'Announcement'] as $cat)
                <button wire:click="setCategory('{{ $cat }}')"
                        class="px-4 py-1.5 rounded-full text-xs font-bold shadow-md transition-all
                               {{ $category === $cat ? 'bg-red-600 text-white' : 'bg-white/60 text-gray-700 hover:bg-white' }}">
                    {{ $cat }}
                </button>
                @endforeach
            </div>

            {{-- Search Input --}}
            <div class="relative w-full md:w-80">
                <input wire:model.live="search" 
                       type="text" 
                       placeholder="Search news..." 
                       class="w-full pl-10 pr-4 py-2 rounded-full border-none bg-white/80 backdrop-blur-sm shadow-sm focus:ring-2 focus:ring-yellow-400 text-sm">
                <svg class="absolute left-3.5 top-2.5 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>
        </div>

        {{-- FEATURED ARTICLE (Static for now) --}}
        @if($category === 'All Stories' && empty($search))
        <div class="relative z-10 mb-12">
            <div class="group relative rounded-3xl overflow-hidden shadow-2xl h-[400px] md:h-[500px]">
                <img src="https://images.unsplash.com/photo-1524178232363-1fb2b075b655?q=80&w=2070&auto=format&fit=crop" 
                     class="absolute inset-0 w-full h-full object-cover transition duration-700 group-hover:scale-105">
                <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/40 to-transparent"></div>
                <div class="absolute bottom-0 left-0 p-8 md:p-12 w-full md:w-2/3">
                    <span class="inline-block px-3 py-1 bg-yellow-400 text-green-900 text-[10px] font-black uppercase tracking-wider rounded-md mb-3">Featured Story</span>
                    <h2 class="font-heading text-3xl md:text-5xl font-bold text-white mb-4 leading-tight">BU MADYA Launches National Youth Advocacy Summit 2025</h2>
                    <a href="{{ route('news.show', 1) }}" class="inline-flex items-center gap-2 text-white font-bold hover:text-yellow-400 transition">
                        Read Full Story &rarr;
                    </a>
                </div>
            </div>
        </div>
        @endif

        {{-- NEWS GRID --}}
        <div class="relative z-10 grid md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($news as $article)
            <article class="group bg-white/60 backdrop-blur-md border border-white/60 rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl hover:-translate-y-2 transition duration-300 flex flex-col">
                <div class="h-48 overflow-hidden relative">
                    <img src="{{ $article['img'] }}" class="w-full h-full object-cover group-hover:scale-110 transition duration-700">
                    <div class="absolute top-4 left-4 bg-white/90 backdrop-blur text-gray-800 text-[10px] font-bold px-3 py-1 rounded-full uppercase tracking-wider shadow-sm">
                        {{ $article['cat'] }}
                    </div>
                </div>
                <div class="p-6 flex flex-col flex-grow">
                    <div class="flex items-center gap-2 text-[10px] text-gray-400 mb-3">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        {{ $article['date'] }}
                    </div>
                    <h3 class="font-heading font-bold text-xl text-gray-900 mb-3 leading-tight group-hover:text-red-600 transition">
                        {{ $article['title'] }}
                    </h3>
                    <p class="text-sm text-gray-600 line-clamp-3 mb-6 flex-grow">
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit...
                    </p>
                    <a href="{{ route('news.show', $article['id']) }}" class="inline-flex items-center text-xs font-bold text-red-600 hover:text-red-800 uppercase tracking-widest">
                        Read Article <span class="ml-2 group-hover:translate-x-1 transition">&rarr;</span>
                    </a>
                </div>
            </article>
            @empty
            <div class="col-span-3 text-center py-10 text-gray-500">
                No stories found for "{{ $search }}" in {{ $category }}.
            </div>
            @endforelse
        </div>
    </div>
</div>
