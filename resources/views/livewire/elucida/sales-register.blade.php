<div class="max-w-6xl mx-auto space-y-8 pb-24 p-6">

    <div class="bg-white border-4 border-iba-black shadow-[8px_8px_0_0_#0095AC] p-6 mb-8 flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-black uppercase tracking-widest text-iba-black">Sales Tracker Terminal</h1>
            <p class="text-sm font-bold text-gray-500 uppercase tracking-widest">Rapidly log batch event revenues into the active ledger.</p>
        </div>
        <div class="text-right">
            <span class="text-[10px] font-black uppercase text-gray-400 block mb-1">Target Ledger</span>
            <span class="bg-iba-black text-white px-3 py-1 font-bold text-xs uppercase">{{ $activeLedger->period_name }}</span>
        </div>
    </div>

    @if (session()->has('success'))
        <div class="bg-iba-teal/10 border-l-4 border-iba-teal p-4 shadow-[4px_4px_0_0_#131011] mb-8">
            <p class="text-xs font-black text-iba-teal uppercase tracking-widest">{{ session('success') }}</p>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">

        {{-- LEFT COLUMN: Batch Settings & Entry --}}
        <div class="lg:col-span-1 space-y-6">

            <div class="bg-gray-100 border-2 border-iba-black p-4">
                <h3 class="text-xs font-black text-gray-500 uppercase tracking-widest border-b-2 border-dashed border-gray-300 pb-2 mb-4">Batch Parameters</h3>

                <div class="space-y-4">
                    <div>
                        <label class="text-[10px] font-black uppercase text-iba-black block mb-1">Event / Source Name</label>
                        <input type="text" wire:model="transactionDescription" placeholder="e.g. IT DAYS - CHAROT READING" class="w-full border-2 border-iba-black p-2 font-bold focus:outline-none focus:border-iba-orange bg-white shadow-inner">
                    </div>
                    <div>
                        <label class="text-[10px] font-black uppercase text-iba-black block mb-1">Lock Default Price</label>
                        <input type="number" step="0.01" wire:model.live="defaultPrice" class="w-full border-2 border-iba-black p-2 font-bold focus:outline-none focus:border-iba-orange bg-white text-iba-orange">
                    </div>
                </div>
            </div>

            <div class="bg-iba-black border-4 border-iba-black shadow-[6px_6px_0_0_#FF8623] p-6 relative">
                <h3 class="text-lg font-black text-white uppercase tracking-widest mb-4">Rapid Entry</h3>

                <form wire:submit.prevent="pushToGrid" class="space-y-4">
                    <div>
                        <label class="text-[10px] font-black uppercase text-gray-400 block mb-1">Student / Buyer Name</label>
                        {{-- autofocus helps rapid continuous scanning --}}
                        <input type="text" wire:model="buyer_name" autofocus class="w-full border-2 border-iba-black p-2 font-bold focus:outline-none focus:border-iba-orange bg-white">
                        @error('buyer_name') <span class="text-xs text-iba-red font-bold block mt-1">{{ $message }}</span> @enderror
                    </div>
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label class="text-[10px] font-black uppercase text-gray-400 block mb-1">Receipt #</label>
                            <input type="text" wire:model="receipt_number" class="w-full border-2 border-iba-black p-2 font-bold focus:outline-none focus:border-iba-orange bg-gray-200">
                            @error('receipt_number') <span class="text-xs text-iba-red font-bold block mt-1">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="text-[10px] font-black uppercase text-gray-400 block mb-1">Price (PHP)</label>
                            <input type="number" step="0.01" wire:model="price" class="w-full border-2 border-iba-black p-2 font-black text-iba-orange focus:outline-none focus:border-iba-orange bg-white">
                        </div>
                    </div>
                    <button type="submit" class="w-full bg-iba-orange text-iba-black text-sm font-black uppercase tracking-widest py-3 border-2 border-transparent hover:bg-white hover:border-iba-black transition-all mt-4">
                        Push to Grid
                    </button>
                </form>
            </div>
        </div>

        {{-- RIGHT COLUMN: Grid Preview & Commitment --}}
        <div class="lg:col-span-3">
            <div class="bg-white border-4 border-iba-black shadow-[6px_6px_0_0_#131011] flex flex-col h-full">

                <div class="p-4 border-b-4 border-iba-black bg-gray-50 flex justify-between items-center">
                    <h2 class="text-sm font-black uppercase tracking-widest">Staged Register Buffer</h2>
                    <span class="text-[10px] font-bold text-gray-500 uppercase">{{ count($salesGrid) }} Entries Staged</span>
                </div>

                <div class="flex-1 overflow-y-auto max-h-[500px] bg-gray-50">
                    <table class="w-full text-left border-collapse">
                        <thead class="sticky top-0 bg-gray-200 border-b-2 border-iba-black">
                            <tr>
                                <th class="p-3 text-[10px] font-black uppercase text-gray-600 tracking-widest w-12 text-center">#</th>
                                <th class="p-3 text-[10px] font-black uppercase text-gray-600 tracking-widest">Buyer Name</th>
                                <th class="p-3 text-[10px] font-black uppercase text-gray-600 tracking-widest">Receipt No.</th>
                                <th class="p-3 text-[10px] font-black uppercase text-gray-600 tracking-widest text-right">Price</th>
                                <th class="p-3 w-12 text-center"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y-2 divide-gray-200">
                            @forelse($salesGrid as $index => $sale)
                                <tr class="hover:bg-white transition-colors">
                                    <td class="p-3 text-xs font-black text-gray-400 text-center">{{ $index + 1 }}</td>
                                    <td class="p-3 text-sm font-black uppercase text-iba-black">{{ $sale['buyer_name'] }}</td>
                                    <td class="p-3 text-xs font-mono font-bold text-gray-500">{{ $sale['receipt_number'] }}</td>
                                    <td class="p-3 text-sm font-black text-iba-teal text-right">PHP {{ number_format($sale['price'], 2) }}</td>
                                    <td class="p-3 text-center">
                                        <button wire:click="removeRow({{ $index }})" class="text-gray-400 hover:text-iba-red transition-colors">
                                            <svg class="w-4 h-4 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="p-12 text-center border-4 border-dashed border-gray-300">
                                        <p class="text-xs font-black text-gray-400 uppercase tracking-widest">Register is empty. Begin scanning receipts.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- The Commit Bar --}}
                <div class="p-4 border-t-4 border-iba-black bg-white flex justify-between items-center shrink-0">
                    <div class="flex flex-col">
                        <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Total Batch Revenue</span>
                        <span class="text-2xl font-black text-iba-teal">PHP {{ number_format($batchTotal, 2) }}</span>
                    </div>
                    <button wire:click="commitBatch" @if(empty($salesGrid)) disabled @endif class="bg-iba-teal text-white px-8 py-3 text-sm font-black uppercase tracking-widest border-2 border-iba-black shadow-[4px_4px_0_0_#131011] hover:translate-y-0.5 hover:shadow-none transition-all disabled:opacity-50 disabled:cursor-not-allowed">
                        Commit Batch to Ledger
                    </button>
                </div>

            </div>
        </div>
    </div>
</div>
