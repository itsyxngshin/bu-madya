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
                    <div class="mt-4 space-y-4">
                        @foreach($pillar->questions as $q)
                        <div class="bg-gray-50 p-3 rounded-xl border border-gray-100">
                            <p class="text-xs font-bold text-gray-700 mb-2 truncate">{{ $q->question_text }}</p>
                            
                            {{-- OPTIONS LIST (Clickable for Voters) --}}
                            <div class="space-y-1">
                                @foreach($q->options as $opt)
                                <div class="flex justify-between items-center text-[10px]">
                                    <span class="flex items-center gap-1 text-gray-500">
                                        <span class="w-2 h-2 rounded-full {{ match($opt->color) { 'green'=>'bg-green-500', 'red'=>'bg-red-500', default=>'bg-gray-400' } }}"></span>
                                        {{ $opt->label }}
                                    </span>
                                    
                                    {{-- CLICKABLE VOTE COUNT --}}
                                    <button wire:click="viewVoters({{ $opt->id }})" 
                                            class="font-bold px-2 py-0.5 rounded bg-white border border-gray-200 hover:border-blue-500 hover:text-blue-600 transition cursor-pointer"
                                            title="View Voters">
                                        {{ $opt->votes->count() }} Votes
                                    </button>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Actions --}}
            <div class="bg-gray-50 p-4 flex justify-end gap-3 border-t border-gray-100">
                {{-- RESET VOTES BUTTON --}}
                <button wire:click="resetVotes({{ $pillar->id }})" 
                        wire:confirm="WARNING: This will delete ALL votes for this pillar. This action cannot be undone. Are you sure?" 
                        class="text-gray-400 hover:text-yellow-600 transition" 
                        title="Reset Votes">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                </button>
                <button wire:click="edit({{ $pillar->id }})" class="text-gray-400 hover:text-blue-600 transition"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg></button>
                <button wire:click="delete({{ $pillar->id }})" wire:confirm="Are you sure?" class="text-gray-400 hover:text-red-600 transition"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg></button>
            </div>
        </div>
        @endforeach
    </div>

    {{-- MODAL FORM --}}
    @if($isModalOpen)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900/60 backdrop-blur-sm md:p-6"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0">

        {{-- MODAL CONTAINER --}}
        {{-- 
            MOBILE: w-full h-full rounded-none (Fills screen completely)
            DESKTOP: md:rounded-2xl md:max-w-4xl md:h-auto md:max-h-[90vh] (Floating Card)
        --}}
        <div class="bg-white w-full h-full md:w-full md:max-w-4xl md:h-auto md:max-h-[90vh] md:rounded-2xl shadow-2xl flex flex-col overflow-hidden transition-all transform border border-gray-100">
            
            {{-- 1. STICKY HEADER --}}
            <div class="p-4 md:p-6 border-b border-gray-100 bg-white sticky top-0 z-20 flex justify-between items-center shrink-0">
                <div>
                    <h2 class="font-heading font-black text-lg md:text-xl text-gray-900">
                        {{ $editingPillarId ? 'Edit Pillar' : 'Create Pillar' }}
                    </h2>
                    <p class="text-[10px] text-gray-500 hidden md:block">Configure your resolution and questions.</p>
                </div>
                <button wire:click="$set('isModalOpen', false)" class="p-2 -mr-2 text-gray-400 hover:text-gray-600 rounded-full hover:bg-gray-100 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            {{-- 2. SCROLLABLE BODY --}}
            <div class="flex-1 overflow-y-auto p-4 md:p-6 space-y-6 md:space-y-8 bg-gray-50/50">
                
                {{-- A. General Info Section --}}
                <div class="bg-white p-4 md:p-6 rounded-xl border border-gray-100 shadow-sm space-y-4">
                    <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-2 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        General Info
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="col-span-1 md:col-span-2">
                            <label class="block text-xs font-bold text-gray-700 mb-1">Title</label>
                            <input wire:model="title" type="text" class="w-full border-gray-200 rounded-xl text-sm font-bold focus:ring-red-500 py-2.5" placeholder="e.g., Campus Safety Reform">
                            @error('title') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        
                        <div class="col-span-1 md:col-span-2">
                            <label class="block text-xs font-bold text-gray-700 mb-1">Description</label>
                            <textarea wire:model="description" rows="3" class="w-full border-gray-200 rounded-xl text-sm focus:ring-red-500 py-2.5" placeholder="What is this resolution about?"></textarea>
                        </div>

                        <div class="col-span-1 md:col-span-2">
                            <label class="block text-xs font-bold text-gray-700 mb-1">Cover Image</label>
                            <div class="flex items-center gap-3">
                                <label class="cursor-pointer bg-white border border-gray-200 hover:border-red-500 text-gray-600 px-4 py-2 rounded-lg text-xs font-bold shadow-sm transition">
                                    <span class="flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        Upload Photo
                                    </span>
                                    <input wire:model="image" type="file" class="hidden">
                                </label>
                                @if($image)
                                    <span class="text-[10px] text-green-600 font-bold bg-green-50 px-2 py-1 rounded">Image Selected</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                {{-- B. Questions Builder --}}
                <div class="space-y-4">
                    <div class="flex justify-between items-center sticky top-0 z-10 py-2 bg-gray-50/95 backdrop-blur">
                        <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest">Questions</h3>
                        <button wire:click="addQuestion" class="text-[10px] bg-white border border-gray-200 hover:border-red-500 text-gray-700 px-3 py-1.5 rounded-lg shadow-sm font-bold uppercase tracking-wide transition flex items-center gap-1">
                            <span class="text-lg leading-none">+</span> Add
                        </button>
                    </div>

                    @foreach($questions as $qIndex => $question)
                    <div class="bg-white p-4 md:p-6 rounded-xl border border-gray-200 shadow-sm relative group animate-fade-in-up">
                        
                        {{-- Remove Question Button --}}
                        <button wire:click="removeQuestion({{ $qIndex }})" class="absolute top-2 right-2 p-2 text-gray-300 hover:text-red-500 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        </button>

                        <div class="mb-4 pr-8">
                            <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Question {{ $qIndex + 1 }}</label>
                            <input wire:model="questions.{{ $qIndex }}.text" type="text" class="w-full border-gray-200 rounded-lg text-sm font-bold focus:ring-red-500 bg-gray-50 focus:bg-white transition" placeholder="e.g. Do you support this?">
                            @error("questions.{$qIndex}.text") <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        {{-- Options List --}}
                        <div class="pl-3 border-l-2 border-gray-100 space-y-3">
                            <label class="block text-[10px] font-bold text-gray-400 uppercase">Options</label>
                            @foreach($question['options'] as $oIndex => $option)
                            <div class="flex gap-2 items-center">
                                {{-- Option Label --}}
                                <input wire:model="questions.{{ $qIndex }}.options.{{ $oIndex }}.label" type="text" class="grow min-w-0 border-gray-200 rounded-lg text-xs py-2" placeholder="Label">
                                
                                {{-- Color Select (Compact) --}}
                                <div class="relative w-10 shrink-0">
                                    <select wire:model="questions.{{ $qIndex }}.options.{{ $oIndex }}.color" 
                                            class="appearance-none w-full border-gray-200 rounded-lg text-xs py-2 px-0 text-center cursor-pointer focus:ring-gray-400 font-bold
                                            {{ match($questions[$qIndex]['options'][$oIndex]['color'] ?? 'gray') { 
                                                'green' => 'bg-green-100 text-green-700',
                                                'red' => 'bg-red-100 text-red-700',
                                                'yellow' => 'bg-yellow-100 text-yellow-700',
                                                'blue' => 'bg-blue-100 text-blue-700',
                                                'purple' => 'bg-purple-100 text-purple-700',
                                                'orange' => 'bg-orange-100 text-orange-700',
                                                'teal' => 'bg-teal-100 text-teal-700',
                                                'pink' => 'bg-pink-100 text-pink-700',
                                                default => 'bg-gray-100 text-gray-500' 
                                            } }}">
                                        <option value="gray">⚪</option>
                                        <option value="green">🟢</option>
                                        <option value="red">🔴</option>
                                        <option value="yellow">🟡</option>
                                        <option value="blue">🔵</option>
                                        <option value="purple">🟣</option>
                                        <option value="orange">🟠</option>
                                        <option value="teal">💠</option>
                                        <option value="pink">🌸</option>
                                    </select>
                                </div>

                                <button wire:click="removeOption({{ $qIndex }}, {{ $oIndex }})" class="shrink-0 p-1 text-gray-300 hover:text-red-500">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                </button>
                            </div>
                            @endforeach
                            <button wire:click="addOption({{ $qIndex }})" class="text-[10px] text-blue-600 hover:text-blue-800 font-bold mt-1 inline-flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                Add Option
                            </button>
                        </div>
                    </div>
                    @endforeach
                </div>
                
                {{-- Spacer for scrolling past sticky footer --}}
                <div class="h-12"></div>
            </div>

            {{-- 3. STICKY FOOTER --}}
            <div class="p-4 md:p-6 border-t border-gray-100 bg-white sticky bottom-0 z-20 flex justify-end gap-3 shrink-0 shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.05)]">
                <button wire:click="$set('isModalOpen', false)" class="px-5 py-3 md:py-2.5 text-xs font-bold text-gray-500 hover:bg-gray-100 rounded-xl uppercase tracking-wider transition">
                    Cancel
                </button>
                <button wire:click="save" class="px-6 py-3 md:py-2.5 bg-gray-900 text-white text-xs font-bold uppercase tracking-widest rounded-xl hover:bg-red-600 shadow-lg active:scale-95 transition-all w-full md:w-auto">
                    {{ $editingPillarId ? 'Update' : 'Launch' }}
                </button>
            </div>

        </div>
    </div>
    @endif

    @if($isVotersModalOpen)
    <div class="fixed inset-0 z-[60] flex items-center justify-center px-4 bg-gray-900/60 backdrop-blur-sm">
        <div class="bg-white w-full max-w-sm rounded-2xl shadow-2xl overflow-hidden flex flex-col max-h-[80vh] animate-scale-in">
            
            <div class="p-4 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
                <div>
                    <p class="text-[10px] font-bold text-gray-400 uppercase">Voters for</p>
                    <h3 class="font-bold text-gray-900 text-lg">{{ $selectedOptionLabel }}</h3>
                </div>
                <button wire:click="$set('isVotersModalOpen', false)" class="text-gray-400 hover:text-red-500">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <div class="overflow-y-auto p-4 space-y-3">
                @forelse($selectedOptionVoters as $voter)
                <div class="flex items-center gap-3 p-2 hover:bg-gray-50 rounded-lg transition border border-transparent hover:border-gray-100">
                    <img src="{{ $voter['avatar'] }}" class="w-8 h-8 rounded-full bg-gray-200 object-cover border border-gray-100 shrink-0">
                    <div>
                        <p class="text-sm font-bold text-gray-900 leading-tight">{{ $voter['name'] }}</p>
                        <p class="text-[10px] text-gray-400">{{ $voter['date'] }}</p>
                    </div>
                </div>
                @empty
                <div class="text-center py-8">
                    <p class="text-gray-400 text-xs italic">No registered users have voted for this option yet.</p>
                </div>
                @endforelse
            </div>

            <div class="p-4 bg-gray-50 border-t border-gray-100 text-center">
                <button wire:click="$set('isVotersModalOpen', false)" class="text-xs font-bold text-gray-500 hover:text-gray-900 uppercase">Close</button>
            </div>
        </div>
    </div>
    @endif
</div>