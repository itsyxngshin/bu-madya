<div class="min-h-screen bg-gray-50 font-sans pb-24">
    
    {{-- Hero Section --}}
    <div class="bg-gray-900 text-white pt-24 pb-16 px-6 text-center rounded-b-[3rem] shadow-xl relative overflow-hidden">
        {{-- Decorative blobs --}}
        <div class="absolute top-0 left-1/4 w-96 h-96 bg-red-600/20 rounded-full mix-blend-screen filter blur-3xl opacity-50 pointer-events-none"></div>
        <div class="absolute bottom-0 right-1/4 w-96 h-96 bg-orange-600/20 rounded-full mix-blend-screen filter blur-3xl opacity-50 pointer-events-none"></div>

        <div class="relative z-10 max-w-3xl mx-auto">
            <span class="inline-block px-3 py-1 bg-red-500/20 text-red-400 text-[10px] font-black uppercase tracking-widest rounded-full mb-4 border border-red-500/30">BU MADYA Community</span>
            <h1 class="text-4xl md:text-6xl font-black tracking-tight leading-tight mb-4">Discover Events</h1>
            <p class="text-gray-400 text-sm md:text-base max-w-xl mx-auto">Find and join upcoming seminars, workshops, and volunteer drives hosted by registered student organizations and CSO partners.</p>
        </div>
    </div>

    {{-- Main Content Container --}}
    <div class="max-w-7xl mx-auto px-6 -mt-8 relative z-20">
        
        {{-- Search & Filter Bar --}}
        <div class="bg-white p-4 rounded-2xl shadow-lg border border-gray-100 mb-10 flex flex-col md:flex-row gap-4 items-center justify-between">
            <div class="w-full md:w-2/3 relative">
                <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search for events or organizers..." class="w-full pl-12 pr-4 py-3 rounded-xl border-gray-200 bg-gray-50 focus:bg-white text-sm focus:ring-red-500 font-medium transition">
                <svg class="w-5 h-5 text-gray-400 absolute left-4 top-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>
            <div class="w-full md:w-1/3">
                <select wire:model.live="filter" class="w-full rounded-xl border-gray-200 bg-gray-50 focus:bg-white text-sm py-3 focus:ring-red-500 font-bold text-gray-600 cursor-pointer transition">
                    <option value="upcoming">Upcoming Events</option>
                    <option value="past">Past Events</option>
                    <option value="all">All Events</option>
                </select>
            </div>
        </div>

        {{-- Event Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 md:gap-8">
            @forelse($events as $event)
                <a href="{{ route('events.show', $event->slug) }}" class="group bg-white rounded-[2rem] shadow-sm hover:shadow-2xl border border-gray-100 overflow-hidden flex flex-col transition-all duration-300 transform hover:-translate-y-2">
                    
                    {{-- Card Image Header --}}
                    <div class="relative w-full aspect-[4/3] bg-gray-100 overflow-hidden">
                        @if($event->cover_image)
                            <img src="{{ asset('storage/' . $event->cover_image) }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700 ease-in-out">
                        @else
                            <div class="w-full h-full flex flex-col items-center justify-center text-gray-300 bg-gradient-to-br from-gray-50 to-gray-200">
                                <svg class="w-12 h-12 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            </div>
                        @endif
                        
                        {{-- Status Badge (Open/Closed) --}}
                        <div class="absolute top-4 right-4">
                            @if($event->isOpen())
                                <span class="px-3 py-1 bg-white/90 backdrop-blur-sm text-green-700 text-[10px] font-black uppercase tracking-widest rounded-full shadow-sm">Open</span>
                            @else
                                <span class="px-3 py-1 bg-gray-900/90 backdrop-blur-sm text-white text-[10px] font-black uppercase tracking-widest rounded-full shadow-sm">Closed</span>
                            @endif
                        </div>
                    </div>

                    {{-- Card Body --}}
                    <div class="p-6 flex flex-col flex-1">
                        {{-- Date --}}
                        <div class="text-xs font-bold text-red-600 uppercase tracking-widest mb-2">
                            {{ $event->start_date ? $event->start_date->format('M d, Y • h:i A') : 'Date TBA' }}
                        </div>
                        
                        {{-- Title --}}
                        <h3 class="text-xl font-black text-gray-900 leading-tight mb-3 line-clamp-2 group-hover:text-red-600 transition-colors">
                            {{ $event->title }}
                        </h3>

                        {{-- Spacer to push organizer to the bottom --}}
                        <div class="flex-1"></div>

                        {{-- Organizer Footer --}}
                        <div class="mt-4 pt-4 border-t border-gray-100 flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center text-gray-500 font-bold text-xs uppercase shrink-0">
                                {{ substr($event->organizer->name ?? 'BU', 0, 2) }}
                            </div>
                            <div class="truncate">
                                <p class="text-[9px] text-gray-400 font-bold uppercase tracking-widest">Hosted By</p>
                                <p class="text-xs font-bold text-gray-700 truncate">{{ $event->organizer->name ?? 'BU MADYA' }}</p>
                            </div>
                        </div>
                    </div>
                </a>
            @empty
                <div class="col-span-full py-20 text-center flex flex-col items-center justify-center border-2 border-dashed border-gray-200 rounded-[2rem] bg-white/50">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center text-gray-400 mb-4">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    </div>
                    <h3 class="text-lg font-black text-gray-900">No events found</h3>
                    <p class="text-sm text-gray-500 mt-1 max-w-sm">We couldn't find any events matching your current filters. Try adjusting your search.</p>
                </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        <div class="mt-12">
            {{ $events->links() }}
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