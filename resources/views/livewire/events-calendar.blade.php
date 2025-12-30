<div class="bg-white rounded-[2rem] border border-gray-200 shadow-sm overflow-hidden">
    
    {{-- 1. HEADER & CONTROLS --}}
    <div class="px-6 py-4 border-b border-gray-100 flex flex-col md:flex-row justify-between items-center bg-gray-50/50 gap-4">
        
        <div class="flex items-center gap-4">
            <div class="p-2 bg-red-100 text-red-600 rounded-lg">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
            </div>
            <div>
                <h2 class="font-heading font-black text-xl text-gray-900 leading-none">
                    {{ $monthName }} <span class="text-gray-400 font-medium">{{ $year }}</span>
                </h2>
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mt-1">Implementation Schedule</p>
            </div>
        </div>

        <div class="flex items-center bg-white rounded-lg border border-gray-200 p-1 shadow-sm">
            <button wire:click="previousMonth" class="p-1.5 rounded-md hover:bg-gray-100 text-gray-400 hover:text-gray-700 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            </button>
            <span class="px-4 text-xs font-bold text-gray-600 min-w-[80px] text-center select-none">
                {{ \Carbon\Carbon::create($currentYear, $currentMonth)->format('M Y') }}
            </span>
            <button wire:click="nextMonth" class="p-1.5 rounded-md hover:bg-gray-100 text-gray-400 hover:text-gray-700 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
            </button>
        </div>
    </div>

    {{-- 2. CALENDAR GRID --}}
    <div class="w-full">
        
        {{-- Days Header --}}
        <div class="grid grid-cols-7 border-b border-gray-100 bg-gray-50">
            @foreach(['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'] as $day)
                <div class="py-2 text-center">
                    <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">{{ $day }}</span>
                </div>
            @endforeach
        </div>

        {{-- Days Cells --}}
        <div class="grid grid-cols-7 auto-rows-fr bg-gray-200 gap-px border-b border-gray-200">
            
            {{-- Empty Cells (Previous Month) --}}
            @for($i = 0; $i < $startDayOfWeek; $i++)
                <div class="bg-gray-50/30 min-h-[100px]"></div>
            @endfor

            {{-- Actual Days --}}
            @for($day = 1; $day <= $daysInMonth; $day++)
                @php
                    $hasEvents = isset($events[$day]);
                    $isToday = $day == now()->day && $currentMonth == now()->month && $currentYear == now()->year;
                @endphp

                <div class="bg-white min-h-[100px] p-2 relative group hover:bg-gray-50 transition cursor-default">
                    
                    {{-- Date Number --}}
                    <span class="text-xs font-bold {{ $isToday ? 'bg-red-600 text-white w-6 h-6 rounded-full flex items-center justify-center shadow-md' : 'text-gray-400' }}">
                        {{ $day }}
                    </span>

                    {{-- Events List --}}
                    <div class="mt-2 space-y-1">
                        @if($hasEvents)
                            @foreach($events[$day] as $event)
                                <a href="{{ route('projects.show', $event->slug) }}" 
                                   class="block px-2 py-1 rounded bg-yellow-50 border-l-2 border-yellow-400 text-[9px] font-bold text-gray-700 truncate hover:bg-yellow-100 transition"
                                   title="{{ $event->title }}">
                                    {{ $event->title }}
                                </a>
                            @endforeach
                        @endif
                    </div>

                    {{-- Add Button (Hover Only) --}}
                    @if(Auth::check() && in_array(Auth::user()->role_id, [1, 2])) {{-- Admin/Director --}}
                        <a href="{{ route('projects.create') }}?date={{ $currentYear }}-{{ $currentMonth }}-{{ $day }}" 
                           class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 p-1 bg-gray-100 text-gray-400 rounded hover:bg-red-50 hover:text-red-600 transition">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        </a>
                    @endif
                </div>
            @endfor

            {{-- Empty Cells (Next Month padding to fill grid) --}}
            @php
                $totalCells = $startDayOfWeek + $daysInMonth;
                $remainingCells = 7 - ($totalCells % 7);
                if($remainingCells == 7) $remainingCells = 0;
            @endphp
            @for($i = 0; $i < $remainingCells; $i++)
                <div class="bg-gray-50/30 min-h-[100px]"></div>
            @endfor

        </div>
    </div>
</div>