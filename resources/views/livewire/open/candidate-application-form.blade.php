<div class="max-w-4xl mx-auto py-8 md:py-12 px-4 sm:px-6 font-sans pb-32">

    {{-- HEADER --}}
    <div class="bg-white rounded-[2rem] shadow-sm border border-gray-200 overflow-hidden mb-8 relative">
        @if($election->cover_photo_path)
            <img src="{{ asset('storage/'.$election->cover_photo_path) }}" class="w-full h-32 md:h-48 object-cover">
        @else
            <div class="w-full h-8 bg-gray-900"></div>
        @endif
        <div class="p-6 md:p-8 text-center relative">
            <div class="inline-flex items-center justify-center gap-2 bg-blue-50 text-blue-700 px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest mb-4">
                Official Filing of Candidacy
            </div>
            <h1 class="text-2xl md:text-4xl font-black text-gray-900 tracking-tight leading-tight">{{ $election->title }}</h1>
        </div>
    </div>

    {{-- STATE 1: ALREADY APPLIED (Takes ultimate precedence) --}}
    @if($hasApplied)
        <div class="bg-white rounded-[2rem] shadow-xl border border-gray-100 p-8 md:p-12 text-center animate-fade-in-up">
            <div class="w-20 h-20 bg-yellow-50 text-yellow-500 rounded-full flex items-center justify-center mx-auto mb-6 border border-yellow-100">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <h2 class="text-2xl md:text-3xl font-black text-gray-900 mb-4">Application Under Review</h2>
            <p class="text-gray-500 mb-8 max-w-md mx-auto leading-relaxed">Your Certificate of Candidacy has been successfully submitted. The Electoral Board will review your credentials before adding you to the official ballot.</p>
            <a href="/" class="inline-block px-8 py-3 bg-gray-100 text-gray-700 font-bold rounded-xl shadow-sm hover:bg-gray-200 transition">Return to Homepage</a>
        </div>

    {{-- STATE 2: UPCOMING (Filing hasn't started) --}}
    @elseif($applicationState === 'upcoming')
        <div class="bg-blue-50 rounded-[2rem] border border-blue-200 p-8 md:p-12 text-center animate-fade-in-up shadow-sm">
            <div class="w-20 h-20 bg-white text-blue-600 rounded-full flex items-center justify-center mx-auto mb-6 border border-blue-100 shadow-sm">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
            </div>
            <h2 class="text-2xl md:text-3xl font-black text-blue-900 mb-2">Filing Opens Soon</h2>
            <p class="text-blue-700 mb-8 max-w-md mx-auto font-medium">The official window for submitting candidacies will open on <br><span class="font-black">{{ $election->application_start->format('F j, Y \a\t g:i A') }}</span>.</p>
            <a href="/" class="inline-block px-8 py-3 bg-blue-600 text-white font-bold rounded-xl shadow-lg hover:bg-blue-700 transition">Return to Homepage</a>
        </div>

    {{-- STATE 3: CLOSED (Filing deadline passed) --}}
    @elseif($applicationState === 'closed')
        <div class="bg-red-50 rounded-[2rem] border border-red-200 p-8 md:p-12 text-center animate-fade-in-up shadow-sm">
            <div class="w-20 h-20 bg-white text-red-500 rounded-full flex items-center justify-center mx-auto mb-6 border border-red-100 shadow-sm">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
            </div>
            <h2 class="text-2xl md:text-3xl font-black text-red-900 mb-2">Filing Period Closed</h2>
            <p class="text-red-700 mb-8 max-w-md mx-auto font-medium">The deadline to file a Certificate of Candidacy for this election has officially passed.</p>
            <a href="/" class="inline-block px-8 py-3 bg-red-600 text-white font-bold rounded-xl shadow-lg hover:bg-red-700 transition">Return to Homepage</a>
        </div>

    {{-- STATE 4: APPLICATION FORM IS OPEN --}}
    @else
        <form wire:submit.prevent="submitApplication" class="space-y-8 animate-fade-in-up">

            <div class="bg-green-50 border border-green-200 rounded-2xl p-4 text-center shadow-sm">
                <p class="text-green-800 font-bold text-sm flex items-center justify-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></span>
                    The candidacy filing window is currently open until <span class="font-black">{{ $election->application_end->format('M j, g:i A') }}</span>.
                </p>
            </div>

            @if(session()->has('error'))
                <div class="bg-red-50 text-red-700 p-4 rounded-xl border border-red-200 font-bold flex items-center gap-2">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    {{ session('error') }}
                </div>
            @endif

            {{-- SECTION 1: BASIC PROFILE --}}
            <div class="bg-white rounded-[2rem] p-6 md:p-8 shadow-sm border border-gray-200">
                <h3 class="text-xl font-black text-gray-900 mb-6 border-b border-gray-100 pb-4">1. Academic & Ballot Profile</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    <div class="md:col-span-2">
                        <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-2">Campaign Photo (Optional)</label>
                        <div class="flex items-center gap-5">
                            <div class="w-20 h-20 rounded-full bg-gray-50 border-2 border-dashed border-gray-300 flex items-center justify-center overflow-hidden shrink-0">
                                @if ($profile_photo)
                                    <img src="{{ $profile_photo->temporaryUrl() }}" class="w-full h-full object-cover">
                                @else
                                    <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                @endif
                            </div>
                            <div class="flex-1">
                                <input type="file" wire:model="profile_photo" accept="image/*" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2.5 file:px-5 file:rounded-full file:border-0 file:text-xs file:font-bold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 transition-colors cursor-pointer">
                                <div wire:loading wire:target="profile_photo" class="text-[10px] text-blue-500 font-bold mt-2 animate-pulse">Uploading preview...</div>
                                @error('profile_photo') <span class="text-[10px] text-red-500 font-bold block mt-1">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">Ballot Display Name</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                            </div>
                            <input wire:model="display_name" type="text" class="w-full pl-11 bg-gray-50 border border-gray-200 focus:bg-white focus:border-orange-500 rounded-xl px-4 py-3 text-sm font-black text-gray-900 shadow-sm transition-colors" placeholder="e.g., John 'JD' Doe">
                        </div>
                        <p class="text-[10px] text-gray-400 font-bold mt-1.5 flex items-center gap-1"><svg class="w-3 h-3 text-orange-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg> Exactly how your name appears on the ballot.</p>
                        @error('display_name') <span class="text-[10px] text-red-500 font-bold block mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">Position Running For</label>
                        <select wire:model="election_position_id" class="w-full bg-gray-50 border border-gray-200 focus:border-orange-500 rounded-xl px-4 py-3 text-sm font-bold text-gray-800 shadow-sm">
                            <option value="">Select Position...</option>
                            @foreach($positions as $pos) <option value="{{ $pos->id }}">{{ $pos->title }}</option> @endforeach
                        </select>
                        @error('election_position_id') <span class="text-[10px] text-red-500 font-bold block mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">College</label>
                        <select wire:model="college_id" class="w-full bg-gray-50 border border-gray-200 focus:border-orange-500 rounded-xl px-4 py-3 text-sm font-bold text-gray-800 shadow-sm">
                            <option value="">Select College...</option>
                            @foreach($colleges as $college) <option value="{{ $college->id }}">{{ $college->name }}</option> @endforeach
                        </select>
                        @error('college_id') <span class="text-[10px] text-red-500 font-bold block mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">Academic Program</label>
                        <input wire:model="program" type="text" class="w-full bg-gray-50 border border-gray-200 focus:border-orange-500 rounded-xl px-4 py-3 text-sm font-bold text-gray-800 shadow-sm" placeholder="e.g. BS Information Technology">
                        @error('program') <span class="text-[10px] text-red-500 font-bold block mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">Year Level</label>
                        <select wire:model="year_level" class="w-full bg-gray-50 border border-gray-200 focus:border-orange-500 rounded-xl px-4 py-3 text-sm font-bold text-gray-800 shadow-sm">
                            <option value="">Select Year...</option>
                            <option value="1st Year">1st Year</option>
                            <option value="2nd Year">2nd Year</option>
                            <option value="3rd Year">3rd Year</option>
                            <option value="4th Year">4th Year</option>
                            <option value="5th Year">5th Year</option>
                        </select>
                        @error('year_level') <span class="text-[10px] text-red-500 font-bold block mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            {{-- SECTION 2: GPOA --}}
            <div class="bg-white rounded-[2rem] p-6 md:p-8 shadow-sm border border-gray-200">
                <div class="flex items-center justify-between mb-6 border-b border-gray-100 pb-4">
                    <h3 class="text-xl font-black text-gray-900">2. General Plan of Action</h3>
                    <button type="button" wire:click="addPlatform" class="text-xs font-bold text-blue-600 bg-blue-50 px-3 py-1.5 rounded-lg hover:bg-blue-100 transition-colors">+ Add Platform</button>
                </div>

                <div class="space-y-6">
                    @foreach($platforms as $index => $platform)
                        <div class="bg-gray-50 p-5 rounded-2xl border border-gray-100 relative group">
                            @if(count($platforms) > 1)
                                <button type="button" wire:click="removePlatform({{ $index }})" class="absolute top-4 right-4 text-gray-400 hover:text-red-500 transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            @endif
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">Project / Initiative Title</label>
                                    <input wire:model="platforms.{{ $index }}.title" type="text" class="w-full bg-white border border-gray-200 focus:border-blue-500 rounded-xl px-4 py-2.5 text-sm font-bold shadow-sm" placeholder="e.g. Student Wifi Expansion">
                                    @error('platforms.'.$index.'.title') <span class="text-[10px] text-red-500 font-bold block mt-1">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">Description & Objectives</label>
                                    <textarea wire:model="platforms.{{ $index }}.description" rows="3" class="w-full bg-white border border-gray-200 focus:border-blue-500 rounded-xl px-4 py-3 text-sm resize-none shadow-sm" placeholder="Detail your plans for this initiative..."></textarea>
                                    @error('platforms.'.$index.'.description') <span class="text-[10px] text-red-500 font-bold block mt-1">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- SECTION 3: CREDENTIALS --}}
            <div class="bg-white rounded-[2rem] p-6 md:p-8 shadow-sm border border-gray-200">
                <div class="flex items-center justify-between mb-6 border-b border-gray-100 pb-4">
                    <h3 class="text-xl font-black text-gray-900">3. Credentials (Optional)</h3>
                    <button type="button" wire:click="addCredential" class="text-xs font-bold text-orange-600 bg-orange-50 px-3 py-1.5 rounded-lg hover:bg-orange-100 transition-colors">+ Add Credential</button>
                </div>

                @if(count($credentials) === 0)
                    <div class="text-center py-8">
                        <p class="text-gray-400 text-sm font-bold">You haven't added any credentials yet. Click the button above to add leadership experience or awards.</p>
                    </div>
                @else
                    <div class="space-y-4">
                        @foreach($credentials as $index => $credential)
                            <div class="bg-gray-50 p-4 rounded-2xl border border-gray-100 flex gap-4 items-start">
                                <div class="flex-1 grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <div class="md:col-span-1">
                                        <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">Type</label>
                                        <select wire:model="credentials.{{ $index }}.type" class="w-full bg-white border border-gray-200 focus:border-orange-500 rounded-xl px-3 py-2 text-sm font-bold">
                                            <option value="">Select...</option>
                                            <option value="Leadership Experience">Leadership Experience</option>
                                            <option value="Academic Award">Academic Award</option>
                                            <option value="Affiliation">Affiliation</option>
                                            <option value="Other">Other</option>
                                        </select>
                                        @error('credentials.'.$index.'.type') <span class="text-[10px] text-red-500 font-bold block mt-1">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="md:col-span-2">
                                        <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">Description</label>
                                        <input wire:model="credentials.{{ $index }}.description" type="text" class="w-full bg-white border border-gray-200 focus:border-orange-500 rounded-xl px-4 py-2 text-sm font-bold" placeholder="e.g. President of Student Council (2024-2025)">
                                        @error('credentials.'.$index.'.description') <span class="text-[10px] text-red-500 font-bold block mt-1">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                                <button type="button" wire:click="removeCredential({{ $index }})" class="mt-6 text-gray-400 hover:text-red-500 transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- SUBMIT BUTTON --}}
            <div class="bg-gray-900 rounded-[2rem] p-6 shadow-xl relative overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-r from-transparent to-white/5 pointer-events-none"></div>
                <div class="relative z-10 flex flex-col md:flex-row items-center justify-between gap-4">
                    <p class="text-xs text-gray-400 font-bold max-w-sm">By submitting, you certify that all information provided is accurate and complies with the electoral code.</p>
                    <button type="submit" wire:loading.attr="disabled" class="w-full md:w-auto px-8 py-4 bg-orange-600 text-white font-black text-lg rounded-xl shadow-lg hover:bg-orange-700 transition-colors flex items-center justify-center gap-2 transform active:scale-95">
                        <span wire:loading.remove wire:target="submitApplication">Submit Application</span>
                        <span wire:loading wire:target="submitApplication">Processing...</span>
                        <span wire:loading wire:target="profile_photo">Uploading Photo...</span>
                    </button>
                </div>
            </div>
        </form>
    @endif
</div>
