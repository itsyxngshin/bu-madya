<div class="max-w-4xl mx-auto space-y-8 pb-24">
    
    {{-- Header --}}
    <div class="bg-white dark:bg-[#1A1617] p-6 border-4 border-iba-black dark:border-iba-light shadow-[8px_8px_0_0_#131011] dark:shadow-[8px_8px_0_0_#FFFBF7]">
        <h1 class="text-2xl font-black text-iba-black dark:text-iba-light uppercase tracking-wider">Community Logs</h1>
        <p class="text-sm font-bold text-gray-500 dark:text-gray-400 mt-1">Share updates, ask questions, and document your hackathon journey.</p>
    </div>

    @if (session()->has('success'))
        <div class="bg-iba-green/10 border-l-4 border-iba-green p-4 flex items-center justify-between">
            <p class="text-sm font-bold text-iba-green uppercase tracking-wider">{{ session('success') }}</p>
        </div>
    @endif

    {{-- CREATE POST COMPONENT --}}
    <div class="bg-white dark:bg-[#1A1617] border-4 border-iba-black dark:border-iba-light shadow-[8px_8px_0_0_#0095AC] p-6">
        <form wire:submit.prevent="createPost">
            
            {{-- IDENTITY SELECTOR --}}
            <div class="mb-4 flex items-center gap-3">
                <span class="text-xs font-black uppercase tracking-widest text-gray-500">Posting As:</span>
                <select wire:model="postingAs" class="border-2 border-iba-black dark:border-iba-light bg-gray-50 dark:bg-gray-800 text-iba-black dark:text-white text-xs font-bold p-2 focus:outline-none focus:border-iba-teal appearance-none cursor-pointer pr-8">
                    @foreach($availableIdentities as $value => $label)
                        <option value="{{ $value }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <textarea wire:model="content" rows="3" placeholder="What's happening with your project? Share an update..." class="w-full border-4 border-iba-black dark:border-iba-light p-4 text-sm focus:outline-none focus:border-iba-teal bg-gray-50 dark:bg-gray-900 text-iba-black dark:text-white font-bold resize-none"></textarea>
            @error('content') <span class="text-iba-red text-xs font-bold block mt-1">⚠ {{ $message }}</span> @enderror

            {{-- Image Preview --}}
            @if($photos)
                <div class="flex flex-wrap gap-3 mt-4">
                    @foreach($photos as $photo)
                        <div class="w-20 h-20 border-2 border-iba-black relative overflow-hidden shadow-[2px_2px_0_0_#131011]">
                            <img src="{{ $photo->temporaryUrl() }}" class="w-full h-full object-cover">
                        </div>
                    @endforeach
                </div>
            @endif

            <div class="mt-4 flex items-center justify-between pt-4 border-t-2 border-dashed border-gray-300 dark:border-gray-700">
                <div>
                    <input type="file" id="photo-upload" wire:model="photos" multiple accept="image/*" class="hidden">
                    <label for="photo-upload" class="cursor-pointer inline-flex items-center gap-2 text-xs font-black uppercase tracking-widest text-iba-teal hover:text-teal-700 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        Attach Photos
                    </label>
                    <div wire:loading wire:target="photos" class="text-[10px] font-bold text-iba-orange ml-2 animate-pulse uppercase">Uploading...</div>
                </div>
                
                <button type="submit" class="bg-iba-black dark:bg-iba-light text-white dark:text-iba-black font-black px-6 py-2.5 text-xs uppercase border-4 border-transparent hover:border-iba-black dark:hover:border-white shadow-[4px_4px_0_0_#0095AC] hover:translate-y-0.5 hover:shadow-none transition-all active:translate-y-1" wire:loading.attr="disabled">
                    Publish Log
                </button>
            </div>
        </form>
    </div>

    {{-- FEED LOOP --}}
    <div class="space-y-8">
        @forelse($posts as $post)
            <div class="bg-white dark:bg-[#1A1617] border-4 border-iba-black dark:border-iba-light shadow-[8px_8px_0_0_#131011] dark:shadow-[8px_8px_0_0_#FFFBF7] flex flex-col relative">
                
                {{-- Official Announcement Badge --}}
                @if($post->is_announcement)
                    <div class="absolute -top-4 -left-4 bg-iba-red text-white font-black text-[10px] uppercase tracking-widest px-4 py-1.5 border-2 border-iba-black shadow-[2px_2px_0_0_#131011] transform -rotate-2 z-10">
                        Official Announcement
                    </div>
                @endif

                {{-- Post Header --}}
                <div class="p-5 flex items-center justify-between border-b-2 border-dashed border-gray-200 dark:border-gray-800">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-iba-teal text-white flex items-center justify-center font-black text-lg border-2 border-iba-black shadow-[2px_2px_0_0_#131011] shrink-0">
                            {{ substr($post->author_display ?? $post->user->name ?? 'U', 0, 1) }}
                        </div>
                        <div>
                            {{-- Use Author Display Identity --}}
                            <h4 class="font-black text-sm text-iba-black dark:text-white uppercase leading-tight">{{ $post->author_display ?? $post->user->name ?? 'Unknown Identity' }}</h4>
                            <p class="text-[10px] font-bold text-gray-500 uppercase tracking-widest">
                                @if($post->is_announcement) Organizing Committee @else Hackathon Cohort @endif • {{ $post->created_at->diffForHumans() }}
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Post Content --}}
                <div class="p-5">
                    <p class="text-sm font-bold text-gray-800 dark:text-gray-200 leading-relaxed whitespace-pre-wrap">{{ $post->content }}</p>
                    
                    {{-- Dynamic Image Grid --}}
                    @if($post->images->count() > 0)
                        <div class="mt-4 grid gap-2 {{ $post->images->count() == 1 ? 'grid-cols-1' : ($post->images->count() == 2 ? 'grid-cols-2' : 'grid-cols-2 sm:grid-cols-3') }}">
                            @foreach($post->images as $image)
                                <div class="border-2 border-iba-black shadow-[2px_2px_0_0_#131011] overflow-hidden aspect-square relative group cursor-pointer">
                                    <img src="{{ Storage::url($image->image_path) }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                {{-- Action Bar --}}
                <div class="bg-gray-50 dark:bg-gray-900 border-t-2 border-iba-black dark:border-iba-light p-3 flex gap-6">
                    @php $hasLiked = $post->likes->contains('user_id', auth('ibalong')->id()); @endphp
                    <button wire:click="toggleLike({{ $post->id }})" class="flex items-center gap-1.5 text-xs font-black uppercase tracking-widest transition-colors {{ $hasLiked ? 'text-iba-red' : 'text-gray-500 hover:text-iba-black dark:hover:text-white' }}">
                        <svg class="w-5 h-5 {{ $hasLiked ? 'fill-current' : 'fill-none stroke-currentColor' }}" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                        {{ $post->likes->count() }}
                    </button>
                    
                    <div class="flex items-center gap-1.5 text-xs font-black uppercase tracking-widest text-gray-500">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                        {{ $post->comments->count() }}
                    </div>
                </div>

                {{-- Comments Section --}}
                <div class="bg-gray-100 dark:bg-gray-800 border-t-2 border-iba-black dark:border-iba-light p-5 space-y-4">
                    @foreach($post->comments as $comment)
                        <div class="flex gap-3">
                            <div class="w-8 h-8 bg-gray-900 dark:bg-gray-700 text-white flex items-center justify-center font-black text-xs border border-iba-black shrink-0">
                                {{ substr($comment->author_display ?? $comment->user->name ?? 'U', 0, 1) }}
                            </div>
                            <div class="bg-white dark:bg-gray-900 border-2 border-iba-black dark:border-gray-600 p-3 flex-1">
                                <div class="flex justify-between items-end mb-1">
                                    <span class="text-[10px] font-black uppercase tracking-widest text-iba-black dark:text-white">{{ $comment->author_display ?? $comment->user->name ?? 'Unknown' }}</span>
                                    <span class="text-[9px] font-bold text-gray-400">{{ $comment->created_at->diffForHumans() }}</span>
                                </div>
                                <p class="text-xs font-bold text-gray-700 dark:text-gray-300 leading-relaxed">{{ $comment->content }}</p>
                            </div>
                        </div>
                    @endforeach

                    {{-- Add Comment Input with Identity Selector --}}
                    <form wire:submit.prevent="addComment({{ $post->id }})" class="mt-4 pt-2 border-t-2 border-dashed border-gray-300 dark:border-gray-700">
                        <div class="flex flex-col sm:flex-row gap-3">
                            
                            {{-- Comment Identity Dropdown --}}
                            <div class="shrink-0">
                                <select wire:model="commentIdentities.{{ $post->id }}" class="w-full sm:w-32 border-4 border-iba-black dark:border-iba-light bg-gray-50 dark:bg-gray-800 text-iba-black dark:text-white text-[10px] font-black uppercase tracking-widest p-2 h-full focus:outline-none focus:border-iba-teal appearance-none cursor-pointer">
                                    <option value="" disabled>Reply As...</option>
                                    @foreach($availableIdentities as $value => $label)
                                        <option value="{{ $value }}">{{ $value }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <input type="text" wire:model="newComments.{{ $post->id }}" placeholder="Write a comment..." class="flex-1 border-4 border-iba-black dark:border-iba-light p-2 text-xs focus:outline-none focus:border-iba-orange bg-white dark:bg-[#1A1617] text-iba-black dark:text-white font-bold">
                            
                            <button type="submit" class="bg-iba-orange text-iba-black font-black px-4 py-2 text-xs uppercase border-4 border-iba-black shadow-[3px_3px_0_0_#131011] hover:translate-y-0.5 hover:shadow-none transition-all active:translate-y-1">
                                Reply
                            </button>
                        </div>
                    </form>
                </div>

            </div>
        @empty
            <div class="bg-white dark:bg-[#1A1617] border-4 border-iba-black border-dashed p-12 text-center shadow-sm">
                <p class="text-sm font-black text-gray-500 uppercase tracking-widest">The community log is currently empty. Be the first to break the ice!</p>
            </div>
        @endforelse
    </div>
</div>