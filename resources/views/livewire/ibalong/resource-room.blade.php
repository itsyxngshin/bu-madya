<div class="max-w-5xl mx-auto space-y-8 pb-24">
    <div class="bg-white dark:bg-[#1A1617] p-6 border-4 border-iba-black dark:border-iba-light shadow-[8px_8px_0_0_#FF8623]">
        <h1 class="text-2xl font-black text-iba-black dark:text-iba-light uppercase tracking-wider">Resource Vault</h1>
        <p class="text-sm font-bold text-gray-500 mt-1">Access essential hackathon files, datasets, and templates here.</p>
    </div>

    @if (session()->has('success'))
        <div class="bg-iba-green/10 border-l-4 border-iba-green p-4 flex items-center">
            <p class="text-sm font-bold text-iba-green uppercase tracking-wider">{{ session('success') }}</p>
        </div>
    @endif

    {{-- ADMIN CREATE SECTION --}}
    @if($isAdmin)
        <div x-data="{ open: false }" class="bg-gray-100 dark:bg-gray-800 border-4 border-iba-black p-6">
            <button @click="open = !open" class="flex items-center justify-between w-full text-left font-black uppercase text-iba-black dark:text-white">
                <span>➕ Upload New Resource Pack</span>
                <span x-text="open ? '−' : '+'"></span>
            </button>
            
            <div x-show="open" x-collapse class="mt-6 pt-6 border-t-4 border-iba-black">
                <form wire:submit.prevent="createResourceGroup" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] font-black uppercase text-gray-500 mb-1">Title</label>
                            <input type="text" wire:model="title" required class="w-full border-4 border-iba-black p-2 text-xs font-bold bg-white focus:outline-none">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black uppercase text-gray-500 mb-1">Schedule Drop (Leave empty to drop now)</label>
                            <input type="datetime-local" wire:model="availableAt" class="w-full border-4 border-iba-black p-2 text-xs font-bold bg-white focus:outline-none">
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-[10px] font-black uppercase text-gray-500 mb-1">Description / Instructions</label>
                        <textarea wire:model="description" rows="2" class="w-full border-4 border-iba-black p-2 text-xs font-bold bg-white focus:outline-none resize-none"></textarea>
                    </div>

                    <div class="flex items-center gap-4">
                        <div class="flex-1">
                            <input type="file" wire:model="uploads" multiple class="w-full border-4 border-iba-black bg-white text-xs font-bold p-1 cursor-pointer file:mr-4 file:py-2 file:px-4 file:border-0 file:bg-iba-black file:text-white file:font-black file:uppercase">
                        </div>
                        <div class="flex items-center gap-2">
                            <input type="checkbox" wire:model="isVisible" id="visible" class="w-5 h-5 border-2 border-iba-black">
                            <label for="visible" class="text-[10px] font-black uppercase text-iba-black">Visible to Cohort</label>
                        </div>
                    </div>
                    
                    <button type="submit" class="w-full bg-iba-orange text-iba-black font-black px-6 py-3 text-xs uppercase border-4 border-iba-black shadow-[4px_4px_0_0_#131011] hover:translate-y-0.5 hover:shadow-none transition-all">Upload Pack</button>
                </form>
            </div>
        </div>
    @endif

    {{-- RESOURCE BARS --}}
    <div class="space-y-6">
        @forelse($resourceGroups as $group)
            <div x-data="{ expanded: false }" class="bg-white dark:bg-[#1A1617] border-4 border-iba-black dark:border-iba-light shadow-[6px_6px_0_0_#131011] dark:shadow-[6px_6px_0_0_#FFFBF7]">
                
                {{-- Admin Controls overlay --}}
                @if($isAdmin)
                    <div class="bg-gray-200 dark:bg-gray-800 border-b-4 border-iba-black px-4 py-2 flex justify-between items-center">
                        <span class="text-[9px] font-black uppercase tracking-widest {{ $group->is_visible ? 'text-iba-green' : 'text-iba-red' }}">
                            {{ $group->is_visible ? '👁 Visible' : '🚫 Hidden' }}
                            @if($group->available_at && $group->available_at > now())
                                | ⏳ Drops at: {{ $group->available_at->format('M d, Y h:i A') }}
                            @endif
                        </span>
                        <div class="flex gap-4">
                            <button wire:click="toggleVisibility({{ $group->id }})" class="text-[9px] font-black uppercase text-gray-500 hover:text-iba-black">Toggle Vis</button>
                            <button wire:click="deleteGroup({{ $group->id }})" class="text-[9px] font-black uppercase text-iba-red hover:text-red-700">Delete</button>
                        </div>
                    </div>
                @endif

                {{-- Header Bar (Click to expand) --}}
                <div @click="expanded = !expanded" class="p-5 cursor-pointer flex justify-between items-center hover:bg-gray-50 dark:hover:bg-gray-900 transition-colors">
                    <div>
                        <h2 class="text-lg font-black text-iba-black dark:text-white uppercase">{{ $group->title }}</h2>
                        @if($group->description)
                            <p class="text-xs font-bold text-gray-600 dark:text-gray-400 mt-1">{{ $group->description }}</p>
                        @endif
                    </div>
                    <div class="shrink-0 ml-4 bg-iba-black text-white w-8 h-8 flex items-center justify-center font-black">
                        <span x-text="expanded ? '−' : '+'"></span>
                    </div>
                </div>

                {{-- File List --}}
                <div x-show="expanded" x-collapse class="border-t-4 border-iba-black dark:border-iba-light bg-gray-50 dark:bg-gray-900 p-5 space-y-3">
                    @forelse($group->files as $file)
                        <div class="flex items-center justify-between border-2 border-dashed border-gray-300 dark:border-gray-700 p-3 bg-white dark:bg-gray-800">
                            <div class="flex items-center gap-3 overflow-hidden">
                                <svg class="w-6 h-6 text-iba-teal shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                                <span class="text-xs font-bold text-gray-800 dark:text-gray-200 truncate">{{ $file->file_name }}</span>
                                <span class="text-[10px] font-black text-gray-400 ml-2 shrink-0">{{ $file->file_size }}</span>
                            </div>
                            <a href="{{ Storage::url($file->file_path) }}" download class="shrink-0 bg-iba-teal text-white text-[10px] font-black uppercase px-4 py-2 border-2 border-iba-black shadow-[2px_2px_0_0_#131011] hover:translate-y-0.5 hover:shadow-none transition-all">Download</a>
                        </div>
                    @empty
                        <p class="text-xs font-bold text-gray-500 uppercase">No files in this pack.</p>
                    @endforelse
                </div>

            </div>
        @empty
            <div class="bg-white dark:bg-[#1A1617] border-4 border-iba-black border-dashed p-12 text-center shadow-sm">
                <p class="text-sm font-black text-gray-500 uppercase tracking-widest">No resources are currently available.</p>
            </div>
        @endforelse
    </div>
</div>