<div class="max-w-7xl mx-auto space-y-8 pb-24">

    {{-- System Header --}}
    <div class="bg-iba-black border-4 border-iba-black shadow-[8px_8px_0_0_#0095AC] p-6 text-white flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <div class="flex items-center gap-3 mb-2">
                <h1 class="text-2xl font-black uppercase tracking-widest text-white">Scheduling Matrix</h1>
                <span class="bg-iba-teal text-white text-[10px] font-black uppercase px-2 py-1">SYS: {{ $activeHackathon->name }}</span>
            </div>
            <p class="text-sm font-bold text-gray-400 uppercase tracking-widest">Establish activities, manage hubs, and assign cohorts.</p>
        </div>
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

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">

        {{-- Activity Creation Form --}}
        <div class="lg:col-span-1">
            <form wire:submit.prevent="createActivity" class="bg-white border-4 border-iba-black shadow-[6px_6px_0_0_#131011] p-6 space-y-4 sticky top-6">
                <h2 class="text-lg font-black uppercase tracking-widest border-b-2 border-iba-black pb-2 mb-4">Forge Activity</h2>

                <div>
                    <label class="text-xs font-black uppercase text-gray-500">Activity Title</label>
                    <input type="text" wire:model="activityTitle" placeholder="e.g. Phase 1 Cliniquing" class="w-full border-2 border-iba-black p-2 font-bold focus:outline-none focus:border-iba-orange bg-gray-50 mt-1">
                </div>

                <div>
                    <label class="text-xs font-black uppercase text-gray-500">Classification</label>
                    <select wire:model="activityType" class="w-full border-2 border-iba-black p-2 font-bold uppercase focus:outline-none focus:border-iba-orange bg-gray-50 mt-1 cursor-pointer">
                        <option value="mentorship">Mentorship</option>
                        <option value="cliniquing">Cliniquing</option>
                        <option value="pitch_practice">Pitch Practice</option>
                    </select>
                </div>

                <button type="submit" class="w-full bg-iba-black text-white font-black uppercase tracking-widest py-3 border-2 border-transparent hover:bg-iba-teal transition-colors mt-2">Initialize Activity</button>
            </form>
        </div>

        {{-- Activity Roster & Timetables --}}
        <div class="lg:col-span-3 space-y-12">
            @forelse($activities as $activity)
                <div class="bg-white border-4 border-iba-black shadow-[8px_8px_0_0_#131011] p-0 overflow-hidden">

                    {{-- Activity Banner --}}
                    <div class="bg-gray-100 p-6 border-b-4 border-iba-black flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                        <div>
                            <h3 class="text-2xl font-black uppercase tracking-widest text-iba-black">{{ $activity->title }}</h3>
                            <span class="text-[10px] font-bold text-gray-500 uppercase bg-gray-200 px-2 py-1 mt-1 inline-block">{{ $activity->type }}</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <button wire:click="togglePublish({{ $activity->id }})" class="text-[10px] font-black uppercase px-3 py-1 border-2 border-iba-black {{ $activity->is_published ? 'bg-iba-green text-white' : 'bg-gray-200 text-gray-600' }}">
                                {{ $activity->is_published ? 'Published' : 'Draft' }}
                            </button>
                            <button wire:click="openTrackGenerator({{ $activity->id }})" class="bg-iba-orange text-iba-black text-[10px] font-black uppercase px-4 py-2 border-2 border-iba-black shadow-[2px_2px_0_0_#131011] hover:translate-y-0.5 hover:shadow-none transition-all">+ Forge Hub</button>
                        </div>
                    </div>

                    <div class="p-6">
                        @if($activity->tracks->count() > 0)

                            {{-- TIMETABLE GRAPH MATRIX --}}
                            <div class="mb-8 border-4 border-iba-black shadow-[4px_4px_0_0_#0095AC] overflow-x-auto bg-white">
                                <div class="bg-iba-black text-white p-2 text-center text-xs font-black uppercase tracking-widest border-b-4 border-iba-black">
                                    Visual Timetable Graph
                                </div>
                                <table class="w-full text-left border-collapse min-w-max">
                                    <thead>
                                        <tr class="bg-gray-100 text-iba-black">
                                            <th class="p-3 border-r-2 border-b-4 border-iba-black text-[10px] font-black uppercase tracking-widest w-32">Time Block</th>
                                            @foreach($activity->tracks as $track)
                                                <th class="p-3 border-r-2 border-b-4 border-iba-black text-center min-w-[150px]">
                                                    <div class="text-xs font-black uppercase text-iba-teal">{{ $track->name }}</div>
                                                    <div class="text-[9px] font-bold text-gray-500 mt-1 uppercase">{{ $track->mentor->name ?? 'Unassigned' }}</div>
                                                </th>
                                            @endforeach
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $allSlots = $activity->tracks->flatMap->slots->sortBy('start_time');
                                            $uniqueTimes = $allSlots->pluck('start_time')->unique(function($time) { return $time->format('H:i'); });
                                        @endphp

                                        @foreach($uniqueTimes as $time)
                                            <tr class="hover:bg-gray-50 transition-colors">
                                                <td class="p-3 border-r-2 border-b-2 border-gray-300 text-sm font-black uppercase bg-gray-50 border-r-iba-black border-b-iba-black">
                                                    {{ $time->format('h:i A') }}
                                                </td>
                                                @foreach($activity->tracks as $track)
                                                    @php
                                                        $slot = $track->slots->first(function($s) use ($time) { return $s->start_time->format('H:i') === $time->format('H:i'); });
                                                    @endphp
                                                    <td class="p-3 border-r-2 border-b-2 border-gray-300 text-center">
                                                        @if($slot)
                                                            @php $booked = $slot->appointments->count(); @endphp
                                                            <div class="inline-block px-3 py-1 border-2 border-iba-black text-[10px] font-black uppercase shadow-[2px_2px_0_0_#131011] {{ $booked >= $slot->capacity ? 'bg-iba-teal text-white' : 'bg-white text-iba-black' }}">
                                                                {{ $booked }}/{{ $slot->capacity }} Booked
                                                            </div>
                                                        @else
                                                            <span class="text-gray-300 font-bold">-</span>
                                                        @endif
                                                    </td>
                                                @endforeach
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            {{-- RAW HUB LIST & MANUAL ASSIGNMENTS --}}
                            <h4 class="text-xs font-black text-gray-500 uppercase tracking-widest border-b-2 border-dashed border-gray-300 pb-2 mb-4">Hub Configuration & Roster Details</h4>
                            <div class="grid grid-cols-1 gap-6">
                                @foreach($activity->tracks as $track)
                                    <div class="border-2 border-iba-black p-4 bg-gray-50 shadow-[4px_4px_0_0_#131011]">

                                        {{-- Hub Header --}}
                                        <div class="flex justify-between items-start mb-4 border-b-2 border-gray-300 pb-3">
                                            <div>
                                                <span class="bg-iba-orange px-2 py-1 font-black text-xs uppercase text-iba-black border-2 border-iba-black shadow-[2px_2px_0_0_#131011]">{{ $track->name }}</span>
                                                <div class="text-[10px] font-bold text-gray-500 uppercase flex flex-col sm:flex-row sm:items-center gap-2 sm:gap-4 mt-3">
                                                    <span class="flex items-center gap-1"><svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path></svg> {{ $track->location ?? 'No location designated' }}</span>
                                                    <span class="flex items-center gap-1 text-iba-teal"><svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg> {{ $track->mentor->name ?? 'NO MENTOR ASSIGNED' }}</span>
                                                </div>
                                            </div>
                                            <div class="flex flex-col sm:flex-row gap-2">
                                                <button wire:click="openEditTrack({{ $track->id }})" class="bg-gray-200 border-2 border-iba-black text-iba-black hover:bg-gray-300 px-3 py-1 text-[10px] font-black uppercase">Edit & Extend</button>
                                                <button wire:click="deleteTrack({{ $track->id }})" wire:confirm="Purge this entire hub and all its time blocks?" class="bg-iba-red border-2 border-iba-black text-white hover:bg-red-800 px-3 py-1 text-[10px] font-black uppercase">Purge Hub</button>
                                            </div>
                                        </div>

                                        {{-- Upgraded Interactive Slot Blocks --}}
                                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-3">
                                            @foreach($track->slots as $slot)
                                                @php $booked = $slot->appointments->count(); @endphp
                                                <div class="border-2 border-iba-black flex flex-col bg-white group relative">

                                                    {{-- Time Header --}}
                                                    <div class="p-2 {{ $booked >= $slot->capacity ? 'bg-iba-black text-white' : 'bg-gray-100 text-iba-black' }} border-b-2 border-iba-black flex justify-between items-center">
                                                        <div class="text-xs font-black uppercase">{{ $slot->start_time->format('h:i A') }}</div>
                                                        <div class="text-[9px] font-bold uppercase">{{ $booked }}/{{ $slot->capacity }}</div>
                                                    </div>

                                                    {{-- NEW: Single Slot Purge Button (Only visible on hover if empty) --}}
                                                    @if($booked == 0)
                                                        <button wire:click="removeSlot({{ $slot->id }})" wire:confirm="Purge this specific time block?" class="absolute -top-2 -right-2 bg-iba-red text-white p-1 shadow-[2px_2px_0_0_#131011] opacity-0 group-hover:opacity-100 transition-opacity hover:scale-110">
                                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                                        </button>
                                                    @endif

                                                    {{-- Assigned Teams Roster --}}
                                                    <div class="p-2 flex-1 flex flex-col gap-2">
                                                        @foreach($slot->appointments as $apt)
                                                            <div class="flex justify-between items-center border-l-2 border-iba-orange pl-2 bg-gray-50 py-1">
                                                                <span class="text-[9px] font-black uppercase text-iba-black truncate pr-2" title="{{ $apt->team->team_name ?? 'Unknown' }}">{{ $apt->team->team_name ?? 'Unknown' }}</span>
                                                                <button wire:click="removeAppointment({{ $apt->id }})" wire:confirm="Evict this cohort from the time block?" class="text-iba-red hover:text-red-800 shrink-0 pr-1">
                                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                                                </button>
                                                            </div>
                                                        @endforeach

                                                        {{-- Add Button (If space available) --}}
                                                        @if($booked < $slot->capacity)
                                                            <button wire:click="openAssignModal({{ $slot->id }})" class="w-full text-center text-[9px] font-black uppercase text-gray-400 hover:text-iba-teal border-2 border-dashed border-gray-300 hover:border-iba-teal p-1 mt-auto transition-colors">
                                                                + Override Assign
                                                            </button>
                                                        @endif
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                        @else
                            <div class="text-center py-6 text-gray-400 font-bold text-xs uppercase tracking-widest border-4 border-dashed border-gray-300">No hubs established for this activity.</div>
                        @endif
                    </div>
                </div>
            @empty
                <div class="bg-gray-50 border-4 border-dashed border-iba-black p-12 text-center shadow-[6px_6px_0_0_#131011]">
                    <p class="text-sm font-black text-gray-500 uppercase tracking-widest">No activities have been initialized in the matrix.</p>
                </div>
            @endforelse
        </div>
    </div>

    {{-- MODAL 1: Slot Generator (Create) --}}
    @if($selectedActivityId)
        <div class="fixed inset-0 z-[100] overflow-y-auto">
            <div class="fixed inset-0 bg-iba-black/80 backdrop-blur-sm" wire:click="$set('selectedActivityId', null)"></div>
            <div class="flex min-h-screen items-center justify-center p-4">
                <div class="relative w-full max-w-md bg-white border-4 border-iba-black shadow-[8px_8px_0_0_#FF8623] p-6">
                    <h3 class="text-lg font-black uppercase tracking-widest text-iba-black border-b-2 border-iba-black pb-2 mb-4">Hub & Time Generator</h3>

                    <form wire:submit.prevent="generateTrackAndSlots" class="space-y-4">
                        <div>
                            <label class="text-[10px] font-black uppercase text-gray-500">Hub / Track Name</label>
                            <input type="text" wire:model="trackName" class="w-full border-2 border-iba-black p-2 font-bold focus:outline-none focus:border-iba-orange bg-gray-50">
                        </div>
                        <div>
                            <label class="text-[10px] font-black uppercase text-gray-500">Assign Mentor / Facilitator</label>
                            <select wire:model="mentorId" class="w-full border-2 border-iba-black p-2 font-bold uppercase focus:outline-none focus:border-iba-orange bg-gray-50">
                                <option value="">-- Unassigned (Open Hub) --</option>
                                @foreach($mentors as $mentor)
                                    <option value="{{ $mentor->id }}">{{ $mentor->name }} ({{ $mentor->designation ?? 'Personnel' }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="text-[10px] font-black uppercase text-gray-500">Location / Platform</label>
                            <input type="text" wire:model="location" class="w-full border-2 border-iba-black p-2 font-bold focus:outline-none focus:border-iba-orange bg-gray-50">
                        </div>

                        <div class="border-t-2 border-dashed border-gray-300 pt-4 mt-4">
                            <label class="text-xs font-black uppercase text-iba-black block mb-2">Automated Slot Parameters</label>
                            <div class="grid grid-cols-2 gap-3">
                                <div class="col-span-2">
                                    <label class="text-[10px] font-black uppercase text-gray-500">Date</label>
                                    <input type="date" wire:model="slotDate" class="w-full border-2 border-iba-black p-2 font-bold focus:outline-none focus:border-iba-orange bg-gray-50">
                                </div>
                                <div>
                                    <label class="text-[10px] font-black uppercase text-gray-500">Start Time</label>
                                    <input type="time" wire:model="startTime" class="w-full border-2 border-iba-black p-2 font-bold focus:outline-none focus:border-iba-orange bg-gray-50">
                                </div>
                                <div>
                                    <label class="text-[10px] font-black uppercase text-gray-500">End Time</label>
                                    <input type="time" wire:model="endTime" class="w-full border-2 border-iba-black p-2 font-bold focus:outline-none focus:border-iba-orange bg-gray-50">
                                </div>
                                <div class="col-span-2">
                                    <label class="text-[10px] font-black uppercase text-gray-500">Duration per Slot</label>
                                    <select wire:model="durationMinutes" class="w-full border-2 border-iba-black p-2 font-bold uppercase focus:outline-none focus:border-iba-orange bg-gray-50">
                                        <option value="15">15 Minutes</option>
                                        <option value="30">30 Minutes</option>
                                        <option value="45">45 Minutes</option>
                                        <option value="60">1 Hour</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="pt-4 flex gap-3">
                            <button type="button" wire:click="$set('selectedActivityId', null)" class="w-full bg-gray-100 text-iba-black font-black uppercase py-2 border-2 border-iba-black hover:bg-gray-200">Cancel</button>
                            <button type="submit" class="w-full bg-iba-orange text-iba-black font-black uppercase py-2 border-2 border-iba-black shadow-[3px_3px_0_0_#131011] hover:translate-y-0.5 hover:shadow-none transition-all">Generate Hub</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    {{-- MODAL 2: Hub Editor & Extension (Update) --}}
    @if($editingTrackId)
        <div class="fixed inset-0 z-[100] overflow-y-auto">
            <div class="fixed inset-0 bg-iba-black/80 backdrop-blur-sm" wire:click="$set('editingTrackId', null)"></div>
            <div class="flex min-h-screen items-center justify-center p-4">
                <div class="relative w-full max-w-md bg-white border-4 border-iba-black shadow-[8px_8px_0_0_#0095AC] p-6">
                    <h3 class="text-lg font-black uppercase tracking-widest text-iba-black border-b-2 border-iba-black pb-2 mb-4">Edit Hub & Extend Timeframe</h3>

                    <form wire:submit.prevent="updateTrack" class="space-y-4">
                        <div>
                            <label class="text-[10px] font-black uppercase text-gray-500">Hub / Track Name</label>
                            <input type="text" wire:model="editTrackName" class="w-full border-2 border-iba-black p-2 font-bold focus:outline-none focus:border-iba-orange bg-gray-50">
                        </div>
                        <div>
                            <label class="text-[10px] font-black uppercase text-gray-500">Assigned Mentor / Facilitator</label>
                            <select wire:model="editMentorId" class="w-full border-2 border-iba-black p-2 font-bold uppercase focus:outline-none focus:border-iba-orange bg-gray-50">
                                <option value="">-- Unassigned (Open Hub) --</option>
                                @foreach($mentors as $mentor)
                                    <option value="{{ $mentor->id }}">{{ $mentor->name }} ({{ $mentor->designation ?? 'Personnel' }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="text-[10px] font-black uppercase text-gray-500">Location / Platform</label>
                            <input type="text" wire:model="editLocation" class="w-full border-2 border-iba-black p-2 font-bold focus:outline-none focus:border-iba-orange bg-gray-50">
                        </div>

                        {{-- NEW: Extension Block --}}
                        <div class="border-t-2 border-dashed border-gray-300 pt-4 mt-4">
                            <label class="text-xs font-black uppercase text-iba-teal block mb-1">Extend Hub (Optional)</label>
                            <p class="text-[9px] font-bold text-gray-500 uppercase mb-3">Generate additional time blocks without overwriting existing ones. Leave blank to skip.</p>

                            <div class="grid grid-cols-2 gap-3">
                                <div class="col-span-2">
                                    <label class="text-[10px] font-black uppercase text-gray-500">Additional Date</label>
                                    <input type="date" wire:model="appendSlotDate" class="w-full border-2 border-iba-black p-2 font-bold focus:outline-none focus:border-iba-orange bg-gray-50">
                                    @error('appendSlotDate') <span class="text-[9px] text-iba-red font-bold uppercase block mt-1">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="text-[10px] font-black uppercase text-gray-500">Start Time</label>
                                    <input type="time" wire:model="appendStartTime" class="w-full border-2 border-iba-black p-2 font-bold focus:outline-none focus:border-iba-orange bg-gray-50">
                                    @error('appendStartTime') <span class="text-[9px] text-iba-red font-bold uppercase block mt-1">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="text-[10px] font-black uppercase text-gray-500">End Time</label>
                                    <input type="time" wire:model="appendEndTime" class="w-full border-2 border-iba-black p-2 font-bold focus:outline-none focus:border-iba-orange bg-gray-50">
                                    @error('appendEndTime') <span class="text-[9px] text-iba-red font-bold uppercase block mt-1">{{ $message }}</span> @enderror
                                </div>
                                <div class="col-span-2">
                                    <label class="text-[10px] font-black uppercase text-gray-500">Duration per Slot</label>
                                    <select wire:model="appendDurationMinutes" class="w-full border-2 border-iba-black p-2 font-bold uppercase focus:outline-none focus:border-iba-orange bg-gray-50">
                                        <option value="15">15 Minutes</option>
                                        <option value="30">30 Minutes</option>
                                        <option value="45">45 Minutes</option>
                                        <option value="60">1 Hour</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="pt-4 flex gap-3">
                            <button type="button" wire:click="$set('editingTrackId', null)" class="w-full bg-gray-100 text-iba-black font-black uppercase py-2 border-2 border-iba-black hover:bg-gray-200">Cancel</button>
                            <button type="submit" class="w-full bg-iba-teal text-white font-black uppercase py-2 border-2 border-iba-black shadow-[3px_3px_0_0_#131011] hover:translate-y-0.5 hover:shadow-none transition-all">Update Hub</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    {{-- MODAL 3: Manual Team Assignment (Override) --}}
    @if($assigningSlotId)
        <div class="fixed inset-0 z-[100] overflow-y-auto">
            <div class="fixed inset-0 bg-iba-black/80 backdrop-blur-sm" wire:click="$set('assigningSlotId', null)"></div>
            <div class="flex min-h-screen items-center justify-center p-4">
                <div class="relative w-full max-w-md bg-white border-4 border-iba-black shadow-[8px_8px_0_0_#FF8623] p-6">
                    <h3 class="text-lg font-black uppercase tracking-widest text-iba-black border-b-2 border-iba-black pb-2 mb-4">Command Override: Inject Cohort</h3>

                    <form wire:submit.prevent="assignTeamToSlot" class="space-y-4">
                        <p class="text-xs font-bold text-gray-600 uppercase mb-4">Bypass the public booking system and forcibly assign a cohort to this specific time block.</p>

                        <div>
                            <label class="text-[10px] font-black uppercase text-gray-500">Select Cohort Roster</label>
                            <select wire:model="assignTeamId" class="w-full border-2 border-iba-black p-2 font-bold uppercase focus:outline-none focus:border-iba-orange bg-gray-50 cursor-pointer">
                                <option value="">-- Choose Target Cohort --</option>
                                @foreach($teams as $team)
                                    <option value="{{ $team->id }}">{{ $team->team_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="pt-4 flex gap-3">
                            <button type="button" wire:click="$set('assigningSlotId', null)" class="w-full bg-gray-100 text-iba-black font-black uppercase py-2 border-2 border-iba-black hover:bg-gray-200">Cancel</button>
                            <button type="submit" class="w-full bg-iba-orange text-iba-black font-black uppercase py-2 border-2 border-iba-black shadow-[3px_3px_0_0_#131011] hover:translate-y-0.5 hover:shadow-none transition-all">Assign Cohort</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

</div>
