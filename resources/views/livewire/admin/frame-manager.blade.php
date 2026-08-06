<div class="p-6 max-w-7xl mx-auto min-h-screen">

    {{-- Header & Filters --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-4 mb-8">
        <div>
            <h1 class="text-3xl font-black text-gray-900 leading-tight">Frame Approvals</h1>
            <p class="text-sm font-bold text-gray-500 mt-1">Review and publish Twibbonize campaign frames.</p>
        </div>

        <div class="flex gap-2 w-full md:w-auto">
            <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search frames or orgs..." class="rounded-xl border-gray-200 text-sm focus:ring-red-500 w-full md:w-64">
            <select wire:model.live="filter" class="rounded-xl border-gray-200 text-sm focus:ring-red-500 font-bold text-gray-600">
                <option value="all">All Submissions</option>
                <option value="pending">Pending Only</option>
                <option value="approved">Approved</option>
            </select>
        </div>
    </div>

    {{-- Flash Message --}}
    @if (session()->has('message'))
        <div class="bg-green-50 text-green-700 px-4 py-3 rounded-xl font-bold text-sm mb-6 border border-green-200 animate-fade-in-down">
            {{ session('message') }}
        </div>
    @endif

    {{-- Frames Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @forelse($frames as $frame)
            @php
                // Safely grab the array of images, or fallback to the old single image format
                $images = is_array($frame->frame_images) ? $frame->frame_images : (empty($frame->frame_image) ? [] : [$frame->frame_image]);
                $firstImage = count($images) > 0 ? asset('storage/' . $images[0]) : '';
            @endphp

            {{-- [NEW] Inject Alpine data to track which variation is currently being previewed --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden flex flex-col group"
                 x-data="{ activePreview: '{{ $firstImage }}' }">
                
                {{-- Main Preview Area (Checkerboard Background) --}}
                <div class="relative w-full aspect-square bg-[url('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAAMUlEQVQ4T2NkYNgBxVD8nwEPsOEHMBqNhsFhAAfLwcAAYf///z8DHgZQDw1DDEAGDAAASgIdX/3i4QAAAABJRU5ErkJggg==')] bg-repeat border-b border-gray-100">
                    
                    {{-- [UPDATED] Image binds to Alpine's activePreview --}}
                    <img :src="activePreview" class="absolute inset-0 w-full h-full object-contain p-4 transition-opacity duration-300">
                    
                    {{-- Status Badge Overlay --}}
                    <div class="absolute top-3 right-3 z-10">
                        @if($frame->is_approved)
                            <span class="bg-green-500 text-white text-[10px] font-black uppercase tracking-widest px-2 py-1 rounded-md shadow-sm">Published</span>
                        @else
                            <span class="bg-yellow-500 text-white text-[10px] font-black uppercase tracking-widest px-2 py-1 rounded-md shadow-sm">Pending</span>
                        @endif
                    </div>
                </div>

                {{-- [NEW] Mini-Gallery for Variations (Only shows if there is more than 1 image) --}}
                @if(count($images) > 1)
                    <div class="px-4 pt-3 flex gap-2 overflow-x-auto pb-1 scrollbar-hide">
                        @foreach($images as $index => $img)
                            @php $imgUrl = asset('storage/' . $img); @endphp
                            
                            <button @click="activePreview = '{{ $imgUrl }}'"
                                    :class="activePreview === '{{ $imgUrl }}' ? 'ring-2 ring-red-500 ring-offset-1' : 'border border-gray-200 opacity-50 hover:opacity-100'"
                                    class="w-10 h-10 rounded-lg bg-[url('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAAMUlEQVQ4T2NkYNgBxVD8nwEPsOEHMBqNhsFhAAfLwcAAYf///z8DHgZQDw1DDEAGDAAASgIdX/3i4QAAAABJRU5ErkJggg==')] bg-repeat flex-shrink-0 transition-all overflow-hidden focus:outline-none">
                                <img src="{{ $imgUrl }}" class="w-full h-full object-contain p-0.5" title="Variation {{ $index + 1 }}">
                            </button>
                        @endforeach
                    </div>
                @endif

                <div class="p-4 flex-1 flex flex-col justify-between">
                    <div>
                        <div class="flex items-start justify-between gap-2">
                            <h3 class="font-bold text-gray-900 truncate" title="{{ $frame->title }}">{{ $frame->title }}</h3>
                            @if(count($images) > 1)
                                <span class="bg-gray-100 text-gray-500 text-[9px] font-bold px-1.5 py-0.5 rounded shrink-0">{{ count($images) }} Vars</span>
                            @endif
                        </div>
                        <p class="text-xs text-gray-500 mt-1 truncate">By: {{ $frame->user->name ?? 'Unknown' }}</p>
                    </div>

                    <div class="mt-4 pt-4 border-t border-gray-100 flex items-center justify-between gap-2">
    
                        <button wire:click="toggleApproval({{ $frame->id }})" 
                                class="flex-1 text-xs font-bold uppercase tracking-widest px-3 py-2 rounded-lg transition
                                {{ $frame->is_approved ? 'bg-orange-50 text-orange-700 hover:bg-orange-100' : 'bg-gray-900 text-white hover:bg-gray-800' }}">
                            {{ $frame->is_approved ? 'Revoke' : 'Approve' }}
                        </button>
                        
                        <a href="{{ route('open.frames.show', $frame->slug) }}" target="_blank" class="p-2 text-gray-400 hover:text-blue-600 bg-gray-50 hover:bg-blue-50 rounded-lg transition" title="Preview Public Page">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                        </a>
                    
                        {{-- NEW: Edit Button --}}
                        <button wire:click="editFrame({{ $frame->id }})" class="p-2 text-gray-400 hover:text-orange-600 bg-gray-50 hover:bg-orange-50 rounded-lg transition" title="Edit Metadata">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                        </button>
                    
                        <button wire:click="deleteFrame({{ $frame->id }})" wire:confirm="Delete this campaign and all variations permanently?" class="p-2 text-gray-400 hover:text-red-600 bg-gray-50 hover:bg-red-50 rounded-lg transition" title="Delete">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        </button>
                    </div>
                    
                    {{-- Add this Edit Modal right before the closing </div> of the main view --}}
                    @if($editMode)
                    <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-gray-900/40 backdrop-blur-sm" x-data @keydown.escape.window="$wire.cancelEdit()">
                        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-xl overflow-hidden animate-fade-in-up max-h-[90vh] flex flex-col">
                            
                            <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50/50 shrink-0">
                                <h3 class="font-black text-gray-900">Edit Campaign</h3>
                                <button wire:click="cancelEdit" class="text-gray-400 hover:text-red-500 transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
                                </button>
                            </div>
                    
                            <div class="p-6 overflow-y-auto space-y-6">
                                
                                {{-- Metadata Section --}}
                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest mb-1.5">Campaign Title</label>
                                        <input wire:model="editTitle" type="text" class="w-full rounded-xl border-gray-200 text-sm focus:ring-orange-500 focus:border-orange-500 font-medium text-gray-700 shadow-sm">
                                        @error('editTitle') <span class="text-xs text-red-500 font-bold mt-1 block">{{ $message }}</span> @enderror
                                    </div>
                                    
                                    <div>
                                        <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest mb-1.5">Description</label>
                                        <textarea wire:model="editDescription" rows="2" class="w-full rounded-xl border-gray-200 text-sm focus:ring-orange-500 focus:border-orange-500 font-medium text-gray-700 shadow-sm"></textarea>
                                        @error('editDescription') <span class="text-xs text-red-500 font-bold mt-1 block">{{ $message }}</span> @enderror
                                    </div>
                                    
                                    <div>
                                        <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest mb-1.5">Official Caption</label>
                                        <textarea wire:model="editCaption" rows="3" class="w-full rounded-xl border-gray-200 text-sm focus:ring-orange-500 focus:border-orange-500 font-medium text-gray-700 shadow-sm"></textarea>
                                        @error('editCaption') <span class="text-xs text-red-500 font-bold mt-1 block">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                    
                                {{-- Variations Section --}}
                                <div class="border-t border-gray-100 pt-5">
                                    <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest mb-3">Frame Variations Sequence</label>
                                    
                                    @error('editImages') 
                                        <div class="bg-red-50 text-red-600 px-3 py-2 rounded-lg text-xs font-bold mb-4 border border-red-100">
                                            {{ $message }}
                                        </div>
                                    @enderror
                    
                                    <div class="space-y-2 mb-4">
                                        @foreach($editImages as $index => $path)
                                            <div class="flex items-center gap-4 bg-gray-50 p-2.5 rounded-xl border border-gray-100 transition-all hover:border-gray-200 hover:shadow-sm">
                                                <div class="w-14 h-14 bg-[url('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAAMUlEQVQ4T2NkYNgBxVD8nwEPsOEHMBqNhsFhAAfLwcAAYf///z8DHgZQDw1DDEAGDAAASgIdX/3i4QAAAABJRU5ErkJggg==')] bg-repeat rounded-lg overflow-hidden shrink-0 border border-gray-200">
                                                    <img src="{{ asset('storage/' . $path) }}" class="w-full h-full object-contain p-1">
                                                </div>
                    
                                                <div class="flex-1 flex items-center justify-between">
                                                    <span class="text-xs font-bold text-gray-400">Layer {{ $index + 1 }}</span>
                    
                                                    <div class="flex items-center gap-1">
                                                        <button wire:click.prevent="moveImageUp({{ $index }})" @if($index === 0) disabled @endif class="p-1.5 rounded-md text-gray-400 hover:bg-white hover:text-gray-900 hover:shadow-sm disabled:opacity-30 disabled:hover:bg-transparent disabled:hover:shadow-none transition-all">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 15l7-7 7 7"></path></svg>
                                                        </button>
                                                        <button wire:click.prevent="moveImageDown({{ $index }})" @if($index === count($editImages) - 1) disabled @endif class="p-1.5 rounded-md text-gray-400 hover:bg-white hover:text-gray-900 hover:shadow-sm disabled:opacity-30 disabled:hover:bg-transparent disabled:hover:shadow-none transition-all">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"></path></svg>
                                                        </button>
                                                        <div class="w-px h-5 bg-gray-200 mx-1.5"></div>
                                                        <button wire:click.prevent="removeImage({{ $index }})" class="p-1.5 rounded-md text-gray-400 hover:bg-red-50 hover:text-red-500 transition-all">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                    
                                    {{-- Upload New Trigger --}}
                                    <div class="flex items-center justify-between mt-2">
                                        <label class="cursor-pointer inline-flex items-center gap-2 px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-[10px] font-black uppercase tracking-widest rounded-xl transition-colors border border-gray-200 shadow-sm">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                            Add Variations
                                            <input type="file" wire:model="newUploads" multiple accept="image/png" class="hidden">
                                        </label>
                    
                                        <div wire:loading wire:target="newUploads" class="text-[10px] font-black uppercase tracking-widest text-orange-500 flex items-center gap-2">
                                            <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                            </svg>
                                            Uploading...
                                        </div>
                                    </div>
                    
                                </div>
                            </div>
                    
                            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50 flex justify-end gap-3 shrink-0">
                                <button wire:click="cancelEdit" class="px-5 py-2.5 text-xs font-black uppercase tracking-widest text-gray-600 bg-white border border-gray-200 rounded-xl hover:bg-gray-50 transition-colors shadow-sm">Cancel</button>
                                <button wire:click="updateFrame" class="px-5 py-2.5 text-xs font-black uppercase tracking-widest text-white bg-orange-500 rounded-xl hover:bg-orange-600 transition-colors shadow-lg shadow-orange-500/20">Save Changes</button>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>

            </div>
        @empty
            <div class="col-span-full py-12 text-center text-gray-400 font-bold border-2 border-dashed border-gray-200 rounded-2xl">
                No frames found matching your criteria.
            </div>
        @endforelse
    </div>

    <div class="mt-6">
        {{ $frames->links() }}
    </div>
</div>
