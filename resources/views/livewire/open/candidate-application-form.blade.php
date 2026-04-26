<div class="max-w-4xl mx-auto py-12 px-4 sm:px-6 font-sans">
    
    <div class="text-center mb-10">
        <h1 class="text-3xl md:text-4xl font-black text-gray-900 tracking-tight mb-2">Electoral Candidacy Portal</h1>
        <p class="text-gray-500 font-medium">Submit your official application to the Board of Elections.</p>
    </div>

    @if($hasApplied)
        {{-- ALREADY APPLIED STATE --}}
        <div class="bg-white rounded-3xl p-10 text-center shadow-xl border border-gray-100 animate-fade-in-up">
            <div class="w-20 h-20 bg-blue-50 text-blue-500 rounded-full flex items-center justify-center mx-auto mb-6">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <h2 class="text-2xl font-black text-gray-900 mb-2">Application Submitted</h2>
            <p class="text-gray-500 mb-6 max-w-md mx-auto">You have already submitted an application for this specific election. It is currently under review by the Electoral Board.</p>
            <div class="inline-block bg-orange-100 text-orange-700 font-bold px-4 py-2 rounded-xl text-sm tracking-wider uppercase">
                Status: Pending Review
            </div>
        </div>
    @else
        {{-- THE APPLICATION FORM --}}
        <form wire:submit.prevent="submitApplication" class="space-y-8">
            
            {{-- MULTI-TENANT ELECTION SELECTION --}}
            <div class="bg-white rounded-3xl p-6 md:p-8 shadow-sm border border-gray-200">
                <h3 class="text-lg font-black text-gray-900 border-b border-gray-100 pb-4 mb-6 flex items-center gap-2">
                    <span class="w-6 h-6 rounded-full bg-orange-100 text-orange-600 flex items-center justify-center text-xs">1</span>
                    Election & Position
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5">Select Election</label>
                        {{-- Notice the wire:model.live so it triggers the dynamic positions! --}}
                        <select wire:model.live="election_id" class="w-full bg-gray-50 border border-gray-200 focus:border-orange-500 focus:bg-white rounded-xl px-4 py-3 text-sm font-bold text-gray-800 shadow-sm transition-colors cursor-pointer">
                            <option value="">-- Choose Election --</option>
                            @foreach($activeElections as $election)
                                <option value="{{ $election->id }}">{{ $election->title }}</option>
                            @endforeach
                        </select>
                        @error('election_id') <span class="text-[10px] text-red-500 font-bold mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5">Position Applied For</label>
                        <select wire:model="election_position_id" class="w-full bg-gray-50 border border-gray-200 focus:border-orange-500 focus:bg-white rounded-xl px-4 py-3 text-sm font-bold text-gray-800 shadow-sm transition-colors cursor-pointer" @if(empty($availablePositions)) disabled @endif>
                            <option value="">-- Select Position --</option>
                            @foreach($availablePositions as $pos)
                                <option value="{{ $pos->id }}">{{ $pos->title }} ({{ $pos->max_winners }} Seat/s)</option>
                            @endforeach
                        </select>
                        @error('election_position_id') <span class="text-[10px] text-red-500 font-bold mt-1 block">{{ $message }}</span> @enderror
                        @if(empty($availablePositions) && $election_id)
                            <span class="text-[10px] text-gray-400 font-bold mt-1 block">Loading positions...</span>
                        @endif
                    </div>
                </div>
            </div>

            {{-- DEMOGRAPHICS --}}
            <div class="bg-white rounded-3xl p-6 md:p-8 shadow-sm border border-gray-200">
                <h3 class="text-lg font-black text-gray-900 border-b border-gray-100 pb-4 mb-6 flex items-center gap-2">
                    <span class="w-6 h-6 rounded-full bg-orange-100 text-orange-600 flex items-center justify-center text-xs">2</span>
                    Personal Details
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5">College</label>
                        <select wire:model="college_id" class="w-full bg-gray-50 border border-gray-200 focus:border-orange-500 focus:bg-white rounded-xl px-4 py-3 text-sm shadow-sm transition-colors">
                            <option value="">-- Select College --</option>
                            @foreach($colleges as $college)
                                <option value="{{ $college->id }}">{{ $college->name }}</option>
                            @endforeach
                        </select>
                        @error('college_id') <span class="text-[10px] text-red-500 font-bold mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5">Program / Course</label>
                        <input wire:model="program" type="text" class="w-full bg-gray-50 border border-gray-200 focus:border-orange-500 focus:bg-white rounded-xl px-4 py-3 text-sm shadow-sm transition-colors" placeholder="e.g. BS Information Technology">
                        @error('program') <span class="text-[10px] text-red-500 font-bold mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5">Year Level</label>
                        <select wire:model="year_level" class="w-full bg-gray-50 border border-gray-200 focus:border-orange-500 focus:bg-white rounded-xl px-4 py-3 text-sm shadow-sm transition-colors">
                            <option value="">-- Select --</option>
                            <option value="1st Year">1st Year</option>
                            <option value="2nd Year">2nd Year</option>
                            <option value="3rd Year">3rd Year</option>
                            <option value="4th Year">4th Year</option>
                            <option value="5th Year">5th Year</option>
                        </select>
                        @error('year_level') <span class="text-[10px] text-red-500 font-bold mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5">Current Address</label>
                        <textarea wire:model="address" rows="2" class="w-full bg-gray-50 border border-gray-200 focus:border-orange-500 focus:bg-white rounded-xl px-4 py-3 text-sm shadow-sm transition-colors resize-none" placeholder="City, Province"></textarea>
                        @error('address') <span class="text-[10px] text-red-500 font-bold mt-1 block">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            {{-- PLATFORMS --}}
            <div class="bg-white rounded-3xl p-6 md:p-8 shadow-sm border border-gray-200">
                <h3 class="text-lg font-black text-gray-900 border-b border-gray-100 pb-4 mb-6 flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <span class="w-6 h-6 rounded-full bg-orange-100 text-orange-600 flex items-center justify-center text-xs">3</span>
                        Platform of Governance
                    </div>
                    <button type="button" wire:click="addPlatform" class="text-xs font-bold text-blue-600 hover:text-blue-800 flex items-center gap-1 bg-blue-50 px-3 py-1.5 rounded-lg transition-colors">
                        <span>+</span> Add Platform
                    </button>
                </h3>

                <div class="space-y-4">
                    @foreach($platforms as $index => $platform)
                        <div class="p-5 border border-gray-200 rounded-2xl bg-gray-50 relative group" wire:key="platform-{{ $index }}">
                            @if(count($platforms) > 1)
                                <button type="button" wire:click="removePlatform({{ $index }})" class="absolute -top-3 -right-3 bg-red-100 text-red-600 hover:bg-red-600 hover:text-white w-8 h-8 rounded-full flex items-center justify-center shadow-md transition-colors opacity-0 group-hover:opacity-100">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                </button>
                            @endif

                            <input wire:model="platforms.{{ $index }}.title" type="text" class="w-full bg-white border border-gray-200 focus:border-orange-500 rounded-xl px-4 py-2.5 text-sm font-bold shadow-sm mb-3" placeholder="Platform Title (e.g., Transparent Budgeting)">
                            @error('platforms.'.$index.'.title') <span class="text-[10px] text-red-500 font-bold -mt-2 mb-2 block">{{ $message }}</span> @enderror
                            
                            <textarea wire:model="platforms.{{ $index }}.description" rows="3" class="w-full bg-white border border-gray-200 focus:border-orange-500 rounded-xl px-4 py-3 text-sm shadow-sm resize-none" placeholder="Describe how you will implement this platform..."></textarea>
                            @error('platforms.'.$index.'.description') <span class="text-[10px] text-red-500 font-bold mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- CREDENTIALS --}}
            <div class="bg-white rounded-3xl p-6 md:p-8 shadow-sm border border-gray-200">
                <h3 class="text-lg font-black text-gray-900 border-b border-gray-100 pb-4 mb-6 flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <span class="w-6 h-6 rounded-full bg-orange-100 text-orange-600 flex items-center justify-center text-xs">4</span>
                        Relevant Credentials
                    </div>
                    <button type="button" wire:click="addCredential" class="text-xs font-bold text-blue-600 hover:text-blue-800 flex items-center gap-1 bg-blue-50 px-3 py-1.5 rounded-lg transition-colors">
                        <span>+</span> Add Credential
                    </button>
                </h3>

                <div class="space-y-4">
                    @foreach($credentials as $index => $credential)
                        <div class="flex flex-col md:flex-row gap-3 relative group" wire:key="credential-{{ $index }}">
                            <div class="w-full md:w-1/3">
                                <select wire:model="credentials.{{ $index }}.type" class="w-full bg-gray-50 border border-gray-200 focus:border-orange-500 rounded-xl px-4 py-2.5 text-sm font-bold text-gray-700 shadow-sm">
                                    <option value="">-- Type --</option>
                                    <option value="Leadership">Leadership Role</option>
                                    <option value="Award">Award / Honor</option>
                                    <option value="Affiliation">Org Affiliation</option>
                                </select>
                                @error('credentials.'.$index.'.type') <span class="text-[10px] text-red-500 font-bold mt-1 block">{{ $message }}</span> @enderror
                            </div>
                            <div class="w-full md:w-2/3 relative">
                                <input wire:model="credentials.{{ $index }}.description" type="text" class="w-full bg-white border border-gray-200 focus:border-orange-500 rounded-xl px-4 py-2.5 text-sm shadow-sm pr-10" placeholder="e.g., Project Head, BU CircUITS 2025">
                                @error('credentials.'.$index.'.description') <span class="text-[10px] text-red-500 font-bold mt-1 block">{{ $message }}</span> @enderror
                                
                                @if(count($credentials) > 1)
                                    <button type="button" wire:click="removeCredential({{ $index }})" class="absolute right-2 top-2 text-gray-300 hover:text-red-500 transition-colors p-1">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- ATTACHMENTS --}}
            <div class="bg-white rounded-3xl p-6 md:p-8 shadow-sm border border-gray-200">
                <h3 class="text-lg font-black text-gray-900 border-b border-gray-100 pb-4 mb-6 flex items-center gap-2">
                    <span class="w-6 h-6 rounded-full bg-orange-100 text-orange-600 flex items-center justify-center text-xs">5</span>
                    Attachments & Verification
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    
                    {{-- Profile Photo Upload --}}
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-3 text-center">Formal Profile Photo</label>
                        <div class="flex flex-col items-center">
                            @if($profile_photo)
                                <img src="{{ $profile_photo->temporaryUrl() }}" class="w-32 h-32 object-cover rounded-2xl border-4 border-white shadow-lg mb-4">
                            @else
                                <div class="w-32 h-32 bg-gray-100 rounded-2xl border-2 border-dashed border-gray-300 flex items-center justify-center text-gray-400 mb-4">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                </div>
                            @endif
                            
                            <label class="cursor-pointer bg-orange-50 hover:bg-orange-100 text-orange-700 px-4 py-2 rounded-xl text-sm font-bold transition shadow-sm border border-orange-100">
                                Browse Photo
                                <input type="file" wire:model="profile_photo" class="hidden" accept="image/*">
                            </label>
                            <div wire:loading wire:target="profile_photo" class="text-[10px] text-orange-500 font-bold mt-2 animate-pulse">Uploading...</div>
                            @error('profile_photo') <span class="text-[10px] text-red-500 font-bold mt-2 text-center">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    {{-- E-Signature Upload --}}
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-3 text-center">Scanned E-Signature (PNG)</label>
                        <div class="flex flex-col items-center">
                            @if($e_signature)
                                <div class="w-full h-32 bg-white rounded-2xl border-2 border-gray-200 shadow-inner flex items-center justify-center p-4 mb-4">
                                    <img src="{{ $e_signature->temporaryUrl() }}" class="max-h-full max-w-full object-contain mix-blend-multiply">
                                </div>
                            @else
                                <div class="w-full h-32 bg-gray-50 rounded-2xl border-2 border-dashed border-gray-300 flex flex-col items-center justify-center text-gray-400 mb-4 px-4 text-center">
                                    <svg class="w-6 h-6 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                    <span class="text-[10px] font-bold uppercase tracking-wider">Clear background preferred</span>
                                </div>
                            @endif

                            <label class="cursor-pointer bg-gray-900 hover:bg-gray-800 text-white px-4 py-2 rounded-xl text-sm font-bold transition shadow-md">
                                Upload Signature
                                <input type="file" wire:model="e_signature" class="hidden" accept="image/png, image/jpeg">
                            </label>
                            <div wire:loading wire:target="e_signature" class="text-[10px] text-orange-500 font-bold mt-2 animate-pulse">Uploading...</div>
                            @error('e_signature') <span class="text-[10px] text-red-500 font-bold mt-2 text-center">{{ $message }}</span> @enderror
                        </div>
                    </div>

                </div>
            </div>

            {{-- SUBMIT --}}
            <div class="pt-4">
                <button type="submit" wire:loading.attr="disabled" class="w-full py-4 md:py-5 bg-gradient-to-r from-orange-500 to-orange-600 hover:from-orange-600 hover:to-orange-700 text-white font-black text-lg rounded-2xl shadow-xl hover:shadow-orange-500/30 transition-all transform hover:-translate-y-1 flex items-center justify-center gap-2">
                    <span wire:loading.remove wire:target="submitApplication">Submit Candidacy for Vetting</span>
                    <span wire:loading wire:target="submitApplication" class="flex items-center gap-2">
                        <svg class="animate-spin -ml-1 mr-2 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        Processing Application...
                    </span>
                </button>
                <p class="text-center text-[10px] text-gray-400 font-bold uppercase tracking-wider mt-4">By submitting, you certify that all provided credentials are authentic.</p>
            </div>
        </form>
    @endif
</div>