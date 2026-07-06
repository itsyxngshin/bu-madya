<div class="max-w-5xl mx-auto py-12 px-4 sm:px-6">
    
    {{-- HEADER BLOCK --}}
    <div class="bg-white border-4 border-iba-black p-6 sm:p-10 shadow-[10px_10px_0_0_#0095AC] mb-12 relative overflow-hidden">
        <div class="absolute top-0 right-0 opacity-5 pointer-events-none">
            <svg class="w-64 h-64 -mt-10 -mr-10" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2L2 22h20L12 2z"/></svg>
        </div>
        
        <h1 class="font-pixel text-xl sm:text-3xl text-iba-black uppercase tracking-wide border-b-8 border-iba-orange pb-6 mb-6 leading-relaxed relative z-10">
            HEROES OF INNOVATION CHALLENGE<br><span class="text-iba-red">Ibalong Festival 2026 Edition</span>
        </h1>
        
        <div class="space-y-4 text-iba-black font-semibold leading-relaxed text-sm sm:text-base relative z-10">
            <p>Welcome, future Heroes of Innovation.</p>
            <p>The Challenge begins with listening. Registered teams will first learn about the Ibalong Heroes, meet the Community Heroes through the <span class="text-iba-teal">Voices of Bicol</span> session, join the <span class="text-iba-teal">Human-Centered Design</span> workshop, and only then develop an Innovation Concept Proposal.</p>
            <div class="bg-iba-red text-white p-4 border-4 border-iba-black shadow-[4px_4px_0_0_#131011] inline-block font-bold">
                ⚠️ At this stage, you are not yet required to submit an idea or solution.
            </div>
            @if ($errors->any())
                <div class="bg-red-100 border-4 border-iba-red text-iba-red p-4 mt-4 font-bold">
                    System Alert: Please verify the highlighted fields below.
                    <ul class="list-disc pl-5 text-sm mt-2">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
    </div>

    {{-- DYNAMIC PROGRESS BAR --}}
    <div class="flex gap-2 sm:gap-4 mb-10">
        <div class="h-4 flex-1 border-4 border-iba-black {{ $step >= 1 ? 'bg-iba-orange' : 'bg-white' }} transition-colors duration-300"></div>
        <div class="h-4 flex-1 border-4 border-iba-black {{ $step >= 2 ? 'bg-iba-teal' : 'bg-white' }} transition-colors duration-300"></div>
        <div class="h-4 flex-1 border-4 border-iba-black {{ $step >= 3 ? 'bg-iba-green' : 'bg-white' }} transition-colors duration-300"></div>
    </div>

    {{-- STEP 1: COHORT PROFILE --}}
    @if($step == 1)
        <div class="bg-iba-light border-4 border-iba-black p-6 sm:p-10 shadow-[10px_10px_0_0_#FF8623] animate-fade-in-up">
            <div class="flex items-center justify-between border-b-4 border-iba-black pb-4 mb-8">
                <h3 class="font-pixel text-xl sm:text-2xl text-iba-black">STEP 1: <span class="text-iba-orange">COHORT PROFILE</span></h3>
                <span class="font-pixel text-iba-orange text-2xl">01</span>
            </div>
            
            <div class="space-y-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <label class="block font-bold text-iba-black mb-2 uppercase tracking-wider text-sm">Designated Team Name</label>
                        <input type="text" wire:model="team_name" class="w-full border-4 border-iba-black p-4 font-bold text-lg focus:outline-none focus:border-iba-orange bg-white shadow-inner transition-colors">
                    </div>
                    
                    <div class="md:col-span-2">
                        <label class="block font-bold text-iba-black mb-2 uppercase tracking-wider text-sm">Origin / Affiliation <span class="text-xs text-gray-500 normal-case">(School, Organization, or Company)</span></label>
                        <input type="text" wire:model="affiliation" class="w-full border-4 border-iba-black p-4 font-bold focus:outline-none focus:border-iba-orange bg-white shadow-inner transition-colors">
                    </div>

                    <div class="md:col-span-2">
                        <label class="block font-bold text-iba-black mb-2 uppercase tracking-wider text-sm">Cohort Manifesto <span class="text-xs text-gray-500 normal-case">(Brief About)</span></label>
                        <textarea wire:model="team_about" rows="3" class="w-full border-4 border-iba-black p-4 font-bold focus:outline-none focus:border-iba-orange bg-white shadow-inner transition-colors"></textarea>
                    </div>
                </div>

                {{-- TACTILE MULTI-SELECT: Community Areas --}}
                <div class="pt-6 border-t-2 border-dashed border-gray-300">
                    <label class="block font-bold text-iba-black mb-4 uppercase tracking-wider text-sm">Target Community Areas of Interest <span class="text-iba-orange">*</span></label>
                    <div class="flex flex-wrap gap-3">
                        @foreach($ref_community_areas as $area)
                            <label class="cursor-pointer relative">
                                <input type="checkbox" wire:model.defer="team_community_areas" value="{{ $area->id }}" class="peer sr-only">
                                <div class="px-4 py-2 border-4 border-iba-black bg-white text-iba-black font-bold text-xs sm:text-sm uppercase tracking-wide peer-checked:bg-iba-orange peer-checked:text-iba-black peer-checked:translate-y-1 peer-checked:shadow-none shadow-[3px_3px_0_0_#131011] transition-all select-none hover:bg-gray-50">
                                    {{ $area->name }}
                                </div>
                            </label>
                        @endforeach
                    </div>
                </div>

                {{-- TACTILE MULTI-SELECT: Experiences --}}
                <div class="pt-6 border-t-2 border-dashed border-gray-300">
                    <label class="block font-bold text-iba-black mb-4 uppercase tracking-wider text-sm">Previous Cohort Experiences</label>
                    <div class="flex flex-wrap gap-3">
                        @foreach($ref_experiences as $exp)
                            <label class="cursor-pointer relative">
                                <input type="checkbox" wire:model.defer="team_experiences" value="{{ $exp->id }}" class="peer sr-only">
                                <div class="px-4 py-2 border-4 border-iba-black bg-white text-iba-black font-bold text-xs sm:text-sm uppercase tracking-wide peer-checked:bg-iba-red peer-checked:text-white peer-checked:translate-y-1 peer-checked:shadow-none shadow-[3px_3px_0_0_#131011] transition-all select-none hover:bg-gray-50">
                                    {{ $exp->name }}
                                </div>
                            </label>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="mt-12 flex justify-end">
                <button wire:click="nextStep" class="btn-retro bg-iba-orange text-iba-black font-pixel px-8 py-4 text-xs sm:text-sm uppercase flex items-center gap-2">
                    Proceed to Roster ➔
                </button>
            </div>
        </div>
    @endif

    {{-- STEP 2: ASSEMBLE COHORT --}}
    @if($step == 2)
        <div class="bg-iba-light border-4 border-iba-black p-6 sm:p-10 shadow-[10px_10px_0_0_#0095AC] animate-fade-in-up">
            <div class="flex items-center justify-between border-b-4 border-iba-black pb-4 mb-8">
                <h3 class="font-pixel text-xl sm:text-2xl text-iba-black">STEP 2: <span class="text-iba-teal">ASSEMBLE ROSTER</span></h3>
                <span class="font-pixel text-iba-teal text-2xl">02</span>
            </div>
            
            <div class="space-y-10">
                @foreach($members as $index => $member)
                    <div class="border-4 border-iba-black p-6 sm:p-8 relative bg-white shadow-[6px_6px_0_0_#131011]">
                        
                        <div class="flex justify-between items-center mb-6 border-b-2 border-dashed border-gray-300 pb-4">
                            <h4 class="font-pixel text-sm sm:text-base text-iba-black">
                                <span class="{{ $index == 0 ? 'text-iba-red' : 'text-iba-teal' }} mr-2">▶</span>{{ $member['team_role'] }}
                            </h4>
                            @if($index > 0)
                                <button wire:click="removeMember({{ $index }})" class="font-bold text-iba-red hover:underline text-sm flex items-center gap-1">✖ REMOVE</button>
                            @endif
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-6">
                            <div>
                                <label class="block text-xs font-bold text-iba-black uppercase mb-1">Full Legal Name</label>
                                <input type="text" wire:model.defer="members.{{ $index }}.full_name" class="w-full border-4 border-iba-black p-3 font-semibold focus:border-iba-teal focus:outline-none bg-iba-light/50">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-iba-black uppercase mb-1">Email Address</label>
                                <input type="email" wire:model.defer="members.{{ $index }}.email_address" class="w-full border-4 border-iba-black p-3 font-semibold focus:border-iba-teal focus:outline-none bg-iba-light/50">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-iba-black uppercase mb-1">Mobile Number</label>
                                <input type="text" wire:model.defer="members.{{ $index }}.mobile_number" class="w-full border-4 border-iba-black p-3 font-semibold focus:border-iba-teal focus:outline-none bg-iba-light/50">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-iba-black uppercase mb-1">Date of Birth</label>
                                <input type="date" wire:model.defer="members.{{ $index }}.birthday" class="w-full border-4 border-iba-black p-3 font-semibold focus:border-iba-teal focus:outline-none bg-iba-light/50">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-iba-black uppercase mb-1">Course / Degree</label>
                                <input type="text" wire:model.defer="members.{{ $index }}.course" class="w-full border-4 border-iba-black p-3 font-semibold focus:border-iba-teal focus:outline-none bg-iba-light/50">
                            </div>
                        </div>

                        {{-- INDIVIDUAL MEMBER SKILLS --}}
                        <div class="pt-4">
                            <label class="block text-xs font-bold text-iba-black uppercase mb-3 bg-iba-teal text-white py-1 px-3 inline-block border-2 border-iba-black">Select Primary Skills</label>
                            <div class="flex flex-wrap gap-2">
                                @foreach($ref_skills as $skill)
                                    <label class="cursor-pointer">
                                        <input type="checkbox" wire:model.defer="members.{{ $index }}.skills" value="{{ $skill->id }}" class="peer sr-only">
                                        <div class="px-3 py-1.5 border-2 border-iba-black bg-white text-gray-600 font-bold text-[10px] sm:text-xs uppercase peer-checked:bg-iba-teal peer-checked:text-white peer-checked:border-iba-black transition-colors select-none hover:bg-gray-100">
                                            {{ $skill->name }}
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endforeach

                {{-- Add Member Logic --}}
                @if(count($members) < 5)
                    <button wire:click="addMember" class="w-full border-4 border-iba-black border-dashed py-6 font-pixel text-iba-teal bg-white hover:bg-iba-teal/10 transition-colors flex items-center justify-center gap-3 text-xs sm:text-sm shadow-sm">
                        + RECRUIT MEMBER ({{ count($members) }}/5)
                    </button>
                @else
                     <div class="w-full border-4 border-iba-black border-dashed py-4 font-pixel text-iba-red bg-white text-center text-xs">
                        MAXIMUM CAPACITY REACHED
                    </div>
                @endif
            </div>

            <div class="mt-12 flex flex-col-reverse sm:flex-row justify-between items-center gap-4">
                <button wire:click="previousStep" class="font-bold text-gray-500 hover:text-iba-black uppercase tracking-widest text-sm">
                    ⬅ Go Back
                </button>
                <button wire:click="nextStep" class="btn-retro bg-iba-teal text-white font-pixel px-8 py-4 text-xs sm:text-sm uppercase flex items-center gap-2 w-full sm:w-auto justify-center">
                    Final Verification ➔
                </button>
            </div>
        </div>
    @endif

    {{-- STEP 3: VERIFICATION & LOGISTICS --}}
    @if($step == 3)
        <div class="bg-iba-light border-4 border-iba-black p-6 sm:p-10 shadow-[10px_10px_0_0_#5C7914] animate-fade-in-up">
            <div class="flex items-center justify-between border-b-4 border-iba-black pb-4 mb-8">
                <h3 class="font-pixel text-xl sm:text-2xl text-iba-black">STEP 3: <span class="text-iba-green">VERIFICATION</span></h3>
                <span class="font-pixel text-iba-green text-2xl">03</span>
            </div>
            
            <div class="space-y-8">
                
                {{-- Dropdowns --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-bold text-iba-black uppercase mb-2">Are all members from Bicol?</label>
                        <select wire:model.defer="team_member_demographics" class="w-full border-4 border-iba-black p-4 font-bold text-sm focus:border-iba-green focus:outline-none bg-white">
                            <option value="">-- AWAITING INPUT --</option>
                            <option value="YES">Yes, all members are from Bicol</option>
                            <option value="NO">No members are from Bicol</option>
                            <option value="NOT ALL BUT MAJORITY FROM BICOL">Not all, but majority are</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-xs font-bold text-iba-black uppercase mb-2">Can the team commit to onsite pitching?</label>
                        <select wire:model.defer="onsite_commitment" class="w-full border-4 border-iba-black p-4 font-bold text-sm focus:border-iba-green focus:outline-none bg-white">
                            <option value="">-- AWAITING INPUT --</option>
                            <option value="YES">YES, we commit</option>
                            <option value="NO">NO, we cannot</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-iba-black uppercase mb-2">Acknowledge Non-Automatic Clause</label>
                        <select wire:model.defer="does_not_automatically_apply_clause" class="w-full border-4 border-iba-black p-4 font-bold text-sm focus:border-iba-green focus:outline-none bg-white">
                            <option value="">-- AWAITING INPUT --</option>
                            <option value="YES">YES, understood</option>
                            <option value="NO">NO</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-iba-black uppercase mb-2">Selection based on Concept Proposal?</label>
                        <select wire:model.defer="selection_on_icp" class="w-full border-4 border-iba-black p-4 font-bold text-sm focus:border-iba-green focus:outline-none bg-white">
                            <option value="">-- AWAITING INPUT --</option>
                            <option value="YES">YES, acknowledged</option>
                            <option value="NO">NO</option>
                        </select>
                    </div>
                </div>

                {{-- Consents --}}
                <div class="pt-6 border-t-2 border-dashed border-gray-300 space-y-4">
                    <label class="flex items-start gap-4 bg-white p-5 border-4 border-iba-black cursor-pointer hover:bg-iba-green/5 transition-colors">
                        <input type="checkbox" wire:model.defer="data_privacy_consent" class="mt-1 w-6 h-6 border-4 border-iba-black text-iba-green focus:ring-0 rounded-none bg-white checked:bg-iba-green">
                        <span class="text-sm font-bold text-iba-black leading-relaxed">I consent to the collection and processing of our team's data in accordance with the Data Privacy Act of 2012 for the purposes of this event.</span>
                    </label>

                    <label class="flex items-start gap-4 bg-white p-5 border-4 border-iba-black cursor-pointer hover:bg-iba-green/5 transition-colors">
                        <input type="checkbox" wire:model.defer="media_consent" class="mt-1 w-6 h-6 border-4 border-iba-black text-iba-green focus:ring-0 rounded-none bg-white checked:bg-iba-green">
                        <span class="text-sm font-bold text-iba-black leading-relaxed">I grant permission for the organizers to use media (photos/videos) captured during the event for promotional and reporting purposes.</span>
                    </label>
                </div>

            </div>

            <div class="mt-12 flex flex-col-reverse sm:flex-row justify-between items-center gap-4">
                <button wire:click="previousStep" class="font-bold text-gray-500 hover:text-iba-black uppercase tracking-widest text-sm">
                    ⬅ Edit Roster
                </button>
                <button wire:click="submit" class="btn-retro bg-iba-green text-white font-pixel px-8 py-5 text-xs sm:text-sm uppercase flex items-center justify-center gap-2 w-full sm:w-auto">
                    CONFIRM & SUBMIT
                </button>
            </div>
        </div>
    @endif
</div>

<style>
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in-up { animation: fadeInUp 0.3s ease-out forwards; }
</style>