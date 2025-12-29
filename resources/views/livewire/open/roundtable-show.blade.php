<div class="min-h-screen bg-gray-50 font-sans text-gray-900 pb-20 wire:poll.10s">

    {{-- NAV HEADER --}}
    <div class="bg-white border-b border-gray-200 sticky top-0 z-30">
        <div class="max-w-4xl mx-auto px-4 h-16 flex items-center justify-between">
            <a href="{{ route('roundtable.index') }}" class="flex items-center gap-2 text-sm font-bold text-gray-500 hover:text-red-600 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Back to Roundtable
            </a>
            <span class="text-xs font-bold bg-gray-100 px-2 py-1 rounded text-gray-500">ID: #{{ $topic->id }}</span>
        </div>
    </div>

    <div class="max-w-4xl mx-auto px-4 pt-8">
        
        {{-- MAIN TOPIC CARD --}}
        <div class="bg-white rounded-3xl shadow-xl p-6 md:p-10 border border-gray-100 mb-8 relative">
            <div class="flex gap-4">
                <div class="flex flex-col items-center gap-2 shrink-0">
                    <img src="{{ $topic->user->profile_photo_url }}" class="w-12 h-12 md:w-16 md:h-16 rounded-full border-4 border-gray-50 shadow-sm">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-gray-400">{{ $topic->user->role->role_name ?? 'Member' }}</span>
                </div>
                <div class="flex-1">
                    <h1 class="font-heading font-black text-2xl md:text-3xl text-gray-900 mb-4 leading-tight">{{ $topic->headline }}</h1>
                    <div class="prose prose-red max-w-none text-gray-600 leading-relaxed text-sm md:text-base">
                        {!! nl2br(e($topic->content)) !!}
                    </div>
                </div>
            </div>
            
            <div class="mt-8 pt-6 border-t border-gray-100 flex justify-between items-center text-xs font-bold text-gray-400 uppercase tracking-widest">
                <span>Posted {{ $topic->created_at->format('M d, Y') }}</span>
                <span>{{ $topic->replies->count() }} Replies</span>
            </div>
        </div>

        {{-- REPLIES SECTION --}}
        <div class="space-y-6 mb-12">
            @foreach($topic->replies as $reply)
            <div class="flex gap-4 {{ $reply->user_id === auth()->id() ? 'flex-row-reverse' : '' }}">
                
                {{-- Avatar --}}
                <img src="{{ $reply->user->profile_photo_url }}" class="w-10 h-10 rounded-full border border-gray-200 shadow-sm shrink-0">
                
                {{-- Bubble --}}
                <div class="max-w-[85%]">
                    <div class="p-5 rounded-2xl shadow-sm text-sm leading-relaxed relative
                        {{ $reply->user_id === auth()->id() 
                            ? 'bg-gradient-to-br from-red-600 to-red-700 text-white rounded-tr-none' 
                            : 'bg-white text-gray-700 border border-gray-100 rounded-tl-none' }}">
                        
                        {{-- Name Tag --}}
                        <span class="block text-[10px] font-bold uppercase mb-1 opacity-70">
                            {{ $reply->user->name }}
                        </span>
                        
                        {{ $reply->content }}
                    </div>
                    <div class="text-[10px] font-bold text-gray-400 mt-1 {{ $reply->user_id === auth()->id() ? 'text-right' : 'text-left' }}">
                        {{ $reply->created_at->diffForHumans() }}
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        {{-- REPLY FORM (Fixed Bottom on Mobile, Inline on Desktop) --}}
        <div class="fixed bottom-0 left-0 w-full bg-white border-t border-gray-200 p-4 z-40 md:relative md:border-none md:bg-transparent md:p-0">
            <div class="max-w-4xl mx-auto flex gap-4 items-end">
                <img src="{{ auth()->user()->profile_photo_url }}" class="w-10 h-10 rounded-full border border-gray-200 hidden md:block">
                <div class="grow relative bg-white md:shadow-lg md:rounded-2xl md:p-2 md:border md:border-gray-200 w-full">
                    <textarea wire:model="newReply" rows="2" class="w-full border-none focus:ring-0 resize-none bg-transparent text-sm p-3" placeholder="Write a reply..."></textarea>
                    <div class="flex justify-between items-center px-3 pb-2">
                        <span class="text-[10px] text-gray-400 font-bold uppercase tracking-wider hidden md:inline">Be kind & constructive</span>
                        <button wire:click="postReply" class="ml-auto px-6 py-2 bg-gray-900 text-white text-xs font-bold uppercase rounded-lg hover:bg-red-600 transition shadow-md">
                            Reply
                        </button>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>