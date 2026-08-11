<div class="max-w-6xl mx-auto space-y-8 pb-24">

    {{-- Header --}}
    <div class="bg-white border-4 border-iba-black shadow-[8px_8px_0_0_#0095AC] p-6 relative overflow-hidden">
        <h1 class="text-2xl font-black text-iba-black uppercase tracking-wider">Mentorship & Appointments</h1>
        <p class="text-sm font-bold text-gray-500 uppercase tracking-widest mt-1">Review your itinerary, secure time blocks, and read mentor assessments.</p>
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
            @php
                // Logic Filter: If locked, does the team even have appointments here?
                $hasAppointmentsInActivity = false;
                if (!$activity->allow_booking) {
                    foreach($activity->tracks as $track) {
                        foreach($track->slots as $slot) {
                            if($slot->appointments->where('team_id', $teamId)->count() > 0) {
                                $hasAppointmentsInActivity = true;
                                break 2;
                            }
                        }
                    }
                }
            @endphp

            {{-- Hide activity entirely if it's locked AND they have no slots assigned to them --}}
            @if(!$activity->allow_booking && !$hasAppointmentsInActivity)
                @continue
            @endif

            <div class="space-y-6">

                {{-- Activity Title --}}
                <div class="flex flex-col sm:flex-row items-center gap-4">
                    <div class="h-1 flex-1 bg-iba-black hidden sm:block"></div>
                    <h2 class="text-xl font-black uppercase tracking-widest text-iba-black text-center">{{ $activity->title }}</h2>
                    <div class="h-1 flex-1 bg-iba-black hidden sm:block"></div>
                </div>

                @if(!$activity->allow_booking)
                    <div class="bg-iba-orange border-2 border-iba-black p-3 text-center shadow-[4px_4px_0_0_#131011] transform -rotate-1 max-w-md mx-auto my-4">
                        <span class="text-xs font-black uppercase text-iba-black flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                            Matrix Locked: Showing Your Itinerary Only
                        </span>
                    </div>
                @elseif($activity->description)
                    <p class="text-sm font-bold text-gray-600 text-center max-w-2xl mx-auto whitespace-pre-wrap">{{ $activity->description }}</p>
                @endif

                {{-- Hubs --}}
                <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
                    @foreach($activity->tracks as $track)
                        @php
                            // Logic Filter: If locked, only show Hubs where they actually have an appointment
                            $trackHasTeamAppointment = false;
                            foreach($track->slots as $slot) {
                                if($slot->appointments->where('team_id', $teamId)->count() > 0) {
                                    $trackHasTeamAppointment = true;
                                    break;
                                }
                            }
                        @endphp

                        @if(!$activity->allow_booking && !$trackHasTeamAppointment)
                            @continue
                        @endif

                        <div class="bg-white border-4 border-iba-black shadow-[6px_6px_0_0_#131011] p-6 relative animate-fade-in-up">

                            {{-- Hub Header --}}
                            <div class="border-b-4 border-iba-black pb-4 mb-4">
                                <h3 class="text-lg font-black uppercase tracking-widest text-iba-orange">{{ $track->name }}</h3>
                                <div class="text-[10px] font-bold text-gray-500 uppercase tracking-widest flex flex-col gap-1 mt-2">
                                    <span class="flex items-center gap-1 text-iba-teal">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                        Mentor: {{ $track->mentor->name ?? 'Unassigned' }}
                                    </span>
                                    <span class="flex items-center gap-1">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                        {{ $track->location ?? 'Location TBA' }}
                                    </span>
                                </div>
                            </div>

                            {{-- Time Blocks Grid --}}
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                                @foreach($track->slots as $slot)
                                    @php
                                        $bookedCount = $slot->appointments->count();
                                        $isFull = $bookedCount >= $slot->capacity;
                                        $myAppointment = $slot->appointments->where('team_id', $teamId)->first();
                                    @endphp

                                    {{-- If Matrix is locked, ONLY render the team's specific slot --}}
                                    @if(!$activity->allow_booking && !$myAppointment)
                                        @continue
                                    @endif

                                    @if($myAppointment)
                                        {{-- State 1: Booked by THIS Team (Upgraded View with Notes) --}}
                                        <div class="border-2 border-iba-black bg-white p-3 shadow-[3px_3px_0_0_#131011] relative group transition-all flex flex-col h-full min-h-[140px]">

                                            <div class="flex justify-between items-start border-b-2 border-iba-black pb-2 mb-2">
                                                <div class="text-sm font-black uppercase text-iba-black">{{ $slot->start_time->format('h:i A') }}</div>
                                                <span class="text-[8px] font-black bg-iba-teal text-white px-2 py-1 uppercase tracking-widest border-2 border-iba-black">Secured</span>
                                            </div>

                                            <div class="text-[10px] font-black text-gray-500 uppercase mb-3">
                                                <span class="{{ $myAppointment->status == 'attended' ? 'text-iba-green' : ($myAppointment->status == 'no_show' ? 'text-iba-red' : 'text-iba-orange') }}">
                                                    Status: {{ str_replace('_', '-', $myAppointment->status) }}
                                                </span>
                                            </div>

                                            {{-- Mentor Assessment Notes --}}
                                            @if($myAppointment->notes)
                                                <div class="mt-auto bg-gray-50 border-2 border-dashed border-gray-300 p-2 relative">
                                                    <span class="absolute -top-2.5 left-2 bg-white px-1 text-[8px] font-black text-gray-400 uppercase tracking-widest">Mentor Log</span>
                                                    <p class="text-[10px] text-gray-700 italic font-medium pt-1">"{{ $myAppointment->notes }}"</p>
                                                </div>
                                            @else
                                                <div class="mt-auto text-[9px] text-gray-400 font-bold uppercase border-t-2 border-dashed border-gray-200 pt-2 text-center">
                                                    No assessment logs yet
                                                </div>
                                            @endif

                                            {{-- Hover to cancel (Only if booking is open) --}}
                                            @if($activity->allow_booking)
                                                <button wire:click="relinquishSlot({{ $myAppointment->id }})" wire:confirm="Are you sure you want to drop this time block?" class="absolute inset-0 bg-iba-red/90 backdrop-blur-sm text-white font-black text-xs uppercase flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity z-10 border-2 border-iba-black">
                                                    Drop Slot
                                                </button>
                                            @endif
                                        </div>

                                    @elseif($isFull)
                                        {{-- State 2: Full / Booked by other teams --}}
                                        <div class="border-2 border-dashed border-gray-300 bg-gray-50 text-gray-400 p-3 opacity-60">
                                            <div class="text-sm font-black uppercase">{{ $slot->start_time->format('h:i A') }}</div>
                                            <div class="text-[9px] font-bold uppercase mt-1">Block Unavailable</div>
                                        </div>

                                    @else
                                        {{-- State 3: Open and Available --}}
                                        <button wire:click="bookSlot({{ $slot->id }})" wire:loading.attr="disabled" class="border-2 border-iba-black bg-white text-iba-black p-3 hover:bg-iba-orange shadow-[3px_3px_0_0_#131011] hover:shadow-none hover:translate-y-0.5 transition-all text-left flex flex-col h-full min-h-[140px]">
                                            <div class="text-sm font-black uppercase">{{ $slot->start_time->format('h:i A') }}</div>
                                            <div class="text-[9px] font-bold uppercase mt-1 text-gray-500">{{ $slot->capacity - $bookedCount }} Slot(s) Open</div>
                                            <div class="mt-auto text-[9px] font-black uppercase tracking-widest text-iba-teal pt-2 border-t-2 border-dashed border-gray-200 w-full text-center">
                                                Click to Secure
                                            </div>
                                        </button>
                                    @endif

                                @endforeach
                            </div>

                        </div>
                    @endforeach
                </div>
            </div>
        @empty
            <div class="bg-gray-50 border-4 border-dashed border-iba-black p-12 text-center shadow-[6px_6px_0_0_#131011]">
                <p class="text-sm font-black text-gray-500 uppercase tracking-widest">No scheduling matrices are actively published at this time.</p>
            </div>
        @endforelse
    </div>
</div>
