<div class="min-h-screen bg-gray-100 p-4 md:p-6 font-sans text-gray-900 pb-24">
    <div class="max-w-5xl mx-auto space-y-6">

        {{-- HEADER --}}
        <div class="relative bg-white rounded-3xl p-8 shadow-sm border border-gray-200 overflow-hidden">
            <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-red-600 via-yellow-400 to-green-500"></div>
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 relative z-10">
                <div>
                    <a href="{{ route('elucida.board', $transaction->ledger_id) }}" class="text-[10px] font-bold uppercase tracking-widest text-gray-400 hover:text-red-600 transition flex items-center gap-1 mb-2">
                        ← Back to Ledger
                    </a>
                    <h1 class="text-2xl font-black text-gray-900 leading-tight">Liquidation Archives</h1>
                    <p class="text-sm font-bold text-gray-500 uppercase tracking-wide mt-1">Transaction: <span class="text-gray-900">{{ $transaction->description }}</span></p>
                </div>
                <div class="bg-red-50 text-red-700 px-4 py-2 rounded-xl text-sm font-black border border-red-100">
                    Outflow Shell: PHP {{ number_format($transaction->amount, 2) }}
                </div>
            </div>
        </div>

        @if (session()->has('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl text-sm font-bold shadow-sm">
                {{ session('success') }}
            </div>
        @endif

        {{-- LIQUIDATION REPORTS LIST --}}
        @forelse($liquidations as $liq)
            <div class="bg-white rounded-3xl shadow-sm border border-gray-200 overflow-hidden">
                {{-- LIQ HEADER --}}
                <div class="bg-gray-900 p-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 text-white">
                    <div>
                        <h2 class="text-lg font-black tracking-wide">{{ $liq->dv_number }}</h2>
                        <p class="text-[10px] font-bold uppercase tracking-widest text-gray-400 mt-1">Tracker: {{ $liq->lr_tracker_number }} • Officer: {{ $liq->accountable_officer }}</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <button wire:click="editLiquidation({{ $liq->id }})" class="bg-white/10 hover:bg-white/20 px-3 py-1.5 rounded-lg text-xs font-bold transition">Edit Details</button>
                        <button onclick="confirm('Permanently delete this entire report?') || event.stopImmediatePropagation()" wire:click="deleteLiquidation({{ $liq->id }})" class="bg-red-500/20 text-red-300 hover:bg-red-600 hover:text-white px-3 py-1.5 rounded-lg text-xs font-bold transition">Delete</button>
                    </div>
                </div>

                {{-- LIQ BODY (ITEMS) --}}
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-xs font-bold uppercase tracking-widest text-gray-500">Itemized Disbursements</h3>
                        <span class="text-xs font-bold uppercase tracking-widest text-gray-500">Cash Advance: PHP {{ number_format($liq->cash_advance, 2) }}</span>
                    </div>

                    <div class="space-y-3">
                        @php $items = $allItems->get($liq->id, collect([])); $totalItems = 0; @endphp

                        @forelse($items as $item)
                            @php $totalItems += $item->amount; @endphp
                            <div class="group bg-gray-50 border border-gray-100 rounded-2xl p-4 flex flex-col sm:flex-row justify-between sm:items-center gap-4 transition hover:border-gray-200">
                                <div class="flex items-center gap-4">
                                    @if($item->receipt_image_path)
                                        <a href="{{ asset('storage/' . $item->receipt_image_path) }}" target="_blank" class="w-12 h-12 rounded-xl bg-white border border-gray-200 overflow-hidden flex items-center justify-center shrink-0 shadow-sm p-1 hover:border-red-400 transition">
                                            <img src="{{ asset('storage/' . $item->receipt_image_path) }}" class="object-cover w-full h-full rounded-lg">
                                        </a>
                                    @else
                                        <div class="w-12 h-12 rounded-xl bg-white border border-dashed border-gray-300 flex items-center justify-center text-[8px] font-bold uppercase text-gray-400 text-center shrink-0">No Img</div>
                                    @endif
                                    <div>
                                        <h4 class="text-sm font-bold text-gray-900">{{ $item->particulars }} <span class="text-xs font-medium text-gray-500">(x{{ $item->qty }})</span></h4>
                                        <p class="text-[10px] font-bold text-gray-500 uppercase tracking-widest mt-0.5">{{ $item->payee }} • OR: {{ $item->receipt_number ?: 'N/A' }}</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-4">
                                    <span class="text-base font-black text-gray-900">PHP {{ number_format($item->amount, 2) }}</span>
                                    <div class="flex gap-1 opacity-100 sm:opacity-0 sm:group-hover:opacity-100 transition">
                                        <button wire:click="editItem({{ $item->id }})" class="p-1.5 bg-white border border-gray-200 rounded-lg text-gray-500 hover:text-blue-600 hover:bg-blue-50 transition"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg></button>
                                        <button onclick="confirm('Delete item?') || event.stopImmediatePropagation()" wire:click="deleteItem({{ $item->id }})" class="p-1.5 bg-white border border-gray-200 rounded-lg text-gray-500 hover:text-red-600 hover:bg-red-50 transition"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg></button>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-widest text-center py-4 border border-dashed border-gray-200 rounded-xl">No receipts logged.</p>
                        @endforelse
                    </div>

                    <div class="mt-4 pt-4 border-t border-gray-100 flex justify-end gap-6 text-sm">
                        <span class="font-bold text-gray-500 uppercase tracking-widest text-[10px]">Total Expenses: <span class="text-gray-900 text-sm">PHP {{ number_format($totalItems, 2) }}</span></span>
                        <span class="font-bold uppercase tracking-widest text-[10px] {{ ($liq->cash_advance - $totalItems) < 0 ? 'text-red-500' : 'text-green-600' }}">Balance: <span class="text-sm">PHP {{ number_format($liq->cash_advance - $totalItems, 2) }}</span></span>
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-white rounded-3xl p-10 text-center shadow-sm border border-gray-200">
                <p class="text-sm font-bold text-gray-400 uppercase tracking-widest">No liquidations have been filed for this transaction yet.</p>
            </div>
        @endforelse
    </div>

    {{-- MODAL: EDIT ITEM --}}
    @if($showItemModal)
        <div class="fixed inset-0 z-[100] flex items-center justify-center p-4">
            <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm" wire:click="$set('showItemModal', false)"></div>
            <form wire:submit.prevent="saveItem" class="relative bg-white rounded-3xl shadow-2xl w-full max-w-md p-6 z-10 space-y-4">
                <h3 class="text-xl font-black text-gray-900 border-b border-gray-100 pb-3">Edit Receipt</h3>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1">Particulars</label>
                        <input type="text" wire:model="item_particulars" class="w-full border-gray-200 rounded-xl text-sm font-bold focus:ring-red-500">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1">Amount</label>
                        <input type="number" step="0.01" wire:model="item_amount" class="w-full border-gray-200 rounded-xl text-sm font-black text-right focus:ring-red-500">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1">Payee</label>
                        <input type="text" wire:model="item_payee" class="w-full border-gray-200 rounded-xl text-sm font-bold focus:ring-red-500">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1">OR / SI No.</label>
                        <input type="text" wire:model="item_or" class="w-full border-gray-200 rounded-xl text-sm font-mono focus:ring-red-500">
                    </div>
                </div>

                <div>
                    <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1">Replace Image (Optional)</label>
                    <input type="file" wire:model="new_receipt_image" class="w-full border border-gray-200 rounded-xl p-1.5 text-xs text-gray-600 bg-gray-50">
                </div>

                <div class="flex gap-3 pt-2">
                    <button type="button" wire:click="$set('showItemModal', false)" class="flex-1 bg-gray-100 text-gray-700 font-bold py-2.5 rounded-xl text-sm hover:bg-gray-200 transition">Cancel</button>
                    <button type="submit" class="flex-1 bg-gray-900 text-white font-bold py-2.5 rounded-xl text-sm hover:bg-red-600 transition">Save Changes</button>
                </div>
            </form>
        </div>
    @endif

    {{-- MODAL: EDIT LIQUIDATION --}}
    @if($showLiquidationModal)
        <div class="fixed inset-0 z-[100] flex items-center justify-center p-4">
            <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm" wire:click="$set('showLiquidationModal', false)"></div>
            <form wire:submit.prevent="saveLiquidation" class="relative bg-white rounded-3xl shadow-2xl w-full max-w-md p-6 z-10 space-y-4">
                <h3 class="text-xl font-black text-gray-900 border-b border-gray-100 pb-3">Edit Report Meta</h3>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1">DV Number</label>
                        <input type="text" wire:model="dv_number" class="w-full border-gray-200 rounded-xl text-sm font-mono focus:ring-red-500">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1">Cash Advance</label>
                        <input type="number" step="0.01" wire:model="cash_advance" class="w-full border-gray-200 rounded-xl text-sm font-black text-right focus:ring-red-500">
                    </div>
                </div>

                <div>
                    <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1">Accountable Officer</label>
                    <input type="text" wire:model="accountable_officer" class="w-full border-gray-200 rounded-xl text-sm font-bold focus:ring-red-500">
                </div>

                <div class="flex gap-3 pt-2">
                    <button type="button" wire:click="$set('showLiquidationModal', false)" class="flex-1 bg-gray-100 text-gray-700 font-bold py-2.5 rounded-xl text-sm hover:bg-gray-200 transition">Cancel</button>
                    <button type="submit" class="flex-1 bg-gray-900 text-white font-bold py-2.5 rounded-xl text-sm hover:bg-red-600 transition">Save Updates</button>
                </div>
            </form>
        </div>
    @endif
</div>
