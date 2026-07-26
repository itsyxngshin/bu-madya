<div class="max-w-4xl mx-auto space-y-8 pb-24">
    
    {{-- Reusable Alpine Logic for Mentions --}}
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('mentionHandler', () => ({
                showDropdown: false,
                searchQuery: '',
                filteredMentions: [],
                mentionStartPoint: 0,
                mentionList: @js($mentionables),
                
                checkMention(e) {
                    const el = e.target;
                    const cursorPos = el.selectionStart;
                    const textBeforeCursor = el.value.substring(0, cursorPos);
                    const match = textBeforeCursor.match(/(?:\s|^)@([A-Za-z0-9_]*)$/);
                    
                    if (match) {
                        this.searchQuery = match[1].toLowerCase();
                        this.filteredMentions = this.mentionList.filter(m => m.tag.toLowerCase().includes(this.searchQuery) || m.display.toLowerCase().includes(this.searchQuery));
                        this.showDropdown = this.filteredMentions.length > 0;
                        this.mentionStartPoint = match.index + (textBeforeCursor.charAt(match.index) === '@' ? 0 : 1);
                    } else {
                        this.showDropdown = false;
                    }
                },
                insertMention(tag) {
                    const el = this.$refs.mentionInput;
                    const before = el.value.substring(0, this.mentionStartPoint);
                    const after = el.value.substring(el.selectionStart);
                    
                    el.value = before + '@' + tag + ' ' + after;
                    this.showDropdown = false;
                    
                    el.dispatchEvent(new Event('input'));
                    
                    this.$nextTick(() => {
                        el.focus();
                        const newPos = before.length + tag.length + 2;
                        el.setSelectionRange(newPos, newPos);
                    });
                }
            }));
        });
    </script>

    <div class="mb-4 flex items-center justify-between">
        <a href="{{ route('ibalong.community-logs') }}" class="inline-flex items-center gap-2 text-xs font-black text-gray-500 hover:text-iba-red uppercase tracking-widest transition-colors border-2 border-transparent hover:border-iba-red px-3 py-1.5">
            &larr; Back to Full Feed
        </a>
    </div>

    @if (session()->has('success'))
        <div class="bg-iba-green/10 border-l-4 border-iba-green p-4 flex items-center justify-between animate-pulse">
            <p class="text-sm font-bold text-iba-green uppercase tracking-wider">{{ session('success') }}</p>
        </div>
    @endif

    {{-- SINGLE POST FOCUS --}}
    <div class="bg-white dark:bg-[#1A1617] border-4 border-iba-black dark:border-iba-light shadow-[12px_12px_0_0_#0095AC] flex flex-col relative">
        
        @if($post->is_announcement)
            <div class="absolute -top-4 -left-4 bg-iba-red text-white font-black text-[10px] uppercase tracking-widest px-4 py-1.5 border-2 border-iba-black shadow-[2px_2px_0_0_#131011] transform -rotate-2 z-10">
                Official Announcement
            </div>
        @endif

        <div class="p-5 flex items-center justify-between border-b-2 border-dashed border-gray-200 dark:border-gray-800">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-iba-teal text-white flex items-center justify-center font-black text-lg border-2 border-iba-black shadow-[2px_2px_0_0_#131011] shrink-0">
                    {{ substr($post->author_display ?? $post->user->name ?? 'U', 0, 1) }}
                </div>
                <div>
                    <h4 class="font-black text-sm text-iba-black dark:text-white uppercase leading-tight">{{ $post->author_display ?? $post->user->name }}</h4>
                    <p class="text-[10px] font-bold text-gray-500 uppercase tracking-widest">
                        @if($post->is_announcement) Organizing Committee @else Hackathon Cohort @endif • {{ $post->created_at->diffForHumans() }}
                        @if($post->created_at != $post->updated_at)
                            <span class="ml-1">(Edited)</span>
                        @endif
                    </p>
                </div>
            </div>

            {{-- Post Edit/Delete Dropdown --}}
            @if($post->user_id === auth('ibalong')->id() || in_array(auth('ibalong')->user()->role_id, [1, 2]))
                <div x-data="{ open: false }" class="relative shrink-0">
                    <button @click="open = !open" @click.away="open = false" class="p-2 text-gray-400 hover:text-iba-black dark:hover:text-white transition-colors focus:outline-none">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 14a2 2 0 100-4 2 2 0 000 4zm-7 0a2 2 0 100-4 2 2 0 000 4zm14 0a2 2 0 100-4 2 2 0 000 4z"/></svg>
                    </button>
                    <div x-show="open" style="display: none;" class="absolute right-0 mt-2 w-32 bg-white dark:bg-gray-800 border-2 border-iba-black dark:border-iba-light shadow-[4px_4px_0_0_#131011] dark:shadow-[4px_4px_0_0_#FFFBF7] z-20 flex flex-col">
                        <button wire:click="editPost" @click="open = false" class="text-left px-4 py-2 text-xs font-black uppercase text-iba-black dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 border-b-2 border-iba-black dark:border-gray-600 transition-colors">
                            Edit Log
                        </button>
                        <button wire:click="confirmDeletePost" @click="open = false" class="text-left px-4 py-2 text-xs font-black uppercase text-iba-red hover:bg-red-50 dark:hover:bg-red-900/30 transition-colors">
                            Delete
                        </button>
                    </div>
                </div>
            @endif
        </div>

        <div class="p-6 md:p-8">
            @if($editingPostId === $post->id)
                <div class="space-y-3">
                    <textarea wire:model="editContent" rows="6" class="w-full border-4 border-iba-black dark:border-iba-light p-4 text-sm focus:outline-none focus:border-iba-orange bg-gray-50 dark:bg-gray-900 text-iba-black dark:text-white font-bold resize-none"></textarea>
                    @error('editContent') <span class="text-iba-red text-xs font-bold block">⚠ {{ $message }}</span> @enderror
                    
                    <div class="flex flex-col sm:flex-row gap-3 justify-end">
                        <button wire:click="cancelEdit" class="text-xs font-black uppercase tracking-widest text-gray-500 hover:text-iba-black dark:hover:text-white px-4 py-2 transition-colors">Cancel</button>
                        <button wire:click="updatePost" class="bg-iba-orange text-iba-black font-black px-6 py-2 text-xs uppercase border-4 border-iba-black shadow-[3px_3px_0_0_#131011] hover:translate-y-0.5 hover:shadow-none transition-all">Save Changes</button>
                    </div>
                </div>
            @else
                <div class="text-sm md:text-base font-bold text-gray-800 dark:text-gray-200 leading-relaxed whitespace-pre-wrap">{!! preg_replace('/@([A-Za-z0-9_]+)/', '<span class="text-iba-teal bg-iba-teal/10 px-1 py-0.5 border border-iba-teal border-dashed">$0</span>', e($post->content)) !!}</div>
                
                @if($post->images->count() > 0)
                    <div class="mt-8 grid gap-3 {{ $post->images->count() == 1 ? 'grid-cols-1' : ($post->images->count() == 2 ? 'grid-cols-2' : 'grid-cols-2 sm:grid-cols-3') }}">
                        @foreach($post->images as $image)
                            <div class="border-2 border-iba-black shadow-[4px_4px_0_0_#131011] overflow-hidden relative cursor-pointer group">
                                <img src="{{ Storage::url($image->image_path) }}" class="w-full h-auto object-cover transition-transform duration-500 group-hover:scale-105">
                            </div>
                        @endforeach
                    </div>
                @endif
            @endif
        </div>

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

        <div class="bg-gray-100 dark:bg-gray-800 border-t-4 border-iba-black dark:border-iba-light p-6 sm:p-8 space-y-6">
            <h3 class="text-sm font-black uppercase tracking-widest text-iba-black dark:text-white border-b-2 border-dashed border-gray-300 dark:border-gray-700 pb-2 mb-6">Discussion Thread</h3>

            @forelse($post->comments as $comment)
                <div class="flex flex-col gap-3">
                    <div class="flex gap-4">
                        <div class="w-10 h-10 bg-gray-900 text-white flex items-center justify-center font-black text-sm border-2 border-iba-black shrink-0">
                            {{ substr($comment->author_display ?? 'U', 0, 1) }}
                        </div>
                        <div class="bg-white dark:bg-gray-900 border-2 border-iba-black dark:border-gray-600 p-4 flex-1 shadow-[3px_3px_0_0_#131011]">
                            <div class="flex justify-between items-start mb-2 border-b border-gray-100 dark:border-gray-800 pb-2">
                                <div>
                                    <span class="text-xs font-black uppercase tracking-widest text-iba-black dark:text-white">{{ $comment->author_display }}</span>
                                    <span class="text-[10px] font-bold text-gray-400 uppercase block sm:inline sm:ml-2">{{ $comment->created_at->diffForHumans() }}</span>
                                    @if($comment->created_at != $comment->updated_at)
                                        <span class="text-[9px] font-bold text-gray-400 italic sm:ml-1">(Edited)</span>
                                    @endif
                                </div>

                                {{-- Comment Edit/Delete Dropdown --}}
                                @if($comment->user_id === auth('ibalong')->id() || in_array(auth('ibalong')->user()->role_id, [1, 2]))
                                    <div x-data="{ open: false }" class="relative shrink-0">
                                        <button @click="open = !open" @click.away="open = false" class="text-gray-400 hover:text-iba-black dark:hover:text-white transition-colors focus:outline-none">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 14a2 2 0 100-4 2 2 0 000 4zm-7 0a2 2 0 100-4 2 2 0 000 4zm14 0a2 2 0 100-4 2 2 0 000 4z"/></svg>
                                        </button>
                                        <div x-show="open" style="display: none;" class="absolute right-0 mt-1 w-28 bg-white dark:bg-gray-800 border-2 border-iba-black dark:border-iba-light shadow-[2px_2px_0_0_#131011] z-20 flex flex-col">
                                            <button wire:click="editComment({{ $comment->id }})" @click="open = false" class="text-left px-3 py-1.5 text-[10px] font-black uppercase text-iba-black dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 border-b-2 border-iba-black dark:border-gray-600 transition-colors">Edit</button>
                                            <button wire:click="confirmDeleteComment({{ $comment->id }})" @click="open = false" class="text-left px-3 py-1.5 text-[10px] font-black uppercase text-iba-red hover:bg-red-50 dark:hover:bg-red-900/30 transition-colors">Delete</button>
                                        </div>
                                    </div>
                                @endif
                            </div>

                            @if($editingCommentId === $comment->id)
                                <div class="mt-2 mb-2">
                                    <textarea wire:model="editCommentContent" rows="3" class="w-full border-2 border-iba-black dark:border-gray-500 p-3 text-sm focus:outline-none focus:border-iba-orange bg-white dark:bg-gray-800 text-iba-black dark:text-white resize-none"></textarea>
                                    @error('editCommentContent') <span class="text-iba-red text-[10px] font-bold block">⚠ {{ $message }}</span> @enderror
                                    <div class="flex gap-2 mt-2 justify-end">
                                        <button wire:click="cancelEditComment" class="text-[10px] font-black uppercase text-gray-500 hover:text-iba-black dark:hover:text-white transition-colors px-2 py-1">Cancel</button>
                                        <button wire:click="updateComment" class="bg-iba-orange text-iba-black font-black px-4 py-1.5 text-[10px] uppercase border-2 border-iba-black shadow-[2px_2px_0_0_#131011] hover:translate-y-0.5 hover:shadow-none transition-all">Save</button>
                                    </div>
                                </div>
                            @else
                                <p class="text-sm font-bold text-gray-700 dark:text-gray-300 leading-relaxed mb-3 whitespace-pre-wrap">{!! preg_replace('/@([A-Za-z0-9_]+)/', '<span class="text-iba-teal font-black">$0</span>', e($comment->content)) !!}</p>
                                <button wire:click="setReply({{ $comment->id }})" class="text-[10px] font-black uppercase text-gray-400 hover:text-iba-orange transition-colors flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/></svg> Reply
                                </button>
                            @endif
                        </div>
                    </div>

                    @if($comment->replies->count() > 0)
                        <div class="pl-14 space-y-3 mt-2">
                            @foreach($comment->replies as $reply)
                                <div class="flex gap-3 border-l-2 border-iba-orange pl-4">
                                    <div class="w-8 h-8 bg-gray-700 text-white flex items-center justify-center font-black text-[10px] border-2 border-iba-black shrink-0">
                                        {{ substr($reply->author_display ?? 'U', 0, 1) }}
                                    </div>
                                    <div class="bg-white dark:bg-gray-900 border-2 border-iba-black p-3 flex-1 shadow-[2px_2px_0_0_#FF8623]">
                                        <div class="flex justify-between items-start mb-1">
                                            <div>
                                                <span class="text-[10px] font-black uppercase tracking-widest text-iba-black dark:text-white">{{ $reply->author_display }}</span>
                                                <span class="text-[9px] font-bold text-gray-400 uppercase block sm:inline sm:ml-2">{{ $reply->created_at->diffForHumans() }}</span>
                                                @if($reply->created_at != $reply->updated_at)
                                                    <span class="text-[8px] font-bold text-gray-400 italic sm:ml-1">(Edited)</span>
                                                @endif
                                            </div>

                                            @if($reply->user_id === auth('ibalong')->id() || in_array(auth('ibalong')->user()->role_id, [1, 2]))
                                                <div x-data="{ open: false }" class="relative shrink-0">
                                                    <button @click="open = !open" @click.away="open = false" class="text-gray-400 hover:text-iba-black dark:hover:text-white transition-colors focus:outline-none">
                                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24"><path d="M12 14a2 2 0 100-4 2 2 0 000 4zm-7 0a2 2 0 100-4 2 2 0 000 4zm14 0a2 2 0 100-4 2 2 0 000 4z"/></svg>
                                                    </button>
                                                    <div x-show="open" style="display: none;" class="absolute right-0 mt-1 w-24 bg-white dark:bg-gray-800 border-2 border-iba-black dark:border-iba-light shadow-[2px_2px_0_0_#131011] z-20 flex flex-col">
                                                        <button wire:click="editComment({{ $reply->id }})" @click="open = false" class="text-left px-2 py-1 text-[9px] font-black uppercase text-iba-black dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 border-b-2 border-iba-black dark:border-gray-600 transition-colors">Edit</button>
                                                        <button wire:click="confirmDeleteComment({{ $reply->id }})" @click="open = false" class="text-left px-2 py-1 text-[9px] font-black uppercase text-iba-red hover:bg-red-50 dark:hover:bg-red-900/30 transition-colors">Delete</button>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>

                                        @if($editingCommentId === $reply->id)
                                            <div class="mt-1 mb-1">
                                                <textarea wire:model="editCommentContent" rows="2" class="w-full border-2 border-iba-black dark:border-gray-500 p-2 text-xs focus:outline-none focus:border-iba-orange bg-white dark:bg-gray-800 text-iba-black dark:text-white resize-none"></textarea>
                                                @error('editCommentContent') <span class="text-iba-red text-[10px] font-bold block">⚠ {{ $message }}</span> @enderror
                                                <div class="flex gap-2 mt-1 justify-end">
                                                    <button wire:click="cancelEditComment" class="text-[9px] font-black uppercase text-gray-500 hover:text-iba-black dark:hover:text-white transition-colors">Cancel</button>
                                                    <button wire:click="updateComment" class="bg-iba-orange text-iba-black font-black px-3 py-1 text-[9px] uppercase border-2 border-iba-black shadow-[1px_1px_0_0_#131011] hover:translate-y-0.5 hover:shadow-none transition-all">Save</button>
                                                </div>
                                            </div>
                                        @else
                                            <p class="text-xs font-bold text-gray-600 dark:text-gray-400 whitespace-pre-wrap">{!! preg_replace('/@([A-Za-z0-9_]+)/', '<span class="text-iba-teal font-black">$0</span>', e($reply->content)) !!}</p>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    @if($replyingTo === $comment->id)
                        <form wire:submit.prevent="addComment({{ $comment->id }})" class="pl-14 mt-2 flex flex-col sm:flex-row gap-2">
                            <div class="shrink-0">
                                <select wire:model="commentIdentities.{{ $comment->id }}" class="w-full sm:w-32 border-4 border-iba-black dark:border-gray-500 bg-gray-50 dark:bg-gray-800 text-iba-black dark:text-white text-[10px] font-black uppercase tracking-widest p-3 focus:outline-none focus:border-iba-orange h-full">
                                    @foreach($availableIdentities as $value => $label)
                                        <option value="{{ $value }}">{{ $value }}</option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div x-data="mentionHandler" class="relative w-full flex-1">
                                <input type="text" x-ref="mentionInput" wire:model="newComments.reply_{{ $comment->id }}" @input="checkMention" placeholder="Replying to {{ $comment->author_display }}... (Type @ to tag)" class="w-full border-4 border-iba-black dark:border-gray-500 p-3 text-xs focus:outline-none focus:border-iba-orange bg-white dark:bg-gray-800 text-iba-black dark:text-white font-bold">
                                <div x-show="showDropdown" @click.away="showDropdown = false" class="absolute bottom-full mb-1 z-50 w-full max-h-48 overflow-y-auto bg-white dark:bg-gray-800 border-4 border-iba-black dark:border-iba-light shadow-[4px_4px_0_0_#131011]" x-cloak>
                                    <template x-for="mention in filteredMentions" :key="mention.tag">
                                        <button type="button" @click.prevent="insertMention(mention.tag)" class="w-full text-left px-4 py-2 text-xs font-bold border-b-2 border-dashed border-gray-200 dark:border-gray-700 hover:bg-iba-teal hover:text-white transition-colors">
                                            <span x-text="mention.display"></span>
                                        </button>
                                    </template>
                                </div>
                            </div>
                            <button type="submit" class="bg-iba-orange text-iba-black font-black px-4 py-2 text-[10px] uppercase border-4 border-iba-black shadow-[3px_3px_0_0_#131011] hover:translate-y-0.5 hover:shadow-none transition-all w-full sm:w-auto">Post Reply</button>
                        </form>
                    @endif
                </div>
            @empty
                <div class="p-8 border-4 border-dashed border-gray-300 dark:border-gray-700 text-center">
                    <p class="text-xs font-black uppercase tracking-widest text-gray-500">No comments yet. Start the conversation!</p>
                </div>
            @endforelse

            <form wire:submit.prevent="addComment()" class="mt-8 pt-6 border-t-2 border-dashed border-gray-300 dark:border-gray-700">
                <label class="block text-[10px] font-black uppercase tracking-widest text-gray-500 mb-2">Join the Conversation</label>
                <div class="flex flex-col sm:flex-row gap-3">
                    <div class="shrink-0">
                        <select wire:model="commentIdentities.main" class="w-full sm:w-48 border-4 border-iba-black dark:border-iba-light bg-gray-50 dark:bg-gray-800 text-iba-black dark:text-white text-[10px] font-black uppercase tracking-widest p-3 focus:outline-none focus:border-iba-teal h-full">
                            @foreach($availableIdentities as $value => $label)
                                <option value="{{ $value }}">{{ $value }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div x-data="mentionHandler" class="relative w-full flex-1">
                        <input type="text" x-ref="mentionInput" wire:model="newComments.main" @input="checkMention" placeholder="Write a comment... (Type @ to tag)" class="w-full border-4 border-iba-black dark:border-iba-light p-3 text-sm focus:outline-none focus:border-iba-orange bg-white dark:bg-[#1A1617] text-iba-black dark:text-white font-bold">
                        <div x-show="showDropdown" @click.away="showDropdown = false" class="absolute bottom-full mb-1 z-50 w-full max-h-48 overflow-y-auto bg-white dark:bg-gray-800 border-4 border-iba-black dark:border-iba-light shadow-[4px_4px_0_0_#131011]" x-cloak>
                            <template x-for="mention in filteredMentions" :key="mention.tag">
                                <button type="button" @click.prevent="insertMention(mention.tag)" class="w-full text-left px-4 py-2 text-xs font-bold border-b-2 border-dashed border-gray-200 dark:border-gray-700 hover:bg-iba-teal hover:text-white transition-colors">
                                    <span x-text="mention.display"></span>
                                </button>
                            </template>
                        </div>
                    </div>

                    <button type="submit" class="bg-iba-orange text-iba-black font-black px-6 py-3 text-xs uppercase border-4 border-iba-black shadow-[4px_4px_0_0_#131011] hover:translate-y-0.5 hover:shadow-none transition-all w-full sm:w-auto">Post Comment</button>
                </div>
            </form>
        </div>
    </div>

    {{-- POST DELETE CONFIRMATION MODAL --}}
    @if($postToDelete)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="fixed inset-0 bg-gray-900/70 backdrop-blur-sm" wire:click="cancelDeletePost"></div>
            <div class="relative w-full max-w-md bg-white dark:bg-[#1A1617] border-4 border-iba-black dark:border-iba-light shadow-[10px_10px_0_0_#D93B3B] p-8 text-center animate-fade-in-up z-10">
                <div class="mx-auto w-16 h-16 bg-iba-red border-4 border-iba-black flex items-center justify-center shadow-[4px_4px_0_0_#131011] mb-6">
                    <svg class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                </div>
                <h3 class="text-2xl font-black font-pixel text-iba-black dark:text-white uppercase mb-2">Delete Log?</h3>
                <p class="text-sm font-bold text-gray-600 dark:text-gray-400 mb-8">This action is permanent and cannot be undone. All associated images, likes, and comments will be wiped.</p>
                <div class="flex flex-col sm:flex-row justify-center gap-4">
                    <button wire:click="cancelDeletePost" class="px-6 py-3 text-xs font-black uppercase tracking-widest text-gray-600 dark:text-gray-400 hover:text-iba-black dark:hover:text-white transition-colors">Cancel</button>
                    <button wire:click="deletePost" class="bg-iba-red text-white font-black px-6 py-3 text-xs uppercase border-4 border-iba-black shadow-[4px_4px_0_0_#131011] hover:translate-y-0.5 hover:shadow-none transition-all">Yes, Delete It</button>
                </div>
            </div>
        </div>
    @endif

    {{-- COMMENT DELETE CONFIRMATION MODAL --}}
    @if($commentToDelete)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="fixed inset-0 bg-gray-900/70 backdrop-blur-sm" wire:click="cancelDeleteComment"></div>
            <div class="relative w-full max-w-sm bg-white dark:bg-[#1A1617] border-4 border-iba-black dark:border-iba-light shadow-[10px_10px_0_0_#D93B3B] p-6 text-center animate-fade-in-up z-10">
                <h3 class="text-xl font-black font-pixel text-iba-black dark:text-white uppercase mb-2">Delete Comment?</h3>
                <p class="text-xs font-bold text-gray-600 dark:text-gray-400 mb-6">Are you sure? This cannot be undone.</p>
                <div class="flex flex-col sm:flex-row justify-center gap-3">
                    <button wire:click="cancelDeleteComment" class="px-4 py-2 text-xs font-black uppercase tracking-widest text-gray-600 dark:text-gray-400 hover:text-iba-black dark:hover:text-white transition-colors">Cancel</button>
                    <button wire:click="deleteComment" class="bg-iba-red text-white font-black px-4 py-2 text-xs uppercase border-4 border-iba-black shadow-[4px_4px_0_0_#131011] hover:translate-y-0.5 hover:shadow-none transition-all">Delete</button>
                </div>
            </div>
        </div>
    @endif
</div>