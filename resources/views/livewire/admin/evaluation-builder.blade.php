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

                {{-- RESET RESPONSES BUTTON --}}
                @if($evaluation->exists && $evaluation->responses()->count() > 0)
                    <button 
                        type="button"
                        onclick="confirmReset({{ $evaluation->responses()->count() }})"
                        class="px-4 py-2 bg-red-50 border border-red-200 text-red-600 font-bold rounded-xl text-xs uppercase tracking-wider hover:bg-red-100 transition flex items-center gap-2"
                        title="Clear all responses to unlock editing">
                        
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        Reset Data ({{ $evaluation->responses()->count() }})
                    </button>
                @endif

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
                    
                    <div class="mb-4">
                        <label class="block text-sm font-bold text-gray-700 mb-1">
                            Link to Project
                            <span class="text-[10px] font-normal text-gray-400 ml-1">(Optional)</span>
                        </label>
                        <div class="relative">
                            <select wire:model="project_id" class="w-full rounded-xl border-gray-200 bg-gray-50 text-sm appearance-none cursor-pointer hover:border-gray-300 transition">
                                <option value="">-- General Evaluation (No Project) --</option>
                                @foreach($available_projects as $proj)
                                    <option value="{{ $proj->id }}">{{ $proj->title }}</option>
                                @endforeach
                            </select>
                            <div class="absolute right-3 top-3 pointer-events-none text-gray-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </div>
                        </div>
                        <p class="text-[10px] text-gray-400 mt-1">If selected, this form will appear on that Project's page.</p>
                    </div>

                    {{-- SLUG INPUT --}}
                    <div class="mb-4">
                        <label class="block text-sm font-bold text-gray-700 mb-1">
                            URL Slug
                            <span class="text-[10px] font-normal text-gray-400 ml-1">(Auto-generated if empty)</span>
                        </label>
                        <div class="flex gap-2">
                            <input wire:model="slug" type="text" class="w-full rounded-xl border-gray-200 bg-gray-50 text-sm font-mono pl-3 pr-10">
                            <button wire:click="generateRandomSlug" type="button" class="px-3 py-2 bg-gray-100 hover:bg-gray-200 rounded-xl border border-gray-200">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                            </button>
                        </div>
                        @error('slug') <span class="text-xs text-red-500 font-bold block mt-1">{{ $message }}</span> @enderror
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
                    <button wire:click="addQuestion('text')" class="px-3 py-1.5 bg-white/10 hover:bg-white/20 rounded-lg text-xs font-bold transition">Text</button>
                    <button wire:click="addQuestion('radio')" class="px-3 py-1.5 bg-white/10 hover:bg-white/20 rounded-lg text-xs font-bold transition">Choice</button>
                    <button wire:click="addQuestion('likert')" class="px-3 py-1.5 bg-white/10 hover:bg-white/20 rounded-lg text-xs font-bold transition">Scale</button>
                    <button wire:click="addQuestion('file')" class="px-3 py-1.5 bg-white/10 hover:bg-white/20 rounded-lg text-xs font-bold transition border border-white/30">Upload Bin</button>
                    <button wire:click="addQuestion('section')" class="px-3 py-1.5 bg-white/10 hover:bg-white/20 rounded-lg text-xs font-bold transition border-l border-white/20 ml-2">Separator</button>
                </div>

                {{-- Questions List --}}
                <div class="bg-white rounded-b-[2rem] p-6 shadow-sm border border-t-0 border-gray-200 min-h-[400px] space-y-4" wire:sortable="updateQuestionOrder">
                    
                    @foreach($questions as $index => $question)
                        
                        {{-- [FIX] Use 'temp_id' for both item value and key --}}
                        @if($question['type'] === 'section')
                            <div wire:sortable.item="{{ $question['temp_id'] }}" wire:key="q-{{ $question['temp_id'] }}" class="group relative bg-orange-50 border-2 border-dashed border-orange-200 rounded-xl p-4 my-6">
                                <div class="absolute left-0 top-0 bottom-0 w-10 flex flex-col items-center justify-center gap-1 bg-gray-50 border-r border-gray-100 rounded-l-2xl">
                                    {{-- Move Up Button --}}
                                    @if($index > 0)
                                        <button wire:click="moveQuestionUp({{ $index }})" class="p-1 text-gray-400 hover:text-orange-500 hover:bg-orange-50 rounded transition" title="Move Up">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path></svg>
                                        </button>
                                    @endif

                                    {{-- Move Down Button --}}
                                    @if($index < count($this->questions) - 1)
                                        <button wire:click="moveQuestionDown({{ $index }})" class="p-1 text-gray-400 hover:text-orange-500 hover:bg-orange-50 rounded transition" title="Move Down">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                        </button>
                                    @endif
                                    
                                </div>
                                <div class="pl-8">
                                    <div class="flex items-center gap-4 mb-2">
                                        <input type="text" wire:model="questions.{{ $index }}.question_text" class="w-full text-xl font-black text-gray-800 border-0 bg-transparent placeholder-orange-300 focus:ring-0 p-0" placeholder="Type Section Title">
                                        <button wire:click="removeQuestion({{ $index }})" class="text-orange-300 hover:text-red-500">&times;</button>
                                    </div>
                                    <input type="text" wire:model="questions.{{ $index }}.description" class="w-full text-sm text-gray-600 border-0 bg-transparent placeholder-orange-300/50 focus:ring-0 p-0" placeholder="Add description (optional)...">
                                </div>
                            </div>
                        @else
                            <div wire:sortable.item="{{ $question['temp_id'] }}" wire:key="q-{{ $question['temp_id'] }}" class="group relative bg-white border border-gray-200 rounded-2xl p-6 hover:shadow-lg transition-all duration-300">
                                
                                <div class="absolute left-0 top-0 bottom-0 w-10 flex flex-col items-center justify-center gap-1 bg-gray-50 border-r border-gray-100 rounded-l-2xl">
                                    {{-- Move Up Button --}}
                                    @if($index > 0)
                                        <button wire:click="moveQuestionUp({{ $index }})" class="p-1 text-gray-400 hover:text-orange-500 hover:bg-orange-50 rounded transition" title="Move Up">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path></svg>
                                        </button>
                                    @endif

                                    {{-- Move Down Button --}}
                                    @if($index < count($this->questions) - 1)
                                        <button wire:click="moveQuestionDown({{ $index }})" class="p-1 text-gray-400 hover:text-orange-500 hover:bg-orange-50 rounded transition" title="Move Down">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                        </button>
                                    @endif
                                    
                                </div>

                                <div class="pl-6">
                                    <div class="flex justify-between items-center mb-3">
                                        <span class="text-[10px] font-bold uppercase tracking-wide text-gray-400 bg-gray-100 px-2 py-1 rounded">
                                            {{ ucfirst($question['type']) }}
                                        </span>
                                        <div class="flex items-center gap-2">
                                            <label class="flex items-center gap-2 cursor-pointer">
                                                <span class="text-[10px] font-bold uppercase text-gray-400">Required</span>
                                                <input type="checkbox" wire:model="questions.{{ $index }}.is_required" class="rounded text-orange-500 w-4 h-4">
                                            </label>
                                            <button wire:click="removeQuestion({{ $index }})" class="text-gray-300 hover:text-red-500 p-1 transition">&times;</button>
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

                                    {{-- OPTIONS LOGIC --}}
                                    @if($question['type'] === 'likert')
                                        <div class="bg-gray-50 p-4 rounded-xl border border-gray-100 mt-2">
                                            <p class="text-[10px] text-gray-400 font-bold uppercase mb-2 text-center">Scale Labels (Left to Right)</p>
                                            <div class="grid grid-cols-2 md:grid-cols-5 gap-2">
                                                @foreach($question['options'] as $optIndex => $option)
                                                    <input type="text" wire:model="questions.{{ $index }}.options.{{ $optIndex }}" class="text-xs text-center border-gray-200 rounded-lg focus:border-orange-500" placeholder="Label">
                                                @endforeach
                                            </div>
                                        </div>
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
                                            User will see a file upload bin here.
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

@push('modals') {{-- Assuming you have a @stack('scripts') in your layout --}}
<script>
    // 1. Trigger the Confirmation Modal
    function confirmReset(count) {
        Swal.fire({
            title: 'Are you absolutely sure?',
            text: `You are about to delete ${count} user responses. This action cannot be undone!`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete everything!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                // Call the Livewire method "resetResponses" via the event we registered
                Livewire.dispatch('confirmed-reset');
            }
        })
    }

    // 2. Listen for Success/Info messages from the Backend
    document.addEventListener('livewire:initialized', () => {
        Livewire.on('swal:modal', (data) => {
            Swal.fire({
                title: data[0].title,
                text: data[0].text,
                icon: data[0].type,
                confirmButtonColor: '#1f2937' // Matches your gray-900 theme
            });
        });
    });
</script>
@endpush 