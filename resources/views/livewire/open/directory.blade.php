<div class="font-sans antialiased text-gray-800 bg-gray-50 selection:bg-red-500 selection:text-white">
    
    {{-- HEADER (Same as before) --}}
    <header class="relative h-[300px] flex items-center justify-center text-white overflow-hidden rounded-3xl shadow-xl mx-6 -mt-20 z-10">
        <div class="absolute inset-0 z-0">
            <img src="{{ asset('images/IMG_2800.jpg') }}" 
                 class="w-full h-full object-cover" alt="Header Background">
            <div class="absolute inset-0 bg-gradient-to-r from-green-900/90 to-red-900/80 mix-blend-multiply"></div>
        </div>
        <div class="relative z-10 text-center px-4 mt-16">
            <h2 class="text-yellow-300 font-bold tracking-[0.3em] text-xs uppercase mb-2">Our Leadership</h2>
            <h1 class="font-heading text-3xl md:text-5xl font-black uppercase tracking-tight mb-2 drop-shadow-lg">
                The Board of Directors
            </h1>
            <p class="text-sm md:text-base text-gray-100 font-light max-w-xl mx-auto italic">
                A.Y. {{ $currentYear }}
            </p>
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
                                    <h3 class="font-heading font-bold leading-tight mb-0.5 md:mb-1 line-clamp-2
                                                 {{ $isVacant ? 'text-gray-400 italic' : 'text-gray-900 group-hover:text-red-700 transition-colors' }}
                                                 {{ $isDG ? 'text-[10px] md:text-base' : 'text-[9px] md:text-sm' }}">
                                        {{ $isVacant ? 'Unfilled' : $user->name }}
                                    </h3>
                                    {{-- UPDATED: User Name Text Size --}}
                                    <p class="font-bold uppercase tracking-wide leading-snug truncate
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
    
    <footer class="mt-0 border-t border-gray-200 py-8 px-6 text-center text-xs text-gray-500 bg-white">
        &copy; 2025 BU MADYA. All Rights Reserved.
    </footer>
</div>