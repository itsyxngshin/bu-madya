<div class="max-w-6xl mx-auto space-y-8 pb-24">

    {{-- Header --}}
    <div class="bg-white border-4 border-iba-black shadow-[8px_8px_0_0_#0095AC] p-6 relative overflow-hidden">
        <h1 class="text-2xl font-black text-iba-black uppercase tracking-wider">Mentorship & Appointments</h1>
        <p class="text-sm font-bold text-gray-500 uppercase tracking-widest mt-1">Secure your cohort's time blocks for active hubs.</p>
    </div>

    @if (session()->has('success'))
        <div class="bg-iba-teal/10 border-l-4 border-iba-teal p-4 shadow-[4px_4px_0_0_#131011]">
            <p class="text-xs font-black text-iba-teal uppercase tracking-widest">{{ session('success') }}</p>
        </div>
    @endif
    @if (session()->has('error'))
        <div class="bg-iba-red/10 border-l-4 border-iba-red p-4 shadow-[4px_4px_0_0_#131011]">
            <p class="text-xs font-black text-iba-red uppercase tracking-widest">{{ session('error') }}</p>
        </div>
    @endif

    <div class="space-y-12">
        @forelse($activities as $activity)
            <div class="space-y-6">

                {{-- Activity Title --}}
                <div class="flex items-center gap-4">
                    <div class="h-1 flex-1 bg-iba-black"></div>
                    <h2 class="text-xl font-black uppercase tracking-widest text-iba-black">{{ $activity->title }}</h2>
                    <div class="h-1 flex-1 bg-iba-black"></div>
                </div>

                @if($activity->description)
                    <p class="text-sm font-bold text-gray-600 text-center max-w-2xl mx-auto whitespace-pre-wrap">{{ $activity->description }}</p>
                @endif

                {{-- Hubs --}}
                <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
                    @foreach($activity->tracks as $track)
                        <div class="bg-white border-4 border-iba-black shadow-[6px_6px_0_0_#131011] p-6 relative animate-fade-in-up">

                            {{-- Hub Header --}}
                            <div class="border-b-4 border-iba-black pb-4 mb-4">
                                <h3 class="text-lg font-black uppercase tracking-widest text-iba-orange">{{ $track->name }}</h3>
                                <p class="text-[10px] font-bold text-gray-500 uppercase tracking-widest flex items-center gap-1 mt-1">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                    {{ $track->location ?? 'Location TBA' }}
                                </p>
                            </div>

                            {{-- Time Blocks Grid --}}
                            <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                                @foreach($track->slots as $slot)
                                    @php
                                        $bookedCount = $slot->appointments->count();
                                        $isFull = $bookedCount >= $slot->capacity;
                                        // Check if THIS specific team has an appointment here
                                        $myAppointment = $slot->appointments->where('team_id', $teamId)->first();
                                    @endphp

                                    @if($myAppointment)
                                        {{-- State 1: Booked by THIS Team --}}
                                        <div class="border-2 border-iba-black bg-iba-teal text-white p-3 shadow-[3px_3px_0_0_#131011] relative group transition-all">
                                            <div class="text-sm font-black uppercase">{{ $slot->start_time->format('h:i A') }}</div>
                                            <div class="text-[9px] font-bold uppercase mt-1 text-teal-100">Secured Block</div>

                                            {{-- Hover to cancel --}}
                                            <button wire:click="relinquishSlot({{ $myAppointment->id }})" wire:confirm="Are you sure you want to drop this time block?" class="absolute inset-0 bg-iba-red text-white font-black text-xs uppercase flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                                Drop Slot
                                            </button>
                                        </div>

                                    @elseif($isFull)
                                        {{-- State 2: Full / Booked by other teams --}}
                                        <div class="border-2 border-dashed border-gray-300 bg-gray-50 text-gray-400 p-3 opacity-60">
                                            <div class="text-sm font-black uppercase">{{ $slot->start_time->format('h:i A') }}</div>
                                            <div class="text-[9px] font-bold uppercase mt-1">Block Unavailable</div>
                                        </div>

                                    @else
                                        {{-- State 3: Open and Available --}}
                                        <button wire:click="bookSlot({{ $slot->id }})" wire:loading.attr="disabled" class="border-2 border-iba-black bg-white text-iba-black p-3 hover:bg-iba-orange shadow-[3px_3px_0_0_#131011] hover:shadow-none hover:translate-y-0.5 transition-all text-left">
                                            <div class="text-sm font-black uppercase">{{ $slot->start_time->format('h:i A') }}</div>
                                            <div class="text-[9px] font-bold uppercase mt-1 text-gray-500">{{ $slot->capacity - $bookedCount }} Slot(s) Open</div>
                                        </button>
                                    @endif

                                @endforeach
                            </div>

                        </div>
                    @endforeach
                </div>
            </div>
        @empty
            <div class="bg-gray-50 border-4 border-dashed border-iba-black p-12 text-center">
                <p class="text-sm font-black text-gray-500 uppercase tracking-widest">No scheduling matrices are actively published at this time.</p>
            </div>
        @endforelse
    </div>
</div>
