<div class="max-w-7xl mx-auto space-y-8 pb-24 p-6">
    <div class="bg-iba-black border-4 border-iba-black shadow-[8px_8px_0_0_#FF8623] p-6 text-white mb-8">
        <h1 class="text-2xl font-black uppercase tracking-widest text-white">Liquidation Terminal</h1>
        <p class="text-sm font-bold text-gray-400 uppercase tracking-widest">Process cash advances, attach receipts, and forge disbursement vouchers.</p>
    </div>

    @if (session()->has('success'))
        <div class="bg-iba-teal/10 border-l-4 border-iba-teal p-4 mb-8 shadow-[4px_4px_0_0_#131011]">
            <p class="text-xs font-black text-iba-teal uppercase tracking-widest">{{ session('success') }}</p>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        {{-- LEFT COLUMN: Report Details & DV Info --}}
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-white border-4 border-iba-black shadow-[6px_6px_0_0_#131011] p-6">
                <h2 class="text-lg font-black uppercase border-b-2 border-iba-black pb-2 mb-4">Report Details</h2>

                <div class="space-y-4">
                    <div>
                        <label class="text-[10px] font-black uppercase text-gray-500 block mb-1">Target Event / Transaction</label>
                        <select wire:model="selectedTransactionId" class="w-full border-2 border-iba-black p-2 font-bold focus:outline-none focus:border-iba-orange bg-gray-50">
                            <option value="">-- Select Transaction --</option>
                            @foreach($transactions as $tx)
                                <option value="{{ $tx->id }}">{{ $tx->description }} (PHP {{ number_format($tx->amount, 2) }})</option>
                            @endforeach
                        </select>
                        @error('selectedTransactionId') <span class="text-xs text-iba-red font-bold block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="text-[10px] font-black uppercase text-gray-500 block mb-1">Tracker Number</label>
                        <input type="text" wire:model="tracker_number" readonly class="w-full border-2 border-iba-black p-2 font-bold bg-gray-200 text-gray-600">
                    </div>

                    <div>
                        <label class="text-[10px] font-black uppercase text-gray-500 block mb-1">Disbursement Voucher No.</label>
                        <input type="text" wire:model="dv_number" placeholder="e.g. CircUITS-DV-2026-016" class="w-full border-2 border-iba-black p-2 font-bold focus:outline-none focus:border-iba-orange bg-gray-50">
                    </div>

                    <div>
                        <label class="text-[10px] font-black uppercase text-gray-500 block mb-1">Accountable Officer</label>
                        <input type="text" wire:model="accountable_officer" placeholder="Name" class="w-full border-2 border-iba-black p-2 font-bold focus:outline-none focus:border-iba-orange bg-gray-50 mb-2">
                        <input type="text" wire:model="officer_position" placeholder="Position (e.g. Business Manager)" class="w-full border-2 border-iba-black p-2 font-bold focus:outline-none focus:border-iba-orange bg-gray-50">
                    </div>

                    <div>
                        <label class="text-[10px] font-black uppercase text-gray-500 block mb-1">Cash Advance (PHP)</label>
                        <input type="number" step="0.01" wire:model.live="cash_advance" class="w-full border-2 border-iba-black p-2 font-black focus:outline-none focus:border-iba-orange bg-white text-iba-orange">
                    </div>
                </div>
            </div>

            {{-- Summary Panel --}}
            <div class="bg-gray-100 border-4 border-iba-black p-6 shadow-[6px_6px_0_0_#131011]">
                <h3 class="text-sm font-black uppercase mb-4 text-iba-black">Liquidation Summary</h3>
                <div class="flex justify-between mb-2">
                    <span class="text-xs font-bold text-gray-600 uppercase">Cash Advance:</span>
                    <span class="text-sm font-black">PHP {{ number_format((float)$cash_advance, 2) }}</span>
                </div>
                <div class="flex justify-between mb-2">
                    <span class="text-xs font-bold text-gray-600 uppercase">Total Spent:</span>
                    <span class="text-sm font-black">PHP {{ number_format((float)$totalExpenses, 2) }}</span>
                </div>
                <div class="flex justify-between border-t-2 border-iba-black pt-2 mt-2">
                    <span class="text-xs font-black uppercase">Excess (Short):</span>
                    <span class="text-sm font-black {{ $excessShort < 0 ? 'text-iba-red' : 'text-iba-green' }}">
                        PHP {{ number_format((float)$excessShort, 2) }}
                    </span>
                </div>

                <button wire:click="saveLiquidation" class="w-full bg-iba-black text-iba-orange text-sm font-black uppercase tracking-widest py-4 border-2 border-transparent hover:bg-iba-orange hover:text-iba-black hover:border-iba-black transition-all mt-6">
                    Establish Report
                </button>
            </div>
        </div>

        {{-- RIGHT COLUMN: Receipts & Itemization --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- Add New Receipt Form --}}
            <div class="bg-white border-4 border-iba-black shadow-[6px_6px_0_0_#131011] p-6">
                <h2 class="text-lg font-black uppercase border-b-2 border-iba-orange pb-2 mb-4">Attach Receipt / Invoice</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="text-[10px] font-black uppercase text-gray-500 block mb-1">Expense Category</label>
                        <input type="text" wire:model="newItem.category" placeholder="e.g. Supplies and Materials" class="w-full border-2 border-iba-black p-2 font-bold focus:outline-none focus:border-iba-orange bg-gray-50">
                    </div>
                    <div>
                        <label class="text-[10px] font-black uppercase text-gray-500 block mb-1">Payee / Merchant</label>
                        <input type="text" wire:model="newItem.payee" placeholder="e.g. Baytown Commercial" class="w-full border-2 border-iba-black p-2 font-bold focus:outline-none focus:border-iba-orange bg-gray-50">
                    </div>
                    <div>
                        <label class="text-[10px] font-black uppercase text-gray-500 block mb-1">Particulars (Item Description)</label>
                        <input type="text" wire:model="newItem.particulars" placeholder="e.g. Bond Paper - 1 Ream" class="w-full border-2 border-iba-black p-2 font-bold focus:outline-none focus:border-iba-orange bg-gray-50">
                    </div>
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label class="text-[10px] font-black uppercase text-gray-500 block mb-1">OR / SI #</label>
                            <input type="text" wire:model="newItem.or_si_number" class="w-full border-2 border-iba-black p-2 font-bold focus:outline-none focus:border-iba-orange bg-gray-50">
                        </div>
                        <div>
                            <label class="text-[10px] font-black uppercase text-gray-500 block mb-1">Amount</label>
                            <input type="number" step="0.01" wire:model="newItem.amount" class="w-full border-2 border-iba-black p-2 font-black text-iba-orange focus:outline-none focus:border-iba-orange bg-white">
                        </div>
                    </div>
                    <div class="md:col-span-2">
                        <label class="text-[10px] font-black uppercase text-gray-500 block mb-1">Upload Receipt (Image)</label>
                        {{-- Uses Livewire's native upload progress --}}
                        <div x-data="{ isUploading: false, progress: 0 }"
                             x-on:livewire-upload-start="isUploading = true"
                             x-on:livewire-upload-finish="isUploading = false"
                             x-on:livewire-upload-error="isUploading = false"
                             x-on:livewire-upload-progress="progress = $event.detail.progress">

                            <input type="file" wire:model="newItem.receipt_image" accept="image/*" class="w-full border-2 border-dashed border-gray-400 p-4 text-xs font-bold bg-gray-50 hover:bg-gray-100 cursor-pointer">

                            <!-- Progress Bar -->
                            <div x-show="isUploading" class="mt-2 h-2 w-full bg-gray-200">
                                <div class="h-2 bg-iba-orange" x-bind:style="'width: ' + progress + '%'"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <button wire:click="addItem" class="mt-4 bg-gray-200 text-iba-black font-black uppercase text-xs px-4 py-2 border-2 border-iba-black hover:bg-gray-300">
                    + Add Item to Ledger
                </button>
            </div>

            {{-- Itemized Ledger Display --}}
            <div>
                <h3 class="text-sm font-black uppercase tracking-widest text-iba-black mb-4">Itemized Disbursements</h3>
                <div class="space-y-4">
                    @forelse($items as $index => $item)
                        <div class="bg-white border-2 border-iba-black p-4 flex justify-between items-center shadow-[4px_4px_0_0_#131011]">
                            <div class="flex items-center gap-4">
                                @if($item['receipt_image_path'])
                                    <div class="w-12 h-12 bg-gray-200 border border-gray-300 overflow-hidden flex items-center justify-center">
                                        <img src="{{ $item['receipt_image_path']->temporaryUrl() }}" class="object-cover w-full h-full">
                                    </div>
                                @else
                                    <div class="w-12 h-12 bg-gray-100 border border-dashed border-gray-300 flex items-center justify-center text-[8px] font-black uppercase text-gray-400 text-center">No Image</div>
                                @endif
                                <div>
                                    <h4 class="text-sm font-black uppercase">{{ $item['particulars'] }}</h4>
                                    <p class="text-[10px] font-bold text-gray-500 uppercase">{{ $item['payee'] }} • OR: {{ $item['or_si_number'] }}</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-4">
                                <span class="text-lg font-black text-iba-orange">PHP {{ number_format($item['amount'], 2) }}</span>
                                <button wire:click="removeItem({{ $index }})" class="text-iba-red hover:text-red-700">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </div>
                        </div>
                    @empty
                        <div class="border-4 border-dashed border-gray-300 p-8 text-center text-xs font-black uppercase text-gray-400 tracking-widest">
                            No receipts attached yet.
                        </div>
                    @endforelse
                </div>
            </div>

        </div>
    </div>
</div>
