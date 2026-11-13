<?php

namespace App\Livewire\Elucida;

use Livewire\Component;
use App\Models\ElucidaLedger;
use App\Models\ElucidaTransaction;
use App\Models\ElucidaSignatory;
use Carbon\Carbon;
use Livewire\Attributes\Layout;

#[Layout('layouts.madya-admin-deck')]
class LedgerBoard extends Component
{
    public $activeLedger;
    public $inflows = [];
    public $outflows = [];

    public $totalCashAvailable = 0;
    public $totalExpenses = 0;
    public $endingBalance = 0;

    // --- TRANSACTION MODAL STATE ---
    public $showTransactionModal = false;
    public $transactionType = 'inflow'; // 'inflow' or 'outflow'
    public $editingTransactionId = null;

    // Form Fields
    public $tx_category = '';
    public $tx_description = '';
    public $tx_reference = '';
    public $tx_amount = '';

    public $inflowCategories = [
        'Membership Fee',
        'Income Generating Fund',
        'External Funds',
        'Sponsorship / Donation',
        'Other'
    ];

    public $outflowCategories = [
        'Events',
        'Operational Expenses',
        'Supplies and Materials',
        'Other'
    ];

    public function mount(ElucidaLedger $ledger)
    {
        $this->activeLedger = $ledger;
        $this->calculateTotals();
    }

    public function openTransactionModal($type = 'inflow')
    {
        $this->resetForm();
        $this->transactionType = $type;
        $this->tx_category = $type === 'inflow' ? 'Income Generating Fund' : 'Events';
        $this->showTransactionModal = true;
    }

    public function editTransaction($id)
    {
        $this->resetForm();
        $tx = ElucidaTransaction::findOrFail($id);

        // Security check
        if($tx->ledger_id !== $this->activeLedger->id) {
            abort(403);
        }

        $this->editingTransactionId = $tx->id;
        $this->transactionType = $tx->type;
        $this->tx_category = $tx->category;
        $this->tx_description = $tx->description;
        $this->tx_reference = $tx->reference_code;
        $this->tx_amount = $tx->amount;

        $this->showTransactionModal = true;
    }

    public function resetForm()
    {
        $this->reset(['editingTransactionId', 'tx_category', 'tx_description', 'tx_reference', 'tx_amount']);
    }

    public function saveTransaction()
    {
        $this->validate([
            'tx_category' => 'required|string',
            'tx_description' => 'required|string|max:255',
            'tx_amount' => 'required|numeric|min:0.01'
        ]);

        if ($this->editingTransactionId) {
            $tx = ElucidaTransaction::findOrFail($this->editingTransactionId);
            $tx->update([
                'category' => $this->tx_category,
                'description' => mb_strtoupper($this->tx_description),
                'reference_code' => $this->tx_reference ?: null,
                'amount' => $this->tx_amount,
            ]);
            session()->flash('success', 'Transaction updated successfully.');
        } else {
            ElucidaTransaction::create([
                'ledger_id' => $this->activeLedger->id,
                'type' => $this->transactionType,
                'category' => $this->tx_category,
                'description' => mb_strtoupper($this->tx_description),
                'reference_code' => $this->tx_reference ?: null,
                'amount' => $this->tx_amount,
                'transaction_date' => Carbon::now()
            ]);
            session()->flash('success', ucfirst($this->transactionType) . ' securely logged to the ledger.');
        }

        $this->showTransactionModal = false;
        $this->resetForm();
        $this->calculateTotals();
    }

    public function deleteTransaction($id)
    {
        $tx = ElucidaTransaction::findOrFail($id);

        if($tx->ledger_id === $this->activeLedger->id) {
            $tx->delete();
            $this->calculateTotals();
            session()->flash('success', 'Transaction permanently removed from the ledger.');
        }
    }

    public function calculateTotals()
    {
        $transactions = ElucidaTransaction::where('ledger_id', $this->activeLedger->id)->get();

        $this->inflows = $transactions->where('type', 'inflow')->groupBy('category');
        $this->outflows = $transactions->where('type', 'outflow')->groupBy('category');

        $totalInflows = $transactions->where('type', 'inflow')->sum('amount');
        $this->totalExpenses = $transactions->where('type', 'outflow')->sum('amount');

        $beginningTotal = $this->activeLedger->beginning_bank + $this->activeLedger->beginning_cash;
        $this->totalCashAvailable = $beginningTotal + $totalInflows;
        $this->endingBalance = $this->totalCashAvailable - $this->totalExpenses;
    }

