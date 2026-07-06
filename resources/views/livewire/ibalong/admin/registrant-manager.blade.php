<div class="max-w-7xl mx-auto animate-fade-in-up">
    
    <div class="mb-8 flex flex-col md:flex-row justify-between items-start md:items-end gap-4">
        <div>
            <h1 class="font-pixel text-2xl sm:text-3xl text-iba-black dark:text-iba-light uppercase tracking-wide">COHORT INTAKE</h1>
            <p class="text-sm font-bold text-gray-500 uppercase tracking-widest mt-2">Evaluate and verify incoming teams</p>
        </div>
        
        <div class="flex flex-col sm:flex-row gap-4 w-full md:w-auto">
            <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search cohorts..." class="border-4 border-iba-black dark:border-iba-light p-3 bg-white dark:bg-[#1A1617] font-bold focus:outline-none focus:border-iba-teal w-full sm:w-64">
            
            <select wire:model.live="statusFilter" class="border-4 border-iba-black dark:border-iba-light p-3 bg-white dark:bg-[#1A1617] font-bold focus:outline-none focus:border-iba-teal uppercase">
                <option value="">All Statuses</option>
                <option value="pending">Pending</option>
                <option value="approved">Approved</option>
                <option value="rejected">Rejected</option>
            </select>
        </div>
    </div>

    @if (session()->has('message'))
        <div class="bg-iba-green border-4 border-iba-black dark:border-iba-light text-white p-4 mb-8 font-bold uppercase tracking-wider shadow-[4px_4px_0_0_#131011] dark:shadow-[4px_4px_0_0_#FFFBF7]">
            ✓ {{ session('message') }}
        </div>
    @endif

    <div class="bg-white dark:bg-[#1A1617] border-4 border-iba-black dark:border-iba-light shadow-[8px_8px_0_0_#0095AC] overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-iba-light dark:bg-iba-black border-b-4 border-iba-black dark:border-iba-light font-pixel text-[10px] text-iba-black dark:text-iba-light">
                        <th class="p-4 uppercase">Team Info</th>
                        <th class="p-4 uppercase">Affiliation</th>
                        <th class="p-4 uppercase">Members</th>
                        <th class="p-4 uppercase">Status</th>
                        <th class="p-4 uppercase text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="font-medium text-sm">
                    @forelse($registrations as $team)
                        <tr class="border-b-2 border-dashed border-gray-300 dark:border-gray-700 hover:bg-iba-light/50 dark:hover:bg-iba-black/50 transition-colors">
                            <td class="p-4">
                                <div class="font-bold text-base uppercase text-iba-teal">{{ $team->team_name }}</div>
                                <div class="text-xs text-gray-500 mt-1 line-clamp-1">{{ $team->team_about }}</div>
                            </td>
                            <td class="p-4 uppercase">{{ $team->affiliation }}</td>
                            <td class="p-4">
                                <span class="bg-iba-black dark:bg-iba-light text-white dark:text-iba-black px-3 py-1 font-bold">{{ $team->number_of_team_members }} / 5</span>
                            </td>
                            <td class="p-4">
                                @if($team->status == 'pending')
                                    <span class="border-2 border-iba-orange text-iba-orange px-2 py-1 font-bold text-xs uppercase">Pending</span>
                                @elseif($team->status == 'approved')
                                    <span class="bg-iba-green border-2 border-iba-black dark:border-iba-light text-white px-2 py-1 font-bold text-xs uppercase">Approved</span>
                                @else
                                    <span class="bg-iba-red border-2 border-iba-black dark:border-iba-light text-white px-2 py-1 font-bold text-xs uppercase">Rejected</span>
                                @endif
                            </td>
                            <td class="p-4 text-right flex justify-end gap-2">
                                <a href="#" class="p-2 border-2 border-iba-black dark:border-iba-light hover:bg-iba-teal hover:text-white transition-colors" title="View Profile">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                </a>
                                @if($team->status == 'pending')
                                    <button wire:click="approveTeam({{ $team->id }})" class="p-2 border-2 border-iba-black dark:border-iba-light hover:bg-iba-green hover:text-white transition-colors" title="Approve">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                    </button>
                                    <button wire:click="rejectTeam({{ $team->id }})" class="p-2 border-2 border-iba-black dark:border-iba-light hover:bg-iba-red hover:text-white transition-colors" title="Reject">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-8 text-center text-gray-500 font-bold uppercase tracking-widest">No cohorts found matching criteria.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="mt-6">
        {{ $registrations->links() }}
    </div>
</div>