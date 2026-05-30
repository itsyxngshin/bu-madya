<div class="max-w-4xl mx-auto py-8 md:py-12 px-4 sm:px-6 font-sans pb-32">

    {{-- HEADER --}}
    <div class="bg-white rounded-[2rem] shadow-sm border border-gray-200 overflow-hidden mb-8 relative">
        @if($election->cover_photo_path)
            <img src="{{ asset('storage/'.$election->cover_photo_path) }}" class="w-full h-32 md:h-48 object-cover" alt="Election Cover Photo">
        @else
            <div class="w-full h-8 bg-gray-900"></div>
        @endif
        <div class="p-6 md:p-8 text-center relative">
            <div class="inline-flex items-center justify-center gap-2 bg-blue-50 text-blue-700 px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-widest mb-4 border border-blue-100">
                Official Filing of Candidacy
            </div>
            <h1 class="text-3xl md:text-4xl font-black text-gray-900 tracking-tight leading-tight">{{ $election->title }}</h1>
        </div>
    </div>

    {{-- STATE 1: ALREADY APPLIED (Takes ultimate precedence) --}}
    @if($hasApplied)
        <div class="bg-white rounded-[2rem] shadow-xl border border-gray-100 p-8 md:p-12 text-center animate-fade-in-up">
            <div class="w-20 h-20 bg-yellow-50 text-yellow-500 rounded-full flex items-center justify-center mx-auto mb-6 border border-yellow-100">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <h2 class="text-2xl md:text-3xl font-black text-gray-900 mb-4">Application Under Review</h2>
            <p class="text-gray-500 mb-8 max-w-md mx-auto leading-relaxed text-sm md:text-base">Your Certificate of Candidacy has been successfully submitted. The Electoral Board will review your credentials before adding you to the official ballot.</p>
            <a href="/" class="inline-block px-8 py-3 bg-gray-100 text-gray-700 font-bold rounded-xl shadow-sm hover:bg-gray-200 transition-colors">Return to Homepage</a>
        </div>

    {{-- STATE 2: UPCOMING (Filing hasn't started) --}}
    @elseif($applicationState === 'upcoming')
        <div class="bg-blue-50 rounded-[2rem] border border-blue-200 p-8 md:p-12 text-center animate-fade-in-up shadow-sm">
            <div class="w-20 h-20 bg-white text-blue-600 rounded-full flex items-center justify-center mx-auto mb-6 border border-blue-100 shadow-sm">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
            </div>
            <h2 class="text-2xl md:text-3xl font-black text-blue-900 mb-3">Filing Opens Soon</h2>
            <p class="text-blue-800 mb-8 max-w-md mx-auto text-sm md:text-base">The official window for submitting candidacies will open on <br><span class="font-black">{{ $election->application_start->format('F j, Y \a\t g:i A') }}</span>.</p>
            <a href="/" class="inline-block px-8 py-3 bg-blue-600 text-white font-bold rounded-xl shadow-md hover:bg-blue-700 transition-colors focus:ring-4 focus:ring-blue-600/20">Return to Homepage</a>
        </div>

    {{-- STATE 3: CLOSED (Filing deadline passed) --}}
    @elseif($applicationState === 'closed')
        <div class="bg-red-50 rounded-[2rem] border border-red-200 p-8 md:p-12 text-center animate-fade-in-up shadow-sm">
            <div class="w-20 h-20 bg-white text-red-500 rounded-full flex items-center justify-center mx-auto mb-6 border border-red-100 shadow-sm">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
            </div>
            <h2 class="text-2xl md:text-3xl font-black text-red-900 mb-3">Filing Period Closed</h2>
            <p class="text-red-800 mb-8 max-w-md mx-auto text-sm md:text-base">The deadline to file a Certificate of Candidacy for this election has officially passed.</p>
            <a href="/" class="inline-block px-8 py-3 bg-red-600 text-white font-bold rounded-xl shadow-md hover:bg-red-700 transition-colors focus:ring-4 focus:ring-red-600/20">Return to Homepage</a>
        </div>

    {{-- STATE 4: APPLICATION FORM IS OPEN --}}
    @else
        <form wire:submit.prevent="submitApplication" class="space-y-8 animate-fade-in-up">

            <div class="bg-green-50 border border-green-200 rounded-2xl p-4 text-center shadow-sm">
                <p class="text-green-800 font-bold text-sm flex items-center justify-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-green-500 animate-pulse shrink-0"></span>
                    The candidacy filing window is currently open until <span class="font-black">{{ $election->application_end->format('M j, g:i A') }}</span>.
                </p>
            </div>

            @if(session()->has('error'))
                <div class="bg-red-50 text-red-700 p-4 rounded-2xl border border-red-200 font-bold flex items-center gap-3">
                    <svg class="w-6 h-6 shrink-0 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    <span class="text-sm">{{ session('error') }}</span>
                </div>
            @endif

            {{-- SECTION 1: BASIC PROFILE --}}
            <div class="bg-white rounded-[2rem] p-6 md:p-8 shadow-sm border border-gray-200">
                <h3 class="text-xl font-black text-gray-900 mb-6 border-b border-gray-100 pb-4">1. Academic & Ballot Profile</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-3">Campaign Photo (Optional)</label>

                        <div class="bg-gray-50/50 rounded-2xl border border-gray-200 p-6 flex flex-col sm:flex-row items-center gap-6 transition-all hover:bg-gray-50 hover:border-blue-300 group focus-within:border-blue-400 focus-within:ring-4 focus-within:ring-blue-500/10">

                            {{-- Left Side: Upload Target & Preview --}}
                            <div class="relative shrink-0">
                                @if ($profile_photo)
                                    {{-- Preview State --}}
                                    <div class="w-32 h-32 rounded-full border-4 border-white shadow-md overflow-hidden relative group/avatar">
                                        <img src="{{ $profile_photo->temporaryUrl() }}" class="w-full h-full object-cover">

                                        {{-- Hover Overlay to Change Photo --}}
                                        <label class="absolute inset-0 bg-gray-900/60 flex flex-col items-center justify-center opacity-0 group-hover/avatar:opacity-100 transition-opacity cursor-pointer text-white">
                                            <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                            <span class="text-[10px] font-bold uppercase tracking-wider">Change</span>
                                            <input type="file" wire:model="profile_photo" accept="image/*" class="hidden">
                                        </label>
                                    </div>

                                    {{-- Remove Button --}}
                                    <button type="button" wire:click="$set('profile_photo', null)" class="absolute -top-1 -right-1 bg-white text-gray-400 hover:text-red-500 rounded-full p-1.5 shadow-md border border-gray-100 hover:bg-red-50 hover:border-red-200 transition-all transform hover:scale-110" title="Remove Photo">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                    </button>
                                @else
                                    {{-- Empty State --}}
                                    <label class="w-32 h-32 rounded-full border-2 border-dashed border-gray-300 flex flex-col items-center justify-center bg-white cursor-pointer hover:border-blue-500 hover:bg-blue-50 transition-colors text-gray-400 hover:text-blue-500 shadow-sm">
                                        <svg class="w-8 h-8 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                                        <span class="text-[10px] font-bold uppercase tracking-wider">Upload</span>
                                        <input type="file" wire:model="profile_photo" accept="image/*" class="hidden focus:outline-none">
                                    </label>
                                @endif
                            </div>

                            {{-- Right Side: Instructions & Validation --}}
                            <div class="flex-1 text-center sm:text-left">
                                <h4 class="text-sm font-bold text-gray-900 mb-1">Official Candidate Portrait</h4>
                                <p class="text-xs text-gray-500 leading-relaxed mb-4 max-w-sm">Ensure your photo is well-lit and professional. This image will be heavily featured on the ballot and official campaign materials.</p>

                                <div class="flex flex-wrap items-center justify-center sm:justify-start gap-2">
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-white border border-gray-200 text-[10px] font-bold text-gray-600 shadow-sm">
                                        <svg class="w-3.5 h-3.5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg> JPG, PNG
                                    </span>
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-white border border-gray-200 text-[10px] font-bold text-gray-600 shadow-sm">
                                        <svg class="w-3.5 h-3.5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"></path></svg> 1:1 Square
                                    </span>
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-white border border-gray-200 text-[10px] font-bold text-gray-600 shadow-sm">
                                        <svg class="w-3.5 h-3.5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4"></path></svg> Max 2MB
                                    </span>
                                </div>

                                {{-- Livewire Processing & Error Messages --}}
                                <div wire:loading wire:target="profile_photo" class="mt-3 flex items-center justify-center sm:justify-start gap-2 text-xs text-blue-600 font-bold bg-blue-50 py-1.5 px-3 rounded-lg w-fit">
                                    <svg class="animate-spin h-4 w-4 text-blue-600" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                    Processing image...
                                </div>

                                @error('profile_photo')
                                    <div class="mt-3 flex items-start sm:items-center gap-1.5 text-xs text-red-600 font-bold bg-red-50 py-2 px-3 rounded-lg border border-red-100">
                                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                        <span>{{ $message }}</span>
                                    </div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Ballot Display Name</label>
                        {{-- UPDATED: Flexbox Wrapper Setup --}}
                        <div class="flex items-center w-full bg-gray-50 border border-gray-200 focus-within:bg-white focus-within:border-orange-500 focus-within:ring-2 focus-within:ring-orange-500/20 rounded-xl shadow-sm transition-all overflow-hidden">
                            <div class="pl-4 pr-3 flex items-center justify-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            </div>
                            <input wire:model="display_name" type="text" class="w-full py-3 pr-4 bg-transparent border-0 focus:ring-0 text-sm font-medium text-gray-900 outline-none placeholder-gray-400" placeholder="e.g., John 'JD' Doe">
                        </div>
                        <p class="text-xs text-gray-500 mt-2 flex items-center gap-1.5"><svg class="w-4 h-4 text-orange-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg> Exactly how your name appears on the ballot.</p>
                        @error('display_name') <span class="text-xs text-red-500 font-semibold block mt-1.5">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Position Running For</label>
                        <select wire:model="election_position_id" class="w-full bg-gray-50 border border-gray-200 focus:bg-white focus:border-orange-500 focus:ring-2 focus:ring-orange-500/20 rounded-xl px-4 py-3 text-sm font-medium text-gray-900 shadow-sm transition-all outline-none">
                            <option value="">Select Position...</option>
                            @foreach($positions as $pos) <option value="{{ $pos->id }}">{{ $pos->title }}</option> @endforeach
                        </select>
                        @error('election_position_id') <span class="text-xs text-red-500 font-semibold block mt-1.5">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">College</label>
                        <select wire:model="college_id" class="w-full bg-gray-50 border border-gray-200 focus:bg-white focus:border-orange-500 focus:ring-2 focus:ring-orange-500/20 rounded-xl px-4 py-3 text-sm font-medium text-gray-900 shadow-sm transition-all outline-none">
                            <option value="">Select College...</option>
                            @foreach($colleges as $college) <option value="{{ $college->id }}">{{ $college->name }}</option> @endforeach
                        </select>
                        @error('college_id') <span class="text-xs text-red-500 font-semibold block mt-1.5">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Academic Program</label>
                        <input wire:model="program" type="text" class="w-full bg-gray-50 border border-gray-200 focus:bg-white focus:border-orange-500 focus:ring-2 focus:ring-orange-500/20 rounded-xl px-4 py-3 text-sm font-medium text-gray-900 shadow-sm transition-all outline-none" placeholder="e.g. BS Information Technology">
                        @error('program') <span class="text-xs text-red-500 font-semibold block mt-1.5">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Year Level</label>
                        <select wire:model="year_level" class="w-full bg-gray-50 border border-gray-200 focus:bg-white focus:border-orange-500 focus:ring-2 focus:ring-orange-500/20 rounded-xl px-4 py-3 text-sm font-medium text-gray-900 shadow-sm transition-all outline-none">
                            <option value="">Select Year...</option>
                            <option value="1st Year">1st Year</option>
                            <option value="2nd Year">2nd Year</option>
                            <option value="3rd Year">3rd Year</option>
                            <option value="4th Year">4th Year</option>
                            <option value="5th Year">5th Year</option>
                        </select>
                        @error('year_level') <span class="text-xs text-red-500 font-semibold block mt-1.5">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            {{-- SECTION 2: GPOA --}}
            <div class="bg-white rounded-[2rem] p-6 md:p-8 shadow-sm border border-gray-200">
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-6 border-b border-gray-100 pb-4 gap-4">
                    <h3 class="text-xl font-black text-gray-900">2. General Plan of Action</h3>
                    <button type="button" wire:click="addPlatform" class="text-xs font-bold text-blue-600 bg-blue-50 px-4 py-2 rounded-xl hover:bg-blue-100 transition-colors focus:ring-2 focus:ring-blue-500/50 outline-none">
                        + Add Platform
                    </button>
                </div>

                <div class="space-y-6">
                    @foreach($platforms as $index => $platform)
                        <div class="bg-gray-50 p-5 md:p-6 rounded-2xl border border-gray-200 relative group transition-all hover:border-blue-200">
                            @if(count($platforms) > 1)
                                <button type="button" wire:click="removePlatform({{ $index }})" class="absolute top-4 right-4 text-gray-400 hover:text-red-600 hover:bg-red-50 p-1.5 rounded-lg transition-colors focus:ring-2 focus:ring-red-500/50 outline-none" title="Remove Platform">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            @endif
                            <div class="space-y-4 pr-8">
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Project / Initiative Title</label>
                                    <input wire:model="platforms.{{ $index }}.title" type="text" class="w-full bg-white border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 rounded-xl px-4 py-2.5 text-sm font-medium text-gray-900 shadow-sm transition-all outline-none" placeholder="e.g. Student Wifi Expansion">
                                    @error('platforms.'.$index.'.title') <span class="text-xs text-red-500 font-semibold block mt-1.5">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Description & Objectives</label>
                                    <textarea wire:model="platforms.{{ $index }}.description" rows="3" class="w-full bg-white border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 rounded-xl px-4 py-3 text-sm font-medium text-gray-900 resize-none shadow-sm transition-all outline-none" placeholder="Detail your plans for this initiative..."></textarea>
                                    @error('platforms.'.$index.'.description') <span class="text-xs text-red-500 font-semibold block mt-1.5">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- SECTION 3: CREDENTIALS --}}
            <div class="bg-white rounded-[2rem] p-6 md:p-8 shadow-sm border border-gray-200">
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-6 border-b border-gray-100 pb-4 gap-4">
                    <h3 class="text-xl font-black text-gray-900">3. Credentials (Optional)</h3>
                    <button type="button" wire:click="addCredential" class="text-xs font-bold text-orange-600 bg-orange-50 px-4 py-2 rounded-xl hover:bg-orange-100 transition-colors focus:ring-2 focus:ring-orange-500/50 outline-none">
                        + Add Credential
                    </button>
                </div>

                @if(count($credentials) === 0)
                    <div class="text-center py-10 bg-gray-50 rounded-2xl border border-dashed border-gray-200">
                        <p class="text-gray-500 text-sm font-medium">You haven't added any credentials yet. <br class="hidden sm:block">Click the button above to add leadership experience or awards.</p>
                    </div>
                @else
                    <div class="space-y-4">
                        @foreach($credentials as $index => $credential)
                            <div class="bg-gray-50 p-5 rounded-2xl border border-gray-200 flex flex-col sm:flex-row gap-4 items-start relative group transition-all hover:border-orange-200">
                                <div class="flex-1 w-full grid grid-cols-1 md:grid-cols-3 gap-4 pr-8 sm:pr-0">
                                    <div class="md:col-span-1">
                                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Type</label>
                                        <select wire:model="credentials.{{ $index }}.type" class="w-full bg-white border border-gray-200 focus:border-orange-500 focus:ring-2 focus:ring-orange-500/20 rounded-xl px-3 py-2.5 text-sm font-medium text-gray-900 transition-all outline-none shadow-sm">
                                            <option value="">Select...</option>
                                            <option value="Leadership Experience">Leadership Experience</option>
                                            <option value="Academic Award">Academic Award</option>
                                            <option value="Affiliation">Affiliation</option>
                                            <option value="Other">Other</option>
                                        </select>
                                        @error('credentials.'.$index.'.type') <span class="text-xs text-red-500 font-semibold block mt-1.5">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="md:col-span-2">
                                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Description</label>
                                        <input wire:model="credentials.{{ $index }}.description" type="text" class="w-full bg-white border border-gray-200 focus:border-orange-500 focus:ring-2 focus:ring-orange-500/20 rounded-xl px-4 py-2.5 text-sm font-medium text-gray-900 transition-all outline-none shadow-sm" placeholder="e.g. President of Student Council (2024-2025)">
                                        @error('credentials.'.$index.'.description') <span class="text-xs text-red-500 font-semibold block mt-1.5">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                                <button type="button" wire:click="removeCredential({{ $index }})" class="absolute top-4 right-4 sm:static sm:mt-8 text-gray-400 hover:text-red-600 hover:bg-red-50 p-1.5 rounded-lg transition-colors focus:ring-2 focus:ring-red-500/50 outline-none" title="Remove Credential">
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
                <div class="relative z-10 flex flex-col md:flex-row items-center justify-between gap-6">
                    <p class="text-sm text-gray-400 font-medium max-w-md text-center md:text-left leading-relaxed">
                        By submitting, you certify that all information provided is accurate and complies with the electoral code.
                    </p>
                    <button type="submit"
                            wire:loading.attr="disabled"
                            class="w-full md:w-auto px-10 py-4 bg-orange-600 text-white font-black text-lg rounded-xl shadow-lg hover:bg-orange-500 transition-all flex items-center justify-center gap-3 transform active:scale-95 disabled:opacity-75 disabled:cursor-not-allowed disabled:hover:bg-orange-600 focus:outline-none focus:ring-4 focus:ring-orange-500/50">

                        <span wire:loading.remove wire:target="submitApplication, profile_photo">
                            Submit Application
                        </span>

                        <span wire:loading wire:target="submitApplication" class="flex items-center gap-2">
                            <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                            Processing...
                        </span>

                        <span wire:loading wire:target="profile_photo" class="flex items-center gap-2">
                            <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                            Uploading Photo...
                        </span>
                    </button>
                </div>
            </div>
        </form>
    @endif
</div>
