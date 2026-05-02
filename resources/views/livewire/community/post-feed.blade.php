<div x-data="{ deleteModalOpen: false, postToDelete: null }" class="relative animate-fade-in-up pb-32">

    {{-- STICKY MOBILE/DESKTOP FEED HEADER --}}
    <div class="sticky top-[64px] lg:top-0 z-30 bg-white/90 backdrop-blur-md px-4 py-3 border-b border-gray-100 sm:border-none sm:mb-2">
        <h2 class="text-base font-black text-gray-900 tracking-tight">Home</h2>
    </div>

    <div class="px-0 sm:px-4">

        {{-- QUICK CREATE BOX (Ultra-compact) --}}
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
            <div class="border-t border-gray-50 mt-3 pt-2 flex gap-2 pl-10">
                <a href="{{ route('community.posts.create') }}" class="flex items-center gap-1.5 px-2 py-1 hover:bg-gray-50 rounded-lg transition-colors text-gray-500 font-semibold text-[11px]">
                    <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    Attach Media
                </a>
            </div>
        </div>

        {{-- NEW APPEALING FILTER PILLS --}}
        <div class="flex overflow-x-auto hide-scrollbar items-center gap-2 py-3 px-4 sm:px-0 border-b sm:border-none border-gray-100 bg-gray-50/30 sm:bg-transparent">
            <button wire:click="clearCategory" class="shrink-0 px-4 py-1.5 rounded-full text-[12px] font-semibold whitespace-nowrap transition-all duration-200 {{ is_null($activeCategoryId) ? 'bg-gray-900 text-white shadow-md shadow-gray-900/20' : 'bg-white border border-gray-200 text-gray-600 hover:bg-gray-50 hover:border-gray-300' }}">
                All Updates
            </button>
            @foreach($categories as $category)
                <button wire:click="setCategory({{ $category->id }})" class="shrink-0 px-4 py-1.5 rounded-full text-[12px] font-semibold whitespace-nowrap transition-all duration-200 {{ $activeCategoryId === $category->id ? 'bg-red-500 text-white shadow-md shadow-red-500/20 border border-red-500' : 'bg-white border border-gray-200 text-gray-600 hover:bg-gray-50 hover:border-gray-300' }}">
                    {{ $category->name }}
                </button>
            @endforeach
        </div>

        {{-- THE SOCIAL FEED --}}
        <div class="sm:space-y-4">
            @forelse($posts as $post)
                {{-- EDGE-TO-EDGE ON MOBILE --}}
                <div class="bg-white border-b border-gray-100 sm:border sm:rounded-[1.25rem] sm:shadow-[0_2px_15px_-3px_rgba(0,0,0,0.05)] overflow-visible transition-shadow hover:shadow-sm">

                    {{-- 1. Post Header --}}
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
                                <a href="{{ route('profile.public', $author->username ?? 'unknown') }}" class="font-bold text-gray-900 text-[14px] leading-tight flex items-center gap-1 hover:text-red-600 transition-colors">
                                    {{ $authorName }}
                                    @if($post->is_featured)
                                        <svg class="w-3.5 h-3.5 text-yellow-500" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                    @endif
                                </a>
                                <div class="flex items-center gap-1.5 text-[11px] font-medium text-gray-400 mt-0.5">
                                    <a href="{{ route('community.posts.show', $post->slug) }}" class="hover:underline">{{ $post->published_at ? $post->published_at->diffForHumans() : 'Draft' }}</a>
                                    @if($post->category)
                                        <span class="text-gray-300">•</span>
                                        <span class="text-red-500 font-semibold">{{ $post->category->name }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        {{-- Options Menu --}}
                        @if(auth()->check() && (auth()->id() == $post->user_id || in_array(auth()->user()->role->role_name ?? '', ['administrator', 'director'])))
                            <div x-data="{ open: false }" class="relative">
                                <button @click="open = !open" @click.away="open = false" class="text-gray-400 hover:text-gray-900 transition-colors p-1.5 rounded-full hover:bg-gray-50 outline-none">
                                    <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 12h.01M12 12h.01M19 12h.01M6 12a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0z"></path></svg>
                                </button>
                                <div x-show="open" style="display: none;" class="absolute right-0 mt-1 w-32 bg-white border border-gray-50 rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.12)] z-50 py-1.5 overflow-hidden">
                                    <a href="{{ route('community.posts.edit', $post->id) }}" class="flex items-center gap-2 w-full text-left px-3.5 py-2 text-[12px] font-bold text-gray-700 hover:bg-gray-50 hover:text-blue-600 transition-colors">
                                        Edit Post
                                    </a>
                                    <div class="border-t border-gray-50 my-0.5"></div>
                                    <button type="button" @click="postToDelete = {{ $post->id }}; deleteModalOpen = true; open = false" class="flex items-center gap-2 w-full text-left px-3.5 py-2 text-[12px] font-bold text-red-600 hover:bg-red-50 transition-colors">
                                        Delete
                                    </button>
                                </div>
                            </div>
                        @endif
                    </div>

                    {{-- 2. Post Body & Excerpt (Denser Text) --}}
                    @php
                        $isLongText = strlen(strip_tags($post->content)) > 150 || substr_count($post->content, "\n") > 2;
                    @endphp

                    <div x-data="{ expanded: false }" class="px-3 sm:px-4 pb-2">
                        <h3 class="font-black text-gray-900 text-[14px] mb-0.5 leading-snug">
                            <a href="{{ route('community.posts.show', $post->slug) }}" class="hover:underline">{{ $post->title }}</a>
                        </h3>

                        <div class="text-[13px] text-gray-800 leading-[1.5] whitespace-pre-wrap break-words" :class="expanded ? '' : 'line-clamp-3'">{{ trim($post->content) }}</div>

                        @if($isLongText)
                            <button x-show="!expanded" @click="expanded = true" type="button" class="text-blue-600 font-bold text-[12px] hover:underline mt-1 inline-block outline-none">
                                See more
                            </button>
                        @endif
                    </div>

                    {{-- 3. EDGE-TO-EDGE DYNAMIC MEDIA GRID --}}
                    @if($post->cover_image_path || !empty($post->gallery))
                        <div class="w-full mt-1.5 bg-gray-50 border-y border-gray-100 overflow-hidden">
                            @if($post->cover_image_path)
                                <a href="{{ route('community.posts.show', $post->slug) }}" class="block w-full max-h-[350px] overflow-hidden bg-gray-100">
                                    <img src="{{ asset('storage/'.$post->cover_image_path) }}" class="w-full h-full object-cover hover:opacity-95 transition-opacity">
                                </a>
                            @elseif(!empty($post->gallery))
                                @php $galleryCount = count($post->gallery); @endphp
                                <div class="grid gap-[2px] bg-white {{ $galleryCount === 1 ? 'grid-cols-1' : 'grid-cols-2' }}">
                                    @foreach(array_slice($post->gallery, 0, 4) as $index => $photo)
                                        <a href="{{ route('community.posts.show', $post->slug) }}" class="block relative overflow-hidden group bg-gray-100 {{ $galleryCount === 1 ? 'aspect-video max-h-[350px]' : '' }} {{ $galleryCount === 3 && $index === 0 ? 'col-span-2 aspect-video' : '' }} {{ $galleryCount > 1 && !($galleryCount === 3 && $index === 0) ? 'aspect-square' : '' }}">
                                            <img src="{{ asset('storage/'.$photo) }}" class="w-full h-full object-cover group-hover:opacity-90 transition-opacity">
                                            @if($index === 3 && count($post->gallery) > 4)
                                                <div class="absolute inset-0 bg-black/40 backdrop-blur-[2px] flex items-center justify-center">
                                                    <span class="text-white font-black text-2xl drop-shadow-md">+{{ count($post->gallery) - 4 }}</span>
                                                </div>
                                            @endif
                                        </a>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @endif

                    {{-- 4. INLINE FEED INTERACTION COMPONENT --}}
                    <div class="sm:rounded-b-[1.25rem] overflow-hidden">
                        <livewire:community.feed-interaction :post="$post" :key="'interact-'.$post->id" />
                    </div>

                </div>
            @empty
                <div class="text-center py-16 bg-white sm:rounded-[1.25rem] border-y sm:border border-gray-100">
                    <div class="w-12 h-12 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-3 border border-gray-100 shadow-sm">
                        <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9.5a2.5 2.5 0 00-2.5-2.5H15"></path></svg>
                    </div>
                    <h3 class="text-[15px] font-black text-gray-900">No updates yet</h3>
                    <p class="text-[13px] text-gray-500 mt-1">Check back later or start a conversation.</p>
                </div>
            @endforelse
        </div>

        <div class="mt-6 px-4 sm:px-0">
            {{ $posts->links() }}
        </div>
    </div>


    {{-- ========================================== --}}
    {{-- SECURE DELETE CONFIRMATION MODAL --}}
    {{-- ========================================== --}}
    <div x-show="deleteModalOpen" style="display: none;" class="relative z-[100]" aria-labelledby="modal-title" role="dialog" aria-modal="true">

        <div x-show="deleteModalOpen"
             x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
             class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm transition-opacity"></div>

        <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">

                <div x-show="deleteModalOpen"
                     @click.away="deleteModalOpen = false"
                     x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     class="relative transform overflow-hidden rounded-[2rem] bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-sm border border-gray-100">

                    <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-red-50 sm:mx-0 sm:h-10 sm:w-10 border border-red-100 shadow-inner">
                                <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                            </div>
                            <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left">
                                <h3 class="text-lg font-black leading-6 text-gray-900 tracking-tight" id="modal-title">Delete Post</h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500 font-medium leading-relaxed">Are you sure you want to permanently delete this post? All elements, comments, and attached media will be wiped. This action cannot be undone.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50/80 px-4 py-4 sm:flex sm:flex-row-reverse sm:px-6 border-t border-gray-100 gap-2">
                        <button type="button" @click="$wire.deletePost(postToDelete); deleteModalOpen = false" class="inline-flex w-full justify-center rounded-xl bg-red-600 px-4 py-3 text-xs font-black uppercase tracking-widest text-white shadow-md hover:bg-red-700 transition-all sm:w-auto active:scale-95">
                            Delete Permanently
                        </button>
                        <button type="button" @click="deleteModalOpen = false" class="mt-3 inline-flex w-full justify-center rounded-xl bg-white px-4 py-3 text-xs font-black uppercase tracking-widest text-gray-700 shadow-sm ring-1 ring-inset ring-gray-200 hover:bg-gray-50 transition-all sm:mt-0 sm:w-auto active:scale-95">
                            Cancel
                        </button>
                    </div>

                </div>
            </div>
        </div>
    </div>

</div>
