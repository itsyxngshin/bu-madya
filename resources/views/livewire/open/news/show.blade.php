<div class="relative min-h-screen bg-gray-50 font-sans text-gray-900 selection:bg-red-200 selection:text-red-900">
    
    {{-- BACKGROUND BLOBS (Consistent with other pages) --}}
    <div class="fixed inset-0 z-0 overflow-hidden pointer-events-none">
        <div class="absolute top-0 left-0 w-full h-full bg-gray-50/80"></div>
        <div class="absolute top-[-10%] right-[-10%] w-[500px] h-[500px] bg-red-200 rounded-full mix-blend-multiply filter blur-3xl opacity-40 animate-blob"></div>
        <div class="absolute top-[20%] left-[-10%] w-[500px] h-[500px] bg-yellow-200 rounded-full mix-blend-multiply filter blur-3xl opacity-40 animate-blob animation-delay-2000"></div>
        <div class="absolute bottom-[-10%] right-[20%] w-[500px] h-[500px] bg-green-200 rounded-full mix-blend-multiply filter blur-3xl opacity-40 animate-blob animation-delay-4000"></div>
    </div>

    {{-- 1. READING PROGRESS BAR (Sticky Top with Glass Effect) --}}
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
            <button class="w-8 h-8 rounded-full bg-white/50 border border-gray-200 text-gray-400 hover:bg-blue-50 hover:text-blue-600 hover:border-blue-200 hover:scale-110 flex items-center justify-center transition shadow-sm">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M18 2h-3a5 5 0 00-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 011-1h3z"></path></svg>
            </button>
            <button class="w-8 h-8 rounded-full bg-white/50 border border-gray-200 text-gray-400 hover:bg-sky-50 hover:text-sky-500 hover:border-sky-200 hover:scale-110 flex items-center justify-center transition shadow-sm">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M23 3a10.9 10.9 0 01-3.14 1.53 4.48 4.48 0 00-7.86 3v1A10.66 10.66 0 013 4s-4 9 5 13a11.64 11.64 0 01-7 2c9 5 20 0 20-11.5a4.5 4.5 0 00-.08-.83A7.72 7.72 0 0023 3z"></path></svg>
            </button>
        </div>

        {{-- Gradient Progress Line --}}
        <div class="absolute bottom-0 left-0 h-[3px] bg-gradient-to-r from-green-500 via-yellow-400 to-red-600 transition-all duration-100 ease-out shadow-[0_0_10px_rgba(239,68,68,0.5)]" 
             :style="`width: ${width}%`"></div>
    </div>

    {{-- 2. HERO HEADER (Immersive) --}}
    <header class="relative pt-32 pb-20 px-6 z-10">
        <div class="max-w-5xl mx-auto text-center">
            
            {{-- Category Badge --}}
            <div class="mb-8 flex justify-center animate-fade-in-up">
                <span class="px-5 py-2 bg-white/60 backdrop-blur-md text-red-600 text-xs font-black uppercase tracking-[0.2em] border border-white/50 rounded-full shadow-lg ring-1 ring-red-100">
                    {{ $article['category'] }}
                </span>
            </div>

            {{-- Headline --}}
            <h1 class="font-heading text-4xl md:text-6xl lg:text-7xl font-black text-gray-900 leading-[1.1] mb-10 drop-shadow-sm animate-fade-in-up animation-delay-100">
                {{ $article['title'] }}
            </h1>

            {{-- Meta Data --}}
            <div class="inline-flex items-center gap-6 px-8 py-4 bg-white/60 backdrop-blur-lg rounded-2xl shadow-lg border border-white/40 animate-fade-in-up animation-delay-200">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-red-500 to-yellow-500 p-0.5 shadow-md">
                        <div class="w-full h-full rounded-full bg-white flex items-center justify-center text-red-600 font-bold text-sm">
                            {{ substr($article['author'], 0, 1) }}
                        </div>
                    </div>
                    <div class="text-left">
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Author</p>
                        <p class="text-xs md:text-sm font-bold text-gray-800">{{ $article['author'] }}</p>
                    </div>
                </div>
                
                <div class="w-px h-8 bg-gray-300"></div>

                <div class="text-left">
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Date</p>
                    <p class="text-xs md:text-sm font-bold text-gray-800">{{ $article['date'] }}</p>
                </div>
            </div>

        </div>
    </header>

    {{-- 3. MAIN IMAGE (Glass Frame) --}}
    <div class="relative z-10 w-full max-w-6xl mx-auto px-4 md:px-6 mb-16">
        <div class="relative p-2 bg-white/30 backdrop-blur-sm rounded-[2.5rem] shadow-2xl border border-white/50">
            <div class="relative aspect-[21/9] overflow-hidden rounded-[2rem]">
                <img src="{{ $article['img'] }}" class="w-full h-full object-cover transform hover:scale-105 transition duration-[2s]">
                {{-- Gradient Overlay --}}
                <div class="absolute inset-0 bg-gradient-to-t from-black/40 to-transparent"></div>
            </div>
            
            {{-- Floating Caption --}}
            <div class="absolute bottom-6 right-8 bg-black/60 backdrop-blur-md px-4 py-2 rounded-xl border border-white/10 hidden md:block">
                <p class="text-xs text-white/90 italic">
                    Photo courtesy of BU MADYA Documentation Committee
                </p>
            </div>
        </div>
    </div>

    {{-- 4. ARTICLE BODY (Glass Layout) --}}
    <div class="relative z-10 max-w-[1400px] mx-auto px-4 md:px-6 grid grid-cols-1 lg:grid-cols-12 gap-8 pb-32">
        
        {{-- LEFT: Sticky Summary --}}
        <aside class="hidden lg:block lg:col-span-3">
            <div class="sticky top-28">
                <div class="bg-white/60 backdrop-blur-md p-6 rounded-3xl border border-white/60 shadow-lg mb-6">
                    <h4 class="font-bold text-gray-900 uppercase tracking-widest text-xs mb-4 flex items-center gap-2">
                        <span class="w-8 h-1 bg-red-500 rounded-full"></span> In Brief
                    </h4>
                    <p class="text-sm text-gray-600 leading-relaxed font-medium">
                        "This summit represents a pivotal moment for youth advocacy, uniting leaders to forge sustainable policies for 2030."
                    </p>
                </div>

                {{-- Key Stats Card --}}
                <div class="bg-gradient-to-br from-red-600 to-red-800 p-6 rounded-3xl shadow-xl text-white relative overflow-hidden group">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full blur-2xl -mr-10 -mt-10 group-hover:scale-150 transition duration-700"></div>
                    
                    <h5 class="font-bold text-red-200 text-xs uppercase mb-1">Impact Goal</h5>
                    <div class="text-4xl font-black mb-2 tracking-tight">500+</div>
                    <p class="text-sm text-red-100 leading-tight">Student Leaders Participating</p>
                </div>
            </div>
        </aside>

        {{-- CENTER: The Content (Glass Card) --}}
        <article class="lg:col-span-6">
            <div class="bg-white/70 backdrop-blur-xl p-8 md:p-12 rounded-[2rem] shadow-xl border border-white/60">
                
                {{-- Lead Paragraph --}}
                <p class="text-xl md:text-2xl text-gray-800 leading-relaxed font-serif mb-10 first-letter:text-6xl first-letter:font-black first-letter:text-transparent first-letter:bg-clip-text first-letter:bg-gradient-to-br first-letter:from-red-600 first-letter:to-yellow-500 first-letter:float-left first-letter:mr-3 first-letter:mt-[-5px]">
                    The youth are not just the leaders of tomorrow, but the partners of today. This summit represents a crucial step in formalizing that partnership across our university campuses.
                </p>

                {{-- Prose Content --}}
                <div class="prose prose-lg prose-slate max-w-none font-sans text-gray-600 leading-8">
                    <p>
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.
                    </p>
                    
                    {{-- Styled Blockquote --}}
                    <div class="my-12 relative py-4">
                        <div class="absolute left-0 top-0 bottom-0 w-1 bg-gradient-to-b from-yellow-400 via-red-500 to-green-600 rounded-full"></div>
                        <blockquote class="pl-8 not-italic">
                            <p class="text-2xl font-bold text-gray-900 italic font-heading leading-tight mb-4">
                                "We are building a movement that transcends classroom walls and enters the heart of the community."
                            </p>
                            <footer class="flex items-center gap-3">
                                <div class="h-px w-8 bg-red-300"></div>
                                <span class="text-xs font-bold text-red-600 uppercase tracking-widest">Director General</span>
                            </footer>
                        </blockquote>
                    </div>

                    <h3 class="font-heading font-bold text-gray-900 text-2xl mt-10 mb-4 flex items-center gap-3">
                        <span class="w-2 h-2 rounded-full bg-green-500"></span>
                        A Vision for 2030
                    </h3>
                    
                    <p>
                        Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
                    </p>

                    <figure class="my-10 group overflow-hidden rounded-2xl shadow-lg relative">
                        <img src="https://images.unsplash.com/photo-1517245386807-bb43f82c33c4?q=80&w=2070" class="w-full grayscale group-hover:grayscale-0 transition duration-700">
                        <div class="absolute bottom-0 left-0 w-full bg-gradient-to-t from-black/80 to-transparent p-6 translate-y-full group-hover:translate-y-0 transition duration-300">
                            <figcaption class="text-xs text-white font-medium">
                                Delegates from the College of Social Sciences during the breakout session.
                            </figcaption>
                        </div>
                    </figure>

                    <p>
                        Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo.
                    </p>
                </div>

                {{-- Tags --}}
                <div class="mt-12 pt-8 border-t border-gray-100/50 flex flex-wrap gap-2">
                    @foreach(['Youth', 'Leadership', 'SDGs', 'Bicol'] as $tag)
                    <span class="px-4 py-1.5 bg-gray-50 text-gray-500 text-xs font-bold uppercase tracking-wider rounded-lg border border-gray-100 hover:bg-yellow-400 hover:text-green-900 hover:border-yellow-400 transition cursor-pointer shadow-sm">
                        #{{ $tag }}
                    </span>
                    @endforeach
                </div>
            </div>
        </article>

        {{-- RIGHT: Related News & Newsletter --}}
        <aside class="lg:col-span-3 space-y-8">
            
            {{-- Related News --}}
            <div class="bg-white/60 backdrop-blur-md p-6 rounded-3xl border border-white/60 shadow-lg">
                <h4 class="font-bold text-gray-900 uppercase tracking-widest text-xs border-b border-gray-200 pb-3 mb-6">
                    Read Next
                </h4>

                <div class="space-y-6">
                    @foreach(range(1,3) as $i)
                    <a href="#" class="group block">
                        <div class="aspect-video rounded-xl bg-gray-200 overflow-hidden mb-3 relative">
                            <img src="https://source.unsplash.com/random/400x300?sig={{$i}}" class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                            <div class="absolute top-2 right-2 bg-white/90 px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wide">
                                News
                            </div>
                        </div>
                        <h5 class="font-bold text-gray-800 text-sm leading-tight group-hover:text-red-600 transition">
                            Annual General Assembly Scheduled for January
                        </h5>
                    </a>
                    @endforeach
                </div>
            </div>

            {{-- Newsletter (Colorful Card) --}}
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
                &copy; 2025 Movement for the Advancement of Youth-led Advocacy.
            </div>
        </div>
    </footer>
</div>