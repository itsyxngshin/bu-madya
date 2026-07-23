<div class="max-w-5xl mx-auto py-12 px-4 sm:px-6 transition-colors duration-300">

    @if(!$isRegistrationOpen)
        {{-- SYSTEM LOCKDOWN SCREEN --}}
        <div class="bg-iba-light dark:bg-[#1A1617] border-4 border-iba-black dark:border-iba-light p-8 sm:p-16 shadow-[15px_15px_0_0_#D93B3B] animate-fade-in-up text-center relative overflow-hidden">

            {{-- Background Warning Pattern --}}
            <div class="absolute inset-0 opacity-5 dark:opacity-10 pointer-events-none" style="background-image: repeating-linear-gradient(45deg, #131011 25%, transparent 25%, transparent 75%, #131011 75%, #131011), repeating-linear-gradient(45deg, #131011 25%, transparent 25%, transparent 75%, #131011 75%, #131011); background-position: 0 0, 10px 10px; background-size: 20px 20px;"></div>

            <div class="relative z-10">
                <div class="mx-auto w-24 h-24 bg-iba-red border-4 border-iba-black dark:border-iba-light flex items-center justify-center shadow-[4px_4px_0_0_#131011] dark:shadow-[4px_4px_0_0_#FFFBF7] mb-8">
                    <svg class="h-12 w-12 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>

                <h2 class="font-pixel text-2xl sm:text-4xl text-iba-black dark:text-iba-light uppercase mb-6 border-b-8 border-iba-red inline-block pb-2">INTAKE CLOSED</h2>

                <p class="text-base sm:text-lg text-gray-700 dark:text-gray-300 font-bold max-w-2xl mx-auto mb-10 leading-relaxed uppercase tracking-wider">
                    The registration portal for the <span class="text-iba-red font-black">Heroes of Innovation Challenge 2026</span> has been officially locked. We are no longer accepting new cohort applications at this time.
                </p>

                <div class="bg-white dark:bg-iba-black border-4 border-iba-black dark:border-iba-light p-6 md:p-8 inline-block text-center shadow-[8px_8px_0_0_#131011] dark:shadow-[8px_8px_0_0_#FFFBF7] mb-12 max-w-xl w-full">
                    <h4 class="font-pixel text-iba-teal uppercase tracking-wider text-sm md:text-base mb-2">Notice to Applicants</h4>
                    <p class="text-sm sm:text-base text-gray-700 dark:text-gray-300 font-bold">
                        For teams that have successfully submitted their manifestos, please monitor your Team Leader's email inbox for the verdict of the Organizing Committee.
                    </p>
                </div>

                <div>
                    <a href="{{ route('ibalong.home') }}" class="btn-retro bg-iba-black dark:bg-iba-light text-white dark:text-iba-black font-pixel px-8 py-5 text-xs sm:text-sm uppercase inline-flex items-center gap-2 transition-transform shadow-[6px_6px_0_0_#0095AC] hover:translate-y-1 hover:shadow-none border-4 border-transparent dark:hover:border-white hover:border-iba-black">
                        ⬅ Return to Launchpad
                    </a>
                </div>
            </div>
        </div>

    @elseif($registrationSuccessful)
        {{-- ... EXACT EXISTING SUCCESS SCREEN HTML ... --}}
        {{-- SUCCESS SCREEN --}}
        <div class="bg-iba-light dark:bg-iba-black border-4 border-iba-black dark:border-iba-light p-8 sm:p-16 shadow-[15px_15px_0_0_#5C7914] animate-fade-in-up text-center relative overflow-hidden">

            <div class="absolute top-0 right-0 opacity-5 dark:opacity-10 pointer-events-none text-iba-green">
                <svg class="w-64 h-64 -mt-10 -mr-10" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2L2 22h20L12 2z"/></svg>
            </div>

            <div class="relative z-10">
                <div class="mx-auto w-24 h-24 bg-iba-green border-4 border-iba-black dark:border-iba-light flex items-center justify-center shadow-[4px_4px_0_0_#131011] dark:shadow-[4px_4px_0_0_#FFFBF7] mb-8 animate-bounce">
                    <svg class="h-12 w-12 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                    </svg>
                </div>

                <h2 class="font-pixel text-2xl sm:text-4xl text-iba-black dark:text-iba-light uppercase mb-6 border-b-8 border-iba-green inline-block pb-2">APPLICATION RECEIVED!</h2>

                <p class="text-base sm:text-lg text-gray-700 dark:text-gray-300 font-bold max-w-2xl mx-auto mb-10 leading-relaxed">
                    Your cohort's application for the <span class="text-iba-teal font-black">Heroes of Innovation Challenge 2026</span> has been successfully logged into our mainframe.
                </p>

                <div class="bg-white dark:bg-[#1A1617] border-4 border-iba-black dark:border-iba-light p-6 md:p-8 inline-block text-left shadow-[8px_8px_0_0_#0095AC] mb-12 max-w-xl w-full">
                    <h4 class="font-pixel text-iba-red uppercase tracking-wider text-sm md:text-base mb-4 border-b-2 border-dashed border-gray-300 dark:border-gray-700 pb-2">Transmission Status</h4>
                    <ul class="space-y-4 text-sm sm:text-base text-gray-700 dark:text-gray-300 font-bold">
                        <li class="flex items-start gap-3">
                            <span class="text-iba-green text-xl leading-none">✓</span>
                            <span>The Heroes of Innovation 2026 Organizing Committee is now reviewing your submission.</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <span class="text-iba-green text-xl leading-none">✓</span>
                            <span>An email confirmation has been dispatched to your Team Leader's inbox.</span>
                        </li>
                        <li class="flex items-start gap-3 text-gray-500 dark:text-gray-400">
                            <span class="text-gray-400 text-xl leading-none">⧖</span>
                            <span>If approved, you will receive your Community Center login credentials via email.</span>
                        </li>
                    </ul>
                </div>

                <div>
                    <a href="{{ route('ibalong.home') }}" class="btn-retro bg-iba-orange text-iba-black font-pixel px-8 py-5 text-xs sm:text-sm uppercase inline-flex items-center gap-2 hover:bg-orange-500 transition-colors shadow-[6px_6px_0_0_#131011] dark:shadow-[6px_6px_0_0_#FFFBF7] hover:translate-y-1 hover:shadow-none">
                        ⬅ Return to Launchpad
                    </a>
                </div>
            </div>
        </div>

    @else
        {{-- ... EXACT EXISTING FORM WRAPPER, HEADER BLOCK, AND STEPS HTML ... --}}

        {{-- HEADER BLOCK --}}
        <div class="bg-white dark:bg-[#1A1617] border-4 border-iba-black dark:border-iba-light p-6 sm:p-10 shadow-[10px_10px_0_0_#0095AC] mb-12 relative overflow-hidden">
            <div class="absolute top-0 right-0 opacity-5 dark:opacity-10 pointer-events-none text-iba-black dark:text-iba-light">
                <svg class="w-64 h-64 -mt-10 -mr-10" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2L2 22h20L12 2z"/></svg>
            </div>

            <h1 class="font-pixel text-xl sm:text-3xl text-iba-black dark:text-iba-light uppercase tracking-wide border-b-8 border-iba-orange pb-6 mb-6 leading-relaxed relative z-10">
                HEROES OF INNOVATION CHALLENGE<br><span class="text-iba-red">Ibalong Festival 2026 Edition</span>
            </h1>

            <div class="space-y-4 text-iba-black dark:text-gray-300 font-semibold leading-relaxed text-sm sm:text-base relative z-10">
                <p>Welcome, future Heroes of Innovation.</p>
                <p>The Challenge begins with listening. Registered teams will first learn about the Ibalong Heroes, meet the Community Heroes through the <span class="text-iba-teal">Voices of Bicol</span> session, join the <span class="text-iba-teal">Human-Centered Design</span> workshop, and only then develop an Innovation Concept Proposal.</p>
                <div class="bg-iba-red text-white p-4 border-4 border-iba-black dark:border-iba-light shadow-[4px_4px_0_0_#131011] dark:shadow-[4px_4px_0_0_#FFFBF7] inline-block font-bold">
                    ⚠️ At this stage, you are not yet required to submit an idea or solution.
                </div>
                <p class="font-bold text-iba-green pt-2">Please register as a team of 3 to 5 members.</p>

                @if ($errors->any())
                    <div class="bg-red-100 dark:bg-iba-red/20 border-4 border-iba-red text-iba-red p-4 mt-4 font-bold">
                        System Alert: Please verify the highlighted fields below to proceed.
                    </div>
                @endif
            </div>
        </div>

        {{-- PROGRESS BAR --}}
        <div class="flex gap-2 sm:gap-4 mb-10">
            <div class="h-4 flex-1 border-4 border-iba-black dark:border-iba-light {{ $step >= 1 ? 'bg-iba-orange' : 'bg-white dark:bg-[#1A1617]' }} transition-colors duration-300"></div>
            <div class="h-4 flex-1 border-4 border-iba-black dark:border-iba-light {{ $step >= 2 ? 'bg-iba-teal' : 'bg-white dark:bg-[#1A1617]' }} transition-colors duration-300"></div>
            <div class="h-4 flex-1 border-4 border-iba-black dark:border-iba-light {{ $step >= 3 ? 'bg-iba-green' : 'bg-white dark:bg-[#1A1617]' }} transition-colors duration-300"></div>
        </div>

        {{-- STEP 1: COHORT PROFILE --}}
        @if($step == 1)
            <div class="bg-iba-light dark:bg-iba-black border-4 border-iba-black dark:border-iba-light p-6 sm:p-10 shadow-[10px_10px_0_0_#FF8623] animate-fade-in-up">
                <div class="flex items-center justify-between border-b-4 border-iba-black dark:border-iba-light pb-4 mb-8">
                    <h3 class="font-pixel text-xl sm:text-2xl text-iba-black dark:text-iba-light">STEP 1: <span class="text-iba-orange">COHORT PROFILE</span></h3>
                    <span class="font-pixel text-iba-orange text-2xl">01</span>
                </div>

                <div class="space-y-8">
                    <div class="grid grid-cols-1 gap-6">

                        {{-- INLINE ERROR HANDLING & TEXT BOXES --}}
                        <div>
                            <label class="block font-bold text-iba-black dark:text-iba-light mb-2 text-sm uppercase tracking-wider">Designated Team Name <span class="text-iba-red">*</span></label>
                            <input type="text" wire:model="team_name" class="w-full border-4 {{ $errors->has('team_name') ? 'border-iba-red bg-red-50 dark:bg-red-900/20' : 'border-iba-black dark:border-iba-light bg-white dark:bg-[#1A1617]' }} p-4 font-bold text-lg focus:outline-none focus:border-iba-orange text-iba-black dark:text-iba-light shadow-inner transition-colors">
                            @error('team_name') <span class="text-iba-red text-xs font-bold block mt-2">⚠ {{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block font-bold text-iba-black dark:text-iba-light mb-2 text-sm uppercase tracking-wider">School / Organization / Company <span class="text-iba-red">*</span></label>
                            <input type="text" wire:model="affiliation" class="w-full border-4 {{ $errors->has('affiliation') ? 'border-iba-red bg-red-50 dark:bg-red-900/20' : 'border-iba-black dark:border-iba-light bg-white dark:bg-[#1A1617]' }} p-4 font-bold focus:outline-none focus:border-iba-orange text-iba-black dark:text-iba-light shadow-inner transition-colors">
                            @error('affiliation') <span class="text-iba-red text-xs font-bold block mt-2">⚠ {{ $message }}</span> @enderror
                        </div>

                        {{-- CASCADING GEOGRAPHIC DROPDOWNS --}}
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 border-l-4 border-iba-orange pl-4">
                            <div>
                                <label class="block font-bold text-iba-black dark:text-iba-light mb-2 text-xs uppercase tracking-wider">Province <span class="text-iba-red">*</span></label>
                                <select wire:model.live="provCode" class="w-full border-4 {{ $errors->has('provCode') ? 'border-iba-red' : 'border-iba-black dark:border-iba-light' }} p-4 font-bold text-sm focus:outline-none focus:border-iba-orange bg-white dark:bg-[#1A1617] text-iba-black dark:text-iba-light transition-colors">
                                    <option value="">-- SELECT PROVINCE --</option>
                                    @foreach($provinces as $prov)
                                        <option value="{{ $prov->provCode }}">{{ $prov->provDesc }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block font-bold text-iba-black dark:text-iba-light mb-2 text-xs uppercase tracking-wider">City / Municipality <span class="text-iba-red">*</span></label>
                                <select wire:model.live="citymunCode" class="w-full border-4 {{ $errors->has('citymunCode') ? 'border-iba-red' : 'border-iba-black dark:border-iba-light' }} p-4 font-bold text-sm focus:outline-none focus:border-iba-orange bg-white dark:bg-[#1A1617] text-iba-black dark:text-iba-light transition-colors" {{ empty($cities) ? 'disabled' : '' }}>
                                    <option value="">-- SELECT CITY/MUN --</option>
                                    @foreach($cities as $city)
                                        <option value="{{ $city->citymunCode }}">{{ $city->citymunDesc }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block font-bold text-iba-black dark:text-iba-light mb-2 text-xs uppercase tracking-wider">Barangay <span class="text-iba-red">*</span></label>
                                <select wire:model="brgyCode" class="w-full border-4 {{ $errors->has('brgyCode') ? 'border-iba-red' : 'border-iba-black dark:border-iba-light' }} p-4 font-bold text-sm focus:outline-none focus:border-iba-orange bg-white dark:bg-[#1A1617] text-iba-black dark:text-iba-light transition-colors" {{ empty($barangays) ? 'disabled' : '' }}>
                                    <option value="">-- SELECT BARANGAY --</option>
                                    @foreach($barangays as $brgy)
                                        <option value="{{ $brgy->brgyCode }}">{{ $brgy->brgyDesc }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        {{-- WORD COUNTER & EXPANDED QUESTION --}}
                        <div x-data="{
                            content: $wire.entangle('team_about'),
                            get wordCount() {
                                return this.content ? this.content.trim().split(/\s+/).filter(w => w.length > 0).length : 0;
                            }
                        }">
                            <label class="block font-bold text-iba-black dark:text-iba-light mb-2 text-sm uppercase tracking-wider">
                                Cohort Manifesto: What brings your team together? What makes your team interested in innovation, entrepreneurship, or solving community challenges? <span class="text-iba-red">*</span>
                            </label>
                            <textarea x-model="content" rows="4" class="w-full border-4 {{ $errors->has('team_about') ? 'border-iba-red bg-red-50 dark:bg-red-900/20' : 'border-iba-black dark:border-iba-light bg-white dark:bg-[#1A1617]' }} p-4 font-bold focus:outline-none focus:border-iba-orange text-iba-black dark:text-iba-light shadow-inner transition-colors placeholder-gray-400"></textarea>

                            <div class="flex justify-between items-start mt-2">
                                <div>
                                    @error('team_about') <span class="text-iba-red text-xs font-bold block">⚠ {{ $message }}</span> @enderror
                                </div>
                                <div class="text-xs font-bold shrink-0" :class="wordCount > 250 ? 'text-iba-red' : 'text-gray-500 dark:text-gray-400'">
                                    <span x-text="wordCount"></span> / 250 Words
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- MULTI-SELECT: Team Skills --}}
                    <div class="pt-6 border-t-2 border-dashed border-gray-300 dark:border-gray-700">
                        <label class="block font-bold text-iba-black dark:text-iba-light mb-4 uppercase tracking-wider text-sm">COLLECTIVE TEAM SKILLS <span class="text-iba-red">*</span></label>
                        <div class="flex flex-wrap gap-3">
                            @foreach($ref_skills as $skill)
                                <label wire:key="team-skill-{{ $skill->id }}" class="cursor-pointer relative inline-block">
                                    <input type="checkbox" wire:model="team_skills" value="{{ $skill->id }}" class="peer sr-only">
                                    <div class="px-4 py-2 border-4 border-iba-black dark:border-iba-light bg-white dark:bg-[#1A1617] text-iba-black dark:text-iba-light font-bold text-xs sm:text-sm uppercase tracking-wide peer-checked:bg-iba-teal dark:peer-checked:bg-iba-teal peer-checked:text-white dark:peer-checked:text-white peer-checked:translate-y-1 peer-checked:shadow-none shadow-[3px_3px_0_0_#131011] dark:shadow-[3px_3px_0_0_#FFFBF7] transition-all select-none hover:bg-gray-50 dark:hover:bg-[#2A2425]">
                                        {{ $skill->name }}
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    {{-- MULTI-SELECT: Community Areas --}}
                    <div class="pt-6 border-t-2 border-dashed border-gray-300 dark:border-gray-700">
                        <label class="block font-bold text-iba-black dark:text-iba-light mb-4 uppercase tracking-wider text-sm">TARGET COMMUNITY AREAS OF INTEREST <span class="text-iba-red">*</span></label>
                        <div class="flex flex-wrap gap-3">
                            @foreach($ref_community_areas as $area)
                                <label wire:key="area-{{ $area->id }}" class="cursor-pointer relative inline-block">
                                    <input type="checkbox" wire:model="team_community_areas" value="{{ $area->id }}" class="peer sr-only">
                                    <div class="px-4 py-2 border-4 border-iba-black dark:border-iba-light bg-white dark:bg-[#1A1617] text-iba-black dark:text-iba-light font-bold text-xs sm:text-sm uppercase tracking-wide peer-checked:bg-iba-orange dark:peer-checked:bg-iba-orange peer-checked:text-iba-black dark:peer-checked:text-iba-black peer-checked:translate-y-1 peer-checked:shadow-none shadow-[3px_3px_0_0_#131011] dark:shadow-[3px_3px_0_0_#FFFBF7] transition-all select-none hover:bg-gray-50 dark:hover:bg-[#2A2425]">
                                        {{ $area->name }}
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    {{-- MULTI-SELECT: Experiences --}}
                    <div class="pt-6 border-t-2 border-dashed border-gray-300 dark:border-gray-700">
                        <label class="block font-bold text-iba-black dark:text-iba-light mb-4 uppercase tracking-wider text-sm">PREVIOUS COHORT EXPERIENCES</label>
                        <div class="flex flex-wrap gap-3">
                            @foreach($ref_experiences as $exp)
                                <label wire:key="exp-{{ $exp->id }}" class="cursor-pointer relative inline-block">
                                    <input type="checkbox" wire:model="team_experiences" value="{{ $exp->id }}" class="peer sr-only">
                                    <div class="px-4 py-2 border-4 border-iba-black dark:border-iba-light bg-white dark:bg-[#1A1617] text-iba-black dark:text-iba-light font-bold text-xs sm:text-sm uppercase tracking-wide peer-checked:bg-iba-red dark:peer-checked:bg-iba-red peer-checked:text-white dark:peer-checked:text-white peer-checked:translate-y-1 peer-checked:shadow-none shadow-[3px_3px_0_0_#131011] dark:shadow-[3px_3px_0_0_#FFFBF7] transition-all select-none hover:bg-gray-50 dark:hover:bg-[#2A2425]">
                                        {{ $exp->name }}
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="mt-12 flex justify-end">
                    <button wire:click="nextStep" class="btn-retro bg-iba-orange text-iba-black font-pixel px-8 py-4 text-xs sm:text-sm uppercase flex items-center gap-2 shadow-[6px_6px_0_0_#131011] dark:shadow-[6px_6px_0_0_#FFFBF7] border-4 border-iba-black dark:border-iba-light hover:translate-y-1 hover:shadow-none transition-all">
                        Proceed to Roster ➔
                    </button>
                </div>
            </div>
        @endif

        {{-- STEP 2: ASSEMBLE COHORT --}}
        @if($step == 2)
            <div class="bg-iba-light dark:bg-iba-black border-4 border-iba-black dark:border-iba-light p-6 sm:p-10 shadow-[10px_10px_0_0_#0095AC] animate-fade-in-up">
                <div class="flex items-center justify-between border-b-4 border-iba-black dark:border-iba-light pb-4 mb-8">
                    <h3 class="font-pixel text-xl sm:text-2xl text-iba-black dark:text-iba-light">STEP 2: <span class="text-iba-teal">ASSEMBLE ROSTER</span></h3>
                    <span class="font-pixel text-iba-teal text-2xl">02</span>
                </div>

                <div class="space-y-10">
                    @foreach($members as $index => $member)
                        <div wire:key="member-block-{{ $index }}" class="border-4 border-iba-black dark:border-iba-light p-6 sm:p-8 relative bg-white dark:bg-[#1A1617] shadow-[6px_6px_0_0_#131011] dark:shadow-[6px_6px_0_0_#FFFBF7]">

                            <div class="flex justify-between items-center mb-6 border-b-2 border-dashed border-gray-300 dark:border-gray-700 pb-4">
                                <h4 class="font-pixel text-sm sm:text-base text-iba-black dark:text-iba-light">
                                    <span class="{{ $index == 0 ? 'text-iba-red' : 'text-iba-teal' }} mr-2">▶</span>{{ $member['team_role'] }}
                                </h4>
                                @if($index > 0)
                                    <button wire:click="removeMember({{ $index }})" class="font-bold text-iba-red hover:underline text-sm flex items-center gap-1">✖ REMOVE</button>
                                @endif
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-6">
                                <div>
                                    <label class="block text-xs font-bold text-iba-black dark:text-iba-light uppercase mb-1">Full Legal Name <span class="text-iba-red">*</span></label>
                                    <input type="text" wire:model="members.{{ $index }}.full_name" class="w-full border-4 border-iba-black dark:border-iba-light p-3 font-semibold focus:border-iba-teal dark:focus:border-iba-teal focus:outline-none bg-iba-light/50 dark:bg-iba-black/50 text-iba-black dark:text-iba-light transition-colors">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-iba-black dark:text-iba-light uppercase mb-1">Email Address <span class="text-iba-red">*</span></label>
                                    <input type="email" wire:model="members.{{ $index }}.email_address" class="w-full border-4 border-iba-black dark:border-iba-light p-3 font-semibold focus:border-iba-teal dark:focus:border-iba-teal focus:outline-none bg-iba-light/50 dark:bg-iba-black/50 text-iba-black dark:text-iba-light transition-colors">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-iba-black dark:text-iba-light uppercase mb-1">Mobile Number</label>
                                    <input type="text" wire:model="members.{{ $index }}.mobile_number" class="w-full border-4 border-iba-black dark:border-iba-light p-3 font-semibold focus:border-iba-teal dark:focus:border-iba-teal focus:outline-none bg-iba-light/50 dark:bg-iba-black/50 text-iba-black dark:text-iba-light transition-colors">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-iba-black dark:text-iba-light uppercase mb-1">Date of Birth</label>
                                    <input type="date" wire:model="members.{{ $index }}.birthday" class="w-full border-4 border-iba-black dark:border-iba-light p-3 font-semibold focus:border-iba-teal dark:focus:border-iba-teal focus:outline-none bg-iba-light/50 dark:bg-iba-black/50 text-iba-black dark:text-iba-light transition-colors">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-iba-black dark:text-iba-light uppercase mb-1">Course / Degree</label>
                                    <input type="text" wire:model="members.{{ $index }}.course" class="w-full border-4 border-iba-black dark:border-iba-light p-3 font-semibold focus:border-iba-teal dark:focus:border-iba-teal focus:outline-none bg-iba-light/50 dark:bg-iba-black/50 text-iba-black dark:text-iba-light transition-colors">
                                </div>
                            </div>

                            {{-- INDIVIDUAL MEMBER SKILLS --}}
                            <div class="pt-4">
                                <label class="block text-xs font-bold text-iba-black dark:text-iba-light uppercase mb-3 bg-iba-teal text-white py-1 px-3 inline-block border-2 border-iba-black dark:border-iba-light">Select Primary Skills</label>
                                <div class="flex flex-wrap gap-2">
                                    @foreach($ref_skills as $skill)
                                        <label wire:key="member-skill-{{ $index }}-{{ $skill->id }}" class="cursor-pointer relative inline-block">
                                            <input type="checkbox" wire:model="members.{{ $index }}.skills" value="{{ $skill->id }}" class="peer sr-only">
                                            <div class="px-3 py-1.5 border-2 border-iba-black dark:border-iba-light bg-white dark:bg-[#1A1617] text-gray-600 dark:text-gray-400 font-bold text-[10px] sm:text-xs uppercase peer-checked:bg-iba-teal dark:peer-checked:bg-iba-teal peer-checked:text-white dark:peer-checked:text-white peer-checked:border-iba-black dark:peer-checked:border-iba-light transition-colors select-none hover:bg-gray-100 dark:hover:bg-[#2A2425]">
                                                {{ $skill->name }}
                                            </div>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endforeach

                    @if(count($members) < 5)
                        <button wire:click="addMember" class="w-full border-4 border-iba-black dark:border-iba-light border-dashed py-6 font-pixel text-iba-teal bg-white dark:bg-[#1A1617] hover:bg-iba-teal/10 transition-colors flex items-center justify-center gap-3 text-xs sm:text-sm shadow-sm">
                            + RECRUIT MEMBER ({{ count($members) }}/5)
                        </button>
                    @else
                         <div class="w-full border-4 border-iba-black dark:border-iba-light border-dashed py-4 font-pixel text-iba-red bg-white dark:bg-[#1A1617] text-center text-xs">
                            MAXIMUM CAPACITY REACHED
                        </div>
                    @endif
                </div>

                <div class="mt-12 flex flex-col-reverse sm:flex-row justify-between items-center gap-4">
                    <button wire:click="previousStep" class="font-bold text-gray-500 dark:text-gray-400 hover:text-iba-black dark:hover:text-iba-light uppercase tracking-widest text-sm">
                        ⬅ Go Back
                    </button>
                    <button wire:click="nextStep" class="btn-retro bg-iba-teal text-white font-pixel px-8 py-4 text-xs sm:text-sm uppercase flex items-center justify-center gap-2 border-4 border-iba-black shadow-[6px_6px_0_0_#131011] dark:shadow-[6px_6px_0_0_#FFFBF7] hover:translate-y-1 hover:shadow-none transition-all w-full sm:w-auto">
                        Final Verification ➔
                    </button>
                </div>
            </div>
        @endif

        {{-- STEP 3: VERIFICATION & LOGISTICS --}}
        @if($step == 3)
            <div class="bg-iba-light dark:bg-iba-black border-4 border-iba-black dark:border-iba-light p-6 sm:p-10 shadow-[10px_10px_0_0_#5C7914] animate-fade-in-up">
                <div class="flex items-center justify-between border-b-4 border-iba-black dark:border-iba-light pb-4 mb-8">
                    <h3 class="font-pixel text-xl sm:text-2xl text-iba-black dark:text-iba-light">STEP 3: <span class="text-iba-green">VERIFICATION</span></h3>
                    <span class="font-pixel text-iba-green text-2xl">03</span>
                </div>

                <div class="space-y-8">

                    {{-- AVAILABILITY AND COMMITMENT (Online Activities) --}}
                    <div class="bg-white dark:bg-[#1A1617] border-4 border-iba-black dark:border-iba-light p-6">
                        <h4 class="font-bold text-iba-black dark:text-iba-light uppercase tracking-wider text-base mb-2">AVAILABILITY AND COMMITMENT</h4>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-6">Read and answer correctly.</p>

                        <label class="block font-bold text-iba-black dark:text-iba-light mb-4 text-sm">Our team is available to attend the required online activities: <span class="text-iba-red">*</span></label>

                        <div class="flex flex-wrap gap-3">
                            @foreach($ref_online_activities as $activity)
                                <label wire:key="activity-{{ $activity->id }}" class="cursor-pointer relative inline-block w-full sm:w-auto">
                                    <input type="checkbox" wire:model="team_online_activities" value="{{ $activity->id }}" class="peer sr-only">
                                    <div class="px-4 py-3 border-4 border-iba-black dark:border-iba-light bg-white dark:bg-[#1A1617] text-iba-black dark:text-iba-light font-bold text-xs sm:text-sm tracking-wide peer-checked:bg-iba-green dark:peer-checked:bg-iba-green peer-checked:text-white dark:peer-checked:text-white peer-checked:translate-y-1 peer-checked:shadow-none shadow-[3px_3px_0_0_#131011] dark:shadow-[3px_3px_0_0_#FFFBF7] transition-all select-none hover:bg-gray-50 dark:hover:bg-[#2A2425] flex items-center justify-between">
                                        <span>{{ $activity->name }}</span>
                                        <svg class="w-4 h-4 opacity-0 peer-checked:opacity-100 transition-opacity ml-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    {{-- Expanded Questions & Dropdowns --}}
                    <div class="grid grid-cols-1 gap-6">
                        <div>
                            <label class="block text-sm font-bold text-iba-black dark:text-iba-light mb-2">Are all members from Bicol? <span class="text-iba-red">*</span></label>
                            <select wire:model="team_member_demographics" class="w-full border-4 border-iba-black dark:border-iba-light p-4 font-bold text-sm focus:border-iba-green dark:focus:border-iba-green focus:outline-none bg-white dark:bg-[#1A1617] text-iba-black dark:text-iba-light transition-colors">
                                <option value="">-- AWAITING INPUT --</option>
                                <option value="YES">Yes, all members are from Bicol</option>
                                <option value="NO">No members are from Bicol</option>
                                <option value="NOT ALL BUT MAJORITY FROM BICOL">Not all, but majority are</option>
                            </select>
                            @error('team_member_demographics') <span class="text-iba-red text-xs font-bold block mt-2">⚠ Required</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-iba-black dark:text-iba-light mb-2">If selected, our team commits to participate in the onsite Heroes of Innovation Challenge: <span class="text-iba-red">*</span></label>
                            <select wire:model="onsite_commitment" class="w-full border-4 border-iba-black dark:border-iba-light p-4 font-bold text-sm focus:border-iba-green dark:focus:border-iba-green focus:outline-none bg-white dark:bg-[#1A1617] text-iba-black dark:text-iba-light transition-colors">
                                <option value="">-- AWAITING INPUT --</option>
                                <option value="YES">YES, we commit</option>
                                <option value="NO">NO, we cannot</option>
                            </select>
                            @error('onsite_commitment') <span class="text-iba-red text-xs font-bold block mt-2">⚠ Required</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-iba-black dark:text-iba-light mb-2">Our team understands that registration does not automatically qualify us for the onsite Challenge: <span class="text-iba-red">*</span></label>
                            <select wire:model="does_not_automatically_apply_clause" class="w-full border-4 border-iba-black dark:border-iba-light p-4 font-bold text-sm focus:border-iba-green dark:focus:border-iba-green focus:outline-none bg-white dark:bg-[#1A1617] text-iba-black dark:text-iba-light transition-colors">
                                <option value="">-- AWAITING INPUT --</option>
                                <option value="YES">YES, understood</option>
                                <option value="NO">NO</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-iba-black dark:text-iba-light mb-2">Our team understands that selected teams will be chosen based on the Innovation Concept Proposal to be submitted after the Opportunity Discovery Phase: <span class="text-iba-red">*</span></label>
                            <select wire:model="selection_on_icp" class="w-full border-4 border-iba-black dark:border-iba-light p-4 font-bold text-sm focus:border-iba-green dark:focus:border-iba-green focus:outline-none bg-white dark:bg-[#1A1617] text-iba-black dark:text-iba-light transition-colors">
                                <option value="">-- AWAITING INPUT --</option>
                                <option value="YES">YES, acknowledged</option>
                                <option value="NO">NO</option>
                            </select>
                        </div>
                    </div>

                    {{-- Expanded Consents --}}
                    <div class="pt-6 border-t-2 border-dashed border-gray-300 dark:border-gray-700 space-y-4">

                        <div class="bg-white dark:bg-[#1A1617] p-5 border-4 {{ $errors->has('data_privacy_consent') ? 'border-iba-red' : 'border-iba-black dark:border-iba-light' }}">
                            <span class="text-sm font-bold text-iba-black dark:text-iba-light leading-relaxed block mb-2">Data Privacy Consent <span class="text-iba-red">*</span></span>
                            <span class="text-sm text-gray-700 dark:text-gray-300 leading-relaxed block mb-2">Your privacy matters to us. The personal information collected in this form will only be used for the HEROES OF INNOVATION CHALLENGE: Ibalong Festival 2026 Edition.</span>
                            <span class="text-sm text-gray-700 dark:text-gray-300 leading-relaxed block mb-4">In compliance with the Data Privacy Act of 2012, we ensure your data is kept confidential and safe. By submitting this form, you consent to the collection and use of your information for this specific purpose.</span>
                            <label class="flex items-center gap-4 cursor-pointer hover:bg-iba-green/5 dark:hover:bg-iba-green/10 transition-colors p-2 -ml-2">
                                <input type="checkbox" wire:model="data_privacy_consent" class="w-6 h-6 border-4 border-iba-black text-iba-green focus:ring-0 rounded-none bg-white checked:bg-iba-green">
                                <span class="text-sm font-bold text-iba-black dark:text-iba-light">I acknowledge and consent to the Data Privacy Act.</span>
                            </label>
                        </div>

                        <label class="flex items-start gap-4 bg-white dark:bg-[#1A1617] p-5 border-4 {{ $errors->has('media_consent') ? 'border-iba-red' : 'border-iba-black dark:border-iba-light' }} cursor-pointer hover:bg-iba-green/5 dark:hover:bg-iba-green/10 transition-colors">
                            <input type="checkbox" wire:model="media_consent" class="mt-1 w-6 h-6 border-4 border-iba-black text-iba-green focus:ring-0 rounded-none bg-white checked:bg-iba-green">
                            <span class="text-sm font-bold text-iba-black dark:text-iba-light leading-relaxed">Media Consent: I grant permission for the organizers to use media (photos/videos) captured during the event for promotional and reporting purposes. <span class="text-iba-red">*</span></span>
                        </label>
                    </div>

                </div>

                <div class="mt-12 flex flex-col-reverse sm:flex-row justify-between items-center gap-4">
                    <button wire:click="previousStep" class="font-bold text-gray-500 dark:text-gray-400 hover:text-iba-black dark:hover:text-iba-light uppercase tracking-widest text-sm">
                        ⬅ Edit Roster
                    </button>
                    <button wire:click="submit" class="btn-retro bg-iba-green text-white font-pixel px-8 py-5 text-xs sm:text-sm uppercase flex items-center justify-center gap-2 border-4 border-iba-black shadow-[6px_6px_0_0_#131011] dark:shadow-[6px_6px_0_0_#FFFBF7] hover:translate-y-1 hover:shadow-none transition-all w-full sm:w-auto">
                        <svg wire:loading.remove wire:target="submit" class="w-5 h-5 animate-pulse" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        <svg wire:loading wire:target="submit" class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                        CONFIRM & SUBMIT
                    </button>
                </div>
            </div>
        @endif
    @endif

    <style>
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
        .animate-fade-in-up { animation: fadeInUp 0.3s ease-out forwards; }
    </style>
</div>
