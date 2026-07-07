<div class="bg-iba-light dark:bg-iba-black min-h-screen py-10 sm:py-16 px-4 sm:px-6 transition-colors duration-300 selection:bg-iba-teal selection:text-white">
    <div class="max-w-5xl mx-auto space-y-16">

        {{-- 1. HERO HEADER --}}
        <div class="text-center relative">
            <div class="absolute inset-0 z-0 opacity-10 pointer-events-none flex justify-center items-center text-iba-black dark:text-iba-light">
                <svg class="w-full h-32 sm:h-48" fill="currentColor" viewBox="0 0 100 100" preserveAspectRatio="none">
                    <pattern id="grid" width="4" height="4" patternUnits="userSpaceOnUse"><path d="M 4 0 L 0 0 0 4" fill="none" stroke="currentColor" stroke-width="0.5"/></pattern>
                    <rect width="100%" height="100%" fill="url(#grid)" />
                </svg>
            </div>

            <div class="relative z-10 inline-block bg-white dark:bg-[#1A1617] border-4 border-iba-black dark:border-iba-light p-6 sm:p-12 shadow-[8px_8px_0_0_#CF452C] sm:shadow-[12px_12px_0_0_#CF452C] mx-2">
                <h1 class="font-pixel text-3xl sm:text-5xl md:text-6xl text-iba-black dark:text-iba-light uppercase tracking-widest leading-tight mb-4">
                    HEROES OF INNOVATION
                </h1>
                <div class="bg-iba-black dark:bg-iba-light text-white dark:text-iba-black inline-block px-4 py-2 font-pixel text-sm sm:text-xl uppercase tracking-widest mb-6">
                    IBALONG FESTIVAL 2026 EDITION
                </div>

                <div class="flex flex-wrap justify-center gap-3 sm:gap-6 text-xs sm:text-sm font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300">
                    <span class="flex items-center gap-2"><svg class="w-4 h-4 text-iba-teal" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg> 12-13 August 2026</span>
                    <span class="flex items-center gap-2"><svg class="w-4 h-4 text-iba-red" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg> Legazpi City</span>
                </div>
            </div>
        </div>

        {{-- 2. THE BRIEF --}}
        <div class="bg-white dark:bg-[#1A1617] border-4 border-iba-black dark:border-iba-light p-6 sm:p-10 shadow-[8px_8px_0_0_#0095AC]">
            <h2 class="font-pixel text-xl sm:text-2xl text-iba-black dark:text-iba-light uppercase border-b-4 border-iba-teal pb-4 mb-6">THE MISSION</h2>
            <p class="text-base sm:text-lg font-bold text-gray-800 dark:text-gray-200 leading-relaxed mb-6">
                Innovation creates the greatest impact when it begins with people. This Challenge was created to cultivate a new generation of innovators who listen before they build, understand before they design, and create solutions that respond to the real needs of communities.
            </p>
            <ul class="space-y-4 text-sm sm:text-base font-semibold text-gray-700 dark:text-gray-300">
                <li class="flex items-start gap-3"><span class="text-iba-teal text-xl leading-none">▶</span> Encourage innovators across the Bicol Region to develop technology-enabled solutions inspired by the values of Baltog, Bantong, and Handiong.</li>
                <li class="flex items-start gap-3"><span class="text-iba-teal text-xl leading-none">▶</span> Discover high-potential teams and innovators who may be supported through incubation, mentoring, and ecosystem programs.</li>
                <li class="flex items-start gap-3"><span class="text-iba-teal text-xl leading-none">▶</span> Promote human-centered, challenge-driven innovation using design, technology, and entrepreneurship.</li>
            </ul>
        </div>

        {{-- 3. HERO PATHWAYS (GRID) --}}
        <div>
            <h2 class="font-pixel text-2xl sm:text-3xl text-center text-iba-black dark:text-iba-light uppercase mb-10">THE HERO PATHWAYS</h2>
            <p class="text-center font-bold text-gray-600 dark:text-gray-400 mb-10 max-w-2xl mx-auto">Rather than competing under separate challenge tracks, every Innovation Team will choose a Hero Pathway that best represents the values, character, and purpose of its proposed innovation.</p>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">

                {{-- BALTOG --}}
                <div class="bg-white dark:bg-gray-800 border-4 border-iba-black dark:border-iba-light p-6 shadow-[6px_6px_0_0_#CF452C] hover:-translate-y-2 transition-transform">
                    <div class="bg-iba-red text-white font-pixel text-lg py-2 px-4 inline-block mb-4 border-2 border-iba-black dark:border-iba-light uppercase">Baltog</div>
                    <h3 class="font-bold text-lg text-iba-black dark:text-white uppercase tracking-wider mb-2">The Pioneer</h3>
                    <p class="text-xs font-bold text-gray-500 italic mb-4">"Every great journey begins with the courage to take the first step."</p>
                    <p class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-4">Pioneers who challenge existing ways of thinking, take bold action, and develop innovations that strengthen communities.</p>
                    <div class="pt-4 border-t-2 border-dashed border-gray-300 dark:border-gray-600">
                        <span class="text-xs font-bold text-iba-red uppercase tracking-widest">Focus Areas:</span>
                        <p class="text-xs font-semibold text-gray-600 dark:text-gray-400 mt-1">Disaster resilience, climate adaptation, environmental innovations, community safety.</p>
                    </div>
                </div>

                {{-- BANTONG --}}
                <div class="bg-white dark:bg-gray-800 border-4 border-iba-black dark:border-iba-light p-6 shadow-[6px_6px_0_0_#0095AC] hover:-translate-y-2 transition-transform">
                    <div class="bg-iba-teal text-white font-pixel text-lg py-2 px-4 inline-block mb-4 border-2 border-iba-black dark:border-iba-light uppercase">Bantong</div>
                    <h3 class="font-bold text-lg text-iba-black dark:text-white uppercase tracking-wider mb-2">The Strategist</h3>
                    <p class="text-xs font-bold text-gray-500 italic mb-4">"Wisdom transforms obstacles into opportunities."</p>
                    <p class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-4">Strategists focusing on creating smarter ways of solving problems through innovation, systems thinking, and technology.</p>
                    <div class="pt-4 border-t-2 border-dashed border-gray-300 dark:border-gray-600">
                        <span class="text-xs font-bold text-iba-teal uppercase tracking-widest">Focus Areas:</span>
                        <p class="text-xs font-semibold text-gray-600 dark:text-gray-400 mt-1">Digital government, tourism tech, smart mobility, ed-tech, AI, business process innovations.</p>
                    </div>
                </div>

                {{-- HANDIONG --}}
                <div class="bg-white dark:bg-gray-800 border-4 border-iba-black dark:border-iba-light p-6 shadow-[6px_6px_0_0_#5C7914] hover:-translate-y-2 transition-transform">
                    <div class="bg-iba-green text-white font-pixel text-lg py-2 px-4 inline-block mb-4 border-2 border-iba-black dark:border-iba-light uppercase">Handiong</div>
                    <h3 class="font-bold text-lg text-iba-black dark:text-white uppercase tracking-wider mb-2">The Visionary</h3>
                    <p class="text-xs font-bold text-gray-500 italic mb-4">"Great leaders build tomorrow's communities."</p>
                    <p class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-4">Visionaries who imagine a better future and develop innovations that empower people and create lasting opportunities.</p>
                    <div class="pt-4 border-t-2 border-dashed border-gray-300 dark:border-gray-600">
                        <span class="text-xs font-bold text-iba-green uppercase tracking-widest">Focus Areas:</span>
                        <p class="text-xs font-semibold text-gray-600 dark:text-gray-400 mt-1">Entrepreneurship, inclusive technologies, accessibility, workforce development.</p>
                    </div>
                </div>

            </div>
        </div>

        {{-- 4. WHO CAN JOIN & THE JOURNEY --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

            {{-- Who Can Join --}}
            <div class="bg-iba-black dark:bg-gray-900 border-4 border-iba-black dark:border-iba-light p-6 sm:p-10 text-white shadow-[8px_8px_0_0_#FF8623]">
                <h2 class="font-pixel text-xl sm:text-2xl text-iba-orange uppercase mb-6 border-b-4 border-iba-orange pb-4">WHO CAN JOIN</h2>
                <p class="text-sm sm:text-base font-semibold text-gray-300 mb-6">Teams must have exactly <span class="text-white font-bold bg-iba-orange/20 px-2">3 to 5 members</span>. Interdisciplinary compositions are highly encouraged.</p>
                <div class="flex flex-wrap gap-2">
                    <span class="px-3 py-1.5 border-2 border-gray-600 text-xs font-bold uppercase tracking-wider bg-gray-800">College/SHS Students</span>
                    <span class="px-3 py-1.5 border-2 border-gray-600 text-xs font-bold uppercase tracking-wider bg-gray-800">Startups</span>
                    <span class="px-3 py-1.5 border-2 border-gray-600 text-xs font-bold uppercase tracking-wider bg-gray-800">Developers</span>
                    <span class="px-3 py-1.5 border-2 border-gray-600 text-xs font-bold uppercase tracking-wider bg-gray-800">Designers & Creatives</span>
                    <span class="px-3 py-1.5 border-2 border-gray-600 text-xs font-bold uppercase tracking-wider bg-gray-800">Researchers</span>
                    <span class="px-3 py-1.5 border-2 border-gray-600 text-xs font-bold uppercase tracking-wider bg-gray-800">Civic Tech Advocates</span>
                </div>

                <div class="mt-10">
                    <a href="{{ route('ibalong.register') }}" class="block text-center bg-iba-orange text-iba-black font-pixel px-6 py-4 text-sm sm:text-base uppercase hover:bg-orange-500 transition-colors border-2 border-iba-black">
                        REGISTER YOUR TEAM NOW ➔
                    </a>
                </div>
            </div>

            {{-- The Journey --}}
            <div class="bg-white dark:bg-[#1A1617] border-4 border-iba-black dark:border-iba-light p-6 sm:p-10 shadow-[8px_8px_0_0_#5C7914]">
                <h2 class="font-pixel text-xl sm:text-2xl text-iba-black dark:text-iba-light uppercase mb-6 border-b-4 border-iba-green pb-4">THE JOURNEY</h2>

                <div class="space-y-6 relative before:absolute before:inset-0 before:ml-4 before:-translate-x-px md:before:mx-auto md:before:translate-x-0 before:h-full before:w-1 before:bg-gray-200 dark:before:bg-gray-700">

                    <div class="relative flex items-center justify-between md:justify-normal md:odd:flex-row-reverse group is-active">
                        <div class="flex items-center justify-center w-8 h-8 rounded-full border-4 border-iba-black dark:border-iba-light bg-iba-green text-white font-bold text-xs shadow-sm shrink-0 md:order-1 md:group-odd:-translate-x-1/2 md:group-even:translate-x-1/2 z-10">1</div>
                        <div class="w-[calc(100%-3rem)] md:w-[calc(50%-2.5rem)] p-4 rounded border-2 border-iba-black dark:border-iba-light bg-gray-50 dark:bg-gray-800">
                            <h4 class="font-bold text-iba-black dark:text-white text-sm uppercase">Voices of the City</h4>
                            <p class="text-xs text-gray-600 dark:text-gray-400 mt-1 font-medium">Listen to the lived experiences of Community Heroes and undergo Human-Centered Design.</p>
                        </div>
                    </div>

                    <div class="relative flex items-center justify-between md:justify-normal md:odd:flex-row-reverse group is-active">
                        <div class="flex items-center justify-center w-8 h-8 rounded-full border-4 border-iba-black dark:border-iba-light bg-iba-orange text-white font-bold text-xs shadow-sm shrink-0 md:order-1 md:group-odd:-translate-x-1/2 md:group-even:translate-x-1/2 z-10">2</div>
                        <div class="w-[calc(100%-3rem)] md:w-[calc(50%-2.5rem)] p-4 rounded border-2 border-iba-black dark:border-iba-light bg-gray-50 dark:bg-gray-800">
                            <h4 class="font-bold text-iba-black dark:text-white text-sm uppercase">Hero's Response</h4>
                            <p class="text-xs text-gray-600 dark:text-gray-400 mt-1 font-medium">Submit your Innovation Concept Proposal detailing your inspiration and solution.</p>
                        </div>
                    </div>

                    <div class="relative flex items-center justify-between md:justify-normal md:odd:flex-row-reverse group is-active">
                        <div class="flex items-center justify-center w-8 h-8 rounded-full border-4 border-iba-black dark:border-iba-light bg-iba-teal text-white font-bold text-xs shadow-sm shrink-0 md:order-1 md:group-odd:-translate-x-1/2 md:group-even:translate-x-1/2 z-10">3</div>
                        <div class="w-[calc(100%-3rem)] md:w-[calc(50%-2.5rem)] p-4 rounded border-2 border-iba-black dark:border-iba-light bg-gray-50 dark:bg-gray-800">
                            <h4 class="font-bold text-iba-black dark:text-white text-sm uppercase">The Forge (Aug 12)</h4>
                            <p class="text-xs text-gray-600 dark:text-gray-400 mt-1 font-medium">The official onsite sprint to collaborate with mentors and strengthen prototypes.</p>
                        </div>
                    </div>

                    <div class="relative flex items-center justify-between md:justify-normal md:odd:flex-row-reverse group is-active">
                        <div class="flex items-center justify-center w-8 h-8 rounded-full border-4 border-iba-black dark:border-iba-light bg-iba-red text-white font-bold text-xs shadow-sm shrink-0 md:order-1 md:group-odd:-translate-x-1/2 md:group-even:translate-x-1/2 z-10">4</div>
                        <div class="w-[calc(100%-3rem)] md:w-[calc(50%-2.5rem)] p-4 rounded border-2 border-iba-black dark:border-iba-light bg-gray-50 dark:bg-gray-800">
                            <h4 class="font-bold text-iba-black dark:text-white text-sm uppercase">Hero's Pitch (Aug 13)</h4>
                            <p class="text-xs text-gray-600 dark:text-gray-400 mt-1 font-medium">Present your innovations to judges and compete for Php 100,000 in support.</p>
                        </div>
                    </div>

                </div>
            </div>
        </div>

    </div>
</div>
