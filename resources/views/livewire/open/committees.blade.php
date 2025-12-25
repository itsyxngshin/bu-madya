<div class="font-sans antialiased text-gray-800 bg-gray-50 selection:bg-red-500 selection:text-white">
    <nav 
        x-data="{ scrolled: false }" 
        @scroll.window="scrolled = (window.pageYOffset > 20)"
        class="sticky top-0 z-30 transition-all duration-300 w-full"
        :class="scrolled ? 'bg-white/90 backdrop-blur-md shadow-md py-2' : 'bg-transparent py-4'"
    >
        <div class="px-6 flex justify-between items-center">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center shadow-lg overflow-hidden p-1">
                   <img src="{{ asset('images/official_logo.png') }}" alt="Logo" class="w-full h-full object-contain">
                </div>
                <span class="font-bold text-lg tracking-tight transition-colors duration-300"
                      :class="scrolled ? 'text-gray-800' : 'text-gray-800'">
                    BU MADYA
                </span>
            </div>

            <div class="hidden md:flex items-center space-x-6 text-sm font-semibold tracking-wide">
                @foreach(['Home', 'Engagement', 'Advocacy', 'Directory'] as $link)
                <a href="#" 
                   class="transition-colors duration-300 relative group text-gray-600 hover:text-red-600">
                    {{ $link }}
                    <span class="absolute -bottom-1 left-0 h-0.5 bg-red-500 transition-all w-0 group-hover:w-full"></span>
                </a>
                @endforeach
            </div>
        </div>
    </nav>

    {{-- 2. HERO HEADER --}}
    <header class="relative h-[350px] flex items-center justify-center text-white overflow-hidden rounded-3xl shadow-xl mx-6 -mt-20 z-10">
        <div class="absolute inset-0 z-0">
            <img src="https://images.unsplash.com/photo-1517048676732-d65bc937f952?q=80&w=2070&auto=format&fit=crop" 
                 class="w-full h-full object-cover" alt="Committees Background">
            <div class="absolute inset-0 bg-gradient-to-r from-blue-900/90 to-green-900/80 mix-blend-multiply"></div>
        </div>

        <div class="relative z-10 text-center px-4 mt-16">
            <h2 class="text-yellow-300 font-bold tracking-[0.3em] text-xs uppercase mb-2">Our Backbone</h2>
            <h1 class="font-heading text-3xl md:text-5xl font-black uppercase tracking-tight mb-2 drop-shadow-lg">
                Committee Roster
            </h1>
            <p class="text-sm md:text-base text-gray-100 font-light max-w-xl mx-auto italic">
                Driving advocacy through focused action and collaboration.
            </p>
        </div>
    </header>

    {{-- 3. MAIN CONTENT --}}
    <div class="relative min-h-screen px-6 pb-24 mt-12 max-w-[1800px] w-[95%] mx-auto">
        
        {{-- Background Blobs --}}
        <div class="absolute inset-0 z-0 overflow-hidden pointer-events-none">
            <div class="absolute top-40 left-0 w-96 h-96 bg-yellow-100 rounded-full mix-blend-multiply filter blur-3xl opacity-50"></div>
            <div class="absolute bottom-40 right-0 w-96 h-96 bg-green-100 rounded-full mix-blend-multiply filter blur-3xl opacity-50"></div>
        </div>

        {{-- ========================================== --}}
        {{-- SECTION 1: ADVOCACY COMMITTEES (Article V) --}}
        {{-- ========================================== --}}
        <section class="relative z-10 mb-32">
            <div class="flex items-center gap-4 mb-8">
                <div class="w-2 h-10 bg-gradient-to-b from-yellow-400 to-red-500 rounded-full"></div>
                <div>
                    <h2 class="font-heading text-3xl font-bold text-gray-900">Advocacy Committees</h2>
                    <p class="text-sm text-gray-500">Tap card to view Committee Head</p>
                </div>
            </div>

            @php
                $advocacyCommittees = [
                    [
                        'name' => 'Strategic Initiatives & Advocacy',
                        'path' => 'M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z',
                        'desc' => 'Leads development of programs promoting core advocacies. Conducts issue-based research and coordinates with partners.',
                        'heads' => [
                            ['name' => 'Mendones, Lance RJ D.', 'img' => asset('images/MENDONES.jpg')]
                        ]
                    ],
                    [
                        'name' => 'Education',
                        'path' => 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253',
                        'desc' => 'Advocates for inclusive, quality education globally. Promotes education as a fundamental human right.',
                        'heads' => [] // Vacant
                    ],
                    [
                        'name' => 'Science & Technology',
                        'path' => 'M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z',
                        'desc' => 'Initiates campaigns for environmental conservation, climate change adaptation, and healthy technology use.',
                        'heads' => [
                            ['name' => 'Buenconsejo, Vincent A.', 'img' => asset('images/BUENCONSEJO.png')]
                        ]
                    ],
                    [
                        'name' => 'Culture & Heritage',
                        'path' => 'M12 21a9.004 9.004 0 008.716-6.747M12 21a9.004 9.004 0 01-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 017.843 4.582M12 3a8.997 8.997 0 00-7.843 4.582m15.686 0A11.953 11.953 0 0112 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0121 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0112 16.5c-3.162 0-6.133-.815-8.716-2.247m0 0A9.015 9.015 0 013 12c0-1.605.42-3.113 1.157-4.418',
                        'desc' => 'Promotes love and respect for culture, tolerance among other cultures, and fostering cross-cultural understanding.',
                        'heads' => [
                            ['name' => 'Jacob, Khryssdale S.', 'img' => null]
                        ]
                    ],
                    [
                        'name' => 'Social Sciences',
                        'path' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z',
                        'desc' => 'Upholds human rights, democracy, equity, and freedom. Leads HIV/AIDS awareness campaigns.',
                        'heads' => [
                            ['name' => 'Monacillo, Briella Dee B.', 'img' => null]
                        ]
                    ],
                    [
                        'name' => 'Digital Strategies & Comm.',
                        'path' => 'M8.288 15.038a5.25 5.25 0 017.424 0M5.106 11.856c3.807-3.808 9.98-3.808 13.788 0M1.924 8.674c5.565-5.565 14.587-5.565 20.152 0M12.53 18.22l-.53.53-.53-.53a.75.75 0 011.06 0z',
                        'desc' => 'Facilitates policy formulation in communication. Promotes freedom of expression via digital tech.',
                        'heads' => [
                            ['name' => 'Garduque, Dana Zusha A.', 'img' => asset('images/GARDUQUE.jpg')]
                        ]
                    ],
                ];
            @endphp

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($advocacyCommittees as $comm)
                    <x-committee-card :comm="$comm" />
                @endforeach
            </div>
        </section>

        {{-- =========================================== --}}
        {{-- SECTION 2: STANDING COMMITTEES (Article VI) --}}
        {{-- =========================================== --}}
        <section class="relative z-10">
            <div class="flex items-center gap-4 mb-8">
                <div class="w-2 h-10 bg-gradient-to-b from-green-500 to-blue-500 rounded-full"></div>
                <div>
                    <h2 class="font-heading text-3xl font-bold text-gray-900">Standing Committees</h2>
                    <p class="text-sm text-gray-500">Operational backbone of the organization (Article VI)</p>
                </div>
            </div>

            @php
                $standingCommittees = [
                    [
                        'name' => 'Planning Committee', 
                        'path' => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z', 
                        'desc' => 'Designs strategic plans, resource allocation, and performance monitoring.',
                        'heads' => [
                            ['name' => 'Cabalbag, Adornado Jr B.', 'img' => asset('images/CABALBAG.png')]
                        ]
                    ],
                    [
                        'name' => 'Internal Affairs', 
                        'path' => 'M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z', 
                        'desc' => 'Oversees governance, compliance, and integrity.',
                        'heads' => [
                             ['name' => 'Gubia, Darwin Isiah L.', 'img' => asset('images/GUBIA.jpg')]
                        ] 
                    ],
                    [
                        'name' => 'External Affairs', 
                        'path' => 'M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9', 
                        'desc' => 'Manages relations, partnerships, and diplomatic engagements.',
                        'heads' => [
                            ['name' => 'Oarde, Shiela Jean E.', 'img' => asset('images/OARDE.png')]
                        ]
                    ],
                    [
                        'name' => 'Secretariat Affairs', 
                        'path' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z', 
                        'desc' => 'Maintains documentation and manages membership records.',
                        'heads' => [
                            ['name' => 'Gerani, Maureen May L.', 'img' => asset('images/GERANI.jpg')]
                        ]
                    ],
                    [
                        'name' => 'Finance Committee', 
                        'path' => 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z', 
                        'desc' => 'Manages all financial transactions, budgeting, and reporting.',
                        'heads' => [
                            ['name' => 'Briol, Nicole Kate G.', 'img' => asset('images/BRIOL.jpeg')]
                        ]
                    ],
                    [
                        'name' => 'Auditing Committee', 
                        'path' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4', 
                        'desc' => 'Verifies financial reports for accuracy and legality.',
                        'heads' => [
                            ['name' => 'Soreda, Kimberly B.', 'img' => asset('images/SOREDA.jpeg')]
                        ]
                    ],
                    [
                        'name' => 'Marketing & Logistics', 
                        'path' => 'M13 10V3L4 14h7v7l9-11h-7z', 
                        'desc' => 'Designs marketing strategies and manages venue/equipment logistics.',
                        'heads' => [
                            ['name' => 'Rosare, Rowena M.', 'img' => null]
                        ]
                    ],
                    [
                        'name' => 'Public Affairs (PR)', 
                        'path' => 'M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6 3 3 0 000 6zM5 19a2 2 0 01-2-2V6a2 2 0 012-2h12a2 2 0 012 2v1m-6.5 11.971a6.745 6.745 0 01-3.84.529', 
                        'desc' => 'Manages communication with external audiences via media channels.',
                        'heads' => [
                            ['name' => 'Esparrago, Kyle Emil E.', 'img' => asset('images/ESPARRAGO.png')]
                        ]
                    ],
                    [
                        'name' => 'Multimedia & Creatives', 
                        'path' => 'M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z', 
                        'desc' => 'Creates digital content, graphics, and videos ensuring consistent branding.',
                        // CO-HEADS Example
                        'heads' => [
                            ['name' => 'Lique, Xanthie Luis S.', 'img' => asset('images/LIQUE.png')],
                            ['name' => 'Nuez, Ma. Allessandra B.', 'img' => null]
                        ]
                    ],
                    [
                        'name' => 'Ops & Documentation', 
                        'path' => 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z', 
                        'desc' => 'Coordinates event logistics and documents activities.',
                        'heads' => [
                            ['name' => 'Orbana, Alexa S.', 'img' => asset('images/ORBANA.jpeg')]
                        ]
                    ],
                    [
                        'name' => 'Technical & Productions', 
                        'path' => 'M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z', 
                        'desc' => 'Oversees audio-visual equipment and technical support.',
                        'heads' => [
                            ['name' => 'Cotara, Julius Christian C.', 'img' => asset('images/COTARA.jpeg')]
                        ]
                    ],
                ];
            @endphp

            <div class="grid md:grid-cols-2 xl:grid-cols-4 gap-5">
                @foreach($standingCommittees as $comm)
                    {{-- REUSABLE CARD COMPONENT LOGIC (Inlined for simplicity) --}}
                    <div x-data="{ flipped: false }" 
                         @click="flipped = !flipped"
                         class="bg-white rounded-xl p-5 shadow-sm border-l-4 border-green-500 hover:border-red-500 hover:shadow-md transition-all duration-300 cursor-pointer h-full relative group min-h-[220px] flex flex-col justify-center">
                        
                        {{-- Front Side --}}
                        <div x-show="!flipped" class="flex flex-col h-full justify-between">
                            <div>
                                <div class="flex items-center gap-3 mb-3">
                                    <div class="w-10 h-10 rounded-full bg-green-50 flex items-center justify-center text-green-600 group-hover:text-red-500 group-hover:bg-red-50 transition-colors shrink-0">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $comm['path'] }}"></path></svg>
                                    </div>
                                    <h4 class="font-bold text-gray-800 text-lg leading-tight">{{ $comm['name'] }}</h4>
                                </div>
                                <p class="text-xs text-gray-500 leading-relaxed text-justify">
                                    {{ $comm['desc'] }}
                                </p>
                            </div>
                            <div class="text-[10px] text-gray-300 text-right mt-3 font-bold uppercase group-hover:text-red-400">View Head</div>
                        </div>

                        {{-- Back Side (Dynamic Logic for Vacant/Single/Co-Heads) --}}
                        <div x-show="flipped" 
                             style="display: none;"
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 translate-y-2"
                             x-transition:enter-end="opacity-100 translate-y-0"
                             class="flex flex-col items-center justify-center h-full text-center w-full">
                            
                            @if(empty($comm['heads']))
                                {{-- 1. VACANT --}}
                                <div class="w-16 h-16 bg-gray-100 rounded-full mb-2 flex items-center justify-center text-gray-300 border border-dashed border-gray-300">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                </div>
                                <p class="font-bold text-gray-400 text-xs uppercase tracking-widest">Position Vacant</p>

                            @elseif(count($comm['heads']) > 1)
                                {{-- 2. CO-HEADS (Grid Layout) --}}
                                <div class="grid grid-cols-2 gap-2 w-full px-1 mb-2">
                                    @foreach($comm['heads'] as $head)
                                    <div class="flex flex-col items-center">
                                        <div class="w-14 h-14 bg-gray-100 rounded-full mb-1 overflow-hidden border border-green-500 shadow-sm">
                                            <img src="{{ $head['img'] ?? 'https://ui-avatars.com/api/?name='.urlencode($head['name']).'&background=random&color=fff' }}" 
                                                 class="w-full h-full object-cover">
                                        </div>
                                        <p class="font-bold text-gray-800 text-[9px] leading-tight line-clamp-2 px-1">
                                            {{ $head['name'] }}
                                        </p>
                                    </div>
                                    @endforeach
                                </div>
                                <span class="text-[10px] text-green-600 font-bold uppercase tracking-wider mb-2">Co-Heads</span>

                            @else
                                {{-- 3. SINGLE HEAD --}}
                                @php $head = $comm['heads'][0]; @endphp
                                <div class="w-16 h-16 bg-gray-100 rounded-full mb-2 overflow-hidden border-2 border-green-500 shadow-md">
                                    <img src="{{ $head['img'] ?? 'https://ui-avatars.com/api/?name='.urlencode($head['name']).'&background=random&color=fff' }}" 
                                         class="w-full h-full object-cover">
                                </div>
                                <p class="font-bold text-gray-800 text-sm leading-tight px-2 mb-1">{{ $head['name'] }}</p>
                                <span class="text-[10px] text-green-600 font-bold uppercase tracking-wider mb-2">Committee Head</span>
                            @endif

                            @if(!empty($comm['heads']))
                                <a href="{{ route('open.committees.show', \Illuminate\Support\Str::slug($comm['name'])) }}" 
                                class="px-3 py-1 bg-green-600 text-white rounded-full text-[10px] font-bold hover:bg-green-700 transition shadow-sm">
                                View Members
                                </a>
                            @endif
                        </div>

                    </div>
                @endforeach
            </div>
        </section>

    </div>

    {{-- FOOTER --}}
    <footer class="bg-gray-900 text-white pt-16 pb-8 border-t-8 border-red-600 relative mt-20">
        <div class="max-w-[1800px] w-[95%] mx-auto px-6 text-center text-xs text-gray-500">
            &copy; 2025 BU MADYA. All Rights Reserved.
        </div>
    </footer>

</div>