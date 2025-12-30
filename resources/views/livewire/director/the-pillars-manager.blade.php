<div class="min-h-screen p-6">
    
    {{-- Header --}}
    <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
        <div>
            <h1 class="font-heading font-black text-2xl text-gray-900">The Pillars Manager</h1>
            <p class="text-sm text-gray-500">Manage advocacy resolutions and polls.</p>
        </div>
        <button wire:click="create" class="bg-gray-900 text-white px-5 py-2.5 rounded-xl text-xs font-bold uppercase tracking-widest hover:bg-red-600 transition flex items-center gap-2 shadow-lg">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            New Pillar
        </button>
    </div>

    {{-- Cards Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($pillars as $pillar)
        <div class="bg-white rounded-[2rem] border border-gray-100 shadow-sm overflow-hidden flex flex-col relative group">
            
            {{-- Status --}}
            <button wire:click="toggleStatus({{ $pillar->id }})" class="absolute top-4 right-4 z-10 px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider transition shadow-sm {{ $pillar->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                {{ $pillar->is_active ? 'Active' : 'Closed' }}
            </button>

            {{-- Image --}}
            <div class="h-40 bg-gray-100 w-full relative">
                @if($pillar->image_path)
                    <img src="{{ asset('storage/'.$pillar->image_path) }}" class="w-full h-full object-cover">
                @else
                    <div class="w-full h-full flex items-center justify-center text-gray-300">
                        <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    </div>
                @endif
            </div>

            {{-- Body --}}
            <div class="p-6 flex-1 flex flex-col">
                <h3 class="font-bold text-gray-900 text-lg mb-2 leading-tight">{{ $pillar->title }}</h3>
                <p class="text-xs text-gray-500 line-clamp-2 mb-4">{{ $pillar->description }}</p>
                
                <div class="mt-auto pt-4 border-t border-gray-50">
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">
                        {{ $pillar->questions->count() }} Questions
                    </p>
                    <div class="flex flex-col gap-1">
                        @foreach($pillar->questions->take(2) as $q)
                            <span class="text-xs text-gray-600 truncate">• {{ $q->question_text }}</span>
                        @endforeach
                        @if($pillar->questions->count() > 2)
                            <span class="text-[10px] text-gray-400 italic">+ {{ $pillar->questions->count() - 2 }} more</span>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Actions --}}
            <div class="bg-gray-50 p-4 flex justify-end gap-3 border-t border-gray-100">
                <button wire:click="edit({{ $pillar->id }})" class="text-gray-400 hover:text-blue-600 transition"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg></button>
                <button wire:click="delete({{ $pillar->id }})" wire:confirm="Are you sure?" class="text-gray-400 hover:text-red-600 transition"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg></button>
            </div>
        </div>
        @endforeach
    </div>

    {{-- MODAL FORM --}}
    @if($isModalOpen)
    <div class="fixed inset-0 z-50 flex items-center justify-center px-4 bg-gray-900/60 backdrop-blur-sm">
        <div class="bg-white w-full max-w-4xl rounded-2xl shadow-2xl overflow-hidden flex flex-col max-h-[90vh]">
            
            {{-- Modal Header --}}
            <div class="p-6 border-b border-gray-100 bg-white sticky top-0 z-20 flex justify-between items-center">
                <h2 class="font-heading font-black text-xl">{{ $editingPillarId ? 'Edit Pillar' : 'Create Pillar' }}</h2>
                <button wire:click="$set('isModalOpen', false)" class="text-gray-400 hover:text-gray-600"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
            </div>

            {{-- Modal Body (Scrollable) --}}
            <div class="p-6 overflow-y-auto space-y-8 bg-gray-50/50">
                
                {{-- 1. General Info --}}
                <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm space-y-4">
                    <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">General Info</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="col-span-1 md:col-span-2">
                            <label class="block text-xs font-bold text-gray-700 mb-1">Title</label>
                            <input wire:model="title" type="text" class="w-full border-gray-200 rounded-xl text-sm font-bold focus:ring-red-500">
                            @error('title') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-span-1 md:col-span-2">
                            <label class="block text-xs font-bold text-gray-700 mb-1">Description</label>
                            <textarea wire:model="description" rows="2" class="w-full border-gray-200 rounded-xl text-sm focus:ring-red-500"></textarea>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">Cover Image</label>
                            <input wire:model="image" type="file" class="w-full text-xs text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-red-50 file:text-red-700 hover:file:bg-red-100">
                        </div>
                    </div>
                </div>

                {{-- 2. Questions Builder --}}
                <div class="space-y-4">
                    <div class="flex justify-between items-center">
                        <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest">Questions & Options</h3>
                        <button wire:click="addQuestion" class="text-[10px] bg-white border border-gray-200 hover:bg-gray-100 text-gray-700 px-3 py-1.5 rounded-lg shadow-sm font-bold uppercase tracking-wide transition">+ Add Question</button>
                    </div>

                    @foreach($questions as $qIndex => $question)
                    <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm relative group">
                        
                        {{-- Remove Question --}}
                        <button wire:click="removeQuestion({{ $qIndex }})" class="absolute top-4 right-4 text-gray-300 hover:text-red-500 p-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        </button>

                        <div class="mb-4 pr-8">
                            <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Question {{ $qIndex + 1 }}</label>
                            <input wire:model="questions.{{ $qIndex }}.text" type="text" class="w-full border-gray-200 rounded-lg text-sm font-bold focus:ring-red-500 bg-gray-50 focus:bg-white" placeholder="Type question here...">
                            @error("questions.{$qIndex}.text") <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        {{-- Options --}}
                        <div class="pl-4 border-l-2 border-gray-100 space-y-2">
                            <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Options</label>
                            @foreach($question['options'] as $oIndex => $option)
                            <div class="flex gap-2 items-center">
                                <input wire:model="questions.{{ $qIndex }}.options.{{ $oIndex }}.label" type="text" class="grow border-gray-200 rounded-md text-xs py-1.5" placeholder="Option Label">
                                
                                <select wire:model="questions.{{ $qIndex }}.options.{{ $oIndex }}.color" class="border-gray-200 rounded-md text-xs py-1.5 w-24">
                                    <option value="gray">Gray</option>
                                    <option value="green">Green</option>
                                    <option value="red">Red</option>
                                    <option value="yellow">Yellow</option>
                                </select>

                                <button wire:click="removeOption({{ $qIndex }}, {{ $oIndex }})" class="text-gray-300 hover:text-red-500">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                </button>
                            </div>
                            @endforeach
                            <button wire:click="addOption({{ $qIndex }})" class="text-[10px] text-blue-500 hover:underline font-bold mt-2">+ Add Option</button>
                        </div>
                    </div>
                    @endforeach
                </div>

            </div>

            {{-- Footer --}}
            <div class="p-6 border-t border-gray-100 bg-white sticky bottom-0 z-20 flex justify-end gap-3">
                <button wire:click="$set('isModalOpen', false)" class="px-5 py-2.5 text-xs font-bold text-gray-500 hover:bg-gray-100 rounded-xl uppercase tracking-wider">Cancel</button>
                <button wire:click="save" class="px-6 py-2.5 bg-gray-900 text-white text-xs font-bold uppercase tracking-widest rounded-xl hover:bg-red-600 shadow-lg transition">
                    {{ $editingPillarId ? 'Update Pillar' : 'Launch Pillar' }}
                </button>
            </div>
        </div>
    </div>
    @endif
</div>