<div class="max-w-5xl mx-auto space-y-8 pb-24 p-6">

    {{-- Header Banner --}}
    <div class="bg-white border-4 border-iba-black shadow-[8px_8px_0_0_#FF8623] p-8 text-center relative overflow-hidden">
        <div class="absolute top-0 left-0 w-2 h-full bg-iba-orange"></div>
        <div class="absolute top-0 right-0 w-2 h-full bg-iba-teal"></div>

        <h2 class="text-xs font-black uppercase text-gray-500 tracking-widest">{{ $activeLedger->organization_name }}</h2>
        <h1 class="text-3xl font-black uppercase text-iba-black mt-2 mb-1">Financial Report</h1>
        <p class="text-sm font-bold text-gray-600 uppercase">{{ $activeLedger->period_name }}</p>
    </div>

    {{-- I. Beginning Balance --}}
    <div class="bg-gray-50 border-2 border-iba-black p-6">
        <div class="flex justify-between items-center mb-4 border-b-2 border-iba-black pb-2">
            <h3 class="text-lg font-black uppercase">I. Beginning Balance</h3>
            <span class="text-lg font-black">PHP {{ number_format($activeLedger->beginning_bank + $activeLedger->beginning_cash, 2) }}</span>
        </div>
        <div class="space-y-2 pl-4">
            <div class="flex justify-between text-sm font-bold text-gray-600 uppercase">
                <span>Cash in Bank</span>
                <span>PHP {{ number_format($activeLedger->beginning_bank, 2) }}</span>
            </div>
            <div class="flex justify-between text-sm font-bold text-gray-600 uppercase">
                <span>Cash on Hand</span>
                <span>PHP {{ number_format($activeLedger->beginning_cash, 2) }}</span>
            </div>
        </div>
    </div>

    {{-- II. Cash Inflows --}}
    <div class="bg-white border-2 border-iba-black p-6">
        <div class="flex justify-between items-center mb-4 border-b-2 border-iba-teal pb-2">
            <h3 class="text-lg font-black uppercase">II. Add: Cash Inflow</h3>
            <span class="text-lg font-black text-iba-teal">PHP {{ number_format($totalCashAvailable - ($activeLedger->beginning_bank + $activeLedger->beginning_cash), 2) }}</span>
        </div>

        @forelse($inflows as $category => $transactions)
            <div class="mb-4">
                <h4 class="text-xs font-black uppercase text-gray-500 mb-2">{{ $category }}</h4>
                <div class="space-y-2 pl-4">
                    @foreach($transactions as $tx)
                        <div class="flex justify-between text-sm font-bold text-gray-700 uppercase">
                            <span>{{ $tx->description }}</span>
                            <span>PHP {{ number_format($tx->amount, 2) }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        @empty
            <p class="text-xs font-bold text-gray-400 uppercase italic">No inflows recorded for this period.</p>
        @endforelse
    </div>

    {{-- III. Total Cash Available --}}
    <div class="bg-iba-teal border-4 border-iba-black p-4 flex justify-between items-center shadow-[4px_4px_0_0_#131011]">
        <h3 class="text-xl font-black uppercase text-white tracking-widest">III. Total Cash Available (I + II)</h3>
        <span class="text-2xl font-black text-white">PHP {{ number_format($totalCashAvailable, 2) }}</span>
    </div>

    {{-- IV. Expenses --}}
    <div class="bg-white border-2 border-iba-black p-6">
        <div class="flex justify-between items-center mb-4 border-b-2 border-iba-red pb-2">
            <h3 class="text-lg font-black uppercase">IV. Less: Expenses</h3>
            <span class="text-lg font-black text-iba-red">PHP {{ number_format($totalExpenses, 2) }}</span>
        </div>

        @forelse($outflows as $category => $transactions)
            <div class="mb-4">
                <h4 class="text-xs font-black uppercase text-gray-500 mb-2">{{ $category }}</h4>
                <div class="space-y-2 pl-4">
                    @foreach($transactions as $tx)
                        <div class="flex justify-between text-sm font-bold text-gray-700 uppercase">
                            <span class="flex items-center gap-2">
                                {{ $tx->description }}
                                @if($tx->reference_code)
                                    <span class="bg-gray-200 text-gray-600 text-[10px] px-2 py-1 rounded">{{ $tx->reference_code }}</span>
                                @endif
                            </span>
                            <span>PHP {{ number_format($tx->amount, 2) }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        @empty
            <p class="text-xs font-bold text-gray-400 uppercase italic">No expenses recorded for this period.</p>
        @endforelse
    </div>

    {{-- V. Ending Balance --}}
    <div class="bg-iba-black border-4 border-iba-black p-6 flex justify-between items-center shadow-[6px_6px_0_0_#FF8623]">
        <h3 class="text-2xl font-black uppercase text-iba-orange tracking-widest">V. Ending Balance (III - IV)</h3>
        <span class="text-3xl font-black text-white">PHP {{ number_format($endingBalance, 2) }}</span>
    </div>

</div>
