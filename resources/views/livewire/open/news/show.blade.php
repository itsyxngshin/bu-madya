<div class="relative min-h-screen bg-gray-50 font-sans text-gray-900 selection:bg-red-200 selection:text-red-900">
    
    {{-- BACKGROUND BLOBS --}}
    <div class="fixed inset-0 z-0 overflow-hidden pointer-events-none">
        <div class="absolute top-0 left-0 w-full h-full bg-gray-50/80"></div>
        <div class="absolute top-[-10%] right-[-10%] w-[500px] h-[500px] bg-red-200 rounded-full mix-blend-multiply filter blur-3xl opacity-40 animate-blob"></div>
        <div class="absolute top-[20%] left-[-10%] w-[500px] h-[500px] bg-yellow-200 rounded-full mix-blend-multiply filter blur-3xl opacity-40 animate-blob animation-delay-2000"></div>
        <div class="absolute bottom-[-10%] right-[20%] w-[500px] h-[500px] bg-green-200 rounded-full mix-blend-multiply filter blur-3xl opacity-40 animate-blob animation-delay-4000"></div>
    </div>

    {{-- 1. READING PROGRESS BAR --}}
    <div x-data="{ width: 0 }" 
         @scroll.window="width = (window.pageYOffset / (document.documentElement.scrollHeight - window.innerHeight)) * 100"
         class="fixed top-0 left-0 w-full z-50 bg-white/80 backdrop-blur-md border-b border-white/20 shadow-sm h-16 flex items-center justify-between px-4 lg:px-12 transition-all duration-300">
        
        <a href="{{ route('news.index') }}" class="group flex items-center gap-2 text-xs font-bold uppercase tracking-widest text-gray-500 hover:text-red-600 transition">
            <div class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center group-hover:bg-red-50 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </div>
            <span class="hidden md:inline">Back to News</span>
        </a>

        <span class="font-heading font-black text-lg tracking-tighter text-gray-900">
            BU <span class="text-transparent bg-clip-text bg-gradient-to-r from-red-600 to-yellow-500">MADYA</span>
        </span>

        <div class="flex gap-2">
            @auth
                @if(auth()->id() === $article->user_id)
                    {{-- Edit Button (Corrected Route) --}}
                    {{-- We pass $article->slug because you switched to using slugs in the URL --}}
                    <a href="{{ route('news.edit', $article->slug) }}" 
                    class="flex items-center gap-1 px-3 py-1 bg-gray-100 hover:bg-yellow-100 text-gray-500 hover:text-yellow-700 rounded-full text-[10px] font-bold uppercase tracking-wider transition border border-gray-200 hover:border-yellow-300">
                        
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                        Edit
                    </a>
                @endif
            @endauth

            {{-- Like Button (Static for now, can be connected to Livewire later) --}}
            <button class="w-8 h-8 rounded-full bg-white/50 border border-gray-200 text-gray-400 hover:bg-red-50 hover:text-red-600 hover:border-red-200 hover:scale-110 flex items-center justify-center transition shadow-sm" title="Like">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"></path></svg>
            </button>
            
            {{-- Share Button --}}
            <button class="w-8 h-8 rounded-full bg-white/50 border border-gray-200 text-gray-400 hover:bg-sky-50 hover:text-sky-500 hover:border-sky-200 hover:scale-110 flex items-center justify-center transition shadow-sm" title="Share">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M18 16.08c-.76 0-1.44.3-1.96.77L8.91 12.7c.05-.23.09-.46.09-.7s-.04-.47-.09-.7l7.05-4.11c.54.5 1.25.81 2.04.81 1.66 0 3-1.34 3-3s-1.34-3-3-3-3 1.34-3 3c0 .24.04.47.09.7L8.04 9.81C7.5 9.31 6.79 9 6 9c-1.66 0-3 1.34-3 3s1.34 3 3 3c.79 0 1.5-.31 2.04-.81l7.12 4.16c-.05.21-.08.43-.08.65 0 1.61 1.31 2.92 2.92 2.92 1.61 0 2.92-1.31 2.92-2.92s-1.31-2.92-2.92-2.92z"></path></svg>
            </button>
        </div>

        {{-- Gradient Progress Line --}}
        <div class="absolute bottom-0 left-0 h-[3px] bg-gradient-to-r from-green-500 via-yellow-400 to-red-600 transition-all duration-100 ease-out shadow-[0_0_10px_rgba(239,68,68,0.5)]" 
             :style="`width: ${width}%`"></div>
    </div>

    {{-- 2. HERO HEADER --}}
    <header class="relative pt-32 pb-20 px-6 z-10">
        <div class="max-w-5xl mx-auto text-center">
            
            {{-- Category --}}
            <div class="mb-8 flex justify-center animate-fade-in-up">
                <span class="px-5 py-2 bg-white/60 backdrop-blur-md text-red-600 text-xs font-black uppercase tracking-[0.2em] border border-white/50 rounded-full shadow-lg ring-1 ring-red-100">
                    {{ $article->category->name ?? 'General' }}
                </span>
            </div>

            {{-- Headline --}}
            <h1 class="font-heading text-4xl md:text-6xl lg:text-7xl font-black text-gray-900 leading-[1.1] mb-10 drop-shadow-sm animate-fade-in-up animation-delay-100">
                {{ $article->title }}
            </h1>

            {{-- Meta Data --}}
            <div class="inline-flex items-center gap-6 px-8 py-4 bg-white/60 backdrop-blur-lg rounded-2xl shadow-lg border border-white/40 animate-fade-in-up animation-delay-200">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-red-500 to-yellow-500 p-0.5 shadow-md">
                        <div class="w-full h-full rounded-full bg-white flex items-center justify-center text-red-600 font-bold text-sm">
                            {{ substr($article->author, 0, 1) }}
                        </div>
                    </div>
                    <div class="text-left">
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Author</p>
                        <p class="text-xs md:text-sm font-bold text-gray-800">{{ $article->author }}</p>
                    </div>
                </div>
                
                <div class="w-px h-8 bg-gray-300"></div>

                <div class="text-left">
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Published</p>
                    <p class="text-xs md:text-sm font-bold text-gray-800">
                        {{ $article->published_at ? $article->published_at->format('M d, Y') : 'Draft' }}
                    </p>
                </div>
            </div>

        </div>
    </header>

    {{-- 3. MAIN IMAGE --}}
    <div class="relative z-10 w-full max-w-6xl mx-auto px-4 md:px-6 mb-16">
        <div class="relative p-2 bg-white/30 backdrop-blur-sm rounded-[2.5rem] shadow-2xl border border-white/50">
            <div class="relative aspect-[21/9] overflow-hidden rounded-[2rem]">
                {{-- Handle both Uploaded Images and External URLs --}}
                <img src="{{ Str::startsWith($article->cover_img, 'http') ? $article->cover_img : asset('storage/' . $article->cover_img) }}" 
                     class="w-full h-full object-cover transform hover:scale-105 transition duration-[2s]">
                
                <div class="absolute inset-0 bg-gradient-to-t from-black/40 to-transparent"></div>
            </div>
            
            @if($article->photo_credit)
            <div class="absolute bottom-6 right-8 bg-black/60 backdrop-blur-md px-4 py-2 rounded-xl border border-white/10 hidden md:block">
                <p class="text-xs text-white/90 italic">
                    Image: {{ $article->photo_credit }}
                </p>
            </div>
            @endif
        </div>
    </div>

    {{-- 4. ARTICLE BODY --}}
    <div class="relative z-10 max-w-[1400px] mx-auto px-4 md:px-6 grid grid-cols-1 lg:grid-cols-12 gap-8 pb-32">
        
        {{-- LEFT: Sticky Sidebar --}}
        <aside class="hidden lg:block lg:col-span-3">
            <div class="sticky top-28 space-y-6">
                {{-- Summary Card --}}
                @if($article->summary)
                <div class="bg-white/60 backdrop-blur-md p-6 rounded-3xl border border-white/60 shadow-lg">
                    <h4 class="font-bold text-gray-900 uppercase tracking-widest text-xs mb-4 flex items-center gap-2">
                        <span class="w-8 h-1 bg-red-500 rounded-full"></span> In Brief
                    </h4>
                    <p class="text-sm text-gray-600 leading-relaxed font-medium">
                        "{{ $article->summary }}"
                    </p>
                </div>
                @endif

                {{-- SDGs (If any) --}}
                @if($article->sdgs->isNotEmpty())
                <div class="bg-white/60 backdrop-blur-md p-6 rounded-3xl border border-white/60 shadow-lg">
                    <h4 class="font-bold text-gray-900 uppercase tracking-widest text-xs mb-4">Targets</h4>
                    <div class="flex flex-wrap gap-2">
                        @foreach($article->sdgs as $sdg)
                            {{-- 
                            FIX: Use style="background-color: ..." for dynamic hex codes.
                            We assume your DB column is named 'color' based on previous steps.
                            If it is 'color_hex', just change $sdg->color to $sdg->color_hex
                            --}}
                            <div style="background-color: {{ $sdg->color_hex ?? '#6b7280' }}"
                                class="w-8 h-8 rounded flex items-center justify-center text-white font-black text-xs shadow-sm" 
                                title="{{ $sdg->name }}">
                                {{ $sdg->id }}
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
            </div>
        </aside>

        {{-- CENTER: Content --}}
        <article class="lg:col-span-6">
            <div class="bg-white/70 backdrop-blur-xl p-8 md:p-12 rounded-[2rem] shadow-xl border border-white/60">
                
                {{-- Markdown Content Rendering --}}
                <div class="prose prose-lg prose-red max-w-none font-sans text-gray-600 leading-8
                            {{ $article->show_drop_cap ? "[&>p:first-child]:first-letter:text-6xl [&>p:first-child]:first-letter:font-black [&>p:first-child]:first-letter:text-transparent [&>p:first-child]:first-letter:bg-clip-text [&>p:first-child]:first-letter:bg-gradient-to-br [&>p:first-child]:first-letter:from-red-600 [&>p:first-child]:first-letter:to-yellow-500 [&>p:first-child]:first-letter:float-left [&>p:first-child]:first-letter:mr-3 [&>p:first-child]:first-letter:mt-[-5px]" : '' }}
                             [&_figcaption]:text-center [&_figcaption]:text-sm [&_figcaption]:text-gray-500 [&_figcaption]:italic [&_figcaption]:mt-2
                            [&_img]:rounded-xl [&_img]:shadow-lg">
                    
                    {!! Str::markdown($article->content) !!}

                </div>

                {{-- Tags --}}
                @if($article->tags)
                <div class="mt-12 pt-8 border-t border-gray-100/50 flex flex-wrap gap-2">
                    @foreach(explode(',', $article->tags) as $tag)
                    <span class="px-4 py-1.5 bg-gray-50 text-gray-500 text-xs font-bold uppercase tracking-wider rounded-lg border border-gray-100 hover:bg-yellow-400 hover:text-green-900 hover:border-yellow-400 transition cursor-pointer shadow-sm">
                        #{{ trim($tag) }}
                    </span>
                    @endforeach
                </div>
                @endif
            </div>
        </article>

        {{-- RIGHT: Related News (Placeholder / Could be Dynamic Later) --}}
        <aside class="lg:col-span-3 space-y-8">
            <div class="bg-gradient-to-br from-gray-900 to-gray-800 p-6 rounded-3xl shadow-xl text-white relative overflow-hidden text-center">
                <div class="absolute top-0 right-0 w-40 h-40 bg-red-500/20 rounded-full blur-3xl -mr-10 -mt-10"></div>
                <h5 class="font-bold text-lg mb-2 text-yellow-400 relative z-10">Stay Updated</h5>
                <p class="text-xs text-gray-400 mb-6 relative z-10">Get the latest advocacy news straight to your inbox.</p>
                <input type="email" placeholder="Email address" class="w-full bg-white/10 border border-white/10 text-xs p-3 rounded-xl text-white mb-3 focus:ring-1 focus:ring-yellow-400 placeholder-gray-500 backdrop-blur-sm relative z-10">
                <button class="w-full bg-gradient-to-r from-red-600 to-red-500 text-white text-xs font-bold uppercase py-3 rounded-xl hover:shadow-lg hover:from-red-500 hover:to-red-400 transition relative z-10">
                    Subscribe
                </button>
            </div>
        </aside>

    </div>

    {{-- 5. FOOTER --}}
    <footer class="bg-white/80 backdrop-blur-md border-t border-gray-200 py-12 relative z-10">
        <div class="max-w-7xl mx-auto px-6 flex flex-col md:flex-row justify-between items-center gap-6">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-gradient-to-br from-red-600 to-yellow-500 p-0.5 rounded-full shadow-md">
                     <div class="w-full h-full bg-white rounded-full flex items-center justify-center">
                        <img src="{{ asset('images/official_logo.png') }}" class="w-6 h-6 object-contain">
                     </div>
                </div>
                <span class="font-bold text-gray-900 tracking-tight text-sm">BU MADYA Newsroom</span>
            </div>
            <div class="text-xs text-gray-500 font-medium">
                &copy; {{ date('Y') }} Movement for the Advancement of Youth-led Advocacy.
            </div>
        </div>
    </footer>
</div>