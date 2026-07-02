<div class="max-w-5xl mx-auto py-8 md:py-12 px-4 sm:px-6 font-sans">

    @php
        // Robust display name fallback for both real users and dummy candidates
        $candidateName = $candidate->display_name ?? optional($candidate->user)->name ?? 'Unknown Candidate';
        $initial = strtoupper(substr($candidateName, 0, 1));
    @endphp

    {{-- BACK BUTTON --}}
    <a href="{{ url()->previous() !== url()->current() ? url()->previous() : '/' }}" class="inline-flex items-center gap-2 text-sm font-bold text-gray-500 hover:text-red-600 transition-colors mb-6 group">
        <svg class="w-4 h-4 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        Back to Previous Page
    </a>

    {{-- PROFILE HEADER CARD --}}
    <div class="bg-white rounded-[2rem] shadow-sm border border-gray-200 overflow-hidden mb-8 relative">

        {{-- Tri-Color Gradient Banner --}}
        <div class="h-32 md:h-48 bg-gradient-to-r from-red-600 via-yellow-400 to-green-600 relative">
            <div class="absolute inset-0 opacity-20 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')]"></div>
        </div>

        <div class="px-6 md:px-10 pb-8 md:pb-10">
            {{-- FIXED FOR MOBILE: Added items-center and text-center for mobile screens --}}
            <div class="flex flex-col md:flex-row items-center md:items-start text-center md:text-left gap-4 md:gap-8">

                {{-- Avatar Wrapper (Portrait Photo Frame) --}}
                <div class="-mt-16 md:-mt-20 shrink-0 relative z-10">
                    <div class="w-32 h-44 md:w-44 md:h-56 rounded-2xl border-[6px] border-white shadow-xl bg-white overflow-hidden flex items-center justify-center">
                        @if($candidate->profile_photo_path)
                            <img src="{{ asset('storage/'.$candidate->profile_photo_path) }}" class="w-full h-full object-cover">
                        @else
                            <span class="text-6xl md:text-7xl font-black text-gray-300">{{ $initial }}</span>
                        @endif
                    </div>
                </div>

                {{-- Basic Info --}}
                {{-- FIXED FOR MOBILE: Added flex-col and items-center to center the elements properly --}}
                <div class="flex-1 pt-2 md:pt-4 flex flex-col items-center md:items-start">
                    <div class="inline-flex items-center justify-center gap-2 bg-yellow-50 text-yellow-800 px-3 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest mb-3 border border-yellow-200">
                        Running for {{ $candidate->electionPosition->title ?? $candidate->position->title ?? 'Position' }}
                    </div>

                    <h1 class="text-3xl md:text-5xl font-black text-gray-900 tracking-tight leading-none mb-2">
                        {{ $candidateName }}
                    </h1>

                    {{-- FIXED FOR MOBILE: Added justify-center for the icon and text alignment --}}
                    <p class="text-sm md:text-base font-bold text-gray-500 flex items-center justify-center md:justify-start gap-2">
                        <svg class="w-4 h-4 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"></path></svg>
                        {{ $candidate->program }} ({{ $candidate->year_level }})
                    </p>
                    <p class="text-xs font-bold text-gray-400 mt-1 uppercase tracking-wider">{{ $candidate->college->name ?? '' }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- DETAILS GRID --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        {{-- LEFT COLUMN: Credentials & Experience --}}
        <div class="lg:col-span-1 space-y-8">
            <div class="bg-white rounded-[2rem] p-6 md:p-8 shadow-sm border border-gray-200">
                <h3 class="text-lg font-black text-gray-900 uppercase tracking-tight flex items-center gap-2 mb-6">
                    <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path></svg>
                    Credentials
                </h3>

                @if($candidate->credentials && $candidate->credentials->count() > 0)
                    <div class="space-y-5">
                        @foreach($candidate->credentials as $credential)
                            <div class="border-l-2 border-red-200 pl-4 relative before:absolute before:w-2 before:h-2 before:bg-red-500 before:rounded-full before:-left-[5px] before:top-1.5">
                                <p class="text-[10px] font-bold text-red-600 uppercase tracking-wider mb-1">{{ $credential->type }}</p>
                                <p class="text-sm font-bold text-gray-800 leading-snug">{{ $credential->description }}</p>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-sm text-gray-400 font-medium italic">No credentials provided.</p>
                @endif
            </div>
        </div>

        {{-- RIGHT COLUMN: General Plan of Action (Platforms) --}}
        <div class="lg:col-span-2 space-y-8">
            <div class="bg-white rounded-[2rem] p-6 md:p-8 shadow-sm border border-gray-200 h-full">
                <h3 class="text-lg font-black text-gray-900 uppercase tracking-tight flex items-center gap-2 mb-6 border-b border-gray-100 pb-4">
                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9.5a2.5 2.5 0 00-2.5-2.5H14"></path></svg>
                    General Plan of Action (GPOA)
                </h3>

                @if($candidate->platforms && $candidate->platforms->count() > 0)
                    <div class="grid grid-cols-1 gap-4">
                        @foreach($candidate->platforms as $platform)
                            <div class="bg-gray-50 rounded-2xl p-5 border border-gray-100 hover:border-green-300 transition-colors group">
                                <h4 class="text-base font-black text-gray-900 mb-2 group-hover:text-green-700 transition-colors">{{ $platform->title }}</h4>
                                <p class="text-sm text-gray-600 leading-relaxed font-medium">{{ $platform->description }}</p>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12 bg-gray-50 rounded-2xl border border-dashed border-gray-200">
                        <svg class="w-10 h-10 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        <p class="text-sm text-gray-400 font-bold">Platforms have not been published yet.</p>
                    </div>
                @endif
            </div>
        </div>

    </div>
</div>