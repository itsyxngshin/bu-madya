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
                    <button type="button" onclick="confirmReset({{ $evaluation->responses()->count() }})" class="px-4 py-2 bg-red-50 border border-red-200 text-red-600 font-bold rounded-xl text-xs uppercase tracking-wider hover:bg-red-100 transition flex items-center gap-2">
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

            {{-- LEFT: CONFIGURATION --}}
            <div class="lg:col-span-4 space-y-6">
                <div class="bg-white rounded-[2rem] p-6 shadow-sm border border-gray-200 sticky top-24">
                    <h2 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4">Settings</h2>

                    {{-- HEADER IMAGE --}}
                    <div class="mb-6">
                        <label class="block text-sm font-bold text-gray-700 mb-2">Header Image</label>
                        @if($header_image)
                            <img src="{{ $header_image->temporaryUrl() }}" class="w-full h-32 object-cover rounded-xl mb-2 border-2 border-orange-500">
                        @elseif($existing_header_image)
                            <img src="{{ asset('storage/'.$existing_header_image) }}" class="w-full h-32 object-cover rounded-xl mb-2 border border-gray-100">
                        @endif
                        <label class="cursor-pointer bg-gray-50 hover:bg-gray-100 text-gray-600 px-4 py-3 rounded-xl text-xs font-bold transition flex items-center justify-center gap-2 border border-dashed border-gray-300">
                            Upload Header <input type="file" wire:model="header_image" class="hidden" accept="image/*">
                        </label>
                    </div>

                    {{-- TITLE --}}
                    <div class="mb-4">
                        <label class="block text-sm font-bold text-gray-700 mb-1">Title <span class="text-red-500">*</span></label>
                        <input wire:model.live="title" type="text" class="w-full rounded-xl border-gray-200 bg-gray-50 text-sm font-bold">
                        @error('title') <span class="text-xs text-red-500 font-bold block mt-1">{{ $message }}</span> @enderror
                    </div>

                    {{-- PROJECT LINK --}}
                    <div class="mb-4">
                        <label class="block text-sm font-bold text-gray-700 mb-1">Link to Project</label>
                        <select wire:model="project_id" class="w-full rounded-xl border-gray-200 bg-gray-50 text-sm">
                            <option value="">-- No Project --</option>
                            @foreach($available_projects as $proj)
                                <option value="{{ $proj->id }}">{{ $proj->title }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- SLUG --}}
                    <div class="mb-4">
                        <label class="block text-sm font-bold text-gray-700 mb-1">URL Slug</label>
                        <div class="flex gap-2">
                            <input wire:model="slug" type="text" class="w-full rounded-xl border-gray-200 bg-gray-50 text-sm font-mono pl-3 pr-10" placeholder="Auto-generated">
                            <button wire:click="generateRandomSlug" class="px-3 py-2 bg-gray-100 hover:bg-gray-200 rounded-xl border border-gray-200">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                            </button>
                        </div>
                    </div>

                    {{-- DESC & ACTIVE --}}
                    <div class="mb-4">
                        <label class="block text-sm font-bold text-gray-700 mb-1">Description</label>
                        <textarea wire:model="description" rows="3" class="w-full rounded-xl border-gray-200 bg-gray-50 text-sm resize-none"></textarea>
                    </div>
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl border border-gray-100">
                        <span class="text-xs font-bold text-gray-600 uppercase tracking-wide">Publish?</span>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" wire:model="is_active" class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:bg-green-500 peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all"></div>
                        </label>
                    </div>
                </div>
            </div>

            {{-- RIGHT: BUILDER --}}
            <div class="lg:col-span-8 relative">

                {{-- STICKY TOOLBAR (Pushed down to top-24 to avoid header overlap) --}}
                <div class="sticky top-24 z-[100] bg-gray-900 text-white p-4 rounded-xl flex flex-wrap gap-2 items-center shadow-2xl border border-gray-800 backdrop-blur-md bg-opacity-95 mb-6">
                    <span class="font-bold ml-2 mr-auto text-sm flex items-center gap-2">Add Content</span>
                    <button wire:click="addQuestion('text')" class="px-3 py-1.5 bg-white/10 hover:bg-white/20 rounded-lg text-xs font-bold transition">Text</button>
                    <button wire:click="addQuestion('radio')" class="px-3 py-1.5 bg-white/10 hover:bg-white/20 rounded-lg text-xs font-bold transition">Radio</button>
                    <button wire:click="addQuestion('checkbox')" class="px-3 py-1.5 bg-white/10 hover:bg-white/20 rounded-lg text-xs font-bold transition">Checkbox</button>
                    <button wire:click="addQuestion('dropdown')" class="px-3 py-1.5 bg-white/10 hover:bg-white/20 rounded-lg text-xs font-bold transition">Dropdown</button>
                    <button wire:click="addQuestion('likert')" class="px-3 py-1.5 bg-white/10 hover:bg-white/20 rounded-lg text-xs font-bold transition">Scale</button>
                    <div class="h-6 w-px bg-gray-700 mx-1"></div>
                    <button wire:click="addQuestion('file')" class="px-3 py-1.5 bg-white/10 hover:bg-white/20 rounded-lg text-xs font-bold transition border border-white/30">Upload</button>
                    <button wire:click="addQuestion('section')" class="px-3 py-1.5 bg-white/10 hover:bg-white/20 rounded-lg text-xs font-bold transition border-l border-white/20 ml-2">Section</button>
                    <button wire:click="addQuestion('page_break')" class="px-3 py-1.5 bg-red-600 hover:bg-red-700 rounded-lg text-xs font-bold transition ml-2 shadow-md">Add Page Break</button>
                </div>

                {{-- QUESTIONS LIST CONTAINER --}}
                <div
                    class="bg-white rounded-b-[2rem] p-6 shadow-sm border border-t-0 border-gray-200 min-h-[400px] space-y-4 pb-20"

                    {{-- [NEW] Alpine Sortable Logic --}}
                    x-data="{
                        initSortable() {
                            // Initialize SortableJS on this element (the root root <div>)
                            this.sortable = new Sortable(this.$el, {
                                animation: 150,
                                handle: '.drag-handle', // Class selector for the drag handle
                                ghostClass: 'bg-orange-50', // Class applied to the placeholder while dragging
                                onEnd: (evt) => {
                                    // Get the new order of IDs
                                    let newOrder = [];
                                    this.$el.querySelectorAll('[data-sort-id]').forEach((el, index) => {
                                        newOrder.push({ value: el.getAttribute('data-sort-id'), order: index });
                                    });

                                    // Send to Livewire
                                    $wire.updateQuestionOrder(newOrder);
                                }
                            });
                        }
                    }"
                    x-init="initSortable()"
                >

                    @foreach($questions as $index => $question)

                        {{-- DRAGGABLE ITEM WRAPPER --}}
                        {{-- Must have a data-sort-id matching the temp_id --}}
                        <div data-sort-id="{{ $question['temp_id'] }}" wire:key="q-{{ $question['temp_id'] }}">

                            {{-- Determine Styles --}}
                            @php
                                $isSection = $question['type'] === 'section';
                                $bgClass = $isSection ? 'bg-orange-50 border-orange-200 border-dashed border-2' : 'bg-white border-gray-200 border';
                                $handleClass = $isSection ? 'border-orange-200 text-orange-300' : 'bg-gray-50 border-gray-100 text-gray-300 hover:text-gray-500';
                            @endphp

                            <div class="group relative {{ $bgClass }} rounded-2xl p-6 hover:shadow-lg transition-all duration-300">

                                {{-- [FIXED] DRAG HANDLE --}}
                                {{-- Added 'drag-handle' class for SortableJS target --}}
                                {{-- Added 'z-50' to ensure it's clickable above other elements --}}
                                <div class="drag-handle absolute left-0 top-0 bottom-0 w-10 flex flex-col items-center justify-center gap-1 border-r rounded-l-2xl cursor-move z-50 {{ $handleClass }}">
                                    {{-- Grip Icon --}}
                                    <svg class="w-6 h-6 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path></svg>
                                </div>

                                <div class="pl-8">
                                    {{-- ITEM HEADER --}}
                                    <div class="flex justify-between items-center mb-3">
                                        @if($question['type'] === 'page_break')
                                            <div class="w-full flex items-center gap-4">
                                                <div class="h-px bg-red-200 flex-1"></div>
                                                <span class="text-xs font-black uppercase tracking-widest text-red-600 bg-red-50 px-3 py-1 rounded-full border border-red-200">Page Break</span>
                                                <div class="h-px bg-red-200 flex-1"></div>
                                            </div>
                                        @elseif(!$isSection)
                                            <span class="text-[10px] font-bold uppercase tracking-wide text-gray-400 bg-gray-100 px-2 py-1 rounded">{{ ucfirst($question['type']) }}</span>
                                        @else
                                            <div class="flex-1 mr-4"><input type="text" wire:model="questions.{{ $index }}.question_text" class="w-full text-xl font-black text-gray-800 border-0 bg-transparent placeholder-orange-300 focus:ring-0 p-0" placeholder="Type Section Title"></div>
                                        @endif

                                        <div class="flex items-center gap-2 ml-auto">
                                            @if(!$isSection)
                                                <label class="flex items-center gap-2 cursor-pointer text-[10px] font-bold uppercase text-gray-400">
                                                    Required <input type="checkbox" wire:model="questions.{{ $index }}.is_required" class="rounded text-orange-500 w-4 h-4">
                                                </label>
                                            @endif
                                            <button wire:click="removeQuestion({{ $index }})" class="text-gray-300 hover:text-red-500 p-1">&times;</button>
                                        </div>
                                    </div>

                                    {{-- MAIN INPUTS --}}
                                    @if(!$isSection)
                                        <input type="text" wire:model="questions.{{ $index }}.question_text" class="w-full text-lg font-bold border-0 border-b-2 border-gray-100 focus:border-orange-500 focus:ring-0 bg-transparent transition mb-4" placeholder="Enter question...">
                                        <input type="text" wire:model="questions.{{ $index }}.description" class="w-full text-xs text-gray-500 border-0 border-b border-gray-50 focus:border-orange-300 focus:ring-0 bg-transparent transition mb-4 placeholder-gray-300" placeholder="Description (optional)">

                                        {{-- Image Upload --}}
                                        <div class="mb-4 bg-gray-50 p-3 rounded-lg border border-dashed border-gray-200">
                                            @if(isset($questions[$index]['new_image']) && $questions[$index]['new_image'])
                                                <img src="{{ $questions[$index]['new_image']->temporaryUrl() }}" class="h-24 w-auto rounded-lg mb-2">
                                            @elseif(isset($question['image_path']) && $question['image_path'])
                                                <img src="{{ asset('storage/'.$question['image_path']) }}" class="h-24 w-auto rounded-lg mb-2">
                                            @endif
                                            <label class="inline-flex items-center gap-2 text-xs font-bold text-gray-600 hover:text-orange-600 cursor-pointer">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                                {{ (isset($question['image_path']) || isset($questions[$index]['new_image'])) ? 'Change Image' : 'Add Image' }}
                                                <input type="file" wire:model="questions.{{ $index }}.new_image" class="hidden" accept="image/*">
                                            </label>
                                        </div>
                                    @else
                                        <input type="text" wire:model="questions.{{ $index }}.description" class="w-full text-sm text-gray-600 border-0 bg-transparent placeholder-orange-300/50 focus:ring-0 p-0" placeholder="Add description (optional)...">
                                    @endif

                                    {{-- RADIO / CHECKBOX/DROPDOWN (With Skip Logic) --}}
                                    @if(in_array($question['type'], ['radio', 'checkbox', 'dropdown']))
                                        <div class="pl-4 border-l-2 border-gray-100 space-y-3 mt-2">
                                            <div class="flex justify-between items-end mb-2">
                                                <p class="text-[10px] text-gray-400 font-bold uppercase">
                                                    {{ $question['type'] === 'checkbox' ? 'Multiple Choice' : ($question['type'] === 'dropdown' ? 'Dropdown Menu' : 'Single Choice') }}
                                                </p>
                                                @if($question['type'] === 'radio') <span class="text-[10px] text-orange-500 bg-orange-50 px-2 py-1 rounded font-bold">Skip Logic Available</span> @endif
                                            </div>
                                            @foreach($question['options'] as $optIndex => $opt)
                                                <div class="flex flex-col gap-1 bg-gray-50 p-2 rounded-xl border border-gray-200">
                                                    <div class="flex items-center gap-2">
                                                        <div class="w-3 h-3 rounded-full border border-gray-300 {{ $question['type'] === 'checkbox' ? 'rounded-sm' : '' }}"></div>
                                                        <input type="text" wire:model="questions.{{ $index }}.options.{{ $optIndex }}{{ is_array($opt) ? '.text' : '' }}" class="w-full text-sm border-0 border-b border-gray-200 bg-transparent focus:ring-0 placeholder-gray-400" placeholder="Option Label">
                                                        <button wire:click="removeOption({{ $index }}, {{ $optIndex }})" class="text-gray-400 hover:text-red-500">&times;</button>
                                                    </div>
                                                    @if($question['type'] === 'radio' && is_array($opt))
                                                        <div class="flex items-center gap-2 mt-1 pl-5">
                                                            <span class="text-[9px] text-gray-400 uppercase font-bold">Go to:</span>
                                                            <select wire:model="questions.{{ $index }}.options.{{ $optIndex }}.jump" class="text-xs border-gray-200 rounded-lg py-1 bg-white">
                                                                <option value="">Next (Default)</option>
                                                                <option value="submit">Submit Form</option>
                                                                @foreach($this->sections as $section)
                                                                    @if($section['order'] > $question['order']) <option value="{{ $section['id'] }}">Section: {{ Str::limit($section['title'], 20) }}</option> @endif
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    @endif
                                                </div>
                                            @endforeach
                                            <button wire:click="addOption({{ $index }})" class="text-xs font-bold text-blue-600 hover:underline mt-2 flex items-center gap-1">+ Add Option</button>
                                        </div>

                                    {{-- LIKERT --}}
                                    @elseif($question['type'] === 'likert')
                                        <div class="bg-gray-50 p-4 rounded-xl border border-gray-100 mt-2">
                                            <p class="text-[10px] text-gray-400 font-bold uppercase mb-2 text-center">Scale Labels</p>
                                            <div class="grid grid-cols-2 md:grid-cols-5 gap-2">
                                                @foreach($question['options'] as $optIndex => $option)
                                                    <input type="text" wire:model="questions.{{ $index }}.options.{{ $optIndex }}" class="text-xs text-center border-gray-200 rounded-lg">
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif

                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function confirmReset(count) {
        Swal.fire({
            title: 'Delete all responses?',
            text: `You are about to delete ${count} user responses.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            confirmButtonText: 'Yes, delete!'
        }).then((result) => {
            if (result.isConfirmed) Livewire.dispatch('confirmed-reset');
        })
    }
    document.addEventListener('livewire:initialized', () => {
        Livewire.on('swal:modal', (data) => {
            Swal.fire({ title: data[0].title, text: data[0].text, icon: data[0].type });
        });
    });
</script>
@endpush
