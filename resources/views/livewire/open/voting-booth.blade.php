<div class="max-w-5xl mx-auto py-8 md:py-12 px-4 sm:px-6 font-sans pb-40 animate-fade-in-up">
    
    {{-- TOP NAVIGATION --}}
    <div class="flex items-center justify-between mb-8">
        <a href="{{ url('/') }}" class="inline-flex items-center gap-2 text-xs font-black text-gray-500 uppercase tracking-widest hover:text-red-600 transition-colors group bg-white px-5 py-2.5 rounded-full border border-gray-200 shadow-sm">
            <svg class="w-4 h-4 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Exit Booth
        </a>

        <div class="flex items-center gap-2 bg-gray-900 px-4 py-2 rounded-full shadow-sm">
            <span class="w-2.5 h-2.5 bg-green-500 rounded-full animate-pulse"></span>
            <span class="text-white text-[10px] font-black uppercase tracking-widest">Secure Connection</span>
        </div>
    </div>

    {{-- LOCKOUT STATES --}}
    @if(!$isVotingOpen)
        <div class="bg-white rounded-[2.5rem] shadow-xl border border-gray-100 p-10 md:p-16 text-center relative overflow-hidden">
            <div class="absolute top-0 left-0 right-0 h-4 bg-gradient-to-r from-red-600 via-yellow-400 to-green-600"></div>
            <div class="w-24 h-24 bg-gray-50 text-gray-400 rounded-full flex items-center justify-center mx-auto mb-6 border-4 border-white shadow-lg">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
            </div>
            <h2 class="text-3xl font-black text-gray-900 mb-2 tracking-tight">Voting is Closed</h2>
            <p class="text-gray-500 max-w-md mx-auto font-medium">The official voting period for this election is not currently active.</p>
        </div>
    @elseif($hasVoted)
        <div class="bg-white rounded-[2.5rem] shadow-xl border border-gray-100 p-10 md:p-16 text-center relative overflow-hidden">
            <div class="absolute top-0 left-0 right-0 h-4 bg-gradient-to-r from-red-600 via-yellow-400 to-green-600"></div>
            <div class="w-24 h-24 bg-green-50 text-green-500 rounded-full flex items-center justify-center mx-auto mb-6 border-4 border-white shadow-lg">
                <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path></svg>
            </div>
            <h2 class="text-3xl font-black text-gray-900 mb-2 tracking-tight">Ballot Successfully Cast</h2>
            <p class="text-gray-500 max-w-md mx-auto font-medium">Your vote has been securely recorded. Thank you for participating in the electoral process!</p>
        </div>
    @else

        {{-- ACTIVE VOTING BOOTH --}}
        
        {{-- ELECTION HEADER (FIXED: Cover Photo as Banner, Tri-Color as Fallback) --}}
        <div class="bg-white rounded-[2.5rem] shadow-lg border border-gray-200 overflow-hidden mb-8">
            
            {{-- Banner Area --}}
            @if($election->cover_photo_path)
                <img src="{{ asset('storage/'.$election->cover_photo_path) }}" class="w-full h-48 md:h-64 object-cover">
            @else
                <div class="w-full h-32 md:h-48 bg-gradient-to-r from-red-600 via-yellow-400 to-green-600 relative overflow-hidden">
                    <div class="absolute inset-0 opacity-20 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')]"></div>
                    <div class="absolute -bottom-16 left-1/2 -translate-x-1/2 w-96 h-96 bg-white/20 rounded-full blur-3xl pointer-events-none"></div>
                </div>
            @endif
            
            {{-- Text Area --}}
            <div class="p-6 md:p-10 text-center relative">
                <p class="text-[10px] font-black text-gray-500 uppercase tracking-[0.3em] mb-2">Official Electronic Ballot</p>
                
                <h1 class="text-3xl md:text-5xl font-black text-gray-900 tracking-tighter leading-tight mb-4 break-words">
                    {{ $election->title }}
                </h1>
                
                @if($election->description)
                    <p class="text-sm md:text-base text-gray-600 font-medium leading-relaxed mb-6 max-w-3xl mx-auto">
                        {{ $election->description }}
                    </p>
                @endif
                
                <div class="inline-flex items-center gap-2 px-3 py-1.5 bg-orange-50 border border-orange-100 rounded-lg shadow-sm mx-auto">
                    <svg class="w-4 h-4 text-orange-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    <p class="text-[11px] font-bold text-orange-700 uppercase tracking-wider">Please review your selections carefully.</p>
                </div>
            </div>
        </div>

        @if(session()->has('error'))
            <div class="mb-8 bg-red-50 text-red-700 p-4 rounded-2xl border border-red-200 shadow-sm flex items-center gap-3">
                <div class="w-8 h-8 rounded-full bg-red-100 flex items-center justify-center shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                </div>
                <p class="text-sm font-bold">{{ session('error') }}</p>
            </div>
        @endif

        {{-- GUEST VOTER DETAILS (If applicable) --}}
        @if(!auth()->check() && $election->allow_guest_voting)
            <div class="bg-white rounded-[2rem] p-6 md:p-8 shadow-sm border border-gray-200 mb-8">
                <h3 class="text-lg font-black text-gray-900 uppercase tracking-tight flex items-center gap-2 mb-6 border-b border-gray-100 pb-4">
                    <div class="w-8 h-8 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    </div>
                    Voter Authentication
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">Full Name</label>
                        <input wire:model="guest_name" type="text" class="w-full bg-gray-50 border border-gray-200 focus:border-blue-500 rounded-xl px-4 py-3 text-sm font-bold shadow-sm">
                        @error('guest_name') <span class="text-[10px] text-red-500 font-bold block mt-1">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">University Email</label>
                        <input wire:model="guest_email" type="email" class="w-full bg-gray-50 border border-gray-200 focus:border-blue-500 rounded-xl px-4 py-3 text-sm font-bold shadow-sm">
                        @error('guest_email') <span class="text-[10px] text-red-500 font-bold block mt-1">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">College</label>
                        <select wire:model="college_id" class="w-full bg-gray-50 border border-gray-200 focus:border-blue-500 rounded-xl px-4 py-3 text-sm font-bold shadow-sm">
                            <option value="">Select...</option>
                            @foreach($colleges as $college) <option value="{{ $college->id }}">{{ $college->name }}</option> @endforeach
                        </select>
                        @error('college_id') <span class="text-[10px] text-red-500 font-bold block mt-1">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">Program & Year</label>
                        <div class="flex gap-2">
                            <input wire:model="program" type="text" placeholder="e.g. BS IT" class="w-2/3 bg-gray-50 border border-gray-200 focus:border-blue-500 rounded-xl px-4 py-3 text-sm font-bold shadow-sm">
                            <select wire:model="year_level" class="w-1/3 bg-gray-50 border border-gray-200 focus:border-blue-500 rounded-xl px-2 py-3 text-sm font-bold shadow-sm">
                                <option value="">Year</option>
                                <option value="1st Year">1st</option><option value="2nd Year">2nd</option><option value="3rd Year">3rd</option><option value="4th Year">4th</option>
                            </select>
                        </div>
                        @error('program') <span class="text-[10px] text-red-500 font-bold block mt-1">{{ $message }}</span> @enderror
                        @error('year_level') <span class="text-[10px] text-red-500 font-bold block mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>
        @endif

        {{-- THE BALLOT POSITIONS --}}
        <div class="space-y-8">
            @foreach($positions as $position)
                @php 
                    $currentSelections = $selections[$position->id] ?? [];
                    $isAbstain = in_array('abstain', $currentSelections);
                    $selectionCount = count(array_filter($currentSelections, fn($val) => $val !== 'abstain'));
                @endphp
                
                <div class="bg-white rounded-[2rem] shadow-[0_4px_20px_rgba(0,0,0,0.03)] border border-gray-200 flex flex-col overflow-hidden" wire:key="pos-{{ $position->id }}">
                    
                    {{-- Security Ribbon --}}
                    <div class="h-2 w-full bg-gradient-to-r from-red-600 via-yellow-400 to-green-600"></div>

                    {{-- Position Header --}}
                    <div class="bg-gray-50 border-b-[3px] border-dashed border-gray-200 px-6 py-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div>
                            <h2 class="text-xl md:text-2xl font-black text-gray-900 tracking-tight uppercase font-serif">{{ $position->title }}</h2>
                        </div>
                        <div class="shrink-0 bg-white px-4 py-2 rounded-xl border border-gray-200 shadow-sm flex items-center gap-3">
                            <span class="text-[10px] font-black text-gray-500 uppercase tracking-widest">Select Up To</span>
                            <span class="w-8 h-8 rounded-lg bg-gray-900 text-white font-black flex items-center justify-center text-sm shadow-inner">{{ $position->max_winners }}</span>
                        </div>
                    </div>

                    {{-- Candidates Grid (Scantron Style Oval integrated into Candidate Card) --}}
                    <div class="p-6 md:p-8">
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                            
                            @foreach($position->candidates as $candidate)
                                @php 
                                    $isSelected = in_array($candidate->id, $currentSelections); 
                                    $candidateName = $candidate->display_name ?? optional($candidate->user)->name ?? 'Unknown';
                                @endphp
                                
                                <div wire:click="toggleSelection({{ $position->id }}, {{ $candidate->id }})" 
                                     class="relative p-5 rounded-2xl border-2 transition-all duration-300 cursor-pointer group flex flex-col gap-4 
                                            {{ $isSelected ? 'border-green-500 bg-green-50/30 shadow-sm transform -translate-y-1' : 'border-gray-100 bg-white hover:border-green-200 hover:bg-gray-50 hover:shadow-sm' }}">
                                    
                                    {{-- Scantron Oval / Checkbox --}}
                                    <div class="absolute top-5 right-5 w-8 h-5 rounded-full border-[3px] flex items-center justify-center transition-all duration-300 shadow-inner
                                                {{ $isSelected ? 'border-green-500 bg-green-500 text-white' : 'border-gray-300 bg-white group-hover:border-green-300 text-transparent' }}">
                                        @if($isSelected)
                                            <svg class="w-3 h-3 animate-fade-in" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="4"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path></svg>
                                        @endif
                                    </div>

                                    {{-- Avatar --}}
                                    <div class="w-16 h-16 rounded-full border-[3px] {{ $isSelected ? 'border-green-500' : 'border-gray-100' }} shadow-sm overflow-hidden shrink-0 bg-white transition-colors">
                                        @if($candidate->profile_photo_path)
                                            <img src="{{ asset('storage/'.$candidate->profile_photo_path) }}" class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full bg-gray-100 flex items-center justify-center font-black text-gray-400 text-xl">{{ substr($candidateName, 0, 1) }}</div>
                                        @endif
                                    </div>

                                    {{-- Info --}}
                                    <div class="flex-1">
                                        <h3 class="text-lg font-black text-gray-900 leading-tight mb-1 group-hover:text-green-700 transition-colors pr-8">{{ $candidateName }}</h3>
                                        <p class="text-xs font-bold text-gray-500">{{ $candidate->program }}</p>
                                    </div>

                                    {{-- Profile Link --}}
                                    <a href="{{ route('candidate.profile', $candidate->id) }}" target="_blank" @click.stop class="mt-auto text-[10px] font-black text-blue-500 hover:text-blue-700 uppercase tracking-widest flex items-center gap-1 w-max">
                                        View Profile <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                                    </a>
                                </div>
                            @endforeach

                            {{-- ABSTAIN OPTION --}}
                            <div wire:click="toggleSelection({{ $position->id }}, 'abstain')" 
                                 class="relative p-5 rounded-2xl border-2 transition-all duration-300 cursor-pointer group flex items-center gap-4 sm:col-span-full lg:col-span-1
                                        {{ $isAbstain ? 'border-orange-500 bg-orange-50/30 shadow-sm' : 'border-gray-100 border-dashed bg-gray-50 hover:border-orange-300 hover:bg-orange-50/50' }}">
                                
                                <div class="w-8 h-5 rounded-full border-[3px] flex items-center justify-center transition-all duration-300 shrink-0 shadow-inner
                                            {{ $isAbstain ? 'border-orange-500 bg-orange-500 text-white' : 'border-gray-300 bg-white group-hover:border-orange-300 text-transparent' }}">
                                    @if($isAbstain)
                                        <svg class="w-3 h-3 animate-fade-in" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="4"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path></svg>
                                    @endif
                                </div>

                                <div>
                                    <h3 class="text-base font-black text-gray-900 uppercase tracking-tight group-hover:text-orange-700 transition-colors">Abstain</h3>
                                    <p class="text-[10px] font-bold text-gray-500 uppercase tracking-widest mt-0.5">Leave position blank</p>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- FIXED SUBMIT BAR (FLOATING) --}}
        <div class="fixed bottom-0 left-0 right-0 z-50 p-4 md:p-6 bg-gradient-to-t from-white via-white/90 to-transparent pointer-events-none">
            <div class="max-w-5xl mx-auto pointer-events-auto">
                <div class="bg-gray-900 rounded-[2rem] p-4 md:p-6 shadow-2xl flex flex-col md:flex-row items-center justify-between gap-4 border border-gray-800">
                    <div class="flex items-center gap-3 text-white hidden md:flex">
                        <div class="w-10 h-10 rounded-full bg-gray-800 flex items-center justify-center border border-gray-700">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">End of Ballot</p>
                            <p class="text-sm font-black">Ready to submit?</p>
                        </div>
                    </div>
                    
                    <button wire:click="castBallot" wire:loading.attr="disabled" class="w-full md:w-auto px-10 py-4 bg-green-500 hover:bg-green-400 text-gray-900 text-lg font-black rounded-xl shadow-lg transition-all transform active:scale-95 flex items-center justify-center gap-2">
                        <span wire:loading.remove wire:target="castBallot">Cast My Ballot Now</span>
                        <span wire:loading wire:target="castBallot">Encrypting & Saving...</span>
                        <svg wire:loading.remove wire:target="castBallot" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </button>
                </div>
            </div>
        </div>

    @endif
</div>