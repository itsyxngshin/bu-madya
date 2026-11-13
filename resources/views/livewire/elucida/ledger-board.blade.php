<div class="min-h-screen bg-gray-100 p-4 md:p-6 font-sans text-gray-900">
    <div class="max-w-5xl mx-auto space-y-6 pb-24">

        {{-- EDGE-TO-EDGE PREMIUM HEADER --}}
        <div class="relative bg-white rounded-3xl p-8 md:p-10 shadow-sm border border-gray-200 overflow-hidden group">
            {{-- Tricolor Sweep --}}
            <div class="absolute top-0 left-0 w-full h-2 bg-gradient-to-r from-red-600 via-yellow-400 to-green-500"></div>

            <div class="relative z-10 flex flex-col md:flex-row justify-between items-center text-center md:text-left gap-6">
                <div>
                    <h2 class="text-xs font-bold uppercase text-gray-500 tracking-widest mb-1">{{ $activeLedger->user ? $activeLedger->user->name : 'BU MADYA' }}</h2>
                    <h1 class="text-3xl font-black text-gray-900 mb-1">Financial Report</h1>
                    <p class="text-sm font-bold text-gray-600 uppercase">{{ $activeLedger->period_name }}</p>
                </div>

                <div class="flex gap-3">
                    <a href="{{ route('elucida.sales', $activeLedger) }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 border border-gray-200 rounded-xl px-5 py-2.5 text-[10px] md:text-xs font-bold uppercase tracking-widest transition flex items-center gap-2">
                        + Log Sales
                    </a>
                    <button wire:click="exportStatementDoc" class="bg-gray-900 hover:bg-red-600 text-white rounded-xl px-5 py-2.5 text-[10px] md:text-xs font-bold uppercase tracking-widest shadow-md transition flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        Export DOCX
                    </button>
                </div>
            </div>
        </div>

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
        <div class="bg-white rounded-3xl p-6 md:p-8 shadow-sm border border-gray-200">
            <div class="flex justify-between items-center mb-6 border-b border-gray-100 pb-4">
                <h3 class="text-lg font-black text-gray-900 uppercase">II. Add: Cash Inflow</h3>
                <span class="text-lg font-black text-green-600">PHP {{ number_format($totalCashAvailable - ($activeLedger->beginning_bank + $activeLedger->beginning_cash), 2) }}</span>
            </div>

            @forelse($inflows as $category => $transactions)
                <div class="mb-6 last:mb-0">
                    <h4 class="text-xs font-bold uppercase text-gray-400 tracking-widest mb-3 bg-gray-50 inline-block px-3 py-1 rounded-lg">{{ $category }}</h4>
                    <div class="space-y-3 pl-2 md:pl-4">
                        @foreach($transactions as $tx)
                            <div class="flex justify-between items-center text-sm">
                                <span class="font-medium text-gray-800">{{ $tx->description }}</span>
                                <span class="font-bold text-gray-900">PHP {{ number_format($tx->amount, 2) }}</span>
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
        <div class="bg-white rounded-3xl p-6 md:p-8 shadow-sm border border-gray-200">
            <div class="flex justify-between items-center mb-6 border-b border-gray-100 pb-4">
                <h3 class="text-lg font-black text-gray-900 uppercase">IV. Less: Expenses</h3>
                <span class="text-lg font-black text-red-600">PHP {{ number_format($totalExpenses, 2) }}</span>
            </div>

            @forelse($outflows as $category => $transactions)
                <div class="mb-6 last:mb-0">
                    <h4 class="text-xs font-bold uppercase text-gray-400 tracking-widest mb-3 bg-gray-50 inline-block px-3 py-1 rounded-lg">{{ $category }}</h4>
                    <div class="space-y-3 pl-2 md:pl-4">
                        @foreach($transactions as $tx)
                            <div class="flex justify-between items-center text-sm">
                                <span class="flex items-center gap-2 font-medium text-gray-800">
                                    {{ $tx->description }}
                                    @if($tx->reference_code)
                                        <span class="bg-gray-100 text-gray-500 text-[10px] px-2 py-0.5 rounded-md font-bold">{{ $tx->reference_code }}</span>
                                    @endif
                                </span>
                                <span class="font-bold text-gray-900">PHP {{ number_format($tx->amount, 2) }}</span>
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

        {{-- 6. Polymorphic Signatory Manager Component Injection --}}
        <div class="mt-8">
            <livewire:elucida.signatory-manager :targetModel="$activeLedger" />
        </div>

    </div>
</div>
