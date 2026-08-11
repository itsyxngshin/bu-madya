<div class="min-h-screen bg-gray-100 py-12 px-4 sm:px-6 lg:px-8 font-sans">
    <div class="max-w-7xl mx-auto space-y-8">

        {{-- Main Banner --}}
        <div class="bg-iba-black border-4 border-iba-black shadow-[10px_10px_0_0_#0095AC] p-8 text-center sm:text-left flex flex-col sm:flex-row justify-between items-center gap-6">
            <div>
                <h1 class="text-3xl sm:text-4xl font-black uppercase tracking-widest text-white mb-2">People's Choice Award</h1>
                <p class="text-sm font-bold text-gray-400 uppercase tracking-widest">
                    @if($activePoll)
                        {{ $activePoll->title }} is currently <span class="text-iba-teal">LIVE</span>. Cast your vote below!
                    @else
                        The voting matrix is currently <span class="text-iba-red">CLOSED</span>.
                    @endif
                </p>
            </div>
            @if($activePoll && $activePoll->require_ticket)
                <div class="bg-iba-orange text-iba-black border-2 border-iba-black px-4 py-2 shadow-[4px_4px_0_0_#FFFBF7] transform rotate-2">
                    <span class="text-xs font-black uppercase tracking-widest">🎫 Event Ticket Required</span>
                </div>
            @endif
        </div>

        @if (session()->has('success'))
            <div class="bg-iba-green border-4 border-iba-black p-4 shadow-[6px_6px_0_0_#131011] text-center animate-bounce">
                <p class="text-sm font-black text-iba-black uppercase tracking-widest">{{ session('success') }}</p>
            </div>
        @endif

        @if($activePoll)
            {{-- Candidates Grid --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($teams as $team)
                    <div class="bg-white border-4 border-iba-black shadow-[6px_6px_0_0_#131011] flex flex-col group hover:-translate-y-1 hover:shadow-[10px_10px_0_0_#FF8623] transition-all duration-300">

                        {{-- Candidate Header --}}
                        <div class="bg-gray-50 border-b-4 border-iba-black p-6 flex flex-col items-center justify-center text-center relative overflow-hidden h-40">

                            {{-- Optional Logo Logic --}}
                            @if($team->logo_path)
                                <img src="{{ Storage::url($team->logo_path) }}" class="w-20 h-20 object-contain mb-3 relative z-10 border-2 border-iba-black bg-white p-1">
                            @else
                                <div class="w-20 h-20 mb-3 bg-iba-teal border-2 border-iba-black flex items-center justify-center text-3xl font-black text-white relative z-10">
                                    {{ substr($team->team_name, 0, 1) }}
                                </div>
                            @endif

                            <h3 class="text-xl font-black uppercase tracking-widest text-iba-black relative z-10 leading-tight">{{ $team->team_name }}</h3>
                            <p class="text-[10px] font-bold text-iba-teal uppercase tracking-widest mt-1 relative z-10">{{ $team->affiliation ?? 'Project Entry' }}</p>

                            {{-- Brutalist Decor --}}
                            <div class="absolute -right-4 -bottom-4 opacity-10 font-pixel text-8xl text-iba-black z-0 pointer-events-none">#</div>
                        </div>

                        {{-- Solution Description --}}
                        <div class="p-6 flex-1 flex flex-col">
                            <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-widest border-b-2 border-dashed border-gray-300 pb-1 mb-3">The Solution</h4>
                            <p class="text-sm font-bold text-gray-700 leading-relaxed flex-1 overflow-hidden" style="display: -webkit-box; -webkit-line-clamp: 4; -webkit-box-orient: vertical;">
                                {{ $team->team_about ?? 'No solution description provided by this cohort.' }}
                            </p>

                            <button wire:click="openVoteModal({{ $team->id }})" class="mt-6 w-full bg-iba-black text-white font-black uppercase tracking-widest py-3 border-2 border-transparent group-hover:bg-iba-orange group-hover:text-iba-black group-hover:border-iba-black transition-colors">
                                Select Cohort
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            {{-- Voting Closed State --}}
            <div class="bg-white border-4 border-dashed border-iba-black p-16 text-center">
                <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                <h3 class="text-2xl font-black text-gray-400 uppercase tracking-widest">Awaiting Command Authorization</h3>
                <p class="text-sm font-bold text-gray-500 uppercase mt-2">The voting poll has not been initiated by the organizers yet.</p>
            </div>
        @endif

    </div>

    {{-- MODAL: Execute Vote --}}
    @if($selectedTeam)
        <div class="fixed inset-0 z-50 overflow-y-auto">
            <div class="fixed inset-0 bg-iba-black/90 backdrop-blur-sm" wire:click="$set('selectedTeam', null)"></div>
            <div class="flex min-h-screen items-center justify-center p-4">
                <div class="relative w-full max-w-md bg-white border-4 border-iba-black shadow-[12px_12px_0_0_#FF8623] p-6 text-center">

                    <h3 class="text-lg font-black uppercase tracking-widest text-gray-500 mb-1">Confirm Vote For:</h3>
                    <h2 class="text-2xl font-black uppercase text-iba-black border-b-4 border-iba-teal pb-4 mb-6 inline-block">{{ $selectedTeam->team_name }}</h2>

                    @if (session()->has('error'))
                        <div class="bg-iba-red text-white p-3 mb-6 border-2 border-iba-black font-black text-xs uppercase tracking-widest shadow-[2px_2px_0_0_#131011]">
                            {{ session('error') }}
                        </div>
                    @endif

                    <form wire:submit.prevent="castVote" class="space-y-6">
                        @if($activePoll->require_ticket)
                            <div class="text-left">
                                <label class="text-[10px] font-black uppercase text-gray-500 block mb-2">
                                    Enter {{ $activePoll->event->title ?? 'Event' }} Ticket Code <span class="text-iba-red">*</span>
                                </label>
                                <input type="text" wire:model="ticketCode" placeholder="e.g. HOI-XXXXXX" class="w-full border-2 border-iba-black p-4 font-black text-lg uppercase focus:outline-none focus:border-iba-orange bg-gray-50 text-center tracking-widest">
                                @error('ticketCode') <span class="text-[9px] font-bold text-iba-red uppercase block mt-2 text-center">{{ $message }}</span> @enderror
                            </div>
                        @else
                            <div class="bg-gray-100 p-4 border-2 border-dashed border-gray-300">
                                <p class="text-xs font-bold text-gray-500 uppercase tracking-widest">This poll is open. No ticket code required.</p>
                            </div>
                        @endif

                        <div class="pt-2 flex flex-col sm:flex-row gap-4">
                            <button type="button" wire:click="$set('selectedTeam', null)" class="w-full bg-gray-100 text-iba-black font-black uppercase py-4 border-2 border-iba-black hover:bg-gray-200">Cancel</button>
                            <button type="submit" class="w-full bg-iba-teal text-white font-black uppercase py-4 border-2 border-iba-black shadow-[4px_4px_0_0_#131011] hover:translate-y-1 hover:shadow-none transition-all">Submit Vote</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>
