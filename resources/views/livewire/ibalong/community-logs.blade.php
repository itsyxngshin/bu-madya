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

    {{-- Header & Sorting Toggle --}}
    <div class="bg-white dark:bg-[#1A1617] p-6 border-4 border-iba-black dark:border-iba-light shadow-[8px_8px_0_0_#131011] dark:shadow-[8px_8px_0_0_#FFFBF7] flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl font-black text-iba-black dark:text-iba-light uppercase tracking-wider">Community Logs</h1>
            <p class="text-sm font-bold text-gray-500 dark:text-gray-400 mt-1">Share updates, ask questions, and document your hackathon journey.</p>
        </div>

        <div class="flex items-center bg-gray-100 dark:bg-gray-800 border-4 border-iba-black dark:border-iba-light p-1 shadow-[4px_4px_0_0_#131011] shrink-0">
            <button wire:click="$set('filterType', 'latest')" class="px-4 py-2 text-[10px] font-black uppercase tracking-widest transition-all {{ $filterType === 'latest' ? 'bg-iba-teal text-white border-2 border-iba-black shadow-sm' : 'text-gray-500 hover:text-iba-black dark:hover:text-white border-2 border-transparent' }}">Latest</button>
            <button wire:click="$set('filterType', 'trending')" class="px-4 py-2 text-[10px] font-black uppercase tracking-widest transition-all {{ $filterType === 'trending' ? 'bg-iba-orange text-iba-black border-2 border-iba-black shadow-sm' : 'text-gray-500 hover:text-iba-black dark:hover:text-white border-2 border-transparent' }}">Trending</button>
        </div>
    </div>

    @if (session()->has('success'))
        <div class="bg-iba-green/10 border-l-4 border-iba-green p-4 flex items-center justify-between animate-pulse">
            <p class="text-sm font-bold text-iba-green uppercase tracking-wider">{{ session('success') }}</p>
        </div>
    @endif

    {{-- CREATE POST COMPONENT --}}
    <div class="bg-white dark:bg-[#1A1617] border-4 border-iba-black dark:border-iba-light shadow-[8px_8px_0_0_#0095AC] p-6">
        <form wire:submit.prevent="createPost">
            <div class="mb-4 flex flex-col sm:flex-row sm:items-center gap-3">
                <span class="text-xs font-black uppercase tracking-widest text-gray-500">Posting As:</span>
                <select wire:model="postingAs" class="border-2 border-iba-black dark:border-iba-light bg-gray-50 dark:bg-gray-800 text-iba-black dark:text-white text-xs font-bold p-2 focus:outline-none focus:border-iba-teal appearance-none cursor-pointer pr-8 w-full sm:w-auto">
                    @foreach($availableIdentities as $value => $label)
                        <option value="{{ $value }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <div x-data="mentionHandler" class="relative w-full">
                <textarea x-ref="mentionInput" wire:model="content" @input="checkMention" rows="3" placeholder="What's happening? Type @ to tag a team or organizer..." class="w-full border-4 border-iba-black dark:border-iba-light p-4 text-sm focus:outline-none focus:border-iba-teal bg-gray-50 dark:bg-gray-900 text-iba-black dark:text-white font-bold resize-none"></textarea>
                
                <div x-show="showDropdown" @click.away="showDropdown = false" class="absolute z-50 w-full max-h-48 overflow-y-auto bg-white dark:bg-gray-800 border-4 border-iba-black dark:border-iba-light shadow-[4px_4px_0_0_#131011] mt-1" x-cloak>
                    <template x-for="mention in filteredMentions" :key="mention.tag">
                        <button type="button" @click.prevent="insertMention(mention.tag)" class="w-full text-left px-4 py-2 text-xs font-bold border-b-2 border-dashed border-gray-200 dark:border-gray-700 hover:bg-iba-teal hover:text-white transition-colors group flex justify-between items-center">
                            <span x-text="mention.display" class="text-iba-black dark:text-white group-hover:text-white"></span>
                            <span class="text-[10px] text-gray-500 group-hover:text-gray-200" x-text="'@' + mention.tag"></span>
                        </button>
                    </template>
                </div>
            </div>
            @error('content') <span class="text-iba-red text-xs font-bold block mt-1">⚠ {{ $message }}</span> @enderror
            
            {{-- NEW: Announcement Toggle (Only visible to Admins/Role 1 & 2) --}}
            @if(in_array(auth('ibalong')->user()->role_id, [1, 2]))
                <div class="mt-3 flex items-center gap-2 bg-iba-red/10 border-2 border-dashed border-iba-red p-2 inline-flex">
                    <input type="checkbox" id="isAnnouncement" wire:model="isAnnouncement" class="w-4 h-4 text-iba-red border-2 border-iba-black focus:ring-0 rounded-none bg-white checked:bg-iba-red cursor-pointer">
                    <label for="isAnnouncement" class="text-[10px] font-black uppercase tracking-widest text-iba-red cursor-pointer">Post as Official Announcement (Notifies all users)</label>
                </div>
            @endif

            @if($photos)
                <div class="flex flex-wrap gap-3 mt-4">
                    @foreach($photos as $photo)
                        <div class="w-20 h-20 border-2 border-iba-black relative overflow-hidden shadow-[2px_2px_0_0_#131011]">
                            <img src="{{ $photo->temporaryUrl() }}" class="w-full h-full object-cover">
                        </div>
                    @endforeach
                </div>
            @endif

            <div class="mt-4 flex flex-col sm:flex-row sm:items-center justify-between pt-4 border-t-2 border-dashed border-gray-300 dark:border-gray-700 gap-4">
                <div>
                    <input type="file" id="photo-upload" wire:model="photos" multiple accept="image/*" class="hidden">
                    <label for="photo-upload" class="cursor-pointer inline-flex items-center gap-2 text-xs font-black uppercase tracking-widest text-iba-teal hover:text-teal-700 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg> Attach Photos
                    </label>
                    <div wire:loading wire:target="photos" class="text-[10px] font-bold text-iba-orange ml-2 animate-pulse uppercase">Uploading...</div>
                </div>
                <button type="submit" class="bg-iba-black dark:bg-iba-light text-white dark:text-iba-black font-black px-6 py-2.5 text-xs uppercase border-4 border-transparent shadow-[4px_4px_0_0_#0095AC] hover:translate-y-0.5 hover:shadow-none transition-all active:translate-y-1 w-full sm:w-auto text-center" wire:loading.attr="disabled">
                    Publish Log
                </button>
            </div>
        </form>
    </div>

    {{-- FEED LOOP --}}
    <div class="space-y-8">
        @forelse($posts as $post)
            <div class="bg-white dark:bg-[#1A1617] border-4 border-iba-black dark:border-iba-light shadow-[8px_8px_0_0_#131011] dark:shadow-[8px_8px_0_0_#FFFBF7] flex flex-col relative">
                
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
                            </p>
                        </div>
                    </div>

                    @if($post->user_id === auth('ibalong')->id() || in_array(auth('ibalong')->user()->role_id, [1, 2]))
                        <div x-data="{ open: false }" class="relative shrink-0">
                            <button @click="open = !open" @click.away="open = false" class="p-2 text-gray-400 hover:text-iba-black dark:hover:text-white transition-colors focus:outline-none">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 14a2 2 0 100-4 2 2 0 000 4zm-7 0a2 2 0 100-4 2 2 0 000 4zm14 0a2 2 0 100-4 2 2 0 000 4z"/></svg>
                            </button>
                            <div x-show="open" style="display: none;" class="absolute right-0 mt-2 w-32 bg-white dark:bg-gray-800 border-2 border-iba-black dark:border-iba-light shadow-[4px_4px_0_0_#131011] dark:shadow-[4px_4px_0_0_#FFFBF7] z-20 flex flex-col">
                                <button wire:click="editPost({{ $post->id }})" @click="open = false" class="text-left px-4 py-2 text-xs font-black uppercase text-iba-black dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 border-b-2 border-iba-black dark:border-gray-600 transition-colors">
                                    Edit Log
                                </button>
                                <button wire:click="confirmDelete({{ $post->id }})" @click="open = false" class="text-left px-4 py-2 text-xs font-black uppercase text-iba-red hover:bg-red-50 dark:hover:bg-red-900/30 transition-colors">
                                    Delete
                                </button>
                            </div>
                        </div>
                    @endif
                </div>

                <div class="p-5" x-data="{ expanded: false }">
                    @if($editingPostId === $post->id)
                        <div class="space-y-3">
                            <textarea wire:model="editContent" rows="4" class="w-full border-4 border-iba-black dark:border-iba-light p-4 text-sm focus:outline-none focus:border-iba-orange bg-gray-50 dark:bg-gray-900 text-iba-black dark:text-white font-bold resize-none"></textarea>
                            @error('editContent') <span class="text-iba-red text-xs font-bold block">⚠ {{ $message }}</span> @enderror
                            
                            <div class="flex flex-col sm:flex-row gap-3 justify-end">
                                <button wire:click="cancelEdit" class="text-xs font-black uppercase tracking-widest text-gray-500 hover:text-iba-black dark:hover:text-white px-4 py-2 transition-colors">Cancel</button>
                                <button wire:click="updatePost" class="bg-iba-orange text-iba-black font-black px-6 py-2 text-xs uppercase border-4 border-iba-black shadow-[3px_3px_0_0_#131011] hover:translate-y-0.5 hover:shadow-none transition-all">Save Changes</button>
                            </div>
                        </div>
                    @else
                        <div class="text-sm font-bold text-gray-800 dark:text-gray-200 leading-relaxed whitespace-pre-wrap transition-all duration-300"
                             :class="expanded ? '' : 'line-clamp-4'">{!! preg_replace('/@([A-Za-z0-9_]+)/', '<span class="text-iba-teal bg-iba-teal/10 px-1 py-0.5 border border-iba-teal border-dashed">$0</span>', e($post->content)) !!}</div>
                        
                        @if(strlen($post->content) > 300)
                            <button @click="expanded = !expanded" class="text-iba-teal text-[10px] font-black uppercase tracking-widest mt-2 hover:underline focus:outline-none" x-text="expanded ? 'SEE LESS ↑' : 'SEE MORE ↓'"></button>
                        @endif
                        
                        @if($post->images->count() > 0)
                            <div class="mt-4 grid gap-2 {{ $post->images->count() == 1 ? 'grid-cols-1' : ($post->images->count() == 2 ? 'grid-cols-2' : 'grid-cols-2 sm:grid-cols-3') }}">
                                @foreach($post->images as $image)
                                    <div class="border-2 border-iba-black shadow-[2px_2px_0_0_#131011] overflow-hidden aspect-square relative group">
                                        <img src="{{ Storage::url($image->image_path) }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    @endif
                </div>

                <div class="bg-gray-50 dark:bg-gray-900 border-t-2 border-iba-black dark:border-iba-light p-3 flex justify-between items-center gap-6">
                    <div class="flex gap-6">
                        <button wire:click="toggleLike({{ $post->id }})" class="flex items-center gap-1.5 text-xs font-black uppercase tracking-widest transition-colors {{ $post->likes->contains('user_id', auth('ibalong')->id()) ? 'text-iba-red' : 'text-gray-500 hover:text-iba-black dark:hover:text-white' }}">
                            <svg class="w-5 h-5 {{ $post->likes->contains('user_id', auth('ibalong')->id()) ? 'fill-current' : 'fill-none stroke-currentColor' }}" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg> {{ $post->likes_count }}
                        </button>
                        <a href="{{ route('ibalong.community-logs.show', $post->id) }}" class="flex items-center gap-1.5 text-xs font-black uppercase tracking-widest text-gray-500 hover:text-iba-teal transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg> {{ $post->comments_count }}
                        </a>
                    </div>
                    
                    <a href="{{ route('ibalong.community-logs.show', $post->id) }}" class="text-[9px] font-black uppercase tracking-widest text-gray-400 hover:text-iba-orange flex items-center gap-1">
                        Open Thread <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                    </a>
                </div>

                <div class="bg-gray-100 dark:bg-gray-800 border-t-2 border-iba-black dark:border-iba-light p-5 space-y-4">
                    @foreach($post->comments->take(2) as $comment)
                        <div class="flex flex-col gap-2">
                            <div class="flex gap-3">
                                <div class="w-8 h-8 bg-gray-900 text-white flex items-center justify-center font-black text-xs border border-iba-black shrink-0">
                                    {{ substr($comment->author_display ?? 'U', 0, 1) }}
                                </div>
                                <div class="bg-white dark:bg-gray-900 border-2 border-iba-black dark:border-gray-600 p-3 flex-1 shadow-[2px_2px_0_0_#131011]">
                                    <div class="flex justify-between items-end mb-1">
                                        <span class="text-[10px] font-black uppercase tracking-widest text-iba-black dark:text-white">{{ $comment->author_display }}</span>
                                        <span class="text-[9px] font-bold text-gray-400">{{ $comment->created_at->diffForHumans() }}</span>
                                    </div>
                                    <p class="text-xs font-bold text-gray-700 dark:text-gray-300 leading-relaxed mb-2 whitespace-pre-wrap">{!! preg_replace('/@([A-Za-z0-9_]+)/', '<span class="text-iba-teal">$0</span>', e($comment->content)) !!}</p>
                                    <button wire:click="setReply({{ $comment->id }})" class="text-[9px] font-black uppercase text-gray-400 hover:text-iba-orange transition-colors">Reply ↳</button>
                                </div>
                            </div>

                            @if($comment->replies->count() > 0)
                                <div class="pl-11 space-y-2 mt-1">
                                    @foreach($comment->replies->take(1) as $reply)
                                        <div class="flex gap-3 border-l-2 border-iba-orange pl-3">
                                            <div class="w-6 h-6 bg-gray-700 text-white flex items-center justify-center font-black text-[9px] border border-iba-black shrink-0">
                                                {{ substr($reply->author_display ?? 'U', 0, 1) }}
                                            </div>
                                            <div class="bg-white dark:bg-gray-900 border border-iba-black p-2 flex-1 shadow-[1px_1px_0_0_#FF8623]">
                                                <div class="flex justify-between items-end mb-1">
                                                    <span class="text-[9px] font-black uppercase tracking-widest text-iba-black">{{ $reply->author_display }}</span>
                                                </div>
                                                <p class="text-[11px] font-bold text-gray-600 dark:text-gray-400 whitespace-pre-wrap">{!! preg_replace('/@([A-Za-z0-9_]+)/', '<span class="text-iba-teal">$0</span>', e($reply->content)) !!}</p>
                                            </div>
                                        </div>
                                    @endforeach
                                    @if($comment->replies->count() > 1)
                                        <a href="{{ route('ibalong.community-logs.show', $post->id) }}" class="text-[9px] font-black uppercase tracking-widest text-iba-teal pl-3 hover:underline block mt-2">View {{ $comment->replies->count() - 1 }} more replies...</a>
                                    @endif
                                </div>
                            @endif

                            @if($replyingTo === $comment->id)
                                <form wire:submit.prevent="addComment({{ $post->id }}, {{ $comment->id }})" class="pl-11 mt-1 flex flex-col sm:flex-row gap-2">
                                    <div x-data="mentionHandler" class="relative w-full flex-1">
                                        <input type="text" x-ref="mentionInput" wire:model="newComments.reply_{{ $comment->id }}" @input="checkMention" placeholder="Replying to {{ $comment->author_display }}... (Type @ to tag)" class="w-full border-2 border-iba-black dark:border-gray-500 p-2 text-xs focus:outline-none focus:border-iba-orange bg-white dark:bg-gray-800 text-iba-black dark:text-white">
                                        <div x-show="showDropdown" @click.away="showDropdown = false" class="absolute bottom-full mb-1 z-50 w-full max-h-48 overflow-y-auto bg-white dark:bg-gray-800 border-4 border-iba-black dark:border-iba-light shadow-[4px_4px_0_0_#131011]" x-cloak>
                                            <template x-for="mention in filteredMentions" :key="mention.tag">
                                                <button type="button" @click.prevent="insertMention(mention.tag)" class="w-full text-left px-4 py-2 text-xs font-bold border-b-2 border-dashed border-gray-200 dark:border-gray-700 hover:bg-iba-teal hover:text-white transition-colors">
                                                    <span x-text="mention.display"></span>
                                                </button>
                                            </template>
                                        </div>
                                    </div>
                                    <button type="submit" class="bg-iba-orange text-iba-black font-black px-3 py-1.5 text-[10px] uppercase border-2 border-iba-black shadow-[2px_2px_0_0_#131011] hover:translate-y-0.5 hover:shadow-none transition-all w-full sm:w-auto text-center">Post</button>
                                </form>
                            @endif
                        </div>
                    @endforeach

                    @if($post->comments->count() > 2)
                        <a href="{{ route('ibalong.community-logs.show', $post->id) }}" class="block text-center mt-4 text-[10px] font-black uppercase tracking-widest text-gray-500 hover:text-iba-teal transition-colors">
                            View all {{ $post->comments->count() }} comments
                        </a>
                    @endif

                    <form wire:submit.prevent="addComment({{ $post->id }})" class="mt-4 pt-4 border-t-2 border-dashed border-gray-300 dark:border-gray-700">
                        <div class="flex flex-col sm:flex-row gap-3">
                            <div class="shrink-0">
                                <select wire:model="commentIdentities.{{ $post->id }}" class="w-full sm:w-32 border-4 border-iba-black dark:border-iba-light bg-gray-50 dark:bg-gray-800 text-iba-black dark:text-white text-[10px] font-black uppercase tracking-widest p-2 focus:outline-none focus:border-iba-teal h-full">
                                    <option value="" disabled>Reply As...</option>
                                    @foreach($availableIdentities as $value => $label)
                                        <option value="{{ $value }}">{{ $value }}</option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div x-data="mentionHandler" class="relative w-full flex-1">
                                <input type="text" x-ref="mentionInput" wire:model="newComments.{{ $post->id }}" @input="checkMention" placeholder="Write a comment... (Type @ to tag)" class="w-full border-4 border-iba-black dark:border-iba-light p-2 text-xs focus:outline-none focus:border-iba-orange bg-white dark:bg-[#1A1617] text-iba-black dark:text-white font-bold">
                                <div x-show="showDropdown" @click.away="showDropdown = false" class="absolute bottom-full mb-1 z-50 w-full max-h-48 overflow-y-auto bg-white dark:bg-gray-800 border-4 border-iba-black dark:border-iba-light shadow-[4px_4px_0_0_#131011]" x-cloak>
                                    <template x-for="mention in filteredMentions" :key="mention.tag">
                                        <button type="button" @click.prevent="insertMention(mention.tag)" class="w-full text-left px-4 py-2 text-xs font-bold border-b-2 border-dashed border-gray-200 dark:border-gray-700 hover:bg-iba-teal hover:text-white transition-colors">
                                            <span x-text="mention.display"></span>
                                        </button>
                                    </template>
                                </div>
                            </div>

                            <button type="submit" class="bg-iba-orange text-iba-black font-black px-4 py-2 text-xs uppercase border-4 border-iba-black shadow-[3px_3px_0_0_#131011] hover:translate-y-0.5 hover:shadow-none transition-all w-full sm:w-auto">Reply</button>
                        </div>
                    </form>
                </div>
            </div>
        @empty
            <div class="bg-white dark:bg-[#1A1617] border-4 border-iba-black border-dashed p-12 text-center shadow-sm">
                <p class="text-sm font-black text-gray-500 uppercase tracking-widest">No logs match your filter criteria.</p>
            </div>
        @endforelse

        {{-- PAGINATION --}}
        <div class="mt-8">
            {{ $posts->links() }}
        </div>
    </div>

    {{-- DELETE CONFIRMATION MODAL --}}
    @if($postToDelete)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="fixed inset-0 bg-gray-900/70 backdrop-blur-sm" wire:click="cancelDelete"></div>
            
            <div class="relative w-full max-w-md bg-white dark:bg-[#1A1617] border-4 border-iba-black dark:border-iba-light shadow-[10px_10px_0_0_#D93B3B] p-8 text-center animate-fade-in-up z-10">
                <div class="mx-auto w-16 h-16 bg-iba-red border-4 border-iba-black flex items-center justify-center shadow-[4px_4px_0_0_#131011] mb-6">
                    <svg class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                </div>
                
                <h3 class="text-2xl font-black font-pixel text-iba-black dark:text-white uppercase mb-2">Delete Log?</h3>
                <p class="text-sm font-bold text-gray-600 dark:text-gray-400 mb-8">This action is permanent and cannot be undone. All associated images, likes, and comments will be wiped.</p>
                
                <div class="flex flex-col sm:flex-row justify-center gap-4">
                    <button wire:click="cancelDelete" class="px-6 py-3 text-xs font-black uppercase tracking-widest text-gray-600 dark:text-gray-400 hover:text-iba-black dark:hover:text-white transition-colors">Cancel</button>
                    <button wire:click="deletePost" class="bg-iba-red text-white font-black px-6 py-3 text-xs uppercase border-4 border-iba-black shadow-[4px_4px_0_0_#131011] hover:translate-y-0.5 hover:shadow-none transition-all">Yes, Delete It</button>
                </div>
            </div>
        </div>
    @endif
</div>