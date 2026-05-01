<div class="max-w-5xl mx-auto py-8 md:py-12 px-4 sm:px-6 font-sans pb-40 animate-fade-in-up">

    {{-- Header & Community Guidelines --}}
    <div class="mb-8">
        <a href="{{ route('community.feed') }}" class="inline-flex items-center gap-2 text-xs font-black text-gray-500 uppercase tracking-widest hover:text-blue-600 transition-colors mb-6 group">
            <svg class="w-4 h-4 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Back to Feed
        </a>
        <h1 class="text-3xl md:text-4xl font-black text-gray-900 tracking-tight">{{ $postRecord ? 'Edit Post' : 'Create a New Post' }}</h1>
        <p class="text-gray-500 font-medium mt-2">Share your advocacy, updates, or insights with the BU MADYA community.</p>

        @if(!$postRecord)
            <div class="mt-6 bg-blue-50 border border-blue-200 rounded-2xl p-4 md:p-5 flex items-start gap-4 shadow-sm">
                <div class="w-10 h-10 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <div>
                    <h4 class="text-sm font-black text-blue-900">Community Guidelines</h4>
                    <p class="text-xs font-medium text-blue-700 mt-1 leading-relaxed">Ensure your post aligns with our organizational values. Content containing hate speech, misinformation, or explicit material will be removed, and posting privileges may be revoked.</p>
                </div>
            </div>
        @endif
    </div>

    {{-- Editor Form --}}
    <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-200 overflow-hidden">

        {{-- Cover Photo Upload --}}
        <div class="relative w-full min-h-[16rem] bg-gray-50 border-b border-gray-200 flex flex-col items-center justify-center group transition-colors hover:bg-gray-100">
            @if($cover_image)
                <img src="{{ $cover_image->temporaryUrl() }}" class="absolute inset-0 w-full h-full object-cover">
                <div class="absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                    <button wire:click="removeCover" type="button" class="px-4 py-2 bg-red-600 text-white text-xs font-black uppercase tracking-widest rounded-xl shadow-lg hover:bg-red-700 transition-colors">Remove Cover</button>
                </div>
            @elseif($existing_cover)
                <img src="{{ asset('storage/'.$existing_cover) }}" class="absolute inset-0 w-full h-full object-cover">
                <div class="absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                    <button wire:click="removeCover" type="button" class="px-4 py-2 bg-red-600 text-white text-xs font-black uppercase tracking-widest rounded-xl shadow-lg hover:bg-red-700 transition-colors">Remove Cover</button>
                </div>
            @else
                <svg class="w-10 h-10 text-gray-300 mb-3 group-hover:text-blue-400 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                <span class="text-sm font-bold text-gray-400 group-hover:text-blue-600 transition-colors">Click to upload a cover photo</span>
                <input type="file" wire:model="cover_image" accept="image/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
            @endif
            <div wire:loading wire:target="cover_image" class="absolute bottom-4 left-1/2 -translate-x-1/2 bg-gray-900 text-white text-[10px] font-black uppercase tracking-widest px-4 py-2 rounded-full animate-pulse z-20 shadow-lg">Uploading...</div>
        </div>
        @error('cover_image') <div class="p-3 bg-red-50 text-red-600 text-[10px] font-bold text-center border-b border-red-100">{{ $message }}</div> @enderror

        <div class="p-6 md:p-10">

            {{-- Title & Category --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="md:col-span-2">
                    <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">Post Title <span class="text-red-500">*</span></label>
                    <input wire:model="title" type="text" placeholder="What's on your mind?" class="w-full bg-gray-50 border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 outline-none transition-all rounded-xl px-4 py-3 text-lg font-black text-gray-900 placeholder:text-gray-400 shadow-sm">
                    @error('title') <span class="text-[10px] text-red-500 font-bold block mt-1">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">Category</label>
                    <select wire:model="category_id" class="w-full bg-gray-50 border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 outline-none transition-all rounded-xl px-4 py-3 text-sm font-bold text-gray-700 shadow-sm">
                        <option value="">General (Uncategorized)</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                    @error('category_id') <span class="text-[10px] text-red-500 font-bold block mt-1">{{ $message }}</span> @enderror
                </div>
            </div>

            {{-- Main Content --}}
            <div class="mb-8">
                <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">Content <span class="text-red-500">*</span></label>
                <textarea wire:model="content" rows="12" placeholder="Write your full story or update here..." class="w-full bg-white border border-gray-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 outline-none transition-all rounded-2xl px-5 py-4 text-base text-gray-800 leading-relaxed resize-y shadow-inner"></textarea>
                @error('content') <span class="text-[10px] text-red-500 font-bold block mt-1">{{ $message }}</span> @enderror
            </div>

            {{-- Media Gallery (Max 4) --}}
            <div class="bg-gray-50 rounded-2xl p-6 border border-gray-200">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h4 class="text-sm font-black text-gray-900 uppercase tracking-wider">Photo Gallery</h4>
                        <p class="text-[10px] font-bold text-gray-500 uppercase mt-0.5">Attach up to 4 additional images</p>
                    </div>
                    <div class="relative">
                        <input type="file" wire:model="gallery_uploads" multiple accept="image/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" @if(count($existing_gallery) + count($gallery_uploads) >= 4) disabled @endif>
                        <button type="button" class="px-4 py-2 bg-white border border-gray-300 rounded-xl shadow-sm text-xs font-bold text-gray-700 hover:bg-gray-100 transition-colors {{ count($existing_gallery) + count($gallery_uploads) >= 4 ? 'opacity-50 cursor-not-allowed' : '' }}">
                            + Browse Photos
                        </button>
                    </div>
                </div>

                <div wire:loading wire:target="gallery_uploads" class="text-[10px] font-black text-blue-600 uppercase tracking-widest mb-4 animate-pulse">Processing uploads...</div>
                @error('gallery_uploads.*') <span class="text-[10px] text-red-500 font-bold block mb-4">{{ $message }}</span> @enderror
                @error('gallery_uploads') <span class="text-[10px] text-red-500 font-bold block mb-4">{{ $message }}</span> @enderror

                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    {{-- Existing Gallery Images --}}
                    @foreach($existing_gallery as $index => $path)
                        <div class="relative aspect-square rounded-xl overflow-hidden border border-gray-200 group shadow-sm bg-white">
                            <img src="{{ asset('storage/'.$path) }}" class="w-full h-full object-cover">
                            <button wire:click="removeExistingGalleryItem({{ $index }})" type="button" class="absolute top-2 right-2 w-7 h-7 bg-white/90 text-red-600 rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity shadow-sm hover:bg-red-600 hover:text-white">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                        </div>
                    @endforeach

                    {{-- Newly Uploaded Preview Images --}}
                    @foreach($gallery_uploads as $index => $photo)
                        <div class="relative aspect-square rounded-xl overflow-hidden border-2 border-blue-400 group shadow-sm bg-white">
                            <img src="{{ $photo->temporaryUrl() }}" class="w-full h-full object-cover">
                            <div class="absolute inset-0 ring-4 ring-inset ring-blue-400/20 pointer-events-none"></div>
                            <button wire:click="removeNewGalleryItem({{ $index }})" type="button" class="absolute top-2 right-2 w-7 h-7 bg-white/90 text-red-600 rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity shadow-sm hover:bg-red-600 hover:text-white">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                        </div>
                    @endforeach

                    {{-- Empty State Placeholders --}}
                    @php $remainingSlots = 4 - (count($existing_gallery) + count($gallery_uploads)); @endphp
                    @for($i = 0; $i < $remainingSlots; $i++)
                        <div class="aspect-square rounded-xl border-2 border-dashed border-gray-200 flex items-center justify-center bg-gray-50/50">
                            <svg class="w-6 h-6 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        </div>
                    @endfor
                </div>
            </div>

        </div>
    </div>

    {{-- FIXED SUBMIT BAR --}}
    <div class="fixed bottom-0 left-0 right-0 z-50 p-4 md:p-6 bg-gradient-to-t from-white via-white/95 to-transparent pointer-events-none">
        <div class="max-w-5xl mx-auto flex items-center justify-between gap-4 pointer-events-auto">

            {{-- Status Toggle --}}
            <div class="bg-white px-4 py-2 md:px-5 md:py-3 rounded-[1.5rem] shadow-lg border border-gray-200 flex items-center gap-3 cursor-pointer" wire:click="$toggle('is_published')">
                <div class="relative w-12 h-6 md:w-14 md:h-7 rounded-full transition-colors duration-300 {{ $is_published ? 'bg-green-500' : 'bg-gray-300' }}">
                    <div class="absolute top-1 left-1 bg-white w-4 h-4 md:w-5 md:h-5 rounded-full transition-transform duration-300 shadow-sm {{ $is_published ? 'translate-x-6 md:translate-x-7' : 'translate-x-0' }}"></div>
                </div>
                <div>
                    <p class="text-[10px] md:text-xs font-black text-gray-900 uppercase tracking-widest">{{ $is_published ? 'Publish Immediately' : 'Save as Draft' }}</p>
                </div>
            </div>

            {{-- Submit Button --}}
            <button wire:click="savePost" wire:loading.attr="disabled" class="px-8 py-3 md:py-4 bg-gray-900 hover:bg-blue-600 text-white font-black rounded-[1.5rem] shadow-xl transition-all transform active:scale-95 flex items-center gap-3 outline-none focus:ring-4 focus:ring-blue-500/50">
                <span wire:loading.remove wire:target="savePost, cover_image, gallery_uploads">{{ $postRecord ? 'Update Post' : 'Submit Post' }}</span>
                <span wire:loading wire:target="savePost">Saving Database...</span>
                <span wire:loading wire:target="cover_image, gallery_uploads">Uploading Media...</span>
                <svg wire:loading.remove wire:target="savePost, cover_image, gallery_uploads" class="w-5 h-5 hidden md:block" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
            </button>
        </div>
    </div>
</div>
