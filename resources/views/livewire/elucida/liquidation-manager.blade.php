<div class="min-h-screen bg-gray-100 p-4 md:p-6 font-sans text-gray-900 pb-24">
    <div class="max-w-7xl mx-auto space-y-6">

        {{-- HEADER BANNER --}}
        <div class="bg-white rounded-3xl p-6 md:p-8 shadow-sm border border-gray-200 relative overflow-hidden flex flex-col md:flex-row justify-between items-center gap-4">
            <div class="absolute top-0 left-0 w-2 h-full bg-gradient-to-b from-red-600 to-orange-500"></div>
            <div>
                <h1 class="text-2xl md:text-3xl font-black text-gray-900 leading-tight">Liquidation Terminal</h1>
                <p class="text-xs font-bold text-gray-500 uppercase tracking-widest mt-1">Process cash advances & attach receipts</p>
            </div>
        </div>

        @if (session()->has('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl text-sm font-bold shadow-sm">
                {{ session('success') }}
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- LEFT COLUMN: Report Details & DV Info --}}
            <div class="lg:col-span-1 space-y-6">

                {{-- Details Card --}}
                <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-200">
                    <div class="flex justify-between items-center border-b border-gray-100 pb-4 mb-6">
                        <h2 class="text-lg font-black text-gray-900 uppercase">Report Details</h2>
                        <button type="button" onclick="printLiquidationReport()" class="bg-gray-100 hover:bg-red-50 text-gray-600 hover:text-red-600 px-3 py-1.5 rounded-lg text-[10px] font-bold uppercase tracking-wider transition-colors flex items-center gap-1.5 border border-gray-200">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                            Save PDF
                        </button>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1.5">Target Transaction</label>
                            <select wire:model="selectedTransactionId" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm font-medium focus:ring-red-500 focus:border-red-500 shadow-sm cursor-pointer">
                                <option value="">-- Select Transaction --</option>
                                @foreach($transactions as $tx)
                                    <option value="{{ $tx->id }}">{{ $tx->description }} (PHP {{ number_format($tx->amount, 2) }})</option>
                                @endforeach
                            </select>
                            @error('selectedTransactionId') <span class="text-xs text-red-500 font-bold block mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1.5">Tracker Number</label>
                            <input type="text" wire:model="tracker_number" readonly class="w-full bg-gray-100 border border-gray-200 text-gray-500 rounded-xl px-4 py-2.5 text-sm font-bold shadow-sm cursor-not-allowed">
                        </div>

                        <div>
                            <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1.5">Disbursement Voucher No.</label>
                            <input type="text" wire:model="dv_number" placeholder="e.g. MADYA-DV-2026-016" class="w-full bg-white border border-gray-200 rounded-xl px-4 py-2.5 text-sm font-medium focus:ring-red-500 focus:border-red-500 shadow-sm">
                        </div>

                        <div>
                            <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1.5">Accountable Officer</label>
                            <input type="text" wire:model="accountable_officer" placeholder="Name" class="w-full bg-white border border-gray-200 rounded-xl px-4 py-2.5 text-sm font-medium focus:ring-red-500 focus:border-red-500 shadow-sm mb-2">
                            <input type="text" wire:model="officer_position" placeholder="Position (e.g. Business Manager)" class="w-full bg-white border border-gray-200 rounded-xl px-4 py-2.5 text-sm font-medium focus:ring-red-500 focus:border-red-500 shadow-sm">
                        </div>

                        <div>
                            <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1.5">Cash Advance (PHP)</label>
                            <input type="number" step="0.01" wire:model.live="cash_advance" class="w-full bg-white border border-gray-200 rounded-xl px-4 py-2.5 text-base font-black text-red-600 focus:ring-red-500 focus:border-red-500 shadow-sm text-right">
                        </div>
                    </div>
                </div>

                {{-- Summary Panel --}}
                <div class="bg-gray-900 rounded-3xl p-6 shadow-lg text-white relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-red-600/20 rounded-full blur-2xl -mt-10 -mr-10 pointer-events-none"></div>

                    <h3 class="text-xs font-bold uppercase tracking-widest text-gray-400 mb-4 relative z-10">Liquidation Summary</h3>

                    <div class="space-y-3 relative z-10 pl-2">
                        <div class="flex justify-between items-center text-sm">
                            <span class="font-medium text-gray-300">Cash Advance:</span>
                            <span class="font-bold">PHP {{ number_format((float)$cash_advance, 2) }}</span>
                        </div>
                        <div class="flex justify-between items-center text-sm">
                            <span class="font-medium text-gray-300">Total Spent:</span>
                            <span class="font-bold">PHP {{ number_format((float)$totalExpenses, 2) }}</span>
                        </div>
                        <div class="flex justify-between items-center border-t border-gray-700 pt-3 mt-3">
                            <span class="text-xs font-bold uppercase tracking-widest text-gray-400">Excess (Short):</span>
                            <span class="text-lg font-black {{ $excessShort < 0 ? 'text-red-400' : 'text-green-400' }}">
                                PHP {{ number_format((float)$excessShort, 2) }}
                            </span>
                        </div>
                    </div>

                    <button wire:click="saveLiquidation" class="w-full bg-red-600 text-white text-xs font-bold uppercase tracking-wider py-3.5 rounded-xl hover:bg-red-700 transition-colors mt-6 shadow-md relative z-10">
                        Establish Report
                    </button>
                </div>
            </div>

            {{-- RIGHT COLUMN: Receipts & Itemization --}}
            <div class="lg:col-span-2 space-y-6">

                {{-- Add New Receipt Form --}}
                <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-200">
                    <h2 class="text-sm font-bold uppercase tracking-widest text-gray-500 border-b border-gray-100 pb-3 mb-5">Attach Receipt / Invoice</h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1.5">Expense Category</label>
                            <input type="text" wire:model="newItem.category" placeholder="e.g. Supplies and Materials" class="w-full bg-white border border-gray-200 rounded-xl px-4 py-2.5 text-sm font-medium focus:ring-red-500 focus:border-red-500 shadow-sm">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1.5">Payee / Merchant</label>
                            <input type="text" wire:model="newItem.payee" placeholder="e.g. Baytown Commercial" class="w-full bg-white border border-gray-200 rounded-xl px-4 py-2.5 text-sm font-medium focus:ring-red-500 focus:border-red-500 shadow-sm">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1.5">Particulars (Item Description)</label>
                            <input type="text" wire:model="newItem.particulars" placeholder="e.g. Bond Paper - 1 Ream" class="w-full bg-white border border-gray-200 rounded-xl px-4 py-2.5 text-sm font-medium focus:ring-red-500 focus:border-red-500 shadow-sm">
                        </div>
                        <div class="grid grid-cols-2 gap-4 md:col-span-2">
                            <div>
                                <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1.5">OR / SI #</label>
                                <input type="text" wire:model="newItem.or_si_number" class="w-full bg-white border border-gray-200 rounded-xl px-4 py-2.5 text-sm font-medium focus:ring-red-500 focus:border-red-500 shadow-sm">
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1.5">Amount (PHP)</label>
                                <input type="number" step="0.01" wire:model="newItem.amount" class="w-full bg-white border border-gray-200 rounded-xl px-4 py-2.5 text-base font-black text-red-600 focus:ring-red-500 focus:border-red-500 shadow-sm text-right">
                            </div>
                        </div>
                        <div class="md:col-span-2 mt-2">
                            <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1.5">Upload Receipt (Image)</label>
                            <div x-data="{ isUploading: false, progress: 0 }"
                                 x-on:livewire-upload-start="isUploading = true"
                                 x-on:livewire-upload-finish="isUploading = false"
                                 x-on:livewire-upload-error="isUploading = false"
                                 x-on:livewire-upload-progress="progress = $event.detail.progress">

                                <input type="file" wire:model="newItem.receipt_image" accept="image/*" class="block w-full text-sm text-gray-600 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:uppercase file:tracking-wider file:bg-gray-100 file:text-gray-700 hover:file:bg-gray-200 transition cursor-pointer bg-gray-50 border border-gray-200 rounded-2xl p-1.5 shadow-sm">

                                <!-- Progress Bar -->
                                <div x-show="isUploading" class="mt-3 h-1.5 w-full bg-gray-100 rounded-full overflow-hidden">
                                    <div class="h-1.5 bg-red-500 rounded-full" x-bind:style="'width: ' + progress + '%'"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <button wire:click="addItem" class="mt-6 w-full md:w-auto bg-gray-900 text-white font-bold text-xs uppercase tracking-wider px-6 py-3 rounded-xl hover:bg-gray-800 transition-colors shadow-sm">
                        + Add to Ledger
                    </button>
                </div>

                {{-- Itemized Ledger Display --}}
                <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-200">
                    <div class="flex justify-between items-center border-b border-gray-100 pb-3 mb-5">
                        <h3 class="text-sm font-bold uppercase tracking-widest text-gray-500">Itemized Disbursements</h3>
                        <span class="text-[10px] font-bold text-gray-400 bg-gray-100 px-2 py-1 rounded-md">{{ count($items) }} Items</span>
                    </div>

                    <div class="space-y-3">
                        @forelse($items as $index => $item)
                            <div class="item-row bg-gray-50 border border-gray-100 rounded-2xl p-4 flex flex-col sm:flex-row justify-between sm:items-center gap-4 hover:border-gray-200 transition-colors">
                                <div class="flex items-start sm:items-center gap-4">
                                    @if(!empty($item['receipt_image_path']))
                                        <div class="w-14 h-14 rounded-xl bg-white border border-gray-200 overflow-hidden flex items-center justify-center shrink-0 shadow-sm p-1">
                                            <img src="{{ $item['receipt_image_path']->temporaryUrl() }}" class="object-cover w-full h-full rounded-lg">
                                        </div>
                                    @else
                                        <div class="w-14 h-14 rounded-xl bg-white border border-dashed border-gray-300 flex items-center justify-center text-[8px] font-bold uppercase text-gray-400 text-center shrink-0">No Img</div>
                                    @endif
                                    <div>
                                        <h4 class="item-particulars text-sm font-bold text-gray-900 leading-tight mb-1">{{ $item['particulars'] }}</h4>
                                        <p class="text-[10px] font-bold text-gray-500 uppercase tracking-wider"><span class="item-payee">{{ $item['payee'] }}</span> • OR: <span class="item-or font-mono">{{ $item['or_si_number'] }}</span></p>
                                    </div>
                                </div>
                                <div class="flex items-center justify-between sm:justify-end w-full sm:w-auto gap-4 pl-18 sm:pl-0">
                                    <span class="item-amount text-lg font-black text-gray-900">PHP {{ number_format((float)$item['amount'], 2) }}</span>
                                    <button wire:click="removeItem({{ $index }})" class="text-gray-400 hover:text-red-500 bg-white hover:bg-red-50 p-2 rounded-lg border border-gray-200 hover:border-red-200 transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </div>
                            </div>
                        @empty
                            <div class="border-2 border-dashed border-gray-200 rounded-2xl p-10 text-center bg-gray-50">
                                <svg class="w-10 h-10 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">No receipts attached yet.</p>
                            </div>
                        @endforelse
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

