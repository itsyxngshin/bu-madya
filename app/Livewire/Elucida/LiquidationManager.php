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

    public $activeLedger;
    public $transactions;
    public $selectedTransactionId = '';
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

        // Fetch all outflows linked to this specific ledger
        $this->transactions = ElucidaTransaction::where('ledger_id', $this->activeLedger->id)
            ->where('type', 'outflow')
            ->orderBy('created_at', 'desc')
            ->get();

        // Generate a dynamic tracker number
        $this->tracker_number = 'MADYA-' . date('Y') . '-LR-' . str_pad(ElucidaLiquidation::count() + 1, 3, '0', STR_PAD_LEFT);
    }

    public function addItem()
    {
        $this->validate([
            'newItem.category' => 'required|string',
            'newItem.particulars' => 'required|string',
            'newItem.amount' => 'required|numeric|min:0.01',
        ]);

        $this->items[] = [
            'category' => $this->newItem['category'],
            'quantity' => $this->newItem['quantity'] ?: 1,
            'particulars' => $this->newItem['particulars'],
            'receipt_date' => $this->newItem['receipt_date'],
            'payee' => $this->newItem['payee'],
            'or_si_number' => $this->newItem['or_si_number'],
            'amount' => $this->newItem['amount'],
            'receipt_image_path' => $this->newItem['receipt_image'],
        ];

        // Reset the form safely
        $this->reset('newItem');
        $this->newItem['quantity'] = 1;
        $this->newItem['amount'] = 0;
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

        // Securely map the liquidation parent record
        $liquidation = ElucidaLiquidation::create([
            'transaction_id' => $this->selectedTransactionId,
            'lr_tracker_number' => $this->tracker_number,
            'dv_number' => $this->dv_number,
            'accountable_officer' => $this->accountable_officer,
            'officer_position' => $this->officer_position,
            'cash_advance' => $this->cash_advance,
            'implementation_start' => empty($this->implementation_start) ? null : $this->implementation_start,
            'implementation_end' => empty($this->implementation_end) ? null : $this->implementation_end,
            'status' => 'pending'
        ]);

        // Map the items to their strict database columns
        foreach ($this->items as $item) {
            $imagePath = null;
            
            if (!empty($item['receipt_image_path'])) {
                if (is_string($item['receipt_image_path'])) {
                    $imagePath = $item['receipt_image_path'];
                } else {
                    $imagePath = $item['receipt_image_path']->store('liquidation-receipts', 'public');
                }
            }

            ElucidaLiquidationItem::create([
                'liquidation_id' => $liquidation->id,
                'expense_category' => $item['category'], // Mapped from 'category'
                'qty' => $item['quantity'] ?? 1,         // Mapped from 'quantity'
                'particulars' => $item['particulars'],
                'date_incurred' => empty($item['receipt_date']) ? null : $item['receipt_date'], // Safe Null formatting
                'payee' => $item['payee'],
                'receipt_number' => $item['or_si_number'], // Mapped from 'or_si_number'
                'amount' => $item['amount'],
                'receipt_image_path' => $imagePath,
            ]);
        }

        session()->flash('success', 'Liquidation Report securely established in the ledger.');
        return redirect()->route('elucida.roster'); 
    }

    public function render()
    {
        $totalExpenses = array_sum(array_column($this->items, 'amount'));
        $excessShort = (float)$this->cash_advance - $totalExpenses;

        return view('livewire.elucida.liquidation-manager', [
            'totalExpenses' => $totalExpenses,
            'excessShort' => $excessShort
        ]);
    }
}