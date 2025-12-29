<div class="min-h-screen bg-gray-50 font-sans text-gray-900 pb-24 relative overflow-x-hidden" wire:poll.10s>
    
    {{-- 1. BACKGROUND BLOBS (Splash of Colors) --}}
    <div class="fixed inset-0 z-0 overflow-hidden pointer-events-none">
        <div class="absolute top-[-10%] left-[-10%] w-[500px] h-[500px] bg-red-200 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob"></div>
        <div class="absolute top-[-10%] right-[-10%] w-[500px] h-[500px] bg-yellow-200 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob animation-delay-2000"></div>
        <div class="absolute bottom-[-10%] left-[20%] w-[500px] h-[500px] bg-pink-200 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob animation-delay-4000"></div>
    </div>

    {{-- NAVIGATION (Floating Header) --}}
    <div class="relative top-0 left-0 w-full z-40 px-4 pt-4 pointer-events-none">
        <div class="max-w-4xl mx-auto flex justify-between items-center pointer-events-auto">
            <a href="{{ route('roundtable.index') }}" 
               class="bg-white/80 backdrop-blur-md border border-white/50 shadow-sm px-4 py-2.5 rounded-full flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-gray-600 hover:bg-gray-900 hover:text-white transition group">
                <svg class="w-4 h-4 text-gray-400 group-hover:text-white transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                <span class="hidden sm:inline">Return to Hall</span>
                <span class="sm:hidden">Back</span>
            </a>
            
            <div class="bg-yellow-100/80 backdrop-blur-md border border-yellow-200 px-3 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest text-yellow-800 shadow-sm">
                Table #{{ $topic->id }}
            </div>
        </div>
    </div>

    {{-- MAIN CONTENT --}}
    <div class="max-w-3xl mx-auto px-4 pt-20 relative z-10">
        
        {{-- TOPIC CENTERPIECE --}}
        <div class="bg-white/80 backdrop-blur-xl rounded-[2.5rem] shadow-xl p-2 border border-white/50 relative mb-12">
            
            {{-- Decorative top marker --}}
            <div class="absolute top-0 left-1/2 -translate-x-1/2 w-32 h-1.5 bg-gradient-to-r from-red-500 to-yellow-500 rounded-b-xl opacity-80"></div>

            <div class="bg-gray-50/50 rounded-[2rem] p-6 md:p-10">
                {{-- Host Info --}}
                <div class="flex flex-col items-center text-center mb-6">
                    <div class="relative">
                        <div class="p-1 rounded-full border-2 border-dashed border-red-200 mb-2">
                            <img src="{{ asset($topic->user->profile_photo_path) }}" class="w-16 h-16 rounded-full object-cover shadow-sm">
                        </div>
                        <div class="absolute -bottom-1 left-1/2 -translate-x-1/2 bg-gray-900 text-white text-[9px] font-black uppercase px-2 py-0.5 rounded-full tracking-wider border-2 border-white">
                            Host
                        </div>
                    </div>
                    <p class="text-xs font-bold text-gray-500 mt-2">Opened by {{ $topic->user->name }}</p>
                    <p class="text-[10px] font-bold text-gray-300 uppercase tracking-widest">{{ $topic->created_at->format('M d, Y') }}</p>
                </div>

                {{-- Content --}}
                <h1 class="font-heading font-black text-2xl md:text-4xl text-gray-900 text-center mb-6 leading-tight">
                    {{ $topic->headline }}
                </h1>
                
                <div class="prose prose-red prose-sm max-w-none text-gray-600 leading-relaxed text-center mx-auto">
                    {!! nl2br(e($topic->content)) !!}
                </div>
            </div>

            {{-- Stats Bar --}}
            <div class="px-8 py-4 flex justify-center gap-6 text-[10px] font-bold uppercase tracking-widest text-gray-400">
                <span class="flex items-center gap-1">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    {{ $topic->roundtable_replies->count() }} Seats Taken
                </span>
            </div>
        </div>

        {{-- DISCUSSION STREAM --}}
        <div class="space-y-6 relative z-10">
            @foreach($topic->roundtable_replies as $reply)
            <div class="flex items-end gap-3 {{ $reply->user_id === auth()->id() ? 'flex-row-reverse' : '' }} group animate-fade-in-up">
                
                {{-- Avatar --}}
                <img src="{{ asset($reply->user->profile_photo_path) }}" class="w-8 h-8 rounded-full border-2 border-white shadow-sm mb-1 bg-white">
                
                {{-- Bubble --}}
                <div class="max-w-[85%] sm:max-w-[75%]">
                    {{-- Metadata Outside Bubble --}}
                    <div class="text-[9px] font-bold text-gray-500 mb-1 px-2 flex gap-1 {{ $reply->user_id === auth()->id() ? 'justify-end' : 'justify-start' }}">
                        <span>{{ $reply->user->name }}</span>
                        <span class="opacity-50">•</span>
                        <span class="opacity-70">{{ $reply->created_at->diffForHumans(null, true, true) }}</span>
                    </div>

                    <div class="px-5 py-3.5 shadow-sm text-sm leading-relaxed relative backdrop-blur-sm
                        {{ $reply->user_id === auth()->id() 
                            ? 'bg-gray-900/90 text-white rounded-[1.5rem] rounded-br-none' 
                            : 'bg-white/80 text-gray-800 border border-white/50 rounded-[1.5rem] rounded-bl-none' }}">
                        {{ $reply->content }}
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        
        {{-- Padding for fixed bottom bar --}}
        <div class="h-24"></div>
    </div>

    {{-- FLOATING REPLY BAR --}}
    <div class="fixed bottom-0 left-0 w-full z-40 px-4 pb-4 md:pb-6">
        <div class="max-w-3xl mx-auto">
            <div class="bg-white/90 backdrop-blur-xl rounded-[2rem] shadow-[0_8px_30px_rgb(0,0,0,0.12)] border border-white/50 p-2 pl-4 flex gap-2 items-end">
                
                <div class="flex-1 py-2">
                    <textarea wire:model="newReply" rows="1" 
                        class="w-full bg-transparent border-none p-0 text-sm focus:ring-0 resize-none max-h-24 placeholder-gray-500 text-gray-800" 
                        placeholder="Join the discussion..."></textarea>
                </div>

                <button wire:click="postReply" 
                    class="h-10 px-5 bg-gradient-to-r from-red-600 to-yellow-500 text-white rounded-full font-bold text-xs uppercase tracking-widest shadow-md hover:shadow-lg transform active:scale-95 transition-all flex items-center gap-2">
                    <span>Send</span>
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                </button>
            </div>
        </div>
    </div>

</div>