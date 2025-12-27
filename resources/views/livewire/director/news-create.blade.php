<div class="min-h-screen bg-gray-100 font-sans text-gray-900" x-data="{ mobilePreview: false, fullScreen: false }">

    {{-- 1. NAVIGATION BAR --}}
    <nav class="shrink-0 z-50 bg-white/90 backdrop-blur-md border-b border-gray-200 h-16 px-6 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <a href="{{ route('news.index') }}" class="text-xs font-bold uppercase text-gray-400 hover:text-red-600 transition">&larr; Exit</a>
            <div class="h-4 w-px bg-gray-300"></div>
            <span class="font-heading font-black text-gray-800 tracking-tight">Editor <span class="text-red-600">Mode</span></span>
        </div>

        {{-- Mobile Toggle --}}
        <button @click="mobilePreview = !mobilePreview" class="md:hidden text-xs font-bold uppercase bg-gray-200 px-3 py-1 rounded">
            <span x-text="mobilePreview ? 'Edit' : 'Preview'"></span>
        </button>

        {{-- Action Buttons --}}
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
                Publish
            </button>
        </div>
    </nav>

    <div class="flex h-[calc(100vh-64px)] overflow-hidden">
        
        {{-- ======================== --}}
        {{-- LEFT PANEL: EDITOR       --}}
        {{-- ======================== --}}
        <div class="h-full overflow-y-auto p-6 bg-gray-50 border-r border-gray-200 transition-all duration-300 ease-in-out"
             :class="[
                mobilePreview ? 'hidden' : 'w-full md:w-1/2 lg:w-5/12',
                fullScreen ? 'hidden' : 'block'
             ]">
            
            <div class="max-w-xl mx-auto space-y-6">
                
                {{-- Metadata Card --}}
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 space-y-4">
                    <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest border-b border-gray-100 pb-2">Article Metadata</h3>
                    
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">URL Slug (Editable)</label>
                        <div class="flex items-center">
                            <span class="text-xs text-gray-400 bg-gray-50 border border-r-0 border-gray-200 rounded-l-lg px-2 py-2">/news/</span>
                           <input wire:model.live="slug" 
                                type="text" 
                                class="w-full text-xs text-gray-600 border-gray-200 rounded-r-lg focus:ring-yellow-400 focus:border-yellow-400 bg-gray-50" 
                                placeholder="my-article-title">
                        </div>
                        @error('slug') <span class="text-red-500 text-[10px]">{{ $message }}</span> @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">Category</label>
                            <select wire:model.live="category" class="w-full text-sm border-gray-200 rounded-lg focus:ring-yellow-400 focus:border-yellow-400">
                                <option value="" disabled>Select a Category</option>
                                
                                @foreach($categoryOptions as $cat)
                                    <option value="{{ $cat->name }}">{{ $cat->name }}</option>
                                @endforeach
                                
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">Author Name</label>
                            <input wire:model.live="author" type="text" class="w-full text-sm border-gray-200 rounded-lg focus:ring-yellow-400 focus:border-yellow-400">
                        </div>
                    </div>

                    {{-- Summary --}}
                    <div>
                        <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1">In Brief / Summary</label>
                        <textarea wire:model.live="summary" rows="2" class="w-full text-xs border-gray-200 rounded-lg focus:ring-yellow-400 resize-none" placeholder="A short catchy summary..."></textarea>
                    </div>

                    {{-- Image & Credit --}}
                    <div x-data="{ isDropping: false, isUploading: false }"
                        x-on:livewire-upload-start="isUploading = true"
                        x-on:livewire-upload-finish="isUploading = false"
                        x-on:livewire-upload-error="isUploading = false">
                        
                        <label class="block text-[10px] font-bold text-gray-500 uppercase mb-2">Cover Image</label>

                        {{-- Container for the Upload Box --}}
                        <div class="relative flex flex-col items-center justify-center w-full h-32 border-2 border-dashed rounded-xl transition-all duration-200 bg-white"
                            :class="isDropping ? 'border-red-500 bg-red-50' : 'border-gray-300 hover:bg-gray-50'">
                            
                            {{-- 1. THE ACTUAL INPUT (Invisible Overlay) --}}
                            {{-- This covers the entire box, ensuring Drag & Click always hit the input --}}
                            <input type="file" 
                                wire:model="cover_photo" 
                                class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" 
                                accept="image/png, image/jpeg, image/jpg"
                                @dragover="isDropping = true" 
                                @dragleave="isDropping = false" 
                                @drop="isDropping = false"
                                title="Click or Drag to Upload">

                            {{-- 2. VISUAL STATE: PREVIEW OR PROMPT --}}
                            <div class="absolute inset-0 w-full h-full flex flex-col items-center justify-center pointer-events-none z-0">
                                @if ($cover_photo)
                                    {{-- Temporary Preview (New Upload) --}}
                                    <img src="{{ $cover_photo->temporaryUrl() }}" class="w-full h-full object-cover rounded-xl opacity-90">
                                    <div class="absolute bg-black/50 text-white text-[10px] px-2 py-1 rounded backdrop-blur-sm">Change Photo</div>
                                
                                @elseif($imageUrl)
                                    {{-- Database Image (Existing) --}}
                                    <img src="{{ Str::startsWith($imageUrl, 'http') ? $imageUrl : asset('storage/'.$imageUrl) }}" class="w-full h-full object-cover rounded-xl opacity-90">
                                    <div class="absolute bg-black/50 text-white text-[10px] px-2 py-1 rounded backdrop-blur-sm">Change Photo</div>
                                
                                @else
                                    {{-- Empty State --}}
                                    <svg class="w-8 h-8 mb-2 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">Click or Drag to Upload</p>
                                @endif
                            </div>

                            {{-- 3. LOADING STATE OVERLAY --}}
                            <div x-show="isUploading" class="absolute inset-0 bg-white/90 flex items-center justify-center z-20 rounded-xl transition-opacity">
                                <div class="flex flex-col items-center">
                                    <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-red-600 mb-2"></div>
                                    <span class="text-[10px] font-bold text-red-600 animate-pulse">Uploading...</span>
                                </div>
                            </div>
                        </div>

                        {{-- 4. ERROR MESSAGES --}}
                        @error('cover_photo') 
                            <span class="text-red-500 text-[10px] font-bold mt-1 block animate-pulse">{{ $message }}</span> 
                        @enderror

                        <div class="text-xs mt-2">
                            <input wire:model.live="photo_credit" type="text" class="w-full text-[10px] border-gray-200 rounded-lg focus:ring-red-500" placeholder="Photo Credit">
                        </div>
                    </div>
                </div>

                {{-- SDG PICKER CARD (Connected to DB) --}}
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 space-y-4 mt-6">
                    <div class="flex items-center justify-between border-b border-gray-100 pb-2">
                        <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest">Target SDGs</h3>
                        <span class="text-[10px] font-bold text-gray-400 bg-gray-100 px-2 py-0.5 rounded-full">
                            {{ count($selectedSdgs) }} Selected
                        </span>
                    </div>

                    <div class="grid grid-cols-6 gap-2">
                        @foreach($sdgOptions as $sdg)
                            <label class="cursor-pointer group relative">
                                
                                {{-- Checkbox --}}
                                <input type="checkbox" wire:model.live="selectedSdgs" value="{{ $sdg->id }}" class="peer hidden">
                                
                                {{-- Visual Box --}}
                                {{-- We use inline style for the specific HEX color from DB --}}
                                <div style="background-color: {{ $sdg->color_hex }}; box-shadow: 0 0 0 1px {{ $sdg->color_hex }}"
                                    class="w-8 h-8 rounded-md flex items-center justify-center transition-all duration-200 
                                            text-white font-black text-[10px]
                                            {{-- DEFAULT STATE (Unchecked): Faded & Grayscale --}}
                                            bg-opacity-10 grayscale opacity-40 
                                            hover:grayscale-0 hover:opacity-70 hover:scale-105
                                            {{-- CHECKED STATE: Full Color & No Filter --}}
                                            peer-checked:bg-opacity-100 peer-checked:grayscale-0 peer-checked:opacity-100 peer-checked:shadow-md peer-checked:scale-110">
                                    
                                    {{-- Number --}}
                                    <span class="drop-shadow-sm">{{ $sdg->id }}</span>
                                </div>

                                {{-- Tooltip --}}
                                <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-1 hidden group-hover:block whitespace-nowrap z-10 bg-gray-900 text-white text-[10px] px-2 py-1 rounded">
                                    {{ $sdg->name }}
                                </div>
                            </label>
                        @endforeach
                    </div>
                    <p class="text-[10px] text-gray-400 italic">Select the goals this advocacy directly addresses.</p>
                </div>

                {{-- Styling Toggle --}}
                <div class="flex items-center justify-between px-2 py-2 border-b border-gray-100 mb-2">
                    <span class="text-[10px] font-bold uppercase text-gray-500">Styling</span>
                    <label class="inline-flex items-center cursor-pointer">
                        <input type="checkbox" wire:model.live="show_drop_cap" class="sr-only peer">
                        <div class="relative w-9 h-5 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-red-600"></div>
                        <span class="ms-2 text-xs font-medium text-gray-900">Drop Cap</span>
                    </label>
                </div>

                {{-- MAIN EDITOR --}}
                <div class="space-y-2"
                     x-data="{ 
                        isUploading: false,
                        insert(start, end) {
                            let el = $refs.editor;
                            let text = el.value;
                            let s = el.selectionStart;
                            let e = el.selectionEnd;
                            let replacement = start + text.substring(s, e) + end;
                            el.value = text.substring(0, s) + replacement + text.substring(e);
                            el.dispatchEvent(new Event('input'));
                            setTimeout(() => { el.focus(); el.setSelectionRange(s + start.length, e + start.length); }, 50);
                        }
                     }"
                     x-on:photo-inserted.window="insert('\n<figure>\n  <img src=\'' + $event.detail.url + '\' alt=\'Image\'>\n  <figcaption>Write your caption here...</figcaption>\n</figure>\n', ''); isUploading = false;"
                     x-on:livewire-upload-start="isUploading = true"
                     x-on:livewire-upload-finish="isUploading = false"
                     x-on:livewire-upload-error="isUploading = false"
                >
                    {{-- Hidden Uploader --}}
                    <input type="file" wire:model="photo_upload" class="hidden" x-ref="photoUploader" accept="image/*">

                    {{-- Toolbar --}}
                    <div class="flex items-center gap-1 bg-white border border-gray-200 p-1.5 rounded-lg shadow-sm w-fit mb-2">
                        <button @click="insert('**', '**')" class="p-2 text-gray-500 hover:text-gray-900 hover:bg-gray-100 rounded-md transition" title="Bold"><strong>B</strong></button>
                        <button @click="insert('*', '*')" class="p-2 text-gray-500 hover:text-gray-900 hover:bg-gray-100 rounded-md transition" title="Italic"><em>I</em></button>
                        <div class="w-px h-4 bg-gray-200 mx-1"></div>
                        <button @click="insert('### ', '')" class="p-2 text-gray-500 hover:text-gray-900 hover:bg-gray-100 rounded-md text-xs font-bold">H3</button>
                        <button @click="insert('#### ', '')" class="p-2 text-gray-500 hover:text-gray-900 hover:bg-gray-100 rounded-md text-xs font-bold">H4</button>
                        <div class="w-px h-4 bg-gray-200 mx-1"></div>
                        <button @click="insert('> ', '')" class="p-2 text-gray-500 hover:text-gray-900 hover:bg-gray-100 rounded-md" title="Quote">&ldquo;</button>
                        
                        {{-- IMG Button --}}
                        <button @click="$refs.photoUploader.click()" class="p-2 hover:bg-gray-100 rounded text-xs font-bold relative group" title="Upload Photo">
                            <span x-show="!isUploading">IMG</span>
                            <span x-show="isUploading" class="animate-spin block w-4 h-4 border-2 border-gray-300 border-t-red-600 rounded-full"></span>
                        </button>
                    </div>
                    
                    {{-- Textarea --}}
                    <div class="relative group">
                        <input wire:model.live="title" type="text" class="w-full text-3xl font-serif font-bold text-gray-900 bg-transparent border-0 border-b-2 border-transparent focus:border-red-500 focus:ring-0 placeholder-gray-300 transition px-0 mb-4" placeholder="Headline...">
                        
                        <textarea x-ref="editor"
                                  wire:model.live="content" 
                                  rows="12" 
                                  class="w-full text-lg leading-relaxed text-gray-700 bg-transparent border-2 border-transparent focus:border-gray-200 rounded-xl p-4 focus:ring-0 resize-none font-serif placeholder-gray-300 transition-all focus:bg-white"
                                  placeholder="Start writing your story..."></textarea>
                    </div>
                </div>

                {{-- Tags Input --}}
                <div class="bg-white p-4 rounded-xl border border-gray-100">
                    <label class="block text-xs font-bold text-gray-700 mb-2">Tags</label>
                    <input wire:model.live="tags" type="text" class="w-full text-sm border-gray-200 rounded-lg focus:ring-yellow-400 focus:border-yellow-400" placeholder="Youth, Leadership...">
                </div>

                <div class="h-10"></div>
            </div>
        </div>

        {{-- ======================== --}}
        {{-- RIGHT PANEL: PREVIEW     --}}
        {{-- ======================== --}}
        <div class="h-full overflow-y-auto bg-stone-100 relative shadow-inner transition-all duration-300 ease-in-out"
             :class="[
                mobilePreview ? 'block w-full' : 'hidden md:block',
                fullScreen ? 'w-full' : 'md:w-1/2 lg:w-7/12'
             ]">
            
            {{-- Floating Controls --}}
            <div class="fixed top-20 right-8 z-50 flex gap-2">
                <button @click="fullScreen = !fullScreen" class="bg-white/80 backdrop-blur text-gray-600 hover:text-red-600 p-2 rounded-full shadow-md border border-gray-200 transition-all hover:scale-110" title="Toggle Full Screen">
                    <svg x-show="!fullScreen" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"></path></svg>
                    <svg x-show="fullScreen" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 10L4 15m0 0l5 5m-5-5h16M15 14l5-5m0 0l-5-5m5 5H4"></path></svg>
                </button>
            </div>

            <div class="min-h-full bg-stone-50 pb-20 origin-top transition-transform" :class="fullScreen ? 'scale-100 px-0' : 'scale-90 md:scale-100'">
                
                {{-- PREVIEW CONTENT --}}
                <header class="relative pt-20 pb-12 px-6 z-10 text-center max-w-4xl mx-auto">
                    <span class="px-4 py-1 bg-red-50 text-red-600 text-[10px] font-black uppercase tracking-[0.2em] rounded-full border border-red-100 mb-6 inline-block">{{ $category }}</span>
                    <h1 class="font-heading text-4xl md:text-6xl font-black text-gray-900 leading-tight mb-8">
                        {{ $title ?: 'Article Headline' }}
                    </h1>

                    <div class="inline-flex items-center gap-4 bg-white/60 px-6 py-2 rounded-full border border-white/40 shadow-sm">
                        <div class="w-8 h-8 rounded-full bg-gradient-to-br from-red-500 to-yellow-500 flex items-center justify-center text-white font-bold text-xs">
                             {{ substr($author, 0, 1) }}
                        </div>
                        <div class="text-left">
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Written By</p>
                            <p class="text-xs font-bold text-gray-800">{{ $author }}</p>
                        </div>
                    </div>
                </header>

                {{-- IMAGE + CREDIT --}}
                {{-- PREVIEW IMAGE CONTAINER --}}
                <div class="w-full max-w-5xl mx-auto px-6 mb-12">
                    <div class="relative group aspect-[21/9] overflow-hidden rounded-3xl shadow-2xl bg-gray-200">
                        
                        {{-- PRIORITY 1: New Upload (Live Preview) --}}
                        {{-- If you just dragged a file, this shows it immediately --}}
                        @if ($cover_photo)
                            <img src="{{ $cover_photo->temporaryUrl() }}" class="w-full h-full object-cover">
                        
                        {{-- PRIORITY 2: Existing Database Image --}}
                        {{-- If you are editing a saved draft, this shows the saved image --}}
                        @elseif($imageUrl)
                            <img src="{{ Str::startsWith($imageUrl, 'http') ? $imageUrl : asset('storage/'.$imageUrl) }}" class="w-full h-full object-cover">
                        
                        {{-- PRIORITY 3: Empty State --}}
                        @else
                            <div class="w-full h-full flex items-center justify-center text-gray-400 font-bold uppercase tracking-widest text-xs">
                                No Cover Image
                            </div>
                        @endif

                        {{-- Photo Credit Overlay --}}
                        @if($photo_credit)
                        <div class="absolute bottom-4 right-4 bg-black/60 backdrop-blur px-3 py-1.5 rounded-lg border border-white/10">
                            <p class="text-[10px] text-white/90 italic">{{ $photo_credit }}</p>
                        </div>
                        @endif
                    </div>
                </div>

                {{-- CONTENT LAYOUT --}}
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

                            {{-- SDGs PREVIEW --}}
                            @if(count($selectedSdgs) > 0)
                            <div class="flex flex-wrap gap-2">
                                @foreach($selectedSdgs as $selectedId)
                                    @php 
                                        $s = $sdgOptions->firstWhere('id', $selectedId); 
                                    @endphp
                                    
                                    @if($s)
                                        {{-- Preview Badge --}}
                                        <div style="background-color: {{ $s->color_hex }}" 
                                            class="w-8 h-8 rounded flex items-center justify-center text-white font-black text-xs shadow-sm transform hover:scale-110 transition" 
                                            title="{{ $s->name }}">
                                            {{ $s->id }}
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                            @endif
                        </div>
                    </aside>

                    <article class="lg:col-span-7">
                        <div class="prose prose-lg prose-red font-serif text-gray-600 leading-8 max-w-none {{ $show_drop_cap ? "[&>p:first-child]:first-letter:text-6xl [&>p:first-child]:first-letter:font-black [&>p:first-child]:first-letter:text-red-600 [&>p:first-child]:first-letter:mr-3 [&>p:first-child]:first-letter:float-left" : '' }}
                            [&_figcaption]:text-center [&_figcaption]:text-sm [&_figcaption]:text-gray-500 [&_figcaption]:italic [&_figcaption]:mt-2
                            [&_img]:rounded-xl [&_img]:shadow-lg" > 
                            {!! Str::markdown($content) !!}
                        </div>
                        <div class="mt-12 pt-8 border-t border-gray-100 flex flex-wrap gap-2">
                            @if($tags)
                                @foreach(explode(',', $tags) as $tag)
                                <span class="px-4 py-1.5 bg-gray-50 text-gray-500 text-xs font-bold uppercase tracking-wider rounded-lg border border-gray-100">#{{ trim($tag) }}</span>
                                @endforeach
                            @endif
                        </div>
                    </article>
                    <div class="hidden lg:block lg:col-span-2"></div>
                </div>
            </div>
        </div>
    </div>
</div>