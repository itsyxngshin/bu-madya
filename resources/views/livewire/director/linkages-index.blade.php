<div class="min-h-screen bg-stone-50 font-sans text-gray-900 overflow-x-hidden relative">
    
    {{-- 1. ATMOSPHERE: SIGNATURE BLOBS --}}
    <div class="fixed inset-0 z-0 overflow-hidden pointer-events-none">
        <div class="absolute top-0 left-0 w-full h-full bg-stone-50/80"></div>
        <div class="absolute top-[-10%] right-[-10%] w-[500px] h-[500px] bg-red-200 rounded-full mix-blend-multiply filter blur-3xl opacity-40 animate-blob"></div>
        <div class="absolute top-[20%] left-[-10%] w-[500px] h-[500px] bg-yellow-200 rounded-full mix-blend-multiply filter blur-3xl opacity-40 animate-blob animation-delay-2000"></div>
        <div class="absolute bottom-[-10%] right-[20%] w-[500px] h-[500px] bg-green-200 rounded-full mix-blend-multiply filter blur-3xl opacity-40 animate-blob animation-delay-4000"></div>
    </div>

    {{-- 2. HERO HEADER (Updated: Floating Card Style) --}}
    {{-- Added 'max-w-7xl mx-4 md:mx-auto mt-4 md:mt-6 rounded-[2.5rem]' to create the whitespace and rounded look --}}
    <header class="relative min-h-[400px] flex items-center justify-center text-white overflow-hidden rounded-[2rem] md:rounded-[3rem] shadow-2xl mx-4 md:mx-auto max-w-7xl mt-4 md:mt-8 z-10 pb-10 pt-20 md:py-0">
        
        {{-- Background Image & Gradient --}}
        <div class="absolute inset-0 z-0">
            <img src="https://images.unsplash.com/photo-1521737604893-d14cc237f11d?q=80&w=2084&auto=format&fit=crop" 
                 class="w-full h-full object-cover transform scale-105" alt="Handshake Background">
            <div class="absolute inset-0 bg-gradient-to-r from-blue-900/90 to-red-900/80 mix-blend-multiply"></div>
            <div class="absolute inset-0 opacity-20 bg-[url('https://www.transparenttextures.com/patterns/stardust.png')]"></div>
        </div>

        <div class="relative z-10 text-center px-4 w-full max-w-4xl mx-auto flex flex-col items-center">
            
            <h2 class="text-yellow-300 font-bold tracking-[0.3em] text-[10px] md:text-xs uppercase mb-3 animate-fade-in-down">Our Network</h2>
            
            {{-- Mobile Optimized Title Size --}}
            <h1 class="font-heading text-3xl md:text-6xl font-black uppercase tracking-tight mb-4 drop-shadow-2xl leading-none">
                External Linkages
            </h1>
            
            <p class="text-xs md:text-lg text-gray-100 font-light max-w-xl mx-auto italic mb-8 px-4 leading-relaxed opacity-90">
                Building bridges with government, NGOs, and civil society to amplify our impact.
            </p>

            {{-- ADD LINKAGE BUTTON --}}
           @auth
                @if(in_array(Auth::user()->role->role_name ?? '', ['administrator', 'director']))
                    <div class="mb-8">
                        <a href="{{ route('linkages.create') }}" 
                        class="group relative inline-flex items-center gap-2 px-5 py-2.5 md:px-6 md:py-3 bg-white/10 hover:bg-white/20 backdrop-blur-md border border-white/30 text-white text-[10px] md:text-xs font-bold uppercase tracking-widest rounded-full transition-all duration-300 hover:scale-105 shadow-lg hover:shadow-red-500/20 ring-1 ring-white/20">
                            <span class="w-4 h-4 md:w-5 md:h-5 bg-white/20 rounded-full flex items-center justify-center group-hover:bg-red-500 group-hover:text-white transition-colors">
                                <svg class="w-2.5 h-2.5 md:w-3 md:h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                            </span>
                            <span>Add New Linkage</span>
                        </a>
                    </div>
                @endif
            @endauth

            {{-- Dynamic Stats Card --}}
            <div class="w-full max-w-2xl bg-black/20 backdrop-blur-xl border border-white/10 rounded-2xl p-4 md:p-6 shadow-2xl">
                <div class="flex flex-wrap justify-center md:grid md:grid-cols-3 gap-4 md:gap-8 md:divide-x divide-white/10">
                    <div class="text-center px-4 min-w-[30%]">
                        <span class="block text-2xl md:text-4xl font-black text-white tracking-tighter">{{ $this->stats['active_count'] }}</span>
                        <span class="text-[8px] md:text-[10px] font-bold uppercase tracking-widest text-gray-300 block mt-1">Partners</span>
                    </div>
                    <div class="text-center px-4 min-w-[30%] border-l border-white/10 md:border-l-0">
                        <span class="block text-2xl md:text-4xl font-black text-yellow-400 tracking-tighter">{{ $this->stats['moa_count'] }}</span>
                        <span class="text-[8px] md:text-[10px] font-bold uppercase tracking-widest text-gray-300 block mt-1">MOAs</span>
                    </div>
                    <div class="text-center px-4 min-w-[30%] border-l border-white/10 md:border-l-0">
                        <span class="block text-2xl md:text-4xl font-black text-green-400 tracking-tighter">{{ $this->stats['intl_count'] }}</span>
                        <span class="text-[8px] md:text-[10px] font-bold uppercase tracking-widest text-gray-300 block mt-1">Global</span>
                    </div>
                </div>
            </div>

        </div>
    </header>

    {{-- 3. MAIN CONTENT (Constrained Width) --}}
    <div class="relative z-10 min-h-screen px-4 md:px-6 pb-24 mt-8 md:mt-12 max-w-7xl w-full mx-auto grid grid-cols-1 lg:grid-cols-12 gap-8 md:gap-12">
        
        {{-- LEFT: PARTNER DIRECTORY (8 Cols) --}}
        <main class="lg:col-span-8 order-1">
            <div class="flex flex-col md:flex-row md:items-center justify-between mb-6 md:mb-8 gap-4">
                <h3 class="font-heading font-bold text-xl md:text-2xl text-gray-900">Partner Directory</h3>
                
                {{-- Filter Tabs --}}
                <div class="w-full md:w-auto overflow-x-auto pb-2 md:pb-0 scrollbar-hide mask-fade-right">
                    <div class="flex gap-2">
                        <button wire:click="setCategory('All')"
                                class="whitespace-nowrap px-4 py-1.5 rounded-full text-[10px] font-bold uppercase tracking-wider transition-all border shrink-0
                                {{ $category === 'All' 
                                    ? 'bg-red-600 text-white border-red-600 shadow-md transform scale-105' 
                                    : 'bg-white text-gray-500 border-gray-200 hover:border-red-400 hover:text-red-500' }}">
                            All
                        </button>

                        @foreach($this->types as $type)
                        <button wire:click="setCategory('{{ $type->name }}')"
                                class="whitespace-nowrap px-4 py-1.5 rounded-full text-[10px] font-bold uppercase tracking-wider transition-all border shrink-0
                                {{ $category === $type->name 
                                    ? 'bg-red-600 text-white border-red-600 shadow-md transform scale-105' 
                                    : 'bg-white text-gray-500 border-gray-200 hover:border-red-400 hover:text-red-500' }}">
                            {{ $type->name }}
                        </button>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Partners Grid --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6">
                @forelse($this->partners as $partner)
                <div class="group bg-white rounded-2xl p-5 md:p-6 shadow-sm border border-gray-100 hover:shadow-xl hover:-translate-y-1 transition duration-300 relative overflow-hidden">
                    
                    {{-- Top Color Line --}}
                    <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-red-500 to-yellow-500 transform scale-x-0 group-hover:scale-x-100 transition duration-500 origin-left"></div>

                    <div class="flex items-start gap-4">
                        {{-- Logo --}}
                        <div class="w-12 h-12 md:w-16 md:h-16 rounded-xl bg-gray-50 p-2 border border-gray-100 shrink-0 flex items-center justify-center">
                            @if($partner->logo_path)
                                <img src="{{ asset('storage/' . $partner->logo_path) }}" class="w-full h-full object-contain mix-blend-multiply">
                            @else
                                <span class="text-[10px] font-bold text-gray-300 text-center leading-tight">{{ $partner->acronym ?? 'LOGO' }}</span>
                            @endif
                        </div>
                        
                        {{-- Info --}}
                        <div class="flex-grow min-w-0">
                            <div class="flex flex-col md:flex-row md:justify-between md:items-start mb-1">
                                <h4 class="font-bold text-gray-900 text-sm md:text-base leading-tight group-hover:text-red-700 transition line-clamp-1 pr-2">
                                    {{ $partner->name }}
                                </h4>
                                @if($partner->type)
                                    <span class="text-[8px] md:text-[9px] font-bold uppercase tracking-wide px-2 py-0.5 rounded-full w-fit mt-1 md:mt-0 {{ $partner->type->color ?? 'bg-gray-100 text-gray-500' }}">
                                        {{ $partner->type->name }}
                                    </span>
                                @endif
                            </div>
                            
                            {{-- Status --}}
                            <p class="text-[9px] font-bold uppercase tracking-widest mb-2 flex items-center gap-1 {{ $partner->status->color ?? 'text-gray-400' }}">
                                <span class="w-1.5 h-1.5 rounded-full {{ str_replace('text-', 'bg-', $partner->status->color ?? 'bg-gray-400') }}"></span> 
                                {{ $partner->status->name ?? 'Unknown' }}
                            </p>
                            
                            {{-- Description --}}
                            <p class="text-[10px] md:text-xs text-gray-500 leading-relaxed mb-3 line-clamp-2">
                                {{ $partner->description ?? 'No description provided.' }}
                            </p>

                            <div class="flex items-center justify-between border-t border-gray-50 pt-3">
                                <span class="text-[9px] text-gray-400 font-bold uppercase">
                                    Since: {{ $partner->established_at ? $partner->established_at->format('Y') : 'N/A' }}
                                </span>
                                <a href="{{ route('linkages.show', ['linkage' => $partner->slug]) }}" 
                                   class="text-[10px] md:text-xs font-bold text-red-600 hover:underline flex items-center gap-1">
                                    View <span class="group-hover:translate-x-0.5 transition">&rarr;</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-span-1 md:col-span-2 text-center py-12 text-gray-400 bg-white/80 rounded-2xl border border-dashed border-gray-200">
                    <p class="text-sm">No partners found in this category.</p>
                </div>
                @endforelse
            </div>
        </main>

        {{-- RIGHT: ENGAGEMENTS TIMELINE --}}
        <aside class="lg:col-span-4 order-2">
            <div class="bg-white/90 backdrop-blur-md rounded-[2rem] p-6 shadow-lg border border-gray-100 lg:sticky lg:top-24">
                <h3 class="font-heading font-bold text-lg md:text-xl text-gray-900 mb-6 flex items-center gap-2">
                    <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Recent Engagements
                </h3>

                <div class="relative border-l-2 border-gray-200 ml-3 space-y-8 pl-6 pb-2">
                    @forelse($this->engagements as $activity)
                    <div class="relative group">
                        <div class="absolute -left-[31px] top-1.5 w-4 h-4 rounded-full border-2 border-white shadow-sm transition-colors duration-300
                            {{ $loop->first ? 'bg-red-500 ring-4 ring-red-50' : 'bg-gray-300 group-hover:bg-red-400' }}">
                        </div>

                        <span class="text-[9px] font-bold text-gray-400 uppercase tracking-wider mb-1 block">
                            {{ $activity->activity_date->format('M d, Y') }}
                        </span>
                        
                        <h5 class="font-bold text-gray-800 text-xs md:text-sm leading-tight group-hover:text-red-600 transition">
                            {{ $activity->title }}
                        </h5>
                        
                        <p class="text-[10px] text-blue-600 font-bold mt-0.5 mb-2">
                            w/ {{ $activity->linkage->acronym ?? $activity->linkage->name }}
                        </p>

                        @if($activity->description)
                        <p class="text-[10px] md:text-xs text-gray-500 leading-relaxed bg-gray-50 p-3 rounded-lg border border-gray-100 line-clamp-3">
                            {{ $activity->description }}
                        </p>
                        @endif
                    </div>
                    @empty
                    <p class="text-xs text-gray-400 italic">No recent activities recorded.</p>
                    @endforelse
                </div>

                {{-- CTA --}}
                <div class="mt-8 pt-6 border-t border-gray-100 text-center">
                    <h4 class="font-bold text-gray-900 text-xs mb-2">Want to partner with us?</h4>
                    <a href="{{ route('linkages.proposal') }}" class="block w-full py-2.5 bg-gradient-to-r from-red-600 to-yellow-500 text-white text-[10px] font-bold uppercase rounded-lg shadow hover:shadow-lg transition transform hover:-translate-y-0.5">
                        Send Proposal
                    </a>
                </div>
            </div>
        </aside>

    </div>

    {{-- FOOTER --}}
    <footer class="bg-gray-900 text-white pt-20 pb-10 border-t-8 border-red-600 relative z-20 mt-20">
        <div class="max-w-7xl w-full mx-auto px-6 grid md:grid-cols-4 gap-12 mb-16">
            {{-- Footer Content (Same as before) --}}
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