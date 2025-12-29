<div class="min-h-screen bg-gray-50 p-6 lg:p-8">
    
    {{-- HEADER --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="font-heading font-black text-2xl text-gray-900">The Pillars Manager</h1>
            <p class="text-sm text-gray-500">Create and manage advocacy resolutions.</p>
        </div>
        <button wire:click="$set('isModalOpen', true)" class="px-5 py-2.5 bg-gray-900 text-white font-bold text-xs uppercase tracking-widest rounded-xl hover:bg-red-600 transition shadow-lg flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            New Pillar
        </button>
    </div>

    {{-- GRID OF PILLARS --}} 
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($polls as $poll) 
        <div class="bg-white rounded-[2rem] border border-gray-100 shadow-sm overflow-hidden flex flex-col group relative">
            
            {{-- Status Badge --}}
            <div class="absolute top-4 right-4 z-10">
                <button wire:click="toggleStatus({{ $poll->id }})" 
                        class="px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider transition shadow-sm
                        {{ $poll->is_active ? 'bg-green-100 text-green-700 hover:bg-green-200' : 'bg-gray-100 text-gray-500 hover:bg-gray-200' }}">
                    {{ $poll->is_active ? 'Active' : 'Closed' }}
                </button>
            </div>

            {{-- Image Preview --}}
            <div class="h-40 w-full bg-gray-100 relative">
                @if($poll->image_path)
                    <img src="{{ asset('storage/' . $poll->image_path) }}" class="w-full h-full object-cover">
                @else
                    <div class="w-full h-full flex items-center justify-center text-gray-300">
                        <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    </div>
                @endif
            </div>

            {{-- Content --}}
            <div class="p-6 flex-1 flex flex-col">
                <p class="text-[10px] font-bold text-red-500 uppercase tracking-widest mb-1">{{ $poll->title ?? 'Untitled Pillar' }}</p>
                <h3 class="font-bold text-gray-900 leading-tight mb-2">{{ $poll->question }}</h3>
                
                {{-- Options Summary --}}
                <div class="mt-auto pt-4 border-t border-gray-50 flex flex-wrap gap-2">
                    @foreach($poll->options as $opt)
                        <span class="text-[10px] font-bold px-2 py-1 bg-gray-50 text-gray-500 rounded border border-gray-100">
                            {{ $opt->label }} ({{ $opt->votes->count() }})
                        </span>
                    @endforeach
                </div>
            </div>

            {{-- Footer Actions --}}
            <div class="bg-gray-50 p-4 flex justify-between items-center border-t border-gray-100">
                <span class="text-[10px] font-bold text-gray-400 uppercase">
                    {{ $poll->options->sum(fn($o) => $o->votes->count()) }} Votes
                </span>
                
                <div class="flex gap-3">
                    {{-- EDIT BUTTON --}}
                    <button wire:click="edit({{ $poll->id }})" class="text-gray-400 hover:text-blue-600 transition" title="Edit Pillar">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                    </button>

                    {{-- DELETE BUTTON --}}
                    <button wire:click="delete({{ $poll->id }})" wire:confirm="Are you sure? This will delete all votes associated with this pillar." class="text-gray-400 hover:text-red-600 transition" title="Delete Pillar">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                    </button>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    {{-- CREATE MODAL --}}
    @if($isModalOpen)
    <div class="fixed inset-0 z-50 flex items-center justify-center px-4 bg-gray-900/60 backdrop-blur-sm">
        <div class="bg-white w-full max-w-2xl rounded-2xl shadow-2xl overflow-hidden flex flex-col max-h-[90vh]">
            
            <div class="p-6 border-b border-gray-100 flex justify-between items-center">
                <h2 class="font-heading font-black text-xl">
                    {{ $editingPollId ? 'Edit Pillar' : 'Create New Pillar' }}
                </h2>
                <button wire:click="$set('isModalOpen', false)" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <div class="p-6 overflow-y-auto space-y-6">
                
                {{-- 1. Main Info --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="col-span-1 md:col-span-2">
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Question</label>
                        <input wire:model="question" type="text" class="w-full border-gray-200 rounded-xl text-sm font-bold" placeholder="e.g. Should we adopt Resolution 101?">
                        @error('question') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Title (Optional)</label>
                        <input wire:model="title" type="text" class="w-full border-gray-200 rounded-xl text-sm" placeholder="e.g. Pillar 1: Education">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Cover Image</label>
                        <input wire:model="image" type="file" class="w-full text-xs text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-red-50 file:text-red-700 hover:file:bg-red-100">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Description / Context</label>
                    <textarea wire:model="description" rows="3" class="w-full border-gray-200 rounded-xl text-sm" placeholder="Explain the context of this vote..."></textarea>
                </div>

                {{-- 2. Dynamic Options --}}
                <div class="bg-gray-50 p-4 rounded-xl border border-gray-100">
                    <div class="flex justify-between items-center mb-2">
                        <label class="text-xs font-bold text-gray-700 uppercase">Voting Options</label>
                        <button wire:click="addOption" class="text-[10px] bg-white border border-gray-200 hover:bg-gray-100 text-gray-600 px-2 py-1 rounded shadow-sm">+ Add Option</button>
                    </div>
                    
                    <div class="space-y-2">
                        @foreach($options as $index => $option)
                        <div class="flex gap-2">
                            <input wire:model="options.{{ $index }}.label" type="text" class="grow border-gray-200 rounded-lg text-xs" placeholder="Label (e.g. Yes)">
                            
                            <select wire:model="options.{{ $index }}.color" class="border-gray-200 rounded-lg text-xs w-24">
                                <option value="gray">Gray</option>
                                <option value="green">Green</option>
                                <option value="red">Red</option>
                                <option value="yellow">Yellow</option>
                            </select>

                            <button wire:click="removeOption({{ $index }})" class="text-gray-400 hover:text-red-500">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </div>
                        @endforeach
                        @error('options') <span class="text-red-500 text-xs block mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>

            </div>

            <div class="p-6 border-t border-gray-100 bg-gray-50 flex justify-end gap-3">
                <button wire:click="$set('isModalOpen', false)" class="px-4 py-2 text-sm font-bold text-gray-500 hover:bg-gray-100 rounded-lg">Cancel</button>
                <button wire:click="save" class="px-6 py-2 bg-gray-900 text-white text-sm font-bold rounded-lg hover:bg-red-600 shadow-md">
                    Launch Pillar
                </button>
            </div>
        </div>
    </div>
    @endif

</div>