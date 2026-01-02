<div class="min-h-screen bg-gray-100 font-sans text-gray-900 pb-32 relative overflow-x-hidden" wire:poll.10s>
    
    {{-- 1. BACKGROUND BLOBS (The Atmosphere - Kept Subtle) --}}
    <div class="fixed inset-0 z-0 overflow-hidden pointer-events-none">
        <div class="absolute top-[-10%] left-[-10%] w-[500px] h-[500px] bg-red-200/40 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob"></div>
        <div class="absolute top-[-10%] right-[-10%] w-[500px] h-[500px] bg-yellow-200/40 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob animation-delay-2000"></div>
        <div class="absolute bottom-[-10%] left-[20%] w-[500px] h-[500px] bg-blue-200/40 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob animation-delay-4000"></div>
    </div>

    {{-- NAVIGATION (Sticky Header) --}}
    <div class="sticky top-0 w-full z-40 bg-gray-100/90 backdrop-blur-md border-b border-white/50 px-4 py-3 shadow-sm">
        <div class="max-w-4xl mx-auto flex justify-between items-center">
            <a href="{{ route('roundtable.index') }}" 
               class="flex items-center gap-2 text-sm font-bold text-gray-600 hover:text-gray-900 transition group">
                <div class="bg-white p-1.5 rounded-full shadow-sm group-hover:bg-gray-200 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                </div>
                <span class="hidden sm:inline uppercase tracking-wider text-xs">Return to Hall</span>
            </a>
            
            <div class="flex items-center gap-3">
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
    </div>

    {{-- MAIN CONTENT --}}
    <div class="max-w-4xl mx-auto px-4 pt-6 relative z-10">
        
        {{-- ORIGINAL POST (Reddit Style Card) --}}
        <div class="bg-white rounded-xl shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04)] border border-gray-200/60 overflow-hidden mb-8">
            <div class="flex">
                {{-- Left Sidebar (Vote Rail - Visual Only) --}}
                <div class="w-12 bg-gray-50/50 border-r border-gray-100 flex flex-col items-center py-4 gap-1">
                    <button class="text-gray-400 hover:text-red-500 hover:bg-gray-100 p-1 rounded"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path></svg></button>
                    <span class="text-xs font-bold text-gray-700">Topic</span>
                    <button class="text-gray-400 hover:text-blue-500 hover:bg-gray-100 p-1 rounded"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg></button>
                </div>

                {{-- Main Content --}}
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

                    {{-- Footer Actions --}}
                    <div class="flex items-center gap-4 border-t border-gray-100 pt-3">
                        <div class="flex items-center gap-1 text-gray-500 text-xs font-bold uppercase tracking-wide">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path></svg>
                            {{ count($topic->roundtable_replies) }} Comments
                        </div>
                        <div class="flex items-center gap-1 text-gray-400 text-xs font-bold uppercase tracking-wide cursor-not-allowed">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"></path></svg>
                            Share
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- DISCUSSION THREADS --}}
        <div class="space-y-4">
            
            {{-- Sort/Filter Bar (Visual Only) --}}
            <div class="flex items-center gap-2 mb-4 px-2">
                <span class="text-xs font-bold uppercase tracking-widest text-gray-400">Discussion Log</span>
                <div class="h-px bg-gray-200 flex-1"></div>
            </div>

            @forelse($topic->roundtable_replies as $reply)
                <div class="group relative transition-all duration-300 animate-fade-in-up">
                    
                    {{-- Thread Line --}}
                    @if(!$loop->last)
                        <div class="absolute left-6 top-10 bottom-[-20px] w-px bg-gray-200 group-hover:bg-gray-300 transition-colors z-0"></div>
                    @endif

                    <div class="flex gap-3 relative z-10">
                        {{-- Avatar Column --}}
                        <div class="shrink-0 flex flex-col items-center">
                            <img src="{{ asset($reply->user->profile_photo_path) }}" class="w-8 h-8 md:w-10 md:h-10 rounded-full border border-gray-200 shadow-sm bg-gray-100 object-cover">
                        </div>

                        {{-- Comment Card --}}
                        <div class="flex-1">
                            <div class="bg-white border rounded-xl p-3 md:p-4 shadow-sm
                                {{ $reply->user_id === $topic->user_id 
                                    ? 'border-red-100 bg-red-50/30' 
                                    : ($reply->user_id === auth()->id() ? 'border-yellow-200 bg-yellow-50/30' : 'border-gray-200') }}">
                                
                                {{-- Meta --}}
                                <div class="flex justify-between items-start mb-2">
                                    <div class="flex items-center gap-2 text-xs">
                                        <span class="font-bold text-gray-800">{{ $reply->user->name }}</span>
                                        
                                        @if($reply->user_id === $topic->user_id)
                                            <span class="text-[9px] font-black uppercase tracking-wider text-red-600 bg-red-100 px-1.5 rounded">Host</span>
                                        @elseif($reply->user_id === auth()->id())
                                            <span class="text-[9px] font-black uppercase tracking-wider text-yellow-600 bg-yellow-100 px-1.5 rounded">You</span>
                                        @endif

                                        <span class="text-gray-400 text-[10px]">{{ $reply->created_at->format('g:i A') }}</span>
                                    </div>
                                </div>

                                {{-- Content --}}
                                <div class="text-sm text-gray-800 leading-relaxed">
                                    {{ $reply->content }}
                                </div>

                                {{-- Actions --}}
                                <div class="flex items-center gap-3 mt-3 pt-2 border-t {{ $reply->user_id === $topic->user_id ? 'border-red-100' : 'border-gray-100' }}">
                                    <div class="flex items-center gap-1 text-gray-400 hover:text-red-500 cursor-pointer transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path></svg>
                                    </div>
                                    <div class="flex items-center gap-1 text-gray-400 hover:text-blue-500 cursor-pointer transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                    </div>
                                    <span class="text-xs font-bold text-gray-500 ml-2">Reply</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-12">
                    <div class="inline-block p-4 rounded-full bg-gray-100 mb-3">
                        <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                    </div>
                    <p class="text-gray-500 text-sm font-bold">The floor is open.</p>
                    <p class="text-gray-400 text-xs">Be the first to share your perspective.</p>
                </div>
            @endforelse
        </div>
    </div>

    {{-- FLOATING INPUT (Updated to look more like a footer editor) --}}
    <div class="fixed bottom-0 left-0 w-full z-50 border-t border-gray-200 bg-white/95 backdrop-blur-xl shadow-[0_-5px_20px_rgb(0,0,0,0.05)]">
        <div class="max-w-4xl mx-auto px-4 py-3">
            <div class="flex gap-4 items-end">
                <div class="relative flex-1">
                    <textarea wire:model="newReply" rows="1" 
                        class="w-full bg-gray-100 border-transparent focus:bg-white focus:border-red-300 focus:ring focus:ring-red-200 rounded-lg py-3 px-4 text-sm resize-none max-h-32 placeholder-gray-500 transition-all" 
                        placeholder="Contribute to the roundtable..."></textarea>
                </div>

                <button wire:click="postReply" 
                    class="h-11 px-6 bg-gray-900 text-white rounded-lg font-bold text-xs uppercase tracking-widest hover:bg-red-600 active:scale-95 transition-all shadow-md flex items-center gap-2 shrink-0">
                    <span>Post</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                </button>
            </div>
            <div class="text-center mt-2">
                <p class="text-[9px] text-gray-400 uppercase tracking-widest">Constructive discourse only</p>
            </div>
        </div>
    </div>

</div>