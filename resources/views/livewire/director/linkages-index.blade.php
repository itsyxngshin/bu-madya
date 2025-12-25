<div class="min-h-screen bg-stone-50 font-sans text-gray-900">
    
    {{-- 1. STICKY NAV --}}
    <nav x-data="{ scrolled: false }" @scroll.window="scrolled = (window.pageYOffset > 20)"
        class="sticky top-0 z-30 transition-all duration-300 w-full"
        :class="scrolled ? 'bg-white/90 backdrop-blur-md shadow-md py-2' : 'bg-transparent py-4'">
        <div class="px-6 flex justify-between items-center">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center shadow-lg overflow-hidden p-1">
                   <img src="{{ asset('images/official_logo.png') }}" alt="Logo" class="w-full h-full object-contain">
                </div>
                <span class="font-bold text-lg tracking-tight transition-colors duration-300">BU MADYA</span>
            </div>
            <div class="hidden md:flex items-center space-x-6 text-sm font-semibold tracking-wide">
                <a href="{{ route('dashboard') }}" class="text-gray-600 hover:text-red-600 transition">Home</a>
                <a href="{{ route('projects.index') }}" class="text-gray-600 hover:text-red-600 transition">Projects</a>
                <a href="#" class="text-red-600">Linkages</a>
            </div>
        </div>
    </nav>

    {{-- 2. HERO HEADER --}}
    <header class="relative h-[400px] flex items-center justify-center text-white overflow-hidden rounded-3xl shadow-xl mx-6 -mt-20 z-10">
        <div class="absolute inset-0 z-0">
            <img src="https://images.unsplash.com/photo-1521737604893-d14cc237f11d?q=80&w=2084&auto=format&fit=crop" 
                 class="w-full h-full object-cover" alt="Handshake Background">
            <div class="absolute inset-0 bg-gradient-to-r from-blue-900/90 to-red-900/80 mix-blend-multiply"></div>
        </div>

        <div class="relative z-10 text-center px-4 mt-16">
            <h2 class="text-yellow-300 font-bold tracking-[0.3em] text-xs uppercase mb-2">Our Network</h2>
            <h1 class="font-heading text-3xl md:text-5xl font-black uppercase tracking-tight mb-4 drop-shadow-lg">
                External Linkages
            </h1>
            <p class="text-sm md:text-base text-gray-100 font-light max-w-xl mx-auto italic mb-8">
                Building bridges with government, NGOs, and civil society to amplify our impact.
            </p>

            {{-- Stats --}}
            <div class="inline-flex gap-8 bg-white/10 backdrop-blur-md px-8 py-4 rounded-2xl border border-white/20">
                <div class="text-center">
                    <span class="block text-2xl font-black text-white">15</span>
                    <span class="text-[10px] uppercase tracking-wider text-gray-200">Active Partners</span>
                </div>
                <div class="w-px bg-white/20"></div>
                <div class="text-center">
                    <span class="block text-2xl font-black text-yellow-400">8</span>
                    <span class="text-[10px] uppercase tracking-wider text-gray-200">MOAs Signed</span>
                </div>
                <div class="w-px bg-white/20"></div>
                <div class="text-center">
                    <span class="block text-2xl font-black text-green-400">3</span>
                    <span class="text-[10px] uppercase tracking-wider text-gray-200">Intl. Affiliates</span>
                </div>
            </div>
        </div>
    </header>

    {{-- 3. MAIN CONTENT --}}
    <div class="relative min-h-screen px-6 pb-24 mt-12 max-w-[1800px] w-[95%] mx-auto grid lg:grid-cols-12 gap-12">
        
        {{-- LEFT: PARTNER DIRECTORY (8 Cols) --}}
        <main class="lg:col-span-8">
            <div class="flex items-center justify-between mb-8">
                <h3 class="font-heading font-bold text-2xl text-gray-900">Partner Directory</h3>
                
                {{-- Filter Tabs --}}
                <div class="flex gap-2">
                    @foreach(['All', 'Government', 'NGO', 'Academic'] as $filter)
                    <button wire:click="setCategory('{{ $filter }}')"
                            class="px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider transition-all border
                            {{ $category === $filter 
                                ? 'bg-red-600 text-white border-red-600' 
                                : 'bg-white text-gray-500 border-gray-200 hover:border-red-400 hover:text-red-500' }}">
                        {{ $filter }}
                    </button>
                    @endforeach
                </div>
            </div>

            <div class="grid md:grid-cols-2 gap-6">
                @foreach($partners as $partner)
                <div class="group bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-lg hover:-translate-y-1 transition duration-300 relative overflow-hidden">
                    
                    {{-- Top Color Line --}}
                    <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-red-500 to-yellow-500 transform scale-x-0 group-hover:scale-x-100 transition duration-500 origin-left"></div>

                    <div class="flex items-start gap-4">
                        {{-- Logo --}}
                        <div class="w-16 h-16 rounded-xl bg-gray-50 p-2 border border-gray-100 shrink-0">
                            <img src="{{ $partner['logo'] }}" class="w-full h-full object-contain mix-blend-multiply">
                        </div>
                        
                        {{-- Info --}}
                        <div class="flex-grow">
                            <div class="flex justify-between items-start">
                                <h4 class="font-bold text-gray-900 leading-tight group-hover:text-red-700 transition">{{ $partner['name'] }}</h4>
                                <span class="text-[9px] font-bold uppercase tracking-wide bg-gray-100 text-gray-500 px-2 py-0.5 rounded-full">{{ $partner['type'] }}</span>
                            </div>
                            
                            <p class="text-[10px] font-bold text-green-600 uppercase tracking-widest mt-1 mb-2 flex items-center gap-1">
                                <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> {{ $partner['status'] }}
                            </p>
                            
                            <p class="text-xs text-gray-500 leading-relaxed mb-3">
                                {{ $partner['desc'] }}
                            </p>

                            <div class="flex items-center justify-between border-t border-gray-50 pt-3">
                                <span class="text-[10px] text-gray-400 font-bold uppercase">Focus: {{ $partner['scope'] }}</span>
                                <a href="{{ route('linkages.show', $loop->index + 1) }}" class="text-xs font-bold text-red-600 hover:underline">
                                    View
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </main>

        {{-- RIGHT: ENGAGEMENTS TIMELINE (4 Cols) --}}
        <aside class="lg:col-span-4">
            <div class="bg-white rounded-3xl p-6 shadow-lg border border-gray-100 sticky top-24">
                <h3 class="font-heading font-bold text-xl text-gray-900 mb-6 flex items-center gap-2">
                    <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Recent Engagements
                </h3>

                <div class="relative border-l-2 border-gray-200 ml-3 space-y-8 pl-6 pb-2">
                    @foreach($engagements as $activity)
                    <div class="relative group">
                        {{-- Dot --}}
                        <div class="absolute -left-[31px] top-1.5 w-4 h-4 rounded-full border-2 border-white shadow-sm transition-colors duration-300
                            {{ $loop->first ? 'bg-red-500 ring-4 ring-red-50' : 'bg-gray-300 group-hover:bg-red-400' }}">
                        </div>

                        <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1 block">
                            {{ $activity['date'] }}
                        </span>
                        
                        <h5 class="font-bold text-gray-800 text-sm leading-tight group-hover:text-red-600 transition">
                            {{ $activity['title'] }}
                        </h5>
                        
                        <p class="text-xs text-blue-600 font-bold mt-0.5 mb-2">
                            w/ {{ $activity['partner'] }}
                        </p>

                        <p class="text-xs text-gray-500 leading-relaxed bg-gray-50 p-3 rounded-lg border border-gray-100">
                            {{ $activity['desc'] }}
                        </p>
                    </div>
                    @endforeach
                </div>

                {{-- CTA --}}
                <div class="mt-8 pt-6 border-t border-gray-100 text-center">
                    <h4 class="font-bold text-gray-900 text-sm mb-2">Want to partner with us?</h4>
                    {{-- 👇 UPDATE THIS LINE --}}
                    <a href="{{ route('linkages.proposal') }}" class="block w-full py-2 bg-gradient-to-r from-red-600 to-yellow-500 text-white text-xs font-bold uppercase rounded-lg shadow hover:shadow-lg transition transform hover:-translate-y-0.5">
                        Send Proposal
                    </a>
                </div>
            </div>
        </aside>

    </div>

    {{-- FOOTER --}}
    <footer class="mt-20 border-t border-gray-200 py-8 px-6 text-center text-xs text-gray-500 bg-white">
        &copy; 2025 BU MADYA. All Rights Reserved.
    </footer>

</div>