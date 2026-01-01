<div class="min-h-screen bg-stone-50 font-sans text-gray-900 relative selection:bg-red-200 selection:text-red-900">

    {{-- SEO --}}
    @section('meta_title', 'Events & Campaigns')
    @section('meta_description', 'Join the movement. Participate in upcoming advocacies, contests, and gatherings.')

    {{-- 1. BACKGROUND TEXTURE (Global Grain) --}}
    <div class="fixed inset-0 z-0 pointer-events-none opacity-[0.03]" 
         style="background-image: url('data:image/svg+xml,%3Csvg viewBox=%220 0 200 200%22 xmlns=%22http://www.w3.org/2000/svg%22%3E%3Cfilter id=%22noiseFilter%22%3E%3CfeTurbulence type=%22fractalNoise%22 baseFrequency=%220.65%22 numOctaves=%223%22 stitchTiles=%22stitch%22/%3E%3C/filter%3E%3Crect width=%22100%25%22 height=%22100%25%22 filter=%22url(%23noiseFilter)%22/%3E%3C/svg%3E');">
    </div>

    {{-- 2. HERO SECTION (Static "Events Portal" Header) --}}
    <header class="relative w-full h-[50vh] min-h-[400px] bg-gray-900 overflow-hidden group">
        
        {{-- Abstract Background Pattern (Since we don't have a single event image here) --}}
        <div class="absolute inset-0 w-full h-full bg-gray-800 opacity-40" 
             style="background-image: radial-gradient(#444 1px, transparent 1px); background-size: 30px 30px;">
        </div>
        
        {{-- Gradient Overlay --}}
        <div class="absolute inset-0 bg-gradient-to-t from-stone-900 via-stone-900/60 to-transparent"></div>

        {{-- Hero Content --}}
        <div class="absolute bottom-0 left-0 w-full z-10 px-6 pb-12 pt-32 bg-gradient-to-t from-stone-900 to-transparent">
            <div class="max-w-7xl mx-auto text-center md:text-left">
                <span class="text-red-400 font-bold tracking-widest uppercase text-xs mb-2 block">
                    Opportunities & Activities
                </span>
                <h1 class="font-heading font-black text-4xl md:text-6xl lg:text-7xl text-white leading-none mb-4 drop-shadow-xl">
                    Events & <span class="text-transparent bg-clip-text bg-gradient-to-r from-red-500 to-orange-500">Campaigns</span>
                </h1>
                <p class="text-gray-300 text-lg max-w-2xl md:mr-auto md:ml-0 mx-auto">
                    Join the movement. Participate in our upcoming advocacies, contests, and gatherings.
                </p>
            </div>
        </div>
    </header>

    {{-- 3. CONTROLS (Search & Filter) - Sticky Bar --}}
    <div class="sticky top-20 z-40 bg-stone-50/80 backdrop-blur-md border-b border-gray-200/50 py-4">
        <div class="max-w-7xl mx-auto px-6 flex flex-col md:flex-row justify-between items-center gap-4">
            
            {{-- Filter Tabs --}}
            <div class="bg-white/80 p-1 rounded-xl shadow-sm border border-gray-200 flex">
                @foreach(['upcoming' => 'Upcoming', 'past' => 'Past Events', 'all' => 'All'] as $key => $label)
                    <button wire:click="$set('filter', '{{ $key }}')" 
                            class="px-4 py-2 text-xs font-bold uppercase tracking-wider rounded-lg transition-all
                            {{ $filter === $key ? 'bg-gray-900 text-white shadow-md transform scale-105' : 'text-gray-500 hover:bg-gray-100' }}">
                        {{ $label }}
                    </button>
                @endforeach
            </div>

            {{-- Search Bar --}}
            <div class="relative w-full md:w-72 group">
                <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search events..." 
                       class="w-full pl-10 pr-4 py-2.5 rounded-xl border-gray-200 bg-white/80 text-sm focus:ring-red-500 focus:border-red-500 shadow-sm group-hover:shadow-md transition">
                <svg class="w-4 h-4 text-gray-400 absolute left-3 top-3 group-hover:text-red-500 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>
        </div>
    </div>

    {{-- 4. LOADING INDICATOR --}}
    <div wire:loading class="w-full text-center py-12">
        <div class="inline-flex items-center gap-3 px-4 py-2 rounded-full bg-white shadow-lg border border-gray-100">
            <svg class="animate-spin h-5 w-5 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
            <span class="text-xs font-bold text-gray-500 uppercase tracking-widest">Loading...</span>
        </div>
    </div>

    {{-- 5. EVENTS GRID --}}
    <div class="max-w-7xl mx-auto px-6 py-12" wire:loading.remove>
        @if($events->count() > 0)
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($events as $event)
                    <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 overflow-hidden group hover:shadow-2xl hover:-translate-y-2 transition-all duration-500 flex flex-col h-full relative">
                        
                        {{-- Image Thumb --}}
                        <div class="relative h-64 overflow-hidden bg-gray-200">
                            <a href="{{ route('events.show', $event->slug) }}">
                                @if($event->cover_image)
                                    <img src="{{ asset('storage/'.$event->cover_image) }}" class="w-full h-full object-cover group-hover:scale-110 transition duration-700 ease-out">
                                @else
                                    <div class="w-full h-full flex flex-col items-center justify-center text-gray-400 bg-gray-100">
                                        <span class="text-3xl mb-2 opacity-50">📅</span>
                                        <span class="text-[10px] uppercase font-bold tracking-widest opacity-50">No Image</span>
                                    </div>
                                @endif
                            </a>

                            {{-- Gradient Overlay --}}
                            <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent opacity-60 group-hover:opacity-40 transition"></div>

                            {{-- Status Badge --}}
                            <div class="absolute top-4 right-4 z-10">
                                @if($event->isOpen())
                                    <span class="px-3 py-1 bg-green-500/90 backdrop-blur text-white text-[10px] font-black uppercase tracking-widest rounded-full shadow-lg border border-green-400/50">
                                        Open
                                    </span>
                                @else
                                    <span class="px-3 py-1 bg-gray-900/90 backdrop-blur text-white text-[10px] font-black uppercase tracking-widest rounded-full shadow-lg border border-gray-700">
                                        Closed
                                    </span>
                                @endif
                            </div>

                            {{-- Date Badge --}}
                            <div class="absolute bottom-4 left-4 z-10">
                                <div class="bg-white/90 backdrop-blur px-3 py-1.5 rounded-lg shadow-lg flex items-center gap-2">
                                    <span class="w-1.5 h-1.5 rounded-full bg-red-600 animate-pulse"></span>
                                    <span class="text-xs font-bold text-gray-900 uppercase tracking-wider">
                                        {{ $event->start_date ? $event->start_date->format('M d') : 'TBA' }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        {{-- Content --}}
                        <div class="p-8 flex-1 flex flex-col relative">
                            {{-- Decorative Blob --}}
                            <div class="absolute top-0 right-0 w-24 h-24 bg-red-50 rounded-full blur-2xl opacity-0 group-hover:opacity-100 transition duration-500 -mr-10 -mt-10"></div>

                            <a href="{{ route('events.show', $event->slug) }}" class="block mb-3 relative z-10">
                                <h3 class="font-heading font-black text-xl text-gray-900 leading-tight group-hover:text-red-600 transition-colors duration-300">
                                    {{ $event->title }}
                                </h3>
                            </a>

                            <div class="text-sm text-gray-500 line-clamp-3 mb-8 flex-1 leading-relaxed relative z-10">
                                {{ Str::limit(strip_tags($event->description), 120) }}
                            </div>

                            <a href="{{ route('events.show', $event->slug) }}" class="relative z-10 w-full py-4 rounded-xl border-2 border-gray-100 text-center text-xs font-bold uppercase tracking-widest text-gray-400 group-hover:border-red-600 group-hover:bg-red-600 group-hover:text-white transition-all duration-300 shadow-sm group-hover:shadow-red-500/30">
                                View Details
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-16">
                {{ $events->links() }}
            </div>
        @else
            <div class="text-center py-24 bg-white rounded-[2.5rem] border border-dashed border-gray-200 mx-auto max-w-2xl">
                <div class="inline-flex p-6 bg-gray-50 rounded-full text-gray-300 mb-6 animate-bounce">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                </div>
                <h3 class="text-2xl font-black text-gray-900 mb-2">No events found</h3>
                <p class="text-gray-500 text-sm max-w-md mx-auto leading-relaxed">
                    We couldn't find anything matching your search. Try adjusting your filters or check back later for new opportunities.
                </p>
            </div>
        @endif
    </div>

</div>