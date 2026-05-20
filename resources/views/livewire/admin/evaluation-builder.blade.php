<div class="min-h-screen bg-gray-50 p-4 md:p-8 font-sans text-gray-900 pb-32"
     x-data="{ sectionToDelete: @entangle('sectionToDeleteIndex') }"
     x-effect="document.body.classList.toggle('overflow-hidden', sectionToDelete !== null)">

    <div class="max-w-6xl mx-auto">

        {{-- ========================================== --}}
        {{-- HEADER / ACTIONS --}}
        {{-- ========================================== --}}
        <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
            <div>
                <h1 class="text-2xl md:text-3xl font-black text-gray-900 flex items-center gap-2 tracking-tight">
                    @if($evaluation->exists)
                        <span class="text-orange-600">Edit</span> Evaluation
                        @if(auth()->user()->role?->role_name === 'administrator' && $evaluation->creator_id !== auth()->id())
                            <span class="ml-3 px-3 py-1 bg-blue-50 border border-blue-200 text-blue-700 text-xs uppercase tracking-wider font-bold rounded-full shadow-sm">
                                Owner: {{ $evaluation->creator->name ?? 'System' }}
                            </span>
                        @endif
                    @else
                        <span class="text-green-600">Create</span> Evaluation
                    @endif
                </h1>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.evaluations.index') }}" class="hidden sm:inline-block px-5 py-2.5 bg-white border border-gray-300 text-gray-700 font-bold rounded-xl text-sm hover:bg-gray-50 transition shadow-sm">Cancel</a>

                @if($evaluation->exists)
                    <button wire:click="duplicate" type="button" class="px-4 py-2.5 bg-blue-50 border border-blue-200 text-blue-700 font-bold rounded-xl text-sm hover:bg-blue-100 transition flex items-center gap-2 shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                        <span class="hidden sm:inline">Duplicate</span>
                    </button>

                    @if($evaluation->responses()->count() > 0)
                        <button type="button" onclick="confirmReset({{ $evaluation->responses()->count() }})" class="px-4 py-2.5 bg-red-50 border border-red-200 text-red-700 font-bold rounded-xl text-sm hover:bg-red-100 transition flex items-center gap-2 shadow-sm">
                            Reset ({{ $evaluation->responses()->count() }})
                        </button>
                    @endif
                @endif

                <button wire:click="save" class="px-8 py-2.5 bg-gray-900 text-white font-bold rounded-xl shadow-lg hover:bg-gray-800 transition text-sm flex items-center gap-2">
                    Save Changes
                </button>
            </div>
        </div>

        <div class="grid grid-cols-1 xl:grid-cols-12 gap-8">

            {{-- ========================================== --}}
            {{-- LEFT: CONFIGURATION --}}
            {{-- ========================================== --}}
            <div class="xl:col-span-4 space-y-6">

                {{-- Main Settings Card --}}
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-200">
                    <h2 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-5">General Settings</h2>

                    <div class="mb-5">
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Header Image</label>
                        @if($header_image)
                            <img src="{{ $header_image->temporaryUrl() }}" class="w-full h-32 object-cover rounded-xl mb-3 border-2 border-orange-500 shadow-sm">
                        @elseif($existing_header_image)
                            <img src="{{ asset('storage/'.$existing_header_image) }}" class="w-full h-32 object-cover rounded-xl mb-3 border border-gray-200 shadow-sm">
                        @endif
                        <label class="cursor-pointer w-full bg-gray-50 hover:bg-gray-100 text-gray-700 px-4 py-3 rounded-xl text-sm font-semibold transition flex items-center justify-center border-2 border-dashed border-gray-300">
                            <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            Upload Header Image <input type="file" wire:model="header_image" class="hidden" accept="image/*">
                        </label>
                    </div>

                    <div class="mb-5">
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Theme Color</label>
                        <div class="flex items-center gap-3">
                            <input type="color" wire:model.live="theme_color" class="w-12 h-12 rounded-xl cursor-pointer border-0 p-0 bg-transparent shrink-0">
                            <input type="text" wire:model.live="theme_color" class="w-full rounded-xl border-gray-200 bg-gray-50 text-sm font-mono p-3 uppercase focus:ring-orange-500 focus:border-orange-500" placeholder="#EA580C">
                        </div>
                    </div>

                    <div class="mb-5">
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Title <span class="text-red-500">*</span></label>
                        <input wire:model.live="title" type="text" class="w-full rounded-xl border-gray-200 bg-gray-50 text-base font-medium p-3 focus:bg-white focus:ring-orange-500 focus:border-orange-500 transition-colors">
                        @error('title') <span class="text-xs text-red-500 font-semibold block mt-1.5">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-5">
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Link to Project</label>
                        <select wire:model="project_id" class="w-full rounded-xl border-gray-200 bg-gray-50 text-sm p-3 focus:bg-white focus:ring-orange-500 focus:border-orange-500 transition-colors cursor-pointer">
                            <option value="">-- No Project Linked --</option>
                            @foreach($available_projects as $proj)
                                <option value="{{ $proj->id }}">{{ $proj->title }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-5">
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">URL Slug</label>
                        <div class="flex gap-2">
                            <input wire:model="slug" type="text" class="w-full rounded-xl border-gray-200 bg-gray-50 text-sm font-mono p-3 focus:bg-white focus:ring-orange-500 focus:border-orange-500 transition-colors" placeholder="auto-generated-slug">
                            <button wire:click="generateRandomSlug" class="px-3 py-2 bg-gray-100 hover:bg-gray-200 rounded-xl border border-gray-200 transition-colors shrink-0" title="Randomize Slug">
                                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                            </button>
                        </div>
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
                                this.$nextTick(() => {
                                    el.style.height = 'auto';
                                    el.style.height = el.scrollHeight + 'px';
                                });
                            }
                        }">
                        <div class="flex justify-between items-end mb-1.5">
                            <label class="block text-sm font-semibold text-gray-700">Description</label>
                            <div class="flex bg-gray-100 rounded-lg border border-gray-200 overflow-hidden">
                                <button type="button" @click="insert('**', '**')" class="px-2.5 py-1.5 hover:bg-gray-200 text-gray-700 font-black text-xs transition" title="Bold">B</button>
                                <button type="button" @click="insert('*', '*')" class="px-2.5 py-1.5 hover:bg-gray-200 text-gray-700 italic text-xs transition border-l border-gray-200" title="Italic">I</button>
                                <button type="button" @click="insert('~~', '~~')" class="px-2.5 py-1.5 hover:bg-gray-200 text-gray-700 line-through text-xs transition border-l border-gray-200" title="Strikethrough">S</button>
                                <button type="button" @click="insert('[', '](https://)')" class="px-2.5 py-1.5 hover:bg-gray-200 text-gray-700 font-semibold text-xs transition border-l border-gray-200" title="Link">Link</button>
                            </div>
                        </div>
                        <textarea x-ref="editor" wire:model="description" rows="2"
                                  x-init="$nextTick(() => resize($el))" @input="resize($el)"
                                  class="w-full rounded-xl border-gray-200 bg-gray-50 text-sm p-3 overflow-hidden focus:bg-white focus:ring-orange-500 focus:border-orange-500 transition-colors"
                                  placeholder="Evaluation instructions or details..."></textarea>
                    </div>

                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl border border-gray-200">
                        <div>
                            <span class="text-sm font-semibold text-gray-900 block">Publish Active</span>
                            <span class="text-xs text-gray-500">Make this evaluation live</span>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" wire:model="is_active" class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:bg-green-500 peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all shadow-inner"></div>
                        </label>
                    </div>
                </div>

                {{-- THE E-CERTIFICATE MASTER SUITE --}}
                <div x-data="{ expanded: false }" class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                    <button @click="expanded = !expanded" type="button" class="w-full flex items-center justify-between p-6 hover:bg-gray-50 transition-colors">
                        <div class="flex flex-col items-start">
                            <h3 class="text-base font-bold text-gray-900 leading-tight">E-Certificate Builder</h3>
                            <p class="text-xs text-gray-500 mt-1">Automate completion certificates.</p>
                        </div>
                        <div class="flex items-center gap-3">
                            @if($evaluation->certificate_template)
                                <span class="px-2.5 py-1 bg-green-50 text-green-700 text-xs font-semibold rounded-lg border border-green-100">Active</span>
                            @endif
                            <div class="p-2 bg-orange-50 text-orange-600 rounded-xl transition-transform duration-200" :class="expanded ? 'rotate-180' : ''">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </div>
                        </div>
                    </button>

                    <div x-show="expanded" x-collapse x-cloak>
                        <div class="p-6 border-t border-gray-100 space-y-6 bg-gray-50/30">

                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-3">Delivery Mode</label>
                                <div class="grid grid-cols-1 gap-3">
                                    <label class="flex flex-col gap-1 p-3 border rounded-xl cursor-pointer transition-colors bg-white" :class="$wire.certDeliveryMode === 'automatic' ? 'border-orange-500 ring-1 ring-orange-500 shadow-sm' : 'border-gray-200 hover:border-orange-300'">
                                        <div class="flex items-center gap-2">
                                            <input type="radio" wire:model.live="certDeliveryMode" value="automatic" class="text-orange-500 focus:ring-orange-500 w-4 h-4 border-gray-300">
                                            <span class="text-sm font-semibold text-gray-900">Automatic (Instant)</span>
                                        </div>
                                        <span class="text-xs text-gray-500 pl-6">Participants download instantly upon submission.</span>
                                    </label>
                                    <label class="flex flex-col gap-1 p-3 border rounded-xl cursor-pointer transition-colors bg-white" :class="$wire.certDeliveryMode === 'manual' ? 'border-orange-500 ring-1 ring-orange-500 shadow-sm' : 'border-gray-200 hover:border-orange-300'">
                                        <div class="flex items-center gap-2">
                                            <input type="radio" wire:model.live="certDeliveryMode" value="manual" class="text-orange-500 focus:ring-orange-500 w-4 h-4 border-gray-300">
                                            <span class="text-sm font-semibold text-gray-900">Manual (Verification)</span>
                                        </div>
                                        <span class="text-xs text-gray-500 pl-6">Generate from dashboard after verifying attendance.</span>
                                    </label>
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Blank Template (PNG/JPG)</label>
                                <input type="file" wire:model="newTemplate" class="block w-full text-sm text-gray-600 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-orange-50 file:text-orange-700 hover:file:bg-orange-100 transition cursor-pointer bg-white border border-gray-200 rounded-xl p-1">
                                <div wire:loading wire:target="newTemplate" class="text-xs text-orange-600 font-semibold mt-2 animate-pulse">Uploading template...</div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                                {{-- 1. Font Family --}}
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5">Font Family</label>
                                    <select wire:model.live="certFontFamily" class="w-full bg-white border border-gray-200 rounded-xl px-3 py-2 text-sm font-medium focus:ring-orange-500 focus:border-orange-500 cursor-pointer shadow-sm">
                                        <option value="Montserrat">Montserrat</option>
                                        <option value="Arial">Arial</option>
                                        <option value="Times New Roman">Times Roman</option>
                                        <option value="Playfair Display">Playfair Display</option>
                                        <option value="Georgia">Georgia</option>
                                        <option value="Roboto">Roboto</option>
                                        <option value="Open Sans">Open Sans</option>
                                        <option value="Lato">Lato</option>
                                        <option value="Merriweather">Merriweather</option>
                                        <option value="Courier New">Courier New</option>
                                        <option value="Verdana">Verdana</option>
                                    </select>
                                </div>

                                {{-- 2. Text Alignment Toggle --}}
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5">Alignment</label>
                                    <div class="flex bg-white border border-gray-200 rounded-xl p-1 shadow-sm h-[38px]">
                                        <button type="button" wire:click="$set('certTextAlign', 'left')" class="flex-1 flex items-center justify-center rounded-lg transition-colors" :class="$wire.certTextAlign === 'left' ? 'bg-orange-50 text-orange-600 font-bold' : 'text-gray-500 hover:bg-gray-50'">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h10M4 18h16"></path></svg>
                                        </button>
                                        <button type="button" wire:click="$set('certTextAlign', 'center')" class="flex-1 flex items-center justify-center rounded-lg transition-colors" :class="$wire.certTextAlign === 'center' ? 'bg-orange-50 text-orange-600 font-bold' : 'text-gray-500 hover:bg-gray-50'">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M7 12h10M4 18h16"></path></svg>
                                        </button>
                                        <button type="button" wire:click="$set('certTextAlign', 'right')" class="flex-1 flex items-center justify-center rounded-lg transition-colors" :class="$wire.certTextAlign === 'right' ? 'bg-orange-50 text-orange-600 font-bold' : 'text-gray-500 hover:bg-gray-50'">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M10 12h10M4 18h16"></path></svg>
                                        </button>
                                    </div>
                                </div>

                                {{-- 3. Text Color --}}
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5">Text Color</label>
                                    <input type="color" wire:model.live="certTextColor" class="h-[38px] w-full rounded-xl border border-gray-200 cursor-pointer p-1 bg-white shadow-sm">
                                </div>

                                {{-- 4. Size --}}
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5">Size (px)</label>
                                    <input type="number" wire:model.live="certFontSize" class="w-full h-[38px] bg-white border border-gray-200 rounded-xl px-3 py-2 text-sm font-medium focus:ring-orange-500 focus:border-orange-500 shadow-sm">
                                </div>
                            </div>

                            @if($newTemplate || $evaluation->certificate_template)
                                @php
                                    $imageUrl = $newTemplate ? $newTemplate->temporaryUrl() : asset('storage/' . $evaluation->certificate_template);
                                @endphp

                                <div x-data="{
                                        isDragging: false,
                                        isResizing: false,
                                        startX: 0, startY: 0, startFontSize: 16,
                                        x: @entangle('certPosX'),
                                        y: @entangle('certPosY'),

                                        startDrag(e) { this.isDragging = true; },
                                        startResize(e) {
                                            this.isResizing = true;
                                            this.startX = e.touches ? e.touches[0].clientX : e.clientX;
                                            this.startY = e.touches ? e.touches[0].clientY : e.clientY;
                                            this.startFontSize = parseFloat(this.$wire.certFontSize) || 16;
                                        },
                                        stopAll() {
                                            this.isDragging = false;
                                            this.isResizing = false;
                                        },
                                        handleMove(e) {
                                            if (this.isDragging) {
                                                let clientX = e.touches ? e.touches[0].clientX : e.clientX;
                                                let clientY = e.touches ? e.touches[0].clientY : e.clientY;
                                                let rect = this.$refs.miniCanvas.getBoundingClientRect();
                                                let calcX = ((clientX - rect.left) / rect.width) * 100;
                                                let calcY = ((clientY - rect.top) / rect.height) * 100;
                                                this.x = Math.max(0, Math.min(100, calcX));
                                                this.y = Math.max(0, Math.min(100, calcY));
                                            }
                                            if (this.isResizing) {
                                                let clientX = e.touches ? e.touches[0].clientX : e.clientX;
                                                let clientY = e.touches ? e.touches[0].clientY : e.clientY;
                                                let delta = Math.max(clientX - this.startX, clientY - this.startY);
                                                let newSize = Math.max(10, this.startFontSize + (delta * 1.5));
                                                this.$wire.certFontSize = Math.round(newSize);
                                            }
                                        }
                                     }">

                                    <div class="w-full bg-gray-100 border-2 border-dashed border-gray-300 rounded-xl flex items-center justify-center p-4 relative group">

                                        <div class="relative inline-block select-none cursor-crosshair shadow-md touch-none overflow-hidden isolate z-10"
                                             @mousemove.window="handleMove($event)"
                                             @touchmove.window="handleMove($event)"
                                             @mouseup.window="stopAll()"
                                             @touchend.window="stopAll()"
                                             x-ref="miniCanvas">

                                            <img src="{{ $imageUrl }}" class="max-h-80 w-auto h-auto pointer-events-none block bg-white rounded">

                                            {{-- DYNAMIC ALIGNMENT APPLIED HERE --}}
                                            <div @mousedown="startDrag($event)" @touchstart.prevent="startDrag($event)"
                                                 class="absolute cursor-move group/text rounded z-20"
                                                 :style="`top: ${y}%; left: ${x}%; transform: translate(${$wire.certTextAlign === 'left' ? '0%' : ($wire.certTextAlign === 'right' ? '-100%' : '-50%')}, -50%);`">

                                                <div class="relative inline-block">
                                                    <span class="font-bold border border-dashed border-transparent group-hover/text:border-blue-400 p-2 whitespace-nowrap block bg-transparent rounded"
                                                          :style="`color: ${$wire.certTextColor}; font-size: ${$wire.certFontSize / 5}px; line-height: 1; font-family: ${$wire.certFontFamily}; text-align: ${$wire.certTextAlign};`">
                                                        [Participant Name]
                                                    </span>

                                                    <div @mousedown.stop="startResize($event)" @touchstart.stop.prevent="startResize($event)"
                                                         class="absolute -bottom-3 -right-3 w-7 h-7 bg-white rounded-full cursor-nwse-resize shadow-lg flex items-center justify-center border border-gray-200 z-30 transition-transform active:scale-95 sm:opacity-0 sm:group-hover/text:opacity-100"
                                                         :class="{ 'opacity-100 scale-110': isResizing }">
                                                         <svg class="w-4 h-4 text-orange-500 transform rotate-90" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4"></path></svg>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <p class="text-center text-xs text-gray-500 mt-3 font-mono bg-gray-100 py-1.5 rounded-lg border border-gray-200 flex justify-center gap-4">
                                        <span>X: <span class="font-bold text-gray-800" x-text="Math.round($wire.certPosX)"></span>%</span>
                                        <span class="text-gray-300">|</span>
                                        <span>Y: <span class="font-bold text-gray-800" x-text="Math.round($wire.certPosY)"></span>%</span>
                                        <span class="text-gray-300">|</span>
                                        <span>Size: <span class="font-bold text-gray-800" x-text="$wire.certFontSize"></span>px</span>
                                        <span class="text-gray-300">|</span>
                                        <span class="uppercase tracking-widest text-[10px] text-gray-600 mt-0.5" x-text="$wire.certTextAlign"></span>
                                    </p>
                                </div>
                            @else
                                <div class="w-full h-32 bg-gray-50 border-2 border-dashed border-gray-200 rounded-xl flex items-center justify-center text-gray-400 font-bold text-xs uppercase tracking-wider">
                                    No Template Uploaded
                                </div>
                            @endif

                            <div class="bg-orange-50/50 p-5 rounded-2xl border border-orange-100">
                                <h4 class="text-sm font-bold text-gray-900 mb-1">Field Mapping</h4>
                                <p class="text-xs text-gray-500 mb-4">Link your form questions to the certificate system.</p>

                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">Name Question</label>
                                        <select wire:model="certNameQuestionId" class="w-full bg-white border border-gray-200 rounded-xl px-4 py-2.5 text-sm font-medium focus:ring-orange-500 focus:border-orange-500 shadow-sm cursor-pointer">
                                            <option value="">-- Select Question --</option>
                                            @foreach(collect($questions)->whereIn('type', ['text', 'textarea']) as $q)
                                                <option value="{{ $q['id'] ?? $q['temp_id'] }}">{{ \Illuminate\Support\Str::limit($q['question_text'], 50) ?: 'Untitled Question' }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">Email Question</label>
                                        <select wire:model="certEmailQuestionId" class="w-full bg-white border border-gray-200 rounded-xl px-4 py-2.5 text-sm font-medium focus:ring-orange-500 focus:border-orange-500 shadow-sm cursor-pointer">
                                            <option value="">-- Select Question --</option>
                                            @foreach(collect($questions)->whereIn('type', ['text', 'textarea']) as $q)
                                                <option value="{{ $q['id'] ?? $q['temp_id'] }}">{{ \Illuminate\Support\Str::limit($q['question_text'], 50) ?: 'Untitled Question' }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="border-t border-gray-200 pt-6">
                                <div class="flex items-center justify-between mb-4">
                                    <div>
                                        <h4 class="text-sm font-bold text-gray-900">Custom Email Delivery</h4>
                                        <p class="text-xs text-gray-500 mt-0.5">Customize the email sent with the certificate.</p>
                                    </div>
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" wire:model.live="certUseCustomEmail" class="sr-only peer">
                                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:bg-orange-500 peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all shadow-inner"></div>
                                    </label>
                                </div>

                                @if($certUseCustomEmail)
                                    <div class="space-y-4 bg-orange-50/50 p-5 rounded-2xl border border-orange-100">
                                        <div>
                                            <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">Subject Line</label>
                                            <input type="text" wire:model="certEmailSubject" class="w-full bg-white border border-gray-200 rounded-xl px-4 py-2.5 text-sm font-medium focus:ring-orange-500 focus:border-orange-500 shadow-sm" placeholder="e.g., Your Event Certificate">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">Email Message</label>
                                            <textarea wire:model="certEmailBody" rows="4" class="w-full bg-white border border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-orange-500 focus:border-orange-500 resize-y shadow-sm" placeholder="Type your message here..."></textarea>
                                            <div class="mt-3 flex items-center gap-2">
                                                <span class="text-xs font-bold text-gray-500 uppercase tracking-wider">Variables:</span>
                                                <span class="text-xs font-bold text-orange-700 bg-orange-100/80 px-2 py-1 rounded-md cursor-help border border-orange-200" title="Will be replaced by respondent's name">[Name]</span>
                                                <span class="text-xs font-bold text-orange-700 bg-orange-100/80 px-2 py-1 rounded-md cursor-help border border-orange-200" title="Will be replaced by event title">[Event]</span>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <div class="bg-gray-50 p-5 rounded-2xl border border-gray-200 border-dashed text-center flex flex-col items-center justify-center">
                                        <svg class="w-6 h-6 text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                        <p class="text-sm font-semibold text-gray-700">Using Default System Template</p>
                                        <p class="text-xs text-gray-500 mt-1">A standard automated message will be sent.</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            {{-- ========================================== --}}
            {{-- RIGHT: BUILDER (The Canvas) --}}
            {{-- ========================================== --}}
            <div class="xl:col-span-8 relative">

                <div class="sticky top-6 z-[100] flex justify-center mb-8 pointer-events-none px-4 sm:px-0">
                    <div class="pointer-events-auto relative w-full max-w-sm sm:max-w-md md:max-w-2xl" x-data="{ open: false }">
                        <button @click="open = !open" @click.outside="open = false" type="button" class="w-full flex items-center justify-center gap-3 py-3.5 bg-gray-900 text-white font-bold rounded-full shadow-2xl hover:bg-gray-800 transition ring-4 ring-white/80 group">
                            <svg class="w-5 h-5 transition-transform duration-300" :class="open ? 'rotate-45 text-orange-400' : 'text-gray-300 group-hover:text-white'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                            <span class="text-sm uppercase tracking-wider" x-text="open ? 'Close Menu' : 'Add Element'"></span>
                        </button>

                        <div x-show="open" x-cloak
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 translate-y-4 scale-95"
                             x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                             x-transition:leave="transition ease-in duration-150"
                             x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                             x-transition:leave-end="opacity-0 translate-y-4 scale-95"
                             class="absolute top-full left-0 mt-4 w-full bg-white rounded-2xl shadow-2xl border border-gray-100 overflow-hidden flex flex-col z-50">

                            <div class="px-6 py-4 bg-gray-50/80 border-b border-gray-100">
                                <span class="text-xs font-bold uppercase tracking-wider text-gray-500">Standard Inputs</span>
                            </div>

                            <div class="p-3 grid grid-cols-2 gap-2">
                                <button wire:click="addQuestion('text'); open = false" class="w-full flex items-center gap-3 px-4 py-3 text-left text-sm font-semibold text-gray-700 hover:bg-orange-50 hover:text-orange-700 rounded-xl transition group/item">
                                    <svg class="w-5 h-5 shrink-0 text-gray-400 group-hover/item:text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"></path></svg>
                                    <span class="leading-tight">Text Question</span>
                                </button>
                                <button wire:click="addQuestion('radio'); open = false" class="w-full flex items-center gap-3 px-4 py-3 text-left text-sm font-semibold text-gray-700 hover:bg-orange-50 hover:text-orange-700 rounded-xl transition group/item">
                                    <svg class="w-5 h-5 shrink-0 text-gray-400 group-hover/item:text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    <span class="leading-tight">Single Choice</span>
                                </button>
                                <button wire:click="addQuestion('checkbox'); open = false" class="w-full flex items-center gap-3 px-4 py-3 text-left text-sm font-semibold text-gray-700 hover:bg-orange-50 hover:text-orange-700 rounded-xl transition group/item">
                                    <svg class="w-5 h-5 shrink-0 text-gray-400 group-hover/item:text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                                    <span class="leading-tight">Multiple Choice</span>
                                </button>
                                <button wire:click="addQuestion('dropdown'); open = false" class="w-full flex items-center gap-3 px-4 py-3 text-left text-sm font-semibold text-gray-700 hover:bg-orange-50 hover:text-orange-700 rounded-xl transition group/item">
                                    <svg class="w-5 h-5 shrink-0 text-gray-400 group-hover/item:text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path></svg>
                                    <span class="leading-tight">Dropdown Menu</span>
                                </button>
                                <button wire:click="addQuestion('likert'); open = false" class="col-span-2 w-full flex items-center justify-center gap-3 px-4 py-3 text-center text-sm font-semibold text-gray-700 hover:bg-orange-50 hover:text-orange-700 rounded-xl transition group/item border border-dashed border-gray-200 hover:border-orange-200 mt-1">
                                    <svg class="w-5 h-5 shrink-0 text-gray-400 group-hover/item:text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    <span class="leading-tight">Rating Scale (Likert)</span>
                                </button>
                            </div>

                            <div class="px-6 py-4 bg-gray-50/80 border-y border-gray-100 mt-1">
                                <span class="text-xs font-bold uppercase tracking-wider text-gray-500">Advanced & Layout</span>
                            </div>

                            <div class="p-3 grid grid-cols-2 gap-2">
                                <button wire:click="addQuestion('file'); open = false" class="w-full flex items-center gap-3 px-4 py-3 text-left text-sm font-semibold text-gray-700 hover:bg-blue-50 hover:text-blue-700 rounded-xl transition group/item">
                                    <svg class="w-5 h-5 shrink-0 text-gray-400 group-hover/item:text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                                    <span class="leading-tight">File Upload</span>
                                </button>
                                <button wire:click="addQuestion('section'); open = false" class="w-full flex items-center gap-3 px-4 py-3 text-left text-sm font-semibold text-gray-700 hover:bg-gray-100 hover:text-gray-900 rounded-xl transition group/item">
                                    <svg class="w-5 h-5 shrink-0 text-gray-400 group-hover/item:text-gray-900" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"></path></svg>
                                    <span class="leading-tight">New Section</span>
                                </button>
                                <button wire:click="addQuestion('page_break'); open = false" class="col-span-2 w-full flex items-center justify-center gap-3 px-4 py-3 text-center text-sm font-semibold text-gray-700 hover:bg-red-50 hover:text-red-700 rounded-xl transition group/item border border-dashed border-gray-200 hover:border-red-200 mt-1">
                                    <svg class="w-5 h-5 shrink-0 text-gray-400 group-hover/item:text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                                    <span class="leading-tight">Page Break</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ========================================== --}}
                {{-- UNIFIED SORTABLE LIST CONTAINER --}}
                {{-- ========================================== --}}
                <div class="bg-transparent min-h-[400px] pb-20 relative"
                     x-data="{
                        initSortable() {
                            this.sortable = new Sortable(this.$el, {
                                animation: 200,
                                handle: '.drag-handle',
                                ghostClass: 'opacity-40',
                                scroll: true,
                                forceFallback: true,
                                scrollSensitivity: 100,
                                scrollSpeed: 25,
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

                    @php
                        $inSection = false;
                    @endphp

                    @foreach($questions as $index => $question)

                        @php
                            $qKey = $question['temp_id'];

                            if ($question['type'] === 'section') $inSection = true;
                            elseif ($question['type'] === 'page_break') $inSection = false;

                            $nextType = $index < count($questions) - 1 ? $questions[$index + 1]['type'] : null;
                            $isLastInSection = $inSection && in_array($nextType, ['page_break', 'section', null]);

                            $isSectionHeader = $question['type'] === 'section';
                            $isPageBreak = $question['type'] === 'page_break';
                            $isEnclosedItem = $inSection && !$isSectionHeader && !$isPageBreak;
                            $isActive = ($activeQuestionIndex === $index);

                            $classes = 'group/card relative transition-all duration-200 cursor-text p-5 sm:p-6 ';

                            if ($isSectionHeader) {
                                $classes .= $isLastInSection
                                    ? 'rounded-3xl border border-orange-200 bg-orange-50/50 mb-6 shadow-sm '
                                    : 'rounded-t-3xl border border-b-0 border-orange-200 bg-orange-50/80 mb-0 pt-6 pb-4 ';
                            } elseif ($isEnclosedItem) {
                                $classes .= $isLastInSection
                                    ? 'rounded-b-3xl border border-t-0 border-orange-200 bg-white mb-6 shadow-sm pt-4 pb-6 '
                                    : 'rounded-none border-x border-x-orange-200 bg-white mb-0 py-4 ';
                            } elseif ($isPageBreak) {
                                $classes .= 'rounded-2xl border border-red-200 bg-red-50/80 shadow-sm mb-6 ';
                            } else {
                                $classes .= 'rounded-3xl border border-gray-200 bg-white shadow-sm mb-6 hover:border-gray-300 ';
                            }

                            if ($isActive) $classes .= 'ring-2 ring-orange-400 ring-offset-2 z-20 ';
                            else $classes .= 'z-10 ';
                        @endphp

                        {{-- [FIXED] wire:key forces a complete DOM re-render when index changes to prevent data bleeding --}}
                        <div data-sort-id="{{ $qKey }}" wire:key="question-{{ $index }}-{{ $qKey }}" class="{{ $classes }}" wire:click="setActiveQuestion({{ $index }})">

                            @if($isActive)
                                <div class="absolute left-0 top-6 bottom-6 w-1.5 bg-orange-500 rounded-r-full"></div>
                            @endif

                            @if($isEnclosedItem)
                                <div class="absolute top-0 left-6 right-6 h-px bg-gray-100"></div>
                            @endif

                            <div class="drag-handle absolute left-0 top-0 bottom-0 w-10 flex flex-col items-center justify-center cursor-move z-10 text-gray-300 hover:text-gray-500 hover:bg-gray-100/50 transition-colors {{ $isSectionHeader && !$isLastInSection ? 'rounded-tl-3xl' : ($isEnclosedItem && $isLastInSection ? 'rounded-bl-3xl' : ($isEnclosedItem ? '' : 'rounded-l-3xl')) }}">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"></path></svg>
                            </div>

                            @if($isPageBreak)
                                <div class="flex items-center justify-between pl-6 sm:pl-8">
                                    <div class="flex-1 flex flex-col sm:flex-row sm:items-center gap-4">
                                        <div class="hidden sm:block h-px bg-red-200 flex-1"></div>
                                        <span class="text-xs font-bold uppercase tracking-wider text-red-700 bg-white px-3 py-1 rounded-lg border border-red-200 shadow-sm w-max">Page Break</span>

                                        @if($isActive)
                                            <div class="flex items-center gap-3">
                                                <span class="text-xs font-bold text-gray-500 uppercase tracking-wider">After page:</span>
                                                <select wire:model="questions.{{ $index }}.options.0.jump" class="text-sm font-medium border-red-200 rounded-xl py-1.5 px-3 bg-white text-gray-700 shadow-sm focus:ring-red-500 focus:border-red-500 w-48 cursor-pointer">
                                                    <option value="">Continue to next page</option>
                                                    <option value="submit">Submit Form</option>
                                                    @foreach($this->sections as $section)
                                                        <option value="{{ $section['id'] }}">
                                                            Go to: {{ Str::limit($section['title'], 20) }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        @endif
                                        <div class="hidden sm:block h-px bg-red-200 flex-1"></div>
                                    </div>
                                    <button wire:click.stop="removeQuestion({{ $index }})" class="text-red-300 hover:text-red-600 p-2 transition-colors ml-2 shrink-0">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </div>

                            @else
                                <div class="{{ $isEnclosedItem ? 'pl-10 sm:pl-16' : 'pl-8' }}" x-data="{
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
                                        },
                                        resize(el) {
                                            this.$nextTick(() => {
                                                el.style.height = 'auto';
                                                el.style.height = el.scrollHeight + 'px';
                                            });
                                        }
                                    }">

                                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start mb-4 gap-4">
                                        <div class="flex flex-col gap-3 flex-1 w-full">

                                            <div x-show="$wire.activeQuestionIndex === {{ $index }}" class="flex bg-orange-50 rounded-lg border border-orange-200 w-max overflow-hidden shadow-sm" style="display: none;">
                                                <button type="button" @click="insert('**', '**')" class="px-3 py-1.5 hover:bg-orange-100 text-orange-700 font-black text-xs transition" title="Bold">B</button>
                                                <button type="button" @click="insert('*', '*')" class="px-3 py-1.5 hover:bg-orange-100 text-orange-700 italic text-xs transition border-l border-orange-200" title="Italic">I</button>
                                                <button type="button" @click="insert('~~', '~~')" class="px-3 py-1.5 hover:bg-orange-100 text-orange-700 line-through text-xs transition border-l border-orange-200" title="Strikethrough">S</button>
                                                <button type="button" @click="insert('[', '](https://)')" class="px-3 py-1.5 hover:bg-orange-100 text-orange-700 font-bold text-xs transition border-l border-orange-200" title="Link">Link</button>
                                            </div>

                                            @if($isSectionHeader)
                                                <textarea x-ref="qText" @focus="activeField = 'qText'" wire:model="questions.{{ $index }}.question_text"
                                                          x-init="$nextTick(() => resize($el))" @input="resize($el)" rows="1"
                                                          class="w-full text-xl font-black text-gray-800 border-0 bg-transparent placeholder-orange-400 focus:ring-0 p-0 overflow-hidden resize-none"
                                                          placeholder="Type Section Title"></textarea>
                                            @else
                                                <span class="text-xs font-semibold uppercase tracking-wider text-gray-500 bg-gray-100/80 px-2.5 py-1 rounded-lg w-max">{{ ucfirst($question['type']) }}</span>
                                            @endif
                                        </div>

                                        <div class="flex items-center gap-2 sm:gap-4 ml-auto shrink-0 mt-3 sm:mt-0">
                                            @if(!$isSectionHeader)
                                                <label class="flex items-center gap-2 cursor-pointer group/req border-r border-gray-200 pr-4 mr-2">
                                                    <input type="checkbox" wire:model="questions.{{ $index }}.is_required" class="rounded text-orange-500 w-4 h-4 border-gray-300 focus:ring-orange-500">
                                                    <span class="text-xs font-semibold text-gray-500 group-hover/req:text-gray-800 transition-colors uppercase tracking-wider">Required</span>
                                                </label>
                                            @endif

                                            @if($isSectionHeader)
                                                <button wire:click.stop="duplicateSection({{ $index }})" class="flex items-center gap-1.5 text-orange-600 hover:text-orange-800 bg-orange-100 hover:bg-orange-200 px-3 py-1.5 rounded-lg transition-colors text-xs font-bold mr-2" title="Duplicate Section">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                                                    Clone Section
                                                </button>
                                                {{-- [FIXED] Section Delete calls the specific modal confirm function --}}
                                                <button wire:click.stop="confirmDeleteSection({{ $index }})" class="text-gray-400 hover:text-red-500 hover:bg-red-50 rounded-lg transition-colors p-1.5" title="Delete Section & Contents">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                </button>
                                            @else
                                                <button wire:click.stop="duplicateQuestion({{ $index }})" class="text-gray-400 hover:text-blue-500 transition-colors p-1 mr-1" title="Duplicate Question">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                                                </button>
                                                {{-- [FIXED] Regular Delete directly deletes the single question --}}
                                                <button wire:click.stop="removeQuestion({{ $index }})" class="text-gray-400 hover:text-red-500 transition-colors p-1" title="Delete Question">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                </button>
                                            @endif
                                        </div>
                                    </div>

                                    @if(!$isSectionHeader)
                                        <textarea x-ref="qText" @focus="activeField = 'qText'" wire:model="questions.{{ $index }}.question_text"
                                                  x-init="$nextTick(() => resize($el))" @input="resize($el)" rows="1"
                                                  class="w-full text-base font-semibold border-0 border-b-2 border-transparent hover:border-gray-200 focus:border-orange-500 focus:ring-0 bg-transparent transition-colors p-0 mb-3 overflow-hidden resize-none leading-snug"
                                                  placeholder="Enter your question..."></textarea>

                                        @if($isActive || $question['description'] || $question['image_path'] || (isset($question['new_image']) && $question['new_image']))
                                            <textarea x-ref="qDesc" @focus="activeField = 'qDesc'" wire:model="questions.{{ $index }}.description"
                                                      x-init="resize($el)" @input="resize($el)" rows="1"
                                                      class="w-full text-sm text-gray-500 border-0 border-b-2 border-transparent hover:border-gray-100 focus:border-orange-300 focus:ring-0 bg-transparent transition-colors p-0 mb-4 placeholder-gray-300 overflow-hidden resize-none"
                                                      placeholder="Add helper text or a description..."></textarea>

                                            <div class="mb-4 flex flex-wrap items-center gap-4">
                                                @if(isset($questions[$index]['new_image']) && $questions[$index]['new_image'])
                                                    <img src="{{ $questions[$index]['new_image']->temporaryUrl() }}" class="h-16 w-auto rounded-lg border border-gray-200 shadow-sm">
                                                @elseif(isset($question['image_path']) && $question['image_path'])
                                                    <img src="{{ asset('storage/'.$question['image_path']) }}" class="h-16 w-auto rounded-lg border border-gray-200 shadow-sm">
                                                @endif

                                                <label class="inline-flex items-center gap-2 text-xs font-semibold uppercase tracking-wider text-gray-500 hover:text-orange-600 hover:bg-orange-50 transition-colors cursor-pointer bg-gray-100 px-3 py-2 rounded-lg">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                                    Attach Image
                                                    <input type="file" wire:model="questions.{{ $index }}.new_image" class="hidden" accept="image/*">
                                                </label>
                                            </div>
                                        @endif
                                    @else
                                        <textarea x-ref="qDesc" @focus="activeField = 'qDesc'" wire:model="questions.{{ $index }}.description"
                                                  x-init="$nextTick(() => resize($el))" @input="resize($el)" rows="1"
                                                  class="w-full text-sm text-gray-600 border-0 bg-transparent placeholder-orange-400/60 focus:ring-0 p-0 overflow-hidden resize-none"
                                                  placeholder="Provide context or instructions for this section..."></textarea>
                                    @endif

                                    @if(in_array($question['type'], ['radio', 'checkbox', 'dropdown']))
                                        <div class="pl-2 border-l-2 border-gray-100 space-y-2.5 mt-4">
                                            @foreach($question['options'] as $optIndex => $opt)
                                                {{-- [FIXED] Options wire:key syncs perfectly with index --}}
                                                <div class="flex flex-col gap-1.5" wire:key="opt-{{ $index }}-{{ $qKey }}-{{ $optIndex }}">
                                                    <div class="flex items-center gap-3">
                                                        <div class="w-3 h-3 border-2 border-gray-300 {{ $question['type'] === 'checkbox' ? 'rounded-sm' : 'rounded-full' }} shrink-0"></div>
                                                        <input type="text" wire:model="questions.{{ $index }}.options.{{ $optIndex }}{{ is_array($opt) ? '.text' : '' }}" class="w-full text-sm border-0 border-b border-dashed border-gray-300 hover:border-gray-400 bg-transparent focus:border-orange-500 focus:ring-0 p-1 placeholder-gray-400 transition-colors" placeholder="Enter option text">
                                                        <button wire:click.stop="removeOption({{ $index }}, {{ $optIndex }})" class="text-gray-300 hover:text-red-500 p-1 transition-colors shrink-0">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                                        </button>
                                                    </div>
                                                    @if($question['type'] === 'radio' && is_array($opt) && $isActive)
                                                        <div class="flex items-center gap-2 pl-6">
                                                            <svg class="w-3.5 h-3.5 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"></path></svg>
                                                            <select wire:model="questions.{{ $index }}.options.{{ $optIndex }}.jump" class="w-full max-w-[200px] text-xs font-medium border-gray-200 rounded-lg py-1.5 px-2 bg-gray-50 hover:bg-white text-gray-600 focus:ring-orange-500 focus:border-orange-500 transition-colors cursor-pointer truncate">
                                                                <option value="">Continue to next</option>
                                                                <option value="submit">Submit Form</option>
                                                                @foreach($this->sections as $section)
                                                                    <option value="{{ $section['id'] }}">
                                                                        Go to: {{ Str::limit($section['title'], 20) }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    @endif
                                                </div>
                                            @endforeach
                                            <button wire:click.stop="addOption({{ $index }})" class="text-sm font-bold text-blue-600 hover:text-blue-800 transition-colors mt-2 ml-6 flex items-center gap-1">
                                                <span>+</span> Add another option
                                            </button>
                                        </div>
                                    @elseif($question['type'] === 'likert')
                                        <div class="bg-gray-50 rounded-xl p-3 border border-gray-100 mt-4 overflow-x-auto">
                                            <div class="flex gap-2 min-w-max">
                                                @foreach($question['options'] as $optIndex => $option)
                                                    {{-- [FIXED] Likert wire:key syncs perfectly with index --}}
                                                    <input type="text" wire:key="likert-{{ $index }}-{{ $qKey }}-{{ $optIndex }}" wire:model="questions.{{ $index }}.options.{{ $optIndex }}" class="w-24 text-xs font-medium text-center border-gray-200 focus:border-orange-500 focus:ring-orange-500 rounded-lg p-2 shadow-sm">
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    {{-- ========================================== --}}
    {{-- DELETE SECTION CONFIRMATION MODAL          --}}
    {{-- ========================================== --}}
    @if($sectionToDeleteIndex !== null)
        <div class="fixed inset-0 z-[100] flex items-center justify-center p-4 sm:p-6">

            <div class="fixed inset-0 bg-gray-900/80 backdrop-blur-sm transition-opacity" wire:click="cancelDeleteSection"></div>

            <div class="relative bg-white rounded-3xl shadow-2xl w-full max-w-md flex flex-col overflow-hidden animate-fade-in-up">
                <div class="p-6 sm:p-8 text-center">

                    <div class="w-16 h-16 bg-red-50 text-red-500 rounded-full flex items-center justify-center mx-auto mb-5 ring-4 ring-red-50">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    </div>

                    <h3 class="text-xl font-black text-gray-900 mb-2">Delete Entire Section?</h3>

                    <p class="text-sm text-gray-500 mb-6 leading-relaxed">
                        You are about to delete <strong class="text-gray-800">"{{ $questions[$sectionToDeleteIndex]['question_text'] ?: 'Untitled Section' }}"</strong> and <strong class="text-red-600">all questions contained inside it</strong>. This action cannot be undone.
                    </p>

                    <div class="flex gap-3 w-full">
                        <button wire:click="cancelDeleteSection" class="flex-1 px-4 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold rounded-xl text-sm transition">
                            Cancel
                        </button>
                        <button wire:click="executeDeleteSection" class="flex-1 px-4 py-3 bg-red-600 hover:bg-red-700 text-white font-bold rounded-xl text-sm shadow-md shadow-red-600/20 transition flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            Yes, Delete
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

@push('scripts')
<script>
    function confirmReset(count) {
        Swal.fire({
            title: 'Delete all responses?',
            text: `You are about to permanently delete ${count} user responses. This cannot be undone.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6B7280',
            confirmButtonText: 'Yes, delete everything!'
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
