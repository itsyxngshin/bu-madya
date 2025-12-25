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
            <button class="px-4 py-2 bg-white border border-gray-300 text-gray-600 text-xs font-bold uppercase rounded-lg hover:bg-gray-50 transition shadow-sm">Save Draft</button>
            <button class="px-5 py-2 bg-gradient-to-r from-red-600 to-yellow-500 text-white text-xs font-bold uppercase rounded-lg hover:shadow-lg hover:scale-105 transition shadow-md">Publish</button>
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
                            <input wire:model.live="date" type="text" class="w-full text-xs border-gray-200 rounded-lg">
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

                    <div>
                         <label class="block text-xs font-bold text-gray-700 mb-1">Cover Image URL</label>
                         <input wire:model.live="coverImg" type="text" class="w-full text-xs text-gray-500 border-gray-200 rounded-lg focus:ring-yellow-400" placeholder="https://...">
                    </div>
                </div>
            </div>

            {{-- SECTION 2: DESCRIPTION --}}
            <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100">
                <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest border-b border-gray-100 pb-2 mb-4">The Story</h3>
                <textarea wire:model.live="description" rows="5" class="w-full text-sm border-gray-200 rounded-lg focus:ring-yellow-400" placeholder="Describe the project..."></textarea>
            </div>

            {{-- SECTION 3: DYNAMIC LISTS (Objectives & Partners) --}}
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
                    @foreach($allSdgs as $id => $sdg)
                    <button wire:click="toggleSdg({{ $id }})" 
                            class="aspect-square flex items-center justify-center rounded-lg text-xs font-black transition-all transform hover:scale-105
                            {{ in_array($id, $selectedSdgs) ? $sdg['color'] . ' text-white ring-2 ring-offset-1 ring-gray-300' : 'bg-gray-100 text-gray-400 hover:bg-gray-200' }}">
                        {{ $id }}
                    </button>
                    @endforeach
                </div>
                <p class="text-[10px] text-gray-400 mt-2 text-center">Click number to toggle selection</p>
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

            {{-- PREVIEW CONTENT (Copied from Project Feature) --}}
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
                            
                            <p class="text-sm md:text-base text-gray-600 leading-relaxed font-serif mb-8 border-l-4 border-yellow-400 pl-6">
                                "{{ $description ?: 'Project description will appear here...' }}"
                            </p>

                            <div class="grid grid-cols-3 gap-4 border-t border-gray-200 pt-8">
                                @foreach($impact_stats as $stat)
                                <div>
                                    <span class="block text-2xl font-black text-gray-900">{{ $stat['value'] ?: '0' }}</span>
                                    <span class="text-[10px] uppercase tracking-wider text-gray-400 font-bold">{{ $stat['label'] ?: 'Stat' }}</span>
                                </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="order-1 lg:order-2 relative">
                            <div class="relative overflow-hidden rounded-[2.5rem] shadow-2xl aspect-[4/3] border-4 border-white bg-gray-200">
                                @if($coverImg)
                                    <img src="{{ $coverImg }}" class="w-full h-full object-cover">
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
                    
                    {{-- SIDEBAR --}}
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
                                    <span class="text-sm font-bold text-gray-800">{{ $date }}</span>
                                </li>
                                <li>
                                    <span class="block text-[10px] font-bold text-gray-400 uppercase">Status</span>
                                    <span class="text-sm font-bold text-gray-800">{{ $status }}</span>
                                </li>
                            </ul>
                        </div>

                        {{-- PARTNERS --}}
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

                        {{-- SDGs --}}
                        <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100">
                            <h3 class="font-bold text-gray-900 uppercase tracking-widest text-xs border-b border-gray-100 pb-3 mb-4">Target SDGs</h3>
                            <div class="flex flex-col gap-2">
                                @foreach($selectedSdgs as $id)
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 {{ $allSdgs[$id]['color'] }} rounded text-white font-black text-xs flex items-center justify-center">
                                        {{ $id }}
                                    </div>
                                    <span class="text-[10px] font-bold text-gray-700 uppercase">{{ $allSdgs[$id]['label'] }}</span>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </aside>

                    {{-- OBJECTIVES --}}
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