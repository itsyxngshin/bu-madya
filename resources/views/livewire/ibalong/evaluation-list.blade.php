<div class="max-w-7xl mx-auto space-y-6 pb-24">

    {{-- Header Section --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-white border-4 border-iba-black shadow-[8px_8px_0_0_#FF8623] p-6">
        <div>
            <h1 class="text-2xl font-black uppercase tracking-widest text-iba-black">{{ $isAdminView ? 'Evaluation Matrix' : 'Active Surveys & Feedback' }}</h1>
            <p class="text-xs font-bold text-gray-500 uppercase mt-1">
                {{ $isAdminView ? 'Manage forms, certificates, and view respondent data.' : 'Provide your feedback for the designated events.' }}
            </p>
        </div>
        @if($isAdminView)
            <a href="{{ route('ibalong.admin.evaluations.forge') }}" class="bg-iba-black text-white text-xs font-black uppercase px-6 py-3 border-2 border-transparent hover:-translate-y-1 hover:shadow-[4px_4px_0_0_#0095AC] transition-all">+ Forge Blueprint</a>
        @endif
    </div>

    @if (session()->has('success'))
        <div class="bg-iba-green/10 border-l-4 border-iba-green p-4 flex items-center shadow-[4px_4px_0_0_#131011]">
            <p class="text-sm font-bold text-iba-green uppercase tracking-wider">{{ session('success') }}</p>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        @forelse($evaluations as $eval)
            <div class="bg-white border-4 border-iba-black shadow-[6px_6px_0_0_#131011] p-6 flex flex-col relative overflow-hidden group">

                @if($isAdminView)
                    <div class="absolute top-4 right-4 {{ $eval->is_active ? 'bg-iba-green' : 'bg-gray-500' }} text-white font-black text-[10px] uppercase px-2 py-1 border-2 border-iba-black cursor-pointer" wire:click="toggleStatus({{ $eval->id }})">
                        {{ $eval->is_active ? 'Live / Active' : 'Offline / Draft' }}
                    </div>
                @endif

                <h2 class="text-lg font-black uppercase mb-1 pr-20 text-iba-black">{{ $eval->title }}</h2>

                {{-- NEW: Access Control Badge --}}
                <div class="mb-3">
                    @if($eval->access_level === 'teams_only')
                        <span class="bg-iba-orange/20 text-iba-orange border-2 border-iba-orange px-2 py-0.5 text-[9px] font-black uppercase tracking-widest">🔒 Cohort Exclusive</span>
                    @else
                        <span class="bg-iba-teal/10 text-iba-teal border-2 border-iba-teal px-2 py-0.5 text-[9px] font-black uppercase tracking-widest">Public Access</span>
                    @endif
                </div>

                <div class="flex items-center gap-2 text-[10px] font-black uppercase mb-4 text-iba-teal tracking-widest">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    Responses Logged: {{ $eval->responses_count }}
                </div>

                <p class="text-sm font-bold text-gray-600 line-clamp-3 mb-6 flex-1">{{ $eval->description }}</p>

                <div class="mt-auto border-t-2 border-dashed border-gray-300 pt-4">
                    @if($isAdminView)
                        <div class="flex flex-col gap-2">
                            <a href="{{ route('ibalong.admin.evaluations.edit', $eval->slug) }}" class="block bg-gray-100 text-iba-black text-center text-xs font-black uppercase tracking-widest w-full py-3 border-2 border-iba-black hover:bg-gray-200 transition-colors">Edit Blueprint</a>
                            <div class="flex gap-2">
                                <a href="{{ route('ibalong.admin.evaluations.list' }}" class="flex-1 bg-iba-teal text-white text-center text-[10px] font-black uppercase tracking-widest py-2 border-2 border-iba-black hover:bg-teal-700 transition-colors">View Data</a>
                                <button wire:click="deleteEvaluation({{ $eval->id }})" wire:confirm="WARNING: This permanently deletes the form and ALL recorded answers. Proceed?" class="flex-1 bg-iba-red text-white text-center text-[10px] font-black uppercase tracking-widest py-2 border-2 border-iba-black hover:bg-red-800 transition-colors">Drop</button>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('ibalong.evaluations.terminal', $eval->slug) }}" class="block bg-iba-black text-white text-center text-xs font-black uppercase tracking-widest w-full py-3 border-2 border-iba-black hover:translate-y-0.5 hover:shadow-[2px_2px_0_0_#FF8623] transition-all">
                            Enter Terminal
                        </a>
                    @endif
                </div>
            </div>
        @empty
            <div class="col-span-1 lg:col-span-2 p-12 border-4 border-dashed border-iba-black text-center bg-gray-50">
                <p class="text-sm font-black text-gray-500 uppercase tracking-widest">No active forms available.</p>
            </div>
        @endforelse
    </div>
</div>
