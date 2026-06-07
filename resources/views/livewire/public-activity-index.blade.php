<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 lg:py-16 animate-fade-in-up">

    {{-- Header Section --}}
    <div class="text-center max-w-3xl mx-auto mb-12 sm:mb-16">
        <span class="text-red-600 font-black text-[10px] sm:text-xs uppercase tracking-widest bg-red-50 px-3 py-1.5 rounded-lg mb-4 inline-block shadow-sm">Impact & Engagements</span>
        <h1 class="text-3xl sm:text-4xl md:text-5xl font-black text-gray-900 tracking-tight leading-tight mb-4">
            Movement in <span class="text-transparent bg-clip-text bg-gradient-to-r from-red-600 to-orange-500">Action</span>
        </h1>
        <p class="text-sm sm:text-base text-gray-500 font-medium">Explore our latest activities, partnerships, and advocacy-driven initiatives contributing to sustainable development across the Bicol Region.</p>
    </div>

    {{-- Search Bar --}}
    <div class="max-w-md mx-auto mb-10">
        <div class="relative shadow-sm rounded-2xl overflow-hidden focus-within:ring-2 focus-within:ring-red-500 border border-gray-200">
            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>
            <input wire:model.live.debounce.300ms="search" type="text" class="block w-full pl-11 pr-4 py-3 border-none bg-white text-sm focus:ring-0 text-gray-900 placeholder-gray-400 font-medium" placeholder="Search events, campaigns, or partnerships...">
        </div>
    </div>

    {{-- Activities Grid --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8">
        @forelse($activities as $activity)
            <a href="{{ route('activities.show', $activity->slug) }}" class="group bg-white rounded-3xl border border-gray-100 shadow-sm hover:shadow-xl transition-all duration-300 flex flex-col h-full overflow-hidden hover:-translate-y-1">

                {{-- Highlight Photo Header --}}
                <div class="h-48 w-full bg-gray-100 relative overflow-hidden shrink-0">
                    @if(!empty($activity->highlight_photos) && count($activity->highlight_photos) > 0)
                        <img src="{{ asset('storage/' . $activity->highlight_photos[0]) }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    @else
                        <div class="absolute inset-0 bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center">
                            <svg class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        </div>
                    @endif

                    <div class="absolute top-4 left-4 bg-white/95 backdrop-blur-sm rounded-xl px-3 py-2 text-center border border-white/50 shadow-sm">
                        <span class="block text-[10px] font-black text-red-600 uppercase tracking-widest leading-none">{{ $activity->start_date->format('M') }}</span>
                        <span class="block text-xl font-black text-gray-900 leading-none mt-0.5">{{ $activity->start_date->format('d') }}</span>
                    </div>

                    <div class="absolute bottom-4 right-4 flex gap-2">
                        <span class="px-2.5 py-1 bg-white/95 backdrop-blur-sm text-[9px] font-black uppercase tracking-widest rounded-lg shadow-sm {{ $activity->status === 'completed' ? 'text-green-600' : ($activity->status === 'ongoing' ? 'text-orange-600' : 'text-blue-600') }}">
                            {{ $activity->status }}
                        </span>
                    </div>
                </div>

                {{-- Content Body --}}
                <div class="p-5 sm:p-6 flex flex-col flex-1">
                    <div class="flex justify-between items-start mb-3 gap-2">
                        <p class="text-[10px] font-bold text-gray-500 uppercase tracking-widest leading-tight line-clamp-1">{{ $activity->nature_of_activity }}</p>
                        @if($activity->sdg)
                            <span class="text-[9px] font-bold text-white px-2 py-0.5 rounded shadow-sm shrink-0 whitespace-nowrap" style="background-color: {{ $activity->sdg->color_hex ?? '#3b82f6' }}" title="{{ $activity->sdg->name }}">
                                SDG {{ $activity->sdg->goal_number }}
                            </span>
                        @endif
                    </div>

                    <h3 class="font-black text-lg text-gray-900 leading-tight mb-3 group-hover:text-red-600 transition-colors">{{ $activity->title }}</h3>

                    <p class="text-xs text-gray-500 line-clamp-2 mb-4">{{ $activity->description ?? 'No description provided.' }}</p>

                    <div class="mt-auto pt-4 border-t border-gray-100 flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            @php
                                $orgPhoto = $activity->user->profile_photo_url ?? 'https://ui-avatars.com/api/?name='.urlencode($activity->user->name).'&background=eff6ff&color=2563eb&bold=true';
                            @endphp
                            <img src="{{ $orgPhoto }}" onerror="this.onerror=null; this.src='https://ui-avatars.com/api/?name={{ urlencode($activity->user->name) }}&background=eff6ff&color=2563eb&bold=true';" class="w-6 h-6 rounded-full border border-gray-200">
                            <span class="text-[10px] font-bold text-gray-600 truncate max-w-[120px]">{{ $activity->user->name }}</span>
                        </div>

                        <span class="text-[10px] font-black uppercase tracking-widest text-red-600 group-hover:translate-x-1 transition-transform flex items-center gap-1">Read <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg></span>
                    </div>
                </div>
            </a>
        @empty
            <div class="col-span-full py-20 text-center border-2 border-dashed border-gray-200 rounded-[2rem] bg-gray-50">
                <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                <p class="text-sm font-bold text-gray-500 uppercase tracking-widest">No activities found.</p>
            </div>
        @endforelse
    </div>

    <div class="mt-10">
        {{ $activities->links() }}
    </div>

</div>
