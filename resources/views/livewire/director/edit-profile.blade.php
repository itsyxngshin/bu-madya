<div class="max-w-4xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
    <div class="bg-white rounded-3xl shadow-xl overflow-hidden">
        
        <div class="p-8 border-b border-gray-100 flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Edit Profile</h2>
                <p class="text-sm text-gray-500">Update your public information.</p>
            </div>
            <a href="{{ route('profile.public', auth()->user()->username) }}" class="text-sm text-blue-600 hover:underline">
                &larr; Back to Profile
            </a>
        </div>

        <div class="p-8 grid md:grid-cols-3 gap-8">
            
            {{-- 1. LEFT COLUMN: PHOTO & BASIC INFO --}}
            <div class="md:col-span-1 space-y-6">
                {{-- ... (Keep existing Photo, Course, Year Level inputs) ... --}}
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Profile Photo</label>
                    <div class="flex items-center gap-4">
                         <div class="w-16 h-16 rounded-full overflow-hidden border border-gray-200">
                            @if ($photo)
                                <img src="{{ $photo->temporaryUrl() }}" class="w-full h-full object-cover">
                            @else
                                @php
                                    $path = $user->profile_photo_path;
                                    $url = (!$path) ? 'https://ui-avatars.com/api/?name='.urlencode($user->name).'&color=7F9CF5&background=EBF4FF' 
                                        : (Str::startsWith($path, 'http') ? $path 
                                        : (file_exists(public_path($path)) ? asset($path) : asset('storage/' . $path)));
                                @endphp
                                <img src="{{ $url }}" class="w-full h-full object-cover">
                            @endif
                        </div>
                        <label class="cursor-pointer bg-white border border-gray-300 px-3 py-1.5 rounded-lg text-xs font-bold hover:bg-gray-50 transition">
                            Change
                            <input type="file" wire:model="photo" class="hidden">
                        </label>
                    </div>
                    @error('photo') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Course</label>
                    <input type="text" wire:model="course" class="w-full rounded-xl border-gray-200 text-sm focus:ring-yellow-400 focus:border-yellow-400">
                </div>

                 <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Year Level</label>
                    <select wire:model="year_level" class="w-full rounded-xl border-gray-200 text-sm">
                        <option>1st Year</option>
                        <option>2nd Year</option>
                        <option>3rd Year</option>
                        <option>4th Year</option>
                    </select>
                </div>
            </div>

            {{-- 2. RIGHT COLUMN: BIO, ENGAGEMENTS, PORTFOLIOS --}}
            <div class="md:col-span-2 space-y-10">
                
                {{-- A. BIO --}}
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Bio</label>
                    <textarea wire:model="bio" rows="4" class="w-full rounded-xl border-gray-200 text-sm focus:ring-yellow-400 focus:border-yellow-400" placeholder="Tell us about yourself..."></textarea>
                    
                    <div class="mt-3 flex justify-between items-center">
                        @if (session()->has('message'))
                            <span class="text-green-600 text-xs font-bold animate-pulse">{{ session('message') }}</span>
                        @else
                            <span></span>
                        @endif
                        <button wire:click="saveBasic" class="bg-gray-900 text-white px-4 py-2 rounded-xl text-sm font-bold hover:bg-gray-800 transition shadow-lg">
                            Save Bio
                        </button>
                    </div>
                </div>

                <hr class="border-gray-100">

                {{-- B. PORTFOLIOS SECTION --}}
                <div>
                    <div class="flex justify-between items-center mb-4">
                        <label class="block text-sm font-bold text-gray-700 flex items-center gap-2">
                            <span class="w-6 h-6 rounded-full bg-red-100 text-red-600 flex items-center justify-center text-xs">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                            </span>
                            Portfolios & Experience
                        </label>
                        <button wire:click="addPortfolio" class="text-xs text-blue-600 font-bold hover:underline flex items-center gap-1">
                            + Add New
                        </button>
                    </div>

                    <div class="space-y-4">
                        @foreach($portfolios as $index => $portfolio)
                        <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm relative group hover:border-red-200 transition">
                            
                            {{-- Remove Button --}}
                            <button wire:click="removePortfolio({{ $index }})" class="absolute top-2 right-2 text-gray-300 hover:text-red-500 transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>

                            <div class="grid grid-cols-2 gap-3 mb-2 pr-6">
                                <input type="text" wire:model="portfolios.{{ $index }}.designation" placeholder="Designation (e.g. President)" class="col-span-2 md:col-span-1 w-full rounded-lg border-gray-200 text-xs font-bold focus:border-red-400 focus:ring-red-400">
                                <input type="text" wire:model="portfolios.{{ $index }}.place" placeholder="Organization / Place" class="col-span-2 md:col-span-1 w-full rounded-lg border-gray-200 text-xs focus:border-red-400 focus:ring-red-400">
                            </div>
                            
                            <div class="grid grid-cols-2 gap-3 mb-2">
                                <select wire:model="portfolios.{{ $index }}.status" class="w-full rounded-lg border-gray-200 text-xs focus:border-red-400 focus:ring-red-400">
                                    <option value="Active">Active</option>
                                    <option value="Former">Former</option>
                                </select>
                                <input type="text" wire:model="portfolios.{{ $index }}.duration" placeholder="Duration (e.g. 2023-2024)" class="w-full rounded-lg border-gray-200 text-xs focus:border-red-400 focus:ring-red-400">
                            </div>

                            <textarea wire:model="portfolios.{{ $index }}.description" placeholder="Short description of your role..." rows="2" class="w-full rounded-lg border-gray-200 text-xs focus:border-red-400 focus:ring-red-400"></textarea>
                        </div>
                        @endforeach
                    </div>

                    <div class="mt-4 text-right">
                        @if (session()->has('portfolio_message'))
                            <span class="text-green-600 text-xs font-bold mr-2">{{ session('portfolio_message') }}</span>
                        @endif
                        <button wire:click="savePortfolios" class="bg-white border border-gray-300 text-gray-700 px-4 py-2 rounded-xl text-sm font-bold hover:bg-gray-50 transition">
                            Update Portfolios
                        </button>
                    </div>
                </div>

                <hr class="border-gray-100">

                {{-- C. ENGAGEMENTS SECTION --}}
                <div>
                    <div class="flex justify-between items-center mb-4">
                        <label class="block text-sm font-bold text-gray-700 flex items-center gap-2">
                            <span class="w-6 h-6 rounded-full bg-yellow-100 text-yellow-600 flex items-center justify-center text-xs">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"></path></svg>
                            </span>
                            Engagements
                        </label>
                        <button wire:click="addEngagement" class="text-xs text-blue-600 font-bold hover:underline flex items-center gap-1">
                            + Add New
                        </button>
                    </div>

                    <div class="space-y-3">
                        @foreach($engagements as $index => $engagement)
                        <div class="bg-gray-50 p-3 rounded-xl border border-gray-200 flex gap-3 items-start group hover:border-blue-200 transition relative">
                             <button wire:click="removeEngagement({{ $index }})" class="absolute top-2 right-2 text-gray-300 hover:text-red-500 transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>

                            <div class="flex-1 space-y-2 pr-6">
                                <input type="text" wire:model="engagements.{{ $index }}.title" placeholder="Event Title" class="w-full rounded-lg border-gray-200 text-xs p-2 focus:ring-blue-400 focus:border-blue-400 font-bold">
                                <textarea wire:model="engagements.{{ $index }}.description" placeholder="Description" rows="2" class="w-full rounded-lg border-gray-200 text-xs p-2 focus:ring-blue-400 focus:border-blue-400"></textarea>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    
                    <div class="mt-4 text-right">
                        @if (session()->has('engagement_message'))
                            <span class="text-green-600 text-xs font-bold mr-2">{{ session('engagement_message') }}</span>
                        @endif
                        <button wire:click="saveEngagements" class="bg-white border border-gray-300 text-gray-700 px-4 py-2 rounded-xl text-sm font-bold hover:bg-gray-50 transition">
                            Update Engagements
                        </button>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>