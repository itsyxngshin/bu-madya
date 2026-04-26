<style> [x-cloak] { display: none !important; } </style>

<div class="max-w-4xl mx-auto py-8 md:py-12 px-4 sm:px-6 font-sans pb-28 md:pb-12">

    @if($hasApplied)
        <div class="bg-white rounded-3xl shadow-xl border border-gray-100 p-8 md:p-12 text-center">
            <div class="w-20 h-20 md:w-24 md:h-24 bg-green-50 text-green-500 rounded-full flex items-center justify-center mx-auto mb-6">
                <svg class="w-10 h-10 md:w-12 md:h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            </div>
            <h2 class="text-2xl md:text-3xl font-black text-gray-900 mb-4">Application Submitted!</h2>
            <p class="text-gray-500 mb-8 max-w-md mx-auto text-sm md:text-lg">Your candidacy for <strong>{{ $election->title }}</strong> is securely transmitted.</p>
            <a href="/" class="inline-block px-8 py-3 bg-gray-100 text-gray-700 font-bold rounded-xl shadow-sm hover:bg-gray-200">Back to Home</a>
        </div>
    @else

        <div class="mb-8 md:mb-10 text-center">
            <h1 class="text-3xl md:text-4xl font-black text-gray-900 tracking-tight mb-2 md:mb-3">File Your Candidacy</h1>
            <p class="text-gray-500 font-medium text-sm md:text-base">Complete the form to submit your official application.</p>
        </div>

        <form wire:submit.prevent="submitApplication" class="space-y-6 md:space-y-8 relative">
            
            {{-- STEP 1: CANDIDACY DETAILS --}}
            <div class="bg-white rounded-[2rem] p-5 md:p-8 shadow-sm border border-gray-200">
                <h3 class="text-base md:text-lg font-black text-gray-900 border-b border-gray-100 pb-3 md:pb-4 mb-4 md:mb-6 flex items-center gap-2">
                    <span class="w-5 h-5 md:w-6 md:h-6 rounded-full bg-orange-100 text-orange-600 flex items-center justify-center text-[10px] md:text-xs">1</span>
                    Candidacy Details
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-5">
                    <div class="bg-gray-50 border border-gray-200 rounded-xl px-4 py-3">
                        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Election</label>
                        <p class="text-sm font-black text-gray-900 truncate">{{ $election->title }}</p>
                    </div>

                    <div>
                        <label class="block text-[10px] md:text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5">Position</label>
                        <div x-data="{ open: false, selectedId: @entangle('election_position_id'), options: [ @foreach($availablePositions as $pos) { id: '{{ $pos->id }}', label: '{{ addslashes($pos->title) }} ({{ $pos->max_winners }})' }, @endforeach ], get selectedLabel() { let o = this.options.find(opt => opt.id == this.selectedId); return o ? o.label : 'Select Position'; } }" @click.away="open = false" class="relative w-full">
                            <button @click="open = !open" type="button" class="w-full bg-white border border-gray-200 rounded-xl px-4 py-3 text-sm font-bold text-gray-800 flex justify-between items-center shadow-sm">
                                <span x-text="selectedLabel" class="truncate" :class="!selectedId ? 'text-gray-400 font-medium' : ''"></span>
                                <svg class="w-4 h-4 text-gray-400 transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </button>
                            <div x-show="open" x-cloak class="absolute z-50 w-full mt-1 bg-white border border-gray-100 rounded-xl shadow-xl overflow-hidden"><ul class="max-h-48 overflow-y-auto p-1"><template x-for="option in options" :key="option.id"><li @click="selectedId = option.id; open = false" class="px-4 py-2.5 text-sm font-bold rounded-lg cursor-pointer" :class="selectedId == option.id ? 'bg-orange-50 text-orange-600' : 'hover:bg-gray-50'"><span x-text="option.label"></span></li></template></ul></div>
                        </div>
                        @error('election_position_id') <span class="text-[10px] text-red-500 font-bold mt-1 block">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            {{-- STEP 2: PROFILE --}}
            <div class="bg-white rounded-[2rem] p-5 md:p-8 shadow-sm border border-gray-200">
                <h3 class="text-base md:text-lg font-black text-gray-900 border-b border-gray-100 pb-3 md:pb-4 mb-4 md:mb-6 flex items-center gap-2">
                    <span class="w-5 h-5 md:w-6 md:h-6 rounded-full bg-orange-100 text-orange-600 flex items-center justify-center text-[10px] md:text-xs">2</span>
                    Academic Profile
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-5 mb-4">
                    <div>
                        <label class="block text-[10px] md:text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5">College</label>
                        <div x-data="{ open: false, selectedId: @entangle('college_id'), options: [ @foreach($colleges as $college) { id: '{{ $college->id }}', label: '{{ addslashes($college->name) }}' }, @endforeach ], get selectedLabel() { let o = this.options.find(opt => opt.id == this.selectedId); return o ? o.label : 'Select College'; } }" @click.away="open = false" class="relative w-full">
                            <button @click="open = !open" type="button" class="w-full bg-white border border-gray-200 rounded-xl px-4 py-3 text-sm font-bold text-gray-800 flex justify-between items-center shadow-sm">
                                <span x-text="selectedLabel" class="truncate" :class="!selectedId ? 'text-gray-400 font-medium' : ''"></span>
                                <svg class="w-4 h-4 text-gray-400 transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </button>
                            <div x-show="open" x-cloak class="absolute z-50 w-full mt-1 bg-white border border-gray-100 rounded-xl shadow-xl overflow-hidden"><ul class="max-h-48 overflow-y-auto p-1"><template x-for="option in options" :key="option.id"><li @click="selectedId = option.id; open = false" class="px-4 py-2.5 text-sm font-bold rounded-lg cursor-pointer" :class="selectedId == option.id ? 'bg-orange-50 text-orange-600' : 'hover:bg-gray-50'"><span x-text="option.label"></span></li></template></ul></div>
                        </div>
                        @error('college_id') <span class="text-[10px] text-red-500 font-bold mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-[10px] md:text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5">Year Level</label>
                        <div x-data="{ open: false, selectedValue: @entangle('year_level'), options: ['1st Year', '2nd Year', '3rd Year', '4th Year', '5th Year'], get selectedLabel() { return this.selectedValue ? this.selectedValue : 'Select Year'; } }" @click.away="open = false" class="relative w-full">
                            <button @click="open = !open" type="button" class="w-full bg-white border border-gray-200 rounded-xl px-4 py-3 text-sm font-bold text-gray-800 flex justify-between items-center shadow-sm">
                                <span x-text="selectedLabel" class="truncate" :class="!selectedValue ? 'text-gray-400 font-medium' : ''"></span>
                                <svg class="w-4 h-4 text-gray-400 transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </button>
                            <div x-show="open" x-cloak class="absolute z-50 w-full mt-1 bg-white border border-gray-100 rounded-xl shadow-xl overflow-hidden"><ul class="max-h-48 overflow-y-auto p-1"><template x-for="option in options" :key="option"><li @click="selectedValue = option; open = false" class="px-4 py-2.5 text-sm font-bold rounded-lg cursor-pointer" :class="selectedValue === option ? 'bg-orange-50 text-orange-600' : 'hover:bg-gray-50'"><span x-text="option"></span></li></template></ul></div>
                        </div>
                        @error('year_level') <span class="text-[10px] text-red-500 font-bold mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-[10px] md:text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5">Program & Address</label>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <input wire:model="program" type="text" class="w-full bg-white border border-gray-200 rounded-xl px-4 py-3 text-sm font-bold text-gray-800" placeholder="e.g. BS IT">
                            <input wire:model="address" type="text" class="w-full bg-white border border-gray-200 rounded-xl px-4 py-3 text-sm font-bold text-gray-800" placeholder="City, Province">
                        </div>
                    </div>
                </div>
            </div>

            {{-- STEP 3: PLATFORMS --}}
            <div class="bg-white rounded-[2rem] p-5 md:p-8 shadow-sm border border-gray-200">
                <div class="flex justify-between items-center border-b border-gray-100 pb-3 md:pb-4 mb-4 md:mb-6">
                    <h3 class="text-base md:text-lg font-black text-gray-900 flex items-center gap-2">
                        <span class="w-5 h-5 md:w-6 md:h-6 rounded-full bg-orange-100 text-orange-600 flex items-center justify-center text-[10px] md:text-xs">3</span>
                        Platforms
                    </h3>
                    <button type="button" wire:click.prevent="addPlatform" class="text-[10px] md:text-xs font-bold text-orange-600 bg-orange-50 px-3 py-1.5 md:px-4 md:py-2 rounded-xl">+ Add</button>
                </div>

                <div class="space-y-4">
                    @foreach($platforms as $index => $platform)
                        <div wire:key="platform-{{ $platform['key'] ?? $index }}" class="relative bg-gray-50 p-4 md:p-5 rounded-2xl border border-gray-200">
                            @if(count($platforms) > 1)
                                <button type="button" wire:click.prevent="removePlatform({{ $index }})" class="absolute top-3 right-3 text-gray-400 hover:text-red-500"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg></button>
                            @endif
                            <div class="pr-8 md:pr-10">
                                <input wire:model="platforms.{{ $index }}.title" type="text" class="w-full bg-white border border-gray-200 rounded-xl px-4 py-2.5 text-sm font-bold text-gray-800 mb-3" placeholder="Platform Title">
                                <textarea wire:model="platforms.{{ $index }}.description" rows="3" class="w-full bg-white border border-gray-200 rounded-xl px-4 py-2.5 text-sm text-gray-700 resize-none" placeholder="Action Plan..."></textarea>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- STEP 4: CREDENTIALS --}}
            <div class="bg-white rounded-[2rem] p-5 md:p-8 shadow-sm border border-gray-200">
                <div class="flex justify-between items-center border-b border-gray-100 pb-3 md:pb-4 mb-4 md:mb-6">
                    <h3 class="text-base md:text-lg font-black text-gray-900 flex items-center gap-2">
                        <span class="w-5 h-5 md:w-6 md:h-6 rounded-full bg-orange-100 text-orange-600 flex items-center justify-center text-[10px] md:text-xs">4</span>
                        Qualifications
                    </h3>
                    <button type="button" wire:click.prevent="addCredential" class="text-[10px] md:text-xs font-bold text-orange-600 bg-orange-50 px-3 py-1.5 md:px-4 md:py-2 rounded-xl">+ Add</button>
                </div>

                <div class="space-y-4">
                    @foreach($credentials as $index => $credential)
                        <div wire:key="credential-{{ $credential['key'] ?? $index }}" class="relative bg-gray-50 p-4 md:p-5 rounded-2xl border border-gray-200">
                            @if(count($credentials) > 1)
                                <button type="button" wire:click.prevent="removeCredential({{ $index }})" class="absolute top-3 right-3 z-10 text-gray-400 hover:text-red-500"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg></button>
                            @endif
                            <div class="pr-8 md:pr-10 grid grid-cols-1 md:grid-cols-3 gap-3 md:gap-5">
                                <div class="md:col-span-1" x-data="{ open: false, selectedValue: @entangle('credentials.'.$index.'.type'), options: ['Experience', 'Affiliation', 'Award'], get selectedLabel() { return this.selectedValue ? this.selectedValue : 'Type'; } }" @click.away="open = false" class="relative w-full">
                                    <button @click="open = !open" type="button" class="w-full bg-white border border-gray-200 rounded-xl px-4 py-2.5 text-sm font-bold text-gray-800 flex justify-between items-center"><span x-text="selectedLabel"></span><svg class="w-4 h-4 text-gray-400" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 9l-7 7-7-7"></path></svg></button>
                                    <div x-show="open" x-cloak class="absolute z-50 w-full mt-1 bg-white border border-gray-100 rounded-xl shadow-xl"><ul class="p-1"><template x-for="option in options"><li @click="selectedValue = option; open = false" class="px-4 py-2 text-sm font-bold rounded-lg cursor-pointer hover:bg-gray-50"><span x-text="option"></span></li></template></ul></div>
                                </div>
                                <div class="md:col-span-2">
                                    <input wire:model="credentials.{{ $index }}.description" type="text" class="w-full bg-white border border-gray-200 rounded-xl px-4 py-2.5 text-sm font-bold text-gray-800" placeholder="Organization & Year">
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- STEP 5: UPLOADS --}}
            <div class="bg-white rounded-[2rem] p-5 md:p-8 shadow-sm border border-gray-200">
                <h3 class="text-base md:text-lg font-black text-gray-900 border-b border-gray-100 pb-3 md:pb-4 mb-4 md:mb-6 flex items-center gap-2">
                    <span class="w-5 h-5 md:w-6 md:h-6 rounded-full bg-orange-100 text-orange-600 flex items-center justify-center text-[10px] md:text-xs">5</span>
                    Attachments
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 md:gap-6">
                    <div x-data="{ isDropping: false }" @dragover.prevent="isDropping = true" @dragleave.prevent="isDropping = false" @drop.prevent="isDropping = false; $refs.photoInput.files = $event.dataTransfer.files; $refs.photoInput.dispatchEvent(new Event('change', { bubbles: true }));" class="border-2 border-dashed rounded-2xl p-6 text-center relative group" :class="isDropping ? 'border-orange-500 bg-orange-50' : 'border-gray-300 bg-gray-50'">
                        @if ($profile_photo) <img src="{{ $profile_photo->temporaryUrl() }}" class="w-20 h-20 mx-auto rounded-full object-cover mb-3"> @else <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center mx-auto mb-3 text-gray-400"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg></div> @endif
                        <input type="file" x-ref="photoInput" wire:model="profile_photo" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-50" accept="image/*">
                        <span class="text-xs font-bold text-gray-700">Upload 2x2 Photo</span>
                    </div>

                    <div x-data="{ isDropping: false }" @dragover.prevent="isDropping = true" @dragleave.prevent="isDropping = false" @drop.prevent="isDropping = false; $refs.sigInput.files = $event.dataTransfer.files; $refs.sigInput.dispatchEvent(new Event('change', { bubbles: true }));" class="border-2 border-dashed rounded-2xl p-6 text-center relative group" :class="isDropping ? 'border-orange-500 bg-orange-50' : 'border-gray-300 bg-gray-50'">
                        @if ($e_signature) <img src="{{ $e_signature->temporaryUrl() }}" class="h-16 mx-auto object-contain mb-3"> @else <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center mx-auto mb-3 text-gray-400"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg></div> @endif
                        <input type="file" x-ref="sigInput" wire:model="e_signature" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-50" accept="image/*">
                        <span class="text-xs font-bold text-gray-700">Upload E-Signature</span>
                    </div>
                </div>
            </div>

            {{-- STICKY MOBILE SUBMIT BUTTON --}}
            <div class="fixed bottom-0 left-0 w-full p-4 bg-white/90 backdrop-blur-md border-t border-gray-200 shadow-[0_-10px_20px_rgba(0,0,0,0.05)] z-50 md:static md:bg-transparent md:border-0 md:shadow-none md:p-0 md:pt-4">
                @if($isApplicationOpen)
                    <button type="submit" wire:loading.attr="disabled" class="w-full py-4 md:py-5 bg-gradient-to-r from-gray-900 to-black text-white font-black text-lg md:text-xl rounded-xl md:rounded-2xl shadow-xl transition-all flex items-center justify-center gap-2">
                        <span wire:loading.remove wire:target="submitApplication">Submit Application</span>
                        <span wire:loading wire:target="submitApplication">Processing...</span>
                    </button>
                    <p class="hidden md:block text-center text-[10px] text-gray-400 font-bold uppercase tracking-wider mt-4">By submitting, you attest that all information is accurate.</p>
                @endif
            </div>

        </form>
    @endif
</div>