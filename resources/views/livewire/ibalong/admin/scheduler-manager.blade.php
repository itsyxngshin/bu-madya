<div class="max-w-6xl mx-auto space-y-8 pb-24">

    {{-- System Header --}}
    <div class="bg-iba-black border-4 border-iba-black shadow-[8px_8px_0_0_#0095AC] p-6 text-white">
        <div class="flex items-center gap-3 mb-2">
            <h1 class="text-2xl font-black uppercase tracking-widest text-white">Scheduling Matrix</h1>
            <span class="bg-iba-teal text-white text-[10px] font-black uppercase px-2 py-1">SYS: {{ $activeHackathon->name }}</span>
        </div>
        <p class="text-sm font-bold text-gray-400 uppercase tracking-widest">Establish activities, assign hubs, and generate booking slots.</p>
    </div>

    @if (session()->has('success'))
        <div class="bg-iba-teal/10 border-l-4 border-iba-teal p-4 shadow-[4px_4px_0_0_#131011]">
            <p class="text-xs font-black text-iba-teal uppercase tracking-widest">{{ session('success') }}</p>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

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

        {{-- Activity Roster --}}
        <div class="lg:col-span-2 space-y-6">
            @forelse($activities as $activity)
                <div class="bg-white border-4 border-iba-black shadow-[6px_6px_0_0_#131011] p-0">

                    {{-- Activity Banner --}}
                    <div class="bg-gray-100 p-4 border-b-4 border-iba-black flex justify-between items-center">
                        <div>
                            <h3 class="text-xl font-black uppercase tracking-widest text-iba-black">{{ $activity->title }}</h3>
                            <span class="text-[10px] font-bold text-gray-500 uppercase bg-gray-200 px-2 py-1 mt-1 inline-block">{{ $activity->type }}</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <button wire:click="togglePublish({{ $activity->id }})" class="text-[10px] font-black uppercase px-3 py-1 border-2 border-iba-black {{ $activity->is_published ? 'bg-iba-green text-white' : 'bg-gray-200 text-gray-600' }}">
                                {{ $activity->is_published ? 'Published' : 'Draft' }}
                            </button>
                            <button wire:click="openTrackGenerator({{ $activity->id }})" class="bg-iba-orange text-iba-black text-[10px] font-black uppercase px-3 py-1 border-2 border-iba-black shadow-[2px_2px_0_0_#131011] hover:translate-y-0.5 hover:shadow-none transition-all">+ Add Hub</button>
                        </div>
                    </div>

                    {{-- Hubs & Slots Display --}}
                    <div class="p-6">
                        @if($activity->tracks->count() > 0)
                            <div class="space-y-6">
                                @foreach($activity->tracks as $track)
                                    <div class="border-2 border-dashed border-gray-300 p-4 relative">
                                        <div class="absolute -top-3 left-4 flex items-center shadow-[2px_2px_0_0_#131011]">
                                            <span class="bg-white px-2 py-1 font-black text-sm uppercase text-iba-teal border-2 border-iba-black border-r-0">{{ $track->name }}</span>
                                            <button wire:click="deleteTrack({{ $track->id }})" wire:confirm="Purge this entire hub and all its time blocks?" class="bg-iba-red text-white px-2 py-1 border-2 border-iba-black hover:bg-red-800 transition-colors">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </div>
                                        <div class="text-[10px] font-bold text-gray-500 uppercase mb-3">{{ $track->location ?? 'No location designated' }}</div>

                                        <div class="flex flex-wrap gap-2">
                                            @foreach($track->slots as $slot)
                                                @php $booked = $slot->appointments->count(); @endphp
                                                <div class="border-2 border-iba-black p-2 {{ $booked >= $slot->capacity ? 'bg-iba-black text-white' : 'bg-gray-50 text-iba-black' }}">
                                                    <div class="text-xs font-black uppercase">{{ $slot->start_time->format('h:i A') }}</div>
                                                    <div class="text-[9px] font-bold uppercase {{ $booked >= $slot->capacity ? 'text-gray-400' : 'text-gray-500' }}">{{ $booked }}/{{ $slot->capacity }} Booked</div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-6 text-gray-400 font-bold text-xs uppercase tracking-widest">No hubs established for this activity.</div>
                        @endif
                    </div>
                </div>
            @empty
                <div class="bg-gray-50 border-4 border-dashed border-iba-black p-12 text-center">
                    <p class="text-sm font-black text-gray-500 uppercase tracking-widest">No activities have been initialized in the matrix.</p>
                </div>
            @endforelse
        </div>
    </div>

    {{-- The Slot Generator Modal --}}
    @if($selectedActivityId)
        <div class="fixed inset-0 z-50 overflow-y-auto">
            <div class="fixed inset-0 bg-iba-black/80 backdrop-blur-sm" wire:click="$set('selectedActivityId', null)"></div>
            <div class="flex min-h-screen items-center justify-center p-4">
                <div class="relative w-full max-w-md bg-white border-4 border-iba-black shadow-[8px_8px_0_0_#FF8623] p-6">
                    <h3 class="text-lg font-black uppercase tracking-widest text-iba-black border-b-2 border-iba-black pb-2 mb-4">Hub & Time Generator</h3>

                    <form wire:submit.prevent="generateTrackAndSlots" class="space-y-4">
                        <div>
                            <label class="text-[10px] font-black uppercase text-gray-500">Hub / Track Name</label>
                            <input type="text" wire:model="trackName" placeholder="e.g. Business Strategy Room" class="w-full border-2 border-iba-black p-2 font-bold focus:outline-none focus:border-iba-orange bg-gray-50">
                        </div>
                        <div>
                            <label class="text-[10px] font-black uppercase text-gray-500">Location / Platform</label>
                            <input type="text" wire:model="location" placeholder="e.g. Zoom Link or Library Hub" class="w-full border-2 border-iba-black p-2 font-bold focus:outline-none focus:border-iba-orange bg-gray-50">
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
                                    <label class="text-[10px] font-black uppercase text-gray-500">Duration per Slot (Minutes)</label>
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
                            <button type="submit" class="w-full bg-iba-orange text-iba-black font-black uppercase py-2 border-2 border-iba-black shadow-[3px_3px_0_0_#131011] hover:translate-y-0.5 hover:shadow-none transition-all">Generate Blocks</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>
