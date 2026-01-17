<div class="min-h-screen bg-neutral-50 font-sans text-gray-900 selection:bg-red-600 selection:text-white overflow-x-hidden">

    {{-- 1. HERO SECTION (Cinematic Redesign) --}}
    <header class="relative min-h-[80vh] flex items-center text-white overflow-hidden bg-gray-900">
        
        {{-- Background Image & Professional Overlay --}}
        <div class="absolute inset-0 z-0">
            {{-- IMAGE --}}
            <img src="{{ asset('images/1760712981522.JPG') }}" 
                class="w-full h-full object-cover object-center scale-105 animate-slow-pan opacity-60" 
                alt="BU MADYA Team">
            
            {{-- OVERLAY 1: Darkening Gradient (Bottom-up for text contrast) --}}
            <div class="absolute inset-0 bg-gradient-to-t from-gray-950 via-gray-900/70 to-gray-900/30"></div>
            
            {{-- OVERLAY 2: Brand Color Tint (Subtle Green/Red mix) --}}
            <div class="absolute inset-0 bg-gradient-to-r from-green-900/40 to-red-900/40 mix-blend-overlay"></div>
            
            {{-- Optional Subtle Texture Pattern (mesh) --}}
            <div class="absolute inset-0 opacity-[0.03]" style="background-image: url('data:image/svg+xml,%3Csvg width=\'20\' height=\'20\' viewBox=\'0 0 20 20\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'%23ffffff\' fill-opacity=\'1\' fill-rule=\'evenodd\'%3E%3Ccircle cx=\'3\' cy=\'3\' r=\'3\'/%3E%3Ccircle cx=\'13\' cy=\'13\' r=\'3\'/%3E%3C/g%3E%3C/svg%3E');"></div>
        </div>

        {{-- Hero Content --}}
        <div x-data="{ show: false }" x-init="setTimeout(() => show = true, 200)" 
            class="relative z-10 container mx-auto px-6 pt-20">
            
            <div class="max-w-4xl mx-auto text-center">
                {{-- Logo --}}
                <div x-show="show" 
                    x-transition:enter="transition ease-out duration-1000"
                    x-transition:enter-start="opacity-0 scale-90"
                    x-transition:enter-end="opacity-100 scale-100"
                    class="inline-block mb-8 p-3 bg-white/5 backdrop-blur-md rounded-2xl border border-white/10 shadow-2xl">
                    <img src="{{ asset('images/MADYA Web Logo1.png') }}" alt="Logo" class="w-20 h-20 md:w-24 md:h-24 object-contain drop-shadow-lg">
                </div>

                <div x-show="show"
                    x-transition:enter="transition ease-out duration-1000 delay-300"
                    x-transition:enter-start="opacity-0 translate-y-8"
                    x-transition:enter-end="opacity-100 translate-y-0">
                    
                    <h2 class="text-yellow-400 font-bold tracking-[0.3em] text-xs md:text-sm mb-6 uppercase drop-shadow-md flex items-center justify-center gap-3">
                        <span class="w-8 h-px bg-yellow-400/50"></span>
                        Bicol University
                        <span class="w-8 h-px bg-yellow-400/50"></span>
                    </h2>
                    
                    <h1 class="font-heading text-4xl md:text-6xl lg:text-7xl font-black uppercase tracking-tight leading-none mb-8 drop-shadow-2xl text-white">
                        Movement for the <br class="hidden md:block">
                        {{-- Gradient Text Effect --}}
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-yellow-300 via-orange-300 to-red-400">
                            Advancement of Youth-led Advocacy
                        </span>
                    </h1>
                    
                    <p class="text-lg md:text-2xl text-gray-300 max-w-2xl mx-auto mb-12 font-light leading-relaxed drop-shadow">
                        Empowering youth-led advocacy and fostering sustainable development through active dialogue.
                    </p>

                    <div class="flex flex-col sm:flex-row gap-5 justify-center items-center">
                        <a href="{{ route('membership-form') }}" class="group relative px-8 py-4 bg-yellow-500 text-gray-900 font-bold rounded-full shadow-lg hover:shadow-yellow-500/50 transition-all duration-300 overflow-hidden">
                            <span class="absolute inset-0 w-full h-full bg-gradient-to-r from-yellow-400 to-yellow-600 opacity-0 group-hover:opacity-100 transition-opacity"></span>
                            <span class="relative flex items-center gap-2 uppercase tracking-wider text-xs">
                                Join the Movement <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                            </span>
                        </a>
                        <a href="#pillars" class="px-8 py-4 bg-transparent border-2 border-white/30 text-white font-bold rounded-full hover:bg-white hover:text-gray-900 transition-all duration-300 uppercase tracking-wider text-xs hover:border-transparent">
                            Learn More
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        {{-- Subtle bottom fade to connect to next section smoothly --}}
        <div class="absolute bottom-0 left-0 w-full h-24 bg-gradient-to-t from-neutral-50 to-transparent z-0"></div>
    </header>

    {{-- MAIN CONTENT CONTAINER --}}
    <div class="relative z-20 bg-neutral-50">
        
        {{-- 2. PILLARS SECTION (Clean Grid) --}}
        <section id="about" class="py-24 px-6 container mx-auto">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <span class="text-red-600 font-bold uppercase tracking-widest text-xs mb-2 block">Our Core Pillars</span>
                <h2 class="font-heading text-4xl md:text-5xl font-black text-gray-900 mb-6">Driving Change Through Action</h2>
                <p class="text-gray-600 text-lg">We focus our efforts on key areas to ensure holistic development and meaningful impact in the Bicol region.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-5 gap-6">
                @php
                    $pillars = [
                        ['title' => 'Culture & Heritage', 'icon' => 'M12 21a9.004 9.004 0 008.716-6.747M12 21a9.004 9.004 0 01-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 017.843 4.582M12 3a8.997 8.997 0 00-7.843 4.582m15.686 0A11.953 11.953 0 0112 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0121 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0112 16.5c-3.162 0-6.133-.815-8.716-2.247m0 0A9.015 9.015 0 013 12c0-1.605.42-3.113 1.157-4.418', 'color' => 'text-amber-600', 'bg' => 'bg-amber-50', 'border' => 'border-amber-100'],
                        ['title' => 'Social Sciences', 'icon' => 'M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z', 'color' => 'text-blue-600', 'bg' => 'bg-blue-50', 'border' => 'border-blue-100'],
                        ['title' => 'Quality Education', 'icon' => 'M4.26 10.147a60.436 60.436 0 00-.491 6.347A48.627 48.627 0 0112 20.904a48.627 48.627 0 018.232-4.41 60.46 60.46 0 00-.491-6.347m-15.482 0a50.57 50.57 0 00-2.658-.813A59.905 59.905 0 0112 3.493a59.902 59.902 0 0110.499 5.221 69.17 69.17 0 00-2.692 1.296M3.191 14.463l.73 1.053a4.5 4.5 0 01.73 2.518V20.89M12 15.63a6.002 6.002 0 01-6-6.002 6.002 6.002 0 0112 0 6.002 6.002 0 01-6 6.002z', 'color' => 'text-red-600', 'bg' => 'bg-red-50', 'border' => 'border-red-100'],
                        ['title' => 'Sci & Tech', 'icon' => 'M8.25 3v1.5M4.5 8.25H3m18 0h-1.5M4.5 12H3m18 0h-1.5m-15 3.75H3m18 0h-1.5M8.25 19.5V21M12 3v1.5m0 15V21m3.75-18v1.5m0 15V21m-9-1.5h10.5a2.25 2.25 0 002.25-2.25V6.75a2.25 2.25 0 00-2.25-2.25H6.75A2.25 2.25 0 004.5 6.75v10.5a2.25 2.25 0 002.25 2.25z', 'color' => 'text-emerald-600', 'bg' => 'bg-emerald-50', 'border' => 'border-emerald-100'],
                        ['title' => 'Digital Strategies', 'icon' => 'M8.288 15.038a5.25 5.25 0 017.424 0M5.106 11.856c3.807-3.808 9.98-3.808 13.788 0M1.924 8.674c5.565-5.565 14.587-5.565 20.152 0M12.53 18.22l-.53.53-.53-.53a.75.75 0 011.06 0z', 'color' => 'text-purple-600', 'bg' => 'bg-purple-50', 'border' => 'border-purple-100'],
                    ];
                @endphp

                @foreach($pillars as $pillar)
                <div class="bg-white rounded-2xl p-6 border {{ $pillar['border'] }} shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 group">
                    <div class="w-12 h-12 {{ $pillar['bg'] }} {{ $pillar['color'] }} rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="{{ $pillar['icon'] }}"></path></svg>
                    </div>
                    <h3 class="font-bold text-gray-900 leading-tight group-hover:{{ $pillar['color'] }} transition-colors">{{ $pillar['title'] }}</h3>
                </div>
                @endforeach
            </div>
        </section>

        {{-- 3. SPLIT SECTION (Mission + Image) --}}
        <section class="py-20 bg-white">
            <div class="container mx-auto px-6">
                <div class="flex flex-col lg:flex-row items-center gap-16">
                    <div class="w-full lg:w-1/2 relative">
                        <div class="absolute -top-4 -left-4 w-full h-full border-2 border-red-500 rounded-3xl translate-x-4 translate-y-4 hidden md:block"></div>
                        <img src="https://images.unsplash.com/photo-1529156069898-49953e39b3ac?q=80&w=1932" class="relative rounded-3xl shadow-2xl w-full object-cover aspect-[4/3] grayscale hover:grayscale-0 transition duration-700">
                    </div>
                    <div class="w-full lg:w-1/2">
                        <span class="text-yellow-500 font-bold uppercase tracking-widest text-xs mb-2 block">Our Mission</span>
                        <h2 class="font-heading text-4xl md:text-5xl font-black text-gray-900 mb-6 leading-tight">
                            Advocacy for <span class="text-red-600 underline decoration-4 decoration-yellow-400">Everyone.</span>
                        </h2>
                        <p class="text-gray-600 text-lg mb-8 leading-relaxed">
                            BU MADYA creates space for meaningful engagement and representation. We believe that true change starts when every student feels empowered to speak, act, and lead.
                        </p>
                        
                        <div class="grid grid-cols-2 gap-6 mb-8">
                            <div class="pl-4 border-l-4 border-red-100">
                                <span class="block text-2xl font-black text-gray-900 mb-1">5+</span>
                                <span class="text-sm text-gray-500 font-bold uppercase">Active Projects</span>
                            </div>
                            <div class="pl-4 border-l-4 border-yellow-100">
                                <span class="block text-2xl font-black text-gray-900 mb-1">100+</span>
                                <span class="text-sm text-gray-500 font-bold uppercase">Members</span>
                            </div>
                        </div>

                        <a href="{{ route('about') }}" class="text-red-600 font-bold uppercase tracking-widest text-xs hover:text-red-800 transition flex items-center gap-2">
                            Read Our Manifesto <span class="text-lg">&rarr;</span>
                        </a>
                    </div>
                </div>
            </div>
        </section>

        {{-- 4. LATEST NEWS (Masonry / Grid) --}}
        <section class="py-24 px-6 container mx-auto bg-neutral-50">
            <div class="flex flex-col md:flex-row justify-between items-end mb-12 gap-4">
                <div>
                    <h2 class="font-heading text-4xl font-black text-gray-900 mb-2">Latest Updates</h2>
                    <p class="text-gray-500">Stay informed with our latest activities and announcements.</p>
                </div>
                <a href="{{ route('news.index') }}" class="px-6 py-3 bg-white border border-gray-200 text-gray-900 text-xs font-bold uppercase rounded-full hover:bg-gray-100 transition shadow-sm">
                    View All News
                </a>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                @forelse($latestNews as $news)
                    @php
                        $catName = is_object($news->category) ? $news->category->name : $news->category;
                        $coverUrl = $news->cover_img ? (Str::startsWith($news->cover_img, ['http', 'https']) ? $news->cover_img : asset('storage/' . $news->cover_img)) : null;
                    @endphp
                    <article class="group bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 border border-gray-100 flex flex-col h-full">
                        <div class="h-56 overflow-hidden relative">
                            @if($coverUrl)
                                <img src="{{ $coverUrl }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-700">
                            @else
                                <div class="w-full h-full bg-gray-100 flex items-center justify-center text-gray-400">
                                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                </div>
                            @endif
                            <div class="absolute top-4 left-4 bg-white/90 backdrop-blur px-3 py-1 text-[10px] font-bold uppercase tracking-wide rounded-full text-gray-900 shadow-sm">
                                {{ $catName }}
                            </div>
                        </div>
                        <div class="p-6 flex flex-col flex-1">
                            <div class="text-xs text-gray-400 mb-3 font-medium">
                                {{ $news->published_at ? $news->published_at->format('F d, Y') : 'Date TBA' }}
                            </div>
                            <h3 class="font-bold text-xl text-gray-900 mb-3 leading-tight group-hover:text-red-600 transition">
                                <a href="{{ route('news.show', $news->slug) }}">{{ $news->title }}</a>
                            </h3>
                            <p class="text-gray-600 text-sm line-clamp-3 mb-6 flex-1">
                                {{ $news->summary ?? Str::limit(strip_tags($news->content), 120) }}
                            </p>
                            <a href="{{ route('news.show', $news->slug) }}" class="text-red-600 font-bold text-xs uppercase tracking-wider flex items-center gap-1 group-hover:gap-2 transition-all">
                                Read Article <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                            </a>
                        </div>
                    </article>
                @empty
                    <div class="col-span-3 py-12 text-center text-gray-400">
                        No updates available at the moment.
                    </div>
                @endforelse
            </div>
        </section>

        {{-- 5. CALL TO ACTION / FOOTER PREVIEW --}}
        <section class="bg-gray-900 text-white py-20 px-6 overflow-hidden relative">
            <div class="absolute top-0 right-0 w-96 h-96 bg-red-600 rounded-full blur-[150px] opacity-20 -mr-20 -mt-20"></div>
            <div class="absolute bottom-0 left-0 w-96 h-96 bg-green-900 rounded-full blur-[150px] opacity-20 -ml-20 -mb-20"></div>
            
            <div class="container mx-auto relative z-10 text-center max-w-4xl">
                <h2 class="font-heading text-4xl md:text-5xl font-black mb-6">Ready to Make an Impact?</h2>
                <p class="text-gray-400 text-lg mb-10 max-w-2xl mx-auto">Join a community of passionate individuals dedicated to creating positive change in Bicol University and beyond.</p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="{{ route('membership-form') }}" class="px-8 py-4 bg-yellow-400 text-gray-900 font-bold rounded-full hover:bg-yellow-300 transition shadow-[0_0_20px_rgba(250,204,21,0.3)] uppercase tracking-widest text-sm">
                        Apply for Membership
                    </a>
                    <a href="{{ route('projects.index') }}" class="px-8 py-4 bg-transparent border border-gray-700 text-white font-bold rounded-full hover:bg-gray-800 transition uppercase tracking-widest text-sm">
                        Browse Projects
                    </a>
                </div>
            </div>
        </section>
    </div>

    {{-- FOOTER --}}
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
                    {{-- Facebook --}}
                    <a href="https://www.facebook.com/BUMadya" class="w-10 h-10 rounded-full bg-gray-800 flex items-center justify-center hover:bg-blue-600 hover:text-white text-gray-400 transition">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                    </a>

                    {{-- Instagram --}}
                    <a href="https://www.instagram.com/bu_madya" class="w-10 h-10 rounded-full bg-gray-800 flex items-center justify-center hover:bg-pink-600 hover:text-white text-gray-400 transition">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                    </a>

                    {{-- X (Twitter) --}}
                    <a href="https://www.x.com/bu_madya" class="w-10 h-10 rounded-full bg-gray-800 flex items-center justify-center hover:bg-black hover:text-white text-gray-400 transition">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                    </a>
                </div>
            </div>
            
            <div>
                <h4 class="font-bold text-lg mb-6 text-red-500 uppercase tracking-widest text-xs">Quick Links</h4>
                <ul class="space-y-3 text-gray-400 text-sm">
                    <li><a href="{{ route('about') }}" class="hover:text-white hover:translate-x-1 transition inline-block">About BU MADYA</a></li>
                    <li><a href="{{ route('open.directory') }}" class="hover:text-white hover:translate-x-1 transition inline-block">Our Officers</a></li>
                    <li><a href="{{ route('transparency.index') }}" class="hover:text-white hover:translate-x-1 transition inline-block">Transparency Board</a></li>
                </ul>
            </div>

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
    