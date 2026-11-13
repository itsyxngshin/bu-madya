<?php

namespace App\Livewire\Elucida;

use Livewire\Component;
use App\Models\ElucidaLedger;
use App\Models\ElucidaTransaction;
use App\Models\ElucidaSignatory;
use Livewire\Attributes\Layout;

#[Layout('layouts.madya-admin-deck')]
class LedgerBoard extends Component
{
    public $activeLedger;
    public $inflows = [];
    public $outflows = [];

    // Core Totals
    public $totalCashAvailable = 0;
    public $totalExpenses = 0;
    public $endingBalance = 0;

    public function mount(ElucidaLedger $ledger)
        {
            // $ledger is automatically fetched via UUID
            $this->activeLedger = $ledger;
            $this->calculateTotals();
        }

    public function calculateTotals()
    {
        // 2. Fetch and group all transactions linked to this ledger
        $transactions = ElucidaTransaction::where('ledger_id', $this->activeLedger->id)->get();

        $this->inflows = $transactions->where('type', 'inflow')->groupBy('category');
        $this->outflows = $transactions->where('type', 'outflow')->groupBy('category');

        $totalInflows = $transactions->where('type', 'inflow')->sum('amount');
        $this->totalExpenses = $transactions->where('type', 'outflow')->sum('amount');

        // Total Cash Available = Beginning Balance + Cash Inflow
        $beginningTotal = $this->activeLedger->beginning_bank + $this->activeLedger->beginning_cash;
        $this->totalCashAvailable = $beginningTotal + $totalInflows;

        // Ending Balance = Total Cash Available - Total Expenses
        $this->endingBalance = $this->totalCashAvailable - $this->totalExpenses;
    }

    public function render()
    {
        // Fetch signatories specifically attached to this ledger
        $signatories = ElucidaSignatory::where('signable_type', ElucidaLedger::class)
            ->where('signable_id', $this->activeLedger->id)
            ->orderBy('order_sequence')
            ->get();

        return view('livewire.elucida.ledger-board', compact('signatories'));
    }
}
