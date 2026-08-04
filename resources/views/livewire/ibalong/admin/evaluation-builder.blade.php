<div class="max-w-7xl mx-auto space-y-6 pb-32"
     x-data="{ sectionToDelete: @entangle('sectionToDeleteIndex') }"
     x-effect="document.body.classList.toggle('overflow-hidden', sectionToDelete !== null)">

    {{-- ========================================== --}}
    {{-- HEADER / ACTIONS --}}
    {{-- ========================================== --}}
    <div class="bg-white border-4 border-iba-black shadow-[8px_8px_0_0_#FF8623] p-6 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-2xl font-black uppercase tracking-widest text-iba-black">
                {{ $evaluation->exists ? 'Modify Form Blueprint' : 'Establish New Blueprint' }}
            </h1>
            <p class="text-xs font-bold text-gray-500 uppercase mt-1">Configure evaluation metrics and automation protocols.</p>
        </div>

        <div class="flex flex-wrap items-center gap-3">
            <a href="#" class="bg-gray-100 text-iba-black text-xs font-black uppercase px-6 py-3 border-2 border-iba-black hover:bg-gray-200 transition-colors">Cancel</a>
            <button wire:click="save" class="bg-iba-black text-white text-xs font-black uppercase tracking-widest px-8 py-3 border-4 border-transparent shadow-[4px_4px_0_0_#0095AC] hover:translate-y-1 hover:shadow-none transition-all">
                Save Blueprint
            </button>
        </div>
    </div>

    @if (session()->has('success'))
        <div class="bg-iba-green/10 border-l-4 border-iba-green p-4 flex items-center shadow-[4px_4px_0_0_#131011]">
            <p class="text-sm font-bold text-iba-green uppercase tracking-wider">{{ session('success') }}</p>
        </div>
    @endif

    <div class="grid grid-cols-1 xl:grid-cols-12 gap-8">
        {{-- ========================================== --}}
        {{-- LEFT: CONFIGURATION --}}
        {{-- ========================================== --}}
        <div class="xl:col-span-4 space-y-6">

            {{-- Main Settings --}}
            <div class="bg-white border-4 border-iba-black shadow-[6px_6px_0_0_#131011] p-6">
                <h2 class="text-lg font-black uppercase border-b-4 border-iba-black pb-2 mb-4">General Settings</h2>

                <div class="mb-5">
                    <label class="block text-[10px] font-black text-gray-500 uppercase mb-1">Theme Identifier</label>
                    <div class="flex items-center gap-2">
                        <input type="color" wire:model.live="theme_color" class="w-12 h-12 border-2 border-iba-black p-0.5 cursor-pointer bg-gray-50 shrink-0">
                        <input type="text" wire:model.live="theme_color" class="w-full border-2 border-iba-black bg-gray-50 text-sm font-bold p-3 uppercase focus:outline-none focus:border-iba-orange">
                    </div>
                </div>

                <div class="mb-5">
                    <label class="block text-[10px] font-black text-gray-500 uppercase mb-1">Form Title <span class="text-iba-red">*</span></label>
                    <input wire:model.live="title" type="text" class="w-full border-2 border-iba-black bg-gray-50 text-base font-bold p-3 focus:outline-none focus:bg-white focus:border-iba-orange">
                    @error('title') <span class="text-[10px] font-black text-iba-red uppercase mt-1 block">⚠ {{ $message }}</span> @enderror
                </div>

                <div class="mb-5" x-data="{
                        insert(start, end) {
                            let el = this.$refs.editor;
                            let text = el.value;
                            let s = el.selectionStart;
                            let e = el.selectionEnd;
                            el.value = text.substring(0, s) + start + text.substring(s, e) + end + text.substring(e);
                            el.dispatchEvent(new Event('input'));
                            setTimeout(() => { el.focus(); el.setSelectionRange(s + start.length, e + start.length); }, 50);
                        },
                        resize(el) {
                            this.$nextTick(() => { el.style.height = 'auto'; el.style.height = el.scrollHeight + 'px'; });
                        }
                    }">
                    <div class="flex justify-between items-end mb-1">
                        <label class="block text-[10px] font-black text-gray-500 uppercase">Context / Instructions</label>
                        <div class="flex bg-gray-100 border-2 border-iba-black border-b-0">
                            <button type="button" @click="insert('**', '**')" class="px-2 py-1 hover:bg-gray-200 text-iba-black font-black text-[10px]" title="Bold">B</button>
                            <button type="button" @click="insert('*', '*')" class="px-2 py-1 hover:bg-gray-200 text-iba-black italic text-[10px] border-l-2 border-iba-black" title="Italic">I</button>
                            <button type="button" @click="insert('[', '](https://)')" class="px-2 py-1 hover:bg-gray-200 text-iba-black font-bold text-[10px] border-l-2 border-iba-black" title="Link">Link</button>
                        </div>
                    </div>
                    <textarea x-ref="editor" wire:model="description" rows="2" x-init="$nextTick(() => resize($el))" @input="resize($el)"
                              class="w-full border-2 border-iba-black bg-gray-50 text-sm font-bold p-3 overflow-hidden focus:outline-none focus:bg-white focus:border-iba-orange resize-none"
                              placeholder="Enter operational directives..."></textarea>
                </div>

                <div class="flex items-center justify-between p-4 bg-gray-100 border-2 border-iba-black">
                    <span class="text-xs font-black uppercase text-iba-black">Live Deployment</span>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" wire:model="is_active" class="sr-only peer">
                        <div class="w-11 h-6 bg-gray-300 border-2 border-iba-black peer-focus:outline-none peer-checked:bg-iba-green after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-2 after:border-iba-black after:h-4 after:w-4 after:transition-all peer-checked:after:translate-x-[18px]"></div>
                    </label>
                </div>
            </div>

            {{-- Certificate Builder --}}
            <div x-data="{ expanded: false }" class="bg-white border-4 border-iba-black shadow-[6px_6px_0_0_#131011]">
                <div class="mb-5">
                    <label class="block text-[10px] font-black text-gray-500 uppercase mb-2">Access Control Protocol</label>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <label class="flex flex-col p-4 border-4 cursor-pointer transition-colors {{ $access_level === 'public' ? 'bg-iba-teal/10 border-iba-teal' : 'bg-gray-50 border-gray-300 hover:border-iba-black' }}">
                            <div class="flex items-center gap-3 mb-1">
                                <input type="radio" wire:model="access_level" value="public" class="w-5 h-5 text-iba-teal border-2 border-iba-black focus:ring-0">
                                <span class="text-xs font-black uppercase text-iba-black tracking-widest">Public Access</span>
                            </div>
                            <span class="text-[10px] font-bold text-gray-500 pl-8 uppercase">Available to all logged-in attendees.</span>
                        </label>

                        <label class="flex flex-col p-4 border-4 cursor-pointer transition-colors {{ $access_level === 'teams_only' ? 'bg-iba-orange/10 border-iba-orange' : 'bg-gray-50 border-gray-300 hover:border-iba-black' }}">
                            <div class="flex items-center gap-3 mb-1">
                                <input type="radio" wire:model="access_level" value="teams_only" class="w-5 h-5 text-iba-orange border-2 border-iba-black focus:ring-0">
                                <span class="text-xs font-black uppercase text-iba-black tracking-widest">Cohorts Only</span>
                            </div>
                            <span class="text-[10px] font-bold text-gray-500 pl-8 uppercase">Restricted strictly to registered teams.</span>
                        </label>
                    </div>
                </div>

                <button @click="expanded = !expanded" type="button" class="w-full flex items-center justify-between p-6 bg-iba-black text-white hover:bg-gray-900 transition-colors">
                    <div class="flex flex-col items-start text-left">
                        <h3 class="text-base font-black uppercase tracking-widest">E-Certificate Factory</h3>
                        <p class="text-[10px] font-bold text-gray-400 mt-1 uppercase">Automate issuance protocols</p>
                    </div>
                    <svg class="w-6 h-6 transition-transform duration-200" :class="expanded ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </button>

                <div x-show="expanded" x-collapse x-cloak>
                    <div class="p-6 border-t-4 border-iba-black space-y-6 bg-gray-50">
                        {{-- Cert Tools from Source 7 go here (adapted to Brutalism) --}}
                        <div class="p-4 border-2 border-dashed border-gray-400 text-center text-xs font-bold text-gray-500 uppercase tracking-widest">
                            <p>Certificate alignment module available in extended view.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ========================================== --}}
        {{-- RIGHT: BUILDER (The Canvas) --}}
        {{-- ========================================== --}}
        <div class="xl:col-span-8 relative">

            {{-- Floating Tool Menu --}}
            <div class="sticky top-6 z-[100] flex justify-center mb-8 pointer-events-none px-4 sm:px-0">
                <div class="pointer-events-auto relative w-full max-w-sm sm:max-w-md md:max-w-2xl" x-data="{ open: false }">
                    <button @click="open = !open" @click.outside="open = false" type="button" class="w-full flex items-center justify-center gap-3 py-4 bg-iba-black text-white font-black uppercase tracking-widest shadow-[6px_6px_0_0_#FF8623] hover:translate-y-1 hover:shadow-none transition-all border-4 border-iba-black">
                        <span x-text="open ? 'Close Component Menu' : 'Inject Form Element'"></span>
                    </button>

                    <div x-show="open" x-cloak class="absolute top-full left-0 mt-2 w-full bg-white border-4 border-iba-black shadow-[8px_8px_0_0_#131011] overflow-hidden flex flex-col z-50">
                        <div class="px-6 py-3 bg-gray-100 border-b-4 border-iba-black">
                            <span class="text-[10px] font-black uppercase tracking-widest text-gray-500">Available Nodes</span>
                        </div>
                        <div class="p-4 grid grid-cols-2 gap-4">
                            <button wire:click="addQuestion('text'); open = false" class="w-full text-center px-4 py-3 bg-white border-2 border-iba-black text-xs font-black uppercase hover:bg-iba-orange transition-colors shadow-[2px_2px_0_0_#131011]">Text Field</button>
                            <button wire:click="addQuestion('radio'); open = false" class="w-full text-center px-4 py-3 bg-white border-2 border-iba-black text-xs font-black uppercase hover:bg-iba-orange transition-colors shadow-[2px_2px_0_0_#131011]">Single Choice</button>
                            <button wire:click="addQuestion('checkbox'); open = false" class="w-full text-center px-4 py-3 bg-white border-2 border-iba-black text-xs font-black uppercase hover:bg-iba-orange transition-colors shadow-[2px_2px_0_0_#131011]">Checkboxes</button>
                            <button wire:click="addQuestion('likert'); open = false" class="w-full text-center px-4 py-3 bg-white border-2 border-iba-black text-xs font-black uppercase hover:bg-iba-orange transition-colors shadow-[2px_2px_0_0_#131011]">Rating Scale</button>

                            <button wire:click="addQuestion('section'); open = false" class="col-span-2 w-full text-center px-4 py-3 bg-gray-100 border-2 border-dashed border-iba-black text-xs font-black uppercase hover:bg-gray-200 transition-colors mt-2">Inject Section Header</button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Unified Sortable List Container --}}
            <div class="bg-transparent min-h-[400px] pb-20 relative"
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
                 }" x-init="initSortable()">

                @foreach($questions as $index => $question)
                    @php
                        $qKey = $question['temp_id'];
                        $isSectionHeader = $question['type'] === 'section';
                        $isActive = ($activeQuestionIndex === $index);

                        // Brutalist Styling logic
                        $classes = 'group/card relative transition-all duration-200 p-6 ';
                        if ($isSectionHeader) {
                            $classes .= 'bg-iba-teal text-white border-4 border-iba-black mt-8 mb-0 shadow-[6px_6px_0_0_#131011] ';
                        } else {
                            $classes .= 'bg-white border-4 border-iba-black border-t-0 mb-0 shadow-[6px_6px_0_0_#131011] ';
                        }
                        if ($isActive) $classes .= 'ring-4 ring-iba-orange ring-offset-2 z-20 ';
                        else $classes .= 'z-10 ';
                    @endphp

                    <div data-sort-id="{{ $qKey }}" wire:key="question-{{ $index }}-{{ $qKey }}" class="{{ $classes }}" wire:click="setActiveQuestion({{ $index }})">

                        {{-- Drag Handle --}}
                        <div class="drag-handle absolute left-0 top-0 bottom-0 w-8 flex flex-col items-center justify-center cursor-move z-10 bg-gray-200 border-r-4 border-iba-black opacity-0 group-hover/card:opacity-100 transition-opacity">
                            <svg class="w-4 h-4 text-iba-black" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"></path></svg>
                        </div>

                        <div class="pl-8" x-data="{
                                activeField: 'qText',
                                resize(el) { this.$nextTick(() => { el.style.height = 'auto'; el.style.height = el.scrollHeight + 'px'; }); }
                            }">

                            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start mb-4 gap-4">
                                <div class="flex flex-col gap-2 flex-1 w-full">
                                    @if(!$isSectionHeader)
                                        <span class="text-[9px] font-black uppercase tracking-widest bg-gray-100 border-2 border-iba-black px-2 py-1 w-max">{{ $question['type'] }}</span>
                                    @endif
                                </div>

                                <div class="flex items-center gap-2 ml-auto shrink-0">
                                    @if(!$isSectionHeader)
                                        <label class="flex items-center gap-2 cursor-pointer bg-gray-100 px-3 py-1 border-2 border-iba-black">
                                            <input type="checkbox" wire:model="questions.{{ $index }}.is_required" class="text-iba-orange border-2 border-iba-black focus:ring-0 w-4 h-4">
                                            <span class="text-[10px] font-black uppercase tracking-widest text-iba-black">Required</span>
                                        </label>
                                    @endif

                                    @if($isSectionHeader)
                                        <button wire:click.stop="confirmDeleteSection({{ $index }})" class="bg-iba-red text-white p-2 border-2 border-iba-black hover:bg-red-800 transition-colors" title="Delete Section">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                        </button>
                                    @else
                                        <button wire:click.stop="removeQuestion({{ $index }})" class="bg-gray-100 text-iba-red p-2 border-2 border-iba-black hover:bg-gray-200 transition-colors" title="Delete Question">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                        </button>
                                    @endif
                                </div>
                            </div>

                            {{-- Question Text --}}
                            <textarea x-ref="qText" @focus="activeField = 'qText'" wire:model="questions.{{ $index }}.question_text"
                                      x-init="$nextTick(() => resize($el))" @input="resize($el)" rows="1"
                                      class="w-full text-base font-black uppercase border-0 border-b-4 border-transparent hover:border-black/20 focus:border-iba-orange focus:ring-0 bg-transparent transition-colors p-0 mb-3 overflow-hidden resize-none {{ $isSectionHeader ? 'text-white placeholder-white/50' : 'text-iba-black placeholder-gray-300' }}"
                                      placeholder="Enter Question Target..."></textarea>

                            {{-- Description --}}
                            @if($isActive || $question['description'])
                                <textarea wire:model="questions.{{ $index }}.description" rows="1" x-init="resize($el)" @input="resize($el)"
                                          class="w-full text-sm font-bold border-0 border-b-2 border-transparent focus:border-iba-orange focus:ring-0 bg-transparent transition-colors p-0 mb-4 overflow-hidden resize-none {{ $isSectionHeader ? 'text-teal-100 placeholder-teal-200/50' : 'text-gray-500 placeholder-gray-300' }}"
                                          placeholder="Add helper directives..."></textarea>
                            @endif

                            {{-- Options --}}
                            @if(in_array($question['type'], ['radio', 'checkbox', 'dropdown']))
                                <div class="pl-4 border-l-4 border-gray-200 space-y-3 mt-4">
                                    @foreach($question['options'] as $optIndex => $opt)
                                        <div class="flex items-center gap-3" wire:key="opt-{{ $index }}-{{ $qKey }}-{{ $optIndex }}">
                                            <div class="w-4 h-4 border-4 border-gray-400 {{ $question['type'] === 'checkbox' ? 'rounded-none' : 'rounded-full' }} shrink-0"></div>
                                            <input type="text" wire:model="questions.{{ $index }}.options.{{ $optIndex }}{{ is_array($opt) ? '.text' : '' }}" class="w-full text-sm font-bold border-0 border-b-2 border-dashed border-gray-300 bg-transparent focus:border-iba-orange focus:ring-0 p-1 placeholder-gray-400" placeholder="Parameter output">
                                            <button wire:click.stop="removeOption({{ $index }}, {{ $optIndex }})" class="text-iba-red hover:text-red-800 p-1 font-black shrink-0 uppercase text-[10px]">Remove</button>
                                        </div>
                                    @endforeach
                                    <button wire:click.stop="addOption({{ $index }})" class="text-[10px] font-black uppercase text-iba-teal hover:text-teal-800 mt-2 bg-teal-50 px-3 py-1 border-2 border-iba-teal">
                                        + Inject Option
                                    </button>
                                </div>
                            @elseif($question['type'] === 'likert')
                                <div class="bg-gray-100 p-4 border-2 border-iba-black mt-4 flex gap-2 overflow-x-auto">
                                    @foreach($question['options'] as $optIndex => $option)
                                        <input type="text" wire:key="likert-{{ $index }}-{{ $qKey }}-{{ $optIndex }}" wire:model="questions.{{ $index }}.options.{{ $optIndex }}" class="w-24 text-[10px] font-black uppercase text-center border-2 border-iba-black focus:border-iba-orange focus:ring-0 bg-white p-2">
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- ========================================== --}}
    {{-- DELETE SECTION CONFIRMATION MODAL --}}
    {{-- ========================================== --}}
    @if($sectionToDeleteIndex !== null)
        <div class="fixed inset-0 z-[100] flex items-center justify-center p-4">
            <div class="fixed inset-0 bg-iba-black/90 backdrop-blur-sm" wire:click="cancelDeleteSection"></div>
            <div class="relative bg-white border-4 border-iba-red shadow-[8px_8px_0_0_#D93B3B] p-8 max-w-md text-center">
                <svg class="w-16 h-16 text-iba-red mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                <h3 class="text-xl font-black uppercase tracking-widest text-iba-black mb-2">Purge Sector?</h3>
                <p class="text-xs font-bold text-gray-500 mb-6">
                    You are deleting sector <strong class="text-iba-red font-black">"{{ $questions[$sectionToDeleteIndex]['question_text'] ?: 'Untitled' }}"</strong> and all integrated nodes. Proceed?
                </p>
                <div class="flex gap-3">
                    <button wire:click="cancelDeleteSection" class="flex-1 bg-gray-100 border-2 border-iba-black text-iba-black text-xs font-black uppercase py-3 hover:bg-gray-200 transition-colors">Abort</button>
                    <button wire:click="executeDeleteSection" class="flex-1 bg-iba-red border-2 border-iba-black text-white text-xs font-black uppercase py-3 hover:bg-red-800 transition-colors shadow-[4px_4px_0_0_#131011]">Purge Node</button>
                </div>
            </div>
        </div>
    @endif
</div>

