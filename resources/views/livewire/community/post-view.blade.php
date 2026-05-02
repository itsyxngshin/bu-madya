@section('meta_title', e($post->title))
@section('meta_description', e(\Illuminate\Support\Str::limit(strip_tags($post->content), 150)))

@if($post->cover_image_path)
    @section('meta_image', asset('storage/'.$post->cover_image_path))
@elseif(is_array($post->gallery) && count($post->gallery) > 0)
    @section('meta_image', asset('storage/'.$post->gallery[0]))
@endif

<div x-data="{ lightboxOpen: false, lightboxImage: '', deleteModalOpen: false, postToDelete: null }" class="bg-white min-h-screen pb-32 animate-fade-in-up relative">

    {{-- STICKY TOP BAR --}}
    <div class="sticky top-[64px] lg:top-0 z-30 bg-white/90 backdrop-blur-md border-b border-gray-100 px-4 md:px-5 py-2.5 flex items-center gap-3">
        <a href="{{ route('community.feed') }}" class="p-1.5 -ml-1.5 rounded-full hover:bg-gray-100 transition-colors text-gray-800">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        </a>
        <h2 class="text-base font-black text-gray-900 tracking-tight">Post</h2>
    </div>

    {{-- AUTHOR HEADER --}}
    <div class="px-4 md:px-5 pt-4 pb-3 flex items-start justify-between">
        <div class="flex items-center gap-2.5">
            @php
                $author = $post->author;
                $authorName = $author->name ?? 'Unknown Author';
                $photoPath = $author->profile_photo_path ?? null;
                $photoUrl = $photoPath ? (Str::startsWith($photoPath, ['http', 'images/']) ? asset($photoPath) : asset('storage/' . $photoPath)) : 'https://ui-avatars.com/api/?name='.urlencode($authorName).'&color=EF4444&background=FEF2F2';
            @endphp
            <a href="{{ route('profile.public', $author->username ?? 'unknown') }}" class="w-10 h-10 rounded-full shrink-0 shadow-sm border border-gray-100 bg-gray-50 overflow-hidden">
                <img src="{{ $photoUrl }}" class="w-full h-full object-cover">
            </a>
            <div class="flex flex-col justify-center">
                <a href="{{ route('profile.public', $author->username ?? 'unknown') }}" class="font-black text-gray-900 text-[14px] hover:text-red-600 transition-colors">
                    {{ $authorName }}
                </a>
                <div class="text-[11px] font-bold text-gray-500 mt-0.5">
                    {{ $post->published_at ? $post->published_at->format('M d, Y • h:i A') : 'Draft' }}
                </div>
            </div>
        </div>

        {{-- OPTIONS MENU WITH DELETE MODAL TRIGGER --}}
        @php
            $isOwnerOrAdmin = auth()->check() && (auth()->id() == $post->user_id || in_array(auth()->user()->role->role_name ?? '', ['administrator', 'director']));
        @endphp

        @if($isOwnerOrAdmin)
            <div x-data="{ open: false }" class="relative mt-1 z-[60]">
                <button @click="open = !open" @click.away="open = false" class="text-gray-400 hover:text-gray-900 transition-colors p-1 rounded-full hover:bg-gray-100 outline-none">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 12h.01M12 12h.01M19 12h.01M6 12a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0z"></path></svg>
                </button>
                <div x-show="open" style="display: none;" class="absolute right-0 mt-1 w-32 bg-white border border-gray-100 rounded-xl shadow-xl z-[70] py-1 overflow-hidden">
                    <a href="{{ route('community.posts.edit', $post->id) }}" class="flex items-center gap-2 w-full text-left px-3 py-2 text-[11px] font-black text-gray-700 hover:bg-gray-50 hover:text-blue-600 transition-colors">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                        Edit
                    </a>
                    <div class="border-t border-gray-50 my-0.5"></div>
                    <button type="button" @click="postToDelete = {{ $post->id }}; deleteModalOpen = true; open = false" class="flex items-center gap-2 w-full text-left px-3 py-2 text-[11px] font-black text-red-600 hover:bg-red-50 transition-colors outline-none">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        Delete
                    </button>
                </div>
            </div>
        @endif
    </div>

    @if($post->category)
        <div class="px-4 md:px-5">
            <span class="bg-gray-100 text-gray-600 text-[9px] font-black uppercase tracking-widest px-2 py-1 rounded">
                {{ $post->category->name }}
            </span>
        </div>
    @endif

    {{-- THE CONTENT --}}
    <div class="px-4 md:px-5 pt-3 pb-3">
        @if($post->reposted_post_id)
            <div class="text-[11px] font-bold text-gray-400 mb-2 flex items-center gap-1">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
                Re-quirked
            </div>
        @endif
        <h1 class="text-xl md:text-2xl font-black text-gray-900 tracking-tight leading-snug mb-3">{{ $post->title }}</h1>
        <div class="text-[14px] md:text-[15px] text-gray-800 leading-[1.7] font-medium whitespace-pre-wrap break-words">{{ trim($post->content) }}</div>
    </div>

    {{-- LIGHTBOX MEDIA GRID --}}
    @if($post->cover_image_path || !empty($post->gallery))
        <div class="w-full bg-gray-50 border-t border-b border-gray-100 mt-3 mb-1 overflow-hidden relative z-10">
            @if($post->cover_image_path)
                <button type="button" @click="lightboxImage = '{{ asset('storage/'.$post->cover_image_path) }}'; lightboxOpen = true" class="block w-full max-h-[450px] overflow-hidden focus:outline-none">
                    <img src="{{ asset('storage/'.$post->cover_image_path) }}" class="w-full h-full object-cover hover:opacity-95 transition-opacity">
                </button>
            @endif
            @if(!empty($post->gallery))
                <div class="grid grid-cols-2 gap-[1px] mt-[1px] bg-white">
                    @foreach($post->gallery as $photo)
                        <button type="button" @click="lightboxImage = '{{ asset('storage/'.$photo) }}'; lightboxOpen = true" class="block aspect-square overflow-hidden bg-gray-100 relative group focus:outline-none">
                            <img src="{{ asset('storage/'.$photo) }}" class="w-full h-full object-cover group-hover:opacity-90 transition-opacity">
                        </button>
                    @endforeach
                </div>
            @endif
        </div>
    @endif

    {{-- FEED INTERACTION COMPONENT --}}
    <livewire:community.feed-interaction :post="$post" />

    {{-- GLOBAL LIGHTBOX MODAL --}}
    <div x-show="lightboxOpen" style="display: none;" class="fixed inset-0 z-[999] flex items-center justify-center bg-black/95 backdrop-blur-sm p-4" x-transition.opacity.duration.300ms @click="lightboxOpen = false" @keydown.escape.window="lightboxOpen = false">
        <button type="button" @click="lightboxOpen = false" class="absolute top-4 right-4 p-2 text-white/70 hover:text-white hover:bg-white/10 rounded-full transition-colors z-50 outline-none">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>
        <img :src="lightboxImage" @click.stop class="max-w-full max-h-[90vh] object-contain rounded-md shadow-2xl transform transition-transform" alt="Expanded view">
    </div>

    {{-- DELETE CONFIRMATION MODAL --}}
    <div x-show="deleteModalOpen" style="display: none;" class="fixed inset-0 z-[999] flex items-center justify-center bg-black/60 backdrop-blur-sm p-4" x-transition.opacity.duration.200ms>
        <div @click.away="deleteModalOpen = false" class="bg-white rounded-[1.25rem] shadow-2xl max-w-sm w-full p-6 text-center transform transition-all relative">
            <div class="w-14 h-14 rounded-full bg-red-50 text-red-500 flex items-center justify-center mx-auto mb-4 border border-red-100">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
            </div>
            <h3 class="text-[17px] font-black text-gray-900 mb-1.5 tracking-tight">Delete this post?</h3>
            <p class="text-[13px] text-gray-500 mb-6 leading-relaxed">This action cannot be undone. This will permanently remove the post, comments, and all reactions.</p>
            <div class="flex gap-2.5">
                <button @click="deleteModalOpen = false; postToDelete = null" class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold py-2.5 rounded-xl transition-colors text-[13px] outline-none">Cancel</button>
                <button wire:click="deletePost(postToDelete)" @click="deleteModalOpen = false" class="flex-1 bg-red-600 hover:bg-red-700 text-white font-bold py-2.5 rounded-xl transition-colors text-[13px] shadow-sm outline-none">Yes, Delete</button>
            </div>
        </div>
    </div>
</div>