<div class="min-h-screen bg-gray-100 p-6 font-sans text-gray-900">
    
    <div class="max-w-6xl mx-auto">
        
        {{-- HEADER / ACTIONS --}}
        <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
            <div>
                <h1 class="text-2xl font-black text-gray-900 flex items-center gap-2">
                    @if($evaluation->exists) <span class="text-orange-500">Edit</span> Evaluation @else <span class="text-green-500">Create</span> Evaluation @endif
                </h1>
                <p class="text-sm text-gray-500">Add questions, images, and file uploads below.</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.evaluations.index') }}" class="px-4 py-2 bg-white border border-gray-300 text-gray-600 font-bold rounded-xl text-xs uppercase tracking-wider hover:bg-gray-50 transition">Cancel</a>
                <button wire:click="save" class="px-6 py-2 bg-gray-900 text-white font-bold rounded-xl shadow-lg hover:bg-gray-800 hover:-translate-y-1 transition text-xs uppercase tracking-wider flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                    Save Changes
                </button>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            
            {{-- LEFT: SETTINGS --}}
            <div class="lg:col-span-4 space-y-6">
                <div class="bg-white rounded-[2rem] p-6 shadow-sm border border-gray-200">
                    <h2 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4">Configuration</h2>
                    
                    {{-- HEADER IMAGE UPLOAD --}}
                    <div class="mb-6">
                        <label class="block text-sm font-bold text-gray-700 mb-2">Header Image</label>
                        @if($header_image)
                            <img src="{{ $header_image->temporaryUrl() }}" class="w-full h-32 object-cover rounded-xl mb-2 border-2 border-orange-500">
                        @elseif($existing_header_image)
                            <img src="{{ asset('storage/'.$existing_header_image) }}" class="w-full h-32 object-cover rounded-xl mb-2 border border-gray-100">
                        @endif
                        
                        <label class="cursor-pointer bg-gray-50 hover:bg-gray-100 text-gray-600 px-4 py-3 rounded-xl text-xs font-bold transition flex items-center justify-center gap-2 border border-dashed border-gray-300">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            Upload Header
                            <input type="file" wire:model="header_image" class="hidden" accept="image/*">
                        </label>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-bold text-gray-700 mb-1">Title <span class="text-red-500">*</span></label>
                        <input wire:model.live="title" type="text" class="w-full rounded-xl border-gray-200 bg-gray-50 text-sm font-bold">
                        @error('title') <span class="text-xs text-red-500 font-bold block mt-1">{{ $message }}</span> @enderror
                    </div>

                    {{-- SLUG / ACCESS KEY INPUT --}}
                    <div class="mb-4">
                        <label class="block text-sm font-bold text-gray-700 mb-1">
                            URL Slug / Access Key
                            <span class="text-[10px] font-normal text-gray-400 ml-1">(Optional - Auto-generated if empty)</span>
                        </label>
                        
                        <div class="flex gap-2">
                            <div class="relative flex-1">
                                <input wire:model="slug" type="text" 
                                    class="w-full rounded-xl border-gray-200 bg-gray-50 focus:bg-white focus:border-orange-500 text-sm font-mono text-gray-600 pl-3 pr-10" 
                                    placeholder="e.g. my-custom-form-name">
                                
                                <div class="absolute right-3 top-2.5 text-gray-300">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path></svg>
                                </div>
                            </div>

                            <button wire:click="generateRandomSlug" type="button" class="px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-600 rounded-xl border border-gray-200 transition" title="Generate Random Key">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                            </button>
                        </div>
                        
                        @error('slug') <span class="text-xs text-red-500 font-bold block mt-1">{{ $message }}</span> @enderror
                        
                        <p class="text-[10px] text-gray-400 mt-1">
                            Result: <span class="font-mono">{{ url('/eval/') }}/<span class="text-orange-500">{{ $slug ?: '...' }}</span></span>
                        </p>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-bold text-gray-700 mb-1">Description</label>
                        <textarea wire:model="description" rows="3" class="w-full rounded-xl border-gray-200 bg-gray-50 text-sm resize-none"></textarea>
                    </div>

                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl border border-gray-100">
                        <span class="text-xs font-bold text-gray-600 uppercase tracking-wide">Publish Form?</span>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" wire:model="is_active" class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:bg-green-500 peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all"></div>
                        </label>
                    </div>
                </div>
            </div>

            {{-- RIGHT: QUESTION BUILDER --}}
            <div class="lg:col-span-8">
                
                {{-- Toolbar --}}
                <div class="bg-gray-900 text-white p-4 rounded-t-[2rem] flex flex-wrap gap-2 items-center shadow-md">
                    <span class="font-bold ml-2 mr-auto text-sm">Add Content:</span>
                    
                    <button wire:click="addQuestion('text')" class="px-3 py-1.5 bg-white/10 hover:bg-white/20 rounded-lg text-xs font-bold transition flex items-center gap-1">Text</button>
                    <button wire:click="addQuestion('radio')" class="px-3 py-1.5 bg-white/10 hover:bg-white/20 rounded-lg text-xs font-bold transition flex items-center gap-1">Choice</button>
                    <button wire:click="addQuestion('likert')" class="px-3 py-1.5 bg-white/10 hover:bg-white/20 rounded-lg text-xs font-bold transition flex items-center gap-1">Scale</button>
                    
                    <button wire:click="addQuestion('file')" class="px-3 py-1.5 bg-white/10 hover:bg-white/20 rounded-lg text-xs font-bold transition flex items-center gap-1 border border-white/30">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                        Upload Bin
                    </button>
                    
                    <button wire:click="addQuestion('section')" class="px-3 py-1.5 bg-white/10 hover:bg-white/20 rounded-lg text-xs font-bold transition flex items-center gap-1 border-l border-white/20 ml-2">Separator</button>
                </div>

                {{-- Questions List --}}
                <div class="bg-white rounded-b-[2rem] p-6 shadow-sm border border-t-0 border-gray-200 min-h-[400px] space-y-4" wire:sortable="updateQuestionOrder">
                    
                    @foreach($questions as $index => $question)
                        @if($question['type'] === 'section')
                            {{-- SECTION HEADER --}}
                            <div wire:sortable.item="{{ $index }}" wire:key="question-{{ $index }}" class="group relative bg-orange-50 border-2 border-dashed border-orange-200 rounded-xl p-4 my-6">
                                <div class="absolute left-0 top-0 bottom-0 w-8 flex items-center justify-center cursor-move text-orange-300 rounded-l-xl" wire:sortable.handle>
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"></path></svg>
                                </div>
                                <div class="pl-8">
                                    <div class="flex items-center gap-4 mb-2">
                                        <input type="text" wire:model="questions.{{ $index }}.question_text" class="w-full text-xl font-black text-gray-800 border-0 bg-transparent placeholder-orange-300 focus:ring-0 p-0" placeholder="Type Section Title">
                                        <button wire:click="removeQuestion({{ $index }})" class="text-orange-300 hover:text-red-500">&times;</button>
                                    </div>
                                    <input type="text" wire:model="questions.{{ $index }}.description" class="w-full text-sm text-gray-600 border-0 bg-transparent placeholder-orange-300/50 focus:ring-0 p-0" placeholder="Add a description for this section (optional)...">
                                </div>
                            </div>
                        @else
                            {{-- STANDARD QUESTION CARD --}}
                            <div wire:sortable.item="{{ $index }}" wire:key="question-{{ $index }}" class="group relative bg-white border border-gray-200 rounded-2xl p-6 hover:shadow-lg transition-all duration-300">
                                
                                <div class="absolute left-0 top-0 bottom-0 w-8 flex items-center justify-center cursor-move text-gray-300 hover:text-gray-500 hover:bg-gray-50 rounded-l-2xl" wire:sortable.handle>
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"></path></svg>
                                </div>

                                <div class="pl-6">
                                    <div class="flex justify-between items-center mb-3">
                                        <span class="text-[10px] font-bold uppercase tracking-wide text-gray-400 bg-gray-100 px-2 py-1 rounded">
                                            {{ ucfirst($question['type']) }} Question
                                        </span>
                                        <div class="flex items-center gap-2">
                                            <label class="flex items-center gap-2 cursor-pointer">
                                                <span class="text-[10px] font-bold uppercase text-gray-400">Required</span>
                                                <input type="checkbox" wire:model="questions.{{ $index }}.is_required" class="rounded text-orange-500 w-4 h-4">
                                            </label>
                                            <button wire:click="removeQuestion({{ $index }})" class="text-gray-300 hover:text-red-500 p-1 transition"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg></button>
                                        </div>
                                    </div>

                                    <input type="text" wire:model="questions.{{ $index }}.question_text" class="w-full text-lg font-bold border-0 border-b-2 border-gray-100 focus:border-orange-500 focus:ring-0 bg-transparent transition mb-4" placeholder="Enter question...">
                                    <input type="text" wire:model="questions.{{ $index }}.description" class="w-full text-xs text-gray-500 border-0 border-b border-gray-50 focus:border-orange-300 focus:ring-0 bg-transparent transition mb-4 placeholder-gray-300" placeholder="Description (optional)">

                                    {{-- QUESTION IMAGE UPLOADER --}}
                                    <div class="mb-4 bg-gray-50 p-3 rounded-lg border border-dashed border-gray-200">
                                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Question Attachment (Optional)</p>
                                        
                                        @if(isset($questions[$index]['new_image']) && $questions[$index]['new_image'])
                                            <img src="{{ $questions[$index]['new_image']->temporaryUrl() }}" class="h-24 w-auto rounded-lg mb-2 border border-orange-200">
                                        @elseif(isset($question['image_path']) && $question['image_path'])
                                             <img src="{{ asset('storage/'.$question['image_path']) }}" class="h-24 w-auto rounded-lg mb-2 border border-gray-200">
                                        @endif

                                        <label class="inline-flex items-center gap-2 text-xs font-bold text-gray-600 hover:text-orange-600 cursor-pointer">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                            {{ (isset($question['image_path']) && $question['image_path']) ? 'Change Image' : 'Add Image' }}
                                            <input type="file" wire:model="questions.{{ $index }}.new_image" class="hidden" accept="image/*">
                                        </label>
                                    </div>

                                    {{-- 4. LIKERT SCALE --}}
                                    @elseif($question->type === 'likert')
                                        <div class="bg-gray-50 rounded-xl p-4 border border-gray-100">
                                            <div class="flex justify-between text-[10px] font-bold text-gray-400 uppercase tracking-widest px-1 mb-2">
                                                {{-- Use array access for first item --}}
                                                <span>{{ $question->options[0] ?? 'Disagree' }}</span>
                                                
                                                {{-- Use 'last()' helper instead of 'end()' --}}
                                                <span>{{ last($question->options) ?? 'Agree' }}</span>
                                            </div>
                                            <div class="flex justify-between gap-1">
                                                @foreach($question->options as $idx => $label)
                                                    <label class="cursor-pointer group/likert text-center relative flex-1">
                                                        <input type="radio" wire:model.live="answers.{{ $question->id }}" value="{{ $label }}" class="peer sr-only">
                                                        
                                                        {{-- Tile --}}
                                                        <div class="w-full aspect-square rounded-lg bg-white shadow-sm border-2 border-transparent flex flex-col items-center justify-center gap-1 group-hover/likert:border-orange-200 peer-checked:border-orange-500 peer-checked:bg-orange-500 peer-checked:text-white transition-all duration-200">
                                                            <span class="text-sm font-black text-gray-300 group-hover/likert:text-orange-300 peer-checked:text-white/90">{{ $idx + 1 }}</span>
                                                        </div>
                                                        
                                                        {{-- Label --}}
                                                        <span class="hidden md:block text-[9px] text-gray-400 mt-1 peer-checked:text-orange-600 truncate px-1">{{ $label }}</span>
                                                    </label>
                                                @endforeach
                                            </div>
                                        </div>

                                    {{-- RADIO EDITOR --}}
                                    @elseif(in_array($question['type'], ['radio', 'checkbox']))
                                        <div class="pl-4 border-l-2 border-gray-100 space-y-2 mt-2">
                                            @foreach($question['options'] as $optIndex => $opt)
                                                <div class="flex items-center gap-2">
                                                    <div class="w-3 h-3 rounded-full border border-gray-300"></div>
                                                    <input type="text" wire:model="questions.{{ $index }}.options.{{ $optIndex }}" class="w-full text-sm border-gray-200 rounded-lg">
                                                    <button wire:click="removeOption({{ $index }}, {{ $optIndex }})" class="text-gray-300 hover:text-red-500">&times;</button>
                                                </div>
                                            @endforeach
                                            <button wire:click="addOption({{ $index }})" class="text-xs font-bold text-blue-600 hover:underline mt-2">+ Add Option</button>
                                        </div>
                                    @elseif($question['type'] === 'file')
                                        <div class="bg-blue-50 border border-blue-100 p-3 rounded-lg text-xs text-blue-600 font-bold flex items-center gap-2">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                                            File Upload Bin
                                        </div>
                                    @endif

                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>