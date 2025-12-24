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
                   class="transition-colors duration-300 relative group {{ $link === 'Directory' ? 'text-yellow-600 font-bold' : 'text-gray-600 hover:text-red-600' }}">
                    {{ $link }}
                    <span class="absolute -bottom-1 left-0 h-0.5 bg-red-500 transition-all {{ $link === 'Directory' ? 'w-full' : 'w-0 group-hover:w-full' }}"></span>
                </a>
                @endforeach
                
                <a href="#" class="px-5 py-2 rounded-full font-bold text-sm bg-red-600 text-white hover:bg-red-500 shadow-lg transition transform hover:scale-105">
                    Contact Us
                </a>
            </div>
        </div>
    </nav>

    {{-- HEADER --}}
    <header class="relative h-[300px] flex items-center justify-center text-white overflow-hidden rounded-3xl shadow-xl mx-6 -mt-20 z-10">
        <div class="absolute inset-0 z-0">
            <img src="https://images.unsplash.com/photo-1523580494863-6f3031224c94?q=80&w=2070&auto=format&fit=crop" 
                 class="w-full h-full object-cover" alt="Header Background">
            <div class="absolute inset-0 bg-gradient-to-r from-green-900/90 to-red-900/80 mix-blend-multiply"></div>
        </div>

        <div class="relative z-10 text-center px-4 mt-16">
            <h2 class="text-yellow-300 font-bold tracking-[0.3em] text-xs uppercase mb-2">Our Leadership</h2>
            <h1 class="font-heading text-3xl md:text-5xl font-black uppercase tracking-tight mb-2 drop-shadow-lg">
                The Board of Directors
            </h1>
            <p class="text-sm md:text-base text-gray-100 font-light max-w-xl mx-auto italic">
                A.Y. 2025-2026
            </p>
        </div>
    </header>

    {{-- MAIN CONTENT AREA --}}
    <div class="relative min-h-screen">
        
        {{-- Background Elements for Glassmorphism Context --}}
        <div class="absolute inset-0 z-0 overflow-hidden pointer-events-none">
            <div class="absolute top-0 left-0 w-full h-full bg-gray-50"></div>
            {{-- Colorful blobs to show off glass effect --}}
            <div class="absolute top-20 left-10 w-96 h-96 bg-green-300 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob"></div>
            <div class="absolute top-20 right-10 w-96 h-96 bg-yellow-300 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob animation-delay-2000"></div>
            <div class="absolute -bottom-32 left-20 w-96 h-96 bg-red-300 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob animation-delay-4000"></div>
        </div>

        <div class="relative z-10 px-6 pb-24 mt-12">
            
            {{-- DATA POPULATION --}}
            @php
                $directors = [
                    ['name' => 'Cabalbag, Adornado Jr B.', 'role' => 'Director General', 'course' => 'BS Information Technology 4th Year', 'college' => 'BUCS', 'status' => 'active', 'img' => asset('images/CABALBAG.png')],
                    ['name' => 'Vacant', 'role' => 'Director for Internal Affairs', 'course' => '', 'college' => '', 'status' => 'vacant', 'img' => null],
                    ['name' => 'Oarde, Shiela Jean E.', 'role' => 'Director for External Affairs', 'course' => 'BS in Social Work - 3rd Year', 'college' => 'BU CSSP', 'status' => 'active', 'img' => asset('images/OARDE.png')],
                    ['name' => 'Gerani, Maureen May L.', 'role' => 'Secretary-General', 'course' => 'BS Economics - 4th Year', 'college' => 'BU CBEM', 'status' => 'active', 'img' => asset('images/GERANI.jpg')],
                    ['name' => 'Cadiz, Francheska Nicole M.', 'role' => 'Deputy Secretary-General', 'course' => 'BS Elementary Education', 'college' => 'BUCE', 'status' => 'active', 'img' => asset('images/CADIZ.jpg')],
                    ['name' => 'Briol, Nicole Kate G.', 'role' => 'Director for Finance', 'course' => 'BS Accountancy - 2nd Year', 'college' => 'BU CBEM', 'status' => 'active',  'img' => asset('images/BRIOL.jpeg')],
                    ['name' => 'Briz, Danica Shien Marie R.', 'role' => 'Deputy Director for Finance', 'course' => 'BSBA Management - 2nd Year', 'college' => 'BU CBEM', 'status' => 'active', 'img' => asset('images/BRIZ.png')],
                    ['name' => 'Soreda, Kimberly B.', 'role' => 'Director for Audit', 'course' => 'BS Geodetic Engineering - 3rd Year', 'college' => 'BUCENG', 'status' => 'active',  'img' => asset('images/SOREDA.jpeg')],
                    ['name' => 'Esparrago, Kyle Emil E.', 'role' => 'Director for Public Affairs', 'course' => 'BS Entrepreneurship - 3rd Year', 'college' => 'BUCBEM', 'status' => 'active',  'img' => asset('images/ESPARRAGO.png')],
                    ['name' => 'Lique, Xanthie Luis S.', 'role' => 'Director for Multimedia & Creatives', 'course' => 'BS Electrical Engineering - 1st Year', 'college' => 'BUCENG', 'status' => 'active', 'img' => asset('images/LIQUE.png')],
                    ['name' => 'Nuez, Ma. Allessandra B.', 'role' => 'Director for Multimedia & Creatives', 'course' => 'BS Chemistry - 4th Year', 'college' => 'BUCS', 'status' => 'active', 'img' => asset('images/NUEZ.jpeg')],
                    ['name' => 'Rosare, Rowena M.', 'role' => 'Director for Marketing & Logistics', 'course' => 'BSBA Operations Management - 1st Year', 'college' => 'BUCBEM', 'status' => 'active', 'img' => null],
                    ['name' => 'Mendones, Lance RJ D.', 'role' => 'Director for Strategic Initiatives', 'course' => 'Bachelor in Physical Education 3rd Year', 'college' => 'BUIPESR', 'status' => 'active', 'img' => asset('images/MENDONES.jpg')],
                    ['name' => 'Garduque, Dana Zusha A.', 'role' => 'Director for Digital Strategies', 'course' => 'BS Biology 2nd Year', 'college' => 'BUCS', 'status' => 'active', 'img' => asset('images/GARDUQUE.jpg')],
                    ['name' => 'Vacant', 'role' => 'Director for Education', 'course' => '', 'college' => '', 'status' => 'vacant', 'img' => null],
                    ['name' => 'Buenconsejo, Vincent A.', 'role' => 'Director for Science & Technology', 'course' => 'BSIT 2nd Year', 'college' => 'BUCS', 'status' => 'active',  'img' => asset('images/BUENCONSEJO.png')],
                    ['name' => 'Monacillo, Briella Dee B.', 'role' => 'Director for Social Sciences', 'course' => 'BS Social Work - 1st Year', 'college' => 'BUCSSP', 'status' => 'active', 'img' => null],
                    ['name' => 'Jacob, Khryssdale S.', 'role' => 'Director for Culture & Heritage', 'course' => 'AB Philosophy - 2nd Year', 'college' => 'BUCSSP', 'status' => 'active', 'img' => asset('images/JACOB.jpg')],
                    ['name' => 'Vacant', 'role' => 'Director for Special Projects & Intl.', 'course' => '', 'college' => '', 'status' => 'vacant', 'img' => null],
                    ['name' => 'Orbana, Alexa S.', 'role' => 'Director for Operations & Documentation', 'course' => 'AB Journalism - 4th Year', 'college' => 'BUCAL', 'status' => 'active', 'img' => asset('images/ORBANA.jpeg')],
                    ['name' => 'Cotara, Julius Christian C.', 'role' => 'Director for Technical & Productions', 'course' => 'BSBA Management - 3rd year', 'college' => 'BU CBEM', 'status' => 'active', 'img' => asset('images/COTARA.jpeg')],
                    ['name' => 'Sorsogon, Mel Liza B.', 'role' => 'BU Legazpi - West Envoy', 'course' => 'AB Journalism- 3rd Year', 'college' => 'BU CAL', 'status' => 'active','img' => asset('images/SORSOGON.png')],
                    ['name' => 'Gubia, Darwin Isiah L.', 'role' => 'BU Legazpi - East Envoy', 'course' => 'BS Chemical Engineering - 3rd Year', 'college' => 'BUCENG', 'status' => 'active', 'img' => asset('images/GUBIA.jpg')],
                    ['name' => 'Nieva, Christine Joy Ll.', 'role' => 'BU Daraga Envoy', 'course' => 'BSBA Management - 4th Year', 'college' => 'BUCBEM', 'status' => 'active', 'img' => asset('images/NIEVA.jpeg')],
                    ['name' => 'Vacant', 'role' => 'BU Tabaco Envoy', 'course' => '', 'college' => '', 'status' => 'vacant', 'img' => null],
                    ['name' => 'Vacant', 'role' => 'BU Guinobatan Envoy', 'course' => '', 'college' => '', 'status' => 'vacant', 'img' => null],
                    ['name' => 'Vacant', 'role' => 'BU Polangui Envoy', 'course' => '', 'college' => '', 'status' => 'vacant', 'img' => null],
                    ['name' => 'Vacant', 'role' => 'BU Gubat Envoy', 'course' => '', 'college' => '', 'status' => 'vacant', 'img' => null],
                ];
            @endphp

            

            <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
                <div class="relative w-full md:w-96">
                    <input type="text" placeholder="Search officer..." 
                           class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-gray-200 bg-white/80 backdrop-blur-sm shadow-sm focus:ring-2 focus:ring-yellow-400 text-sm">
                    <svg class="absolute left-3.5 top-3 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
                
                <div class="flex gap-2">
                    <button class="px-4 py-1.5 rounded-lg bg-red-600 text-white text-xs font-bold hover:bg-red-700 shadow-md">All</button>
                    <button class="px-4 py-1.5 rounded-lg bg-white/60 backdrop-blur-md text-gray-700 border border-white/40 text-xs font-bold hover:bg-white shadow-sm">Executive</button>
                    <button class="px-4 py-1.5 rounded-lg bg-white/60 backdrop-blur-md text-gray-700 border border-white/40 text-xs font-bold hover:bg-white shadow-sm">Envoys</button>
                </div>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-5">
                @foreach($directors as $director)
                
                @php
                    $isDG = $director['role'] === 'Director General';
                @endphp

                {{-- CARD CONTAINER --}}
                <div class="group relative flex flex-col overflow-hidden rounded-2xl transition-all duration-300 hover:-translate-y-2
                            {{-- DISTINGUISHING DESIGN FOR DG --}}
                            {{ $isDG 
                                ? 'bg-gradient-to-b from-yellow-50 to-white border-2 border-yellow-400 shadow-[0_0_15px_rgba(250,204,21,0.3)] z-10 scale-105' 
                                : 'bg-white/40 backdrop-blur-md border border-white/50 shadow-lg hover:shadow-xl hover:bg-white/60' 
                            }}">
                    
                    <div class="aspect-square relative overflow-hidden {{ $isDG ? 'bg-yellow-100' : 'bg-white/30' }}">
                        
                        {{-- DG EXCLUSIVE BADGE --}}
                        @if($director['status'] === 'vacant')
                        <div class="absolute inset-0 flex flex-col items-center justify-center text-gray-400">
                            <div class="{{ $isDG ? 'w-20 h-20' : 'w-12 h-12' }} rounded-full bg-gray-200/50 flex items-center justify-center mb-2">
                                <svg class="{{ $isDG ? 'w-10 h-10' : 'w-6 h-6' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            </div>
                            <span class="{{ $isDG ? 'text-sm' : 'text-[10px]' }} font-bold uppercase tracking-widest opacity-70">Vacant</span>
                        </div>

                    {{-- 2. ACTIVE OFFICER LOGIC --}}
                    @else
                        <div class="absolute inset-0 bg-gradient-to-t from-black/50 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition duration-300 z-10"></div>
                        
                        {{-- DEFINE IMAGE SOURCE --}}
                        {{-- If 'img' is not null, use it. Otherwise, generate UI Avatar --}}
                        <img src="{{ $director['img'] ?? 'https://ui-avatars.com/api/?name='.urlencode($director['name']).'&background=random&color=fff&size=512' }}" 
                            alt="{{ $director['name'] }}" 
                            class="w-full h-full object-cover {{ $isDG ? 'object-center' : '' }}"
                            {{-- Extra fallback: If file is missing (404), switch to avatar automatically --}}
                            onerror="this.onerror=null; this.src='https://ui-avatars.com/api/?name={{ urlencode($director['name']) }}&background=random&color=fff&size=512';"
                        >
                    @endif

                    {{-- DG Badge (Keep this overlay) --}}
                    @if($isDG)
                        <div class="absolute top-4 left-4 z-20 bg-yellow-400 text-green-900 text-xs font-black px-3 py-1 rounded-full shadow-lg border border-yellow-200 uppercase tracking-wider">
                            Head
                        </div>
                    @endif
                    </div>

                    <div class="p-4 flex-grow flex flex-col justify-between 
                                {{ $isDG ? 'border-t-2 border-yellow-200 bg-white/50' : 'border-t border-white/40' }}">
                        
                        <div class="mb-2">
                            <h3 class="font-heading font-bold leading-tight mb-1 group-hover:text-red-700 transition-colors
                                       {{ $isDG ? 'text-gray-900 text-sm md:text-base' : 'text-gray-900 text-xs md:text-sm' }}">
                                {{ $director['name'] }}
                            </h3>
                            <p class="font-bold uppercase tracking-wide leading-snug
                                      {{ $isDG ? 'text-xs text-yellow-700 font-black' : 'text-[10px] text-green-700' }}">
                                {{ $director['role'] }}
                            </p>
                        </div>
                        
                        @if($director['status'] !== 'vacant')
                        <div class="pt-2 border-t {{ $isDG ? 'border-yellow-200/50' : 'border-gray-200/30' }}">
                            <p class="text-[10px] text-gray-600 font-medium truncate">
                                {{ $director['course'] }}
                            </p>
                            <div class="flex items-center gap-1 mt-1">
                                <span class="inline-block w-1.5 h-1.5 rounded-full {{ $isDG ? 'bg-red-500' : 'bg-yellow-400' }}"></span>
                                <span class="text-[10px] text-gray-500 font-bold uppercase tracking-wider">
                                    {{ $director['college'] }}
                                </span>
                            </div>
                        </div>
                        @endif
                    </div>

                </div>
                @endforeach
            </div>

        </div>
    </div>

    {{-- FOOTER --}}
    <footer class="mt-0 border-t border-gray-200 py-8 px-6 text-center text-xs text-gray-500 bg-white">
        &copy; 2025 BU MADYA. All Rights Reserved.
    </footer>
</div>