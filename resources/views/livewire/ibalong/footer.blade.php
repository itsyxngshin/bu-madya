<footer class="bg-iba-light dark:bg-iba-black pt-10 sm:pt-16 transition-colors duration-300 border-t-8 border-iba-red relative z-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6">

            {{-- SECTION 1: EVENT PARTNERS --}}
            @php
                // Fetch active partners ordered by your custom priority
                $partners = \App\Models\IbalongPartner::where('is_active', true)->orderBy('display_order', 'asc')->get();
            @endphp

            <div class="mb-12 sm:mb-16">
                <div class="text-center mb-8 sm:mb-12">
                    <h2 class="font-pixel text-lg sm:text-2xl text-iba-black dark:text-iba-light uppercase tracking-wide">ORGANIZED BY & PARTNERS</h2>
                </div>

                {{-- Neo-Brutalist Flex/Grid Container (Optimized for Mobile) --}}
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 sm:gap-6 justify-center">
                    @foreach($partners as $partner)
                        <div class="bg-white dark:bg-[#1A1617] border-2 sm:border-4 border-iba-black dark:border-iba-light p-4 sm:p-6 shadow-[4px_4px_0_0_#CF452C] sm:shadow-[6px_6px_0_0_#CF452C] hover:translate-y-1 hover:shadow-none transition-all flex flex-col items-center justify-center text-center group h-full">

                            {{-- Dynamic Logo with Emphasis Sizing --}}
                            <div class="mb-3 sm:mb-4 flex-1 flex items-center justify-center w-full">
                                <img src="{{ Storage::url($partner->logo_path) }}"
                                    alt="{{ $partner->name }}"
                                    class="{{ $partner->emphasis === 'medium' ? 'h-16 sm:h-24' : 'h-10 sm:h-16' }} w-auto object-contain grayscale group-hover:grayscale-0 transition-all duration-300">
                            </div>

                            {{-- Role Text --}}
                            <p class="text-[8px] sm:text-[10px] font-bold text-gray-500 uppercase tracking-widest mt-auto w-full pt-3 sm:pt-4 border-t-2 border-dashed border-gray-300 dark:border-gray-700">
                                {{ $partner->role }}
                            </p>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- HEAVY DIVIDER --}}
            <div class="border-t-4 border-dashed border-iba-black/20 dark:border-iba-light/20 w-full mb-10 sm:mb-16"></div>

            {{-- SECTION 2: BU MADYA ORIGINAL FOOTER CONTENT --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-10 sm:gap-12 mb-10 sm:mb-12">

                {{-- Col 1 & 2: Branding & Socials --}}
                <div class="sm:col-span-2">
                    <div class="flex items-center gap-3 sm:gap-4 mb-4 sm:mb-6">
                        <div class="w-10 h-10 sm:w-12 sm:h-12 bg-iba-red border-2 sm:border-4 border-iba-black dark:border-iba-light text-white flex items-center justify-center shadow-[3px_3px_0_0_#131011] dark:shadow-[4px_4px_0_0_#FFFBF7]">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.879 16.121A3 3 0 1012.015 11L11 14H9c0 .768.293 1.536.879 2.121z"></path></svg>
                        </div>
                        <span class="font-pixel text-base sm:text-xl tracking-tight text-iba-black dark:text-iba-light">BU MADYA</span>
                    </div>

                    <p class="text-gray-700 dark:text-gray-300 font-medium leading-relaxed max-w-sm mb-6 sm:mb-8 text-xs sm:text-sm">
                        The Bicol University - Movement for the Advancement of Youth-led Advocacy is a duly-accredited University Based Organization in Bicol University committed to service and reaching communities through advocacy.
                    </p>

                    {{-- Social Media Links (Neo-Brutalist) --}}
                    <div class="flex space-x-3 sm:space-x-4">
                        {{-- Facebook --}}
                        <a href="https://www.facebook.com/BUMadya" target="_blank" rel="noopener noreferrer" class="w-10 h-10 sm:w-12 sm:h-12 bg-white dark:bg-[#1A1617] border-2 sm:border-4 border-iba-black dark:border-iba-light flex items-center justify-center hover:-translate-y-1 hover:bg-iba-teal hover:text-white transition-all shadow-[3px_3px_0_0_#131011] dark:shadow-[4px_4px_0_0_#FFFBF7] text-iba-black dark:text-iba-light">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                        </a>

                        {{-- Instagram --}}
                        <a href="https://www.instagram.com/bu_madya" target="_blank" rel="noopener noreferrer" class="w-10 h-10 sm:w-12 sm:h-12 bg-white dark:bg-[#1A1617] border-2 sm:border-4 border-iba-black dark:border-iba-light flex items-center justify-center hover:-translate-y-1 hover:bg-iba-red hover:text-white transition-all shadow-[3px_3px_0_0_#131011] dark:shadow-[4px_4px_0_0_#FFFBF7] text-iba-black dark:text-iba-light">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                        </a>

                        {{-- X (Twitter) --}}
                        <a href="https://www.x.com/bu_madya" target="_blank" rel="noopener noreferrer" class="w-10 h-10 sm:w-12 sm:h-12 bg-white dark:bg-[#1A1617] border-2 sm:border-4 border-iba-black dark:border-iba-light flex items-center justify-center hover:-translate-y-1 hover:bg-iba-black hover:text-white dark:hover:bg-iba-light dark:hover:text-iba-black transition-all shadow-[3px_3px_0_0_#131011] dark:shadow-[4px_4px_0_0_#FFFBF7] text-iba-black dark:text-iba-light">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                        </a>
                    </div>
                </div>

                {{-- Col 3: Navigation Links --}}
                <div class="flex flex-col justify-center">
                    <ul class="space-y-3 sm:space-y-4 text-iba-black dark:text-iba-light font-bold text-xs sm:text-sm">
                        <li><a href="{{ route('about') ?? '#' }}" class="hover:text-iba-orange hover:translate-x-1 transition-transform inline-block">About BU MADYA</a></li>
                        <li><a href="{{ route('open.directory') ?? '#' }}" class="hover:text-iba-teal hover:translate-x-1 transition-transform inline-block">Our Officers</a></li>
                        <li><a href="{{ route('transparency.index') ?? '#' }}" class="hover:text-iba-green hover:translate-x-1 transition-transform inline-block">Transparency Board</a></li>
                        <li class="pt-3 sm:pt-4 mt-2 border-t-2 sm:border-t-4 border-dashed border-iba-black/20 dark:border-iba-light/20">
                            <a href="{{ route('privacy') ?? '#' }}" class="text-[10px] sm:text-xs text-gray-500 hover:text-iba-red hover:translate-x-1 transition-transform inline-block">Privacy Policy</a>
                        </li>
                    </ul>
                </div>

                {{-- Col 4: Live Stats --}}
                <div class="flex flex-col justify-center min-w-0">
                    <h4 class="font-pixel text-[9px] sm:text-[10px] mb-3 sm:mb-4 text-iba-green tracking-widest uppercase truncate">Live System Stats</h4>
                    <div class="bg-iba-black dark:bg-[#1A1617] p-4 sm:p-5 border-2 sm:border-4 border-iba-black dark:border-iba-light shadow-[4px_4px_0_0_#0095AC] sm:shadow-[6px_6px_0_0_#0095AC] overflow-hidden w-full">
                        <span class="block font-bold text-[9px] sm:text-[10px] uppercase tracking-widest text-iba-light/50 mb-1 sm:mb-2 truncate">Total Terminals Accessed</span>
                        
                        <div class="text-xl sm:text-2xl lg:text-3xl font-pixel text-iba-orange tracking-widest break-all">
                            {{-- Calls your Livewire property --}}
                            {{ str_pad($visitorCount, 7, '0', STR_PAD_LEFT) }}
                        </div>
                    </div>
                </div>

            </div>

            {{-- SECTION 3: COPYRIGHT & POWERED BY (Mobile Responsive) --}}
            <div class="pt-6 pb-8 border-t-4 border-iba-black dark:border-iba-light flex flex-col-reverse md:flex-row justify-between items-center gap-4 text-center md:text-left">
                <div class="text-iba-black dark:text-iba-light font-bold text-[9px] sm:text-xs uppercase tracking-widest">
                    &copy; {{ date('Y') }} BU MADYA. All Rights Reserved.
                </div>
                
                {{-- Powered By Badge --}}
                <div class="flex items-center gap-2 text-[9px] sm:text-xs font-bold uppercase tracking-widest text-gray-500 dark:text-gray-400">
                    <span>Powered By</span>
                    <span class="bg-iba-teal text-white px-2 py-1 border-2 border-iba-black dark:border-iba-light shadow-[2px_2px_0_0_#131011] dark:shadow-[2px_2px_0_0_#FFFBF7]">
                        BU MADYA Web
                    </span>
                </div>
            </div>

        </div>
    </footer>