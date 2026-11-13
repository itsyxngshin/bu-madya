<?php

namespace App\Livewire\Elucida;

use Livewire\Component;
use App\Models\ElucidaLedger;
use App\Models\ElucidaTransaction;
use App\Models\ElucidaSalesRegister;
use Carbon\Carbon;

class SalesRegister extends Component
{
    public $activeLedger;

    // Core Entry Form
    public $transactionDescription = '';
    public $defaultPrice = 10.00;

    // The Rapid Grid
    public $salesGrid = [];
    public $buyer_name = '';
    public $receipt_number = '';
    public $price = '';

    public function mount(ElucidaLedger $ledger)
        {
            $this->activeLedger = $ledger;
            $this->price = $this->defaultPrice;
        }

    public function updatedDefaultPrice()
    {
        $this->price = $this->defaultPrice;
    }

    public function pushToGrid()
    {
        $this->validate([
            'buyer_name' => 'required|string|max:255',
            'receipt_number' => 'required|string',
            'price' => 'required|numeric|min:0.01'
        ]);

        $this->salesGrid[] = [
            'buyer_name' => $this->buyer_name,
            'receipt_number' => $this->receipt_number,
            'price' => (float) $this->price
        ];

        // Auto-increment receipt number if it's numeric for ultra-fast entry
        if (is_numeric($this->receipt_number)) {
            $nextReceipt = (int) $this->receipt_number + 1;
            $this->receipt_number = str_pad($nextReceipt, strlen($this->receipt_number), '0', STR_PAD_LEFT);
        } else {
            $this->receipt_number = '';
        }

        $this->buyer_name = '';
        // Price remains locked to the default to avoid re-typing
    }

    public function removeRow($index)
    {
        unset($this->salesGrid[$index]);
        $this->salesGrid = array_values($this->salesGrid); // Re-index array
    }

    public function commitBatch()
    {
        $this->validate([
            'transactionDescription' => 'required|string|max:255',
            'salesGrid' => 'required|array|min:1'
        ]);

        $totalRevenue = array_sum(array_column($this->salesGrid, 'price'));

        // 1. Forge the Parent Inflow Transaction
        $transaction = ElucidaTransaction::create([
            'ledger_id' => $this->activeLedger->id,
            'type' => 'inflow',
            'category' => 'Income Generating Fund',
            'description' => $this->transactionDescription,
            'amount' => $totalRevenue,
            'transaction_date' => Carbon::now()
        ]);

        // 2. Persist the individual receipt rows
        foreach ($this->salesGrid as $sale) {
            ElucidaSalesRegister::create([
                'transaction_id' => $transaction->id,
                'buyer_name' => $sale['buyer_name'],
                'receipt_number' => $sale['receipt_number'],
                'price' => $sale['price']
            ]);
        }

        // Reset Terminal
        $this->salesGrid = [];
        $this->transactionDescription = '';

        session()->flash('success', "Batch committed! PHP " . number_format($totalRevenue, 2) . " established in the ledger.");
    }

    public function render()
    {
        $batchTotal = array_sum(array_column($this->salesGrid, 'price'));
        return view('livewire.elucida.sales-register', compact('batchTotal'));
    }
}
