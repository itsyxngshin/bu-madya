{{-- REMOVED hardcoded z-20. ADDED root shareOpen state and dynamic :class --}}
<div x-data="{ showReacts: false, showComments: false, shareOpen: false, copied: false }" 
     class="mt-1 relative transition-all duration-75"
     :class="(showReacts || shareOpen) ? 'z-[60]' : 'z-0'">

    {{-- STATS SUMMARY --}}
    <div class="px-3 py-1.5 flex items-center justify-between text-[10px] font-bold text-gray-500 border-b border-gray-100 mx-1">
        <div wire:click="openReactors" class="flex items-center gap-1.5 cursor-pointer hover:bg-gray-50 px-1 py-0.5 rounded transition-colors">
            @if(array_sum($elementCounts) > 0)
                <span class="flex -space-x-1">
                    @foreach($elementCounts as $type => $count)
                        @if($count > 0 && isset($availableElements[$type]))
                            <span class="w-4 h-4 rounded-full bg-gray-50 flex items-center justify-center text-[9px] shadow-sm border border-white z-10">{{ $availableElements[$type]['icon'] }}</span>
                        @endif
                    @endforeach
                </span>
                <span class="ml-0.5 hover:underline">{{ array_sum($elementCounts) }}</span>
            @endif
        </div>
        <div @click="showComments = !showComments" class="hover:underline cursor-pointer transition-colors">
            {{ $totalComments > 0 ? $totalComments . ' Comments' : '' }}
        </div>
    </div>

    {{-- ACTION BUTTONS --}}
    <div class="px-2 py-1 flex items-center justify-between relative gap-1">
        
        {{-- 1. React Dropdown --}}
        <div class="flex-1 relative" @mouseenter="showReacts = true" @mouseleave="showReacts = false">
            <div x-show="showReacts" x-transition.opacity class="absolute bottom-full left-0 mb-1 bg-white rounded-full shadow-[0_8px_30px_rgb(0,0,0,0.12)] border border-gray-100 p-1 flex gap-0.5 z-[100]" style="display: none;">
                @foreach($availableElements as $key => $data)
                    <button wire:click="toggleElement('{{ $key }}')" @click="showReacts = false" class="w-8 h-8 rounded-full hover:bg-gray-50 flex items-center justify-center text-base hover:scale-125 hover:-translate-y-1 transition-all outline-none" title="{{ $data['label'] }}">
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

        {{-- 3. Share Dropdown (Nested x-data removed, now using root state) --}}
        <div class="flex-1 relative">
            <button @click="shareOpen = !shareOpen" @click.away="shareOpen = false" class="w-full flex items-center justify-center gap-1.5 py-1.5 rounded-md hover:bg-gray-50 text-gray-600 font-bold text-[11px] md:text-[12px] transition-colors outline-none">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
                <span class="hidden sm:inline">Share</span>
            </button>
            <div x-show="shareOpen" x-transition.opacity class="absolute bottom-full right-0 mb-1 w-40 bg-white border border-gray-100 rounded-xl shadow-[0_8px_30px_rgb(0,0,0,0.12)] z-[100] py-1" style="display: none;">
                <button wire:click="requirkPost" @click="shareOpen = false" class="w-full text-left px-4 py-2 text-[12px] font-bold text-gray-700 hover:bg-gray-50 hover:text-green-600 flex items-center gap-2 outline-none">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                    Re-quirk
                </button>
                <div class="border-t border-gray-50 my-1"></div>
                <button @click="navigator.clipboard.writeText('{{ route('community.posts.show', $post->slug) }}'); copied = true; setTimeout(() => { copied = false; shareOpen = false; }, 1500)" class="w-full text-left px-4 py-2 text-[12px] font-bold text-gray-700 hover:bg-gray-50 hover:text-blue-600 flex items-center gap-2 outline-none">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path></svg>
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

    {{-- STANDARD IN-PLACE MODAL: WHO REACTED --}}
    @if($showReactorsModal)
        <div class="fixed inset-0 z-[999999] flex items-center justify-center bg-black/60 backdrop-blur-sm p-4 animate-fade-in">
            <div @click.away="$wire.closeReactors()" class="bg-white rounded-[1.25rem] shadow-2xl max-w-sm w-full relative overflow-hidden flex flex-col max-h-[80vh]">
                <div class="flex items-center justify-between p-4 border-b border-gray-100 bg-white z-10 shrink-0">
                    <h3 class="text-base font-black text-gray-900 tracking-tight">Reactions</h3>
                    <button wire:click="closeReactors" class="text-gray-400 hover:text-gray-900 bg-gray-50 hover:bg-gray-100 rounded-full p-1.5 transition-colors outline-none">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
                <div class="overflow-y-auto p-2 space-y-1 bg-gray-50/30 flex-1">
                    @forelse($this->reactorsList as $element)
                        <div class="flex items-center justify-between p-2.5 hover:bg-gray-50 rounded-xl transition-colors">
                            <div class="flex items-center gap-3">
                                @php
                                    $reactorName = $element->user->name ?? 'Unknown User';
                                    $photoPath = $element->user->profile_photo_path ?? null;
                                    $photoUrl = $photoPath ? (Str::startsWith($photoPath, ['http', 'images/']) ? asset($photoPath) : asset('storage/' . $photoPath)) : 'https://ui-avatars.com/api/?name='.urlencode($reactorName).'&color=EF4444&background=FEF2F2';
                                @endphp
                                <a href="{{ route('profile.public', $element->user->username ?? 'unknown') }}" class="w-10 h-10 rounded-full shrink-0 shadow-sm border border-gray-100 bg-gray-100 overflow-hidden relative">
                                    <img src="{{ $photoUrl }}" class="w-full h-full object-cover">
                                    <span class="absolute -bottom-1 -right-1 text-[12px] bg-white rounded-full p-0.5 shadow-sm">
                                        {{ $availableElements[$element->type]['icon'] ?? '👍' }}
                                    </span>
                                </a>
                                <div>
                                    <a href="{{ route('profile.public', $element->user->username ?? 'unknown') }}" class="text-[13px] font-black text-gray-900 hover:text-red-600 transition-colors block leading-tight">{{ $reactorName }}</a>
                                    <span class="text-[11px] text-gray-500 font-medium">{{ $element->created_at->diffForHumans() }}</span>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8 text-gray-400 text-[12px] font-bold">No reactions yet.</div>
                    @endforelse
                </div>
            </div>
        </div>
    @endif
</div>