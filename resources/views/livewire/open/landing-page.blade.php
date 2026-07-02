<div class="min-h-screen bg-stone-50 font-sans text-gray-900 selection:bg-red-600 selection:text-white overflow-x-hidden">
    
    {{-- ========================================== --}}
    {{-- 1. ANNOUNCEMENTS TICKER / ALERT BAR        --}}
    {{-- ========================================== --}}
    @if(count($announcements ?? []) > 0)
        <div class="w-full flex flex-col relative">
            @foreach($announcements as $announcement)
                <div x-data="{ show: true }" x-show="show" x-transition.opacity 
                     class="{{ $announcement->type->color_theme }} px-4 py-3 shadow-md border-b border-black/10">
                    <div class="max-w-[1800px] w-[95%] mx-auto flex items-start sm:items-center justify-between gap-4">
                        <div class="flex items-start sm:items-center gap-3">
                            <svg class="w-6 h-6 shrink-0 mt-0.5 sm:mt-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                {!! $announcement->type->icon_svg !!}
                            </svg>
                            <div class="text-sm">
                                <span class="font-bold uppercase tracking-wider mr-2">{{ $announcement->title }}:</span>
                                <span class="opacity-90">{{ $announcement->message }}</span>
                            </div>
                        </div>
                        <button @click="show = false" class="shrink-0 p-1 hover:bg-black/20 rounded transition" aria-label="Dismiss">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                        </button>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    {{-- 2. HERO SECTION (Mobile Responsive) --}}
    <header class="relative min-h-[85vh] md:min-h-[85vh] flex items-center bg-gray-900 overflow-hidden shadow-2xl">

        {{-- Image Container (Hidden on Mobile, Spanning Right on Desktop) --}}
        <div class="hidden md:block absolute top-0 right-0 w-1/2 h-full z-0">
            <img src="{{ asset('images/1760712981522.JPG') }}"
                 class="w-full h-full object-cover brightness-110"
                 style="mask-image: linear-gradient(to right, transparent 0%, black 30%); -webkit-mask-image: linear-gradient(to right, transparent 0%, black 30%);">
            <div class="absolute inset-0 bg-gradient-to-r from-gray-900 via-gray-900/60 to-transparent"></div>
        </div>

        {{-- Mobile Image Overlay (Only visible on small screens) --}}
        <div class="md:hidden absolute inset-0 z-0 opacity-30">
            <img src="{{ asset('images/1760712981522.JPG') }}" class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-b from-gray-900/90 via-gray-900/80 to-gray-900"></div>
        </div>

        {{-- Text Content Container --}}
        <div class="relative z-10 container mx-auto px-6 md:px-12">
            <div class="w-full md:w-5/12 text-left space-y-6 md:space-y-8">
                {{-- Stylized Badge (Architectural Look) --}}
                <h1 class="font-heading text-5xl md:text-8xl font-black uppercase tracking-tighter leading-[0.9] text-white">
                    ADVOCACY <br>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-yellow-300 via-orange-400 to-red-500">
                        IN ACTION
                    </span>
                </h1>

                <p class="text-lg md:text-xl text-gray-400 font-light leading-relaxed max-w-lg">
                    We are Bicol University’s dedicated force for social change. Bridging student voices with community action to foster a sustainable and empowered future.
                </p>

                <div class="flex flex-wrap gap-4 pt-2 md:pt-4">
                    <a href="{{ route('membership-form') }}" class="w-full md:w-auto px-8 py-4 bg-red-600 text-white font-black hover:bg-red-700 transition-all shadow-[0_10px_30px_-10px_rgba(220,38,38,0.5)] uppercase tracking-widest text-center text-sm">
                        JOIN THE MOVEMENT
                    </a>
                </div>
            </div>
        </div>

        {{-- Tricolor Stroke at the Bottom --}}
        <div class="absolute bottom-0 left-0 w-full h-2 flex z-20">
            <div class="w-1/3 bg-green-600"></div>
            <div class="w-1/3 bg-yellow-400"></div>
            <div class="w-1/3 bg-red-600"></div>
        </div>
    </header>

    {{-- MAIN CONTENT --}}
    <main class="max-w-[1800px] w-[95%] mx-auto px-4 relative z-20 pb-24">

        {{-- ========================================== --}}
        {{-- 3. SPOTLIGHT LANDSCAPE AREA (CAROUSEL)     --}}
        {{-- ========================================== --}}
        @if(count($spotlights ?? []) > 0)
            <section class="mt-12 mb-8 w-full">
                <div class="flex items-center justify-between mb-6">
                    <div class="pl-4 border-l-4 border-purple-600">
                        <h2 class="font-heading text-3xl md:text-4xl font-black text-gray-900 tracking-tight">Spotlight</h2>
                        <p class="text-gray-500 font-medium mt-1 uppercase tracking-widest text-[10px]">Featured Promotions & Greetings</p>
                    </div>
                </div>

                <div x-data="{ 
                            activeSlide: 0, 
                            totalSlides: {{ $spotlights->count() }},
                            next() { this.activeSlide = this.activeSlide === this.totalSlides - 1 ? 0 : this.activeSlide + 1 },
                            prev() { this.activeSlide = this.activeSlide === 0 ? this.totalSlides - 1 : this.activeSlide - 1 }
                        }" 
                        x-init="if (totalSlides > 1) setInterval(() => next(), 6000)"
                        class="relative w-full aspect-[21/9] md:aspect-[4/1] bg-gray-900 rounded-[2rem] overflow-hidden shadow-sm border border-gray-100 group">
                    
                    <div class="relative w-full h-full flex transition-transform duration-700 ease-in-out" 
                         :style="'transform: translateX(-' + (activeSlide * 100) + '%)'">
                        
                        @foreach($spotlights as $spotlight)
                            <div class="w-full h-full shrink-0 relative">
                                <img src="{{ Storage::url($spotlight->image_path) }}" alt="{{ $spotlight->title }}" class="absolute inset-0 w-full h-full object-cover opacity-60 mix-blend-overlay">
                                <div class="absolute inset-0 bg-gradient-to-t from-gray-900 via-gray-900/40 to-transparent"></div>
                                
                                <div class="absolute inset-0 flex flex-col justify-end p-8 md:p-16">
                                    <span class="inline-block px-3 py-1 bg-purple-600 text-white text-[10px] font-black uppercase tracking-widest rounded-full w-max mb-4 shadow-md">
                                        {{ $spotlight->category->name }}
                                    </span>
                                    <h3 class="font-heading text-3xl md:text-5xl font-black text-white leading-tight mb-4 max-w-4xl drop-shadow-lg">
                                        {{ $spotlight->title }}
                                    </h3>
                                    @if($spotlight->link && $spotlight->link !== '#')
                                        <a href="{{ $spotlight->link }}" target="_blank" class="inline-flex items-center gap-2 text-[10px] text-white font-black hover:text-purple-400 uppercase tracking-widest transition w-max group/link">
                                            Find out more 
                                            <svg class="w-4 h-4 group-hover/link:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3" /></svg>
                                        </a>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <button @click="prev()" class="absolute left-6 top-1/2 -translate-y-1/2 w-12 h-12 bg-white/20 hover:bg-purple-600 text-white rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition backdrop-blur-sm shadow-lg">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" /></svg>
                    </button>
                    <button @click="next()" class="absolute right-6 top-1/2 -translate-y-1/2 w-12 h-12 bg-white/20 hover:bg-purple-600 text-white rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition backdrop-blur-sm shadow-lg">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" /></svg>
                    </button>

                    <div class="absolute bottom-6 left-1/2 -translate-x-1/2 flex gap-2">
                        <template x-for="i in totalSlides" :key="i">
                            <button @click="activeSlide = i - 1" 
                                    :class="{'w-6 bg-purple-600': activeSlide === i - 1, 'w-2 bg-white/50 hover:bg-white': activeSlide !== i - 1}"
                                    class="h-2 rounded-full transition-all duration-300"></button>
                        </template>
                    </div>
                </div>
            </section>
        @endif

        <div class="grid lg:grid-cols-12 gap-12 items-start pt-12">

            {{-- LEFT COLUMN (Main Content) --}}
            <div class="lg:col-span-8">

                {{-- PILLARS SECTION --}}
                <section id="pillars" class="mb-32 md:mb-48"
                    x-data="{
                        activePillar: null,
                        pillars: [
                            { id: 1, title: 'Culture & Heritage', icon: 'M12 21a9.004 9.004 0 008.716-6.747M12 21a9.004 9.004 0 01-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 017.843 4.582M12 3a8.997 8.997 0 00-7.843 4.582m15.686 0A11.953 11.953 0 0112 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0121 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0112 16.5c-3.162 0-6.133-.815-8.716-2.247m0 0A9.015 9.015 0 013 12c0-1.605.42-3.113 1.157-4.418', color: 'text-amber-600', bg: 'bg-amber-50', border: 'border-amber-100', desc: 'Preserving our roots by documenting local history and promoting Bicolano arts and traditions.' },
                            { id: 2, title: 'Social Sciences', icon: 'M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z', color: 'text-blue-600', bg: 'bg-blue-50', border: 'border-blue-100', desc: 'Fostering critical thinking through debates, forums, and social research.' },
                            { id: 3, title: 'Quality Education', icon: 'M4.26 10.147a60.436 60.436 0 00-.491 6.347A48.627 48.627 0 0112 20.904a48.627 48.627 0 018.232-4.41 60.46 60.46 0 00-.491-6.347m-15.482 0a50.57 50.57 0 00-2.658-.813A59.905 59.905 0 0112 3.493a59.902 59.902 0 0110.499 5.221 69.17 69.17 0 00-2.692 1.296M3.191 14.463l.73 1.053a4.5 4.5 0 01.73 2.518V20.89M12 15.63a6.002 6.002 0 01-6-6.002 6.002 6.002 0 0112 0 6.002 6.002 0 01-6 6.002z', color: 'text-red-600', bg: 'bg-red-50', border: 'border-red-100', desc: 'Ensuring accessible learning resources and peer-tutoring programs to leave no student behind.' },
                            { id: 4, title: 'Science & Technology', icon: 'M8.25 3v1.5M4.5 8.25H3m18 0h-1.5M4.5 12H3m18 0h-1.5m-15 3.75H3m18 0h-1.5M8.25 19.5V21M12 3v1.5m0 15V21m3.75-18v1.5m0 15V21m-9-1.5h10.5a2.25 2.25 0 002.25-2.25V6.75a2.25 2.25 0 00-2.25-2.25H6.75A2.25 2.25 0 004.5 6.75v10.5a2.25 2.25 0 002.25 2.25z', color: 'text-emerald-600', bg: 'bg-emerald-50', border: 'border-emerald-100', desc: 'Innovating for the future by supporting student-led research and tech solutions.' },
                            { id: 5, title: 'Digital Strategies', icon: 'M8.288 15.038a5.25 5.25 0 017.424 0M5.106 11.856c3.807-3.808 9.98-3.808 13.788 0M1.924 8.674c5.565-5.565 14.587-5.565 20.152 0M12.53 18.22l-.53.53-.53-.53a.75.75 0 011.06 0z', color: 'text-purple-600', bg: 'bg-purple-50', border: 'border-purple-100', desc: 'Leveraging modern media to amplify our advocacy reach and combat misinformation.' }
                        ]
                    }">

                    <div class="mb-8 pl-4 border-l-4 border-green-600">
                        <h2 class="font-heading text-3xl md:text-4xl font-black text-gray-900 tracking-tight">Our Core Pillars</h2>
                        <p class="text-gray-500 font-medium mt-1 uppercase tracking-widest text-[10px]">The 5 Foundations of our Advocacy</p>
                    </div>

                    {{-- IMPROVED CARD GRID --}}
                    <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-5 gap-4">
                        <template x-for="pillar in pillars" :key="pillar.id">
                            <button
                                @click="activePillar = (activePillar === pillar.id ? null : pillar.id)"
                                class="bg-white rounded-2xl p-4 md:p-5 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 group text-left border h-36 md:h-44 flex flex-col justify-between"
                                :class="activePillar === pillar.id ? 'ring-2 ring-offset-2 ring-gray-200 ' + pillar.border : 'border-gray-100'"
                            >
                                <div class="w-10 h-10 md:w-12 md:h-12 rounded-xl flex items-center justify-center transition-transform group-hover:scale-110 shrink-0"
                                     :class="pillar.bg + ' ' + pillar.color">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 md:w-6 md:h-6">
                                        <path stroke-linecap="round" stroke-linejoin="round" :d="pillar.icon" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="font-bold text-gray-800 leading-tight text-xs md:text-sm group-hover:text-black transition-colors" x-text="pillar.title"></h3>
                                </div>
                            </button>
                        </template>
                    </div>

                    {{-- Expanded Details Panel --}}
                    <div x-show="activePillar !== null" x-collapse class="mt-4">
                        <template x-for="pillar in pillars" :key="pillar.id">
                            <div x-show="activePillar === pillar.id"
                                 class="bg-white rounded-[2rem] p-6 md:p-8 shadow-lg border border-gray-100 relative overflow-hidden"
                                 x-transition:enter="transition ease-out duration-300"
                                 x-transition:enter-start="opacity-0 -translate-y-2"
                                 x-transition:enter-end="opacity-100 translate-y-0">
                                <div class="relative z-10 flex flex-col md:flex-row gap-6 items-start">
                                    <div class="hidden md:flex w-14 h-14 rounded-full items-center justify-center shrink-0" :class="pillar.bg + ' ' + pillar.color">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-7 h-7">
                                            <path stroke-linecap="round" stroke-linejoin="round" :d="pillar.icon" />
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-2xl font-bold text-gray-900 mb-2" x-text="pillar.title"></h3>
                                        <p class="text-gray-600 text-base md:text-lg leading-relaxed" x-text="pillar.desc"></p>
                                        <div class="mt-6">
                                            <a href="{{ route('projects.index') }}" class="inline-flex items-center text-xs font-black uppercase tracking-widest transition-colors" :class="pillar.color">
                                                View Related Projects <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                </section>

                {{-- FEATURED PROJECTS CAROUSEL --}}
                <section class="mb-8 md:mb-48"
                    x-data="{
                        activeSlide: 0,
                        slidesCount: {{ collect($featuredProjects ?? [])->count() ?: 0 }},
                        next() { this.activeSlide = this.activeSlide === this.slidesCount - 1 ? 0 : this.activeSlide + 1 },
                        prev() { this.activeSlide = this.activeSlide === 0 ? this.slidesCount - 1 : this.activeSlide - 1 }
                    }">

                    <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 mb-8">
                        <div class="pl-4 border-l-4 border-yellow-400">
                            <h2 class="font-heading text-3xl md:text-4xl font-black text-gray-900 tracking-tight">Featured Projects</h2>
                            <p class="text-gray-500 font-medium mt-1 uppercase tracking-widest text-[10px]">Our Proudest Initiatives</p>
                        </div>

                        {{-- Carousel Controls --}}
                        <div class="flex gap-2" x-show="slidesCount > 1">
                            <button @click="prev()" class="w-10 h-10 rounded-full bg-white border border-gray-200 flex items-center justify-center text-gray-600 hover:bg-green-500 hover:text-white hover:border-green-500 transition-all shadow-sm active:scale-95">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"></path></svg>
                            </button>
                            <button @click="next()" class="w-10 h-10 rounded-full bg-white border border-gray-200 flex items-center justify-center text-gray-600 hover:bg-red-600 hover:text-white hover:border-red-600 transition-all shadow-sm active:scale-95">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"></path></svg>
                            </button>
                        </div>
                    </div>

                    {{-- Carousel Track --}}
                    <div class="relative overflow-hidden rounded-[2rem] bg-white shadow-sm border border-gray-100 h-[500px] md:h-[400px]">
                        @forelse($featuredProjects ?? [] as $index => $project)
                            <div x-show="activeSlide === {{ $index }}"
                                 x-transition:enter="transition ease-out duration-500"
                                 x-transition:enter-start="opacity-0 translate-x-8"
                                 x-transition:enter-end="opacity-100 translate-x-0"
                                 x-transition:leave="transition ease-in duration-300 absolute inset-0"
                                 x-transition:leave-start="opacity-100 translate-x-0"
                                 x-transition:leave-end="opacity-0 -translate-x-8"
                                 class="w-full h-full flex flex-col md:flex-row absolute inset-0">

                                {{-- Image Side --}}
                                <div class="w-full md:w-1/2 h-48 md:h-full relative overflow-hidden bg-gray-100 shrink-0">
                                    @if($project->cover_img)
                                        <img src="{{ asset('storage/' . $project->cover_img) }}" alt="{{ $project->title }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-gray-50 to-gray-200 text-gray-400">
                                            <svg class="w-16 h-16 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        </div>
                                    @endif
                                </div>

                                {{-- Content Side --}}
                                <div class="w-full md:w-1/2 p-6 md:p-10 flex flex-col justify-center bg-white relative flex-1">
                                    <div class="absolute top-0 right-0 w-32 h-32 bg-yellow-400/10 rounded-full blur-2xl -mr-10 -mt-10"></div>

                                    <div class="inline-flex items-center gap-2 mb-4">
                                        <span class="w-2 h-2 rounded-full bg-red-600 animate-pulse"></span>
                                        <span class="text-[10px] font-black uppercase tracking-widest text-gray-400">Completed Initiative</span>
                                    </div>

                                    <h3 class="font-heading text-2xl md:text-3xl font-black text-gray-900 mb-4 leading-tight group-hover:text-red-600 transition-colors">
                                        {{ $project->title }}
                                    </h3>

                                    <p class="text-gray-500 text-sm leading-relaxed mb-8 line-clamp-3 md:line-clamp-4">
                                        {{ Str::limit(strip_tags($project->description), 150) }}
                                    </p>

                                    <div class="mt-auto">
                                        <a href="{{ route('projects.show', $project->slug) }}" class="inline-flex items-center gap-2 text-[10px] font-black text-gray-900 hover:text-green-600 uppercase tracking-widest transition-colors group/link">
                                            View Project
                                            <svg class="w-4 h-4 group-hover/link:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="w-full h-full flex flex-col items-center justify-center text-gray-400 bg-gray-50">
                                <svg class="w-12 h-12 mb-3 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                                <p class="text-[10px] font-black uppercase tracking-widest">No projects currently active.</p>
                            </div>
                        @endforelse
                    </div>

                    {{-- Optional Slider Dots --}}
                    <div class="flex justify-center gap-2 mt-6" x-show="slidesCount > 1">
                        <template x-for="i in slidesCount" :key="i">
                            <button @click="activeSlide = i - 1"
                                    class="w-2 h-2 rounded-full transition-all duration-300"
                                    :class="activeSlide === i - 1 ? 'bg-red-600 w-6' : 'bg-gray-300 hover:bg-yellow-400'"></button>
                        </template>
                    </div>
                </section>


                {{-- FEATURES SECTION --}}
                <section class="bg-white rounded-[2rem] p-8 md:p-12 shadow-sm border border-gray-100 flex flex-col md:flex-row items-center gap-12 overflow-hidden relative mb-8 md:mb-48">
                    <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-5 mix-blend-overlay pointer-events-none"></div>

                    <div class="w-full md:w-1/2 relative z-10">
                        <span class="text-yellow-500 font-black uppercase tracking-widest text-[10px] mb-3 block">Our Mission</span>
                        <h3 class="font-heading text-3xl md:text-4xl font-black text-gray-900 mb-6 tracking-tight">Advocacy for <span class="text-red-600">Everyone.</span></h3>
                        <p class="text-gray-500 mb-8 leading-relaxed text-base md:text-lg">BU MADYA creates space for meaningful engagement and representation. We believe that true change starts when every student feels empowered to speak, act, and lead.</p>
                        <a href="{{ route('about') }}" class="inline-flex items-center justify-center px-6 py-3.5 bg-gray-900 text-white font-bold rounded-xl hover:bg-gray-800 transition-all shadow-md active:scale-95 text-[10px] uppercase tracking-widest">
                            Read our Manifesto
                        </a>
                    </div>
                    <div class="w-full md:w-1/2 relative z-10">
                        <div class="absolute inset-0 bg-gradient-to-tr from-yellow-100 to-red-50 rounded-[2rem] transform rotate-3 scale-105"></div>
                        <img src="https://images.unsplash.com/photo-1529156069898-49953e39b3ac?q=80&w=1932" class="relative rounded-[1.5rem] shadow-lg w-full object-cover aspect-[4/3] transform hover:-rotate-1 transition-transform duration-500">
                    </div>
                </section>


                {{-- UPCOMING EVENTS CAROUSEL --}}
                <section class="mb-32 md:mb-48"
                    x-data="{
                        activeEvent: 0,
                        eventCount: {{ collect($upcomingEvents ?? [])->count() ?: 0 }},
                        nextEvent() { this.activeEvent = this.activeEvent === this.eventCount - 1 ? 0 : this.activeEvent + 1 },
                        prevEvent() { this.activeEvent = this.activeEvent === 0 ? this.eventCount - 1 : this.activeEvent - 1 }
                    }">

                    <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 mb-8">
                        <div class="pl-4 border-l-4 border-red-600">
                            <h2 class="font-heading text-3xl md:text-4xl font-black text-gray-900 tracking-tight">Upcoming Events</h2>
                            <p class="text-gray-500 font-medium mt-1 uppercase tracking-widest text-[10px]">Mark Your Calendars</p>
                        </div>

                        <div class="flex gap-2" x-show="eventCount > 1">
                            <button @click="prevEvent()" class="w-10 h-10 rounded-full bg-white border border-gray-200 flex items-center justify-center text-gray-600 hover:bg-red-600 hover:text-white hover:border-red-600 transition-all shadow-sm active:scale-95">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"></path></svg>
                            </button>
                            <button @click="nextEvent()" class="w-10 h-10 rounded-full bg-white border border-gray-200 flex items-center justify-center text-gray-600 hover:bg-red-600 hover:text-white hover:border-red-600 transition-all shadow-sm active:scale-95">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"></path></svg>
                            </button>
                        </div>
                    </div>

                    <div class="relative overflow-hidden rounded-[2rem] bg-white shadow-sm border border-gray-100 h-[500px] md:h-[400px]">
                        @forelse($upcomingEvents ?? [] as $index => $event)
                            <div x-show="activeEvent === {{ $index }}"
                                 x-transition:enter="transition ease-out duration-500"
                                 x-transition:enter-start="opacity-0 translate-x-8"
                                 x-transition:enter-end="opacity-100 translate-x-0"
                                 x-transition:leave="transition ease-in duration-300 absolute inset-0"
                                 x-transition:leave-start="opacity-100 translate-x-0"
                                 x-transition:leave-end="opacity-0 -translate-x-8"
                                 class="w-full h-full flex flex-col md:flex-row absolute inset-0">

                                <div class="w-full md:w-1/2 h-48 md:h-full relative overflow-hidden bg-gray-900 group shrink-0">
                                    @if($event->cover_image)
                                        <img src="{{ asset('storage/' . $event->cover_image) }}" alt="{{ $event->title }}" class="w-full h-full object-cover opacity-90 group-hover:scale-105 group-hover:opacity-100 transition duration-700">
                                    @else
                                        <div class="w-full h-full flex flex-col items-center justify-center bg-gradient-to-br from-red-900 to-gray-900 text-red-500/50">
                                            <svg class="w-20 h-20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        </div>
                                    @endif

                                    <div class="absolute top-6 left-6 bg-white/95 backdrop-blur-sm rounded-2xl shadow-xl border border-white/20 text-center overflow-hidden flex flex-col w-20">
                                        <div class="bg-red-600 text-white text-[10px] font-black uppercase tracking-widest py-1.5">
                                            {{ $event->start_date ? $event->start_date->format('M') : 'TBA' }}
                                        </div>
                                        <div class="text-3xl font-black text-gray-900 py-2 leading-none">
                                            {{ $event->start_date ? $event->start_date->format('d') : '--' }}
                                        </div>
                                    </div>
                                </div>

                                <div class="w-full md:w-1/2 p-6 md:p-10 flex flex-col justify-center bg-white relative flex-1">
                                    <div class="flex items-center gap-3 mb-4">
                                        @if($event->isOpen())
                                            <span class="px-3 py-1 bg-green-50 text-green-600 text-[10px] font-black uppercase tracking-widest rounded-lg shadow-sm border border-green-100 flex items-center gap-1.5">
                                                <span class="w-1.5 h-1.5 rounded-full bg-green-500 animate-pulse"></span> Registration Open
                                            </span>
                                        @else
                                            <span class="px-3 py-1 bg-gray-50 text-gray-500 text-[10px] font-black uppercase tracking-widest rounded-lg shadow-sm border border-gray-200">
                                                Closed
                                            </span>
                                        @endif
                                    </div>

                                    <h3 class="font-heading text-2xl md:text-3xl font-black text-gray-900 mb-4 leading-tight group-hover:text-red-600 transition-colors">
                                        {{ $event->title }}
                                    </h3>

                                    <p class="text-gray-500 text-sm leading-relaxed mb-8 line-clamp-3 md:line-clamp-4">
                                        {{ Str::limit(strip_tags($event->description), 150) }}
                                    </p>

                                    <div class="mt-auto flex flex-col sm:flex-row gap-4">
                                        <a href="{{ route('events.show', $event->slug ?? $event->id) }}" class="inline-flex items-center justify-center gap-2 px-6 py-3.5 bg-gray-900 text-white font-bold rounded-xl text-[10px] uppercase tracking-widest hover:bg-red-600 transition-all shadow-md hover:-translate-y-0.5 active:scale-95">
                                            Event Details
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="w-full h-full flex flex-col items-center justify-center text-gray-400 bg-gray-50">
                                <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center mb-4 shadow-sm border border-gray-100">
                                    <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                </div>
                                <p class="text-[10px] font-black uppercase tracking-widest text-gray-500">No upcoming events right now.</p>
                            </div>
                        @endforelse
                    </div>

                    <div class="flex justify-center gap-2 mt-6" x-show="eventCount > 1">
                        <template x-for="i in eventCount" :key="i">
                            <button @click="activeEvent = i - 1"
                                    class="w-2 h-2 rounded-full transition-all duration-300"
                                    :class="activeEvent === i - 1 ? 'bg-red-600 w-6' : 'bg-gray-300 hover:bg-red-400'"></button>
                        </template>
                    </div>
                </section>

                {{-- DYNAMIC NEWS SECTION --}}
                <section class="mb-12">
                    <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 mb-8">
                        <div class="pl-4 border-l-4 border-blue-500">
                            <h2 class="font-heading text-3xl md:text-4xl font-black text-gray-900 tracking-tight">Latest Updates</h2>
                            <p class="text-gray-500 font-medium mt-1 uppercase tracking-widest text-[10px]">News & Announcements</p>
                        </div>
                        <a href="{{ route('news.index') }}" class="text-[10px] font-black text-gray-400 hover:text-blue-600 uppercase tracking-widest flex items-center gap-1.5 group transition-colors">
                            View Archive <svg class="w-3.5 h-3.5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                        </a>
                    </div>

                    <div class="grid sm:grid-cols-2 gap-6 md:gap-8">
                    @forelse($latestNews ?? [] as $news)
                        <article class="group bg-white rounded-[2rem] shadow-sm border border-gray-100 overflow-hidden hover:shadow-md hover:-translate-y-1 transition-all duration-300 flex flex-col h-full">

                            <div class="h-56 overflow-hidden relative bg-gray-100 shrink-0">
                                @php
                                    $catName = is_object($news->category) ? $news->category->name : $news->category;
                                    $coverUrl = $news->cover_img ? (Str::startsWith($news->cover_img, ['http', 'https']) ? $news->cover_img : asset('storage/' . $news->cover_img)) : null;
                                @endphp

                                <div class="absolute top-4 left-4 z-10">
                                    <span class="bg-white/95 backdrop-blur-sm text-gray-900 text-[9px] font-black px-3 py-1.5 rounded-lg shadow-sm uppercase tracking-widest">
                                        {{ $catName }}
                                    </span>
                                </div>

                                @if($coverUrl)
                                    <img src="{{ $coverUrl }}" alt="{{ $news->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                                @else
                                    <div class="w-full h-full bg-gray-50 flex items-center justify-center">
                                        <svg class="w-12 h-12 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    </div>
                                @endif
                            </div>

                            <div class="p-6 md:p-8 flex flex-col flex-1">
                                <div class="flex items-center gap-2 text-[10px] text-gray-400 font-bold uppercase tracking-widest mb-3">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    {{ $news->published_at ? $news->published_at->format('M d, Y') : 'TBA' }}
                                </div>

                                <h3 class="font-black text-xl text-gray-900 mb-3 leading-tight group-hover:text-blue-600 transition-colors">
                                    <a href="{{ route('news.show', $news->slug) }}">{{ $news->title }}</a>
                                </h3>

                                <p class="text-gray-500 text-sm line-clamp-3 mb-6 flex-1 leading-relaxed">
                                    {{ $news->summary ?? Str::limit(strip_tags($news->content), 100) }}
                                </p>

                                <div class="pt-5 border-t border-gray-100 flex items-center justify-between mt-auto">
                                    <a href="{{ route('news.show', $news->slug) }}" class="text-[10px] font-black text-gray-900 hover:text-blue-600 uppercase tracking-widest flex items-center gap-1.5 group/link transition-colors">
                                        Read Article <svg class="w-3.5 h-3.5 group-hover/link:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                                    </a>
                                </div>
                            </div>
                        </article>
                    @empty
                        <div class="col-span-1 sm:col-span-2 text-center py-12 bg-white rounded-[2rem] border border-dashed border-gray-200">
                            <p class="text-[10px] font-black uppercase tracking-widest text-gray-400">No updates posted yet.</p>
                        </div>
                    @endforelse
                    </div>
                </section>
            </div>

            {{-- RIGHT COLUMN (Sidebar) --}}
            <aside class="lg:col-span-4 space-y-8 lg:sticky lg:top-24">

                {{-- Membership Card --}}
                <div class="bg-gray-900 rounded-[2rem] p-8 text-white relative overflow-hidden shadow-2xl group">
                    {{-- Decorative Blob --}}
                    <div class="absolute top-0 right-0 w-64 h-64 bg-red-600/20 rounded-full blur-3xl -mr-16 -mt-16 group-hover:bg-red-600/30 transition duration-700"></div>

                    <div class="relative z-10">
                        <div class="w-12 h-12 bg-white/10 rounded-xl flex items-center justify-center mb-6 backdrop-blur-sm border border-white/10">
                            <svg class="w-6 h-6 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        </div>
                        <h3 class="font-heading text-2xl font-bold mb-2">Be One of Us</h3>
                        <p class="text-gray-400 text-sm mb-8 leading-relaxed">Join a network of passionate youth leaders. Get exclusive access to workshops, events, and mentorship.</p>
                        <a href="{{ route('membership-form') }}" class="block w-full py-3.5 bg-red-600 text-white font-bold rounded-xl hover:bg-red-700 transition shadow-lg text-center text-[10px] uppercase tracking-widest">
                            Apply for Membership
                        </a>
                    </div>
                </div>

                {{-- DYNAMIC CALENDAR WIDGET --}}
                <div class="bg-white rounded-2xl md:rounded-[2rem] p-5 md:p-8 shadow-sm border border-gray-100"
                     x-data="{
                        currentMonth: new Date().getMonth(),
                        currentYear: new Date().getFullYear(),
                        monthNames: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
                        activities: @js($calendarData ?? []),
                        activeDate: null,

                        get daysInMonth() { return new Date(this.currentYear, this.currentMonth + 1, 0).getDate(); },
                        get blankDays() { return new Date(this.currentYear, this.currentMonth, 1).getDay(); },

                        get daysArray() { return Array.from({length: this.daysInMonth}, (_, i) => i + 1); },
                        get blanksArray() { return Array.from({length: this.blankDays}, (_, i) => i); },

                        formatDate(day) {
                            return this.currentYear + '-' + String(this.currentMonth + 1).padStart(2, '0') + '-' + String(day).padStart(2, '0');
                        },
                        hasActivity(day) {
                            return this.activities[this.formatDate(day)] !== undefined;
                        },

                        // New Navigation Logic
                        prevMonth() {
                            if (this.currentMonth === 0) {
                                this.currentMonth = 11;
                                this.currentYear--;
                            } else {
                                this.currentMonth--;
                            }
                            this.activeDate = null; // Close agenda when changing months
                        },
                        nextMonth() {
                            if (this.currentMonth === 11) {
                                this.currentMonth = 0;
                                this.currentYear++;
                            } else {
                                this.currentMonth++;
                            }
                            this.activeDate = null;
                        }
                     }">

                    {{-- Calendar Header --}}
                    <div class="flex items-center justify-between mb-4 md:mb-5">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-red-50 text-red-600 flex items-center justify-center shrink-0 border border-red-100">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            </div>
                            <div>
                                <h3 class="font-heading font-black text-gray-900 text-lg md:text-xl tracking-tight leading-none mb-1" x-text="monthNames[currentMonth]"></h3>
                                <p class="text-[9px] md:text-[10px] font-bold text-gray-400 uppercase tracking-widest leading-none" x-text="currentYear"></p>
                            </div>
                        </div>

                        {{-- Navigation Arrows --}}
                        <div class="flex items-center gap-1 bg-gray-50 rounded-lg border border-gray-200 p-0.5">
                            <button @click="prevMonth()" class="p-1.5 md:p-2 text-gray-400 hover:text-red-600 hover:bg-white rounded-md transition-colors focus:outline-none shadow-sm border border-transparent hover:border-gray-100">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M15 19l-7-7 7-7"></path></svg>
                            </button>
                            <div class="w-[1px] h-4 bg-gray-200"></div>
                            <button @click="nextMonth()" class="p-1.5 md:p-2 text-gray-400 hover:text-red-600 hover:bg-white rounded-md transition-colors focus:outline-none shadow-sm border border-transparent hover:border-gray-100">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7"></path></svg>
                            </button>
                        </div>
                    </div>

                    {{-- Days of Week (Bulletproof Grid) --}}
                    <div style="display: grid; grid-template-columns: repeat(7, 1fr); gap: 4px;" class="text-center mb-2 mt-4">
                        <template x-for="day in ['Su','Mo','Tu','We','Th','Fr','Sa']" :key="day">
                            <div class="text-[8px] md:text-[9px] font-bold text-gray-400 uppercase tracking-widest py-1" x-text="day"></div>
                        </template>
                    </div>

                    {{-- Calendar Grid (Bulletproof Grid & Fixed Heights) --}}
                    <div style="display: grid; grid-template-columns: repeat(7, 1fr); gap: 4px;">
                        <template x-for="i in blanksArray" :key="'blank-'+i">
                            <div></div>
                        </template>
                        <template x-for="day in daysArray" :key="'day-'+day">
                            <div class="relative w-full">
                                <button @click="hasActivity(day) ? (activeDate === formatDate(day) ? activeDate = null : activeDate = formatDate(day)) : null"
                                        class="w-full h-8 md:h-10 flex flex-col items-center justify-center rounded-lg transition-all focus:outline-none"
                                        :class="{
                                            'hover:bg-gray-50 text-gray-500 cursor-default': !hasActivity(day),
                                            'bg-red-50 text-red-700 font-black border border-red-100 hover:bg-red-100 shadow-sm cursor-pointer': hasActivity(day) && activeDate !== formatDate(day),
                                            'bg-red-600 text-white font-black shadow-md cursor-pointer': activeDate === formatDate(day),
                                        }">
                                    <span x-text="day" class="text-[10px] md:text-xs z-10"></span>
                                    <span x-show="hasActivity(day) && activeDate !== formatDate(day)" class="absolute bottom-1 w-1 h-1 md:w-1.5 md:h-1.5 bg-red-500 rounded-full"></span>
                                </button>
                            </div>
                        </template>
                    </div>

                    {{-- Tooltip / Activity List Panel --}}
                    <div x-show="activeDate && activities[activeDate]" x-collapse class="mt-4 md:mt-5 pt-4 md:pt-5 border-t border-gray-100">
                        <div class="flex items-center justify-between mb-3 md:mb-4">
                            <span class="text-[9px] font-bold text-gray-500 uppercase tracking-widest" x-text="'Agenda for ' + monthNames[currentMonth] + ' ' + parseInt(activeDate.split('-')[2])"></span>
                            <button @click="activeDate = null" class="text-gray-400 hover:text-red-600 transition-colors p-1 bg-gray-50 hover:bg-red-50 rounded-full">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                        </div>
                        <div class="space-y-2.5">
                            <template x-for="(activity, index) in activities[activeDate]" :key="index">
                                <a :href="activity.url" class="block p-3 md:p-4 rounded-xl border border-gray-100 hover:border-red-200 bg-gray-50 hover:bg-white hover:shadow-sm transition-all group/link">
                                    <div class="flex items-center gap-2 mb-2">
                                        <span class="w-1.5 h-1.5 rounded-full" :class="activity.type === 'Event' ? 'bg-red-500' : 'bg-yellow-400'"></span>
                                        <span class="text-[8px] md:text-[9px] font-black uppercase tracking-widest text-gray-500 group-hover/link:text-gray-900 transition-colors" x-text="activity.type"></span>
                                    </div>
                                    <h4 class="font-bold text-xs md:text-sm text-gray-900 group-hover/link:text-red-600 transition-colors leading-tight line-clamp-2" x-text="activity.title"></h4>
                                </a>
                            </template>
                        </div>
                    </div>
                </div>

                {{-- Gallery Widget --}}
                <div x-data="{ activeSlide: 0, slides: [{ img: 'https://images.unsplash.com/photo-1517486808906-6ca8b3f04846?q=80&w=1949', caption: 'Tree Planting' }, { img: 'https://images.unsplash.com/photo-1523580494863-6f3031224c94?q=80&w=2070', caption: 'Student Leadership' }, { img: 'https://images.unsplash.com/photo-1517245386807-bb43f82c33c4?q=80&w=2070', caption: 'General Assembly' }] }"
                     class="bg-white rounded-[2rem] p-2 shadow-lg border border-gray-100">
                    <div class="relative rounded-[1.5rem] overflow-hidden aspect-square">
                        <template x-for="(slide, index) in slides" :key="index">
                            <div x-show="activeSlide === index" x-transition:enter="transition ease-out duration-500" x-transition:enter-start="opacity-0 scale-105" x-transition:enter-end="opacity-100 scale-100" class="absolute inset-0">
                                <img :src="slide.img" class="w-full h-full object-cover">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent"></div>
                                <div class="absolute bottom-6 left-6 right-6">
                                    <span class="text-[10px] font-bold text-yellow-400 uppercase tracking-wider mb-1 block">Gallery</span>
                                    <p x-text="slide.caption" class="text-white font-bold text-lg md:text-xl leading-tight"></p>
                                </div>
                            </div>
                        </template>

                        {{-- Controls --}}
                        <div class="absolute bottom-6 right-6 flex gap-2">
                            <button @click="activeSlide = activeSlide === 0 ? slides.length - 1 : activeSlide - 1" class="w-8 h-8 bg-white/20 backdrop-blur rounded-full flex items-center justify-center text-white hover:bg-white hover:text-black transition"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"></path></svg></button>
                            <button @click="activeSlide = activeSlide === slides.length - 1 ? 0 : activeSlide + 1" class="w-8 h-8 bg-white/20 backdrop-blur rounded-full flex items-center justify-center text-white hover:bg-white hover:text-black transition"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"></path></svg></button>
                        </div>
                    </div>
                </div>

            </aside>
        </div>
    </main>

    {{-- FOOTER (Keep as livewire component if already swapped, otherwise use this static version) --}}
    <footer class="bg-gray-900 text-white pt-20 pb-10 border-t-8 border-red-600 relative z-20">
        <div class="max-w-[1800px] w-[95%] mx-auto px-6 grid md:grid-cols-4 gap-12 mb-16">

            <div class="col-span-1 md:col-span-2">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 bg-red-600 text-white rounded-full flex items-center justify-center shadow-[0_0_15px_rgba(220,38,38,0.5)]">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.879 16.121A3 3 0 1012.015 11L11 14H9c0 .768.293 1.536.879 2.121z"></path></svg>
                    </div>
                    <span class="font-heading font-bold text-2xl tracking-tight">BU MADYA</span>
                </div>
                <p class="text-gray-400 leading-relaxed max-w-sm mb-6 text-sm">
                    The Bicol University - Movement for the Advancement of Youth-led Advocacy is a duly-accredited University Based Organization in Bicol University committed to service and reaching communities through advocacy.
                </p>

                {{-- Social Media Links --}}
                <div class="flex space-x-4">
                    <a href="https://www.facebook.com/BUMadya" class="w-10 h-10 rounded-full bg-gray-800 flex items-center justify-center hover:bg-blue-600 hover:text-white text-gray-400 transition">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                    </a>
                    <a href="https://www.instagram.com/bu_madya" class="w-10 h-10 rounded-full bg-gray-800 flex items-center justify-center hover:bg-pink-600 hover:text-white text-gray-400 transition">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                    </a>
                    <a href="https://www.x.com/bu_madya" class="w-10 h-10 rounded-full bg-gray-800 flex items-center justify-center hover:bg-black hover:text-white text-gray-400 transition">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                    </a>
                </div>
            </div>

            <ul class="space-y-3 text-gray-400 text-sm">
                <li><a href="{{ route('about') }}" class="hover:text-white hover:translate-x-1 transition inline-block">About BU MADYA</a></li>
                <li><a href="{{ route('open.directory') }}" class="hover:text-white hover:translate-x-1 transition inline-block">Our Officers</a></li>
                <li><a href="{{ route('transparency.index') }}" class="hover:text-white hover:translate-x-1 transition inline-block">Transparency Board</a></li>
                <li class="pt-2 mt-2 border-t border-gray-800">
                    <a href="{{ route('privacy') }}" class="text-xs text-gray-500 hover:text-white hover:translate-x-1 transition inline-block">Privacy Policy</a>
                </li>
            </ul>

            <div>
                <h4 class="font-bold text-lg mb-6 text-green-500 uppercase tracking-widest text-xs">Live Stats</h4>
                <div class="bg-gray-800 p-6 rounded-2xl border border-gray-700 shadow-inner">
                    <span class="block text-[10px] uppercase tracking-widest text-gray-500 mb-2">Total Visitors</span>
                    <div class="text-4xl font-mono text-yellow-400 tracking-widest">
                        {{ str_pad($visitorCount ?? 0, 7, '0', STR_PAD_LEFT) }}
                    </div>
                </div>
            </div>
        </div>
        <div class="border-t border-gray-800 pt-8 text-center text-gray-600 text-xs uppercase tracking-widest">
            &copy; {{ date('Y') }} BU MADYA. All Rights Reserved.
        </div>
    </footer>
</div>