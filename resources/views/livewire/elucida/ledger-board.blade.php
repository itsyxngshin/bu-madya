<div class="min-h-screen bg-gray-100 p-4 md:p-6 font-sans text-gray-900 pb-24">
    <div class="max-w-5xl mx-auto space-y-6 pb-24">

        {{-- EDGE-TO-EDGE PREMIUM HEADER --}}
        <div class="relative bg-white rounded-3xl p-8 md:p-10 shadow-sm border border-gray-200 overflow-hidden group">
            <div class="absolute top-0 left-0 w-full h-2 bg-gradient-to-r from-red-600 via-yellow-400 to-green-500"></div>

            <div class="relative z-10 flex flex-col md:flex-row justify-between items-center text-center md:text-left gap-6">
                <div>
                    <h2 class="text-xs font-bold uppercase text-gray-500 tracking-widest mb-1">{{ $activeLedger->user ? $activeLedger->user->name : 'BU MADYA' }}</h2>
                    <h1 class="text-3xl font-black text-gray-900 mb-1">Financial Report</h1>
                    <p class="text-sm font-bold text-gray-600 uppercase">{{ $activeLedger->period_name }}</p>
                </div>

                <div class="flex flex-wrap justify-center sm:justify-end gap-3">
                    <button wire:click="openTransactionModal('inflow')" class="bg-green-50 hover:bg-green-100 text-green-700 border border-green-200 rounded-xl px-4 py-2.5 text-[10px] md:text-xs font-bold uppercase tracking-widest transition flex items-center gap-2">
                        + Log Inflow
                    </button>
                    <a href="{{ route('elucida.sales', $activeLedger) }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 border border-gray-200 rounded-xl px-4 py-2.5 text-[10px] md:text-xs font-bold uppercase tracking-widest transition flex items-center gap-2">
                        POS Tracker
                    </a>
                    <button wire:click="exportStatementDoc" class="bg-gray-900 hover:bg-red-600 text-white rounded-xl px-4 py-2.5 text-[10px] md:text-xs font-bold uppercase tracking-widest shadow-md transition flex items-center gap-2 w-full sm:w-auto justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        Export DOCX
                    </button>
                </div>
            </div>
        </div>

        @if (session()->has('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl text-sm font-bold shadow-sm">
                {{ session('success') }}
            </div>
        @endif

        {{-- MAIN DATA CARDS --}}

        {{-- I. Beginning Balance --}}
        <div class="bg-white rounded-3xl p-6 md:p-8 shadow-sm border border-gray-200">
            <div class="flex justify-between items-center mb-6 border-b border-gray-100 pb-4">
                <h3 class="text-lg font-black text-gray-900 uppercase">I. Beginning Balance</h3>
                <span class="text-lg font-black text-gray-900">PHP {{ number_format($activeLedger->beginning_bank + $activeLedger->beginning_cash, 2) }}</span>
            </div>
            <div class="space-y-3 pl-2 md:pl-4">
                <div class="flex justify-between text-sm font-bold text-gray-600">
                    <span>Cash in Bank</span>
                    <span>PHP {{ number_format($activeLedger->beginning_bank, 2) }}</span>
                </div>
                <div class="flex justify-between text-sm font-bold text-gray-600">
                    <span>Cash on Hand</span>
                    <span>PHP {{ number_format($activeLedger->beginning_cash, 2) }}</span>
                </div>
            </div>
        </div>

        {{-- II. Cash Inflows --}}
        <div class="bg-white rounded-3xl p-6 md:p-8 shadow-sm border border-gray-200 relative">
            <div class="flex justify-between items-center mb-6 border-b border-gray-100 pb-4">
                <h3 class="text-lg font-black text-gray-900 uppercase">II. Add: Cash Inflow</h3>
                <span class="text-lg font-black text-green-600">PHP {{ number_format($totalCashAvailable - ($activeLedger->beginning_bank + $activeLedger->beginning_cash), 2) }}</span>
            </div>

            @forelse($inflows as $category => $transactions)
                <div class="mb-6 last:mb-0">
                    <h4 class="text-[10px] sm:text-xs font-bold uppercase text-gray-400 tracking-widest mb-3 bg-gray-50 inline-block px-3 py-1 rounded-lg border border-gray-100">{{ $category }}</h4>
                    <div class="space-y-3 pl-1 sm:pl-4">
                        @foreach($transactions as $tx)
                            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center text-sm group hover:bg-gray-50 p-2 -mx-2 rounded-lg transition-colors gap-2 sm:gap-0">
                                <span class="font-medium text-gray-800 flex items-center gap-2">
                                    {{ $tx->description }}
                                    @if($tx->reference_code)
                                        <span class="bg-gray-100 border border-gray-200 text-gray-500 text-[9px] px-1.5 py-0.5 rounded font-bold uppercase tracking-wider">{{ $tx->reference_code }}</span>
                                    @endif
                                    @if($tx->receipt_image_path)
                                        <a href="{{ asset('storage/' . $tx->receipt_image_path) }}" target="_blank" class="text-blue-500 hover:text-blue-700 bg-blue-50 p-1 rounded-md" title="View Proof of Receipt">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path></svg>
                                        </a>
                                    @endif
                                </span>
                                <div class="flex items-center justify-between sm:justify-end gap-3 w-full sm:w-auto">
                                    <span class="font-bold text-gray-900">PHP {{ number_format($tx->amount, 2) }}</span>

                                    {{-- Edit/Delete Actions --}}
                                    <div x-data="{ open: false }" class="relative">
                                        <button @click="open = !open" @click.outside="open = false" class="opacity-100 sm:opacity-0 sm:group-hover:opacity-100 text-gray-400 hover:text-gray-900 bg-white border border-gray-200 hover:bg-gray-100 px-2 py-1 rounded-lg transition-all shadow-sm focus:outline-none">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path></svg>
                                        </button>
                                        <div x-show="open" style="display: none;" class="absolute right-0 top-full mt-1 w-28 bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden z-20">
                                            <button wire:click="editTransaction({{ $tx->id }})" class="w-full text-left px-4 py-2 text-xs font-bold text-gray-700 hover:bg-gray-50">Edit</button>
                                            <button onclick="confirm('Permanently delete this inflow?') || event.stopImmediatePropagation()" wire:click="deleteTransaction({{ $tx->id }})" class="w-full text-left px-4 py-2 text-xs font-bold text-red-600 hover:bg-red-50 border-t border-gray-50">Delete</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @empty
                <div class="p-6 bg-gray-50 rounded-2xl text-center border border-dashed border-gray-200">
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">No inflows recorded.</p>
                </div>
            @endforelse
        </div>

        {{-- III. Total Cash Available (Accent Banner) --}}
        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-3xl p-6 shadow-md flex justify-between items-center text-white">
            <h3 class="text-base md:text-xl font-black uppercase tracking-wide">III. Total Cash Available (I + II)</h3>
            <span class="text-xl md:text-2xl font-black">PHP {{ number_format($totalCashAvailable, 2) }}</span>
        </div>

        {{-- IV. Expenses --}}
        <div class="bg-white rounded-3xl p-6 md:p-8 shadow-sm border border-gray-200 relative">
            <div class="flex flex-col sm:flex-row justify-between sm:items-center mb-6 border-b border-gray-100 pb-4 gap-2">
                <h3 class="text-lg font-black text-gray-900 uppercase">IV. Less: Expenses</h3>
                <div class="flex items-center gap-3">
                    <button wire:click="openTransactionModal('outflow')" class="text-[10px] font-bold uppercase tracking-widest text-red-600 bg-red-50 hover:bg-red-100 px-3 py-1.5 rounded-lg transition-colors border border-red-100">
                        + Add Expense
                    </button>
                    <span class="text-lg font-black text-red-600">PHP {{ number_format($totalExpenses, 2) }}</span>
                </div>
            </div>

            @forelse($outflows as $category => $transactions)
                <div class="mb-6 last:mb-0">
                    <h4 class="text-[10px] sm:text-xs font-bold uppercase text-gray-400 tracking-widest mb-3 bg-gray-50 inline-block px-3 py-1 rounded-lg border border-gray-100">{{ $category }}</h4>
                    <div class="space-y-3 pl-1 sm:pl-4">
                        @foreach($transactions as $tx)
                            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center text-sm group hover:bg-gray-50 p-2 -mx-2 rounded-lg transition-colors gap-2 sm:gap-0">
                                <span class="font-medium text-gray-800 flex items-center gap-2">
                                    {{ $tx->description }}
                                    @if($tx->reference_code)
                                        <span class="bg-gray-100 border border-gray-200 text-gray-500 text-[9px] px-1.5 py-0.5 rounded font-bold uppercase tracking-wider">{{ $tx->reference_code }}</span>
                                    @endif
                                </span>
                                <div class="flex items-center justify-between sm:justify-end gap-3 w-full sm:w-auto">
                                    <span class="font-bold text-gray-900">PHP {{ number_format($tx->amount, 2) }}</span>

                                    {{-- Edit/Delete Actions --}}
                                    <div x-data="{ open: false }" class="relative">
                                        <button @click="open = !open" @click.outside="open = false" class="opacity-100 sm:opacity-0 sm:group-hover:opacity-100 text-gray-400 hover:text-gray-900 bg-white border border-gray-200 hover:bg-gray-100 px-2 py-1 rounded-lg transition-all shadow-sm focus:outline-none">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path></svg>
                                        </button>
                                        <div x-show="open" style="display: none;" class="absolute right-0 top-full mt-1 w-28 bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden z-20">
                                            <button wire:click="editTransaction({{ $tx->id }})" class="w-full text-left px-4 py-2 text-xs font-bold text-gray-700 hover:bg-gray-50">Edit</button>
                                            <button onclick="confirm('Permanently delete this expense?') || event.stopImmediatePropagation()" wire:click="deleteTransaction({{ $tx->id }})" class="w-full text-left px-4 py-2 text-xs font-bold text-red-600 hover:bg-red-50 border-t border-gray-50">Delete</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @empty
                <div class="p-6 bg-gray-50 rounded-2xl text-center border border-dashed border-gray-200">
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">No expenses recorded.</p>
                </div>
            @endforelse
        </div>

        {{-- V. Ending Balance (Dark Banner) --}}
        <div class="bg-gray-900 rounded-3xl p-6 md:p-8 shadow-xl flex justify-between items-center text-white relative overflow-hidden">
            <div class="absolute top-0 right-0 w-32 h-32 bg-red-600/20 rounded-full blur-2xl -mt-10 -mr-10 pointer-events-none"></div>
            <h3 class="text-base md:text-xl font-black uppercase text-gray-300 tracking-wide relative z-10">V. Ending Balance (III - IV)</h3>
            <span class="text-2xl md:text-3xl font-black text-white relative z-10">PHP {{ number_format($endingBalance, 2) }}</span>
        </div>

        {{-- Signatory Manager Component Injection --}}
        <div class="mt-8">
            <livewire:elucida.signatory-manager :targetModel="$activeLedger" />
        </div>

    </div>

    {{-- UNIFIED TRANSACTION MODAL (Create/Edit, Inflow/Outflow) --}}
    @if($showTransactionModal)
        <div class="fixed inset-0 z-[100] flex items-center justify-center p-4 sm:p-6">
            <div class="fixed inset-0 bg-gray-900/80 backdrop-blur-sm transition-opacity" wire:click="$set('showTransactionModal', false)"></div>

            <div class="relative bg-white rounded-3xl shadow-2xl w-full max-w-md flex flex-col overflow-hidden transform transition-all max-h-screen">
                <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r {{ $transactionType === 'inflow' ? 'from-green-400 to-green-600' : 'from-red-400 to-red-600' }}"></div>

                <div class="flex items-center justify-between p-6 border-b border-gray-100 bg-white">
                    <div>
                        <h3 class="text-xl font-black text-gray-900 leading-tight">
                            {{ $editingTransactionId ? 'Edit' : 'Log' }} {{ ucfirst($transactionType) }}
                        </h3>
                        <p class="text-xs font-bold text-gray-400 mt-1 uppercase tracking-widest">Update ledger entry</p>
                    </div>
                    <button wire:click="$set('showTransactionModal', false)" class="p-2 text-gray-400 hover:text-red-500 hover:bg-red-50 rounded-xl transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <form wire:submit.prevent="saveTransaction" class="p-6 bg-gray-50 space-y-4 overflow-y-auto custom-scrollbar">
                    <div>
                        <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1.5">Category</label>
                        <select wire:model="tx_category" class="w-full border-gray-200 rounded-xl text-sm font-bold shadow-sm focus:ring-2 {{ $transactionType === 'inflow' ? 'focus:ring-green-500 focus:border-green-500' : 'focus:ring-red-500 focus:border-red-500' }}">
                            @foreach($transactionType === 'inflow' ? $inflowCategories : $outflowCategories as $cat)
                                <option value="{{ $cat }}">{{ $cat }}</option>
                            @endforeach
                            <option value="Custom">Custom (Type below)</option>
                        </select>
                        @if($tx_category === 'Custom')
                            <input type="text" wire:model="tx_category" placeholder="Custom category..." class="w-full mt-2 border-gray-200 rounded-xl text-sm font-bold shadow-sm focus:ring-2 {{ $transactionType === 'inflow' ? 'focus:ring-green-500 focus:border-green-500' : 'focus:ring-red-500 focus:border-red-500' }}">
                        @endif
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1.5">Description (Particulars)</label>
                        <input type="text" wire:model="tx_description" class="w-full border-gray-200 rounded-xl text-sm font-bold shadow-sm focus:ring-2 {{ $transactionType === 'inflow' ? 'focus:ring-green-500 focus:border-green-500' : 'focus:ring-red-500 focus:border-red-500' }}">
                        @error('tx_description') <span class="text-xs text-red-500 font-bold block mt-1.5">{{ $message }}</span> @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1.5">Ref. Code (Optional)</label>
                            <input type="text" wire:model="tx_reference" placeholder="e.g. Note 3 / LR014" class="w-full border-gray-200 rounded-xl text-sm font-mono shadow-sm focus:ring-2 {{ $transactionType === 'inflow' ? 'focus:ring-green-500 focus:border-green-500' : 'focus:ring-red-500 focus:border-red-500' }}">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1.5">Amount (PHP)</label>
                            <input type="number" step="0.01" wire:model="tx_amount" class="w-full border-gray-200 rounded-xl text-sm font-black shadow-sm text-right focus:ring-2 {{ $transactionType === 'inflow' ? 'text-green-600 focus:ring-green-500 focus:border-green-500' : 'text-red-600 focus:ring-red-500 focus:border-red-500' }}">
                        </div>
                    </div>

                    {{-- Inflow Proof Uploader (Only visible if the transaction type is inflow) --}}
                    @if($transactionType === 'inflow')
                        <div class="mt-4 border-t border-gray-200 pt-4">
                            <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1.5">
                                Proof of Transaction <span class="text-red-500">*</span>
                            </label>

                            @if($existing_receipt_path && !$tx_receipt_image)
                                <div class="mb-3 relative rounded-xl border border-gray-200 overflow-hidden bg-gray-50">
                                    <img src="{{ asset('storage/' . $existing_receipt_path) }}" class="w-full h-32 object-cover opacity-75">
                                    <div class="absolute inset-0 flex items-center justify-center">
                                        <span class="bg-gray-900/80 text-white text-[10px] font-bold uppercase px-3 py-1 rounded-lg backdrop-blur-sm">Current File on Record</span>
                                    </div>
                                </div>
                            @endif

                            <div x-data="{ isUploading: false, progress: 0 }"
                                 x-on:livewire-upload-start="isUploading = true"
                                 x-on:livewire-upload-finish="isUploading = false"
                                 x-on:livewire-upload-error="isUploading = false"
                                 x-on:livewire-upload-progress="progress = $event.detail.progress">

                                <input type="file" wire:model="tx_receipt_image" accept="image/*" class="block w-full text-sm text-gray-600 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-[10px] file:font-bold file:uppercase file:tracking-wider file:bg-gray-100 file:text-gray-700 hover:file:bg-gray-200 transition cursor-pointer bg-white border border-gray-200 rounded-2xl p-1.5 shadow-sm focus:ring-2 focus:ring-green-500">
                                @error('tx_receipt_image') <span class="text-xs text-red-500 font-bold block mt-1.5">{{ $message }}</span> @enderror

                                <div x-show="isUploading" style="display: none;" class="mt-3 h-1.5 w-full bg-gray-100 rounded-full overflow-hidden">
                                    <div class="h-1.5 rounded-full bg-green-500" x-bind:style="'width: ' + progress + '%'"></div>
                                </div>

                                @if($tx_receipt_image)
                                    <div class="mt-2 text-[10px] font-bold text-green-600 uppercase tracking-widest flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                        File staged for upload
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif

                    <button type="submit" class="w-full mt-4 bg-gray-900 text-white font-bold rounded-xl py-3 shadow-md transition flex justify-center items-center gap-2 {{ $transactionType === 'inflow' ? 'hover:bg-green-600' : 'hover:bg-red-600' }}">
                        {{ $editingTransactionId ? 'Save Changes' : 'Commit to Ledger' }}
                    </button>
                </form>
            </div>
        </div>
    @endif
</div>
