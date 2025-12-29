<div class="min-h-screen bg-gray-50 font-sans text-gray-900 pb-20">
    
    {{-- HEADER --}}
    <div class="bg-gray-900 text-white pt-10 pb-20 px-4 md:px-0 rounded-b-[40px] shadow-xl relative overflow-hidden">
        <div class="absolute top-[-50%] right-[-10%] w-96 h-96 bg-red-600 rounded-full mix-blend-overlay filter blur-3xl opacity-20"></div>
        
        <div class="max-w-4xl mx-auto text-center relative z-10">
            <div class="inline-flex items-center gap-2 bg-white/10 px-4 py-1.5 rounded-full border border-white/10 mb-4">
                <span class="w-2 h-2 bg-yellow-400 rounded-full animate-pulse"></span>
                <span class="text-[10px] font-bold uppercase tracking-widest text-gray-300">Members Forum</span>
            </div>
            <h1 class="font-heading font-black text-3xl md:text-5xl mb-2">The Roundtable</h1>
            <p class="text-gray-400 text-sm md:text-base max-w-xl mx-auto">
                A space for dialogue, debate, and discourse.
            </p>
        </div>
    </div>

    {{-- MAIN CONTENT --}}
    <div class="max-w-4xl mx-auto px-4 -mt-10 relative z-20">
        
        {{-- Toolbar --}}
        <div class="bg-white p-4 rounded-2xl shadow-lg border border-gray-100 flex flex-col md:flex-row gap-4 justify-between items-center mb-6">
            <div class="relative w-full md:w-96">
                <svg class="w-4 h-4 absolute left-4 top-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                <input wire:model.live.debounce="search" type="text" placeholder="Find a discussion..." class="w-full pl-10 pr-4 py-2.5 bg-gray-50 border-gray-200 rounded-xl text-sm focus:ring-red-500">
            </div>
            <button wire:click="$set('isCreateModalOpen', true)" class="w-full md:w-auto px-6 py-2.5 bg-red-600 text-white font-bold text-xs uppercase tracking-widest rounded-xl hover:bg-red-700 transition shadow-md flex items-center justify-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Start Topic
            </button>
        </div>

        {{-- Topics List --}}
        <div class="space-y-4">
            @forelse($topics as $topic)
            <div class="group bg-white rounded-2xl p-6 border border-gray-100 hover:border-red-200 hover:shadow-lg transition-all duration-300 relative overflow-hidden">
                @if($topic->is_pinned)
                    <div class="absolute top-0 right-0 bg-yellow-400 text-yellow-900 text-[9px] font-black uppercase px-3 py-1 rounded-bl-xl">Pinned</div>
                @endif

                <div class="flex items-start gap-4">
                    <img src="{{ $topic->user->profile_photo_url }}" class="w-10 h-10 rounded-full border border-gray-200">
                    <div class="flex-1">
                        <a href="{{ route('roundtable.show', $topic->id) }}" class="block">
                            <h3 class="font-heading font-bold text-lg text-gray-900 group-hover:text-red-600 transition mb-1">{{ $topic->headline }}</h3>
                        </a>
                        <p class="text-sm text-gray-500 line-clamp-2 mb-3">{{ Str::limit($topic->content, 120) }}</p>
                        
                        <div class="flex items-center gap-4 text-xs text-gray-400 font-medium">
                            <span class="text-gray-600">By {{ $topic->user->name }}</span>
                            <span>•</span>
                            <span>{{ $topic->created_at->diffForHumans() }}</span>
                            <span class="ml-auto flex items-center gap-1 bg-gray-50 px-2 py-1 rounded-lg text-gray-500">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                                {{ $topic->replies_count }} replies
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="text-center py-20">
                <div class="inline-block p-4 rounded-full bg-gray-100 text-gray-400 mb-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                </div>
                <p class="text-gray-500 font-medium">It's quiet here. Start a conversation!</p>
            </div>
            @endforelse
        </div>

        <div class="mt-6">
            {{ $topics->links() }}
        </div>
    </div>

    {{-- CREATE MODAL --}}
    @if($isCreateModalOpen)
    <div class="fixed inset-0 z-50 flex items-center justify-center px-4 bg-gray-900/60 backdrop-blur-sm">
        <div class="bg-white w-full max-w-lg rounded-2xl shadow-2xl p-6 md:p-8 animate-fade-in-up">
            <h2 class="font-heading font-black text-2xl mb-6">Start a Topic</h2>
            
            <div class="space-y-4">
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Topic Title</label>
                    <input wire:model="headline" type="text" class="w-full border-gray-200 rounded-xl text-sm focus:ring-red-500" placeholder="What's on your mind?">
                    @error('headline') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Content</label>
                    <textarea wire:model="content" rows="5" class="w-full border-gray-200 rounded-xl text-sm focus:ring-red-500" placeholder="Elaborate on your topic..."></textarea>
                    @error('content') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="flex justify-end gap-3 mt-8">
                <button wire:click="$set('isCreateModalOpen', false)" class="px-4 py-2 text-sm font-bold text-gray-500 hover:bg-gray-100 rounded-lg">Cancel</button>
                <button wire:click="createTopic" class="px-6 py-2 bg-red-600 text-white text-sm font-bold rounded-lg hover:bg-red-700 shadow-md">Post Topic</button>
            </div>
        </div>
    </div>
    @endif
</div>