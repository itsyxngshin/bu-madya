<div class="max-w-4xl mx-auto py-6 px-4 sm:px-6 sm:py-12 lg:px-8"> {{-- Adjusted py-12 to py-6 for mobile --}}
    
    {{-- CASE 1: NO ACTIVE WAVE --}}
    @if(!$activeWave)
        <div class="text-center py-12 sm:py-20 bg-white rounded-2xl shadow-xl"> {{-- Reduced vertical padding --}}
            <div class="bg-gray-100 w-16 h-16 sm:w-20 sm:h-20 rounded-full flex items-center justify-center mx-auto mb-4 sm:mb-6">
                <svg class="w-8 h-8 sm:w-10 sm:h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
            </div>
            <h2 class="text-2xl sm:text-3xl font-extrabold text-gray-900">Registration is Closed</h2>
            <p class="mt-2 sm:mt-4 text-sm sm:text-base text-gray-500 max-w-lg mx-auto px-4">
                Thank you for your interest in BU MADYA. We are not currently accepting applications. Please stay tuned for our next recruitment wave!
            </p>
        </div>
    
    {{-- CASE 2: FORM IS OPEN --}}
    @else
        <div class="text-center mb-6 sm:mb-10">
            <span class="bg-red-100 text-red-800 text-[10px] sm:text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider mb-2 inline-block">
                {{ $activeWave->name }}
            </span>
            <div class="text-center mb-6 sm:mb-10">
                <h2 class="text-2xl sm:text-3xl font-extrabold text-gray-900 tracking-tight sm:text-4xl">
                    Join BU MADYA
                </h2>
                <p class="mt-2 sm:mt-4 text-sm sm:text-lg text-gray-500">
                    Be a Member-Advocate. Fill out the form below to begin.
                </p>
            </div>

            @if (session()->has('message'))
                <div class="mb-6 p-4 bg-green-100 border-l-4 border-green-500 text-green-700 text-sm sm:text-base text-left">
                    <p class="font-bold">Success!</p>
                    <p>{{ session('message') }}</p>
                </div>
            @endif

            {{-- FORM CONTAINER: Reduced padding p-4 for mobile --}}
            <form wire:submit="submit" class="space-y-6 sm:space-y-8 divide-y divide-gray-200 bg-white p-4 sm:p-8 rounded-2xl shadow-xl text-left">
                
                {{-- SECTION 1: PRIVACY ACT --}}
                <div class="space-y-4 sm:space-y-6 pt-2">
                    <div>
                        <h3 class="text-base sm:text-lg leading-6 font-medium text-gray-900">Data Privacy Consent</h3>
                        <p class="mt-1 text-xs sm:text-sm text-gray-500">In compliance with the Data Privacy Act 2012 (RA 10173).</p>
                    </div>
                    <div class="flex items-start">
                        <div class="flex items-center h-5">
                            <input wire:model="privacy_consent" id="consent" type="checkbox" class="focus:ring-red-500 h-4 w-4 text-red-600 border-gray-300 rounded">
                        </div>
                        <div class="ml-3 text-sm">
                            <label for="consent" class="font-medium text-gray-700 text-sm">I Consent</label>
                            <p class="text-xs sm:text-sm text-gray-500">I hereby consent to the collection, usage, processing, storage, and preservation of personal data by BU MADYA.</p>
                            @error('privacy_consent') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>

                {{-- SECTION 2: PERSONAL INFO --}}
                <div class="pt-6 sm:pt-8 space-y-4 sm:space-y-6">
                    <div>
                        <h3 class="text-base sm:text-lg leading-6 font-medium text-gray-900">Personal Information</h3>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 sm:gap-6">
                        {{-- Name --}}
                        <div class="md:col-span-1">
                            <label class="block text-xs sm:text-sm font-medium text-gray-700">Surname</label>
                            {{-- Added text-base for mobile to prevent iOS Zoom --}}
                            <input wire:model="last_name" type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 text-base sm:text-sm">
                            @error('last_name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div class="md:col-span-1">
                            <label class="block text-xs sm:text-sm font-medium text-gray-700">First Name</label>
                            <input wire:model="first_name" type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 text-base sm:text-sm">
                            @error('first_name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div class="md:col-span-1">
                            <label class="block text-xs sm:text-sm font-medium text-gray-700">M.I.</label>
                            <input wire:model="middle_initial" type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 text-base sm:text-sm">
                        </div>

                        {{-- Address & Bday --}}
                        <div class="md:col-span-2">
                            <label class="block text-xs sm:text-sm font-medium text-gray-700">Home Address</label>
                            <input wire:model="home_address" type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 text-base sm:text-sm">
                            @error('home_address') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div class="md:col-span-1">
                            <label class="block text-xs sm:text-sm font-medium text-gray-700">Birthday</label>
                            <input wire:model="birthday" type="date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 text-base sm:text-sm">
                            @error('birthday') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        {{-- Contacts --}}
                        <div class="md:col-span-1">
                            <label class="block text-xs sm:text-sm font-medium text-gray-700">Contact No.</label>
                            <input wire:model="contact_number" type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 text-base sm:text-sm">
                        </div>
                        <div class="md:col-span-1">
                            <label class="block text-xs sm:text-sm font-medium text-gray-700">Email Address</label>
                            <input wire:model="email" type="email" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 text-base sm:text-sm">
                            @error('email') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div class="md:col-span-1">
                            <label class="block text-xs sm:text-sm font-medium text-gray-700">Facebook Link</label>
                            <input wire:model="facebook_link" type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 text-base sm:text-sm" placeholder="https://facebook.com/...">
                        </div>
                    </div>
                </div>

                {{-- SECTION 3: ACADEMIC --}}
                <div class="pt-6 sm:pt-8 space-y-4 sm:space-y-6">
                    <h3 class="text-base sm:text-lg leading-6 font-medium text-gray-900">Academic Details</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 sm:gap-6">
                        <div class="md:col-span-1">
                            <label class="block text-xs sm:text-sm font-medium text-gray-700">College / Institute</label>
                            {{-- Update wire:model --}}
                            <select wire:model="college_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 text-base sm:text-sm">
                                <option value="">Select College</option>
                                @foreach($colleges as $c) 
                                    {{-- Update value to ID --}}
                                    <option value="{{ $c->id }}">{{ $c->name }}</option> 
                                @endforeach
                            </select>
                            @error('college_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div class="md:col-span-1">
                            <label class="block text-xs sm:text-sm font-medium text-gray-700">Year Level</label>
                            <select wire:model="year_level" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 text-base sm:text-sm">
                                <option value="">Select Year</option>
                                <option>1st Year</option><option>2nd Year</option><option>3rd Year</option><option>4th Year</option><option>5th Year</option>
                            </select>
                        </div>
                        <div class="md:col-span-1">
                            <label class="block text-xs sm:text-sm font-medium text-gray-700">Course / Program</label>
                            <input wire:model="course" type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 text-base sm:text-sm">
                        </div>
                    </div>
                </div>

                {{-- SECTION 4: FILES & AFFILIATIONS --}}
                <div class="pt-6 sm:pt-8 space-y-4 sm:space-y-6">
                    <h3 class="text-base sm:text-lg leading-6 font-medium text-gray-900">Affiliations & Documents</h3>
                    
                    {{-- Politics --}}
                    <div>
                        <label class="block text-xs sm:text-sm font-medium text-gray-700">Political Affiliations</label>
                        <p class="text-[10px] sm:text-xs text-gray-500 mb-2">NOTE: BU MADYA is nonpartisan. We encourage non-affiliation to any political parties.</p>
                        <textarea wire:model="political_affiliation" rows="2" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 text-base sm:text-sm" placeholder="List affiliations or type 'None'"></textarea>
                    </div>

                    {{-- File Uploads (Grid) --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
            
                        {{-- A. FORMAL PHOTO --}}
                        <div x-data="{ isDropping: false, isUploading: false, progress: 0 }"
                            x-on:livewire-upload-start="isUploading = true"
                            x-on:livewire-upload-finish="isUploading = false"
                            x-on:livewire-upload-error="isUploading = false"
                            x-on:livewire-upload-progress="progress = $event.detail.progress">
                            
                            <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-2">Formal Picture (White BG)</label>

                            {{-- Reduced height to h-32 on mobile --}}
                            <label class="relative flex flex-col items-center justify-center w-full h-32 sm:h-48 border-2 border-dashed rounded-lg cursor-pointer transition-all hover:bg-gray-50 group overflow-hidden"
                                :class="{'border-red-500 bg-red-50 ring-2 ring-red-200': isDropping, 'border-gray-300': !isDropping}"
                                @dragover.prevent="isDropping = true"
                                @dragleave.prevent="isDropping = false"
                                @drop.prevent="isDropping = false">
                                
                                <input type="file" wire:model="photo" accept="image/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-50">

                                <div x-show="isUploading" class="absolute inset-0 z-40 flex flex-col items-center justify-center bg-white/90">
                                    <svg class="animate-spin h-6 w-6 text-red-600 mb-2" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                    <div class="w-1/2 bg-gray-200 rounded-full h-1"><div class="bg-red-600 h-1 rounded-full transition-all" :style="'width: ' + progress + '%'"></div></div>
                                </div>

                                @if ($photo)
                                    <img src="{{ $photo->temporaryUrl() }}" class="absolute inset-0 w-full h-full object-cover z-30">
                                    <div class="absolute bottom-2 bg-white/80 px-2 py-1 rounded text-[10px] font-bold z-30 shadow-sm">Click to Change</div>
                                @else
                                    <div class="text-center p-4">
                                        <svg class="mx-auto h-8 w-8 sm:h-10 sm:w-10 text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        <p class="text-[10px] sm:text-xs text-gray-500">Drag or <span class="text-red-600 font-bold">browse</span></p>
                                    </div>
                                @endif
                            </label>
                            @error('photo') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        {{-- B. E-SIGNATURE --}}
                        <div x-data="{ isDropping: false, isUploading: false, progress: 0 }"
                            x-on:livewire-upload-start="isUploading = true"
                            x-on:livewire-upload-finish="isUploading = false"
                            x-on:livewire-upload-error="isUploading = false"
                            x-on:livewire-upload-progress="progress = $event.detail.progress">
                            
                            <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-2">E-Signature</label>

                            <label class="relative flex flex-col items-center justify-center w-full h-32 sm:h-48 border-2 border-dashed rounded-lg cursor-pointer transition-all hover:bg-gray-50 group overflow-hidden"
                                :class="{'border-red-500 bg-red-50 ring-2 ring-red-200': isDropping, 'border-gray-300': !isDropping}"
                                @dragover.prevent="isDropping = true"
                                @dragleave.prevent="isDropping = false"
                                @drop.prevent="isDropping = false">
                                
                                <input type="file" wire:model="signature" accept="image/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-50">

                                <div x-show="isUploading" class="absolute inset-0 z-40 flex flex-col items-center justify-center bg-white/90">
                                    <svg class="animate-spin h-6 w-6 text-red-600 mb-2" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                </div>

                                @if ($signature)
                                    <img src="{{ $signature->temporaryUrl() }}" class="absolute inset-0 w-full h-full object-contain p-4 z-30">
                                    <div class="absolute bottom-2 bg-white/80 px-2 py-1 rounded text-[10px] font-bold z-30 shadow-sm">Click to Change</div>
                                @else
                                    <div class="text-center p-4">
                                        <svg class="mx-auto h-8 w-8 sm:h-10 sm:w-10 text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                        <p class="text-[10px] sm:text-xs text-gray-500">Upload E-Signature</p>
                                    </div>
                                @endif
                            </label>
                            @error('signature') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>

                {{-- SECTION 5: ESSAYS & COMMITTEES --}}
                <div class="pt-6 sm:pt-8 space-y-4 sm:space-y-6">
                    <h3 class="text-base sm:text-lg leading-6 font-medium text-gray-900">Committees & Insights</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
                        {{-- 1st Choice --}}
                        <div>
                            <label class="block text-xs sm:text-sm font-medium text-gray-700">1st Choice Committee</label>
                            <select wire:model="committee_1_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 text-base sm:text-sm">
                                <option value="">Select...</option>
                                @foreach($committees as $comm) 
                                    <option value="{{ $comm->id }}">{{ $comm->name }}</option> 
                                @endforeach
                            </select>
                        </div>

                        {{-- 2nd Choice --}}
                        <div>
                            <label class="block text-xs sm:text-sm font-medium text-gray-700">2nd Choice Committee</label>
                            <select wire:model="committee_2_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 text-base sm:text-sm">
                                <option value="">Select...</option>
                                @foreach($committees as $comm) 
                                    <option value="{{ $comm->id }}">{{ $comm->name }}</option> 
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- Essay 1 --}}
                    <div>
                        <label class="block text-xs sm:text-sm font-medium text-gray-700">1. In your perspective, what can you observe about your community?</label>
                        <textarea wire:model="essay_1_community" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 text-base sm:text-sm"></textarea>
                        @error('essay_1_community') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    {{-- Essay 2 --}}
                    <div>
                        <label class="block text-xs sm:text-sm font-medium text-gray-700">2. As advocates, what can we do to help address and confront our present societal issues?</label>
                        <textarea wire:model="essay_2_action" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 text-base sm:text-sm"></textarea>
                        @error('essay_2_action') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    {{-- Essay 3 --}}
                    <div>
                        <label class="block text-xs sm:text-sm font-medium text-gray-700">3. Briefly cite your experience with advocacy, leadership, or outreach.</label>
                        <textarea wire:model="essay_3_experience" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 text-base sm:text-sm"></textarea>
                    </div>

                    {{-- Essay 4 --}}
                    <div>
                        <label class="block text-xs sm:text-sm font-medium text-gray-700">4. Why do you want to take part as a member-advocate of BU MADYA?</label>
                        <textarea wire:model="essay_4_reason" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 text-base sm:text-sm"></textarea>
                        @error('essay_4_reason') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    {{-- Essay 5 --}}
                    <div>
                        <label class="block text-xs sm:text-sm font-medium text-gray-700">5. Provide suggestions on engagement, platforms, or projects.</label>
                        <textarea wire:model="essay_5_suggestion" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 text-base sm:text-sm"></textarea>
                    </div>
                </div>

                {{-- SECTION 6: PAYMENT & SUBMIT --}}
                <div class="pt-6 sm:pt-8 space-y-6">
                    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-3 sm:p-4">
                        <div class="flex">
                            <div class="ml-2 sm:ml-3">
                                <p class="text-xs sm:text-sm text-yellow-700">
                                    Membership Fee: <span class="font-bold">₱100.00</span> (Effective AY 2025-2026). Collection will be announced later.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-start">
                        <div class="flex items-center h-5">
                            <input wire:model="willing_to_pay" id="pay" type="checkbox" class="focus:ring-red-500 h-4 w-4 text-red-600 border-gray-300 rounded">
                        </div>
                        <div class="ml-3 text-sm">
                            <label for="pay" class="font-medium text-gray-700 text-sm">I am willing to pay the membership fee.</label>
                            @error('willing_to_pay') <span class="block text-red-600 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="flex justify-end pt-5">
                        <button type="submit" wire:loading.attr="disabled" class="w-full sm:w-auto inline-flex justify-center py-3 px-6 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-all">
                            <span wire:loading.remove>Submit Application</span>
                            <span wire:loading>Processing...</span>
                        </button>
                    </div>
                </div>
            </form>
    @endif
    
</div>