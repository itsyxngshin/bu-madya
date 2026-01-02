<div class="min-h-screen bg-stone-50 font-sans text-gray-900 relative overflow-x-hidden">

    {{-- SEO --}}
    @section('meta_title', 'Transparency Board - BU MADYA')
    @section('meta_description', 'Access our financial reports, memorandums, and accomplishment reports.')

    {{-- 1. BACKGROUND DECOR (Subtle & Professional) --}}
    <div class="fixed inset-0 z-0 overflow-hidden pointer-events-none">
        <div class="absolute top-0 left-0 w-full h-96 bg-gradient-to-b from-white to-stone-50"></div>
        {{-- Subtle blobs for brand identity --}}
        <div class="absolute top-[-10%] right-[-5%] w-[600px] h-[600px] bg-red-50/60 rounded-full blur-[100px] mix-blend-multiply"></div>
        <div class="absolute top-[20%] left-[-10%] w-[500px] h-[500px] bg-yellow-50/60 rounded-full blur-[80px] mix-blend-multiply"></div>
    </div>

    {{-- 2. HERO HEADER --}}
    <header class="relative z-10 pt-40 pb-16 px-6"> {{-- Increased PT to 40 to clear navbar --}}
        <div class="max-w-7xl mx-auto text-center">
            
            {{-- Eyebrow Badge --}}
            <span class="inline-flex items-center gap-2 py-1.5 px-4 rounded-full bg-white border border-gray-200 shadow-sm text-gray-600 font-bold tracking-widest uppercase text-[10px] mb-6">
                <span class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></span>
                Public Disclosure & Open Governance
            </span>

            <h1 class="font-heading font-black text-4xl md:text-6xl text-gray-900 mb-6 drop-shadow-sm tracking-tight">
                Transparency <span class="text-transparent bg-clip-text bg-gradient-to-r from-red-600 to-yellow-500">Board</span>
            </h1>
            
            <p class="text-gray-500 text-lg md:text-xl max-w-2xl mx-auto leading-relaxed font-medium">
                Access our official financial statements, memorandums, and accomplishment reports. We believe trust is built through openness.
            </p>
        </div>
    </header>

    {{-- 3. STICKY FILTER BAR --}}
    <div class="sticky top-24 z-30 px-6 mb-12"> {{-- Top-24 ensures it sits below your main navbar --}}
        <div class="max-w-7xl mx-auto">
            <div class="bg-white/80 backdrop-blur-xl border border-gray-200/60 p-2 rounded-2xl shadow-xl shadow-gray-200/40 flex flex-col lg:flex-row justify-between items-center gap-4">
                
                {{-- Category Tabs --}}
                <div class="flex gap-1 overflow-x-auto w-full lg:w-auto p-1 no-scrollbar">
                    <button wire:click="setCategory('all')" 
                            class="whitespace-nowrap px-6 py-2.5 rounded-xl text-xs font-bold uppercase tracking-wider transition-all duration-300
                            {{ $filter_category === 'all' 
                                ? 'bg-gray-900 text-white shadow-md transform scale-105' 
                                : 'text-gray-500 hover:bg-gray-100' }}">
                        All Files
                    </button>

                    @foreach($categories as $cat)
                        <button wire:click="setCategory({{ $cat->id }})" 
                                class="whitespace-nowrap px-6 py-2.5 rounded-xl text-xs font-bold uppercase tracking-wider transition-all duration-300
                                {{ $filter_category === $cat->id 
                                    ? 'bg-red-50 text-red-600 ring-1 ring-red-100 shadow-sm transform scale-105' 
                                    : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900' }}">
                            {{ $cat->name }}
                        </button>
                    @endforeach
                </div>

                <div class="flex gap-2 w-full lg:w-auto">
                    {{-- Year Dropdown --}}
                    <div class="relative min-w-[140px]">
                        <select wire:model.live="filter_year" class="w-full appearance-none pl-4 pr-10 py-2.5 rounded-xl border-none bg-gray-50 text-sm font-bold text-gray-700 focus:ring-2 focus:ring-red-500 cursor-pointer transition hover:bg-gray-100">
                            <option value="all">All Years</option>
                            @foreach($years as $year)
                                <option value="{{ $year }}">A.Y. {{ $year }}</option>
                            @endforeach
                        </select>
                        {{-- Custom Arrow --}}
                        <svg class="w-4 h-4 text-gray-500 absolute right-3 top-3 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </div>

                    {{-- Search --}}
                    <div class="relative w-full lg:w-64 group">
                        <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search..." 
                               class="w-full pl-10 pr-4 py-2.5 rounded-xl border-none bg-gray-50 text-sm focus:ring-2 focus:ring-red-500 transition placeholder-gray-400 group-hover:bg-gray-100">
                        <svg class="w-4 h-4 text-gray-400 absolute left-3 top-3 group-hover:text-red-500 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- 4. DOCUMENTS GRID --}}
    <div class="relative z-10 max-w-7xl mx-auto px-6 pb-24">
        
        {{-- Loading State --}}
        <div wire:loading class="w-full py-12">
            <div class="flex flex-col items-center justify-center gap-3 text-gray-400 animate-pulse">
                <div class="w-12 h-12 rounded-full bg-gray-200"></div>
                <div class="h-4 w-32 bg-gray-200 rounded"></div>
            </div>
        </div>

        <div wire:loading.remove>
            @if($documents->count() > 0)
                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($documents as $doc)
                        <div class="group bg-white rounded-2xl p-6 shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04)] hover:shadow-[0_8px_30px_rgb(0,0,0,0.12)] border border-gray-100 hover:border-red-100 hover:-translate-y-1 transition-all duration-300 relative flex flex-col h-full">
                            
                            {{-- Color Spine --}}
                            <div class="absolute top-4 left-0 w-1 h-12 rounded-r-full 
                                bg-{{ $doc->category->color ?? 'gray' }}-500"></div>

                            {{-- Card Header --}}
                            <div class="flex justify-between items-start mb-4 pl-4">
                                <div class="flex flex-col">
                                    <span class="text-[10px] font-black uppercase tracking-widest text-{{ $doc->category->color ?? 'gray' }}-600 mb-1">
                                        {{ $doc->category->name }}
                                    </span>
                                    <h3 class="font-heading font-bold text-lg text-gray-900 leading-tight group-hover:text-red-600 transition line-clamp-2" title="{{ $doc->title }}">
                                        {{ $doc->title }}
                                    </h3>
                                </div>
                            </div>

                            {{-- Meta Info --}}
                            <div class="pl-4 mb-4 flex items-center gap-3 text-xs font-mono text-gray-400 border-b border-gray-50 pb-4">
                                <span class="flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    {{ $doc->published_date->format('M d, Y') }}
                                </span>
                                <span class="bg-gray-100 text-gray-600 px-1.5 py-0.5 rounded">
                                    {{ $doc->academic_year }}
                                </span>
                            </div>

                            {{-- Description --}}
                            <div class="pl-4 flex-grow">
                                <p class="text-sm text-gray-500 line-clamp-2 leading-relaxed">
                                    {{ $doc->description ?? 'No description provided for this document.' }}
                                </p>
                            </div>

                            {{-- Footer Actions --}}
                            <div class="pl-3 pt-4 mt-auto border-t border-gray-100 flex items-center justify-between">
                                <div class="flex flex-col">
                                    <span class="text-[10px] font-bold uppercase tracking-widest text-gray-400">
                                        {{ strtoupper(pathinfo($doc->file_path, PATHINFO_EXTENSION)) }} FILE
                                    </span>
                                    
                                    {{-- Visibility Badge (Optional: helps users know why they see it) --}}
                                    @if($doc->visibility === 'auth')
                                        <span class="text-[9px] font-bold text-blue-600 flex items-center gap-1 mt-0.5">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                            Members Only
                                        </span>
                                    @endif
                                </div>
                                
                                {{-- LIVEWIRE DOWNLOAD BUTTON --}}
                                {{-- 'wire:loading.attr="disabled"' prevents double clicks --}}
                                <button wire:click="download({{ $doc->id }})" 
                                        wire:loading.attr="disabled"
                                        class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-gray-900 text-white text-xs font-bold uppercase tracking-widest hover:bg-red-600 hover:shadow-lg hover:-translate-y-0.5 transition-all shadow-md group-hover:shadow-xl disabled:opacity-50 disabled:cursor-wait">
                                    
                                    {{-- Normal Icon --}}
                                    <svg wire:loading.remove wire:target="download({{ $doc->id }})" class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                    
                                    {{-- Loading Spinner (Shows only when clicking THIS specific button) --}}
                                    <svg wire:loading wire:target="download({{ $doc->id }})" class="animate-spin w-3 h-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                    
                                    Download
                                </button>
                            </div>

                        </div>
                    @endforeach
                </div>

                <div class="mt-12">
                    {{ $documents->links() }}
                </div>
            @else
                {{-- REFINED EMPTY STATE --}}
                <div class="flex flex-col items-center justify-center py-24 px-4">
                    <div class="relative mb-6">
                        <div class="absolute inset-0 bg-red-100 rounded-full blur-xl opacity-50"></div>
                        <div class="relative w-24 h-24 bg-white rounded-full shadow-lg flex items-center justify-center text-red-100">
                            <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        </div>
                    </div>
                    <h3 class="text-xl font-black text-gray-900 mb-2">No documents found</h3>
                    <p class="text-gray-500 text-sm mb-6 max-w-xs text-center leading-relaxed">
                        We couldn't find any documents matching your current filters. 
                    </p>
                    <button wire:click="$set('filter_category', 'all')" 
                            class="px-6 py-2 bg-gray-900 text-white text-xs font-bold uppercase tracking-widest rounded-xl hover:bg-red-600 transition shadow-lg">
                        Clear Filters
                    </button>
                </div>
            @endif
        </div>
    </div>
</div>