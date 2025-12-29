<div class="min-h-screen bg-gray-50 font-sans text-gray-900 pb-24 relative overflow-x-hidden" wire:poll.10s>
    
    {{-- 1. BACKGROUND BLOBS (The Atmosphere) --}}
    <div class="fixed inset-0 z-0 overflow-hidden pointer-events-none">
        <div class="absolute top-[-10%] left-[-10%] w-[500px] h-[500px] bg-red-200/40 rounded-full mix-blend-multiply filter blur-3xl opacity-40 animate-blob"></div>
        <div class="absolute top-[-10%] right-[-10%] w-[500px] h-[500px] bg-yellow-200/40 rounded-full mix-blend-multiply filter blur-3xl opacity-40 animate-blob animation-delay-2000"></div>
        <div class="absolute bottom-[-10%] left-[20%] w-[500px] h-[500px] bg-blue-200/40 rounded-full mix-blend-multiply filter blur-3xl opacity-40 animate-blob animation-delay-4000"></div>
    </div>

    {{-- NAVIGATION (Floating Header) --}}
    <div class="fixed top-0 left-0 w-full z-40 px-4 pt-4 pointer-events-none">
        <div class="max-w-3xl mx-auto flex justify-between items-center pointer-events-auto">
            {{-- Back Button --}}
            <a href="{{ route('roundtable.index') }}" 
               class="bg-white/80 backdrop-blur-md border border-white/60 shadow-sm px-4 py-2.5 rounded-full flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-gray-600 hover:bg-gray-900 hover:text-white transition group">
                <svg class="w-4 h-4 text-gray-400 group-hover:text-white transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                <span class="hidden sm:inline">Return to Hall</span>
                <span class="sm:hidden">Exit</span>
            </a>
            
            {{-- Live Indicator --}}
            <div class="bg-white/80 backdrop-blur-md border border-white/60 px-4 py-1.5 rounded-full shadow-sm flex items-center gap-2">
                <span class="relative flex h-2 w-2">
                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                  <span class="relative inline-flex rounded-full h-2 w-2 bg-green-500"></span>
                </span>
                <span class="text-[10px] font-black uppercase tracking-widest text-gray-500">Live</span>
            </div>
        </div>
    </div>

    {{-- MAIN CONTENT --}}
    <div class="max-w-3xl mx-auto px-4 pt-24 relative z-10">
        
        {{-- TOPIC CENTERPIECE (The Document) --}}
        <div class="bg-white/90 backdrop-blur-xl rounded-[2.5rem] shadow-xl border border-white relative mb-12 overflow-hidden">
            
            {{-- Decorative Header Bar --}}
            <div class="h-2 w-full bg-gradient-to-r from-red-500 via-yellow-500 to-red-500 opacity-80"></div>

            <div class="p-8 md:p-10 text-center">
                {{-- Host Avatar --}}
                <div class="relative inline-block mb-6">
                    <div class="p-1.5 rounded-full border-2 border-dashed border-red-300">
                        <img src="{{ asset($topic->user->profile_photo_path) }}" class="w-20 h-20 rounded-full object-cover shadow-sm">
                    </div>
                    <div class="absolute -bottom-2 left-1/2 -translate-x-1/2 bg-gray-900 text-white text-[9px] font-black uppercase px-3 py-1 rounded-full tracking-widest border-4 border-white shadow-sm">
                        Host
                    </div>
                </div>

                <p class="text-xs font-bold text-gray-400 mb-2 uppercase tracking-wide">Discussion opened by {{ $topic->user->name }}</p>
                
                <h1 class="font-heading font-black text-2xl md:text-4xl text-gray-900 leading-tight mb-6">
                    {{ $topic->headline }}
                </h1>
                
                <div class="prose prose-red prose-sm max-w-none text-gray-600 leading-relaxed mx-auto bg-gray-50/50 p-6 rounded-2xl border border-gray-100/50">
                    {!! nl2br(e($topic->content)) !!}
                </div>
                
                <div class="mt-6 flex justify-center items-center gap-2 text-[10px] font-bold text-gray-400 uppercase tracking-widest">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Started {{ $topic->created_at->diffForHumans() }}
                </div>
            </div>
        </div>

        {{-- DISCUSSION STREAM --}}
        <div class="space-y-8 relative z-10 pb-4">
            
            @forelse($topic->roundtable_replies as $reply)
                <div class="flex items-end gap-3 {{ $reply->user_id === auth()->id() ? 'flex-row-reverse' : '' }} group animate-fade-in-up">
                    
                    {{-- Avatar --}}
                    <div class="shrink-0">
                        <img src="{{ asset($reply->user->profile_photo_path) }}" class="w-10 h-10 rounded-full border-2 border-white shadow-sm object-cover bg-gray-100">
                    </div>
                    
                    {{-- Message Bubble --}}
                    <div class="max-w-[85%] sm:max-w-[75%] flex flex-col {{ $reply->user_id === auth()->id() ? 'items-end' : 'items-start' }}">
                        
                        {{-- Meta (Name & Time) --}}
                        <div class="text-[10px] font-bold text-gray-400 mb-1 px-2 flex gap-1 items-center">
                            @if($reply->user_id === $topic->user_id)
                                <span class="text-red-500 flex items-center gap-0.5">
                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"></path></svg>
                                    Host
                                </span>
                                <span class="opacity-50">•</span>
                            @endif
                            <span>{{ $reply->user->name }}</span>
                            <span class="opacity-50">•</span>
                            <span class="opacity-70">{{ $reply->created_at->format('g:i A') }}</span>
                        </div>

                        {{-- The Bubble --}}
                        <div class="px-6 py-4 shadow-sm text-sm leading-relaxed relative backdrop-blur-md border
                            {{ $reply->user_id === auth()->id() 
                                ? 'bg-yellow-500/95 text-gray-800 border-transparent rounded-[2rem] rounded-br-sm' 
                                : 'bg-white/90 text-gray-800 border-white/60 rounded-[2rem] rounded-bl-sm' }}">
                            {{ $reply->content }}
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-12 opacity-60">
                    <p class="text-gray-500 text-sm font-bold">No replies yet. Break the ice!</p>
                </div>
            @endforelse

        </div>
        
        {{-- Spacer for floating bar --}}
        <div class="h-28"></div>
    </div>

    {{-- FLOATING REPLY BAR --}}
    <div class="fixed bottom-0 left-0 w-full z-50 px-4 pb-4 md:pb-6">
        <div class="max-w-3xl mx-auto">
            <div class="bg-white/90 backdrop-blur-xl rounded-[2.5rem] shadow-[0_8px_30px_rgb(0,0,0,0.12)] border border-white/50 p-2 pl-5 flex gap-3 items-end transition-all focus-within:shadow-[0_8px_40px_rgb(220,38,38,0.15)] focus-within:border-red-100">
                
                <div class="flex-1 py-3">
                    <textarea wire:model="newReply" rows="1" 
                        class="w-full bg-transparent border-none p-0 text-sm focus:ring-0 resize-none max-h-32 placeholder-gray-400 text-gray-800" 
                        placeholder="Type your thought here..."></textarea>
                </div>

                <button wire:click="postReply" 
                    class="h-12 px-6 bg-gradient-to-r from-red-600 to-red-700 text-white rounded-full font-bold text-xs uppercase tracking-widest shadow-lg hover:shadow-red-200/50 hover:scale-105 active:scale-95 transition-all flex items-center gap-2 mb-0.5">
                    <span class="hidden md:inline">Send</span>
                    <svg class="w-4 h-4 md:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    <svg class="w-4 h-4 hidden md:block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path></svg>
                </button>
            </div>
        </div>
    </div>

</div>