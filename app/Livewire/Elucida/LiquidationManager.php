<?php

namespace App\Livewire\Elucida;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\ElucidaLedger;
use App\Models\ElucidaTransaction;
use App\Models\ElucidaLiquidation;
use App\Models\ElucidaLiquidationItem;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;

#[Layout('layouts.madya-admin-deck')]
class LiquidationManager extends Component
{
    use WithFileUploads;

    public $transactions;
    public $selectedTransactionId;
    public $tracker_number;
    public $dv_number;
    public $accountable_officer;
    public $officer_position;
    public $cash_advance;
    public $implementation_start;
    public $implementation_end;

    public $items = [];

    // Form fields for a new item
    public $newItem = [
        'category' => '',
        'quantity' => 1,
        'particulars' => '',
        'receipt_date' => '',
        'payee' => '',
        'or_si_number' => '',
        'amount' => 0,
        'receipt_image' => null,
    ];

    public function mount(ElucidaLedger $ledger)
    {
        $this->activeLedger = $ledger;
                // Load outflows that might need liquidation for THIS specific ledger
                $this->transactions = ElucidaTransaction::where('ledger_id', $this->activeLedger->id)
                                                        ->where('type', 'outflow')
                                                        ->get();
        // Generate a tracker number (e.g., CircUITS-2026-LR-XXX)
        $this->tracker_number = 'MADYA-' . date('Y') . '-LR-' . str_pad(ElucidaLiquidation::count() + 1, 3, '0', STR_PAD_LEFT);
    }

    public function addItem()
    {
        $this->validate([
            'newItem.category' => 'required|string',
            'newItem.particulars' => 'required|string',
            'newItem.amount' => 'required|numeric|min:0.01',
        ]);

        $imagePath = null;
        if ($this->newItem['receipt_image']) {
            $imagePath = $this->newItem['receipt_image']->store('receipts', 'public');
        }

        $this->items[] = [
            'category' => $this->newItem['category'],
            'quantity' => $this->newItem['quantity'],
            'particulars' => $this->newItem['particulars'],
            'receipt_date' => $this->newItem['receipt_date'],
            'payee' => $this->newItem['payee'],
            'or_si_number' => $this->newItem['or_si_number'],
            'amount' => $this->newItem['amount'],
            'receipt_image_path' => $imagePath,
        ];

        // Reset the form
        $this->newItem = [
            'category' => '',
            'quantity' => 1,
            'particulars' => '',
            'receipt_date' => '',
            'payee' => '',
            'or_si_number' => '',
            'amount' => 0,
            'receipt_image' => null,
        ];
    }

    public function removeItem($index)
    {
        unset($this->items[$index]);
        $this->items = array_values($this->items);
    }

    public function saveLiquidation()
    {
        $this->validate([
            'selectedTransactionId' => 'required|exists:elucida_transactions,id',
            'accountable_officer' => 'required|string',
            'officer_position' => 'required|string',
            'cash_advance' => 'required|numeric',
        ]);

        $liquidation = ElucidaLiquidation::create([
            'lr_tracker_number' => $this->tracker_number,
            'tracker_number' => $this->tracker_number,
            'dv_number' => $this->dv_number,
            'accountable_officer' => $this->accountable_officer,
            'officer_position' => $this->officer_position,
            'cash_advance' => $this->cash_advance,
            'implementation_start' => $this->implementation_start,
            'implementation_end' => $this->implementation_end,
            'status' => 'pending'
        ]);

        foreach ($this->items as $item) {
            ElucidaLiquidationItem::create([
                'liquidation_id' => $liquidation->id,
                'category' => $item['category'],
                'quantity' => $item['quantity'],
                'particulars' => $item['particulars'],
                'receipt_date' => $item['receipt_date'],
                'payee' => $item['payee'],
                'or_si_number' => $item['or_si_number'],
                'amount' => $item['amount'],
                'receipt_image_path' => $item['receipt_image_path'],
            ]);
        }

        session()->flash('success', 'Liquidation Report securely established in the ledger.');
        return redirect()->route('elucida.liquidation.index'); // Adjust route as needed
    }

    public function render()
    {
        $totalExpenses = array_sum(array_column($this->items, 'amount'));
        $excessShort = $this->cash_advance - $totalExpenses;

        return view('livewire.elucida.liquidation-manager', [
            'totalExpenses' => $totalExpenses,
            'excessShort' => $excessShort
        ]);
    }
}
