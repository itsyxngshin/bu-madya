<div class="max-w-7xl mx-auto space-y-8 pb-24">

    {{-- System Header --}}
    <div class="bg-iba-black border-4 border-iba-black shadow-[8px_8px_0_0_#0095AC] p-6 text-white flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <div class="flex items-center gap-3 mb-2">
                <h1 class="text-2xl font-black uppercase tracking-widest text-white">Scheduling Matrix</h1>
                <span class="bg-iba-teal text-white text-[10px] font-black uppercase px-2 py-1">SYS: {{ $activeHackathon->name }}</span>
            </div>
            <p class="text-sm font-bold text-gray-400 uppercase tracking-widest">Establish activities, monitor mentor assessments, and map out cohorts.</p>
        </div>
        <button wire:click="openForgeActivityModal" class="bg-iba-orange text-iba-black font-black uppercase px-6 py-3 border-2 border-transparent shadow-[4px_4px_0_0_#FFFBF7] hover:translate-y-0.5 hover:shadow-none transition-all">
            + Forge Activity
        </button>
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

    {{-- Activity Roster & Timetables --}}
    <div class="space-y-12">
        @forelse($activities as $activity)
            <div class="bg-white border-4 border-iba-black shadow-[8px_8px_0_0_#131011] p-0 overflow-hidden">

                {{-- Activity Banner --}}
                <div class="bg-gray-100 p-6 border-b-4 border-iba-black flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                    <div>
                        <h3 class="text-2xl font-black uppercase tracking-widest text-iba-black">{{ $activity->title }}</h3>
                        <span class="text-[10px] font-bold text-gray-500 uppercase bg-gray-200 px-2 py-1 mt-1 inline-block">{{ $activity->type }}</span>
                    </div>
                    <div class="flex flex-wrap items-center gap-3">
                        <button wire:click="togglePublish({{ $activity->id }})" class="text-[10px] font-black uppercase px-3 py-1.5 border-2 border-iba-black {{ $activity->is_published ? 'bg-iba-green text-white' : 'bg-white text-gray-500 hover:bg-gray-200' }}">
                            {{ $activity->is_published ? 'Live Broadcast' : 'Draft Mode' }}
                        </button>
                        
                        {{-- NEW: BOOKING LOCK TOGGLE --}}
                        <button wire:click="toggleBooking({{ $activity->id }})" class="text-[10px] font-black uppercase px-3 py-1.5 border-2 border-iba-black shadow-[2px_2px_0_0_#131011] hover:translate-y-0.5 hover:shadow-none transition-all {{ $activity->allow_booking ? 'bg-iba-teal text-white' : 'bg-iba-orange text-iba-black' }}">
                            {{ $activity->allow_booking ? '🔓 Booking Open' : '🔒 Matrix Locked' }}
                        </button>
                        <button wire:click="openEditActivity({{ $activity->id }})" class="bg-gray-200 text-iba-black border-2 border-iba-black px-3 py-1.5 text-[10px] font-black uppercase hover:bg-gray-300 transition-colors">
                            Edit Setup
                        </button>
                        <button wire:click="openTrackGenerator({{ $activity->id }})" class="bg-iba-orange text-iba-black text-[10px] font-black uppercase px-4 py-1.5 border-2 border-iba-black shadow-[2px_2px_0_0_#131011] hover:translate-y-0.5 hover:shadow-none transition-all">
                            + Forge Hub
                        </button>
                        
                        {{-- Random Auto-Assign Button --}}
                        <button wire:click="openRandomAssignModal({{ $activity->id }})" class="bg-iba-teal text-white text-[10px] font-black uppercase px-4 py-1.5 border-2 border-iba-black shadow-[2px_2px_0_0_#131011] hover:translate-y-0.5 hover:shadow-none transition-all flex items-center gap-1">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                            Auto-Assign Matrix
                        </button>

                        {{-- Nuclear Reset Schedules Button --}}
                        <button wire:click="openResetSchedulesModal({{ $activity->id }})" class="bg-iba-red text-white text-[10px] font-black uppercase px-4 py-1.5 border-2 border-iba-black shadow-[2px_2px_0_0_#131011] hover:translate-y-0.5 hover:shadow-none transition-all flex items-center gap-1">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            Reset Matrix
                        </button>
                    </div>
                </div>

                <div class="p-6">
                    @if($activity->tracks->count() > 0)

                        {{-- TIMETABLE GRAPH MATRIX --}}
                        <div class="mb-8 border-4 border-iba-black shadow-[4px_4px_0_0_#0095AC] overflow-x-auto bg-white" id="timetable-{{ $activity->id }}">
                            
                            {{-- Header with Export Controls --}}
                            <div class="bg-iba-black text-white p-2 border-b-4 border-iba-black flex justify-between items-center min-w-max">
                                <div class="text-xs font-black uppercase tracking-widest pl-2">
                                    Visual Timetable Graph
                                </div>
                                <div class="flex gap-2">
                                    {{-- Save PDF Button triggers JS --}}
                                    <button onclick="exportToPDF('{{ $activity->id }}', '{{ addslashes($activity->title) }}')" type="button" class="bg-iba-orange text-iba-black border-2 border-transparent px-3 py-1 text-[10px] font-black uppercase tracking-widest flex items-center gap-1 hover:bg-white hover:border-iba-black transition-all">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                                        Save PDF
                                    </button>
                                    
                                    {{-- CSV Export Button --}}
                                    <button wire:click="exportMatrixCsv({{ $activity->id }})" type="button" class="bg-iba-teal text-white border-2 border-transparent px-3 py-1 text-[10px] font-black uppercase tracking-widest flex items-center gap-1 hover:bg-white hover:text-iba-black hover:border-iba-black transition-all">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                        CSV
                                    </button>
                                </div>
                            </div>
                            
                            <table class="w-full text-left border-collapse min-w-max">
                                <thead>
                                    <tr class="bg-gray-100 text-iba-black">
                                        <th class="p-3 border-r-2 border-b-4 border-iba-black text-[10px] font-black uppercase tracking-widest w-32">Time Block</th>
                                        @foreach($activity->tracks as $track)
                                            <th class="p-3 border-r-2 border-b-4 border-iba-black text-center min-w-[200px]">
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
                                                <td class="p-3 border-r-2 border-b-2 border-gray-300 text-center align-top">
                                                    @if($slot)
                                                        @php $booked = $slot->appointments->count(); @endphp
                                                        <div class="flex flex-col items-center justify-center gap-1">
                                                            <div class="inline-block px-3 py-1 border-2 border-iba-black text-[10px] font-black uppercase shadow-[2px_2px_0_0_#131011] {{ $booked >= $slot->capacity ? 'bg-iba-teal text-white' : 'bg-white text-iba-black' }}">
                                                                {{ $booked }}/{{ $slot->capacity }} Booked
                                                            </div>
                                                            @if($booked > 0)
                                                                <div class="mt-2 flex flex-col gap-1 w-full px-2">
                                                                    @foreach($slot->appointments as $apt)
                                                                        <span class="text-[9px] font-bold uppercase text-gray-700 tracking-tight text-center leading-tight truncate border-b border-gray-200 pb-1 last:border-0" title="{{ $apt->team->team_name ?? 'Unknown' }}">
                                                                            {{ $apt->team->team_name ?? 'Unknown' }}
                                                                        </span>
                                                                    @endforeach
                                                                </div>
                                                            @endif
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

                        {{-- RAW HUB LIST & MENTOR ASSESSMENTS --}}
                        <h4 class="text-xs font-black text-gray-500 uppercase tracking-widest border-b-2 border-dashed border-gray-300 pb-2 mb-4">Hub Detail View & Assessment Override</h4>
                        <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
                            @foreach($activity->tracks as $track)
                                <div class="border-2 border-iba-black p-4 bg-gray-50 shadow-[4px_4px_0_0_#131011]">

                                    {{-- Hub Header --}}
                                    <div class="flex flex-col md:flex-row justify-between items-start mb-4 border-b-2 border-gray-300 pb-3 gap-4">
                                        <div>
                                            <span class="bg-iba-orange px-2 py-1 font-black text-xs uppercase text-iba-black border-2 border-iba-black shadow-[2px_2px_0_0_#131011]">{{ $track->name }}</span>
                                            <div class="text-[10px] font-bold text-gray-500 uppercase flex flex-col gap-1 mt-3">
                                                <span class="flex items-center gap-1"><svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path></svg> {{ $track->location ?? 'No location designated' }}</span>
                                                <span class="flex items-center gap-1 text-iba-teal"><svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg> {{ $track->mentor->name ?? 'NO MENTOR ASSIGNED' }}</span>
                                            </div>
                                        </div>
                                        <div class="flex flex-col sm:flex-row gap-2 w-full md:w-auto">
                                            <button wire:click="openEditTrack({{ $track->id }})" class="bg-gray-200 border-2 border-iba-black text-iba-black hover:bg-gray-300 px-3 py-1 text-[10px] font-black uppercase text-center w-full sm:w-auto">Extend Timetable</button>
                                            <button wire:click="deleteTrack({{ $track->id }})" wire:confirm="Purge this entire hub and all its time blocks?" class="bg-iba-red border-2 border-iba-black text-white hover:bg-red-800 px-3 py-1 text-[10px] font-black uppercase text-center w-full sm:w-auto">Purge Hub</button>
                                        </div>
                                    </div>

                                    {{-- Interactive Slot Blocks & Comments --}}
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                        @foreach($track->slots as $slot)
                                            @php $booked = $slot->appointments->count(); @endphp
                                            <div class="border-2 border-iba-black flex flex-col bg-white group relative">

                                                {{-- Time Header --}}
                                                <div class="p-2 {{ $booked >= $slot->capacity ? 'bg-iba-black text-white' : 'bg-gray-100 text-iba-black' }} border-b-2 border-iba-black flex justify-between items-center">
                                                    <div class="text-xs font-black uppercase">{{ $slot->start_time->format('h:i A') }}</div>
                                                    <div class="text-[9px] font-bold uppercase">{{ $booked }}/{{ $slot->capacity }}</div>
                                                </div>

                                                @if($booked == 0)
                                                    <button wire:click="removeSlot({{ $slot->id }})" wire:confirm="Purge this specific time block?" class="absolute -top-2 -right-2 bg-iba-red text-white p-1 shadow-[2px_2px_0_0_#131011] opacity-0 group-hover:opacity-100 transition-opacity hover:scale-110">
                                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                                    </button>
                                                @endif

                                                {{-- Assigned Teams Roster with Assessment Logs --}}
                                                <div class="p-2 flex-1 flex flex-col gap-2">
                                                    @foreach($slot->appointments as $apt)
                                                        <div class="flex flex-col border-l-2 border-iba-orange pl-2 bg-gray-50 py-1 gap-1 relative group/apt">
                                                            <div class="flex justify-between items-start">
                                                                <span class="text-[9px] font-black uppercase text-iba-black truncate pr-2" title="{{ $apt->team->team_name ?? 'Unknown' }}">{{ $apt->team->team_name ?? 'Unknown' }}</span>
                                                                
                                                                <div class="flex items-center gap-2 shrink-0">
                                                                    <button wire:click="openOverrideModal({{ $apt->id }})" class="text-blue-600 hover:text-blue-800 text-[8px] font-black uppercase underline" title="Override Mentor Assessment">Override</button>
                                                                    <button wire:click="removeAppointment({{ $apt->id }})" wire:confirm="Evict this cohort from the time block?" class="text-iba-red hover:text-red-800">
                                                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                                                    </button>
                                                                </div>
                                                            </div>
                                                            
                                                            <div class="text-[8px] font-bold uppercase tracking-widest mt-0.5 {{ $apt->status == 'attended' ? 'text-iba-green' : ($apt->status == 'no_show' ? 'text-iba-red' : 'text-gray-500') }}">
                                                                Status: {{ str_replace('_', '-', $apt->status) }}
                                                            </div>
                                                            
                                                            @if($apt->notes)
                                                                <div class="text-[9px] text-gray-600 italic bg-white p-1.5 border border-gray-200 mt-1 truncate" title="{{ $apt->notes }}">
                                                                    "{{ $apt->notes }}"
                                                                </div>
                                                            @endif
                                                        </div>
                                                    @endforeach

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

    {{-- MODAL 1: Forge Activity --}}
    @if($showForgeActivityModal)
        <div class="fixed inset-0 z-[100] overflow-y-auto">
            <div class="fixed inset-0 bg-iba-black/80 backdrop-blur-sm" wire:click="$set('showForgeActivityModal', false)"></div>
            <div class="flex min-h-screen items-center justify-center p-4">
                <div class="relative w-full max-w-md bg-white border-4 border-iba-black shadow-[8px_8px_0_0_#0095AC] p-6">
                    <h3 class="text-lg font-black uppercase tracking-widest text-iba-black border-b-2 border-iba-black pb-2 mb-4">Forge New Activity</h3>
                    
                    <form wire:submit.prevent="createActivity" class="space-y-4">
                        <div>
                            <label class="text-[10px] font-black uppercase text-gray-500">Activity Title</label>
                            <input type="text" wire:model="activityTitle" placeholder="e.g. Phase 1 Cliniquing" class="w-full border-2 border-iba-black p-2 font-bold focus:outline-none focus:border-iba-orange bg-gray-50 mt-1">
                            @error('activityTitle') <span class="text-[9px] font-bold text-iba-red uppercase">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="text-[10px] font-black uppercase text-gray-500">Classification</label>
                            <select wire:model="activityType" class="w-full border-2 border-iba-black p-2 font-bold uppercase focus:outline-none focus:border-iba-orange bg-gray-50 mt-1 cursor-pointer">
                                <option value="mentorship">Mentorship</option>
                                <option value="cliniquing">Cliniquing</option>
                                <option value="pitch_practice">Pitch Practice</option>
                            </select>
                        </div>

                        <div class="pt-4 flex gap-3">
                            <button type="button" wire:click="$set('showForgeActivityModal', false)" class="w-full bg-gray-100 text-iba-black font-black uppercase py-2 border-2 border-iba-black hover:bg-gray-200">Cancel</button>
                            <button type="submit" class="w-full bg-iba-teal text-white font-black uppercase tracking-widest py-2 border-2 border-iba-black shadow-[3px_3px_0_0_#131011] hover:translate-y-0.5 hover:shadow-none transition-all">Initialize Activity</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    {{-- MODAL 2: Edit Activity --}}
    @if($editingActivityId)
        <div class="fixed inset-0 z-[100] overflow-y-auto">
            <div class="fixed inset-0 bg-iba-black/80 backdrop-blur-sm" wire:click="$set('editingActivityId', null)"></div>
            <div class="flex min-h-screen items-center justify-center p-4">
                <div class="relative w-full max-w-md bg-white border-4 border-iba-black shadow-[8px_8px_0_0_#0095AC] p-6">
                    <h3 class="text-lg font-black uppercase tracking-widest text-iba-black border-b-2 border-iba-black pb-2 mb-4">Edit Activity Settings</h3>
                    
                    <form wire:submit.prevent="updateActivity" class="space-y-4">
                        <div>
                            <label class="text-[10px] font-black uppercase text-gray-500">Activity Title</label>
                            <input type="text" wire:model="editActivityTitle" class="w-full border-2 border-iba-black p-2 font-bold focus:outline-none focus:border-iba-orange bg-gray-50 mt-1">
                            @error('editActivityTitle') <span class="text-[9px] font-bold text-iba-red uppercase">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="text-[10px] font-black uppercase text-gray-500">Classification</label>
                            <select wire:model="editActivityType" class="w-full border-2 border-iba-black p-2 font-bold uppercase focus:outline-none focus:border-iba-orange bg-gray-50 mt-1 cursor-pointer">
                                <option value="mentorship">Mentorship</option>
                                <option value="cliniquing">Cliniquing</option>
                                <option value="pitch_practice">Pitch Practice</option>
                            </select>
                        </div>

                        <div class="pt-4 flex gap-3">
                            <button type="button" wire:click="$set('editingActivityId', null)" class="w-full bg-gray-100 text-iba-black font-black uppercase py-2 border-2 border-iba-black hover:bg-gray-200">Cancel</button>
                            <button type="submit" class="w-full bg-iba-teal text-white font-black uppercase tracking-widest py-2 border-2 border-iba-black shadow-[3px_3px_0_0_#131011] hover:translate-y-0.5 hover:shadow-none transition-all">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    {{-- MODAL 3: Hub Forge Generator --}}
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

    {{-- MODAL 4: Hub Editor & Extension --}}
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

    {{-- MODAL 5: Manual Team Assignment (Override) --}}
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

    {{-- MODAL 6: Mentor Assessment Command Override --}}
    @if($overrideAppointmentId)
        <div class="fixed inset-0 z-[100] overflow-y-auto">
            <div class="fixed inset-0 bg-iba-black/80 backdrop-blur-sm transition-opacity" wire:click="$set('overrideAppointmentId', null)"></div>
            <div class="flex min-h-screen items-center justify-center p-4">
                <div class="relative w-full max-w-2xl bg-white border-4 border-iba-black shadow-[8px_8px_0_0_#0095AC] flex flex-col text-left">
                    
                    <div class="px-6 py-4 border-b-4 border-iba-black bg-gray-100 flex justify-between items-center">
                        <div>
                            <h3 class="text-lg font-black text-iba-black uppercase tracking-wider">Assessment Override</h3>
                            <p class="text-[10px] font-bold text-iba-teal mt-1 uppercase">{{ $overrideTeamName }}</p>
                        </div>
                    </div>

                    <form wire:submit.prevent="saveOverrideAssessment" class="p-6 space-y-6">
                        <div>
                            <label class="block text-xs font-black text-gray-500 uppercase tracking-widest mb-2">Override Attendance Status</label>
                            <div class="grid grid-cols-2 sm:grid-cols-4 gap-2">
                                @foreach(['booked' => 'Pending', 'attended' => 'Attended', 'no_show' => 'No-Show', 'cancelled' => 'Cancelled'] as $val => $label)
                                    <label class="cursor-pointer">
                                        <input type="radio" wire:model="overrideStatus" value="{{ $val }}" class="peer sr-only">
                                        <div class="text-center px-2 py-3 border-2 border-gray-300 text-[10px] font-black uppercase text-gray-500 peer-checked:border-iba-black peer-checked:bg-iba-black peer-checked:text-white transition-all shadow-none peer-checked:shadow-[3px_3px_0_0_#0095AC]">
                                            {{ $label }}
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-black text-gray-500 uppercase tracking-widest mb-2">Override Mentor Notes</label>
                            <textarea wire:model="overrideNotes" rows="6" class="w-full border-2 border-iba-black p-3 font-bold text-sm focus:outline-none focus:border-iba-teal bg-gray-50 resize-none"></textarea>
                        </div>

                        <div class="pt-4 flex gap-4">
                            <button type="button" wire:click="$set('overrideAppointmentId', null)" class="w-full px-6 py-3 border-2 border-iba-black bg-gray-100 text-xs font-black uppercase tracking-widest text-iba-black hover:bg-gray-200">Cancel</button>
                            <button type="submit" class="w-full bg-iba-teal text-white font-black uppercase text-xs tracking-widest py-3 border-2 border-iba-black shadow-[4px_4px_0_0_#131011] hover:translate-y-1 hover:shadow-none transition-all">Force Rewrite Logs</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    {{-- MODAL 7: Random Auto-Assign Draft Mode --}}
    @if($showRandomAssignModal)
        <div class="fixed inset-0 z-[100] overflow-y-auto">
            <div class="fixed inset-0 bg-iba-black/90 backdrop-blur-sm transition-opacity"></div>
            <div class="flex min-h-screen items-center justify-center p-4">
                <div class="relative w-full max-w-4xl bg-white border-4 border-iba-black shadow-[12px_12px_0_0_#131011] flex flex-col max-h-[90vh]">
                    
                    {{-- Header --}}
                    <div class="px-6 py-4 border-b-4 border-iba-black bg-gray-100 flex justify-between items-center shrink-0">
                        <div>
                            <h3 class="text-xl font-black text-iba-black uppercase tracking-wider">Circuit Assignment Protocol</h3>
                            <p class="text-[10px] font-bold text-gray-500 mt-1 uppercase tracking-widest">Generate a mathematically perfect rotation matrix where cohorts visit hubs without repeating.</p>
                        </div>
                        <button wire:click="discardDraft(); $set('showRandomAssignModal', false)" class="text-gray-500 hover:text-iba-red transition-colors"><svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg></button>
                    </div>

                    {{-- Body --}}
                    <div class="p-6 overflow-y-auto flex-1 bg-white">
                        
                        @if (session()->has('draft_error'))
                            <div class="bg-iba-red/10 border-l-4 border-iba-red p-4 mb-6 shadow-[4px_4px_0_0_#131011]">
                                <p class="text-xs font-black text-iba-red uppercase tracking-widest">{{ session('draft_error') }}</p>
                            </div>
                        @endif

                        @if(count($draftPreview) === 0)
                            {{-- Step 1: Selection Phase --}}
                            <div class="space-y-4">
                                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-end border-b-2 border-dashed border-gray-300 pb-2 gap-2">
                                    <h4 class="text-sm font-black text-iba-black uppercase tracking-widest">Step 1: Select Target Cohorts</h4>
                                    <div class="flex gap-2">
                                        <span class="text-[10px] font-black uppercase text-gray-500 py-1">Rule: Select at least as many teams as hubs.</span>
                                        <span class="text-[10px] font-black uppercase bg-iba-black text-white px-2 py-1">{{ count($selectedTeamsForRandom) }} Selected</span>
                                    </div>
                                </div>
                                
                                {{-- Checkbox Roster --}}
                                <div class="border-2 border-iba-black bg-gray-50 max-h-[50vh] overflow-y-auto divide-y-2 divide-gray-200 shadow-inner">
                                    @forelse($teams as $team)
                                        <label class="flex items-center gap-4 p-4 cursor-pointer hover:bg-teal-50 transition-colors {{ in_array($team->id, $selectedTeamsForRandom) ? 'bg-teal-50/60' : '' }}">
                                            <input type="checkbox" wire:model="selectedTeamsForRandom" value="{{ $team->id }}" class="w-5 h-5 text-iba-teal border-2 border-iba-black focus:ring-0">
                                            <div class="flex flex-col">
                                                <span class="text-sm font-black uppercase text-iba-black">{{ $team->team_name }}</span>
                                                <span class="text-[10px] font-bold uppercase text-gray-500">{{ $team->affiliation ?? 'Project Entry' }}</span>
                                            </div>
                                        </label>
                                    @empty
                                        <div class="p-6 text-center text-xs font-bold text-gray-400 uppercase">No approved cohorts available.</div>
                                    @endforelse
                                </div>
                                @error('selectedTeamsForRandom') <span class="text-[10px] font-black text-iba-red uppercase block">⚠ {{ $message }}</span> @enderror
                            </div>
                        @else
                            {{-- Step 2: Preview Phase --}}
                            <div class="space-y-4">
                                <h4 class="text-sm font-black text-iba-black uppercase tracking-widest border-b-2 border-dashed border-gray-300 pb-2">Step 2: Preview Circuit Matrix</h4>
                                
                                <div class="border-4 border-iba-black shadow-[6px_6px_0_0_#0095AC] overflow-hidden max-h-[50vh] overflow-y-auto">
                                    <table class="w-full text-left border-collapse">
                                        <thead>
                                            <tr class="bg-iba-black text-white sticky top-0 shadow-sm">
                                                <th class="p-3 text-[10px] font-black uppercase tracking-widest">Time Block</th>
                                                <th class="p-3 text-[10px] font-black uppercase tracking-widest border-l border-gray-700">Hub / Track</th>
                                                <th class="p-3 text-[10px] font-black uppercase tracking-widest border-l border-gray-700 w-1/3">Assigned Cohort</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y-2 divide-gray-200">
                                            @foreach($draftPreview as $preview)
                                                <tr class="hover:bg-gray-50 transition-colors">
                                                    <td class="p-3 text-xs font-black uppercase text-iba-teal">
                                                        {{ $preview['date'] }} <br> <span class="text-iba-black">{{ $preview['time'] }}</span>
                                                    </td>
                                                    <td class="p-3 text-xs font-bold uppercase text-gray-600 border-l-2 border-gray-200">{{ $preview['hub_name'] }}</td>
                                                    <td class="p-3 text-xs font-black uppercase text-iba-black border-l-2 border-gray-200">{{ $preview['team_name'] }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <p class="text-[10px] font-bold text-gray-500 uppercase text-center mt-2">Notice: These assignments are held in temporary memory. Click 'Commit to Database' to finalize.</p>
                            </div>
                        @endif
                    </div>

                    {{-- Footer Controls --}}
                    <div class="bg-gray-50 px-6 py-4 border-t-4 border-iba-black shrink-0 flex gap-4">
                        @if(count($draftPreview) === 0)
                            <button type="button" wire:click="discardDraft(); $set('showRandomAssignModal', false)" class="w-full bg-gray-200 text-iba-black font-black uppercase tracking-widest py-3 border-2 border-iba-black hover:bg-gray-300">Cancel</button>
                            <button type="button" wire:click="generateRandomDraft" class="w-full bg-iba-teal text-white font-black uppercase tracking-widest py-3 border-2 border-iba-black shadow-[4px_4px_0_0_#131011] hover:translate-y-0.5 hover:shadow-none transition-all">Generate Circuit Rotation</button>
                        @else
                            <button type="button" wire:click="discardDraft" class="w-full bg-iba-red text-white font-black uppercase tracking-widest py-3 border-2 border-iba-black shadow-[4px_4px_0_0_#131011] hover:translate-y-0.5 hover:shadow-none transition-all">Scrap Draft & Retry</button>
                            <button type="button" wire:click="commitRandomAssignments" class="w-full bg-iba-orange text-iba-black font-black uppercase tracking-widest py-3 border-2 border-iba-black shadow-[4px_4px_0_0_#131011] hover:translate-y-0.5 hover:shadow-none transition-all">Commit to Database</button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- MODAL 8: Nuclear Reset Matrix --}}
    @if($resettingActivityId)
        <div class="fixed inset-0 z-[100] overflow-y-auto">
            <div class="fixed inset-0 bg-iba-black/80 backdrop-blur-sm" wire:click="$set('resettingActivityId', null)"></div>
            <div class="flex min-h-screen items-center justify-center p-4">
                <div class="relative w-full max-w-md bg-white border-4 border-iba-black shadow-[8px_8px_0_0_#CF452C] p-6 text-left">
                    
                    <h3 class="text-xl font-black uppercase tracking-widest text-iba-red border-b-2 border-iba-black pb-2 mb-4">Nuclear Purge: Schedules</h3>
                    
                    <form wire:submit.prevent="executeSchedulesReset" class="space-y-4">
                        <div class="bg-red-50 border-2 border-dashed border-iba-red p-3 mb-4">
                            <p class="text-xs font-black text-iba-red uppercase tracking-widest">Warning: Data Destruction</p>
                            <p class="text-[10px] font-bold text-gray-700 uppercase mt-1">This will permanently delete ALL team bookings and mentor assessments across ALL hubs for this activity. Hub structures and time slots will remain intact.</p>
                        </div>

                        <div class="pt-4 flex gap-3">
                            <button type="button" wire:click="$set('resettingActivityId', null)" class="w-full bg-gray-100 text-iba-black font-black uppercase py-3 border-2 border-iba-black hover:bg-gray-200">Abort</button>
                            <button type="submit" class="w-full bg-iba-red text-white font-black uppercase py-3 border-2 border-iba-black shadow-[3px_3px_0_0_#131011] hover:translate-y-0.5 hover:shadow-none transition-all">Proceed to Authorization</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    {{-- MODAL 9: ADMIN AUTHORIZATION INTERCEPTOR --}}
    @if($requiresAdminAuth)
        <div class="fixed inset-0 z-[200] overflow-y-auto">
            <div class="fixed inset-0 bg-iba-black/90 backdrop-blur-sm" wire:click="$set('requiresAdminAuth', false)"></div>
            <div class="flex min-h-screen items-center justify-center p-4">
                <div class="relative w-full max-w-sm bg-white border-4 border-iba-black shadow-[12px_12px_0_0_#CF452C] p-6 text-left transform transition-all animate-fade-in-up">
                    
                    <h3 class="text-xl font-black uppercase tracking-widest text-iba-red border-b-2 border-iba-black pb-2 mb-4">Command Authorization</h3>
                    
                    <form wire:submit.prevent="processAdminAuth" class="space-y-4">
                        <div class="bg-red-50 border-2 border-dashed border-iba-red p-3 mb-4">
                            <p class="text-[10px] font-bold text-gray-700 uppercase">You are attempting a restricted command. An Administrator must authorize this action.</p>
                        </div>

                        @if(session()->has('auth_error'))
                            <div class="text-xs font-black text-white bg-iba-red px-3 py-2 uppercase tracking-widest border-2 border-iba-black shadow-[2px_2px_0_0_#131011]">
                                {{ session('auth_error') }}
                            </div>
                        @endif

                        <div>
                            <label class="text-[10px] font-black uppercase text-gray-500">Administrator Email</label>
                            <input type="email" wire:model="adminAuthEmail" class="w-full border-2 border-iba-black p-2 font-bold focus:outline-none focus:border-iba-red bg-white mt-1 text-iba-black">
                            @error('adminAuthEmail') <span class="text-[9px] font-bold text-iba-red uppercase mt-1 block">⚠ {{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="text-[10px] font-black uppercase text-gray-500">Authorization Code (Password)</label>
                            <input type="password" wire:model="adminAuthPassword" class="w-full border-2 border-iba-black p-2 font-bold focus:outline-none focus:border-iba-red bg-white mt-1 text-iba-black tracking-widest">
                            @error('adminAuthPassword') <span class="text-[9px] font-bold text-iba-red uppercase mt-1 block">⚠ {{ $message }}</span> @enderror
                        </div>

                        <div class="pt-4 flex gap-3">
                            <button type="button" wire:click="$set('requiresAdminAuth', false)" class="w-full bg-gray-100 text-iba-black font-black uppercase py-3 border-2 border-iba-black hover:bg-gray-200">Abort</button>
                            <button type="submit" class="w-full bg-iba-red text-white font-black uppercase py-3 border-2 border-iba-black shadow-[3px_3px_0_0_#131011] hover:translate-y-0.5 hover:shadow-none transition-all">Authorize Command</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

</div>

{{-- CLIENT-SIDE PDF ENGINE --}}
<script>
    function exportToPDF(activityId, title) {
        const tableHtml = document.getElementById('timetable-' + activityId).outerHTML;
        const printWindow = window.open('', '_blank', 'height=800,width=1200');
        
        printWindow.document.write(`
            <html>
                <head>
                    <title>Matrix Extraction - ${title}</title>
                    <script src="https://cdn.tailwindcss.com"><\/script>
                    <style>
                        @media print {
                            @page { size: landscape; margin: 10mm; }
                            body { -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important; }
                            button { display: none !important; }
                        }
                    </style>
                </head>
                <body class="p-8 bg-white text-black font-sans">
                    <div class="mb-6 border-b-4 border-black pb-4 flex justify-between items-end">
                        <div>
                            <h1 class="text-3xl font-black uppercase tracking-widest text-black">${title}</h1>
                            <p class="text-xs font-bold text-gray-500 uppercase tracking-widest mt-1">Heroes of Innovation 2026 - Master Scheduling Matrix</p>
                        </div>
                        <div class="text-[10px] font-black uppercase tracking-widest text-gray-400">
                            Generated: ${new Date().toLocaleDateString()}
                        </div>
                    </div>
                    ${tableHtml}
                </body>
            </html>
        `);
        
        printWindow.document.close();
        printWindow.focus();
        
        setTimeout(() => {
            printWindow.print();
            printWindow.close();
        }, 1500);
    }
</script>