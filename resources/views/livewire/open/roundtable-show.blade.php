<div class="min-h-screen bg-gray-100 font-sans text-gray-900 pb-32 relative overflow-x-hidden" wire:poll.10s>
    
    {{-- 1. BACKGROUND ATMOSPHERE --}}
    <div class="fixed inset-0 z-0 overflow-hidden pointer-events-none">
        <div class="absolute top-[-10%] left-[-10%] w-[500px] h-[500px] bg-red-200/40 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob"></div>
        <div class="absolute top-[-10%] right-[-10%] w-[500px] h-[500px] bg-yellow-200/40 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob animation-delay-2000"></div>
        <div class="absolute bottom-[-10%] left-[20%] w-[500px] h-[500px] bg-blue-200/40 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob animation-delay-4000"></div>
    </div>

    {{-- 2. STICKY HEADER --}}
    <div class="sticky top-0 w-full z-40 bg-gray-100/95 backdrop-blur-md border-b border-white/50 px-4 py-3 shadow-sm transition-all">
        <div class="max-w-6xl mx-auto flex justify-between items-center">
            {{-- Back Button (Icon only on mobile) --}}
            <a href="{{ route('roundtable.index') }}" 
               class="flex items-center gap-2 text-sm font-bold text-gray-600 hover:text-gray-900 transition group">
                <div class="bg-white p-2 md:p-1.5 rounded-full shadow-sm group-hover:bg-gray-200 transition border border-gray-100">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                </div>
                <span class="hidden sm:inline uppercase tracking-wider text-xs">Return to Hall</span>
            </a>
            
            {{-- Live Indicator --}}
            <div class="bg-red-50 border border-red-100 px-3 py-1 rounded-full flex items-center gap-2 shadow-sm">
                <span class="relative flex h-2 w-2">
                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                  <span class="relative inline-flex rounded-full h-2 w-2 bg-red-500"></span>
                </span>
                <span class="text-[10px] font-black uppercase tracking-widest text-red-600">Live</span>
            </div>
        </div>
    </div>

    {{-- 3. MAIN CONTENT AREA --}}
    <div class="max-w-6xl mx-auto px-4 pt-4 md:pt-6 relative z-10">
        
        {{-- A. ORIGINAL TOPIC CARD --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200/60 overflow-hidden mb-6 md:mb-8 relative group">
            
            {{-- ADMIN/OWNER ACTION MENU (Top Right) --}}
            @if(auth()->id() === $topic->user_id || auth()->user()->is_admin)
                <div class="absolute top-3 right-3 z-20" x-data="{ open: false }">
                    <button @click="open = !open" @click.away="open = false" class="p-1 text-gray-400 hover:text-gray-900 bg-white/50 hover:bg-white rounded-full transition backdrop-blur-sm">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"></path></svg>
                    </button>

                    <div x-show="open" style="display: none;" 
                        class="absolute right-0 mt-1 w-40 bg-white border border-gray-100 rounded-lg shadow-xl py-1 overflow-hidden animate-fade-in-up origin-top-right">
                        
                        {{-- Delete Button --}}
                        <button @click="confirmAction('deleteTopic', null, 'Delete this Topic?', 'This will remove the entire discussion permanently.')" 
                                wire:confirm="Are you sure you want to delete this entire topic? This cannot be undone."
                                class="w-full text-left px-4 py-3 text-xs font-bold text-red-600 hover:bg-red-50 flex items-center gap-2 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            Delete Topic
                        </button>
                    </div>
                </div>
            @endif

            <div class="flex">
                {{-- Left Sidebar (Hidden on mobile) --}}
                <div class="hidden md:flex w-12 bg-gray-50/50 border-r border-gray-100 flex-col items-center py-4 gap-1">
                    <div class="text-gray-300 p-1"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path></svg></div>
                    <span class="text-xs font-bold text-gray-400">OP</span>
                    <div class="text-gray-300 p-1"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg></div>
                </div>

                {{-- Topic Content --}}
                <div class="flex-1 p-4 md:p-6">
                    {{-- Header Meta --}}
                    <div class="flex flex-wrap items-center gap-2 mb-3 text-xs text-gray-500 pr-8"> {{-- Added pr-8 to avoid overlap with menu --}}
                        <img class="w-6 h-6 md:w-5 md:h-5 rounded-full object-cover ring-2 ring-gray-100 md:ring-0 bg-gray-100"
                            src="{{ 
                                $topic->user->profile_photo_path 
                                    ? (
                                        Str::startsWith($topic->user->profile_photo_path, 'http') 
                                            ? $topic->user->profile_photo_path 
                                            : (
                                                Str::startsWith($topic->user->profile_photo_path, 'images/') 
                                                    ? asset($topic->user->profile_photo_path) 
                                                    : asset('storage/' . $topic->user->profile_photo_path)
                                            )
                                    ) 
                                    : 'https://ui-avatars.com/api/?name='.urlencode($topic->user->name).'&color=7F9CF5&background=EBF4FF' 
                            }}" 
                            alt="{{ $topic->user->name }}">
                        <span class="flex items-center gap-1">
                            <span class="font-bold text-gray-700">{{ $topic->user->name }}</span>
                            <span class="text-red-500 font-bold bg-red-50 px-1.5 py-0.5 rounded border border-red-100 uppercase text-[9px] tracking-wider">Host</span>
                        </span>
                        <span class="text-gray-300">•</span>
                        <span>{{ $topic->created_at->diffForHumans() }}</span>
                    </div>

                    <h1 class="font-heading font-black text-xl md:text-2xl text-gray-900 leading-tight mb-4">
                        {{ $topic->headline }}
                    </h1>

                    <div class="prose prose-red prose-sm max-w-none text-gray-800 leading-relaxed mb-6 break-words">
                        {!! nl2br(e($topic->content)) !!}
                    </div>

                    {{-- Topic Footer --}}
                    <div class="flex items-center gap-4 border-t border-gray-100 pt-3">
                        <div class="flex items-center gap-1 text-gray-500 text-xs font-bold uppercase tracking-wide">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path></svg>
                            {{ count($topic->roundtable_replies) }} Comments
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- B. DISCUSSION STREAM --}}
        <div class="space-y-6 mt-8">
        
        {{-- Header Separator --}}
        <div class="flex items-center justify-between px-1">
            <h3 class="text-sm font-bold uppercase tracking-widest text-gray-500">
                {{ count($topic->roundtable_replies) }} Responses
            </h3>
            
            {{-- Optional Sort Dropdown could go here --}}
            <div class="text-xs text-gray-400">
                Sorted by: <span class="font-bold text-gray-600">Time</span>
            </div>
        </div>

        @forelse($topic->roundtable_replies as $reply)
            @php 
                $myVote = $reply->userVote(auth()->id()); 
                $isHost = $reply->user_id === $topic->user_id;
                
                // Check if user is owner OR admin (assuming 'is_admin' boolean or role check)
                $isMe = $reply->user_id === auth()->id();
                $isAdmin = auth()->user()->is_admin; // Or auth()->user()->role_id === 1, etc.
                
                $canModerate = $isMe || $isAdmin;
            @endphp

            {{-- FORUM POST CARD --}}
            <div id="reply-{{ $reply->id }}" 
                 class="group bg-white border rounded-lg shadow-sm overflow-hidden transition-all duration-300 hover:shadow-md
                 {{ $isHost ? 'border-red-200 ring-1 ring-red-50' : ($isMe ? 'border-yellow-300' : 'border-gray-200') }}">
                
                {{-- 1. POST HEADER (User Info) --}}
                <div class="px-4 py-3 bg-gray-50/80 border-b border-gray-100 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        {{-- Avatar --}}
                        <img src="{{ 
                                $reply->user->profile_photo_path 
                                    ? (
                                        Str::startsWith($reply->user->profile_photo_path, 'http') 
                                            ? $reply->user->profile_photo_path 
                                            : (
                                                Str::startsWith($reply->user->profile_photo_path, 'images/') 
                                                    ? asset($reply->user()->profile_photo_path) 
                                                    : asset('storage/' . $reply->user()->profile_photo_path)
                                            )
                                    ) 
                                    : 'https://ui-avatars.com/api/?name='.urlencode($reply->user()->name).'&color=7F9CF5&background=EBF4FF' 
                            }}" 
                            alt="{{ auth()->user()->name }}"
                             class="w-8 h-8 rounded-full object-cover border border-gray-200 shadow-sm">
                        
                        <div class="flex flex-col md:flex-row md:items-baseline md:gap-2">
                            <span class="text-sm font-bold text-gray-900">{{ $reply->user->name }}</span>
                            
                            {{-- Badges --}}
                            <div class="flex items-center gap-1">
                                @if($isHost)
                                    <span class="text-[9px] font-black uppercase tracking-wider text-red-700 bg-red-100 px-1.5 py-0.5 rounded border border-red-200">Host</span>
                                @endif
                                @if($isMe)
                                    <span class="text-[9px] font-black uppercase tracking-wider text-yellow-700 bg-yellow-100 px-1.5 py-0.5 rounded border border-yellow-200">You</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Meta / Actions --}}
                    <div class="flex items-center gap-3">
                        <span class="text-xs text-gray-400 font-mono" title="{{ $reply->created_at }}">
                            {{ $reply->created_at->diffForHumans() }}
                        </span>

                        {{-- Kebab Menu --}}
                        @if($canModerate)
                            <div x-data="{ open: false }" class="relative">
                                <button @click="open = !open" @click.away="open = false" class="text-gray-300 hover:text-gray-600 transition">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"></path></svg>
                                </button>
                                
                                <div x-show="open" style="display: none;" class="absolute right-0 mt-1 w-32 bg-white border border-gray-100 rounded-lg shadow-lg z-20 py-1">
                                    
                                    {{-- Edit: Only show if it's actually their own post (Admins shouldn't edit others' words usually) --}}
                                    @if($isMe)
                                        <button wire:click="editReply({{ $reply->id }})" @click="open = false" class="block w-full text-left px-4 py-2 text-xs text-gray-700 hover:bg-gray-50">Edit Post</button>
                                    @endif

                                    {{-- Delete: Show for both Owner AND Admin --}}
                                    <button @click="open = false; confirmAction('deleteReply', {{ $reply->id }}, 'Delete Post?', 'Are you sure you want to remove this reply?')" 
                                            class="block w-full text-left px-4 py-2 text-xs text-red-600 hover:bg-red-50">
                                        Delete
                                    </button>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- 2. POST BODY --}}
                <div class="px-4 py-4 md:px-5 md:py-5 min-h-[80px]">
                    @if($editingReplyId === $reply->id)
                        <div class="animate-fade-in">
                            <textarea wire:model.defer="editingContent" class="w-full text-sm border-gray-300 rounded-md shadow-sm focus:border-red-300 focus:ring focus:ring-red-200 focus:ring-opacity-50" rows="4"></textarea>
                            <div class="flex gap-2 mt-3 justify-end">
                                <button wire:click="cancelEdit" class="text-xs font-bold text-gray-500 uppercase tracking-wide hover:underline">Cancel</button>
                                <button wire:click="updateReply" class="text-xs font-bold bg-gray-900 text-white px-4 py-2 rounded shadow hover:bg-black transition">Update Post</button>
                            </div>
                        </div>
                    @else
                        <div class="prose prose-sm max-w-none text-gray-800 leading-relaxed">
                            {!! nl2br(e($reply->content)) !!}
                        </div>
                    @endif
                </div>

                {{-- 3. POST FOOTER (Action Bar) --}}
                <div class="px-4 py-2 bg-gray-50/50 border-t border-gray-100 flex items-center justify-between">
                    
                    {{-- Left: Voting --}}
                    <div class="flex items-center border border-gray-200 rounded bg-white shadow-sm overflow-hidden">
                        <button wire:click="vote({{ $reply->id }}, 1)" 
                            class="px-2 py-1 hover:bg-gray-50 border-r border-gray-100 transition {{ $myVote === 1 ? 'text-red-600 bg-red-50' : 'text-gray-400' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path></svg>
                        </button>
                        
                        <div class="px-3 text-xs font-bold text-gray-700 min-w-[30px] text-center">
                            {{ $reply->score }}
                        </div>
                        
                        <button wire:click="vote({{ $reply->id }}, -1)" 
                            class="px-2 py-1 hover:bg-gray-50 border-l border-gray-100 transition {{ $myVote === -1 ? 'text-blue-600 bg-blue-50' : 'text-gray-400' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>
                    </div>

                    {{-- Right: Reply --}}
                    <button @click="$wire.set('newReply', '@' + '{{ $reply->user->name }} ' + $wire.get('newReply')); document.getElementById('replyInput').focus()"
                            class="text-xs font-bold text-gray-500 uppercase tracking-wider hover:text-red-600 transition flex items-center gap-1 group/btn">
                        <span>Reply</span>
                        <svg class="w-3 h-3 group-hover/btn:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </button>
                </div>

            </div>
        @empty
            <div class="text-center py-16 bg-white border border-dashed border-gray-300 rounded-xl">
                <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                <p class="text-gray-500 font-medium">No responses yet.</p>
                <p class="text-xs text-gray-400">Be the first to share your thoughts on this topic.</p>
            </div>
        @endforelse
    </div>
    
    {{-- Bottom Spacer to allow scrolling past the fixed input --}}
    <div class="h-32"></div>

    {{-- 4. FLOATING FOOTER INPUT (Refined to look less like chat, more like "Quick Reply") --}}
    <div class="fixed bottom-0 left-0 w-full z-50 bg-white border-t border-gray-200 shadow-[0_-5px_25px_rgba(0,0,0,0.05)]">
        <div class="max-w-7xl mx-auto px-4 py-4">
            <div class="flex items-end gap-4"> {{-- Changed items-start to items-end for better alignment if text wraps --}}
                
                {{-- User Avatar --}}
                <img class="w-10 h-10 rounded-full border border-gray-200 hidden md:block mb-2 object-cover bg-gray-100" 
                    src="{{ 
                        auth()->user()->profile_photo_path 
                            ? (
                                Str::startsWith(auth()->user()->profile_photo_path, 'http') 
                                    ? auth()->user()->profile_photo_path 
                                    : (
                                        Str::startsWith(auth()->user()->profile_photo_path, 'images/') 
                                            ? asset(auth()->user()->profile_photo_path) 
                                            : asset('storage/' . auth()->user()->profile_photo_path)
                                    )
                            ) 
                            : 'https://ui-avatars.com/api/?name='.urlencode(auth()->user()->name).'&color=7F9CF5&background=EBF4FF' 
                    }}" 
                    alt="{{ auth()->user()->name }}">
                
                <div class="flex-1 relative">
                    <textarea 
                        id="replyInput"
                        wire:model="newReply" 
                        rows="1" 
                        class="w-full bg-gray-50 border-gray-300 focus:bg-white focus:border-red-500 focus:ring-1 focus:ring-red-500 rounded-xl py-4 px-5 text-sm shadow-sm transition-all resize-none min-h-[56px] max-h-40 placeholder-gray-400" 
                        placeholder="Write a response..."></textarea>
                    
                    @error('newReply') 
                        <span class="text-xs text-red-500 font-bold mt-1 block pl-1">{{ $message }}</span> 
                    @enderror
                </div>

                {{-- "POST" BUTTON (Bulked Up) --}}
                <button wire:click="postReply" 
                    class="h-14 px-8 bg-gray-900 text-white font-bold text-sm uppercase tracking-widest rounded-xl hover:bg-red-600 transition shadow-lg flex items-center gap-2 shrink-0 mb-[1px]">
                    <span>Post</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                </button>
            </div>
        </div>
    </div>


</div>

@push('scripts') 
<script>
    function confirmAction(method, params, title = 'Are you sure?', text = "You won't be able to revert this!") {
        Swal.fire({
            title: title,
            text: text,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc2626', // Red-600 to match your delete theme
            cancelButtonColor: '#6b7280', // Gray-500
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                // Call the Livewire method dynamically
                // @this is a Blade directive that gives JS access to the Livewire component
                @this.call(method, params);
            }
        });
    }
</script>
@endpush