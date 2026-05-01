<div class="bg-white">
    
    {{-- STATS SUMMARY (Mimics the Feed) --}}
    <div class="px-5 md:px-6 py-3 flex items-center justify-between text-xs font-bold text-gray-500 border-b border-gray-100">
        <div class="flex items-center gap-1.5">
            @if(array_sum($elementCounts) > 0)
                <span class="flex -space-x-1">
                    @foreach($elementCounts as $type => $count)
                        @if($count > 0)
                            <span class="w-5 h-5 rounded-full bg-gray-50 flex items-center justify-center text-[11px] shadow-sm border border-white z-10">{{ $availableElements[$type]['icon'] }}</span>
                        @endif
                    @endforeach
                </span>
                <span class="ml-1 text-gray-600">{{ array_sum($elementCounts) }}</span>
            @else
                <span>No reactions yet</span>
            @endif
        </div>
        <div>
            {{ $comments->count() }} Comments
        </div>
    </div>

    {{-- INTERACTION BUTTONS (The Elements Bar) --}}
    <div class="px-4 md:px-6 py-2 flex flex-wrap items-center justify-start gap-1.5 md:gap-2 border-b border-gray-100 bg-gray-50/50">
        @foreach($availableElements as $key => $data)
            @php
                $isActive = $userElement === $key;
            @endphp
            
            <button wire:click="toggleElement('{{ $key }}')" 
                    class="flex items-center gap-1.5 px-3 py-1.5 rounded-full transition-all duration-200 transform active:scale-95 border
                        {{ $isActive ? $data['bg'] . ' ' . $data['color'] . ' border-transparent shadow-sm' : 'bg-white border-gray-200 hover:bg-gray-50 text-gray-600' }}">
                
                <span class="text-base leading-none filter {{ $isActive ? 'drop-shadow-sm' : 'grayscale opacity-70' }}">{{ $data['icon'] }}</span>
                <span class="text-[10px] font-black uppercase tracking-wider">{{ $data['label'] }}</span>
            </button>
        @endforeach
    </div>

    {{-- COMMENT SECTION --}}
    <div class="p-5 md:p-6 bg-white">
        
        {{-- Add Comment Input --}}
        @auth
            <div class="flex gap-3 mb-6">
                <div class="w-10 h-10 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center font-black shrink-0 border border-blue-200 shadow-sm">
                    {{ substr(auth()->user()->name, 0, 1) }}
                </div>
                <div class="flex-1 relative">
                    <textarea wire:model="newComment" rows="2" placeholder="Write a comment..." class="w-full bg-gray-50 border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 outline-none rounded-[1.25rem] px-4 py-3 text-sm text-gray-800 resize-none transition-all"></textarea>
                    
                    <div class="flex justify-between items-center mt-2">
                        @error('newComment') <span class="text-[10px] text-red-500 font-bold">{{ $message }}</span> @else <span></span> @enderror
                        <button wire:click="postComment" wire:loading.attr="disabled" class="px-4 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-[11px] font-black uppercase tracking-widest rounded-full shadow-md transition-all active:scale-95">
                            Post
                        </button>
                    </div>
                </div>
            </div>
        @else
            <div class="mb-6 p-4 bg-gray-50 rounded-2xl text-center border border-gray-100">
                <p class="text-xs font-bold text-gray-500">You must be logged in to join the discussion.</p>
                <a href="{{ route('login') }}" class="inline-block mt-2 text-xs font-black text-blue-600 uppercase tracking-widest hover:underline">Sign In Now</a>
            </div>
        @endauth

        {{-- Comments List (Facebook Bubble Style) --}}
        <div class="space-y-4">
            @forelse($comments as $comment)
                <div class="flex gap-3">
                    <div class="w-10 h-10 rounded-full bg-gray-100 border border-gray-200 text-gray-500 flex items-center justify-center font-black shrink-0 shadow-sm">
                        {{ substr($comment->user->name, 0, 1) }}
                    </div>
                    <div>
                        <div class="bg-gray-100 px-4 py-3 rounded-2xl rounded-tl-sm text-sm inline-block">
                            <p class="font-black text-gray-900 text-xs mb-0.5">{{ $comment->user->name }}</p>
                            <p class="text-gray-800 leading-snug">{{ $comment->content }}</p>
                        </div>
                        <div class="text-[10px] font-bold text-gray-400 mt-1 ml-2">
                            {{ $comment->created_at->diffForHumans() }}
                        </div>
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