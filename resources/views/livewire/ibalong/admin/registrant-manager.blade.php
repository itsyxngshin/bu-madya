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
        <div class="fixed inset-0 z-[100] overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            
            {{-- Background overlay with blur --}}
            <div class="fixed inset-0 bg-gray-800/75 dark:bg-gray-900/80 backdrop-blur-sm transition-opacity" wire:click="closeModal"></div>

            {{-- Modern Flexbox Centering Wrapper --}}
            <div class="flex min-h-screen items-center justify-center p-4 text-center sm:p-6">
                
                {{-- Modal Panel (Flex Col for sticky header/footer) --}}
                <div class="relative w-full sm:max-w-5xl max-h-[90vh] flex flex-col bg-white dark:bg-gray-800 rounded-xl text-left shadow-2xl transform transition-all border border-gray-200 dark:border-gray-700 overflow-hidden">
                    
                    {{-- Modal Header (Fixed at top) --}}
                    <div class="bg-gray-50 dark:bg-gray-900/90 px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center shrink-0">
                        <div>
                            <h3 class="text-xl leading-6 font-bold text-gray-900 dark:text-white" id="modal-title">
                                {{ $viewingTeam->team_name }}
                            </h3>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mt-1">{{ $viewingTeam->affiliation }}</p>
                        </div>
                        <button wire:click="closeModal" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 focus:outline-none bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 rounded-full p-2 transition-colors">
                            <span class="sr-only">Close</span>
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                        </button>
                    </div>

                    {{-- Modal Body (Scrollable Middle) --}}
                    <div class="px-6 py-6 overflow-y-auto space-y-10 flex-1">
                        
                        {{-- 1. Profile & Location Map --}}
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            
                            {{-- Manifesto & Basic Data --}}
                            <div class="flex flex-col h-full">
                                <h4 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Cohort Manifesto</h4>
                                <div class="text-sm text-gray-900 dark:text-gray-300 bg-gray-50 dark:bg-gray-900/50 p-4 rounded-lg border border-gray-100 dark:border-gray-700 leading-relaxed flex-1">
                                    "{{ $viewingTeam->team_about }}"
                                </div>
                            </div>
                            
                            {{-- Geographic Map Integration --}}
                            <div class="flex flex-col h-full">
                                <h4 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Team Headquarters</h4>
                                <div class="bg-gray-50 dark:bg-gray-900/50 p-4 rounded-lg border border-gray-100 dark:border-gray-700 flex-1 flex flex-col">
                                    <p class="text-sm text-gray-900 dark:text-gray-300 font-semibold mb-3 flex items-center gap-2">
                                        <svg class="w-5 h-5 text-iba-red" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                        {{ $fullAddress ?: 'Address resolving...' }}
                                    </p>
                                    
                                    @if($mapQuery)
                                        <div class="w-full flex-1 min-h-[150px] bg-gray-200 dark:bg-gray-700 rounded-md overflow-hidden border border-gray-300 dark:border-gray-600 shadow-inner">
                                            <iframe 
                                                width="100%" 
                                                height="100%" 
                                                frameborder="0" 
                                                scrolling="no" 
                                                marginheight="0" 
                                                marginwidth="0" 
                                                src="https://maps.google.com/maps?q={{ $mapQuery }}&t=&z=13&ie=UTF8&iwloc=&output=embed">
                                            </iframe>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <hr class="border-gray-200 dark:border-gray-700 border-dashed">

                        {{-- 2. Declarations & Q&A --}}
                        <div>
                            <h4 class="text-sm font-bold text-gray-900 dark:text-white uppercase tracking-wider mb-4 border-l-4 border-iba-orange pl-3">Specific Declarations & Commitments</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                                <div class="bg-white dark:bg-gray-800 p-4 border border-gray-200 dark:border-gray-700 rounded-lg shadow-sm">
                                    <p class="text-[10px] text-gray-500 dark:text-gray-400 font-bold uppercase tracking-wider mb-1">Q: Are all members from Bicol?</p>
                                    <p class="text-sm font-semibold text-iba-teal">{{ $viewingTeam->team_member_demographics }}</p>
                                </div>
                                <div class="bg-white dark:bg-gray-800 p-4 border border-gray-200 dark:border-gray-700 rounded-lg shadow-sm">
                                    <p class="text-[10px] text-gray-500 dark:text-gray-400 font-bold uppercase tracking-wider mb-1">Q: Commit to Onsite Pitching?</p>
                                    <p class="text-sm font-semibold text-iba-teal">{{ $viewingTeam->onsite_commitment }}</p>
                                </div>
                                <div class="bg-white dark:bg-gray-800 p-4 border border-gray-200 dark:border-gray-700 rounded-lg shadow-sm">
                                    <p class="text-[10px] text-gray-500 dark:text-gray-400 font-bold uppercase tracking-wider mb-1">Q: Ack. Non-Automatic Selection?</p>
                                    <p class="text-sm font-semibold text-iba-teal">{{ $viewingTeam->does_not_automatically_apply_clause }}</p>
                                </div>
                                <div class="bg-white dark:bg-gray-800 p-4 border border-gray-200 dark:border-gray-700 rounded-lg shadow-sm">
                                    <p class="text-[10px] text-gray-500 dark:text-gray-400 font-bold uppercase tracking-wider mb-1">Q: Selection via Concept Proposal?</p>
                                    <p class="text-sm font-semibold text-iba-teal">{{ $viewingTeam->selection_on_icp }}</p>
                                </div>
                            </div>
                            <div class="mt-4 flex gap-4 text-xs font-bold bg-gray-50 dark:bg-gray-900/50 p-3 rounded-lg border border-gray-200 dark:border-gray-700 w-fit">
                                <span class="{{ $viewingTeam->data_privacy_consent ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">✓ Data Privacy Consented</span>
                                <span class="text-gray-300 dark:text-gray-600">|</span>
                                <span class="{{ $viewingTeam->media_consent ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">✓ Media Recording Consented</span>
                            </div>
                        </div>

                        <hr class="border-gray-200 dark:border-gray-700 border-dashed">

                        {{-- 3. Pivot Data (Tags) --}}
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <h4 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Team Skills</h4>
                                <div class="flex flex-wrap gap-2">
                                    @forelse($viewingTeam->skills as $skill)
                                        <span class="inline-flex items-center px-2.5 py-1 rounded border border-blue-200 dark:border-blue-800 bg-blue-50 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 text-xs font-medium">{{ $skill->name }}</span>
                                    @empty
                                        <span class="text-xs text-gray-400">None declared.</span>
                                    @endforelse
                                </div>
                            </div>
                            <div>
                                <h4 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Community Areas</h4>
                                <div class="flex flex-wrap gap-2">
                                    @forelse($viewingTeam->communityAreas as $area)
                                        <span class="inline-flex items-center px-2.5 py-1 rounded border border-orange-200 dark:border-orange-800 bg-orange-50 dark:bg-orange-900/30 text-orange-700 dark:text-orange-300 text-xs font-medium">{{ $area->name }}</span>
                                    @empty
                                        <span class="text-xs text-gray-400">None declared.</span>
                                    @endforelse
                                </div>
                            </div>
                            <div>
                                <h4 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Committed Online Activities</h4>
                                <div class="flex flex-wrap gap-2">
                                    @forelse($viewingTeam->onlineActivities as $activity)
                                        <span class="inline-flex items-center px-2.5 py-1 rounded border border-green-200 dark:border-green-800 bg-green-50 dark:bg-green-900/30 text-green-700 dark:text-green-300 text-xs font-medium">{{ $activity->name }}</span>
                                    @empty
                                        <span class="text-xs text-gray-400">None declared.</span>
                                    @endforelse
                                </div>
                            </div>
                        </div>

                        <hr class="border-gray-200 dark:border-gray-700 border-dashed">

                        {{-- 4. Roster & Individual Skills --}}
                        <div>
                            <h4 class="text-sm font-bold text-gray-900 dark:text-white uppercase tracking-wider mb-4 border-l-4 border-iba-teal pl-3">Team Roster ({{ $viewingTeam->number_of_team_members }})</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
                                @foreach($viewingTeam->members as $member)
                                    <div class="border border-gray-200 dark:border-gray-700 rounded-xl p-5 bg-white dark:bg-gray-800 shadow-sm flex flex-col hover:border-iba-teal transition-colors">
                                        <div class="flex justify-between items-start mb-3">
                                            <h5 class="font-bold text-sm text-gray-900 dark:text-white">{{ $member->full_name }}</h5>
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider {{ $member->team_role == 'Team Leader' ? 'bg-iba-red text-white' : 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300' }}">{{ $member->team_role }}</span>
                                        </div>
                                        
                                        <div class="text-xs text-gray-500 dark:text-gray-400 space-y-1.5 mb-4 flex-1">
                                            <p class="flex items-center gap-2"><svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg> {{ $member->email_address }}</p>
                                            <p class="flex items-center gap-2"><svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg> {{ $member->mobile_number ?? 'N/A' }}</p>
                                            <p class="flex items-center gap-2"><svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14v6.5"/></svg> {{ $member->course ?? 'N/A' }}</p>
                                        </div>

                                        {{-- Individual Skills --}}
                                        @if($member->skills && $member->skills->count() > 0)
                                            <div class="pt-3 border-t border-gray-100 dark:border-gray-700">
                                                <p class="text-[9px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest mb-1.5">Primary Skillset</p>
                                                <div class="flex flex-wrap gap-1.5">
                                                    @foreach($member->skills as $mskill)
                                                        <span class="inline-block px-2 py-0.5 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded text-[10px] font-semibold">{{ $mskill->name }}</span>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>

                    </div>

                    {{-- Modal Footer / Actions (Fixed at bottom) --}}
                    <div class="bg-gray-50 dark:bg-gray-900/90 px-6 py-4 border-t border-gray-200 dark:border-gray-700 sm:flex sm:flex-row-reverse gap-3 shrink-0">
                        @if($viewingTeam->status == 'pending')
                            <button wire:click="approveTeam({{ $viewingTeam->id }})" type="button" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-6 py-2.5 bg-iba-teal text-sm font-bold text-white hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-iba-teal sm:w-auto transition-colors">
                                Verify & Approve Team
                            </button>
                            <button wire:click="rejectTeam({{ $viewingTeam->id }})" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-6 py-2.5 bg-white dark:bg-gray-800 text-sm font-bold text-red-600 dark:text-red-400 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:mt-0 sm:w-auto transition-colors">
                                Reject Application
                            </button>
                        @else
                            <button wire:click="closeModal" type="button" class="w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-6 py-2.5 bg-white dark:bg-gray-800 text-sm font-bold text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-iba-teal sm:w-auto transition-colors">
                                Close Window
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>