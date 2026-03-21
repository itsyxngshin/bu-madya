<div class="min-h-screen bg-gray-100 p-6 font-sans text-gray-900">
    <div class="max-w-7xl mx-auto">

        {{-- HEADER & ACTIONS --}}
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
            <div>
                <h1 class="text-3xl font-black text-gray-900">Welfare & Grievances</h1>
                <p class="text-sm text-gray-500 mt-1">Confidential tracker for STRAW Head & CSC President.</p>
            </div>

            <div class="flex flex-col sm:flex-row items-center gap-3 w-full md:w-auto">
                {{-- Status Filter --}}
                <select wire:model.live="statusFilter" class="w-full sm:w-auto rounded-xl border-gray-200 focus:border-orange-500 focus:ring-orange-500 text-sm font-bold text-gray-600 bg-white px-4 py-2 shadow-sm">
                    <option value="">All Statuses</option>
                    <option value="Pending">Pending</option>
                    <option value="Under Review">Under Review</option>
                    <option value="Resolved">Resolved</option>
                </select>

                {{-- Search Bar --}}
                <div class="relative w-full sm:w-64">
                    <input wire:model.live.debounce.300ms="search" type="text" class="w-full pl-10 pr-4 py-2 rounded-xl border-gray-200 focus:border-orange-500 focus:ring-orange-500 text-sm shadow-sm" placeholder="Search case or name...">
                    <svg class="w-4 h-4 text-gray-400 absolute left-3 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
            </div>
        </div>

        {{-- FLASH MESSAGE --}}
        @if (session()->has('success'))
            <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl text-sm font-bold flex items-center gap-2" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                {{ session('success') }}
            </div>
        @endif

        {{-- DATA TABLE (Replaces the Google Sheet) --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-100 text-[10px] uppercase tracking-widest text-gray-500 font-bold">
                            <th class="p-4 whitespace-nowrap">Case Number</th>
                            <th class="p-4 whitespace-nowrap">Date Submitted</th>
                            <th class="p-4 whitespace-nowrap">Reporter Name</th>
                            <th class="p-4 whitespace-nowrap">Assigned To</th>
                            <th class="p-4 whitespace-nowrap">Nature of Incident</th>
                            <th class="p-4 whitespace-nowrap">Status</th>
                            <th class="p-4 whitespace-nowrap text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-sm">
                        @forelse($tickets as $ticket)
                            <tr class="hover:bg-gray-50 transition group">
                                <td class="p-4 font-black text-gray-900">{{ $ticket->case_number }}</td>
                                <td class="p-4 text-gray-500 font-medium">{{ $ticket->created_at->format('M d, Y') }}</td>
                                <td class="p-4 font-bold text-gray-700">{{ $ticket->first_name }} {{ $ticket->last_name }}</td>
                                <td class="p-4">
                                    @if($ticket->assignedOrganization)
                                        <span class="px-2.5 py-1 bg-purple-50 text-purple-700 border border-purple-100 rounded-lg text-[10px] font-bold uppercase tracking-wider inline-block truncate max-w-[120px]" title="{{ $ticket->assignedOrganization->name }}">
                                            {{ $ticket->assignedOrganization->name }}
                                        </span>
                                    @else
                                        <span class="px-2.5 py-1 bg-gray-100 text-gray-500 border border-gray-200 rounded-lg text-[10px] font-bold uppercase tracking-wider inline-block">
                                            Main
                                        </span>
                                    @endif
                                </td>
                                <td class="p-4">
                                    <span class="px-2.5 py-1 bg-gray-100 text-gray-600 rounded-lg text-[10px] font-bold uppercase tracking-wider">
                                        {{ $ticket->nature_of_incident }}
                                    </span>
                                </td>
                                <td class="p-4">
                                    @if($ticket->status === 'Pending')
                                        <span class="px-2.5 py-1 bg-orange-100 text-orange-700 rounded-lg text-[10px] font-bold uppercase tracking-wider flex items-center gap-1 w-max">
                                            <span class="w-1.5 h-1.5 rounded-full bg-orange-500 animate-pulse"></span> Pending
                                        </span>
                                    @elseif($ticket->status === 'Under Review')
                                        <span class="px-2.5 py-1 bg-blue-100 text-blue-700 rounded-lg text-[10px] font-bold uppercase tracking-wider flex items-center gap-1 w-max">
                                            Under Review
                                        </span>
                                    @else
                                        <span class="px-2.5 py-1 bg-green-100 text-green-700 rounded-lg text-[10px] font-bold uppercase tracking-wider flex items-center gap-1 w-max">
                                            Resolved
                                        </span>
                                    @endif
                                </td>
                                <td class="p-4 text-right">
                                    <button wire:click="viewTicket({{ $ticket->id }})" class="px-3 py-1.5 bg-gray-900 text-white rounded-lg text-xs font-bold hover:bg-orange-600 transition shadow-sm">
                                        Review Case
                                    </button>
                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="p-8 text-center text-gray-400 font-bold">
                                    No incident reports found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($tickets->hasPages())
                <div class="p-4 border-t border-gray-100 bg-gray-50">
                    {{ $tickets->links() }}
                </div>
            @endif
        </div>

        {{-- ========================================== --}}
        {{-- VIEW CASE MODAL --}}
        {{-- ========================================== --}}
        @if($viewModalOpen && $selectedTicket)
            <div class="fixed inset-0 z-[100] flex items-center justify-center p-4 sm:p-6" x-data="{}" x-cloak>
                <div class="fixed inset-0 bg-gray-900/80 backdrop-blur-sm transition-opacity" wire:click="closeTicket"></div>

                <div class="relative bg-white rounded-3xl shadow-2xl w-full max-w-2xl flex flex-col max-h-[90vh] overflow-hidden transform transition-all">

                    {{-- Modal Header --}}
                    <div class="flex items-center justify-between p-6 border-b border-gray-100 bg-gray-50 shrink-0">
                        <div>
                            <span class="text-[10px] font-black uppercase tracking-widest text-orange-600 block mb-1">Confidential Report</span>
                            <h3 class="text-2xl font-black text-gray-900 leading-tight">{{ $selectedTicket->case_number }}</h3>
                        </div>
                        <button wire:click="closeTicket" class="p-2 text-gray-400 hover:text-red-500 hover:bg-red-50 rounded-xl transition">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>

                    {{-- Modal Body --}}
                    <div class="p-6 overflow-y-auto custom-scrollbar">

                        <div class="grid grid-cols-2 gap-6 mb-8 border-b border-gray-100 pb-8">
                            <div>
                                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest block mb-1">Reporter</span>
                                <p class="text-sm font-black text-gray-900">{{ $selectedTicket->first_name }} {{ $selectedTicket->middle_name }} {{ $selectedTicket->last_name }}</p>
                            </div>
                            <div>
                                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest block mb-1">Year & Block</span>
                                <p class="text-sm font-bold text-gray-700">{{ $selectedTicket->year_and_block }}</p>
                            </div>
                            <div>
                                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest block mb-1">Email Address</span>
                                <p class="text-sm font-bold text-gray-700">{{ $selectedTicket->email }}</p>
                            </div>
                            <div>
                                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest block mb-1">Contact Number</span>
                                <p class="text-sm font-bold text-gray-700">{{ $selectedTicket->phone_number }}</p>
                            </div>
                        </div>

                        <div class="mb-8">
                            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest block mb-2">Nature of Incident</span>
                            <span class="px-3 py-1.5 bg-gray-100 text-gray-800 rounded-lg text-xs font-bold uppercase tracking-wider border border-gray-200">
                                {{ $selectedTicket->nature_of_incident }}
                            </span>
                        </div>

                        <div class="mb-8">
                            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest block mb-2">Full Statement</span>
                            <div class="bg-orange-50/50 p-4 rounded-xl border border-orange-100">
                                <p class="text-sm text-gray-800 leading-relaxed whitespace-pre-wrap">{{ $selectedTicket->incident_details }}</p>
                            </div>
                        </div>

                        {{-- [NEW] Official Updates & Notes Box --}}
                        <div class="mb-8 border-t border-gray-100 pt-6">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-[10px] font-bold text-orange-600 uppercase tracking-widest">Official Updates (Visible to Student)</span>

                                {{-- Save Button --}}
                                <button wire:click="saveNotes" class="text-[10px] bg-orange-100 text-orange-700 hover:bg-orange-200 px-3 py-1 rounded-md font-bold uppercase tracking-wider transition">
                                    <span wire:loading.remove wire:target="saveNotes">Save Updates</span>
                                    <span wire:loading wire:target="saveNotes">Saving...</span>
                                </button>
                            </div>

                            <textarea wire:model="adminNotes" rows="4"
                                      class="w-full bg-white border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-orange-500 focus:border-orange-500 block p-4 shadow-sm resize-y"
                                      placeholder="Write an update here. The student will see this message when they track their case..."></textarea>
                            <p class="text-[10px] text-gray-400 mt-1 italic">Note: Remember to click 'Save Updates' after typing.</p>
                        </div>

                        @if($selectedTicket->file_upload_path)
                            <div class="mb-4">
                                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest block mb-2">Attached Evidence</span>
                                <a href="{{ asset('storage/' . $selectedTicket->file_upload_path) }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-3 bg-gray-50 border border-gray-200 hover:border-gray-300 rounded-xl text-sm font-bold text-gray-700 hover:text-orange-600 transition w-full sm:w-auto">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                    View Uploaded File
                                </a>
                            </div>
                        @endif
                    </div>

                    {{-- Modal Footer: Status Update Actions --}}
                    <div class="p-6 border-t border-gray-100 bg-gray-50 shrink-0 flex flex-col sm:flex-row items-center justify-between gap-4">
                        <span class="text-xs font-bold text-gray-500 uppercase tracking-widest">Update Case Status:</span>

                        <div class="flex gap-2 w-full sm:w-auto">
                            <button wire:click="updateStatus('Pending')" class="flex-1 sm:flex-none px-4 py-2 text-xs font-bold uppercase tracking-wider rounded-lg transition border {{ $selectedTicket->status === 'Pending' ? 'bg-orange-100 text-orange-700 border-orange-200' : 'bg-white text-gray-500 border-gray-200 hover:bg-gray-50' }}">
                                Pending
                            </button>
                            <button wire:click="updateStatus('Under Review')" class="flex-1 sm:flex-none px-4 py-2 text-xs font-bold uppercase tracking-wider rounded-lg transition border {{ $selectedTicket->status === 'Under Review' ? 'bg-blue-100 text-blue-700 border-blue-200' : 'bg-white text-gray-500 border-gray-200 hover:bg-gray-50' }}">
                                Reviewing
                            </button>
                            <button wire:click="updateStatus('Resolved')" class="flex-1 sm:flex-none px-4 py-2 text-xs font-bold uppercase tracking-wider rounded-lg transition border {{ $selectedTicket->status === 'Resolved' ? 'bg-green-100 text-green-700 border-green-200' : 'bg-white text-gray-500 border-gray-200 hover:bg-gray-50' }}">
                                Resolved
                            </button>
                        </div>
                    </div>

                </div>
            </div>
        @endif

    </div>
</div>
