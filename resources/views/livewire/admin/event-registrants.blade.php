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
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search name, org, school..." class="w-full sm:w-56 rounded-xl border-gray-200 text-sm focus:ring-red-500 focus:border-red-500 bg-gray-50">

            {{-- Classification Filter --}}
            <select wire:model.live="classificationFilter" class="w-full sm:w-auto rounded-xl border-gray-200 text-sm focus:ring-red-500 focus:border-red-500 font-bold bg-gray-50 cursor-pointer text-gray-700">
                <option value="">All Types</option>
                @foreach($classifications as $c)
                    <option value="{{ $c }}">{{ $c }}</option>
                @endforeach
            </select>

            <select wire:model.live="statusFilter" class="w-full sm:w-auto rounded-xl border-gray-200 text-sm focus:ring-red-500 focus:border-red-500 font-bold bg-gray-50 cursor-pointer text-gray-700">
                <option value="">All Statuses</option>
                <option value="Registered">Registered</option>
                <option value="Attended">Checked In</option>
            </select>

            <div class="flex gap-2 w-full sm:w-auto">
                <button wire:click="openBlastModal" class="flex-1 sm:flex-none bg-blue-600 text-white font-bold px-4 py-2.5 rounded-xl text-sm shadow-sm hover:bg-blue-700 hover:-translate-y-0.5 transition-all flex items-center justify-center gap-2 whitespace-nowrap">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                    Email
                </button>
                <button wire:click="exportRosterXls" class="flex-1 sm:flex-none bg-green-600 text-white font-bold px-4 py-2.5 rounded-xl text-sm shadow-sm hover:bg-green-700 hover:-translate-y-0.5 transition-all flex items-center justify-center gap-2 whitespace-nowrap">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    XLSX
                </button>
            </div>
        </div>
    </div>

    @if (session()->has('message'))
        <div class="bg-emerald-50 border border-emerald-100 text-emerald-700 px-6 py-4 rounded-xl text-sm font-bold shadow-sm animate-fade-in-down">
            {{ session('message') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="bg-red-50 border border-red-100 text-red-700 px-6 py-4 rounded-xl text-sm font-bold shadow-sm animate-fade-in-down">
            {{ session('error') }}
        </div>
    @endif

    {{-- Registrants Data Table --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse min-w-max">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="p-4 w-10 text-center">
                            <svg class="w-4 h-4 text-gray-400 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </th>
                        <th class="p-4 text-xs font-black uppercase text-gray-500 tracking-widest">Ticket Code</th>
                        <th class="p-4 text-xs font-black uppercase text-gray-500 tracking-widest">Attendee</th>
                        <th class="p-4 text-xs font-black uppercase text-gray-500 tracking-widest">Affiliation</th>
                        <th class="p-4 text-xs font-black uppercase text-gray-500 tracking-widest">Status</th>
                        <th class="p-4 text-xs font-black uppercase text-gray-500 tracking-widest text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($registrants as $reg)
                        <tr class="hover:bg-gray-50 transition-colors {{ in_array($reg->id, $selectedRegistrants) ? 'bg-blue-50/50' : '' }}">
                            <td class="p-4 text-center">
                                <input type="checkbox" wire:model="selectedRegistrants" value="{{ $reg->id }}" class="w-4 h-4 text-blue-600 rounded border-gray-300 focus:ring-blue-500 cursor-pointer">
                            </td>
                            <td class="p-4 text-sm font-mono font-bold text-gray-600">
                                {{ $reg->ticket_code }}
                            </td>
                            <td class="p-4">
                                <p class="text-sm font-bold text-gray-900">{{ $reg->name }}</p>
                                <p class="text-xs text-gray-500 mt-0.5">{{ $reg->email }}</p>
                            </td>
                            <td class="p-4">
                                <p class="text-sm font-bold text-gray-700">{{ $reg->classification ?? 'N/A' }}</p>
                                @if($reg->organization_name)
                                    <p class="text-xs text-indigo-600 font-bold mt-0.5">{{ $reg->organization_name }}</p>
                                @elseif($reg->college)
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
                                    Profile
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-8 text-center text-gray-400 font-bold text-sm">
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
        <div class="fixed inset-0 z-40 overflow-y-auto">
            <div class="fixed inset-0 bg-gray-900/40 backdrop-blur-sm" wire:click="$set('showModal', false)"></div>
            <div class="flex min-h-screen items-center justify-center p-4">
                <div class="relative w-full max-w-3xl bg-white rounded-[2rem] shadow-2xl p-8 transform transition-all flex flex-col max-h-[90vh] z-50">

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

                        {{-- Top Identity Panel & QR Code --}}
                        <div class="flex flex-col md:flex-row gap-6 bg-gray-50 p-5 rounded-2xl border border-gray-100">

                            {{-- Dynamic QR Code Display --}}
                            <div class="flex flex-col items-center justify-center shrink-0">
                                <div class="bg-white p-3 rounded-xl shadow-sm border border-gray-200 inline-block">
                                    <div id="admin-qr-container" class="w-32 h-32 flex items-center justify-center bg-gray-50 rounded-lg">
                                        {{-- The JS library will inject the QR code here --}}
                                        <svg class="w-8 h-8 text-gray-300 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm14 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path></svg>
                                    </div>
                                </div>
                                <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest mt-2">Scan for Attendance</p>
                            </div>

                            {{-- Demographics Grid --}}
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 flex-1">
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
                                    <div class="col-span-1 sm:col-span-2 mt-2 pt-3 border-t border-gray-200/60">
                                        <p class="text-[10px] font-black uppercase text-gray-500 mb-1">Organization Affiliation</p>
                                        <p class="text-sm font-bold text-gray-900">{{ $viewingRegistrant->organization_name }} <span class="text-gray-400 font-medium ml-1">({{ $viewingRegistrant->position }})</span></p>
                                    </div>
                                @endif
                            </div>
                        </div>

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
                            <button wire:click="deleteRegistrant({{ $viewingRegistrant->id }})" wire:confirm="Are you sure you want to permanently delete this registration? This action cannot be undone." class="w-full sm:w-auto bg-white border border-red-200 text-red-600 font-bold px-4 py-2 rounded-xl text-sm hover:bg-red-50 transition-colors">
                                Delete Record
                            </button>
                        </div>

                        <button wire:click="exportProfileDoc({{ $viewingRegistrant->id }})" class="w-full sm:w-auto bg-slate-900 text-white font-bold px-5 py-2.5 rounded-xl text-sm shadow-sm hover:bg-black hover:-translate-y-0.5 transition-all flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            Export DOC Profile
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- MODAL: Email Blast --}}
    @if($showBlastModal)
        <div class="fixed inset-0 z-50 overflow-y-auto">
            <div class="fixed inset-0 bg-gray-900/40 backdrop-blur-sm" wire:click="$set('showBlastModal', false)"></div>
            <div class="flex min-h-screen items-center justify-center p-4">
                <div class="relative w-full max-w-2xl bg-white rounded-[2rem] shadow-2xl p-8 transform transition-all flex flex-col z-50">

                    <div class="flex justify-between items-start mb-6">
                        <div>
                            <h2 class="text-2xl font-black text-gray-900">Email Broadcast</h2>
                            <p class="text-sm font-medium text-gray-500 mt-1">Send an announcement to event participants.</p>
                        </div>
                        <button wire:click="$set('showBlastModal', false)" class="text-gray-400 hover:text-red-500 bg-gray-100 hover:bg-red-50 rounded-full p-2 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>

                    <form wire:submit.prevent="sendBlast" class="space-y-5">
                        <div>
                            <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest mb-2">Target Audience</label>
                            <select wire:model="blastTarget" class="w-full rounded-xl border-gray-200 text-sm py-3 px-4 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 font-bold outline-none cursor-pointer">
                                <option value="all">Everyone on Roster</option>
                                <option value="Registered">Registered (Not Checked In)</option>
                                <option value="Attended">Checked In (Attended)</option>
                                <option value="selected" class="font-black text-blue-600">Selected Participants Only ({{ count($selectedRegistrants) }})</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest mb-2">Email Subject</label>
                            <input type="text" wire:model="blastSubject" class="w-full rounded-xl border-gray-200 text-sm py-3 px-4 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 font-bold outline-none">
                            @error('blastSubject') <span class="text-xs text-red-500 mt-1 font-bold">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest mb-2">Message Body</label>
                            <textarea wire:model="blastMessage" rows="6" placeholder="Type your announcement here..." class="w-full rounded-xl border-gray-200 text-sm py-3 px-4 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none resize-none"></textarea>
                            @error('blastMessage') <span class="text-xs text-red-500 mt-1 font-bold">{{ $message }}</span> @enderror
                        </div>

                        <div class="pt-4 border-t border-gray-100 flex gap-3 justify-end">
                            <button type="button" wire:click="$set('showBlastModal', false)" class="px-5 py-2.5 rounded-xl font-bold text-gray-500 hover:bg-gray-100 text-sm transition-colors">Cancel</button>
                            <button type="submit" wire:loading.attr="disabled" class="bg-blue-600 text-white px-6 py-2.5 rounded-xl font-bold text-sm hover:bg-blue-700 shadow-md transition-all flex items-center gap-2">
                                <span wire:loading.remove wire:target="sendBlast">Send Broadcast</span>
                                <span wire:loading wire:target="sendBlast" class="animate-pulse">Queueing Emails...</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>

{{-- Javascript for Dynamic QR Generation in Admin Modal --}}
@push('scripts')
    <script type="text/javascript" src="https://unpkg.com/qr-code-styling@1.5.0/lib/qr-code-styling.js"></script>
    <script>
        document.addEventListener('livewire:initialized', () => {
            let qrCodeInstance = null;

            // Watch for Livewire DOM updates to inject the QR Code when the modal opens
            Livewire.hook('morph.updated', ({ component, el }) => {
                const container = document.getElementById('admin-qr-container');

                // Only run if the container exists and we have a ticket code loaded
                if (container && @this.viewingRegistrant) {
                    const ticketCode = @this.viewingRegistrant.ticket_code;

                    // Clear the container (removes the loading spinner)
                    container.innerHTML = '';

                    qrCodeInstance = new QRCodeStyling({
                        width: 128,
                        height: 128,
                        data: ticketCode,
                        margin: 0,
                        dotsOptions: {
                            type: 'square',
                            gradient: {
                                type: 'linear',
                                rotation: Math.PI / 4,
                                colorStops: [
                                    { offset: 0, color: '#ef4444' }, // Red
                                    { offset: 0.5, color: '#eab308' }, // Yellow
                                    { offset: 1, color: '#22c55e' }  // Green
                                ]
                            }
                        },
                        cornersSquareOptions: {
                            type: 'square',
                            gradient: {
                                type: 'linear',
                                rotation: Math.PI / 4,
                                colorStops: [
                                    { offset: 0, color: '#ef4444' },
                                    { offset: 0.5, color: '#eab308' },
                                    { offset: 1, color: '#22c55e' }
                                ]
                            }
                        },
                        backgroundOptions: { color: 'transparent' }
                    });

                    qrCodeInstance.append(container);
                }
            });
        });
    </script>
@endpush