    // --- DOCX EXPORT PROTOCOL ---
    public function exportStatementDoc()
    {
        $this->calculateTotals();
        $signatories = \App\Models\ElucidaSignatory::where('signable_type', \App\Models\ElucidaLedger::class)
            ->where('signable_id', $this->activeLedger->id)->orderBy('order_sequence')->get();

        $fileName = "Statement_of_Cash_Flows_" . \Illuminate\Support\Str::slug($this->activeLedger->user->name ?? 'Organization') . ".doc";

        return response()->streamDownload(function () use ($signatories) {
            echo "<html xmlns:w='urn:schemas-microsoft-com:office:word'><head><meta charset='utf-8'></head><body style='font-family: Arial, sans-serif; line-height: 1.5; font-size: 11pt;'>";

            echo "<div style='text-align: center; margin-bottom: 20px;'>";
            echo "<h3 style='margin: 0; font-size: 14pt; font-weight: bold;'>Bicol University</h3>";
            echo "<h4 style='margin: 0; font-size: 12pt;'>Legazpi City, Albay</h4>";
            echo "<h2 style='margin-top: 15px; margin-bottom: 5px; font-size: 16pt; font-weight: bold; text-transform: uppercase;'>" . htmlspecialchars($this->activeLedger->user->name ?? 'Organization') . "</h2>";
            echo "<h3 style='margin: 0; font-size: 14pt; font-weight: bold;'>Statement of Cash Inflows and Outflows</h3>";
            echo "<p style='margin: 5px 0 0 0; font-size: 11pt; font-style: italic;'>" . htmlspecialchars($this->activeLedger->period_name) . "</p>";
            echo "</div>";

            echo "<table style='width: 100%; border-collapse: collapse;' cellpadding='5'>";

            echo "<tr><td colspan='2' style='font-weight: bold; background-color: #f3f4f6; border-bottom: 1px solid #000;'>BEGINNING BALANCE</td></tr>";
            echo "<tr><td style='padding-left: 20px;'>Cash in Bank</td><td style='text-align: right;'>PHP " . number_format($this->activeLedger->beginning_bank, 2) . "</td></tr>";
            echo "<tr><td style='padding-left: 20px;'>Cash on Hand</td><td style='text-align: right;'>PHP " . number_format($this->activeLedger->beginning_cash, 2) . "</td></tr>";

            echo "<tr><td colspan='2' style='font-weight: bold; background-color: #f3f4f6; border-bottom: 1px solid #000; margin-top: 15px;'>ADD: CASH INFLOW</td></tr>";
            echo "<tr><td style='font-weight: bold; text-decoration: underline; padding-top: 10px;'>Activity/Transaction</td><td style='font-weight: bold; text-align: right; text-decoration: underline; padding-top: 10px;'>Total</td></tr>";

            foreach ($this->inflows as $category => $transactions) {
                foreach ($transactions as $tx) {
                    echo "<tr><td>" . htmlspecialchars($tx->description) . "</td><td style='text-align: right;'>PHP " . number_format($tx->amount, 2) . "</td></tr>";
                }
            }

            echo "<tr><td style='font-weight: bold; padding-top: 15px; border-top: 2px solid #000;'>TOTAL CASH AVAILABLE</td><td style='font-weight: bold; text-align: right; padding-top: 15px; border-top: 2px solid #000;'>PHP " . number_format($this->totalCashAvailable, 2) . "</td></tr>";

            echo "<tr><td colspan='2' style='font-weight: bold; background-color: #f3f4f6; border-bottom: 1px solid #000; padding-top: 15px;'>LESS: EXPENSES</td></tr>";
            echo "<tr><td style='font-weight: bold; text-decoration: underline; padding-top: 10px;'>Activity/Transaction</td><td style='font-weight: bold; text-align: right; text-decoration: underline; padding-top: 10px;'>Total</td></tr>";

            foreach ($this->outflows as $category => $transactions) {
                foreach ($transactions as $tx) {
                    $ref = $tx->reference_code ? " (" . $tx->reference_code . ")" : "";
                    echo "<tr><td>" . htmlspecialchars($tx->description . $ref) . "</td><td style='text-align: right;'>PHP " . number_format($tx->amount, 2) . "</td></tr>";
                }
            }

            echo "<tr><td style='font-weight: bold; padding-top: 10px;'>TOTAL CASH EXPENSES</td><td style='font-weight: bold; text-align: right; padding-top: 10px;'>PHP " . number_format($this->totalExpenses, 2) . "</td></tr>";

            echo "<tr><td colspan='2' style='font-weight: bold; background-color: #f3f4f6; border-bottom: 1px solid #000; padding-top: 15px;'>ENDING CASH BALANCE (III - V)</td></tr>";
            echo "<tr><td style='padding-left: 20px; font-weight: bold;'>Cash in Bank</td><td style='text-align: right; font-weight: bold;'>PHP " . number_format($this->activeLedger->beginning_bank, 2) . "</td></tr>";
            $endingCashOnHand = $this->endingBalance - $this->activeLedger->beginning_bank;
            echo "<tr><td style='padding-left: 20px; font-weight: bold;'>Cash on Hand</td><td style='text-align: right; font-weight: bold;'>PHP " . number_format($endingCashOnHand, 2) . "</td></tr>";
            echo "</table>";

            if ($signatories->count() > 0) {
                echo "<div style='margin-top: 40px;'>";
                foreach ($signatories as $sig) {
                    echo "<div style='margin-bottom: 30px; display: inline-block; width: 45%; vertical-align: top;'>";
                    echo "<p style='margin: 0; font-size: 10pt;'>" . htmlspecialchars($sig->role) . ":</p>";
                    echo "<p style='margin: 30px 0 0 0; font-weight: bold; text-decoration: underline; text-transform: uppercase;'>" . htmlspecialchars($sig->name) . "</p>";
                    echo "<p style='margin: 0; font-size: 10pt;'>" . htmlspecialchars($sig->position) . "</p>";
                    echo "</div>";
                }
                echo "</div>";
            }
            echo "</body></html>";
        }, $fileName, ['Content-Type' => 'application/msword', 'Content-Disposition' => 'attachment; filename="' . $fileName . '"']);
    }

    public function render()
    {
        $signatories = ElucidaSignatory::where('signable_type', ElucidaLedger::class)
            ->where('signable_id', $this->activeLedger->id)
            ->orderBy('order_sequence')->get();

        return view('livewire.elucida.ledger-board', compact('signatories'));
    }
}
