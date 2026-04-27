<div class="max-w-7xl mx-auto py-8 px-4 font-sans pb-32">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div>
            <h1 class="text-2xl md:text-3xl font-black text-gray-900 tracking-tight flex items-center gap-3">
                <a href="{{ route('admin.elections.index') }}" class="text-gray-400 hover:text-gray-600 transition-colors"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg></a>
                Candidate Vetting
            </h1>
            <p class="text-gray-500 font-medium ml-9 text-sm md:text-base">Reviewing applications for: <span class="font-bold">{{ $election->title }}</span></p>
        </div>
        <button wire:click="$set('showTestModal', true)" class="w-full md:w-auto justify-center px-5 py-2.5 bg-gray-900 hover:bg-orange-600 text-white font-bold rounded-xl shadow-md transition-colors flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg> Add Test Candidate
        </button>
    </div>

    @if (session()->has('success'))
        <div class="mb-6 bg-green-50 text-green-700 p-4 rounded-xl border border-green-200 font-bold flex items-center gap-2 text-sm"><svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> {{ session('success') }}</div>
    @endif

    <div class="bg-white rounded-3xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse whitespace-nowrap md:whitespace-normal">
                <thead>
                    <tr class="bg-gray-50 text-xs font-bold text-gray-500 uppercase tracking-wider border-b border-gray-200">
                        <th class="p-4 md:p-5">Applicant</th><th class="p-4 md:p-5">Position</th><th class="p-4 md:p-5">College & Program</th><th class="p-4 md:p-5">Status</th><th class="p-4 md:p-5 text-right">Electoral Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($candidates as $candidate)
                        @php
                            $candidateName = $candidate->display_name ?? optional($candidate->user)->name ?? 'Unknown';
                            $initial = strtoupper(substr($candidateName, 0, 1));
                        @endphp
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="p-4 md:p-5">
                                <div class="flex items-center gap-3">
                                    @if($candidate->profile_photo_path) <img src="{{ asset('storage/'.$candidate->profile_photo_path) }}" class="w-10 h-10 rounded-full object-cover shadow-sm border border-gray-200 shrink-0">
                                    @else <div class="w-10 h-10 rounded-full bg-gray-200 text-gray-500 flex items-center justify-center font-bold shrink-0">{{ $initial }}</div> @endif
                                    <div>
                                        <p class="font-black text-gray-900 text-sm md:text-base">{{ $candidateName }}</p>
                                        <a href="{{ route('candidate.profile', $candidate->id) }}" target="_blank" class="text-[10px] font-bold text-blue-500 hover:text-blue-700 uppercase tracking-wider flex items-center gap-1 mt-0.5">View Profile <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg></a>
                                    </div>
                                </div>
                            </td>
                            <td class="p-4 md:p-5 font-bold text-gray-800 text-sm">{{ $candidate->position->title ?? 'N/A' }}</td>
                            <td class="p-4 md:p-5 text-sm text-gray-600"><span class="font-bold">{{ $candidate->college->name ?? 'N/A' }}</span><br><span class="text-[10px] md:text-xs text-gray-500 uppercase">{{ $candidate->program }} ({{ $candidate->year_level }})</span></td>
                            <td class="p-4 md:p-5">
                                @if($candidate->status === 'pending') <span class="px-3 py-1 bg-yellow-100 text-yellow-800 text-[10px] uppercase font-black tracking-widest rounded-full">Pending</span>
                                @elseif($candidate->status === 'approved') <span class="px-3 py-1 bg-green-100 text-green-800 text-[10px] uppercase font-black tracking-widest rounded-full">Approved</span>
                                @else <span class="px-3 py-1 bg-red-100 text-red-800 text-[10px] uppercase font-black tracking-widest rounded-full">Rejected</span> @endif
                            </td>
                            <td class="p-4 md:p-5 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <button wire:click="openEditModal({{ $candidate->id }})" class="px-3 py-1.5 bg-yellow-50 hover:bg-yellow-100 text-yellow-700 font-bold rounded-lg transition-colors text-xs border border-yellow-200">Edit</button>
                                    @if($candidate->status === 'pending' || $candidate->status === 'rejected') <button wire:click="approveCandidate({{ $candidate->id }})" class="px-3 py-1.5 bg-green-50 hover:bg-green-100 text-green-700 font-bold rounded-lg transition-colors text-xs border border-green-200">Approve</button> @endif
                                    @if($candidate->status === 'pending' || $candidate->status === 'approved') <button wire:click="confirmRejection({{ $candidate->id }})" class="px-3 py-1.5 bg-red-50 hover:bg-red-100 text-red-700 font-bold rounded-lg transition-colors text-xs border border-red-200">Reject</button> @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="p-12 text-center text-gray-400 font-bold">No candidates found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- ADD TEST CANDIDATE MODAL --}}
    @if($showTestModal)
        <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm flex items-center justify-center z-50 p-4 overflow-y-auto">
            <div class="bg-white rounded-3xl p-8 max-w-2xl w-full shadow-2xl my-8">
                <h3 class="text-xl font-black text-gray-900 mb-2">Generate Test Candidate</h3>
                <p class="text-sm text-gray-500 mb-6">Instantly create an auto-approved dummy candidate for testing purposes.</p>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                    {{-- THE FIX: Avatar / Photo Upload Zone --}}
                    <div class="md:col-span-2 mb-2">
                        <label class="block text-[10px] font-bold text-gray-500 uppercase mb-2">Profile Photo (Optional)</label>
                        <div class="flex items-center gap-4">
                            <div class="w-16 h-16 rounded-full bg-gray-50 border-2 border-dashed border-gray-300 flex items-center justify-center overflow-hidden shrink-0">
                                @if ($testPhoto)
                                    <img src="{{ $testPhoto->temporaryUrl() }}" class="w-full h-full object-cover">
                                @else
                                    <svg class="w-6 h-6 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                @endif
                            </div>
                            <div class="flex-1">
                                <input type="file" wire:model="testPhoto" accept="image/*" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-bold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 transition-colors cursor-pointer">
                                <div wire:loading wire:target="testPhoto" class="text-[10px] text-blue-500 font-bold mt-1 animate-pulse">Uploading preview...</div>
                                @error('testPhoto') <span class="text-[10px] text-red-500 font-bold block mt-1">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1.5">Candidate / Display Name</label>
                        <input wire:model="testName" type="text" placeholder="e.g. John Doe (Test)" class="w-full bg-gray-50 border border-gray-200 focus:border-blue-500 rounded-xl px-4 py-3 text-sm font-bold">
                        @error('testName') <span class="text-[10px] text-red-500 font-bold block mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1.5">Position</label>
                        <select wire:model="testPositionId" class="w-full bg-gray-50 border border-gray-200 focus:border-blue-500 rounded-xl px-4 py-3 text-sm font-bold">
                            <option value="">Select Position...</option>
                            @foreach($positions as $pos) <option value="{{ $pos->id }}">{{ $pos->title }}</option> @endforeach
                        </select>
                        @error('testPositionId') <span class="text-[10px] text-red-500 font-bold block mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1.5">College</label>
                        <select wire:model="testCollegeId" class="w-full bg-gray-50 border border-gray-200 focus:border-blue-500 rounded-xl px-4 py-3 text-sm font-bold">
                            <option value="">Select College...</option>
                            @foreach($colleges as $college) <option value="{{ $college->id }}">{{ $college->name }}</option> @endforeach
                        </select>
                        @error('testCollegeId') <span class="text-[10px] text-red-500 font-bold block mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1.5">Program</label>
                        <input wire:model="testProgram" type="text" placeholder="e.g. BS IT" class="w-full bg-gray-50 border border-gray-200 focus:border-blue-500 rounded-xl px-4 py-3 text-sm font-bold">
                        @error('testProgram') <span class="text-[10px] text-red-500 font-bold block mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1.5">Year Level</label>
                        <select wire:model="testYearLevel" class="w-full bg-gray-50 border border-gray-200 focus:border-blue-500 rounded-xl px-4 py-3 text-sm font-bold">
                            <option value="">Select Year...</option>
                            <option value="1st Year">1st Year</option>
                            <option value="2nd Year">2nd Year</option>
                            <option value="3rd Year">3rd Year</option>
                            <option value="4th Year">4th Year</option>
                        </select>
                        @error('testYearLevel') <span class="text-[10px] text-red-500 font-bold block mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="flex justify-end gap-3 mt-8">
                    <button wire:click="$set('showTestModal', false)" class="px-5 py-2.5 text-sm font-bold text-gray-600 hover:bg-gray-100 rounded-xl transition-colors">Cancel</button>
                    <button wire:click="createTestCandidate" class="px-5 py-2.5 text-sm font-black text-white bg-gray-900 hover:bg-blue-600 rounded-xl shadow-lg transition-colors flex items-center gap-2">
                        <span wire:loading.remove wire:target="createTestCandidate, testPhoto">
                            <span class="flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                                Generate & Approve
                            </span>
                        </span>
                        <span wire:loading wire:target="createTestCandidate">Processing...</span>
                        <span wire:loading wire:target="testPhoto">Wait for upload...</span>
                    </button>
                </div>
            </div>
        </div>
    @endif

    {{-- REJECTION MODAL --}}
    @if($candidateToReject)
        <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm flex items-center justify-center z-50 p-4">
            <div class="bg-white rounded-3xl p-8 max-w-lg w-full shadow-2xl">
                <h3 class="text-xl font-black text-red-600 mb-2">Reject Application</h3>
                <p class="text-sm text-gray-600 mb-6">Please provide an official reason for rejection.</p>
                <textarea wire:model="rejectRemarks" rows="4" class="w-full bg-gray-50 border border-gray-200 focus:border-red-500 rounded-xl p-4 text-sm resize-none mb-1"></textarea>
                @error('rejectRemarks') <span class="text-[10px] text-red-500 font-bold block mb-4">{{ $message }}</span> @enderror
                <div class="flex justify-end gap-3 mt-4">
                    <button wire:click="$set('candidateToReject', null)" class="px-5 py-2.5 text-sm font-bold text-gray-600 hover:bg-gray-100 rounded-xl transition-colors">Cancel</button>
                    <button wire:click="rejectCandidate" class="px-5 py-2.5 text-sm font-black text-white bg-red-600 hover:bg-red-700 rounded-xl shadow-lg transition-colors">Confirm Rejection</button>
                </div>
            </div>
        </div>
    @endif

    {{-- EDIT MODAL --}}
    @if($candidateToEdit)
        <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm flex items-center justify-center z-50 p-4">
            <div class="bg-white rounded-3xl p-8 max-w-lg w-full shadow-2xl">
                <h3 class="text-xl font-black text-yellow-600 mb-2">Edit Candidate Details</h3>
                <div class="space-y-4">
                    <div>
                        <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1.5">Academic Program</label>
                        <input wire:model="editProgram" type="text" class="w-full bg-gray-50 border border-gray-200 focus:border-yellow-500 rounded-xl px-4 py-3 text-sm font-bold">
                        @error('editProgram') <span class="text-[10px] text-red-500 font-bold block mt-1">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1.5">Year Level</label>
                        <select wire:model="editYearLevel" class="w-full bg-gray-50 border border-gray-200 focus:border-yellow-500 rounded-xl px-4 py-3 text-sm font-bold">
                            <option value="1st Year">1st Year</option><option value="2nd Year">2nd Year</option><option value="3rd Year">3rd Year</option><option value="4th Year">4th Year</option><option value="5th Year">5th Year</option>
                        </select>
                        @error('editYearLevel') <span class="text-[10px] text-red-500 font-bold block mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="flex justify-end gap-3 mt-6">
                    <button wire:click="$set('candidateToEdit', null)" class="px-5 py-2.5 text-sm font-bold text-gray-600 hover:bg-gray-100 rounded-xl transition-colors">Cancel</button>
                    <button wire:click="saveEdit" class="px-5 py-2.5 text-sm font-black text-yellow-900 bg-yellow-400 hover:bg-yellow-500 rounded-xl shadow-lg transition-colors">Save Changes</button>
                </div>
            </div>
        </div>
    @endif
</div>
