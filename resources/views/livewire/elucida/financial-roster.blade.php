<div class="min-h-screen bg-gray-100 p-4 md:p-6 font-sans text-gray-900">
    <div class="max-w-7xl mx-auto">

        {{-- 1. HEADER & ACTIONS --}}
        <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
            <div class="w-full md:w-auto text-center md:text-left">
                <h1 class="text-2xl md:text-3xl font-black text-gray-900">Financial Ledgers</h1>
                <p class="text-xs md:text-sm text-gray-500 mt-1">Manage organizational budgets and liquidations.</p>
            </div>

            <div class="flex flex-col sm:flex-row items-center gap-3 w-full md:w-auto">
                {{-- Search Bar --}}
                <div class="relative w-full sm:w-64">
                    <input wire:model.live.debounce.300ms="search" type="text" class="w-full pl-10 pr-4 py-2.5 md:py-2 rounded-xl border-gray-200 focus:border-red-500 focus:ring-red-500 text-sm shadow-sm" placeholder="Search ledgers...">
                    <svg class="w-4 h-4 text-gray-400 absolute left-3 top-3 md:top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>

                {{-- Create Button --}}
                <button wire:click="$set('showCreateModal', true)" class="w-full sm:w-auto justify-center px-5 py-2.5 md:py-2 bg-gray-900 text-white font-bold rounded-xl shadow-md hover:bg-red-600 transition flex items-center gap-2 text-sm whitespace-nowrap">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    New Ledger
                </button>
            </div>
        </div>

        @if (session()->has('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl mb-6 text-sm font-bold shadow-sm">
                {{ session('success') }}
            </div>
        @endif

        {{-- 2. GRID LAYOUT --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

            {{-- "Create New" Ghost Card --}}
            <button wire:click="$set('showCreateModal', true)" class="group border-2 border-dashed border-gray-300 rounded-[2rem] p-6 flex flex-col items-center justify-center text-center hover:border-red-400 hover:bg-red-50 transition min-h-[250px] cursor-pointer w-full">
                <div class="w-16 h-16 rounded-full bg-gray-100 text-gray-400 flex items-center justify-center mb-4 group-hover:bg-red-100 group-hover:text-red-500 transition shadow-sm">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                </div>
                <h3 class="font-bold text-gray-500 group-hover:text-red-700">Forge New Ledger</h3>
            </button>

            {{-- Ledger Cards --}}
            @forelse($ledgers as $ledger)
                <div class="bg-white rounded-[2rem] p-6 shadow-sm border border-gray-200 relative group hover:-translate-y-1 hover:shadow-md transition duration-300 flex flex-col h-full overflow-hidden">

                    {{-- Tricolor Accent (Top border) --}}
                    <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-red-600 via-yellow-400 to-green-500"></div>

                    {{-- Status Badge --}}
                    <div class="absolute top-6 right-6">
                        <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wide border {{ $ledger->status === 'approved' ? 'bg-green-100 text-green-700 border-green-200' : 'bg-yellow-50 text-yellow-700 border-yellow-200' }}">
                            {{ $ledger->status }}
                        </span>
                    </div>

                    {{-- Card Content --}}
                    <div class="mt-2 flex-1">
                        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-red-500 to-orange-500 text-white flex items-center justify-center mb-4 shadow-lg shadow-orange-200">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        </div>

                        <h3 class="text-xl font-bold text-gray-900 leading-tight mb-1">
                            {{ $ledger->period_name }}
                        </h3>
                        <p class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-4 truncate">{{ $ledger->user ? $ledger->user->name : 'Unassigned' }}</p>

                        {{-- Stats --}}
                        <div class="space-y-2 mt-4 bg-gray-50 p-4 rounded-xl border border-gray-100">
                            <div class="flex justify-between items-center text-sm">
                                <span class="text-gray-500 font-medium">Starting Cap:</span>
                                <span class="font-bold text-gray-900">PHP {{ number_format($ledger->beginning_bank + $ledger->beginning_cash, 2) }}</span>
                            </div>
                            <div class="flex justify-between items-center text-sm">
                                <span class="text-gray-500 font-medium">Ending Bal:</span>
                                <span class="font-bold {{ $ledger->ending_balance < 0 ? 'text-red-600' : 'text-green-600' }}">PHP {{ number_format($ledger->ending_balance, 2) }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- Actions Footer --}}
                    <div class="border-t border-gray-100 pt-4 mt-6 flex gap-2">
                        <a href="{{ route('elucida.board', $ledger) }}" class="flex-1 text-center bg-gray-900 text-white px-4 py-2 rounded-xl text-[10px] sm:text-xs font-bold uppercase tracking-wider hover:bg-red-600 transition">
                            Board
                        </a>
                        <a href="{{ route('elucida.liquidation', $ledger) }}" class="flex-1 text-center bg-gray-50 border border-gray-200 text-gray-700 px-4 py-2 rounded-xl text-[10px] sm:text-xs font-bold uppercase tracking-wider hover:bg-gray-100 transition">
                            Liquidations
                        </a>
                    </div>
                </div>
            @empty
                {{-- Empty state is handled by the Ghost Card above --}}
            @endforelse
        </div>

        <div class="mt-8">
            {{ $ledgers->links() }}
        </div>
    </div>

    {{-- CREATE MODAL (BU MADYA UI) --}}
    @if($showCreateModal)
        <div class="fixed inset-0 z-[100] flex items-center justify-center p-4 sm:p-6">
            <div class="fixed inset-0 bg-gray-900/80 backdrop-blur-sm transition-opacity" wire:click="$set('showCreateModal', false)"></div>

            <div class="relative bg-white rounded-3xl shadow-2xl w-full max-w-md flex flex-col overflow-hidden transform transition-all">
                <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-red-600 via-yellow-400 to-green-500"></div>

                <div class="flex items-center justify-between p-6 border-b border-gray-100 bg-white">
                    <div>
                        <h3 class="text-xl font-black text-gray-900 leading-tight">Initialize Ledger</h3>
                        <p class="text-xs font-bold text-gray-400 mt-1 uppercase tracking-widest">Setup new period</p>
                    </div>
                    <button wire:click="$set('showCreateModal', false)" class="p-2 text-gray-400 hover:text-red-500 hover:bg-red-50 rounded-xl transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <form wire:submit.prevent="createLedger" class="p-6 bg-gray-50 space-y-4">
                    <div>
                        <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1.5">Target Period</label>
                        <input type="text" wire:model="period_name" placeholder="e.g. 2nd Sem 25-26" class="w-full border-gray-200 rounded-xl focus:border-red-500 focus:ring-red-500 text-sm font-bold shadow-sm">
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1.5">Assigned Organization</label>
                        @if(auth()->user()->role?->role_name === 'organization')
                            <input type="text" value="{{ auth()->user()->name }}" readonly class="w-full border-gray-200 rounded-xl bg-gray-200 text-gray-600 text-sm font-bold shadow-sm cursor-not-allowed">
                        @else
                            <select wire:model="target_user_id" class="w-full border-gray-200 rounded-xl focus:border-red-500 focus:ring-red-500 text-sm font-bold shadow-sm">
                                <option value="">-- Select Organization --</option>
                                @foreach($organizations as $org)
                                    <option value="{{ $org->id }}">{{ $org->name }}</option>
                                @endforeach
                            </select>
                        @endif
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1.5">Beginning Bank</label>
                            <input type="number" step="0.01" wire:model="beginning_bank" class="w-full border-gray-200 rounded-xl focus:border-red-500 focus:ring-red-500 text-sm font-black text-gray-900 shadow-sm">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1.5">Beginning Cash</label>
                            <input type="number" step="0.01" wire:model="beginning_cash" class="w-full border-gray-200 rounded-xl focus:border-red-500 focus:ring-red-500 text-sm font-black text-gray-900 shadow-sm">
                        </div>
                    </div>

                    <button type="submit" class="w-full mt-4 bg-gray-900 text-white font-bold rounded-xl py-3 shadow-md hover:bg-red-600 transition flex justify-center items-center gap-2">
                        Forge Ledger
                    </button>
                </form>
            </div>
        </div>
    @endif
</div>
