<div class="bg-iba-light dark:bg-iba-black min-h-screen py-16 px-4 sm:px-6 transition-colors duration-300">
    <div class="max-w-7xl mx-auto space-y-20">

        {{-- PAGE HEADER --}}
        <div class="text-center relative">
            {{-- Decorative Grid Background --}}
            <div class="absolute inset-0 z-0 opacity-10 pointer-events-none flex justify-center items-center text-iba-black dark:text-iba-light">
                <svg class="w-full h-48" fill="currentColor" viewBox="0 0 100 100" preserveAspectRatio="none">
                    <pattern id="grid" width="4" height="4" patternUnits="userSpaceOnUse">
                        <path d="M 4 0 L 0 0 0 4" fill="none" stroke="currentColor" stroke-width="0.5"/>
                    </pattern>
                    <rect width="100%" height="100%" fill="url(#grid)" />
                </svg>
            </div>

            <div class="relative z-10 inline-block bg-white dark:bg-[#1A1617] border-4 border-iba-black dark:border-iba-light p-6 sm:p-10 shadow-[10px_10px_0_0_#CF452C]">
                <h1 class="font-pixel text-3xl sm:text-5xl md:text-6xl text-iba-black dark:text-iba-light uppercase tracking-widest leading-tight">
                    THE ROSTER
                </h1>
                <p class="font-bold text-gray-700 dark:text-gray-300 mt-4 max-w-2xl mx-auto text-sm sm:text-base uppercase tracking-wider border-t-4 border-iba-orange pt-4">
                    Meet the working committees and community leaders powering the Heroes of Innovation Challenge 2026.
                </p>
            </div>
        </div>

        {{-- DYNAMIC COMMITTEES LOOP --}}
        <div class="space-y-24">
            @foreach($committees as $committee)
                <div class="relative">

                    {{-- Committee Section Header --}}
                    <div class="mb-10 flex items-center gap-4">
                        <h2 class="font-pixel text-xl sm:text-3xl text-white bg-iba-black dark:bg-iba-light dark:text-iba-black px-6 py-3 border-4 border-iba-black dark:border-iba-light shadow-[6px_6px_0_0_#0095AC] inline-block uppercase">
                            {{ $committee->name }}
                        </h2>
                        <div class="flex-1 h-2 bg-iba-black dark:bg-iba-light w-full"></div>
                    </div>

                    {{-- Members Grid --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-x-8 gap-y-12">
                        @foreach($committee->members as $member)

                            {{-- Determine Card Accent Colors Based on Role --}}
                            @php
                                $isHead = $member->role === 'Head';
                                $accentColor = $isHead ? 'iba-red' : 'iba-teal';
                                $shadowColor = $isHead ? '#CF452C' : '#0095AC';
                            @endphp

                            <div class="group relative flex flex-col bg-white dark:bg-[#1A1617] border-4 border-iba-black dark:border-iba-light transition-transform duration-200 hover:-translate-y-2"
                                 style="box-shadow: 8px 8px 0px 0px {{ $shadowColor }};">

                                {{-- Avatar / Photo Block --}}
                                <div class="w-full aspect-square border-b-4 border-iba-black dark:border-iba-light bg-gray-100 dark:bg-gray-800 relative overflow-hidden">
                                    @if($member->photo_path)
                                        <img src="{{ Storage::url($member->photo_path) }}" alt="{{ $member->name }}" class="w-full h-full object-cover filter grayscale group-hover:grayscale-0 transition-all duration-500">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center bg-gray-200 dark:bg-gray-800 text-gray-400 dark:text-gray-600 font-pixel text-6xl">
                                            {{ substr($member->name, 0, 1) }}
                                        </div>
                                    @endif

                                    {{-- Role Badge --}}
                                    <div class="absolute top-0 right-0 bg-{{ $accentColor }} text-white font-bold text-[10px] sm:text-xs px-3 py-1 border-b-4 border-l-4 border-iba-black dark:border-iba-light uppercase tracking-wider shadow-sm z-10">
                                        @if($isHead) ★ @endif {{ $member->role }}
                                    </div>
                                </div>

                                {{-- Text Info Block --}}
                                <div class="p-5 flex flex-col flex-1">
                                    <h3 class="font-bold text-lg sm:text-xl text-iba-black dark:text-iba-light uppercase leading-tight mb-1">
                                        {{ $member->name }}
                                    </h3>

                                    @if($member->designation)
                                        <p class="text-sm font-bold text-{{ $accentColor }} uppercase tracking-wider mb-3">
                                            {{ $member->designation }}
                                        </p>
                                    @endif

                                    @if($member->affiliation)
                                        <div class="mt-auto pt-4 border-t-2 border-dashed border-gray-300 dark:border-gray-700">
                                            <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-widest flex items-center gap-2">
                                                <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                                {{ $member->affiliation }}
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
