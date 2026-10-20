<div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8 space-y-8">

    {{-- Header & Top Controls --}}
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <div class="flex items-center gap-3 mb-1">
                <a href="{{ route('admin.events.index') }}" class="text-gray-400 hover:text-red-600 transition font-bold text-sm">&larr; Back to Events</a>
            </div>
            <h1 class="text-2xl font-black text-gray-900 tracking-tight">{{ $event->title }}</h1>
            <p class="text-sm font-medium text-gray-500 mt-1">Manage {{ $stats['total'] }} total registrants and check-ins.</p>
        </div>

        <div class="flex flex-col sm:flex-row items-center gap-3 w-full md:w-auto">
            <input type="text" wire:model.live="search" placeholder="Search name, email, or ticket..." class="w-full sm:w-64 rounded-xl border-gray-200 text-sm focus:ring-red-500 focus:border-red-500 bg-gray-50">

            <select wire:model.live="statusFilter" class="w-full sm:w-auto rounded-xl border-gray-200 text-sm focus:ring-red-500 focus:border-red-500 font-bold bg-gray-50 cursor-pointer text-gray-700">
                <option value="">All Statuses</option>
                <option value="Registered">Registered</option>
                <option value="Attended">Checked In</option>
            </select>

            <button wire:click="exportRosterXls" class="w-full sm:w-auto bg-green-600 text-white font-bold px-4 py-2.5 rounded-xl text-sm shadow-sm hover:bg-green-700 hover:-translate-y-0.5 transition-all flex items-center justify-center gap-2 whitespace-nowrap">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                Export XLSX
            </button>
        </div>
    </div>

    @if (session()->has('message'))
        <div class="bg-red-50 border border-red-100 text-red-700 px-6 py-4 rounded-xl text-sm font-bold shadow-sm animate-fade-in-down">
            {{ session('message') }}
        </div>
    @endif

    {{-- Registrants Data Table --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse min-w-max">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100">
                        <th class="p-4 text-xs font-black uppercase text-gray-500 tracking-widest">Ticket Code</th>
                        <th class="p-4 text-xs font-black uppercase text-gray-500 tracking-widest">Attendee</th>
                        <th class="p-4 text-xs font-black uppercase text-gray-500 tracking-widest">Classification</th>
                        <th class="p-4 text-xs font-black uppercase text-gray-500 tracking-widest">Status</th>
                        <th class="p-4 text-xs font-black uppercase text-gray-500 tracking-widest text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($registrants as $reg)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="p-4 text-sm font-mono font-bold text-gray-600">
                                {{ $reg->ticket_code }}
                            </td>
                            <td class="p-4">
                                <p class="text-sm font-bold text-gray-900">{{ $reg->name }}</p>
                                <p class="text-xs text-gray-500 mt-0.5">{{ $reg->email }}</p>
                            </td>
                            <td class="p-4">
                                <p class="text-sm font-bold text-gray-700">{{ $reg->classification ?? 'N/A' }}</p>
                                @if($reg->college)
                                    <p class="text-xs text-gray-500 mt-0.5">{{ $reg->college->name }}</p>
                                @endif
                            </td>
                            <td class="p-4">
                                <span class="px-3 py-1 text-[10px] font-black uppercase tracking-widest rounded-full
                                    {{ $reg->status === 'Attended' ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-700' }}">
                                    {{ $reg->status ?? 'Registered' }}
                                </span>
                            </td>
                            <td class="p-4 text-right">
                                <button wire:click="viewRegistrant({{ $reg->id }})" class="text-red-600 hover:text-red-800 font-bold text-sm bg-red-50 hover:bg-red-100 px-4 py-2 rounded-lg transition-colors inline-block">
                                    Review Profile
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-8 text-center text-gray-400 font-bold text-sm">
                                No attendees found matching your filters.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-gray-100">
            {{ $registrants->links() }}
        </div>
    </div>

    {{-- MODAL: Review Registrant Profile --}}
    @if($showModal && $viewingRegistrant)
        <div class="fixed inset-0 z-50 overflow-y-auto">
            <div class="fixed inset-0 bg-gray-900/40 backdrop-blur-sm" wire:click="$set('showModal', false)"></div>
            <div class="flex min-h-screen items-center justify-center p-4">
                <div class="relative w-full max-w-2xl bg-white rounded-[2rem] shadow-2xl p-8 transform transition-all flex flex-col max-h-[90vh]">

                    {{-- Modal Header --}}
                    <div class="flex justify-between items-start mb-6 shrink-0">
                        <div>
                            <h2 class="text-2xl font-black text-gray-900">{{ $viewingRegistrant->name }}</h2>
                            <p class="text-sm font-mono font-bold text-red-600 mt-1 uppercase tracking-widest">{{ $viewingRegistrant->ticket_code }}</p>
                        </div>
                        <button wire:click="$set('showModal', false)" class="text-gray-400 hover:text-red-500 bg-gray-100 hover:bg-red-50 rounded-full p-2 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>

                    {{-- Scrollable Profile Body --}}
                    <div class="overflow-y-auto flex-1 pr-2 space-y-6">

                        {{-- Demographics Grid --}}
                        <div class="grid grid-cols-2 gap-4 bg-gray-50 p-5 rounded-2xl border border-gray-100">
                            <div>
                                <p class="text-[10px] font-black uppercase text-gray-500 mb-1">Email Address</p>
                                <p class="text-sm font-bold text-gray-900">{{ $viewingRegistrant->email }}</p>
                            </div>
                            <div>
                                <p class="text-[10px] font-black uppercase text-gray-500 mb-1">Contact Number</p>
                                <p class="text-sm font-bold text-gray-900">{{ $viewingRegistrant->contact_number ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <p class="text-[10px] font-black uppercase text-gray-500 mb-1">Classification</p>
                                <p class="text-sm font-bold text-gray-900">{{ $viewingRegistrant->classification ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <p class="text-[10px] font-black uppercase text-gray-500 mb-1">College / School</p>
                                <p class="text-sm font-bold text-gray-900">{{ $viewingRegistrant->college ? $viewingRegistrant->college->name : ($viewingRegistrant->school ?? 'N/A') }}</p>
                            </div>
                            @if($viewingRegistrant->organization_name)
                                <div class="col-span-2">
                                    <p class="text-[10px] font-black uppercase text-gray-500 mb-1">Organization Affiliation</p>
                                    <p class="text-sm font-bold text-gray-900">{{ $viewingRegistrant->organization_name }} <span class="text-gray-400 font-medium ml-1">({{ $viewingRegistrant->position }})</span></p>
                                </div>
                            @endif
                        </div>

                        {{-- Dynamic Questionnaire Responses --}}
                        <div>
                            <h3 class="text-sm font-black text-gray-900 uppercase tracking-widest border-b border-gray-200 pb-2 mb-4">Questionnaire Responses</h3>

                            @if(!$viewingRegistrant->answers || $viewingRegistrant->answers->count() === 0)
                                <p class="text-sm text-gray-500 italic p-4 bg-gray-50 rounded-xl">No custom questions answered.</p>
                            @else
                                <div class="space-y-4">
                                    @foreach($viewingRegistrant->answers as $ansRecord)
                                        <div class="bg-red-50/50 p-4 rounded-xl border border-red-100">
                                            <p class="text-[11px] font-black text-red-800 uppercase mb-2 leading-tight">
                                                {{ $ansRecord->question ? $ansRecord->question->question_text : 'Deleted Question' }}
                                            </p>
                                            @php
                                                $val = $ansRecord->answer;
                                                if ($ansRecord->question && $ansRecord->question->type === 'checkbox') {
                                                    $val = ($val === 'true' || $val === '1' || $val === 'Yes' || $val === 'on') ? 'Yes / Agreed' : 'No';
                                                }
                                            @endphp
                                            <p class="text-sm text-gray-800 font-medium whitespace-pre-wrap leading-relaxed">{{ $val ?: 'No response provided' }}</p>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Footer Action Bar --}}
                    <div class="pt-6 mt-4 border-t border-gray-100 shrink-0 flex flex-col sm:flex-row justify-between items-center gap-4">
                        <div class="flex gap-2 w-full sm:w-auto">
                            @if($viewingRegistrant->status !== 'Attended')
                                <button wire:click="updateStatus({{ $viewingRegistrant->id }}, 'Attended')" class="w-full sm:w-auto bg-emerald-100 text-emerald-700 font-bold px-4 py-2 rounded-xl text-sm hover:bg-emerald-200 transition-colors">
                                    Mark as Attended
                                </button>
                            @endif
                        </div>

                        {{-- Download Profile DOC --}}
                        <button wire:click="exportProfileDoc({{ $viewingRegistrant->id }})" class="w-full sm:w-auto bg-slate-900 text-white font-bold px-5 py-2.5 rounded-xl text-sm shadow-sm hover:bg-black hover:-translate-y-0.5 transition-all flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            Export DOC Profile
                        </button>
                    </div>

                </div>
            </div>
        </div>
    @endif
</div>
