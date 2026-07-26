<div class="max-w-4xl mx-auto space-y-8 pb-24">
    
    <div class="mb-4">
        <a href="{{ route('ibalong.community-logs') }}" class="inline-flex items-center gap-2 text-xs font-black text-gray-500 hover:text-iba-red uppercase tracking-widest transition-colors border-2 border-transparent hover:border-iba-red px-3 py-1.5">
            &larr; Back to Full Feed
        </a>
    </div>

    {{-- SINGLE POST FOCUS --}}
    <div class="bg-white dark:bg-[#1A1617] border-4 border-iba-black dark:border-iba-light shadow-[12px_12px_0_0_#0095AC] flex flex-col relative">
        
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
                    <h4 class="font-black text-sm text-iba-black dark:text-white uppercase leading-tight">{{ $post->author_display ?? $post->user->name }}</h4>
                    <p class="text-[10px] font-bold text-gray-500 uppercase tracking-widest">
                        @if($post->is_announcement) Organizing Committee @else Hackathon Cohort @endif • {{ $post->created_at->diffForHumans() }}
                    </p>
                </div>
            </div>
        </div>

        {{-- Post Content --}}
        <div class="p-6 md:p-8">
            {{-- We use whitespace-pre-wrap WITHOUT nl2br() to fix the double spacing issue --}}
            <div class="text-sm md:text-base font-bold text-gray-800 dark:text-gray-200 leading-relaxed whitespace-pre-wrap">{!! preg_replace('/@([A-Za-z0-9_]+)/', '<span class="text-iba-teal bg-iba-teal/10 px-1 py-0.5 border border-iba-teal border-dashed">$0</span>', e($post->content)) !!}</div>
            
            {{-- Image Grid --}}
            @if($post->images->count() > 0)
                <div class="mt-8 grid gap-3 {{ $post->images->count() == 1 ? 'grid-cols-1' : ($post->images->count() == 2 ? 'grid-cols-2' : 'grid-cols-2 sm:grid-cols-3') }}">
                    @foreach($post->images as $image)
                        <div class="border-2 border-iba-black shadow-[4px_4px_0_0_#131011] overflow-hidden relative cursor-pointer group">
                            {{-- Removed aspect-square so images show their full natural proportions on the single view --}}
                            <img src="{{ Storage::url($image->image_path) }}" class="w-full h-auto object-cover transition-transform duration-500 group-hover:scale-105">
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Action Bar --}}
        <div class="bg-gray-50 dark:bg-gray-900 border-t-2 border-iba-black dark:border-iba-light p-4 flex gap-6">
            @php $hasLiked = $post->likes->contains('user_id', auth('ibalong')->id()); @endphp
            <button wire:click="toggleLike" class="flex items-center gap-2 text-sm font-black uppercase tracking-widest transition-colors {{ $hasLiked ? 'text-iba-red' : 'text-gray-500 hover:text-iba-black dark:hover:text-white' }}">
                <svg class="w-6 h-6 {{ $hasLiked ? 'fill-current' : 'fill-none stroke-currentColor' }}" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg> 
                {{ $post->likes->count() }} Likes
            </button>
            <div class="flex items-center gap-2 text-sm font-black uppercase tracking-widest text-gray-500">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg> 
                {{ $post->comments->count() }} Comments
            </div>
        </div>

        {{-- Expanded Comments Section (Discussion Thread) --}}
        <div class="bg-gray-100 dark:bg-gray-800 border-t-4 border-iba-black dark:border-iba-light p-6 sm:p-8 space-y-6">
            
            <h3 class="text-sm font-black uppercase tracking-widest text-iba-black dark:text-white border-b-2 border-dashed border-gray-300 dark:border-gray-700 pb-2 mb-6">Discussion Thread</h3>

            @forelse($post->comments as $comment)
                <div class="flex flex-col gap-3">
                    {{-- Parent Comment --}}
                    <div class="flex gap-4">
                        <div class="w-10 h-10 bg-gray-900 text-white flex items-center justify-center font-black text-sm border-2 border-iba-black shrink-0">
                            {{ substr($comment->author_display ?? 'U', 0, 1) }}
                        </div>
                        <div class="bg-white dark:bg-gray-900 border-2 border-iba-black dark:border-gray-600 p-4 flex-1 shadow-[3px_3px_0_0_#131011]">
                            <div class="flex justify-between items-end mb-2 border-b border-gray-100 dark:border-gray-800 pb-2">
                                <span class="text-xs font-black uppercase tracking-widest text-iba-black dark:text-white">{{ $comment->author_display }}</span>
                                <span class="text-[10px] font-bold text-gray-400 uppercase">{{ $comment->created_at->diffForHumans() }}</span>
                            </div>
                            <p class="text-sm font-bold text-gray-700 dark:text-gray-300 leading-relaxed mb-3 whitespace-pre-wrap">{!! preg_replace('/@([A-Za-z0-9_]+)/', '<span class="text-iba-teal font-black">$0</span>', e($comment->content)) !!}</p>
                            
                            <button wire:click="setReply({{ $comment->id }})" class="text-[10px] font-black uppercase text-gray-400 hover:text-iba-orange transition-colors flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/></svg> Reply
                            </button>
                        </div>
                    </div>

                    {{-- Nested Replies --}}
                    @if($comment->replies->count() > 0)
                        <div class="pl-14 space-y-3 mt-2">
                            @foreach($comment->replies as $reply)
                                <div class="flex gap-3 border-l-2 border-iba-orange pl-4">
                                    <div class="w-8 h-8 bg-gray-700 text-white flex items-center justify-center font-black text-[10px] border-2 border-iba-black shrink-0">
                                        {{ substr($reply->author_display ?? 'U', 0, 1) }}
                                    </div>
                                    <div class="bg-white dark:bg-gray-900 border-2 border-iba-black p-3 flex-1 shadow-[2px_2px_0_0_#FF8623]">
                                        <div class="flex justify-between items-end mb-1">
                                            <span class="text-[10px] font-black uppercase tracking-widest text-iba-black dark:text-white">{{ $reply->author_display }}</span>
                                            <span class="text-[9px] font-bold text-gray-400 uppercase">{{ $reply->created_at->diffForHumans() }}</span>
                                        </div>
                                        <p class="text-xs font-bold text-gray-600 dark:text-gray-400 whitespace-pre-wrap">{!! preg_replace('/@([A-Za-z0-9_]+)/', '<span class="text-iba-teal font-black">$0</span>', e($reply->content)) !!}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    {{-- Reply Input Box (Opens when clicking Reply) --}}
                    @if($replyingTo === $comment->id)
                        <form wire:submit.prevent="addComment({{ $comment->id }})" class="pl-14 mt-2 flex flex-col sm:flex-row gap-2">
                            <input type="text" wire:model="newComments.reply_{{ $comment->id }}" placeholder="Replying to {{ $comment->author_display }}..." class="flex-1 border-4 border-iba-black dark:border-gray-500 p-3 text-xs focus:outline-none focus:border-iba-orange bg-white dark:bg-gray-800 text-iba-black dark:text-white font-bold">
                            <button type="submit" class="bg-iba-orange text-iba-black font-black px-4 py-2 text-[10px] uppercase border-4 border-iba-black shadow-[3px_3px_0_0_#131011] hover:translate-y-0.5 hover:shadow-none transition-all">Post Reply</button>
                        </form>
                    @endif
                </div>
            @empty
                <div class="p-8 border-4 border-dashed border-gray-300 dark:border-gray-700 text-center">
                    <p class="text-xs font-black uppercase tracking-widest text-gray-500">No comments yet. Start the conversation!</p>
                </div>
            @endforelse

            {{-- Main Add Comment Form (Always visible at bottom) --}}
            <form wire:submit.prevent="addComment()" class="mt-8 pt-6 border-t-2 border-dashed border-gray-300 dark:border-gray-700">
                <label class="block text-[10px] font-black uppercase tracking-widest text-gray-500 mb-2">Join the Conversation</label>
                <div class="flex flex-col sm:flex-row gap-3">
                    <div class="shrink-0">
                        <select wire:model="postingAs" class="w-full sm:w-48 border-4 border-iba-black dark:border-iba-light bg-gray-50 dark:bg-gray-800 text-iba-black dark:text-white text-[10px] font-black uppercase tracking-widest p-3 focus:outline-none focus:border-iba-teal h-full">
                            @foreach($availableIdentities as $value => $label)
                                <option value="{{ $value }}">{{ $value }}</option>
                            @endforeach
                        </select>
                    </div>
                    <input type="text" wire:model="newComments.main" placeholder="Write a comment..." class="flex-1 border-4 border-iba-black dark:border-iba-light p-3 text-sm focus:outline-none focus:border-iba-orange bg-white dark:bg-[#1A1617] text-iba-black dark:text-white font-bold">
                    <button type="submit" class="bg-iba-orange text-iba-black font-black px-6 py-3 text-xs uppercase border-4 border-iba-black shadow-[4px_4px_0_0_#131011] hover:translate-y-0.5 hover:shadow-none transition-all w-full sm:w-auto">Post Comment</button>
                </div>
            </form>
        </div>
    </div>
</div>