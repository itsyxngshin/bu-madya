<div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8 font-sans">

    {{-- Notifications --}}
    @if (session()->has('message'))
        <div class="mb-4 bg-green-50 text-green-700 p-4 rounded-xl border border-green-200 text-sm font-bold flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            {{ session('message') }}
        </div>
    @endif
    @if (session()->has('error'))
        <div class="mb-4 bg-red-50 text-red-700 p-4 rounded-xl border border-red-200 text-sm font-bold flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            {{ session('error') }}
        </div>
    @endif

    {{-- Header & Toolbar --}}
    <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
        <div>
            <h2 class="font-black text-2xl text-gray-900 tracking-tight">Linkages & Proposals</h2>
            <p class="text-sm text-gray-500">Manage partnership requests from external organizations.</p>
        </div>
        <div class="flex items-center gap-3 w-full md:w-auto">
            <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search orgs or titles..." class="w-full md:w-64 rounded-xl border-gray-200 text-sm focus:ring-red-500">
            <select wire:model.live="statusFilter" class="rounded-xl border-gray-200 text-sm focus:ring-red-500 bg-gray-50">
                <option value="">All Statuses</option>
                <option value="pending">Pending</option>
                <option value="reviewed">Reviewed</option>
                <option value="accepted">Accepted</option>
                <option value="declined">Declined</option>
            </select>
        </div>
    </div>

    {{-- Main Table --}}
    <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-600">
                <thead class="bg-gray-50/50 border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-4 font-bold text-gray-400 uppercase tracking-wider text-[10px]">Organization</th>
                        <th class="px-6 py-4 font-bold text-gray-400 uppercase tracking-wider text-[10px]">Proposal Title & Type</th>
                        <th class="px-6 py-4 font-bold text-gray-400 uppercase tracking-wider text-[10px]">Date</th>
                        <th class="px-6 py-4 font-bold text-gray-400 uppercase tracking-wider text-[10px]">Status</th>
                        <th class="px-6 py-4 font-bold text-gray-400 uppercase tracking-wider text-[10px] text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($proposals as $p)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4">
                            <p class="font-bold text-gray-900">{{ $p->organization_name }}</p>
                            <p class="text-xs text-gray-500">{{ $p->contact_person }}</p>
                        </td>
                        <td class="px-6 py-4">
                            <p class="font-bold text-gray-800 truncate max-w-xs" title="{{ $p->title }}">{{ $p->title }}</p>
                            <span class="inline-block mt-1 px-2 py-0.5 bg-blue-50 text-blue-600 rounded text-[9px] font-bold uppercase tracking-wider border border-blue-100">
                                {{ $p->partnership_type }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-xs text-gray-500 whitespace-nowrap">
                            {{ $p->created_at->format('M d, Y') }}
                        </td>
                        <td class="px-6 py-4">
                            @php
                                $statusColors = [
                                    'pending' => 'bg-yellow-100 text-yellow-700',
                                    'reviewed' => 'bg-blue-100 text-blue-700',
                                    'accepted' => 'bg-green-100 text-green-700',
                                    'declined' => 'bg-red-100 text-red-700',
                                ];
                                $color = $statusColors[$p->status] ?? 'bg-gray-100 text-gray-700';
                            @endphp
                            <span class="px-2.5 py-1 rounded text-[10px] font-black uppercase tracking-widest {{ $color }}">
                                {{ $p->status }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <button wire:click="viewDetails({{ $p->id }})" class="px-3 py-1.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-bold rounded-lg transition">
                                    Review
                                </button>
                                <button wire:click="delete({{ $p->id }})" wire:confirm="Delete this proposal permanently?" class="p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-gray-400 font-bold uppercase tracking-widest text-xs">
                            No proposals found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-gray-100 bg-gray-50/50">
            {{ $proposals->links() }}
        </div>
    </div>

    {{-- DETAILS MODAL --}}
    @if($viewingProposal)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-gray-900/40 backdrop-blur-sm animate-fade-in-down">
        <div class="bg-white rounded-3xl shadow-2xl w-full max-w-3xl max-h-[90vh] flex flex-col overflow-hidden">
            
            {{-- Modal Header --}}
            <div class="px-8 py-5 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                <h3 class="font-black text-xl text-gray-900">Proposal Details</h3>
                <button wire:click="closeDetails" class="text-gray-400 hover:text-red-600 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            {{-- Modal Body --}}
            <div class="p-8 overflow-y-auto flex-1">
                <div class="grid md:grid-cols-2 gap-8 mb-8">
                    <div>
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Organization</p>
                        <p class="text-base font-bold text-gray-900">{{ $viewingProposal->organization_name }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Contact Details</p>
                        <p class="text-sm font-bold text-gray-900">{{ $viewingProposal->contact_person }}</p>
                        <p class="text-sm text-gray-600"><a href="mailto:{{ $viewingProposal->email }}" class="text-blue-600 hover:underline">{{ $viewingProposal->email }}</a></p>
                        @if($viewingProposal->phone)
                            <p class="text-sm text-gray-600">{{ $viewingProposal->phone }}</p>
                        @endif
                    </div>
                </div>

                <div class="mb-8">
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">The Pitch ({{ $viewingProposal->partnership_type }})</p>
                    <h4 class="text-xl font-black text-gray-900 mb-3">{{ $viewingProposal->title }}</h4>
                    <div class="bg-gray-50 p-5 rounded-2xl border border-gray-100 text-gray-700 text-sm leading-relaxed whitespace-pre-wrap">
                        {{ $viewingProposal->message }}
                    </div>
                </div>

                @if($viewingProposal->file_path)
                <div class="mb-4">
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Attached Document</p>
                    <button wire:click="downloadFile({{ $viewingProposal->id }})" class="inline-flex items-center gap-2 px-5 py-2.5 bg-gray-900 text-white text-xs font-bold uppercase tracking-wide rounded-xl shadow-md hover:bg-red-600 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                        Download Attachment
                    </button>
                </div>
                @endif
            </div>

            {{-- Modal Footer / Actions --}}
            <div class="px-8 py-5 border-t border-gray-100 bg-gray-50 flex items-center justify-between">
                <span class="text-xs font-bold text-gray-500 uppercase tracking-widest">Update Status:</span>
                <div class="flex gap-2">
                    <button wire:click="updateStatus({{ $viewingProposal->id }}, 'pending')" class="px-3 py-1.5 rounded-lg text-xs font-bold transition {{ $viewingProposal->status === 'pending' ? 'bg-yellow-100 text-yellow-700 ring-2 ring-yellow-400' : 'bg-white border border-gray-200 text-gray-600 hover:bg-gray-100' }}">Pending</button>
                    <button wire:click="updateStatus({{ $viewingProposal->id }}, 'reviewed')" class="px-3 py-1.5 rounded-lg text-xs font-bold transition {{ $viewingProposal->status === 'reviewed' ? 'bg-blue-100 text-blue-700 ring-2 ring-blue-400' : 'bg-white border border-gray-200 text-gray-600 hover:bg-gray-100' }}">Reviewed</button>
                    <button wire:click="updateStatus({{ $viewingProposal->id }}, 'accepted')" class="px-3 py-1.5 rounded-lg text-xs font-bold transition {{ $viewingProposal->status === 'accepted' ? 'bg-green-100 text-green-700 ring-2 ring-green-400' : 'bg-white border border-gray-200 text-gray-600 hover:bg-gray-100' }}">Accept</button>
                    <button wire:click="updateStatus({{ $viewingProposal->id }}, 'declined')" class="px-3 py-1.5 rounded-lg text-xs font-bold transition {{ $viewingProposal->status === 'declined' ? 'bg-red-100 text-red-700 ring-2 ring-red-400' : 'bg-white border border-gray-200 text-gray-600 hover:bg-gray-100' }}">Decline</button>
                </div>
            </div>

        </div>
    </div>
    @endif

</div>