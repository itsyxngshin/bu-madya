<style>
    /* Ensure Alpine dropdowns don't flash on page load */
    [x-cloak] { display: none !important; }
</style>

<div class="max-w-4xl mx-auto py-12 px-4 sm:px-6 font-sans">

    {{-- SUCCESS STATE --}}
    @if($hasApplied)
        <div class="bg-white rounded-3xl shadow-xl border border-gray-100 p-12 text-center animate-fade-in-up">
            <div class="w-24 h-24 bg-green-50 text-green-500 rounded-full flex items-center justify-center mx-auto mb-6 shadow-inner border border-green-100">
                <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            </div>
            <h2 class="text-3xl font-black text-gray-900 mb-4">Application Submitted!</h2>
            <p class="text-gray-500 mb-8 max-w-md mx-auto text-lg leading-relaxed">Your candidacy for <strong>{{ $election->title }}</strong> has been officially securely transmitted to the Electoral Board for vetting.</p>
            <a href="/" class="inline-block px-8 py-3 bg-gray-100 text-gray-700 font-bold rounded-xl shadow-sm hover:bg-gray-200 transition">Back to Home</a>
        </div>
    @else

        <div class="mb-10 text-center">
            <h1 class="text-4xl font-black text-gray-900 tracking-tight mb-3">File Your Candidacy</h1>
            <p class="text-gray-500 font-medium">Complete the form below to submit your official application to the Electoral Board.</p>
        </div>

        <form wire:submit.prevent="submitApplication" class="space-y-8">
            
            {{-- STEP 1: CANDIDACY DETAILS --}}
            <div class="bg-white rounded-3xl p-6 md:p-8 shadow-sm border border-gray-200">
                <h3 class="text-lg font-black text-gray-900 border-b border-gray-100 pb-4 mb-6 flex items-center gap-2">
                    <span class="w-6 h-6 rounded-full bg-orange-100 text-orange-600 flex items-center justify-center text-xs">1</span>
                    Candidacy Details
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    {{-- Locked Election Display --}}
                    <div class="bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 shadow-inner">
                        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Filing for Election</label>
                        <p class="text-sm font-black text-gray-900 truncate">{{ $election->title }}</p>
                    </div>

                    {{-- PREMIUM POSITION DROPDOWN --}}
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5">Position Applied For</label>
                        
                        <div x-data="{
                                open: false,
                                selectedId: @entangle('election_position_id'),
                                options: [
                                    @foreach($availablePositions as $pos)
                                        { id: '{{ $pos->id }}', label: '{{ addslashes($pos->title) }} ({{ $pos->max_winners }} Seat/s)' },
                                    @endforeach
                                ],
                                get selectedLabel() {
                                    let option = this.options.find(opt => opt.id == this.selectedId);
                                    return option ? option.label : '-- Select Position --';
                                }
                            }"
                            @click.away="open = false"
                            class="relative w-full"
                        >
                            <button @click="open = !open" type="button" :disabled="{{ empty($availablePositions) ? 'true' : 'false' }}"
                                    class="w-full bg-white border border-gray-200 focus:border-orange-500 focus:ring-2 focus:ring-orange-200/50 rounded-xl px-4 py-3 text-sm font-bold text-gray-800 shadow-sm transition-all cursor-pointer flex justify-between items-center disabled:bg-gray-50 disabled:text-gray-400">
                                <span x-text="selectedLabel" class="truncate" :class="!selectedId ? 'text-gray-400 font-medium' : ''"></span>
                                <svg class="w-5 h-5 text-gray-400 transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </button>

                            <div x-show="open" x-cloak
                                 x-transition:enter="transition ease-out duration-100"
                                 x-transition:enter-start="transform opacity-0 scale-95"
                                 x-transition:enter-end="transform opacity-100 scale-100"
                                 x-transition:leave="transition ease-in duration-75"
                                 x-transition:leave-start="transform opacity-100 scale-100"
                                 x-transition:leave-end="transform opacity-0 scale-95"
                                 class="absolute z-50 w-full mt-2 bg-white border border-gray-100 rounded-xl shadow-xl overflow-hidden">
                                <ul class="max-h-60 overflow-y-auto p-1">
                                    <template x-for="option in options" :key="option.id">
                                        <li @click="selectedId = option.id; open = false"
                                            class="px-4 py-2.5 text-sm font-bold rounded-lg cursor-pointer transition-colors flex items-center justify-between"
                                            :class="selectedId == option.id ? 'bg-orange-50 text-orange-600' : 'text-gray-700 hover:bg-gray-50 hover:text-gray-900'">
                                            <span x-text="option.label"></span>
                                            <svg x-show="selectedId == option.id" class="w-4 h-4 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                        </li>
                                    </template>
                                </ul>
                            </div>
                        </div>
                        @error('election_position_id') <span class="text-[10px] text-red-500 font-bold mt-1 block">{{ $message }}</span> @enderror
                    </div>
                </div>

                @if(!$isApplicationOpen)
                    <div class="mt-6 bg-red-50 text-red-700 p-4 rounded-xl border border-red-200 font-bold flex items-center justify-center gap-2 text-sm">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        The application filing period for this election is currently closed.
                    </div>
                @endif
            </div>

            {{-- STEP 2: PERSONAL INFORMATION --}}
            <div class="bg-white rounded-3xl p-6 md:p-8 shadow-sm border border-gray-200">
                <h3 class="text-lg font-black text-gray-900 border-b border-gray-100 pb-4 mb-6 flex items-center gap-2">
                    <span class="w-6 h-6 rounded-full bg-orange-100 text-orange-600 flex items-center justify-center text-xs">2</span>
                    Personal & Academic Profile
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5">
                    
                    {{-- PREMIUM COLLEGE DROPDOWN --}}
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5">College</label>
                        <div x-data="{
                                open: false,
                                selectedId: @entangle('college_id'),
                                options: [
                                    @foreach($colleges as $college)
                                        { id: '{{ $college->id }}', label: '{{ addslashes($college->name) }}' },
                                    @endforeach
                                ],
                                get selectedLabel() {
                                    let option = this.options.find(opt => opt.id == this.selectedId);
                                    return option ? option.label : '-- Select College --';
                                }
                            }"
                            @click.away="open = false"
                            class="relative w-full"
                        >
                            <button @click="open = !open" type="button"
                                    class="w-full bg-white border border-gray-200 focus:border-orange-500 focus:ring-2 focus:ring-orange-200/50 rounded-xl px-4 py-3 text-sm font-bold text-gray-800 shadow-sm transition-all cursor-pointer flex justify-between items-center">
                                <span x-text="selectedLabel" class="truncate" :class="!selectedId ? 'text-gray-400 font-medium' : ''"></span>
                                <svg class="w-5 h-5 text-gray-400 transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </button>

                            <div x-show="open" x-cloak
                                 x-transition:enter="transition ease-out duration-100"
                                 x-transition:enter-start="transform opacity-0 scale-95"
                                 x-transition:enter-end="transform opacity-100 scale-100"
                                 x-transition:leave="transition ease-in duration-75"
                                 x-transition:leave-start="transform opacity-100 scale-100"
                                 x-transition:leave-end="transform opacity-0 scale-95"
                                 class="absolute z-50 w-full mt-2 bg-white border border-gray-100 rounded-xl shadow-xl overflow-hidden">
                                <ul class="max-h-60 overflow-y-auto p-1">
                                    <template x-for="option in options" :key="option.id">
                                        <li @click="selectedId = option.id; open = false"
                                            class="px-4 py-2.5 text-sm font-bold rounded-lg cursor-pointer transition-colors flex items-center justify-between"
                                            :class="selectedId == option.id ? 'bg-orange-50 text-orange-600' : 'text-gray-700 hover:bg-gray-50 hover:text-gray-900'">
                                            <span x-text="option.label"></span>
                                            <svg x-show="selectedId == option.id" class="w-4 h-4 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                        </li>
                                    </template>
                                </ul>
                            </div>
                        </div>
                        @error('college_id') <span class="text-[10px] text-red-500 font-bold mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    {{-- PREMIUM YEAR LEVEL DROPDOWN --}}
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5">Year Level</label>
                        <div x-data="{
                                open: false,
                                selectedValue: @entangle('year_level'),
                                options: ['1st Year', '2nd Year', '3rd Year', '4th Year', '5th Year'],
                                get selectedLabel() {
                                    return this.selectedValue ? this.selectedValue : '-- Select Year --';
                                }
                            }"
                            @click.away="open = false"
                            class="relative w-full"
                        >
                            <button @click="open = !open" type="button"
                                    class="w-full bg-white border border-gray-200 focus:border-orange-500 focus:ring-2 focus:ring-orange-200/50 rounded-xl px-4 py-3 text-sm font-bold text-gray-800 shadow-sm transition-all cursor-pointer flex justify-between items-center">
                                <span x-text="selectedLabel" class="truncate" :class="!selectedValue ? 'text-gray-400 font-medium' : ''"></span>
                                <svg class="w-5 h-5 text-gray-400 transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </button>

                            <div x-show="open" x-cloak
                                 x-transition:enter="transition ease-out duration-100"
                                 x-transition:enter-start="transform opacity-0 scale-95"
                                 x-transition:enter-end="transform opacity-100 scale-100"
                                 x-transition:leave="transition ease-in duration-75"
                                 x-transition:leave-start="transform opacity-100 scale-100"
                                 x-transition:leave-end="transform opacity-0 scale-95"
                                 class="absolute z-50 w-full mt-2 bg-white border border-gray-100 rounded-xl shadow-xl overflow-hidden">
                                <ul class="max-h-60 overflow-y-auto p-1">
                                    <template x-for="option in options" :key="option">
                                        <li @click="selectedValue = option; open = false"
                                            class="px-4 py-2.5 text-sm font-bold rounded-lg cursor-pointer transition-colors flex items-center justify-between"
                                            :class="selectedValue === option ? 'bg-orange-50 text-orange-600' : 'text-gray-700 hover:bg-gray-50 hover:text-gray-900'">
                                            <span x-text="option"></span>
                                            <svg x-show="selectedValue === option" class="w-4 h-4 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                        </li>
                                    </template>
                                </ul>
                            </div>
                        </div>
                        @error('year_level') <span class="text-[10px] text-red-500 font-bold mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    {{-- Program & Address --}}
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5">Academic Program / Course</label>
                        <input wire:model="program" type="text" class="w-full bg-white border border-gray-200 focus:border-orange-500 focus:ring-2 focus:ring-orange-200/50 rounded-xl px-4 py-3 text-sm font-bold text-gray-800 shadow-sm transition-colors" placeholder="e.g. BS Information Technology">
                        @error('program') <span class="text-[10px] text-red-500 font-bold mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5">Current Address</label>
                        <input wire:model="address" type="text" class="w-full bg-white border border-gray-200 focus:border-orange-500 focus:ring-2 focus:ring-orange-200/50 rounded-xl px-4 py-3 text-sm font-bold text-gray-800 shadow-sm transition-colors" placeholder="City, Province">
                        @error('address') <span class="text-[10px] text-red-500 font-bold mt-1 block">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            {{-- STEP 3: ELECTION PLATFORMS --}}
            <div class="bg-white rounded-3xl p-6 md:p-8 shadow-sm border border-gray-200">
                <div class="flex justify-between items-center border-b border-gray-100 pb-4 mb-6">
                    <h3 class="text-lg font-black text-gray-900 flex items-center gap-2">
                        <span class="w-6 h-6 rounded-full bg-orange-100 text-orange-600 flex items-center justify-center text-xs">3</span>
                        Platforms & Vision
                    </h3>
                    <button type="button" wire:click="addPlatform" class="text-xs font-bold text-orange-600 bg-orange-50 hover:bg-orange-100 px-4 py-2 rounded-xl transition-colors shadow-sm">
                        + Add Platform
                    </button>
                </div>

                <div class="space-y-6">
                    @foreach($platforms as $index => $platform)
                        {{-- CRITICAL FIX: wire:key added here --}}
                        <div wire:key="platform-{{ $index }}" class="relative bg-gray-50 p-5 rounded-2xl border border-gray-200 shadow-inner">
                            @if(count($platforms) > 1)
                                <button type="button" wire:click="removePlatform({{ $index }})" class="absolute top-4 right-4 text-gray-400 hover:text-red-500 transition">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            @endif

                            <div class="pr-10">
                                <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">Platform Title</label>
                                <input wire:model="platforms.{{ $index }}.title" type="text" class="w-full bg-white border border-gray-200 focus:border-orange-500 rounded-xl px-4 py-2.5 text-sm font-bold text-gray-800 shadow-sm mb-3" placeholder="e.g. Student Welfare Initiative">
                                @error("platforms.$index.title") <span class="text-[10px] text-red-500 font-bold block mb-2">{{ $message }}</span> @enderror
                                
                                <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">Description & Action Plan</label>
                                <textarea wire:model="platforms.{{ $index }}.description" rows="3" class="w-full bg-white border border-gray-200 focus:border-orange-500 rounded-xl px-4 py-3 text-sm text-gray-700 shadow-sm resize-none" placeholder="Elaborate on how you will implement this..."></textarea>
                                @error("platforms.$index.description") <span class="text-[10px] text-red-500 font-bold block mt-1">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- STEP 4: CREDENTIALS --}}
            <div class="bg-white rounded-3xl p-6 md:p-8 shadow-sm border border-gray-200">
                <div class="flex justify-between items-center border-b border-gray-100 pb-4 mb-6">
                    <h3 class="text-lg font-black text-gray-900 flex items-center gap-2">
                        <span class="w-6 h-6 rounded-full bg-orange-100 text-orange-600 flex items-center justify-center text-xs">4</span>
                        Qualifications
                    </h3>
                    <button type="button" wire:click="addCredential" class="text-xs font-bold text-orange-600 bg-orange-50 hover:bg-orange-100 px-4 py-2 rounded-xl transition-colors shadow-sm">
                        + Add Credential
                    </button>
                </div>

                <div class="space-y-6">
                    @foreach($credentials as $index => $credential)
                        {{-- CRITICAL FIX: wire:key added here --}}
                        <div wire:key="credential-{{ $index }}" class="relative bg-gray-50 p-5 rounded-2xl border border-gray-200 shadow-inner">
                            
                            @if(count($credentials) > 1)
                                <button type="button" wire:click="removeCredential({{ $index }})" class="absolute top-4 right-4 text-gray-400 hover:text-red-500 transition">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            @endif

                            <div class="pr-10 grid grid-cols-1 md:grid-cols-3 gap-5">
                                <div class="md:col-span-1">
                                    <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">Credential Type</label>
                                    
                                    <div x-data="{
                                            open: false,
                                            selectedValue: @entangle('credentials.'.$index.'.type'),
                                            options: ['Experience', 'Affiliation', 'Award'],
                                            get selectedLabel() {
                                                return this.selectedValue ? this.selectedValue : 'Select Type';
                                            }
                                        }"
                                        @click.away="open = false"
                                        class="relative w-full"
                                    >
                                        <button @click="open = !open" type="button"
                                                class="w-full bg-white border border-gray-200 focus:border-orange-500 focus:ring-2 focus:ring-orange-200/50 rounded-xl px-4 py-3 text-sm font-bold text-gray-800 shadow-sm transition-all cursor-pointer flex justify-between items-center">
                                            <span x-text="selectedLabel" class="truncate" :class="!selectedValue ? 'text-gray-400 font-medium' : ''"></span>
                                            <svg class="w-5 h-5 text-gray-400 transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                        </button>

                                        <div x-show="open" x-cloak
                                             x-transition:enter="transition ease-out duration-100"
                                             x-transition:enter-start="transform opacity-0 scale-95"
                                             x-transition:enter-end="transform opacity-100 scale-100"
                                             x-transition:leave="transition ease-in duration-75"
                                             x-transition:leave-start="transform opacity-100 scale-100"
                                             x-transition:leave-end="transform opacity-0 scale-95"
                                             class="absolute z-50 w-full mt-2 bg-white border border-gray-100 rounded-xl shadow-xl overflow-hidden">
                                            <ul class="max-h-60 overflow-y-auto p-1">
                                                <template x-for="option in options" :key="option">
                                                    <li @click="selectedValue = option; open = false"
                                                        class="px-4 py-2.5 text-sm font-bold rounded-lg cursor-pointer transition-colors flex items-center justify-between"
                                                        :class="selectedValue === option ? 'bg-orange-50 text-orange-600' : 'text-gray-700 hover:bg-gray-50 hover:text-gray-900'">
                                                        <span x-text="option"></span>
                                                        <svg x-show="selectedValue === option" class="w-4 h-4 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                                    </li>
                                                </template>
                                            </ul>
                                        </div>
                                    </div>
                                    @error('credentials.'.$index.'.type') <span class="text-[10px] text-red-500 font-bold block mt-1">{{ $message }}</span> @enderror
                                </div>

                                <div class="md:col-span-2">
                                    <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">Details (Organization, Year, etc.)</label>
                                    <input wire:model="credentials.{{ $index }}.description" type="text" class="w-full bg-white border border-gray-200 focus:border-orange-500 focus:ring-2 focus:ring-orange-200/50 rounded-xl px-4 py-3 text-sm font-bold text-gray-800 shadow-sm" placeholder="e.g. President, BU Student Council (2025)">
                                    @error('credentials.'.$index.'.description') <span class="text-[10px] text-red-500 font-bold block mt-1">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- STEP 5: ATTACHMENTS & SIGNATURE --}}
            <div class="bg-white rounded-3xl p-6 md:p-8 shadow-sm border border-gray-200">
                <h3 class="text-lg font-black text-gray-900 border-b border-gray-100 pb-4 mb-6 flex items-center gap-2">
                    <span class="w-6 h-6 rounded-full bg-orange-100 text-orange-600 flex items-center justify-center text-xs">5</span>
                    Official Requirements
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    
                    {{-- DRAG & DROP: Profile Photo --}}
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">2x2 Profile Photo</label>
                        <div x-data="{ isDropping: false }"
                             @dragover.prevent="isDropping = true"
                             @dragleave.prevent="isDropping = false"
                             @drop.prevent="isDropping = false; $refs.photoInput.files = $event.dataTransfer.files; $refs.photoInput.dispatchEvent(new Event('change', { bubbles: true }));"
                             class="border-2 border-dashed rounded-2xl p-6 text-center transition-all relative group"
                             :class="isDropping ? 'border-orange-500 bg-orange-50 scale-[1.02]' : 'border-gray-300 bg-gray-50 hover:border-orange-500'">
                            
                            @if ($profile_photo)
                                <img src="{{ $profile_photo->temporaryUrl() }}" class="w-24 h-24 mx-auto rounded-full object-cover shadow-sm mb-3">
                            @else
                                <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center mx-auto shadow-sm mb-3 text-gray-400"
                                     :class="isDropping ? 'text-orange-500 scale-110 transition-transform' : ''">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                </div>
                            @endif
                            
                            {{-- CRITICAL FIX: z-50 ensures click works perfectly --}}
                            <input type="file" x-ref="photoInput" wire:model="profile_photo" id="profile_photo" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-50" accept="image/*">
                            
                            <span class="text-sm font-bold transition-colors" :class="isDropping ? 'text-orange-600' : 'text-gray-700 group-hover:text-orange-600'">Drag & Drop or Click to upload</span>
                            <p class="text-[10px] text-gray-400 mt-1">PNG, JPG up to 2MB</p>
                            
                            <div wire:loading wire:target="profile_photo" class="text-[10px] font-bold text-orange-500 mt-2 animate-pulse">Processing image...</div>
                        </div>
                        @error('profile_photo') <span class="text-[10px] text-red-500 font-bold mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    {{-- DRAG & DROP: E-Signature --}}
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">E-Signature</label>
                        <div x-data="{ isDropping: false }"
                             @dragover.prevent="isDropping = true"
                             @dragleave.prevent="isDropping = false"
                             @drop.prevent="isDropping = false; $refs.sigInput.files = $event.dataTransfer.files; $refs.sigInput.dispatchEvent(new Event('change', { bubbles: true }));"
                             class="border-2 border-dashed rounded-2xl p-6 text-center transition-all relative group"
                             :class="isDropping ? 'border-orange-500 bg-orange-50 scale-[1.02]' : 'border-gray-300 bg-gray-50 hover:border-orange-500'">
                            
                            @if ($e_signature)
                                <img src="{{ $e_signature->temporaryUrl() }}" class="h-20 mx-auto object-contain mb-3">
                            @else
                                <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center mx-auto shadow-sm mb-3 text-gray-400"
                                     :class="isDropping ? 'text-orange-500 scale-110 transition-transform' : ''">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                </div>
                            @endif
                            
                            {{-- CRITICAL FIX: z-50 ensures click works perfectly --}}
                            <input type="file" x-ref="sigInput" wire:model="e_signature" id="e_signature" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-50" accept="image/*">
                            
                            <span class="text-sm font-bold transition-colors" :class="isDropping ? 'text-orange-600' : 'text-gray-700 group-hover:text-orange-600'">Drag & Drop or Click to upload</span>
                            <p class="text-[10px] text-gray-400 mt-1">Clear background preferred</p>
                            
                            <div wire:loading wire:target="e_signature" class="text-[10px] font-bold text-orange-500 mt-2 animate-pulse">Processing image...</div>
                        </div>
                        @error('e_signature') <span class="text-[10px] text-red-500 font-bold mt-1 block">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            {{-- SUBMIT ACTIONS --}}
            <div class="pt-6 border-t border-gray-200">
                @if($isApplicationOpen)
                    <button type="submit" wire:loading.attr="disabled" class="w-full py-5 bg-gradient-to-r from-gray-900 to-black hover:from-black hover:to-gray-900 text-white font-black text-xl rounded-2xl shadow-2xl transition-all transform hover:-translate-y-1 flex items-center justify-center gap-2">
                        <span wire:loading.remove wire:target="submitApplication">Submit Official Candidacy</span>
                        <span wire:loading wire:target="submitApplication">Encrypting & Submitting...</span>
                    </button>
                    <p class="text-center text-[10px] text-gray-400 font-bold uppercase tracking-wider mt-4">By submitting, you attest that all provided information is accurate and true.</p>
                @else
                    <button type="button" disabled class="w-full py-5 bg-gray-200 text-gray-400 font-black text-xl rounded-2xl shadow-none cursor-not-allowed flex items-center justify-center gap-2">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                        Application Window Closed
                    </button>
                @endif
            </div>

        </form>
    @endif
</div>