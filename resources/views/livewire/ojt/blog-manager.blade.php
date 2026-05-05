<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mt-6">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-lg font-black text-gray-900">OJT Journal</h2>
        <button wire:click="$set('showModal', true)" class="px-4 py-2 bg-red-50 text-red-600 hover:bg-red-100 font-bold rounded-lg transition-colors text-sm">
            + New Entry
        </button>
    </div>

    @if (session()->has('success'))
        <div class="mb-4 p-3 bg-green-50 text-green-700 text-sm font-bold rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    {{-- Recent Entries List with Image Support --}}
    <div class="space-y-4">
        @forelse($recentBlogs as $blog)
            <div class="p-4 border border-gray-100 rounded-xl hover:bg-gray-50 transition">
                <div class="flex justify-between items-start mb-2">
                    <h3 class="font-bold text-gray-900">{{ $blog->title }}</h3>
                    <span class="text-[10px] font-black uppercase px-2 py-1 bg-gray-100 text-gray-500 rounded">
                        {{ str_replace('_', ' ', $blog->type) }}
                    </span>
                </div>
                <p class="text-xs text-gray-500 mb-2">{{ \Carbon\Carbon::parse($blog->report_date)->format('M d, Y') }}</p>
                <p class="text-sm text-gray-700 mb-3 line-clamp-3">{{ $blog->content }}</p>

                {{-- Display the image if it exists --}}
                @if($blog->attachment_path)
                    <div class="mt-2 rounded-lg overflow-hidden border border-gray-100 max-h-48 relative">
                        <img src="{{ asset('storage/' . $blog->attachment_path) }}" alt="Attachment" class="w-full h-full object-cover">
                    </div>
                @endif
            </div>
        @empty
            <p class="text-sm text-gray-500 italic text-center py-4">No journal entries yet.</p>
        @endforelse
    </div>

    {{-- The Blog Editor Modal --}}
    @if($showModal)
        @teleport('body')
            <div class="fixed inset-0 z-[9999] flex items-center justify-center bg-black/60 backdrop-blur-sm p-4 overflow-y-auto">
                <div @click.away="$wire.set('showModal', false)" class="bg-white rounded-2xl shadow-2xl max-w-lg w-full p-6 my-8 relative">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-black text-gray-900">Write OJT Log</h3>
                        <button wire:click="$set('showModal', false)" class="text-gray-400 hover:text-gray-900"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
                    </div>

                    <form wire:submit.prevent="saveBlog" class="space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Date</label>
                                <input type="date" wire:model="reportDate" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-sm outline-none focus:border-red-500">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Log Type</label>
                                <select wire:model="type" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-sm outline-none focus:border-red-500">
                                    <option value="daily_report">Daily Report</option>
                                    <option value="weekly_summary">Weekly Summary</option>
                                </select>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Title</label>
                            <input type="text" wire:model="title" placeholder="e.g., UI Fixes & API Testing" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-sm outline-none focus:border-red-500">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Activities / Learnings</label>
                            <textarea wire:model="content" rows="4" placeholder="What did you accomplish today?" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-sm outline-none focus:border-red-500 resize-none"></textarea>
                        </div>

                        {{-- Photo Upload Section --}}
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Attach Photo (Optional)</label>

                            {{-- Custom File Upload Button --}}
                            <div class="flex items-center justify-center w-full">
                                <label class="flex flex-col items-center justify-center w-full h-24 border-2 border-dashed border-gray-300 rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100 transition-colors">
                                    <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                        <svg class="w-6 h-6 mb-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                                        <p class="text-[11px] text-gray-500"><span class="font-bold text-red-600">Click to upload</span> or drag and drop</p>
                                    </div>
                                    <input type="file" wire:model="photo" accept="image/*" class="hidden" />
                                </label>
                            </div>

                            <div wire:loading wire:target="photo" class="text-[10px] text-red-600 font-bold mt-1 animate-pulse">Uploading...</div>
                            @error('photo') <span class="text-[10px] text-red-600 font-bold mt-1">{{ $message }}</span> @enderror

                            {{-- Live Image Preview --}}
                            @if ($photo && !$errors->has('photo'))
                                <div class="mt-3 relative rounded-lg overflow-hidden border border-gray-200 inline-block h-32">
                                    <img src="{{ $photo->temporaryUrl() }}" class="h-full object-cover">
                                    <button type="button" wire:click="$set('photo', null)" class="absolute top-1 right-1 bg-black/50 text-white rounded-full p-1 hover:bg-red-600 transition">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                    </button>
                                </div>
                            @endif
                        </div>

                        <div class="flex justify-end gap-2 mt-6 pt-4 border-t border-gray-100">
                            <button type="button" wire:click="$set('showModal', false)" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold rounded-lg text-sm transition">Cancel</button>
                            <button type="submit" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-bold rounded-lg text-sm shadow-md transition-colors relative">
                                <span wire:loading.remove wire:target="saveBlog">Save Log</span>
                                <span wire:loading wire:target="saveBlog">Saving...</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        @endteleport
    @endif
</div>
