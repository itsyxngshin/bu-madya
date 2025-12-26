<div class="min-h-screen bg-gray-100 font-sans text-gray-900" x-data="{ mobilePreview: false }">

    {{-- 1. EDITOR NAV --}}
    <nav class="sticky top-0 z-50 bg-white/90 backdrop-blur-md border-b border-gray-200 h-16 px-6 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <a href="{{ route('linkages.index') }}" class="text-xs font-bold uppercase text-gray-400 hover:text-red-600 transition">
                &larr; Cancel
            </a>
            <div class="h-4 w-px bg-gray-300"></div>
            <span class="font-heading font-black text-gray-800 tracking-tight">
                Edit <span class="text-blue-600">Partner</span>
            </span>
        </div>
        
        {{-- Mobile Toggle --}}
        <button @click="mobilePreview = !mobilePreview" class="md:hidden text-xs font-bold uppercase bg-gray-200 px-3 py-1 rounded">
            <span x-text="mobilePreview ? 'Edit' : 'Preview'"></span>
        </button>

        <div class="flex items-center gap-3">
            <div wire:loading class="text-xs text-blue-500 font-bold animate-pulse">Saving...</div>
            <button wire:click="save" class="px-5 py-2 bg-gradient-to-r from-blue-600 to-cyan-500 text-white text-xs font-bold uppercase rounded-lg hover:shadow-lg hover:scale-105 transition shadow-md">
                Update Profile
            </button>
        </div>
    </nav>

    <div class="flex h-[calc(100vh-64px)] overflow-hidden">
        
        {{-- ======================== --}}
        {{-- LEFT PANEL: EDITOR       --}}
        {{-- ======================== --}}
        <div class="w-full md:w-5/12 h-full overflow-y-auto p-6 bg-gray-50 border-r border-gray-200 space-y-6"
             :class="mobilePreview ? 'hidden' : 'block'">

            {{-- 1. IDENTITY --}}
            <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100">
                <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest border-b border-gray-100 pb-2 mb-4">Partner Identity</h3>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Organization Name</label>
                        <input wire:model.live="name" type="text" class="w-full text-sm border-gray-200 rounded-lg focus:ring-blue-500 focus:border-blue-500" placeholder="e.g. Dept of Science & Tech">
                        @error('name') <span class="text-red-500 text-[10px]">{{ $message }}</span> @enderror
                    </div>

                    <div class="mt-3">
                        <label class="block text-xs font-bold text-gray-500 mb-1">
                            URL Slug <span class="text-[9px] font-normal text-gray-400">(Auto-generated)</span>
                        </label>
                        <div class="flex items-center">
                            <span class="bg-gray-100 border border-r-0 border-gray-200 rounded-l-lg px-3 py-2 text-xs text-gray-500 select-none">
                                /linkages/
                            </span>
                            <input wire:model.blur="slug" type="text" 
                                class="w-full text-xs text-blue-600 font-mono bg-gray-50 border-gray-200 rounded-r-lg focus:ring-blue-500 focus:border-blue-500 placeholder-gray-300">
                        </div>
                        @error('slug') <span class="text-red-500 text-[10px] block mt-1">{{ $message }}</span> @enderror
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">Type</label>
                            <select wire:model.live="type_id" class="w-full text-xs border-gray-200 rounded-lg">
                                <option value="">Select Type</option>
                                @foreach($this->types as $t)
                                    <option value="{{ $t->id }}">{{ $t->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">Status</label>
                            <select wire:model.live="status_id" class="w-full text-xs border-gray-200 rounded-lg">
                                <option value="">Select Status</option>
                                @foreach($this->statuses as $s)
                                    <option value="{{ $s->id }}">{{ $s->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">Partner Since</label>
                            <input wire:model.live="established_at" type="date" class="w-full text-xs border-gray-200 rounded-lg">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">Scope (Tags)</label>
                            <input wire:model.live="scope" type="text" class="w-full text-xs border-gray-200 rounded-lg" placeholder="Policy, Youth, Tech...">
                        </div>
                    </div>

                    {{-- LOGO UPLOAD (Modified for Edit) --}}
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-2">Organization Logo</label>
                        <div x-data="{ isDropping: false, isUploading: false }" class="relative w-24 h-24 group">
                            <div class="absolute inset-0 flex flex-col items-center justify-center border-2 border-dashed rounded-xl transition-colors duration-200"
                                 :class="isDropping ? 'border-blue-500 bg-blue-50' : 'border-gray-300 group-hover:border-blue-400 group-hover:bg-gray-50'">
                                
                                @if($logo)
                                    {{-- New Upload --}}
                                    <img src="{{ $logo->temporaryUrl() }}" class="w-full h-full object-contain p-1 rounded-xl">
                                @elseif($existingLogo)
                                    {{-- Existing --}}
                                    <img src="{{ asset('storage/'.$existingLogo) }}" class="w-full h-full object-contain p-1 rounded-xl">
                                @else
                                    <svg class="w-6 h-6 text-gray-400 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    <p class="text-[9px] text-gray-500 text-center leading-tight">Drop or<br>Click</p>
                                @endif
                            </div>
                            <input type="file" wire:model="logo" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" accept="image/png, image/jpeg, image/jpg" x-on:livewire-upload-start="isUploading = true" x-on:livewire-upload-finish="isUploading = false">
                            <div x-show="isUploading" class="absolute inset-0 bg-white/80 flex items-center justify-center z-20 rounded-xl">
                                <svg class="animate-spin h-5 w-5 text-blue-600" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                            </div>
                        </div>
                    </div>

                    {{-- COVER UPLOAD (Modified for Edit) --}}
                    <div class="mt-4">
                        <label class="block text-xs font-bold text-gray-700 mb-2">Cover Image</label>
                        <div x-data="{ isDropping: false, isUploading: false }" class="relative w-full h-32 group">
                            <div class="absolute inset-0 flex flex-col items-center justify-center border-2 border-dashed rounded-xl transition-colors duration-200 overflow-hidden"
                                 :class="isDropping ? 'border-blue-500 bg-blue-50' : 'border-gray-300 group-hover:border-blue-400 group-hover:bg-gray-50'">
                                
                                @if($cover)
                                    <img src="{{ $cover->temporaryUrl() }}" class="w-full h-full object-cover">
                                @elseif($existingCover)
                                    <img src="{{ asset('storage/'.$existingCover) }}" class="w-full h-full object-cover">
                                @else
                                    <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                        <svg class="w-8 h-8 text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                                        <p class="text-xs text-gray-500">Drag & Drop cover image here</p>
                                    </div>
                                @endif
                            </div>
                            <input type="file" wire:model="cover" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" accept="image/png, image/jpeg, image/jpg" x-on:livewire-upload-start="isUploading = true" x-on:livewire-upload-finish="isUploading = false">
                            <div x-show="isUploading" class="absolute inset-0 bg-white/80 flex items-center justify-center z-20 rounded-xl">
                                <div class="w-full max-w-[100px] bg-gray-200 rounded-full h-1.5"><div class="bg-blue-600 h-1.5 rounded-full animate-pulse" style="width: 100%"></div></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 2. DESCRIPTION --}}
            <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100">
                <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest border-b border-gray-100 pb-2 mb-4">About the Partnership</h3>
                <textarea wire:model.live="description" rows="4" class="w-full text-sm border-gray-200 rounded-lg focus:ring-blue-500" placeholder="Describe the strategic value of this partnership..."></textarea>
            </div>

            {{-- 3. CONTACT INFO --}}
            <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100">
                <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest border-b border-gray-100 pb-2 mb-4">Contact Details</h3>
                <div class="space-y-3">
                    <input wire:model.live="email" type="email" class="w-full text-xs border-gray-200 rounded-lg" placeholder="Email Address">
                    <input wire:model.live="website" type="text" class="w-full text-xs border-gray-200 rounded-lg" placeholder="Website URL">
                    <input wire:model.live="address" type="text" class="w-full text-xs border-gray-200 rounded-lg" placeholder="Office Address">
                </div>
            </div>

            {{-- 4. ENGAGEMENT TIMELINE --}}
            <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100">
                <div class="flex justify-between items-center mb-4">
                    <label class="text-xs font-bold text-gray-700 uppercase">Engagement History</label>
                    <button wire:click="addEngagement" class="text-[10px] bg-gray-100 hover:bg-blue-100 text-gray-600 px-2 py-1 rounded transition">+ Add Activity</button>
                </div>
                
                <div class="space-y-4">
                    @foreach($engagements as $index => $eng)
                    <div class="p-3 bg-gray-50 rounded-xl border border-gray-100 relative group">
                        <button wire:click="removeEngagement({{ $index }})" class="absolute top-2 right-2 text-gray-300 hover:text-red-500">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                        
                        <div class="grid grid-cols-2 gap-2 mb-2">
                            <input wire:model.live="engagements.{{ $index }}.date" type="date" class="text-[10px] border-gray-200 rounded">
                            <input wire:model.live="engagements.{{ $index }}.type" type="text" class="text-[10px] border-gray-200 rounded" placeholder="Type (e.g. Meeting)">
                        </div>
                        <input wire:model.live="engagements.{{ $index }}.title" type="text" class="w-full text-xs font-bold border-gray-200 rounded mb-2" placeholder="Activity Title">
                        <textarea wire:model.live="engagements.{{ $index }}.desc" rows="2" class="w-full text-[10px] border-gray-200 rounded" placeholder="Brief description..."></textarea>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- 5. SDGs --}}
            <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100">
                <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest border-b border-gray-100 pb-2 mb-4">Shared Goals (SDGs)</h3>
                <div class="grid grid-cols-2 gap-2">
                    @foreach($this->allSdgs as $sdg)
                        @php
                            $baseColor = str_replace('bg-', '', $sdg->color); 
                            $activeBorder = "border-$baseColor"; 
                            $activeBg = "bg-" . explode('-', $baseColor)[0] . "-50";
                            $activeText = "text-" . explode('-', $baseColor)[0] . "-700";
                        @endphp
                        <button wire:click="toggleSdg({{ $sdg->id }})" 
                                class="group flex items-center gap-2 p-1.5 rounded-lg border transition-all text-left
                                {{ in_array($sdg->id, $selectedSdgs) 
                                    ? "$activeBorder $activeBg shadow-sm ring-1 ring-offset-0" 
                                    : 'border-transparent hover:bg-gray-50' }}"
                                style="{{ in_array($sdg->id, $selectedSdgs) ? 'border-color: var(--tw-color-' . str_replace('-', '-', $baseColor) . ')' : '' }}">
                            <span class="w-8 h-8 shrink-0 flex items-center justify-center rounded text-white font-black text-[10px] shadow-sm {{ $sdg->color }}">
                                {{ $sdg->id }}
                            </span>
                            <span class="text-[10px] font-bold leading-tight line-clamp-2
                                {{ in_array($sdg->id, $selectedSdgs) ? $activeText : 'text-gray-500 group-hover:text-gray-700' }}">
                                {{ $sdg->name }}
                            </span>
                        </button>
                    @endforeach
                </div>
            </div>

            {{-- 6. JOINT PROJECTS --}}
            <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 mt-6">
                <div class="flex items-center justify-between border-b border-gray-100 pb-2 mb-4">
                    <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest">Joint Projects</h3>
                    <button wire:click="$set('showProjectModal', true)" class="text-[10px] font-bold text-blue-600 hover:text-blue-700 bg-blue-50 hover:bg-blue-100 px-2 py-1 rounded transition flex items-center gap-1">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        New Project
                    </button>
                </div>
                <div class="space-y-2 max-h-60 overflow-y-auto pr-2 custom-scrollbar">
                    @forelse($this->allProjects as $proj)
                    <button wire:click="toggleProject({{ $proj->id }})" 
                            class="w-full flex items-center gap-3 p-2 rounded-xl border text-left transition-all group
                            {{ in_array($proj->id, $selectedProjects) ? 'border-yellow-400 bg-yellow-50 ring-1 ring-yellow-200' : 'border-gray-100 hover:border-blue-200 hover:bg-gray-50' }}">
                        <div class="w-12 h-12 rounded-lg bg-gray-200 overflow-hidden shrink-0">
                            @if($proj->cover_image)
                                <img src="{{ asset('storage/'.$proj->cover_image) }}" class="w-full h-full object-cover">
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <h4 class="text-xs font-bold text-gray-800 truncate">{{ $proj->title }}</h4>
                            <span class="text-[10px] text-gray-400 uppercase tracking-wide">{{ $proj->status ?? 'Ongoing' }}</span>
                        </div>
                        <div class="w-5 h-5 rounded-full border flex items-center justify-center transition-colors {{ in_array($proj->id, $selectedProjects) ? 'bg-yellow-400 border-yellow-400 text-white' : 'border-gray-200 text-transparent' }}">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        </div>
                    </button>
                    @empty
                    <div class="text-center py-4"><p class="text-xs text-gray-400 italic">No projects found.</p></div>
                    @endforelse
                </div>
            </div>

        </div>

        {{-- ======================== --}}
        {{-- RIGHT PANEL: PREVIEW     --}}
        {{-- ======================== --}}
        <div class="w-full md:w-7/12 h-full overflow-y-auto bg-stone-100 relative shadow-inner"
             :class="mobilePreview ? 'block' : 'hidden md:block'">
            
            <div class="absolute top-4 right-4 z-50 bg-black/80 text-white text-[10px] font-bold uppercase px-3 py-1 rounded-full backdrop-blur pointer-events-none">Live Preview</div>

            <div class="min-h-full bg-stone-50 pb-20 origin-top scale-90 md:scale-100 transition-transform pointer-events-none select-none">
                {{-- HERO PREVIEW --}}
                <div class="relative h-[300px] w-full overflow-hidden bg-gray-200">
                    @if($cover)
                        <img src="{{ $cover->temporaryUrl() }}" class="w-full h-full object-cover">
                    @elseif($existingCover)
                        <img src="{{ asset('storage/'.$existingCover) }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-gray-400 font-bold uppercase tracking-widest text-xs">No Cover Image</div>
                    @endif
                    <div class="absolute inset-0 bg-gradient-to-t from-stone-50 via-stone-50/20 to-transparent"></div>
                </div>

                <div class="max-w-5xl mx-auto px-6 -mt-32 relative z-10 pb-24">
                    <div class="grid lg:grid-cols-12 gap-8">
                        <aside class="lg:col-span-4 space-y-8">
                            <div class="bg-white p-8 rounded-[2rem] shadow-xl border border-gray-100 text-center relative overflow-hidden">
                                <div class="absolute top-0 left-0 w-full h-2 bg-gradient-to-r from-blue-600 to-green-400"></div>
                                <div class="w-32 h-32 mx-auto bg-white rounded-2xl p-1 shadow-lg -mt-16 mb-6 border border-gray-100 relative z-10 flex items-center justify-center">
                                    @if($logo)
                                        <img src="{{ $logo->temporaryUrl() }}" class="w-full h-full object-contain rounded-xl">
                                    @elseif($existingLogo)
                                        <img src="{{ asset('storage/'.$existingLogo) }}" class="w-full h-full object-contain rounded-xl">
                                    @else
                                        <span class="text-xs font-bold text-gray-300">LOGO</span>
                                    @endif
                                </div>
                                <h1 class="font-heading font-black text-2xl text-gray-900 leading-tight mb-2">{{ $name ?: 'Partner Name' }}</h1>
                                {{-- (Rest of preview similar to Create/Show, ensuring data binding) --}}
                                {{-- ... --}}
                            </div>
                            
                            {{-- Preview SDGs --}}
                            @if(count($selectedSdgs) > 0)
                            <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100">
                                <h3 class="font-bold text-gray-900 uppercase tracking-widest text-xs border-b border-gray-100 pb-3 mb-4">Shared Goals</h3>
                                <div class="flex flex-col gap-3">
                                    @foreach($selectedSdgs as $id)
                                        @php $sdg = $this->allSdgs->find($id); @endphp
                                        @if($sdg)
                                        <div class="flex items-center gap-3 p-2 rounded-lg border border-transparent bg-gray-50">
                                            <div class="w-8 h-8 {{ $sdg->color }} rounded-md text-white font-black text-xs flex items-center justify-center shadow-sm">{{ $sdg->id }}</div>
                                            <span class="text-[10px] font-bold text-gray-700 uppercase tracking-wide">{{ $sdg->name }}</span>
                                        </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                            @endif
                        </aside>

                        <main class="lg:col-span-8 space-y-12 pt-8 lg:pt-0">
                            {{-- About --}}
                            <section>
                                <h3 class="font-bold text-gray-900 uppercase tracking-widest text-xs border-b-2 border-blue-500 w-16 pb-2 mb-6">About</h3>
                                <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100">
                                    <p class="text-gray-600 leading-relaxed font-serif text-lg whitespace-pre-line">{{ $description ?: 'Description...' }}</p>
                                    @if($scope)
                                    <div class="mt-6">
                                        <h4 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Scope</h4>
                                        <div class="flex flex-wrap gap-2">
                                            @foreach(explode(',', $scope) as $tag)
                                                @if(trim($tag))<span class="px-3 py-1 bg-gray-50 text-gray-600 text-xs font-bold rounded-lg border border-gray-200">{{ trim($tag) }}</span>@endif
                                            @endforeach
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </section>

                            {{-- Timeline --}}
                            <section>
                                <h3 class="font-bold text-gray-900 uppercase tracking-widest text-xs border-b-2 border-red-500 w-32 pb-2 mb-6">Partnership Journey</h3>
                                <div class="relative border-l-2 border-gray-200 ml-4 space-y-8 pl-8 pb-4">
                                    @forelse($engagements as $activity)
                                        @if(!empty($activity['title']))
                                        <div class="relative group">
                                            <div class="absolute -left-[41px] top-1 w-6 h-6 bg-white rounded-full border-4 border-gray-200"></div>
                                            <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-2">
                                                <h4 class="font-heading font-bold text-lg text-gray-900">{{ $activity['title'] }}</h4>
                                                <span class="text-xs font-bold text-gray-400 bg-gray-100 px-2 py-1 rounded">{{ $activity['date'] }}</span>
                                            </div>
                                            <p class="text-sm text-gray-600 leading-relaxed bg-white p-4 rounded-xl shadow-sm border border-gray-100">{{ $activity['desc'] }}</p>
                                        </div>
                                        @endif
                                    @empty
                                        <p class="text-xs text-gray-400 italic">No activities recorded.</p>
                                    @endforelse
                                </div>
                            </section>

                            {{-- Projects Preview --}}
                            @if(count($selectedProjects) > 0)
                            <section class="mt-12 border-t border-gray-200 pt-8">
                                <h3 class="font-bold text-gray-900 uppercase tracking-widest text-xs border-b-2 border-yellow-500 w-24 pb-2 mb-6">Joint Projects</h3>
                                <div class="grid sm:grid-cols-2 gap-4">
                                    @foreach($selectedProjects as $id)
                                        @php $proj = $this->allProjects->find($id); @endphp
                                        @if($proj)
                                        <div class="group relative aspect-video rounded-xl overflow-hidden shadow-sm hover:shadow-md transition">
                                            @if($proj->cover_image)
                                                <img src="{{ asset('storage/'.$proj->cover_image) }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-700">
                                            @else
                                                <div class="w-full h-full bg-gray-200 flex items-center justify-center text-gray-400 text-xs font-bold uppercase">No Image</div>
                                            @endif
                                            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent opacity-90"></div>
                                            <div class="absolute bottom-3 left-3 right-3">
                                                <span class="text-[9px] font-bold text-yellow-400 uppercase tracking-widest mb-0.5 block">Project</span>
                                                <h4 class="font-bold text-white text-sm leading-tight line-clamp-2">{{ $proj->title }}</h4>
                                            </div>
                                        </div>
                                        @endif
                                    @endforeach
                                </div>
                            </section>
                            @endif
                        </main>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL (Include same modal code from Create) --}}
    @if($showProjectModal)
    <div class="fixed inset-0 z-[100] flex items-center justify-center px-4">
        <div wire:click="$set('showProjectModal', false)" class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity"></div>
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md relative z-10 p-6 border border-gray-100 transform transition-all">
            <h3 class="font-heading font-bold text-lg text-gray-900 mb-1">Create New Project</h3>
            <p class="text-xs text-gray-500 mb-6">Quickly add a project to link with this partner.</p>
            <div class="space-y-4">
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Project Title</label>
                    <input wire:model="newProjectTitle" type="text" class="w-full text-sm border-gray-200 rounded-lg focus:ring-blue-500" placeholder="e.g. Coastal Cleanup 2025" autofocus>
                    @error('newProjectTitle') <span class="text-red-500 text-[10px]">{{ $message }}</span> @enderror
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Category</label>
                        <select wire:model="newProjectCategoryId" class="w-full text-sm border-gray-200 rounded-lg focus:ring-blue-500">
                            <option value="">Select...</option>
                            @foreach($this->projectCategories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                        @error('newProjectCategoryId') <span class="text-red-500 text-[10px] block">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Status</label>
                        <select wire:model="newProjectStatus" class="w-full text-sm border-gray-200 rounded-lg focus:ring-blue-500">
                            <option value="Upcoming">Upcoming</option>
                            <option value="Ongoing">Ongoing</option>
                            <option value="Completed">Completed</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="flex items-center justify-end gap-3 mt-8">
                <button wire:click="$set('showProjectModal', false)" class="px-4 py-2 text-xs font-bold text-gray-500 hover:text-gray-700 uppercase tracking-wider">Cancel</button>
                <button wire:click="createProject" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold uppercase tracking-wider rounded-lg shadow-md hover:shadow-lg transition">Create & Link</button>
            </div>
        </div>
    </div>
    @endif

</div>