<div class="max-w-7xl mx-auto space-y-6 pb-24">

    {{-- Header Section --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-white dark:bg-[#1A1617] border-4 border-iba-black dark:border-gray-200 shadow-[8px_8px_0_0_#FF8623] p-6 transition-colors duration-300">
        <div>
            <h1 class="text-2xl font-black uppercase tracking-widest text-iba-black dark:text-white">{{ $isAdminView ? 'Master Quest Roster' : 'My Active Quests' }}</h1>
            <p class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase mt-1">
                {{ $isAdminView ? 'Manage challenges and evaluate cohort submissions.' : 'Track your deadlines and submit your deliverables.' }}
            </p>
        </div>
        @if(in_array(auth('ibalong')->user()->role_id, [1, 2, 4]))
            <a href="{{ route('ibalong.admin.quests.forge') }}" class="bg-iba-black dark:bg-gray-200 text-white dark:text-iba-black text-xs font-black uppercase px-6 py-3 border-2 border-transparent hover:-translate-y-1 hover:shadow-[4px_4px_0_0_#0095AC] transition-all">+ Forge New Quest</a>
        @endif
    </div>

    @if (session()->has('success'))
        <div class="bg-iba-green/10 border-l-4 border-iba-green p-4 flex items-center shadow-[4px_4px_0_0_#131011] dark:shadow-[4px_4px_0_0_#FFFBF7]">
            <p class="text-sm font-bold text-iba-green uppercase tracking-wider">{{ session('success') }}</p>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        @forelse($quests as $quest)
            @php
                $mySubmission = !$isAdminView ? $quest->submissions->where('team_id', auth('ibalong')->user()->registration->id ?? 0)->first() : null;
                $isLate = $quest->deadline->isPast();
            @endphp

            {{-- Quest Card --}}
            <div class="bg-white dark:bg-[#1A1617] border-4 border-iba-black dark:border-gray-200 shadow-[6px_6px_0_0_#131011] dark:shadow-[6px_6px_0_0_#FFFBF7] p-6 flex flex-col relative overflow-hidden group transition-colors duration-300">

                {{-- Admin Status Badge --}}
                @if($isAdminView)
                    <div class="absolute top-4 right-4 {{ $quest->is_published ? 'bg-iba-green' : 'bg-gray-500' }} text-white font-black text-[10px] uppercase px-2 py-1 border-2 border-iba-black dark:border-gray-200 cursor-pointer" wire:click="togglePublish({{ $quest->id }})">
                        {{ $quest->is_published ? 'Published' : 'Draft Mode' }}
                    </div>
                {{-- Team Status Badge --}}
                @elseif($mySubmission)
                    <div class="absolute top-4 right-4 {{ $mySubmission->status == 'submitted' || $mySubmission->status == 'reviewed' ? 'bg-iba-teal' : 'bg-iba-orange text-iba-black' }} text-white font-black text-[10px] uppercase px-2 py-1 border-2 border-iba-black dark:border-gray-200">
                        {{ $mySubmission->status }}
                    </div>
                @endif

                {{-- Quest Title & Deadline --}}
                <h2 class="text-lg font-black uppercase mb-1 pr-20 text-iba-black dark:text-white">{{ $quest->title }}</h2>
                <div class="flex items-center gap-2 text-xs font-bold uppercase mb-4 {{ $isLate ? 'text-iba-red' : 'text-gray-500 dark:text-gray-400' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Due: {{ $quest->deadline->format('M d, Y - h:i A') }}
                </div>

                {{-- Description --}}
                <p class="text-sm font-bold text-gray-600 dark:text-gray-300 line-clamp-3 mb-6 flex-1">{{ $quest->description }}</p>

                {{-- Action Controls --}}
                <div class="mt-auto border-t-2 border-dashed border-gray-300 dark:border-gray-600 pt-4">
                    @if($isAdminView)
                        {{-- Admin/Judge Controls --}}
                        <div class="flex flex-col gap-2">
                            <div class="flex gap-2">
                                <a href="{{ route('ibalong.admin.quests.submissions', $quest->id) }}" class="flex-1 bg-iba-teal text-white text-center text-xs font-black uppercase tracking-widest py-3 border-2 border-iba-black dark:border-gray-200 hover:translate-y-0.5 hover:shadow-[2px_2px_0_0_#131011] dark:hover:shadow-[2px_2px_0_0_#FFFBF7] transition-all">Submissions</a>

                                @if(in_array(auth('ibalong')->user()->role_id, [1, 2, 4]))
                                    <a href="{{ route('ibalong.admin.quests.results', $quest->id) }}" class="flex-1 bg-iba-black dark:bg-gray-200 text-iba-orange dark:text-iba-black text-center text-xs font-black uppercase tracking-widest py-3 border-2 border-iba-black dark:border-gray-200 hover:translate-y-0.5 hover:shadow-[2px_2px_0_0_#FF8623] transition-all">Tabulation</a>
                                    {{-- NEW: Clearance Terminal Route --}}
                                    <a href="{{ route('ibalong.admin.quests.clearance', $quest->id) }}" class="flex-1 bg-white dark:bg-[#1A1617] text-iba-red text-center text-xs font-black uppercase tracking-widest py-3 border-2 border-iba-black dark:border-gray-200 hover:translate-y-0.5 hover:shadow-[2px_2px_0_0_#D93B3B] transition-all">
                                        @if($quest->is_restricted)
                                            <span class="flex items-center justify-center gap-1">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                                Locked
                                            </span>
                                        @else
                                            Clearance
                                        @endif
                                    </a>
                                @endif
                            </div>

                            <div class="flex gap-2">
                                <a href="{{ route('ibalong.admin.quests.forge', $quest->id) }}" class="flex-1 bg-gray-100 dark:bg-gray-700 text-iba-black dark:text-white text-center text-[10px] font-black uppercase tracking-widest py-2 border-2 border-iba-black dark:border-gray-200 hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">Edit Quest</a>
                                <button wire:click="deleteQuest({{ $quest->id }})" wire:confirm="WARNING: This will permanently delete the Quest, all Tasks, Criteria, AND all Team Submissions/Scores attached to it. Proceed?" class="flex-1 bg-iba-red text-white text-center text-[10px] font-black uppercase tracking-widest py-2 border-2 border-iba-black dark:border-gray-200 hover:bg-red-800 transition-colors">Drop</button>
                            </div>
                        </div>
                    @else
                        {{-- Team Controls --}}
                        <a href="{{ route('ibalong.team.quests.terminal', $quest->id) }}" class="block bg-iba-black dark:bg-gray-200 text-white dark:text-iba-black text-center text-xs font-black uppercase tracking-widest w-full py-3 border-2 border-iba-black dark:border-gray-200 hover:translate-y-0.5 hover:shadow-[2px_2px_0_0_#FF8623] transition-all">
                            {{ $mySubmission && $mySubmission->status !== 'draft' ? 'View Transmitted Data' : 'Enter Terminal' }}
                        </a>
                    @endif
                </div>
            </div>
        @empty
            <div class="col-span-1 lg:col-span-2 p-12 border-4 border-dashed border-iba-black dark:border-gray-500 text-center bg-gray-50 dark:bg-gray-800">
                <p class="text-sm font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest">No Quests established in the logs.</p>
            </div>
        @endforelse
    </div>
</div>
