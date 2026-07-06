<div class="max-w-7xl mx-auto">
    
    {{-- Header & Filters --}}
    <div class="sm:flex sm:items-center sm:justify-between mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white tracking-tight">Cohort Intake</h1>
            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Evaluate, verify, and approve incoming teams.</p>
        </div>
        <div class="mt-4 sm:mt-0 flex flex-col sm:flex-row gap-3">
            <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search cohorts..." class="block w-full sm:w-64 rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-iba-teal focus:ring-iba-teal sm:text-sm">
            
            <select wire:model.live="statusFilter" class="block w-full sm:w-auto rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-iba-teal focus:ring-iba-teal sm:text-sm">
                <option value="">All Statuses</option>
                <option value="pending">Pending</option>
                <option value="approved">Approved</option>
                <option value="rejected">Rejected</option>
            </select>
        </div>
    </div>

    {{-- Alert Messages --}}
    @if (session()->has('message'))
        <div class="rounded-md bg-green-50 dark:bg-green-900/30 p-4 mb-6 border border-green-200 dark:border-green-800">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-green-800 dark:text-green-300">{{ session('message') }}</p>
                </div>
            </div>
        </div>
    @endif

    {{-- Data Table --}}
    <div class="bg-white dark:bg-gray-800 shadow-sm border border-gray-200 dark:border-gray-700 rounded-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-900/50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Team Info</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Affiliation</th>
                        <th scope="col" class="px-6 py-3 text-center text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Members</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                        <th scope="col" class="relative px-6 py-3 text-right">
                            <span class="sr-only">Actions</span>
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700 bg-white dark:bg-gray-800">
                    @forelse($registrations as $team)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="text-sm font-bold text-gray-900 dark:text-white">{{ $team->team_name }}</div>
                                <div class="text-sm text-gray-500 dark:text-gray-400 mt-1 truncate max-w-xs">{{ $team->team_about }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                {{ $team->affiliation }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">
                                    {{ $team->number_of_team_members }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($team->status == 'pending')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400">Pending</span>
                                @elseif($team->status == 'approved')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">Approved</span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400">Rejected</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex justify-end gap-3">
                                    <button wire:click="viewTeamDetails({{ $team->id }})" class="text-gray-400 hover:text-gray-900 dark:hover:text-white" title="View Details">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    </button>
                                    @if($team->status == 'pending')
                                        <button wire:click="approveTeam({{ $team->id }})" class="text-iba-teal hover:text-teal-700 dark:hover:text-teal-400" title="Approve">
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                        </button>
                                        <button wire:click="rejectTeam({{ $team->id }})" class="text-iba-red hover:text-red-700 dark:hover:text-red-400" title="Reject">
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                </svg>
                                <p class="mt-4 text-sm text-gray-500 dark:text-gray-400">No cohorts match your criteria.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    
    <div class="mt-4">
        {{ $registrations->links() }}
    </div>

    {{-- FULL DETAILS MODAL --}}
    @if($showModal && $viewingTeam)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                
                {{-- Background overlay --}}
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 dark:bg-gray-900 dark:bg-opacity-80 transition-opacity" wire:click="closeModal"></div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                {{-- Modal Panel --}}
                <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl w-full border border-gray-200 dark:border-gray-700">
                    
                    {{-- Modal Header --}}
                    <div class="bg-gray-50 dark:bg-gray-900/50 px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                        <div>
                            <h3 class="text-lg leading-6 font-bold text-gray-900 dark:text-white" id="modal-title">
                                {{ $viewingTeam->team_name }}
                            </h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ $viewingTeam->affiliation }}</p>
                        </div>
                        <button wire:click="closeModal" class="text-gray-400 hover:text-gray-500 focus:outline-none">
                            <span class="sr-only">Close</span>
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                        </button>
                    </div>

                    {{-- Modal Body (Scrollable) --}}
                    <div class="px-6 py-6 max-h-[70vh] overflow-y-auto space-y-8">
                        
                        {{-- Section: Profile & Logistics --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <h4 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Cohort Manifesto</h4>
                                <p class="text-sm text-gray-900 dark:text-gray-300 bg-gray-50 dark:bg-gray-900/50 p-3 rounded-lg border border-gray-100 dark:border-gray-700">{{ $viewingTeam->team_about }}</p>
                                
                                <h4 class="text-xs font-bold text-gray-500 uppercase tracking-wider mt-4 mb-2">Location Codes (PSGC)</h4>
                                <p class="text-sm text-gray-900 dark:text-gray-300 font-mono">Prov: {{ $viewingTeam->provCode ?? $viewingTeam->province_id }} | City: {{ $viewingTeam->citymunCode ?? $viewingTeam->citymun_id }} | Brgy: {{ $viewingTeam->brgyCode ?? $viewingTeam->barangay_id }}</p>
                            </div>
                            <div class="space-y-3 bg-gray-50 dark:bg-gray-900/50 p-4 rounded-lg border border-gray-100 dark:border-gray-700">
                                <h4 class="text-xs font-bold text-gray-500 uppercase tracking-wider border-b border-gray-200 dark:border-gray-700 pb-2 mb-2">Logistics & Commitments</h4>
                                <div class="text-sm"><span class="font-semibold text-gray-700 dark:text-gray-300">From Bicol:</span> <span class="text-iba-teal">{{ $viewingTeam->team_member_demographics }}</span></div>
                                <div class="text-sm"><span class="font-semibold text-gray-700 dark:text-gray-300">Onsite Commit:</span> <span class="text-iba-teal">{{ $viewingTeam->onsite_commitment }}</span></div>
                                <div class="text-sm"><span class="font-semibold text-gray-700 dark:text-gray-300">Clause Ack:</span> <span class="text-iba-teal">{{ $viewingTeam->does_not_automatically_apply_clause }}</span></div>
                                <div class="text-sm"><span class="font-semibold text-gray-700 dark:text-gray-300">ICP Selection:</span> <span class="text-iba-teal">{{ $viewingTeam->selection_on_icp }}</span></div>
                                <div class="mt-2 pt-2 border-t border-gray-200 dark:border-gray-700 text-xs">
                                    <span class="{{ $viewingTeam->data_privacy_consent ? 'text-green-600' : 'text-red-600' }}">✓ Data Privacy</span> | 
                                    <span class="{{ $viewingTeam->media_consent ? 'text-green-600' : 'text-red-600' }}">✓ Media Consent</span>
                                </div>
                            </div>
                        </div>

                        {{-- Section: Pivot Data (Tags) --}}
                        <div class="space-y-4">
                            <div>
                                <h4 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Team Skills</h4>
                                <div class="flex flex-wrap gap-2">
                                    @forelse($viewingTeam->skills as $skill)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400 border border-blue-200 dark:border-blue-800">{{ $skill->name }}</span>
                                    @empty
                                        <span class="text-xs text-gray-400">None declared.</span>
                                    @endforelse
                                </div>
                            </div>
                            <div>
                                <h4 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Community Areas</h4>
                                <div class="flex flex-wrap gap-2">
                                    @forelse($viewingTeam->communityAreas as $area)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-medium bg-orange-100 text-orange-800 dark:bg-orange-900/30 dark:text-orange-400 border border-orange-200 dark:border-orange-800">{{ $area->name }}</span>
                                    @empty
                                        <span class="text-xs text-gray-400">None declared.</span>
                                    @endforelse
                                </div>
                            </div>
                            <div>
                                <h4 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Committed Online Activities</h4>
                                <div class="flex flex-wrap gap-2">
                                    @forelse($viewingTeam->onlineActivities as $activity)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400 border border-green-200 dark:border-green-800">{{ $activity->name }}</span>
                                    @empty
                                        <span class="text-xs text-gray-400">None declared.</span>
                                    @endforelse
                                </div>
                            </div>
                        </div>

                        {{-- Section: Roster --}}
                        <div>
                            <h4 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-3">Team Roster ({{ $viewingTeam->number_of_team_members }})</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @foreach($viewingTeam->members as $member)
                                    <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4 bg-white dark:bg-gray-800 shadow-sm">
                                        <div class="flex justify-between items-start mb-2">
                                            <h5 class="font-bold text-sm text-gray-900 dark:text-white">{{ $member->full_name }}</h5>
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-medium {{ $member->team_role == 'Team Leader' ? 'bg-iba-red text-white' : 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300' }}">{{ $member->team_role }}</span>
                                        </div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400 space-y-1">
                                            <p>📧 {{ $member->email_address }}</p>
                                            <p>📱 {{ $member->mobile_number ?? 'N/A' }}</p>
                                            <p>🎓 {{ $member->course ?? 'N/A' }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                    </div>

                    {{-- Modal Footer / Actions --}}
                    <div class="bg-gray-50 dark:bg-gray-900/50 px-6 py-4 border-t border-gray-200 dark:border-gray-700 sm:flex sm:flex-row-reverse gap-3">
                        @if($viewingTeam->status == 'pending')
                            <button wire:click="approveTeam({{ $viewingTeam->id }})" type="button" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-iba-teal text-base font-medium text-white hover:bg-teal-700 focus:outline-none sm:w-auto sm:text-sm transition-colors">
                                Verify & Approve Team
                            </button>
                            <button wire:click="rejectTeam({{ $viewingTeam->id }})" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-800 text-base font-medium text-red-600 dark:text-red-400 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none sm:mt-0 sm:w-auto sm:text-sm transition-colors">
                                Reject
                            </button>
                        @else
                            <button wire:click="closeModal" type="button" class="w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-800 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none sm:w-auto sm:text-sm transition-colors">
                                Close Window
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>