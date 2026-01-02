<div class="min-h-screen bg-stone-50 font-sans text-gray-900 relative overflow-x-hidden">

    {{-- SEO --}}
    @section('meta_title', 'Transparency Board - BU MADYA')
    @section('meta_description', 'Access our financial reports, memorandums, and accomplishment reports.')

    {{-- 1. BACKGROUND BLOBS (Consistent Style) --}}
    <div class="fixed inset-0 z-0 overflow-hidden pointer-events-none">
        <div class="absolute top-0 left-0 w-full h-full bg-gray-50/80"></div>
        <div class="absolute top-[-10%] right-[-10%] w-[500px] h-[500px] bg-red-200 rounded-full mix-blend-multiply filter blur-3xl opacity-40 animate-blob"></div>
        <div class="absolute top-[20%] left-[-10%] w-[500px] h-[500px] bg-yellow-200 rounded-full mix-blend-multiply filter blur-3xl opacity-40 animate-blob animation-delay-2000"></div>
        <div class="absolute bottom-[-10%] right-[20%] w-[500px] h-[500px] bg-green-200 rounded-full mix-blend-multiply filter blur-3xl opacity-40 animate-blob animation-delay-4000"></div>
    </div>

    {{-- 2. HERO HEADER --}}
    <header class="relative z-10 pt-32 pb-12 px-6">
        <div class="max-w-7xl mx-auto text-center">
            <span class="inline-block py-1 px-3 rounded-full bg-red-50 border border-red-100 text-red-600 font-bold tracking-widest uppercase text-[10px] mb-4 shadow-sm">
                Public Disclosure
            </span>
            <h1 class="font-heading font-black text-4xl md:text-6xl text-gray-900 mb-6 drop-shadow-sm">
                Transparency <span class="text-transparent bg-clip-text bg-gradient-to-r from-red-600 to-yellow-500">Board</span>
            </h1>
            <p class="text-gray-500 text-lg max-w-2xl mx-auto leading-relaxed">
                Review our official financial statements, memorandums, and accomplishment reports. We believe in open governance.
            </p>
        </div>
    </header>

    {{-- 3. FILTERS & CONTROLS --}}
    <div class="sticky top-20 z-30 bg-stone-50/90 backdrop-blur-xl border-y border-gray-200/50 py-4 mb-12 shadow-sm">
        <div class="max-w-7xl mx-auto px-6 flex flex-col lg:flex-row justify-between items-center gap-4">
            
            {{-- Category Tabs --}}
            <div class="flex gap-2 overflow-x-auto pb-2 lg:pb-0 max-w-full no-scrollbar w-full lg:w-auto">
                <button wire:click="setCategory('all')" 
                        class="whitespace-nowrap px-5 py-2.5 rounded-full text-xs font-bold uppercase tracking-wider transition-all border
                        {{ $filter_category === 'all' 
                            ? 'bg-gray-900 text-white border-gray-900 shadow-lg scale-105' 
                            : 'bg-white text-gray-500 border-gray-200 hover:border-gray-300 hover:text-gray-900' }}">
                    All Files
                </button>

                @foreach($categories as $cat)
                    <button wire:click="setCategory({{ $cat->id }})" 
                            class="whitespace-nowrap px-5 py-2.5 rounded-full text-xs font-bold uppercase tracking-wider transition-all border
                            {{ $filter_category === $cat->id 
                                ? 'bg-red-600 text-white border-red-600 shadow-red-200 shadow-lg scale-105' 
                                : 'bg-white text-gray-500 border-gray-200 hover:border-gray-300 hover:text-gray-900' }}">
                        {{ $cat->name }}
                    </button>
                @endforeach
            </div>

            <div class="flex gap-3 w-full lg:w-auto">
                {{-- Year Dropdown --}}
                <div class="relative min-w-[140px]">
                    <select wire:model.live="filter_year" class="w-full appearance-none pl-4 pr-10 py-2.5 rounded-xl border-gray-200 bg-white text-sm focus:ring-red-500 focus:border-red-500 shadow-sm font-bold text-gray-600 cursor-pointer hover:border-gray-300 transition">
                        <option value="all">All Years</option>
                        @foreach($years as $year)
                            <option value="{{ $year }}">A.Y. {{ $year }}</option>
                        @endforeach
                    </select>
                    <svg class="w-4 h-4 text-gray-400 absolute right-3 top-3 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </div>

                {{-- Search --}}
                <div class="relative w-full lg:w-64 group">
                    <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search files..." 
                           class="w-full pl-10 pr-4 py-2.5 rounded-xl border-gray-200 bg-white text-sm focus:ring-red-500 focus:border-red-500 shadow-sm transition">
                    <svg class="w-4 h-4 text-gray-400 absolute left-3 top-3 group-hover:text-red-500 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
            </div>
        </div>
    </div>

    {{-- 4. DOCUMENTS GRID --}}
    <div class="relative z-10 max-w-7xl mx-auto px-6 pb-24">
        
        {{-- Loading State --}}
        <div wire:loading class="w-full text-center py-12">
            <div class="inline-flex items-center gap-2 text-gray-400 text-sm font-bold animate-pulse">
                <svg class="animate-spin h-5 w-5 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                Fetching documents...
            </div>
        </div>

        <div wire:loading.remove>
            @if($documents->count() > 0)
                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($documents as $doc)
                        <div class="group bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 relative flex flex-col h-full">
                            
                            {{-- Color Bar (Dynamic based on Category Color) --}}
                            <div class="absolute top-0 left-0 w-1.5 h-full rounded-l-2xl 
                                bg-{{ $doc->category->color ?? 'gray' }}-500 transition-colors"></div>

                            {{-- Card Header --}}
                            <div class="flex justify-between items-start mb-4 pl-3">
                                {{-- Category Badge --}}
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg 
                                    bg-{{ $doc->category->color ?? 'gray' }}-50 
                                    text-{{ $doc->category->color ?? 'gray' }}-600 
                                    text-[10px] font-black uppercase tracking-widest border border-{{ $doc->category->color ?? 'gray' }}-100">
                                    {{ $doc->category->name }}
                                </span>
                                
                                {{-- Year Badge --}}
                                <span class="bg-gray-50 text-gray-500 text-[10px] font-bold px-2 py-1 rounded-lg border border-gray-200">
                                    {{ $doc->academic_year }}
                                </span>
                            </div>

                            {{-- Card Content --}}
                            <div class="pl-3 flex-grow">
                                <h3 class="font-heading font-bold text-lg text-gray-900 mb-2 leading-tight group-hover:text-red-600 transition line-clamp-2" title="{{ $doc->title }}">
                                    {{ $doc->title }}
                                </h3>
                                <div class="flex items-center gap-2 text-xs text-gray-400 mb-3 font-mono">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    {{ $doc->published_date->format('M d, Y') }}
                                </div>
                                <p class="text-sm text-gray-500 line-clamp-2 mb-4">
                                    {{ $doc->description ?? 'No description provided.' }}
                                </p>
                            </div>

                            {{-- Card Footer & Actions --}}
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

                {{-- Pagination --}}
                <div class="mt-12">
                    {{ $documents->links() }}
                </div>
            @else
                {{-- Empty State --}}
                <div class="flex flex-col items-center justify-center py-24 bg-white/50 backdrop-blur-sm rounded-[2rem] border-2 border-dashed border-gray-200">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center text-gray-300 mb-4">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-1">No documents found</h3>
                    <p class="text-gray-500 text-sm">Try adjusting your filters or check back later.</p>
                    <button wire:click="$set('filter_category', 'all')" class="mt-4 text-red-600 text-xs font-bold uppercase tracking-widest hover:underline">Clear Filters</button>
                </div>
            @endif
        </div>
    </div>
    <footer class="bg-gray-900 text-white pt-20 pb-10 border-t-8 border-red-600 relative z-20">
        <div class="max-w-[1800px] w-[95%] mx-auto px-6 grid md:grid-cols-4 gap-12 mb-16">
            
            <div class="col-span-1 md:col-span-2">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 bg-red-600 text-white rounded-full flex items-center justify-center shadow-[0_0_15px_rgba(220,38,38,0.5)]">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.879 16.121A3 3 0 1012.015 11L11 14H9c0 .768.293 1.536.879 2.121z"></path></svg>
                    </div>
                    <span class="font-heading font-bold text-2xl tracking-tight">BU MADYA</span>
                </div>
                <p class="text-gray-400 leading-relaxed max-w-sm mb-6 text-sm">
                    The Bicol University - Movement for the Advancement of Youth-led Advocacy is a duly-accredited University Based Organization in Bicol University committed to service and reaching communities through advocacy.
                </p>
                
                {{-- Social Media Links --}}
                <div class="flex space-x-4">
                    {{-- Facebook --}}
                    <a href="www.facebook.com/BUMadya" class="w-10 h-10 rounded-full bg-gray-800 flex items-center justify-center hover:bg-blue-600 hover:text-white text-gray-400 transition">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                    </a>

                    {{-- Instagram --}}
                    <a href="https://www.instagram.com/bu_madya" class="w-10 h-10 rounded-full bg-gray-800 flex items-center justify-center hover:bg-pink-600 hover:text-white text-gray-400 transition">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                    </a>

                    {{-- X (Twitter) --}}
                    <a href="https://www.x.com/bu_madya" class="w-10 h-10 rounded-full bg-gray-800 flex items-center justify-center hover:bg-black hover:text-white text-gray-400 transition">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                    </a>
                </div>
            </div>
            
            <div>
                <h4 class="font-bold text-lg mb-6 text-red-500 uppercase tracking-widest text-xs">Quick Links</h4>
                <ul class="space-y-3 text-gray-400 text-sm">
                    <li><a href="{{ route('about') }}" class="hover:text-white hover:translate-x-1 transition inline-block">About BU MADYA</a></li>
                    <li><a href="{{ route('open.directory') }}" class="hover:text-white hover:translate-x-1 transition inline-block">Our Officers</a></li>
                    <li><a href="{{ route('transparency.index') }}" class="hover:text-white hover:translate-x-1 transition inline-block">Transparency Board</a></li>
                </ul>
            </div>

            <div>
                <h4 class="font-bold text-lg mb-6 text-green-500 uppercase tracking-widest text-xs">Live Stats</h4>
                <div class="bg-gray-800 p-6 rounded-2xl border border-gray-700 shadow-inner">
                    <span class="block text-[10px] uppercase tracking-widest text-gray-500 mb-2">Total Visitors</span>
                    <div class="text-4xl font-mono text-yellow-400 tracking-widest">
                        {{ str_pad($visitorCount ?? 0, 7, '0', STR_PAD_LEFT) }}
                    </div>
                </div>
            </div>
        </div>
        <div class="border-t border-gray-800 pt-8 text-center text-gray-600 text-xs uppercase tracking-widest">
            &copy; {{ date('Y') }} BU MADYA. All Rights Reserved.
        </div>
    </footer>
</div>