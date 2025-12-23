<div class="text-gray-800 bg-gray-50 overflow-x-hidden selection:bg-red-500 selection:text-white">

    <nav 
        x-data="{ scrolled: false }" 
        @scroll.window="scrolled = (window.pageYOffset > 20)"
        :class="scrolled ? 'bg-white/95 backdrop-blur-md shadow-lg py-4' : 'bg-transparent py-6'"
        class="fixed top-0 w-full z-50 transition-all duration-300"
    >
        <div class="container mx-auto px-6 flex justify-between items-center">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-gradient-to-br from-green-600 to-red-600 rounded-full flex items-center justify-center text-white shadow-lg">
                   <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.879 16.121A3 3 0 1012.015 11L11 14H9c0 .768.293 1.536.879 2.121z"></path></svg>
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

    <header class="relative h-screen min-h-[700px] flex items-center justify-center text-white overflow-hidden rounded-b-[60px] shadow-2xl">
        
        <div class="absolute inset-0 z-0">
            <img src="https://images.unsplash.com/photo-1523050854058-8df90110c9f1?q=80&w=2070&auto=format&fit=crop" 
                 class="w-full h-full object-cover" alt="University Background">
            
            <div class="absolute inset-0 bg-gradient-to-br from-green-900/95 via-green-800/80 to-red-700/80 mix-blend-multiply"></div>
        </div>

        <div 
            x-data="{ show: false }" 
            x-init="setTimeout(() => show = true, 100)"
            class="relative z-10 container mx-auto px-6 text-center mt-16"
        >
            <div x-show="show" 
                 x-transition:enter="transition ease-out duration-1000"
                 x-transition:enter-start="opacity-0 scale-50 rotate-180"
                 x-transition:enter-end="opacity-100 scale-100 rotate-0"
                 class="w-32 h-32 mx-auto mb-8 bg-white/10 backdrop-blur-sm rounded-full flex items-center justify-center border border-yellow-400/30 shadow-2xl">
                 <svg class="w-16 h-16 text-red-500 drop-shadow-[0_0_15px_rgba(239,68,68,0.8)]" fill="currentColor" viewBox="0 0 20 20"><path d="M13.572 5.223C12.49 5.223 11.611 6.103 11.611 7.186C11.611 8.268 10.731 9.148 9.648 9.148C8.566 9.148 7.686 8.268 7.686 7.186C7.686 6.103 6.806 5.223 5.723 5.223C4.64 5.223 3.761 6.103 3.761 7.186C3.761 10.978 6.838 14.055 10.63 14.055C14.422 14.055 17.499 10.978 17.499 7.186C17.499 6.103 16.62 5.223 15.537 5.223H13.572Z"></path></svg>
            </div>

            <div x-show="show" 
                 x-transition:enter="transition ease-out duration-1000 delay-300"
                 x-transition:enter-start="opacity-0 translate-y-10"
                 x-transition:enter-end="opacity-100 translate-y-0">
                
                <h2 class="text-yellow-300 font-bold tracking-[0.3em] text-sm md:text-base mb-4 uppercase">Bicol University</h2>
                
                <h1 class="font-heading text-4xl md:text-7xl font-black uppercase tracking-tight leading-none mb-6 drop-shadow-xl">
                    Movement for the <br> 
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-yellow-300 to-red-400">Advancement</span>
                </h1>
                
                <p class="text-lg md:text-xl text-green-50 max-w-2xl mx-auto mb-10 font-light">
                    Empowering youth-led advocacy and fostering sustainable development through active dialogue.
                </p>

                <div class="flex flex-col md:flex-row gap-4 justify-center">
                    <button class="px-8 py-4 bg-yellow-400 text-green-900 font-bold rounded-full shadow-[0_0_20px_rgba(250,204,21,0.4)] hover:bg-yellow-300 hover:scale-105 transition transform">
                        Join the Movement
                    </button>
                    <button class="px-8 py-4 bg-transparent border-2 border-red-400 text-white font-bold rounded-full hover:bg-red-600 hover:border-red-600 transition">
                        Learn More
                    </button>
                </div>
            </div>
        </div>
    </header>

    <main class="container mx-auto px-6 -mt-20 relative z-20 space-y-24 pb-24">
        
        <section 
            x-data="{ shown: false }" 
            x-intersect.threshold.20="shown = true"
            class="bg-white p-2 md:p-8 rounded-3xl shadow-xl border-t-4 border-yellow-400"
        >
            <div class="grid md:grid-cols-2 gap-8 items-center">
                <div 
                    x-show="shown"
                    x-transition:enter="transition transform ease-out duration-700"
                    x-transition:enter-start="opacity-0 scale-95"
                    x-transition:enter-end="opacity-100 scale-100"
                    class="rounded-2xl overflow-hidden aspect-[4/3] relative group"
                >
                    <div class="absolute inset-0 bg-green-900/10 group-hover:bg-transparent transition duration-500 z-10"></div>
                    <img src="http://googleusercontent.com/image_collection/image_retrieval/9398460692115697528_0" 
                         alt="Student advocacy" 
                         class="w-full h-full object-cover transform transition duration-700 group-hover:scale-110">
                </div>
                
                <div 
                    x-show="shown"
                    x-transition:enter="transition transform ease-out duration-700 delay-200"
                    x-transition:enter-start="opacity-0 translate-x-10"
                    x-transition:enter-end="opacity-100 translate-x-0"
                    class="p-4 md:p-8"
                >
                    <div class="flex items-center gap-3 mb-4">
                        <span class="p-2 bg-yellow-100 text-yellow-700 rounded-lg">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        </span>
                        <h4 class="text-yellow-600 font-bold uppercase tracking-wider text-sm">Community First</h4>
                    </div>
                    
                    <h3 class="font-heading text-3xl md:text-4xl font-bold text-gray-900 mb-6 leading-tight">
                        Student advocacy <br> for <span class="text-green-600">everyone.</span>
                    </h3>
                    <p class="text-gray-600 leading-relaxed mb-6">
                        BU MADYA creates space for meaningful engagement. We foster representation of young people in policies, driving broader awareness on societal matters.
                    </p>
                    <a href="#" class="inline-flex items-center font-bold text-green-700 hover:text-green-900 group">
                        Read our Manifesto 
                        <svg class="w-4 h-4 ml-2 transform group-hover:translate-x-1 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                    </a>
                </div>
            </div>
        </section>

        <section 
            x-data="{ shown: false }" 
            x-intersect.threshold.20="shown = true"
            class="bg-white p-2 md:p-8 rounded-3xl shadow-xl border-b-4 border-red-600"
        >
            <div class="grid md:grid-cols-2 gap-8 items-center">
                <div 
                    x-show="shown"
                    x-transition:enter="transition transform ease-out duration-700"
                    x-transition:enter-start="opacity-0 -translate-x-10"
                    x-transition:enter-end="opacity-100 translate-x-0"
                    class="order-2 md:order-1 p-4 md:p-8"
                >
                    <div class="flex items-center gap-3 mb-4">
                        <span class="p-2 bg-red-100 text-red-700 rounded-lg">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                        </span>
                        <h4 class="text-red-600 font-bold uppercase tracking-wider text-sm">Igniting Change</h4>
                    </div>

                    <h3 class="font-heading text-3xl md:text-4xl font-bold text-gray-900 mb-6 leading-tight">
                        Seeking the <br> <span class="text-red-600">Extraordinary.</span>
                    </h3>
                    <p class="text-gray-600 leading-relaxed mb-6">
                        Driven by passion and the core pillars of UNESCO, we align initiatives to meet the Sustainable Development Goals (SDGs) by 2030.
                    </p>
                    <a href="#" class="inline-flex items-center font-bold text-red-700 hover:text-red-900 group">
                        View our Projects 
                        <svg class="w-4 h-4 ml-2 transform group-hover:translate-x-1 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                    </a>
                </div>

                <div 
                    x-show="shown"
                    x-transition:enter="transition transform ease-out duration-700 delay-200"
                    x-transition:enter-start="opacity-0 scale-95"
                    x-transition:enter-end="opacity-100 scale-100"
                    class="order-1 md:order-2 rounded-2xl overflow-hidden aspect-[4/3] relative group"
                >
                     <div class="absolute inset-0 bg-red-900/10 group-hover:bg-transparent transition duration-500 z-10"></div>
                     <img src="http://googleusercontent.com/image_collection/image_retrieval/5090167561371053593_0" 
                          alt="Collaboration" 
                          class="w-full h-full object-cover transform transition duration-700 group-hover:scale-110">
                </div>
            </div>
        </section>

    </main>

    <footer class="bg-gray-900 text-white pt-20 pb-10 border-t-8 border-red-600 relative">
        <div class="container mx-auto px-6 grid md:grid-cols-4 gap-12 mb-16">
            
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