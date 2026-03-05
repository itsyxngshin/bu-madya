@section('meta_title', $linkage->name)
@section('meta_description', $linkage->description ?? Str::limit(strip_tags($linkage->description), 150))
@php
    // 1. Determine the image URL using PHP logic
    $ogImage = $linkage->cover_img_path 
        ? (Str::startsWith($linkage->cover_img_path, 'http') ? $linkage->cover_img_path : asset('storage/' . $linkage->cover_img_path))
        : asset('images/default_news.jpg');
@endphp
@section('meta_image', $ogImage)

<div class="min-h-screen bg-stone-50 font-sans text-gray-900">
    
    {{-- 1. STICKY NAV --}}
    <div class="fixed top-0 left-0 w-full z-50 bg-white/90 backdrop-blur-md border-b border-gray-200 h-16 flex items-center justify-between px-6 transition-all duration-300">
        <a href="{{ route('linkages.index') }}" class="group flex items-center gap-2 text-xs font-bold uppercase tracking-widest text-gray-500 hover:text-red-600 transition">
            <div class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center group-hover:bg-red-50 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </div>
            <span class="hidden md:inline">Back to Linkage</span>
        </a>
        <span class="font-heading font-black text-lg tracking-tighter text-gray-900">
            Partner <span class="text-blue-600">Profile</span>
        </span>
        <div class="flex items-center gap-3">
            {{-- Only show Edit button to authorized users --}}
            @auth
            <a href="{{ route('linkages.edit',['linkage' => $linkage->slug]) }}" class="flex items-center gap-2 px-4 py-2 bg-gray-100 hover:bg-blue-50 text-gray-600 hover:text-blue-600 rounded-full text-xs font-bold uppercase tracking-wider transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                Edit
            </a>
            @endauth
        </div>
    </div>

    {{-- 2. HERO COVER --}}
    <div class="relative h-[300px] md:h-[400px] w-full overflow-hidden bg-gray-200">
        @if($linkage->cover_img_path)
            <img src="{{ asset('storage/' . $linkage->cover_img_path) }}" class="w-full h-full object-cover">
        @else
            <div class="w-full h-full flex items-center justify-center text-gray-400 font-bold uppercase tracking-widest">No Cover Image</div>
        @endif
        <div class="absolute inset-0 bg-gradient-to-t from-stone-50 via-stone-50/20 to-transparent"></div>
    </div>

    {{-- 3. MAIN CONTENT CONTAINER --}}
    <div class="max-w-7xl mx-auto px-6 -mt-32 relative z-10 pb-24">
        
        <div class="grid lg:grid-cols-12 gap-8">
            
            {{-- LEFT COLUMN: IDENTITY CARD --}}
            <aside class="lg:col-span-4 space-y-8">
                
                {{-- Profile Card --}}
                <div class="bg-white p-8 rounded-[2rem] shadow-xl border border-gray-100 text-center relative">
                    {{-- Status Banner Line --}}
                    <div class="absolute top-0 left-0 w-full h-2 bg-gradient-to-r from-blue-600 to-green-400"></div>

                    {{-- Logo --}}
                    <div class="w-32 h-32 mx-auto bg-white rounded-2xl p-2 shadow-lg -mt-16 mb-6 border border-gray-100 relative z-10 flex items-center justify-center">
                        @if($linkage->logo_path)
                            <img src="{{ asset('storage/' . $linkage->logo_path) }}" class="w-full h-full object-contain rounded-xl">
                        @else
                            <span class="text-xs font-bold text-gray-300">LOGO</span>
                        @endif
                    </div>

                    <h1 class="font-heading font-black text-2xl text-gray-900 leading-tight mb-2">
                        {{ $linkage->name }}
                    </h1>
                    
                    <div class="flex flex-wrap justify-center gap-2 mb-6">
                        @if($linkage->type)
                        <span class="px-3 py-1 bg-gray-100 text-gray-600 text-[10px] font-bold uppercase tracking-wider rounded-full">
                            {{ $linkage->type->name }}
                        </span>
                        @endif

                        @if($linkage->status)
                        <span class="px-3 py-1 {{ $linkage->status->color ?? 'bg-gray-100' }} text-gray-700 text-[10px] font-bold uppercase tracking-wider rounded-full flex items-center gap-1">
                            <span class="w-1.5 h-1.5 rounded-full bg-current"></span> {{ $linkage->status->name }}
                        </span>
                        @endif
                    </div>

                    <div class="text-sm text-gray-500 space-y-3 border-t border-gray-100 pt-6 text-left">
                        <div class="flex items-start gap-3">
                            <svg class="w-4 h-4 text-gray-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            <div>
                                <span class="block text-[10px] font-bold uppercase text-gray-400">Partner Since</span>
                                <span class="font-medium">{{ $linkage->established_at ? $linkage->established_at->format('M Y') : 'N/A' }}</span>
                            </div>
                        </div>
                        
                        @if($linkage->website)
                        <div class="flex items-start gap-3">
                            <svg class="w-4 h-4 text-gray-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <div>
                                <span class="block text-[10px] font-bold uppercase text-gray-400">Website</span>
                                <a href="{{ "https://" . $linkage->website }}" target="_blank" class="font-medium text-blue-600 hover:underline break-all">{{ $linkage->website }}</a>
                            </div>
                        </div>
                        @endif

                        @if($linkage->address)
                        <div class="flex items-start gap-3">
                            <svg class="w-4 h-4 text-gray-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            <div>
                                <span class="block text-[10px] font-bold uppercase text-gray-400">Address</span>
                                <span class="font-medium">{{ $linkage->address }}</span>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                {{-- SDGs Card --}}
                @if($linkage->sdgs->count() > 0)
                <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100">
                    <h3 class="font-bold text-gray-900 uppercase tracking-widest text-xs border-b border-gray-100 pb-3 mb-4">
                        Shared Goals (SDGs)
                    </h3>
                    <div class="flex flex-col gap-3">
                        @foreach($linkage->sdgs as $sdg)
                            {{-- 
                            LOGIC: 
                            1. We append '1A' to the hex code to make the background 10% transparent (Hex Alpha).
                            2. We use the raw hex for the main box and text color.
                            --}}
                            <div class="flex items-center gap-3 p-2 rounded-lg transition hover:brightness-95"
                                style="background-color: {{ $sdg->color_hex }}1A;"> 
                                
                                {{-- SDG Icon Box --}}
                                <div class="w-10 h-10 rounded-lg text-white font-black text-lg flex items-center justify-center shadow-sm shrink-0"
                                    style="background-color: {{ $sdg->color_hex }};">
                                    {{ $sdg->id }}
                                </div>
                                
                                {{-- Text Label --}}
                                <span class="text-xs font-bold uppercase tracking-wide"
                                    style="color: {{ $sdg->color_hex }}; filter: brightness(0.8);"> {{-- Darken text slightly for readability --}}
                                    {{ $sdg->name }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                </div>
                @endif

            </aside>

            {{-- RIGHT COLUMN: CONTENT --}}
            <main class="lg:col-span-8 space-y-12 pt-8 lg:pt-0">
                
                {{-- About Section --}}
                <section>
                    <h3 class="font-bold text-gray-900 uppercase tracking-widest text-xs border-b-2 border-blue-500 w-16 pb-2 mb-6">About</h3>
                    <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100">
                        <p class="text-gray-600 leading-relaxed font-sans text-lg whitespace-pre-line">
                            {{ $linkage->description }}
                        </p>
                        
                        {{-- Scope is not a column in previous schema, but you used it in 'Create'. 
                             If you appended it to description, ignore this. 
                             If you added a column 'scope', render it here: --}}
                        {{-- 
                        <div class="mt-6">
                            <h4 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Scope of Partnership</h4>
                            <div class="flex flex-wrap gap-2">
                                ...
                            </div>
                        </div> 
                        --}}
                    </div>
                </section>

                {{-- Engagement Timeline --}}
                <section>
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="font-bold text-gray-900 uppercase tracking-widest text-xs border-b-2 border-red-500 w-32 pb-2">Partnership Journey</h3>
                        <span class="text-xs font-bold text-gray-400">{{ $linkage->activities->count() }} Activities</span>
                    </div>

                    <div class="relative border-l-2 border-gray-200 ml-4 space-y-8 pl-8 pb-4">
                        @forelse($linkage->activities as $activity)
                        <div class="relative group">
                            {{-- Timeline Dot --}}
                            <div class="absolute -left-[41px] top-1 w-6 h-6 bg-white rounded-full border-4 border-gray-200 group-hover:border-red-500 transition-colors duration-300"></div>

                            <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-2">
                                <h4 class="font-heading font-bold text-lg text-gray-900 group-hover:text-red-600 transition">
                                    {{ $activity->title }}
                                </h4>
                                <span class="text-xs font-bold text-gray-400 bg-gray-100 px-2 py-1 rounded">
                                    {{ $activity->activity_date->format('M d, Y') }}
                                </span>
                            </div>
                            
                            {{-- If you extract Type from description in previous step, parse it here, otherwise just show description --}}
                            <p class="text-sm text-gray-600 leading-relaxed bg-white p-4 rounded-xl shadow-sm border border-gray-100">
                                {{ $activity->description }}
                            </p>
                        </div>
                        @empty
                        <p class="text-gray-400 italic text-sm">No recorded activities yet.</p>
                        @endforelse
                    </div>
                </section>

                {{-- Joint Projects (If applicable) --}}
                @if(isset($linkage->projects) && $linkage->projects->count() > 0)
                <section>
                    <h3 class="font-bold text-gray-900 uppercase tracking-widest text-xs border-b-2 border-yellow-500 w-24 pb-2 mb-6">Joint Projects</h3>
                    
                    <div class="grid sm:grid-cols-2 gap-6">
                        @foreach($linkage->projects as $proj)
                        <a href="{{ route('projects.show', $proj->slug) }}" class="group relative aspect-video rounded-2xl overflow-hidden shadow-md">
                            @if($proj->cover_img)
                                <img src="{{ asset('storage/'.$proj->cover_img) }}" class="w-full h-full object-cover group-hover:scale-110 transition duration-700">
                            @else
                                <div class="w-full h-full bg-gray-200 flex items-center justify-center text-gray-400">No Image</div>
                            @endif
                            <div class="absolute inset-0 bg-gradient-to-t from-black/80 to-transparent opacity-80 group-hover:opacity-100 transition"></div>
                            <div class="absolute bottom-4 left-4">
                                <span class="text-[10px] font-bold text-yellow-400 uppercase tracking-widest">Project</span>
                                <h4 class="font-bold text-white text-lg leading-tight">{{ $proj->title }}</h4>
                            </div>
                        </a>
                        @endforeach
                    </div>
                </section>
                @endif

            </main>
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
                    <a href="https://www.facebook.com/BUMadya" class="w-10 h-10 rounded-full bg-gray-800 flex items-center justify-center hover:bg-blue-600 hover:text-white text-gray-400 transition">
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
            
            <ul class="space-y-3 text-gray-400 text-sm">
                    <li><a href="{{ route('about') }}" class="hover:text-white hover:translate-x-1 transition inline-block">About BU MADYA</a></li>
                    <li><a href="{{ route('open.directory') }}" class="hover:text-white hover:translate-x-1 transition inline-block">Our Officers</a></li>
                    <li><a href="{{ route('transparency.index') }}" class="hover:text-white hover:translate-x-1 transition inline-block">Transparency Board</a></li>
                    <li class="pt-2 mt-2 border-t border-gray-800">
                        <a href="{{ route('privacy') }}" class="text-xs text-gray-500 hover:text-white hover:translate-x-1 transition inline-block">Privacy Policy</a>
                    </li>
            </ul>

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