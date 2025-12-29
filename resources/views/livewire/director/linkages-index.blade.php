<div class="min-h-screen bg-stone-50 font-sans text-gray-900">
    
    {{-- 2. HERO HEADER --}}
    <header class="relative h-[400px] flex items-center justify-center text-white overflow-hidden rounded-3xl shadow-xl mx-4 md:mx-6 -mt-20 z-10">
        {{-- Background Image --}}
        <div class="absolute inset-0 z-0">
            <img src="https://images.unsplash.com/photo-1521737604893-d14cc237f11d?q=80&w=2084&auto=format&fit=crop" 
                 class="w-full h-full object-cover" alt="Handshake Background">
            <div class="absolute inset-0 bg-gradient-to-r from-blue-900/90 to-red-900/80 mix-blend-multiply"></div>
        </div>

        <div class="relative z-10 text-center px-4 mt-16 w-full">
            <h2 class="text-yellow-300 font-bold tracking-[0.3em] text-[10px] md:text-xs uppercase mb-2">Our Network</h2>
            <h1 class="font-heading text-3xl md:text-5xl font-black uppercase tracking-tight mb-4 drop-shadow-lg">
                External Linkages
            </h1>
            <p class="text-xs md:text-base text-gray-100 font-light max-w-xl mx-auto italic mb-8 px-4">
                Building bridges with government, NGOs, and civil society to amplify our impact.
            </p>

            {{-- Dynamic Stats (Responsive Grid) --}}
            <div class="inline-grid grid-cols-3 gap-4 md:gap-8 bg-white/10 backdrop-blur-md px-4 md:px-8 py-4 rounded-2xl border border-white/20 divide-x divide-white/20">
                <div class="text-center px-2">
                    <span class="block text-xl md:text-2xl font-black text-white">{{ $this->stats['active_count'] }}</span>
                    <span class="text-[8px] md:text-[10px] uppercase tracking-wider text-gray-200 block mt-1">Partners</span>
                </div>
                <div class="text-center px-2">
                    <span class="block text-xl md:text-2xl font-black text-yellow-400">{{ $this->stats['moa_count'] }}</span>
                    <span class="text-[8px] md:text-[10px] uppercase tracking-wider text-gray-200 block mt-1">MOAs</span>
                </div>
                <div class="text-center px-2">
                    <span class="block text-xl md:text-2xl font-black text-green-400">{{ $this->stats['intl_count'] }}</span>
                    <span class="text-[8px] md:text-[10px] uppercase tracking-wider text-gray-200 block mt-1">Global</span>
                </div>
            </div>
        </div>
    </header>

    {{-- 3. MAIN CONTENT --}}
    <div class="relative min-h-screen px-4 md:px-6 pb-24 mt-8 md:mt-12 max-w-[1800px] w-full mx-auto grid grid-cols-1 lg:grid-cols-12 gap-8 md:gap-12">
        
        {{-- LEFT: PARTNER DIRECTORY (8 Cols) --}}
        <main class="lg:col-span-8 order-1">
            <div class="flex flex-col md:flex-row md:items-center justify-between mb-6 md:mb-8 gap-4">
                <h3 class="font-heading font-bold text-xl md:text-2xl text-gray-900">Partner Directory</h3>
                
                <div class="flex items-center gap-3 w-full md:w-auto justify-between md:justify-end">
                    @auth
                    <a href="{{ route('linkages.create') }}" 
                    class="inline-flex items-center gap-2 px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white text-[10px] font-bold uppercase tracking-wider rounded-lg transition shadow-md hover:shadow-lg transform hover:-translate-y-0.5 whitespace-nowrap">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        Add
                    </a>
                    @endauth

                    {{-- Dynamic Filter Tabs (Scrollable on mobile) --}}
                    <div class="flex gap-2 overflow-x-auto pb-2 md:pb-0 scrollbar-hide mask-fade-right">
                        <button wire:click="setCategory('All')"
                                class="whitespace-nowrap px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider transition-all border shrink-0
                                {{ $category === 'All' 
                                    ? 'bg-red-600 text-white border-red-600' 
                                    : 'bg-white text-gray-500 border-gray-200 hover:border-red-400 hover:text-red-500' }}">
                            All
                        </button>

                        @foreach($this->types as $type)
                        <button wire:click="setCategory('{{ $type->name }}')"
                                class="whitespace-nowrap px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider transition-all border shrink-0
                                {{ $category === $type->name 
                                    ? 'bg-red-600 text-white border-red-600' 
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
                <div class="group bg-white rounded-2xl p-5 md:p-6 shadow-sm border border-gray-100 hover:shadow-lg hover:-translate-y-1 transition duration-300 relative overflow-hidden">
                    
                    {{-- Top Color Line --}}
                    <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-red-500 to-yellow-500 transform scale-x-0 group-hover:scale-x-100 transition duration-500 origin-left"></div>

                    <div class="flex items-start gap-4">
                        {{-- Logo --}}
                        <div class="w-14 h-14 md:w-16 md:h-16 rounded-xl bg-gray-50 p-2 border border-gray-100 shrink-0 flex items-center justify-center">
                            @if($partner->logo_path)
                                <img src="{{ asset('storage/' . $partner->logo_path) }}" class="w-full h-full object-contain mix-blend-multiply">
                            @else
                                <span class="text-[10px] font-bold text-gray-300 text-center leading-tight">{{ $partner->acronym ?? 'LOGO' }}</span>
                            @endif
                        </div>
                        
                        {{-- Info --}}
                        <div class="flex-grow min-w-0"> {{-- min-w-0 ensures truncation works in flex child --}}
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
                <div class="col-span-1 md:col-span-2 text-center py-12 text-gray-400 bg-white rounded-2xl border border-dashed border-gray-200">
                    <p class="text-sm">No partners found in this category.</p>
                </div>
                @endforelse
            </div>
        </main>

        {{-- RIGHT: ENGAGEMENTS TIMELINE (4 Cols) --}}
        {{-- Order-2 on mobile ensures it appears below partners --}}
        <aside class="lg:col-span-4 order-2">
            <div class="bg-white rounded-3xl p-6 shadow-lg border border-gray-100 lg:sticky lg:top-24">
                <h3 class="font-heading font-bold text-lg md:text-xl text-gray-900 mb-6 flex items-center gap-2">
                    <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Recent Engagements
                </h3>

                <div class="relative border-l-2 border-gray-200 ml-3 space-y-8 pl-6 pb-2">
                    @forelse($this->engagements as $activity)
                    <div class="relative group">
                        {{-- Timeline Dot --}}
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
    <footer class="mt-12 md:mt-20 border-t border-gray-200 py-8 px-6 text-center text-[10px] md:text-xs text-gray-500 bg-white">
        &copy; {{ date('Y') }} BU MADYA. All Rights Reserved.
    </footer>
</div>