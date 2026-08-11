<div class="max-w-7xl mx-auto space-y-8 pb-24">

    {{-- System Header --}}
    <div class="bg-iba-black border-4 border-iba-black shadow-[8px_8px_0_0_#FF8623] p-6 text-white flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <div class="flex items-center gap-3 mb-2">
                <h1 class="text-2xl font-black uppercase tracking-widest text-white">Live Voting Control</h1>
                <span class="bg-iba-orange text-iba-black text-[10px] font-black uppercase px-2 py-1">SYS: {{ $activeHackathon->name }}</span>
            </div>
            <p class="text-sm font-bold text-gray-400 uppercase tracking-widest">Establish polls, manage ticket gates, and monitor the live leaderboard.</p>
        </div>
    </div>

    @if (session()->has('success'))
        <div class="bg-iba-teal/10 border-l-4 border-iba-teal p-4 shadow-[4px_4px_0_0_#131011]">
            <p class="text-xs font-black text-iba-teal uppercase tracking-widest">{{ session('success') }}</p>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        {{-- Poll Forge Form --}}
        <div class="lg:col-span-1">
            <form wire:submit.prevent="createPoll" class="bg-white border-4 border-iba-black shadow-[6px_6px_0_0_#131011] p-6 space-y-4 sticky top-6">
                <h2 class="text-lg font-black uppercase tracking-widest border-b-2 border-iba-black pb-2 mb-4">Initialize Poll</h2>

                <div>
                    <label class="text-[10px] font-black uppercase text-gray-500 block mb-1">Poll Title <span class="text-iba-red">*</span></label>
                    <input type="text" wire:model="title" class="w-full border-2 border-iba-black p-2 font-bold focus:outline-none focus:border-iba-orange bg-gray-50">
                    @error('title') <span class="text-[9px] font-bold text-iba-red uppercase">{{ $message }}</span> @enderror
                </div>

                <div class="border-t-2 border-dashed border-gray-300 pt-4 mt-4">
                    <label class="flex items-center gap-3 cursor-pointer mb-3">
                        <input type="checkbox" wire:model.live="requireTicket" class="w-5 h-5 text-iba-teal border-2 border-iba-black focus:ring-0">
                        <div class="flex flex-col">
                            <span class="text-xs font-black uppercase text-iba-black">Require Event Ticket</span>
                        </div>
                    </label>

                    {{-- NEW: Event Dropdown conditionally appears --}}
                    @if($requireTicket)
                        <div class="animate-fade-in-up bg-gray-50 p-3 border-2 border-iba-black">
                            <label class="text-[10px] font-black uppercase text-gray-500 block mb-1">Link to Event <span class="text-iba-red">*</span></label>
                            <select wire:model="selectedEventId" class="w-full border-2 border-iba-black p-2 font-bold uppercase focus:outline-none focus:border-iba-orange bg-white cursor-pointer">
                                <option value="">-- Select Master Event --</option>
                                @foreach($events as $event)
                                    <option value="{{ $event->id }}">{{ $event->title }}</option>
                                @endforeach
                            </select>
                            @error('selectedEventId') <span class="text-[9px] font-bold text-iba-red uppercase mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    @else
                        <div class="bg-gray-100 p-3 border-2 border-dashed border-gray-300">
                            <span class="text-[9px] font-bold uppercase text-gray-500">The poll will be completely open to the public without validation.</span>
                        </div>
                    @endif
                </div>

                <button type="submit" class="w-full bg-iba-black text-white font-black uppercase tracking-widest py-3 border-2 border-transparent hover:bg-iba-orange hover:text-iba-black transition-colors mt-4">Generate Poll</button>
            </form>
        </div>

        {{-- Polls & Leaderboards --}}
        <div class="lg:col-span-2 space-y-8">
            @forelse($polls as $poll)
                <div class="bg-white border-4 border-iba-black shadow-[8px_8px_0_0_#131011] p-0 overflow-hidden relative group">

                    {{-- Delete Button (Hover) --}}
                    <button wire:click="deletePoll({{ $poll->id }})" wire:confirm="Purge this poll and destroy all cast votes? This cannot be undone." class="absolute top-4 right-4 bg-iba-red text-white p-2 border-2 border-iba-black shadow-[2px_2px_0_0_#131011] opacity-0 group-hover:opacity-100 transition-opacity hover:scale-110 z-10">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                    </button>

                    {{-- Poll Header --}}
                    <div class="bg-gray-100 p-6 border-b-4 border-iba-black flex flex-col sm:flex-row justify-between items-start gap-4">
                        <div class="pr-12">
                            <h3 class="text-2xl font-black uppercase tracking-widest text-iba-black leading-tight">{{ $poll->title }}</h3>

                            {{-- Linked Event Display --}}
                            @if($poll->require_ticket && $poll->event)
                                <p class="text-[10px] font-bold text-iba-teal uppercase tracking-widest mt-1 flex items-center gap-1">📍 Linked to: {{ $poll->event->title }}</p>
                            @endif

                            <div class="flex flex-wrap items-center gap-2 mt-3">
                                <span class="text-[10px] font-black uppercase tracking-widest px-2 py-1 border-2 border-iba-black {{ $poll->is_active ? 'bg-iba-green text-white shadow-[2px_2px_0_0_#131011]' : 'bg-gray-300 text-gray-600' }}">
                                    {{ $poll->is_active ? '● LIVE BROADCASTING' : 'OFFLINE' }}
                                </span>
                                <span class="text-[10px] font-black uppercase tracking-widest px-2 py-1 bg-iba-black text-white border-2 border-iba-black">
                                    {{ $poll->require_ticket ? '🎫 TICKET GATED' : '🌍 OPEN TO PUBLIC' }}
                                </span>
                            </div>
                        </div>

                        <div class="flex flex-col gap-2 shrink-0 w-full sm:w-auto">
                            <button wire:click="togglePollStatus({{ $poll->id }})" class="border-2 border-iba-black px-4 py-2 text-[10px] font-black uppercase shadow-[2px_2px_0_0_#131011] hover:translate-y-0.5 hover:shadow-none transition-all {{ $poll->is_active ? 'bg-iba-red text-white' : 'bg-iba-teal text-white' }}">
                                {{ $poll->is_active ? 'Halt Voting' : 'Open Voting' }}
                            </button>
                            <button wire:click="toggleTicketRequirement({{ $poll->id }})" class="border-2 border-dashed border-gray-400 text-gray-600 px-4 py-2 text-[10px] font-black uppercase bg-white hover:border-iba-black hover:text-iba-black transition-colors">
                                Toggle Ticket Gate
                            </button>
                        </div>
                    </div>

                    {{-- Live Leaderboard --}}
                    <div class="p-6">
                        <h4 class="text-xs font-black text-gray-500 uppercase tracking-widest border-b-2 border-dashed border-gray-300 pb-2 mb-4">Real-Time Leaderboard</h4>

                        @php
                            // Group votes by team and sort descending
                            $leaderboard = $poll->votes->groupBy('team_id')->map(function($votes) {
                                return [
                                    'team' => $votes->first()->team,
                                    'count' => $votes->count()
                                ];
                            })->sortByDesc('count')->values();
                            $totalVotes = $poll->votes->count();
                        @endphp

                        @if($leaderboard->count() > 0)
                            <div class="space-y-4">
                                <div class="flex justify-between items-center bg-iba-black text-white px-4 py-2 text-[10px] font-black uppercase border-2 border-iba-black shadow-[2px_2px_0_0_#0095AC] mb-4">
                                    <span>Cohort Matrix</span>
                                    <span>Total Votes Cast: {{ $totalVotes }}</span>
                                </div>

                                @foreach($leaderboard as $index => $entry)
                                    @php
                                        $percentage = $totalVotes > 0 ? round(($entry['count'] / $totalVotes) * 100) : 0;
                                        $isTop = $index === 0;
                                    @endphp
                                    <div class="relative pt-1">
                                        <div class="flex mb-2 items-center justify-between">
                                            <div class="flex items-center gap-3">
                                                <span class="text-sm font-black text-iba-black uppercase {{ $isTop ? 'text-iba-orange' : '' }}">
                                                    {{ $index + 1 }}. {{ $entry['team']->team_name ?? 'Unknown Cohort' }}
                                                    @if($isTop) <span class="text-[10px] ml-1">👑</span> @endif
                                                </span>
                                            </div>
                                            <div class="text-right">
                                                <span class="text-xs font-black inline-block text-iba-black bg-gray-100 px-2 py-0.5 border-2 border-iba-black">
                                                    {{ $entry['count'] }} Votes <span class="text-gray-500 ml-1">({{ $percentage }}%)</span>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="overflow-hidden h-6 mb-4 text-xs flex rounded-none border-2 border-iba-black bg-gray-50 shadow-inner">
                                            <div style="width:{{ $percentage }}%" class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center transition-all duration-1000 ease-out {{ $isTop ? 'bg-iba-orange' : 'bg-iba-teal' }}">
                                                @if($percentage > 5)
                                                    <span class="font-black text-[10px] opacity-80">{{ $percentage }}%</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8 bg-gray-50 border-2 border-dashed border-gray-300">
                                <span class="text-2xl mb-2 block">📊</span>
                                <span class="text-xs font-black text-gray-400 uppercase tracking-widest">No votes cast yet.</span>
                            </div>
                        @endif
                    </div>
                </div>
            @empty
                <div class="bg-gray-50 border-4 border-dashed border-iba-black p-12 text-center shadow-[6px_6px_0_0_#131011]">
                    <p class="text-sm font-black text-gray-500 uppercase tracking-widest">No polls have been initialized in the matrix.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
