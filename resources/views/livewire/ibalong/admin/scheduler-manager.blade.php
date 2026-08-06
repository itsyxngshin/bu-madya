<div class="max-w-7xl mx-auto space-y-8 pb-24">

    {{-- System Header --}}
    <div class="bg-iba-black border-4 border-iba-black shadow-[8px_8px_0_0_#0095AC] p-6 text-white">
        <div class="flex items-center gap-3 mb-2">
            <h1 class="text-2xl font-black uppercase tracking-widest text-white">Scheduling Matrix</h1>
            <span class="bg-iba-teal text-white text-[10px] font-black uppercase px-2 py-1">SYS: {{ $activeHackathon->name }}</span>
        </div>
        <p class="text-sm font-bold text-gray-400 uppercase tracking-widest">Establish activities, manage hubs, and analyze timetable graphs.</p>
    </div>

    @if (session()->has('success'))
        <div class="bg-iba-teal/10 border-l-4 border-iba-teal p-4 shadow-[4px_4px_0_0_#131011]">
            <p class="text-xs font-black text-iba-teal uppercase tracking-widest">{{ session('success') }}</p>
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
                                            // Extract all unique times across all hubs to build the rows
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
                                                        // Find the slot for this specific hub at this specific time
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

                            {{-- RAW HUB LIST (For Details & Editing) --}}
                            <h4 class="text-xs font-black text-gray-500 uppercase tracking-widest border-b-2 border-dashed border-gray-300 pb-2 mb-4">Hub Configuration Details</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                @foreach($activity->tracks as $track)
                                    <div class="border-2 border-iba-black p-4 bg-gray-50 shadow-[4px_4px_0_0_#131011]">
                                        <div class="flex justify-between items-start mb-2">
                                            <span class="bg-iba-orange px-2 py-1 font-black text-xs uppercase text-iba-black border-2 border-iba-black">{{ $track->name }}</span>
                                            <div class="flex gap-2">
                                                <button wire:click="openEditTrack({{ $track->id }})" class="text-blue-600 hover:text-blue-800 text-[10px] font-black uppercase underline">Edit</button>
                                                <button wire:click="deleteTrack({{ $track->id }})" wire:confirm="Purge this entire hub and all its time blocks?" class="text-iba-red hover:text-red-800 text-[10px] font-black uppercase underline">Purge</button>
                                            </div>
                                        </div>
                                        <div class="text-[10px] font-bold text-gray-500 uppercase flex flex-col gap-1 mt-3">
                                            <span class="flex items-center gap-2"><svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg> {{ $track->location ?? 'No location designated' }}</span>
                                            <span class="flex items-center gap-2 text-iba-teal"><svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg> {{ $track->mentor->name ?? 'NO MENTOR ASSIGNED' }}</span>
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

    {{-- MODAL 2: Hub Editor (Update) --}}
    @if($editingTrackId)
        <div class="fixed inset-0 z-[100] overflow-y-auto">
            <div class="fixed inset-0 bg-iba-black/80 backdrop-blur-sm" wire:click="$set('editingTrackId', null)"></div>
            <div class="flex min-h-screen items-center justify-center p-4">
                <div class="relative w-full max-w-md bg-white border-4 border-iba-black shadow-[8px_8px_0_0_#0095AC] p-6">
                    <h3 class="text-lg font-black uppercase tracking-widest text-iba-black border-b-2 border-iba-black pb-2 mb-4">Edit Hub Parameters</h3>

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

                        <div class="pt-4 flex gap-3">
                            <button type="button" wire:click="$set('editingTrackId', null)" class="w-full bg-gray-100 text-iba-black font-black uppercase py-2 border-2 border-iba-black hover:bg-gray-200">Cancel</button>
                            <button type="submit" class="w-full bg-iba-teal text-white font-black uppercase py-2 border-2 border-iba-black shadow-[3px_3px_0_0_#131011] hover:translate-y-0.5 hover:shadow-none transition-all">Update Hub</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>
