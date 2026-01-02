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

    {{-- 2. HERO HEADER (Newsroom Reference Style) --}}
    {{-- Fixed h-[350px], -mt-20 overlap, rounded-3xl, shadow-xl --}}
    <header class="relative h-[350px] flex items-center justify-center text-white overflow-hidden rounded-3xl shadow-xl mx-4 md:mx-6 -mt-20 z-10">
        
        {{-- Background Image & Gradient --}}
        <div class="absolute inset-0 z-0">
            <img src="https://images.unsplash.com/photo-1523580494863-6f3031224c94?q=80&w=2070" class="w-full h-full object-cover">
            {{-- Gradient: Red to Yellow Mix --}}
            <div class="absolute inset-0 bg-gradient-to-r from-red-900/90 to-yellow-700/80 mix-blend-multiply"></div>
        </div>
        
        {{-- Content (with mt-16 offset as requested) --}}
        <div class="relative z-10 text-center px-4 mt-16">
            <h2 class="text-yellow-300 font-bold tracking-[0.3em] text-xs uppercase mb-2 animate-fade-in-down">Opportunities & Activities</h2>
            <h1 class="font-heading text-3xl md:text-5xl font-black uppercase tracking-tight mb-4 drop-shadow-lg animate-fade-in-up leading-none">
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

    {{-- 3. MAIN CONTENT --}}
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
                                        <img src="{{ asset('storage/'.$event->cover_image) }}" class="w-full h-full object-cover group-hover:scale-110 transition duration-700">
                                    @else
                                        <div class="w-full h-full flex flex-col items-center justify-center text-gray-300 bg-gray-100">
                                            <span class="text-4xl mb-2 opacity-50">📅</span>
                                        </div>
                                    @endif
                                </a>
                                
                                {{-- Status Badge --}}
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

                                {{-- Date Badge --}}
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
</div>