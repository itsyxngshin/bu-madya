<div class="min-h-screen bg-gray-100 p-4 md:p-6 font-sans text-gray-900 pb-32">
    
    <div class="max-w-5xl mx-auto">
        
        {{-- HEADER / ACTIONS --}}
        <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
            <div>
                <h1 class="text-xl md:text-2xl font-black text-gray-900 flex items-center gap-2">
                    @if($evaluation->exists) <span class="text-orange-500">Edit</span> Evaluation @else <span class="text-green-500">Create</span> Evaluation @endif
                </h1>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.evaluations.index') }}" class="px-4 py-2 bg-white border border-gray-300 text-gray-600 font-bold rounded-lg text-xs uppercase tracking-wider hover:bg-gray-50 transition">Cancel</a>

                @if($evaluation->exists && $evaluation->responses()->count() > 0)
                    <button type="button" onclick="confirmReset({{ $evaluation->responses()->count() }})" class="px-4 py-2 bg-red-50 border border-red-200 text-red-600 font-bold rounded-lg text-xs uppercase tracking-wider hover:bg-red-100 transition flex items-center gap-2">
                        Reset ({{ $evaluation->responses()->count() }})
                    </button>
                @endif

                <button wire:click="save" class="px-6 py-2 bg-gray-900 text-white font-bold rounded-lg shadow-lg hover:bg-gray-800 transition text-xs uppercase tracking-wider flex items-center gap-2">
                    Save Changes
                </button>
            </div>
        </div>

        <div class="grid grid-cols-1 xl:grid-cols-12 gap-6">
            
            {{-- LEFT: CONFIGURATION --}}
            <div class="xl:col-span-4 space-y-4">
                <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-200 sticky top-24">
                    <h2 class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-4">Settings</h2>
                    
                    {{-- HEADER IMAGE --}}
                    <div class="mb-5">
                        <label class="block text-xs font-bold text-gray-700 mb-1">Header Image</label>
                        @if($header_image)
                            <img src="{{ $header_image->temporaryUrl() }}" class="w-full h-24 object-cover rounded-lg mb-2 border-2 border-orange-500">
                        @elseif($existing_header_image)
                            <img src="{{ asset('storage/'.$existing_header_image) }}" class="w-full h-24 object-cover rounded-lg mb-2 border border-gray-100">
                        @endif
                        <label class="cursor-pointer w-full bg-gray-50 hover:bg-gray-100 text-gray-600 px-3 py-2 rounded-lg text-xs font-bold transition flex items-center justify-center border border-dashed border-gray-300">
                            Upload Header <input type="file" wire:model="header_image" class="hidden" accept="image/*">
                        </label>
                    </div>

                    {{-- TITLE --}}
                    <div class="mb-4">
                        <label class="block text-xs font-bold text-gray-700 mb-1">Title <span class="text-red-500">*</span></label>
                        <input wire:model.live="title" type="text" class="w-full rounded-lg border-gray-200 bg-gray-50 text-sm font-bold p-2">
                        @error('title') <span class="text-[10px] text-red-500 font-bold block mt-1">{{ $message }}</span> @enderror
                    </div>

                    {{-- PROJECT LINK --}}
                    <div class="mb-4">
                        <label class="block text-xs font-bold text-gray-700 mb-1">Link to Project</label>
                        <select wire:model="project_id" class="w-full rounded-lg border-gray-200 bg-gray-50 text-sm p-2">
                            <option value="">-- No Project --</option>
                            @foreach($available_projects as $proj)
                                <option value="{{ $proj->id }}">{{ $proj->title }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- SLUG --}}
                    <div class="mb-4">
                        <label class="block text-xs font-bold text-gray-700 mb-1">URL Slug</label>
                        <div class="flex gap-1">
                            <input wire:model="slug" type="text" class="w-full rounded-lg border-gray-200 bg-gray-50 text-xs font-mono p-2" placeholder="Auto-generated">
                            <button wire:click="generateRandomSlug" class="px-2 py-1 bg-gray-100 hover:bg-gray-200 rounded-lg border border-gray-200" title="Randomize">
                                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                            </button>
                        </div>
                    </div>

                    {{-- DESC & ACTIVE --}}
                    <div class="mb-4">
                        <label class="block text-xs font-bold text-gray-700 mb-1">Description</label>
                        <textarea wire:model="description" rows="2" class="w-full rounded-lg border-gray-200 bg-gray-50 text-xs p-2 resize-none"></textarea>
                    </div>
                    <div class="flex items-center justify-between p-2 bg-gray-50 rounded-lg border border-gray-100">
                        <span class="text-[10px] font-bold text-gray-600 uppercase tracking-wide">Publish Active?</span>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" wire:model="is_active" class="sr-only peer">
                            <div class="w-9 h-5 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:bg-green-500 peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-4 after:w-4 after:transition-all"></div>
                        </label>
                    </div>
                </div>
            </div>

            {{-- RIGHT: BUILDER --}}
            <div class="xl:col-span-8 relative">
                
                {{-- STICKY TOOLBAR --}}
                <div class="sticky top-2 z-[100] bg-gray-900 text-white p-3 rounded-2xl flex flex-wrap gap-1.5 items-center shadow-xl border border-gray-800 backdrop-blur-md bg-opacity-95 mb-4">
                    <span class="font-bold ml-2 mr-auto text-xs uppercase tracking-widest text-gray-400">Insert</span>
                    <button wire:click="addQuestion('text')" class="px-2 py-1.5 bg-white/10 hover:bg-white/20 rounded-md text-[10px] uppercase font-bold transition">Text</button>
                    <button wire:click="addQuestion('radio')" class="px-2 py-1.5 bg-white/10 hover:bg-white/20 rounded-md text-[10px] uppercase font-bold transition">Radio</button>
                    <button wire:click="addQuestion('checkbox')" class="px-2 py-1.5 bg-white/10 hover:bg-white/20 rounded-md text-[10px] uppercase font-bold transition">Check</button>
                    <button wire:click="addQuestion('dropdown')" class="px-2 py-1.5 bg-white/10 hover:bg-white/20 rounded-md text-[10px] uppercase font-bold transition">Drop</button>
                    <button wire:click="addQuestion('likert')" class="px-2 py-1.5 bg-white/10 hover:bg-white/20 rounded-md text-[10px] uppercase font-bold transition">Scale</button>
                    <div class="h-4 w-px bg-gray-700 mx-1"></div>
                    <button wire:click="addQuestion('section')" class="px-2 py-1.5 bg-white/10 hover:bg-white/20 rounded-md text-[10px] uppercase font-bold transition">Section</button>
                    <button wire:click="addQuestion('page_break')" class="px-2 py-1.5 bg-red-600 hover:bg-red-700 rounded-md text-[10px] uppercase font-bold transition shadow-sm ml-1">Page Break</button>
                </div>
                
                {{-- QUESTIONS LIST CONTAINER --}}
                <div 
                    class="bg-gray-50/50 rounded-2xl min-h-[400px] space-y-3 pb-20"
                    x-data="{
                        initSortable() {
                            this.sortable = new Sortable(this.$el, {
                                animation: 150,
                                handle: '.drag-handle', 
                                ghostClass: 'opacity-50', 
                                onEnd: (evt) => {
                                    let newOrder = [];
                                    this.$el.querySelectorAll('[data-sort-id]').forEach((el, index) => {
                                        newOrder.push({ value: el.getAttribute('data-sort-id'), order: index });
                                    });
                                    $wire.updateQuestionOrder(newOrder);
                                }
                            });
                        }
                    }"
                    x-init="initSortable()"
                >
                    
                    @foreach($questions as $index => $question)
                        
                        @php
                            $isActive = ($activeQuestionIndex === $index);
                        @endphp

                        {{-- DRAGGABLE ITEM WRAPPER --}}
                        <div data-sort-id="{{ $question['temp_id'] }}" wire:key="q-{{ $question['temp_id'] }}">
                            
                            {{-- PAGE BREAK UI (Super slim, no inputs) --}}
                            @if($question['type'] === 'page_break')
                                <div class="group relative bg-red-50/80 border {{ $isActive ? 'border-red-400 shadow-md ring-1 ring-red-400' : 'border-red-100 hover:border-red-200' }} rounded-xl p-2 flex items-center justify-between transition-all cursor-pointer" wire:click="setActiveQuestion({{ $index }})">
                                    <div class="drag-handle w-6 flex items-center justify-center cursor-move text-red-300 hover:text-red-500">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"></path></svg>
                                    </div>
                                    <div class="flex-1 flex items-center gap-3 px-2 pointer-events-none opacity-70">
                                        <div class="h-px bg-red-300 flex-1"></div>
                                        <span class="text-[9px] font-black uppercase tracking-widest text-red-600 bg-white px-2 py-0.5 rounded border border-red-200">Page Break</span>
                                        <div class="h-px bg-red-300 flex-1"></div>
                                    </div>
                                    <button wire:click.stop="removeQuestion({{ $index }})" class="text-red-300 hover:text-red-600 p-1">&times;</button>
                                </div>

                            {{-- STANDARD UI (Text, Radio, Section, etc) --}}
                            @else
                                @php
                                    $isSection = $question['type'] === 'section';
                                    $bgClass = $isSection ? 'bg-orange-50/50' : 'bg-white';
                                    $borderClass = $isActive ? 'border-orange-400 shadow-lg ring-1 ring-orange-400' : 'border-gray-200 hover:border-gray-300 shadow-sm';
                                @endphp

                                <div class="group relative {{ $bgClass }} border {{ $borderClass }} rounded-xl p-4 transition-all duration-200 cursor-text" wire:click="setActiveQuestion({{ $index }})">
                                    
                                    {{-- Active Indicator Stripe --}}
                                    @if($isActive) <div class="absolute left-0 top-3 bottom-3 w-1 bg-orange-500 rounded-r"></div> @endif
                                    
                                    {{-- DRAG HANDLE --}}
                                    <div class="drag-handle absolute left-0 top-0 bottom-0 w-8 flex flex-col items-center justify-center gap-1 cursor-move z-10 text-gray-200 hover:text-gray-400 hover:bg-gray-50 rounded-l-xl">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"></path></svg>
                                    </div>

                                    <div class="pl-6">
                                        {{-- ITEM HEADER --}}
                                        <div class="flex justify-between items-start mb-2">
                                            @if($isSection)
                                                <div class="flex-1 mr-2">
                                                    <input type="text" wire:model="questions.{{ $index }}.question_text" class="w-full text-base font-black text-gray-800 border-0 bg-transparent placeholder-orange-300 focus:ring-0 p-0" placeholder="Type Section Title">
                                                </div>
                                            @else
                                                <span class="text-[9px] font-bold uppercase tracking-wide text-gray-400 bg-gray-100 px-1.5 py-0.5 rounded">{{ ucfirst($question['type']) }}</span>
                                            @endif

                                            <div class="flex items-center gap-3 ml-auto shrink-0">
                                                @if(!$isSection)
                                                    <label class="flex items-center gap-1 cursor-pointer text-[9px] font-bold uppercase text-gray-400">
                                                        Req <input type="checkbox" wire:model="questions.{{ $index }}.is_required" class="rounded text-orange-500 w-3.5 h-3.5 border-gray-300">
                                                    </label>
                                                @endif
                                                <button wire:click.stop="removeQuestion({{ $index }})" class="text-gray-300 hover:text-red-500">&times;</button>
                                            </div>
                                        </div>

                                        {{-- MAIN INPUTS --}}
                                        @if(!$isSection)
                                            <input type="text" wire:model="questions.{{ $index }}.question_text" class="w-full text-sm font-bold border-0 border-b border-gray-100 focus:border-orange-500 focus:ring-0 bg-transparent transition mb-2 p-0" placeholder="Enter question...">
                                            
                                            {{-- Description & Image Toggles (Only show fully when active to save space) --}}
                                            @if($isActive || $question['description'] || $question['image_path'] || (isset($question['new_image']) && $question['new_image']))
                                                <input type="text" wire:model="questions.{{ $index }}.description" class="w-full text-xs text-gray-500 border-0 border-b border-gray-50 focus:border-orange-300 focus:ring-0 bg-transparent transition mb-3 p-0 placeholder-gray-300" placeholder="Help text / description (optional)">
                                                
                                                <div class="mb-3 flex items-center gap-3">
                                                    @if(isset($questions[$index]['new_image']) && $questions[$index]['new_image'])
                                                        <img src="{{ $questions[$index]['new_image']->temporaryUrl() }}" class="h-12 w-auto rounded border border-gray-200">
                                                    @elseif(isset($question['image_path']) && $question['image_path'])
                                                        <img src="{{ asset('storage/'.$question['image_path']) }}" class="h-12 w-auto rounded border border-gray-200">
                                                    @endif
                                                    
                                                    <label class="inline-flex items-center gap-1 text-[10px] font-bold uppercase text-gray-400 hover:text-orange-600 cursor-pointer bg-gray-50 px-2 py-1 rounded">
                                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                                        Image
                                                        <input type="file" wire:model="questions.{{ $index }}.new_image" class="hidden" accept="image/*">
                                                    </label>
                                                </div>
                                            @endif
                                        @else
                                            <input type="text" wire:model="questions.{{ $index }}.description" class="w-full text-xs text-gray-500 border-0 bg-transparent placeholder-orange-300/50 focus:ring-0 p-0" placeholder="Section description (optional)...">
                                        @endif

                                        {{-- RADIO / CHECKBOX / DROPDOWN --}}
                                        @if(in_array($question['type'], ['radio', 'checkbox', 'dropdown']))
                                            <div class="pl-2 border-l-2 border-gray-100 space-y-1.5 mt-2">
                                                @foreach($question['options'] as $optIndex => $opt)
                                                    <div class="flex flex-col gap-1">
                                                        <div class="flex items-center gap-2">
                                                            <div class="w-2 h-2 border border-gray-300 {{ $question['type'] === 'checkbox' ? 'rounded-sm' : 'rounded-full' }}"></div>
                                                            <input type="text" wire:model="questions.{{ $index }}.options.{{ $optIndex }}{{ is_array($opt) ? '.text' : '' }}" class="w-full text-xs border-0 border-b border-dashed border-gray-200 bg-transparent focus:border-orange-400 focus:ring-0 p-0 placeholder-gray-300" placeholder="Option">
                                                            <button wire:click.stop="removeOption({{ $index }}, {{ $optIndex }})" class="text-gray-300 hover:text-red-500">&times;</button>
                                                        </div>
                                                        @if($question['type'] === 'radio' && is_array($opt) && $isActive)
                                                            <div class="flex items-center gap-2 pl-4">
                                                                <svg class="w-2.5 h-2.5 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"></path></svg>
                                                                <select wire:model="questions.{{ $index }}.options.{{ $optIndex }}.jump" class="text-[9px] border-gray-200 rounded py-0.5 px-1 bg-gray-50 text-gray-500">
                                                                    <option value="">Continue to next</option>
                                                                    <option value="submit">Submit Form</option>
                                                                    @foreach($this->sections as $section)
                                                                        @if($section['order'] > $question['order']) <option value="{{ $section['id'] }}">Go to: {{ Str::limit($section['title'], 15) }}</option> @endif
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        @endif
                                                    </div>
                                                @endforeach
                                                <button wire:click.stop="addOption({{ $index }})" class="text-[10px] font-bold text-blue-500 hover:underline mt-1">+ Add Option</button>
                                            </div>
                                        
                                        {{-- LIKERT --}}
                                        @elseif($question['type'] === 'likert')
                                            <div class="bg-gray-50 rounded-lg p-2 border border-gray-100 mt-2">
                                                <div class="grid grid-cols-5 gap-1">
                                                    @foreach($question['options'] as $optIndex => $option)
                                                        <input type="text" wire:model="questions.{{ $index }}.options.{{ $optIndex }}" class="text-[9px] text-center border-gray-200 rounded p-1">
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif

                                    </div>
                                </div>
                            @endif
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