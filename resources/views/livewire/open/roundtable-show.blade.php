<div class="min-h-screen bg-gray-100 font-sans text-gray-900 pb-32 relative overflow-x-hidden" wire:poll.10s>
    
    {{-- 1. BACKGROUND ATMOSPHERE --}}
    <div class="fixed inset-0 z-0 overflow-hidden pointer-events-none">
        <div class="absolute top-[-10%] left-[-10%] w-[500px] h-[500px] bg-red-200/40 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob"></div>
        <div class="absolute top-[-10%] right-[-10%] w-[500px] h-[500px] bg-yellow-200/40 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob animation-delay-2000"></div>
        <div class="absolute bottom-[-10%] left-[20%] w-[500px] h-[500px] bg-blue-200/40 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob animation-delay-4000"></div>
    </div>

    {{-- 2. STICKY HEADER --}}
    <div class="sticky top-0 w-full z-40 bg-gray-100/95 backdrop-blur-md border-b border-white/50 px-4 py-3 shadow-sm transition-all">
        <div class="max-w-4xl mx-auto flex justify-between items-center">
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
    <div class="max-w-4xl mx-auto px-4 pt-4 md:pt-6 relative z-10">
        
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
                        <button wire:click="deleteTopic" 
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
                        <img src="{{ asset($topic->user->profile_photo_path) }}" class="w-6 h-6 md:w-5 md:h-5 rounded-full object-cover ring-2 ring-gray-100 md:ring-0">
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
        <div class="space-y-4">
            
            {{-- Visual Separator --}}
            <div class="flex items-center gap-2 mb-4 px-2">
                <span class="text-xs font-bold uppercase tracking-widest text-gray-400">Discussion</span>
                <div class="h-px bg-gray-200 flex-1"></div>
            </div>

            @forelse($topic->roundtable_replies as $reply)
                @php 
                    $myVote = $reply->userVote(auth()->id()); 
                @endphp

                <div class="group relative transition-all duration-300 animate-fade-in-up">
                    
                    {{-- Thread Guide Line (Adjusted for mobile avatar size) --}}
                    @if(!$loop->last)
                        <div class="absolute left-[15px] md:left-6 top-10 bottom-[-20px] w-px bg-gray-200 group-hover:bg-gray-300 transition-colors z-0"></div>
                    @endif

                    <div class="flex gap-2 md:gap-3 relative z-10">
                        {{-- Avatar --}}
                        <div class="shrink-0 flex flex-col items-center">
                            <img src="{{ asset($reply->user->profile_photo_path) }}" class="w-8 h-8 md:w-10 md:h-10 rounded-full border border-gray-200 shadow-sm bg-gray-100 object-cover">
                        </div>

                        {{-- Comment Body --}}
                        <div class="flex-1 min-w-0"> {{-- min-w-0 prevents flex overflow --}}
                            <div class="bg-white border rounded-xl p-3 md:p-4 shadow-sm relative group/card
                                {{ $reply->user_id === $topic->user_id ? 'border-red-100 bg-red-50/30' : ($reply->user_id === auth()->id() ? 'border-yellow-200 bg-yellow-50/30' : 'border-gray-200') }}">
                                
                                {{-- Comment Header --}}
                                <div class="flex justify-between items-start mb-2">
                                    <div class="flex flex-wrap items-center gap-x-2 gap-y-0.5 text-xs">
                                        <span class="font-bold text-gray-800">{{ $reply->user->name }}</span>
                                        
                                        @if($reply->user_id === $topic->user_id)
                                            <span class="text-[9px] font-black uppercase tracking-wider text-red-600 bg-red-100 px-1.5 rounded">Host</span>
                                        @elseif($reply->user_id === auth()->id())
                                            <span class="text-[9px] font-black uppercase tracking-wider text-yellow-600 bg-yellow-100 px-1.5 rounded">You</span>
                                        @endif

                                        <span class="text-gray-400 text-[10px]">{{ $reply->created_at->format('g:i A') }}</span>
                                    </div>

                                    {{-- Kebab Menu --}}
                                    @if($reply->user_id === auth()->id())
                                        <div x-data="{ open: false }" class="relative">
                                            <button @click="open = !open" @click.away="open = false" class="text-gray-300 hover:text-gray-600 p-1.5 -mr-2 -mt-2 md:mr-0 md:mt-0">
                                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"></path></svg>
                                            </button>
                                            {{-- Dropdown content... --}}
                                            <div x-show="open" style="display: none;" class="absolute right-0 mt-1 w-32 bg-white border border-gray-100 rounded-lg shadow-lg z-20 py-1">
                                                <button wire:click="editReply({{ $reply->id }})" @click="open = false" class="block w-full text-left px-4 py-3 md:py-2 text-xs text-gray-700">Edit</button>
                                                <button wire:click="deleteReply({{ $reply->id }})" wire:confirm="Are you sure?" @click="open = false" class="block w-full text-left px-4 py-3 md:py-2 text-xs text-red-600">Delete</button>
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                {{-- CONTENT --}}
                                @if($editingReplyId === $reply->id)
                                    <div class="mt-2 animate-fade-in">
                                        <textarea wire:model.defer="editingContent" class="w-full text-sm border-gray-300 rounded-md shadow-sm" rows="3"></textarea>
                                        <div class="flex gap-2 mt-2 justify-end">
                                            <button wire:click="cancelEdit" class="text-xs text-gray-500 px-3 py-2">Cancel</button>
                                            <button wire:click="updateReply" class="text-xs bg-gray-900 text-white px-3 py-2 rounded">Save</button>
                                        </div>
                                    </div>
                                @else
                                    <div class="text-sm text-gray-800 leading-relaxed break-words">
                                        {!! nl2br(e($reply->content)) !!}
                                    </div>
                                @endif

                                {{-- ACTIONS --}}
                                <div class="flex items-center justify-between md:justify-start md:gap-4 mt-3 pt-2 border-t {{ $reply->user_id === $topic->user_id ? 'border-red-200' : 'border-gray-100' }}">
                                    
                                    {{-- Votes --}}
                                    <div class="flex items-center gap-1 bg-gray-50 rounded-lg p-0.5 border border-gray-100">
                                        <button wire:click="vote({{ $reply->id }}, 1)" class="p-1.5 md:p-1 rounded {{ $myVote === 1 ? 'text-red-600 bg-white shadow-sm' : 'text-gray-400' }}">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path></svg>
                                        </button>
                                        <span class="text-xs font-bold w-5 text-center {{ $myVote !== 0 ? 'text-gray-900' : 'text-gray-500' }}">{{ $reply->score }}</span>
                                        <button wire:click="vote({{ $reply->id }}, -1)" class="p-1.5 md:p-1 rounded {{ $myVote === -1 ? 'text-blue-600 bg-white shadow-sm' : 'text-gray-400' }}">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                        </button>
                                    </div>

                                    {{-- Reply --}}
                                    <button @click="$wire.set('newReply', '@' + '{{ $reply->user->name }} ' + $wire.get('newReply')); document.getElementById('replyInput').focus()"
                                        class="flex items-center gap-1 text-gray-400 hover:text-gray-900 px-2 py-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"></path></svg>
                                        <span class="text-xs font-bold">Reply</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                {{-- Empty State --}}
                <div class="text-center py-12">
                    <p class="text-gray-500 text-sm font-bold">The floor is open.</p>
                </div>
            @endforelse
        </div>
        <div class="h-40 md:h-48 w-full"></div>
    </div>

    {{-- 4. FLOATING FOOTER INPUT --}}
    <div class="fixed bottom-0 left-0 w-full z-50 border-t border-gray-200 bg-white/95 backdrop-blur-xl shadow-[0_-5px_20px_rgb(0,0,0,0.05)] pb-safe pl-safe pr-safe">
        {{-- Added pb-2 here to the inner container for extra lift --}}
        <div class="max-w-4xl mx-auto px-3 py-2 pb-4 md:py-3"> 
            <div class="flex gap-2 md:gap-4 items-end">
                <div class="relative flex-1">
                    <textarea 
                        id="replyInput"
                        wire:model="newReply" 
                        rows="1" 
                        class="w-full bg-gray-100 border-transparent focus:bg-white focus:border-red-300 focus:ring focus:ring-red-200 rounded-lg py-3 px-4 text-sm resize-none max-h-32 placeholder-gray-500 transition-all" 
                        placeholder="Type here..."></textarea>
                    
                    @error('newReply') 
                        <span class="absolute -top-8 left-0 text-xs text-white font-bold bg-red-500 px-2 py-1 rounded shadow-sm">{{ $message }}</span> 
                    @enderror
                </div>

                <button wire:click="postReply" 
                    class="group h-12 w-12 md:w-auto md:h-11 md:px-6 bg-gradient-to-br from-red-600 to-red-500 text-white rounded-full md:rounded-xl shadow-lg shadow-red-200 hover:shadow-red-300 hover:scale-105 active:scale-95 transition-all flex items-center justify-center shrink-0">
                    
                    {{-- Desktop Text --}}
                    <span class="hidden md:inline text-xs font-bold uppercase tracking-widest">Post Reply</span>
                    
                    {{-- Mobile Icon (Paper Plane) --}}
                    {{-- Note: Added translate classes to center the icon visually since paper planes are often off-center --}}
                    <svg class="w-5 h-5 md:hidden -ml-0.5 mt-0.5 group-hover:-translate-y-0.5 group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                    </svg>
                    
                    {{-- Desktop Icon --}}
                    <svg class="w-4 h-4 hidden md:block ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                    </svg>
                </button>
            </div>
        </div>
    </div>


</div>