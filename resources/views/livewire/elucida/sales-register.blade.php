<div class="min-h-screen bg-gray-100 p-4 md:p-6 font-sans text-gray-900 pb-24">
    <div class="max-w-6xl mx-auto space-y-6">

        {{-- HEADER BANNER --}}
        <div class="bg-white rounded-3xl p-6 md:p-8 shadow-sm border border-gray-200 relative overflow-hidden flex flex-col md:flex-row justify-between items-center gap-4">
            <div class="absolute top-0 left-0 w-2 h-full bg-gradient-to-b from-blue-600 to-teal-400"></div>
            <div>
                <h1 class="text-2xl md:text-3xl font-black text-gray-900 leading-tight">Sales Tracker POS</h1>
                <p class="text-xs font-bold text-gray-500 uppercase tracking-widest mt-1">Rapidly log batch event revenues</p>
            </div>
            <div class="text-center md:text-right bg-gray-50 border border-gray-100 px-4 py-2 rounded-xl">
                <span class="text-[9px] font-bold uppercase text-gray-400 block mb-0.5">Target Ledger</span>
                <span class="text-gray-900 font-bold text-xs uppercase">{{ $activeLedger->period_name }}</span>
            </div>
        </div>

        @if (session()->has('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl text-sm font-bold shadow-sm">
                {{ session('success') }}
            </div>
        @endif

        <div class="grid grid-cols-1 xl:grid-cols-12 gap-6">

            {{-- LEFT COLUMN: Batch Settings & Entry --}}
            <div class="xl:col-span-4 space-y-6">

                {{-- Parameters --}}
                <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-200">
                    <h3 class="text-xs font-bold text-gray-500 uppercase tracking-widest border-b border-gray-100 pb-3 mb-5">Batch Parameters</h3>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1.5">Event / Source Name</label>
                            <input type="text" wire:model="transactionDescription" placeholder="e.g. IT DAYS - CHAROT READING" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm font-medium focus:bg-white focus:ring-blue-500 focus:border-blue-500 shadow-sm transition-colors">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1.5">Lock Default Price (PHP)</label>
                            <input type="number" step="0.01" wire:model.live="defaultPrice" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-base font-black text-blue-600 focus:bg-white focus:ring-blue-500 focus:border-blue-500 shadow-sm transition-colors text-right">
                        </div>
                    </div>
                </div>

                {{-- Rapid Entry Form --}}
                <div class="bg-gray-900 rounded-3xl p-6 shadow-lg relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-blue-500/20 rounded-full blur-3xl -mt-10 -mr-10 pointer-events-none"></div>

                    <h3 class="text-sm font-bold text-gray-300 uppercase tracking-widest mb-5 relative z-10">Rapid Entry Panel</h3>

                    <form wire:submit.prevent="pushToGrid" class="space-y-4 relative z-10">
                        <div>
                            <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1.5">Student / Buyer Name</label>
                            <input type="text" wire:model="buyer_name" autofocus class="w-full bg-white border-0 rounded-xl px-4 py-3 text-sm font-bold focus:ring-2 focus:ring-blue-500 shadow-inner">
                            @error('buyer_name') <span class="text-xs text-red-400 font-bold block mt-1.5">{{ $message }}</span> @enderror
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1.5">Receipt #</label>
                                <input type="text" wire:model="receipt_number" class="w-full bg-gray-100 border-0 rounded-xl px-4 py-3 text-sm font-mono font-bold text-gray-700 focus:ring-2 focus:ring-blue-500 shadow-inner">
                                @error('receipt_number') <span class="text-xs text-red-400 font-bold block mt-1.5">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1.5">Price</label>
                                <input type="number" step="0.01" wire:model="price" class="w-full bg-white border-0 rounded-xl px-4 py-3 text-sm font-black text-blue-600 focus:ring-2 focus:ring-blue-500 shadow-inner text-right">
                            </div>
                        </div>

                        <button type="submit" class="w-full bg-blue-600 text-white text-xs font-bold uppercase tracking-wider py-3.5 rounded-xl hover:bg-blue-500 transition-colors mt-2 shadow-md flex justify-center items-center gap-2">
                            Push to Grid
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                        </button>
                    </form>
                </div>
            </div>

            {{-- RIGHT COLUMN: Grid Preview & Commitment --}}
            <div class="xl:col-span-8">
                <div class="bg-white rounded-3xl shadow-sm border border-gray-200 flex flex-col h-full overflow-hidden">

                    <div class="p-5 sm:p-6 border-b border-gray-100 flex justify-between items-center bg-gray-50">
                        <h2 class="text-sm font-bold uppercase tracking-widest text-gray-700">Staged Register Buffer</h2>
                        <span class="text-[10px] font-bold text-blue-600 bg-blue-50 px-2.5 py-1 rounded-lg border border-blue-100">{{ count($salesGrid) }} Entries</span>
                    </div>

                    <div class="flex-1 overflow-y-auto max-h-[500px] custom-scrollbar">
                        <table class="w-full text-left border-collapse min-w-[500px]">
                            <thead class="sticky top-0 bg-white shadow-sm z-10">
                                <tr>
                                    <th class="p-4 text-[10px] font-bold uppercase text-gray-400 tracking-widest w-12 text-center border-b border-gray-100">#</th>
                                    <th class="p-4 text-[10px] font-bold uppercase text-gray-400 tracking-widest border-b border-gray-100">Buyer Name</th>
                                    <th class="p-4 text-[10px] font-bold uppercase text-gray-400 tracking-widest border-b border-gray-100">Receipt No.</th>
                                    <th class="p-4 text-[10px] font-bold uppercase text-gray-400 tracking-widest text-right border-b border-gray-100">Price</th>
                                    <th class="p-4 w-12 text-center border-b border-gray-100"></th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                @forelse($salesGrid as $index => $sale)
                                    <tr class="hover:bg-blue-50/50 transition-colors group">
                                        <td class="p-4 text-xs font-medium text-gray-400 text-center">{{ $index + 1 }}</td>
                                        <td class="p-4 text-sm font-bold text-gray-900">{{ $sale['buyer_name'] }}</td>
                                        <td class="p-4 text-xs font-mono font-medium text-gray-500">{{ $sale['receipt_number'] }}</td>
                                        <td class="p-4 text-sm font-black text-gray-900 text-right">PHP {{ number_format($sale['price'], 2) }}</td>
                                        <td class="p-4 text-center">
                                            <button wire:click="removeRow({{ $index }})" class="text-gray-300 hover:text-red-500 bg-white group-hover:bg-red-50 p-2 rounded-lg border border-transparent group-hover:border-red-100 transition-colors">
                                                <svg class="w-4 h-4 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="p-16 text-center">
                                            <svg class="w-12 h-12 text-gray-200 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                                            <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Register is empty. Begin scanning.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- The Commit Bar --}}
                    <div class="p-5 sm:p-6 border-t border-gray-100 bg-gray-50 flex flex-col sm:flex-row justify-between items-center gap-4 shrink-0">
                        <div class="flex flex-col text-center sm:text-left">
                            <span class="text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-0.5">Total Batch Revenue</span>
                            <span class="text-2xl font-black text-gray-900">PHP {{ number_format($batchTotal, 2) }}</span>
                        </div>
                        <button wire:click="commitBatch" @if(empty($salesGrid)) disabled @endif class="w-full sm:w-auto bg-gray-900 text-white px-8 py-3.5 rounded-xl text-xs font-bold uppercase tracking-wider shadow-md hover:bg-green-600 transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                            Commit to Ledger
                        </button>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
