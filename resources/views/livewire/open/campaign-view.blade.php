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
                        
                        <div class="bg-gray-50 p-5 rounded-2xl border border-gray-100 space-y-4 shadow-inner">
                            
                            {{-- GUEST IDENTIFICATION (Only show if not logged in) --}}
                            @if(!auth()->check())
                                <div class="grid grid-cols-1 gap-3">
                                    <div>
                                        <input wire:model="guestName" type="text" class="w-full bg-white border-gray-200 focus:border-orange-500 focus:ring-orange-500 rounded-xl px-4 py-3 text-sm shadow-sm" placeholder="First and Last Name">
                                        @error('guestName') <span class="text-[10px] text-red-500 font-bold mt-1 block px-1">{{ $message }}</span> @enderror
                                    </div>
                                    <div>
                                        <input wire:model="guestEmail" type="email" class="w-full bg-white border-gray-200 focus:border-orange-500 focus:ring-orange-500 rounded-xl px-4 py-3 text-sm shadow-sm" placeholder="Email Address">
                                        @error('guestEmail') <span class="text-[10px] text-red-500 font-bold mt-1 block px-1">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                                <hr class="border-gray-200 my-2">
                            @endif

                            {{-- AFFILIATION DROPDOWN --}}
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5">Your Affiliation</label>
                                <select wire:model.live="affiliation" class="w-full bg-white border-gray-200 focus:border-orange-500 focus:ring-orange-500 rounded-xl px-4 py-3 text-sm shadow-sm font-semibold text-gray-700 cursor-pointer">
                                    <option value="student">Current BU Student</option>
                                    <option value="alumni">BU Alumni</option>
                                    <option value="faculty">BU Faculty / Staff</option>
                                    <option value="stakeholder">External Stakeholder / Citizen</option>
                                </select>
                            </div>

                            {{-- DYNAMIC STUDENT FIELDS (Slides open only for students) --}}
                            <div x-show="affiliation === 'student'" x-collapse x-cloak>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3 pt-1">
                                    
                                    {{-- Dynamic Database Colleges Dropdown --}}
                                    <div class="md:col-span-2">
                                        <select wire:model="college_id" class="w-full bg-white border-gray-200 focus:border-orange-500 focus:ring-orange-500 rounded-xl px-4 py-3 text-sm shadow-sm">
                                            <option value="">-- Select College --</option>
                                            @foreach($colleges as $c)
                                                <option value="{{ $c->id }}">{{ $c->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('college_id') <span class="text-[10px] text-red-500 font-bold mt-1 block px-1">{{ $message }}</span> @enderror
                                    </div>
                                    
                                    <div>
                                        <input wire:model="program" type="text" class="w-full bg-white border-gray-200 focus:border-orange-500 focus:ring-orange-500 rounded-xl px-4 py-3 text-sm shadow-sm" placeholder="Program (e.g. BSIT)">
                                        @error('program') <span class="text-[10px] text-red-500 font-bold mt-1 block px-1">{{ $message }}</span> @enderror
                                    </div>
                                    
                                    <div>
                                        <select wire:model="yearLevel" class="w-full bg-white border-gray-200 focus:border-orange-500 focus:ring-orange-500 rounded-xl px-4 py-3 text-sm shadow-sm cursor-pointer">
                                            <option value="">-- Year Level --</option>
                                            <option value="1st Year">1st Year</option>
                                            <option value="2nd Year">2nd Year</option>
                                            <option value="3rd Year">3rd Year</option>
                                            <option value="4th Year">4th Year</option>
                                            <option value="5th Year">5th Year</option>
                                        </select>
                                        @error('yearLevel') <span class="text-[10px] text-red-500 font-bold mt-1 block px-1">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            </div>

                            <p class="text-[10px] text-gray-400 font-medium text-center px-2 pt-2">By signing, you support this advocacy. Your email will remain private.</p>
                        </div>

                        {{-- SUBMIT BUTTON --}}
                        <button wire:click="signPetition" wire:loading.attr="disabled" class="w-full py-4 bg-gray-900 hover:bg-orange-600 text-white font-black text-lg rounded-2xl shadow-xl hover:shadow-orange-500/30 transition-all transform hover:-translate-y-0.5 flex items-center justify-center gap-2 group">
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