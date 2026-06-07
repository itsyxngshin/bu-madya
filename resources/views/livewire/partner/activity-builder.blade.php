<div class="min-h-screen bg-gray-100 font-sans text-gray-900" x-data="{ mobilePreview: false }">

    {{-- 1. EDITOR NAVBAR (Sticky & Responsive) --}}
    <nav class="sticky top-0 z-50 bg-white/90 backdrop-blur-md border-b border-gray-200 h-16 px-4 md:px-6 flex items-center justify-between">
        
        @php
            $role = auth()->user()->role?->role_name ?? 'guest';
            $manageRoute = match($role) {
                'administrator' => route('admin.activities.manage'),
                'organization'  => route('partner.activities.manage'),
                'director'      => route('director.activities.manage'),
                default         => route('dashboard'),
            };
        @endphp

        <div class="flex items-center gap-3">
            {{-- FIXED: Dynamic Role-Based Route --}}
            <a href="{{ $manageRoute }}" class="text-gray-400 hover:text-red-600 transition p-1" title="Back to Manager">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <div class="h-4 w-px bg-gray-300 hidden md:block"></div>
            <span class="font-heading font-black text-gray-800 tracking-tight hidden md:block">
                Activity <span class="text-red-600">Builder</span>
            </span>
        </div>
        
        <div class="md:hidden bg-gray-100 p-1 rounded-lg flex items-center shadow-inner">
            <button @click="mobilePreview = false" :class="!mobilePreview ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-500 hover:text-gray-700'" class="px-3 py-1.5 rounded-md text-[10px] font-bold uppercase tracking-wide transition-all">Editor</button>
            <button @click="mobilePreview = true" :class="mobilePreview ? 'bg-white text-red-600 shadow-sm' : 'text-gray-500 hover:text-gray-700'" class="px-3 py-1.5 rounded-md text-[10px] font-bold uppercase tracking-wide transition-all">Preview</button>
        </div>

        <div class="flex items-center gap-2 md:gap-3">
            <button wire:click="saveActivity" wire:loading.attr="disabled" class="px-5 py-2 bg-gradient-to-r from-red-600 to-orange-500 text-white text-xs font-bold uppercase rounded-lg hover:shadow-lg hover:scale-105 transition shadow-md flex items-center gap-2">
                <span wire:loading.remove wire:target="saveActivity">{{ $isEditMode ? 'Update Activity' : 'Publish' }}</span>
                <span wire:loading wire:target="saveActivity">Saving...</span>
            </button>
        </div>
    </nav>

    <div class="flex h-[calc(100vh-64px)] overflow-hidden relative">

        {{-- ======================== --}}
        {{-- LEFT PANEL: FORM EDITOR  --}}
        {{-- ======================== --}}
        <div class="w-full md:w-5/12 h-full overflow-y-auto p-4 md:p-6 bg-gray-50 border-r border-gray-200 space-y-6 pb-24" :class="mobilePreview ? 'hidden md:block' : 'block'">

            {{-- SECTION 1: CORE DETAILS --}}
            <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100">
                <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest border-b border-gray-100 pb-2 mb-4">Core Details</h3>

                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Activity Title <span class="text-red-500">*</span></label>
                        <input wire:model.live.debounce.300ms="title" type="text" class="w-full text-sm border-gray-200 rounded-lg focus:ring-red-400 focus:border-red-400">
                        @error('title') <span class="text-red-500 text-[10px]">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">URL Slug <span class="text-red-500">*</span></label>
                        <div class="flex rounded-lg shadow-sm">
                            <span class="inline-flex items-center px-3 rounded-l-lg border border-r-0 border-gray-200 bg-gray-50 text-gray-500 text-[10px] md:text-xs">/a/</span>
                            <input wire:model.live="slug" type="text" class="flex-1 min-w-0 block w-full px-3 py-2 rounded-none rounded-r-lg text-xs border-gray-200 text-gray-600 focus:ring-red-400 focus:border-red-400">
                        </div>
                        @error('slug') <span class="text-red-500 text-[10px] mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">Nature of Activity <span class="text-red-500">*</span></label>
                            <input wire:model.live="nature_of_activity" type="text" placeholder="e.g. Partnership" class="w-full text-xs border-gray-200 rounded-lg focus:ring-red-400">
                            @error('nature_of_activity') <span class="text-red-500 text-[10px]">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">Status</label>
                            <select wire:model.live="status" class="w-full text-xs border-gray-200 rounded-lg focus:ring-red-400">
                                <option value="upcoming">Upcoming</option>
                                <option value="ongoing">Ongoing</option>
                                <option value="completed">Completed</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">Start Date <span class="text-red-500">*</span></label>
                            <input wire:model.live="start_date" type="date" class="w-full text-xs border-gray-200 rounded-lg focus:ring-red-400">
                            @error('start_date') <span class="text-red-500 text-[10px]">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">End Date</label>
                            <input wire:model.live="end_date" type="date" class="w-full text-xs border-gray-200 rounded-lg focus:ring-red-400">
                        </div>
                    </div>

                    {{-- NEW: Interactive SDG Mapping Grid --}}
                    {{-- SECTION: TARGET SDGS --}}
                    <div class="col-span-full mt-6">
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest border-b border-gray-100 pb-2 mb-4">Target SDGs</label>
                        
                        <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-5 gap-3">
                            @foreach($sdgs as $sdg)
                                <button type="button" wire:click="toggleSdg({{ $sdg->id }})" 
                                        style="background-color: {{ in_array($sdg->id, $selectedSdgs) ? $sdg->color_hex : '#f8fafc' }};
                                               color: {{ in_array($sdg->id, $selectedSdgs) ? 'white' : '#64748b' }};
                                               border-color: {{ in_array($sdg->id, $selectedSdgs) ? $sdg->color_hex : '#f1f5f9' }};"
                                        class="flex flex-col items-center justify-center p-3 rounded-xl border transition-all transform hover:-translate-y-1 shadow-sm text-center gap-1.5 h-full min-h-[80px]">
                                    
                                    {{-- Number --}}
                                    <span class="text-xl font-black leading-none">{{ $sdg->number }}</span>
                                    
                                    {{-- Name --}}
                                    <span class="text-[8px] font-bold uppercase leading-tight"
                                          style="color: {{ in_array($sdg->id, $selectedSdgs) ? 'rgba(255,255,255,0.9)' : '#64748b' }}">
                                        {{ $sdg->name }}
                                    </span>
                                </button>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            {{-- SECTION 2: DESCRIPTION --}}
            <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100">
                <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest border-b border-gray-100 pb-2 mb-4">Activity Overview</h3>
                <textarea wire:model.live="description" rows="5" class="w-full text-sm border-gray-200 rounded-lg focus:ring-red-400" placeholder="Describe the activity..."></textarea>
            </div>

            {{-- SECTION 3: MEDIA UPLOAD --}}
            <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100">
                <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest border-b border-gray-100 pb-2 mb-4">Highlight Photos</h3>

                <div x-data="{ isDropping: false }" class="relative group mb-4">
                    <label
                        x-on:dragover.prevent="isDropping = true"
                        x-on:dragleave.prevent="isDropping = false"
                        x-on:drop.prevent="isDropping = false; $refs.multiFile.files = $event.dataTransfer.files; $refs.multiFile.dispatchEvent(new Event('change', { bubbles: true }));"
                        :class="{'border-red-400 bg-red-50 ring-2 ring-red-200': isDropping, 'border-gray-300 bg-gray-50 hover:bg-gray-100': !isDropping}"
                        class="flex flex-col items-center justify-center w-full h-24 border-2 border-dashed rounded-xl transition-all duration-200 cursor-pointer overflow-hidden relative">

                        <div class="flex flex-col items-center justify-center pt-5 pb-6 text-gray-400">
                            <svg class="w-6 h-6 mb-2 text-gray-400 group-hover:text-red-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                            <p class="text-[10px] font-bold text-gray-600"><span class="text-red-600">Tap to upload</span> or drag images here</p>
                        </div>
                        <input type="file" x-ref="multiFile" wire:model="photos" multiple accept="image/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                    </label>
                    <div wire:loading wire:target="photos" class="text-[10px] text-red-500 font-bold mt-2 animate-pulse text-center w-full">Uploading...</div>
                </div>

                {{-- Image Previews --}}
                <div class="flex flex-wrap gap-2">
                    @foreach($existing_photos as $index => $photo)
                        <div class="relative w-16 h-16 rounded-lg overflow-hidden border border-gray-200 group">
                            <img src="{{ asset('storage/' . $photo) }}" class="w-full h-full object-cover">
                            <button wire:click="removeExistingPhoto({{ $index }})" class="absolute inset-0 bg-red-500/80 text-white opacity-0 group-hover:opacity-100 flex items-center justify-center transition"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg></button>
                        </div>
                    @endforeach

                    @if($photos)
                        @foreach($photos as $index => $photo)
                            <div class="relative w-16 h-16 rounded-lg overflow-hidden border border-red-200 group">
                                <img src="{{ $photo->temporaryUrl() }}" class="w-full h-full object-cover">
                                <button wire:click="removeUploadedPhoto({{ $index }})" class="absolute inset-0 bg-red-500/80 text-white opacity-0 group-hover:opacity-100 flex items-center justify-center transition"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg></button>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>

            {{-- SECTION 4: TAGGING --}}
            <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 space-y-6">
                <div>
                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Lead Coordinating Entity (If External)</label>
                    <input wire:model.live="lead_organization" type="text" class="w-full text-xs border-gray-200 rounded-lg focus:ring-red-400" placeholder="e.g. Red Cross Youth">
                </div>

                <div class="bg-gray-50 p-4 rounded-xl border border-gray-200 relative">
                    <h4 class="text-[10px] font-black text-gray-600 uppercase tracking-widest mb-2">Tag Users</h4>
                    <input type="text" wire:model.live.debounce.300ms="searchQuery" placeholder="Search by name or ID..." class="w-full text-xs rounded-lg border-gray-300 focus:ring-red-400 shadow-sm mb-2">

                    @if(strlen($searchQuery) >= 2)
                        <div class="absolute z-50 left-4 right-4 mt-1 bg-white border border-gray-100 shadow-xl rounded-xl overflow-hidden max-h-48 overflow-y-auto">
                            @forelse($searchResults as $user)
                                <div class="flex items-center justify-between p-2 border-b border-gray-50 hover:bg-gray-50 transition">
                                    <div class="min-w-0 flex-1"><p class="text-[10px] font-bold text-gray-900 truncate">{{ $user->name }}</p></div>
                                    <div class="flex gap-1 shrink-0 ml-2">
                                        <button wire:click.prevent="addUserToRole({{ $user->id }}, 'focal')" class="px-2 py-1 bg-orange-100 hover:bg-orange-600 text-orange-700 hover:text-white text-[9px] font-bold uppercase rounded-md transition">Focal</button>
                                        <button wire:click.prevent="addUserToRole({{ $user->id }}, 'participant')" class="px-2 py-1 bg-blue-100 hover:bg-blue-600 text-blue-700 hover:text-white text-[9px] font-bold uppercase rounded-md transition">Participant</button>
                                    </div>
                                </div>
                            @empty
                                <div class="p-3 text-center text-[9px] font-bold text-gray-400 uppercase">No users found.</div>
                            @endforelse
                        </div>
                    @endif
                </div>

                {{-- Focals --}}
                <div>
                    <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 flex justify-between">Lead Focals <span class="bg-gray-100 px-2 py-0.5 rounded-full">{{ count($selectedFocals) }}</span></h4>
                    <div class="flex flex-wrap gap-2">
                        @forelse($selectedFocals as $focal)
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold bg-orange-50 text-orange-700 border border-orange-100">
                                {{ $focal['name'] }}
                                <button wire:click.prevent="removeUserFromRole({{ $focal['id'] }}, 'focal')" class="text-orange-400 hover:text-red-500"><svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
                            </span>
                        @empty
                            <span class="text-[10px] text-gray-400 italic">None selected.</span>
                        @endforelse
                    </div>
                </div>

                {{-- Participants --}}
                <div>
                    <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 flex justify-between">Participants <span class="bg-gray-100 px-2 py-0.5 rounded-full">{{ count($selectedParticipants) }}</span></h4>
                    <div class="flex flex-wrap gap-2 max-h-32 overflow-y-auto custom-scrollbar">
                        @forelse($selectedParticipants as $participant)
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold bg-blue-50 text-blue-700 border border-blue-100">
                                {{ $participant['name'] }}
                                <button wire:click.prevent="removeUserFromRole({{ $participant['id'] }}, 'participant')" class="text-blue-400 hover:text-red-500"><svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
                            </span>
                        @empty
                            <span class="text-[10px] text-gray-400 italic">None selected.</span>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        {{-- ======================== --}}
        {{-- RIGHT PANEL: LIVE PREVIEW--}}
        {{-- ======================== --}}
        @php
            // Prepare preview data safely
            $previewTitle = $title ?: 'Activity Title';
            $previewNature = $nature_of_activity ?: 'Nature';
            $previewDesc = $description ?: 'Detailed description for this activity is currently being updated.';
            $previewStartDate = $start_date ? \Carbon\Carbon::parse($start_date)->format('F d, Y') : 'Date';
            $previewEndDate = $end_date ? \Carbon\Carbon::parse($end_date)->format('F d, Y') : null;

            // Build Cover Image Array for Carousel
            $previewImages = [];
            foreach($existing_photos as $ep) { $previewImages[] = asset('storage/' . $ep); }
            if ($photos) { foreach($photos as $p) { $previewImages[] = $p->temporaryUrl(); } }
        @endphp

        <div class="w-full md:w-7/12 h-full overflow-y-auto bg-stone-100 relative shadow-inner" :class="mobilePreview ? 'block' : 'hidden md:block'">

             <div class="absolute top-4 right-4 z-50 bg-black/80 text-white text-[10px] font-bold uppercase px-3 py-1 rounded-full backdrop-blur pointer-events-none shadow-md">
                Live Preview
            </div>

            <div class="min-h-full bg-stone-50 pb-20 origin-top pointer-events-none select-none">

                {{-- Preview: Hero Section --}}
                <div class="w-full relative bg-gray-900 overflow-hidden flex" style="max-height: 400px;">
                    @if(count($previewImages) > 0)
                        <div class="w-full h-full flex overflow-hidden">
                            <div class="shrink-0 w-full h-[300px] sm:h-[400px] relative">
                                <img src="{{ $previewImages[0] }}" class="w-full h-full object-cover opacity-90">
                                <div class="absolute inset-0 bg-gradient-to-t from-gray-900 via-gray-900/40 to-transparent"></div>
                            </div>
                        </div>
                        @if(count($previewImages) > 1)
                            <div class="absolute bottom-6 right-6">
                                <span class="bg-black/50 backdrop-blur-md text-white text-[9px] font-black uppercase tracking-widest px-3 py-1 rounded-full shadow-lg border border-white/20">
                                    +{{ count($previewImages) - 1 }} Photos
                                </span>
                            </div>
                        @endif
                    @else
                        <div class="w-full h-[250px] md:h-[350px] bg-gradient-to-br from-gray-800 to-gray-900 flex items-center justify-center">
                            <span class="text-gray-600 font-bold uppercase tracking-widest text-xs">No Photos Uploaded</span>
                        </div>
                    @endif

                    <div class="absolute bottom-0 inset-x-0 p-6 md:p-10 z-10">
                        <div class="flex flex-wrap items-center gap-2 mb-3">
                            <span class="px-2.5 py-1 text-[9px] font-black uppercase tracking-widest rounded-lg bg-white/20 backdrop-blur-md text-white border border-white/30">{{ $status }}</span>
                            <span class="px-2.5 py-1 text-[9px] font-black uppercase tracking-widest rounded-lg bg-red-600 text-white shadow-sm">{{ $previewNature }}</span>
                        </div>
                        <h1 class="text-3xl md:text-5xl font-black text-white leading-tight drop-shadow-lg mb-2">{{ $previewTitle }}</h1>
                        <p class="text-gray-300 text-sm font-medium flex items-center gap-2 drop-shadow-md">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            {{ $previewStartDate }} {{ $previewEndDate && $previewEndDate != $previewStartDate ? '- ' . $previewEndDate : '' }}
                        </p>
                    </div>
                </div>

                {{-- Preview: Body Grid --}}
                <div class="p-6 md:p-10 grid grid-cols-1 lg:grid-cols-3 gap-10 max-w-5xl mx-auto">

                    <div class="lg:col-span-2 space-y-10">
                        <div>
                            <h3 class="text-xs font-black text-gray-400 uppercase tracking-widest mb-4 border-b border-gray-100 pb-2">Activity Overview</h3>
                            <div class="text-sm md:text-base text-gray-700 leading-relaxed whitespace-pre-line">{{ $previewDesc }}</div>
                        </div>

                        @if($lead_organization)
                            <div class="bg-blue-50/50 rounded-2xl p-6 border border-blue-100">
                                <h4 class="text-[10px] font-black text-blue-500 uppercase tracking-widest mb-1">Lead Coordinating Entity</h4>
                                <p class="text-sm font-bold text-gray-900">{{ $lead_organization }}</p>
                            </div>
                        @endif

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-8 pt-6 border-t border-gray-100">
                            @if(count($selectedFocals) > 0)
                                <div>
                                    <h3 class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-4">Lead Focals</h3>
                                    <ul class="space-y-3">
                                        @foreach($selectedFocals as $focal)
                                            <li class="flex items-center gap-3">
                                                <div class="w-8 h-8 rounded-full bg-orange-100 border border-orange-200 flex items-center justify-center text-[10px] font-bold text-orange-700">{{ substr($focal['name'], 0, 2) }}</div>
                                                <p class="text-xs font-bold text-gray-900">{{ $focal['name'] }}</p>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            @if(count($selectedParticipants) > 0)
                                <div>
                                    <h3 class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-4">Participants</h3>
                                    <div class="flex flex-wrap gap-2">
                                        @foreach($selectedParticipants as $participant)
                                            <span class="px-2 py-1 bg-gray-100 text-gray-700 text-[10px] font-bold rounded-md border border-gray-200">{{ $participant['name'] }}</span>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="space-y-6">
                        <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-sm flex flex-col items-center text-center">
                            <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-3">Initiated By</p>
                            <div class="w-16 h-16 rounded-full bg-red-100 border border-red-200 flex items-center justify-center text-red-600 font-bold mb-3">{{ substr(Auth::user()->name, 0, 2) }}</div>
                            <h4 class="text-sm font-black text-gray-900">{{ Auth::user()->name }}</h4>
                        </div>

                        {{-- Multiple SDG Preview Cards --}}
                        {{-- Multiple SDG Preview Cards --}}
                        @if(count($selectedSdgs) > 0)
                            <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-sm mt-6">
                                <h4 class="font-bold text-gray-900 uppercase tracking-widest text-[10px] border-b border-gray-100 pb-3 mb-4">
                                    Sustainable Development Goals
                                </h4>
                                
                                <div class="space-y-3">
                                    @foreach($selectedSdgs as $id)
                                        @php $sdg = $sdgs->find($id); @endphp
                                        @if($sdg)
                                            {{-- Horizontal Pill Design --}}
                                            <div class="flex items-stretch rounded-xl border overflow-hidden shadow-sm" 
                                                 style="background-color: {{ $sdg->color_hex }}15; border-color: {{ $sdg->color_hex }}40;">
                                                
                                                {{-- Left Side: Colored Number Block --}}
                                                <div class="w-14 shrink-0 flex items-center justify-center text-white font-black text-lg"
                                                     style="background-color: {{ $sdg->color_hex }}">
                                                    {{ $sdg->number }}
                                                </div>

                                                {{-- Right Side: Tinted Text Block --}}
                                                <div class="flex items-center px-4 py-3 flex-1">
                                                    <span class="text-[10px] font-black uppercase tracking-wider leading-tight"
                                                          style="color: {{ $sdg->color_hex }}">
                                                        {{ $sdg->name }}
                                                    </span>
                                                </div>
                                            </div>
                                        @endif
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
