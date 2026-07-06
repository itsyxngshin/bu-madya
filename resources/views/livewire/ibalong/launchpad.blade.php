<div class="w-full">
    {{-- HERO HEADER --}}
    <header id="home" class="relative min-h-[85vh] flex flex-col items-center justify-center px-4 sm:px-6 text-center overflow-hidden pb-12 bg-iba-light dark:bg-iba-black bg-pixel-pattern transition-colors duration-300">
        <div class="relative z-10 space-y-8 max-w-5xl mx-auto w-full bg-iba-light/90 dark:bg-iba-black/90 p-8 sm:p-12 border-4 border-iba-black dark:border-iba-light shadow-[12px_12px_0_0_#0095AC] dark:shadow-[12px_12px_0_0_#0095AC]">
            
            <div class="inline-flex items-center justify-center gap-2 bg-iba-orange text-iba-black border-4 border-iba-black px-4 py-2 font-pixel text-[8px] sm:text-[9px] tracking-tight shadow-[4px_4px_0_0_#131011]">
                <svg class="w-4 h-4 animate-pulse" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.362 5.214A8.252 8.252 0 0112 21 8.25 8.25 0 016.038 7.048 8.287 8.287 0 009 9.6a8.983 8.983 0 013.361-6.867 8.21 8.21 0 003 2.48z" />
                </svg>
                LIVE REGISTRATION PHASE OPEN
            </div>

            <h1 class="font-pixel text-4xl sm:text-5xl md:text-6xl lg:text-7xl tracking-wide text-iba-black dark:text-iba-light leading-[1.3] sm:leading-tight uppercase drop-shadow-md">
                FROM EPIC<br><span class="text-iba-red">TO IMPACT</span>
            </h1>
            
            <div class="inline-block bg-iba-black text-iba-light font-pixel text-xs sm:text-lg md:text-xl px-6 py-3 border-4 border-iba-red shadow-[4px_4px_0_0_#CF452C] uppercase tracking-widest">
                AUGUST 12-13, 2026
            </div>
            
            <div class="pt-6 sm:pt-8 w-full sm:w-auto relative inline-block">
                <a href="{{ route('ibalong.register') }}" class="btn-retro flex items-center justify-center sm:inline-block font-pixel text-iba-black dark:text-iba-black bg-iba-green px-6 py-4 sm:px-10 sm:py-5 text-[10px] sm:text-sm md:text-base cursor-pointer tracking-wider w-full sm:w-auto">
                    LAUNCH APPLICATION
                </a>
            </div>
        </div>

        <div class="absolute bottom-0 w-full trim-element-2"></div>
    </header>

    {{-- THE BRIEFING --}}
    <section id="about" class="pt-16 sm:pt-24 bg-iba-teal dark:bg-iba-black relative border-y-4 border-iba-black dark:border-y-iba-teal transition-colors duration-300">
        <div class="max-w-6xl mx-auto flex flex-col md:flex-row gap-10 sm:gap-16 items-center justify-between px-4 sm:px-6 pb-16 sm:pb-24">
            
            <div class="w-full md:w-7/12 space-y-6 text-iba-light leading-relaxed text-sm sm:text-base md:text-lg">
                <h3 class="font-pixel text-[12px] sm:text-sm text-iba-orange tracking-widest uppercase flex items-center gap-3 drop-shadow-md">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 111.063.852l-.708 2.836a.75.75 0 001.063.852l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" /></svg>
                    The Briefing
                </h3>
                <p class="text-lg sm:text-xl md:text-2xl font-extrabold tracking-tight dark:text-iba-light">
                    The <span class="text-iba-orange bg-iba-black px-2 py-1 rounded-sm border-2 border-iba-black dark:border-iba-orange">Heroes of Innovation Challenge</span> is a regional sprint calling upon Bicolano builders to create technology-enabled solutions.
                </p>
                <p class="font-medium text-iba-light/90 dark:text-gray-400">
                    Anchored on the legendary character models of Baltog, Bantong, and Handiong, the challenge invites multidisciplinary modules to formulate modern frameworks responding to regional smart infrastructure goals.
                </p>
                <div class="border-l-8 border-iba-orange pl-5 py-4 bg-iba-black text-iba-light text-sm italic shadow-[4px_4px_0_0_#FF8623]">
                    Coordinated via BiCoRSE under Project REACH, working side-by-side with localized administration networks, startup business incubators, and civic tech working groups.
                </div>
            </div>
            
            <div class="w-full md:w-4/12 flex justify-center md:justify-end mt-6 md:mt-0">
                <div class="bg-iba-light dark:bg-[#1A1617] border-4 border-iba-black dark:border-iba-light p-8 sm:p-10 text-center shadow-[10px_10px_0_0_#CF452C] w-full max-w-[320px] transform rotate-2 hover:rotate-0 transition-transform duration-300">
                    <div class="w-20 h-20 sm:w-24 sm:h-24 bg-iba-red rounded-full flex items-center justify-center mx-auto mb-6 border-4 border-iba-black dark:border-iba-light shadow-inner">
                        <span class="text-4xl">🏹</span>
                    </div>
                    <h2 class="font-pixel text-iba-black dark:text-iba-light text-[11px] sm:text-xs tracking-tight leading-[1.8] mb-3">HEROES OF<br>INNOVATION</h2>
                    <span class="text-[9px] sm:text-[10px] font-pixel text-white bg-iba-black px-3 py-1 inline-block border-2 border-iba-black dark:border-iba-light">COHORT 2026</span>
                </div>
            </div>
        </div>

        <div class="w-full trim-element-1 border-t-4 border-iba-black dark:border-iba-light"></div>
    </section>

    {{-- PATHWAYS --}}
    <section id="pathways" class="py-20 sm:py-28 px-4 sm:px-6 bg-iba-light dark:bg-iba-black transition-colors duration-300">
        <div class="max-w-6xl mx-auto">
            <div class="text-center mb-16 sm:mb-20 space-y-4">
                <h2 class="font-pixel text-2xl sm:text-3xl md:text-4xl text-iba-black dark:text-iba-light leading-snug">CHOOSE YOUR PATHWAY</h2>
                <p class="text-iba-black dark:text-gray-300 font-bold max-w-xl mx-auto text-sm sm:text-base border-b-4 border-iba-orange inline-block pb-2">Select the framework that lines up with your focus.</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-8 sm:gap-10">
                {{-- Baltog --}}
                <div class="bg-white dark:bg-[#1A1617] border-4 border-iba-black dark:border-iba-light p-6 sm:p-8 shadow-[8px_8px_0_0_#CF452C] hover:-translate-y-2 transition-transform">
                    <div class="flex items-center justify-between mb-4">
                        <span class="font-pixel text-sm lg:text-base text-iba-black bg-iba-red text-white px-3 py-1 border-2 border-iba-black dark:border-iba-light">BALTOG</span>
                        <div class="text-iba-red"><svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 008.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 017.843 4.582M12 3a8.997 8.997 0 00-7.843 4.582" /></svg></div>
                    </div>
                    <div class="text-[9px] sm:text-[10px] font-pixel text-iba-orange uppercase tracking-wider mb-4">The Pioneer</div>
                    <p class="text-iba-black dark:text-gray-300 font-medium text-sm sm:text-base mb-6">"Every great journey begins with the courage to take the first step."</p>
                    <div class="space-y-3 border-t-4 border-iba-black dark:border-iba-light pt-5 text-sm text-iba-black dark:text-iba-light font-bold">
                        <div class="flex items-center gap-3"><span class="text-xl">🛡️</span> Disaster Resilience</div>
                        <div class="flex items-center gap-3"><span class="text-xl">🌾</span> Food Security Systems</div>
                        <div class="flex items-center gap-3"><span class="text-xl">🌱</span> Climate Adaptation</div>
                    </div>
                </div>

                {{-- Bantong --}}
                <div class="bg-white dark:bg-[#1A1617] border-4 border-iba-black dark:border-iba-light p-6 sm:p-8 shadow-[8px_8px_0_0_#0095AC] hover:-translate-y-2 transition-transform">
                    <div class="flex items-center justify-between mb-4">
                        <span class="font-pixel text-sm lg:text-base text-iba-black bg-iba-teal text-white px-3 py-1 border-2 border-iba-black dark:border-iba-light">BANTONG</span>
                        <div class="text-iba-teal"><svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M14.25 9.75L16.5 12l-2.25 2.25m-4.5 0L7.5 12l2.25-2.25M6 20.25h12A2.25 2.25 0 0020.25 18V6A2.25 2.25 0 0018 3.75H6A2.25 2.25 0 003.75 6v12A2.25 2.25 0 006 20.25z" /></svg></div>
                    </div>
                    <div class="text-[9px] sm:text-[10px] font-pixel text-iba-green uppercase tracking-wider mb-4">The Strategist</div>
                    <p class="text-iba-black dark:text-gray-300 font-medium text-sm sm:text-base mb-6">"Wisdom transforms obstacles into opportunities."</p>
                    <div class="space-y-3 border-t-4 border-iba-black dark:border-iba-light pt-5 text-sm text-iba-black dark:text-iba-light font-bold">
                        <div class="flex items-center gap-3"><span class="text-xl">🏛️</span> Digital Gov Services</div>
                        <div class="flex items-center gap-3"><span class="text-xl">🗺️</span> Smart Tourism Apps</div>
                        <div class="flex items-center gap-3"><span class="text-xl">🤖</span> Systems Automation</div>
                    </div>
                </div>

                {{-- Handiong --}}
                <div class="bg-white dark:bg-[#1A1617] border-4 border-iba-black dark:border-iba-light p-6 sm:p-8 shadow-[8px_8px_0_0_#5C7914] hover:-translate-y-2 transition-transform">
                    <div class="flex items-center justify-between mb-4">
                        <span class="font-pixel text-sm lg:text-base text-iba-black bg-iba-green text-white px-3 py-1 border-2 border-iba-black dark:border-iba-light">HANDIONG</span>
                        <div class="text-iba-green"><svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72" /></svg></div>
                    </div>
                    <div class="text-[9px] sm:text-[10px] font-pixel text-iba-teal uppercase tracking-wider mb-4">The Visionary</div>
                    <p class="text-iba-black dark:text-gray-300 font-medium text-sm sm:text-base mb-6">"Great leaders build tomorrow's communities."</p>
                    <div class="space-y-3 border-t-4 border-iba-black dark:border-iba-light pt-5 text-sm text-iba-black dark:text-iba-light font-bold">
                        <div class="flex items-center gap-3"><span class="text-xl">👥</span> Inclusive Access Tech</div>
                        <div class="flex items-center gap-3"><span class="text-xl">💼</span> Livelihood Generation</div>
                        <div class="flex items-center gap-3"><span class="text-xl">🚀</span> Youth Empowerment</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- TIMELINE --}}
    <section id="timeline" class="py-20 sm:py-24 px-4 sm:px-6 bg-iba-green dark:bg-iba-black border-y-4 border-iba-black dark:border-y-iba-green transition-colors duration-300">
        <div class="max-w-4xl mx-auto">
            <div class="text-center mb-16 sm:mb-20 space-y-4">
                <h2 class="font-pixel text-2xl sm:text-3xl md:text-4xl text-iba-light">QUEST PROGRESSION</h2>
                <p class="text-xs sm:text-sm text-white font-bold bg-iba-black inline-block px-4 py-2 border-2 border-iba-black dark:border-iba-light shadow-[4px_4px_0_0_#CF452C]">Chronological sprint milestones</p>
            </div>

            <div class="relative pl-8 sm:pl-10 md:pl-0 border-l-8 border-iba-black dark:border-iba-light md:border-l-0">
                <div class="hidden md:block absolute left-1/2 top-0 bottom-0 w-2 bg-iba-black dark:bg-iba-light transform -translate-x-1/2"></div>

                @php
                    $timeline = [
                        ['date' => 'JULY 3, 2026', 'title' => 'Launch of Open Call', 'desc' => 'Online Platform Entry Initiative', 'theme' => 'bg-iba-light dark:bg-[#1A1617] border-iba-black dark:border-iba-light text-iba-black dark:text-iba-light'],
                        ['date' => 'JULY 3-19, 2026', 'title' => 'Application Period', 'desc' => 'Online Portal Intake Window (Active)', 'theme' => 'bg-iba-orange border-iba-black dark:border-iba-light text-iba-black shadow-[6px_6px_0_0_#131011] dark:shadow-[6px_6px_0_0_#FFFBF7]', 'highlight' => true],
                        ['date' => 'JULY 20, 2026', 'title' => 'Voices of the City', 'desc' => 'Human-Centered Empathy Research Frameworks', 'theme' => 'bg-iba-light dark:bg-[#1A1617] border-iba-black dark:border-iba-light text-iba-black dark:text-iba-light'],
                        ['date' => 'AUGUST 12, 2026', 'title' => 'The Forge', 'desc' => 'Onsite Implementation & Rapid Prototyping Sprint', 'theme' => 'bg-iba-light dark:bg-[#1A1617] border-iba-black dark:border-iba-light text-iba-black dark:text-iba-light'],
                        ['date' => 'AUGUST 13, 2026', 'title' => 'Hero\'s Pitch Day', 'desc' => 'Jury Verification Panel & Award Allocation Ceremony', 'theme' => 'bg-iba-red border-iba-black dark:border-iba-light text-white shadow-[6px_6px_0_0_#131011] dark:shadow-[6px_6px_0_0_#FFFBF7]'],
                    ];
                @endphp

                @foreach($timeline as $index => $item)
                    <div class="relative flex items-center justify-between md:justify-normal w-full mb-10 sm:mb-12 {{ $index % 2 == 0 ? 'md:flex-row-reverse' : '' }}">
                        <div class="absolute left-[-24px] sm:left-[-28px] md:left-1/2 md:transform md:-translate-x-1/2 w-12 h-12 sm:w-14 sm:h-14 border-4 {{ isset($item['highlight']) ? 'bg-iba-orange' : 'bg-iba-light dark:bg-[#1A1617]' }} border-iba-black dark:border-iba-light flex items-center justify-center z-10 shadow-[4px_4px_0_0_#131011] dark:shadow-[4px_4px_0_0_#FFFBF7]">
                            <span class="font-pixel text-xs sm:text-sm text-iba-black {{ !isset($item['highlight']) ? 'dark:text-iba-light' : '' }}">{{ $index + 1 }}</span>
                        </div>

                        <div class="w-full md:w-[46%] {{ $index % 2 == 0 ? 'md:pl-8' : 'md:pr-8 md:text-right' }}">
                            <div class="p-5 sm:p-6 border-4 {{ $item['theme'] }}">
                                <span class="font-pixel text-[9px] sm:text-[10px] block mb-2">{{ $item['date'] }}</span>
                                <h4 class="font-bold text-base sm:text-lg md:text-xl mb-2 tracking-tight uppercase">{{ $item['title'] }}</h4>
                                <p class="text-xs sm:text-sm font-semibold leading-relaxed {{ isset($item['highlight']) || str_contains($item['theme'], 'bg-iba-red') ? '' : 'text-gray-600 dark:text-gray-400' }}">{{ $item['desc'] }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- CTA ACTION BLOCK --}}
    <section id="register" class="py-24 sm:py-32 px-4 sm:px-6 text-center bg-iba-red dark:bg-iba-black border-b-4 border-iba-black dark:border-y-iba-red relative bg-pixel-pattern transition-colors duration-300">
        <div class="max-w-3xl mx-auto space-y-8 bg-iba-red dark:bg-iba-black p-8 border-4 border-iba-black dark:border-iba-light shadow-[12px_12px_0_0_#131011] dark:shadow-[12px_12px_0_0_#FFFBF7]">
            <h2 class="font-pixel text-2xl sm:text-3xl md:text-4xl text-iba-light leading-relaxed sm:leading-loose drop-shadow-md">
                COMMENCE YOUR <br><span class="text-iba-orange bg-iba-black px-4 py-2 border-4 border-iba-black dark:border-iba-light">REGIONAL QUEST</span>
            </h2>
            <p class="text-white dark:text-gray-300 font-semibold max-w-xl mx-auto text-sm sm:text-base leading-relaxed px-2">
                Assemble a cohort containing 3 to 5 members. Interdisciplinary skill sets are highly valued. Intake closes on July 19, 2026.
            </p>
            
            <div class="pt-8 w-full sm:w-auto">
                <a href="{{ route('ibalong.register') }}" class="btn-retro flex items-center justify-center sm:inline-block font-pixel text-iba-black bg-iba-orange px-6 py-5 sm:px-10 sm:py-6 text-[11px] sm:text-sm md:text-base tracking-widest font-bold w-full sm:w-auto uppercase">
                    INITIALIZE LINK REGISTRATION
                </a>
            </div>
        </div>
    </section>
</div>