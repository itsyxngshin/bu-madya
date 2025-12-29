<div class="min-h-screen bg-gray-100 font-sans text-gray-900" x-data="{ mobilePreview: false }">

    {{-- 1. EDITOR NAVBAR (Sticky & Responsive) --}}
    <nav class="sticky top-0 z-50 bg-white/90 backdrop-blur-md border-b border-gray-200 h-16 px-4 md:px-6 flex items-center justify-between">
        
        {{-- Left: Branding / Exit --}}
        <div class="flex items-center gap-3">
            <a href="{{ route('projects.index') }}" class="text-gray-400 hover:text-red-600 transition p-1">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <div class="h-4 w-px bg-gray-300 hidden md:block"></div>
            <span class="font-heading font-black text-gray-800 tracking-tight hidden md:block">
                Project <span class="text-red-600">Builder</span>
            </span>
        </div>
        
        {{-- Center: Mobile Toggle (Segmented Control) --}}
        <div class="md:hidden bg-gray-100 p-1 rounded-lg flex items-center shadow-inner">
            <button @click="mobilePreview = false" 
                :class="!mobilePreview ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-500 hover:text-gray-700'"
                class="px-3 py-1.5 rounded-md text-[10px] font-bold uppercase tracking-wide transition-all">
                Editor
            </button>
            <button @click="mobilePreview = true" 
                :class="mobilePreview ? 'bg-white text-red-600 shadow-sm' : 'text-gray-500 hover:text-gray-700'"
                class="px-3 py-1.5 rounded-md text-[10px] font-bold uppercase tracking-wide transition-all">
                Preview
            </button>
        </div>

        {{-- Right: Actions --}}
        <div class="flex items-center gap-2 md:gap-3">
            <button class="hidden md:block px-4 py-2 bg-white border border-gray-300 text-gray-600 text-xs font-bold uppercase rounded-lg hover:bg-gray-50 transition shadow-sm">
                Save Draft
            </button>
            {{-- Mobile "Save" Icon Only --}}
            <button class="md:hidden p-2 bg-white border border-gray-300 text-gray-600 rounded-lg hover:bg-gray-50 shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
            </button>

            <button wire:click="save" 
                    wire:loading.attr="disabled"
                    class="px-4 md:px-5 py-2 bg-gradient-to-r from-red-600 to-yellow-500 text-white text-xs font-bold uppercase rounded-lg hover:shadow-lg hover:scale-105 transition shadow-md flex items-center gap-2">
                <span wire:loading.remove wire:target="save">Publish</span>
                <span wire:loading wire:target="save" class="hidden md:inline">Saving...</span>
                <svg wire:loading wire:target="save" class="animate-spin h-3 w-3 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            </button>
        </div>
    </nav>

    <div class="flex h-[calc(100vh-64px)] overflow-hidden relative">
        
        {{-- ======================== --}}
        {{-- LEFT PANEL: FORM EDITOR  --}}
        {{-- ======================== --}}
        <div class="w-full md:w-5/12 h-full overflow-y-auto p-4 md:p-6 bg-gray-50 border-r border-gray-200 space-y-6 pb-24"
             :class="mobilePreview ? 'hidden md:block' : 'block'">

            {{-- SECTION 1: BASIC INFO --}}
            <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100">
                <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest border-b border-gray-100 pb-2 mb-4">Core Details</h3>
                
                <div class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
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
                        
                        <div class="md:col-span-2">
                            <label class="block text-xs font-bold text-gray-700 mb-1">Project Title</label>
                            <input wire:model.live="title" type="text" class="w-full text-sm border-gray-200 rounded-lg focus:ring-yellow-400 focus:border-yellow-400">
                            @error('title') <span class="text-red-500 text-[10px]">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">
                            URL Slug
                        </label>
                        <div class="flex rounded-lg shadow-sm">
                            <span class="inline-flex items-center px-3 rounded-l-lg border border-r-0 border-gray-200 bg-gray-50 text-gray-500 text-[10px] md:text-xs truncate max-w-[100px] md:max-w-none">
                                .../projects/
                            </span>
                            <input wire:model.live="slug" type="text" class="flex-1 min-w-0 block w-full px-3 py-2 rounded-none rounded-r-lg text-xs border-gray-200 text-gray-600 focus:ring-yellow-400 focus:border-yellow-400" placeholder="project-name-here">
                        </div>
                        @error('slug') <span class="text-red-500 text-[10px] mt-1">{{ $message }}</span> @enderror
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">Category</label>
                            <select wire:model.live="project_category_id" class="w-full text-xs border-gray-200 rounded-lg focus:ring-yellow-400 focus:border-yellow-400">
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
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
                    
                    {{-- Proponents Section --}}
                    <div class="space-y-3 pt-2">
                        <div class="flex justify-between items-center">
                            <label class="text-xs font-bold text-gray-700">Lead Proponents</label>
                            <button wire:click="addProponent" class="text-[10px] bg-gray-100 hover:bg-green-100 text-gray-600 px-2 py-1 rounded transition border border-gray-200">+ Add</button>
                        </div>

                        @foreach($proponents as $index => $prop)
                        <div class="p-3 bg-gray-50 rounded-lg border border-gray-100 relative group transition hover:border-gray-300">
                            @if(count($proponents) > 1)
                            <button wire:click="removeProponent({{ $index }})" class="absolute top-2 right-2 text-gray-300 hover:text-red-500 p-1">
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
                    
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Beneficiaries (Summary)</label>
                        <input wire:model.live="beneficiaries" type="text" class="w-full text-xs border-gray-200 rounded-lg" placeholder="e.g. 150 Families">
                    </div>

                    {{-- IMAGE UPLOAD --}}
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Cover Image</label>
                        <div x-data="{ isDropping: false, isFocused: false }" class="relative group">
                            <label @dragover.prevent="isDropping = true" @dragleave.prevent="isDropping = false" @drop.prevent="isDropping = false"
                                :class="{'border-yellow-400 bg-yellow-50 ring-2 ring-yellow-200': isDropping || isFocused, 'border-gray-300 bg-white hover:bg-gray-50': !isDropping && !isFocused}"
                                class="flex flex-col items-center justify-center w-full h-36 border-2 border-dashed rounded-xl transition-all duration-200 cursor-pointer overflow-hidden relative">
                                
                                <div wire:loading wire:target="coverImg" class="absolute inset-0 z-50 flex flex-col items-center justify-center bg-white/90 backdrop-blur-sm">
                                    <svg class="animate-spin h-8 w-8 text-yellow-500 mb-2" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                </div>

                                @if($coverImg && !is_string($coverImg))
                                    <img src="{{ $coverImg->temporaryUrl() }}" class="absolute inset-0 w-full h-full object-cover z-10 opacity-100 group-hover:opacity-40 transition-opacity">
                                    <div class="relative z-20 flex flex-col items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                        <span class="text-[10px] font-bold uppercase bg-white/90 px-2 py-1 rounded-md text-gray-700 shadow-sm">Change Image</span>
                                    </div>
                                @else
                                    <div class="flex flex-col items-center justify-center pt-5 pb-6 text-gray-400">
                                        <svg class="w-10 h-10 mb-3 text-gray-300 group-hover:text-yellow-400 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                                        <p class="mb-1 text-xs font-bold text-gray-700 text-center"><span class="text-yellow-600 hover:underline">Tap to upload</span></p>
                                    </div>
                                @endif
                                <input type="file" wire:model="coverImg" accept="image/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-30" @focus="isFocused = true" @blur="isFocused = false">
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

            {{-- SECTION 3: PARTNERS & STATS --}}
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
                            <button wire:click="removeObjective({{ $index }})" class="text-red-400 hover:text-red-600 p-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                        </div>
                        @endforeach
                    </div>
                </div>

                {{-- Partners (Responsive Layout) --}}
                <div>
                    <div class="flex justify-between items-center mb-2">
                        <label class="text-xs font-bold text-gray-700 uppercase">Partners</label>
                        <button wire:click="addPartner" class="text-[10px] bg-gray-100 hover:bg-green-100 text-gray-600 px-2 py-1 rounded transition">+ Add</button>
                    </div>

                    <div class="space-y-3">
                        @foreach($partners as $index => $partner)
                        <div class="p-3 bg-gray-50 rounded-lg border border-gray-100 relative group">
                            <button wire:click="removePartner({{ $index }})" class="absolute top-2 right-2 text-gray-300 hover:text-red-500 p-1">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>

                            {{-- Stack on Mobile, Grid on Desktop --}}
                            <div class="flex flex-col md:grid md:grid-cols-12 gap-2">
                                {{-- Type Toggle --}}
                                <div class="md:col-span-2 flex items-center">
                                    <button title="Toggle Type" wire:click="$set('partners.{{ $index }}.type', '{{ $partner['type'] === 'database' ? 'custom' : 'database' }}')" class="w-8 h-8 rounded-full flex items-center justify-center border transition {{ $partner['type'] === 'database' ? 'bg-blue-100 border-blue-200 text-blue-600' : 'bg-gray-200 border-gray-300 text-gray-500' }}">
                                        @if($partner['type'] === 'database')
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4"></path></svg>
                                        @else
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                        @endif
                                    </button>
                                </div>

                                {{-- Main Inputs --}}
                                <div class="md:col-span-10 space-y-2">
                                    @if($partner['type'] === 'database')
                                        <select wire:model.live="partners.{{ $index }}.id" class="w-full text-xs border-gray-200 rounded-lg focus:ring-blue-400">
                                            <option value="">-- Select Official Linkage --</option>
                                            @foreach($availableLinkages as $linkage)
                                                <option value="{{ $linkage->id }}">{{ $linkage->name }}</option>
                                            @endforeach
                                        </select>
                                    @else
                                        <input wire:model.live="partners.{{ $index }}.name" type="text" class="w-full text-xs border-gray-200 rounded-lg focus:ring-gray-400" placeholder="Partner Name">
                                    @endif
                                    
                                    <div class="flex items-center gap-2">
                                        <span class="text-[9px] font-bold text-gray-400 uppercase tracking-wider whitespace-nowrap">Role:</span>
                                        <input wire:model.live="partners.{{ $index }}.role" type="text" class="flex-1 py-1 px-2 text-[10px] border-gray-200 rounded-md focus:ring-blue-400 bg-white" placeholder="Sponsor">
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
                    <button wire:click="addStat" class="text-[10px] bg-gray-100 hover:bg-green-100 text-gray-600 px-2 py-1 rounded transition">+ Add</button>
                </div>
                <div class="space-y-2">
                    @foreach($impact_stats as $index => $stat)
                    <div class="grid grid-cols-2 gap-2">
                        <input wire:model.live="impact_stats.{{ $index }}.value" type="text" class="text-xs border-gray-200 rounded font-bold" placeholder="500+">
                        <div class="flex gap-2">
                            <input wire:model.live="impact_stats.{{ $index }}.label" type="text" class="w-full text-xs border-gray-200 rounded" placeholder="Label">
                            <button wire:click="removeStat({{ $index }})" class="text-red-400 hover:text-red-600 p-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- SECTION 5: SDGS --}}
            <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100">
                <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest border-b border-gray-100 pb-2 mb-4">Target SDGs</h3>
                <div class="grid grid-cols-4 gap-2">
                    @foreach($sdgs as $sdg)
                    <button wire:click="toggleSdg({{ $sdg->id }})" 
                            style="background-color: {{ in_array($sdg->id, $selectedSdgs) ? $sdg->color_hex : '#f3f4f6' }};
                                   color: {{ in_array($sdg->id, $selectedSdgs) ? 'white' : '#9ca3af' }};"
                            class="aspect-square flex flex-col items-center justify-center p-1 rounded-lg transition-all transform hover:scale-105 border border-transparent shadow-sm hover:shadow-md">
                        <span class="text-sm font-black leading-none">{{ $sdg->number }}</span>
                    </button>
                    @endforeach
                </div>
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
            $proponentLabel = empty($previewNames) ? 'Select Proponent' : implode(', ', $previewNames);
        @endphp

        {{-- ======================== --}}
        {{-- RIGHT PANEL: LIVE PREVIEW--}}
        {{-- ======================== --}}
        <div class="w-full md:w-7/12 h-full overflow-y-auto bg-stone-100 relative shadow-inner"
             :class="mobilePreview ? 'block' : 'hidden md:block'">
            
             <div class="absolute top-4 right-4 z-50 bg-black/80 text-white text-[10px] font-bold uppercase px-3 py-1 rounded-full backdrop-blur pointer-events-none shadow-md">
                Live Preview
            </div>

            {{-- PREVIEW CONTENT --}}
            {{-- Changed: Removed 'scale-90' on mobile for readable text, kept md:scale-100 --}}
            <div class="min-h-full bg-stone-50 pb-20 origin-top transition-transform pointer-events-none select-none">
                
                <header class="relative pt-20 pb-16 px-4 md:px-6 max-w-5xl mx-auto">
                    <div class="grid lg:grid-cols-2 gap-8 md:gap-12 items-center">
                        <div class="order-2 lg:order-1">
                            <div class="mb-6 flex items-center gap-3">
                                <span class="w-10 h-1 bg-gradient-to-r from-red-600 to-yellow-500 rounded-full"></span>
                                <span class="text-xs font-bold text-gray-500 uppercase tracking-widest">
                                    {{ $categories->find($project_category_id)?->name ?? 'Uncategorized' }}
                                </span>
                            </div>
                            
                            <h1 class="font-heading text-3xl md:text-5xl font-black text-gray-900 leading-[1.1] mb-6">
                                {{ $title ?: 'Project Title' }}
                            </h1>
                            
                            <p class="text-sm md:text-base text-gray-600 leading-relaxed font-serif mb-8 border-l-4 border-yellow-400 pl-4 md:pl-6 whitespace-pre-line">
                                "{{ $description ?: 'Project description will appear here...' }}"
                            </p>

                            <div class="grid grid-cols-3 gap-2 md:gap-4 border-t border-gray-200 pt-8">
                                @foreach($impact_stats as $stat)
                                    @if(!empty($stat['value']))
                                    <div>
                                        <span class="block text-xl md:text-2xl font-black text-gray-900">{{ $stat['value'] }}</span>
                                        <span class="text-[9px] md:text-[10px] uppercase tracking-wider text-gray-400 font-bold">{{ $stat['label'] ?: 'Stat' }}</span>
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

                <div class="max-w-5xl mx-auto px-6 grid lg:grid-cols-12 gap-8 md:gap-12">
                    <aside class="lg:col-span-4 space-y-8">
                        <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 relative">
                            <h3 class="font-bold text-gray-900 uppercase tracking-widest text-xs border-b border-gray-100 pb-3 mb-4">Project Details</h3>
                            <ul class="space-y-4">
                                <li>
                                    <span class="block text-[10px] font-bold text-gray-400 uppercase">Lead Proponents</span>
                                    <span class="text-sm font-bold text-gray-800 leading-tight block">{{ $proponentLabel }}</span>
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