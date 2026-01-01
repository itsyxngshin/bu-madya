<div x-data="{ 
        showModal: false, 
        activeDate: '', 
        activeEvents: [],
        openDay(date, events) {
            if (events.length > 0) {
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
        {{-- Days Header --}}
        {{-- FIX 1: Use minmax(0, 1fr) to prevent header blowouts --}}
        <div style="display: grid; grid-template-columns: repeat(7, minmax(0, 1fr));" class="border-b border-gray-200 bg-gray-50 text-center">
            @foreach(['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'] as $day)
                <div class="py-2 text-[10px] md:text-xs font-bold text-gray-400 uppercase tracking-widest">{{ $day }}</div>
            @endforeach
        </div>

        {{-- Days Grid --}}
        {{-- FIX 2: Use minmax(0, 1fr) to prevent content blowouts --}}
        <div style="display: grid; grid-template-columns: repeat(7, minmax(0, 1fr));" class="bg-gray-200 gap-px border-b border-gray-200">
            {{-- Padding Cells --}}
            @for($i = 0; $i < $startDayOfWeek; $i++)
                <div class="bg-stone-50 min-h-[70px] md:min-h-[120px]"></div>
            @endfor

            {{-- Actual Days --}}
            @for($day = 1; $day <= $daysInMonth; $day++)
                @php
                    $dayEvents = $events[$day] ?? [];
                    $isToday = $day == now()->day && $currentMonth == now()->month && $currentYear == now()->year;
                    $hasHoliday = collect($dayEvents)->contains('is_holiday', true);
                    $hasProject = collect($dayEvents)->contains('is_holiday', false);
                @endphp

                {{-- DAY CELL --}}
                <div @click="openDay('{{ $monthName }} {{ $day }}', {{ \Illuminate\Support\Js::from($dayEvents) }})"
                     class="bg-white min-h-[70px] md:min-h-[120px] p-1 md:p-2 relative hover:bg-red-50 transition group flex flex-col gap-1 cursor-pointer overflow-hidden">
                    
                    {{-- Date Number & Mobile Dots --}}
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

                    {{-- DESKTOP LIST --}}
                    <div class="hidden md:block flex-1 w-full min-w-0 mt-1 space-y-1">
                        @foreach($dayEvents as $event)
                            <div wire:key="event-{{ $event['id'] }}">
                                @if($event['is_holiday'])
                                    {{-- HOLIDAY: Added 'truncate' and 'max-w-full' --}}
                                    <div class="block w-full max-w-full px-2 py-1 rounded bg-blue-50 border-l-2 border-blue-500 text-[9px] font-bold text-blue-700 truncate" 
                                         title="{{ $event['title'] }}">
                                        <span class="mr-1 opacity-70">★</span> {{ $event['title'] }}
                                    </div>
                                @else
                                    {{-- PROJECT: Added 'truncate' and 'max-w-full' --}}
                                    <div class="block w-full max-w-full px-2 py-1 rounded bg-yellow-50 border-l-2 border-yellow-400 text-[9px] font-bold text-gray-800 truncate hover:bg-yellow-100 transition shadow-sm"
                                         title="{{ $event['title'] }}">
                                        {{ $event['title'] }}
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>

                    {{-- Quick Add Button --}}
                    @if(Auth::check() && in_array(Auth::user()->role_id, [1, 2]))
                        @php $targetDate = \Carbon\Carbon::create($currentYear, $currentMonth, $day)->format('Y-m-d'); @endphp
                        <a href="{{ route('projects.create', ['date' => $targetDate]) }}" 
                           @click.stop
                           class="absolute top-2 right-2 hidden md:group-hover:block p-1 bg-gray-100 text-gray-500 rounded hover:bg-red-600 hover:text-white transition z-10">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        </a>
                    @endif
                </div>
            @endfor

            {{-- Padding for end of month --}}
            @php $remainingCells = (7 - (($startDayOfWeek + $daysInMonth) % 7)) % 7; @endphp
            @for($i = 0; $i < $remainingCells; $i++)
                <div class="bg-stone-50 min-h-[70px] md:min-h-[120px]"></div>
            @endfor
        </div>
    </div>

    {{-- 3. UNIFIED MODAL --}}
    <template x-teleport="body">
        <div x-show="showModal" 
             style="display: none;"
             class="fixed inset-0 z-[300] flex items-center justify-center p-4 bg-gray-900/60 backdrop-blur-sm"
             x-transition.opacity>
            
            <div @click.away="showModal = false"
                 class="bg-white w-full max-w-md rounded-2xl shadow-2xl overflow-hidden transform transition-all"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="scale-95 opacity-0"
                 x-transition:enter-end="scale-100 opacity-100">
                
                {{-- Modal Header --}}
                <div class="bg-gray-900 px-5 py-4 flex justify-between items-center text-white">
                    <div>
                        <span class="text-xs font-bold text-gray-400 uppercase tracking-widest block">Events For</span>
                        <h3 class="font-heading font-bold text-lg uppercase tracking-wider" x-text="activeDate"></h3>
                    </div>
                    <button @click="showModal = false" class="text-white/60 hover:text-white transition bg-white/10 p-1 rounded-full">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                {{-- Modal Content --}}
                <div class="p-5 max-h-[60vh] overflow-y-auto space-y-3 bg-stone-50">
                    <template x-for="event in activeEvents" :key="event.id">
                        <div class="w-full">
                            <div x-show="event.is_holiday" class="p-4 bg-blue-50 rounded-xl border border-blue-200 shadow-sm">
                                <h4 class="font-bold text-blue-900 text-sm md:text-base leading-tight" x-text="event.title"></h4>
                                <span class="text-[10px] font-bold text-blue-600 uppercase mt-1 block" x-text="event.status"></span>
                            </div>

                            <a x-show="!event.is_holiday" :href="'/projects/' + event.slug" class="block p-4 bg-white rounded-xl border border-gray-200 hover:border-red-400 hover:shadow-md transition group">
                                <div class="flex items-center justify-between mb-2">
                                    <div class="flex items-center gap-2">
                                        <span class="w-2 h-2 rounded-full" :class="event.status === 'Completed' ? 'bg-green-500' : 'bg-yellow-400'"></span>
                                        <span class="text-[10px] font-bold uppercase tracking-wide text-gray-400">Project</span>
                                    </div>
                                    <span class="text-gray-300 group-hover:text-red-500 transition">&rarr;</span>
                                </div>
                                <h4 class="font-bold text-gray-900 text-sm md:text-base leading-tight group-hover:text-red-600 transition" x-text="event.title"></h4>
                            </a>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </template>
</div>