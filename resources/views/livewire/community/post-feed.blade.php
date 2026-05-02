<div x-data="{ lightboxOpen: false, lightboxImage: '', deleteModalOpen: false, postToDelete: null }" class="relative animate-fade-in-up pb-32">

    <div class="sticky top-[64px] lg:top-0 z-30 bg-white/90 backdrop-blur-md px-4 py-3 border-b border-gray-100 sm:border-none sm:mb-2">
        <h2 class="text-base font-black text-gray-900 tracking-tight">Home</h2>
    </div>

    <div class="px-0 sm:px-4">
        {{-- QUICK CREATE BOX --}}
        <div class="bg-white sm:rounded-[1.25rem] sm:shadow-[0_2px_15px_-3px_rgba(0,0,0,0.05)] border-b sm:border border-gray-100 p-3 sm:mb-4">
            @php
                $photoUrl = 'https://ui-avatars.com/api/?name=Advocate&color=EF4444&background=FEF2F2';
                if(auth()->check()) {
                    $user = auth()->user();
                    $photoPath = $user->profile_photo_path;
                    $photoUrl = $photoPath ? (Str::startsWith($photoPath, ['http', 'images/']) ? asset($photoPath) : asset('storage/' . $photoPath)) : 'https://ui-avatars.com/api/?name='.urlencode($user->name).'&color=EF4444&background=FEF2F2';
                }
            @endphp
            <div class="flex gap-2.5 items-center">
                <a href="{{ auth()->check() ? route('profile.public', auth()->user()->username ?? 'unknown') : route('login') }}" class="w-8 h-8 rounded-full shrink-0 shadow-sm border border-gray-100 bg-gray-50 overflow-hidden">
                    <img src="{{ $photoUrl }}" class="w-full h-full object-cover">
                </a>
                <a href="{{ route('community.posts.create') }}" class="flex-1 bg-gray-50 hover:bg-gray-100 border border-gray-100/50 transition-colors rounded-full px-4 py-2 flex items-center text-gray-400 text-[13px] font-medium cursor-pointer">
                    What's happening, {{ auth()->check() ? explode(' ', auth()->user()->name)[0] : 'BU' }}?
                </a>
            </div>
        </div>

        {{-- THE SOCIAL FEED LOOP --}}
        <div class="sm:space-y-4">
            @forelse($posts as $post)
                <div class="bg-white border-b border-gray-100 sm:border sm:rounded-[1.25rem] sm:shadow-[0_2px_15px_-3px_rgba(0,0,0,0.05)] transition-shadow hover:shadow-sm">
                    
                    <div class="p-3 flex items-start justify-between">
                        <div class="flex items-center gap-2.5">
                            @php
                                $author = $post->author;
                                $authorName = $author->name ?? 'Unknown Author';
                                $photoPath = $author->profile_photo_path ?? null;
                                $photoUrl = $photoPath ? (Str::startsWith($photoPath, ['http', 'images/']) ? asset($photoPath) : asset('storage/' . $photoPath)) : 'https://ui-avatars.com/api/?name='.urlencode($authorName).'&color=EF4444&background=FEF2F2';
                            @endphp
                            <a href="{{ route('profile.public', $author->username ?? 'unknown') }}" class="w-9 h-9 rounded-full shrink-0 shadow-sm hover:opacity-90 transition-all overflow-hidden border border-gray-100 bg-gray-50">
                                <img src="{{ $photoUrl }}" class="w-full h-full object-cover">
                            </a>
                            <div class="flex flex-col justify-center -mt-0.5">
                                <a href="{{ route('profile.public', $author->username ?? 'unknown') }}" class="font-black text-gray-900 text-[14px] hover:text-red-600 transition-colors">
                                    {{ $authorName }}
                                </a>
                                <div class="text-[11px] font-bold text-gray-400 mt-0.5">
                                    <a href="{{ route('community.posts.show', $post->slug) }}" class="hover:underline">{{ $post->published_at ? $post->published_at->diffForHumans() : 'Draft' }}</a>
                                </div>
                            </div>
                        </div>

                        {{-- Feed Option Menu (If you allow feed-level deletion) --}}
                        @if(auth()->check() && (auth()->id() == $post->user_id || in_array(auth()->user()->role->role_name ?? '', ['administrator', 'director'])))
                            <div x-data="{ open: false }" class="relative z-[60]">
                                <button @click="open = !open" @click.away="open = false" class="text-gray-400 hover:text-gray-900 p-1 rounded-full outline-none">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 12h.01M12 12h.01M19 12h.01M6 12a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0z"></path></svg>
                                </button>
                                <div x-show="open" style="display: none;" class="absolute right-0 mt-1 w-32 bg-white border border-gray-100 rounded-xl shadow-xl z-[70] py-1 overflow-hidden">
                                    <button type="button" @click="postToDelete = {{ $post->id }}; deleteModalOpen = true; open = false" class="flex items-center gap-2 w-full text-left px-3 py-2 text-[11px] font-black text-red-600 hover:bg-red-50 transition-colors outline-none">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        Delete
                                    </button>
                                </div>
                            </div>
                        @endif
                    </div>

                    <div x-data="{ expanded: false }" class="px-3 sm:px-4 pb-2">
                        @if($post->reposted_post_id)
                            <div class="text-[11px] font-bold text-gray-400 mb-1 flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
                                Re-quirked
                            </div>
                        @endif
                        <h3 class="font-black text-gray-900 text-[14px] mb-0.5 leading-snug"><a href="{{ route('community.posts.show', $post->slug) }}" class="hover:underline">{{ $post->title }}</a></h3>
                        <div class="text-[13px] text-gray-800 leading-[1.5] whitespace-pre-wrap break-words" :class="expanded ? '' : 'line-clamp-3'">{{ trim($post->content) }}</div>
                        @if(strlen(strip_tags($post->content)) > 150)
                            <button x-show="!expanded" @click="expanded = true" type="button" class="text-blue-600 font-bold text-[12px] hover:underline mt-1 inline-block outline-none">See more</button>
                        @endif
                    </div>

                    @if($post->cover_image_path || !empty($post->gallery))
                        <div class="w-full mt-1.5 bg-gray-50 border-y border-gray-100 overflow-hidden relative z-10">
                            @if($post->cover_image_path)
                                <button type="button" @click="lightboxImage = '{{ asset('storage/'.$post->cover_image_path) }}'; lightboxOpen = true" class="block w-full max-h-[350px] overflow-hidden focus:outline-none">
                                    <img src="{{ asset('storage/'.$post->cover_image_path) }}" class="w-full h-full object-cover hover:opacity-95 transition-opacity">
                                </button>
                            @elseif(!empty($post->gallery))
                                @php $galleryCount = count($post->gallery); @endphp
                                <div class="grid gap-[2px] bg-white {{ $galleryCount === 1 ? 'grid-cols-1' : 'grid-cols-2' }}">
                                    @foreach(array_slice($post->gallery, 0, 4) as $index => $photo)
                                        <button type="button" @click="lightboxImage = '{{ asset('storage/'.$photo) }}'; lightboxOpen = true" class="block relative overflow-hidden group focus:outline-none {{ $galleryCount === 1 ? 'aspect-video max-h-[350px]' : '' }} {{ $galleryCount === 3 && $index === 0 ? 'col-span-2 aspect-video' : '' }} {{ $galleryCount > 1 && !($galleryCount === 3 && $index === 0) ? 'aspect-square' : '' }}">
                                            <img src="{{ asset('storage/'.$photo) }}" class="w-full h-full object-cover group-hover:opacity-90 transition-opacity">
                                            @if($index === 3 && count($post->gallery) > 4)
                                                <div class="absolute inset-0 bg-black/40 backdrop-blur-[2px] flex items-center justify-center">
                                                    <span class="text-white font-black text-2xl drop-shadow-md">+{{ count($post->gallery) - 4 }}</span>
                                                </div>
                                            @endif
                                        </button>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @endif

                    <div class="sm:rounded-b-[1.25rem]">
                        <livewire:community.feed-interaction :post="$post" :key="'interact-'.$post->id" />
                    </div>
                </div>
            @empty
                <div class="text-center py-16 bg-white sm:rounded-[1.25rem] border-y sm:border border-gray-100">
                    <p class="text-[13px] text-gray-500 mt-1">No updates yet.</p>
                </div>
            @endforelse
        </div>

        <div class="mt-6 px-4 sm:px-0">
            {{ $posts->links() }}
        </div>
    </div>

    {{-- GLOBAL LIGHTBOX MODAL --}}
    <template x-teleport="body">
        <div x-show="lightboxOpen" style="display: none;" class="fixed inset-0 z-[9999] flex items-center justify-center bg-black/95 backdrop-blur-sm p-4" x-transition.opacity.duration.300ms @click="lightboxOpen = false" @keydown.escape.window="lightboxOpen = false">
            <button type="button" @click="lightboxOpen = false" class="absolute top-4 right-4 p-2 text-white/70 hover:text-white hover:bg-white/10 rounded-full transition-colors z-[10000] outline-none">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
            <img :src="lightboxImage" @click.stop class="max-w-full max-h-[90vh] object-contain rounded-md shadow-2xl transform transition-transform" alt="Expanded view">
        </div>
    </template>

    {{-- DELETE CONFIRMATION MODAL --}}
    <template x-teleport="body">
        <div x-show="deleteModalOpen" style="display: none;" class="fixed inset-0 z-[9999] flex items-center justify-center bg-black/60 backdrop-blur-sm p-4" x-transition.opacity.duration.200ms>
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
    </template>
</div>