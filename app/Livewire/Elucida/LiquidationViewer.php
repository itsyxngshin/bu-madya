<?php

namespace App\Livewire\Elucida;

use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Layout;
use App\Models\ElucidaTransaction;
use App\Models\ElucidaLiquidation;
use App\Models\ElucidaLiquidationItem;
use Illuminate\Support\Facades\Storage;

#[Layout('layouts.madya-admin-deck')]
class LiquidationViewer extends Component
{
    use WithFileUploads;

    public ElucidaTransaction $transaction;

    // Edit Liquidation State
    public $showLiquidationModal = false;
    public $editingLiquidationId = null;
    public $lr_tracker_number, $dv_number, $accountable_officer, $officer_position, $cash_advance;

    // Edit Item State
    public $showItemModal = false;
    public $editingItemId = null;
    public $item_category, $item_quantity, $item_particulars, $item_date, $item_payee, $item_or, $item_amount;
    public $new_receipt_image, $existing_receipt_path;

    public function mount(ElucidaTransaction $transaction)
    {
        $this->transaction = $transaction;

        // Security check: Must be authorized for the parent ledger
        $user = auth()->user();
        $ledger = $transaction->ledger;
        $isAuthorized = $user && ($ledger->user_id === $user->id || in_array($user->role?->role_name, ['administrator', 'director']));

        if (!$isAuthorized) { abort(403, 'Unauthorized access to this liquidation record.'); }
    }

    // --- LIQUIDATION PARENT CRUD ---

    public function editLiquidation($id)
    {
        $liq = ElucidaLiquidation::findOrFail($id);
        $this->editingLiquidationId = $liq->id;
        $this->lr_tracker_number = $liq->lr_tracker_number;
        $this->dv_number = $liq->dv_number;
        $this->accountable_officer = $liq->accountable_officer;
        $this->officer_position = $liq->officer_position;
        $this->cash_advance = $liq->cash_advance;
        $this->showLiquidationModal = true;
    }

    public function saveLiquidation()
    {
        $this->validate([
            'lr_tracker_number' => 'required|string',
            'dv_number' => 'required|string',
            'accountable_officer' => 'required|string',
            'cash_advance' => 'required|numeric'
        ]);

        $liq = ElucidaLiquidation::findOrFail($this->editingLiquidationId);
        $liq->update([
            'lr_tracker_number' => $this->lr_tracker_number,
            'dv_number' => $this->dv_number,
            'accountable_officer' => $this->accountable_officer,
            'officer_position' => $this->officer_position,
            'cash_advance' => $this->cash_advance,
        ]);

        $this->showLiquidationModal = false;
        session()->flash('success', 'Liquidation metadata updated successfully.');
    }

    public function deleteLiquidation($id)
    {
        ElucidaLiquidation::findOrFail($id)->delete();
        session()->flash('success', 'Liquidation report and all attached items deleted.');
    }

    // --- LIQUIDATION ITEMS CRUD ---

    public function editItem($id)
    {
        $item = ElucidaLiquidationItem::findOrFail($id);
        $this->editingItemId = $item->id;
        $this->item_category = $item->expense_category;
        $this->item_quantity = $item->qty;
        $this->item_particulars = $item->particulars;
        $this->item_date = $item->date_incurred;
        $this->item_payee = $item->payee;
        $this->item_or = $item->receipt_number;
        $this->item_amount = $item->amount;
        $this->existing_receipt_path = $item->receipt_image_path;
        $this->new_receipt_image = null;

        $this->showItemModal = true;
    }

    public function saveItem()
    {
        $this->validate([
            'item_category' => 'required|string',
            'item_particulars' => 'required|string',
            'item_amount' => 'required|numeric|min:0.01',
            'new_receipt_image' => 'nullable|image|max:10240'
        ]);

        $item = ElucidaLiquidationItem::findOrFail($this->editingItemId);
        $imagePath = $this->existing_receipt_path;

        if ($this->new_receipt_image) {
            $imagePath = $this->new_receipt_image->store('liquidation-receipts', 'public');
        }

        $item->update([
            'expense_category' => $this->item_category,
            'qty' => $this->item_quantity ?: 1,
            'particulars' => $this->item_particulars,
            'date_incurred' => empty($this->item_date) ? null : $this->item_date,
            'payee' => $this->item_payee,
            'receipt_number' => $this->item_or,
            'amount' => $this->item_amount,
            'receipt_image_path' => $imagePath,
        ]);

        $this->showItemModal = false;
        session()->flash('success', 'Itemized receipt updated successfully.');
    }

    public function deleteItem($id)
    {
        ElucidaLiquidationItem::findOrFail($id)->delete();
        session()->flash('success', 'Itemized disbursement removed from the report.');
    }

    public function render()
    {
        // Load liquidations manually to prevent morph class bugs
        $liquidations = ElucidaLiquidation::where('transaction_id', $this->transaction->id)->orderBy('created_at', 'desc')->get();
        $liquidationIds = $liquidations->pluck('id');

        // Load items mapped to their parent liquidation
        $allItems = ElucidaLiquidationItem::whereIn('liquidation_id', $liquidationIds)->get()->groupBy('liquidation_id');

        return view('livewire.elucida.liquidation-viewer', compact('liquidations', 'allItems'));
    }
}
