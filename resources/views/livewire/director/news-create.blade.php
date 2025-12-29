<div class="bg-gray-100 font-sans text-gray-900 h-[calc(100vh-120px)]" 
     x-data="{ mobilePreview: false, previewFullScreen: false, editorFullScreen: false }"> 

    {{-- 1. NAVIGATION BAR --}}
    <nav class="shrink-0 z-30 bg-white/90 backdrop-blur-md border-b border-gray-200 h-16 px-4 md:px-6 flex items-center justify-between rounded-t-2xl">
        <div class="flex items-center gap-3">
            <a href="{{ route('news.index') }}" class="text-xs font-bold uppercase text-gray-400 hover:text-red-600 transition">
                &larr; <span class="hidden md:inline">Exit</span>
            </a>
            <div class="h-4 w-px bg-gray-300 hidden md:block"></div>
            <span class="font-heading font-black text-gray-800 tracking-tight text-sm md:text-base">
                Editor <span class="text-red-600">Mode</span>
            </span>
        </div>

        {{-- Mobile Toggle --}}
        <button @click="mobilePreview = !mobilePreview" class="md:hidden text-[10px] font-bold uppercase bg-gray-200 text-gray-700 px-4 py-2 rounded-full hover:bg-gray-300 transition">
            <span x-text="mobilePreview ? 'Back to Editor' : 'View Preview'"></span>
        </button>

        {{-- Action Buttons --}}
        <div class="flex items-center gap-2 md:gap-3">
            <span class="text-xs text-green-600 font-bold mr-2 hidden md:inline" 
                  x-data="{ show: false }" 
                  x-show="show" 
                  x-transition.duration.1000ms 
                  x-init="@this.on('message', () => { show = true; setTimeout(() => show = false, 2000) })">
                Saved!
            </span>
            <button wire:click="saveDraft" class="px-3 py-2 md:px-4 md:py-2 bg-white border border-gray-300 text-gray-600 text-[10px] md:text-xs font-bold uppercase rounded-lg hover:bg-gray-50 transition shadow-sm">
                <span class="hidden md:inline">Save</span> Draft
            </button>
            <button wire:click="publish" class="px-3 py-2 md:px-5 md:py-2 bg-gradient-to-r from-red-600 to-yellow-500 text-white text-[10px] md:text-xs font-bold uppercase rounded-lg hover:shadow-lg hover:scale-105 transition shadow-md">
                Submit <span class="hidden md:inline">for Review</span>
            </button>
        </div>
    </nav>

    {{-- MAIN CONTAINER --}}
    <div class="flex h-full overflow-hidden rounded-b-2xl border-x border-b border-gray-200 bg-white">
        
        {{-- ======================== --}}
        {{-- LEFT PANEL: EDITOR       --}}
        {{-- ======================== --}}
        <div class="h-full overflow-y-auto p-4 md:p-6 bg-gray-50 border-r border-gray-200 transition-all duration-300 ease-in-out"
             :class="[
                mobilePreview ? 'hidden' : '',
                editorFullScreen ? 'w-full' : (previewFullScreen ? 'hidden' : 'w-full md:w-1/2 lg:w-5/12')
             ]">
            
            <div class="mx-auto space-y-6 transition-all duration-300 pb-6" 
                 :class="editorFullScreen ? 'max-w-4xl' : 'max-w-xl'">
                
                {{-- Metadata Card --}}
                <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 space-y-4">
                    <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest border-b border-gray-100 pb-2">Article Metadata</h3>
                    
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">URL Slug</label>
                        <div class="flex items-center">
                            <span class="text-[10px] text-gray-400 bg-gray-50 border border-r-0 border-gray-200 rounded-l-lg px-2 py-2">/news/</span>
                           <input wire:model.live="slug" type="text" class="w-full text-xs text-gray-600 border-gray-200 rounded-r-lg focus:ring-yellow-400 focus:border-yellow-400 bg-gray-50">
                        </div>
                        @error('slug') <span class="text-red-500 text-[10px]">{{ $message }}</span> @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">Category</label>
                            <select wire:model.live="category" class="w-full text-sm border-gray-200 rounded-lg focus:ring-yellow-400 focus:border-yellow-400">
                                <option value="" disabled>Select</option>
                                @foreach($categoryOptions as $cat)
                                    <option value="{{ $cat->name }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                             <label class="block text-xs font-bold text-gray-700 mb-1">Publication Date</label>
                             <input wire:model="published_at" type="date" class="w-full text-sm border-gray-200 rounded-lg focus:ring-yellow-400 focus:border-yellow-400 text-gray-600">
                        </div>
                    </div>

                    {{-- Authors Section --}}
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
                                        {{-- NAME --}}
                                        <div class="col-span-2 relative">
                                            <input 
                                                wire:model.live.debounce.300ms="authors.{{ $index }}.name" 
                                                type="text" 
                                                placeholder="Name" 
                                                class="w-full text-xs border-gray-200 rounded-lg focus:ring-yellow-400 placeholder-gray-300 py-1.5"
                                                autocomplete="off"
                                            >
                                            
                                            {{-- Link Icon --}}
                                            @if(!empty($auth['user_id']))
                                                <div class="absolute right-2 top-1.5 text-green-500" title="Linked User">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                </div>
                                            @endif

                                            {{-- Dropdown --}}
                                            @if(!empty($authorMatches[$index]))
                                                <div class="absolute z-50 w-full bg-white border border-gray-100 rounded-lg shadow-xl mt-1 max-h-40 overflow-y-auto">
                                                    <ul>
                                                        @foreach($authorMatches[$index] as $match)
                                                            <li wire:click="selectUser({{ $index }}, {{ $match['id'] }}, '{{ addslashes($match['name']) }}')"
                                                                class="px-3 py-2 hover:bg-red-50 cursor-pointer border-b border-gray-50 last:border-0">
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

                                    <button wire:click="removeAuthor({{ $index }})" class="shrink-0 p-1.5 text-gray-400 hover:text-red-500 hover:bg-red-50 rounded-lg transition mt-0.5">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                    </button>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Summary --}}
                    <div>
                        <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1">Summary</label>
                        <textarea wire:model.live="summary" rows="2" class="w-full text-xs border-gray-200 rounded-lg focus:ring-yellow-400 resize-none"></textarea>
                    </div>

                    {{-- Cover Photo --}}
                    <div x-data="{ isDropping: false, isUploading: false }"
                         class="border-2 border-dashed rounded-xl p-4 text-center transition-colors relative"
                         :class="isDropping ? 'border-red-500 bg-red-50' : 'border-gray-300'"
                         x-on:livewire-upload-start="isUploading = true"
                         x-on:livewire-upload-finish="isUploading = false"
                         x-on:livewire-upload-error="isUploading = false">
                        
                        <label class="block text-[10px] font-bold text-gray-500 uppercase mb-2">Cover Image</label>
                        
                        <div class="relative h-24 bg-gray-50 rounded-lg flex items-center justify-center overflow-hidden group">
                            <input type="file" wire:model="cover_photo" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-20"
                                   accept="image/*"
                                   @dragover="isDropping = true" @dragleave="isDropping = false" @drop="isDropping = false">
                            
                            @if ($cover_photo)
                                <img src="{{ $cover_photo->temporaryUrl() }}" class="h-full w-full object-cover opacity-80">
                                <div class="absolute inset-0 flex items-center justify-center bg-black/40 text-white text-[10px] opacity-0 group-hover:opacity-100 transition">Change</div>
                            @elseif($imageUrl)
                                <img src="{{ Str::startsWith($imageUrl, 'http') ? $imageUrl : asset('storage/'.$imageUrl) }}" class="h-full w-full object-cover opacity-80">
                                <div class="absolute inset-0 flex items-center justify-center bg-black/40 text-white text-[10px] opacity-0 group-hover:opacity-100 transition">Change</div>
                            @else
                                <span class="text-[10px] text-gray-400 font-bold">Click or Drag to Upload</span>
                            @endif

                            <div x-show="isUploading" class="absolute inset-0 bg-white/90 z-30 flex items-center justify-center">
                                <span class="text-[10px] font-bold text-red-600 animate-pulse">Uploading...</span>
                            </div>
                        </div>
                        @error('cover_photo') <span class="text-red-500 text-[10px]">{{ $message }}</span> @enderror
                        
                        <div class="mt-2">
                            <input wire:model.live="photo_credit" type="text" class="w-full text-[10px] border-gray-200 rounded-lg" placeholder="Photo Credit">
                        </div>
                    </div>
                </div>

                {{-- SDGs --}}
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
                                <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 w-max max-w-[150px] hidden group-hover:block z-20">
                                    <div class="bg-gray-900 text-white text-[10px] font-bold px-3 py-2 rounded shadow-lg text-center leading-tight">
                                        {{ $sdg->name }}
                                    </div>
                                    <div class="w-2 h-2 bg-gray-900 rotate-45 absolute left-1/2 -translate-x-1/2 -bottom-1"></div>
                                </div>
                            </label>
                        @endforeach
                    </div>
                </div>

                <div class="flex items-center justify-between px-2 py-2 border-b border-gray-100 mb-2">
                    <span class="text-[10px] font-bold uppercase text-gray-500">Styling</span>
                    <label class="inline-flex items-center cursor-pointer">
                        <input type="checkbox" wire:model.live="show_drop_cap" class="sr-only peer">
                        <div class="relative w-9 h-5 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-red-600"></div>
                        <span class="ms-2 text-xs font-medium text-gray-900">Drop Cap</span>
                    </label>
                </div>

                {{-- MAIN EDITOR AREA --}}
                <div class="space-y-2 relative"
                     x-data="{ 
                        insert(start, end) {
                            let el = $refs.editor;
                            if(!el) return;
                            let text = el.value;
                            let s = el.selectionStart;
                            let e = el.selectionEnd;
                            el.value = text.substring(0, s) + start + text.substring(s, e) + end + text.substring(e);
                            el.dispatchEvent(new Event('input'));
                            setTimeout(() => { el.focus(); el.setSelectionRange(s + start.length, e + start.length); }, 50);
                        }
                     }"
                     x-on:photo-inserted.window="insert('\n<figure>\n  <img src=\'' + $event.detail.url + '\' alt=\'Image\'>\n  <figcaption>Write your caption here...</figcaption>\n</figure>\n', '');"
                >
                    <input type="file" wire:model="photo_upload" class="hidden" x-ref="photoUploader" accept="image/*">

                    {{-- TOOLBAR (Responsive Flex Wrap) --}}
                    <div class="flex items-center justify-between sticky top-0 z-20 bg-gray-50 pb-2">
                        <div class="flex flex-wrap items-center gap-1 bg-white border border-gray-200 p-1.5 rounded-lg shadow-sm">
                            <button @click="insert('**', '**')" class="p-2 text-gray-500 hover:text-gray-900 hover:bg-gray-100 rounded-md transition font-bold text-xs" title="Bold">B</button>
                            <button @click="insert('*', '*')" class="p-2 text-gray-500 hover:text-gray-900 hover:bg-gray-100 rounded-md transition italic text-xs" title="Italic">I</button>
                            <button @click="insert('~~', '~~')" class="p-2 text-gray-500 hover:text-gray-900 hover:bg-gray-100 rounded-md transition line-through text-xs" title="Strikethrough">S</button>
                            <div class="w-px h-4 bg-gray-200 mx-1"></div>
                            <button @click="insert('### ', '')" class="p-2 text-gray-500 hover:text-gray-900 hover:bg-gray-100 rounded-md text-xs font-bold">H3</button>
                            <button @click="insert('> ', '')" class="p-2 text-gray-500 hover:text-gray-900 hover:bg-gray-100 rounded-md" title="Quote">&ldquo;</button>
                            <button @click="insert('[', '](http://)')" class="p-2 text-gray-500 hover:text-gray-900 hover:bg-gray-100 rounded-md transition" title="Link">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path></svg>
                            </button>
                            <div class="w-px h-4 bg-gray-200 mx-1"></div>
                            <button @click="insert('- ', '')" class="p-2 text-gray-500 hover:text-gray-900 hover:bg-gray-100 rounded-md transition" title="Bullet List"><svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg></button>
                            <button @click="insert('1. ', '')" class="p-2 text-gray-500 hover:text-gray-900 hover:bg-gray-100 rounded-md transition" title="Numbered List"><svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h12M7 12h12M7 17h12M3 7h.01M3 12h.01M3 17h.01"></path></svg></button>
                            <div class="w-px h-4 bg-gray-200 mx-1"></div>
                            <button @click="insert('\n---\n', '')" class="p-2 text-gray-500 hover:text-gray-900 hover:bg-gray-100 rounded-md transition font-bold text-xs" title="Divider">&minus;</button>
                            <button @click="$refs.photoUploader.click()" class="p-2 hover:bg-gray-100 rounded text-xs font-bold relative group">
                                <span wire:loading.remove wire:target="photo_upload">IMG</span>
                                <span wire:loading wire:target="photo_upload" class="animate-spin block w-4 h-4 border-2 border-gray-300 border-t-red-600 rounded-full"></span>
                            </button>
                        </div>
                        <input type="file" wire:model="photo_upload" class="hidden" x-ref="photoUploader" accept="image/*">
                        
                        <button @click="editorFullScreen = !editorFullScreen; previewFullScreen = false" 
                                class="p-2 bg-white border border-gray-200 rounded-lg shadow-sm text-gray-500 hover:text-red-600 transition"
                                :class="editorFullScreen ? 'text-red-600 ring-2 ring-red-100' : ''"
                                title="Toggle Focus Mode">
                            <svg x-show="!editorFullScreen" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"></path></svg>
                            <svg x-show="editorFullScreen" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 10L4 15m0 0l5 5m-5-5h16M15 14l5-5m0 0l-5-5m5 5H4"></path></svg>
                        </button>
                    </div>
                    
                    <div class="relative group">
                        <input wire:model.live="title" type="text" class="w-full text-3xl font-serif font-bold text-gray-900 bg-transparent border-0 border-b-2 border-transparent focus:border-red-500 focus:ring-0 placeholder-gray-300 transition px-0 mb-4" placeholder="Headline...">
                        
                        <textarea x-ref="editor"
                                  wire:model.live="content" 
                                  rows="25" 
                                  class="w-full text-lg leading-relaxed text-gray-700 bg-transparent border-2 border-transparent focus:border-gray-200 rounded-xl p-4 focus:ring-0 resize-none font-serif placeholder-gray-300 transition-all focus:bg-white min-h-[500px]"
                                  placeholder="Start writing your story..."></textarea>
                    </div>
                </div>

                {{-- Tags --}}
                <div class="bg-white p-4 rounded-xl border border-gray-100">
                    <label class="block text-xs font-bold text-gray-700 mb-2">Tags</label>
                    <input wire:model.live="tags" type="text" class="w-full text-sm border-gray-200 rounded-lg focus:ring-yellow-400 focus:border-yellow-400" placeholder="Youth, Leadership...">
                </div>
            </div>
        </div>

        {{-- ======================== --}}
        {{-- RIGHT PANEL: PREVIEW     --}}
        {{-- ======================== --}}
        <div class="h-full overflow-y-auto bg-stone-100 relative shadow-inner transition-all duration-300 ease-in-out"
             :class="[
                mobilePreview ? 'block w-full' : 'hidden md:block',
                previewFullScreen ? 'w-full' : (editorFullScreen ? 'hidden' : 'md:w-1/2 lg:w-7/12')
             ]">
            
            <div class="fixed top-24 right-8 z-50 flex gap-2">
                <button @click="previewFullScreen = !previewFullScreen; editorFullScreen = false" 
                        class="bg-white/80 backdrop-blur text-gray-600 hover:text-red-600 p-2 rounded-full shadow-md border border-gray-200 transition-all hover:scale-110 hidden md:block" 
                        :class="previewFullScreen ? 'text-red-600 ring-2 ring-red-100' : ''"
                        title="Toggle Preview Full Screen">
                    <svg x-show="!previewFullScreen" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"></path></svg>
                    <svg x-show="previewFullScreen" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 10L4 15m0 0l5 5m-5-5h16M15 14l5-5m0 0l-5-5m5 5H4"></path></svg>
                </button>
            </div>

            <div class="min-h-full bg-stone-50 pb-8 origin-top transition-transform" 
                 :class="previewFullScreen ? 'scale-100 px-0' : 'scale-90 md:scale-100'">
                
                {{-- PREVIEW HEADER --}}
                <section class="relative pt-24 pb-8 px-4 md:px-6 z-10 max-w-[1400px] mx-auto">
                    <div class="grid lg:grid-cols-2 gap-8 lg:gap-12 items-center">
                        <div class="text-left space-y-6 md:space-y-8">
                            <div>
                                <span class="px-3 py-1 md:px-4 md:py-1.5 bg-white/60 backdrop-blur-md text-red-600 text-[10px] md:text-xs font-black uppercase tracking-[0.2em] border border-white/50 rounded-full shadow-sm ring-1 ring-red-100">
                                    {{ $category ?: 'Category' }}
                                </span>
                            </div>
                            <h1 class="font-heading text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-black text-gray-900 leading-tight drop-shadow-sm">
                                {{ $title ?: 'Article Headline' }}
                            </h1>
                            <div class="flex flex-wrap items-start gap-3 md:gap-4 text-left">
                                @foreach($authors as $auth)
                                    <div class="inline-flex items-center gap-2 md:gap-3 px-4 py-2 md:px-5 md:py-2.5 bg-white/60 backdrop-blur-lg rounded-2xl shadow-sm border border-white/40">
                                        <div class="w-8 h-8 md:w-10 md:h-10 rounded-full bg-gradient-to-br from-red-500 to-yellow-500 p-0.5 shadow-md shrink-0">
                                            <div class="w-full h-full rounded-full bg-white flex items-center justify-center text-red-600 font-bold text-xs md:text-sm">
                                                {{ substr($auth['name'] ?? '?', 0, 1) }}
                                            </div>
                                        </div>
                                        <div>
                                            <p class="text-[8px] md:text-[9px] font-bold text-gray-400 uppercase tracking-wider leading-none mb-0.5">{{ $auth['type'] }}</p>
                                            <p class="text-xs md:text-sm font-bold text-gray-800 leading-none whitespace-nowrap">{{ $auth['name'] }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="relative mt-4 lg:mt-0">
                            <div class="relative p-2 bg-white/30 backdrop-blur-sm rounded-[2rem] md:rounded-[2.5rem] shadow-2xl border border-white/50">
                                <div class="relative aspect-[16/9] lg:aspect-[4/3] overflow-hidden rounded-[1.5rem] md:rounded-[2rem]">
                                    @if ($cover_photo)
                                        <img src="{{ $cover_photo->temporaryUrl() }}" class="w-full h-full object-cover">
                                    @elseif($imageUrl)
                                        <img src="{{ Str::startsWith($imageUrl, 'http') ? $imageUrl : asset('storage/'.$imageUrl) }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-gray-400 font-bold uppercase tracking-widest text-xs">No Cover Image</div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <div class="max-w-[1400px] mx-auto px-4 md:px-6 grid grid-cols-1 lg:grid-cols-12 gap-8 md:gap-12 pb-12">
                    <aside class="lg:col-span-3 order-1 lg:order-1">
                        <div class="lg:sticky lg:top-24 space-y-6">
                            @if($summary)
                            <div class="bg-white p-6 rounded-2xl border-l-4 border-red-500 shadow-sm relative overflow-hidden">
                                <h4 class="font-bold text-gray-900 uppercase tracking-widest text-xs mb-3">In Brief</h4>
                                <p class="text-sm text-gray-600 leading-relaxed font-medium italic">"{{ $summary }}"</p>
                            </div>
                            @endif

                            @if(count($selectedSdgs) > 0)
                            <div class="bg-white/60 backdrop-blur-md p-6 rounded-2xl border border-white/60 shadow-sm">
                                <h4 class="font-bold text-gray-900 uppercase tracking-widest text-xs mb-4 text-gray-500">Target SDGs</h4>
                                <div class="flex flex-wrap gap-2">
                                    @foreach($selectedSdgs as $selectedId)
                                        @php $s = $sdgOptions->firstWhere('id', $selectedId); @endphp
                                        @if($s)
                                            <div style="background-color: {{ $s->color_hex }}" class="w-8 h-8 rounded flex items-center justify-center text-white font-black text-xs shadow-sm" title="{{ $s->name }}">
                                                {{ $s->id }}
                                            </div>
                                            <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 w-max max-w-[150px] hidden group-hover:block z-20">
                                                <div class="bg-gray-900 text-white text-[10px] font-bold px-3 py-2 rounded shadow-lg text-center leading-tight">
                                                    {{ $s->name }}
                                                </div>
                                            <div class="w-2 h-2 bg-gray-900 rotate-45 absolute left-1/2 -translate-x-1/2 -bottom-1"></div>
                                        </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                            @endif
                        </div>
                    </aside>

                    <article class="lg:col-span-9 order-2 lg:order-2">
                        <div class="bg-white/70 backdrop-blur-xl p-6 md:p-12 rounded-[2rem] shadow-xl border border-white/60">
                            <div class="prose prose-red max-w-none font-sans text-gray-600 leading-7 md:prose-lg md:leading-8
                                        {{ $show_drop_cap ? "[&>p:first-child]:first-letter:text-4xl md:[&>p:first-child]:first-letter:text-6xl [&>p:first-child]:first-letter:font-black [&>p:first-child]:first-letter:text-transparent [&>p:first-child]:first-letter:bg-clip-text [&>p:first-child]:first-letter:bg-gradient-to-br [&>p:first-child]:first-letter:from-red-600 [&>p:first-child]:first-letter:to-yellow-500 [&>p:first-child]:first-letter:float-left [&>p:first-child]:first-letter:mr-2 md:[&>p:first-child]:first-letter:mr-3 [&>p:first-child]:first-letter:mt-[-2px] md:[&>p:first-child]:first-letter:mt-[-5px]" : '' }}
                                        [&_img]:rounded-xl [&_img]:shadow-lg [&_img]:w-full">
                                {!! Str::markdown($content ?? '') !!}
                            </div>
                            <div class="mt-8 md:mt-12 pt-6 md:pt-8 border-t border-gray-100/50 flex flex-wrap gap-2">
                                @if($tags)
                                    @foreach(explode(',', $tags) as $tag)
                                    <span class="px-3 py-1 md:px-4 md:py-1.5 bg-gray-50 text-gray-500 text-[10px] md:text-xs font-bold uppercase tracking-wider rounded-lg border border-gray-100">#{{ trim($tag) }}</span>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </article>
                    
                    <div class="hidden lg:block lg:col-span-2"></div>
                </div>
            </div>
        </div>
    </div>
</div>