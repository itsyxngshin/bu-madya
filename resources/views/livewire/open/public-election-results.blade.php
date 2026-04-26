<div class="min-h-screen bg-stone-50 py-8 md:py-16 px-4 font-sans selection:bg-red-600 selection:text-white">
    <div class="max-w-4xl mx-auto">
        
        {{-- HEADER SECTION --}}
        <div class="text-center mb-10 md:mb-16">
            <div class="inline-flex items-center justify-center gap-2 bg-red-100 text-red-700 px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest mb-4">
                <span class="w-2 h-2 rounded-full bg-red-600 animate-pulse"></span>
                Official Results
            </div>
            <h1 class="text-3xl md:text-5xl font-black text-gray-900 tracking-tight mb-3 leading-tight">
                {{ $election->title }}
            </h1>
            <p class="text-gray-500 font-medium text-sm md:text-base">
                Total Ballots Cast: <span class="font-bold text-gray-900">{{ number_format($totalVotesCast) }}</span>
            </p>
        </div>

        {{-- RESULTS BY POSITION --}}
        <div class="space-y-8 md:space-y-16">
            @foreach($election->positions as $position)
                <div class="bg-white rounded-[2rem] p-5 md:p-8 shadow-[0_4px_20px_rgba(0,0,0,0.03)] border border-gray-100">
                    
                    {{-- Position Header --}}
                    <div class="flex flex-col md:flex-row md:items-center justify-between mb-5 md:mb-6 pb-4 border-b border-gray-100 gap-2">
                        <h2 class="text-xl md:text-2xl font-black text-gray-900">
                            {{ $position->title }}
                        </h2>
                        <span class="text-[10px] md:text-xs font-bold text-orange-600 bg-orange-50 px-3 py-1.5 rounded-lg w-max uppercase tracking-wider">
                            Electing {{ $position->max_winners }} Seat(s)
                        </span>
                    </div>

                    {{-- Candidates List (Mobile-Optimized Cards) --}}
                    <div class="space-y-3 md:space-y-4">
                        @foreach($position->candidates as $index => $candidate)
                            @php
                                $percentage = $totalVotesCast > 0 ? ($candidate->votes_count / $totalVotesCast) * 100 : 0;
                                $isWinner = $index < $position->max_winners;
                            @endphp

                            <div class="relative bg-gray-50 rounded-2xl p-4 border {{ $isWinner ? 'border-green-200 bg-green-50/50' : 'border-gray-200' }} overflow-hidden">
                                
                                {{-- Background Progress Bar --}}
                                <div class="absolute top-0 left-0 bottom-0 opacity-10 transition-all duration-1000 ease-out {{ $isWinner ? 'bg-green-500' : 'bg-gray-400' }}" style="width: {{ $percentage }}%;"></div>

                                <div class="relative z-10 flex items-center gap-3 md:gap-5">
                                    {{-- Rank --}}
                                    <div class="w-6 md:w-8 flex justify-center shrink-0">
                                        <span class="text-base md:text-xl font-black {{ $isWinner ? 'text-green-600' : 'text-gray-400' }}">
                                            #{{ $index + 1 }}
                                        </span>
                                    </div>

                                    {{-- Avatar --}}
                                    <div class="w-10 h-10 md:w-14 md:h-14 rounded-full overflow-hidden border-2 border-white shadow-sm shrink-0 bg-white">
                                        @if($candidate->profile_photo_path)
                                            <img src="{{ asset('storage/'.$candidate->profile_photo_path) }}" class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full bg-gray-100 flex items-center justify-center text-gray-400 font-black text-sm md:text-xl">
                                                {{ substr($candidate->user->name, 0, 1) }}
                                            </div>
                                        @endif
                                    </div>

                                    {{-- Info --}}
                                    <div class="flex-1 min-w-0">
                                        <h3 class="font-bold text-gray-900 text-sm md:text-base truncate leading-tight">
                                            {{ $candidate->user->name }}
                                        </h3>
                                        <p class="text-[9px] md:text-xs text-gray-500 font-medium truncate mt-0.5">
                                            {{ $candidate->program }}
                                        </p>
                                    </div>

                                    {{-- Stats --}}
                                    <div class="text-right shrink-0 pl-2">
                                        <div class="font-black text-base md:text-2xl {{ $isWinner ? 'text-green-700' : 'text-gray-900' }} leading-none">
                                            {{ number_format($candidate->votes_count) }}
                                        </div>
                                        <div class="text-[9px] md:text-xs font-bold text-gray-400 uppercase tracking-widest mt-1">
                                            {{ number_format($percentage, 1) }}%
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-12 text-center">
            <a href="{{ route('open.home') }}" class="text-[10px] md:text-xs font-bold text-gray-400 hover:text-gray-900 uppercase tracking-widest transition-colors py-4 inline-block">
                &larr; Return to Homepage
            </a>
        </div>

    </div>
</div>