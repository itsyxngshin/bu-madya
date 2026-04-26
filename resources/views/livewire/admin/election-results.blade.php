<div class="max-w-7xl mx-auto py-8 px-4 font-sans pb-32">
    
    {{-- HEADER & ELECTION SELECTOR --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div>
            <h1 class="text-3xl font-black text-gray-900 tracking-tight flex items-center gap-3">
                <a href="{{ route('admin.elections.index') }}" class="text-gray-400 hover:text-orange-600 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                </a>
                Live Election Analytics
            </h1>
            <p class="text-gray-500 font-medium ml-9">Monitoring: <span class="font-bold">{{ $election->title }}</span></p>
        </div>
    </div>

    @if($election)
        {{-- ELECTION STATUS & STATS OVERVIEW --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-200 flex items-center gap-4">
                <div class="w-14 h-14 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                </div>
                <div>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Total Turnout</p>
                    <p class="text-2xl font-black text-gray-900">{{ number_format($totalVoters) }} <span class="text-sm font-bold text-gray-500">voters</span></p>
                </div>
            </div>

            <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-200 flex items-center gap-4 md:col-span-2">
                <div class="w-14 h-14 rounded-full bg-orange-50 text-orange-600 flex items-center justify-center shrink-0">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <div class="flex-1">
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Timeline Status</p>
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between mt-1">
                        <p class="text-sm font-bold text-gray-800">
                            @if(now() < $election->voting_start)
                                <span class="text-yellow-600">Opens:</span> {{ $election->voting_start->format('M d, Y h:i A') }}
                            @elseif(now() > $election->voting_end)
                                <span class="text-red-600">Closed:</span> {{ $election->voting_end->format('M d, Y h:i A') }}
                            @else
                                <span class="text-green-600 flex items-center gap-1.5"><span class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></span> Voting Live Now</span>
                            @endif
                        </p>
                        <p class="text-xs font-bold text-gray-400 mt-1 sm:mt-0">Results Publish: {{ $election->results_release ? $election->results_release->format('M d, h:i A') : 'TBA' }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- THE TALLIES PER POSITION --}}
        <div class="space-y-8">
            @forelse($election->positions as $position)
                @php
                    // Calculate the total votes cast specifically for this position
                    $totalPositionVotes = $position->candidates->sum('votes_count');
                @endphp

                <div class="bg-white rounded-[2rem] shadow-sm border border-gray-200 overflow-hidden">
                    <div class="bg-gray-50/80 border-b border-gray-200 p-6 flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
                        <div>
                            <h3 class="text-xl font-black text-gray-900 uppercase tracking-tight">{{ $position->title }}</h3>
                            <p class="text-xs font-bold text-gray-500 mt-1">{{ number_format($totalPositionVotes) }} total votes cast • Top {{ $position->max_winners }} win</p>
                        </div>
                    </div>
                    
                    <div class="p-6 md:p-8 space-y-6">
                        @forelse($position->candidates as $index => $candidate)
                            @php
                                // Math for the progress bar
                                $percentage = $totalPositionVotes > 0 ? ($candidate->votes_count / $totalPositionVotes) * 100 : 0;
                                
                                // Determine if they are in a winning slot based on the max_winners setting
                                $isWinning = $index < $position->max_winners && $candidate->votes_count > 0;
                            @endphp

                            <div class="relative">
                                <div class="flex items-center justify-between mb-2">
                                    <div class="flex items-center gap-3">
                                        @if($candidate->profile_photo_path)
                                            <img src="{{ asset('storage/'.$candidate->profile_photo_path) }}" class="w-10 h-10 rounded-full object-cover shadow-sm border border-gray-200">
                                        @else
                                            <div class="w-10 h-10 rounded-full bg-gray-100 text-gray-500 flex items-center justify-center font-bold text-sm">{{ substr($candidate->user->name ?? '?', 0, 1) }}</div>
                                        @endif
                                        
                                        <div>
                                            <p class="font-black text-gray-900 flex items-center gap-2">
                                                {{ $candidate->user->name ?? 'Unknown' }}
                                                @if($isWinning)
                                                    <span class="px-2 py-0.5 bg-green-100 text-green-700 text-[9px] uppercase tracking-widest font-black rounded-full">{{ now() > $election->voting_end ? 'Winner' : 'Leading' }}</span>
                                                @endif
                                            </p>
                                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">{{ $candidate->program }}</p>
                                        </div>
                                    </div>
                                    
                                    <div class="text-right">
                                        <p class="text-lg font-black {{ $isWinning ? 'text-green-600' : 'text-gray-900' }}">{{ number_format($candidate->votes_count) }}</p>
                                        <p class="text-xs font-bold text-gray-400">{{ number_format($percentage, 1) }}%</p>
                                    </div>
                                </div>

                                {{-- Progress Bar --}}
                                <div class="w-full bg-gray-100 rounded-full h-3 overflow-hidden shadow-inner">
                                    <div class="h-full rounded-full transition-all duration-1000 ease-out {{ $isWinning ? 'bg-green-500' : 'bg-gray-400' }}" style="width: {{ $percentage }}%;"></div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center text-gray-400 font-bold py-4 text-sm">No approved candidates for this position.</div>
                        @endforelse
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-3xl p-12 text-center border border-gray-200">
                    <p class="text-gray-500 font-bold">No positions configured for this election yet.</p>
                </div>
            @endforelse
        </div>
    @else
        <div class="bg-white rounded-3xl p-12 text-center border border-gray-200">
            <p class="text-gray-500 font-bold">Please select an election to view results.</p>
        </div>
    @endif
</div>