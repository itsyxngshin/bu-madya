<div class="font-sans antialiased text-gray-800 bg-gray-50 selection:bg-red-500 selection:text-white">
    
    {{-- HEADER (Same as before) --}}
    <header class="relative h-[300px] flex items-center justify-center text-white overflow-hidden rounded-3xl shadow-xl mx-6 -mt-20 z-10">
        <div class="absolute inset-0 z-0">
            <img src="{{ asset('images/IMG_2800.jpg') }}" class="w-full h-full object-cover" alt="Header Background">
            <div class="absolute inset-0 bg-gradient-to-r from-green-900/90 to-red-900/80 mix-blend-multiply"></div>
        </div>
        <div class="relative z-10 text-center px-4 mt-16">
            <h2 class="text-yellow-300 font-bold tracking-[0.3em] text-xs uppercase mb-2">Our Leadership</h2>
            <h1 class="font-heading text-3xl md:text-5xl font-black uppercase tracking-tight mb-4 drop-shadow-lg">
                The Board of Directors
            </h1>
            
            {{-- ACADEMIC YEAR DROPDOWN --}}
            <div class="inline-flex items-center relative group">
                <svg class="w-4 h-4 absolute left-3 text-white/70 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                
                <select wire:model.live="selectedYearId" 
                    class="appearance-none bg-white/20 hover:bg-white/30 backdrop-blur-md border border-white/30 text-white font-bold py-2 pl-10 pr-10 rounded-full text-sm focus:ring-2 focus:ring-yellow-400 focus:border-yellow-400 transition cursor-pointer">
                    @foreach($this->academicYears as $year)
                        <option value="{{ $year->id }}" class="text-gray-900 font-medium">
                            A.Y. {{ $year->year }} {{ $year->is_active ? '(Current)' : '' }}
                        </option>
                    @endforeach
                </select>
                
                <svg class="w-4 h-4 absolute right-3 text-white/70 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
            </div>
        </div>
    </header>

    <div class="relative min-h-screen">
        {{-- Background Blobs (Same as before) --}}
        <div class="absolute inset-0 z-0 overflow-hidden pointer-events-none">
            <div class="absolute top-0 left-0 w-full h-full bg-gray-50"></div>
            <div class="absolute top-20 left-10 w-96 h-96 bg-green-300 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob"></div>
            <div class="absolute top-20 right-10 w-96 h-96 bg-yellow-300 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob animation-delay-2000"></div>
        </div>

        <div class="relative z-10 px-6 pb-24 mt-12">
            
            {{-- SEARCH & FILTER --}}
            <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
                <div class="relative w-full md:w-96">
                    <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search officer or position..." 
                           class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-gray-200 bg-white/80 backdrop-blur-sm shadow-sm focus:ring-2 focus:ring-yellow-400 text-sm transition-all">
                    <svg class="absolute left-3.5 top-3 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
                <div class="flex gap-2 p-1 bg-gray-200/50 rounded-xl backdrop-blur-sm">
                    @foreach(['All', 'Executive', 'Envoys'] as $f)
                        <button wire:click="setFilter('{{ $f }}')"
                            class="px-4 py-1.5 rounded-lg text-xs font-bold transition-all duration-200 shadow-sm
                            {{ $filter === $f ? 'bg-red-600 text-white shadow-md transform scale-105' : 'bg-white/40 text-gray-600 hover:bg-white border border-transparent' }}">
                            {{ $f }}
                        </button>
                    @endforeach
                </div>
            </div>

            {{-- DIRECTORS GRID --}}
            <div class="grid grid-cols-3 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-2 md:gap-5">
                
                @forelse($officers as $director)
                    @php
                        $assignments = $director->assignments;
                        $cardsToRender = $assignments->isEmpty() ? [null] : $assignments;
                    @endphp

                    @foreach($cardsToRender as $assignment)
                        @php
                            $isVacant = is_null($assignment);
                            $user = $assignment?->user;
                            $profile = $user?->profile;
                            $isDG = $director->name === 'Director General';
                            
                            $collegeDisplay = 'N/A';
                            if ($profile?->college) {
                                $collegeDisplay = strtoupper(str_replace('bu-', '', $profile->college->slug));
                                if($profile->college->slug === 'bu-cbem') $collegeDisplay = 'CBEM';
                            }
                        @endphp

                        <div wire:key="dir-{{ $director->id }}-{{ $isVacant ? 'vacant' : $user->id }}" 
                             class="group relative flex flex-col overflow-hidden rounded-xl md:rounded-2xl transition-all duration-300 hover:-translate-y-2
                             {{ $isVacant 
                                ? 'bg-gray-100 border border-gray-200 opacity-80 hover:opacity-100' 
                                : ($isDG 
                                    ? 'bg-gradient-to-b from-yellow-50 to-white border-2 border-yellow-400 shadow-[0_0_15px_rgba(250,204,21,0.3)] z-10 scale-105' 
                                    : 'bg-white/40 backdrop-blur-md border border-white/50 shadow-lg hover:shadow-xl hover:bg-white/60') 
                             }}">

                             @if(!$isVacant && $user)
                                <a href="{{ route('profile.public', $user->username) }}" class="absolute inset-0 z-30">
                                    <span class="sr-only">View Profile</span>
                                </a>
                            @endif
                            
                            {{-- IMAGE CONTAINER --}}
                            <div class="aspect-square relative overflow-hidden {{ $isVacant ? 'bg-gray-200' : ($isDG ? 'bg-yellow-100' : 'bg-white/30') }}">
                                
                                @if($isVacant)
                                    <div class="absolute inset-0 flex flex-col items-center justify-center text-gray-400">
                                        {{-- Smaller icon on mobile --}}
                                        <div class="w-8 h-8 md:w-12 md:h-12 rounded-full bg-gray-300/50 flex items-center justify-center mb-1 md:mb-2">
                                            <svg class="w-4 h-4 md:w-6 md:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                        </div>
                                        <span class="text-[8px] md:text-[10px] font-bold uppercase tracking-widest opacity-70">Vacant</span>
                                    </div>
                                @else
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/50 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition duration-300 z-10"></div>
                                   <img src="{{ $user->profile_photo_path 
                                                ? (filter_var($user->profile_photo_path, FILTER_VALIDATE_URL) ? $user->profile_photo_path : asset($user->profile_photo_path)) 
                                                : 'https://images.unsplash.com/photo-1557683316-973673baf926?q=80&w=1000&auto=format&fit=crop' }}" 
                                        alt="{{ $user->name }}"
                                        class="w-full h-full object-cover {{ $isDG ? 'object-center' : 'object-top' }} transition duration-500 group-hover:scale-110" 
                                        loading="lazy">
                                @endif

                                {{-- UPDATED: Smaller DG Badge --}}
                                @if($isDG && !$isVacant)
                                    <div class="absolute top-2 left-2 md:top-4 md:left-4 z-20 bg-yellow-400 text-green-900 text-[8px] md:text-xs font-black px-2 py-0.5 md:px-3 md:py-1 rounded-full shadow-lg border border-yellow-200 uppercase tracking-wider">Head</div>
                                @endif
                            </div>

                            {{-- DETAILS CONTAINER --}}
                            {{-- UPDATED: Reduced padding (p-2 on mobile, p-4 on desktop) --}}
                            <div class="p-2 md:p-4 flex-grow flex flex-col justify-between 
                                    {{ $isVacant ? 'border-t border-gray-200' : ($isDG ? 'border-t-2 border-yellow-200 bg-white/50' : 'border-t border-white/40') }}">
                                
                                <div class="mb-1 md:mb-2">
                                    {{-- UPDATED: Position Name Text Size --}}
                                    <h3 class="font-heading font-bold leading-tight mb-0.5 md:mb-1
                                                 {{ $isVacant ? 'text-gray-400 italic' : 'text-gray-900 group-hover:text-red-700 transition-colors' }}
                                                 {{ $isDG ? 'text-[10px] md:text-base' : 'text-[9px] md:text-sm' }}">
                                        {{ $isVacant ? 'Unfilled' : $user->name }}
                                    </h3>
                                    {{-- UPDATED: User Name Text Size --}}
                                    <p class="font-bold uppercase tracking-wide leading-tight break-words
                                              {{ $isVacant ? 'text-[8px] text-gray-400' : ($isDG ? 'text-[9px] md:text-xs text-yellow-700 font-black' : 'text-[8px] md:text-[10px] text-green-700') }}">
                                        {{ $director->name }}
                                    </p>
                                </div>
                                
                                @if(!$isVacant)
                                <div class="pt-1 md:pt-2 border-t {{ $isDG ? 'border-yellow-200/50' : 'border-gray-200/30' }}">
                                    {{-- UPDATED: Course Text Size --}}
                                    <p class="text-[8px] md:text-[10px] text-gray-600 font-medium truncate" title="{{ $profile->course ?? 'N/A' }}">
                                        {{ $profile->course ?? 'Unspecified' }}
                                    </p>
                                    <div class="flex items-center gap-1 mt-0.5 md:mt-1">
                                        <span class="inline-block w-1 h-1 md:w-1.5 md:h-1.5 rounded-full {{ $isDG ? 'bg-red-500' : 'bg-yellow-400' }}"></span>
                                        {{-- UPDATED: College/Year Text Size --}}
                                        <span class="text-[8px] md:text-[10px] text-gray-500 font-bold uppercase tracking-wider">
                                            {{ $collegeDisplay }} • {{ $profile->year_level ?? '' }}
                                        </span>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    @endforeach

                @empty
                    <div class="col-span-full py-12 text-center">
                        <p class="text-gray-500 font-medium">No positions found.</p>
                    </div>
                @endforelse

            </div>
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