{{-- CLIENT-SIDE PDF ENGINE FOR LIQUIDATION REPORTS --}}
<script>
    function printLiquidationReport() {
        const printWindow = window.open('', '_blank', 'height=800,width=800');
        const trackerNo = document.querySelector('input[wire\\:model="tracker_number"]').value || 'N/A';
        const dvNo = document.querySelector('input[wire\\:model="dv_number"]').value || 'N/A';
        const officerName = document.querySelector('input[wire\\:model="accountable_officer"]').value || 'N/A';
        const officerPos = document.querySelector('input[wire\\:model="officer_position"]').value || 'N/A';
        const advance = document.querySelector('input[wire\\:model\\.live="cash_advance"]').value || '0.00';

        const selectEl = document.querySelector('select[wire\\:model="selectedTransactionId"]');
        const txDesc = selectEl.options[selectEl.selectedIndex]?.text || 'N/A';

        let itemsHtml = '';
        let totalExpenses = 0;
        document.querySelectorAll('.item-row').forEach(row => {
            const part = row.querySelector('.item-particulars').innerText;
            const payee = row.querySelector('.item-payee').innerText;
            const or = row.querySelector('.item-or').innerText;
            const amt = parseFloat(row.querySelector('.item-amount').innerText.replace(/[^0-9.-]+/g,""));
            totalExpenses += amt;

            itemsHtml += `
                <tr>
                    <td style="border: 1px solid #d1d5db; padding: 10px;">${part}</td>
                    <td style="border: 1px solid #d1d5db; padding: 10px;">${payee}</td>
                    <td style="border: 1px solid #d1d5db; padding: 10px; font-family: monospace;">${or}</td>
                    <td style="border: 1px solid #d1d5db; padding: 10px; text-align: right;">${amt.toFixed(2)}</td>
                </tr>
            `;
        });

        if(itemsHtml === '') {
            itemsHtml = '<tr><td colspan="4" style="border: 1px solid #d1d5db; padding: 20px; text-align: center; font-style: italic; color: #6b7280;">No receipts attached</td></tr>';
        }

        const excessShort = parseFloat(advance) - totalExpenses;

        printWindow.document.write(`
            <html>
                <head>
                    <title>Liquidation Report - ${trackerNo}</title>
                    <style>
                        @media print {
                            @page { size: portrait; margin: 15mm; }
                            body { -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important; }
                        }
                        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 10pt; line-height: 1.5; color: #111827; }
                        table { width: 100%; border-collapse: collapse; margin-bottom: 25px; }
                        th, td { border: 1px solid #d1d5db; padding: 10px; }
                        th { background-color: #f9fafb; text-align: left; font-weight: bold; color: #374151; font-size: 9pt; text-transform: uppercase; }
                        .header { text-align: center; margin-bottom: 35px; border-bottom: 2px solid #111827; padding-bottom: 15px; }
                        .meta-grid { display: grid; grid-template-columns: 160px 1fr; gap: 8px; margin-bottom: 30px; font-size: 10pt; }
                        .meta-label { font-weight: bold; color: #4b5563; }
                        .signature-block { margin-top: 50px; display: flex; justify-content: space-between; }
                        .sig-line { width: 250px; border-top: 1px solid #000; text-align: center; padding-top: 5px; font-weight: bold; }
                    </style>
                </head>
                <body>
                    <div class="header">
                        <h2 style="margin:0; font-size: 18pt; text-transform: uppercase; letter-spacing: 1px;">Liquidation Report</h2>
                        <p style="margin:5px 0 0 0; font-size: 11pt; color: #4b5563;">${txDesc}</p>
                    </div>

                    <div class="meta-grid">
                        <div class="meta-label">ACCOUNTABLE OFFICER:</div><div>${officerName} (${officerPos})</div>
                        <div class="meta-label">CASH ADVANCE:</div><div style="font-weight: bold;">PHP ${parseFloat(advance).toFixed(2)}</div>
                        <div class="meta-label">REF. DV NO.:</div><div style="font-family: monospace;">${dvNo}</div>
                        <div class="meta-label">TRACKER NO.:</div><div style="font-family: monospace;">${trackerNo}</div>
                        <div class="meta-label">REPORT DATE:</div><div>${new Date().toLocaleDateString()}</div>
                    </div>

                    <h4 style="font-size: 11pt; margin-bottom: 10px; color: #111827;">I. BREAKDOWN OF FUND USAGE</h4>
                    <table>
                        <thead>
                            <tr>
                                <th>Particulars (Item Description)</th>
                                <th>Payee / Merchant</th>
                                <th>OR / SI #</th>
                                <th style="text-align: right;">Amount (PHP)</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${itemsHtml}
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="3" style="text-align: right; background-color: #f3f4f6;">TOTAL SPENT</th>
                                <th style="text-align: right; background-color: #f3f4f6; font-size: 11pt;">PHP ${totalExpenses.toFixed(2)}</th>
                            </tr>
                            <tr>
                                <th colspan="3" style="text-align: right; background-color: #f9fafb;">EXCESS (SHORT)</th>
                                <th style="text-align: right; background-color: #f9fafb; font-size: 11pt; color: ${excessShort < 0 ? '#dc2626' : '#16a34a'}">PHP ${excessShort.toFixed(2)}</th>
                            </tr>
                        </tfoot>
                    </table>

                    <div class="signature-block">
                        <div class="sig-line">
                            ${officerName}<br>
                            <span style="font-size: 8pt; font-weight: normal; color: #6b7280;">Accountable Officer</span>
                        </div>
                        <div class="sig-line">
                            <br>
                            <span style="font-size: 8pt; font-weight: normal; color: #6b7280;">Date Received</span>
                        </div>
                    </div>
                </body>
            </html>
        `);

        printWindow.document.close();
        printWindow.focus();

        setTimeout(() => {
            printWindow.print();
            printWindow.close();
        }, 1000);
    }
</script>
