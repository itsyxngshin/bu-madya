<div class="max-w-7xl mx-auto space-y-6 pb-24 p-6">

    {{-- Header Matrix --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 bg-white p-6 border-4 border-iba-black shadow-[8px_8px_0_0_#0095AC] mb-8">
        <div>
            <h1 class="text-2xl font-black text-iba-black uppercase tracking-widest">eLucida Financial Grid</h1>
            <p class="text-sm font-bold text-gray-500 mt-1 uppercase tracking-wider">Manage semestral ledgers and liquidations.</p>
        </div>

        <div class="w-full md:w-auto flex flex-col sm:flex-row gap-3">
            <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search ledgers..." class="w-full sm:w-64 border-2 border-iba-black p-2 text-sm bg-gray-50 focus:outline-none focus:border-iba-teal font-bold uppercase">
            <button wire:click="$set('showCreateModal', true)" class="bg-iba-teal text-white font-black px-6 py-2 text-xs uppercase tracking-widest border-2 border-iba-black shadow-[3px_3px_0_0_#131011] hover:translate-y-0.5 hover:shadow-none transition-all w-full sm:w-auto">
                + Forge Ledger
            </button>
        </div>
    </div>

    @if (session()->has('success'))
        <div class="bg-iba-teal/10 border-l-4 border-iba-teal p-4 mb-6">
            <p class="text-xs font-black text-iba-teal uppercase tracking-wider">{{ session('success') }}</p>
        </div>
    @endif

    {{-- The Ledger Registry --}}
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
        @forelse($ledgers as $ledger)
            <div class="bg-white border-2 border-iba-black shadow-[4px_4px_0_0_#131011] flex flex-col group">
                <div class="p-4 border-b-2 border-iba-black bg-gray-50 flex justify-between items-start">
                    <div>
                        <span class="text-[9px] font-black uppercase tracking-widest text-iba-orange">{{ $ledger->status }}</span>
                        <h3 class="font-black text-sm uppercase text-iba-black mt-1 leading-tight">{{ $ledger->period_name }}</h3>
                    </div>
                </div>
                <div class="p-4 flex-1">
                    <p class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-4">{{ $ledger->organization_name }}</p>
                    <div class="space-y-2">
                        <div class="flex justify-between items-center text-sm border-b border-dashed border-gray-200 pb-1">
                            <span class="font-bold text-gray-400 uppercase">Starting Cap:</span>
                            <span class="font-black text-iba-black">PHP {{ number_format($ledger->beginning_bank + $ledger->beginning_cash, 2) }}</span>
                        </div>
                        <div class="flex justify-between items-center text-sm">
                            <span class="font-bold text-gray-400 uppercase">Ending Bal:</span>
                            <span class="font-black {{ $ledger->ending_balance < 0 ? 'text-iba-red' : 'text-iba-teal' }}">PHP {{ number_format($ledger->ending_balance, 2) }}</span>
                        </div>
                    </div>
                </div>
                <div class="p-3 border-t-2 border-iba-black bg-gray-100 flex gap-2">
                    <a href="{{ route('elucida.board', $ledger->id) }}" class="flex-1 text-center bg-iba-black text-white text-[10px] font-black uppercase py-2 hover:bg-iba-orange hover:text-iba-black transition-colors">Access Board</a>
                    <a href="{{ route('elucida.liquidation', $ledger->id) }}" class="flex-1 text-center bg-white border-2 border-iba-black text-iba-black text-[10px] font-black uppercase py-2 hover:bg-gray-200 transition-colors">Liquidations</a>
                </div>
            </div>
        @empty
            <div class="col-span-full p-12 text-center border-4 border-dashed border-gray-300 bg-white">
                <p class="text-sm font-black text-gray-400 uppercase tracking-widest">No active ledgers found in the matrix.</p>
            </div>
        @endforelse
    </div>

    <div>{{ $ledgers->links() }}</div>

    {{-- Forge Ledger Modal --}}
    @if($showCreateModal)
        <div class="fixed inset-0 z-50 overflow-y-auto">
            <div class="fixed inset-0 bg-iba-black/80 backdrop-blur-sm transition-opacity" wire:click="$set('showCreateModal', false)"></div>
            <div class="flex min-h-screen items-center justify-center p-4">
                <div class="relative w-full max-w-md bg-white border-4 border-iba-black shadow-[8px_8px_0_0_#FF8623] flex flex-col">
                    <div class="px-6 py-4 border-b-4 border-iba-black bg-gray-50 flex justify-between items-center">
                        <h3 class="text-lg font-black text-iba-black uppercase tracking-wider">Initialize Ledger</h3>
                        <button wire:click="$set('showCreateModal', false)" class="text-gray-400 hover:text-iba-red"><svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg></button>
                    </div>

                    <form wire:submit.prevent="createLedger" class="p-6 space-y-4">
                        <div>
                            <label class="block text-[10px] font-black text-gray-500 uppercase mb-1">Target Period (e.g. 2nd Sem 25-26)</label>
                            <input type="text" wire:model="period_name" class="w-full border-2 border-iba-black p-2 text-sm font-bold focus:outline-none focus:border-iba-orange">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-gray-500 uppercase mb-1">Organization Name</label>
                            <input type="text" wire:model="organization_name" {{ auth()->user()->role?->role_name === 'organization' ? 'readonly' : '' }} class="w-full border-2 border-iba-black p-2 text-sm font-bold {{ auth()->user()->role?->role_name === 'organization' ? 'bg-gray-200 text-gray-600' : 'focus:outline-none focus:border-iba-orange' }}">
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-[10px] font-black text-gray-500 uppercase mb-1">Beginning Bank (PHP)</label>
                                <input type="number" step="0.01" wire:model="beginning_bank" class="w-full border-2 border-iba-black p-2 text-sm font-black text-iba-teal focus:outline-none">
                            </div>
                            <div>
                                <label class="block text-[10px] font-black text-gray-500 uppercase mb-1">Beginning Cash (PHP)</label>
                                <input type="number" step="0.01" wire:model="beginning_cash" class="w-full border-2 border-iba-black p-2 text-sm font-black text-iba-orange focus:outline-none">
                            </div>
                        </div>

                        <div class="pt-4 flex gap-3">
                            <button type="submit" class="w-full bg-iba-black text-white font-black uppercase text-xs tracking-widest py-3 hover:bg-iba-orange hover:text-iba-black transition-colors">Establish Ledger</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>
