<div class="p-6">
    {{-- 1. HEADER & FILTERS --}}
    <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Membership Applications</h2>
            <p class="text-sm text-gray-500">Review and manage incoming member requests.</p>
        </div>
        
        <div class="flex flex-col md:flex-row gap-2 w-full md:w-auto">
            {{-- Wave Filter --}}
            <select wire:model.live="waveFilter" class="rounded-lg border-gray-300 text-sm font-bold text-gray-700 focus:ring-red-500 focus:border-red-500">
                <option value="">All Recruitment Waves</option>
                @foreach($waves as $wave)
                    <option value="{{ $wave->id }}">
                        {{ $wave->name }} ({{ $wave->is_active ? 'Active' : 'Closed' }})
                    </option>
                @endforeach
            </select>

            {{-- Status Filter --}}
            <select wire:model.live="statusFilter" class="rounded-lg border-gray-300 text-sm focus:ring-red-500 focus:border-red-500">
                <option value="pending">Pending Review</option>
                <option value="approved">Approved</option>
                <option value="rejected">Rejected</option>
                <option value="">All Statuses</option>
            </select>

            {{-- Search --}}
            <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search Name/Email..." class="rounded-lg border-gray-300 text-sm focus:ring-red-500 focus:border-red-500">
        </div>
    </div>

    {{-- 2. FLASH MESSAGES --}}
    @if (session()->has('message'))
        <div class="bg-green-100 text-green-700 p-3 rounded-lg mb-4 text-sm font-bold border-l-4 border-green-500 shadow-sm">
            {{ session('message') }}
        </div>
    @endif
    @if (session()->has('error'))
        <div class="bg-yellow-100 text-yellow-800 p-3 rounded-lg mb-4 text-sm font-bold border-l-4 border-yellow-500 shadow-sm">
            {{ session('error') }}
        </div>
    @endif

    {{-- 3. THE TABLE --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Applicant</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Batch/Wave</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">College/Course</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Committees</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Action</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($applications as $app)
                <tr class="hover:bg-gray-50 transition-colors">
                    {{-- Applicant Info --}}
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="h-10 w-10 flex-shrink-0">
                                @if($app->photo_path)
                                    <img class="h-10 w-10 rounded-full object-cover border border-gray-200" src="{{ asset('storage/'.$app->photo_path) }}" alt="">
                                @else
                                    <div class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center text-gray-500 font-bold text-xs">
                                        {{ substr($app->first_name, 0, 1) }}
                                    </div>
                                @endif
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-bold text-gray-900">{{ $app->last_name }}, {{ $app->first_name }}</div>
                                <div class="text-xs text-gray-500">{{ $app->email }}</div>
                            </div>
                        </div>
                    </td>
                    
                    {{-- Wave Badge --}}
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($app->membershipWave)
                            <span class="px-2 inline-flex text-[10px] leading-5 font-semibold rounded-full bg-blue-50 text-blue-700 border border-blue-100">
                                {{ $app->membershipWave->name }}
                            </span>
                            <div class="text-[10px] text-gray-400 mt-1 font-mono">{{ $app->created_at->format('M d, Y') }}</div>
                        @else
                            <span class="text-xs text-gray-400 italic">No Wave</span>
                        @endif
                    </td>

                    {{-- Academic Info --}}
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-xs text-gray-900 font-bold">{{ \Illuminate\Support\Str::limit($app->college, 20) }}</div>
                        <div class="text-xs text-gray-500">{{ $app->course }} ({{ $app->year_level }})</div>
                    </td>
                    
                    {{-- Committees --}}
                    <td class="px-6 py-4 whitespace-nowrap text-xs text-gray-500">
                        <div class="font-medium">1. {{ \Illuminate\Support\Str::limit($app->committee_1, 15) }}</div>
                        <div class="text-gray-400">2. {{ \Illuminate\Support\Str::limit($app->committee_2, 15) }}</div>
                    </td>

                    {{-- Status Badge --}}
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                            {{ $app->status === 'approved' ? 'bg-green-100 text-green-800' : 
                              ($app->status === 'rejected' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                            {{ ucfirst($app->status) }}
                        </span>
                    </td>

                    {{-- Action --}}
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <button wire:click="viewDetails({{ $app->id }})" class="text-white bg-indigo-600 hover:bg-indigo-700 px-3 py-1 rounded text-xs font-bold uppercase tracking-wider shadow-sm transition-all">
                            Review
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-10 text-center text-gray-500">
                        No applications found matching your criteria.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        
        {{-- Pagination --}}
        <div class="p-4 border-t border-gray-200 bg-gray-50">
            {{ $applications->links() }}
        </div>
    </div>

    {{-- 4. DETAILS MODAL --}}
    @if($showDetailsModal && $selectedApplication)
    <div class="fixed inset-0 z-[100] overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            
            {{-- Backdrop --}}
            <div class="fixed inset-0 bg-gray-600 bg-opacity-75 transition-opacity backdrop-blur-sm" 
                 aria-hidden="true" 
                 wire:click="$set('showDetailsModal', false)">
            </div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div class="inline-block align-bottom bg-white rounded-xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full">
                
                {{-- Modal Header --}}
                <div class="bg-gray-50 px-4 py-4 sm:px-6 border-b border-gray-200 flex justify-between items-center">
                    <h3 class="text-lg leading-6 font-bold text-gray-900" id="modal-title">
                        Application Review: <span class="text-red-600">{{ $selectedApplication->first_name }} {{ $selectedApplication->last_name }}</span>
                    </h3>
                    <button wire:click="$set('showDetailsModal', false)" class="text-gray-400 hover:text-gray-500">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
                
                {{-- Modal Body --}}
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        
                        {{-- LEFT COLUMN: Basic Info --}}
                        <div class="space-y-4 border-r border-gray-100 pr-0 md:pr-4">
                            <div class="flex items-center gap-4 mb-4">
                                <img src="{{ asset('storage/'.$selectedApplication->photo_path) }}" class="w-20 h-20 rounded-md object-cover border border-gray-200 shadow-sm">
                                <div>
                                    <p class="text-sm font-bold text-gray-900">{{ $selectedApplication->email }}</p>
                                    <p class="text-xs text-gray-500">{{ $selectedApplication->contact_number }}</p>
                                    <p class="text-xs text-gray-500">{{ $selectedApplication->home_address }}</p>
                                </div>
                            </div>

                            <div class="text-sm">
                                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Academic</p>
                                <p class="font-medium">{{ $selectedApplication->college }}</p>
                                <p class="text-gray-500">{{ $selectedApplication->course }} — {{ $selectedApplication->year_level }}</p>
                            </div>

                            <div class="text-sm">
                                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Affiliations</p>
                                <div class="bg-gray-50 p-2 rounded text-gray-600 text-xs">
                                    {{ $selectedApplication->political_affiliation ?: 'None declared' }}
                                </div>
                            </div>

                            <div class="text-sm">
                                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Links</p>
                                @if($selectedApplication->facebook_link)
                                    <a href="{{ $selectedApplication->facebook_link }}" target="_blank" class="text-blue-600 hover:underline flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                                        Facebook Profile
                                    </a>
                                @else
                                    <span class="text-gray-400">No link provided</span>
                                @endif
                            </div>

                            <div class="text-sm mt-4">
                                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Secure Signature</p>
                                <div class="border border-gray-200 rounded p-2 bg-gray-50 inline-block">
                                    {{-- Uses the secure route we created earlier --}}
                                    <img src="{{ route('secure.signature', $selectedApplication->id) }}" class="h-10 w-auto object-contain opacity-80">
                                </div>
                            </div>
                        </div>

                        {{-- RIGHT COLUMN: Essays --}}
                        <div class="space-y-6 h-[500px] overflow-y-auto pr-2 custom-scrollbar">
                            <h4 class="font-bold text-gray-900 border-b border-gray-200 pb-2">Applicant Insights</h4>
                            
                            <div>
                                <p class="text-xs font-bold text-indigo-600 uppercase mb-1">1. Community Observation</p>
                                <p class="text-sm text-gray-700 bg-gray-50 p-3 rounded-lg">{{ $selectedApplication->essay_1_community }}</p>
                            </div>

                            <div>
                                <p class="text-xs font-bold text-indigo-600 uppercase mb-1">2. Action on Issues</p>
                                <p class="text-sm text-gray-700 bg-gray-50 p-3 rounded-lg">{{ $selectedApplication->essay_2_action }}</p>
                            </div>

                            <div>
                                <p class="text-xs font-bold text-indigo-600 uppercase mb-1">3. Experience</p>
                                <p class="text-sm text-gray-700 bg-gray-50 p-3 rounded-lg">{{ $selectedApplication->essay_3_experience ?: 'N/A' }}</p>
                            </div>

                            <div>
                                <p class="text-xs font-bold text-indigo-600 uppercase mb-1">4. Why BU MADYA?</p>
                                <p class="text-sm text-gray-700 bg-gray-50 p-3 rounded-lg">{{ $selectedApplication->essay_4_reason }}</p>
                            </div>

                            <div>
                                <p class="text-xs font-bold text-indigo-600 uppercase mb-1">5. Suggestions</p>
                                <p class="text-sm text-gray-700 bg-gray-50 p-3 rounded-lg">{{ $selectedApplication->essay_5_suggestion ?: 'N/A' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Modal Footer --}}
                <div class="bg-gray-50 px-4 py-4 sm:px-6 sm:flex sm:flex-row-reverse gap-3 border-t border-gray-200">
                    @if($selectedApplication->status === 'pending')
                        <button wire:click="approve({{ $selectedApplication->id }})" wire:loading.attr="disabled" type="button" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 sm:ml-3 sm:w-auto sm:text-sm">
                            <span wire:loading.remove wire:target="approve">Approve & Email</span>
                            <span wire:loading wire:target="approve">Sending...</span>
                        </button>
                        <button wire:click="reject({{ $selectedApplication->id }})" wire:loading.attr="disabled" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Reject
                        </button>
                    @else
                        <div class="flex items-center px-4 py-2 bg-gray-100 rounded-md text-sm font-bold text-gray-500">
                            Status: {{ strtoupper($selectedApplication->status) }}
                        </div>
                    @endif
                    <button wire:click="$set('showDetailsModal', false)" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>