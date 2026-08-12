<div class="max-w-5xl mx-auto px-4 py-12 space-y-8">

    {{-- Terminal Header --}}
    <div class="bg-iba-black border-4 border-iba-black shadow-[8px_8px_0_0_#FF8623] p-6 sm:p-8 text-white text-center relative overflow-hidden">
        @if(!$activePoll)
            <h1 class="text-3xl font-black uppercase tracking-widest text-iba-red">Terminal Offline</h1>
            <p class="text-sm font-bold text-gray-400 uppercase tracking-widest mt-2">There are currently no active voting broadcasts.</p>
        @else
            <div class="absolute top-4 right-4 flex items-center gap-2">
                <span class="relative flex h-3 w-3">
                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-iba-green opacity-75"></span>
                  <span class="relative inline-flex rounded-full h-3 w-3 bg-iba-green"></span>
                </span>
                <span class="text-[10px] font-black text-iba-green uppercase tracking-widest hidden sm:inline-block">Live Broadcast</span>
            </div>

            <h1 class="text-2xl sm:text-4xl font-black uppercase tracking-widest text-white mt-4">{{ $activePoll->title }}</h1>
            <p class="text-sm font-bold text-gray-400 uppercase tracking-widest mt-2">Select your choice from the official nominees below.</p>
        @endif
    </div>

    @if (session()->has('success'))
        <div class="bg-iba-teal/10 border-l-4 border-iba-teal p-6 shadow-[4px_4px_0_0_#131011] animate-fade-in-up">
            <p class="text-sm font-black text-iba-teal uppercase tracking-widest flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                {{ session('success') }}
            </p>
        </div>
    @endif

    @if($activePoll)
        @if($hasVoted)
            {{-- Post-Vote State --}}
            <div class="bg-gray-50 border-4 border-dashed border-gray-400 p-12 text-center shadow-[6px_6px_0_0_#131011] animate-fade-in-up">
                <h2 class="text-xl font-black text-iba-black uppercase tracking-widest mb-2">Vote Locked</h2>
                <p class="text-xs font-bold text-gray-500 uppercase tracking-widest">Your authorization has been recorded. Please wait for the final tabulation.</p>
            </div>
        @elseif(count($teams) === 0)
            {{-- No Nominees State --}}
            <div class="bg-gray-50 border-4 border-dashed border-iba-red p-12 text-center shadow-[6px_6px_0_0_#131011] animate-fade-in-up">
                <h2 class="text-xl font-black text-iba-red uppercase tracking-widest mb-2">Standby for Nominees</h2>
                <p class="text-xs font-bold text-gray-500 uppercase tracking-widest">The Command Center is currently finalizing the eligible cohorts.</p>
            </div>
        @else
            {{-- Active Voting Interface --}}
            <div class="space-y-8 animate-fade-in-up">
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                    @foreach($teams as $team)
                        {{-- Click triggers the modal and passes both ID and Name --}}
                        <div wire:click="selectTeam({{ $team->id }}, '{{ addslashes($team->team_name) }}')"
                             class="border-4 cursor-pointer transition-all duration-200 relative group p-6 border-gray-300 bg-white hover:border-iba-black hover:bg-orange-50 hover:-translate-y-1 hover:shadow-[6px_6px_0_0_#131011]">

                            <div class="pr-4 text-center">
                                <h3 class="text-lg font-black uppercase text-iba-black mb-1 leading-tight">{{ $team->team_name }}</h3>
                                <p class="text-[10px] font-bold uppercase text-gray-500">
                                    {{ $team->category ?? 'General' }}
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    @endif

    {{-- MODAL: Execute Vote --}}
    @if($selectedTeamId)
        <div class="fixed inset-0 z-50 overflow-y-auto">
            <div class="fixed inset-0 bg-iba-black/90 backdrop-blur-sm" wire:click="cancelSelection"></div>
            <div class="flex min-h-screen items-center justify-center p-4">
                <div class="relative w-full max-w-md bg-white border-4 border-iba-black shadow-[12px_12px_0_0_#FF8623] p-6 text-center animate-fade-in-up">

                    <h3 class="text-lg font-black uppercase tracking-widest text-gray-500 mb-1">Confirm Vote For:</h3>
                    <h2 class="text-2xl font-black uppercase text-iba-black border-b-4 border-iba-teal pb-4 mb-6 inline-block">{{ $selectedTeamName }}</h2>

                    @if (session()->has('error'))
                        <div class="bg-iba-red text-white p-3 mb-6 border-2 border-iba-black font-black text-xs uppercase tracking-widest shadow-[2px_2px_0_0_#131011]">
                            {{ session('error') }}
                        </div>
                    @endif

                    <form wire:submit.prevent="castVote" class="space-y-6">
                        @if($activePoll->require_ticket)
                            <div class="text-left bg-gray-50 p-4 border-2 border-dashed border-gray-300">
                                <label class="block text-[10px] font-black uppercase text-iba-red mb-2 tracking-widest">Event Ticket Code Required <span class="text-iba-black">*</span></label>
                                <input type="text" wire:model="ticketCode" placeholder="Enter alphanumeric code..." class="w-full border-2 border-iba-black p-3 font-black uppercase tracking-widest focus:outline-none focus:border-iba-teal bg-white text-center text-lg">
                                @error('ticketCode') <span class="text-[10px] font-bold text-iba-red uppercase mt-1 block text-center">{{ $message }}</span> @enderror
                            </div>
                        @else
                            <div class="bg-gray-100 border-2 border-dashed border-gray-300 p-4">
                                <p class="text-[10px] font-black text-gray-500 uppercase tracking-widest">Public Broadcast: No ticket required.</p>
                            </div>
                        @endif

                        <div class="flex flex-col sm:flex-row gap-3 pt-2">
                            <button type="button" wire:click="cancelSelection" class="w-full sm:w-1/3 bg-gray-100 text-iba-black text-xs font-black uppercase tracking-widest py-4 border-2 border-iba-black hover:bg-gray-200 transition-colors">
                                Cancel
                            </button>
                            <button type="submit" class="w-full sm:w-2/3 bg-iba-teal text-white text-sm font-black uppercase tracking-widest py-4 border-2 border-iba-black shadow-[4px_4px_0_0_#131011] hover:translate-y-1 hover:shadow-none transition-all">
                                Authorize Vote
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    @endif
</div>
