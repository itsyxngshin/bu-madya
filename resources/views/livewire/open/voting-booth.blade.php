<div class="max-w-4xl mx-auto py-8 md:py-12 px-4 sm:px-6 font-sans">

        {{-- ELECTION HEADER --}}
    <div class="bg-white rounded-[2rem] shadow-sm border border-gray-200 overflow-hidden mb-8 relative">
        {{-- Cover Photo Section --}}
        <div class="relative w-full border-b border-gray-100 bg-slate-50">
            @if($election->cover_photo_path)
                <img src="{{ asset('storage/'.$election->cover_photo_path) }}" class="w-full h-auto block">
            @else
                <div class="w-full h-32 md:h-48 bg-gradient-to-r from-gray-100 to-gray-50"></div>
            @endif
        </div>

        <div class="p-6 md:p-12 text-center relative bg-white">
            {{-- Upgraded Live Badge --}}
            <div class="inline-flex items-center justify-center gap-2.5 bg-red-50/80 text-red-700 border border-red-200/80 px-4 py-1.5 rounded-full text-[11px] font-black uppercase tracking-[0.2em] mb-6 shadow-sm shadow-red-500/10">
                <span class="relative flex h-2 w-2">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-500 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-red-600"></span>
                </span>
                Live Voting Booth
            </div>

            <h1 class="text-3xl md:text-5xl font-black text-gray-900 tracking-tight leading-tight">{{ $election->title }}</h1>

            {{-- Added Subtitle for Better Visual Balance --}}
            <p class="mt-4 text-xs font-bold text-gray-400 uppercase tracking-widest flex items-center justify-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                Official Digital Ballot
            </p>
        </div>
    </div>


    @if($hasVoted)
        <div class="bg-white rounded-[2rem] shadow-lg border border-green-100 p-8 md:p-14 text-center relative overflow-hidden">
            <div class="absolute inset-0 bg-green-50/30 pointer-events-none"></div>
            <div class="relative z-10">
                <div class="w-24 h-24 bg-green-100 text-green-600 rounded-full flex items-center justify-center mx-auto mb-6 border-4 border-white shadow-sm">
                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                </div>
                <h2 class="text-3xl md:text-4xl font-black text-gray-900 mb-4">Ballot Successfully Cast!</h2>
                <p class="text-gray-500 mb-10 max-w-md mx-auto text-lg">Your official vote has been securely encrypted and deposited into the digital ballot box.</p>
                <a href="/" class="inline-block px-8 py-3.5 bg-gray-900 text-white font-bold rounded-xl hover:bg-gray-800 shadow-md transition-all">Return to Homepage</a>
            </div>
        </div>

    @elseif(!$isVotingOpen)
        <div class="bg-red-50 border border-red-100 rounded-[2rem] p-8 md:p-12 text-center shadow-sm">
            <div class="w-20 h-20 bg-white text-red-500 rounded-full flex items-center justify-center mx-auto mb-5 shadow-sm border border-red-100">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
            </div>
            <h2 class="text-2xl md:text-3xl font-black text-red-900 mb-3">Voting is Closed</h2>
            <p class="text-red-700 font-medium max-w-sm mx-auto">The voting window for this election is not currently active.</p>
            <a href="/" class="inline-block mt-8 px-8 py-3 bg-red-600 text-white font-bold rounded-xl shadow-md shadow-red-600/20 hover:bg-red-700 transition-all">Return Home</a>
        </div>

    @elseif(!auth()->check() && !$election->allow_guest_voting)
        <div class="bg-amber-50 border border-amber-200 rounded-[2rem] p-8 md:p-12 text-center shadow-sm">
            <div class="w-20 h-20 bg-white text-amber-500 rounded-full flex items-center justify-center mx-auto mb-5 shadow-sm border border-amber-100">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c0 3.517-1.009 6.799-2.753 9.571m-3.44-2.04l.054-.09A13.916 13.916 0 008 11a4 4 0 118 0c0 1.017-.07 2.019-.203 3m-2.118 6.844A21.88 21.88 0 0015.171 17m3.839 1.132c.645-2.266.99-4.659.99-7.132A8 8 0 008 4.07M3 15.364c.64-1.319 1-2.8 1-4.364 0-1.457.39-2.823 1.07-4"></path></svg>
            </div>
            <h2 class="text-2xl md:text-3xl font-black text-amber-900 mb-3">Authentication Required</h2>
            <p class="text-amber-800 font-medium max-w-sm mx-auto">This election is restricted to registered members only.</p>
            <a href="{{ route('login') }}" class="inline-block mt-8 px-8 py-3 bg-amber-500 text-white font-bold rounded-xl shadow-md shadow-amber-500/20 hover:bg-amber-600 transition-all">Log In Now</a>
        </div>

    @else
        <div class="space-y-8 md:space-y-12">

            {{-- PHASE 1: GUEST VERIFICATION --}}
            @if(!auth()->check() && $election->allow_guest_voting)
                <div class="bg-white rounded-[2rem] p-6 md:p-10 shadow-sm border border-gray-200 relative overflow-hidden">
                    <h3 class="text-lg md:text-xl font-black text-gray-900 border-b border-gray-100 pb-4 mb-6 flex items-center gap-3">
                        <span class="w-8 h-8 rounded-full bg-red-100 text-red-600 flex items-center justify-center text-xs border border-red-200">1</span>
                        Voter Verification
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-[11px] font-bold text-gray-500 uppercase mb-2 tracking-wide">Full Name</label>
                            <input wire:model="guest_name" type="text" class="w-full bg-slate-50 border border-gray-200 rounded-xl px-4 py-3.5 text-sm font-semibold shadow-sm focus:border-red-500 focus:ring-4 focus:ring-red-500/10 transition-all outline-none">
                            @error('guest_name') <span class="text-[11px] text-red-500 font-bold block mt-1.5">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold text-gray-500 uppercase mb-2 tracking-wide">Email</label>
                            <input wire:model="guest_email" type="email" class="w-full bg-slate-50 border border-gray-200 rounded-xl px-4 py-3.5 text-sm font-semibold shadow-sm focus:border-red-500 focus:ring-4 focus:ring-red-500/10 transition-all outline-none">
                            @error('guest_email') <span class="text-[11px] text-red-500 font-bold block mt-1.5">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold text-gray-500 uppercase mb-2 tracking-wide">College</label>
                            <select wire:model="college_id" class="w-full bg-slate-50 border border-gray-200 rounded-xl px-4 py-3.5 text-sm font-semibold shadow-sm focus:border-red-500 focus:ring-4 focus:ring-red-500/10 transition-all outline-none appearance-none">
                                <option value="">Select College</option>
                                @foreach($colleges as $college) <option value="{{ $college->id }}">{{ $college->name }}</option> @endforeach
                            </select>
                            @error('college_id') <span class="text-[11px] text-red-500 font-bold block mt-1.5">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold text-gray-500 uppercase mb-2 tracking-wide">Year Level</label>
                            <select wire:model="year_level" class="w-full bg-slate-50 border border-gray-200 rounded-xl px-4 py-3.5 text-sm font-semibold shadow-sm focus:border-red-500 focus:ring-4 focus:ring-red-500/10 transition-all outline-none appearance-none">
                                <option value="">Select Year</option>
                                <option value="1st Year">1st Year</option>
                                <option value="2nd Year">2nd Year</option>
                                <option value="3rd Year">3rd Year</option>
                                <option value="4th Year">4th Year</option>
                            </select>
                            @error('year_level') <span class="text-[11px] text-red-500 font-bold block mt-1.5">{{ $message }}</span> @enderror
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-[11px] font-bold text-gray-500 uppercase mb-2 tracking-wide">Program</label>
                            <input wire:model="program" type="text" class="w-full bg-slate-50 border border-gray-200 rounded-xl px-4 py-3.5 text-sm font-semibold shadow-sm focus:border-red-500 focus:ring-4 focus:ring-red-500/10 transition-all outline-none">
                            @error('program') <span class="text-[11px] text-red-500 font-bold block mt-1.5">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>
            @endif

            {{-- PHASE 2: OFFICIAL BALLOT --}}
            <div class="space-y-10 md:space-y-14">
                @foreach($positions as $position)
                    @php
                        $selectedIds = $selections[$position->id] ?? [];
                        $isAbstain = in_array('abstain', $selectedIds);
                        $selectedCount = count($selectedIds);
                        $isMaxed = $selectedCount >= $position->max_winners && !$isAbstain;
                    @endphp

                    <div wire:key="position-{{ $position->id }}">
                        <div class="flex flex-col sm:flex-row sm:items-end justify-between mb-5 gap-2">
                            <div class="min-w-0 pr-4">
                                <h4 class="text-xl font-black text-gray-900 uppercase tracking-tight truncate">{{ $position->title }}</h4>
                                <p class="text-xs font-bold text-gray-500 uppercase mt-1">Select up to {{ $position->max_winners }}</p>
                            </div>
                            <span class="inline-flex text-[11px] font-black px-4 py-1.5 rounded-full transition-colors shrink-0 self-start sm:self-auto {{ $isMaxed || $isAbstain ? 'text-green-700 bg-green-100 border border-green-200' : 'text-gray-600 bg-gray-100' }}">
                                {{ $isAbstain ? 'Abstained' : $selectedCount . ' / ' . $position->max_winners }}
                            </span>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-5">
                            @forelse($position->candidates as $candidate)
                                @php
                                    $isSelected = in_array($candidate->id, $selectedIds);
                                    $candidateName = $candidate->display_name ?? optional($candidate->user)->name ?? 'Unknown Candidate';
                                    $initial = strtoupper(substr($candidateName, 0, 1));
                                @endphp

                                <button type="button"
                                        wire:key="candidate-{{ $candidate->id }}"
                                        wire:click="toggleSelection('{{ $position->id }}', '{{ $candidate->id }}')"
                                        wire:loading.attr="disabled"
                                        @disabled($isMaxed && !$isSelected)
                                        class="w-full text-left flex items-center gap-4 p-4 md:p-5 rounded-2xl border-2 transition-all disabled:opacity-50 disabled:cursor-not-allowed group {{ $isSelected ? 'border-green-500 bg-green-50 shadow-md ring-4 ring-green-500/10' : 'border-gray-100 bg-white shadow-sm hover:shadow-md hover:border-green-300' }}">

                                    {{-- Radio Button --}}
                                    <div class="w-6 h-6 rounded-full border-2 flex items-center justify-center shrink-0 transition-all duration-200 {{ $isSelected ? 'border-green-500 bg-green-500 scale-110' : 'border-gray-300 group-hover:border-green-400 bg-gray-50' }}">
                                        @if($isSelected)
                                            <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path></svg>
                                        @endif
                                    </div>

                                    {{-- Avatar --}}
                                    <div class="w-12 h-12 md:w-14 md:h-14 rounded-full overflow-hidden bg-slate-100 border border-gray-200 shrink-0 flex items-center justify-center shadow-inner">
                                        @if($candidate->profile_photo_path)
                                            <img src="{{ asset('storage/'.$candidate->profile_photo_path) }}" class="w-full h-full object-cover">
                                        @else
                                            <span class="text-gray-400 font-black text-lg">{{ $initial }}</span>
                                        @endif
                                    </div>

                                    {{-- Candidate Details --}}
                                    <div class="min-w-0 flex-1">
                                        <p class="font-black truncate text-base transition-colors {{ $isSelected ? 'text-green-900' : 'text-gray-900 group-hover:text-gray-700' }}">
                                            {{ $candidateName }}
                                        </p>
                                        <p class="text-[10px] md:text-xs font-bold text-gray-500 uppercase mt-0.5 truncate tracking-wide">{{ $candidate->program }}</p>
                                    </div>
                                </button>
                            @empty
                                <div class="col-span-full text-center py-8 bg-gray-50 rounded-2xl border-2 border-dashed border-gray-200">
                                    <p class="text-gray-400 text-sm font-bold">No approved candidates.</p>
                                </div>
                            @endforelse

                            {{-- MANDATORY ABSTAIN --}}
                            <button type="button"
                                    wire:click="toggleSelection('{{ $position->id }}', 'abstain')"
                                    wire:loading.attr="disabled"
                                    class="w-full text-left flex items-center gap-4 p-4 md:p-5 rounded-2xl border-2 border-dashed transition-all group {{ $isAbstain ? 'border-slate-500 bg-slate-100 shadow-inner' : 'border-gray-200 bg-gray-50 hover:bg-white hover:border-gray-300 hover:shadow-sm' }}">

                                <div class="w-6 h-6 rounded-full border-2 flex items-center justify-center shrink-0 transition-all duration-200 {{ $isAbstain ? 'border-slate-600 bg-slate-600 scale-110' : 'border-gray-300 group-hover:border-slate-400 bg-white' }}">
                                    @if($isAbstain)
                                        <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path></svg>
                                    @endif
                                </div>
                                <div class="min-w-0">
                                    <p class="font-black text-slate-700 text-base italic">Abstain</p>
                                    <p class="text-[10px] md:text-xs font-bold text-slate-400 uppercase mt-0.5 truncate tracking-wide">Skip this position</p>
                                </div>
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- INLINE SUBMIT BAR --}}
            <div class="mt-14 bg-white rounded-[2rem] p-6 md:p-10 border border-gray-200 shadow-sm relative overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-t from-slate-50 to-transparent pointer-events-none"></div>
                <div class="relative z-10 max-w-2xl mx-auto">
                    @if(session()->has('error') || $errors->any())
                        <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-5 py-4 rounded-xl text-xs md:text-sm font-bold text-center flex justify-center items-center gap-3 shadow-sm">
                            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                            {{ session('error') ?? 'Validation failed. Please check the fields above.' }}
                        </div>
                    @endif

                    <button type="button" wire:click="castBallot" wire:loading.attr="disabled" class="w-full py-4 md:py-5 bg-gradient-to-r from-red-600 to-red-700 text-white font-black text-lg md:text-xl rounded-2xl shadow-lg shadow-red-600/30 hover:shadow-red-600/50 hover:from-red-700 hover:to-red-800 transition-all flex items-center justify-center gap-3 transform active:scale-[0.98]">
                        <span wire:loading.remove wire:target="castBallot">Cast Official Ballot</span>
                        <span wire:loading wire:target="castBallot">Encrypting & Submitting...</span>
                    </button>
                    <p class="text-center text-[11px] text-gray-500 font-bold uppercase tracking-widest mt-5">By casting this ballot, your choices become final and immutable.</p>
                </div>
            </div>

        </div>
    @endif
</div>
