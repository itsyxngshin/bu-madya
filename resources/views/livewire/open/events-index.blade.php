<div class="min-h-screen bg-stone-50 py-12 md:py-20">
    <div class="max-w-7xl mx-auto px-6">
        
        {{-- 1. HEADER --}}
        <div class="text-center max-w-3xl mx-auto mb-12">
            <h1 class="font-heading font-black text-4xl md:text-5xl text-gray-900 mb-4">
                Events & <span class="text-red-600">Campaigns</span>
            </h1>
            <p class="text-lg text-gray-500">
                Join the movement. Participate in our upcoming advocacies, contests, and gatherings.
            </p>
        </div>

        {{-- 2. CONTROLS (Search & Filter) --}}
        <div class="flex flex-col md:flex-row justify-between items-center gap-4 mb-10 sticky top-20 z-30 bg-stone-50/95 backdrop-blur py-4">
            
            {{-- Filter Tabs --}}
            <div class="bg-white p-1 rounded-xl shadow-sm border border-gray-200 flex">
                @foreach(['upcoming' => 'Upcoming', 'past' => 'Past Events', 'all' => 'All'] as $key => $label)
                    <button wire:click="$set('filter', '{{ $key }}')" 
                            class="px-4 py-2 text-xs font-bold uppercase tracking-wider rounded-lg transition-all
                            {{ $filter === $key ? 'bg-gray-900 text-white shadow' : 'text-gray-500 hover:bg-gray-50' }}">
                        {{ $label }}
                    </button>
                @endforeach
            </div>

            {{-- Search Bar --}}
            <div class="relative w-full md:w-72">
                <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search events..." 
                       class="w-full pl-10 pr-4 py-2.5 rounded-xl border-gray-200 text-sm focus:ring-red-500 focus:border-red-500 shadow-sm">
                <svg class="w-4 h-4 text-gray-400 absolute left-3 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>
        </div>

        {{-- 3. LOADING INDICATOR --}}
        <div wire:loading class="w-full text-center py-12">
            <svg class="animate-spin h-8 w-8 text-red-600 mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
        </div>

        {{-- 4. EVENTS GRID --}}
        <div wire:loading.remove>
            @if($events->count() > 0)
                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($events as $event)
                        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden group hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex flex-col h-full">
                            
                            {{-- Image Thumb --}}
                            <div class="relative h-56 overflow-hidden bg-gray-200">
                                <a href="{{ route('events.show', $event->slug) }}">
                                    @if($event->cover_image)
                                        <img src="{{ asset('storage/'.$event->cover_image) }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-700">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-gray-400 bg-gray-100">
                                            <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        </div>
                                    @endif
                                </a>

                                {{-- Status Badge --}}
                                <div class="absolute top-4 right-4">
                                    @if($event->isOpen())
                                        <span class="px-3 py-1 bg-green-500 text-white text-[10px] font-bold uppercase tracking-widest rounded-full shadow-md">
                                            Open
                                        </span>
                                    @else
                                        <span class="px-3 py-1 bg-gray-800 text-white text-[10px] font-bold uppercase tracking-widest rounded-full shadow-md">
                                            Closed
                                        </span>
                                    @endif
                                </div>
                            </div>

                            {{-- Content --}}
                            <div class="p-6 flex-1 flex flex-col">
                                <div class="flex items-center gap-2 mb-3">
                                    <span class="w-2 h-2 rounded-full bg-red-600"></span>
                                    <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">
                                        {{ $event->start_date ? $event->start_date->format('M d, Y') : 'TBA' }}
                                    </span>
                                </div>

                                <a href="{{ route('events.show', $event->slug) }}" class="block mb-3">
                                    <h3 class="font-heading font-black text-xl text-gray-900 leading-tight group-hover:text-red-600 transition">
                                        {{ $event->title }}
                                    </h3>
                                </a>

                                <div class="text-sm text-gray-500 line-clamp-3 mb-6 flex-1">
                                    {{ Str::limit(strip_tags($event->description), 120) }}
                                </div>

                                <a href="{{ route('events.show', $event->slug) }}" class="w-full py-3 rounded-xl border-2 border-gray-100 text-center text-xs font-bold uppercase tracking-widest text-gray-600 group-hover:border-red-600 group-hover:bg-red-600 group-hover:text-white transition-all">
                                    View Details
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Pagination --}}
                <div class="mt-12">
                    {{ $events->links() }}
                </div>
            @else
                <div class="text-center py-20 bg-white rounded-3xl border border-dashed border-gray-200">
                    <div class="inline-flex p-4 bg-gray-50 rounded-full text-gray-400 mb-4">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900">No events found</h3>
                    <p class="text-gray-500 text-sm">Try adjusting your filters or search.</p>
                </div>
            @endif
        </div>
    </div>
</div>