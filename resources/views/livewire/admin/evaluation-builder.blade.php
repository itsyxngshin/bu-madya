<div class="min-h-screen bg-gray-100 p-4 md:p-6 font-sans text-gray-900 pb-32">

    <div class="max-w-5xl mx-auto">

        {{-- HEADER / ACTIONS --}}
        <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
            <div>
                <h1 class="text-xl md:text-2xl font-black text-gray-900 flex items-center gap-2">
                    @if($evaluation->exists) <span class="text-orange-500">Edit</span> Evaluation 
                        @if(auth()->user()->role?->role_name === 'administrator' && $evaluation->creator_id !== auth()->id())
                                <span class="ml-2 px-2 py-0.5 bg-blue-50 border border-blue-200 text-blue-700 text-[10px] uppercase tracking-widest font-bold rounded-full shadow-sm">
                                    Owner: {{ $evaluation->creator->name ?? 'System' }}
                                </span>
                            @endif
                    @else <span class="text-green-500">Create</span> Evaluation @endif
                </h1>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.evaluations.index') }}" class="hidden sm:inline-block px-4 py-2 bg-white border border-gray-300 text-gray-600 font-bold rounded-lg text-xs uppercase tracking-wider hover:bg-gray-50 transition">Cancel</a>

                @if($evaluation->exists)
                    {{-- Duplicate Button --}}
                    <button wire:click="duplicate" type="button" class="px-4 py-2 bg-blue-50 border border-blue-200 text-blue-700 font-bold rounded-lg text-xs uppercase tracking-wider hover:bg-blue-100 transition flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                        <span class="hidden sm:inline">Duplicate</span>
                    </button>

                    @if($evaluation->responses()->count() > 0)
                        <button type="button" onclick="confirmReset({{ $evaluation->responses()->count() }})" class="px-4 py-2 bg-red-50 border border-red-200 text-red-600 font-bold rounded-lg text-xs uppercase tracking-wider hover:bg-red-100 transition flex items-center gap-2">
                            Reset ({{ $evaluation->responses()->count() }})
                        </button>
                    @endif
                @endif

                <button wire:click="save" class="px-6 py-2 bg-gray-900 text-white font-bold rounded-lg shadow-lg hover:bg-gray-800 transition text-xs uppercase tracking-wider flex items-center gap-2">
                    Save
                </button>
            </div>
        </div>

        <div class="grid grid-cols-1 xl:grid-cols-12 gap-6">

            {{-- LEFT: CONFIGURATION --}}
            <div class="xl:col-span-4 space-y-4">
                <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-200">
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

                    {{-- THEME COLOR --}}
                    <div class="mb-5">
                        <label class="block text-xs font-bold text-gray-700 mb-1">Theme Color</label>
                        <div class="flex items-center gap-3">
                            <input type="color" wire:model.live="theme_color" class="w-10 h-10 rounded cursor-pointer border-0 p-0 bg-transparent">
                            <input type="text" wire:model.live="theme_color" class="w-full rounded-lg border-gray-200 bg-gray-50 text-xs font-mono p-2 uppercase" placeholder="#EA580C">
                        </div>
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
                    <div class="mb-4" x-data="{
                            insert(start, end) {
                                let el = this.$refs.editor;
                                let text = el.value;
                                let s = el.selectionStart;
                                let e = el.selectionEnd;
                                el.value = text.substring(0, s) + start + text.substring(s, e) + end + text.substring(e);
                                el.dispatchEvent(new Event('input'));
                                setTimeout(() => { el.focus(); el.setSelectionRange(s + start.length, e + start.length); }, 50);
                            }
                        }">

                        <div class="flex justify-between items-end mb-1">
                            <label class="block text-xs font-bold text-gray-700">Description</label>

                            {{-- Mini Toolbar --}}
                            <div class="flex bg-gray-100 rounded border border-gray-200">
                                <button type="button" @click="insert('**', '**')" class="px-2 py-1 hover:bg-gray-200 text-gray-600 font-black text-[9px] transition" title="Bold">B</button>
                                <button type="button" @click="insert('*', '*')" class="px-2 py-1 hover:bg-gray-200 text-gray-600 italic text-[9px] transition border-l border-gray-200" title="Italic">I</button>
                                <button type="button" @click="insert('~~', '~~')" class="px-2 py-1 hover:bg-gray-200 text-gray-600 line-through text-[9px] transition border-l border-gray-200" title="Strikethrough">S</button>
                                <button type="button" @click="insert('[', '](https://)')" class="px-2 py-1 hover:bg-gray-200 text-gray-600 font-bold text-[9px] transition border-l border-gray-200" title="Link">Link</button>
                            </div>
                        </div>

                        <textarea x-ref="editor" wire:model="description" rows="3" class="w-full rounded-lg border-gray-200 bg-gray-50 text-xs p-2 resize-y" placeholder="Use the toolbar to format your text..."></textarea>
                    </div>
                    <div class="flex items-center justify-between p-2 bg-gray-50 rounded-lg border border-gray-100">
                        <span class="text-[10px] font-bold text-gray-600 uppercase tracking-wide">Publish Active?</span>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" wire:model="is_active" class="sr-only peer">
                            <div class="w-9 h-5 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:bg-green-500 peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-4 after:w-4 after:transition-all"></div>
                        </label>
                    </div>
                </div>

                {{-- ========================================== --}}
                {{-- [NEW] E-CERTIFICATE BUILDER                --}}
                {{-- ========================================== --}}
                <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-200">

                    {{-- Delivery Mode Controls --}}
                    <div class="mb-5 bg-gray-50 p-3 rounded-lg border border-gray-100">
                        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Delivery Mode</label>
                        <div class="flex flex-col gap-2">
                            <label class="flex items-center gap-2 cursor-pointer group">
                                <input type="radio" wire:model.live="certDeliveryMode" value="automatic" class="text-orange-500 focus:ring-orange-500 w-4 h-4 border-gray-300">
                                <span class="text-xs font-bold text-gray-700 group-hover:text-gray-900 transition">Automatic (Instant Download)</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer group">
                                <input type="radio" wire:model.live="certDeliveryMode" value="manual" class="text-orange-500 focus:ring-orange-500 w-4 h-4 border-gray-300">
                                <span class="text-xs font-bold text-gray-700 group-hover:text-gray-900 transition">Manual (Admin Verification Required)</span>
                            </label>
                        </div>
                        
                        {{-- Helper Text using Alpine for smooth transitions --}}
                        <div class="mt-2 text-[9px] text-gray-500 font-medium italic border-t border-gray-200 pt-2">
                            <span x-show="$wire.certDeliveryMode === 'automatic'">Participants will download their certificate instantly upon submitting the form.</span>
                            <span x-show="$wire.certDeliveryMode === 'manual'" x-cloak>Certificates are held back. You must manually generate them from the Results Dashboard after verifying attendance.</span>
                        </div>
                    </div>
                    
                    <h3 class="text-sm font-black text-gray-900 mb-1">E-Certificate Builder</h3>
                    <p class="text-[10px] text-gray-500 mb-4 leading-tight">Upload a blank template and drag the name placeholder to automate certificates.</p>

                    {{-- File Upload --}}
                    <div class="mb-4">
                        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Upload Blank Template</label>
                        <input type="file" wire:model="newTemplate" class="block w-full text-xs text-gray-500 file:mr-3 file:py-1 file:px-3 file:rounded file:border-0 file:font-bold file:bg-orange-50 file:text-orange-700 hover:file:bg-orange-100 transition cursor-pointer">
                        <div wire:loading wire:target="newTemplate" class="text-[10px] text-orange-500 font-bold mt-1 animate-pulse">Uploading template...</div>
                    </div>

                    {{-- Controls --}}
                    <div class="grid grid-cols-2 gap-3 mb-4">
                        <div>
                            <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Text Color</label>
                            <input type="color" wire:model.live="certTextColor" class="h-8 w-full rounded border-gray-200 cursor-pointer p-0.5">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Size (px)</label>
                            <input type="number" wire:model.live="certFontSize" class="w-full bg-gray-50 border border-gray-200 rounded px-2 py-1.5 text-xs font-bold focus:ring-orange-500 focus:border-orange-500">
                        </div>
                    </div>

                    {{-- INTERACTIVE PREVIEW CANVAS --}}
                    @if($newTemplate || $evaluation->certificate_template)
                        @php
                            $imageUrl = $newTemplate ? $newTemplate->temporaryUrl() : asset('storage/' . $evaluation->certificate_template);
                        @endphp

                        <div class="relative w-full rounded-lg border-2 border-dashed border-gray-300 bg-gray-50 overflow-hidden select-none cursor-crosshair"
                             x-data="{
                                 isDragging: false,
                                 x: @entangle('certPosX'),
                                 y: @entangle('certPosY'),
                                 startDrag(e) { this.isDragging = true; },
                                 stopDrag() { this.isDragging = false; },
                                 onDrag(e) {
                                     if (!this.isDragging) return;
                                     let rect = this.$refs.canvas.getBoundingClientRect();
                                     let calcX = ((e.clientX - rect.left) / rect.width) * 100;
                                     let calcY = ((e.clientY - rect.top) / rect.height) * 100;
                                     this.x = Math.max(0, Math.min(100, calcX));
                                     this.y = Math.max(0, Math.min(100, calcY));
                                 }
                             }"
                             @mousemove.window="onDrag($event)"
                             @mouseup.window="stopDrag()"
                             x-ref="canvas">
                             
                            <img src="{{ $imageUrl }}" class="w-full h-auto pointer-events-none">

                            {{-- The Draggable Text Element --}}
                            <div @mousedown="startDrag($event)"
                                 class="absolute cursor-move group hover:ring-2 hover:ring-blue-500 rounded transition-shadow"
                                 :style="`top: ${y}%; left: ${x}%; transform: translate(-50%, -50%);`">
                                 
                                 <span class="font-bold border border-dashed border-transparent group-hover:border-blue-400 p-1 whitespace-nowrap block"
                                       :style="`color: ${$wire.certTextColor}; font-size: ${$wire.certFontSize / 5}px; line-height: 1;`"> {{-- Divided by 5 for mini preview scaling --}}
                                     [Participant Name]
                                 </span>
                            </div>
                        </div>
                        
                        <p class="text-center text-[9px] text-gray-400 font-bold mt-2 font-mono uppercase tracking-widest">
                            X: <span x-text="Math.round($wire.certPosX)"></span>% | Y: <span x-text="Math.round($wire.certPosY)"></span>%
                        </p>
                    @else
                        <div class="w-full h-32 bg-gray-50 border-2 border-dashed border-gray-200 rounded-lg flex items-center justify-center text-gray-400 font-bold text-[10px] uppercase tracking-widest">
                            No Template
                        </div>
                    @endif
                    
                    {{-- ========================================== --}}
                    {{-- [NEW] EMAIL CUSTOMIZATION SECTION          --}}
                    {{-- ========================================== --}}
                    <div class="mt-6 border-t border-gray-100 pt-5">
                        <div class="flex items-center justify-between mb-4">
                            <div>
                                <h4 class="text-xs font-bold text-gray-900">Email Delivery Template</h4>
                                <p class="text-[10px] text-gray-500 mt-0.5">Customize the email sent to participants with their certificate.</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" wire:model.live="certUseCustomEmail" class="sr-only peer">
                                <div class="w-9 h-5 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:bg-orange-500 peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-4 after:w-4 after:transition-all shadow-inner"></div>
                            </label>
                        </div>

                        @if($certUseCustomEmail)
                            <div class="space-y-3 bg-orange-50/50 p-4 rounded-xl border border-orange-100">
                                <div>
                                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Subject Line</label>
                                    <input type="text" wire:model="certEmailSubject" class="w-full bg-white border border-gray-200 rounded-lg px-3 py-2 text-xs font-bold focus:ring-orange-500 focus:border-orange-500 shadow-sm" placeholder="e.g., Your Event Certificate">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Email Message</label>
                                    <textarea wire:model="certEmailBody" rows="4" class="w-full bg-white border border-gray-200 rounded-lg px-3 py-2 text-xs focus:ring-orange-500 focus:border-orange-500 resize-y shadow-sm" placeholder="Type your message here..."></textarea>
                                    
                                    {{-- Dynamic Placeholders Helper --}}
                                    <div class="mt-2 flex items-center gap-2">
                                        <span class="text-[9px] font-bold text-gray-400 uppercase tracking-widest">Smart Tags:</span>
                                        <span class="text-[9px] font-bold text-orange-600 bg-orange-100 px-1.5 py-0.5 rounded cursor-help" title="Will be replaced by the respondent's name">[Name]</span>
                                        <span class="text-[9px] font-bold text-orange-600 bg-orange-100 px-1.5 py-0.5 rounded cursor-help" title="Will be replaced by the event/form title">[Event]</span>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="bg-gray-50 p-4 rounded-xl border border-gray-200 border-dashed text-center flex flex-col items-center justify-center">
                                <svg class="w-5 h-5 text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                <p class="text-xs font-bold text-gray-500">Using the Generic System Template</p>
                                <p class="text-[9px] text-gray-400 mt-1 uppercase tracking-widest font-medium">Standard automated message will be sent.</p>
                            </div>
                        @endif
                    </div>
                    
                    @if($newTemplate)
                        <button wire:click="saveCertificateSettings" class="w-full mt-4 py-2 bg-gray-900 text-white font-bold rounded-lg shadow hover:bg-orange-600 transition text-[10px] uppercase tracking-widest flex items-center justify-center gap-1">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                            Save Template
                        </button>
                    @endif
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
                    <button wire:click="addQuestion('file')" class="px-2 py-1.5 bg-blue-600 hover:bg-blue-700 rounded-md text-[10px] uppercase font-bold transition shadow-sm ml-1">File Bin</button>
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

                            @if($question['type'] === 'page_break')
                                <div class="group relative bg-red-50/80 border {{ $isActive ? 'border-red-400 shadow-md ring-1 ring-red-400' : 'border-red-100 hover:border-red-200' }} rounded-xl p-2 flex items-center justify-between transition-all cursor-pointer" wire:click="setActiveQuestion({{ $index }})">
                                    
                                    <div class="drag-handle w-6 flex items-center justify-center cursor-move text-red-300 hover:text-red-500">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"></path></svg>
                                    </div>
                                    
                                    <div class="flex-1 flex flex-col sm:flex-row sm:items-center gap-3 px-4">
                                        <div class="hidden sm:block h-px bg-red-300 flex-1 opacity-70"></div>
                                        
                                        <span class="text-[9px] font-black uppercase tracking-widest text-red-600 bg-white px-2 py-0.5 rounded border border-red-200 shadow-sm w-max">Page Break</span>
                                        
                                        @if($isActive)
                                            <div class="flex items-center gap-2">
                                                <span class="text-[9px] font-bold text-gray-500 uppercase tracking-widest">After page:</span>
                                                <select wire:model="questions.{{ $index }}.options.0.jump" class="text-[10px] font-bold border-red-200 rounded-lg py-1 px-2 bg-white text-gray-700 shadow-sm focus:ring-red-500 focus:border-red-500 w-40 cursor-pointer">
                                                    <option value="">Continue to next page</option>
                                                    <option value="submit">Submit Form</option>
                                                    @foreach($this->sections as $section)
                                                        @if($section['order'] > $question['order']) 
                                                            <option value="{{ $section['id'] }}">Go to: {{ Str::limit($section['title'], 15) }}</option> 
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>
                                        @endif
                                        
                                        <div class="hidden sm:block h-px bg-red-300 flex-1 opacity-70"></div>
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

                                    {{-- ALPINE WRAPPER FOR SMART TOOLBAR --}}
                                    <div class="pl-6" x-data="{
                                            activeField: 'qText',
                                            insert(start, end) {
                                                let el = this.$refs[this.activeField];
                                                if(!el) return;
                                                let text = el.value;
                                                let s = el.selectionStart;
                                                let e = el.selectionEnd;
                                                el.value = text.substring(0, s) + start + text.substring(s, e) + end + text.substring(e);
                                                el.dispatchEvent(new Event('input'));
                                                setTimeout(() => { el.focus(); el.setSelectionRange(s + start.length, e + start.length); }, 50);
                                            }
                                        }">

                                        {{-- ITEM HEADER & SMART TOOLBAR --}}
                                        <div class="flex justify-between items-start mb-2">

                                            <div class="flex flex-col gap-2 flex-1 mr-2">

                                                {{-- The Smart Toolbar --}}
                                                <div x-show="$wire.activeQuestionIndex === {{ $index }}" class="flex bg-orange-50 rounded border border-orange-200 w-max overflow-hidden shadow-sm" style="display: none;">
                                                    <button type="button" @click="insert('**', '**')" class="px-2 py-1 hover:bg-orange-100 text-orange-700 font-black text-[10px] transition" title="Bold">B</button>
                                                    <button type="button" @click="insert('*', '*')" class="px-2 py-1 hover:bg-orange-100 text-orange-700 italic text-[10px] transition border-l border-orange-200" title="Italic">I</button>
                                                    <button type="button" @click="insert('~~', '~~')" class="px-2 py-1 hover:bg-orange-100 text-orange-700 line-through text-[10px] transition border-l border-orange-200" title="Strikethrough">S</button>
                                                    <button type="button" @click="insert('[', '](https://)')" class="px-2 py-1 hover:bg-orange-100 text-orange-700 font-bold text-[10px] transition border-l border-orange-200" title="Link">Link</button>
                                                </div>

                                                @if($isSection)
                                                    {{-- SECTION TITLE --}}
                                                    <textarea x-ref="qText" @focus="activeField = 'qText'" wire:model="questions.{{ $index }}.question_text" class="w-full text-base font-black text-gray-800 border-0 bg-transparent placeholder-orange-300 focus:ring-0 p-0 resize-y" rows="2" placeholder="Type Section Title"></textarea>
                                                @else
                                                    <span class="text-[9px] font-bold uppercase tracking-wide text-gray-400 bg-gray-100 px-1.5 py-0.5 rounded w-max">{{ ucfirst($question['type']) }}</span>
                                                @endif
                                            </div>

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
                                            {{-- QUESTION TITLE --}}
                                            <textarea x-ref="qText" @focus="activeField = 'qText'" wire:model="questions.{{ $index }}.question_text" class="w-full text-sm font-bold border-0 border-b border-gray-100 focus:border-orange-500 focus:ring-0 bg-transparent transition mb-2 p-0 resize-y" rows="2" placeholder="Enter question..."></textarea>

                                            {{-- Description & Image Toggles --}}
                                            @if($isActive || $question['description'] || $question['image_path'] || (isset($question['new_image']) && $question['new_image']))

                                                {{-- QUESTION DESCRIPTION --}}
                                                <textarea x-ref="qDesc" @focus="activeField = 'qDesc'" wire:model="questions.{{ $index }}.description" class="w-full text-xs text-gray-500 border-0 border-b border-gray-50 focus:border-orange-300 focus:ring-0 bg-transparent transition mb-3 p-0 placeholder-gray-300 resize-y" rows="2" placeholder="Help text / description"></textarea>

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
                                            {{-- SECTION DESCRIPTION --}}
                                            <textarea x-ref="qDesc" @focus="activeField = 'qDesc'" wire:model="questions.{{ $index }}.description" class="w-full text-xs text-gray-500 border-0 bg-transparent placeholder-orange-300/50 focus:ring-0 p-0 resize-y" rows="2" placeholder="Section description..."></textarea>
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