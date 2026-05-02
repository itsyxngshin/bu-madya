<div x-data="{ showReacts: false, showComments: false, showReactors: false }">

    @php
        $totalComments = $post->comments()->count();
        $recentComments = $post->comments()->latest()->take(3)->get();
    @endphp

    {{-- STATS SUMMARY --}}
    <div class="px-3 py-2 flex items-center justify-between text-[11px] font-medium text-gray-500 border-b border-gray-50">
        <div @click="showReactors = true" class="flex items-center gap-1.5 cursor-pointer hover:bg-gray-50 px-1 py-0.5 rounded transition-colors group">
            @if(array_sum($elementCounts) > 0)
                <span class="flex -space-x-1">
                    @foreach($elementCounts as $type => $count)
                        @if($count > 0)
                            <span class="w-[18px] h-[18px] rounded-full bg-white flex items-center justify-center text-[10px] shadow-sm border border-gray-50 z-10">{{ $availableElements[$type]['icon'] }}</span>
                        @endif
                    @endforeach
                </span>
                <span class="ml-1 group-hover:text-gray-800 transition-colors">{{ array_sum($elementCounts) }}</span>
            @else
                <span class="ml-1 group-hover:text-gray-800 transition-colors">0</span>
            @endif
        </div>
        <div @click="showComments = !showComments" class="hover:underline cursor-pointer transition-colors px-1 py-0.5">
            {{ $totalComments > 0 ? $totalComments . ' Comments' : '' }}
        </div>
    </div>

    {{-- ACTION BUTTONS --}}
    <div class="px-2 py-1 flex items-center justify-between relative">
        <div class="flex-1 relative" @mouseenter="showReacts = true" @mouseleave="showReacts = false">

            <div x-show="showReacts" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2 scale-95" x-transition:enter-end="opacity-100 translate-y-0 scale-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 translate-y-0 scale-100" x-transition:leave-end="opacity-0 translate-y-2 scale-95" class="absolute bottom-full left-0 mb-1 bg-white rounded-full shadow-[0_8px_30px_rgb(0,0,0,0.12)] border border-gray-100 p-1.5 flex gap-1 z-50 origin-bottom-left" style="display: none;">
                @foreach($availableElements as $key => $data)
                    <button wire:click="toggleElement('{{ $key }}')" @click="showReacts = false" class="w-9 h-9 rounded-full hover:bg-gray-50 flex items-center justify-center text-[18px] hover:scale-125 hover:-translate-y-1 transition-all duration-200 transform origin-bottom focus:outline-none" title="{{ $data['label'] }}">
                        {{ $data['icon'] }}
                    </button>
                @endforeach
            </div>

            <button @click="showReacts = !showReacts" class="w-full flex items-center justify-center gap-1.5 py-1.5 rounded-md hover:bg-gray-50 font-bold text-[12px] transition-colors {{ $userElement ? $availableElements[$userElement]['color'] : 'text-gray-600' }}">
                @if($userElement)
                    <span class="text-[16px] leading-none">{{ $availableElements[$userElement]['icon'] }}</span>
                    {{ $availableElements[$userElement]['label'] }}
                @else
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5"></path>
                     </svg>

                    React
                @endif
            </button>
        </div>

        <button @click="showComments = !showComments" class="flex-1 flex items-center justify-center gap-1.5 py-1.5 rounded-md hover:bg-gray-50 text-gray-600 font-bold text-[12px] transition-colors"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
             </svg>
            Comment
        </button>
    </div>

    {{-- INLINE COMMENT SECTION --}}
    <div x-show="showComments" x-collapse class="border-t border-gray-50 bg-gray-50/30">
        <div class="p-3 space-y-3">
            @if($totalComments > 3)
                <a href="{{ route('community.posts.show', $post->slug) }}" class="text-[11px] font-semibold text-gray-400 hover:text-blue-600 hover:underline block mb-1">
                    View previous {{ $totalComments - 3 }} comments...
                </a>
            @endif

            <div class="space-y-3">
                @foreach($recentComments as $comment)
                    <div class="flex gap-2">
                        @php
                            $commentPhotoPath = $comment->user->profile_photo_path ?? null;
                            $commentPhotoUrl = $commentPhotoPath ? (Str::startsWith($commentPhotoPath, ['http', 'images/']) ? asset($commentPhotoPath) : asset('storage/' . $commentPhotoPath)) : 'https://ui-avatars.com/api/?name='.urlencode($comment->user->name).'&color=EF4444&background=FEF2F2';
                        @endphp
                        <a href="{{ route('profile.public', $comment->user->username ?? 'unknown') }}" class="w-6 h-6 rounded-full shrink-0 shadow-sm overflow-hidden border border-gray-100 mt-0.5">
                            <img src="{{ $commentPhotoUrl }}" class="w-full h-full object-cover">
                        </a>
                        <div class="bg-white px-3 py-1.5 rounded-[1rem] rounded-tl-sm border border-gray-100 shadow-[0_1px_2px_rgba(0,0,0,0.02)] text-sm max-w-[90%]">
                            <a href="{{ route('profile.public', $comment->user->username ?? 'unknown') }}" class="font-bold text-gray-900 text-[12px] hover:underline">{{ $comment->user->name }}</a>
                            <p class="text-gray-700 text-[13px] leading-snug mt-0.5">{{ $comment->content }}</p>
                        </div>
                    </div>
                @endforeach
            </div>

            @auth
                <div class="flex gap-2 mt-3 pt-3 border-t border-gray-100/50">
                    @php
                        $myPhotoPath = auth()->user()->profile_photo_path;
                        $myPhotoUrl = $myPhotoPath ? (Str::startsWith($myPhotoPath, ['http', 'images/']) ? asset($myPhotoPath) : asset('storage/' . $myPhotoPath)) : 'https://ui-avatars.com/api/?name='.urlencode(auth()->user()->name).'&color=EF4444&background=FEF2F2';
                    @endphp
                    <div class="w-7 h-7 rounded-full shrink-0 shadow-sm overflow-hidden border border-gray-100">
                        <img src="{{ $myPhotoUrl }}" class="w-full h-full object-cover">
                    </div>
                    <div class="flex-1 relative">
                        <input wire:model="newComment" wire:keydown.enter="postComment" type="text" placeholder="Write a comment..." class="w-full bg-white border border-gray-200 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none rounded-full px-3.5 py-1.5 text-[13px] shadow-sm transition-all">
                        <div wire:loading wire:target="postComment" class="absolute right-3 top-2 w-3.5 h-3.5 border-2 border-blue-600 border-t-transparent rounded-full animate-spin"></div>
                    </div>
                </div>
            @endauth
        </div>
    </div>


    {{-- MODAL: WHO REACTED --}}
    <div x-show="showReactors" style="display: none;" class="relative z-50" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div x-transition.opacity class="fixed inset-0 bg-gray-900/20 backdrop-blur-sm" @click="showReactors = false"></div>
        <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
            <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
                <div x-show="showReactors" @click.away="showReactors = false"
                     x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     class="relative transform overflow-hidden rounded-[1.5rem] bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-sm border border-gray-100">

                    {{-- Modal Header --}}
                    <div class="bg-gray-50 px-5 py-3.5 border-b border-gray-100 flex items-center justify-between">
                        <h3 class="text-xs font-black text-gray-900 uppercase tracking-widest">Reactions</h3>
                        <button @click="showReactors = false" class="text-gray-400 hover:text-gray-900 transition-colors bg-white rounded-full p-1 border border-gray-200 shadow-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>

                    {{-- Modal List --}}
                    <div class="max-h-64 overflow-y-auto p-2 hide-scrollbar">
                        @if($post->elements && $post->elements->count() > 0)
                            @foreach($post->elements as $element)
                                @php
                                    $reactor = $element->user;
                                    $reactorPhoto = $reactor->profile_photo_path ? (Str::startsWith($reactor->profile_photo_path, ['http', 'images/']) ? asset($reactor->profile_photo_path) : asset('storage/' . $reactor->profile_photo_path)) : 'https://ui-avatars.com/api/?name='.urlencode($reactor->name ?? 'U').'&color=EF4444&background=FEF2F2';
                                @endphp
                                <div class="flex items-center justify-between px-3 py-2 hover:bg-gray-50 rounded-xl transition-colors">
                                    <a href="{{ route('profile.public', $reactor->username ?? 'unknown') }}" class="flex items-center gap-3">
                                        <img src="{{ $reactorPhoto }}" class="w-8 h-8 rounded-full border border-gray-100 object-cover shadow-sm">
                                        <span class="text-[13px] font-black text-gray-900 hover:text-red-600 transition-colors">{{ $reactor->name ?? 'Unknown User' }}</span>
                                    </a>

                                    {{-- Element Icon. Fallback to Solidarity if type is null/invalid --}}
                                    <span class="text-lg drop-shadow-sm" title="{{ $element->type ?? 'Reaction' }}">
                                        {{ $availableElements[$element->type ?? 'solidarity']['icon'] ?? '✨' }}
                                    </span>
                                </div>
                            @endforeach
                        @else
                            <div class="text-center py-6">
                                <p class="text-xs font-bold text-gray-400">No reactions yet.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
