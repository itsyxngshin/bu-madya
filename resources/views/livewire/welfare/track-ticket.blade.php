<div class="min-h-screen bg-gray-900 py-12 px-4 sm:px-6 lg:px-8 flex items-center justify-center relative overflow-hidden">

    {{-- Orange Background Accent --}}
    <div class="absolute inset-0 z-0 flex justify-center">
        <div class="w-[800px] h-full bg-orange-500 rounded-t-[500px] mt-32 shadow-2xl"></div>
    </div>

    <div class="max-w-xl w-full z-10">
        <div class="bg-white rounded-3xl shadow-2xl overflow-hidden transform transition-all">

            <div class="p-8 md:p-10">
                <div class="text-center mb-8">
                    <div class="w-16 h-16 bg-orange-100 text-orange-600 rounded-2xl flex items-center justify-center mx-auto mb-4 transform rotate-3">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                    <h2 class="text-3xl font-black text-gray-900">Track Your Case</h2>
                    <p class="text-sm text-gray-500 mt-2">Enter your Case Number and Email to securely view the status of your incident report.</p>
                </div>

                @if(!$ticket)
                    {{-- SEARCH FORM --}}
                    <form wire:submit.prevent="searchTicket" class="space-y-5">

                        @if (session()->has('error'))
                            <div class="bg-red-50 text-red-600 p-4 rounded-xl text-sm font-bold flex items-start gap-3">
                                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                {{ session('error') }}
                            </div>
                        @endif

                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Case Number</label>
                            <input type="text" wire:model="case_number" class="w-full rounded-xl border-gray-200 focus:border-orange-500 focus:ring-orange-500 text-lg font-bold uppercase tracking-wider px-4 py-3 placeholder-gray-300" placeholder="CASE-0001" required>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Email Address</label>
                            <input type="email" wire:model="email" class="w-full rounded-xl border-gray-200 focus:border-orange-500 focus:ring-orange-500 px-4 py-3 placeholder-gray-300" placeholder="Used during submission" required>
                        </div>

                        <button type="submit" class="w-full bg-gray-900 hover:bg-orange-600 text-white font-bold py-4 rounded-xl shadow-lg transition transform hover:-translate-y-0.5 flex items-center justify-center gap-2 mt-4">
                            <span wire:loading.remove wire:target="searchTicket">Search Status</span>
                            <span wire:loading wire:target="searchTicket">Searching Securely...</span>
                        </button>
                    </form>
                @else
                    {{-- SEARCH RESULT (TICKET FOUND) --}}
                    <div class="animate-fade-in-up">
                        <div class="bg-gray-50 rounded-2xl p-6 border border-gray-100 mb-6">
                            <div class="flex justify-between items-start mb-4 border-b border-gray-200 pb-4">
                                <div>
                                    <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest block mb-1">Case Number</span>
                                    <h3 class="text-2xl font-black text-gray-900 tracking-wider">{{ $ticket->case_number }}</h3>
                                </div>
                                <div class="text-right">
                                    <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest block mb-1">Submitted</span>
                                    <p class="text-sm font-bold text-gray-700">{{ $ticket->created_at->format('M d, Y') }}</p>
                                </div>
                            </div>

                            <div class="mb-2">
                                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest block mb-2">Current Status</span>

                                {{-- Dynamic Status Badge --}}
                                @if($ticket->status === 'Pending')
                                    <div class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-orange-100 text-orange-700 font-bold border border-orange-200">
                                        <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                        Pending Review
                                    </div>
                                @elseif($ticket->status === 'Under Review')
                                    <div class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-blue-100 text-blue-700 font-bold border border-blue-200">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                        Under Investigation
                                    </div>
                                @elseif($ticket->status === 'Resolved')
                                    <div class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-green-100 text-green-700 font-bold border border-green-200">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                        Resolved / Closed
                                    </div>
                                @endif
                            </div>

                            {{-- [NEW] Official Updates Display --}}
                            @if($ticket->admin_notes)
                                <div class="mt-6 border-t border-gray-200 pt-6 animate-fade-in-up">
                                    <span class="text-[10px] font-bold text-orange-500 uppercase tracking-widest block mb-2 flex items-center gap-1.5">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
                                        Official Update from STRAW
                                    </span>
                                    <div class="bg-white p-4 rounded-xl border border-gray-100 shadow-sm">
                                        <p class="text-sm text-gray-800 leading-relaxed whitespace-pre-wrap">{{ $ticket->admin_notes }}</p>
                                    </div>
                                </div>
                            @endif

                            <p class="text-xs text-gray-500 mt-3 leading-relaxed">
                                The STRAW Head and CSC President have received your report. Please check back here periodically or monitor your email for official updates regarding your case.
                            </p>
                        </div>

                        <button wire:click="resetSearch" class="w-full text-sm font-bold text-gray-500 hover:text-orange-600 transition flex items-center justify-center gap-1.5 py-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                            Track Another Case
                        </button>
                    </div>
                @endif
            </div>

            <div class="bg-gray-50 px-8 py-4 border-t border-gray-100 text-center">
                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">BU MADYA Student Welfare & Grievance Portal</p>
            </div>
        </div>
    </div>
</div>
