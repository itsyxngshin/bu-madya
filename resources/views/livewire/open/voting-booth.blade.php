<style> [x-cloak] { display: none !important; } </style>

<div class="max-w-4xl mx-auto py-8 md:py-12 px-4 sm:px-6 font-sans pb-28 md:pb-12">

    {{-- ELECTION HEADER --}}
    <div class="bg-white rounded-[2rem] shadow-sm border border-gray-200 overflow-hidden mb-8">
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

    {{-- STATE 1: ALREADY VOTED (SUCCESS) --}}
    @if($hasVoted)
        <div class="bg-white rounded-[2rem] shadow-xl border border-gray-100 p-8 md:p-12 text-center animate-fade-in-up">
            <div class="w-20 h-20 md:w-24 md:h-24 bg-green-50 text-green-500 rounded-full flex items-center justify-center mx-auto mb-6 shadow-inner border border-green-100">
                <svg class="w-10 h-10 md:w-12 md:h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            </div>
            <h2 class="text-2xl md:text-3xl font-black text-gray-900 mb-4">Ballot Successfully Cast!</h2>
            <p class="text-gray-500 mb-8 max-w-md mx-auto text-sm md:text-base leading-relaxed">Your official vote has been securely encrypted and deposited into the digital ballot box.</p>
            <a href="/" class="inline-block px-8 py-3 bg-gray-100 text-gray-700 font-bold rounded-xl shadow-sm hover:bg-gray-200 transition">Return to Homepage</a>
        </div>

    {{-- STATE 2: VOTING CLOSED --}}
    @elseif(!$isVotingOpen)
        <div class="bg-red-50 border border-red-200 rounded-[2rem] p-8 md:p-10 text-center animate-fade-in-up">
            <div class="w-16 h-16 bg-white text-red-500 rounded-full flex items-center justify-center mx-auto mb-4 shadow-sm border border-red-100">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
            </div>
            <h2 class="text-2xl font-black text-red-900 mb-2">Voting is Closed</h2>
            <p class="text-red-700 font-medium text-sm md:text-base">The voting window for this election is not currently active.</p>
            <a href="/" class="inline-block mt-6 px-6 py-2.5 bg-red-600 text-white font-bold rounded-xl shadow-sm hover:bg-red-700 transition">Return Home</a>
        </div>

    {{-- STATE 3: AUTH REQUIRED (Guest Voting Disabled) --}}
    @elseif(!auth()->check() && !$election->allow_guest_voting)
        <div class="bg-blue-50 border border-blue-200 rounded-[2rem] p-8 md:p-10 text-center animate-fade-in-up">
            <div class="w-16 h-16 bg-white text-blue-500 rounded-full flex items-center justify-center mx-auto mb-4 shadow-sm border border-blue-100">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c0 3.517-1.009 6.799-2.753 9.571m-3.44-2.04l.054-.09A13.916 13.916 0 008 11a4 4 0 118 0c0 1.017-.07 2.019-.203 3m-2.118 6.844A21.88 21.88 0 0015.171 17m3.839 1.132c.645-2.266.99-4.659.99-7.132A8 8 0 008 4.07M3 15.364c.64-1.319 1-2.8 1-4.364 0-1.457.39-2.823 1.07-4"></path></svg>
            </div>
            <h2 class="text-2xl font-black text-blue-900 mb-2">Authentication Required</h2>
            <p class="text-blue-700 font-medium text-sm md:text-base">This election is restricted to registered members only. Please log in to cast your ballot.</p>
            <a href="{{ route('login') }}" class="inline-block mt-6 px-8 py-3 bg-blue-600 text-white font-bold rounded-xl shadow-md hover:bg-blue-700 transition">Log In Now</a>
        </div>

    {{-- STATE 4: THE OFFICIAL BALLOT --}}
    @else
        <form wire:submit.prevent="castBallot" class="space-y-6 md:space-y-8 relative">
            
            {{-- PHASE 1: GUEST VERIFICATION (Only shows if they are logged out AND guest voting is ON) --}}
            @if(!auth()->check() && $election->allow_guest_voting)
                <div class="bg-white rounded-[2rem] p-6 md:p-8 shadow-sm border border-gray-200 relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-blue-50 rounded-full blur-3xl -mt-10 -mr-10"></div>
                    
                    <h3 class="text-lg md:text-xl font-black text-gray-900 border-b border-gray-100 pb-3 md:pb-4 mb-5 md:mb-6 flex items-center gap-2 relative z-10">
                        <span class="w-6 h-6 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center text-[10px]">1</span>
                        Voter Verification
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-5 relative z-10">
                        <div>
                            <label class="block text-[10px] md:text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5">Full Name</label>
                            <input wire:model="guest_name" type="text" class="w-full bg-gray-50 border border-gray-200 focus:border-blue-500 focus:bg-white rounded-xl px-4 py-3 text-sm font-bold text-gray-800 shadow-sm" placeholder="Juan Dela Cruz">
                            @error('guest_name') <span class="text-[10px] text-red-500 font-bold mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-[10px] md:text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5">Email Address</label>
                            <input wire:model="guest_email" type="email" class="w-full bg-gray-50 border border-gray-200 focus:border-blue-500 focus:bg-white rounded-xl px-4 py-3 text-sm font-bold text-gray-800 shadow-sm" placeholder="juan@bicol-u.edu.ph">
                            @error('guest_email') <span class="text-[10px] text-red-500 font-bold mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        {{-- PREMIUM COLLEGE DROPDOWN --}}
                        <div>
                            <label class="block text-[10px] md:text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5">College</label>
                            <div x-data="{ open: false, selectedId: @entangle('college_id'), options: [ @foreach($colleges as $college) { id: '{{ $college->id }}', label: '{{ addslashes($college->name) }}' }, @endforeach ], get selectedLabel() { let o = this.options.find(opt => opt.id == this.selectedId); return o ? o.label : 'Select College'; } }" @click.away="open = false" class="relative w-full">
                                <button @click="open = !open" type="button" class="w-full bg-gray-50 border border-gray-200 focus:border-blue-500 focus:bg-white rounded-xl px-4 py-3 text-sm font-bold text-gray-800 flex justify-between items-center shadow-sm">
                                    <span x-text="selectedLabel" class="truncate" :class="!selectedId ? 'text-gray-400 font-medium' : ''"></span>
                                    <svg class="w-4 h-4 text-gray-400" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                </button>
                                <div x-show="open" x-cloak class="absolute z-50 w-full mt-1 bg-white border border-gray-100 rounded-xl shadow-xl overflow-hidden"><ul class="max-h-48 overflow-y-auto p-1"><template x-for="option in options" :key="option.id"><li @click="selectedId = option.id; open = false" class="px-4 py-2.5 text-sm font-bold rounded-lg cursor-pointer" :class="selectedId == option.id ? 'bg-blue-50 text-blue-600' : 'hover:bg-gray-50'"><span x-text="option.label"></span></li></template></ul></div>
                            </div>
                            @error('college_id') <span class="text-[10px] text-red-500 font-bold mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        {{-- PREMIUM YEAR LEVEL DROPDOWN --}}
                        <div>
                            <label class="block text-[10px] md:text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5">Year Level</label>
                            <div x-data="{ open: false, selectedValue: @entangle('year_level'), options: ['1st Year', '2nd Year', '3rd Year', '4th Year', '5th Year'], get selectedLabel() { return this.selectedValue ? this.selectedValue : 'Select Year'; } }" @click.away="open = false" class="relative w-full">
                                <button @click="open = !open" type="button" class="w-full bg-gray-50 border border-gray-200 focus:border-blue-500 focus:bg-white rounded-xl px-4 py-3 text-sm font-bold text-gray-800 flex justify-between items-center shadow-sm">
                                    <span x-text="selectedLabel" class="truncate" :class="!selectedValue ? 'text-gray-400 font-medium' : ''"></span>
                                    <svg class="w-4 h-4 text-gray-400" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                </button>
                                <div x-show="open" x-cloak class="absolute z-50 w-full mt-1 bg-white border border-gray-100 rounded-xl shadow-xl overflow-hidden"><ul class="max-h-48 overflow-y-auto p-1"><template x-for="option in options"><li @click="selectedValue = option; open = false" class="px-4 py-2.5 text-sm font-bold rounded-lg cursor-pointer" :class="selectedValue === option ? 'bg-blue-50 text-blue-600' : 'hover:bg-gray-50'"><span x-text="option"></span></li></template></ul></div>
                            </div>
                            @error('year_level') <span class="text-[10px] text-red-500 font-bold mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-[10px] md:text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5">Academic Program</label>
                            <input wire:model="program" type="text" class="w-full bg-gray-50 border border-gray-200 focus:border-blue-500 focus:bg-white rounded-xl px-4 py-3 text-sm font-bold text-gray-800 shadow-sm" placeholder="e.g. BS Information Technology">
                            @error('program') <span class="text-[10px] text-red-500 font-bold mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>
            @endif

            {{-- PHASE 2: THE OFFICIAL BALLOT --}}
            <div class="space-y-8 md:space-y-10">
                @foreach($positions as $position)
                    <div wire:key="ballot-position-{{ $position->id }}" class="relative">
                        
                        <div class="flex flex-col md:flex-row md:items-end justify-between mb-4 gap-2">
                            <div>
                                <h4 class="text-lg font-black text-gray-900 uppercase tracking-tight">{{ $position->title }}</h4>
                                <p class="text-[10px] md:text-xs font-bold text-gray-500 uppercase tracking-wider mt-1">
                                    Select up to {{ $position->max_winners }} candidate(s)
                                </p>
                            </div>
                            <span class="text-[10px] font-black {{ count($selectedCandidates[$position->id] ?? []) == $position->max_winners && !in_array('abstain', $selectedCandidates[$position->id] ?? []) ? 'text-green-600 bg-green-50' : 'text-gray-500 bg-gray-100' }} px-3 py-1 rounded-full w-max transition-colors">
                                @if(in_array('abstain', $selectedCandidates[$position->id] ?? []))
                                    Abstained
                                @else
                                    {{ count($selectedCandidates[$position->id] ?? []) }} / {{ $position->max_winners }} Selected
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

                                {{-- PURE BUTTON (No Checkboxes) --}}
                                <button type="button" 
                                        wire:click="toggleSelection({{ $position->id }}, '{{ $candidate->id }}')"
                                        wire:key="candidate-card-{{ $position->id }}-{{ $candidate->id }}"
                                        class="w-full text-left relative flex items-center gap-4 p-4 rounded-2xl border-2 transition-all duration-200 ease-in-out group disabled:opacity-50 disabled:cursor-not-allowed disabled:bg-gray-100 {{ $isSelected ? 'border-orange-500 bg-orange-50 shadow-md' : 'border-gray-100 bg-gray-50 hover:border-orange-200' }}"
                                        @if($reachedMax && !$isSelected) disabled @endif>
                                    
                                    {{-- Custom Checkbox UI --}}
                                    <div class="w-6 h-6 rounded-full border-2 flex items-center justify-center shrink-0 transition-colors {{ $isSelected ? 'border-orange-500 bg-orange-500' : 'border-gray-300 group-hover:border-orange-400' }}">
                                        @if($isSelected)
                                            <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path></svg>
                                        @endif
                                    </div>

                                    @if($candidate->profile_photo_path)
                                        <img src="{{ asset('storage/'.$candidate->profile_photo_path) }}" class="w-12 h-12 rounded-full object-cover shadow-sm shrink-0 bg-white">
                                    @else
                                        <div class="w-12 h-12 rounded-full bg-white border border-gray-200 text-gray-400 flex items-center justify-center font-black text-lg shrink-0">{{ substr($candidate->user->name ?? '?', 0, 1) }}</div>
                                    @endif
                                    
                                    <div class="min-w-0 flex-1">
                                        <p class="font-black leading-tight text-sm md:text-base truncate transition-colors {{ $isSelected ? 'text-orange-900' : 'text-gray-900' }}">{{ $candidate->user->name ?? 'Unknown' }}</p>
                                        <p class="text-[10px] font-bold text-gray-500 uppercase tracking-wider mt-0.5 truncate">{{ $candidate->program }}</p>
                                    </div>
                                </button>
                            @empty
                                <div class="col-span-full text-center py-6 bg-gray-50 rounded-2xl border border-dashed border-gray-200">
                                    <p class="text-gray-400 text-sm font-bold">No approved candidates for this position.</p>
                                </div>
                            @endforelse

                            {{-- MANDATORY ABSTAIN BUTTON --}}
                            @php
                                $isAbstainSelected = in_array('abstain', $selectedCandidates[$position->id] ?? []);
                            @endphp
                            <button type="button" 
                                    wire:click="toggleSelection({{ $position->id }}, 'abstain')"
                                    class="w-full text-left relative flex items-center gap-4 p-4 rounded-2xl border-2 border-dashed transition-all duration-200 ease-in-out group {{ $isAbstainSelected ? 'border-gray-500 bg-gray-200 shadow-inner' : 'border-gray-200 bg-gray-100/50 hover:border-gray-300' }}">
                                
                                <div class="w-6 h-6 rounded-full border-2 flex items-center justify-center shrink-0 transition-colors {{ $isAbstainSelected ? 'border-gray-600 bg-gray-600' : 'border-gray-300 group-hover:border-gray-400' }}">
                                    @if($isAbstainSelected)
                                        <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path></svg>
                                    @endif
                                </div>

                                <div class="min-w-0 flex-1">
                                    <p class="font-black text-gray-600 leading-tight text-sm md:text-base italic">Abstain / No Selection</p>
                                    <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest mt-0.5">Skip voting for this position</p>
                                </div>
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- THE UI FIX: A large empty spacer so the sticky button never overlaps candidates --}}
            <div class="h-40 md:h-12 w-full pointer-events-none"></div>

            {{-- STICKY MOBILE SUBMIT BUTTON --}}
            <div class="fixed bottom-0 left-0 w-full p-4 bg-white/90 backdrop-blur-md border-t border-gray-200 shadow-[0_-10px_20px_rgba(0,0,0,0.05)] z-50 md:static md:bg-transparent md:border-0 md:shadow-none md:p-0 md:pt-4">
                
                @if($errors->any())
                    <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-[10px] md:text-sm font-bold text-center flex justify-center items-center gap-2 animate-pulse">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        Validation failed! Please scroll up and complete the required fields.
                    </div>
                @endif

                <button type="submit" wire:loading.attr="disabled" class="w-full py-4 md:py-5 bg-gradient-to-r from-gray-900 to-black text-white font-black text-lg md:text-xl rounded-xl md:rounded-2xl shadow-xl transition-all flex items-center justify-center gap-2 hover:from-black hover:to-gray-800 transform active:scale-[0.98]">
                    <span wire:loading.remove wire:target="castBallot">Cast Official Ballot</span>
                    <span wire:loading wire:target="castBallot">Encrypting...</span>
                </button>
                <p class="hidden md:block text-center text-[10px] text-gray-400 font-bold uppercase tracking-wider mt-4">By casting this ballot, your choices become final and immutable.</p>
            </div>

        </form>
    @endif
</div>