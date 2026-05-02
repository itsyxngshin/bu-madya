{{-- BULLETPROOF SEO TAGS --}}
@section('meta_title', e($post->title))
@section('meta_description', e(\Illuminate\Support\Str::limit(strip_tags($post->content), 150)))

{{-- Safe Image Fallback --}}
@if($post->cover_image_path)
    @section('meta_image', asset('storage/'.$post->cover_image_path))
@elseif(is_array($post->gallery) && count($post->gallery) > 0)
    @section('meta_image', asset('storage/'.$post->gallery[0]))
@endif

{{-- ADDED ALPINE LIGHTBOX STATE HERE --}}
<div x-data="{ lightboxOpen: false, lightboxImage: '' }" class="bg-white min-h-screen pb-32 animate-fade-in-up relative">

    {{-- 1. STICKY TOP BAR --}}
    <div class="sticky top-[64px] lg:top-0 z-30 bg-white/90 backdrop-blur-md border-b border-gray-100 px-4 md:px-5 py-2.5 flex items-center gap-3">
        <a href="{{ route('community.feed') }}" class="p-1.5 -ml-1.5 rounded-full hover:bg-gray-100 transition-colors text-gray-800">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        </a>
        <h2 class="text-base font-black text-gray-900 tracking-tight">Post</h2>
    </div>

    {{-- 2. AUTHOR HEADER --}}
    <div class="px-4 md:px-5 pt-4 pb-3 flex items-start justify-between">
        {{-- Left Side: Author Info --}}
        <div class="flex items-center gap-2.5">
            @php
                $author = $post->author;
                $authorName = $author->name ?? 'Unknown Author';
                $authorUsername = $author->username ?? 'unknown';
                $photoPath = $author->profile_photo_path ?? null;

                $photoUrl = $photoPath
                    ? (Str::startsWith($photoPath, ['http', 'images/']) ? asset($photoPath) : asset('storage/' . $photoPath))
                    : 'https://ui-avatars.com/api/?name='.urlencode($authorName).'&color=EF4444&background=FEF2F2';
            @endphp

            <a href="{{ route('profile.public', $authorUsername) }}" class="w-10 h-10 rounded-full shrink-0 shadow-sm hover:ring-2 hover:ring-red-200 transition-all overflow-hidden border border-gray-100 bg-gray-50">
                <img src="{{ $photoUrl }}" alt="{{ $authorName }}" class="w-full h-full object-cover">
            </a>

            <div class="flex flex-col justify-center">
                <a href="{{ route('profile.public', $authorUsername) }}" class="font-black text-gray-900 text-[14px] leading-tight flex items-center gap-1 hover:text-red-600 transition-colors cursor-pointer group">
                    {{ $authorName }}
                    @if($post->is_featured)
                        <svg class="w-3.5 h-3.5 text-yellow-500 group-hover:scale-110 transition-transform" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                    @endif
                </a>
                <div class="flex items-center gap-1.5 text-[11px] font-bold text-gray-500 mt-0.5">
                    <span>{{ $post->published_at ? $post->published_at->format('M d, Y • h:i A') : 'Draft' }}</span>
                </div>
            </div>
        </div>

        {{-- Right Side: Options Menu --}}
        @php
            $isOwnerOrAdmin = auth()->check() && (auth()->id() == $post->user_id || in_array(auth()->user()->role->role_name ?? '', ['administrator', 'director']));
        @endphp

        @if($isOwnerOrAdmin)
            <div x-data="{ open: false }" class="relative mt-1">
                <button @click="open = !open" @click.away="open = false" class="text-gray-400 hover:text-gray-900 transition-colors p-1 rounded-full hover:bg-gray-100 outline-none">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 12h.01M12 12h.01M19 12h.01M6 12a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0z"></path></svg>
                </button>

                <div x-show="open" style="display: none;" class="absolute right-0 mt-1 w-32 bg-white border border-gray-100 rounded-xl shadow-xl z-50 py-1 overflow-hidden">
                    <a href="{{ route('community.posts.edit', $post->id) }}" class="flex items-center gap-2 w-full text-left px-3 py-2 text-[11px] font-black text-gray-700 hover:bg-gray-50 hover:text-blue-600 transition-colors">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                        Edit
                    </a>
                    <div class="border-t border-gray-50 my-0.5"></div>
                    <button type="button" wire:click="deletePost" onclick="confirm('Permanently delete this post?') || event.stopImmediatePropagation()" class="flex items-center gap-2 w-full text-left px-3 py-2 text-[11px] font-black text-red-600 hover:bg-red-50 transition-colors">
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

    {{-- 3. THE CONTENT --}}
    <div class="px-4 md:px-5 pt-3 pb-3">
        @if($post->reposted_post_id)
            <div class="text-[11px] font-bold text-gray-400 mb-2 flex items-center gap-1">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
                Re-quirked
            </div>
        @endif

        <h1 class="text-xl md:text-2xl font-black text-gray-900 tracking-tight leading-snug mb-3">
            {{ $post->title }}
        </h1>
        <div class="text-[14px] md:text-[15px] text-gray-800 leading-[1.7] font-medium whitespace-pre-wrap break-words">{{ trim($post->content) }}</div>
    </div>

    {{-- 4. EDGE-TO-EDGE LIGHTBOX MEDIA --}}
    @if($post->cover_image_path || !empty($post->gallery))
        <div class="w-full bg-gray-50 border-t border-b border-gray-100 mt-3 mb-1 overflow-hidden relative z-10">
            @if($post->cover_image_path)
                {{-- Switched to Lightbox Button --}}
                <button type="button" @click="lightboxImage = '{{ asset('storage/'.$post->cover_image_path) }}'; lightboxOpen = true" class="block w-full max-h-[450px] overflow-hidden focus:outline-none">
                    <img src="{{ asset('storage/'.$post->cover_image_path) }}" class="w-full h-full object-cover bg-gray-100 hover:opacity-95 transition-opacity">
                </button>
            @endif

            @if(!empty($post->gallery))
                <div class="grid grid-cols-2 gap-[1px] mt-[1px] bg-white">
                    @foreach($post->gallery as $photo)
                        {{-- Switched to Lightbox Button --}}
                        <button type="button" @click="lightboxImage = '{{ asset('storage/'.$photo) }}'; lightboxOpen = true" class="block aspect-square overflow-hidden bg-gray-100 relative group focus:outline-none">
                            <img src="{{ asset('storage/'.$photo) }}" class="w-full h-full object-cover group-hover:opacity-90 transition-opacity">
                        </button>
                    @endforeach
                </div>
            @endif
        </div>
    @endif

    {{-- 5. THE ENGAGEMENT BAR (Swapped to feed-interaction component & z-index added) --}}
    <div class="relative z-20">
        <livewire:community.feed-interaction :post="$post" />
    </div>

    {{-- 6. GLOBAL LIGHTBOX WINDOW --}}
    <div x-show="lightboxOpen" 
         style="display: none;" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-[999] flex items-center justify-center bg-black/95 backdrop-blur-sm p-4"
         @click="lightboxOpen = false" 
         @keydown.escape.window="lightboxOpen = false">
        
        <button type="button" @click="lightboxOpen = false" class="absolute top-4 right-4 md:top-6 md:right-6 p-2 text-white/70 hover:text-white hover:bg-white/10 rounded-full transition-colors z-50 outline-none">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>

        <img :src="lightboxImage" 
             @click.stop 
             class="max-w-full max-h-[90vh] object-contain rounded-md shadow-2xl transform transition-transform" 
             alt="Expanded view">
    </div>

</div>