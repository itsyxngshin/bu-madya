<div class="min-h-screen bg-gray-100 font-sans text-gray-900 pb-32 relative overflow-x-hidden" wire:poll.10s>
    
    {{-- 1. BACKGROUND ATMOSPHERE --}}
    <div class="fixed inset-0 z-0 overflow-hidden pointer-events-none">
        <div class="absolute top-[-10%] left-[-10%] w-[500px] h-[500px] bg-red-200/40 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob"></div>
        <div class="absolute top-[-10%] right-[-10%] w-[500px] h-[500px] bg-yellow-200/40 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob animation-delay-2000"></div>
        <div class="absolute bottom-[-10%] left-[20%] w-[500px] h-[500px] bg-blue-200/40 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob animation-delay-4000"></div>
    </div>

    {{-- 2. STICKY HEADER --}}
    <div class="sticky top-0 w-full z-40 bg-gray-100/90 backdrop-blur-md border-b border-white/50 px-4 py-3 shadow-sm">
        <div class="max-w-4xl mx-auto flex justify-between items-center">
            {{-- Back Button --}}
            <a href="{{ route('roundtable.index') }}" 
               class="flex items-center gap-2 text-sm font-bold text-gray-600 hover:text-gray-900 transition group">
                <div class="bg-white p-1.5 rounded-full shadow-sm group-hover:bg-gray-200 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                </div>
                <span class="hidden sm:inline uppercase tracking-wider text-xs">Return to Hall</span>
            </a>
            
            {{-- Live Indicator --}}
            <div class="bg-red-50 border border-red-100 px-3 py-1 rounded-full flex items-center gap-2">
                <span class="relative flex h-2 w-2">
                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                  <span class="relative inline-flex rounded-full h-2 w-2 bg-red-500"></span>
                </span>
                <span class="text-[10px] font-black uppercase tracking-widest text-red-600">Roundtable Live</span>
            </div>
        </div>
    </div>

    {{-- 3. MAIN CONTENT AREA --}}
    <div class="max-w-4xl mx-auto px-4 pt-6 relative z-10">
        
        {{-- A. ORIGINAL TOPIC CARD --}}
        <div class="bg-white rounded-xl shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04)] border border-gray-200/60 overflow-hidden mb-8">
            <div class="flex">
                {{-- Left Sidebar (Visual Rail for Topic) --}}
                <div class="w-12 bg-gray-50/50 border-r border-gray-100 flex flex-col items-center py-4 gap-1">
                    <div class="text-gray-300 p-1"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path></svg></div>
                    <span class="text-xs font-bold text-gray-400">OP</span>
                    <div class="text-gray-300 p-1"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg></div>
                </div>

                {{-- Topic Content --}}
                <div class="flex-1 p-4 md:p-6">
                    {{-- Header Meta --}}
                    <div class="flex items-center gap-2 mb-3 text-xs text-gray-500">
                        <img src="{{ asset($topic->user->profile_photo_path) }}" class="w-5 h-5 rounded-full object-cover">
                        <span>Posted by <span class="font-bold text-gray-700">{{ $topic->user->name }}</span></span>
                        <span class="text-red-500 font-bold bg-red-50 px-1.5 py-0.5 rounded border border-red-100 uppercase text-[10px] tracking-wider">Host</span>
                        <span>•</span>
                        <span>{{ $topic->created_at->diffForHumans() }}</span>
                    </div>

                    <h1 class="font-heading font-black text-xl md:text-2xl text-gray-900 leading-tight mb-4">
                        {{ $topic->headline }}
                    </h1>

                    <div class="prose prose-red prose-sm max-w-none text-gray-800 leading-relaxed mb-6">
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
        <div class="space-y-4">
            
            {{-- Visual Separator --}}
            <div class="flex items-center gap-2 mb-4 px-2">
                <span class="text-xs font-bold uppercase tracking-widest text-gray-400">Discussion Log</span>
                <div class="h-px bg-gray-200 flex-1"></div>
            </div>

            @forelse($topic->roundtable_replies as $reply)
                @php 
                    $myVote = $reply->userVote(auth()->id()); 
                @endphp

                <div class="group relative transition-all duration-300 animate-fade-in-up">
                    
                    {{-- Thread Guide Line --}}
                    @if(!$loop->last)
                        <div class="absolute left-6 top-10 bottom-[-20px] w-px bg-gray-200 group-hover:bg-gray-300 transition-colors z-0"></div>
                    @endif

                    <div class="flex gap-3 relative z-10">
                        {{-- Avatar --}}
                        <div class="shrink-0 flex flex-col items-center">
                            <img src="{{ asset($reply->user->profile_photo_path) }}" class="w-8 h-8 md:w-10 md:h-10 rounded-full border border-gray-200 shadow-sm bg-gray-100 object-cover">
                        </div>

                        {{-- Comment Body --}}
                        <div class="flex-1">
                            <div class="bg-white border rounded-xl p-3 md:p-4 shadow-sm relative group/card
                                {{ $reply->user_id === $topic->user_id ? 'border-red-100 bg-red-50/30' : ($reply->user_id === auth()->id() ? 'border-yellow-200 bg-yellow-50/30' : 'border-gray-200') }}">
                                
                                {{-- Comment Header & Kebab Menu --}}
                                <div class="flex justify-between items-start mb-2">
                                    <div class="flex items-center gap-2 text-xs">
                                        <span class="font-bold text-gray-800">{{ $reply->user->name }}</span>
                                        
                                        @if($reply->user_id === $topic->user_id)
                                            <span class="text-[9px] font-black uppercase tracking-wider text-red-600 bg-red-100 px-1.5 rounded">Host</span>
                                        @elseif($reply->user_id === auth()->id())
                                            <span class="text-[9px] font-black uppercase tracking-wider text-yellow-600 bg-yellow-100 px-1.5 rounded">You</span>
                                        @endif

                                        <span class="text-gray-400 text-[10px]">{{ $reply->created_at->format('g:i A') }}</span>
                                        @if($reply->created_at != $reply->updated_at)
                                            <span class="text-gray-300 text-[9px] italic">(edited)</span>
                                        @endif
                                    </div>

                                    {{-- EDIT/DELETE DROPDOWN (AlpineJS) --}}
                                    @if($reply->user_id === auth()->id())
                                        <div x-data="{ open: false }" class="relative">
                                            <button @click="open = !open" @click.away="open = false" class="text-gray-300 hover:text-gray-600 p-1 rounded-full hover:bg-black/5 transition">
                                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"></path></svg>
                                            </button>

                                            <div x-show="open" style="display: none;" 
                                                 class="absolute right-0 mt-1 w-32 bg-white border border-gray-100 rounded-lg shadow-lg z-20 py-1 overflow-hidden">
                                                <button wire:click="editReply({{ $reply->id }})" @click="open = false" class="block w-full text-left px-4 py-2 text-xs text-gray-700 hover:bg-gray-50 transition">
                                                    Edit
                                                </button>
                                                <button wire:click="deleteReply({{ $reply->id }})" 
                                                        wire:confirm="Are you sure you want to delete this contribution?"
                                                        @click="open = false" 
                                                        class="block w-full text-left px-4 py-2 text-xs text-red-600 hover:bg-red-50 transition">
                                                    Delete
                                                </button>
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                {{-- CONTENT: VIEW OR EDIT MODE --}}
                                @if($editingReplyId === $reply->id)
                                    <div class="mt-2 animate-fade-in">
                                        <textarea wire:model.defer="editingContent" 
                                            class="w-full text-sm border-gray-300 rounded-md shadow-sm focus:border-red-300 focus:ring focus:ring-red-200 focus:ring-opacity-50" 
                                            rows="3"></textarea>
                                        <div class="flex gap-2 mt-2 justify-end">
                                            <button wire:click="cancelEdit" class="text-xs text-gray-500 hover:text-gray-800 px-3 py-1 font-bold">Cancel</button>
                                            <button wire:click="updateReply" class="text-xs bg-gray-900 text-white px-3 py-1 rounded shadow-sm hover:bg-gray-700 font-bold transition">Save</button>
                                        </div>
                                    </div>
                                @else
                                    <div class="text-sm text-gray-800 leading-relaxed break-words">
                                        {!! nl2br(e($reply->content)) !!}
                                    </div>
                                @endif

                                {{-- ACTIONS: VOTES & REPLY --}}
                                <div class="flex items-center gap-4 mt-3 pt-2 border-t {{ $reply->user_id === $topic->user_id ? 'border-red-200' : 'border-gray-100' }}">
                                    
                                    {{-- Voting Cluster --}}
                                    <div class="flex items-center gap-1 bg-gray-50 rounded-lg p-0.5 border border-gray-100">
                                        {{-- Up --}}
                                        <button wire:click="vote({{ $reply->id }}, 1)" 
                                            class="p-1 rounded hover:bg-white transition {{ $myVote === 1 ? 'text-red-600 bg-white shadow-sm ring-1 ring-gray-100' : 'text-gray-400 hover:text-red-500' }}">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path></svg>
                                        </button>
                                        
                                        {{-- Score --}}
                                        <span class="text-xs font-bold w-5 text-center {{ $myVote !== 0 ? 'text-gray-900' : 'text-gray-500' }}">
                                            {{ $reply->score }}
                                        </span>

                                        {{-- Down --}}
                                        <button wire:click="vote({{ $reply->id }}, -1)" 
                                            class="p-1 rounded hover:bg-white transition {{ $myVote === -1 ? 'text-blue-600 bg-white shadow-sm ring-1 ring-gray-100' : 'text-gray-400 hover:text-blue-500' }}">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                        </button>
                                    </div>

                                    {{-- Reply Trigger --}}
                                    <button 
                                        @click="$wire.set('newReply', '@' + '{{ $reply->user->name }} ' + $wire.get('newReply')); document.getElementById('replyInput').focus()"
                                        class="flex items-center gap-1 text-gray-400 hover:text-gray-900 cursor-pointer transition group/reply">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"></path></svg>
                                        <span class="text-xs font-bold group-hover/reply:underline">Reply</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-12">
                    <div class="inline-block p-4 rounded-full bg-white shadow-sm mb-3">
                        <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                    </div>
                    <p class="text-gray-500 text-sm font-bold">The floor is open.</p>
                    <p class="text-gray-400 text-xs">Be the first to share your perspective.</p>
                </div>
            @endforelse
        </div>
    </div>

    {{-- 4. FLOATING FOOTER INPUT --}}
    <div class="fixed bottom-0 left-0 w-full z-50 border-t border-gray-200 bg-white/95 backdrop-blur-xl shadow-[0_-5px_20px_rgb(0,0,0,0.05)]">
        <div class="max-w-4xl mx-auto px-4 py-3">
            <div class="flex gap-4 items-end">
                <div class="relative flex-1">
                    <textarea 
                        id="replyInput"
                        wire:model="newReply" 
                        rows="1" 
                        class="w-full bg-gray-100 border-transparent focus:bg-white focus:border-red-300 focus:ring focus:ring-red-200 rounded-lg py-3 px-4 text-sm resize-none max-h-32 placeholder-gray-500 transition-all" 
                        placeholder="Contribute to the roundtable..."></textarea>
                    
                    @error('newReply') 
                        <span class="absolute -top-6 left-0 text-xs text-red-500 font-bold bg-white px-2 py-0.5 rounded shadow-sm border border-red-100">{{ $message }}</span> 
                    @enderror
                </div>

                <button wire:click="postReply" 
                    class="h-11 px-6 bg-gray-900 text-white rounded-lg font-bold text-xs uppercase tracking-widest hover:bg-red-600 active:scale-95 transition-all shadow-md flex items-center gap-2 shrink-0">
                    <span>Post</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                </button>
            </div>
            <div class="text-center mt-2">
                <p class="text-[9px] text-gray-400 uppercase tracking-widest">Constructive discourse only • Filter active</p>
            </div>
        </div>
    </div>

</div>