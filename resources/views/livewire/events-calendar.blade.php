<div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 p-6 md:p-8">
    
    {{-- CALENDAR HEADER --}}
    <div class="flex items-center justify-between mb-8">
        <div>
            <h2 class="font-heading font-black text-2xl md:text-3xl text-gray-900">
                {{ $monthName }} <span class="text-red-600">{{ $year }}</span>
            </h2>
            <p class="text-xs text-gray-400 font-bold uppercase tracking-widest mt-1">Project Implementation Schedule</p>
        </div>

        <div class="flex items-center gap-2">
            <button wire:click="previousMonth" class="p-2 rounded-full hover:bg-red-50 text-gray-400 hover:text-red-600 transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            </button>
            <button wire:click="nextMonth" class="p-2 rounded-full hover:bg-red-50 text-gray-400 hover:text-red-600 transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
            </button>
        </div>
    </div>

    {{-- CALENDAR GRID --}}
    <div class="grid grid-cols-7 gap-y-4 gap-x-2 md:gap-x-4">
        
        {{-- Weekday Headers --}}
        @foreach(['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'] as $day)
            <div class="text-center mb-2">
                <span class="text-[10px] md:text-xs font-black uppercase text-gray-400 tracking-widest">{{ $day }}</span>
            </div>
        @endforeach

        {{-- Padding for Empty Days (Start of Month) --}}
        @for($i = 0; $i < $startDayOfWeek; $i++)
            <div class="h-24 md:h-32 bg-gray-50/50 rounded-2xl"></div>
        @endfor

        {{-- Actual Days --}}
        @for($day = 1; $day <= $daysInMonth; $day++)
            @php
                $hasEvents = isset($events[$day]);
                $isToday = $day == now()->day && $currentMonth == now()->month && $currentYear == now()->year;
            @endphp

            <div class="relative h-24 md:h-32 bg-white rounded-2xl border {{ $isToday ? 'border-red-500 ring-4 ring-red-50' : 'border-gray-100' }} hover:shadow-md hover:border-red-200 transition p-2 md:p-3 flex flex-col group overflow-hidden">
                
                {{-- Date Number --}}
                <div class="flex justify-between items-start">
                    <span class="text-sm font-bold {{ $isToday ? 'text-red-600 bg-red-100 w-6 h-6 rounded-full flex items-center justify-center' : 'text-gray-700' }}">
                        {{ $day }}
                    </span>
                    
                    {{-- Dot indicator for mobile if too many events --}}
                    @if($hasEvents)
                        <span class="md:hidden w-2 h-2 rounded-full bg-yellow-400"></span>
                    @endif
                </div>

                {{-- Events List --}}
                <div class="mt-2 space-y-1 overflow-y-auto scrollbar-hide flex-1">
                    @if($hasEvents)
                        @foreach($events[$day] as $event)
                            <a href="{{ route('projects.show', $event->slug) }}" 
                               class="block text-[10px] font-bold bg-stone-100 text-stone-600 px-2 py-1 rounded-md border-l-2 border-yellow-400 hover:bg-red-50 hover:text-red-700 hover:border-red-500 transition truncate"
                               title="{{ $event->title }}">
                                {{ $event->title }}
                            </a>
                        @endforeach
                    @endif
                </div>

                {{-- Add Button (Only visible on hover for Admins) --}}
                @if(Auth::check() && Auth::user()->role_id === 1) 
                    <a href="{{ route('projects.create') }}?date={{ $currentYear }}-{{ $currentMonth }}-{{ $day }}" 
                       class="absolute bottom-2 right-2 opacity-0 group-hover:opacity-100 transition p-1 bg-gray-900 text-white rounded-lg hover:bg-red-600">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    </a>
                @endif
            </div>
        @endfor

    </div>
</div>