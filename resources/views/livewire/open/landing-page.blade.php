<div class="min-h-screen bg-stone-50 font-sans text-gray-900 selection:bg-red-600 selection:text-white overflow-x-hidden">
    
    {{-- 1. HERO SECTION (Color-Harmonized & Refined) --}}
    <header class="relative min-h-[85vh] md:min-h-[800px] flex items-center justify-center text-white overflow-hidden rounded-b-[50px] md:rounded-b-[80px] shadow-2xl border-b-4 border-red-600">
        
        {{-- Background & Overlay --}}
        <div class="absolute inset-0 z-0 bg-gray-950">
            <img src="{{ asset('images/1760712981522.JPG') }}" 
                 class="w-full h-full object-cover transform scale-105 animate-slow-pan opacity-60" 
                 alt="BU MADYA Team">
            
            {{-- Harmonized Overlay: Deep Gray to Maroon/Red --}}
            <div class="absolute inset-0 bg-gradient-to-b from-gray-900/90 via-gray-900/70 to-red-950/90 mix-blend-multiply"></div>
            
            {{-- Decorative Glows --}}
            <div class="absolute top-1/4 left-1/4 w-[500px] h-[500px] bg-red-600/20 rounded-full blur-[120px] animate-pulse"></div>
            <div class="absolute bottom-1/4 right-1/4 w-[400px] h-[400px] bg-yellow-500/10 rounded-full blur-[100px] animate-pulse delay-1000"></div>
        </div>

        {{-- Hero Content --}}
        <div x-data="{ show: false }" x-init="setTimeout(() => show = true, 100)" 
             class="relative z-10 container mx-auto px-6 text-center mt-10 pb-20 md:pb-32">
            
            {{-- Logo Animation --}}
            <div x-show="show" 
                 x-transition:enter="transition ease-out duration-1000"
                 x-transition:enter-start="opacity-0 -translate-y-10 scale-50"
                 x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                 class="w-24 h-24 md:w-32 md:h-32 mx-auto mb-8 bg-white/5 backdrop-blur-md rounded-full flex items-center justify-center border border-white/10 shadow-2xl p-4 ring-1 ring-white/20">
                <img src="{{ asset('images/MADYA Web Logo1.png') }}" alt="Logo" class="w-full h-full object-contain drop-shadow-2xl">
            </div>

            <div x-show="show"
                 x-transition:enter="transition ease-out duration-1000 delay-300"
                 x-transition:enter-start="opacity-0 translate-y-8"
                 x-transition:enter-end="opacity-100 translate-y-0">
                
                <h2 class="text-yellow-400 font-black tracking-[0.3em] text-xs md:text-sm mb-4 uppercase drop-shadow-md">
                    Bicol University
                </h2>
                
                <h1 class="font-heading text-4xl md:text-6xl lg:text-7xl font-black uppercase tracking-tight leading-none mb-6 drop-shadow-2xl text-white">
                    Movement for the <br class="hidden md:block">
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-yellow-300 via-yellow-400 to-red-500 drop-shadow-sm">
                        Advocacy of Youth
                    </span>
                </h1>
                
                <p class="text-base md:text-xl text-gray-200 max-w-2xl mx-auto mb-10 font-medium drop-shadow-md">
                    Empowering youth-led advocacy and fostering sustainable development through active dialogue and democratic action.
                </p>

                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="{{ route('membership-form') }}" class="px-8 py-4 bg-gradient-to-r from-yellow-400 to-yellow-500 text-yellow-950 font-black rounded-full shadow-[0_0_30px_rgba(250,204,21,0.3)] hover:shadow-[0_0_40px_rgba(250,204,21,0.5)] hover:scale-105 transition transform uppercase tracking-widest text-xs">
                        Join the Movement
                    </a>
                    <a href="#pillars" class="px-8 py-4 bg-white/5 backdrop-blur-md border border-white/20 text-white font-bold rounded-full hover:bg-white/10 transition uppercase tracking-widest text-xs">
                        Explore Pillars
                    </a>
                </div>
            </div>
        </div>
    </header>

    {{-- MAIN CONTENT --}}
    <main class="max-w-[1800px] w-[95%] mx-auto px-4 relative z-20 pb-24">
        
        <div class="grid lg:grid-cols-12 gap-8 xl:gap-12 items-start pt-12">
            
            {{-- LEFT COLUMN (Main Content) --}}
            <div class="lg:col-span-8 space-y-20">    

                {{-- PILLARS SECTION --}}
                <section id="pillars"
                    x-data="{ 
                        activePillar: null,
                        pillars: [
                            { id: 1, title: 'Culture & Heritage', icon: 'M12 21a9.004 9.004 0 008.716-6.747M12 21a9.004 9.004 0 01-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 017.843 4.582M12 3a8.997 8.997 0 00-7.843 4.582m15.686 0A11.953 11.953 0 0112 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0121 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0112 16.5c-3.162 0-6.133-.815-8.716-2.247m0 0A9.015 9.015 0 013 12c0-1.605.42-3.113 1.157-4.418', color: 'text-amber-600', bg: 'bg-amber-50', border: 'border-amber-200', desc: 'Preserving our roots by documenting local history and promoting Bicolano arts and traditions.' },
                            { id: 2, title: 'Social Sciences', icon: 'M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z', color: 'text-blue-600', bg: 'bg-blue-50', border: 'border-blue-200', desc: 'Fostering critical thinking through debates, forums, and social research.' },
                            { id: 3, title: 'Quality Education', icon: 'M4.26 10.147a60.436 60.436 0 00-.491 6.347A48.627 48.627 0 0112 20.904a48.627 48.627 0 018.232-4.41 60.46 60.46 0 00-.491-6.347m-15.482 0a50.57 50.57 0 00-2.658-.813A59.905 59.905 0 0112 3.493a59.902 59.902 0 0110.499 5.221 69.17 69.17 0 00-2.692 1.296M3.191 14.463l.73 1.053a4.5 4.5 0 01.73 2.518V20.89M12 15.63a6.002 6.002 0 01-6-6.002 6.002 6.002 0 0112 0 6.002 6.002 0 01-6 6.002z', color: 'text-red-600', bg: 'bg-red-50', border: 'border-red-200', desc: 'Ensuring accessible learning resources and peer-tutoring programs to leave no student behind.' },
                            { id: 4, title: 'Science & Tech', icon: 'M8.25 3v1.5M4.5 8.25H3m18 0h-1.5M4.5 12H3m18 0h-1.5m-15 3.75H3m18 0h-1.5M8.25 19.5V21M12 3v1.5m0 15V21m3.75-18v1.5m0 15V21m-9-1.5h10.5a2.25 2.25 0 002.25-2.25V6.75a2.25 2.25 0 00-2.25-2.25H6.75A2.25 2.25 0 004.5 6.75v10.5a2.25 2.25 0 002.25 2.25z', color: 'text-emerald-600', bg: 'bg-emerald-50', border: 'border-emerald-200', desc: 'Innovating for the future by supporting student-led research and tech solutions.' },
                            { id: 5, title: 'Digital Strategies', icon: 'M8.288 15.038a5.25 5.25 0 017.424 0M5.106 11.856c3.807-3.808 9.98-3.808 13.788 0M1.924 8.674c5.565-5.565 14.587-5.565 20.152 0M12.53 18.22l-.53.53-.53-.53a.75.75 0 011.06 0z', color: 'text-purple-600', bg: 'bg-purple-50', border: 'border-purple-200', desc: 'Leveraging modern media to amplify our advocacy reach and combat misinformation.' }
                        ]
                    }">
                    
                    <div class="mb-8 pl-4 border-l-4 border-red-600">
                        <h2 class="font-heading text-3xl font-black text-gray-800">Our Core Pillars</h2>
                        <p class="text-gray-500 font-medium mt-1">The 5 Foundations of our Advocacy</p>
                    </div>

                    <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-5 gap-3">
                        <template x-for="pillar in pillars" :key="pillar.id">
                            <button 
                                @click="activePillar = (activePillar === pillar.id ? null : pillar.id)"
                                class="bg-white rounded-2xl p-5 shadow-sm hover:shadow-md transition-all duration-300 group text-left border h-36 md:h-40 flex flex-col justify-between relative overflow-hidden"
                                :class="activePillar === pillar.id ? 'ring-2 ring-offset-2 ring-gray-100 ' + pillar.border : 'border-gray-200 hover:border-gray-300'"
                            >
                                <div class="w-12 h-12 rounded-xl flex items-center justify-center transition-transform duration-300 group-hover:scale-110 group-hover:rotate-3"
                                     :class="pillar.bg + ' ' + pillar.color">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                        <path stroke-linecap="round" stroke-linejoin="round" :d="pillar.icon" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="font-bold text-gray-800 leading-tight text-xs md:text-sm group-hover:text-black transition-colors" x-text="pillar.title"></h3>
                                </div>
                            </button>
                        </template>
                    </div>

                    <div x-show="activePillar !== null" x-collapse class="mt-4">
                        <template x-for="pillar in pillars" :key="pillar.id">
                            <div x-show="activePillar === pillar.id"
                                 class="bg-white rounded-2xl p-6 md:p-8 shadow-sm border border-gray-200 relative overflow-hidden"
                                 x-transition:enter="transition ease-out duration-300"
                                 x-transition:enter-start="opacity-0 -translate-y-2"
                                 x-transition:enter-end="opacity-100 translate-y-0">
                                <div class="relative z-10 flex flex-col md:flex-row gap-6 items-start">
                                    <div class="hidden md:flex w-16 h-16 rounded-2xl items-center justify-center shrink-0" :class="pillar.bg + ' ' + pillar.color">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8">
                                            <path stroke-linecap="round" stroke-linejoin="round" :d="pillar.icon" />
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-xl md:text-2xl font-black text-gray-900 mb-3" x-text="pillar.title"></h3>
                                        <p class="text-gray-600 text-base md:text-lg leading-relaxed" x-text="pillar.desc"></p>
                                        <div class="mt-6 pt-6 border-t border-gray-100">
                                            <a href="{{ route('projects.index') }}" class="inline-flex items-center text-xs font-black uppercase tracking-widest transition-colors hover:opacity-70" :class="pillar.color">
                                                Explore Initiatives <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                </section>

                {{-- FEATURES SECTION --}}
                <section class="bg-white rounded-[2rem] p-8 md:p-12 shadow-sm border border-gray-200 flex flex-col md:flex-row items-center gap-12 relative overflow-hidden">
                    <div class="absolute -right-20 -top-20 w-64 h-64 bg-yellow-50 rounded-full blur-3xl"></div>
                    <div class="w-full md:w-1/2 relative z-10">
                        <span class="text-yellow-600 font-black uppercase tracking-widest text-[10px] mb-3 block">Our Mission</span>
                        <h3 class="font-heading text-3xl md:text-5xl font-black text-gray-900 mb-6 tracking-tight leading-tight">Advocacy for <br><span class="text-red-600">Everyone.</span></h3>
                        <p class="text-gray-600 mb-8 leading-relaxed text-lg">BU MADYA creates space for meaningful engagement and representation. We believe that true change starts when every student feels empowered to speak, act, and lead.</p>
                        <a href="{{ route('about') }}" class="inline-flex items-center justify-center px-8 py-3.5 bg-gray-900 text-white font-bold rounded-xl hover:bg-red-600 transition-colors shadow-lg text-sm uppercase tracking-wider">
                            Read Manifesto
                        </a>
                    </div>
                    <div class="w-full md:w-1/2 relative z-10">
                        <div class="absolute inset-0 bg-gradient-to-br from-yellow-400 to-orange-500 rounded-3xl transform rotate-3 scale-[0.98] shadow-inner"></div>
                        <img src="https://images.unsplash.com/photo-1529156069898-49953e39b3ac?q=80&w=1932" class="relative rounded-3xl shadow-xl w-full object-cover aspect-[4/3] transform hover:-rotate-1 transition duration-500 border-4 border-white">
                    </div>
                </section>

                {{-- DYNAMIC NEWS SECTION --}}
                <section class="space-y-8">
                    <div class="flex items-center justify-between px-2 border-b border-gray-200 pb-4">
                        <h2 class="font-heading text-2xl font-black text-gray-900">Latest Dispatches</h2>
                        <a href="{{ route('news.index') }}" class="text-[10px] font-black text-gray-500 hover:text-red-600 uppercase tracking-widest flex items-center gap-2 group transition">
                            View Archive <svg class="w-4 h-4 group-hover:translate-x-1 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                        </a>
                    </div>

                    <div class="grid md:grid-cols-2 gap-6">
                    @forelse($latestNews ?? [] as $news)
                        <article class="group bg-white rounded-3xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex flex-col h-full">
                            <div class="h-48 md:h-56 overflow-hidden relative bg-gray-100">
                                @php
                                    $catName = is_object($news->category) ? $news->category->name : $news->category;
                                    $coverUrl = $news->cover_img ? (Str::startsWith($news->cover_img, ['http', 'https']) ? $news->cover_img : asset('storage/' . $news->cover_img)) : null;
                                @endphp
                                
                                <div class="absolute top-4 left-4 z-10">
                                    <span class="bg-white/95 backdrop-blur text-gray-900 text-[10px] font-black px-3 py-1.5 rounded-md shadow-sm uppercase tracking-widest">
                                        {{ $catName }}
                                    </span>
                                </div>

                                @if($coverUrl)
                                    <img src="{{ $coverUrl }}" alt="{{ $news->title }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-700">
                                @endif
                            </div>

                            <div class="p-6 md:p-8 flex flex-col flex-1">
                                <div class="flex items-center gap-2 text-[10px] text-gray-400 font-bold uppercase tracking-widest mb-3">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    {{ $news->published_at ? $news->published_at->format('M d, Y') : 'TBA' }}
                                </div>
                                
                                <h3 class="font-black text-xl text-gray-900 mb-3 leading-tight group-hover:text-red-600 transition">
                                    <a href="{{ route('news.show', $news->slug) }}" class="focus:outline-none">{{ $news->title }}</a>
                                </h3>
                                
                                <p class="text-gray-500 text-sm line-clamp-3 mb-6 flex-1 leading-relaxed">
                                    {{ $news->summary ?? Str::limit(strip_tags($news->content), 100) }}
                                </p>
                                
                                <div class="pt-4 border-t border-gray-100">
                                    <a href="{{ route('news.show', $news->slug) }}" class="text-[10px] font-black text-red-600 uppercase tracking-widest flex items-center gap-1 group/link">
                                        Read Article <svg class="w-4 h-4 group-hover/link:translate-x-1 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                                    </a>
                                </div>
                            </div>
                        </article>
                    @empty
                        <div class="col-span-2 text-center py-16 bg-white rounded-3xl border-2 border-dashed border-gray-200">
                            <p class="text-gray-400 font-bold">No updates posted yet. Check back soon!</p>
                        </div>
                    @endforelse
                    </div>
                </section>
            </div>

            {{-- RIGHT COLUMN (Sidebar) --}}
            <aside class="lg:col-span-4 space-y-8 lg:sticky lg:top-8">
                
                {{-- NEW: DEMOCRACY HUB (Live Elections Widget) --}}
                @if(isset($activeElections) && $activeElections->count() > 0)
                    <div class="bg-white rounded-3xl p-1 shadow-md border border-green-100 relative overflow-hidden">
                        <div class="bg-green-50 rounded-[1.35rem] p-6 md:p-8">
                            <div class="flex justify-between items-center mb-6">
                                <h3 class="font-black text-green-900 text-lg flex items-center gap-2">
                                    <span class="w-2.5 h-2.5 rounded-full bg-green-500 animate-ping absolute"></span>
                                    <span class="w-2.5 h-2.5 rounded-full bg-green-500 relative"></span>
                                    Live Elections
                                </h3>
                            </div>
                            
                            <div class="space-y-4">
                                @foreach($activeElections as $election)
                                    <div class="bg-white rounded-xl p-4 shadow-sm border border-green-100">
                                        <h4 class="font-bold text-gray-900 text-sm mb-1 leading-tight">{{ $election->title }}</h4>
                                        <p class="text-[10px] text-gray-500 font-bold uppercase tracking-wider mb-4">Closes: {{ $election->voting_end->format('M d, g:i A') }}</p>
                                        
                                        <div class="grid grid-cols-2 gap-2">
                                            <a href="{{ route('elections.apply', $election->slug) }}" class="text-center py-2 bg-gray-50 hover:bg-gray-100 text-gray-700 text-[10px] font-black uppercase tracking-widest rounded-lg border border-gray-200 transition">Apply</a>
                                            <a href="{{ route('elections.vote', $election->slug) }}" class="text-center py-2 bg-green-600 hover:bg-green-700 text-white text-[10px] font-black uppercase tracking-widest rounded-lg shadow-sm transition">Vote Now</a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Membership Card --}}
                <div class="bg-gray-950 rounded-3xl p-8 text-white relative overflow-hidden shadow-xl border border-gray-800 group">
                    <div class="absolute -top-24 -right-24 w-64 h-64 bg-red-600/20 rounded-full blur-3xl group-hover:bg-red-600/30 transition duration-700"></div>
                    
                    <div class="relative z-10">
                        <div class="w-12 h-12 bg-white/5 rounded-2xl flex items-center justify-center mb-6 backdrop-blur-sm border border-white/10">
                            <svg class="w-6 h-6 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        </div>
                        <h3 class="font-heading text-2xl font-black mb-3">Join the Movement</h3>
                        <p class="text-gray-400 text-sm mb-8 leading-relaxed font-medium">Get exclusive access to workshops, events, and mentorship. Become a youth advocate today.</p>
                        <a href="{{ route('membership-form') }}" class="block w-full py-4 bg-red-600 text-white font-black rounded-xl hover:bg-red-500 transition shadow-lg text-center text-xs uppercase tracking-widest">
                            Apply for Membership
                        </a>
                    </div>
                </div>

                {{-- Improved Gallery Widget with Dots --}}
                <div x-data="{ 
                        activeSlide: 0, 
                        slides: [
                            { img: 'https://images.unsplash.com/photo-1517486808906-6ca8b3f04846?q=80&w=1949', caption: 'Tree Planting' }, 
                            { img: 'https://images.unsplash.com/photo-1523580494863-6f3031224c94?q=80&w=2070', caption: 'Student Leadership' }, 
                            { img: 'https://images.unsplash.com/photo-1517245386807-bb43f82c33c4?q=80&w=2070', caption: 'General Assembly' }
                        ],
                        next() { this.activeSlide = this.activeSlide === this.slides.length - 1 ? 0 : this.activeSlide + 1 },
                        prev() { this.activeSlide = this.activeSlide === 0 ? this.slides.length - 1 : this.activeSlide - 1 }
                    }" 
                    class="bg-white rounded-3xl p-1.5 shadow-sm border border-gray-200">
                    
                    <div class="relative rounded-2xl overflow-hidden aspect-[4/3] md:aspect-square group">
                        <template x-for="(slide, index) in slides" :key="index">
                            <div x-show="activeSlide === index" 
                                 x-transition:enter="transition ease-out duration-500" 
                                 x-transition:enter-start="opacity-0 scale-105" 
                                 x-transition:enter-end="opacity-100 scale-100" 
                                 class="absolute inset-0">
                                <img :src="slide.img" class="w-full h-full object-cover">
                                <div class="absolute inset-0 bg-gradient-to-t from-gray-900/90 via-gray-900/20 to-transparent"></div>
                                <div class="absolute bottom-8 left-6 right-6">
                                    <span class="text-[10px] font-black text-yellow-400 uppercase tracking-widest mb-1.5 block">Gallery</span>
                                    <p x-text="slide.caption" class="text-white font-bold text-xl leading-tight"></p>
                                </div>
                            </div>
                        </template>
                        
                        {{-- Controls --}}
                        <div class="absolute top-1/2 -translate-y-1/2 left-4 right-4 flex justify-between opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                            <button @click="prev()" class="w-8 h-8 bg-white/20 backdrop-blur rounded-full flex items-center justify-center text-white hover:bg-white hover:text-gray-900 transition"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg></button>
                            <button @click="next()" class="w-8 h-8 bg-white/20 backdrop-blur rounded-full flex items-center justify-center text-white hover:bg-white hover:text-gray-900 transition"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg></button>
                        </div>

                        {{-- Dots Indicator --}}
                        <div class="absolute bottom-4 left-0 right-0 flex justify-center gap-1.5">
                            <template x-for="(slide, index) in slides" :key="index">
                                <button @click="activeSlide = index" class="w-1.5 h-1.5 rounded-full transition-all duration-300" :class="activeSlide === index ? 'bg-yellow-400 w-4' : 'bg-white/50 hover:bg-white/80'"></button>
                            </template>
                        </div>
                    </div>
                </div>

            </aside>
        </div>
    </main>

    {{-- FOOTER (Cleaned up borders & spacing) --}}
    <footer class="bg-gray-950 text-white pt-20 pb-8 border-t-4 border-red-600 relative z-20">
        <div class="max-w-[1800px] w-[95%] mx-auto px-4 grid md:grid-cols-4 gap-12 mb-16">
            
            <div class="col-span-1 md:col-span-2">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 bg-red-600 text-white rounded-xl flex items-center justify-center shadow-lg border border-red-500">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.879 16.121A3 3 0 1012.015 11L11 14H9c0 .768.293 1.536.879 2.121z"></path></svg>
                    </div>
                    <span class="font-heading font-black text-2xl tracking-tight">BU MADYA</span>
                </div>
                <p class="text-gray-400 leading-relaxed max-w-sm mb-8 text-sm font-medium">
                    The Bicol University - Movement for the Advancement of Youth-led Advocacy is a duly-accredited University Based Organization committed to service through advocacy.
                </p>
                
                {{-- Social Media Links --}}
                <div class="flex space-x-3">
                    <a href="https://www.facebook.com/BUMadya" class="w-10 h-10 rounded-xl bg-gray-900 border border-gray-800 flex items-center justify-center hover:bg-blue-600 hover:border-blue-500 hover:text-white text-gray-400 transition-colors">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                    </a>
                    <a href="https://www.instagram.com/bu_madya" class="w-10 h-10 rounded-xl bg-gray-900 border border-gray-800 flex items-center justify-center hover:bg-pink-600 hover:border-pink-500 hover:text-white text-gray-400 transition-colors">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                    </a>
                    <a href="https://www.x.com/bu_madya" class="w-10 h-10 rounded-xl bg-gray-900 border border-gray-800 flex items-center justify-center hover:bg-white hover:border-white hover:text-black text-gray-400 transition-colors">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                    </a>
                </div>
            </div>
            
            <ul class="space-y-4 font-bold text-sm">
                <li><a href="{{ route('about') }}" class="text-gray-400 hover:text-white hover:translate-x-1 transition inline-block">About BU MADYA</a></li>
                <li><a href="{{ route('open.directory') }}" class="text-gray-400 hover:text-white hover:translate-x-1 transition inline-block">Our Officers</a></li>
                <li><a href="{{ route('transparency.index') }}" class="text-gray-400 hover:text-white hover:translate-x-1 transition inline-block">Transparency Board</a></li>
                <li class="pt-4 mt-4 border-t border-gray-800">
                    <a href="{{ route('privacy') }}" class="text-[10px] uppercase tracking-widest text-gray-500 hover:text-white transition inline-block">Privacy Policy</a>
                </li>
            </ul>

            <div>
                <h4 class="font-black text-sm mb-4 text-green-500 uppercase tracking-widest">Live Platform Stats</h4>
                <div class="bg-gray-900 p-6 rounded-2xl border border-gray-800 shadow-inner">
                    <span class="block text-[10px] font-bold uppercase tracking-widest text-gray-500 mb-2">Total Visitors</span>
                    <div class="text-3xl font-mono font-black text-yellow-400 tracking-widest">
                        {{ str_pad($visitorCount ?? 0, 7, '0', STR_PAD_LEFT) }}
                    </div>
                </div>
            </div>
        </div>
        <div class="border-t border-gray-900 pt-8 text-center text-gray-600 font-bold text-[10px] uppercase tracking-widest">
            &copy; {{ date('Y') }} BU MADYA. All Rights Reserved.
        </div>
    </footer>
</div>