<style> [x-cloak] { display: none !important; } </style>

<div class="max-w-4xl mx-auto py-8 md:py-12 px-4 sm:px-6 font-sans">

    {{-- ELECTION HEADER --}}
    <div class="bg-white rounded-[2rem] shadow-sm border border-gray-200 overflow-hidden mb-8 relative">
        @if($election->cover_photo_path)
            <img src="{{ asset('storage/'.$election->cover_photo_path) }}" class="w-full h-32 md:h-48 object-cover">
        @else
            <div class="w-full h-8 bg-gray-900"></div>
        @endif
        <div class="p-6 md:p-8 text-center relative">
            <div class="inline-flex items-center justify-center gap-2 bg-orange-100 text-orange-700 px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest mb-4">
                <span class="w-2 h-2 rounded-full bg-orange-500 animate-pulse"></span>
                Live Voting Booth
            </div>
            <h1 class="text-2xl md:text-4xl font-black text-gray-900 tracking-tight leading-tight">{{ $election->title }}</h1>
        </div>
    </div>

    {{-- STATE 1: ALREADY VOTED --}}
    @if($hasVoted)
        <div class="bg-white rounded-[2rem] shadow-xl border border-gray-100 p-8 md:p-12 text-center">
            <div class="w-20 h-20 bg-green-50 text-green-500 rounded-full flex items-center justify-center mx-auto mb-6 border border-green-100">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            </div>
            <h2 class="text-2xl md:text-3xl font-black text-gray-900 mb-4">Ballot Successfully Cast!</h2>
            <p class="text-gray-500 mb-8 max-w-md mx-auto">Your official vote has been securely encrypted and deposited into the digital ballot box.</p>
            <a href="/" class="inline-block px-8 py-3 bg-gray-100 text-gray-700 font-bold rounded-xl hover:bg-gray-200">Return to Homepage</a>
        </div>

    {{-- STATE 2: VOTING CLOSED --}}
    @elseif(!$isVotingOpen)
        <div class="bg-red-50 border border-red-200 rounded-[2rem] p-8 md:p-10 text-center">
            <div class="w-16 h-16 bg-white text-red-500 rounded-full flex items-center justify-center mx-auto mb-4 border border-red-100">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
            </div>
            <h2 class="text-2xl font-black text-red-900 mb-2">Voting is Closed</h2>
            <p class="text-red-700 font-medium">The voting window for this election is not currently active.</p>
            <a href="/" class="inline-block mt-6 px-6 py-2.5 bg-red-600 text-white font-bold rounded-xl hover:bg-red-700">Return Home</a>
        </div>

    {{-- STATE 3: AUTH REQUIRED --}}
    @elseif(!auth()->check() && !$election->allow_guest_voting)
        <div class="bg-blue-50 border border-blue-200 rounded-[2rem] p-8 md:p-10 text-center">
            <div class="w-16 h-16 bg-white text-blue-500 rounded-full flex items-center justify-center mx-auto mb-4 border border-blue-100">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c0 3.517-1.009 6.799-2.753 9.571m-3.44-2.04l.054-.09A13.916 13.916 0 008 11a4 4 0 118 0c0 1.017-.07 2.019-.203 3m-2.118 6.844A21.88 21.88 0 0015.171 17m3.839 1.132c.645-2.266.99-4.659.99-7.132A8 8 0 008 4.07M3 15.364c.64-1.319 1-2.8 1-4.364 0-1.457.39-2.823 1.07-4"></path></svg>
            </div>
            <h2 class="text-2xl font-black text-blue-900 mb-2">Authentication Required</h2>
            <p class="text-blue-700 font-medium">This election is restricted to registered members only.</p>
            <a href="{{ route('login') }}" class="inline-block mt-6 px-8 py-3 bg-blue-600 text-white font-bold rounded-xl hover:bg-blue-700">Log In Now</a>
        </div>

    {{-- STATE 4: THE OFFICIAL BALLOT --}}
    @else
        <form wire:submit.prevent="castBallot" class="space-y-6 md:space-y-8">
            
            {{-- PHASE 1: GUEST VERIFICATION --}}
            @if(!auth()->check() && $election->allow_guest_voting)
                <div class="bg-white rounded-[2rem] p-6 md:p-8 shadow-sm border border-gray-200 relative overflow-hidden">
                    <h3 class="text-lg md:text-xl font-black text-gray-900 border-b border-gray-100 pb-3 md:pb-4 mb-5 flex items-center gap-2">
                        <span class="w-6 h-6 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center text-[10px]">1</span>
                        Voter Verification
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1.5">Full Name</label>
                            <input wire:model="guest_name" type="text" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm font-bold shadow-sm">
                            @error('guest_name') <span class="text-[10px] text-red-500 font-bold block mt-1">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1.5">Email</label>
                            <input wire:model="guest_email" type="email" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm font-bold shadow-sm">
                            @error('guest_email') <span class="text-[10px] text-red-500 font-bold block mt-1">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1.5">College</label>
                            <select wire:model="college_id" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm font-bold shadow-sm">
                                <option value="">Select College</option>
                                @foreach($colleges as $college) <option value="{{ $college->id }}">{{ $college->name }}</option> @endforeach
                            </select>
                            @error('college_id') <span class="text-[10px] text-red-500 font-bold block mt-1">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1.5">Year Level</label>
                            <select wire:model="year_level" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm font-bold shadow-sm">
                                <option value="">Select Year</option>
                                <option value="1st Year">1st Year</option>
                                <option value="2nd Year">2nd Year</option>
                                <option value="3rd Year">3rd Year</option>
                                <option value="4th Year">4th Year</option>
                            </select>
                            @error('year_level') <span class="text-[10px] text-red-500 font-bold block mt-1">{{ $message }}</span> @enderror
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1.5">Program</label>
                            <input wire:model="program" type="text" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm font-bold shadow-sm">
                            @error('program') <span class="text-[10px] text-red-500 font-bold block mt-1">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>
            @endif

            {{-- PHASE 2: OFFICIAL BALLOT --}}
            <div class="space-y-8 md:space-y-10">
                @foreach($positions as $position)
                    <div wire:key="ballot-position-{{ $position->id }}">
                        
                        <div class="flex items-end justify-between mb-4">
                            <div>
                                <h4 class="text-lg font-black text-gray-900 uppercase tracking-tight">{{ $position->title }}</h4>
                                <p class="text-[10px] font-bold text-gray-500 uppercase mt-1">Select up to {{ $position->max_winners }}</p>
                            </div>
                            <span class="text-[10px] font-black px-3 py-1 rounded-full {{ count($selectedCandidates[$position->id] ?? []) == $position->max_winners && !in_array('abstain', $selectedCandidates[$position->id] ?? []) ? 'text-green-600 bg-green-50' : 'text-gray-500 bg-gray-100' }}">
                                @if(in_array('abstain', $selectedCandidates[$position->id] ?? [])) Abstained
                                @else {{ count($selectedCandidates[$position->id] ?? []) }} / {{ $position->max_winners }}
                                @endif
                            </span>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @forelse($position->candidates as $candidate)
                                @php
                                    $currentSelected = $selectedCandidates[$position->id] ?? [];
                                    $isSelected = in_array((string)$candidate->id, $currentSelected);
                                    $reachedMax = count($currentSelected) >= $position->max_winners;
                                @endphp

                                <button type="button" wire:click="toggleSelection({{ $position->id }}, '{{ $candidate->id }}')"
                                        class="w-full text-left flex items-center gap-4 p-4 rounded-2xl border-2 transition-all disabled:opacity-50 disabled:cursor-not-allowed {{ $isSelected ? 'border-orange-500 bg-orange-50 shadow-md' : 'border-gray-100 bg-gray-50 hover:border-orange-200' }}"
                                        @if($reachedMax && !$isSelected) disabled @endif>
                                    
                                    <div class="w-6 h-6 rounded-full border-2 flex items-center justify-center shrink-0 {{ $isSelected ? 'border-orange-500 bg-orange-500' : 'border-gray-300' }}">
                                        @if($isSelected) <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path></svg> @endif
                                    </div>

                                    @if($candidate->profile_photo_path)
                                        <img src="{{ asset('storage/'.$candidate->profile_photo_path) }}" class="w-12 h-12 rounded-full object-cover">
                                    @else
                                        <div class="w-12 h-12 rounded-full bg-white border border-gray-200 text-gray-400 flex items-center justify-center font-black">{{ substr($candidate->user->name ?? '?', 0, 1) }}</div>
                                    @endif
                                    
                                    <div class="min-w-0 flex-1">
                                        <p class="font-black truncate text-sm {{ $isSelected ? 'text-orange-900' : 'text-gray-900' }}">{{ $candidate->user->name ?? 'Unknown' }}</p>
                                        <p class="text-[10px] font-bold text-gray-500 uppercase mt-0.5 truncate">{{ $candidate->program }}</p>
                                    </div>
                                </button>
                            @empty
                                <div class="col-span-full text-center py-6 bg-gray-50 rounded-2xl border border-dashed border-gray-200">
                                    <p class="text-gray-400 text-sm font-bold">No approved candidates.</p>
                                </div>
                            @endforelse

                            {{-- MANDATORY ABSTAIN --}}
                            @php $isAbstain = in_array('abstain', $selectedCandidates[$position->id] ?? []); @endphp
                            <button type="button" wire:click="toggleSelection({{ $position->id }}, 'abstain')"
                                    class="w-full text-left flex items-center gap-4 p-4 rounded-2xl border-2 border-dashed transition-all {{ $isAbstain ? 'border-gray-500 bg-gray-200 shadow-inner' : 'border-gray-200 bg-gray-100 hover:border-gray-300' }}">
                                <div class="w-6 h-6 rounded-full border-2 flex items-center justify-center shrink-0 {{ $isAbstain ? 'border-gray-600 bg-gray-600' : 'border-gray-300' }}">
                                    @if($isAbstain) <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path></svg> @endif
                                </div>
                                <div>
                                    <p class="font-black text-gray-600 text-sm italic">Abstain</p>
                                    <p class="text-[9px] font-bold text-gray-400 uppercase mt-0.5">Skip this position</p>
                                </div>
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- THE NEW INLINE SUBMIT BAR --}}
            <div class="mt-12 bg-white rounded-[2rem] p-6 md:p-8 border border-gray-200 shadow-sm relative overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-t from-gray-50 to-transparent pointer-events-none"></div>
                <div class="relative z-10">
                    @if(session()->has('error') || $errors->any())
                        <div class="mb-5 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-xs md:text-sm font-bold text-center flex justify-center items-center gap-2">
                            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                            {{ session('error') ?? 'Validation failed. Please check the fields above.' }}
                        </div>
                    @endif

                    <button type="submit" wire:loading.attr="disabled" class="w-full py-4 md:py-5 bg-gray-900 text-white font-black text-lg md:text-xl rounded-xl md:rounded-2xl shadow-xl hover:bg-orange-600 transition-colors flex items-center justify-center gap-2">
                        <span wire:loading.remove wire:target="castBallot">Cast Official Ballot</span>
                        <span wire:loading wire:target="castBallot">Encrypting...</span>
                    </button>
                    <p class="text-center text-[10px] text-gray-400 font-bold uppercase tracking-wider mt-4">By casting this ballot, your choices become final and immutable.</p>
                </div>
            </div>

        </form>
    @endif
</div>