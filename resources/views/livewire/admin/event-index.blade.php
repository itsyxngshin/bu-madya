<div class="min-h-screen bg-stone-50 font-sans text-gray-900 overflow-x-hidden relative">

    {{-- SEO --}}
    @section('meta_title', 'Events & Campaigns')
    @section('meta_description', 'Join the movement. Participate in upcoming advocacies, contests, and gatherings.')

    {{-- 1. ATMOSPHERE --}}
    <div class="fixed inset-0 z-0 overflow-hidden pointer-events-none">
        <div class="absolute top-0 left-0 w-full h-full bg-gray-50/80"></div>
        <div class="absolute top-[-10%] right-[-10%] w-[500px] h-[500px] bg-red-200 rounded-full mix-blend-multiply filter blur-3xl opacity-40 animate-blob"></div>
        <div class="absolute top-[20%] left-[-10%] w-[500px] h-[500px] bg-yellow-200 rounded-full mix-blend-multiply filter blur-3xl opacity-40 animate-blob animation-delay-2000"></div>
        <div class="absolute bottom-[-10%] right-[20%] w-[500px] h-[500px] bg-green-200 rounded-full mix-blend-multiply filter blur-3xl opacity-40 animate-blob animation-delay-4000"></div>
    </div>

    {{-- 2. HERO HEADER --}}
    <header class="relative h-[350px] flex items-center justify-center text-white overflow-hidden rounded-3xl shadow-xl mx-6 -mt-20 z-10">
        <div class="absolute inset-0 z-0">
            <img src="https://images.unsplash.com/photo-1523580494863-6f3031224c94?q=80&w=2070" class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-r from-red-900/90 to-yellow-700/80 mix-blend-multiply"></div>
        </div>

        <div class="relative z-10 text-center px-4 mt-16">
            <h2 class="text-yellow-300 font-bold tracking-[0.3em] text-xs uppercase mb-2 animate-fade-in-down">Opportunities & Activities</h2>
            <h1 class="font-heading text-3xl md:text-5xl font-black uppercase tracking-tight mb-4 drop-shadow-lg animate-fade-in-up leading-none">
                Events & Campaigns
            </h1>

            @auth
                @if(in_array(Auth::user()?->role?->role_name, ['administrator', 'director']))
                <div class="mt-6 animate-fade-in-up delay-100">
                    <a href="{{ route('admin.events.create') }}"
                       class="inline-flex items-center gap-2 px-6 py-3 bg-white text-red-600 rounded-full font-bold text-xs uppercase tracking-widest hover:bg-yellow-400 hover:text-red-900 transition shadow-lg transform hover:-translate-y-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        Create Event
                    </a>
                </div>
                @endif
            @endauth
        </div>
    </header>

    {{-- 3. MAIN CONTENT --}}
    <div class="relative min-h-screen px-6 pb-24 mt-12 max-w-[1800px] w-[95%] mx-auto">

        {{-- ========================================== --}}
        {{-- FEATURED / UPCOMING EVENT SPOTLIGHT        --}}
        {{-- ========================================== --}}
        @if($events->count() > 0)
            @php
                // Automatically grabs the first event in the collection for the spotlight
                // Because of our smart sorting, this is guaranteed to be the most pressing upcoming event!
                $featuredEvent = $events->first();
            @endphp
            <div class="mb-16 relative group cursor-pointer animate-fade-in-up delay-200">
                {{-- Dynamic Glowing Tricolor Backdrop --}}
                <div class="absolute -inset-1 bg-gradient-to-r from-red-500 via-yellow-400 to-green-500 rounded-[2.5rem] blur-lg opacity-25 group-hover:opacity-60 transition duration-1000 group-hover:duration-300"></div>

                <div class="relative bg-white rounded-[2rem] shadow-xl overflow-hidden flex flex-col lg:flex-row border border-gray-100">

                    {{-- Highlight Image Side --}}
                    <div class="lg:w-1/2 h-80 lg:h-[450px] relative overflow-hidden bg-gray-100">
                        <a href="{{ route('events.show', $featuredEvent->slug) }}" class="block w-full h-full">
                            @if($featuredEvent->cover_image)
                                <img src="{{ asset('storage/'.$featuredEvent->cover_image) }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-700">
                            @else
                                <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-gray-50 to-gray-200">
                                    <svg class="w-20 h-20 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                </div>
                            @endif
                        </a>

                        {{-- Floating Status Badge --}}
                        <div class="absolute top-6 left-6 z-20">
                            <span class="px-4 py-1.5 bg-yellow-400 text-yellow-900 text-[10px] font-black uppercase tracking-widest rounded-full shadow-md flex items-center gap-2">
                                <span class="w-1.5 h-1.5 rounded-full bg-yellow-900 animate-pulse"></span>
                                Featured Event
                            </span>
                        </div>
                    </div>

                    {{-- Highlight Content Side --}}
                    <div class="lg:w-1/2 p-8 md:p-12 flex flex-col justify-center">
                        <div class="flex items-center gap-3 mb-4">
                            <span class="px-3 py-1 bg-red-50 text-red-600 text-[10px] font-bold uppercase tracking-widest rounded-lg shadow-sm border border-red-100">
                                {{ $featuredEvent->start_date ? $featuredEvent->start_date->format('M d, Y') : 'TBA' }}
                            </span>
                            @if($featuredEvent->isOpen())
                                <span class="px-3 py-1 bg-green-50 text-green-600 text-[10px] font-bold uppercase tracking-widest rounded-lg shadow-sm border border-green-100">Registration Open</span>
                            @else
                                <span class="px-3 py-1 bg-gray-100 text-gray-600 text-[10px] font-bold uppercase tracking-widest rounded-lg shadow-sm border border-gray-200">Event Ended</span>
                            @endif
                        </div>

                        <a href="{{ route('events.show', $featuredEvent->slug) }}" class="block mb-4">
                            <h3 class="font-heading font-black text-3xl md:text-4xl text-gray-900 leading-tight group-hover:text-red-600 transition-colors">
                                {{ $featuredEvent->title }}
                            </h3>
                        </a>

                        <div class="text-base text-gray-500 line-clamp-3 mb-8 leading-relaxed">
                            {{ Str::limit(strip_tags($featuredEvent->description), 200) }}
                        </div>

                        <div class="mt-auto">
                            <a href="{{ route('events.show', $featuredEvent->slug) }}" class="inline-flex items-center justify-center gap-2 px-8 py-4 w-full md:w-auto bg-gray-900 text-white rounded-xl font-bold text-xs uppercase tracking-widest hover:bg-red-600 transition-all duration-300 shadow-md">
                                View Event Details
                                <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        {{-- ========================================== --}}
                {{-- SEARCH & MODERN DROPDOWN FILTER            --}}
                {{-- ========================================== --}}
                <div class="relative z-10 flex flex-col md:flex-row justify-between items-center mb-12 gap-4">

                    {{-- Modern Dropdown Filter --}}
                    <div class="w-full md:w-auto relative" x-data="{ open: false, current: '{{ $filter === 'upcoming' ? 'Upcoming Events' : ($filter === 'past' ? 'Past Events' : 'All Events') }}' }">
                        <button @click="open = !open" @click.outside="open = false" type="button"
                                class="w-full md:w-56 flex items-center justify-between px-6 py-3 bg-red-600 text-white rounded-full font-bold text-xs uppercase tracking-widest shadow-md hover:bg-red-700 hover:shadow-lg transition-all">
                            <span class="flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                                <span x-text="current"></span>
                            </span>
                            <svg class="w-4 h-4 transition-transform duration-300" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>

                        {{-- Dropdown Menu --}}
                        <div x-show="open" x-cloak
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 scale-95 translate-y-[-10px]"
                             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                             x-transition:leave="transition ease-in duration-150"
                             x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                             x-transition:leave-end="opacity-0 scale-95 translate-y-[-10px]"
                             class="absolute top-full left-0 mt-3 w-full md:w-56 bg-white border border-gray-100 rounded-2xl shadow-xl overflow-hidden z-50">

                            <div class="p-1.5 space-y-1">
                                <button wire:click="$set('filter', 'all')" @click="current = 'All Events'; open = false"
                                        class="w-full text-left px-4 py-3 rounded-xl text-xs font-bold uppercase tracking-widest transition-colors flex items-center justify-between {{ $filter === 'all' ? 'bg-red-50 text-red-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                                    <span>All Events</span>
                                    @if($filter === 'all') <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> @endif
                                </button>

                                <div class="h-px bg-gray-100 mx-2"></div>

                                <button wire:click="$set('filter', 'upcoming')" @click="current = 'Upcoming Events'; open = false"
                                        class="w-full text-left px-4 py-3 rounded-xl text-xs font-bold uppercase tracking-widest transition-colors flex items-center justify-between {{ $filter === 'upcoming' ? 'bg-red-50 text-red-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                                    <span>Upcoming Events</span>
                                    @if($filter === 'upcoming') <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> @endif
                                </button>

                                <button wire:click="$set('filter', 'past')" @click="current = 'Past Events'; open = false"
                                        class="w-full text-left px-4 py-3 rounded-xl text-xs font-bold uppercase tracking-widest transition-colors flex items-center justify-between {{ $filter === 'past' ? 'bg-red-50 text-red-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                                    <span>Past Events</span>
                                    @if($filter === 'past') <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> @endif
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- Search Bar --}}
                    <div class="relative w-full md:w-80">
                        <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search events..."
                               class="w-full pl-11 pr-4 py-3 rounded-full border border-gray-200 bg-white shadow-sm focus:border-red-500 focus:ring-2 focus:ring-red-500/20 text-sm placeholder-gray-400 transition-all font-medium">
                        <svg class="absolute left-4 top-3.5 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                </div>

        {{-- LOADING SPINNER --}}
        <div wire:loading class="w-full text-center py-12">
            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white shadow-md">
                <svg class="animate-spin h-5 w-5 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                <span class="text-xs font-bold text-gray-500 uppercase tracking-widest">Loading...</span>
            </div>
        </div>

        {{-- ========================================== --}}
        {{-- STANDARD EVENTS GRID                       --}}
        {{-- ========================================== --}}
        <div wire:loading.remove>
            @if($events->count() > 0)
                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($events as $index => $event)
                        {{-- Skip the first event in the grid IF we are currently viewing the default 'Upcoming' or 'All' filter to avoid duplication --}}
                        @if($index === 0 && ($filter === 'upcoming' || $filter === 'all' || empty($search)))
                            @continue
                        @endif

                        <article class="group bg-white rounded-3xl overflow-hidden shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex flex-col relative h-full border border-gray-100">

                            {{-- Image Thumbnail --}}
                            <div class="h-56 overflow-hidden relative bg-gray-100">
                                <a href="{{ route('events.show', $event->slug) }}">
                                    @if($event->cover_image)
                                        <img src="{{ asset('storage/'.$event->cover_image) }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                                    @else
                                        <div class="w-full h-full flex flex-col items-center justify-center text-gray-300 bg-gray-50">
                                            <svg class="w-12 h-12 mb-2 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        </div>
                                    @endif
                                </a>

                                {{-- Status Badge --}}
                                <div class="absolute top-4 right-4 z-20">
                                    @if($event->isOpen())
                                        <span class="px-3 py-1 bg-green-500/90 backdrop-blur text-white text-[9px] font-black uppercase tracking-widest rounded-full shadow-sm">
                                            Open
                                        </span>
                                    @else
                                        <span class="px-3 py-1 bg-gray-800/90 backdrop-blur text-white text-[9px] font-black uppercase tracking-widest rounded-full shadow-sm">
                                            Closed
                                        </span>
                                    @endif
                                </div>

                                {{-- Date Badge --}}
                                <div class="absolute bottom-4 left-4 z-20">
                                    <span class="px-3 py-1.5 bg-white/95 backdrop-blur text-gray-900 text-[9px] font-bold uppercase tracking-widest rounded-lg shadow-sm flex items-center gap-1.5">
                                        <svg class="w-3 h-3 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        {{ $event->start_date ? $event->start_date->format('M d, Y') : 'TBA' }}
                                    </span>
                                </div>
                            </div>

                            {{-- Content Body --}}
                            <div class="p-6 md:p-8 flex flex-col flex-grow">
                                <a href="{{ route('events.show', $event->slug) }}" class="block mb-3">
                                    <h3 class="font-heading font-black text-xl text-gray-900 leading-tight group-hover:text-red-600 transition-colors">
                                        {{ $event->title }}
                                    </h3>
                                </a>

                                <div class="text-sm text-gray-500 line-clamp-3 mb-6 flex-grow leading-relaxed">
                                    {{ Str::limit(strip_tags($event->description), 120) }}
                                </div>

                                <div class="pt-5 border-t border-gray-100 mt-auto">
                                    <a href="{{ route('events.show', $event->slug) }}" class="w-full flex items-center justify-center gap-2 py-3 rounded-xl bg-gray-50 text-gray-600 text-[10px] font-bold uppercase tracking-widest group-hover:bg-red-600 group-hover:text-white transition-colors duration-300">
                                        View Details
                                        <svg class="w-3.5 h-3.5 opacity-0 -ml-4 group-hover:opacity-100 group-hover:ml-0 transition-all duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                                    </a>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>

                {{-- Pagination --}}
                <div class="mt-16">
                    {{ $events->links() }}
                </div>
            @else
                {{-- Empty State --}}
                <div class="flex flex-col items-center justify-center py-24 text-center bg-white rounded-[2rem] border border-dashed border-gray-200 shadow-sm">
                    <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center text-gray-300 mb-4">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    </div>
                    <h3 class="text-xl font-black text-gray-900">No events found</h3>
                    <p class="text-gray-500 text-sm mt-2 font-medium">Try adjusting your filters or search terms.</p>
                </div>
            @endif
        </div>
    </div>
</div>
