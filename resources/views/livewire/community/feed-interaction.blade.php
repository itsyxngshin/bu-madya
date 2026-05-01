<div x-data="{ showReacts: false, showComments: false }" class="mt-2">
    
    {{-- STATS SUMMARY --}}
    <div class="px-4 py-2 flex items-center justify-between text-[11px] font-bold text-gray-500 border-b border-gray-100 mx-2">
        <div class="flex items-center gap-1.5">
            @if(array_sum($elementCounts) > 0)
                <span class="flex -space-x-1">
                    @foreach($elementCounts as $type => $count)
                        <span class="w-4 h-4 rounded-full bg-gray-50 flex items-center justify-center text-[10px] shadow-sm border border-white z-10">{{ $availableElements[$type]['icon'] }}</span>
                    @endforeach
                </span>
                <span>{{ array_sum($elementCounts) }} Elements</span>
            @else
                <span>No reactions yet</span>
            @endif
        </div>
        <div @click="showComments = !showComments" class="hover:underline cursor-pointer transition-colors">
            {{ $totalComments }} Comments
        </div>
    </div>

    {{-- ACTION BUTTONS --}}
    <div class="px-2 py-1 flex items-center justify-between relative">
        
        {{-- REACT BUTTON & HOVER POPUP --}}
        <div class="flex-1 relative" @mouseenter="showReacts = true" @mouseleave="showReacts = false">
            
            {{-- The Floating Elements Bar --}}
            <div x-show="showReacts" 
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 translate-y-2 scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                 x-transition:leave-end="opacity-0 translate-y-2 scale-95"
                 class="absolute bottom-full left-0 mb-2 bg-white rounded-[2rem] shadow-[0_8px_30px_rgb(0,0,0,0.12)] border border-gray-100 p-1.5 flex gap-1 z-50 origin-bottom-left" style="display: none;">
                
                @foreach($availableElements as $key => $data)
                    <button wire:click="toggleElement('{{ $key }}')" @click="showReacts = false" class="w-10 h-10 rounded-full hover:bg-gray-50 flex items-center justify-center text-xl hover:scale-125 hover:-translate-y-2 transition-all duration-200 transform origin-bottom focus:outline-none" title="{{ $data['label'] }}">
                        {{ $data['icon'] }}
                    </button>
                @endforeach
            </div>

            {{-- The Main React Button --}}
            <button @click="showReacts = !showReacts" class="w-full flex items-center justify-center gap-2 py-2 rounded-lg hover:bg-gray-50 font-bold text-sm transition-colors {{ $userElement ? $availableElements[$userElement]['color'] : 'text-gray-600' }}">
                @if($userElement)
                    <span class="text-lg leading-none">{{ $availableElements[$userElement]['icon'] }}</span>
                    {{ $availableElements[$userElement]['label'] }}
                @else
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5"></path></svg>
                    React
                @endif
            </button>
        </div>

        {{-- COMMENT BUTTON --}}
        <button @click="showComments = !showComments" class="flex-1 flex items-center justify-center gap-2 py-2 rounded-lg hover:bg-gray-50 text-gray-600 font-bold text-sm transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
            Comment
        </button>
    </div>

    {{-- INLINE COMMENT SECTION (Expands down) --}}
    <div x-show="showComments" x-collapse class="border-t border-gray-100 bg-gray-50/50">
        <div class="p-4 space-y-4">
            
            {{-- Quick View Comments --}}
            @if($totalComments > 3)
                <a href="{{ route('community.posts.show', $post->slug) }}" class="text-[11px] font-bold text-gray-500 hover:text-blue-600 hover:underline block mb-2">
                    View previous {{ $totalComments - 3 }} comments...
                </a>
            @endif

            <div class="space-y-3">
                @foreach($recentComments as $comment)
                    <div class="flex gap-2">
                        <div class="w-7 h-7 rounded-full bg-white text-gray-600 font-black flex items-center justify-center text-[10px] shrink-0 border border-gray-200 shadow-sm mt-0.5">
                            {{ substr($comment->user->name, 0, 1) }}
                        </div>
                        <div class="bg-white px-3 py-2 rounded-2xl rounded-tl-sm border border-gray-100 shadow-sm text-sm">
                            <p class="font-black text-gray-900 text-xs">{{ $comment->user->name }}</p>
                            <p class="text-gray-700 leading-snug">{{ $comment->content }}</p>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Input Field --}}
            @auth
                <div class="flex gap-2 mt-4 pt-2">
                    <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-600 font-black flex items-center justify-center text-xs shrink-0 border border-blue-200">
                        {{ substr(auth()->user()->name, 0, 1) }}
                    </div>
                    <div class="flex-1 relative">
                        <input wire:model="newComment" wire:keydown.enter="postComment" type="text" placeholder="Write a comment... (Press Enter to post)" class="w-full bg-white border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 outline-none rounded-full px-4 py-1.5 text-sm shadow-inner transition-all">
                        <div wire:loading wire:target="postComment" class="absolute right-3 top-2 w-4 h-4 border-2 border-blue-600 border-t-transparent rounded-full animate-spin"></div>
                    </div>
                </div>
            @else
                <p class="text-[10px] text-center font-bold text-gray-500 mt-2">
                    <a href="{{ route('login') }}" class="text-blue-600 hover:underline">Log in</a> to join the discussion.
                </p>
            @endauth
            
        </div>
    </div>
</div>