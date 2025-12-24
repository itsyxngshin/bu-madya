<div class="min-h-screen bg-gray-100 font-sans text-gray-900" x-data="{ mobilePreview: false }">

    {{-- NAV (Keep existing code) --}}
    <nav class="sticky top-0 z-50 bg-white/90 backdrop-blur-md border-b border-gray-200 h-16 px-6 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <a href="{{ route('news.index') }}" class="text-xs font-bold uppercase text-gray-400 hover:text-red-600 transition">&larr; Exit</a>
            <div class="h-4 w-px bg-gray-300"></div>
            <span class="font-heading font-black text-gray-800 tracking-tight">Editor <span class="text-red-600">Mode</span></span>
        </div>
        <button @click="mobilePreview = !mobilePreview" class="md:hidden text-xs font-bold uppercase bg-gray-200 px-3 py-1 rounded">
            <span x-text="mobilePreview ? 'Edit' : 'Preview'"></span>
        </button>
        <div class="flex items-center gap-3">
            <span class="text-xs text-green-600 font-bold mr-2" x-data="{ show: false }" x-show="show" x-transition.duration.1000ms x-init="@this.on('message', () => { show = true; setTimeout(() => show = false, 2000) })">Saved!</span>
            <button wire:click="saveDraft" class="px-4 py-2 bg-white border border-gray-300 text-gray-600 text-xs font-bold uppercase rounded-lg hover:bg-gray-50 transition shadow-sm">Save Draft</button>
            <button wire:click="publish" class="px-5 py-2 bg-gradient-to-r from-red-600 to-yellow-500 text-white text-xs font-bold uppercase rounded-lg hover:shadow-lg hover:scale-105 transition shadow-md">Publish</button>
        </div>
    </nav>

    <div class="flex h-[calc(100vh-64px)] overflow-hidden">
        
        {{-- ======================== --}}
        {{-- LEFT PANEL: EDITOR       --}}
        {{-- ======================== --}}
        <div class="w-full md:w-1/2 lg:w-5/12 h-full overflow-y-auto p-6 bg-gray-50 border-r border-gray-200"
             :class="mobilePreview ? 'hidden' : 'block'">
            
            <div class="max-w-xl mx-auto space-y-6">
                
                {{-- Metadata Card (Keep existing) --}}
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 space-y-4">
                    <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest border-b border-gray-100 pb-2">Article Metadata</h3>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">Category</label>
                            <select wire:model.live="category" class="w-full text-sm border-gray-200 rounded-lg focus:ring-yellow-400 focus:border-yellow-400">
                                <option>Advocacy</option>
                                <option>Event</option>
                                <option>Announcement</option>
                                <option>Opinion</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">Author Name</label>
                            <input wire:model.live="author" type="text" class="w-full text-sm border-gray-200 rounded-lg focus:ring-yellow-400 focus:border-yellow-400">
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Cover Image URL</label>
                        <input wire:model.live="imageUrl" type="text" class="w-full text-xs text-gray-500 border-gray-200 rounded-lg focus:ring-yellow-400 focus:border-yellow-400" placeholder="https://...">
                    </div>
                </div>

                {{-- MAIN EDITOR AREA --}}
                <div class="space-y-2"
                     x-data="{ 
                        insert(start, end) {
                            let el = $refs.editor;
                            let text = el.value;
                            let s = el.selectionStart;
                            let e = el.selectionEnd;
                            let selected = text.substring(s, e);
                            let replacement = start + selected + end;
                            
                            // Update Livewire model manually via event dispatch
                            el.value = text.substring(0, s) + replacement + text.substring(e);
                            el.dispatchEvent(new Event('input'));
                            
                            // Restore cursor focus
                            setTimeout(() => {
                                el.focus();
                                el.setSelectionRange(s + start.length, e + start.length);
                            }, 50);
                        }
                     }">
                    
                    {{-- Title Input --}}
                    <input wire:model.live="title" type="text" 
                           class="w-full text-3xl font-serif font-bold text-gray-900 bg-transparent border-0 border-b-2 border-transparent focus:border-red-500 focus:ring-0 placeholder-gray-300 transition px-0" 
                           placeholder="Enter Headline Here...">

                    {{-- 👇 FORMATTING TOOLBAR --}}
                    <div class="flex items-center gap-1 bg-white border border-gray-200 p-1.5 rounded-lg shadow-sm w-fit mb-2">
                        
                        {{-- Bold --}}
                        <button @click="insert('**', '**')" class="p-2 text-gray-500 hover:text-gray-900 hover:bg-gray-100 rounded-md transition" title="Bold">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 4h8a4 4 0 014 4 4 4 0 01-4 4H6V4zm0 8h9a4 4 0 014 4 4 4 0 01-4 4H6v-8z"></path></svg>
                        </button>
                        
                        {{-- Italic --}}
                        <button @click="insert('*', '*')" class="p-2 text-gray-500 hover:text-gray-900 hover:bg-gray-100 rounded-md transition" title="Italic">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path></svg> <span class="font-serif italic font-bold">I</span>
                        </button>

                        <div class="w-px h-4 bg-gray-200 mx-1"></div>

                        {{-- Headlines --}}
                        <button @click="insert('### ', '')" class="p-2 text-gray-500 hover:text-gray-900 hover:bg-gray-100 rounded-md transition text-xs font-bold" title="Subheading">
                            H3
                        </button>
                        <button @click="insert('#### ', '')" class="p-2 text-gray-500 hover:text-gray-900 hover:bg-gray-100 rounded-md transition text-xs font-bold" title="Minor Heading">
                            H4
                        </button>

                        <div class="w-px h-4 bg-gray-200 mx-1"></div>

                        {{-- Insert Image --}}
                        <button @click="insert('\n![Image Description](', ')\n')" class="p-2 text-gray-500 hover:text-red-600 hover:bg-red-50 rounded-md transition" title="Insert Image Link">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        </button>

                        {{-- Quote --}}
                        <button @click="insert('> ', '')" class="p-2 text-gray-500 hover:text-gray-900 hover:bg-gray-100 rounded-md transition" title="Quote">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path></svg>
                        </button>
                    </div>
                    
                    {{-- Textarea --}}
                    <div class="relative group">
                        <textarea x-ref="editor"
                                  wire:model.live="content" 
                                  rows="20" 
                                  class="w-full text-lg leading-relaxed text-gray-700 bg-transparent border-2 border-transparent focus:border-gray-200 rounded-xl p-4 focus:ring-0 resize-none font-serif placeholder-gray-300 transition-all focus:bg-white"
                                  placeholder="Start writing your story..."></textarea>
                    </div>
                </div>

                {{-- Tags Input (Keep existing) --}}
                <div class="bg-white p-4 rounded-xl border border-gray-100">
                    <label class="block text-xs font-bold text-gray-700 mb-2">Tags</label>
                    <input wire:model.live="tags" type="text" class="w-full text-sm border-gray-200 rounded-lg focus:ring-yellow-400 focus:border-yellow-400" placeholder="Youth, Leadership...">
                </div>

            </div>
        </div>

        {{-- ======================== --}}
        {{-- RIGHT PANEL: PREVIEW     --}}
        {{-- ======================== --}}
        <div class="w-full md:w-1/2 lg:w-7/12 h-full overflow-y-auto bg-stone-100 relative shadow-inner"
             :class="mobilePreview ? 'block' : 'hidden md:block'">
            
            <div class="absolute top-4 right-4 z-50 bg-black/80 text-white text-[10px] font-bold uppercase px-3 py-1 rounded-full backdrop-blur pointer-events-none">
                Live Preview (Markdown)
            </div>

            <div class="min-h-full bg-stone-50 pb-20 origin-top scale-90 md:scale-100 transition-transform">
                
                {{-- HERO HEADER --}}
                <header class="relative pt-20 pb-12 px-6 z-10 text-center">
                    <div class="mb-6 flex justify-center">
                        <span class="px-5 py-2 bg-white/60 text-red-600 text-xs font-black uppercase tracking-[0.2em] border border-white/50 rounded-full shadow-lg">
                            {{ $category }}
                        </span>
                    </div>

                    <h1 class="font-heading text-4xl md:text-5xl font-black text-gray-900 leading-[1.1] mb-8">
                        {{ $title ?: 'Article Headline' }}
                    </h1>

                    <div class="inline-flex items-center gap-6 px-8 py-4 bg-white/60 rounded-2xl shadow-lg border border-white/40">
                        {{-- Author info... --}}
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-red-500 to-yellow-500 p-0.5">
                                <div class="w-full h-full rounded-full bg-white flex items-center justify-center text-red-600 font-bold text-sm">
                                    {{ substr($author, 0, 1) }}
                                </div>
                            </div>
                            <div class="text-left">
                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Author</p>
                                <p class="text-sm font-bold text-gray-800">{{ $author }}</p>
                            </div>
                        </div>
                        {{-- Date info... --}}
                    </div>
                </header>

                {{-- IMAGE --}}
                <div class="w-full max-w-4xl mx-auto px-6 mb-12">
                    <div class="relative p-2 bg-white/30 rounded-[2.5rem] shadow-xl border border-white/50">
                        <div class="relative aspect-[21/9] overflow-hidden rounded-[2rem] bg-gray-200">
                            @if($imageUrl)
                                <img src="{{ $imageUrl }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-gray-400 text-xs font-bold uppercase tracking-widest">No Image</div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- CONTENT --}}
                <div class="max-w-3xl mx-auto px-6">
                    <div class="bg-white/70 p-8 md:p-12 rounded-[2rem] shadow-xl border border-white/60">
                        
                        {{-- 👇 UPDATED CONTENT RENDERER FOR MARKDOWN --}}
                        <div class="prose prose-lg prose-red font-serif text-gray-600 leading-8">
                            {!! Str::markdown($content) !!}
                        </div>

                        <div class="mt-12 pt-8 border-t border-gray-100 flex flex-wrap gap-2">
                            @if($tags)
                                @foreach(explode(',', $tags) as $tag)
                                <span class="px-4 py-1.5 bg-gray-50 text-gray-500 text-xs font-bold uppercase tracking-wider rounded-lg border border-gray-100">
                                    #{{ trim($tag) }}
                                </span>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>