<div class="min-h-screen bg-stone-100 font-sans text-gray-900" x-data="{ mobilePreview: false }">

    {{-- 1. EDITOR NAVBAR (Sticky & Mobile Responsive) --}}
    <nav class="sticky top-0 z-50 bg-white/90 backdrop-blur-md border-b border-gray-200 h-16 px-4 sm:px-6 flex items-center justify-between shadow-sm">
        
        {{-- Left: Back & Title --}}
        <div class="flex items-center gap-3">
            <a href="{{ route('projects.index') }}" class="text-xs font-bold uppercase text-gray-400 hover:text-red-600 transition flex items-center gap-1">
                <span class="text-lg leading-none">&larr;</span> <span class="hidden sm:inline">Cancel</span>
            </a>
            <div class="h-4 w-px bg-gray-300 mx-1"></div>
            <span class="font-heading font-black text-gray-800 tracking-tight text-sm sm:text-base">
                Edit <span class="text-red-600">Project</span>
            </span>
        </div>
        
        {{-- Center: Mobile Toggle --}}
        <div class="md:hidden absolute left-1/2 top-1/2 transform -translate-x-1/2 -translate-y-1/2">
            <button @click="mobilePreview = !mobilePreview" 
                    class="flex items-center gap-2 bg-gray-100 border border-gray-200 rounded-full p-1 pr-4 transition-all">
                <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase transition-colors"
                      :class="!mobilePreview ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-400'">
                    Edit
                </span>
                <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase transition-colors"
                      :class="mobilePreview ? 'bg-red-600 text-white shadow-sm' : 'text-gray-400'">
                    Preview
                </span>
            </button>
        </div>

        {{-- Right: Save Button --}}
        <div class="flex items-center gap-3">
            <button wire:click="update" 
                    wire:loading.attr="disabled"
                    class="px-4 py-2 sm:px-5 sm:py-2 bg-gradient-to-r from-red-600 to-yellow-500 text-white text-xs font-bold uppercase rounded-lg hover:shadow-lg hover:scale-105 transition shadow-md flex items-center gap-2">
                <span wire:loading.remove wire:target="update">Update</span>
                <span wire:loading wire:target="update">Saving...</span>
                <svg wire:loading wire:target="update" class="animate-spin h-3 w-3 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
            </button>
        </div>
    </nav>

    {{-- MAIN CONTAINER (Split Screen) --}}
    <div class="flex h-[calc(100vh-64px)] overflow-hidden relative">
        
        {{-- ========================================== --}}
        {{-- LEFT PANEL: FORM EDITOR                    --}}
        {{-- ========================================== --}}
        <div class="w-full md:w-5/12 h-full overflow-y-auto p-4 sm:p-6 bg-gray-50 border-r border-gray-200 space-y-6 scrollbar-thin scrollbar-thumb-gray-300"
             :class="mobilePreview ? 'hidden' : 'block'">

            {{-- 1. BASIC INFO --}}
            <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100">
                <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest border-b border-gray-100 pb-2 mb-4">Core Details</h3>
                <div class="space-y-4">
                    <div class="grid grid-cols-3 gap-4">
                        <div class="col-span-1">
                            <label class="block text-xs font-bold text-gray-700 mb-1">Academic Year</label>
                            <select wire:model.live="academic_year_id" class="w-full text-xs border-gray-200 rounded-lg focus:ring-yellow-400 focus:border-yellow-400 bg-yellow-50/50">
                                @foreach($academic_years as $year)
                                    <option value="{{ $year->id }}">{{ $year->name }} {{ $year->is_active ? '(Active)' : '' }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-span-2">
                            <label class="block text-xs font-bold text-gray-700 mb-1">Project Title</label>
                            <input wire:model.live="title" type="text" class="w-full text-sm border-gray-200 rounded-lg focus:ring-yellow-400 focus:border-yellow-400">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">URL Slug</label>
                        <div class="flex rounded-lg shadow-sm">
                            <span class="inline-flex items-center px-3 rounded-l-lg border border-r-0 border-gray-200 bg-gray-50 text-gray-500 text-xs">projects/</span>
                            <input wire:model.live="slug" type="text" class="flex-1 min-w-0 block w-full px-3 py-2 rounded-none rounded-r-lg text-xs border-gray-200 text-gray-600 focus:ring-yellow-400">
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">Category</label>
                            <select wire:model.live="project_category_id" class="w-full text-xs border-gray-200 rounded-lg focus:ring-yellow-400">
                                @foreach($categories as $category) <option value="{{ $category->id }}">{{ $category->name }}</option> @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">Status</label>
                            <select wire:model.live="status" class="w-full text-xs border-gray-200 rounded-lg focus:ring-yellow-400">
                                <option>Upcoming</option><option>Ongoing</option><option>Completed</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">Date</label>
                            <input wire:model.live="date" type="date" class="w-full text-xs border-gray-200 rounded-lg">
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
                            @if(count($proponents) > 1) <button wire:click="removeProponent({{ $index }})" class="absolute top-2 right-2 text-gray-300 hover:text-red-500"><svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button> @endif
                            <div class="flex bg-white p-1 rounded-md w-max mb-2 border border-gray-200">
                                <button wire:click="$set('proponents.{{ $index }}.type', 'user')" class="px-2 py-0.5 text-[9px] font-bold rounded transition {{ $prop['type'] === 'user' ? 'bg-gray-800 text-white shadow' : 'text-gray-400 hover:text-gray-600' }}">User</button>
                                <button wire:click="$set('proponents.{{ $index }}.type', 'custom')" class="px-2 py-0.5 text-[9px] font-bold rounded transition {{ $prop['type'] === 'custom' ? 'bg-gray-800 text-white shadow' : 'text-gray-400 hover:text-gray-600' }}">Custom</button>
                            </div>
                            @if($prop['type'] === 'user') <select wire:model.live="proponents.{{ $index }}.id" class="w-full text-xs border-gray-200 rounded-lg focus:ring-yellow-400"><option value="">-- Select User --</option>@foreach($users as $user)<option value="{{ $user->id }}">{{ $user->name }}</option>@endforeach</select> @else <input wire:model.live="proponents.{{ $index }}.name" type="text" class="w-full text-xs border-gray-200 rounded-lg focus:ring-yellow-400" placeholder="e.g. Committee Name"> @endif
                        </div>
                        @endforeach
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Beneficiaries</label>
                        <input wire:model.live="beneficiaries" type="text" class="w-full text-xs border-gray-200 rounded-lg">
                    </div>

                    {{-- IMAGE UPLOAD --}}
                    <div x-data="{ isDropping: false, isUploading: false, progress: 0 }" x-on:livewire-upload-start="isUploading = true" x-on:livewire-upload-finish="isUploading = false" x-on:livewire-upload-error="isUploading = false" x-on:livewire-upload-progress="progress = $event.detail.progress" class="w-full space-y-2">
                        <label class="block text-xs font-bold text-gray-700">Cover Image</label>
                        <div class="relative w-full h-48 bg-gray-50 rounded-xl overflow-hidden border-2 border-dashed transition-all duration-200 group" style="min-height: 12rem;" :class="{'border-yellow-400 bg-yellow-50 ring-4 ring-yellow-100': isDropping, 'border-gray-300 hover:bg-gray-100': !isDropping}">
                            <input type="file" wire:model="coverImg" accept="image/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-50" x-on:dragover.prevent="isDropping = true" x-on:dragleave="isDropping = false" x-on:drop="isDropping = false">
                            <div x-show="isUploading" class="absolute inset-0 z-40 flex flex-col items-center justify-center bg-white/90 backdrop-blur-sm" style="display: none;"><svg class="animate-spin h-8 w-8 text-yellow-500 mb-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg><div class="w-1/2 bg-gray-200 rounded-full h-1.5"><div class="bg-yellow-500 h-1.5 rounded-full transition-all duration-300" :style="'width: ' + progress + '%'"></div></div><p class="text-[10px] font-bold text-gray-500 mt-2 uppercase tracking-wider">Uploading...</p></div>
                            <div class="absolute inset-0 w-full h-full z-10 flex flex-col items-center justify-center pointer-events-none">
                                @if($coverImg) <img src="{{ $coverImg->temporaryUrl() }}" class="absolute inset-0 w-full h-full object-cover"><div class="absolute inset-0 bg-black/40"></div><div class="relative bg-white/90 backdrop-blur px-3 py-1.5 rounded-full shadow-lg flex items-center gap-2"><span class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></span><span class="text-[10px] font-bold uppercase text-gray-800">New Image Ready</span></div>
                                @elseif($oldCoverImg) <img src="{{ asset('storage/'.$oldCoverImg) }}" class="absolute inset-0 w-full h-full object-cover grayscale opacity-50 group-hover:grayscale-0 group-hover:opacity-100 transition-all duration-500"><div class="relative flex flex-col items-center text-gray-600 group-hover:text-white transition-colors"><div class="bg-white/80 p-2 rounded-full shadow-sm mb-2 group-hover:scale-110 transition-transform"><svg class="w-5 h-5 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg></div><span class="text-[10px] font-bold uppercase tracking-wider bg-white/30 px-2 py-1 rounded backdrop-blur-sm">Replace Image</span></div>
                                @else <div class="flex flex-col items-center justify-center pt-5 pb-6 text-gray-400 group-hover:text-yellow-600 transition-colors"><svg class="w-10 h-10 mb-3 text-gray-300 group-hover:text-yellow-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg><p class="text-xs font-medium"><span class="font-bold underline decoration-yellow-400">Click to upload</span></p></div> @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 2. DESCRIPTION --}}
            <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100">
                <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest border-b border-gray-100 pb-2 mb-4">The Story</h3>
                <textarea wire:model.live="description" rows="5" class="w-full text-sm border-gray-200 rounded-lg focus:ring-yellow-400"></textarea>
            </div>

            {{-- 3. LISTS (Objectives/Partners) --}}
            <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 space-y-6">
                <div>
                    <div class="flex justify-between items-center mb-2"><label class="text-xs font-bold text-gray-700 uppercase">Objectives</label><button wire:click="addObjective" class="text-[10px] bg-gray-100 hover:bg-green-100 text-gray-600 px-2 py-1 rounded transition">+ Add</button></div>
                    <div class="space-y-2">@foreach($objectives as $index => $obj)<div class="flex gap-2"><input wire:model.live="objectives.{{ $index }}" type="text" class="w-full text-xs border-gray-200 rounded"><button wire:click="removeObjective({{ $index }})" class="text-red-400 hover:text-red-600"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button></div>@endforeach</div>
                </div>
                <div>
                    <div class="flex justify-between items-center mb-2"><label class="text-xs font-bold text-gray-700 uppercase">Partners</label><button wire:click="addPartner" class="text-[10px] bg-gray-100 hover:bg-green-100 text-gray-600 px-2 py-1 rounded transition">+ Add</button></div>
                    <div class="space-y-3">
                        @foreach($partners as $index => $partner)
                        <div class="p-3 bg-gray-50 rounded-lg border border-gray-100 relative group">
                            <button wire:click="removePartner({{ $index }})" class="absolute top-2 right-2 text-gray-300 hover:text-red-500"><svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
                            <div class="grid grid-cols-12 gap-2">
                                <div class="col-span-2 flex items-center justify-center">
                                    <button title="Toggle Type" wire:click="$set('partners.{{ $index }}.type', '{{ $partner['type'] === 'database' ? 'custom' : 'database' }}')" class="w-8 h-8 rounded-full flex items-center justify-center border transition {{ $partner['type'] === 'database' ? 'bg-blue-100 border-blue-200 text-blue-600' : 'bg-gray-200 border-gray-300 text-gray-500' }}">
                                        @if($partner['type'] === 'database') <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4"></path></svg> @else <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg> @endif
                                    </button>
                                </div>
                                <div class="col-span-10 space-y-2">
                                    @if($partner['type'] === 'database') <select wire:model.live="partners.{{ $index }}.id" class="w-full text-xs border-gray-200 rounded-lg focus:ring-blue-400"><option value="">-- Select --</option>@foreach($availableLinkages as $linkage)<option value="{{ $linkage->id }}">{{ $linkage->name }}</option>@endforeach</select> @else <input wire:model.live="partners.{{ $index }}.name" type="text" class="w-full text-xs border-gray-200 rounded-lg focus:ring-gray-400" placeholder="Custom Partner"> @endif
                                    <div class="flex items-center gap-2"><span class="text-[9px] font-bold text-gray-400 uppercase tracking-wider">Role:</span><input wire:model.live="partners.{{ $index }}.role" type="text" class="flex-1 py-1 px-2 text-[10px] border-gray-200 rounded-md focus:ring-blue-400 bg-white" placeholder="e.g. Sponsor"></div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                {{-- Impact Stats Editor --}}
                <div>
                    <div class="flex justify-between items-center mb-4"><label class="text-xs font-bold text-gray-700 uppercase">Impact Stats</label><button wire:click="addStat" class="text-[10px] bg-gray-100 hover:bg-green-100 text-gray-600 px-2 py-1 rounded transition">+ Add</button></div>
                    <div class="space-y-2">@foreach($impact_stats as $index => $stat)<div class="grid grid-cols-2 gap-2"><input wire:model.live="impact_stats.{{ $index }}.value" type="text" class="text-xs border-gray-200 rounded font-bold" placeholder="Value (e.g. 500+)"><div class="flex gap-2"><input wire:model.live="impact_stats.{{ $index }}.label" type="text" class="w-full text-xs border-gray-200 rounded" placeholder="Label"><button wire:click="removeStat({{ $index }})" class="text-red-400 hover:text-red-600"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button></div></div>@endforeach</div>
                </div>
            </div>

            {{-- 4. SDGs --}}
            <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100">
                <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest border-b border-gray-100 pb-2 mb-4">Target SDGs</h3>
                <div class="grid grid-cols-4 gap-2">
                    @foreach($sdgs as $sdg)
                    <button wire:click="toggleSdg({{ $sdg->id }})" style="background-color: {{ in_array($sdg->id, $selectedSdgs) ? $sdg->color_hex : '#f3f4f6' }}; color: {{ in_array($sdg->id, $selectedSdgs) ? 'white' : '#9ca3af' }};" class="aspect-square flex flex-col items-center justify-center p-1 rounded-lg transition-all transform hover:scale-105 border border-transparent shadow-sm hover:shadow-md">
                        <span class="text-sm font-black leading-none">{{ $sdg->number }}</span><span class="text-[7px] font-bold uppercase leading-tight text-center mt-1 line-clamp-2">{{ $sdg->name }}</span>
                    </button>
                    @endforeach
                </div>
            </div>

            {{-- 5. GALLERY --}}
            <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100" x-data="{ isUploadingGallery: false, progress: 0 }">
                <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest border-b border-gray-100 pb-2 mb-4">Gallery</h3>
                @if(count($galleryInputs) > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                        @foreach($galleryInputs as $index => $item)
                            <div class="flex gap-3 p-2 border border-gray-200 rounded-lg bg-gray-50 relative group">
                                <button wire:click="deleteGalleryItem({{ $item['id'] }})" type="button" class="absolute -top-2 -right-2 bg-red-100 text-red-500 rounded-full p-1 shadow hover:bg-red-600 hover:text-white transition z-10"><svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
                                <div class="w-20 h-20 flex-shrink-0 bg-gray-200 rounded-md overflow-hidden"><img src="{{ asset('storage/'.$item['image_path']) }}" class="w-full h-full object-cover"></div>
                                <div class="flex-1 space-y-2">
                                    <input wire:model="galleryInputs.{{ $index }}.title" type="text" class="w-full text-xs font-bold border-gray-200 rounded px-2 py-1 placeholder-gray-400 focus:ring-yellow-400" placeholder="Title">
                                    <textarea wire:model="galleryInputs.{{ $index }}.description" rows="2" class="w-full text-xs border-gray-200 rounded px-2 py-1 resize-none placeholder-gray-400 focus:ring-yellow-400" placeholder="Desc..."></textarea>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
                @if(count($pendingGalleryItems) > 0)
                    <div class="space-y-3 mb-6">
                        @foreach($pendingGalleryItems as $index => $item)
                            <div class="flex gap-3 p-3 border-2 border-green-100 rounded-xl bg-green-50/30 relative" wire:key="pending-{{ $item['temp_id'] }}">
                                <button wire:click="removePendingItem({{ $index }})" class="absolute top-2 right-2 text-gray-400 hover:text-red-500 transition"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
                                <div class="w-24 h-24 flex-shrink-0 bg-gray-200 rounded-lg overflow-hidden shadow-sm"><img src="{{ $item['temp_file']->temporaryUrl() }}" class="w-full h-full object-cover"></div>
                                <div class="flex-1 pr-6 space-y-2">
                                    <input wire:model="pendingGalleryItems.{{ $index }}.title" type="text" class="w-full text-xs font-bold border-gray-200 rounded-lg focus:border-green-400 focus:ring-green-400 bg-white" placeholder="Title">
                                    <input wire:model="pendingGalleryItems.{{ $index }}.description" type="text" class="w-full text-xs border-gray-200 rounded-lg focus:border-green-400 focus:ring-green-400 bg-white" placeholder="Desc...">
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
                <div x-on:livewire-upload-start="isUploadingGallery = true" x-on:livewire-upload-finish="isUploadingGallery = false" x-on:livewire-upload-error="isUploadingGallery = false" x-on:livewire-upload-progress="progress = $event.detail.progress" class="relative mt-4">
                    <label class="relative flex flex-col items-center justify-center w-full h-24 border-2 border-dashed border-gray-300 rounded-xl cursor-pointer hover:bg-gray-50 hover:border-yellow-400 transition-all group">
                        <div x-show="isUploadingGallery" class="absolute inset-0 z-20 flex flex-col items-center justify-center bg-white/90 rounded-xl"><svg class="animate-spin h-5 w-5 text-yellow-500 mb-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg><span class="text-[10px] font-bold uppercase text-gray-500">Processing...</span></div>
                        <div class="flex flex-col items-center justify-center pt-5 pb-6 text-gray-400 group-hover:text-yellow-600"><svg class="w-6 h-6 mb-1 transition-transform group-hover:-translate-y-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v16m8-8H4"></path></svg><p class="text-[10px] font-bold">Add More Photos</p></div>
                        <input type="file" wire:model="newGalleryImages" multiple class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                    </label>
                    @error('newGalleryImages.*') <span class="text-red-500 text-[10px]">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>

        {{-- ========================================== --}}
        {{-- RIGHT PANEL: LIVE PREVIEW                  --}}
        {{-- ========================================== --}}
        @php
            // Construct Preview Data using your Logic
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

        <div class="w-full md:w-7/12 h-full overflow-y-auto bg-stone-50 relative shadow-inner scrollbar-thin scrollbar-thumb-gray-400"
             :class="mobilePreview ? 'block' : 'hidden md:block'">
             
             <div class="min-h-full pb-20 origin-top pointer-events-none select-none">
                
                {{-- A. HERO SECTION (Identical to Show Page) --}}
                <header class="relative pt-12 pb-12 px-6 max-w-5xl mx-auto">
                    <div class="grid lg:grid-cols-2 gap-8 lg:gap-12 items-center">
                        {{-- Text Content --}}
                        <div class="order-2 lg:order-1">
                            <div class="flex items-center gap-3 mb-6">
                                <span class="w-10 h-1 bg-gradient-to-r from-red-600 to-yellow-500 rounded-full"></span>
                                @if($project_category_id)
                                    @php $cat = $categories->find($project_category_id); @endphp
                                    <span class="text-xs font-bold text-gray-500 uppercase tracking-widest">
                                        {{ $cat ? $cat->name : 'Category' }}
                                    </span>
                                @endif
                            </div>
                            
                            <h1 class="font-heading text-4xl md:text-5xl font-black text-gray-900 leading-[1.1] mb-6">
                                {{ $title ?: 'Project Title' }}
                            </h1>
                            
                            {{-- Description Excerpt / Intro --}}
                            <p class="text-lg text-gray-600 leading-relaxed font-sans mb-8 border-l-4 border-yellow-400 pl-6 whitespace-pre-line">
                                {{ Str::limit($description, 200) ?: 'Project description preview...' }}
                            </p>

                            {{-- Impact Stats (Placed in Hero as per Show Page Design) --}}
                            @if(count($impact_stats) > 0 && !empty($impact_stats[0]['value']))
                            <div class="grid grid-cols-3 gap-4 border-t border-gray-200 pt-8">
                                @foreach($impact_stats as $stat)
                                    @if(!empty($stat['value']))
                                    <div>
                                        <span class="block text-2xl font-black text-gray-900">{{ $stat['value'] }}</span>
                                        <span class="text-[10px] uppercase tracking-wider text-gray-400 font-bold">{{ $stat['label'] }}</span>
                                    </div>
                                    @endif
                                @endforeach
                            </div>
                            @endif
                        </div>

                        {{-- Hero Image Card --}}
                        <div class="order-1 lg:order-2 relative group">
                            <div class="absolute inset-0 bg-red-600 rounded-[2.5rem] rotate-3 opacity-20 group-hover:rotate-6 transition duration-500"></div>
                            <div class="relative overflow-hidden rounded-[2.5rem] shadow-2xl aspect-[4/3] border-4 border-white bg-gray-200">
                                @if($coverImg)
                                    <img src="{{ $coverImg->temporaryUrl() }}" class="w-full h-full object-cover">
                                @elseif($oldCoverImg)
                                    <img src="{{ asset('storage/'.$oldCoverImg) }}" class="w-full h-full object-cover">
                                @else
                                    <div class="flex items-center justify-center h-full text-gray-400 font-bold">NO IMAGE</div>
                                @endif
                                <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent"></div>
                                <div class="absolute bottom-6 left-6 bg-white/90 backdrop-blur px-4 py-2 rounded-xl flex items-center gap-2 shadow-lg">
                                    <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                    <div>
                                        <p class="text-[10px] text-gray-400 font-bold uppercase">Location</p>
                                        <p class="text-xs font-bold text-gray-900">{{ $location ?: 'TBA' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </header>

                {{-- B. MAIN CONTENT GRID (Sidebar Left, Content Right - Matching Show Page) --}}
                <div class="px-6 max-w-7xl mx-auto grid lg:grid-cols-12 gap-12 pb-24">
                    
                    {{-- SIDEBAR (Left) --}}
                    <aside class="lg:col-span-4 space-y-8">
                        {{-- Quick Info Card --}}
                        <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 relative overflow-hidden">
                            <h3 class="font-bold text-gray-900 uppercase tracking-widest text-xs border-b border-gray-100 pb-3 mb-4">Project Details</h3>
                            <ul class="space-y-4">
                                <li class="flex items-start gap-3">
                                    <div class="w-8 h-8 rounded-full bg-red-50 text-red-600 flex items-center justify-center shrink-0"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg></div>
                                    <div><span class="block text-[10px] font-bold text-gray-400 uppercase">Lead Proponents</span><span class="text-xs font-bold text-gray-800">{{ $proponentLabel }}</span></div>
                                </li>
                                <li class="flex items-start gap-3">
                                    <div class="w-8 h-8 rounded-full bg-gray-50 text-gray-500 flex items-center justify-center shrink-0"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg></div>
                                    <div><span class="block text-[10px] font-bold text-gray-400 uppercase">Date</span><span class="text-sm font-bold text-gray-800">{{ $date ? \Carbon\Carbon::parse($date)->format('F d, Y') : 'TBA' }}</span></div>
                                </li>
                                <li class="flex items-start gap-3">
                                    <div class="w-8 h-8 rounded-full bg-yellow-50 text-yellow-600 flex items-center justify-center shrink-0"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg></div>
                                    <div><span class="block text-[10px] font-bold text-gray-400 uppercase">Status</span><span class="text-sm font-bold text-gray-800">{{ $status }}</span></div>
                                </li>
                            </ul>
                        </div>

                        {{-- Partners --}}
                        @if(!empty($previewPartners))
                        <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100">
                            <h3 class="font-bold text-gray-900 uppercase tracking-widest text-xs border-b border-gray-100 pb-3 mb-4">In Partnership With</h3>
                            <div class="flex flex-wrap gap-2">
                                @foreach($previewPartners as $p)
                                <div class="inline-flex items-center rounded-lg border overflow-hidden {{ $p['is_official'] ? 'border-blue-100 bg-blue-50' : 'border-gray-200 bg-gray-50' }}">
                                    <span class="px-2 py-1 text-xs font-bold text-gray-700">{{ $p['name'] }}</span>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        {{-- SDGs --}}
                        @if(count($selectedSdgs) > 0)
                        <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100">
                            <h3 class="font-bold text-gray-900 uppercase tracking-widest text-xs border-b border-gray-100 pb-3 mb-4">Target SDGs</h3>
                            <div class="flex flex-col gap-2">
                                @foreach($selectedSdgs as $id)
                                    @php $s = $sdgs->find($id); @endphp
                                    @if($s)
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded text-white font-bold text-xs flex items-center justify-center" style="background-color: {{ $s->color_hex }}">{{ $s->number }}</div>
                                        <span class="text-xs font-bold text-gray-700 uppercase">{{ $s->name }}</span>
                                    </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                        @endif
                    </aside>

                    {{-- MAIN CONTENT (Right) --}}
                    <div class="lg:col-span-8 space-y-12">
                        
                        {{-- 1. OBJECTIVES (Black Gradient Card) --}}
                        @if(count($objectives) > 0 && !empty($objectives[0]))
                        <div class="bg-gradient-to-br from-gray-900 to-gray-800 text-white p-8 rounded-[2rem] shadow-lg relative overflow-hidden">
                            <div class="absolute top-0 right-0 w-48 h-48 bg-white/10 rounded-full blur-3xl -mr-10 -mt-10"></div>
                            <h3 class="font-bold uppercase tracking-widest text-sm mb-6 text-yellow-400 relative z-10 flex items-center gap-2">
                                <span class="w-2 h-2 bg-yellow-400 rounded-full"></span> Project Objectives
                            </h3>
                            <ul class="space-y-4 relative z-10">
                                @foreach($objectives as $obj)
                                    @if(!empty($obj))
                                    <li class="flex items-start gap-3 group">
                                        <div class="w-6 h-6 rounded-full bg-white/10 flex items-center justify-center shrink-0"><svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg></div>
                                        <span class="text-gray-200 text-sm md:text-base leading-relaxed">{{ $obj }}</span>
                                    </li>
                                    @endif
                                @endforeach
                            </ul>
                        </div>
                        @endif

                        {{-- 2. FULL DESCRIPTION --}}
                        <div class="prose prose-stone max-w-none">
                            <p class="whitespace-pre-line">{{ $description }}</p>
                        </div>

                        {{-- 3. GALLERY PREVIEW --}}
                        @if(count($galleryInputs) > 0 || count($pendingGalleryItems) > 0)
                        <div class="mt-12 bg-white rounded-3xl p-8 border border-gray-100 shadow-xl shadow-gray-200/50">
                            <div class="flex items-center gap-3 mb-8">
                                <div class="p-2 bg-red-50 rounded-lg text-red-600"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg></div>
                                <div><h3 class="font-heading text-xl font-black text-gray-900 leading-tight">Project Gallery</h3></div>
                            </div>
                            <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                                {{-- Existing --}}
                                @foreach($galleryInputs as $item)
                                    <div class="aspect-square bg-gray-200 rounded-xl overflow-hidden relative"><img src="{{ asset('storage/'.$item['image_path']) }}" class="w-full h-full object-cover"></div>
                                @endforeach
                                {{-- New --}}
                                @foreach($pendingGalleryItems as $item)
                                    <div class="aspect-square bg-green-50 rounded-xl overflow-hidden relative border-2 border-green-200"><img src="{{ $item['temp_file']->temporaryUrl() }}" class="w-full h-full object-cover"></div>
                                @endforeach
                            </div>
                        </div>
                        @endif

                    </div>
                </div>
             </div>
        </div>

    </div>
</div>