@section('meta_title', $article->title . ' | BU MADYA')

@section('meta_description', $article->summary ?? Str::limit(strip_tags($article->content), 150))

@section('meta_image')
    @if($article->cover_img)
        {{ Str::startsWith($article->cover_img, 'https') ? $article->cover_img : asset('storage/' . $article->cover_img) }}
    @else
        {{ asset('images/default_news.jpg') }}
    @endif
@endsection

<div class="relative min-h-screen bg-gray-50 font-sans text-gray-900 selection:bg-red-200 selection:text-red-900">
    
    {{-- BACKGROUND BLOBS --}}
    <div class="fixed inset-0 z-0 overflow-hidden pointer-events-none">
        <div class="absolute top-0 left-0 w-full h-full bg-gray-50/80"></div>
        <div class="absolute top-[-10%] right-[-10%] w-[500px] h-[500px] bg-red-200 rounded-full mix-blend-multiply filter blur-3xl opacity-40 animate-blob"></div>
        <div class="absolute top-[20%] left-[-10%] w-[500px] h-[500px] bg-yellow-200 rounded-full mix-blend-multiply filter blur-3xl opacity-40 animate-blob animation-delay-2000"></div>
        <div class="absolute bottom-[-10%] right-[20%] w-[500px] h-[500px] bg-green-200 rounded-full mix-blend-multiply filter blur-3xl opacity-40 animate-blob animation-delay-4000"></div>
    </div>

    {{-- 1. READING PROGRESS BAR & STICKY NAV --}}
    {{-- (Kept identical to previous version) --}}
    <div x-data="{ width: 0 }" 
         @scroll.window="width = (window.pageYOffset / (document.documentElement.scrollHeight - window.innerHeight)) * 100"
         class="fixed top-0 left-0 w-full z-50 bg-white/80 backdrop-blur-md border-b border-white/20 shadow-sm h-16 flex items-center justify-between px-4 lg:px-12 transition-all duration-300">
        
        <a href="{{ route('news.index') }}" class="group flex items-center gap-2 text-xs font-bold uppercase tracking-widest text-gray-500 hover:text-red-600 transition">
            <div class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center group-hover:bg-red-50 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </div>
            <span class="hidden md:inline">Back</span>
        </a>

        <span class="font-heading font-black text-lg tracking-tighter text-gray-900">
            BU <span class="text-transparent bg-clip-text bg-gradient-to-r from-red-600 to-yellow-500">MADYA</span>
        </span>

        <div class="flex gap-2">
            @auth
                @if(auth()->id() === $article->user_id)
                    <a href="{{ route('news.edit', $article->slug) }}" 
                    class="flex items-center gap-1 px-3 py-1 bg-gray-100 hover:bg-yellow-100 text-gray-500 hover:text-yellow-700 rounded-full text-[10px] font-bold uppercase tracking-wider transition border border-gray-200 hover:border-yellow-300">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                        Edit
                    </a>
                @endif
            @endauth

            @auth
                <button wire:click="toggleLike" 
                        class="group relative w-8 h-8 rounded-full border flex items-center justify-center transition shadow-sm {{ $isLiked ? 'bg-red-50 border-red-200 text-red-600' : 'bg-white/50 border-gray-200 text-gray-400 hover:text-red-600' }}" 
                        title="{{ $likesCount }} Likes">
                    <svg class="w-4 h-4 transition-transform group-hover:scale-110 {{ $isLiked ? 'fill-current' : 'fill-none' }}" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                    </svg>
                    @if($likesCount > 0)
                    <span class="absolute -top-2 -right-2 bg-red-600 text-white text-[9px] font-bold px-1.5 py-0.5 rounded-full shadow-sm">{{ $likesCount }}</span>
                    @endif
                </button>
            @endauth
        </div>

        <div class="absolute bottom-0 left-0 h-[3px] bg-gradient-to-r from-green-500 via-yellow-400 to-red-600 transition-all duration-100 ease-out shadow-[0_0_10px_rgba(239,68,68,0.5)]" 
             :style="`width: ${width}%`"></div>
    </div>

    {{-- 2. NEW SIDE-BY-SIDE HEADER SECTION --}}
    <section class="relative pt-24 pb-8 px-4 md:px-6 z-10 max-w-[1400px] mx-auto animate-fade-in-up">
        
        {{-- Added gap-8 for mobile (tighter) vs gap-12 for desktop --}}
        <div class="grid lg:grid-cols-2 gap-8 lg:gap-12 items-center">
            
            {{-- LEFT COLUMN: Text Content --}}
            <div class="text-left space-y-6 md:space-y-8">
                
                {{-- Category: Smaller text and padding on mobile --}}
                <div>
                    <span class="px-3 py-1 md:px-4 md:py-1.5 bg-white/60 backdrop-blur-md text-red-600 text-[10px] md:text-xs font-black uppercase tracking-[0.2em] border border-white/50 rounded-full shadow-sm ring-1 ring-red-100">
                        {{ $article->category->name ?? 'General' }}
                    </span>
                </div>

                {{-- Headline: text-3xl on mobile -> text-6xl on desktop --}}
                <h1 class="font-heading text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-black text-gray-900 leading-tight drop-shadow-sm">
                    {{ $article->title }}
                </h1>

                {{-- Meta Data: Stacked on very small screens, flex on others --}}
                <div class="flex flex-wrap items-start gap-3 md:gap-4 text-left">
                    @foreach($article->authors as $author)
                        <div class="inline-flex items-center gap-2 md:gap-3 px-4 py-2 md:px-5 md:py-2.5 bg-white/60 backdrop-blur-lg rounded-2xl shadow-sm border border-white/40">
                            {{-- Smaller Avatar on Mobile --}}
                            <div class="w-8 h-8 md:w-10 md:h-10 rounded-full bg-gradient-to-br from-red-500 to-yellow-500 p-0.5 shadow-md shrink-0">
                                <div class="w-full h-full rounded-full bg-white flex items-center justify-center text-red-600 font-bold text-xs md:text-sm">
                                    {{ substr($author->name, 0, 1) }}
                                </div>
                            </div>
                            <div>
                                <p class="text-[8px] md:text-[9px] font-bold text-gray-400 uppercase tracking-wider leading-none mb-0.5">{{ $author->type }}</p>
                                {{-- Smaller Name text on Mobile --}}
                                <p class="text-xs md:text-sm font-bold text-gray-800 leading-none whitespace-nowrap">{{ $author->name }}</p>
                            </div>
                        </div>
                    @endforeach

                    {{-- Date Badge --}}
                    <div class="inline-flex items-center gap-3 px-4 py-2 md:px-5 md:py-2.5 bg-white/60 backdrop-blur-lg rounded-2xl shadow-sm border border-white/40">
                        <div>
                            <p class="text-[8px] md:text-[9px] font-bold text-gray-400 uppercase tracking-wider leading-none mb-0.5">Published</p>
                            <p class="text-xs md:text-sm font-bold text-gray-800 leading-none">
                                {{ $article->published_at ? $article->published_at->format('M d, Y') : 'Draft' }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- RIGHT COLUMN: Image --}}
            <div class="relative mt-4 lg:mt-0">
                <div class="relative p-2 bg-white/30 backdrop-blur-sm rounded-[2rem] md:rounded-[2.5rem] shadow-2xl border border-white/50 transform hover:scale-[1.02] transition duration-500">
                    <div class="relative aspect-[16/9] lg:aspect-[4/3] overflow-hidden rounded-[1.5rem] md:rounded-[2rem]">
                        @php
                            $imgSrc = $article->cover_img 
                                ? (Str::startsWith($article->cover_img, 'https') ? $article->cover_img : asset('storage/' . $article->cover_img))
                                : asset('images/default_news.jpg');
                        @endphp
                        <img src="{{ $imgSrc }}" class="w-full h-full object-cover">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent"></div>
                    </div>
                    
                    @if($article->photo_credit)
                    <div class="absolute bottom-4 right-6 bg-black/60 backdrop-blur-md px-3 py-1.5 rounded-xl border border-white/10 hidden md:block">
                        <p class="text-[10px] md:text-xs text-white/90 italic">Image: {{ $article->photo_credit }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </section>

    {{-- 3. ARTICLE BODY & SIDEBAR --}}
    <div class="relative z-10 max-w-[1400px] mx-auto px-4 md:px-6 grid grid-cols-1 lg:grid-cols-12 gap-8 md:gap-12 pb-12">
        
        {{-- LEFT SIDEBAR: Summary & SDGs --}}
        {{-- Mobile: Appears First. Desktop: Sticky Left Column --}}
        <aside class="lg:col-span-3 order-1 lg:order-1">
            <div class="lg:sticky lg:top-28 space-y-6">
                
                {{-- Summary Card --}}
                @if($article->summary)
                <div class="bg-white/80 backdrop-blur-md p-6 rounded-2xl border-l-4 border-red-500 shadow-sm">
                    <h4 class="font-bold text-gray-900 uppercase tracking-widest text-xs mb-3 flex items-center gap-2">
                        In Brief
                    </h4>
                    <p class="text-sm text-gray-600 leading-relaxed font-medium italic">
                        "{{ $article->summary }}"
                    </p>
                </div>
                @endif

                {{-- SDGs Card --}}
                @if($article->sdgs->isNotEmpty())
                <div class="bg-white/60 backdrop-blur-md p-6 rounded-2xl border border-white/60 shadow-sm">
                    <h4 class="font-bold text-gray-900 uppercase tracking-widest text-xs mb-4 text-gray-500">
                        Target SDGs
                    </h4>
                    <div class="flex flex-wrap gap-2">
                        @foreach($article->sdgs as $sdg)
                            <div class="relative group cursor-help">
                                {{-- The SDG Icon --}}
                                <div style="background-color: {{ $sdg->color_hex ?? '#6b7280' }}" 
                                     class="w-10 h-10 rounded-lg flex items-center justify-center text-white font-black text-xs shadow-sm transform group-hover:scale-110 transition z-10 relative">
                                    {{ $sdg->id }}
                                </div>

                                {{-- Tooltip --}}
                                <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 w-max max-w-[150px] hidden group-hover:block z-20">
                                    <div class="bg-gray-900 text-white text-[10px] font-bold px-3 py-2 rounded shadow-lg text-center leading-tight">
                                        {{ $sdg->name }}
                                    </div>
                                    <div class="w-2 h-2 bg-gray-900 rotate-45 absolute left-1/2 -translate-x-1/2 -bottom-1"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                @endif

            </div>
        </aside>

        {{-- CENTER: Main Content --}}
        {{-- Mobile: Appears Second. Desktop: Takes up remaining 9 cols --}}
        <article class="lg:col-span-9 order-2 lg:order-2">
            <div class="bg-white/70 backdrop-blur-xl p-5 md:p-12 rounded-[2rem] shadow-xl border border-white/60">
                
                {{-- Markdown Content --}}
                <div class="prose prose-red max-w-none font-sans text-gray-600 leading-7 md:prose-lg md:leading-8
                            {{-- Drop Cap Logic --}}
                            {{ $article->show_drop_cap ? "[&>p:first-child]:first-letter:text-4xl md:[&>p:first-child]:first-letter:text-6xl [&>p:first-child]:first-letter:font-black [&>p:first-child]:first-letter:text-transparent [&>p:first-child]:first-letter:bg-clip-text [&>p:first-child]:first-letter:bg-gradient-to-br [&>p:first-child]:first-letter:from-red-600 [&>p:first-child]:first-letter:to-yellow-500 [&>p:first-child]:first-letter:float-left [&>p:first-child]:first-letter:mr-2 md:[&>p:first-child]:first-letter:mr-3 [&>p:first-child]:first-letter:mt-[-2px] md:[&>p:first-child]:first-letter:mt-[-5px]" : '' }}
                            [&_img]:rounded-xl [&_img]:shadow-lg [&_img]:w-full">
                    
                    {!! Str::markdown($article->content) !!}
                
                </div>

                {{-- Tags --}}
                @if($article->tags)
                <div class="mt-8 md:mt-12 pt-6 md:pt-8 border-t border-gray-100/50 flex flex-wrap gap-2">
                    @foreach(explode(',', $article->tags) as $tag)
                    <span class="px-3 py-1 md:px-4 md:py-1.5 bg-gray-50 text-gray-500 text-[10px] md:text-xs font-bold uppercase tracking-wider rounded-lg border border-gray-100 shadow-sm hover:bg-red-50 hover:text-red-600 transition cursor-default">
                        #{{ trim($tag) }}
                    </span>
                    @endforeach
                </div>
                @endif
            </div>

            {{-- COMMENT SECTION --}}
            <div class="mt-8 bg-white/60 backdrop-blur-xl p-6 md:p-8 rounded-[2rem] shadow-lg border border-white/60">
                <h3 class="font-heading font-bold text-lg md:text-xl text-gray-900 mb-6 flex items-center gap-2">
                    Discussion <span class="bg-red-100 text-red-600 text-xs px-2 py-0.5 rounded-full">{{ $article->comments->count() }}</span>
                </h3>

                {{-- Comment Form --}}
                @auth
                    <div class="mb-8 flex gap-3 md:gap-4">
                        <div class="w-8 h-8 md:w-10 md:h-10 rounded-full bg-gray-200 shrink-0 overflow-hidden border border-white shadow-sm">
                             <div class="w-full h-full flex items-center justify-center bg-gray-300 font-bold text-gray-500 text-xs md:text-sm">{{ substr(auth()->user()->name, 0, 1) }}</div>
                        </div>
                        <div class="grow">
                            <textarea wire:model="newComment" rows="2" class="w-full bg-white/50 border border-white/60 rounded-xl p-3 md:p-4 text-xs md:text-sm focus:ring-2 focus:ring-yellow-400 focus:border-transparent transition resize-none shadow-inner" placeholder="Share your thoughts..."></textarea>
                            <div class="flex justify-end mt-2">
                                <button wire:click="postComment" class="px-4 py-2 md:px-6 md:py-2 bg-gray-900 text-white text-[10px] md:text-xs font-bold uppercase rounded-lg hover:bg-red-600 transition shadow-lg">
                                    Post Comment
                                </button>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="bg-gray-50 rounded-xl p-6 text-center mb-8 border border-gray-200">
                        <p class="text-xs md:text-sm text-gray-500 mb-2">Join the conversation</p>
                        <a href="{{ route('login') }}" class="text-red-600 font-bold text-xs md:text-sm hover:underline">Log in to comment</a>
                    </div>
                @endauth

                {{-- Comments List --}}
                <div class="space-y-6">
                    @forelse($article->comments as $comment)
                        <div class="flex gap-3 md:gap-4 group">
                            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-gray-200 to-gray-300 shrink-0 flex items-center justify-center font-bold text-gray-500 text-xs border border-white shadow-sm">
                                {{ substr($comment->user->name, 0, 1) }}
                            </div>
                            <div class="grow">
                                <div class="bg-white/80 p-3 md:p-4 rounded-2xl rounded-tl-none shadow-sm border border-gray-100">
                                    <div class="flex justify-between items-start mb-1">
                                        <span class="text-xs font-bold text-gray-900">{{ $comment->user->name }}</span>
                                        <span class="text-[9px] md:text-[10px] text-gray-400">{{ $comment->created_at->diffForHumans() }}</span>
                                    </div>
                                    <p class="text-xs md:text-sm text-gray-600 leading-relaxed">{{ $comment->content }}</p>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8 md:py-10">
                            <p class="text-gray-400 italic text-xs md:text-sm">No comments yet. Be the first to share your thoughts!</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </article>

    </div>

    {{-- FOOTER (Kept identical) --}}
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