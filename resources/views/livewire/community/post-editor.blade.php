<div class="bg-white min-h-screen animate-fade-in-up flex flex-col relative">

    {{-- 1. STICKY TOP HEADER (Matches Feed & Post View) --}}
    <div class="sticky top-[64px] lg:top-0 z-40 bg-white/90 backdrop-blur-md border-b border-gray-100 px-4 md:px-6 py-3 flex items-center gap-4">
        <a href="{{ route('community.feed') }}" class="p-2 -ml-2 rounded-full hover:bg-gray-100 transition-colors text-gray-800">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        </a>
        <h2 class="text-lg font-black text-gray-900 tracking-tight">{{ $postRecord ? 'Edit Post' : 'Create Post' }}</h2>
    </div>

    {{-- 2. COMMUNITY GUIDELINES (Only on Create) --}}
    @if(!$postRecord)
        <div class="px-4 md:px-6 pt-6">
            <div class="bg-blue-50 border border-blue-100 rounded-2xl p-4 flex items-start gap-3">
                <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center shrink-0 mt-0.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <div>
                    <h4 class="text-xs font-black text-blue-900 uppercase tracking-widest">Community Guidelines</h4>
                    <p class="text-xs font-medium text-blue-700 mt-1 leading-relaxed">Hate speech, misinformation, or explicit material will be removed, and posting privileges may be revoked.</p>
                </div>
            </div>
        </div>
    @endif

    {{-- 3. EDGE-TO-EDGE COVER PHOTO --}}
    <div class="relative w-full h-48 md:h-64 bg-gray-50 border-y border-gray-100 flex flex-col items-center justify-center group transition-colors hover:bg-gray-100 mt-6 cursor-pointer">
        @if($cover_image)
            <img src="{{ $cover_image->temporaryUrl() }}" class="absolute inset-0 w-full h-full object-cover">
            <div class="absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                <button wire:click.stop="removeCover" type="button" class="px-4 py-2 bg-red-600 text-white text-xs font-black uppercase tracking-widest rounded-xl shadow-sm hover:bg-red-700 transition-colors z-20">Remove Cover</button>
            </div>
        @elseif($existing_cover)
            <img src="{{ asset('storage/'.$existing_cover) }}" class="absolute inset-0 w-full h-full object-cover">
            <div class="absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                <button wire:click.stop="removeCover" type="button" class="px-4 py-2 bg-red-600 text-white text-xs font-black uppercase tracking-widest rounded-xl shadow-sm hover:bg-red-700 transition-colors z-20">Remove Cover</button>
            </div>
        @else
            <svg class="w-8 h-8 text-gray-300 mb-2 group-hover:text-blue-400 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
            <span class="text-xs font-bold text-gray-400 uppercase tracking-widest group-hover:text-blue-600 transition-colors">Add Cover Photo</span>
            <input type="file" wire:model="cover_image" accept="image/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10 title="Upload Cover Photo">
        @endif
        <div wire:loading wire:target="cover_image" class="absolute bottom-4 left-1/2 -translate-x-1/2 bg-gray-900 text-white text-[10px] font-black uppercase tracking-widest px-4 py-2 rounded-full animate-pulse z-20 shadow-md">Uploading...</div>
    </div>
    @error('cover_image') <div class="px-4 py-2 bg-red-50 text-red-600 text-[10px] font-bold text-center border-b border-red-100">{{ $message }}</div> @enderror

    {{-- 4. SEAMLESS EDITOR CONTENT --}}
    <div class="flex-1 px-4 md:px-6 py-6 flex flex-col gap-6">

        {{-- Title Input (Seamless Medium Style) --}}
        <div>
            <input wire:model="title" type="text" placeholder="Title" class="w-full bg-transparent border-0 border-b border-transparent hover:border-gray-100 focus:border-gray-200 focus:ring-0 outline-none transition-all px-0 py-2 text-3xl md:text-4xl font-black text-gray-900 placeholder:text-gray-300">
            @error('title') <span class="text-[10px] text-red-500 font-bold block mt-1">{{ $message }}</span> @enderror
        </div>

        {{-- Category Selection --}}
        <div class="flex items-center gap-3 border-b border-gray-100 pb-4">
            <label class="text-xs font-bold text-gray-400 uppercase tracking-widest shrink-0">Category:</label>
            <select wire:model="category_id" class="flex-1 bg-transparent border-0 focus:ring-0 outline-none text-sm font-bold text-blue-600 cursor-pointer p-0">
                <option value="" class="text-gray-900">General (Uncategorized)</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" class="text-gray-900">{{ $category->name }}</option>
                @endforeach
            </select>
        </div>
        @error('category_id') <span class="text-[10px] text-red-500 font-bold block -mt-4">{{ $message }}</span> @enderror

        {{-- Main Body Textarea --}}
        <div class="flex-1 min-h-[300px]">
            <textarea wire:model="content" placeholder="Tell your story..." class="w-full h-full bg-transparent border-0 focus:ring-0 outline-none resize-y px-0 py-2 text-[16px] md:text-[17px] text-gray-800 leading-[1.8] font-medium placeholder:text-gray-300"></textarea>
            @error('content') <span class="text-[10px] text-red-500 font-bold block mt-1">{{ $message }}</span> @enderror
        </div>

        {{-- Media Gallery Uploader --}}
        <div class="bg-gray-50 rounded-2xl p-4 md:p-5 border border-gray-100 mb-6">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h4 class="text-xs font-black text-gray-900 uppercase tracking-widest">Photo Gallery</h4>
                    <p class="text-[10px] font-bold text-gray-500 mt-0.5">Attach up to 4 images</p>
                </div>
                <div class="relative">
                    <input type="file" wire:model="gallery_uploads" multiple accept="image/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" @if(count($existing_gallery) + count($gallery_uploads) >= 4) disabled @endif>
                    <button type="button" class="px-3 py-1.5 bg-white border border-gray-200 rounded-lg shadow-sm text-[11px] font-black text-gray-700 hover:bg-gray-50 transition-colors uppercase tracking-widest {{ count($existing_gallery) + count($gallery_uploads) >= 4 ? 'opacity-50 cursor-not-allowed' : '' }}">
                        + Add Photos
                    </button>
                </div>
            </div>

            <div wire:loading wire:target="gallery_uploads" class="text-[10px] font-black text-blue-600 uppercase tracking-widest mb-4 animate-pulse">Processing...</div>
            @error('gallery_uploads.*') <span class="text-[10px] text-red-500 font-bold block mb-4">{{ $message }}</span> @enderror
            @error('gallery_uploads') <span class="text-[10px] text-red-500 font-bold block mb-4">{{ $message }}</span> @enderror

            <div class="grid grid-cols-4 gap-2 md:gap-3">
                {{-- Existing & New Gallery Items --}}
                @foreach($existing_gallery as $index => $path)
                    <div class="relative aspect-square rounded-xl overflow-hidden border border-gray-200 group bg-white">
                        <img src="{{ asset('storage/'.$path) }}" class="w-full h-full object-cover">
                        <button wire:click="removeExistingGalleryItem({{ $index }})" type="button" class="absolute top-1 right-1 w-6 h-6 bg-white/90 text-red-600 rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity shadow-sm hover:bg-red-600 hover:text-white"><svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path></svg></button>
                    </div>
                @endforeach

                @foreach($gallery_uploads as $index => $photo)
                    <div class="relative aspect-square rounded-xl overflow-hidden border-2 border-blue-400 group bg-white">
                        <img src="{{ $photo->temporaryUrl() }}" class="w-full h-full object-cover">
                        <button wire:click="removeNewGalleryItem({{ $index }})" type="button" class="absolute top-1 right-1 w-6 h-6 bg-white/90 text-red-600 rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity shadow-sm hover:bg-red-600 hover:text-white"><svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path></svg></button>
                    </div>
                @endforeach

                {{-- Empty State Placeholders --}}
                @php $remainingSlots = 4 - (count($existing_gallery) + count($gallery_uploads)); @endphp
                @for($i = 0; $i < $remainingSlots; $i++)
                    <div class="aspect-square rounded-xl border border-dashed border-gray-200 bg-gray-50/50"></div>
                @endfor
            </div>
        </div>
    </div>

    {{-- 5. SCOPED STICKY BOTTOM BAR --}}
    <div class="sticky bottom-0 left-0 right-0 z-50 bg-white/90 backdrop-blur-md border-t border-gray-100 p-4 md:px-6 md:py-4 flex items-center justify-between gap-4 mt-auto shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.02)]">
        
        {{-- Status Toggle --}}
        <div class="flex items-center gap-2.5 cursor-pointer group" wire:click="$toggle('is_published')">
            <div class="relative w-10 h-5 md:w-11 md:h-6 rounded-full transition-colors duration-300 {{ $is_published ? 'bg-green-500' : 'bg-gray-300' }}">
                <div class="absolute top-[2px] left-[2px] bg-white w-4 h-4 md:w-5 md:h-5 rounded-full transition-transform duration-300 shadow-sm {{ $is_published ? 'translate-x-5' : 'translate-x-0' }}"></div>
            </div>
            <p class="text-[10px] md:text-xs font-black text-gray-700 uppercase tracking-widest group-hover:text-gray-900 transition-colors">{{ $is_published ? 'Publish Now' : 'Save Draft' }}</p>
        </div>

        {{-- Submit Button --}}
        <button wire:click="savePost" wire:loading.attr="disabled" class="px-5 py-2.5 bg-gray-900 hover:bg-blue-600 text-white text-xs font-black uppercase tracking-widest rounded-full shadow-sm transition-all active:scale-95 flex items-center gap-2">
            <span wire:loading.remove wire:target="savePost, cover_image, gallery_uploads">{{ $postRecord ? 'Update' : 'Post' }}</span>
            <span wire:loading wire:target="savePost">Saving...</span>
            <span wire:loading wire:target="cover_image, gallery_uploads">Uploading...</span>
        </button>
    </div>

</div>