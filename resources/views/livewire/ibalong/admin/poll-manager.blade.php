<div class="max-w-7xl mx-auto space-y-8 pb-24">

    {{-- Header --}}
    <div class="bg-iba-black border-4 border-iba-black shadow-[8px_8px_0_0_#0095AC] p-6 text-white">
        <h1 class="text-2xl font-black uppercase tracking-widest text-white">Polls & Voting Manager</h1>
        <p class="text-sm font-bold text-gray-400 uppercase tracking-widest mt-1">Configure live voting events and lock in eligible nominees.</p>
    </div>

    @if (session()->has('success'))
        <div class="bg-iba-teal/10 border-l-4 border-iba-teal p-4 shadow-[4px_4px_0_0_#131011]">
            <p class="text-xs font-black text-iba-teal uppercase tracking-widest">{{ session('success') }}</p>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        {{-- Left Column: Forge Poll --}}
        <div class="lg:col-span-1">
            <div class="bg-white border-4 border-iba-black shadow-[6px_6px_0_0_#131011] p-6 sticky top-6">
                <h2 class="text-lg font-black uppercase border-b-4 border-iba-orange pb-2 mb-4">Initialize Poll</h2>

                <form wire:submit.prevent="createPoll" class="space-y-4">
                    <div>
                        <label class="block text-[10px] font-black uppercase text-gray-500 mb-1">Poll Title</label>
                        <input type="text" wire:model="title" placeholder="e.g. People's Choice Award" class="w-full border-2 border-iba-black p-3 font-bold focus:outline-none focus:border-iba-teal bg-gray-50">
                        @error('title') <span class="text-[9px] font-bold text-iba-red uppercase">{{ $message }}</span> @enderror
                    </div>

                    <div class="border-2 border-iba-black p-4 bg-gray-50">
                        <label class="flex items-center gap-3 cursor-pointer mb-2">
                            <input type="checkbox" wire:model.live="requireTicket" class="w-5 h-5 text-iba-teal border-2 border-iba-black focus:ring-0">
                            <span class="text-xs font-black uppercase">Require Event Ticket?</span>
                        </label>
                        <p class="text-[9px] font-bold text-gray-500 uppercase">If checked, audiences must have a valid QR ticket to cast a vote.</p>

                        @if($requireTicket)
                            <div class="mt-4 pt-4 border-t-2 border-dashed border-gray-300">
                                <label class="block text-[10px] font-black uppercase text-gray-500 mb-1">Link to Event</label>
                                <select wire:model="selectedEventId" class="w-full border-2 border-iba-black p-2 font-bold focus:outline-none focus:border-iba-teal cursor-pointer">
                                    <option value="">-- Select Target Event --</option>
                                    @foreach($events as $event)
                                        <option value="{{ $event->id }}">{{ $event->title ?? 'Unnamed Event' }}</option>
                                    @endforeach
                                </select>
                                @error('selectedEventId') <span class="text-[9px] font-bold text-iba-red uppercase">{{ $message }}</span> @enderror
                            </div>
                        @endif
                    </div>

                    <button type="submit" class="w-full bg-iba-orange text-iba-black text-sm font-black uppercase tracking-widest py-4 border-4 border-iba-black shadow-[4px_4px_0_0_#131011] hover:translate-y-1 hover:shadow-[2px_2px_0_0_#131011] transition-all">
                        Deploy Poll
                    </button>
                </form>
            </div>
        </div>

        {{-- Right Column: Active Polls Roster --}}
        <div class="lg:col-span-2 space-y-6">
            @forelse($polls as $poll)
                <div class="bg-white border-4 border-iba-black p-6 shadow-[6px_6px_0_0_#131011] relative group">

                    <div class="flex flex-col sm:flex-row justify-between items-start gap-4 mb-4">
                        <div>
                            <div class="flex items-center gap-2 mb-1">
                                <span class="text-[10px] font-black uppercase px-2 py-1 border-2 border-iba-black {{ $poll->is_active ? 'bg-iba-green text-white' : 'bg-gray-200 text-gray-600' }}">
                                    {{ $poll->is_active ? 'Live Broadcast' : 'Draft / Closed' }}
                                </span>
                                @if($poll->require_ticket)
                                    <span class="text-[10px] font-black uppercase text-iba-teal flex items-center gap-1">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path></svg>
                                        Ticketed Event
                                    </span>
                                @endif
                            </div>
                            <h3 class="text-xl font-black uppercase tracking-widest text-iba-black">{{ $poll->title }}</h3>
                        </div>

                        <div class="flex items-center gap-2 w-full sm:w-auto">
                            <button wire:click="togglePollStatus({{ $poll->id }})" class="w-full sm:w-auto text-[10px] font-black uppercase px-4 py-2 border-2 border-iba-black hover:bg-gray-100 transition-colors">
                                Toggle Status
                            </button>
                            <button wire:click="deletePoll({{ $poll->id }})" wire:confirm="Purge this poll and all associated votes?" class="bg-iba-red text-white text-[10px] font-black uppercase px-3 py-2 border-2 border-iba-black hover:bg-red-800 transition-colors">
                                X
                            </button>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4 border-t-2 border-dashed border-gray-300 pt-4">
                        <div class="bg-gray-50 border-2 border-iba-black p-3 text-center">
                            <span class="block text-2xl font-black text-iba-orange">{{ count($poll->nominee_ids ?? []) }}</span>
                            <span class="text-[9px] font-bold text-gray-500 uppercase tracking-widest">Locked Nominees</span>
                        </div>
                        <div class="bg-gray-50 border-2 border-iba-black p-3 text-center">
                            <span class="block text-2xl font-black text-iba-teal">{{ $poll->votes->count() }}</span>
                            <span class="text-[9px] font-bold text-gray-500 uppercase tracking-widest">Total Votes Cast</span>
                        </div>
                    </div>

                    {{-- NEW: Assign Nominees Button --}}
                    <button wire:click="openNomineeManager({{ $poll->id }})" class="mt-4 w-full bg-iba-black text-white text-xs font-black uppercase tracking-widest py-3 border-2 border-transparent hover:bg-gray-800 hover:-translate-y-0.5 hover:shadow-[4px_4px_0_0_#0095AC] transition-all">
                        Configure Nominee Roster
                    </button>

                </div>
            @empty
                <div class="bg-gray-50 border-4 border-dashed border-gray-400 p-12 text-center shadow-[6px_6px_0_0_#131011]">
                    <p class="text-sm font-black text-gray-500 uppercase tracking-widest">No polls have been initialized.</p>
                </div>
            @endforelse
        </div>
    </div>

    {{-- MODAL: Nominee Selection --}}
    @if($managingPollId)
        <div class="fixed inset-0 z-[100] overflow-y-auto">
            <div class="fixed inset-0 bg-iba-black/80 backdrop-blur-sm" wire:click="$set('managingPollId', null)"></div>
            <div class="flex min-h-screen items-center justify-center p-4">
                <div class="relative w-full max-w-4xl bg-white border-4 border-iba-black shadow-[8px_8px_0_0_#0095AC] flex flex-col text-left max-h-[90vh]">

                    <div class="px-6 py-4 border-b-4 border-iba-black bg-gray-100 flex justify-between items-center shrink-0">
                        <div>
                            <h3 class="text-lg font-black text-iba-black uppercase tracking-wider">Nominee Selection Roster</h3>
                            <p class="text-[10px] font-bold text-gray-500 uppercase mt-1">Select the cohorts eligible to receive votes in this specific poll.</p>
                        </div>
                        <span class="bg-iba-teal text-white text-[10px] font-black uppercase px-3 py-1 border-2 border-iba-black shadow-[2px_2px_0_0_#131011]">{{ count($selectedNominees) }} Selected</span>
                    </div>

                    <form wire:submit.prevent="saveNominees" class="flex flex-col flex-1 overflow-hidden">
                        <div class="p-6 overflow-y-auto flex-1 bg-white">
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                                @foreach($teams as $team)
                                    <label class="flex items-start gap-3 p-3 border-2 border-iba-black cursor-pointer hover:bg-teal-50 transition-colors {{ in_array($team->id, $selectedNominees) ? 'border-iba-teal bg-teal-50 shadow-[3px_3px_0_0_#0095AC]' : 'bg-gray-50' }}">
                                        <input type="checkbox" wire:model="selectedNominees" value="{{ $team->id }}" class="mt-0.5 w-4 h-4 text-iba-teal border-2 border-iba-black focus:ring-0">
                                        <div class="flex flex-col">
                                            <span class="text-xs font-black uppercase text-iba-black leading-tight">{{ $team->team_name }}</span>
                                            <span class="text-[9px] font-bold uppercase text-gray-500 mt-1">{{ $team->category ?? 'General' }}</span>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <div class="px-6 py-4 border-t-4 border-iba-black bg-gray-100 flex gap-4 shrink-0">
                            <button type="button" wire:click="$set('managingPollId', null)" class="w-full px-6 py-4 border-2 border-iba-black bg-white text-xs font-black uppercase tracking-widest text-iba-black hover:bg-gray-200">Cancel</button>
                            <button type="submit" class="w-full bg-iba-teal text-white font-black uppercase text-xs tracking-widest py-4 border-2 border-iba-black shadow-[4px_4px_0_0_#131011] hover:translate-y-1 hover:shadow-none transition-all">Lock Nominees</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

</div>
