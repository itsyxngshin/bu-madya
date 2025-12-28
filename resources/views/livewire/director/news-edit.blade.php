<div class="h-screen bg-gray-100 font-sans text-gray-900 overflow-hidden flex flex-col" x-data="{ mobilePreview: false, fullScreen: false }">

    {{-- 1. NAVIGATION BAR --}}
    <nav class="shrink-0 z-50 bg-white/90 backdrop-blur-md border-b border-gray-200 h-16 px-6 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <a href="{{ route('news.show', $slug) }}" class="text-xs font-bold uppercase text-gray-400 hover:text-red-600 transition">
                &larr; Cancel
            </a>
            <div class="h-4 w-px bg-gray-300"></div>
            <span class="font-heading font-black text-gray-800 tracking-tight">
                Edit <span class="text-red-600">Mode</span>
            </span>
        </div>

        <button @click="mobilePreview = !mobilePreview" class="md:hidden text-xs font-bold uppercase bg-gray-200 px-3 py-1 rounded">
            <span x-text="mobilePreview ? 'Edit' : 'Preview'"></span>
        </button>

        <div class="flex items-center gap-3">
            <span class="text-xs text-green-600 font-bold mr-2" 
                  x-data="{ show: false }" 
                  x-show="show" 
                  x-transition.duration.1000ms 
                  x-init="@this.on('message', () => { show = true; setTimeout(() => show = false, 2000) })">
                Saved!
            </span>
            <button wire:click="saveDraft" class="px-4 py-2 bg-white border border-gray-300 text-gray-600 text-xs font-bold uppercase rounded-lg hover:bg-gray-50 transition shadow-sm">
                Save Draft
            </button>
            <button wire:click="publish" class="px-5 py-2 bg-gradient-to-r from-red-600 to-yellow-500 text-white text-xs font-bold uppercase rounded-lg hover:shadow-lg hover:scale-105 transition shadow-md">
                Publish Updates
            </button>
        </div>
    </nav>

    {{-- 2. MAIN WORKSPACE --}}
    <div class="flex-1 flex overflow-hidden relative">
        
        {{-- ======================== --}}
        {{-- LEFT PANEL: EDITOR       --}}
        {{-- ======================== --}}
        <div class="h-full overflow-y-auto bg-gray-50 border-r border-gray-200 transition-all duration-300 ease-in-out pb-20"
             :class="[mobilePreview ? 'hidden' : 'w-full md:w-1/2 lg:w-5/12', fullScreen ? 'hidden' : 'block']">
            
            <div class="p-6 max-w-xl mx-auto flex flex-col gap-6">
                
                {{-- Metadata Card --}}
                <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 space-y-4">
                    <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest border-b border-gray-100 pb-2">Metadata</h3>
                    
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">URL Slug</label>
                        <div class="flex items-center">
                            <span class="text-[10px] text-gray-400 bg-gray-50 border border-r-0 border-gray-200 rounded-l-lg px-2 py-2">/news/</span>
                            <input wire:model="slug" type="text" class="w-full text-xs text-gray-600 border-gray-200 rounded-r-lg focus:ring-yellow-400 focus:border-yellow-400 bg-gray-50 h-8">
                        </div>
                        @error('slug') <span class="text-red-500 text-[10px]">{{ $message }}</span> @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">Category</label>
                            <select wire:model.live="category" class="w-full text-xs border-gray-200 rounded-lg focus:ring-yellow-400 py-1.5">
                                @foreach($categoryOptions as $cat)
                                    <option value="{{ $cat->name }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">Publication Date</label>
                            <input wire:model="published_at" type="date" class="w-full text-xs border-gray-200 rounded-lg focus:ring-yellow-400 py-1.5 text-gray-600">
                        </div>
                    </div>

                    {{-- Authors Section with Search --}}
                    <div class="space-y-2 pt-2 border-t border-gray-50">
                        <div class="flex items-center justify-between">
                            <label class="block text-xs font-bold text-gray-700">Authors / Credits</label>
                            <button wire:click="addAuthor" class="text-[10px] uppercase font-bold text-red-600 hover:text-red-700 bg-red-50 hover:bg-red-100 px-2 py-1 rounded transition">
                                + Add
                            </button>
                        </div>
                        
                        <div class="space-y-3">
                            @foreach($authors as $index => $auth)
                                <div class="flex gap-2 items-start z-20 relative" wire:key="author-row-{{ $index }}">
                                    
                                    <div class="grow grid grid-cols-3 gap-2">
                                        {{-- NAME INPUT --}}
                                        <div class="col-span-2 relative">
                                            <input 
                                                wire:model.live.debounce.300ms="authors.{{ $index }}.name" 
                                                type="text" 
                                                placeholder="Name (Search...)" 
                                                class="w-full text-xs border-gray-200 rounded-lg focus:ring-yellow-400 placeholder-gray-300 py-1.5"
                                                autocomplete="off"
                                            >
                                            
                                            {{-- Link Icon --}}
                                            @if(!empty($auth['user_id']))
                                                <div class="absolute right-2 top-1.5 text-green-500" title="Linked User">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                </div>
                                            @endif

                                            {{-- DROPDOWN --}}
                                            @if(!empty($authorMatches[$index]))
                                                <div class="absolute z-50 w-full bg-white border border-gray-100 rounded-lg shadow-xl mt-1 max-h-40 overflow-y-auto">
                                                    <ul>
                                                        @foreach($authorMatches[$index] as $match)
                                                            <li 
                                                                wire:click="selectUser({{ $index }}, {{ $match['id'] }}, '{{ addslashes($match['name']) }}')"
                                                                class="px-3 py-2 hover:bg-red-50 cursor-pointer border-b border-gray-50 last:border-0"
                                                            >
                                                                <p class="text-xs font-bold text-gray-800">{{ $match['name'] }}</p>
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            @endif
                                            @error("authors.{$index}.name") <span class="text-red-500 text-[9px]">Required</span> @enderror
                                        </div>

                                        {{-- ROLE --}}
                                        <div>
                                            <select wire:model="authors.{{ $index }}.type" class="w-full text-xs border-gray-200 rounded-lg focus:ring-yellow-400 text-gray-500 py-1.5">
                                                <option value="Head Writer">Writer</option>
                                                <option value="Contributor">Contrib.</option>
                                                <option value="Photographer">Photo</option>
                                                <option value="Editor">Editor</option>
                                            </select>
                                        </div>
                                    </div>

                                    <button wire:click="removeAuthor({{ $index }})" class="shrink-0 p-1.5 text-gray-400 hover:text-red-500 hover:bg-red-50 rounded-lg transition mt-0.5" title="Remove">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                    </button>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Summary --}}
                    <div>
                        <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1">Summary</label>
                        <textarea wire:model.live="summary" rows="2" class="w-full text-xs border-gray-200 rounded-lg resize-none focus:ring-yellow-400"></textarea>
                    </div>

                    {{-- Cover Image --}}
                    <div x-data="{ isDropping: false, isUploading: false }"
                         x-on:livewire-upload-start="isUploading = true"
                         x-on:livewire-upload-finish="isUploading = false"
                         x-on:livewire-upload-error="isUploading = false">
                        
                        <label class="block text-[10px] font-bold text-gray-500 uppercase mb-2">Cover Image</label>

                        <div class="relative flex flex-col items-center justify-center w-full h-32 border-2 border-dashed rounded-xl transition-all duration-200 bg-white"
                             :class="isDropping ? 'border-red-500 bg-red-50' : 'border-gray-300 hover:bg-gray-50'">
                            
                            <input type="file" wire:model="cover_photo" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" accept="image/*"
                                   @dragover="isDropping = true" @dragleave="isDropping = false" @drop="isDropping = false">

                            <div class="absolute inset-0 w-full h-full flex flex-col items-center justify-center pointer-events-none z-0">
                                @if ($cover_photo)
                                    <img src="{{ $cover_photo->temporaryUrl() }}" class="w-full h-full object-cover rounded-xl opacity-90">
                                    <div class="absolute bg-black/50 text-white text-[10px] px-2 py-1 rounded backdrop-blur-sm">New File</div>
                                @elseif($imageUrl)
                                    <img src="{{ Str::startsWith($imageUrl, 'http') ? $imageUrl : asset('storage/'.$imageUrl) }}" class="w-full h-full object-cover rounded-xl opacity-90">
                                    <div class="absolute bg-black/50 text-white text-[10px] px-2 py-1 rounded backdrop-blur-sm">Current</div>
                                @else
                                    <div class="text-gray-400 text-center"><p class="text-[10px] font-bold">Click to Upload</p></div>
                                @endif
                            </div>

                            <div x-show="isUploading" class="absolute inset-0 bg-white/90 flex items-center justify-center z-20 rounded-xl">
                                <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-red-600"></div>
                            </div>
                        </div>

                        <div class="mt-2">
                            <input wire:model.live="photo_credit" type="text" class="w-full text-[10px] border-gray-200 rounded-lg focus:ring-red-500" placeholder="Photo Credit">
                        </div>
                    </div>
                </div>

                {{-- SDG Picker --}}
                <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 space-y-3">
                    <div class="flex items-center justify-between border-b border-gray-100 pb-2">
                        <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest">Target SDGs</h3>
                        <span class="text-[10px] font-bold text-gray-400 bg-gray-100 px-2 py-0.5 rounded-full">{{ count($selectedSdgs) }} Selected</span>
                    </div>
                    <div class="grid grid-cols-6 gap-2">
                        @foreach($sdgOptions as $sdg)
                            <label class="cursor-pointer group relative">
                                <input type="checkbox" wire:model.live="selectedSdgs" value="{{ $sdg->id }}" class="peer hidden">
                                <div style="background-color: {{ $sdg->color_hex }}" class="w-8 h-8 rounded-md flex items-center justify-center text-white font-black text-[10px] bg-opacity-10 grayscale opacity-40 hover:grayscale-0 hover:opacity-100 peer-checked:bg-opacity-100 peer-checked:grayscale-0 peer-checked:opacity-100 transition-all shadow-sm">
                                    {{ $sdg->id }}
                                </div>
                            </label>
                        @endforeach
                    </div>
                </div>

                {{-- Editor --}}
                <div class="space-y-2" x-data="{ isUploading: false, insert(start, end) { 
                        let el = $refs.editor;
                        let text = el.value;
                        let s = el.selectionStart;
                        let e = el.selectionEnd;
                        el.value = text.substring(0, s) + start + text.substring(s, e) + end + text.substring(e);
                        el.dispatchEvent(new Event('input'));
                        setTimeout(() => { el.focus(); el.setSelectionRange(s + start.length, e + start.length); }, 50);
                    } }">
                    
                    <div class="flex items-center gap-1 bg-white border border-gray-200 p-1.5 rounded-lg shadow-sm w-fit mb-2">
                        <button @click="insert('**', '**')" class="p-2 text-gray-500 hover:text-gray-900 hover:bg-gray-100 rounded-md transition" title="Bold"><strong>B</strong></button>
                        <button @click="insert('*', '*')" class="p-2 text-gray-500 hover:text-gray-900 hover:bg-gray-100 rounded-md transition" title="Italic"><em>I</em></button>
                        <div class="w-px h-4 bg-gray-200 mx-1"></div>
                        <button @click="insert('### ', '')" class="p-2 text-gray-500 hover:text-gray-900 hover:bg-gray-100 rounded-md text-xs font-bold">H3</button>
                        <button @click="insert('> ', '')" class="p-2 text-gray-500 hover:text-gray-900 hover:bg-gray-100 rounded-md" title="Quote">&ldquo;</button>
                        <button @click="$refs.photoUploader.click()" class="p-2 hover:bg-gray-100 rounded text-xs font-bold relative group">
                            <span x-show="!isUploading">IMG</span>
                            <span x-show="isUploading" class="animate-spin block w-4 h-4 border-2 border-gray-300 border-t-red-600 rounded-full"></span>
                        </button>
                    </div>

                    <input type="file" wire:model="photo_upload" class="hidden" x-ref="photoUploader" accept="image/*">
                    <input wire:model.live="title" type="text" class="w-full text-2xl font-serif font-bold text-gray-900 bg-transparent border-0 border-b-2 border-transparent focus:border-red-500 focus:ring-0 placeholder-gray-300 transition px-0 mb-4" placeholder="Headline...">
                    <textarea x-ref="editor" wire:model.live="content" rows="12" class="w-full text-base leading-relaxed text-gray-700 bg-transparent border-2 border-transparent focus:border-gray-200 rounded-xl p-4 focus:ring-0 resize-none font-serif placeholder-gray-300 transition-all focus:bg-white" placeholder="Start writing..."></textarea>
                </div>

                {{-- Tags --}}
                <div class="bg-white p-4 rounded-xl border border-gray-100">
                    <label class="block text-xs font-bold text-gray-700 mb-2">Tags</label>
                    <input wire:model.live="tags" type="text" class="w-full text-sm border-gray-200 rounded-lg focus:ring-yellow-400 focus:border-yellow-400">
                </div>
                 <div class="h-10"></div>
            </div>
        </div>

        {{-- ======================== --}}
        {{-- RIGHT PANEL: PREVIEW     --}}
        {{-- ======================== --}}
        <div class="h-full overflow-y-auto bg-stone-100 relative shadow-inner transition-all duration-300 ease-in-out"
             :class="[mobilePreview ? 'block w-full' : 'hidden md:block', fullScreen ? 'w-full' : 'md:w-1/2 lg:w-7/12']">
            
            <button @click="fullScreen = !fullScreen" class="fixed top-20 right-8 z-50 bg-white/80 p-2 rounded-full shadow-md hover:scale-110 transition">
               <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"></path></svg>
            </button>

            <div class="min-h-full bg-stone-50 pb-20 origin-top transition-transform" :class="fullScreen ? 'scale-100 px-0' : 'scale-90 md:scale-100'">
                
                <header class="relative pt-20 pb-12 px-6 z-10 text-center max-w-4xl mx-auto">
                    <span class="px-4 py-1 bg-red-50 text-red-600 text-[10px] font-black uppercase tracking-[0.2em] rounded-full border border-red-100 mb-6 inline-block">{{ $category ?: 'Category' }}</span>
                    <h1 class="font-heading text-4xl md:text-6xl font-black text-gray-900 leading-tight mb-8">{{ $title ?: 'Article Headline' }}</h1>
                    
                    {{-- AUTHOR PREVIEW --}}
                    <div class="flex flex-wrap justify-center gap-3">
                        @foreach($authors as $auth)
                            <div class="inline-flex items-center gap-3 bg-white/60 px-4 py-1.5 rounded-full border border-white/40 shadow-sm">
                                <div class="w-6 h-6 rounded-full bg-gradient-to-br from-red-500 to-yellow-500 flex items-center justify-center text-white font-bold text-[10px]">
                                     {{ substr($auth['name'] ?? '?', 0, 1) }}
                                </div>
                                <div class="text-left">
                                    <p class="text-[9px] font-bold text-gray-400 uppercase tracking-wider leading-none">{{ $auth['type'] }}</p>
                                    <p class="text-[11px] font-bold text-gray-800 leading-none">{{ $auth['name'] }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </header>

                <div class="w-full max-w-5xl mx-auto px-6 mb-12">
                     <div class="relative group aspect-[21/9] overflow-hidden rounded-3xl shadow-2xl bg-gray-200">
                        @if ($cover_photo)
                            <img src="{{ $cover_photo->temporaryUrl() }}" class="w-full h-full object-cover">
                        @elseif($imageUrl)
                            <img src="{{ Str::startsWith($imageUrl, 'http') ? $imageUrl : asset('storage/'.$imageUrl) }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-gray-400 font-bold uppercase tracking-widest text-xs">No Cover Image</div>
                        @endif

                        @if($photo_credit)
                        <div class="absolute bottom-4 right-4 bg-black/60 backdrop-blur px-3 py-1.5 rounded-lg border border-white/10">
                            <p class="text-[10px] text-white/90 italic">{{ $photo_credit }}</p>
                        </div>
                        @endif
                    </div>
                </div>

                <div class="max-w-[1400px] mx-auto px-6 grid grid-cols-1 lg:grid-cols-12 gap-10">
                    <aside class="hidden lg:block lg:col-span-3">
                        <div class="sticky top-24 space-y-6">
                            @if($summary)
                            <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm relative overflow-hidden">
                                <div class="absolute top-0 left-0 w-1 h-full bg-red-500"></div>
                                <h4 class="font-bold text-gray-900 uppercase tracking-widest text-xs mb-3">In Brief</h4>
                                <p class="text-sm text-gray-600 leading-relaxed font-medium italic">"{{ $summary }}"</p>
                            </div>
                            @endif

                            @if(count($selectedSdgs) > 0)
                            <div class="flex flex-wrap gap-2">
                                @foreach($selectedSdgs as $selectedId)
                                    @php $s = $sdgOptions->firstWhere('id', $selectedId); @endphp
                                    @if($s)
                                        <div style="background-color: {{ $s->color_hex }}" class="w-8 h-8 rounded flex items-center justify-center text-white font-black text-xs shadow-sm transform hover:scale-110 transition" title="{{ $s->name }}">
                                            {{ $s->id }}
                                        </div>

                                        {{-- THE TOOLTIP --}}
                                        <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 w-max max-w-[150px] hidden group-hover:block z-20">
                                            <div class="bg-gray-900 text-white text-[10px] font-bold px-2 py-1.5 rounded shadow-lg text-center leading-tight">
                                                {{ $s->name }}
                                            </div>
                                            {{-- Little Triangle Pointer --}}
                                            <div class="w-2 h-2 bg-gray-900 rotate-45 absolute left-1/2 -translate-x-1/2 -bottom-1"></div>
                                        </div>
                                    @endif
                                    
                                @endforeach
                            </div>
                            @endif
                        </div>
                    </aside>
                    <article class="lg:col-span-7">
                        <div class="prose prose-lg prose-red font-serif text-gray-600 leading-8 max-w-none {{ $show_drop_cap ? "[&>p:first-child]:first-letter:text-6xl [&>p:first-child]:first-letter:font-black [&>p:first-child]:first-letter:text-red-600 [&>p:first-child]:first-letter:mr-3 [&>p:first-child]:first-letter:float-left" : '' }}">
                            {!! Str::markdown($content ?? '') !!}
                        </div>
                    </article>
                </div>
            </div>
        </div>
    </div>
</div>