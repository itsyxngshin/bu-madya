<div class="bg-white rounded-3xl border border-gray-200 shadow-sm overflow-hidden font-sans">
    
    {{-- 1. HEADER --}}
    <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between bg-stone-50">
        <div>
            <h2 class="text-xl font-black text-gray-900 uppercase tracking-tight">
                {{ $monthName }} <span class="text-red-600">{{ $year }}</span>
            </h2>
        </div>

        <div class="flex items-center bg-white rounded-lg border border-gray-200 shadow-sm">
            <button wire:click="previousMonth" class="px-3 py-1 hover:bg-gray-100 text-gray-500 border-r border-gray-200 transition">
                &larr;
            </button>
            <button wire:click="nextMonth" class="px-3 py-1 hover:bg-gray-100 text-gray-500 transition">
                &rarr;
            </button>
        </div>
    </div>

    {{-- 2. CALENDAR BODY --}}
    {{-- We use style="..." to FORCE the grid layout regardless of Tailwind --}}
    <div class="w-full">
        
        {{-- Days of Week Header --}}
        <div style="display: grid; grid-template-columns: repeat(7, 1fr);" class="border-b border-gray-200 bg-gray-50 text-center">
            @foreach(['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'] as $day)
                <div class="py-2 text-[10px] font-bold text-gray-400 uppercase tracking-widest">
                    {{ $day }}
                </div>
            @endforeach
        </div>

        {{-- Days Grid --}}
        <div style="display: grid; grid-template-columns: repeat(7, 1fr);" class="bg-gray-200 gap-px border-b border-gray-200">
            
            {{-- Empty Cells (Previous Month) --}}
            @for($i = 0; $i < $startDayOfWeek; $i++)
                <div class="bg-stone-50 min-h-[100px]"></div>
            @endfor

            {{-- Actual Days --}}
            @for($day = 1; $day <= $daysInMonth; $day++)
                @php
                    $hasEvents = isset($events[$day]);
                    $isToday = $day == now()->day && $currentMonth == now()->month && $currentYear == now()->year;
                @endphp

                <div class="bg-white min-h-[100px] p-2 relative hover:bg-red-50 transition group flex flex-col gap-1">
                    
                    {{-- Date Number --}}
                    <span class="text-xs font-bold {{ $isToday ? 'bg-red-600 text-white w-6 h-6 rounded-full flex items-center justify-center shadow-md' : 'text-gray-700' }}">
                        {{ $day }}
                    </span>

                    {{-- Events List --}}
                    <div class="flex-1">
                        @if($hasEvents)
                            @foreach($events[$day] as $event)
                                <a href="{{ route('projects.show', $event->slug) }}" 
                                   class="block px-2 py-1 mb-1 rounded bg-yellow-50 border-l-2 border-yellow-400 text-[9px] font-bold text-gray-800 truncate hover:bg-yellow-100 transition shadow-sm"
                                   title="{{ $event->title }}">
                                    {{ $event->title }}
                                </a>
                            @endforeach
                        @endif
                    </div>

                    {{-- Add Button (Hover Only) --}}
                    @if(Auth::check() && in_array(Auth::user()->role_id, [1, 2])) 
                        {{-- Format the date properly --}}
                        @php
                            $targetDate = \Carbon\Carbon::create($currentYear, $currentMonth, $day)->format('Y-m-d');
                        @endphp

                        <a href="{{ route('projects.create', ['date' => $targetDate]) }}" 
                        class="absolute top-2 right-2 hidden group-hover:block p-1 bg-gray-100 text-gray-400 rounded hover:bg-red-600 hover:text-white transition">
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
                <div class="bg-stone-50 min-h-[100px]"></div>
            @endfor

        </div>
    </div>
</div>