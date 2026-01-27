<div class="min-h-screen bg-gray-100 p-6 font-sans text-gray-900">
    
    <div class="max-w-6xl mx-auto">
        
        {{-- HEADER / ACTIONS BAR --}}
        <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
            <div>
                <h1 class="text-2xl font-black text-gray-900 flex items-center gap-2">
                    @if($evaluation->exists)
                        <span class="text-orange-500">Edit</span> Evaluation
                    @else
                        <span class="text-green-500">Create</span> Evaluation
                    @endif
                </h1>
                <p class="text-sm text-gray-500">Design your feedback form below.</p>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('admin.evaluations.index') }}" class="px-4 py-2 bg-white border border-gray-300 text-gray-600 font-bold rounded-xl text-xs uppercase tracking-wider hover:bg-gray-50 transition">
                    Cancel
                </a>
                <button wire:click="save" class="px-6 py-2 bg-gray-900 text-white font-bold rounded-xl shadow-lg hover:bg-gray-800 hover:-translate-y-1 transition text-xs uppercase tracking-wider flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                    Save Changes
                </button>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            
            {{-- LEFT: SETTINGS PANEL --}}
            <div class="lg:col-span-4 space-y-6">
                
                {{-- General Settings Card --}}
                <div class="bg-white rounded-[2rem] p-6 shadow-sm border border-gray-200">
                    <h2 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4">Configuration</h2>
                    
                    {{-- Title --}}
                    <div class="mb-4">
                        <label class="block text-sm font-bold text-gray-700 mb-1">Form Title <span class="text-red-500">*</span></label>
                        <input wire:model="evaluation.title" type="text" class="w-full rounded-xl border-gray-200 bg-gray-50 focus:bg-white focus:border-orange-500 focus:ring-orange-500 text-sm font-bold transition" placeholder="e.g., Project Post-Mortem">
                        @error('evaluation.title') <span class="text-xs text-red-500 font-bold">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-bold text-gray-700 mb-1">Link to Project (Optional)</label>
                        <select wire:model="evaluation.project_id" class="w-full rounded-xl border-gray-200 bg-gray-50 focus:border-orange-500 text-sm font-bold">
                            <option value="">-- General / No Specific Project --</option>
                            @foreach(\App\Models\Project::all() as $project)
                                <option value="{{ $project->id }}">{{ $project->title }}</option>
                            @endforeach
                        </select>
                        <p class="text-[10px] text-gray-400 mt-1">If selected, this form will appear on the project's page.</p>
                    </div>

                    {{-- Description --}}
                    <div class="mb-4">
                        <label class="block text-sm font-bold text-gray-700 mb-1">Description</label>
                        <textarea wire:model="evaluation.description" rows="4" class="w-full rounded-xl border-gray-200 bg-gray-50 focus:bg-white focus:border-orange-500 focus:ring-orange-500 text-sm transition resize-none" placeholder="Instructions for the user..."></textarea>
                    </div>

                    {{-- Status Toggle --}}
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl border border-gray-100">
                        <span class="text-xs font-bold text-gray-600 uppercase tracking-wide">Publish Form?</span>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" wire:model="evaluation.is_active" class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-green-100 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-500"></div>
                        </label>
                    </div>
                </div>

                {{-- Danger Zone (Only in Edit Mode) --}}
                @if($evaluation->exists)
                <div class="bg-red-50 rounded-[2rem] p-6 border border-red-100">
                    <h2 class="text-xs font-bold text-red-400 uppercase tracking-widest mb-2">Danger Zone</h2>
                    <p class="text-xs text-red-800 mb-4">Deleting this form will remove all associated responses.</p>
                    <button onclick="confirm('Are you sure?') || event.stopImmediatePropagation()" wire:click="delete" class="w-full py-2 bg-white border border-red-200 text-red-600 font-bold rounded-xl hover:bg-red-600 hover:text-white transition text-xs uppercase tracking-wide">
                        Delete Form
                    </button>
                </div>
                @endif

            </div>

            {{-- RIGHT: QUESTION BUILDER --}}
            <div class="lg:col-span-8">
                
                {{-- Questions Toolbar --}}
                <div class="bg-gray-900 text-white p-4 rounded-t-[2rem] flex justify-between items-center shadow-md">
                    <span class="font-bold ml-2">Form Questions</span>
                    <div class="flex gap-2">
                        <button wire:click="addQuestion('text')" class="px-3 py-1.5 bg-white/10 hover:bg-white/20 rounded-lg text-xs font-bold transition flex items-center gap-1">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg> Text
                        </button>
                        <button wire:click="addQuestion('radio')" class="px-3 py-1.5 bg-white/10 hover:bg-white/20 rounded-lg text-xs font-bold transition flex items-center gap-1">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg> Choice
                        </button>
                        <button wire:click="addQuestion('likert')" class="px-3 py-1.5 bg-white/10 hover:bg-white/20 rounded-lg text-xs font-bold transition flex items-center gap-1">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg> Scale
                        </button>
                    </div>
                </div>

                {{-- Questions List Area --}}
                <div class="bg-white rounded-b-[2rem] p-6 shadow-sm border border-t-0 border-gray-200 min-h-[400px] space-y-4" wire:sortable="updateQuestionOrder">
                    
                    @if(count($questions) === 0)
                        <div class="flex flex-col items-center justify-center h-64 text-gray-400 border-2 border-dashed border-gray-100 rounded-xl">
                            <svg class="w-12 h-12 mb-2 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                            <p class="text-sm font-bold">Start by adding a question above.</p>
                        </div>
                    @endif

                    @foreach($questions as $index => $question)
                        <div wire:sortable.item="{{ $index }}" wire:key="question-{{ $index }}" class="group relative bg-white border border-gray-200 rounded-2xl p-6 hover:shadow-lg transition-all duration-300 hover:border-orange-200">
                            
                            {{-- Drag Handle (Visible on Hover) --}}
                            <div class="absolute left-0 top-0 bottom-0 w-6 flex items-center justify-center cursor-move text-gray-300 hover:text-gray-500 hover:bg-gray-50 rounded-l-2xl" wire:sortable.handle title="Drag to reorder">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"></path></svg>
                            </div>

                            <div class="pl-4">
                                {{-- Question Header --}}
                                <div class="flex justify-between items-center mb-3">
                                    <span class="text-[10px] font-bold uppercase tracking-wide text-gray-400 bg-gray-100 px-2 py-1 rounded">
                                        Question {{ $index + 1 }} • {{ ucfirst($question['type']) }}
                                    </span>
                                    
                                    <div class="flex items-center gap-2">
                                        {{-- Required Toggle --}}
                                        <label class="flex items-center gap-2 cursor-pointer">
                                            <span class="text-[10px] font-bold uppercase text-gray-400">Required</span>
                                            <input type="checkbox" wire:model="questions.{{ $index }}.is_required" class="rounded text-orange-500 focus:ring-orange-500 border-gray-300 w-4 h-4">
                                        </label>
                                        {{-- Delete --}}
                                        <button wire:click="removeQuestion({{ $index }})" class="text-gray-300 hover:text-red-500 p-1 transition">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </div>
                                </div>

                                {{-- Question Text --}}
                                <input type="text" wire:model="questions.{{ $index }}.question_text" 
                                       class="w-full text-lg font-bold border-0 border-b-2 border-gray-100 focus:border-orange-500 focus:ring-0 placeholder-gray-300 bg-transparent transition mb-4" 
                                       placeholder="Enter your question here...">

                                {{-- Options Editor (For Radio/Checkbox) --}}
                                @if(in_array($question['type'], ['radio', 'checkbox']))
                                    <div class="space-y-2 pl-4 border-l-2 border-gray-100">
                                        @foreach($question['options'] as $optIndex => $option)
                                            <div class="flex items-center gap-2">
                                                <div class="w-3 h-3 rounded-full border border-gray-300 shrink-0"></div>
                                                <input type="text" wire:model="questions.{{ $index }}.options.{{ $optIndex }}" 
                                                       class="w-full text-sm border-gray-200 rounded-lg focus:border-orange-500 focus:ring-orange-500" 
                                                       placeholder="Option {{ $optIndex + 1 }}">
                                                <button wire:click="removeOption({{ $index }}, {{ $optIndex }})" class="text-gray-300 hover:text-red-500">&times;</button>
                                            </div>
                                        @endforeach
                                        <button wire:click="addOption({{ $index }})" class="text-xs font-bold text-blue-600 hover:underline mt-2 flex items-center gap-1">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg> Add Option
                                        </button>
                                    </div>
                                @endif

                                {{-- Likert Scale Editor --}}
                                @if($question['type'] === 'likert')
                                    <div class="bg-gray-50 p-4 rounded-xl border border-gray-100">
                                        <p class="text-[10px] text-gray-400 font-bold uppercase mb-2 text-center">Scale Labels (1 to 5)</p>
                                        <div class="grid grid-cols-5 gap-2">
                                            @foreach($question['options'] as $optIndex => $option)
                                                <input type="text" wire:model="questions.{{ $index }}.options.{{ $optIndex }}" 
                                                       class="text-xs text-center border-gray-200 rounded-lg focus:border-green-500 focus:ring-green-500" 
                                                       placeholder="Label">
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>

            </div>
        </div>
    </div>
</div>