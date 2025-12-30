<div x-data="{ 
        showModal: false, 
        activeDate: '', 
        activeEvents: [],
        openDay(date, events) {
            // Check if mobile (width < 768px) AND events exist
            if (window.innerWidth < 768 && events.length > 0) {
                this.activeDate = date;
                this.activeEvents = events;
                this.showModal = true;
            }
        }
     }"
     class="bg-white rounded-3xl border border-gray-200 shadow-sm overflow-hidden font-sans relative">
    
    {{-- 1. HEADER --}}
    <div class="px-6 py-4 border-b border-gray-200 flex flex-col md:flex-row items-center justify-between bg-stone-50 gap-4">
        <div>
            <h2 class="text-xl font-black text-gray-900 uppercase tracking-tight text-center md:text-left">
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
        <div style="display: grid; grid-template-columns: repeat(7, 1fr);" class="border-b border-gray-200 bg-gray-50 text-center">
            @foreach(['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'] as $day)
                <div class="py-2 text-[10px] md:text-xs font-bold text-gray-400 uppercase tracking-widest">{{ $day }}</div>
            @endforeach
        </div>

        <div style="display: grid; grid-template-columns: repeat(7, 1fr);" class="bg-gray-200 gap-px border-b border-gray-200">
            @for($i = 0; $i < $startDayOfWeek; $i++)
                <div class="bg-stone-50 min-h-[70px] md:min-h-[120px]"></div>
            @endfor

            @for($day = 1; $day <= $daysInMonth; $day++)
                @php
                    $dayEvents = $events[$day] ?? [];
                    $isToday = $day == now()->day && $currentMonth == now()->month && $currentYear == now()->year;
                    $hasHoliday = collect($dayEvents)->contains('is_holiday', true);
                    $hasProject = collect($dayEvents)->contains('is_holiday', false);
                @endphp

                {{-- DAY CELL --}}
                {{-- FIX 1: Use Js::from() to prevent syntax errors with quotes --}}
                <div @click="openDay('{{ $monthName }} {{ $day }}', {{ \Illuminate\Support\Js::from($dayEvents) }})"
                     class="bg-white min-h-[70px] md:min-h-[120px] p-1 md:p-2 relative hover:bg-red-50 transition group flex flex-col gap-1 cursor-pointer md:cursor-default">
                    
                    <div class="flex justify-between items-start">
                        <span class="text-xs font-bold {{ $isToday ? 'bg-red-600 text-white w-6 h-6 rounded-full flex items-center justify-center shadow-md' : 'text-gray-700' }}">
                            {{ $day }}
                        </span>
                        
                        {{-- Mobile Dots --}}
                        <div class="flex gap-1 md:hidden">
                            @if($hasProject) <div class="w-1.5 h-1.5 rounded-full bg-yellow-400 animate-pulse"></div> @endif
                            @if($hasHoliday) <div class="w-1.5 h-1.5 rounded-full bg-blue-400"></div> @endif
                        </div>
                    </div>

                    {{-- Desktop List --}}
                    <div class="hidden md:block flex-1 w-full min-w-0 mt-1">
                        @foreach($dayEvents as $event)
                            <div wire:key="event-{{ $event['id'] }}">
                                @if($event['is_holiday'])
                                    <div class="block px-2 py-1 mb-1 rounded bg-blue-50 border-l-2 border-blue-500 text-[9px] font-bold text-blue-700 truncate cursor-help" title="{{ $event['title'] }}">
                                        <span class="mr-1 opacity-70">★</span> {{ $event['title'] }}
                                    </div>
                                @else
                                    <a href="{{ route('projects.show', $event['slug']) }}" class="block px-2 py-1 mb-1 rounded bg-yellow-50 border-l-2 border-yellow-400 text-[9px] font-bold text-gray-800 truncate hover:bg-yellow-100 transition shadow-sm" title="{{ $event['title'] }}">
                                        {{ $event['title'] }}
                                    </a>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @endfor

            @php $remainingCells = (7 - (($startDayOfWeek + $daysInMonth) % 7)) % 7; @endphp
            @for($i = 0; $i < $remainingCells; $i++)
                <div class="bg-stone-50 min-h-[70px] md:min-h-[120px]"></div>
            @endfor
        </div>
    </div>

    {{-- 3. MOBILE MODAL (Fixed with Teleport) --}}
    {{-- FIX 2: Use x-teleport="body" so the modal isn't trapped/hidden by the calendar card --}}
    <template x-teleport="body">
        <div x-show="showModal" 
             style="display: none;"
             class="fixed inset-0 z-[9999] flex items-end sm:items-center justify-center p-4 bg-gray-900/60 backdrop-blur-sm"
             x-transition.opacity>
            
            <div @click.away="showModal = false"
                 class="bg-white w-full max-w-sm rounded-2xl shadow-2xl overflow-hidden transform transition-all"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="translate-y-full opacity-0"
                 x-transition:enter-end="translate-y-0 opacity-100">
                
                <div class="bg-gray-900 px-4 py-3 flex justify-between items-center text-white">
                    <h3 class="font-bold text-sm uppercase tracking-wider" x-text="activeDate"></h3>
                    <button @click="showModal = false" class="text-white/80 hover:text-white">&times;</button>
                </div>

                <div class="p-4 max-h-[60vh] overflow-y-auto space-y-3 bg-stone-50">
                    <template x-for="event in activeEvents" :key="event.id">
                        <div class="w-full">
                            <div x-show="event.is_holiday" class="p-3 bg-blue-50 rounded-xl border border-blue-200 shadow-sm">
                                <h4 class="font-bold text-blue-900 text-sm" x-text="event.title"></h4>
                                <span class="text-[10px] font-bold text-blue-600 uppercase mt-1 block" x-text="event.status"></span>
                            </div>
                            <a x-show="!event.is_holiday" :href="'/projects/' + event.slug" class="block p-3 bg-white rounded-xl border border-gray-200 hover:border-red-400 hover:shadow-md transition">
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="w-2 h-2 rounded-full" :class="event.status === 'Completed' ? 'bg-green-500' : 'bg-yellow-400'"></span>
                                    <span class="text-[10px] font-bold uppercase tracking-wide text-gray-400">Project</span>
                                </div>
                                <h4 class="font-bold text-gray-900 text-sm" x-text="event.title"></h4>
                            </a>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </template>
</div>