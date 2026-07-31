<div class="max-w-7xl mx-auto space-y-6 pb-24">
    
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-white border-4 border-iba-black shadow-[8px_8px_0_0_#FF8623] p-6">
        <div>
            <h1 class="text-2xl font-black uppercase tracking-widest text-iba-black">{{ $isAdminView ? 'Master Quest Roster' : 'My Active Quests' }}</h1>
            <p class="text-xs font-bold text-gray-500 uppercase mt-1">
                {{ $isAdminView ? 'Manage challenges and evaluate cohort submissions.' : 'Track your deadlines and submit your deliverables.' }}
            </p>
        </div>
        @if(in_array(auth('ibalong')->user()->role_id, [1, 2]))
            <a href="{{ route('admin.quests.forge') }}" class="bg-iba-black text-white text-xs font-black uppercase px-6 py-3 border-2 border-transparent hover:-translate-y-1 hover:shadow-[4px_4px_0_0_#0095AC] transition-all">+ Forge New Quest</a>
        @endif
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        @forelse($quests as $quest)
            @php 
                $mySubmission = !$isAdminView ? $quest->submissions->where('team_id', auth('ibalong')->user()->registration->id ?? 0)->first() : null;
                $isLate = $quest->deadline->isPast();
            @endphp
            
            <div class="bg-white border-4 border-iba-black shadow-[6px_6px_0_0_#131011] p-6 flex flex-col relative overflow-hidden group">
                
                {{-- Admin Status Badge --}}
                @if($isAdminView)
                    <div class="absolute top-4 right-4 {{ $quest->is_published ? 'bg-iba-green' : 'bg-gray-500' }} text-white font-black text-[10px] uppercase px-2 py-1 border-2 border-iba-black cursor-pointer" wire:click="togglePublish({{ $quest->id }})">
                        {{ $quest->is_published ? 'Published' : 'Draft Mode' }}
                    </div>
                {{-- Team Status Badge --}}
                @elseif($mySubmission)
                    <div class="absolute top-4 right-4 {{ $mySubmission->status == 'submitted' || $mySubmission->status == 'reviewed' ? 'bg-iba-teal' : 'bg-iba-orange text-iba-black' }} text-white font-black text-[10px] uppercase px-2 py-1 border-2 border-iba-black">
                        {{ $mySubmission->status }}
                    </div>
                @endif

                <h2 class="text-lg font-black uppercase mb-1 pr-20">{{ $quest->title }}</h2>
                <div class="flex items-center gap-2 text-xs font-bold uppercase mb-4 {{ $isLate ? 'text-iba-red' : 'text-gray-500' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Due: {{ $quest->deadline->format('M d, Y - h:i A') }}
                </div>
                
                <p class="text-sm font-bold text-gray-600 line-clamp-3 mb-6 flex-1">{{ $quest->description }}</p>

                <div class="mt-auto border-t-2 border-dashed border-gray-300 pt-4 flex gap-3">
                    @if($isAdminView)
                        {{-- Admin/Judge Buttons --}}
                        <a href="#" class="bg-iba-teal text-white text-center text-xs font-black uppercase tracking-widest w-full py-3 border-2 border-iba-black hover:translate-y-0.5 hover:shadow-[2px_2px_0_0_#131011] transition-all">Evaluate Submissions</a>
                    @else
                        {{-- Team Buttons --}}
                        <a href="{{ route('team.quests.terminal', $quest->id) }}" class="bg-iba-black text-white text-center text-xs font-black uppercase tracking-widest w-full py-3 border-2 border-iba-black hover:translate-y-0.5 hover:shadow-[2px_2px_0_0_#FF8623] transition-all">
                            {{ $mySubmission && $mySubmission->status !== 'draft' ? 'View Transmitted Data' : 'Enter Terminal' }}
                        </a>
                    @endif
                </div>
            </div>
        @empty
            <div class="col-span-1 lg:col-span-2 p-12 border-4 border-dashed border-iba-black text-center bg-gray-50">
                <p class="text-sm font-black text-gray-500 uppercase tracking-widest">No Quests established in the logs.</p>
            </div>
        @endforelse
    </div>
</div>