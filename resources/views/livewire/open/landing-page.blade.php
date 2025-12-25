<div class="text-gray-800 bg-gray-50 overflow-x-hidden selection:bg-red-500 selection:text-white">

    <nav 
        x-data="{ scrolled: false }" 
        @scroll.window="scrolled = (window.pageYOffset > 20)"
        :class="scrolled ? 'bg-white/95 backdrop-blur-md shadow-lg py-4' : 'bg-transparent py-6'"
        class="fixed top-0 w-full z-50 transition-all duration-300"
    >
        <div class="max-w-[1800px] w-[95%] mx-auto px-6 flex justify-between items-center">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center shadow-lg overflow-hidden p-1">
                   <img src="{{ asset('images/official_logo.png') }}" alt="Logo" class="w-full h-full object-contain">
                </div>
                
                <span :class="scrolled ? 'text-gray-800' : 'text-white'" class="font-heading font-bold text-lg tracking-tight transition-colors duration-300">BU MADYA</span>
            </div>

            <div class="hidden md:flex items-center space-x-8 text-sm font-semibold tracking-wide">
                @foreach(['Home', 'Engagement', 'Advocacy', 'Directory'] as $link)
                <a href="#" 
                   :class="scrolled ? 'text-gray-600 hover:text-red-600' : 'text-green-50 hover:text-yellow-300'"
                   class="transition-colors duration-300 relative group">
                    {{ $link }}
                    <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-red-500 transition-all group-hover:w-full"></span>
                </a>
                @endforeach
                
                <a href="#" class="px-5 py-2 rounded-full font-bold text-sm transition transform hover:scale-105 shadow-lg"
                   :class="scrolled ? 'bg-red-600 text-white hover:bg-red-700' : 'bg-red-600 text-white hover:bg-red-500 border border-white/20'">
                    Contact Us
                </a>
            </div>
        </div>
    </nav>

    <header class="relative min-h-[850px] flex items-center justify-center text-white overflow-hidden rounded-b-[60px] shadow-2xl">
    
        <div class="absolute inset-0 z-0">
            <img src="https://images.unsplash.com/photo-1541339907198-e08756dedf3f?q=80&w=2070&auto=format&fit=crop" 
                class="w-full h-full object-cover" alt="University Background">
            <div class="absolute inset-0 bg-gradient-to-b from-green-900/80 via-green-800/50 to-gray-900/90 mix-blend-multiply"></div>
        </div>

        <div 
            x-data="{ show: false }" 
            x-init="setTimeout(() => show = true, 100)"
            class="relative z-10 container mx-auto px-6 text-center mt-20 pb-64" 
        >
            <div x-show="show" 
                class="w-24 h-24 md:w-32 md:h-32 mx-auto mb-8 bg-white/10 backdrop-blur-md rounded-full flex items-center justify-center border border-white/20 shadow-2xl p-4 animate-fade-in-down">
                <img src="{{ asset('images/official_logo.png') }}" alt="Logo" class="w-full h-full object-contain drop-shadow-lg">
            </div>

            <div>
                <h2 class="text-yellow-300 font-bold tracking-[0.3em] text-sm md:text-base mb-4 uppercase drop-shadow-md">
                    Bicol University
                </h2>
                
                <h1 class="font-heading text-4xl md:text-6xl lg:text-7xl font-black uppercase tracking-tight leading-none mb-6 drop-shadow-2xl text-white">
                    Movement for the <br> 
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-yellow-300 to-orange-400 drop-shadow-sm filter">
                        Advancement of Youth-led Advocacy
                    </span>
                </h1>
                
                <p class="text-lg md:text-xl text-green-50 max-w-2xl mx-auto mb-10 font-light drop-shadow-md bg-black/20 backdrop-blur-sm py-2 rounded-xl">
                    Empowering youth-led advocacy and fostering sustainable development through active dialogue.
                </p>

                <div class="flex flex-col md:flex-row gap-4 justify-center">
                    <button class="px-8 py-4 bg-yellow-400 text-green-900 font-bold rounded-full shadow-[0_0_20px_rgba(250,204,21,0.5)] hover:bg-yellow-300 hover:scale-105 transition transform">
                        Join the Movement
                    </button>
                    <button class="px-8 py-4 bg-white/10 backdrop-blur-md border border-white/30 text-white font-bold rounded-full hover:bg-white/20 transition">
                        Learn More
                    </button>
                </div>
            </div>
        </div>
  </header>

    <main class="max-w-[1800px] w-[95%] mx-auto px-4 -mt-20 relative z-20 pb-24">
        
        <div class="grid lg:grid-cols-12 gap-8 items-start">
            
            <div class="lg:col-span-8 space-y-12">    

                <section 
                    x-data="{ 
                        activePillar: null,
                        pillars: [
                            { 
                                id: 1, 
                                title: 'Culture & Heritage', 
                                /* Icon: Library/Building (Classic Pillar Look) */
                                path: 'M12 21a9.004 9.004 0 008.716-6.747M12 21a9.004 9.004 0 01-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 017.843 4.582M12 3a8.997 8.997 0 00-7.843 4.582m15.686 0A11.953 11.953 0 0112 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0121 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0112 16.5c-3.162 0-6.133-.815-8.716-2.247m0 0A9.015 9.015 0 013 12c0-1.605.42-3.113 1.157-4.418',
                                color: 'bg-[#d97706]', 
                                text: 'text-amber-100', 
                                desc: 'Preserving our roots by documenting local history and promoting Bicolano arts and traditions through university-wide exhibits.' 
                            },
                            { 
                                id: 2, 
                                title: 'Social Sciences', 
                                /* Icon: Users/Community Group */
                                path: 'M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z',
                                color: 'bg-[#2563eb]', 
                                text: 'text-blue-100', 
                                desc: 'Fostering critical thinking through debates, forums, and social research that address community issues.' 
                            },
                            { 
                                id: 3, 
                                title: 'Quality Education', 
                                /* Icon: Academic Cap */
                                path: 'M4.26 10.147a60.436 60.436 0 00-.491 6.347A48.627 48.627 0 0112 20.904a48.627 48.627 0 018.232-4.41 60.46 60.46 0 00-.491-6.347m-15.482 0a50.57 50.57 0 00-2.658-.813A59.905 59.905 0 0112 3.493a59.902 59.902 0 0110.499 5.221 69.17 69.17 0 00-2.692 1.296M3.191 14.463l.73 1.053a4.5 4.5 0 01.73 2.518V20.89M12 15.63a6.002 6.002 0 01-6-6.002 6.002 6.002 0 0112 0 6.002 6.002 0 01-6 6.002z',
                                color: 'bg-[#dc2626]', 
                                text: 'text-red-100', 
                                desc: 'Ensuring accessible learning resources and peer-tutoring programs to leave no student behind.' 
                            },
                            { 
                                id: 4, 
                                title: 'Science & Technology', 
                                /* Icon: Beaker/Science */
                                path: 'M9.75 9.75l4.5 4.5m0-4.5l-4.5 4.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z', 
                                /* Replaced simple circle with Atom/Network shape below in template logic if needed, but keeping simple for path binding. Let's use a Chip/Cpu path for Tech: */
                                path: 'M8.25 3v1.5M4.5 8.25H3m18 0h-1.5M4.5 12H3m18 0h-1.5m-15 3.75H3m18 0h-1.5M8.25 19.5V21M12 3v1.5m0 15V21m3.75-18v1.5m0 15V21m-9-1.5h10.5a2.25 2.25 0 002.25-2.25V6.75a2.25 2.25 0 00-2.25-2.25H6.75A2.25 2.25 0 004.5 6.75v10.5a2.25 2.25 0 002.25 2.25z',
                                color: 'bg-[#059669]', 
                                text: 'text-emerald-100', 
                                desc: 'Innovating for the future by supporting student-led research and tech solutions for sustainable development.' 
                            },
                            { 
                                id: 5, 
                                title: 'Digital Strategies', 
                                /* Icon: Signal/Wifi */
                                path: 'M8.288 15.038a5.25 5.25 0 017.424 0M5.106 11.856c3.807-3.808 9.98-3.808 13.788 0M1.924 8.674c5.565-5.565 14.587-5.565 20.152 0M12.53 18.22l-.53.53-.53-.53a.75.75 0 011.06 0z',
                                color: 'bg-[#7c3aed]', 
                                text: 'text-purple-100', 
                                desc: 'Leveraging modern media to amplify our advocacy reach and combat misinformation in the digital age.' 
                            }
                        ]
                    }"
                    class="animate-fade-in-up"
                >
                    <div class="flex items-center gap-2 mb-4 ml-2">
                        <span class="w-3 h-3 bg-red-500 rounded-full animate-pulse"></span>
                        <h4 class="font-bold text-gray-200 uppercase tracking-widest text-xs shadow-black drop-shadow-md">Our Core Pillars</h4>
                    </div>

                    <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-5 gap-4 relative z-30">
                        <template x-for="pillar in pillars" :key="pillar.id">
                            <button 
                                @click="activePillar = (activePillar === pillar.id ? null : pillar.id)"
                                :class="pillar.color"
                                class="rounded-xl p-4 text-white shadow-lg transition-all duration-300 group cursor-pointer relative overflow-hidden h-40 flex flex-col justify-between text-left hover:-translate-y-2 hover:shadow-2xl border-2 border-transparent focus:border-white/50 focus:outline-none"
                            >
                                <div class="mb-2 opacity-80 group-hover:scale-110 transition duration-500 group-hover:opacity-100">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-10 h-10">
                                        <path stroke-linecap="round" stroke-linejoin="round" :d="pillar.path" />
                                    </svg>
                                </div>
                                
                                <div class="relative z-10">
                                    <h3 class="font-bold leading-tight text-sm" x-text="pillar.title"></h3>
                                    <div 
                                        :class="activePillar === pillar.id ? 'w-full' : 'w-8 group-hover:w-16'"
                                        class="h-1 bg-white/50 mt-2 rounded-full transition-all duration-300">
                                    </div>
                                </div>
                                
                                <span x-text="'0' + pillar.id" class="absolute top-2 right-3 font-black text-4xl text-white/10 group-hover:text-white/20 transition-colors"></span>
                                
                                <div x-show="activePillar === pillar.id" 
                                    class="absolute bottom-[-8px] left-1/2 -translate-x-1/2 w-4 h-4 rotate-45 transform"
                                    :class="pillar.color"></div>
                            </button>
                        </template>
                    </div>

                    <div 
                        x-show="activePillar !== null"
                        x-collapse
                        class="mt-6"
                        style="display: none;" 
                    >
                        <template x-for="pillar in pillars" :key="pillar.id">
                            <div 
                                x-show="activePillar === pillar.id"
                                x-transition:enter="transition ease-out duration-300"
                                x-transition:enter-start="opacity-0 translate-y-4"
                                x-transition:enter-end="opacity-100 translate-y-0"
                                :class="pillar.color"
                                class="rounded-2xl p-8 shadow-inner text-white relative overflow-hidden"
                            >
                                <div class="absolute top-0 right-0 -mt-4 -mr-4 w-32 h-32 bg-white/10 rounded-full blur-2xl"></div>
                                <div class="absolute bottom-0 left-0 -mb-4 -ml-4 w-24 h-24 bg-black/10 rounded-full blur-xl"></div>

                                <div class="relative z-10 flex flex-col md:flex-row gap-6 items-center">
                                    <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center shadow-sm flex-shrink-0">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8">
                                            <path stroke-linecap="round" stroke-linejoin="round" :d="pillar.path" />
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-2xl font-heading font-bold mb-2 flex items-center gap-3">
                                            <span x-text="pillar.title"></span>
                                            <span class="text-xs border border-white/30 px-2 py-1 rounded-full uppercase tracking-wider font-normal">Active Focus</span>
                                        </h3>
                                        <p class="text-lg leading-relaxed opacity-95" :class="pillar.text" x-text="pillar.desc"></p>
                                    </div>
                                    <div class="ml-auto flex-shrink-0">
                                        <button class="px-6 py-2 bg-white text-gray-900 font-bold rounded-lg text-sm hover:bg-opacity-90 transition shadow-lg">
                                            View Projects
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                </section>

                <section x-data="{ shown: false }" x-intersect.threshold.20="shown = true" class="bg-white p-6 md:p-8 rounded-3xl shadow-xl border-t-4 border-yellow-400">
                    <div class="flex flex-col md:flex-row gap-8 items-center">
                        <div class="w-full md:w-1/2 rounded-2xl overflow-hidden aspect-[4/3] relative group">
                            <img src="https://images.unsplash.com/photo-1529156069898-49953e39b3ac?q=80&w=1932&auto=format&fit=crop" class="w-full h-full object-cover">
                        </div>
                        <div class="w-full md:w-1/2">
                            <h4 class="text-yellow-600 font-bold uppercase tracking-wider text-sm mb-2">Community First</h4>
                            <h3 class="font-heading text-3xl font-bold text-gray-900 mb-4">Student advocacy for <span class="text-green-600">everyone.</span></h3>
                            <p class="text-gray-600 mb-4">BU MADYA creates space for meaningful engagement and representation.</p>
                            <a href="#" class="font-bold text-green-700 hover:text-green-900">Read our Manifesto &rarr;</a>
                        </div>
                    </div>
                </section>

                <section x-data="{ shown: false }" x-intersect.threshold.20="shown = true" class="bg-white p-6 md:p-8 rounded-3xl shadow-xl border-b-4 border-red-600">
                    <div class="flex flex-col md:flex-row gap-8 items-center">
                        <div class="w-full md:w-1/2 order-2 md:order-1">
                            <h4 class="text-red-600 font-bold uppercase tracking-wider text-sm mb-2">Igniting Change</h4>
                            <h3 class="font-heading text-3xl font-bold text-gray-900 mb-4">Seeking the <span class="text-red-600">Extraordinary.</span></h3>
                            <p class="text-gray-600 mb-4">Driven by passion and UNESCO pillars to meet SDGs by 2030.</p>
                            <a href="#" class="font-bold text-red-700 hover:text-red-900">View Projects &rarr;</a>
                        </div>
                        <div class="w-full md:w-1/2 order-1 md:order-2 rounded-2xl overflow-hidden aspect-[4/3] relative group">
                            <img src="https://images.unsplash.com/photo-1522071820081-009f0129c71c?q=80&w=2070&auto=format&fit=crop" class="w-full h-full object-cover">
                        </div>
                    </div>
                </section>

                <section class="space-y-6">
                    <div class="flex items-center justify-between px-2">
                        <h2 class="font-heading text-2xl font-bold text-gray-800 border-l-4 border-green-600 pl-4">Latest Updates</h2>
                        <a href="#" class="text-sm font-bold text-red-600 hover:underline">View All News</a>
                    </div>

                    <div class="grid md:grid-cols-2 gap-6">
                        <article class="bg-white rounded-2xl shadow-md overflow-hidden hover:shadow-xl transition group border border-gray-100">
                            <div class="h-48 overflow-hidden relative">
                                <span class="absolute top-4 left-4 bg-red-600 text-white text-xs font-bold px-3 py-1 rounded-full z-10">Event</span>
                                <img src="https://images.unsplash.com/photo-1544531586-fde5298cdd40?q=80&w=2070&auto=format&fit=crop" class="w-full h-full object-cover group-hover:scale-110 transition duration-700">
                            </div>
                            <div class="p-6">
                                <div class="text-xs text-gray-400 mb-2 flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    Dec 12, 2025
                                </div>
                                <h3 class="font-bold text-xl text-gray-900 mb-2 group-hover:text-red-600 transition">Annual Leadership Summit</h3>
                                <p class="text-gray-600 text-sm line-clamp-3">Join student leaders from across Bicol University as we discuss the future of campus governance...</p>
                            </div>
                        </article>

                        <article class="bg-white rounded-2xl shadow-md overflow-hidden hover:shadow-xl transition group border border-gray-100">
                            <div class="h-48 overflow-hidden relative">
                                <span class="absolute top-4 left-4 bg-yellow-400 text-green-900 text-xs font-bold px-3 py-1 rounded-full z-10">Achievement</span>
                                <img src="https://images.unsplash.com/photo-1531545514256-b1400bc00f31?q=80&w=1974&auto=format&fit=crop" class="w-full h-full object-cover group-hover:scale-110 transition duration-700">
                            </div>
                            <div class="p-6">
                                <div class="text-xs text-gray-400 mb-2 flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    Nov 28, 2025
                                </div>
                                <h3 class="font-bold text-xl text-gray-900 mb-2 group-hover:text-red-600 transition">Outstanding Organization Award</h3>
                                <p class="text-gray-600 text-sm line-clamp-3">We are proud to announce that BU MADYA has been recognized as one of the top student orgs...</p>
                            </div>
                        </article>
                    </div>
                </section>
            </div>

            <aside class="lg:col-span-4 space-y-8 sticky top-28">
                
                <div class="relative overflow-hidden rounded-3xl shadow-xl group bg-gradient-to-br from-red-600 to-red-800 p-8 text-center text-white">
                    <div class="w-16 h-16 bg-white/20 backdrop-blur-sm rounded-full flex items-center justify-center mx-auto mb-6 border border-white/30">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                    </div>
                    <h3 class="font-heading text-2xl font-bold mb-2">Join the Movement</h3>
                    <p class="text-red-100 text-sm mb-6">Get exclusive access to workshops and networking events.</p>
                    <button class="w-full py-3 bg-white text-red-700 font-bold rounded-xl hover:bg-yellow-300 hover:text-red-800 transition transform hover:scale-105 shadow-lg">
                        Apply Now
                    </button>
                </div>

                <div x-data="{ 
                        activeSlide: 0,
                        slides: [
                            { img: 'https://images.unsplash.com/photo-1517486808906-6ca8b3f04846?q=80&w=1949&auto=format&fit=crop', caption: 'Tree Planting at Albay Park' },
                            { img: 'https://images.unsplash.com/photo-1523580494863-6f3031224c94?q=80&w=2070&auto=format&fit=crop', caption: 'Student Leadership Seminar' },
                            { img: 'https://images.unsplash.com/photo-1517245386807-bb43f82c33c4?q=80&w=2070&auto=format&fit=crop', caption: 'General Assembly 2024' }
                        ]
                     }" 
                     class="bg-white rounded-3xl shadow-xl overflow-hidden border border-gray-100"
                >
                    <div class="p-4 border-b border-gray-100 flex justify-between items-center">
                        <h3 class="font-bold text-gray-800 flex items-center gap-2">
                            <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            Gallery
                        </h3>
                        <div class="flex gap-1">
                            <button @click="activeSlide = activeSlide === 0 ? slides.length - 1 : activeSlide - 1" class="p-1 hover:bg-gray-100 rounded text-gray-500">&larr;</button>
                            <button @click="activeSlide = activeSlide === slides.length - 1 ? 0 : activeSlide + 1" class="p-1 hover:bg-gray-100 rounded text-gray-500">&rarr;</button>
                        </div>
                    </div>

                    <div class="relative aspect-square bg-gray-100">
                        <template x-for="(slide, index) in slides" :key="index">
                            <div x-show="activeSlide === index" 
                                 x-transition:enter="transition ease-out duration-300"
                                 x-transition:enter-start="opacity-0 scale-95"
                                 x-transition:enter-end="opacity-100 scale-100"
                                 class="absolute inset-0"
                            >
                                <img :src="slide.img" class="w-full h-full object-cover">
                                
                                <div class="absolute bottom-0 left-0 w-full bg-gradient-to-t from-black/80 to-transparent p-6 pt-12">
                                    <p x-text="slide.caption" class="text-white font-medium text-sm"></p>
                                </div>
                            </div>
                        </template>
                    </div>
                    
                    <div class="flex p-2 gap-1 justify-center bg-gray-50">
                        <template x-for="(slide, index) in slides" :key="index">
                            <button @click="activeSlide = index" 
                                    :class="activeSlide === index ? 'bg-red-500 w-6' : 'bg-gray-300 w-2'"
                                    class="h-1 rounded-full transition-all duration-300"></button>
                        </template>
                    </div>

                    <div class="bg-yellow-400 rounded-3xl p-6 text-green-900 shadow-lg relative overflow-hidden">
                        <svg class="absolute -right-4 -bottom-4 w-24 h-24 text-yellow-300 opacity-50" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg>
                        <h4 class="font-bold text-lg mb-1">Have a project idea?</h4>
                        <p class="text-sm opacity-80 mb-4">We are open for collaborations.</p>
                         <a href="{{ route('linkages.proposal') }}" class="block w-full py-2 bg-gradient-to-r from-red-600 to-yellow-500 text-white text-xs font-bold uppercase rounded-lg shadow hover:shadow-lg transition transform hover:-translate-y-0.5">
                            Propose Project
                        </a>
                    </div>
                </div>

            </aside>

        </div>
    </main>

    <footer class="bg-gray-900 text-white pt-20 pb-10 border-t-8 border-red-600 relative">
        <div class="max-w-[1800px] w-[95%] mx-auto px-6 grid md:grid-cols-4 gap-12 mb-16">
            
            <div class="col-span-1 md:col-span-2">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-8 h-8 bg-red-600 text-white rounded-full flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.879 16.121A3 3 0 1012.015 11L11 14H9c0 .768.293 1.536.879 2.121z"></path></svg>
                    </div>
                    <span class="font-heading font-bold text-xl">BU MADYA</span>
                </div>
                <p class="text-gray-400 leading-relaxed max-w-sm mb-6">
                    The Bicol University - Movement for the Advancement of Youth-led Advocacy is a duly-accredited University Based Organization.
                </p>
                <div class="flex space-x-4">
                    <a href="#" class="w-10 h-10 rounded-full bg-gray-800 flex items-center justify-center hover:bg-red-600 transition">f</a>
                    <a href="#" class="w-10 h-10 rounded-full bg-gray-800 flex items-center justify-center hover:bg-yellow-500 transition">O</a>
                    <a href="#" class="w-10 h-10 rounded-full bg-gray-800 flex items-center justify-center hover:bg-blue-400 transition">t</a>
                </div>
            </div>

            <div>
                <h4 class="font-bold text-lg mb-6 text-red-500">Quick Links</h4>
                <ul class="space-y-3 text-gray-400">
                    <li><a href="#" class="hover:text-white transition">About Organization</a></li>
                    <li><a href="#" class="hover:text-white transition">Our Officers</a></li>
                    <li><a href="#" class="hover:text-white transition">Upcoming Events</a></li>
                    <li><a href="#" class="hover:text-white transition">Join Membership</a></li>
                </ul>
            </div>

            <div>
                <h4 class="font-bold text-lg mb-6 text-green-500">Live Stats</h4>
                <div class="bg-gray-800 p-6 rounded-2xl border border-gray-700">
                    <span class="block text-xs uppercase tracking-widest text-gray-500 mb-2">Total Visitors</span>
                    <div class="text-4xl font-mono text-yellow-400 tracking-widest">
                        {{ str_pad($visitorCount, 7, '0', STR_PAD_LEFT) }}
                    </div>
                </div>
            </div>
        </div>

        <div class="border-t border-gray-800 pt-8 text-center text-gray-600 text-sm">
            &copy; 2025 BU MADYA. All Rights Reserved.
        </div>
    </footer>
</div>