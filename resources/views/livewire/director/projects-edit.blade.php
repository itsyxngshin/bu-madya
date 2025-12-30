<div class="min-h-screen bg-gray-100 font-sans text-gray-900" x-data="{ mobilePreview: false }">

    {{-- 1. EDITOR NAVBAR --}}
    <nav class="sticky top-0 z-50 bg-white/90 backdrop-blur-md border-b border-gray-200 h-16 px-6 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <a href="{{ route('projects.index') }}" class="text-xs font-bold uppercase text-gray-400 hover:text-red-600 transition">
                &larr; Cancel
            </a>
            <div class="h-4 w-px bg-gray-300"></div>
            <span class="font-heading font-black text-gray-800 tracking-tight">
                Edit <span class="text-red-600">Project</span>
            </span>
        </div>
        
        <button @click="mobilePreview = !mobilePreview" class="md:hidden text-xs font-bold uppercase bg-gray-200 px-3 py-1 rounded">
            <span x-text="mobilePreview ? 'Edit' : 'Preview'"></span>
        </button>

        <div class="flex items-center gap-3">
            <button wire:click="update" 
                    wire:loading.attr="disabled"
                    class="px-5 py-2 bg-gradient-to-r from-red-600 to-yellow-500 text-white text-xs font-bold uppercase rounded-lg hover:shadow-lg hover:scale-105 transition shadow-md flex items-center gap-2">
                <span wire:loading.remove wire:target="update">Update Project</span>
                <span wire:loading wire:target="update">Saving...</span>
                <svg wire:loading wire:target="update" class="animate-spin h-3 w-3 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            </button>
        </div>
    </nav>

    <div class="flex h-[calc(100vh-64px)] overflow-hidden">
        
        {{-- LEFT PANEL: FORM EDITOR --}}
        <div class="w-full md:w-5/12 h-full overflow-y-auto p-6 bg-gray-50 border-r border-gray-200 space-y-6"
             :class="mobilePreview ? 'hidden' : 'block'">

            {{-- SECTION 1: BASIC INFO --}}
            <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100">
                <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest border-b border-gray-100 pb-2 mb-4">Core Details</h3>
                
                <div class="space-y-4">
                    {{-- ACADEMIC YEAR & TITLE --}}
                    <div class="grid grid-cols-3 gap-4">
                        <div class="col-span-1">
                            <label class="block text-xs font-bold text-gray-700 mb-1">Academic Year</label>
                            <select wire:model.live="academic_year_id" class="w-full text-xs border-gray-200 rounded-lg focus:ring-yellow-400 focus:border-yellow-400 bg-yellow-50/50">
                                @foreach($academic_years as $year)
                                    <option value="{{ $year->id }}">{{ $year->name }} {{ $year->is_active ? '(Active)' : '' }}</option>
                                @endforeach
                            </select>
                            @error('academic_year_id') <span class="text-red-500 text-[10px]">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-span-2">
                            <label class="block text-xs font-bold text-gray-700 mb-1">Project Title</label>
                            <input wire:model.live="title" type="text" class="w-full text-sm border-gray-200 rounded-lg focus:ring-yellow-400 focus:border-yellow-400">
                            @error('title') <span class="text-red-500 text-[10px]">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    {{-- SLUG --}}
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">URL Slug</label>
                        <div class="flex rounded-lg shadow-sm">
                            <span class="inline-flex items-center px-3 rounded-l-lg border border-r-0 border-gray-200 bg-gray-50 text-gray-500 text-xs">
                                config('app.url')/projects/
                            </span>
                            <input wire:model.live="slug" type="text" class="flex-1 min-w-0 block w-full px-3 py-2 rounded-none rounded-r-lg text-xs border-gray-200 text-gray-600 focus:ring-yellow-400">
                        </div>
                        @error('slug') <span class="text-red-500 text-[10px] mt-1">{{ $message }}</span> @enderror
                    </div>
                    
                    {{-- CATEGORY & STATUS --}}
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">Category</label>
                            <select wire:model.live="project_category_id" class="w-full text-xs border-gray-200 rounded-lg focus:ring-yellow-400">
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">Status</label>
                            <select wire:model.live="status" class="w-full text-xs border-gray-200 rounded-lg focus:ring-yellow-400">
                                <option>Upcoming</option>
                                <option>Ongoing</option>
                                <option>Completed</option>
                            </select>
                        </div>
                    </div>

                    {{-- DATE & LOCATION --}}
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">Date</label>
                            <input wire:model.live="date" type="date" class="w-full text-xs border-gray-200 rounded-lg">
                            @error('date') <span class="text-red-500 text-[10px]">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">Location</label>
                            <input wire:model.live="location" type="text" class="w-full text-xs border-gray-200 rounded-lg">
                        </div>
                    </div>
                    
                    {{-- PROPONENTS --}}
                    <div class="space-y-3">
                        <div class="flex justify-between items-center">
                            <label class="text-xs font-bold text-gray-700">Lead Proponents</label>
                            <button wire:click="addProponent" class="text-[10px] bg-gray-100 hover:bg-green-100 text-gray-600 px-2 py-1 rounded transition">+ Add</button>
                        </div>
                        @foreach($proponents as $index => $prop)
                        <div class="p-3 bg-gray-50 rounded-lg border border-gray-100 relative group">
                            @if(count($proponents) > 1)
                            <button wire:click="removeProponent({{ $index }})" class="absolute top-2 right-2 text-gray-300 hover:text-red-500">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                            @endif
                            <div class="flex bg-white p-1 rounded-md w-max mb-2 border border-gray-200">
                                <button wire:click="$set('proponents.{{ $index }}.type', 'user')" class="px-2 py-0.5 text-[9px] font-bold rounded transition {{ $prop['type'] === 'user' ? 'bg-gray-800 text-white shadow' : 'text-gray-400 hover:text-gray-600' }}">User</button>
                                <button wire:click="$set('proponents.{{ $index }}.type', 'custom')" class="px-2 py-0.5 text-[9px] font-bold rounded transition {{ $prop['type'] === 'custom' ? 'bg-gray-800 text-white shadow' : 'text-gray-400 hover:text-gray-600' }}">Custom</button>
                            </div>
                            @if($prop['type'] === 'user')
                                <select wire:model.live="proponents.{{ $index }}.id" class="w-full text-xs border-gray-200 rounded-lg focus:ring-yellow-400">
                                    <option value="">-- Select a User --</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                    @endforeach
                                </select>
                            @else
                                <input wire:model.live="proponents.{{ $index }}.name" type="text" class="w-full text-xs border-gray-200 rounded-lg focus:ring-yellow-400" placeholder="e.g. Committee on Education">
                            @endif
                        </div>
                        @endforeach
                    </div>
                    
                    {{-- BENEFICIARIES --}}
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Beneficiaries</label>
                        <input wire:model.live="beneficiaries" type="text" class="w-full text-xs border-gray-200 rounded-lg">
                    </div>

                    {{-- IMAGE UPLOAD (EDIT MODE) --}}
                    <div x-data="{ 
                            isDropping: false, 
                            isUploading: false, 
                            progress: 0 
                        }"
                        x-on:livewire-upload-start="isUploading = true"
                        x-on:livewire-upload-finish="isUploading = false"
                        x-on:livewire-upload-error="isUploading = false"
                        x-on:livewire-upload-progress="progress = $event.detail.progress"
                        class="w-full"
                    >
                        <label class="block text-xs font-bold text-gray-700 mb-1">
                            Cover Image 
                        </label>

                        {{-- PARENT CONTAINER: Enforce Fixed Height (h-40) so it never collapses --}}
                        <div class="relative w-full h-40 rounded-xl overflow-hidden bg-gray-50 border-2 border-dashed transition-all duration-200 group"
                            :class="{'border-yellow-400 bg-yellow-50 ring-4 ring-yellow-100': isDropping, 'border-gray-300 hover:bg-gray-100': !isDropping}"
                        >
                            
                            {{-- 1. THE INVISIBLE INPUT (The "Click & Drag" Layer) --}}
                            {{-- Z-Index 50 ensures this is ALWAYS on top to catch the drop --}}
                            <input type="file" 
                                wire:model="coverImg" 
                                accept="image/*" 
                                class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-50"
                                {{-- EVENTS: Attached directly to input to ensure sync --}}
                                x-on:dragover.prevent="isDropping = true"
                                x-on:dragleave="isDropping = false"
                                x-on:drop="isDropping = false"
                            >

                            {{-- 2. LOADING OVERLAY (Z-Index 40) --}}
                            <div x-show="isUploading" 
                                x-transition.opacity
                                class="absolute inset-0 z-40 flex flex-col items-center justify-center bg-white/90 backdrop-blur-sm"
                                style="display: none;"> {{-- Default hidden --}}
                                
                                <svg class="animate-spin h-8 w-8 text-yellow-500 mb-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                <div class="w-1/2 bg-gray-200 rounded-full h-1.5">
                                    <div class="bg-yellow-500 h-1.5 rounded-full transition-all duration-300" :style="'width: ' + progress + '%'"></div>
                                </div>
                                <p class="text-[10px] font-bold text-gray-500 mt-2 uppercase tracking-wider">Uploading...</p>
                            </div>

                            {{-- 3. VISUAL LAYER (Z-Index 10) --}}
                            {{-- This sits underneath the input and just looks pretty --}}
                            <div class="absolute inset-0 w-full h-full z-10 flex flex-col items-center justify-center pointer-events-none">
                                
                                {{-- A. New Upload Preview --}}
                                @if($coverImg)
                                    <img src="{{ $coverImg->temporaryUrl() }}" class="absolute inset-0 w-full h-full object-cover">
                                    <div class="absolute inset-0 bg-black/40"></div>
                                    <div class="relative bg-white/90 backdrop-blur px-3 py-1.5 rounded-full shadow-lg flex items-center gap-2">
                                        <span class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></span>
                                        <span class="text-[10px] font-bold uppercase text-gray-800">New Image Ready</span>
                                    </div>
                                
                                {{-- B. Existing Database Image --}}
                                @elseif($oldCoverImg)
                                    <img src="{{ asset('storage/'.$oldCoverImg) }}" class="absolute inset-0 w-full h-full object-cover grayscale opacity-50 group-hover:grayscale-0 group-hover:opacity-100 transition-all duration-500">
                                    
                                    <div class="relative flex flex-col items-center text-gray-600 group-hover:text-white transition-colors">
                                        <div class="bg-white/80 p-2 rounded-full shadow-sm mb-2 group-hover:scale-110 transition-transform">
                                            <svg class="w-5 h-5 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        </div>
                                        <span class="text-[10px] font-bold uppercase tracking-wider bg-white/30 px-2 py-1 rounded backdrop-blur-sm">Replace Image</span>
                                    </div>

                                {{-- C. Empty State --}}
                                @else
                                    <div class="flex flex-col items-center justify-center pt-5 pb-6 text-gray-400 group-hover:text-yellow-600 transition-colors">
                                        <svg class="w-8 h-8 mb-2 transform group-hover:-translate-y-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                                        <p class="text-xs font-medium"><span class="font-bold underline decoration-yellow-400">Click to upload</span> or drag & drop</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                        
                        @error('coverImg') <span class="text-red-500 text-[10px] mt-1 block">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            {{-- SECTION 2: DESCRIPTION --}}
            <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100">
                <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest border-b border-gray-100 pb-2 mb-4">The Story</h3>
                <textarea wire:model.live="description" rows="5" class="w-full text-sm border-gray-200 rounded-lg focus:ring-yellow-400"></textarea>
            </div>

            {{-- SECTION 3: DYNAMIC LISTS --}}
            <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 space-y-6">
                
                {{-- OBJECTIVES --}}
                <div>
                    <div class="flex justify-between items-center mb-2">
                        <label class="text-xs font-bold text-gray-700 uppercase">Objectives</label>
                        <button wire:click="addObjective" class="text-[10px] bg-gray-100 hover:bg-green-100 text-gray-600 px-2 py-1 rounded transition">+ Add</button>
                    </div>
                    <div class="space-y-2">
                        @foreach($objectives as $index => $obj)
                        <div class="flex gap-2">
                            <input wire:model.live="objectives.{{ $index }}" type="text" class="w-full text-xs border-gray-200 rounded">
                            <button wire:click="removeObjective({{ $index }})" class="text-red-400 hover:text-red-600">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                        </div>
                        @endforeach
                    </div>
                </div>

                {{-- PARTNERS --}}
                <div>
                    <div class="flex justify-between items-center mb-2">
                        <label class="text-xs font-bold text-gray-700 uppercase">Partners</label>
                        <button wire:click="addPartner" class="text-[10px] bg-gray-100 hover:bg-green-100 text-gray-600 px-2 py-1 rounded transition">+ Add</button>
                    </div>
                    <div class="space-y-3">
                        @foreach($partners as $index => $partner)
                        <div class="p-3 bg-gray-50 rounded-lg border border-gray-100 relative group">
                            <button wire:click="removePartner({{ $index }})" class="absolute top-2 right-2 text-gray-300 hover:text-red-500">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                            <div class="grid grid-cols-12 gap-2">
                                <div class="col-span-2 flex items-center justify-center">
                                    <button title="Toggle Type" wire:click="$set('partners.{{ $index }}.type', '{{ $partner['type'] === 'database' ? 'custom' : 'database' }}')"
                                            class="w-8 h-8 rounded-full flex items-center justify-center border transition {{ $partner['type'] === 'database' ? 'bg-blue-100 border-blue-200 text-blue-600' : 'bg-gray-200 border-gray-300 text-gray-500' }}">
                                        @if($partner['type'] === 'database')
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4"></path></svg>
                                        @else
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                        @endif
                                    </button>
                                </div>
                                <div class="col-span-10 space-y-2">
                                    @if($partner['type'] === 'database')
                                        <select wire:model.live="partners.{{ $index }}.id" class="w-full text-xs border-gray-200 rounded-lg focus:ring-blue-400">
                                            <option value="">-- Select Official Linkage --</option>
                                            @foreach($availableLinkages as $linkage)
                                                <option value="{{ $linkage->id }}">{{ $linkage->name }}</option>
                                            @endforeach
                                        </select>
                                    @else
                                        <input wire:model.live="partners.{{ $index }}.name" type="text" class="w-full text-xs border-gray-200 rounded-lg focus:ring-gray-400" placeholder="Custom Partner Name">
                                    @endif
                                    <div class="flex items-center gap-2">
                                        <span class="text-[9px] font-bold text-gray-400 uppercase tracking-wider">Role:</span>
                                        <input wire:model.live="partners.{{ $index }}.role" type="text" class="flex-1 py-1 px-2 text-[10px] border-gray-200 rounded-md focus:ring-blue-400 bg-white" placeholder="e.g. Sponsor">
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- SECTION 4: IMPACT STATS --}}
            <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100">
                <div class="flex justify-between items-center mb-4">
                    <label class="text-xs font-bold text-gray-700 uppercase">Impact Stats</label>
                    <button wire:click="addStat" class="text-[10px] bg-gray-100 hover:bg-green-100 text-gray-600 px-2 py-1 rounded transition">+ Add Row</button>
                </div>
                <div class="space-y-2">
                    @foreach($impact_stats as $index => $stat)
                    <div class="grid grid-cols-2 gap-2">
                        <input wire:model.live="impact_stats.{{ $index }}.value" type="text" class="text-xs border-gray-200 rounded font-bold" placeholder="Value (e.g. 500+)">
                        <div class="flex gap-2">
                            <input wire:model.live="impact_stats.{{ $index }}.label" type="text" class="w-full text-xs border-gray-200 rounded" placeholder="Label">
                            <button wire:click="removeStat({{ $index }})" class="text-red-400 hover:text-red-600">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- SECTION 5: SDGs --}}
            <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100">
                <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest border-b border-gray-100 pb-2 mb-4">Target SDGs</h3>
                <div class="grid grid-cols-4 gap-2">
                    @foreach($sdgs as $sdg)
                    <button wire:click="toggleSdg({{ $sdg->id }})" 
                            style="background-color: {{ in_array($sdg->id, $selectedSdgs) ? $sdg->color_hex : '#f3f4f6' }};
                                   color: {{ in_array($sdg->id, $selectedSdgs) ? 'white' : '#9ca3af' }};"
                            class="aspect-square flex flex-col items-center justify-center p-1 rounded-lg transition-all transform hover:scale-105 border border-transparent shadow-sm hover:shadow-md">
                        <span class="text-sm font-black leading-none">{{ $sdg->number }}</span>
                        <span class="text-[7px] font-bold uppercase leading-tight text-center mt-1 line-clamp-2">{{ $sdg->name }}</span>
                    </button>
                    @endforeach
                </div>
            </div>

            {{-- SECTION 6: PROJECT GALLERY --}}
            <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100" x-data="{ isUploadingGallery: false, progress: 0 }">
                <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest border-b border-gray-100 pb-2 mb-4">Project Gallery</h3>

                {{-- A. EXISTING GALLERY (Database Items) --}}
                @if(count($galleryInputs) > 0)
                    <h4 class="text-[10px] font-bold uppercase text-gray-400 mb-2">Existing Photos</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                        @foreach($galleryInputs as $index => $item)
                            <div class="flex gap-3 p-2 border border-gray-200 rounded-lg bg-gray-50 relative group">
                                <button wire:click="deleteGalleryItem({{ $item['id'] }})" type="button" class="absolute -top-2 -right-2 bg-red-100 text-red-500 rounded-full p-1 shadow hover:bg-red-600 hover:text-white transition z-10">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                </button>
                                {{-- Image --}}
                                <div class="w-20 h-20 flex-shrink-0 bg-gray-200 rounded-md overflow-hidden">
                                    <img src="{{ asset('storage/'.$item['image_path']) }}" class="w-full h-full object-cover">
                                </div>
                                {{-- Inputs --}}
                                <div class="flex-1 space-y-2">
                                    <input wire:model="galleryInputs.{{ $index }}.title" type="text" class="w-full text-xs font-bold border-gray-200 rounded px-2 py-1 placeholder-gray-400 focus:ring-yellow-400" placeholder="Photo Title">
                                    <textarea wire:model="galleryInputs.{{ $index }}.description" rows="2" class="w-full text-xs border-gray-200 rounded px-2 py-1 resize-none placeholder-gray-400 focus:ring-yellow-400" placeholder="Description..."></textarea>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif

                {{-- B. PENDING UPLOADS (The Staging Area) --}}
                @if(count($pendingGalleryItems) > 0)
                    <h4 class="text-[10px] font-bold uppercase text-green-600 mb-2">New Uploads (Add Details)</h4>
                    <div class="space-y-3 mb-6">
                        @foreach($pendingGalleryItems as $index => $item)
                            <div class="flex gap-3 p-3 border-2 border-green-100 rounded-xl bg-green-50/30 relative" wire:key="pending-{{ $item['temp_id'] }}">
                                
                                {{-- Remove Button --}}
                                <button wire:click="removePendingItem({{ $index }})" class="absolute top-2 right-2 text-gray-400 hover:text-red-500 transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                </button>

                                {{-- Image Preview --}}
                                <div class="w-24 h-24 flex-shrink-0 bg-gray-200 rounded-lg overflow-hidden shadow-sm">
                                    <img src="{{ $item['temp_file']->temporaryUrl() }}" class="w-full h-full object-cover">
                                </div>

                                {{-- Inputs --}}
                                <div class="flex-1 pr-6 space-y-2">
                                    <div>
                                        <label class="block text-[9px] font-bold uppercase text-gray-500 mb-0.5">Title</label>
                                        <input wire:model="pendingGalleryItems.{{ $index }}.title" type="text" class="w-full text-xs font-bold border-gray-200 rounded-lg focus:border-green-400 focus:ring-green-400 bg-white" placeholder="e.g. Community Gathering">
                                    </div>
                                    <div>
                                        <label class="block text-[9px] font-bold uppercase text-gray-500 mb-0.5">Description</label>
                                        <input wire:model="pendingGalleryItems.{{ $index }}.description" type="text" class="w-full text-xs border-gray-200 rounded-lg focus:border-green-400 focus:ring-green-400 bg-white" placeholder="e.g. Volunteers distributing goods...">
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif

                {{-- C. DROP ZONE --}}
                <div 
                    x-on:livewire-upload-start="isUploadingGallery = true"
                    x-on:livewire-upload-finish="isUploadingGallery = false"
                    x-on:livewire-upload-error="isUploadingGallery = false"
                    x-on:livewire-upload-progress="progress = $event.detail.progress"
                    class="relative mt-4"
                >
                    <label class="relative flex flex-col items-center justify-center w-full h-24 border-2 border-dashed border-gray-300 rounded-xl cursor-pointer hover:bg-gray-50 hover:border-yellow-400 transition-all group">
                        
                        {{-- Uploading State --}}
                        <div x-show="isUploadingGallery" class="absolute inset-0 z-20 flex flex-col items-center justify-center bg-white/90 rounded-xl">
                            <svg class="animate-spin h-5 w-5 text-yellow-500 mb-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span class="text-[10px] font-bold uppercase text-gray-500">Processing...</span>
                        </div>

                        {{-- Empty State --}}
                        <div class="flex flex-col items-center justify-center pt-5 pb-6 text-gray-400 group-hover:text-yellow-600">
                            <svg class="w-6 h-6 mb-1 transition-transform group-hover:-translate-y-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v16m8-8H4"></path></svg>
                            <p class="text-[10px] font-bold">Add More Photos</p>
                        </div>

                        <input type="file" wire:model="newGalleryImages" multiple class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                    </label>
                    
                    @error('newGalleryImages.*') <span class="text-red-500 text-[10px]">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>

        {{-- RIGHT PANEL: LIVE PREVIEW --}}
        {{-- ... (The Preview logic is the same as create, but ensure variable names match the updated array logic I provided earlier) ... --}}
        
        {{-- I will re-paste the corrected PHP logic for preview here just to be safe --}}
        @php
            // Proponents Preview
            $previewProponents = [];
            foreach($proponents as $prop) {
                if ($prop['type'] === 'user' && $prop['id']) {
                    $u = $users->find($prop['id']);
                    if($u) $previewProponents[] = $u->name;
                } elseif ($prop['name']) {
                    $previewProponents[] = $prop['name'];
                }
            }
            $proponentLabel = empty($previewProponents) ? 'Select Proponent' : implode(', ', $previewProponents);

            // Partners Preview
            $previewPartners = [];
            foreach($partners as $p) {
                if ($p['type'] === 'database' && !empty($p['id'])) {
                    $link = $availableLinkages->find($p['id']);
                    if($link) $previewPartners[] = ['name' => $link->name, 'role' => $p['role'] ?? 'Partner', 'is_official' => true];
                } elseif ($p['type'] === 'custom' && !empty($p['name'])) {
                    $previewPartners[] = ['name' => $p['name'], 'role' => $p['role'] ?? 'Partner', 'is_official' => false];
                }
            }
        @endphp

        <div class="w-full md:w-7/12 h-full overflow-y-auto bg-stone-100 relative shadow-inner"
             :class="mobilePreview ? 'block' : 'hidden md:block'">
             
             {{-- ... Preview Content (Same as Create, using $title, $description, $impact_stats, $previewPartners, $proponentLabel etc) ... --}}
             {{-- Just ensure the COVER IMAGE in preview handles the old vs new logic --}}
             <div class="min-h-full bg-stone-50 pb-20 origin-top scale-90 md:scale-100 transition-transform pointer-events-none select-none">
                <header class="relative pt-20 pb-16 px-6 max-w-5xl mx-auto">
                    <div class="grid lg:grid-cols-2 gap-12 items-center">
                        <div class="order-2 lg:order-1">
                            {{-- ... text content ... --}}
                            <h1 class="font-heading text-4xl md:text-5xl font-black text-gray-900 leading-[1.1] mb-6">
                                {{ $title ?: 'Project Title' }}
                            </h1>
                            {{-- ... --}}
                        </div>
                        <div class="order-1 lg:order-2 relative">
                            <div class="relative overflow-hidden rounded-[2.5rem] shadow-2xl aspect-[4/3] border-4 border-white bg-gray-200">
                                @if($coverImg)
                                    <img src="{{ $coverImg->temporaryUrl() }}" class="w-full h-full object-cover">
                                @elseif($oldCoverImg)
                                    <img src="{{ asset('storage/'.$oldCoverImg) }}" class="w-full h-full object-cover">
                                @else
                                    <div class="flex items-center justify-center h-full text-gray-400">No Image</div>
                                @endif
                                <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent"></div>
                                {{-- ... --}}
                            </div>
                        </div>
                    </div>
                </header>
                {{-- ... --}}
                {{-- Use $previewPartners loop for the Partners Card here --}}
             </div>
        </div>

    </div>
</div>