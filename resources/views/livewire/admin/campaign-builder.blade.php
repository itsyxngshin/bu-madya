<div class="min-h-screen bg-gray-50 p-4 md:p-8 font-sans text-gray-900 pb-32">
    <div class="max-w-5xl mx-auto">

        {{-- ========================================== --}}
        {{-- HEADER / ACTIONS --}}
        {{-- ========================================== --}}
        <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
            <div>
                <h1 class="text-2xl md:text-3xl font-black text-gray-900 flex items-center gap-2 tracking-tight">
                    @if($campaign->exists)
                        <span class="text-orange-600">Edit</span> Campaign
                        @if(auth()->user()->role?->role_name === 'administrator' && $campaign->created_by !== auth()->id())
                            <span class="ml-3 px-3 py-1 bg-blue-50 border border-blue-200 text-blue-700 text-xs uppercase tracking-wider font-bold rounded-full shadow-sm">
                                Owner: {{ $campaign->creator->name ?? 'System' }}
                            </span>
                        @endif
                    @else
                        <span class="text-green-600">Launch</span> Campaign
                    @endif
                </h1>
                <p class="text-sm text-gray-500 mt-1">Write your petition and set your goals.</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.campaigns.index') }}" class="hidden sm:inline-block px-5 py-2.5 bg-white border border-gray-300 text-gray-700 font-bold rounded-xl text-sm hover:bg-gray-50 transition shadow-sm">Cancel</a>

                <button wire:click="save" class="px-8 py-2.5 bg-gray-900 text-white font-bold rounded-xl shadow-lg hover:bg-gray-800 transition text-sm flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    Save Campaign
                </button>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            {{-- ========================================== --}}
            {{-- LEFT: MAIN COPY (Title & Description) --}}
            {{-- ========================================== --}}
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white rounded-3xl p-6 md:p-8 shadow-sm border border-gray-200">
                    
                    {{-- TITLE --}}
                    <div class="mb-6">
                        <label class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-wider">Campaign Title <span class="text-red-500">*</span></label>
                        <input wire:model.live="title" type="text" class="w-full rounded-xl border-gray-200 bg-gray-50 text-xl font-black p-4 focus:bg-white focus:ring-orange-500 focus:border-orange-500 transition-colors placeholder-gray-300" placeholder="e.g., Ban Single-Use Plastics in Bicol University">
                        @error('title') <span class="text-xs text-red-500 font-bold block mt-2">{{ $message }}</span> @enderror
                    </div>

                    {{-- SLUG --}}
                    <div class="mb-6">
                        <label class="block text-xs font-bold text-gray-500 mb-2 uppercase tracking-wider">Public URL Slug</label>
                        <div class="flex gap-2">
                            <input wire:model="slug" type="text" class="w-full rounded-xl border-gray-200 bg-gray-50 text-sm font-mono p-3 focus:bg-white focus:ring-orange-500 focus:border-orange-500 transition-colors" placeholder="auto-generated-from-title">
                            <button wire:click="generateRandomSlug" type="button" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 rounded-xl border border-gray-200 transition-colors shrink-0" title="Randomize Slug">
                                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                            </button>
                        </div>
                    </div>

                    {{-- DESCRIPTION (Markdown Editor) --}}
                    <div x-data="{
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
                                    el.style.height = (el.scrollHeight) + 'px';
                                });
                            }
                        }">
                        <div class="flex justify-between items-end mb-2">
                            <label class="block text-sm font-bold text-gray-700 uppercase tracking-wider">The Petition/Advocacy <span class="text-red-500">*</span></label>
                            <div class="flex bg-gray-100 rounded-lg border border-gray-200 overflow-hidden shadow-sm">
                                <button type="button" @click="insert('**', '**')" class="px-3 py-1.5 hover:bg-gray-200 text-gray-700 font-black text-xs transition">B</button>
                                <button type="button" @click="insert('*', '*')" class="px-3 py-1.5 hover:bg-gray-200 text-gray-700 italic text-xs transition border-l border-gray-200">I</button>
                                <button type="button" @click="insert('> ', '')" class="px-3 py-1.5 hover:bg-gray-200 text-gray-700 font-serif text-xs transition border-l border-gray-200" title="Quote">"</button>
                                <button type="button" @click="insert('[', '](https://)')" class="px-3 py-1.5 hover:bg-gray-200 text-gray-700 font-semibold text-xs transition border-l border-gray-200">Link</button>
                            </div>
                        </div>
                        <textarea x-ref="editor" wire:model="description" rows="8"
                                  x-init="$nextTick(() => resize($el))" @input="resize($el)"
                                  class="w-full rounded-xl border-gray-200 bg-gray-50 text-base p-4 overflow-hidden focus:bg-white focus:ring-orange-500 focus:border-orange-500 transition-colors leading-relaxed"
                                  placeholder="Explain why people should sign this petition. What is the goal? Who does it help?"></textarea>
                        @error('description') <span class="text-xs text-red-500 font-bold block mt-2">{{ $message }}</span> @enderror
                    </div>

                </div>
            </div>

            {{-- ========================================== --}}
            {{-- RIGHT: SETTINGS (Image, Goals, Status) --}}
            {{-- ========================================== --}}
            <div class="lg:col-span-1 space-y-6">
                
                {{-- Goal Card --}}
                <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-200">
                    <h2 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-4">Target & Status</h2>
                    
                    <div class="mb-5">
                        <label class="block text-sm font-bold text-gray-700 mb-2">Signature Goal</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                            </div>
                            <input wire:model.live="target_signatures" type="number" class="w-full pl-10 pr-4 py-3 rounded-xl border-gray-200 bg-gray-50 font-black text-gray-900 text-lg focus:bg-white focus:ring-orange-500 focus:border-orange-500 transition-colors">
                        </div>
                        <p class="text-[10px] text-gray-400 mt-1.5 font-semibold">How many signatures define success?</p>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Campaign Status</label>
                        <select wire:model="status" class="w-full rounded-xl border-gray-200 bg-gray-50 text-sm font-bold p-3 focus:bg-white focus:ring-orange-500 focus:border-orange-500 transition-colors cursor-pointer">
                            <option value="draft">Draft (Hidden)</option>
                            <option value="active">Active (Collecting Signatures)</option>
                            <option value="victorious">Victorious (Success Banner)</option>
                            <option value="closed">Closed (No longer accepting)</option>
                        </select>
                    </div>
                </div>

                {{-- Cover Image Card --}}
                <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-200">
                    <h2 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-4">Cover Image</h2>
                    
                    @if($cover_image)
                        <img src="{{ $cover_image->temporaryUrl() }}" class="w-full h-40 object-cover rounded-xl mb-4 border border-gray-200 shadow-sm">
                    @elseif($existing_cover_image)
                        <img src="{{ asset('storage/'.$existing_cover_image) }}" class="w-full h-40 object-cover rounded-xl mb-4 border border-gray-200 shadow-sm">
                    @else
                        <div class="w-full h-40 bg-gray-50 rounded-xl mb-4 border-2 border-dashed border-gray-200 flex flex-col items-center justify-center text-gray-400">
                            <svg class="w-8 h-8 mb-2 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            <span class="text-xs font-bold uppercase tracking-wider">No Image</span>
                        </div>
                    @endif

                    <label class="cursor-pointer w-full bg-orange-50 hover:bg-orange-100 text-orange-700 px-4 py-3 rounded-xl text-sm font-bold transition flex items-center justify-center border border-orange-200 group">
                        <svg class="w-4 h-4 mr-2 group-hover:-translate-y-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                        Upload Cover Image 
                        <input type="file" wire:model="cover_image" class="hidden" accept="image/*">
                    </label>
                    <div wire:loading wire:target="cover_image" class="text-xs text-orange-600 font-bold mt-3 text-center w-full animate-pulse">Uploading image...</div>
                    @error('cover_image') <span class="text-xs text-red-500 font-bold block mt-2 text-center w-full">{{ $message }}</span> @enderror
                </div>

            </div>
        </div>
    </div>
</div>