<div class="p-6">
    {{-- 1. HEADER & FILTERS --}}
    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center mb-8 gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Membership Applications</h2>
            <p class="text-sm text-gray-500">Manage incoming requests from potential advocates.</p>
        </div>
        
        <div class="flex flex-col sm:flex-row gap-3 w-full lg:w-auto bg-white p-1 rounded-xl border border-gray-200 shadow-sm">
            {{-- Wave Filter --}}
            <select wire:model.live="waveFilter" class="rounded-lg border-transparent text-sm font-bold text-gray-600 focus:ring-indigo-500 focus:border-indigo-500 bg-gray-50 py-2 pl-3 pr-8 w-full sm:w-48">
                <option value="">All Waves</option>
                @foreach($waves as $wave)
                    <option value="{{ $wave->id }}">
                        {{ $wave->name }} ({{ $wave->is_active ? 'Active' : 'Closed' }})
                    </option>
                @endforeach
            </select>

            {{-- Status Filter --}}
            <select wire:model.live="statusFilter" class="rounded-lg border-transparent text-sm font-medium text-gray-600 focus:ring-indigo-500 focus:border-indigo-500 bg-gray-50 py-2 pl-3 pr-8 w-full sm:w-40">
                <option value="pending">Pending</option>
                <option value="approved">Approved</option>
                <option value="rejected">Rejected</option>
                <option value="">All Status</option>
            </select>

            {{-- Search --}}
            <div class="relative w-full sm:w-64">
                <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search applicant..." class="w-full rounded-lg border-transparent text-sm focus:ring-indigo-500 focus:border-indigo-500 bg-gray-50 py-2 pl-9 pr-4">
                <svg class="w-4 h-4 text-gray-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>
        </div>
    </div>

    {{-- 2. FEEDBACK MESSAGES --}}
    @if (session()->has('message'))
        <div class="bg-green-50 text-green-700 p-4 rounded-xl mb-6 text-sm font-bold border border-green-200 flex items-center gap-2">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
            {{ session('message') }}
        </div>
    @endif

    {{-- 3. THE TABLE --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Applicant</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Wave / Date</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Academic</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Committees</th>
                    <th class="px-6 py-4 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-4 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Action</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($applications as $app)
                <tr class="hover:bg-gray-50 transition-colors group">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="h-10 w-10 flex-shrink-0">
                                @if($app->photo_path)
                                    <img class="h-10 w-10 rounded-full object-cover border border-gray-200 group-hover:scale-110 transition-transform" src="{{ asset('storage/'.$app->photo_path) }}">
                                @else
                                    <div class="h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-bold text-xs">
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
                    
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($app->membershipWave)
                            <span class="px-2.5 py-0.5 inline-flex text-[10px] font-bold rounded-full bg-blue-50 text-blue-700 border border-blue-100">
                                {{ $app->membershipWave->name }}
                            </span>
                        @else
                            <span class="text-xs text-gray-400">--</span>
                        @endif
                        <div class="text-[10px] text-gray-400 mt-1 font-mono">{{ $app->created_at->format('M d, Y') }}</div>
                    </td>

                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-xs text-gray-900 font-bold max-w-[150px] truncate" title="{{ $app->college ? $app->college->name : '' }}">
                            {{ $app->college ? $app->college->name : 'N/A' }}
                        </div>
                        <div class="text-xs text-gray-500">{{ $app->course }} ({{ $app->year_level }})</div>
                    </td>
                    
                    <td class="px-6 py-4 whitespace-nowrap text-xs text-gray-500">
                        <div class="font-medium text-gray-700">1. {{ $app->firstCommittee ? Str::limit($app->firstCommittee->name, 20) : 'N/A' }}</div>
                        <div class="text-gray-400">2. {{ $app->secondCommittee ? Str::limit($app->secondCommittee->name, 20) : 'N/A' }}</div>
                    </td>

                    <td class="px-6 py-4 whitespace-nowrap text-center">
                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full 
                            {{ $app->status === 'approved' ? 'bg-green-100 text-green-700 border border-green-200' : 
                              ($app->status === 'rejected' ? 'bg-red-100 text-red-700 border border-red-200' : 'bg-yellow-100 text-yellow-700 border border-yellow-200') }}">
                            {{ ucfirst($app->status) }}
                        </span>
                    </td>

                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <button wire:click="viewDetails({{ $app->id }})" class="text-indigo-600 hover:text-indigo-900 font-bold text-xs uppercase tracking-wide bg-indigo-50 hover:bg-indigo-100 px-3 py-1.5 rounded-lg transition-colors">
                            Review
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center">
                        <div class="flex flex-col items-center justify-center text-gray-400">
                            <svg class="w-12 h-12 mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            <span class="text-sm font-medium">No applications found matching your filters.</span>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        
        <div class="p-4 border-t border-gray-200 bg-gray-50">
            {{ $applications->links() }}
        </div>
    </div>

    {{-- 4. DETAILS MODAL --}}
    @if($showDetailsModal && $selectedApplication)
    <div class="fixed inset-0 z-[100] overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            
            <div class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity backdrop-blur-sm" aria-hidden="true" wire:click="$set('showDetailsModal', false)"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div class="inline-block align-middle bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:max-w-4xl sm:w-full border border-gray-100">
                
                {{-- Header --}}
                <div class="bg-white px-6 py-5 border-b border-gray-100 flex justify-between items-center">
                    <div>
                        <h3 class="text-xl font-bold text-gray-900">Application Review</h3>
                        <p class="text-xs text-gray-500 uppercase tracking-widest mt-1">ID: #{{ $selectedApplication->id }}</p>
                    </div>
                    <button wire:click="$set('showDetailsModal', false)" class="text-gray-400 hover:text-gray-600 bg-gray-50 hover:bg-gray-100 p-2 rounded-full transition-colors">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
                
                <div class="bg-white px-6 py-6">
                    <div class="grid grid-cols-1 md:grid-cols-12 gap-8">
                        
                        {{-- LEFT: Profile & Meta --}}
                        <div class="md:col-span-4 space-y-6">
                            <div class="text-center">
                                <img src="{{ asset('storage/'.$selectedApplication->photo_path) }}" class="w-32 h-32 rounded-full object-cover border-4 border-white shadow-lg mx-auto mb-4">
                                <h2 class="text-lg font-bold text-gray-900">{{ $selectedApplication->first_name }} {{ $selectedApplication->last_name }}</h2>
                                <p class="text-sm text-indigo-600 font-medium">{{ $selectedApplication->course }}</p>
                                <p class="text-xs text-gray-500 mt-1">{{ $selectedApplication->college ? $selectedApplication->college->name : 'N/A' }}</p>
                            </div>

                            <div class="bg-gray-50 rounded-xl p-4 space-y-3 text-sm border border-gray-100">
                                <div>
                                    <span class="block text-xs font-bold text-gray-400 uppercase">Contact</span>
                                    <span class="text-gray-700 font-medium">{{ $selectedApplication->contact_number }}</span>
                                </div>
                                <div>
                                    <span class="block text-xs font-bold text-gray-400 uppercase">Email</span>
                                    <span class="text-gray-700 font-medium break-all">{{ $selectedApplication->email }}</span>
                                </div>
                                <div>
                                    <span class="block text-xs font-bold text-gray-400 uppercase">Address</span>
                                    <span class="text-gray-700">{{ $selectedApplication->home_address }}</span>
                                </div>
                            </div>

                            <div>
                                <span class="block text-xs font-bold text-gray-400 uppercase mb-2">Secure Signature</span>
                                <div class="bg-white border border-gray-200 rounded-lg p-3 flex justify-center">
                                    <img src="{{ route('secure.signature', $selectedApplication->id) }}" class="h-12 w-auto opacity-70">
                                </div>
                            </div>
                        </div>

                        {{-- RIGHT: Essays --}}
                        <div class="md:col-span-8">
                            <div class="h-[500px] overflow-y-auto pr-4 custom-scrollbar space-y-6">
                                <div class="bg-indigo-50 rounded-lg p-4 border border-indigo-100">
                                    <h4 class="text-xs font-bold text-indigo-800 uppercase mb-2">Political Affiliations</h4>
                                    <p class="text-sm text-indigo-900">{{ $selectedApplication->political_affiliation ?: 'None declared' }}</p>
                                </div>

                                <div class="space-y-6">
                                    @foreach([
                                        'Community Observation' => $selectedApplication->essay_1_community,
                                        'Action on Issues' => $selectedApplication->essay_2_action,
                                        'Experience' => $selectedApplication->essay_3_experience,
                                        'Why BU MADYA?' => $selectedApplication->essay_4_reason,
                                        'Suggestions' => $selectedApplication->essay_5_suggestion
                                    ] as $label => $essay)
                                        <div class="group">
                                            <h5 class="text-xs font-bold text-gray-400 uppercase mb-1 group-hover:text-gray-600 transition-colors">{{ $loop->iteration }}. {{ $label }}</h5>
                                            <div class="text-sm text-gray-700 leading-relaxed bg-white border-l-2 border-gray-200 pl-4 py-1">
                                                {{ $essay ?: 'N/A' }}
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-indigo-50 px-6 py-4 border-t border-indigo-100">
                        <h4 class="text-xs font-bold text-indigo-800 uppercase mb-3">Final Committee Assignment</h4>
                        
                        <div class="space-y-3">
                            {{-- Option A: 1st Choice --}}
                            <label class="flex items-center p-3 bg-white border {{ $assign_committee_id == $selectedApplication->committee_1_id ? 'border-indigo-500 ring-1 ring-indigo-500' : 'border-gray-200' }} rounded-lg cursor-pointer hover:bg-gray-50">
                                <input type="radio" wire:model="assign_committee_id" value="{{ $selectedApplication->committee_1_id }}" class="text-indigo-600 focus:ring-indigo-500">
                                <div class="ml-3">
                                    <span class="block text-sm font-bold text-gray-900">1st Choice: {{ $selectedApplication->firstCommittee->name }}</span>
                                </div>
                            </label>

                            {{-- Option B: 2nd Choice --}}
                            <label class="flex items-center p-3 bg-white border {{ $assign_committee_id == $selectedApplication->committee_2_id ? 'border-indigo-500 ring-1 ring-indigo-500' : 'border-gray-200' }} rounded-lg cursor-pointer hover:bg-gray-50">
                                <input type="radio" wire:model="assign_committee_id" value="{{ $selectedApplication->committee_2_id }}" class="text-indigo-600 focus:ring-indigo-500">
                                <div class="ml-3">
                                    <span class="block text-sm font-bold text-gray-900">2nd Choice: {{ $selectedApplication->secondCommittee->name }}</span>
                                </div>
                            </label>

                            {{-- Option C: Select Other --}}
                            <div class="flex items-center gap-2">
                                <div class="flex items-center h-5">
                                    {{-- If ID is not 1st or 2nd, assume it's "Other" --}}
                                    <input type="radio" 
                                        name="committee_selection" 
                                        {{ !in_array($assign_committee_id, [$selectedApplication->committee_1_id, $selectedApplication->committee_2_id]) ? 'checked' : '' }}
                                        disabled
                                        class="text-indigo-600 focus:ring-indigo-500">
                                </div>
                                <div class="w-full">
                                    <select wire:model="assign_committee_id" class="block w-full text-sm border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                                        <option value="">-- Assign to Different Committee --</option>
                                        @foreach($all_committees as $c)
                                            <option value="{{ $c->id }}">
                                                {{ $c->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Footer --}}
                <div class="bg-gray-50 px-6 py-4 flex justify-between items-center border-t border-gray-200">
                    <div class="text-xs text-gray-400 font-medium">
                        Applied: {{ $selectedApplication->created_at->format('F d, Y h:i A') }}
                    </div>

                    <div class="flex gap-3">
                        @if($selectedApplication->status === 'pending')
                            <button wire:click="reject({{ $selectedApplication->id }})" wire:loading.attr="disabled" class="px-4 py-2 bg-white text-red-600 text-sm font-bold rounded-lg border border-gray-300 hover:bg-red-50 hover:border-red-200 transition-all shadow-sm">
                                Reject
                            </button>
                            <button wire:click="approve" wire:loading.attr="disabled" class="px-6 py-2 bg-green-600 text-white text-sm font-bold rounded-lg hover:bg-green-700 shadow-md hover:shadow-lg transition-all flex items-center gap-2">
                                <span wire:loading.remove wire:target="approve">Approve & Email</span>
                                <span wire:loading wire:target="approve" class="animate-pulse">Sending...</span>
                            </button>
                        @else
                            <span class="px-4 py-2 rounded-lg bg-gray-200 text-gray-600 text-sm font-bold uppercase tracking-wider">
                                {{ $selectedApplication->status }}
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

@script
<script>
    // 1. Listen for the 'swal:toast' event from Livewire
    Livewire.on('swal:toast', (data) => {
        
        // 2. Fire a SweetAlert2 Toast
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end', // Top Right Corner
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        });

        Toast.fire({
            icon: data[0].icon,  // 'success', 'error', 'warning'
            title: data[0].title
        });
    });
</script>
@endscript