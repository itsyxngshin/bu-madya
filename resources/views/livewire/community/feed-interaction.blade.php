<div x-data="{ showReacts: false, showComments: false }" class="mt-1 relative z-20">

    {{-- STATS SUMMARY --}}
    <div class="px-3 py-1.5 flex items-center justify-between text-[10px] font-bold text-gray-500 border-b border-gray-100 mx-1">
        <div class="flex items-center gap-1.5">
            @if(array_sum($elementCounts) > 0)
                <span class="flex -space-x-1">
                    @foreach($elementCounts as $type => $count)
                        @if($count > 0 && isset($availableElements[$type]))
                            <span class="w-4 h-4 rounded-full bg-gray-50 flex items-center justify-center text-[9px] shadow-sm border border-white z-10">{{ $availableElements[$type]['icon'] }}</span>
                        @endif
                    @endforeach
                </span>
                <span class="ml-0.5">{{ array_sum($elementCounts) }}</span>
            @endif
        </div>
        <div @click="showComments = !showComments" class="hover:underline cursor-pointer transition-colors">
            {{ $totalComments > 0 ? $totalComments . ' Comments' : '' }}
        </div>
    </div>

    {{-- ACTION BUTTONS --}}
    <div class="px-2 py-1 flex items-center justify-between relative gap-1">
        
        {{-- 1. React Button --}}
        <div class="flex-1 relative" @mouseenter="showReacts = true" @mouseleave="showReacts = false">
            <div x-show="showReacts" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2 scale-95" x-transition:enter-end="opacity-100 translate-y-0 scale-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 translate-y-0 scale-100" x-transition:leave-end="opacity-0 translate-y-2 scale-95" class="absolute bottom-full left-0 mb-1 bg-white rounded-full shadow-[0_8px_30px_rgb(0,0,0,0.12)] border border-gray-100 p-1 flex gap-0.5 z-[100] origin-bottom-left" style="display: none;">
                @foreach($availableElements as $key => $data)
                    <button wire:click="toggleElement('{{ $key }}')" @click="showReacts = false" class="w-8 h-8 rounded-full hover:bg-gray-50 flex items-center justify-center text-base hover:scale-125 hover:-translate-y-1 transition-all duration-200 transform origin-bottom focus:outline-none" title="{{ $data['label'] }}">
                        {{ $data['icon'] }}
                    </button>
                @endforeach
            </div>

            <button @click="showReacts = !showReacts" class="w-full flex items-center justify-center gap-1.5 py-1.5 rounded-md hover:bg-gray-50 font-bold text-[11px] md:text-[12px] transition-colors {{ $userElement ? $availableElements[$userElement]['color'] : 'text-gray-600' }}">
                @if($userElement)
                    <span class="text-[14px] leading-none">{{ $availableElements[$userElement]['icon'] }}</span>
                    <span class="hidden sm:inline">{{ $availableElements[$userElement]['label'] }}</span>
                @else
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5"></path></svg>
                    <span class="hidden sm:inline">React</span>
                @endif
            </button>
        </div>

        {{-- 2. Comment Button --}}
        <button @click="showComments = !showComments" class="flex-1 flex items-center justify-center gap-1.5 py-1.5 rounded-md hover:bg-gray-50 text-gray-600 font-bold text-[11px] md:text-[12px] transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
            <span class="hidden sm:inline">Comment</span>
        </button>

        {{-- 3. Re-quirk / Share Button --}}
        <div class="flex-1 relative" x-data="{ showShare: false, copied: false }">
            <button @click="showShare = !showShare" class="w-full flex items-center justify-center gap-1.5 py-1.5 rounded-md hover:bg-gray-50 text-gray-600 font-bold text-[11px] md:text-[12px] transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
                <span class="hidden sm:inline">Re-quirk</span>
            </button>

            <div x-show="showShare" @click.away="showShare = false" style="display: none;" class="absolute bottom-full right-0 mb-1 w-36 bg-white rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.12)] border border-gray-100 py-1.5 z-[100] overflow-hidden origin-bottom-right">
                <button wire:click="requirkPost" @click="showShare = false" class="flex items-center gap-2 w-full text-left px-3 py-2 text-[11px] font-bold text-gray-700 hover:bg-gray-50 hover:text-green-600 transition-colors outline-none">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                    Re-quirk
                </button>
                <div class="border-t border-gray-50 my-0.5"></div>
                <button @click="navigator.clipboard.writeText('{{ route('community.posts.show', $post->slug) }}'); copied = true; setTimeout(() => { copied = false; showShare = false; }, 1500)" class="flex items-center gap-2 w-full text-left px-3 py-2 text-[11px] font-bold text-gray-700 hover:bg-gray-50 hover:text-blue-600 transition-colors outline-none">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path></svg>
                    <span x-text="copied ? 'Copied!' : 'Copy Link'"></span>
                </button>
            </div>
        </div>
    </div>

    {{-- INLINE COMMENT SECTION --}}
    <div x-show="showComments" x-collapse class="border-t border-gray-100 bg-gray-50/30 rounded-b-[1rem]">
        <div class="p-3 space-y-3">
            @if($totalComments > 3)
                <a href="{{ route('community.posts.show', $post->slug) }}" class="text-[10px] font-bold text-gray-400 hover:text-blue-600 hover:underline block mb-1">
                    View previous {{ $totalComments - 3 }} comments...
                </a>
            @endif

            <div class="space-y-2.5">
                @foreach($recentComments as $comment)
                    <div class="flex gap-2">
                        @php
                            $commentPhotoPath = $comment->user->profile_photo_path ?? null;
                            $commentPhotoUrl = $commentPhotoPath ? (Str::startsWith($commentPhotoPath, ['http', 'images/']) ? asset($commentPhotoPath) : asset('storage/' . $commentPhotoPath)) : 'https://ui-avatars.com/api/?name='.urlencode($comment->user->name ?? 'User').'&color=EF4444&background=FEF2F2';
                        @endphp
                        <a href="{{ route('profile.public', $comment->user->username ?? 'unknown') }}" class="w-6 h-6 rounded-full shrink-0 shadow-sm overflow-hidden border border-gray-100 mt-0.5">
                            <img src="{{ $commentPhotoUrl }}" class="w-full h-full object-cover">
                        </a>
                        <div class="bg-gray-100/80 px-3 py-1.5 rounded-2xl rounded-tl-sm border border-gray-100/50 text-sm max-w-[90%]">
                            <a href="{{ route('profile.public', $comment->user->username ?? 'unknown') }}" class="font-black text-gray-900 text-[11px] hover:underline">{{ $comment->user->name ?? 'User' }}</a>
                            <p class="text-gray-700 text-[12px] leading-snug">{{ $comment->content }}</p>
                        </div>
                    </div>
                @endforeach
            </div>

            @auth
                <div class="flex gap-2 mt-3 pt-2 border-t border-gray-100/50">
                    @php
                        $myPhotoPath = auth()->user()->profile_photo_path ?? null;
                        $myPhotoUrl = $myPhotoPath ? (Str::startsWith($myPhotoPath, ['http', 'images/']) ? asset($myPhotoPath) : asset('storage/' . $myPhotoPath)) : 'https://ui-avatars.com/api/?name='.urlencode(auth()->user()->name ?? 'User').'&color=EF4444&background=FEF2F2';
                    @endphp
                    <div class="w-7 h-7 rounded-full shrink-0 shadow-sm overflow-hidden border border-gray-100">
                        <img src="{{ $myPhotoUrl }}" class="w-full h-full object-cover">
                    </div>
                    <div class="flex-1 relative">
                        <input wire:model="newComment" wire:keydown.enter="postComment" type="text" placeholder="Write a comment..." class="w-full bg-white border border-gray-200 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none rounded-full px-3 py-1.5 text-[12px] shadow-sm transition-all">
                        <div wire:loading wire:target="postComment" class="absolute right-3 top-2 w-3.5 h-3.5 border-2 border-blue-600 border-t-transparent rounded-full animate-spin"></div>
                    </div>
                </div>
            @else
                <p class="text-[10px] text-center font-bold text-gray-400 mt-2">
                    <a href="{{ route('login') }}" class="text-blue-600 hover:underline">Log in</a> to comment.
                </p>
            @endauth
        </div>
    </div>
</div>