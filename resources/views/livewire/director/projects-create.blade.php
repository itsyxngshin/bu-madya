<div class="min-h-screen bg-gray-100 font-sans text-gray-900" x-data="{ mobilePreview: false }">

    {{-- 1. EDITOR NAVBAR --}}
    <nav class="sticky top-0 z-50 bg-white/90 backdrop-blur-md border-b border-gray-200 h-16 px-6 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <a href="{{ route('projects.index') }}" class="text-xs font-bold uppercase text-gray-400 hover:text-red-600 transition">
                &larr; Exit
            </a>
            <div class="h-4 w-px bg-gray-300"></div>
            <span class="font-heading font-black text-gray-800 tracking-tight">
                Project <span class="text-red-600">Builder</span>
            </span>
        </div>
        
        <button @click="mobilePreview = !mobilePreview" class="md:hidden text-xs font-bold uppercase bg-gray-200 px-3 py-1 rounded">
            <span x-text="mobilePreview ? 'Edit' : 'Preview'"></span>
        </button>

        <div class="flex items-center gap-3">
            <button class="px-4 py-2 bg-white border border-gray-300 text-gray-600 text-xs font-bold uppercase rounded-lg hover:bg-gray-50 transition shadow-sm">
                Save Draft
            </button>
            <button wire:click="save" 
                    wire:loading.attr="disabled"
                    class="px-5 py-2 bg-gradient-to-r from-red-600 to-yellow-500 text-white text-xs font-bold uppercase rounded-lg hover:shadow-lg hover:scale-105 transition shadow-md flex items-center gap-2">
                <span wire:loading.remove wire:target="save">Publish</span>
                <span wire:loading wire:target="save">Saving...</span>
                <svg wire:loading wire:target="save" class="animate-spin h-3 w-3 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            </button>
        </div>
    </nav>

    <div class="flex h-[calc(100vh-64px)] overflow-hidden">
        
        {{-- ======================== --}}
        {{-- LEFT PANEL: FORM EDITOR  --}}
        {{-- ======================== --}}
        <div class="w-full md:w-5/12 h-full overflow-y-auto p-6 bg-gray-50 border-r border-gray-200 space-y-6"
             :class="mobilePreview ? 'hidden' : 'block'">

            {{-- SECTION 1: BASIC INFO --}}
            <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100">
                <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest border-b border-gray-100 pb-2 mb-4">Core Details</h3>
                
                <div class="space-y-4">
                    <div class="grid grid-cols-3 gap-4">
                        <div class="col-span-1">
                            <label class="block text-xs font-bold text-gray-700 mb-1">Academic Year</label>
                            <select wire:model.live="academic_year_id" class="w-full text-xs border-gray-200 rounded-lg focus:ring-yellow-400 focus:border-yellow-400 bg-yellow-50/50">
                                <option value="">-- Select --</option>
                                @foreach($academic_years as $year)
                                    <option value="{{ $year->id }}">
                                        {{ $year->name }} {{ $year->is_active ? '(Active)' : '' }}
                                    </option>
                                @endforeach
                            </select>
                            @error('academic_year_id') <span class="text-red-500 text-[10px]">{{ $message }}</span> @enderror
                        </div>
                        
                        {{-- Project Title takes up the rest of the space --}}
                        <div class="col-span-2">
                            <label class="block text-xs font-bold text-gray-700 mb-1">Project Title</label>
                            <input wire:model.live="title" type="text" class="w-full text-sm border-gray-200 rounded-lg focus:ring-yellow-400 focus:border-yellow-400">
                            @error('title') <span class="text-red-500 text-[10px]">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">
                            URL Slug <span class="font-normal text-gray-400">(Unique ID)</span>
                        </label>
                        <div class="flex rounded-lg shadow-sm">
                            <span class="inline-flex items-center px-3 rounded-l-lg border border-r-0 border-gray-200 bg-gray-50 text-gray-500 text-xs">
                                config('app.url')/projects/
                            </span>
                            <input wire:model.live="slug" 
                                type="text" 
                                class="flex-1 min-w-0 block w-full px-3 py-2 rounded-none rounded-r-lg text-xs border-gray-200 text-gray-600 focus:ring-yellow-400 focus:border-yellow-400"
                                placeholder="project-name-here">
                        </div>
                        @error('slug') <span class="text-red-500 text-[10px] mt-1">{{ $message }}</span> @enderror
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">Category</label>
                            <select wire:model.live="project_category_id" class="w-full text-xs border-gray-200 rounded-lg focus:ring-yellow-400 focus:border-yellow-400">
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('project_category_id') <span class="text-red-500 text-[10px]">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">Status</label>
                            <select wire:model.live="status" class="w-full text-xs border-gray-200 rounded-lg focus:ring-yellow-400 focus:border-yellow-400">
                                <option>Upcoming</option>
                                <option>Ongoing</option>
                                <option>Completed</option>
                            </select>
                        </div>
                    </div>

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
                    
                    <div class="space-y-3">
                        <div class="flex justify-between items-center">
                            <label class="text-xs font-bold text-gray-700">Lead Proponents</label>
                            <button wire:click="addProponent" class="text-[10px] bg-gray-100 hover:bg-green-100 text-gray-600 px-2 py-1 rounded transition">+ Add</button>
                        </div>

                        @foreach($proponents as $index => $prop)
                        <div class="p-3 bg-gray-50 rounded-lg border border-gray-100 relative group">
                            
                            {{-- Remove Button (Top Right) --}}
                            @if(count($proponents) > 1)
                            <button wire:click="removeProponent({{ $index }})" class="absolute top-2 right-2 text-gray-300 hover:text-red-500">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                            @endif

                            {{-- Toggle Buttons --}}
                            <div class="flex bg-white p-1 rounded-md w-max mb-2 border border-gray-200">
                                <button wire:click="$set('proponents.{{ $index }}.type', 'user')" 
                                        class="px-2 py-0.5 text-[9px] font-bold rounded transition {{ $prop['type'] === 'user' ? 'bg-gray-800 text-white shadow' : 'text-gray-400 hover:text-gray-600' }}">
                                    User
                                </button>
                                <button wire:click="$set('proponents.{{ $index }}.type', 'custom')" 
                                        class="px-2 py-0.5 text-[9px] font-bold rounded transition {{ $prop['type'] === 'custom' ? 'bg-gray-800 text-white shadow' : 'text-gray-400 hover:text-gray-600' }}">
                                    Custom
                                </button>
                            </div>

                            {{-- Inputs --}}
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
                    
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Beneficiaries (Summary)</label>
                        <input wire:model.live="beneficiaries" type="text" class="w-full text-xs border-gray-200 rounded-lg" placeholder="e.g. 150 Families">
                    </div>

                    {{-- ========================================== --}}
                    {{-- DRAG AND DROP COVER IMAGE UPLOAD         --}}
                    {{-- ========================================== --}}
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Cover Image</label>
                        
                        <div x-data="{ isDropping: false, isFocused: false }"
                            class="relative group">
                            
                            <label 
                                {{-- 1. Handle Drag Events on the Container --}}
                                @dragover.prevent="isDropping = true"
                                @dragleave.prevent="isDropping = false"
                                @drop.prevent="isDropping = false"
                                
                                {{-- 2. Dynamic Classes --}}
                                :class="{'border-yellow-400 bg-yellow-50 ring-2 ring-yellow-200': isDropping || isFocused, 'border-gray-300 bg-white hover:bg-gray-50': !isDropping && !isFocused}"
                                
                                class="flex flex-col items-center justify-center w-full h-36 border-2 border-dashed rounded-xl transition-all duration-200 cursor-pointer overflow-hidden relative">
                                
                                {{-- A. Loading State --}}
                                <div wire:loading wire:target="coverImg" class="absolute inset-0 z-50 flex flex-col items-center justify-center bg-white/90 backdrop-blur-sm">
                                    <svg class="animate-spin h-8 w-8 text-yellow-500 mb-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    <span class="text-xs font-bold text-yellow-700 uppercase tracking-wider">Uploading...</span>
                                </div>

                                {{-- B. Image Preview State --}}
                                @if($coverImg && !is_string($coverImg))
                                    <img src="{{ $coverImg->temporaryUrl() }}" class="absolute inset-0 w-full h-full object-cover z-10 opacity-100 group-hover:opacity-40 transition-opacity">
                                    <div class="relative z-20 flex flex-col items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                        <div class="bg-white/90 text-gray-800 p-2 rounded-full shadow-sm">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                                        </div>
                                        <span class="mt-2 text-[10px] font-bold uppercase bg-white/90 px-2 py-1 rounded-md text-gray-700 shadow-sm">Change Image</span>
                                    </div>
                                @else
                                {{-- C. Default State --}}
                                    <div class="flex flex-col items-center justify-center pt-5 pb-6 text-gray-400">
                                        <svg class="w-10 h-10 mb-3 text-gray-300 group-hover:text-yellow-400 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                                        <p class="mb-1 text-sm font-bold text-gray-700"><span class="text-yellow-600 hover:underline">Click to upload</span> or drag and drop</p>
                                        <p class="text-xs text-gray-400">PNG, JPG up to 5MB</p>
                                    </div>
                                @endif

                                {{-- THE INPUT: Must cover everything and be on top (z-30) but invisible --}}
                                <input 
                                    type="file" 
                                    wire:model="coverImg" 
                                    accept="image/png, image/jpeg, image/jpg"
                                    class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-30"
                                    @focus="isFocused = true"
                                    @blur="isFocused = false"
                                >
                            </label>
                        </div>
                        @error('coverImg') <span class="text-red-500 text-[10px] mt-1 block">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            {{-- SECTION 2: DESCRIPTION --}}
            <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100">
                <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest border-b border-gray-100 pb-2 mb-4">The Story</h3>
                <textarea wire:model.live="description" rows="5" class="w-full text-sm border-gray-200 rounded-lg focus:ring-yellow-400" placeholder="Describe the project..."></textarea>
            </div>

            {{-- SECTION 3: DYNAMIC LISTS --}}
            <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 space-y-6">
                {{-- Objectives --}}
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

                {{-- Partners --}}
                <div>
                    <div class="flex justify-between items-center mb-2">
                        <label class="text-xs font-bold text-gray-700 uppercase">Partners / Linkages</label>
                        <button wire:click="addPartner" class="text-[10px] bg-gray-100 hover:bg-green-100 text-gray-600 px-2 py-1 rounded transition">+ Add</button>
                    </div>

                    <div class="space-y-3">
                        @foreach($partners as $index => $partner)
                        <div class="p-3 bg-gray-50 rounded-lg border border-gray-100 relative group">
                            
                            {{-- Remove Button --}}
                            <button wire:click="removePartner({{ $index }})" class="absolute top-2 right-2 text-gray-300 hover:text-red-500">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>

                            <div class="grid grid-cols-12 gap-2">
                                
                                {{-- 1. Toggle Type (Icon Based) --}}
                                <div class="col-span-2 flex items-center justify-center">
                                    <button title="Toggle Type" 
                                            wire:click="$set('partners.{{ $index }}.type', '{{ $partner['type'] === 'database' ? 'custom' : 'database' }}')"
                                            class="w-8 h-8 rounded-full flex items-center justify-center border transition {{ $partner['type'] === 'database' ? 'bg-blue-100 border-blue-200 text-blue-600' : 'bg-gray-200 border-gray-300 text-gray-500' }}">
                                        @if($partner['type'] === 'database')
                                            {{-- Database Icon --}}
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4"></path></svg>
                                        @else
                                            {{-- Text Icon --}}
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                        @endif
                                    </button>
                                </div>

                                {{-- 2. Main Input (Select or Text) --}}
                                <div class="col-span-10 space-y-2">
                                    @if($partner['type'] === 'database')
                                        <select wire:model.live="partners.{{ $index }}.id" class="w-full text-xs border-gray-200 rounded-lg focus:ring-blue-400">
                                            <option value="">-- Select Official Linkage --</option>
                                            @foreach($availableLinkages as $linkage)
                                                <option value="{{ $linkage->id }}">{{ $linkage->name }} {{ $linkage->acronym ? "({$linkage->acronym})" : '' }}</option>
                                            @endforeach
                                        </select>
                                    @else
                                        <input wire:model.live="partners.{{ $index }}.name" type="text" class="w-full text-xs border-gray-200 rounded-lg focus:ring-gray-400" placeholder="Custom Partner Name">
                                    @endif

                                    {{-- 3. Role Input (Small underneath) --}}
                                    <div class="flex items-center gap-2">
                                        <span class="text-[9px] font-bold text-gray-400 uppercase tracking-wider">Role:</span>
                                        <input wire:model.live="partners.{{ $index }}.role" type="text" class="flex-1 py-1 px-2 text-[10px] border-gray-200 rounded-md focus:ring-blue-400 bg-white" placeholder="e.g. Sponsor, Co-Implementer">
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

            {{-- SECTION 5: SDG SELECTOR --}}
            <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100">
                <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest border-b border-gray-100 pb-2 mb-4">Target SDGs</h3>
                
                <div class="grid grid-cols-4 gap-2">
                    @foreach($sdgs as $sdg)
                    <button wire:click="toggleSdg({{ $sdg->id }})" 
                            style="background-color: {{ in_array($sdg->id, $selectedSdgs) ? $sdg->color_hex : '#f3f4f6' }};
                                   color: {{ in_array($sdg->id, $selectedSdgs) ? 'white' : '#9ca3af' }};"
                            class="aspect-square flex flex-col items-center justify-center p-1 rounded-lg transition-all transform hover:scale-105 border border-transparent shadow-sm hover:shadow-md">
                        <span class="text-sm font-black leading-none">{{ $sdg->number }}</span>
                        <span class="text-[7px] font-bold uppercase leading-tight text-center mt-1 line-clamp-2">
                            {{ $sdg->name }}
                        </span>
                    </button>
                    @endforeach
                </div>
                <p class="text-[10px] text-gray-400 mt-2 text-center">Click to toggle selection</p>
            </div>

        </div>

        @php
            $previewNames = [];
            foreach($proponents as $prop) {
                if ($prop['type'] === 'user' && $prop['id']) {
                    $u = $users->find($prop['id']);
                    if($u) $previewNames[] = $u->name;
                } elseif ($prop['name']) {
                    $previewNames[] = $prop['name'];
                }
            }
            // Join names with commas (e.g., "John Doe, Jane Smith")
            $proponentLabel = empty($previewNames) ? 'Select Proponent' : implode(', ', $previewNames);
        @endphp
        {{-- ======================== --}}
        {{-- RIGHT PANEL: LIVE PREVIEW--}}
        {{-- ======================== --}}
        <div class="w-full md:w-7/12 h-full overflow-y-auto bg-stone-100 relative shadow-inner"
             :class="mobilePreview ? 'block' : 'hidden md:block'">
            
             <div class="absolute top-4 right-4 z-50 bg-black/80 text-white text-[10px] font-bold uppercase px-3 py-1 rounded-full backdrop-blur pointer-events-none">
                Live Preview
            </div>

            {{-- PREVIEW CONTENT --}}
            <div class="min-h-full bg-stone-50 pb-20 origin-top scale-90 md:scale-100 transition-transform pointer-events-none select-none">
                
                {{-- HERO --}}
                <header class="relative pt-20 pb-16 px-6 max-w-5xl mx-auto">
                    <div class="grid lg:grid-cols-2 gap-12 items-center">
                        <div class="order-2 lg:order-1">
                            <div class="mb-6 flex items-center gap-3">
                                <span class="w-10 h-1 bg-gradient-to-r from-red-600 to-yellow-500 rounded-full"></span>
                                <span class="text-xs font-bold text-gray-500 uppercase tracking-widest">
                                    {{-- [UPDATED] Look up the name from the collection using the ID --}}
                                    {{ $categories->find($project_category_id)?->name ?? 'Uncategorized' }}
                                </span>
                            </div>
                            
                            <h1 class="font-heading text-4xl md:text-5xl font-black text-gray-900 leading-[1.1] mb-6">
                                {{ $title ?: 'Project Title' }}
                            </h1>
                            
                            <p class="text-sm md:text-base text-gray-600 leading-relaxed font-serif mb-8 border-l-4 border-yellow-400 pl-6 whitespace-pre-line">
                                "{{ $description ?: 'Project description will appear here...' }}"
                            </p>
                            

                            <div class="grid grid-cols-3 gap-4 border-t border-gray-200 pt-8">
                                @foreach($impact_stats as $stat)
                                    @if(!empty($stat['value']))
                                    <div>
                                        <span class="block text-2xl font-black text-gray-900">{{ $stat['value'] }}</span>
                                        <span class="text-[10px] uppercase tracking-wider text-gray-400 font-bold">{{ $stat['label'] ?: 'Stat' }}</span>
                                    </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>

                        <div class="order-1 lg:order-2 relative">
                            <div class="relative overflow-hidden rounded-[2.5rem] shadow-2xl aspect-[4/3] border-4 border-white bg-gray-200">
                                @if($coverImg)
                                    <img src="{{ is_string($coverImg) ? $coverImg : $coverImg->temporaryUrl() }}" class="w-full h-full object-cover">
                                @else
                                    <div class="flex items-center justify-center h-full text-gray-400 font-bold uppercase tracking-widest text-xs">No Image</div>
                                @endif
                                <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent"></div>
                                <div class="absolute bottom-6 left-6 bg-white/90 px-4 py-2 rounded-xl">
                                    <p class="text-[10px] text-gray-400 font-bold uppercase">Location</p>
                                    <p class="text-xs font-bold text-gray-900">{{ $location ?: 'Location' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </header>

                {{-- DETAILS GRID --}}
                <div class="max-w-5xl mx-auto px-6 grid lg:grid-cols-12 gap-12">
                    
                    <aside class="lg:col-span-4 space-y-8">
                        <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100">
                            <h3 class="font-bold text-gray-900 uppercase tracking-widest text-xs border-b border-gray-100 pb-3 mb-4">Project Details</h3>
                            <ul class="space-y-4">
                                <li>
                                    <span class="block text-[10px] font-bold text-gray-400 uppercase">Lead Proponents</span>
                                    <span class="text-sm font-bold text-gray-800 leading-tight block">
                                        {{ $proponentLabel }}
                                    </span>
                                </li>
                                <li>
                                    <span class="block text-[10px] font-bold text-gray-400 uppercase">Date</span>
                                    <span class="text-sm font-bold text-gray-800">{{ $date ?: 'TBA' }}</span>
                                </li>
                                <li>
                                    <span class="block text-[10px] font-bold text-gray-400 uppercase">Status</span>
                                    <span class="text-sm font-bold text-gray-800">{{ $status }}</span>
                                </li>
                            </ul>
                        </div>

                        <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100">
                            <div class="absolute top-0 right-0 bg-yellow-400 text-yellow-900 text-[9px] font-black uppercase px-2 py-1 rounded-bl-lg">
                                AY {{ $academic_years->find($academic_year_id)?->name ?? 'TBA' }}
                            </div>
                            <div class="flex flex-wrap gap-2">
                                @php
                                    $previewPartners = [];
                                    foreach($partners as $p) {
                                        // 1. Resolve Official Linkage Name
                                        if ($p['type'] === 'database' && !empty($p['id'])) {
                                            $link = $availableLinkages->find($p['id']);
                                            if($link) {
                                                $previewPartners[] = [
                                                    'name' => $link->name, 
                                                    'role' => $p['role'] ?? 'Partner', 
                                                    'is_official' => true
                                                ];
                                            }
                                        } 
                                        // 2. Resolve Custom Name
                                        elseif ($p['type'] === 'custom' && !empty($p['name'])) {
                                            $previewPartners[] = [
                                                'name' => $p['name'], 
                                                'role' => $p['role'] ?? 'Partner', 
                                                'is_official' => false
                                            ];
                                        }
                                    }
                                @endphp

                                {{-- DISPLAY THE PARTNERS CARD --}}
                                @if(count($previewPartners) > 0)
                                <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100">
                                    <h3 class="font-bold text-gray-900 uppercase tracking-widest text-xs border-b border-gray-100 pb-3 mb-4">
                                        In Partnership With
                                    </h3>
                                    <div class="flex flex-wrap gap-2">
                                        @foreach($previewPartners as $partner)
                                        <div class="inline-flex items-center rounded-lg border overflow-hidden {{ $partner['is_official'] ? 'border-blue-100 bg-blue-50' : 'border-gray-200 bg-gray-50' }}">
                                            {{-- Icon --}}
                                            <div class="px-2 py-1 {{ $partner['is_official'] ? 'bg-blue-100 text-blue-600' : 'bg-gray-200 text-gray-500' }}">
                                                @if($partner['is_official'])
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                                                @else
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                                @endif
                                            </div>
                                            
                                            {{-- Name --}}
                                            <span class="px-2 py-1 text-xs font-bold text-gray-700">{{ $partner['name'] }}</span>

                                            {{-- Role --}}
                                            @if(!empty($partner['role']))
                                            <span class="px-2 py-1 bg-white text-[9px] font-bold uppercase text-gray-400 border-l border-gray-100 tracking-wider">
                                                {{ $partner['role'] }}
                                            </span>
                                            @endif
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                                @endif
                            </div>
                            
                        </div>
                        
                        <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100">
                            <h3 class="font-bold text-gray-900 uppercase tracking-widest text-xs border-b border-gray-100 pb-3 mb-4">Target SDGs</h3>
                            <div class="flex flex-col gap-2">
                                @foreach($sdgs as $sdg)
                                    @if(in_array($sdg->id, $selectedSdgs))
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded text-white font-black text-xs flex items-center justify-center shadow-sm"
                                             style="background-color: {{ $sdg->color_hex }}">
                                            {{ $sdg->number }}
                                        </div>
                                        <span class="text-[10px] font-bold text-gray-700 uppercase leading-tight">{{ $sdg->name }}</span>
                                    </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </aside>

                    <main class="lg:col-span-8">
                        <div class="bg-gradient-to-br from-gray-900 to-gray-800 text-white p-8 rounded-[2rem] shadow-lg relative overflow-hidden">
                             <h3 class="font-bold uppercase tracking-widest text-sm mb-6 text-yellow-400 relative z-10">Project Objectives</h3>
                             <ul class="space-y-4 relative z-10">
                                @foreach($objectives as $obj)
                                    @if($obj)
                                    <li class="flex items-start gap-3">
                                        <div class="w-5 h-5 rounded-full bg-white/10 flex items-center justify-center shrink-0">✔</div>
                                        <span class="text-gray-200 text-sm">{{ $obj }}</span>
                                    </li>
                                    @endif
                                @endforeach
                             </ul>
                        </div>
                    </main>

                </div>
            </div>

        </div>
    </div>
</div>