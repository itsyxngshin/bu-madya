<div class="max-w-4xl mx-auto space-y-8 pb-24">
    
    <div class="mb-4">
        <a href="{{ route('ibalong.community-logs') }}" class="inline-flex items-center gap-2 text-xs font-black text-gray-500 hover:text-iba-red uppercase tracking-widest transition-colors">
            &larr; Back to Full Feed
        </a>
    </div>

    {{-- SINGLE POST FOCUS --}}
    <div class="bg-white dark:bg-[#1A1617] border-4 border-iba-black dark:border-iba-light shadow-[12px_12px_0_0_#0095AC] flex flex-col relative">
        
        {{-- ... (Paste the identical Post Header, Content, Image Grid, and Action Bar from community-logs.blade.php here, but remove the line-clamp logic since it's the single page) ... --}}

        {{-- Expanded Comments Section --}}
        <div class="bg-gray-100 dark:bg-gray-800 border-t-4 border-iba-black dark:border-iba-light p-6 sm:p-8 space-y-6">
            
            <h3 class="text-sm font-black uppercase tracking-widest text-iba-black dark:text-white border-b-2 border-dashed border-gray-300 dark:border-gray-700 pb-2 mb-6">Discussion Thread</h3>

            @foreach($post->comments as $comment)
                {{-- ... (Paste the identical Nested Comments logic from previous step here) ... --}}
            @endforeach

            {{-- Main Add Comment Form (Always visible at bottom) --}}
            <form wire:submit.prevent="addComment()" class="mt-8 pt-6 border-t-2 border-dashed border-gray-300 dark:border-gray-700">
                <label class="block text-[10px] font-black uppercase tracking-widest text-gray-500 mb-2">Join the Conversation</label>
                <div class="flex flex-col sm:flex-row gap-3">
                    <div class="shrink-0">
                        <select wire:model="postingAs" class="w-full sm:w-48 border-4 border-iba-black dark:border-iba-light bg-gray-50 dark:bg-gray-800 text-iba-black dark:text-white text-[10px] font-black uppercase tracking-widest p-3 focus:outline-none focus:border-iba-teal">
                            @foreach($availableIdentities as $value => $label)
                                <option value="{{ $value }}">{{ $value }}</option>
                            @endforeach
                        </select>
                    </div>
                    <input type="text" wire:model="newComments.main" placeholder="Write a comment..." class="flex-1 border-4 border-iba-black dark:border-iba-light p-3 text-sm focus:outline-none focus:border-iba-orange bg-white dark:bg-[#1A1617] text-iba-black dark:text-white font-bold">
                    <button type="submit" class="bg-iba-orange text-iba-black font-black px-6 py-3 text-xs uppercase border-4 border-iba-black shadow-[4px_4px_0_0_#131011] hover:translate-y-0.5 hover:shadow-none transition-all w-full sm:w-auto">Post Reply</button>
                </div>
            </form>
        </div>
    </div>
</div>