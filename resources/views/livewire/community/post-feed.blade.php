<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex gap-8">
        
        {{-- Left Sidebar (Desktop Only) --}}
        <div class="w-64 shrink-0 hidden lg:block">
            <livewire:community.sidebar /> 
            {{-- Or @include('livewire.community.sidebar') --}}
        </div>

        {{-- Center Feed (Expands to fill space) --}}
        <div class="flex-1 py-8">
            {{-- FEED HEADER --}}
            <div class="mb-8">
                <h1 class="text-3xl md:text-4xl font-black text-gray-900 tracking-tight flex items-center gap-3">
                    BU MADYA <span class="text-blue-600">Community</span>
                </h1>
                <p class="text-sm text-gray-500 font-medium mt-1">Advocacy, updates, and student-led initiatives.</p>
            </div>

            {{-- "WHAT'S ON YOUR MIND?" - QUICK CREATE BOX --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-4 mb-6">
                <div class="flex gap-3">
                    <div class="w-10 h-10 rounded-full bg-blue-100 text-blue-600 font-black flex items-center justify-center shrink-0 border border-blue-200">
                        {{ auth()->check() ? substr(auth()->user()->name, 0, 1) : '?' }}
                    </div>
                    <a href="{{ route('community.posts.create') }}" class="flex-1 bg-gray-100 hover:bg-gray-200 transition-colors rounded-full px-5 flex items-center text-gray-500 text-sm font-medium cursor-pointer">
                        What's happening in your advocacy, {{ auth()->check() ? explode(' ', auth()->user()->name)[0] : 'Advocate' }}?
                    </a>
                </div>
                <div class="border-t border-gray-100 mt-4 pt-3 flex gap-2">
                    <a href="{{ route('community.posts.create') }}" class="flex items-center gap-2 px-4 py-2 hover:bg-gray-50 rounded-xl transition-colors text-gray-600 font-bold text-xs">
                        <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        Attach Media
                    </a>
                </div>
            </div>

            {{-- CATEGORY FILTER PILLS (Horizontal Scroll) --}}
            <div class="flex overflow-x-auto hide-scrollbar items-center gap-2 mb-6 pb-2">
                <button wire:click="clearCategory" class="shrink-0 px-4 py-2 rounded-full text-xs font-black uppercase tracking-wider transition-all {{ is_null($activeCategoryId) ? 'bg-gray-900 text-white shadow-md' : 'bg-white border border-gray-200 text-gray-600 hover:bg-gray-50' }}">
                    All Updates
                </button>
                @foreach($categories as $category)
                    <button wire:click="setCategory({{ $category->id }})" class="shrink-0 px-4 py-2 rounded-full text-xs font-black uppercase tracking-wider transition-all {{ $activeCategoryId === $category->id ? 'bg-blue-600 text-white shadow-md' : 'bg-white border border-gray-200 text-gray-600 hover:bg-gray-50' }}">
                        {{ $category->name }}
                    </button>
                @endforeach
            </div>

            {{-- THE SOCIAL FEED --}}
            <div class="space-y-6">
                @forelse($posts as $post)
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                        
                        {{-- 1. Post Header (Author Info) --}}
                        <div class="p-4 flex items-start justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-blue-50 text-blue-600 font-black flex items-center justify-center border border-blue-100 shrink-0">
                                    {{ substr($post->author->name ?? 'A', 0, 1) }}
                                </div>
                                <div>
                                    <p class="font-black text-gray-900 text-sm leading-tight flex items-center gap-1.5">
                                        {{ $post->author->name ?? 'Unknown Author' }}
                                        @if($post->is_featured)
                                            <svg class="w-3.5 h-3.5 text-yellow-500" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                        @endif
                                    </p>
                                    <div class="flex items-center gap-1.5 text-[11px] font-bold text-gray-500 mt-0.5">
                                        <span>{{ $post->published_at ? $post->published_at->diffForHumans() : 'Draft' }}</span>
                                        @if($post->category)
                                            <span>•</span>
                                            <span class="uppercase tracking-wider text-blue-600">{{ $post->category->name }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            
                            {{-- Options Menu (Only visible to Author or Admin) --}}
                            @if(auth()->check() && (auth()->id() === $post->user_id || in_array(auth()->user()->role->role_name ?? '', ['administrator', 'director'])))
                                <div x-data="{ open: false }" class="relative">
                                    
                                    {{-- The Three Dots Button --}}
                                    <button @click="open = !open" @click.away="open = false" class="text-gray-400 hover:text-gray-700 transition-colors p-1.5 rounded-full hover:bg-gray-100 outline-none">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 12h.01M12 12h.01M19 12h.01M6 12a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0z"></path></svg>
                                    </button>

                                    {{-- The Dropdown Panel --}}
                                    <div x-show="open" 
                                        x-transition:enter="transition ease-out duration-200"
                                        x-transition:enter-start="opacity-0 scale-95 -translate-y-2"
                                        x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                                        x-transition:leave="transition ease-in duration-150"
                                        x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                                        x-transition:leave-end="opacity-0 scale-95 -translate-y-2"
                                        class="absolute right-0 mt-2 w-36 bg-white border border-gray-100 rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.12)] z-50 py-1.5 overflow-hidden origin-top-right" 
                                        style="display: none;">
                                        
                                        {{-- Edit Button (Routes to your PostEditor) --}}
                                        <a href="{{ route('community.posts.edit', $post->id) }}" class="flex items-center gap-2.5 w-full text-left px-4 py-2.5 text-xs font-black text-gray-700 hover:bg-gray-50 hover:text-blue-600 transition-colors">
                                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                            Edit Post
                                        </a>
                                        
                                        <div class="border-t border-gray-50 my-1"></div>
                                        
                                        {{-- Delete Button (Triggers Livewire method) --}}
                                        <button wire:click="deletePost({{ $post->id }})" 
                                                onclick="confirm('Are you sure you want to permanently delete this post?') || event.stopImmediatePropagation()"
                                                class="flex items-center gap-2.5 w-full text-left px-4 py-2.5 text-xs font-black text-red-600 hover:bg-red-50 transition-colors">
                                            <svg class="w-4 h-4 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            Delete
                                        </button>
                                    </div>
                                </div>
                            @endif
                        </div>

                        {{-- 2. Post Body & Excerpt --}}
                        <div class="px-4 pb-3">
                            <h3 class="font-black text-gray-900 text-lg mb-1">{{ $post->title }}</h3>
                            <p class="text-sm text-gray-800 leading-relaxed whitespace-pre-line line-clamp-4">
                                {{ $post->excerpt ?? Str::limit(strip_tags($post->content), 200) }}
                            </p>
                            <a href="{{ route('community.posts.show', $post->slug) }}" class="text-blue-600 font-bold text-sm hover:underline mt-1 inline-block">See more</a>
                        </div>

                        {{-- 3. Edge-to-Edge Media --}}
                        @if($post->cover_image_path)
                            <a href="{{ route('community.posts.show', $post->slug) }}" class="block w-full max-h-96 overflow-hidden bg-gray-100">
                                <img src="{{ asset('storage/'.$post->cover_image_path) }}" class="w-full h-full object-cover">
                            </a>
                        @endif

                        {{-- Inline Interaction Component --}}
                        <livewire:community.feed-interaction :post="$post" :key="'interact-'.$post->id" />

                    </div>
                @empty
                    <div class="text-center py-20 bg-white rounded-2xl border border-dashed border-gray-200">
                        <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4 border border-gray-100">
                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9.5a2.5 2.5 0 00-2.5-2.5H15"></path></svg>
                        </div>
                        <h3 class="text-lg font-black text-gray-900">No updates yet</h3>
                        <p class="text-sm text-gray-500 mt-1">Check back later or be the first to start a conversation.</p>
                        <button wire:click="clearCategory" class="mt-4 text-xs font-black text-blue-600 uppercase tracking-widest hover:underline">Clear Filters</button>
                    </div>
                @endforelse
            </div>

            {{-- Pagination --}}
            <div class="mt-8">
                {{ $posts->links() }}
            </div>

            <style>
                /* Hides the scrollbar for the category pills but keeps them scrollable on mobile */
                .hide-scrollbar::-webkit-scrollbar { display: none; }
                .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
            </style>
        </div>

        {{-- Optional: Right Sidebar for Trending/Announcements --}}
        <div class="w-80 shrink-0 hidden xl:block py-8">
            {{-- Right side content --}}
        </div>

    </div>
</div>

{{-- MOBILE BOTTOM NAVBAR --}}
<aside class="fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200 shadow-sm z-50 lg:hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @auth
            @php
                $canViewDashboard = auth()->user()->role && in_array(auth()->user()->role->role_name, ['administrator', 'director']);
                $navDashboardRoute = match (auth()->user()->role->role_name ?? '') {
                    'administrator' => route('admin.dashboard'),
                    'organization'  => route('partner.dashboard'),
                    default         => route('dashboard'),
                };
            @endphp
            <div class="flex items-center justify-between h-16">
                <a href="{{ route('community.feed') }}" class="flex flex-col items-center text-gray-600 hover:text-blue-600 transition-colors">
                    <svg class="w-5 h-5 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                    <span class="text-xs font-bold">Feed</span>
                </a>
                @if($canViewDashboard)
                    <a href="{{ $navDashboardRoute }}" class="flex flex-col items-center text-gray-600 hover:text-blue-600 transition-colors">
                        <svg class="w-5 h-5 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9.663 17h4.673M12.586 3.993l-.707.707a1 1 0 00-.707-.707l-.707-.707A1 1 0 0012.586 3.993zm6.778.707l-.707-.707a1 1 0 00-.707.707l-.707.707a1 1 0 001.414-1.414zM3.172 5.636l-.707.707A1 1 0 004.586 7.05l.707-.707A1 1 0 003.172 5.636zm12.02-.707l-.707.707a1 1 0 00-.707-.707l-.707-.707a1 1 0 00-1.414 1.414zM5.636 7.05a1 1 0 00-.707.707l-.707.707a1 1 0 001.414 1.414l.707-.707a1 1 0 00-.707-1.414zM14.828 9.05a1 1 0 00-.707.707l-.707.707a1 1 0 001.414 1.414l.707-.707a1 1 0 00-.707-1.414zM4.586 10.05a1 1 0 00-.707.707l-.707.707a1 1 0 001.414 1.414l.707-.707a1 1 0 00-.707-1.414z"></path></svg>
                        <span class="text-xs font-bold">Dashboard</span>
                    </a>
                @endif
                <a href="{{ route('community.posts.create') }}" class="flex flex-col items-center text-gray-600 hover:text-blue-600 transition-colors">
                    <svg class="w-5 h-5 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"></path></svg>
                    <span class="text-xs font-bold">Create</span>
                </a>
                <a href="{{ route('community.categories') }}" class="flex flex-col items-center text-gray-600 hover:text-blue-600 transition-colors">
                    <svg class="w-5 h-5 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path></svg>
                    <span class="text-xs font-bold">Categories</span>
                </a>
                <a href="{{ route('community.roundtable') }}" class="flex flex-col items-center text-gray-600 hover:text-blue-600 transition-colors">
                    <svg class="w-5 h-5 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h10a2 2 0 002-2v-2m-4 4h4m0 0h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span class="text-xs font-bold">Roundtable</span>
                </a>
            </div>
        @else
            <div class="flex items-center justify-center h-16">
                <a href="{{ route('login') }}" class="text-gray-600 hover:text-blue-600 transition-colors font-bold">Login to Engage</a>
            </div>
        @endauth
    </div>
</aside>