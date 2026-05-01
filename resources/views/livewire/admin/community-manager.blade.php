<div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8 font-sans pb-32">

    {{-- HEADER & QUICK STATS --}}
    <div class="mb-8">
        <h1 class="text-3xl font-black text-gray-900 tracking-tight">Community Moderation</h1>
        <p class="text-sm text-gray-500 font-medium mt-1">Monitor, feature, and manage student posts across the feed.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
        <div class="bg-white rounded-2xl p-5 border border-gray-200 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Total Posts</p>
                <p class="text-2xl font-black text-gray-900 leading-none mt-1">{{ $totalPosts }}</p>
            </div>
            <div class="w-10 h-10 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
            </div>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-gray-200 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Published</p>
                <p class="text-2xl font-black text-green-600 leading-none mt-1">{{ $publishedPosts }}</p>
            </div>
            <div class="w-10 h-10 rounded-full bg-green-50 text-green-600 flex items-center justify-center">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-gray-200 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Currently Featured</p>
                <p class="text-2xl font-black text-yellow-600 leading-none mt-1">{{ $featuredPosts }}</p>
            </div>
            <div class="w-10 h-10 rounded-full bg-yellow-50 text-yellow-600 flex items-center justify-center">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
            </div>
        </div>
    </div>

    {{-- FILTER CONTROLS --}}
    <div class="bg-white p-4 rounded-2xl border border-gray-200 shadow-sm mb-6 flex flex-col md:flex-row gap-4">
        <div class="flex-1 relative">
            <svg class="w-5 h-5 text-gray-400 absolute left-3 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search by title or author..." class="w-full pl-10 pr-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition-all">
        </div>
        
        <select wire:model.live="statusFilter" class="bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm font-bold text-gray-700 outline-none focus:border-blue-500 min-w-[150px]">
            <option value="">All Statuses</option>
            <option value="published">Published</option>
            <option value="draft">Drafts / Hidden</option>
            <option value="flagged">Flagged for Review</option>
        </select>

        <select wire:model.live="categoryFilter" class="bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm font-bold text-gray-700 outline-none focus:border-blue-500 min-w-[180px]">
            <option value="">All Categories</option>
            @foreach($categories as $category)
                <option value="{{ $category->id }}">{{ $category->name }}</option>
            @endforeach
        </select>
    </div>

    {{-- DATA TABLE --}}
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/80 border-b border-gray-200">
                        <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest w-1/3">Post Details</th>
                        <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Author</th>
                        <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Stats</th>
                        <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Status</th>
                        <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($posts as $post)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            
                            {{-- Post Details --}}
                            <td class="px-6 py-4">
                                <a href="{{ route('community.posts.show', $post->slug) }}" target="_blank" class="font-black text-gray-900 text-sm hover:text-blue-600 transition-colors line-clamp-1 block">
                                    {{ $post->title }}
                                </a>
                                <div class="flex items-center gap-2 mt-1">
                                    @if($post->category)
                                        <span class="text-[9px] font-bold uppercase tracking-wider text-blue-600 bg-blue-50 px-2 py-0.5 rounded-md">{{ $post->category->name }}</span>
                                    @endif
                                    <span class="text-[10px] text-gray-400 font-bold">{{ $post->created_at->format('M d, Y') }}</span>
                                </div>
                            </td>

                            {{-- Author --}}
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <div class="w-6 h-6 rounded-full bg-gray-200 text-gray-600 flex items-center justify-center text-[9px] font-black shrink-0">
                                        {{ substr($post->author->name ?? 'A', 0, 1) }}
                                    </div>
                                    <span class="text-sm font-bold text-gray-700">{{ $post->author->name ?? 'Unknown' }}</span>
                                </div>
                            </td>

                            {{-- Stats --}}
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3 text-xs font-bold text-gray-500">
                                    <span title="Elements">🔥 {{ $post->elements_count }}</span>
                                    <span title="Comments">💬 {{ $post->comments_count }}</span>
                                </div>
                            </td>

                            {{-- Status Badges --}}
                            <td class="px-6 py-4">
                                <div class="flex flex-col gap-1.5 items-start">
                                    @if($post->is_flagged)
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-md bg-red-50 text-red-700 text-[10px] font-black uppercase tracking-wider border border-red-200 shadow-sm">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                            Under Review
                                        </span>
                                        <span class="text-[9px] font-bold text-red-500 uppercase tracking-widest">
                                            Triggers: {{ $post->flagged_words }}
                                        </span>
                                    @elseif($post->is_published)
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-md bg-green-50 text-green-700 text-[10px] font-black uppercase tracking-wider border border-green-100">
                                            <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> Published
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-md bg-gray-100 text-gray-600 text-[10px] font-black uppercase tracking-wider border border-gray-200">
                                            Draft
                                        </span>
                                    @endif
                                </div>
                            </td>

                            {{-- Actions --}}
                            <td class="px-6 py-4 text-right space-x-1">
                                
                                {{-- Feature Toggle --}}
                                <button wire:click="toggleFeature({{ $post->id }})" title="{{ $post->is_featured ? 'Remove from Featured' : 'Feature Post' }}" class="inline-flex items-center justify-center w-8 h-8 rounded-lg transition-colors {{ $post->is_featured ? 'bg-yellow-50 text-yellow-500 hover:bg-yellow-100' : 'text-gray-400 hover:bg-gray-100 hover:text-yellow-500' }}">
                                    <svg class="w-4 h-4" fill="{{ $post->is_featured ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path></svg>
                                </button>

                                @if($post->is_flagged)
                                    <button wire:click="clearFlag({{ $post->id }})" title="Approve & Publish" class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-green-50 text-green-600 hover:bg-green-100 transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path></svg>
                                    </button>
                                @endif

                                {{-- Publish/Hide Toggle --}}
                                <button wire:click="togglePublish({{ $post->id }})" title="{{ $post->is_published ? 'Hide Post' : 'Publish Post' }}" class="inline-flex items-center justify-center w-8 h-8 rounded-lg transition-colors {{ $post->is_published ? 'text-green-600 hover:bg-green-50' : 'text-gray-400 hover:bg-gray-100 hover:text-green-600' }}">
                                    @if($post->is_published)
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path></svg>
                                    @else
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                    @endif
                                </button>

                                {{-- Delete Button --}}
                                <button onclick="confirm('Are you sure you want to permanently delete this post?') || event.stopImmediatePropagation()" wire:click="deletePost({{ $post->id }})" title="Delete Post" class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-gray-400 hover:bg-red-50 hover:text-red-600 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center">
                                <div class="w-12 h-12 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-3 border border-gray-100">
                                    <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                                </div>
                                <p class="text-sm font-bold text-gray-900">No posts found.</p>
                                <p class="text-xs text-gray-500 mt-1">Try adjusting your filters or search term.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($posts->hasPages())
            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50">
                {{ $posts->links() }}
            </div>
        @endif
    </div>

</div>