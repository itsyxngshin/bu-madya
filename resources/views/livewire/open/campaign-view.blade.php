<div class="max-w-5xl mx-auto py-12 px-4 sm:px-6 font-sans">
    
    {{-- ========================================== --}}
    {{-- HERO / COVER IMAGE --}}
    {{-- ========================================== --}}
    @if($campaign->cover_image)
        <div class="w-full h-64 md:h-96 mb-10 rounded-3xl overflow-hidden relative shadow-lg">
            <img src="{{ asset('storage/'.$campaign->cover_image) }}" class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-t from-gray-900/60 to-transparent"></div>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
        
        {{-- ========================================== --}}
        {{-- LEFT COLUMN: CAMPAIGN DETAILS --}}
        {{-- ========================================== --}}
        <div class="lg:col-span-7 xl:col-span-8">
            <h1 class="text-3xl md:text-5xl font-black text-gray-900 mb-6 leading-tight tracking-tight">{{ $campaign->title }}</h1>
            
            {{-- THE ORGANIZER BLOCK --}}
            <div class="flex items-center gap-4 py-5 mb-8 border-y border-gray-100 bg-gray-50/50 px-4 rounded-2xl">
                @if($campaign->creator->role?->role_name === 'organization')
                    @if($campaign->creator->profile_photo_path)
                        <img src="{{ asset('storage/'.$campaign->creator->profile_photo_path) }}" class="w-14 h-14 rounded-full border-2 border-white shadow-md object-cover">
                    @else
                        <div class="w-14 h-14 rounded-full bg-orange-100 text-orange-600 flex items-center justify-center font-black text-2xl border-2 border-white shadow-md">
                            {{ substr($campaign->creator->name, 0, 1) }}
                        </div>
                    @endif
                    <div>
                        <div class="flex items-center gap-1.5 mb-0.5">
                            <svg class="w-4 h-4 text-blue-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                            <p class="text-[10px] font-bold uppercase tracking-widest text-gray-500">Official Campaign</p>
                        </div>
                        <p class="text-base font-black text-gray-900">{{ $campaign->creator->name }}</p>
                    </div>
                @else
                    <div class="w-12 h-12 rounded-full bg-gray-200 text-gray-600 flex items-center justify-center font-bold text-xl border-2 border-white shadow-sm">
                        {{ substr($campaign->creator->name, 0, 1) }}
                    </div>
                    <div>
                        <p class="text-[10px] font-bold uppercase tracking-widest text-gray-400">Started by</p>
                        <p class="text-sm font-bold text-gray-700">{{ $campaign->creator->name }}</p>
                    </div>
                @endif
            </div>

            {{-- THE PETITION BODY (Markdown) --}}
            <div class="prose prose-lg prose-orange max-w-none text-gray-700 leading-relaxed">
                {!! Str::markdown($campaign->description) !!}
            </div>
        </div>

        {{-- ========================================== --}}
        {{-- RIGHT COLUMN: ACTION WIDGET --}}
        {{-- ========================================== --}}
        <div class="lg:col-span-5 xl:col-span-4 relative">
            
            <div class="bg-white p-6 md:p-8 rounded-[2rem] shadow-[0_8px_30px_rgb(0,0,0,0.08)] border border-gray-100 sticky top-8">
                
                {{-- Signature Counter --}}
                <div class="mb-6">
                    <div class="flex items-baseline gap-2 mb-1">
                        <span class="text-4xl md:text-5xl font-black text-gray-900 tracking-tighter">{{ number_format($signatureCount) }}</span>
                        <span class="text-sm font-bold text-gray-500 uppercase tracking-widest mb-1">Signatures</span>
                    </div>
                    <p class="text-sm text-gray-500 font-medium">Help us reach our goal of <strong class="text-gray-900">{{ number_format($campaign->target_signatures) }}</strong></p>
                </div>

                {{-- The Progress Bar --}}
                <div class="w-full bg-gray-100 rounded-full h-4 mb-8 overflow-hidden relative shadow-inner">
                    <div class="absolute top-0 left-0 h-full bg-gradient-to-r from-orange-400 to-orange-600 rounded-full transition-all duration-1000 ease-out shadow-[0_0_10px_rgba(234,88,12,0.5)]" 
                         style="width: {{ $progressPercentage }}%">
                        <div class="absolute top-0 left-0 w-full h-full bg-white/20" style="animation: shimmer 2s infinite linear; background-image: linear-gradient(90deg, transparent, rgba(255,255,255,0.4), transparent);"></div>
                    </div>
                </div>

                {{-- Interactive Form / Button --}}
                @if($hasSigned)
                    <div class="w-full py-5 bg-green-50 border border-green-200 text-green-700 font-black rounded-2xl text-center flex flex-col items-center justify-center gap-2 animate-fade-in-up">
                        <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mb-1">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        </div>
                        Thank you for signing!
                    </div>
                @else
                    <div class="space-y-4" x-data="{ affiliation: @entangle('affiliation') }">
                        <div class="bg-gray-50/80 p-5 rounded-[1.5rem] border border-gray-100 space-y-5 shadow-sm">
                            
                            {{-- GUEST IDENTIFICATION (Only show if not logged in) --}}
                            @if(!auth()->check())
                                <div class="grid grid-cols-1 gap-4">
                                    <div>
                                        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1.5 ml-1">Full Name</label>
                                        <input wire:model="guestName" type="text" class="w-full bg-white border border-gray-200 focus:border-orange-500 focus:ring-2 focus:ring-orange-500/20 rounded-xl px-4 py-3 text-sm shadow-sm transition-all outline-none" placeholder="e.g., Juan Dela Cruz">
                                        @error('guestName') <span class="text-[10px] text-red-500 font-bold mt-1 block px-1">{{ $message }}</span> @enderror
                                    </div>
                                    <div>
                                        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1.5 ml-1">Email Address</label>
                                        <input wire:model="guestEmail" type="email" class="w-full bg-white border border-gray-200 focus:border-orange-500 focus:ring-2 focus:ring-orange-500/20 rounded-xl px-4 py-3 text-sm shadow-sm transition-all outline-none" placeholder="juan@example.com">
                                        @error('guestEmail') <span class="text-[10px] text-red-500 font-bold mt-1 block px-1">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                                <div class="h-px bg-gradient-to-r from-transparent via-gray-200 to-transparent my-2"></div>
                            @endif

                            {{-- PREMIUM AFFILIATION DROPDOWN --}}
                            <div x-data="{
                                    open: false,
                                    value: @entangle('affiliation'),
                                    options: {
                                        'student': 'Current BU Student',
                                        'alumni': 'BU Alumni',
                                        'faculty': 'BU Faculty / Staff',
                                        'stakeholder': 'External Stakeholder / Citizen'
                                    },
                                    get selectedLabel() { return this.options[this.value] || 'Select Affiliation'; }
                                }" class="relative">
                                
                                <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1.5 ml-1">Your Affiliation</label>
                                
                                {{-- Custom Trigger Button --}}
                                <button @click="open = !open" @click.outside="open = false" type="button" 
                                        class="w-full bg-white border focus:outline-none rounded-xl px-4 py-3 text-sm shadow-sm font-semibold flex items-center justify-between transition-all"
                                        :class="open ? 'border-orange-500 ring-2 ring-orange-500/20 text-orange-700' : 'border-gray-200 text-gray-700 hover:border-gray-300'">
                                    <span x-text="selectedLabel"></span>
                                    <svg class="w-4 h-4 transition-transform duration-200" :class="open ? 'rotate-180 text-orange-500' : 'text-gray-400'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                </button>

                                {{-- Custom Dropdown Menu --}}
                                <div x-show="open" x-transition.opacity.duration.200ms x-cloak
                                    class="absolute z-50 w-full mt-2 bg-white border border-gray-100 rounded-xl shadow-xl overflow-hidden py-1">
                                    <template x-for="(label, key) in options" :key="key">
                                        <button type="button" @click="value = key; open = false" 
                                                class="w-full text-left px-4 py-2.5 text-sm font-medium transition-colors"
                                                :class="value === key ? 'bg-orange-50 text-orange-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900'">
                                            <div class="flex items-center justify-between">
                                                <span x-text="label"></span>
                                                <svg x-show="value === key" class="w-4 h-4 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                            </div>
                                        </button>
                                    </template>
                                </div>
                            </div>

                            {{-- DYNAMIC STUDENT FIELDS (Slides open only for students) --}}
                            <div x-show="affiliation === 'student'" x-collapse x-cloak>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-2">
                                    
                                    {{-- PREMIUM DATABASE COLLEGES DROPDOWN --}}
                                    @php
                                        $collegeOptions = $colleges->mapWithKeys(function($c) { return [$c->id => $c->name]; })->toArray();
                                    @endphp
                                    <div x-data="{
                                            open: false,
                                            value: @entangle('college_id'),
                                            options: {{ json_encode($collegeOptions) }},
                                            get selectedLabel() { return this.options[this.value] || '-- Select College --'; }
                                        }" class="relative md:col-span-2">
                                        
                                        <button @click="open = !open" @click.outside="open = false" type="button" 
                                                class="w-full bg-white border focus:outline-none rounded-xl px-4 py-3 text-sm shadow-sm font-semibold flex items-center justify-between transition-all"
                                                :class="open ? 'border-orange-500 ring-2 ring-orange-500/20 text-orange-700' : 'border-gray-200 text-gray-700 hover:border-gray-300'">
                                            <span x-text="selectedLabel" class="truncate pr-4"></span>
                                            <svg class="w-4 h-4 shrink-0 transition-transform duration-200" :class="open ? 'rotate-180 text-orange-500' : 'text-gray-400'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                        </button>

                                        <div x-show="open" x-transition.opacity.duration.200ms x-cloak
                                            class="absolute z-50 w-full mt-2 bg-white border border-gray-100 rounded-xl shadow-xl overflow-y-auto max-h-60 py-1 scrollbar-thin scrollbar-thumb-gray-200">
                                            <template x-for="(label, key) in options" :key="key">
                                                <button type="button" @click="value = key; open = false" 
                                                        class="w-full text-left px-4 py-2.5 text-sm font-medium transition-colors"
                                                        :class="value == key ? 'bg-orange-50 text-orange-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900'">
                                                    <span x-text="label"></span>
                                                </button>
                                            </template>
                                        </div>
                                        @error('college_id') <span class="text-[10px] text-red-500 font-bold mt-1 block px-1">{{ $message }}</span> @enderror
                                    </div>
                                    
                                    {{-- PROGRAM INPUT --}}
                                    <div>
                                        <input wire:model="program" type="text" class="w-full bg-white border border-gray-200 focus:border-orange-500 focus:ring-2 focus:ring-orange-500/20 rounded-xl px-4 py-3 text-sm shadow-sm transition-all outline-none font-medium text-gray-700" placeholder="Program (e.g. BSIT)">
                                        @error('program') <span class="text-[10px] text-red-500 font-bold mt-1 block px-1">{{ $message }}</span> @enderror
                                    </div>
                                    
                                    {{-- PREMIUM YEAR LEVEL DROPDOWN --}}
                                    <div x-data="{
                                            open: false,
                                            value: @entangle('yearLevel'),
                                            options: { '1st Year': '1st Year', '2nd Year': '2nd Year', '3rd Year': '3rd Year', '4th Year': '4th Year', '5th Year': '5th Year' },
                                            get selectedLabel() { return this.options[this.value] || '-- Year Level --'; }
                                        }" class="relative">
                                        
                                        <button @click="open = !open" @click.outside="open = false" type="button" 
                                                class="w-full bg-white border focus:outline-none rounded-xl px-4 py-3 text-sm shadow-sm font-semibold flex items-center justify-between transition-all"
                                                :class="open ? 'border-orange-500 ring-2 ring-orange-500/20 text-orange-700' : 'border-gray-200 text-gray-700 hover:border-gray-300'">
                                            <span x-text="selectedLabel"></span>
                                            <svg class="w-4 h-4 transition-transform duration-200" :class="open ? 'rotate-180 text-orange-500' : 'text-gray-400'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                        </button>

                                        <div x-show="open" x-transition.opacity.duration.200ms x-cloak
                                            class="absolute z-50 w-full mt-2 bg-white border border-gray-100 rounded-xl shadow-xl overflow-hidden py-1">
                                            <template x-for="(label, key) in options" :key="key">
                                                <button type="button" @click="value = key; open = false" 
                                                        class="w-full text-left px-4 py-2.5 text-sm font-medium transition-colors"
                                                        :class="value === key ? 'bg-orange-50 text-orange-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900'">
                                                    <span x-text="label"></span>
                                                </button>
                                            </template>
                                        </div>
                                        @error('yearLevel') <span class="text-[10px] text-red-500 font-bold mt-1 block px-1">{{ $message }}</span> @enderror
                                    </div>

                                </div>
                            </div>

                            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wide text-center px-2 pt-3">
                                <svg class="w-3 h-3 inline-block mr-1 -mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                Your information is securely encrypted
                            </p>
                        </div>

                        {{-- SUBMIT BUTTON --}}
                        <button wire:click="signPetition" wire:loading.attr="disabled" class="w-full py-4 bg-gray-900 hover:bg-orange-600 text-white font-black text-lg rounded-2xl shadow-[0_8px_20px_rgb(0,0,0,0.12)] hover:shadow-[0_8px_25px_rgba(234,88,12,0.3)] transition-all duration-300 transform hover:-translate-y-1 flex items-center justify-center gap-2 group mt-2 border border-gray-800 hover:border-orange-500">
                            <span wire:loading.remove wire:target="signPetition" class="flex items-center gap-2">
                                Sign Petition 
                                <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                            </span>
                            <span wire:loading wire:target="signPetition" class="flex items-center gap-2">
                                <svg class="animate-spin -ml-1 mr-2 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                Processing...
                            </span>
                        </button>
                    </div>
                @endif

            </div>
        </div>
    </div>
</div>

<style>
    @keyframes shimmer {
        0% { transform: translateX(-100%); }
        100% { transform: translateX(100%); }
    }
</style>