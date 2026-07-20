<div class="max-w-7xl mx-auto space-y-6">
    
    {{-- Header & Filters --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 bg-white dark:bg-[#1A1617] p-6 border-2 border-iba-black dark:border-iba-light shadow-[4px_4px_0_0_#131011] dark:shadow-[4px_4px_0_0_#FFFBF7]">
        <div>
            <h1 class="text-xl font-black text-iba-black dark:text-iba-light uppercase tracking-wider">Cohort Intake</h1>
            <p class="text-sm font-bold text-gray-500 dark:text-gray-400 mt-1">Evaluate, verify, and approve incoming teams.</p>
        </div>
        <div class="flex flex-col sm:flex-row gap-3 w-full md:w-auto">
            <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search cohorts..." class="w-full sm:w-64 border-2 border-iba-black dark:border-iba-light p-2 text-sm bg-white dark:bg-gray-900 focus:outline-none focus:border-iba-teal text-iba-black dark:text-white font-bold">
            
            <select wire:model.live="statusFilter" class="w-full sm:w-auto border-2 border-iba-black dark:border-iba-light p-2 text-sm bg-white dark:bg-gray-900 focus:outline-none focus:border-iba-teal text-iba-black dark:text-white font-bold uppercase">
                <option value="">All Statuses</option>
                <option value="pending">Pending</option>
                <option value="approved">Approved</option>
                <option value="rejected">Rejected</option>
            </select>
        </div>
    </div>

    {{-- Alert Messages --}}
    @if (session()->has('message'))
        <div class="bg-iba-green/10 border-l-4 border-iba-green p-4 flex items-center justify-between">
            <p class="text-sm font-bold text-iba-green uppercase tracking-wider">{{ session('message') }}</p>
        </div>
    @endif

    {{-- Data Table --}}
    <div class="bg-white dark:bg-[#1A1617] border-2 border-iba-black dark:border-iba-light shadow-[4px_4px_0_0_#131011] dark:shadow-[4px_4px_0_0_#FFFBF7] overflow-x-auto">
        <table class="min-w-full divide-y-2 divide-iba-black dark:divide-iba-light">
            <thead class="bg-gray-100 dark:bg-gray-800">
                <tr>
                    <th scope="col" class="px-6 py-4 text-left text-xs font-black text-iba-black dark:text-iba-light uppercase tracking-wider">Team Info</th>
                    <th scope="col" class="px-6 py-4 text-left text-xs font-black text-iba-black dark:text-iba-light uppercase tracking-wider">Affiliation</th>
                    <th scope="col" class="px-6 py-4 text-center text-xs font-black text-iba-black dark:text-iba-light uppercase tracking-wider">Members</th>
                    <th scope="col" class="px-6 py-4 text-left text-xs font-black text-iba-black dark:text-iba-light uppercase tracking-wider">Status</th>
                    <th scope="col" class="px-6 py-4 text-right text-xs font-black text-iba-black dark:text-iba-light uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700 bg-white dark:bg-[#1A1617]">
                @forelse($registrations as $team)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="text-sm font-bold text-iba-black dark:text-white uppercase">{{ $team->team_name }}</div>
                            <div class="text-xs text-gray-500 mt-1 truncate max-w-xs font-semibold">{{ $team->team_about }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-600 dark:text-gray-400 uppercase">
                            {{ $team->affiliation }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <span class="inline-flex items-center justify-center px-3 py-1 border-2 border-iba-black dark:border-iba-light bg-gray-100 dark:bg-gray-800 font-bold text-xs">
                                {{ $team->number_of_team_members }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($team->status == 'pending')
                                <span class="px-2 py-1 text-[10px] font-bold uppercase tracking-wider border-2 border-yellow-500 text-yellow-600 bg-yellow-50 dark:bg-yellow-900/30">Pending</span>
                            @elseif($team->status == 'approved')
                                <span class="px-2 py-1 text-[10px] font-bold uppercase tracking-wider border-2 border-iba-green text-iba-green bg-green-50 dark:bg-green-900/30">Approved</span>
                            @else
                                <span class="px-2 py-1 text-[10px] font-bold uppercase tracking-wider border-2 border-iba-red text-iba-red bg-red-50 dark:bg-red-900/30">Rejected</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex justify-end gap-3 items-center">
                                <button wire:click="viewTeamDetails({{ $team->id }})" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 font-bold uppercase text-xs tracking-wider">Review</button>
                                
                                {{-- FACILITATOR RBAC: Hide Approve/Reject buttons --}}
                                @if(auth('ibalong')->user()->role_id != 5)
                                    @if($team->status == 'pending')
                                        <span class="text-gray-300 dark:text-gray-600">|</span>
                                        <button wire:click="approveTeam({{ $team->id }})" class="text-iba-teal hover:text-teal-700 font-bold uppercase text-xs tracking-wider">Approve</button>
                                        <span class="text-gray-300 dark:text-gray-600">|</span>
                                        <button wire:click="rejectTeam({{ $team->id }})" class="text-iba-red hover:text-red-700 font-bold uppercase text-xs tracking-wider">Reject</button>
                                    @endif
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-10 text-center">
                            <div class="text-gray-500 dark:text-gray-400 font-bold text-sm uppercase tracking-wider">No cohorts match your criteria.</div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="mt-4">
        {{ $registrations->links() }}
    </div>

    {{-- FULL DETAILS MODAL --}}
    @if($showModal && $viewingTeam)
        <div class="fixed inset-0 z-[100] overflow-y-auto">
            <div class="fixed inset-0 bg-iba-black/80 backdrop-blur-sm transition-opacity" wire:click="closeModal"></div>

            <div class="flex min-h-screen items-center justify-center p-4 text-center sm:p-6">
                <div class="relative w-full max-w-5xl bg-white dark:bg-[#1A1617] border-4 border-iba-black dark:border-iba-light shadow-[8px_8px_0_0_#FF8623] flex flex-col text-left max-h-[90vh]">
                    
                    {{-- Modal Header --}}
                    <div class="px-6 py-4 border-b-4 border-iba-black dark:border-iba-light bg-gray-50 dark:bg-gray-800 flex justify-between items-center shrink-0">
                        <div>
                            <h3 class="text-xl font-black text-iba-black dark:text-white uppercase tracking-wider">{{ $viewingTeam->team_name }}</h3>
                            <p class="text-sm font-bold text-iba-teal mt-1 uppercase">{{ $viewingTeam->affiliation }}</p>
                        </div>
                        <button wire:click="closeModal" class="text-gray-500 hover:text-iba-red transition-colors">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>

                    {{-- Modal Body --}}
                    <div class="px-6 py-6 overflow-y-auto space-y-8 flex-1">
                        
                        {{-- 1. Profile & Location Map --}}
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <div class="flex flex-col h-full">
                                <h4 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Cohort Manifesto</h4>
                                <div class="bg-gray-50 dark:bg-gray-900 border-2 border-iba-black dark:border-gray-700 p-4 font-bold text-sm text-iba-black dark:text-gray-300 leading-relaxed flex-1">
                                    "{{ $viewingTeam->team_about }}"
                                </div>
                            </div>
                            
                            <div class="flex flex-col h-full">
                                <h4 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Team Headquarters</h4>
                                <div class="bg-gray-50 dark:bg-gray-900 border-2 border-iba-black dark:border-gray-700 p-4 flex-1 flex flex-col">
                                    <p class="text-sm font-bold uppercase mb-3 flex items-center gap-2 text-iba-black dark:text-white">
                                        📍 {{ $fullAddress ?: 'Address resolving...' }}
                                    </p>
                                    @if($mapQuery)
                                        <div class="w-full flex-1 min-h-[150px] border-2 border-iba-black dark:border-iba-light overflow-hidden">
                                            <iframe width="100%" height="100%" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="https://maps.google.com/maps?q={{ $mapQuery }}&t=&z=13&ie=UTF8&iwloc=&output=embed"></iframe>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <hr class="border-t-2 border-dashed border-gray-300 dark:border-gray-700">

                        {{-- 2. Declarations & Q&A --}}
                        <div>
                            <h4 class="text-sm font-black text-iba-black dark:text-white uppercase tracking-wider mb-4 border-l-4 border-iba-orange pl-3">Specific Declarations</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                                <div class="bg-white dark:bg-gray-800 p-4 border-2 border-iba-black dark:border-gray-700">
                                    <p class="text-[10px] text-gray-500 font-bold uppercase tracking-wider mb-1">Members from Bicol?</p>
                                    <p class="text-sm font-bold text-iba-teal uppercase">{{ $viewingTeam->team_member_demographics }}</p>
                                </div>
                                <div class="bg-white dark:bg-gray-800 p-4 border-2 border-iba-black dark:border-gray-700">
                                    <p class="text-[10px] text-gray-500 font-bold uppercase tracking-wider mb-1">Onsite Pitching?</p>
                                    <p class="text-sm font-bold text-iba-teal uppercase">{{ $viewingTeam->onsite_commitment }}</p>
                                </div>
                                <div class="bg-white dark:bg-gray-800 p-4 border-2 border-iba-black dark:border-gray-700">
                                    <p class="text-[10px] text-gray-500 font-bold uppercase tracking-wider mb-1">Ack. Selection Rules?</p>
                                    <p class="text-sm font-bold text-iba-teal uppercase">{{ $viewingTeam->does_not_automatically_apply_clause }}</p>
                                </div>
                                <div class="bg-white dark:bg-gray-800 p-4 border-2 border-iba-black dark:border-gray-700">
                                    <p class="text-[10px] text-gray-500 font-bold uppercase tracking-wider mb-1">Via Concept Proposal?</p>
                                    <p class="text-sm font-bold text-iba-teal uppercase">{{ $viewingTeam->selection_on_icp }}</p>
                                </div>
                            </div>
                            <div class="mt-4 flex gap-4 text-xs font-bold border-2 border-iba-black dark:border-gray-700 bg-gray-50 dark:bg-gray-900 p-3 w-fit">
                                <span class="{{ $viewingTeam->data_privacy_consent ? 'text-iba-green' : 'text-iba-red' }}">✓ Data Privacy Consented</span>
                                <span class="text-gray-300 dark:text-gray-600">|</span>
                                <span class="{{ $viewingTeam->media_consent ? 'text-iba-green' : 'text-iba-red' }}">✓ Media Recording Consented</span>
                            </div>
                        </div>

                        <hr class="border-t-2 border-dashed border-gray-300 dark:border-gray-700">

                        {{-- 3. Pivot Data --}}
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <h4 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Team Skills</h4>
                                <div class="flex flex-wrap gap-2">
                                    @forelse($viewingTeam->skills as $skill)
                                        <span class="border-2 border-iba-black bg-blue-50 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 px-2 py-1 text-[10px] font-bold uppercase">{{ $skill->name }}</span>
                                    @empty
                                        <span class="text-xs font-bold text-gray-400 uppercase">None</span>
                                    @endforelse
                                </div>
                            </div>
                            <div>
                                <h4 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Community Areas</h4>
                                <div class="flex flex-wrap gap-2">
                                    @forelse($viewingTeam->communityAreas as $area)
                                        <span class="border-2 border-iba-black bg-orange-50 dark:bg-orange-900/30 text-orange-700 dark:text-orange-300 px-2 py-1 text-[10px] font-bold uppercase">{{ $area->name }}</span>
                                    @empty
                                        <span class="text-xs font-bold text-gray-400 uppercase">None</span>
                                    @endforelse
                                </div>
                            </div>
                            <div>
                                <h4 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Online Activities</h4>
                                <div class="flex flex-wrap gap-2">
                                    @forelse($viewingTeam->onlineActivities as $activity)
                                        <span class="border-2 border-iba-black bg-green-50 dark:bg-green-900/30 text-green-700 dark:text-green-300 px-2 py-1 text-[10px] font-bold uppercase">{{ $activity->name }}</span>
                                    @empty
                                        <span class="text-xs font-bold text-gray-400 uppercase">None</span>
                                    @endforelse
                                </div>
                            </div>
                        </div>

                        <hr class="border-t-2 border-dashed border-gray-300 dark:border-gray-700">

                        {{-- 4. Roster --}}
                        <div>
                            <h4 class="text-sm font-black text-iba-black dark:text-white uppercase tracking-wider mb-4 border-l-4 border-iba-teal pl-3">Team Roster ({{ $viewingTeam->number_of_team_members }})</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
                                @foreach($viewingTeam->members as $member)
                                    <div class="border-2 border-iba-black dark:border-iba-light p-5 bg-white dark:bg-gray-800 shadow-[4px_4px_0_0_#131011] dark:shadow-[4px_4px_0_0_#FFFBF7] flex flex-col">
                                        <div class="flex justify-between items-start mb-3">
                                            <h5 class="font-bold text-sm text-iba-black dark:text-white uppercase">{{ $member->full_name }}</h5>
                                            <span class="px-2 py-1 border-2 border-iba-black text-[9px] font-bold uppercase tracking-wider {{ $member->team_role == 'Team Leader' ? 'bg-iba-red text-white' : 'bg-gray-100 text-iba-black' }}">{{ $member->team_role }}</span>
                                        </div>
                                        
                                        <div class="text-xs font-semibold text-gray-600 dark:text-gray-400 space-y-1 mb-4 flex-1">
                                            <p>{{ $member->email_address }}</p>
                                            <p>{{ $member->mobile_number ?? 'N/A' }}</p>
                                            <p class="uppercase">{{ $member->course ?? 'N/A' }}</p>
                                        </div>

                                        @if($member->skills && $member->skills->count() > 0)
                                            <div class="pt-3 border-t border-dashed border-gray-300 dark:border-gray-700">
                                                <div class="flex flex-wrap gap-1.5">
                                                    @foreach($member->skills as $mskill)
                                                        <span class="px-2 py-0.5 bg-gray-100 dark:bg-gray-900 border border-iba-black dark:border-gray-600 text-gray-700 dark:text-gray-300 text-[9px] font-bold uppercase">{{ $mskill->name }}</span>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>

                    </div>

                    {{-- Modal Footer --}}
                    <div class="bg-gray-50 dark:bg-gray-800 px-6 py-4 border-t-4 border-iba-black dark:border-iba-light sm:flex sm:flex-row-reverse gap-4 shrink-0">
                        {{-- FACILITATOR RBAC: Hide Approve/Reject buttons --}}
                        @if(auth('ibalong')->user()->role_id != 5 && $viewingTeam->status == 'pending')
                            <button wire:click="approveTeam({{ $viewingTeam->id }})" type="button" class="bg-iba-teal text-white font-bold px-6 py-2.5 text-sm uppercase border-2 border-iba-black dark:border-iba-light shadow-[3px_3px_0_0_#131011] dark:shadow-[3px_3px_0_0_#FFFBF7] hover:translate-y-0.5 hover:shadow-none transition-all active:translate-y-1 w-full sm:w-auto">
                                Verify & Approve
                            </button>
                            <button wire:click="rejectTeam({{ $viewingTeam->id }})" type="button" class="bg-iba-red text-white font-bold px-6 py-2.5 text-sm uppercase border-2 border-iba-black dark:border-iba-light shadow-[3px_3px_0_0_#131011] dark:shadow-[3px_3px_0_0_#FFFBF7] hover:translate-y-0.5 hover:shadow-none transition-all active:translate-y-1 w-full sm:w-auto mt-3 sm:mt-0">
                                Reject Application
                            </button>
                        @endif
                        <button wire:click="closeModal" type="button" class="px-6 py-2 text-sm font-bold uppercase text-gray-600 hover:text-iba-black dark:text-gray-400 dark:hover:text-white transition-colors w-full sm:w-auto mt-3 sm:mt-0 border-2 border-transparent hover:border-gray-300 dark:hover:border-gray-600">
                            Close Window
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>