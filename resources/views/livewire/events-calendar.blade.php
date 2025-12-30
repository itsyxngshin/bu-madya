<div x-data="{ 
        showModal: false, 
        activeDate: '', 
        activeEvents: [],
        openDay(date, events) {
            this.activeDate = date;
            this.activeEvents = events;
            if (events.length > 0) {
                this.showModal = true;
            }
        }
     }"
     class="bg-white rounded-3xl border border-gray-200 shadow-sm overflow-hidden font-sans relative">
    
    {{-- 1. HEADER --}}
    <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between bg-stone-50">
        <div>
            <h2 class="text-xl font-black text-gray-900 uppercase tracking-tight">
                {{ $monthName }} <span class="text-red-600">{{ $year }}</span>
            </h2>
        </div>

        <div class="flex items-center bg-white rounded-lg border border-gray-200 shadow-sm">
            <button wire:click="previousMonth" class="px-3 py-1 hover:bg-gray-100 text-gray-500 border-r border-gray-200 transition">&larr;</button>
            <button wire:click="nextMonth" class="px-3 py-1 hover:bg-gray-100 text-gray-500 transition">&rarr;</button>
        </div>
    </div>

    {{-- 2. CALENDAR GRID --}}
    <div class="w-full">
        
        {{-- Days Header --}}
        <div style="display: grid; grid-template-columns: repeat(7, 1fr);" class="border-b border-gray-200 bg-gray-50 text-center">
            @foreach(['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'] as $day)
                <div class="py-2 text-[8px] md:text-[10px] font-bold text-gray-400 uppercase tracking-widest">
                    {{ $day }}
                </div>
            @endforeach
        </div>

        {{-- Days Grid --}}
        <div style="display: grid; grid-template-columns: repeat(7, 1fr);" class="bg-gray-200 gap-px border-b border-gray-200">
            
            {{-- Empty Cells (Previous Month) --}}
            @for($i = 0; $i < $startDayOfWeek; $i++)
                <div class="bg-stone-50 min-h-[60px] md:min-h-[100px]"></div>
            @endfor

            {{-- Actual Days --}}
            @for($day = 1; $day <= $daysInMonth; $day++)
                @php
                    $dayEvents = $events[$day] ?? [];
                    $hasEvents = count($dayEvents) > 0;
                    $isToday = $day == now()->day && $currentMonth == now()->month && $currentYear == now()->year;
                @endphp

                {{-- DAY CELL --}}
                <div @click="openDay('{{ $monthName }} {{ $day }}', {{ json_encode($dayEvents) }})"
                     class="bg-white min-h-[60px] md:min-h-[100px] p-1 md:p-2 relative hover:bg-red-50 transition group flex flex-col gap-1 cursor-pointer">
                    
                    {{-- Date Number --}}
                    <div class="flex justify-between items-start">
                        <span class="text-[10px] md:text-xs font-bold {{ $isToday ? 'bg-red-600 text-white w-5 h-5 md:w-6 md:h-6 rounded-full flex items-center justify-center shadow-md' : 'text-gray-700' }}">
                            {{ $day }}
                        </span>

                        {{-- MOBILE INDICATOR (Red Dot) --}}
                        @if($hasEvents)
                            <div class="md:hidden w-1.5 h-1.5 rounded-full bg-red-500 mt-1 mr-1 animate-pulse"></div>
                        @endif
                    </div>

                    {{-- DESKTOP: Event List (Hidden on Mobile) --}}
                    <div class="hidden md:block flex-1 mt-1">
                        @foreach($dayEvents as $event)
                            <a href="{{ route('projects.show', $event['slug']) }}" 
                               @click.stop {{-- Prevent opening modal when clicking specific link on desktop --}}
                               class="block px-2 py-1 mb-1 rounded bg-yellow-50 border-l-2 border-yellow-400 text-[9px] font-bold text-gray-800 truncate hover:bg-yellow-100 transition shadow-sm"
                               title="{{ $event['title'] }}">
                                {{ $event['title'] }}
                            </a>
                        @endforeach
                    </div>

                    {{-- Add Button (Desktop Hover Only) --}}
                    @if(Auth::check() && in_array(Auth::user()->role_id, [1, 2]))
                        <a href="{{ route('projects.create') }}?date={{ $currentYear }}-{{ $currentMonth }}-{{ $day }}" 
                           @click.stop
                           class="absolute bottom-1 right-1 md:top-2 md:right-2 p-1 bg-gray-100 text-gray-500 rounded hover:bg-red-600 hover:text-white transition opacity-0 group-hover:opacity-100">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        </a>
                    @endif
                </div>
            @endfor

            {{-- Empty Cells (Next Month Padding) --}}
            @php
                $totalCells = $startDayOfWeek + $daysInMonth;
                $remainingCells = 7 - ($totalCells % 7);
                if($remainingCells == 7) $remainingCells = 0;
            @endphp
            @for($i = 0; $i < $remainingCells; $i++)
                <div class="bg-stone-50 min-h-[60px] md:min-h-[100px]"></div>
            @endfor

        </div>
    </div>

    {{-- 3. MOBILE MODAL (Alpine.js) --}}
    <div x-show="showModal" 
         style="display: none;"
         class="absolute inset-0 z-50 flex items-end sm:items-center justify-center p-4 bg-gray-900/20 backdrop-blur-sm"
         x-transition.opacity>
        
        <div @click.away="showModal = false"
             class="bg-white w-full max-w-sm rounded-2xl shadow-2xl overflow-hidden transform transition-all"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="translate-y-full opacity-0"
             x-transition:enter-end="translate-y-0 opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="translate-y-0 opacity-100"
             x-transition:leave-end="translate-y-full opacity-0">
            
            {{-- Modal Header --}}
            <div class="bg-red-600 px-4 py-3 flex justify-between items-center text-white">
                <h3 class="font-bold text-sm uppercase tracking-wider" x-text="activeDate"></h3>
                <button @click="showModal = false" class="text-white/80 hover:text-white">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            {{-- Modal Content (Event List) --}}
            <div class="p-4 max-h-[300px] overflow-y-auto">
                <template x-for="event in activeEvents" :key="event.id">
                    <a :href="'/projects/' + event.slug" class="block mb-3 p-3 bg-stone-50 rounded-xl border border-stone-100 hover:border-red-200 transition group">
                        <h4 class="font-bold text-gray-900 text-sm group-hover:text-red-600" x-text="event.title"></h4>
                        <div class="flex items-center gap-2 mt-1">
                            <span class="w-2 h-2 rounded-full" 
                                  :class="event.status === 'Completed' ? 'bg-green-500' : (event.status === 'Ongoing' ? 'bg-red-500' : 'bg-yellow-400')"></span>
                            <span class="text-xs text-gray-500 uppercase font-bold" x-text="event.status"></span>
                        </div>
                    </a>
                </template>
                
                {{-- Empty State --}}
                <div x-show="activeEvents.length === 0" class="text-center py-4 text-gray-400 text-xs">
                    No events scheduled for this day.
                </div>
            </div>
            
            {{-- Modal Footer --}}
            <div class="bg-gray-50 px-4 py-3 border-t border-gray-100 text-center">
                <button @click="showModal = false" class="text-xs font-bold text-gray-500 hover:text-gray-800 uppercase">Close</button>
            </div>
        </div>
    </div>

</div>