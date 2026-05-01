<div class="mt-8 border-t border-gray-100 pt-6">

    {{-- THE ELEMENTS BAR --}}
    <div class="flex flex-wrap items-center gap-2 md:gap-3 mb-8">
        @foreach($availableElements as $key => $data)
            @php
                $isActive = $userElement === $key;
                $count = $elementCounts[$key] ?? 0;
            @endphp

            <button wire:click="toggleElement('{{ $key }}')"
                    class="relative flex items-center gap-2 px-4 py-2 rounded-full border shadow-sm transition-all duration-300 transform active:scale-95
                           {{ $isActive ? $data['bg'] . ' ' . $data['color'] . ' ring-2 ring-offset-1 ring-current' : 'bg-white border-gray-200 text-gray-500 hover:bg-gray-50 hover:border-gray-300' }}">

                <span class="text-lg leading-none filter {{ $isActive ? 'drop-shadow-sm' : 'grayscale opacity-70' }} transition-all">{{ $data['icon'] }}</span>
                <span class="text-xs font-black uppercase tracking-wider">{{ $data['label'] }}</span>

                @if($count > 0)
                    <span class="ml-1 px-1.5 py-0.5 rounded-md text-[10px] font-bold {{ $isActive ? 'bg-white/50' : 'bg-gray-100' }}">{{ $count }}</span>
                @endif
            </button>
        @endforeach
    </div>

    {{-- COMMENT SECTION --}}
    <div class="bg-gray-50 rounded-3xl p-5 md:p-8 border border-gray-100">
        <h4 class="text-sm font-black text-gray-900 uppercase tracking-widest mb-6 flex items-center gap-2">
            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
            Community Discussion ({{ $comments->count() }})
        </h4>

        {{-- Add Comment Input --}}
        @auth
            <div class="flex gap-4 mb-8">
                <div class="w-10 h-10 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center font-black shrink-0 border border-blue-200 shadow-sm">
                    {{ substr(auth()->user()->name, 0, 1) }}
                </div>
                <div class="flex-1 relative">
                    <textarea wire:model="newComment" rows="2" placeholder="Share your thoughts..." class="w-full bg-white border border-gray-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 outline-none rounded-2xl px-4 py-3 text-sm text-gray-800 resize-none shadow-sm transition-all"></textarea>

                    <div class="flex justify-between items-center mt-2">
                        @error('newComment') <span class="text-[10px] text-red-500 font-bold">{{ $message }}</span> @else <span></span> @enderror
                        <button wire:click="postComment" wire:loading.attr="disabled" class="px-5 py-2 bg-gray-900 hover:bg-blue-600 text-white text-xs font-black uppercase tracking-widest rounded-xl shadow-md transition-all active:scale-95">
                            Post Reply
                        </button>
                    </div>
                </div>
            </div>
        @else
            <div class="mb-8 p-4 bg-white border border-gray-200 rounded-2xl text-center shadow-sm">
                <p class="text-xs font-bold text-gray-500">You must be logged in to join the discussion.</p>
                <a href="{{ route('login') }}" class="inline-block mt-2 text-xs font-black text-blue-600 uppercase tracking-widest hover:underline">Sign In Now</a>
            </div>
        @endauth

        {{-- Comments List --}}
        <div class="space-y-5">
            @forelse($comments as $comment)
                <div class="flex gap-4 group">
                    <div class="w-10 h-10 rounded-full bg-white border border-gray-200 text-gray-500 flex items-center justify-center font-black shrink-0 shadow-sm">
                        {{ substr($comment->user->name, 0, 1) }}
                    </div>
                    <div class="flex-1 bg-white border border-gray-100 rounded-2xl p-4 shadow-sm relative">
                        <div class="flex items-center justify-between mb-1">
                            <span class="text-sm font-black text-gray-900">{{ $comment->user->name }}</span>
                            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">{{ $comment->created_at->diffForHumans() }}</span>
                        </div>
                        <p class="text-sm text-gray-600 leading-relaxed">{{ $comment->content }}</p>
                    </div>
                </div>
            @empty
                <div class="text-center py-6">
                    <p class="text-xs font-bold text-gray-400">No comments yet. Be the first to spark the conversation!</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
