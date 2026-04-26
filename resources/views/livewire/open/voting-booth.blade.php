<div class="max-w-4xl mx-auto py-12 px-4 sm:px-6 font-sans">

    {{-- ELECTION HEADER --}}
    <div class="bg-white rounded-3xl shadow-sm border border-gray-200 overflow-hidden mb-8">
        @if($election->cover_photo_path)
            <img src="{{ asset('storage/'.$election->cover_photo_path) }}" class="w-full h-48 object-cover">
        @else
            <div class="w-full h-8 bg-gray-900"></div>
        @endif
        <div class="p-8 text-center">
            <h1 class="text-3xl font-black text-gray-900 tracking-tight">{{ $election->title }}</h1>
            @if($election->description)
                <p class="text-gray-500 mt-2">{{ $election->description }}</p>
            @endif
        </div>
    </div>

    {{-- ERROR STATE (Closed, Not Started, or Already Voted) --}}
    @if($errorMessage)
        <div class="bg-red-50 border border-red-200 rounded-3xl p-10 text-center animate-fade-in-up">
            <div class="w-16 h-16 bg-white text-red-500 rounded-full flex items-center justify-center mx-auto mb-4 shadow-sm border border-red-100">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
            </div>
            <h2 class="text-2xl font-black text-red-900 mb-2">Access Denied</h2>
            <p class="text-red-700 font-medium">{{ $errorMessage }}</p>
            <a href="/" class="inline-block mt-6 px-6 py-2.5 bg-red-600 text-white font-bold rounded-xl shadow-md hover:bg-red-700 transition">Return Home</a>
        </div>
    @endif

    {{-- PHASE: GUEST VERIFICATION --}}
    @if(!$errorMessage && $phase === 'verification')
        <div class="bg-white rounded-3xl p-8 shadow-sm border border-gray-200">
            <h2 class="text-xl font-black text-gray-900 mb-4 border-b border-gray-100 pb-4">Guest Voter Verification</h2>
            <p class="text-sm text-gray-500 mb-6">Since you are not logged in, please provide your details to verify your eligibility. Your email ensures one vote per student.</p>
            
            <form wire:submit.prevent="verifyGuest" class="space-y-5">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5">Full Name</label>
                        <input wire:model="guest_name" type="text" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-gray-900">
                        @error('guest_name') <span class="text-[10px] text-red-500 font-bold block">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5">University Email</label>
                        <input wire:model="guest_email" type="email" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-gray-900">
                        @error('guest_email') <span class="text-[10px] text-red-500 font-bold block">{{ $message }}</span> @enderror
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5">College</label>
                        <select wire:model="college_id" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-gray-900">
                            <option value="">-- Select --</option>
                            @foreach($colleges as $college)
                                <option value="{{ $college->id }}">{{ $college->name }}</option>
                            @endforeach
                        </select>
                        @error('college_id') <span class="text-[10px] text-red-500 font-bold block">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5">Program</label>
                        <input wire:model="program" type="text" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-gray-900">
                        @error('program') <span class="text-[10px] text-red-500 font-bold block">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5">Year Level</label>
                        <select wire:model="year_level" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-gray-900">
                            <option value="">-- Select --</option>
                            <option value="1st Year">1st Year</option>
                            <option value="2nd Year">2nd Year</option>
                            <option value="3rd Year">3rd Year</option>
                            <option value="4th Year">4th Year</option>
                            <option value="5th Year">5th Year</option>
                        </select>
                        @error('year_level') <span class="text-[10px] text-red-500 font-bold block">{{ $message }}</span> @enderror
                    </div>
                </div>
                <button type="submit" class="w-full py-4 bg-gray-900 text-white font-black rounded-xl shadow-lg hover:bg-gray-800 transition">Proceed to Ballot</button>
            </form>
        </div>
    @endif

    {{-- PHASE: THE OFFICIAL BALLOT --}}
    @if(!$errorMessage && $phase === 'ballot')
        
        @if (session()->has('error'))
            <div class="mb-6 bg-red-50 text-red-700 p-4 rounded-xl border border-red-200 font-bold flex items-center justify-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                {{ session('error') }}
            </div>
        @endif

        <form wire:submit.prevent="castVote" class="space-y-8">
            @foreach($election->positions as $position)
                <div class="bg-white rounded-3xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="bg-gray-50 border-b border-gray-200 p-5 flex justify-between items-center">
                        <h3 class="text-lg font-black text-gray-900 uppercase tracking-tight">{{ $position->title }}</h3>
                        <span class="text-xs font-bold text-blue-700 bg-blue-100 px-3 py-1 rounded-lg">Select up to {{ $position->max_winners }}</span>
                    </div>
                    
                    <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-4">
                        @forelse($position->candidates as $candidate)
                            <label class="relative flex items-center p-4 border rounded-2xl cursor-pointer hover:bg-gray-50 transition-colors {{ in_array($candidate->id, $selectedCandidates[$position->id] ?? []) ? 'border-gray-900 ring-1 ring-gray-900 bg-gray-50' : 'border-gray-200' }}">
                                
                                <input type="checkbox" wire:model.live="selectedCandidates.{{ $position->id }}" value="{{ $candidate->id }}" 
                                       class="w-5 h-5 text-gray-900 rounded border-gray-300 focus:ring-gray-900 mr-4">
                                
                                <div class="flex items-center gap-3">
                                    @if($candidate->profile_photo_path)
                                        <img src="{{ asset('storage/'.$candidate->profile_photo_path) }}" class="w-12 h-12 rounded-full object-cover shadow-sm border border-gray-200">
                                    @else
                                        <div class="w-12 h-12 rounded-full bg-gray-200 text-gray-500 flex items-center justify-center font-bold">{{ substr($candidate->user->name ?? '?', 0, 1) }}</div>
                                    @endif
                                    <div>
                                        <p class="font-black text-gray-900 leading-tight">{{ $candidate->user->name ?? 'Unknown' }}</p>
                                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">{{ $candidate->program }}</p>
                                    </div>
                                </div>
                            </label>
                        @empty
                            <div class="col-span-2 text-center text-gray-400 font-bold py-6">No approved candidates for this position.</div>
                        @endforelse
                    </div>
                </div>
            @endforeach

            <div class="pt-6">
                <button type="submit" wire:loading.attr="disabled" class="w-full py-5 bg-gradient-to-r from-gray-900 to-black hover:from-black hover:to-gray-900 text-white font-black text-xl rounded-2xl shadow-2xl transition-all transform hover:-translate-y-1 flex items-center justify-center gap-2">
                    <span wire:loading.remove wire:target="castVote">Cast Official Ballot</span>
                    <span wire:loading wire:target="castVote">Processing Secure Vote...</span>
                </button>
                <p class="text-center text-[10px] text-gray-400 font-bold uppercase tracking-wider mt-4">By casting this ballot, you confirm your selections. This action is final and anonymous.</p>
            </div>
        </form>
    @endif

    {{-- PHASE: SUCCESS --}}
    @if($phase === 'success')
        <div class="bg-white rounded-3xl shadow-xl border border-gray-100 p-12 text-center animate-fade-in-up">
            <div class="w-24 h-24 bg-green-50 text-green-500 rounded-full flex items-center justify-center mx-auto mb-6 shadow-inner border border-green-100">
                <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            </div>
            <h2 class="text-3xl font-black text-gray-900 mb-4">Vote Successfully Cast!</h2>
            <p class="text-gray-500 mb-8 max-w-md mx-auto text-lg leading-relaxed">Your ballot has been encrypted and anonymously dropped into the secure database. Thank you for participating in the electoral process.</p>
            <a href="/" class="inline-block px-8 py-3 bg-gray-100 text-gray-700 font-bold rounded-xl shadow-sm hover:bg-gray-200 transition">Back to Home</a>
        </div>
    @endif

</div>