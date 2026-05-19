<div class="max-w-5xl mx-auto p-4 sm:p-6 lg:p-8">

    {{-- STEP PROGRESS BAR --}}
    <div class="mb-8 bg-white p-4 rounded-2xl shadow-sm border border-gray-100 flex items-center justify-between text-xs font-black uppercase tracking-widest text-gray-400">
        <div class="flex items-center gap-2 {{ $currentStep >= 1 ? 'text-blue-600' : '' }}">
            <div class="w-6 h-6 rounded-full flex items-center justify-center border-2 {{ $currentStep >= 1 ? 'border-blue-600 bg-blue-50' : 'border-gray-200' }}">1</div>
            <span class="hidden md:block">General Info</span>
        </div>
        <div class="flex-1 h-px bg-gray-200 mx-4"></div>
        <div class="flex items-center gap-2 {{ $currentStep >= 2 ? 'text-blue-600' : '' }}">
            <div class="w-6 h-6 rounded-full flex items-center justify-center border-2 {{ $currentStep >= 2 ? 'border-blue-600 bg-blue-50' : 'border-gray-200' }}">2</div>
            <span class="hidden md:block">Documents</span>
        </div>
        <div class="flex-1 h-px bg-gray-200 mx-4"></div>
        <div class="flex items-center gap-2 {{ $currentStep >= 3 ? 'text-blue-600' : '' }}">
            <div class="w-6 h-6 rounded-full flex items-center justify-center border-2 {{ $currentStep >= 3 ? 'border-blue-600 bg-blue-50' : 'border-gray-200' }}">3</div>
            <span class="hidden md:block">Officers</span>
        </div>
        <div class="flex-1 h-px bg-gray-200 mx-4"></div>
        <div class="flex items-center gap-2 {{ $currentStep >= 4 ? 'text-blue-600' : '' }}">
            <div class="w-6 h-6 rounded-full flex items-center justify-center border-2 {{ $currentStep >= 4 ? 'border-blue-600 bg-blue-50' : 'border-gray-200' }}">4</div>
            <span class="hidden md:block">Members</span>
        </div>
        <div class="flex-1 h-px bg-gray-200 mx-4"></div>
        <div class="flex items-center gap-2 {{ $currentStep == 5 ? 'text-blue-600' : '' }}">
            <div class="w-6 h-6 rounded-full flex items-center justify-center border-2 {{ $currentStep == 5 ? 'border-blue-600 bg-blue-50' : 'border-gray-200' }}">5</div>
            <span class="hidden md:block">Activities</span>
        </div>
    </div>

    <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 p-6 md:p-10">

        {{-- STEP 1: General & Finance --}}
        @if($currentStep === 1)
            <h2 class="text-2xl font-black text-gray-900 mb-6">General Information & Finance</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                
                {{-- Organization Name --}}
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-1">Organization Name</label>
                    <input type="text" wire:model="name" class="w-full rounded-xl border-gray-200 bg-gray-50 focus:border-blue-500 focus:ring-blue-500 transition-colors">
                    @error('name') <span class="text-red-500 text-xs mt-1 block font-medium">{{ $message }}</span> @enderror
                </div>

                {{-- PREMIUM ALPINE.JS DROPDOWN: Organization Type --}}
                <div x-data="{ 
                        open: false, 
                        options: [
                            { value: 'academic', label: 'Academic' },
                            { value: 'socio-civic', label: 'Socio-Civic' },
                            { value: 'political', label: 'Political' },
                            { value: 'fraternity/sorority', label: 'Fraternity / Sorority' },
                            { value: 'environmental', label: 'Environmental' },
                            { value: 'spiritual/religious', label: 'Spiritual / Religious' },
                            { value: 'lifestyle', label: 'Lifestyle' },
                            { value: 'others', label: 'Others (Specify)' }
                        ],
                        get selectedLabel() {
                            let opt = this.options.find(o => o.value === $wire.type);
                            return opt ? opt.label : 'Select Type...';
                        }
                    }" class="relative">
                    
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-1">Organization Type</label>
                    
                    {{-- Premium Trigger Button --}}
                    <button @click="open = !open" @click.away="open = false" type="button" 
                            class="w-full flex items-center justify-between px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl hover:border-blue-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all text-sm text-left">
                        <span x-text="selectedLabel" :class="$wire.type ? 'text-gray-900 font-bold' : 'text-gray-500'"></span>
                        <svg class="w-4 h-4 text-gray-400 transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </button>

                    {{-- Premium Dropdown Menu --}}
                    <div x-show="open" 
                         x-transition:enter="transition ease-out duration-100" 
                         x-transition:enter-start="opacity-0 scale-95" 
                         x-transition:enter-end="opacity-100 scale-100" 
                         x-transition:leave="transition ease-in duration-75" 
                         x-transition:leave-start="opacity-100 scale-100" 
                         x-transition:leave-end="opacity-0 scale-95" 
                         class="absolute z-50 w-full mt-2 bg-white/95 backdrop-blur-xl border border-gray-100 rounded-xl shadow-xl overflow-hidden" style="display: none;">
                        <div class="p-1 max-h-60 overflow-y-auto">
                            <template x-for="option in options" :key="option.value">
                                <button @click="$wire.set('type', option.value); open = false" type="button" 
                                        class="w-full text-left px-4 py-2.5 text-sm rounded-lg transition-colors flex items-center justify-between group"
                                        :class="$wire.type === option.value ? 'bg-blue-50 text-blue-700 font-bold' : 'text-gray-700 hover:bg-gray-50 hover:text-blue-600'">
                                    <span x-text="option.label"></span>
                                    <svg x-show="$wire.type === option.value" class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                </button>
                            </template>
                        </div>
                    </div>
                    @error('type') <span class="text-red-500 text-xs mt-1 block font-medium">{{ $message }}</span> @enderror
                </div>

                {{-- Type Specification (Only shows if 'others' is selected) --}}
                @if($type === 'others')
                    <div class="md:col-span-2 p-4 bg-gray-50 rounded-xl border border-gray-200 animate-fade-in-up">
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-1">Please Specify Organization Type</label>
                        <input type="text" wire:model="type_specification" placeholder="e.g. Esports, Tech Community, etc." class="w-full rounded-lg border-gray-200 focus:border-blue-500 focus:ring-blue-500">
                        @error('type_specification') <span class="text-red-500 text-xs mt-1 block font-medium">{{ $message }}</span> @enderror
                    </div>
                @endif

                {{-- Academic Year --}}
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-1">Academic Year</label>
                    <select wire:model="academic_year_id" class="w-full rounded-xl border-gray-200 bg-gray-50 focus:border-blue-500 focus:ring-blue-500">
                        <option value="">Select A.Y...</option>
                        @foreach($academicYears as $ay)
                            <option value="{{ $ay->id }}">{{ $ay->name }}</option>
                        @endforeach
                    </select>
                    @error('academic_year_id') <span class="text-red-500 text-xs mt-1 block font-medium">{{ $message }}</span> @enderror
                </div>

                {{-- Year Established --}}
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-1">Year Established</label>
                    <input type="number" wire:model="year_established" placeholder="e.g. 1998" class="w-full rounded-xl border-gray-200 bg-gray-50 focus:border-blue-500 focus:ring-blue-500">
                    @error('year_established') <span class="text-red-500 text-xs mt-1 block font-medium">{{ $message }}</span> @enderror
                </div>

                <div class="md:col-span-2 pt-6 mt-2 border-t border-gray-100">
                    <h3 class="font-black text-gray-900 mb-4">Financial & Bank Details</h3>
                </div>

                {{-- Membership Fee --}}
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-1">Membership Fee (PHP)</label>
                    <input type="number" step="0.01" wire:model="membership_fee" class="w-full rounded-xl border-gray-200 bg-gray-50 focus:border-blue-500 focus:ring-blue-500">
                    @error('membership_fee') <span class="text-red-500 text-xs mt-1 block font-medium">{{ $message }}</span> @enderror
                </div>

                {{-- Collection Frequency --}}
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-1">Collection Frequency</label>
                    <select wire:model="collection_frequency" class="w-full rounded-xl border-gray-200 bg-gray-50 focus:border-blue-500 focus:ring-blue-500">
                        <option value="none">None</option>
                        <option value="semestral">Semestral</option>
                        <option value="annual">Annual</option>
                    </select>
                    @error('collection_frequency') <span class="text-red-500 text-xs mt-1 block font-medium">{{ $message }}</span> @enderror
                </div>

                {{-- Bank Details --}}
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-1">Bank Name</label>
                    <input type="text" wire:model="bank_name" placeholder="e.g. Landbank, BPI" class="w-full rounded-xl border-gray-200 bg-gray-50 focus:border-blue-500 focus:ring-blue-500">
                    @error('bank_name') <span class="text-red-500 text-xs mt-1 block font-medium">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-1">Account Number</label>
                    <input type="text" wire:model="bank_account_number" class="w-full rounded-xl border-gray-200 bg-gray-50 focus:border-blue-500 focus:ring-blue-500">
                    @error('bank_account_number') <span class="text-red-500 text-xs mt-1 block font-medium">{{ $message }}</span> @enderror
                </div>
            </div>
        @endif

        {{-- STEP 2: Documents --}}
        @if($currentStep === 2)
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-black text-gray-900">Required Documents</h2>

                <div class="bg-gray-100 p-1 rounded-lg flex gap-1">
                    <button wire:click="$set('application_type', 'accreditation')" class="px-4 py-1.5 text-xs font-bold rounded-md transition-colors {{ $application_type === 'accreditation' ? 'bg-white shadow-sm text-gray-900' : 'text-gray-500' }}">New Accreditation</button>
                    <button wire:click="$set('application_type', 'reaccreditation')" class="px-4 py-1.5 text-xs font-bold rounded-md transition-colors {{ $application_type === 'reaccreditation' ? 'bg-white shadow-sm text-gray-900' : 'text-gray-500' }}">Re-accreditation</button>
                </div>
            </div>

            <div class="space-y-6">
                <div class="bg-blue-50/50 p-4 rounded-xl border border-blue-100">
                    <label class="block text-xs font-bold text-blue-800 uppercase tracking-widest mb-1">Bankbook Photo (Proof of Account)</label>
                    <input type="file" wire:model="bankbook_photo" accept="image/*" class="w-full text-sm">
                    @error('bankbook_photo') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-gray-50 p-4 rounded-xl border border-gray-100">
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-widest mb-1">Constitution & By-Laws (PDF)</label>
                        <input type="file" wire:model="cbl" accept=".pdf" class="w-full text-sm">
                        @error('cbl') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div class="bg-gray-50 p-4 rounded-xl border border-gray-100">
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-widest mb-1">Recent Fliers/Materials (PDF/IMG)</label>
                        <input type="file" wire:model="recent_fliers" accept=".pdf,image/*" class="w-full text-sm">
                        @error('recent_fliers') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    @if($application_type === 'reaccreditation')
                        <div class="bg-orange-50/50 p-4 rounded-xl border border-orange-100">
                            <label class="block text-xs font-bold text-orange-800 uppercase tracking-widest mb-1">Accomplishment Report (PDF)</label>
                            <input type="file" wire:model="accomplishment_report" accept=".pdf" class="w-full text-sm">
                            @error('accomplishment_report') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div class="bg-orange-50/50 p-4 rounded-xl border border-orange-100">
                            <label class="block text-xs font-bold text-orange-800 uppercase tracking-widest mb-1">Audited Financial Report (PDF)</label>
                            <input type="file" wire:model="audited_financial_report" accept=".pdf" class="w-full text-sm">
                            @error('audited_financial_report') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                    @endif
                </div>
            </div>
        @endif

        {{-- STEP 3: Officers --}}
        @if($currentStep === 3)
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-black text-gray-900">Organization Officers</h2>
                <button wire:click="addOfficer" class="px-4 py-2 bg-blue-50 text-blue-600 text-xs font-bold uppercase tracking-widest rounded-lg">+ Add Officer</button>
            </div>

            <div class="space-y-6">
                @foreach($officers as $index => $officer)
                    <div class="p-6 bg-gray-50 rounded-2xl border border-gray-200 relative">
                        <button wire:click="removeOfficer({{ $index }})" class="absolute top-4 right-4 text-red-500 hover:text-red-700 font-bold text-xs uppercase">Remove</button>
                        <h4 class="text-sm font-black text-gray-800 mb-4">Officer #{{ $index + 1 }}</h4>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-[10px] font-bold text-gray-500 uppercase">Position</label>
                                <input type="text" wire:model="officers.{{ $index }}.position" class="w-full text-sm rounded-lg border-gray-200">
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-[10px] font-bold text-gray-500 uppercase">Complete Name</label>
                                <input type="text" wire:model="officers.{{ $index }}.complete_name" class="w-full text-sm rounded-lg border-gray-200">
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-gray-500 uppercase">Course & Year</label>
                                <input type="text" wire:model="officers.{{ $index }}.course_and_year" placeholder="e.g. BSIT 3" class="w-full text-sm rounded-lg border-gray-200">
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-[10px] font-bold text-gray-500 uppercase">College</label>
                                <select wire:model="officers.{{ $index }}.college_id" class="w-full text-sm rounded-lg border-gray-200">
                                    <option value="">Select College...</option>
                                    @foreach($colleges as $college)
                                        <option value="{{ $college->id }}">{{ $college->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-gray-500 uppercase">Contact Number</label>
                                <input type="text" wire:model="officers.{{ $index }}.contact_number" class="w-full text-sm rounded-lg border-gray-200">
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-[10px] font-bold text-gray-500 uppercase">Email Address</label>
                                <input type="email" wire:model="officers.{{ $index }}.email_address" class="w-full text-sm rounded-lg border-gray-200">
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        {{-- STEP 4: Members --}}
        @if($currentStep === 4)
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-black text-gray-900">Organization Members</h2>
                <button wire:click="addMember" class="px-4 py-2 bg-blue-50 text-blue-600 text-xs font-bold uppercase tracking-widest rounded-lg">+ Add Member</button>
            </div>

            <div class="space-y-4">
                @foreach($members as $index => $member)
                    <div class="p-4 bg-gray-50 rounded-xl border border-gray-200 flex flex-col md:flex-row gap-4 items-start md:items-center relative">
                        <div class="flex-1 grid grid-cols-1 md:grid-cols-3 gap-3 w-full">
                            <input type="text" wire:model="members.{{ $index }}.complete_name" placeholder="Complete Name" class="w-full text-sm rounded-lg border-gray-200">
                            <input type="text" wire:model="members.{{ $index }}.course_and_year" placeholder="Course & Year" class="w-full text-sm rounded-lg border-gray-200">
                            <select wire:model="members.{{ $index }}.college_id" class="w-full text-sm rounded-lg border-gray-200">
                                <option value="">Select College...</option>
                                @foreach($colleges as $college)
                                    <option value="{{ $college->id }}">{{ $college->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button wire:click="removeMember({{ $index }})" class="text-red-500 hover:text-red-700 bg-white p-2 rounded-lg border border-gray-200 shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        </button>
                    </div>
                @endforeach
            </div>
        @endif

        {{-- STEP 5: Activities --}}
        @if($currentStep === 5)
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-black text-gray-900">Program of Activities</h2>
                <button wire:click="addActivity" class="px-4 py-2 bg-blue-50 text-blue-600 text-xs font-bold uppercase tracking-widest rounded-lg">+ Add Activity</button>
            </div>

            <div class="space-y-6">
                @foreach($activities as $index => $activity)
                    <div class="p-5 bg-gray-50 rounded-2xl border border-gray-200 relative">
                        <button wire:click="removeActivity({{ $index }})" class="absolute top-4 right-4 text-red-500 hover:text-red-700 font-bold text-[10px] uppercase">Remove</button>

                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mt-2">
                            <div class="md:col-span-3">
                                <label class="block text-[10px] font-bold text-gray-500 uppercase">Activity Title</label>
                                <input type="text" wire:model="activities.{{ $index }}.title" class="w-full text-sm rounded-lg border-gray-200">
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-gray-500 uppercase">Target Month</label>
                                <input type="month" wire:model="activities.{{ $index }}.target_month" class="w-full text-sm rounded-lg border-gray-200">
                            </div>
                            <div class="md:col-span-4">
                                <label class="block text-[10px] font-bold text-gray-500 uppercase">Description</label>
                                <textarea wire:model="activities.{{ $index }}.description" rows="2" class="w-full text-sm rounded-lg border-gray-200 resize-none"></textarea>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        {{-- STEP 6: Letters & Signatories --}}
        @if($currentStep === 6)
            <div class="mb-6">
                <h2 class="text-2xl font-black text-gray-900">Letters of Intent & Acceptance</h2>
                <p class="text-xs text-gray-500 mt-1">The system will automatically generate the formal BU-OSAS letters using the signatures provided below.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

                {{-- President's Block --}}
                <div class="p-6 bg-blue-50/50 border border-blue-100 rounded-2xl">
                    <h3 class="font-black text-blue-900 mb-4 uppercase tracking-widest text-xs">President / Chairman (Prepared By)</h3>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-[10px] font-bold text-gray-500 uppercase">Complete Name</label>
                            <input type="text" wire:model="president_name" class="w-full text-sm rounded-lg border-gray-200">
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-[10px] font-bold text-gray-500 uppercase">Contact Number</label>
                                <input type="text" wire:model="president_contact" class="w-full text-sm rounded-lg border-gray-200">
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-gray-500 uppercase">Active Email</label>
                                <input type="email" wire:model="president_email" class="w-full text-sm rounded-lg border-gray-200">
                            </div>
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1">Upload E-Signature (PNG with transparent background)</label>
                            <input type="file" wire:model="president_signature" accept="image/png, image/jpeg" class="w-full text-sm bg-white p-2 rounded-lg border border-gray-200">
                        </div>

                        <div class="mt-4 p-4 bg-white rounded-lg border border-gray-200 text-[10px] text-gray-600 leading-relaxed italic">
                            "I guarantee that the purposes of our organization are not contrary to law, morals, good custom, public policy or public order... Further, the membership is voluntary and open to all students..."
                        </div>
                    </div>
                </div>

                {{-- Adviser's Block --}}
                <div class="p-6 bg-orange-50/50 border border-orange-100 rounded-2xl">
                    <h3 class="font-black text-orange-900 mb-4 uppercase tracking-widest text-xs">Organization Adviser</h3>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-[10px] font-bold text-gray-500 uppercase">Complete Name with Title</label>
                            <input type="text" wire:model="adviser_name" placeholder="e.g. Prof. Juan Dela Cruz, MSc" class="w-full text-sm rounded-lg border-gray-200">
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-[10px] font-bold text-gray-500 uppercase">Contact Number</label>
                                <input type="text" wire:model="adviser_contact" class="w-full text-sm rounded-lg border-gray-200">
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-gray-500 uppercase">BU Email Address</label>
                                <input type="email" wire:model="adviser_email" class="w-full text-sm rounded-lg border-gray-200">
                            </div>
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1">Upload Adviser's E-Signature</label>
                            <input type="file" wire:model="adviser_signature" accept="image/png, image/jpeg" class="w-full text-sm bg-white p-2 rounded-lg border border-gray-200">
                        </div>

                        <div class="mt-4 p-4 bg-white rounded-lg border border-gray-200 text-[10px] text-gray-600 leading-relaxed italic">
                            "I am willing to devote part of my time to assist the officers and members of the organization... I accept the responsibilities of an Adviser as enumerated in the BU Student Handbook..."
                        </div>
                    </div>
                </div>

                <div class="md:col-span-2 p-6 bg-gray-50 border border-gray-200 rounded-2xl">
                    <label class="block text-[10px] font-bold text-gray-700 uppercase mb-2">Organization Scope (For Routing)</label>
                    <select wire:model="committee_type" class="w-full md:w-1/2 text-sm rounded-lg border-gray-200">
                        <option value="CBO">College-Based Organization (CBO)</option>
                        <option value="UBO">University-Based Organization (UBO)</option>
                    </select>
                </div>
            </div>
        @endif

        {{-- WIZARD NAVIGATION FOOTER --}}
        <div class="mt-10 pt-6 border-t border-gray-100 flex items-center justify-between">
            @if($currentStep > 1)
                <button wire:click="previousStep" class="px-6 py-2.5 bg-gray-100 text-gray-600 font-bold uppercase tracking-widest text-xs rounded-xl hover:bg-gray-200 transition-colors">Back</button>
            @else
                <div></div> {{-- Empty div for flex-between alignment --}}
            @endif

            @if($currentStep < 5)
                <button wire:click="nextStep" class="px-8 py-2.5 bg-blue-600 text-white font-black uppercase tracking-widest text-xs rounded-xl shadow-md hover:bg-blue-700 transition-colors flex items-center gap-2">
                    Next Step <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7"></path></svg>
                </button>
            @else
                <button wire:click="submit" class="px-8 py-2.5 bg-green-600 text-white font-black uppercase tracking-widest text-xs rounded-xl shadow-md hover:bg-green-700 transition-colors flex items-center gap-2">
                    Submit Application
                </button>
            @endif
        </div>

    </div>
</div>
