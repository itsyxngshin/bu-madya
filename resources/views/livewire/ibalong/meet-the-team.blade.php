<div class="bg-iba-light dark:bg-iba-black min-h-screen py-10 sm:py-16 px-4 sm:px-6 transition-colors duration-300">
    <div class="max-w-7xl mx-auto space-y-16 sm:space-y-20">

        {{-- PAGE HEADER --}}
        <div class="text-center relative">
            {{-- Decorative Grid Background --}}
            <div class="absolute inset-0 z-0 opacity-10 pointer-events-none flex justify-center items-center text-iba-black dark:text-iba-light">
                <svg class="w-full h-32 sm:h-48" fill="currentColor" viewBox="0 0 100 100" preserveAspectRatio="none">
                    <pattern id="grid" width="4" height="4" patternUnits="userSpaceOnUse">
                        <path d="M 4 0 L 0 0 0 4" fill="none" stroke="currentColor" stroke-width="0.5"/>
                    </pattern>
                    <rect width="100%" height="100%" fill="url(#grid)" />
                </svg>
            </div>

            <div class="relative z-10 inline-block bg-white dark:bg-[#1A1617] border-4 border-iba-black dark:border-iba-light p-5 sm:p-10 shadow-[6px_6px_0_0_#CF452C] sm:shadow-[10px_10px_0_0_#CF452C] mx-2">
                <h1 class="font-pixel text-2xl sm:text-5xl md:text-6xl text-iba-black dark:text-iba-light uppercase tracking-widest leading-tight">
                    THE ROSTER
                </h1>
                <p class="font-bold text-gray-700 dark:text-gray-300 mt-3 sm:mt-4 max-w-2xl mx-auto text-xs sm:text-base uppercase tracking-wider border-t-2 sm:border-t-4 border-iba-orange pt-3 sm:pt-4">
                    Meet the working committees and community leaders powering the Heroes of Innovation Challenge 2026.
                </p>
            </div>
        </div>

        {{-- DYNAMIC COMMITTEES LOOP --}}
        <div class="space-y-16 sm:space-y-24">
            @foreach($committees as $committee)
                <div class="relative">

                    {{-- Committee Section Header --}}
                    <div class="mb-6 sm:mb-10 flex items-center gap-3 sm:gap-4">
                        <h2 class="font-pixel text-lg sm:text-3xl text-white bg-iba-black dark:bg-iba-light dark:text-iba-black px-4 sm:px-6 py-2 sm:py-3 border-2 sm:border-4 border-iba-black dark:border-iba-light shadow-[4px_4px_0_0_#0095AC] sm:shadow-[6px_6px_0_0_#0095AC] inline-block uppercase">
                            {{ $committee->name }}
                        </h2>
                        <div class="flex-1 h-1 sm:h-2 bg-iba-black dark:bg-iba-light w-full"></div>
                    </div>

                    {{-- Members Grid (2 per row on mobile, up to 4 on desktop) --}}
                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3 sm:gap-6 lg:gap-8">
                        @foreach($committee->members as $member)

                            {{-- Determine Card Accent Colors Based on Role --}}
                            @php
                                $isHead = $member->role === 'Head';
                                $accentColor = $isHead ? 'iba-red' : 'iba-teal';
                                $shadowColor = $isHead ? '#CF452C' : '#0095AC';
                            @endphp

                            <div class="group relative flex flex-col bg-white dark:bg-[#1A1617] border-2 sm:border-4 border-iba-black dark:border-iba-light transition-transform duration-200 hover:-translate-y-1 sm:hover:-translate-y-2"
                                 style="box-shadow: 4px 4px 0px 0px {{ $shadowColor }};">

                                {{-- Card Content Wrapper --}}
                                <div class="p-4 sm:p-6 flex flex-col flex-1 items-center text-center">

                                    {{-- Centered, Smaller Avatar Block --}}
                                    <div class="w-20 h-20 sm:w-32 sm:h-32 mb-3 sm:mb-4 border-2 sm:border-4 border-iba-black dark:border-iba-light bg-gray-100 dark:bg-gray-800 relative overflow-hidden shrink-0 transition-transform duration-300 group-hover:scale-105">
                                        @if($member->photo_path)
                                            <img src="{{ Storage::url($member->photo_path) }}" alt="{{ $member->name }}" class="w-full h-full object-cover filter grayscale group-hover:grayscale-0 transition-all duration-500">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center bg-gray-200 dark:bg-gray-800 text-gray-400 dark:text-gray-600 font-pixel text-3xl sm:text-5xl">
                                                {{ substr($member->name, 0, 1) }}
                                            </div>
                                        @endif
                                    </div>

                                    {{-- Role Badge --}}
                                    <div class="bg-{{ $accentColor }} text-white font-bold text-[9px] sm:text-xs px-2 py-0.5 sm:px-3 sm:py-1 border-2 border-iba-black dark:border-iba-light uppercase tracking-wider mb-2 sm:mb-3 shadow-sm inline-block">
                                        @if($isHead) ★ @endif {{ $member->role }}
                                    </div>

                                    {{-- Text Info Block --}}
                                    <h3 class="font-bold text-xs sm:text-lg text-iba-black dark:text-iba-light uppercase leading-tight mb-1 line-clamp-2">
                                        {{ $member->name }}
                                    </h3>

                                    @if($member->designation)
                                        <p class="text-[9px] sm:text-xs font-bold text-{{ $accentColor }} uppercase tracking-wider mb-3 line-clamp-2">
                                            {{ $member->designation }}
                                        </p>
                                    @endif

                                    @if($member->affiliation)
                                        <div class="mt-auto pt-3 sm:pt-4 border-t-2 border-dashed border-gray-300 dark:border-gray-700 w-full">
                                            <p class="text-[8px] sm:text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-widest flex items-center justify-center gap-1 truncate w-full" title="{{ $member->affiliation }}">
                                                <svg class="w-3 h-3 sm:w-4 sm:h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                                <span class="truncate">{{ $member->affiliation }}</span>
                                            </p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>

    </div>
</div>
