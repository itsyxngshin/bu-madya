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
                <div class="mt-2 space-y-1">
                    @if($hasEvents)
                        @foreach($events[$day] as $event)
                            {{-- FIX: Use array syntax $event['...'] instead of -> --}}
                            <a href="{{ route('projects.show', $event['slug']) }}" 
                            class="block px-2 py-1 mb-1 rounded bg-yellow-50 border-l-2 border-yellow-400 text-[9px] font-bold text-gray-800 truncate hover:bg-yellow-100 transition shadow-sm"
                            title="{{ $event['title'] }}">
                                {{ $event['title'] }}
                            </a>
                        @endforeach
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