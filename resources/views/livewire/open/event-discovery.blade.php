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
</div>