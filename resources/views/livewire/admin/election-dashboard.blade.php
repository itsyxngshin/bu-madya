<div class="max-w-7xl mx-auto py-8 px-4 font-sans pb-32">
    
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div>
            <h1 class="text-3xl font-black text-gray-900 tracking-tight flex items-center gap-3">
                <a href="{{ route('admin.elections.index') }}" class="text-gray-400 hover:text-orange-600 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                </a>
                Candidate Vetting
            </h1>
            <p class="text-gray-500 font-medium ml-9">Reviewing applications for: <span class="font-bold">{{ $election->title }}</span></p>
        </div>
    </div>

    @if (session()->has('success'))
        <div class="mb-6 bg-green-50 text-green-700 p-4 rounded-xl border border-green-200 font-bold flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            {{ session('success') }}
        </div>
    @endif
    @if (session()->has('error'))
        <div class="mb-6 bg-red-50 text-red-700 p-4 rounded-xl border border-red-200 font-bold flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
            {{ session('error') }}
        </div>
    @endif

    <div class="bg-white rounded-3xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 text-xs font-bold text-gray-500 uppercase tracking-wider border-b border-gray-200">
                        <th class="p-5">Applicant</th>
                        <th class="p-5">Position</th>
                        <th class="p-5">College & Program</th>
                        <th class="p-5">Status</th>
                        <th class="p-5 text-right">Electoral Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($candidates as $candidate)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="p-5">
                                <div class="flex items-center gap-3">
                                    @if($candidate->profile_photo_path)
                                        <img src="{{ asset('storage/'.$candidate->profile_photo_path) }}" class="w-10 h-10 rounded-full object-cover shadow-sm border border-gray-200">
                                    @else
                                        <div class="w-10 h-10 rounded-full bg-orange-100 text-orange-600 flex items-center justify-center font-bold">{{ substr($candidate->user->name ?? '?', 0, 1) }}</div>
                                    @endif
                                    <div>
                                        <p class="font-black text-gray-900">{{ $candidate->user->name ?? 'Guest/Unknown' }}</p>
                                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Year: {{ $candidate->year_level }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="p-5 font-bold text-gray-800">{{ $candidate->position->title }}</td>
                            <td class="p-5 text-sm text-gray-600">
                                <span class="font-bold">{{ $candidate->college->name ?? 'N/A' }}</span><br>
                                <span class="text-xs">{{ $candidate->program }}</span>
                            </td>
                            <td class="p-5">
                                @if($candidate->status === 'pending')
                                    <span class="px-3 py-1 bg-yellow-100 text-yellow-800 text-[10px] uppercase font-black tracking-widest rounded-full">Pending</span>
                                @elseif($candidate->status === 'approved')
                                    <span class="px-3 py-1 bg-green-100 text-green-800 text-[10px] uppercase font-black tracking-widest rounded-full">Approved</span>
                                @else
                                    <span class="px-3 py-1 bg-red-100 text-red-800 text-[10px] uppercase font-black tracking-widest rounded-full cursor-help" title="{{ $candidate->remarks }}">Rejected</span>
                                @endif
                            </td>
                            <td class="p-5 text-right">
                                @if($candidate->status === 'pending')
                                    <div class="flex items-center justify-end gap-2">
                                        <button wire:click="approveCandidate({{ $candidate->id }})" class="px-4 py-2 bg-green-50 hover:bg-green-100 text-green-700 font-bold rounded-lg transition-colors text-xs">Approve</button>
                                        <button wire:click="confirmRejection({{ $candidate->id }})" class="px-4 py-2 bg-red-50 hover:bg-red-100 text-red-700 font-bold rounded-lg transition-colors text-xs">Reject</button>
                                    </div>
                                @else
                                    <button disabled class="text-xs font-bold text-gray-400 cursor-not-allowed">Processed</button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-12 text-center text-gray-400 font-bold">No candidates found for this election.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- REJECTION MODAL --}}
    @if($candidateToReject)
        <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm flex items-center justify-center z-50 p-4">
            <div class="bg-white rounded-3xl p-8 max-w-lg w-full shadow-2xl">
                <h3 class="text-xl font-black text-red-600 mb-2">Reject Application</h3>
                <p class="text-sm text-gray-600 mb-6">Please provide an official reason for rejection. This remark will be visible to the candidate.</p>
                
                <textarea wire:model="rejectRemarks" rows="4" class="w-full bg-gray-50 border border-gray-200 focus:border-red-500 rounded-xl p-4 text-sm resize-none mb-2" placeholder="e.g., Did not meet residency requirements..."></textarea>
                @error('rejectRemarks') <span class="text-[10px] text-red-500 font-bold block mb-4">{{ $message }}</span> @enderror

                <div class="flex justify-end gap-3 mt-4">
                    <button wire:click="$set('candidateToReject', null)" class="px-5 py-2.5 text-sm font-bold text-gray-600 hover:bg-gray-100 rounded-xl transition-colors">Cancel</button>
                    <button wire:click="rejectCandidate" class="px-5 py-2.5 text-sm font-black text-white bg-red-600 hover:bg-red-700 rounded-xl shadow-lg transition-colors">Confirm Rejection</button>
                </div>
            </div>
        </div>
    @endif
</div>