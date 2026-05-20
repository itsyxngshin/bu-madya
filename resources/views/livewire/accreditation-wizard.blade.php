<div class="max-w-5xl mx-auto p-4 sm:p-6 lg:p-8 animate-fade-in-up">

    {{-- SUCCESS BANNER --}}
    @if(session()->has('success'))
        <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl text-sm font-bold flex items-center justify-between shadow-sm">
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                {{ session('success') }}
            </div>
            <button type="button" class="text-green-500 hover:text-green-700" onclick="this.parentElement.remove()">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
    @endif

    {{-- STEP PROGRESS BAR --}}
    <div class="mb-8 bg-white p-4 rounded-2xl shadow-sm border border-gray-100 flex items-center justify-between text-[10px] sm:text-xs font-black uppercase tracking-widest text-gray-400 overflow-x-auto hide-scrollbar">
        <div class="flex items-center gap-2 {{ $currentStep >= 1 ? 'text-blue-600' : '' }} shrink-0">
            <div class="w-6 h-6 rounded-full flex items-center justify-center border-2 {{ $currentStep >= 1 ? 'border-blue-600 bg-blue-50' : 'border-gray-200' }}">1</div>
            <span class="hidden md:block">General</span>
        </div>
        <div class="flex-1 h-px bg-gray-200 mx-2 md:mx-4 min-w-[20px]"></div>
        <div class="flex items-center gap-2 {{ $currentStep >= 2 ? 'text-blue-600' : '' }} shrink-0">
            <div class="w-6 h-6 rounded-full flex items-center justify-center border-2 {{ $currentStep >= 2 ? 'border-blue-600 bg-blue-50' : 'border-gray-200' }}">2</div>
            <span class="hidden md:block">Docs</span>
        </div>
        <div class="flex-1 h-px bg-gray-200 mx-2 md:mx-4 min-w-[20px]"></div>
        <div class="flex items-center gap-2 {{ $currentStep >= 3 ? 'text-blue-600' : '' }} shrink-0">
            <div class="w-6 h-6 rounded-full flex items-center justify-center border-2 {{ $currentStep >= 3 ? 'border-blue-600 bg-blue-50' : 'border-gray-200' }}">3</div>
            <span class="hidden md:block">Officers</span>
        </div>
        <div class="flex-1 h-px bg-gray-200 mx-2 md:mx-4 min-w-[20px]"></div>
        <div class="flex items-center gap-2 {{ $currentStep >= 4 ? 'text-blue-600' : '' }} shrink-0">
            <div class="w-6 h-6 rounded-full flex items-center justify-center border-2 {{ $currentStep >= 4 ? 'border-blue-600 bg-blue-50' : 'border-gray-200' }}">4</div>
            <span class="hidden md:block">Members</span>
        </div>
        <div class="flex-1 h-px bg-gray-200 mx-2 md:mx-4 min-w-[20px]"></div>
        <div class="flex items-center gap-2 {{ $currentStep >= 5 ? 'text-blue-600' : '' }} shrink-0">
            <div class="w-6 h-6 rounded-full flex items-center justify-center border-2 {{ $currentStep >= 5 ? 'border-blue-600 bg-blue-50' : 'border-gray-200' }}">5</div>
            <span class="hidden md:block">Activities</span>
        </div>
        <div class="flex-1 h-px bg-gray-200 mx-2 md:mx-4 min-w-[20px]"></div>
        <div class="flex items-center gap-2 {{ $currentStep == 6 ? 'text-blue-600' : '' }} shrink-0">
            <div class="w-6 h-6 rounded-full flex items-center justify-center border-2 {{ $currentStep == 6 ? 'border-blue-600 bg-blue-50' : 'border-gray-200' }}">6</div>
            <span class="hidden md:block">Sign</span>
        </div>
    </div>

    <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 p-6 md:p-10">

        {{-- STEP 1: General & Finance --}}
        @if($currentStep === 1)
            <h2 class="text-2xl font-black text-gray-900 mb-6">General Information</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-1">Organization Name</label>
                    <input type="text" wire:model="name" class="w-full rounded-xl border-gray-200 bg-gray-50 focus:border-blue-500">
                    @error('name') <span class="text-red-500 text-xs mt-1 block font-medium">{{ $message }}</span> @enderror
                </div>

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
                    <button @click="open = !open" @click.away="open = false" type="button"
                            class="w-full flex items-center justify-between px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:border-blue-500 text-sm">
                        <span x-text="selectedLabel" :class="$wire.type ? 'text-gray-900 font-bold' : 'text-gray-500'"></span>
                        <svg class="w-4 h-4 text-gray-400" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </button>
                    <div x-show="open" class="absolute z-50 w-full mt-2 bg-white border border-gray-100 rounded-xl shadow-xl overflow-hidden" style="display: none;">
                        <div class="p-1 max-h-60 overflow-y-auto">
                            <template x-for="option in options" :key="option.value">
                                <button @click="$wire.set('type', option.value); open = false" type="button"
                                        class="w-full text-left px-4 py-2.5 text-sm rounded-lg flex items-center justify-between"
                                        :class="$wire.type === option.value ? 'bg-blue-50 text-blue-700 font-bold' : 'text-gray-700 hover:bg-gray-50'">
                                    <span x-text="option.label"></span>
                                    <svg x-show="$wire.type === option.value" class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                </button>
                            </template>
                        </div>
                    </div>
                    @error('type') <span class="text-red-500 text-xs mt-1 block font-medium">{{ $message }}</span> @enderror
                </div>

                @if($type === 'others')
                    <div class="md:col-span-2 p-4 bg-gray-50 rounded-xl border border-gray-200">
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-1">Specify Type</label>
                        <input type="text" wire:model="type_specification" class="w-full rounded-lg border-gray-200">
                        @error('type_specification') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>
                @endif

                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-1">Academic Year</label>
                    <select wire:model="academic_year_id" class="w-full rounded-xl border-gray-200 bg-gray-50">
                        <option value="">Select A.Y...</option>
                        @foreach($academicYears as $ay)
                            <option value="{{ $ay->id }}">{{ $ay->name }}</option>
                        @endforeach
                    </select>
                    @error('academic_year_id') <span class="text-red-500 text-xs mt-1 block font-medium">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-1">Year Established</label>
                    <input type="number" wire:model="year_established" class="w-full rounded-xl border-gray-200 bg-gray-50">
                    @error('year_established') <span class="text-red-500 text-xs mt-1 block font-medium">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-1">Official Email</label>
                    <input type="email" wire:model="email_address" class="w-full rounded-xl border-gray-200 bg-gray-50">
                    @error('email_address') <span class="text-red-500 text-xs mt-1 block font-medium">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-1">Facebook Page</label>
                    <input type="text" wire:model="facebook_account" class="w-full rounded-xl border-gray-200 bg-gray-50">
                    @error('facebook_account') <span class="text-red-500 text-xs mt-1 block font-medium">{{ $message }}</span> @enderror
                </div>

                <div class="md:col-span-2 pt-6 mt-2 border-t border-gray-100">
                    <h3 class="font-black text-gray-900 mb-4">Financial & Bank Details</h3>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-1">Membership Fee (PHP)</label>
                    <input type="number" step="0.01" wire:model="membership_fee" class="w-full rounded-xl border-gray-200 bg-gray-50">
                    @error('membership_fee') <span class="text-red-500 text-xs mt-1 block font-medium">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-1">Collection Frequency</label>
                    <select wire:model="collection_frequency" class="w-full rounded-xl border-gray-200 bg-gray-50">
                        <option value="none">None</option>
                        <option value="semestral">Semestral</option>
                        <option value="annual">Annual</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-1">Bank Name</label>
                    <input type="text" wire:model="bank_name" class="w-full rounded-xl border-gray-200 bg-gray-50">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-1">Account Number</label>
                    <input type="text" wire:model="bank_account_number" class="w-full rounded-xl border-gray-200 bg-gray-50">
                </div>
            </div>
        @endif

        {{-- STEP 2: Documents --}}
        @if($currentStep === 2)
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h2 class="text-2xl font-black text-gray-900">Required Documents</h2>
                </div>
                <div class="bg-gray-100 p-1 rounded-xl flex gap-1 shadow-inner">
                    <button wire:click="$set('application_type', 'accreditation')" class="px-4 py-1.5 text-xs font-black uppercase rounded-lg {{ $application_type === 'accreditation' ? 'bg-white shadow text-blue-600' : 'text-gray-400' }}">New</button>
                    <button wire:click="$set('application_type', 'reaccreditation')" class="px-4 py-1.5 text-xs font-black uppercase rounded-lg {{ $application_type === 'reaccreditation' ? 'bg-white shadow text-orange-600' : 'text-gray-400' }}">Renew</button>
                </div>
            </div>

            <div class="space-y-6">
                {{-- Dropzone Template Maker --}}
                @php
                    function renderDropzone($model, $existing, $label, $accept, $iconColor) {
                        return '
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">'.$label.'</label>
                            <div class="relative group rounded-2xl border-2 border-dashed transition-all p-6 flex flex-col items-center justify-center text-center overflow-hidden
                                        '.($model || $existing ? 'border-green-400 bg-green-50' : 'border-gray-300 bg-gray-50 hover:bg-gray-100').'">
                                <input type="file" wire:model="'.$model.'" accept="'.$accept.'" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">

                                '.($model ? '
                                    <div class="text-green-600">
                                        <svg class="w-8 h-8 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                        <span class="text-[10px] font-black uppercase tracking-widest">New File Attached</span>
                                    </div>
                                ' : ($existing ? '
                                    <div class="text-green-700">
                                        <svg class="w-8 h-8 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        <span class="text-[10px] font-black uppercase tracking-widest">Saved Draft File Applied</span>
                                        <p class="text-[9px] text-green-600 mt-1">Upload a new file to overwrite</p>
                                    </div>
                                ' : '
                                    <div wire:loading.remove wire:target="'.$model.'" class="text-'.$iconColor.'-500">
                                        <svg class="w-8 h-8 mx-auto mb-2 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        <span class="text-xs font-bold">Click to Upload</span>
                                    </div>
                                    <div wire:loading wire:target="'.$model.'" class="text-'.$iconColor.'-600">
                                        <span class="text-[10px] font-black uppercase tracking-widest">Uploading...</span>
                                    </div>
                                ')).'
                            </div>
                            @error("'.$model.'") <span class="text-red-500 text-xs font-medium mt-1 block">{{ $message }}</span> @enderror
                        </div>';
                    }
                @endphp

                {!! renderDropzone('bankbook_photo', $existing_bankbook, 'Proof of Account (Bankbook)', 'image/*', 'blue') !!}

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {!! renderDropzone('cbl', $existing_cbl, 'Constitution & By-Laws', '.pdf', 'gray') !!}
                    {!! renderDropzone('recent_fliers', $existing_fliers, 'Recent Fliers / Promo', '.pdf,image/*', 'gray') !!}

                    @if($application_type === 'reaccreditation')
                        {!! renderDropzone('accomplishment_report', $existing_accomplishment, 'Accomplishment Report', '.pdf', 'orange') !!}
                        {!! renderDropzone('audited_financial_report', $existing_audited, 'Audited Financials', '.pdf', 'orange') !!}
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

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div><label class="block text-[10px] font-bold text-gray-500 uppercase">Position</label><input type="text" wire:model="officers.{{ $index }}.position" class="w-full text-sm rounded-lg border-gray-200"></div>
                            <div class="md:col-span-2"><label class="block text-[10px] font-bold text-gray-500 uppercase">Name</label><input type="text" wire:model="officers.{{ $index }}.complete_name" class="w-full text-sm rounded-lg border-gray-200"></div>
                            <div><label class="block text-[10px] font-bold text-gray-500 uppercase">Course/Year</label><input type="text" wire:model="officers.{{ $index }}.course_and_year" class="w-full text-sm rounded-lg border-gray-200"></div>
                            <div class="md:col-span-2"><label class="block text-[10px] font-bold text-gray-500 uppercase">College</label>
                                <select wire:model="officers.{{ $index }}.college_id" class="w-full text-sm rounded-lg border-gray-200">
                                    <option value="">Select College...</option>
                                    @foreach($colleges as $college)<option value="{{ $college->id }}">{{ $college->name }}</option>@endforeach
                                </select>
                            </div>
                            <div><label class="block text-[10px] font-bold text-gray-500 uppercase">Contact</label><input type="text" wire:model="officers.{{ $index }}.contact_number" class="w-full text-sm rounded-lg border-gray-200"></div>
                            <div class="md:col-span-2"><label class="block text-[10px] font-bold text-gray-500 uppercase">Email</label><input type="email" wire:model="officers.{{ $index }}.email_address" class="w-full text-sm rounded-lg border-gray-200"></div>
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
                    <div class="p-4 bg-gray-50 rounded-xl border border-gray-200 flex flex-col md:flex-row gap-4 items-start md:items-center">
                        <div class="flex-1 grid grid-cols-1 md:grid-cols-3 gap-3 w-full">
                            <input type="text" wire:model="members.{{ $index }}.complete_name" placeholder="Name" class="w-full text-sm rounded-lg border-gray-200">
                            <input type="text" wire:model="members.{{ $index }}.course_and_year" placeholder="Course/Year" class="w-full text-sm rounded-lg border-gray-200">
                            <select wire:model="members.{{ $index }}.college_id" class="w-full text-sm rounded-lg border-gray-200">
                                <option value="">Select College...</option>
                                @foreach($colleges as $college)<option value="{{ $college->id }}">{{ $college->name }}</option>@endforeach
                            </select>
                        </div>
                        <button wire:click="removeMember({{ $index }})" class="text-red-500 hover:text-red-700 bg-white p-2 rounded-lg border border-gray-200 shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
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
                        <button wire:click="removeActivity({{ $index }})" class="absolute top-4 right-4 text-red-500 font-bold text-[10px] uppercase">Remove</button>
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mt-2">
                            <div class="md:col-span-3"><label class="block text-[10px] font-bold text-gray-500 uppercase">Title</label><input type="text" wire:model="activities.{{ $index }}.title" class="w-full text-sm rounded-lg border-gray-200"></div>
                            <div><label class="block text-[10px] font-bold text-gray-500 uppercase">Month</label><input type="month" wire:model="activities.{{ $index }}.target_month" class="w-full text-sm rounded-lg border-gray-200"></div>
                            <div class="md:col-span-4"><label class="block text-[10px] font-bold text-gray-500 uppercase">Description</label><textarea wire:model="activities.{{ $index }}.description" rows="2" class="w-full text-sm rounded-lg border-gray-200 resize-none"></textarea></div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        {{-- STEP 6: Signatories --}}
        @if($currentStep === 6)
            <div class="mb-6">
                <h2 class="text-2xl font-black text-gray-900">Letters & Signatures</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="p-6 bg-blue-50/50 border border-blue-100 rounded-2xl space-y-4">
                    <h3 class="font-black text-blue-900 uppercase tracking-widest text-xs">President (Prepared By)</h3>
                    <div><label class="block text-[10px] font-bold text-gray-500">Name</label><input type="text" wire:model="president_name" class="w-full text-sm rounded-lg border-gray-200"> @error('president_name') <span class="text-red-500 text-[10px]">{{ $message }}</span> @enderror</div>
                    <div class="grid grid-cols-2 gap-4">
                        <div><label class="block text-[10px] font-bold text-gray-500">Contact</label><input type="text" wire:model="president_contact" class="w-full text-sm rounded-lg border-gray-200"></div>
                        <div><label class="block text-[10px] font-bold text-gray-500">Email</label><input type="email" wire:model="president_email" class="w-full text-sm rounded-lg border-gray-200">@error('president_email') <span class="text-red-500 text-[10px]">{{ $message }}</span> @enderror</div>
                    </div>
                    {!! renderDropzone('president_signature', $existing_president_signature, 'President Signature (PNG)', 'image/png, image/jpeg', 'blue') !!}
                </div>

                <div class="p-6 bg-orange-50/50 border border-orange-100 rounded-2xl space-y-4">
                    <h3 class="font-black text-orange-900 uppercase tracking-widest text-xs">Org Adviser</h3>
                    <div><label class="block text-[10px] font-bold text-gray-500">Name</label><input type="text" wire:model="adviser_name" class="w-full text-sm rounded-lg border-gray-200">@error('adviser_name') <span class="text-red-500 text-[10px]">{{ $message }}</span> @enderror</div>
                    <div class="grid grid-cols-2 gap-4">
                        <div><label class="block text-[10px] font-bold text-gray-500">Contact</label><input type="text" wire:model="adviser_contact" class="w-full text-sm rounded-lg border-gray-200"></div>
                        <div><label class="block text-[10px] font-bold text-gray-500">Email</label><input type="email" wire:model="adviser_email" class="w-full text-sm rounded-lg border-gray-200">@error('adviser_email') <span class="text-red-500 text-[10px]">{{ $message }}</span> @enderror</div>
                    </div>
                    {!! renderDropzone('adviser_signature', $existing_adviser_signature, 'Adviser Signature (PNG)', 'image/png, image/jpeg', 'orange') !!}
                </div>

                <div class="md:col-span-2 p-6 bg-gray-50 border border-gray-200 rounded-2xl">
                    <label class="block text-[10px] font-bold text-gray-700 uppercase mb-2">Scope (For Routing)</label>
                    <select wire:model="committee_type" class="w-full md:w-1/2 text-sm rounded-lg border-gray-200">
                        <option value="CBO">College-Based Organization (CBO)</option>
                        <option value="UBO">University-Based Organization (UBO)</option>
                    </select>
                </div>
            </div>
        @endif

        {{-- WIZARD NAVIGATION FOOTER --}}
        <div class="mt-10 pt-6 border-t border-gray-100 flex items-center justify-between">
            <div>
                @if($currentStep > 1)
                    <button wire:click="previousStep" class="px-6 py-2.5 bg-gray-100 text-gray-600 font-bold uppercase tracking-widest text-xs rounded-xl hover:bg-gray-200 transition-colors">Back</button>
                @endif
            </div>

            <div class="flex items-center gap-3">
                @if($academic_year_id)
                    <button wire:click="saveDraft" class="px-6 py-2.5 bg-white border border-gray-200 text-gray-700 font-bold uppercase tracking-widest text-xs rounded-xl hover:bg-gray-50 transition-colors flex items-center gap-2 shadow-sm">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg> Save Draft
                    </button>
                @endif

                @if($currentStep < 6)
                    <button wire:click="nextStep" class="px-8 py-2.5 bg-blue-600 text-white font-black uppercase tracking-widest text-xs rounded-xl shadow-md hover:bg-blue-700 transition-colors flex items-center gap-2">
                        Next <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="3" d="M9 5l7 7-7 7"></path></svg>
                    </button>
                @else
                    <button wire:click="submit" class="px-8 py-2.5 bg-green-600 text-white font-black uppercase tracking-widest text-xs rounded-xl shadow-md hover:bg-green-700 transition-colors flex items-center gap-2">
                        Submit
                    </button>
                @endif
            </div>
        </div>

    </div>
</div>
