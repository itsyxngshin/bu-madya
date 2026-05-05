<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 relative">

    {{-- Top Controls: Date Picker & Manual Toggle --}}
    <div class="flex items-center justify-between mb-6 pb-4 border-b border-gray-100">
        <div>
            <input type="date" wire:model.live="selectedDate" max="{{ \Carbon\Carbon::today()->format('Y-m-d') }}"
                   class="text-sm font-black text-gray-900 border-none bg-gray-50 rounded-lg focus:ring-red-500 cursor-pointer">
        </div>
        <button wire:click="toggleManualMode" class="text-xs font-bold text-gray-400 hover:text-red-600 underline transition-colors outline-none">
            {{ $manualMode ? 'Cancel Edit' : 'Edit Manually' }}
        </button>
    </div>

    <div class="text-center">
        @if(!$manualMode)
            {{-- ========================================== --}}
            {{-- STANDARD REAL-TIME CLOCK VIEW              --}}
            {{-- ========================================== --}}
            <h2 class="text-sm font-bold text-gray-500 uppercase tracking-widest mb-1">Current Status</h2>

            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-black uppercase tracking-wider mb-6
                @if(str_contains($currentStatus, 'Clocked In')) bg-green-100 text-green-700
                @elseif($currentStatus === 'On Lunch Break') bg-yellow-100 text-yellow-700
                @else bg-red-100 text-red-700 @endif">
                {{ $currentStatus }}
            </span>

            {{-- Only show live ticking clock if looking at TODAY --}}
            @if($selectedDate === \Carbon\Carbon::today()->format('Y-m-d'))
                <div x-data="{ time: new Date().toLocaleTimeString() }"
                     x-init="setInterval(() => time = new Date().toLocaleTimeString(), 1000)"
                     class="text-4xl font-black text-gray-900 mb-6 font-mono"
                     x-text="time">
                </div>

                @if($currentStatus !== 'Shift Completed')
                    <button wire:click="punchTime"
                            class="w-full sm:w-auto px-8 py-3 bg-gray-900 hover:bg-red-600 text-white font-bold rounded-xl transition-all shadow-md active:scale-95 outline-none">
                        @if(!$todayLog->morning_in) Morning In
                        @elseif(!$todayLog->morning_out) Morning Out
                        @elseif(!$todayLog->afternoon_in) Afternoon In
                        @else Afternoon Out
                        @endif
                    </button>
                @endif
            @else
                {{-- Looking at a past date --}}
                <div class="text-3xl font-black text-gray-900 mb-6 font-mono">
                    {{ floor($todayLog->total_minutes_rendered / 60) }}h {{ $todayLog->total_minutes_rendered % 60 }}m
                </div>
                <p class="text-xs text-gray-400">Total time rendered on this date.</p>
            @endif

        @else
            {{-- ========================================== --}}
            {{-- MANUAL ENTRY / BACKTRACKING FORM           --}}
            {{-- ========================================== --}}
            <form wire:submit.prevent="saveManualTimes" class="text-left space-y-4">

                {{-- Morning Session --}}
                <div class="bg-gray-50 p-4 rounded-xl border border-gray-100">
                    <h3 class="text-xs font-black text-gray-700 uppercase tracking-widest mb-3">Morning Shift</h3>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Time In</label>
                            <input type="time" wire:model="m_in" class="w-full text-sm font-bold bg-white border border-gray-200 rounded-lg focus:ring-red-500">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Time Out</label>
                            <input type="time" wire:model="m_out" class="w-full text-sm font-bold bg-white border border-gray-200 rounded-lg focus:ring-red-500">
                        </div>
                    </div>
                </div>

                {{-- Afternoon Session --}}
                <div class="bg-gray-50 p-4 rounded-xl border border-gray-100">
                    <h3 class="text-xs font-black text-gray-700 uppercase tracking-widest mb-3">Afternoon Shift</h3>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Time In</label>
                            <input type="time" wire:model="a_in" class="w-full text-sm font-bold bg-white border border-gray-200 rounded-lg focus:ring-red-500">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Time Out</label>
                            <input type="time" wire:model="a_out" class="w-full text-sm font-bold bg-white border border-gray-200 rounded-lg focus:ring-red-500">
                        </div>
                    </div>
                </div>

                <button type="submit" class="w-full py-3 bg-red-600 hover:bg-red-700 text-white font-bold rounded-xl transition-colors shadow-md outline-none">
                    Save Manual Times
                </button>
            </form>
        @endif

        {{-- Alerts --}}
        @if (session()->has('message'))
            <p class="mt-4 text-xs font-bold text-green-600 bg-green-50 py-2 rounded-lg">{{ session('message') }}</p>
        @endif
        @if (session()->has('error'))
            <p class="mt-4 text-xs font-bold text-red-600 bg-red-50 py-2 rounded-lg">{{ session('error') }}</p>
        @endif
    </div>
</div>
