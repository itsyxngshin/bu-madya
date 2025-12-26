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
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Project Title</label>
                        <input wire:model.live="title" type="text" class="w-full text-sm border-gray-200 rounded-lg focus:ring-yellow-400 focus:border-yellow-400">
                        @error('title') <span class="text-red-500 text-[10px]">{{ $message }}</span> @enderror
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
                            <select wire:model.live="cat" class="w-full text-xs border-gray-200 rounded-lg focus:ring-yellow-400 focus:border-yellow-400">
                                <option>Community Outreach</option>
                                <option>Capacity Building</option>
                                <option>Environmental</option>
                                <option>Policy Advocacy</option>
                                <option>Partnership</option>
                            </select>
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
                    
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Lead Proponent</label>
                        <input wire:model.live="proponent" type="text" class="w-full text-xs border-gray-200 rounded-lg" placeholder="e.g. Committee on Education">
                    </div>
                    
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Beneficiaries (Summary)</label>
                        <input wire:model.live="beneficiaries" type="text" class="w-full text-xs border-gray-200 rounded-lg" placeholder="e.g. 150 Families">
                    </div>

                    {{-- ========================================== --}}
                    {{-- DRAG AND DROP COVER IMAGE UPLOAD         --}}
                    {{-- ========================================== --}}
                    <div x-data="{ isDropping: false, isFocused: false }">
                        <label class="block text-xs font-bold text-gray-700 mb-1">Cover Image</label>
                        
                        {{-- The Drop Zone Label --}}
                        <label
                            {{-- Alpine Event Handlers for Drag State --}}
                            @dragover.prevent="isDropping = true"
                            @dragleave.prevent="isDropping = false"
                            @drop.prevent="isDropping = false"
                            
                            {{-- Dynamic Classes for visual feedback on drag/focus --}}
                            :class="{'border-yellow-400 bg-yellow-50 ring-2 ring-yellow-200': isDropping || isFocused, 'border-gray-300 bg-white': !isDropping && !isFocused}"
                            
                            class="relative flex flex-col items-center justify-center w-full h-36 border-2 border-dashed rounded-xl cursor-pointer hover:bg-gray-50 transition-all group overflow-hidden"
                        >
                            {{-- State 1: Uploading Spinner (Shows when Livewire is busy with coverImg) --}}
                            <div wire:loading wire:target="coverImg" class="absolute inset-0 z-20 flex flex-col items-center justify-center bg-white/90 rounded-xl backdrop-blur-sm">
                                <svg class="animate-spin h-8 w-8 text-yellow-500 mb-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                <span class="text-xs font-bold text-yellow-700 uppercase tracking-wider">Uploading...</span>
                            </div>
         
                            {{-- State 2: Image Selected (Show Thumbnail Preview) --}}
                            @if($coverImg && !is_string($coverImg))
                                 {{-- The thumbnail image --}}
                                 <img src="{{ $coverImg->temporaryUrl() }}" class="absolute inset-0 w-full h-full object-cover z-0 opacity-60 group-hover:opacity-40 transition">
                                 
                                 {{-- Overlay text --}}
                                 <div class="relative z-10 flex flex-col items-center justify-center text-gray-800 group-hover:text-yellow-700">
                                     <svg class="w-8 h-8 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                                     <p class="text-[10px] font-bold uppercase bg-white/80 px-2 py-1 rounded-full">Change Image</p>
                                 </div>
                            @else
                            {{-- State 3: Default (No image selected yet) --}}
                                 <div class="flex flex-col items-center justify-center pt-5 pb-6 text-gray-400 group-hover:text-yellow-600 transition">
                                     <svg class="w-10 h-10 mb-3 text-gray-300 group-hover:text-yellow-400 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                                     <p class="mb-1 text-sm font-bold text-gray-700"><span class="font-black underline text-yellow-600">Click to upload</span> or drag and drop</p>
                                     <p class="text-xs text-gray-400">PNG, JPG up to 5MB</p>
                                 </div>
                            @endif
         
                            {{-- THE HIDDEN INPUT - The magic sauce. It covers the whole area but is invisible. --}}
                            <input
                                type="file"
                                wire:model="coverImg"
                                accept="image/png, image/jpeg, image/jpg"
                                class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10"
                                @focus="isFocused = true"
                                @blur="isFocused = false"
                            />
                        </label>
                    </div>
                    @error('coverImg') <span class="text-red-500 text-[10px] mt-1 block">{{ $message }}</span> @enderror
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
                        <label class="text-xs font-bold text-gray-700 uppercase">Partners</label>
                        <button wire:click="addPartner" class="text-[10px] bg-gray-100 hover:bg-green-100 text-gray-600 px-2 py-1 rounded transition">+ Add</button>
                    </div>
                    <div class="space-y-2">
                        @foreach($partners as $index => $partner)
                        <div class="flex gap-2">
                            <input wire:model.live="partners.{{ $index }}" type="text" class="w-full text-xs border-gray-200 rounded">
                            <button wire:click="removePartner({{ $index }})" class="text-red-400 hover:text-red-600">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
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
                                 <span class="text-xs font-bold text-gray-500 uppercase tracking-widest">{{ $cat }}</span>
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
                                    <span class="block text-[10px] font-bold text-gray-400 uppercase">Lead Proponent</span>
                                    <span class="text-sm font-bold text-gray-800">{{ $proponent ?: 'Committee Name' }}</span>
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
                             <h3 class="font-bold text-gray-900 uppercase tracking-widest text-xs border-b border-gray-100 pb-3 mb-4">Partners</h3>
                             <div class="flex flex-wrap gap-2">
                                @foreach($partners as $partner)
                                    @if($partner)
                                    <span class="px-3 py-1 bg-gray-50 text-gray-600 text-xs font-bold rounded-lg border border-gray-200">{{ $partner }}</span>
                                    @endif
                                @endforeach
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