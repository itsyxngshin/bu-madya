<div class="min-h-screen bg-stone-50 font-sans text-gray-900">

    {{-- SEO --}}
    @section('meta_title', 'Events & Campaigns')
    @section('meta_description', 'Join the movement. Participate in upcoming advocacies, contests, and gatherings.')

    <div class="fixed inset-0 z-0 overflow-hidden pointer-events-none">
        <div class="absolute top-0 left-0 w-full h-full bg-gray-50/80"></div>
        <div class="absolute top-[-10%] right-[-10%] w-[500px] h-[500px] bg-red-200 rounded-full mix-blend-multiply filter blur-3xl opacity-40 animate-blob"></div>
        <div class="absolute top-[20%] left-[-10%] w-[500px] h-[500px] bg-yellow-200 rounded-full mix-blend-multiply filter blur-3xl opacity-40 animate-blob animation-delay-2000"></div>
        <div class="absolute bottom-[-10%] right-[20%] w-[500px] h-[500px] bg-green-200 rounded-full mix-blend-multiply filter blur-3xl opacity-40 animate-blob animation-delay-4000"></div>
    </div>

    {{-- HERO HEADER (Newsroom Style) --}}
    <header class="relative h-[350px] flex items-center justify-center text-white overflow-hidden rounded-3xl shadow-xl mx-4 md:mx-6 mt-6 z-10">
        {{-- Background Image & Gradient --}}
        <div class="absolute inset-0 z-0">
            {{-- You can change this unsplash ID to something event-related --}}
            <img src="https://images.unsplash.com/photo-1523580494863-6f3031224c94?q=80&w=2070" class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-r from-red-900/90 to-yellow-700/80 mix-blend-multiply"></div>
        </div>
        

        {{-- Content --}}
        <div class="relative z-10 text-center px-4 mt-8">
            <h2 class="text-yellow-300 font-bold tracking-[0.3em] text-xs uppercase mb-2 animate-fade-in-down">Opportunities & Activities</h2>
            <h1 class="font-heading text-3xl md:text-5xl font-black uppercase tracking-tight mb-4 drop-shadow-lg animate-fade-in-up">
                Events & Campaigns
            </h1>
            
            {{-- Admin Create Button --}}
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

    {{-- MAIN CONTENT --}}
    <div class="relative min-h-screen px-6 pb-24 mt-12 max-w-[1800px] w-[95%] mx-auto">
        
        {{-- SEARCH & FILTER BAR --}}
        <div class="relative z-10 flex flex-col md:flex-row justify-between items-center mb-12 gap-4">
            
            {{-- Filter Pills --}}
            <div class="flex gap-2 overflow-x-auto pb-2 max-w-full no-scrollbar">
                @foreach(['upcoming' => 'Upcoming', 'past' => 'Past Events', 'all' => 'All Events'] as $key => $label)
                <button wire:click="$set('filter', '{{ $key }}')"
                        class="whitespace-nowrap px-6 py-2 rounded-full text-xs font-bold shadow-md transition-all uppercase tracking-wide
                               {{ $filter === $key ? 'bg-red-600 text-white transform scale-105' : 'bg-white text-gray-600 hover:bg-gray-50' }}">
                    {{ $label }}
                </button>
                @endforeach
            </div>

            {{-- Search Input --}}
            <div class="relative w-full md:w-80">
                <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search events..." 
                       class="w-full pl-10 pr-4 py-2.5 rounded-full border-none bg-white shadow-sm focus:ring-2 focus:ring-yellow-400 text-sm placeholder-gray-400">
                <svg class="absolute left-3.5 top-3 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>
        </div>

        {{-- LOADING SPINNER --}}
        <div wire:loading class="w-full text-center py-12">
            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white shadow-md">
                <svg class="animate-spin h-5 w-5 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                <span class="text-xs font-bold text-gray-500 uppercase tracking-widest">Loading...</span>
            </div>
        </div>

        {{-- EVENTS GRID --}}
        <div wire:loading.remove>
            @if($events->count() > 0)
                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($events as $event)
                        <article class="group bg-white rounded-3xl overflow-hidden shadow-lg hover:shadow-2xl hover:-translate-y-2 transition duration-300 flex flex-col relative h-full border border-gray-100">
                            
                            {{-- Image Thumbnail --}}
                            <div class="h-56 overflow-hidden relative bg-gray-200">
                                <a href="{{ route('events.show', $event->slug) }}">
                                    @if($event->cover_image)
                                        <img src="{{ asset(path: 'storage/'.$event->cover_image) }}" class="w-full h-full object-cover group-hover:scale-110 transition duration-700">
                                    @else
                                        <div class="w-full h-full flex flex-col items-center justify-center text-gray-300 bg-gray-100">
                                            <span class="text-4xl mb-2 opacity-50">📅</span>
                                        </div>
                                    @endif
                                </a>
                                
                                {{-- Status Badge (Floating Top Right) --}}
                                <div class="absolute top-4 right-4 z-20">
                                    @if($event->isOpen())
                                        <span class="px-3 py-1 bg-green-500/90 backdrop-blur text-white text-[10px] font-black uppercase tracking-widest rounded-full shadow-sm">
                                            Open
                                        </span>
                                    @else
                                        <span class="px-3 py-1 bg-gray-800/90 backdrop-blur text-white text-[10px] font-black uppercase tracking-widest rounded-full shadow-sm">
                                            Closed
                                        </span>
                                    @endif
                                </div>

                                {{-- Date Badge (Floating Bottom Left) --}}
                                <div class="absolute bottom-4 left-4 z-20">
                                    <span class="px-3 py-1 bg-white/90 backdrop-blur text-gray-900 text-[10px] font-bold uppercase tracking-widest rounded-lg shadow-sm flex items-center gap-2">
                                        <span class="w-1.5 h-1.5 rounded-full bg-red-500 animate-pulse"></span>
                                        {{ $event->start_date ? $event->start_date->format('M d, Y') : 'TBA' }}
                                    </span>
                                </div>
                            </div>

                            {{-- Content Body --}}
                            <div class="p-8 flex flex-col flex-grow">
                                <a href="{{ route('events.show', $event->slug) }}" class="block mb-3">
                                    <h3 class="font-heading font-black text-xl text-gray-900 leading-tight group-hover:text-red-600 transition">
                                        {{ $event->title }}
                                    </h3>
                                </a>

                                <div class="text-sm text-gray-500 line-clamp-3 mb-6 flex-grow leading-relaxed">
                                    {{ Str::limit(strip_tags($event->description), 120) }}
                                </div>

                                <div class="pt-6 border-t border-gray-100 mt-auto">
                                    <a href="{{ route('events.show', $event->slug) }}" class="w-full block text-center py-3 rounded-xl bg-gray-50 text-gray-600 text-xs font-bold uppercase tracking-widest group-hover:bg-red-600 group-hover:text-white transition-all duration-300">
                                        View Details
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
                <div class="flex flex-col items-center justify-center py-24 text-center">
                    <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center text-gray-300 mb-4">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900">No events found</h3>
                    <p class="text-gray-500 text-sm mt-2">Try adjusting your filters or search terms.</p>
                </div>
            @endif
        </div>
    </div>
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