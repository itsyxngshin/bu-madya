<?php

namespace App\Livewire\Elucida;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use App\Models\ElucidaLedger;

#[Layout('layouts.madya-admin-deck')]
class FinancialRoster extends Component
{
    use WithPagination;

    public $search = '';

    // Modal state for forging a new ledger
    public $showCreateModal = false;
    public $period_name = '';
    public $organization_name = '';
    public $beginning_bank = 0;
    public $beginning_cash = 0;

    public function mount()
    {
        // Auto-fill organization name if the user belongs to an org
        if (auth()->user()->role?->role_name === 'organization') {
            $this->organization_name = auth()->user()->name; // Or pull from org profile relationship
        }
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function createLedger()
    {
        $this->validate([
            'period_name' => 'required|string|max:255',
            'organization_name' => 'required|string|max:255',
            'beginning_bank' => 'required|numeric|min:0',
            'beginning_cash' => 'required|numeric|min:0',
        ]);

        $ledger = ElucidaLedger::create([
            'period_name' => $this->period_name,
            'organization_name' => $this->organization_name,
            'beginning_bank' => $this->beginning_bank,
            'beginning_cash' => $this->beginning_cash,
            'status' => 'draft'
        ]);

        $this->reset(['showCreateModal', 'period_name', 'beginning_bank', 'beginning_cash']);

        session()->flash('success', 'New Financial Ledger successfully forged.');
        return redirect()->route('elucida.board', $ledger); // Pass the model directly
    }

    public function render()
    {
        $query = ElucidaLedger::query();

        // RBAC Protocol: Organizations only see their own ledgers
        if (auth()->user()->role?->role_name === 'organization') {
            $query->where('organization_name', auth()->user()->name);
        }

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('period_name', 'like', '%' . $this->search . '%')
                  ->orWhere('organization_name', 'like', '%' . $this->search . '%');
            });
        }

        $ledgers = $query->latest()->paginate(10);

        return view('livewire.elucida.financial-roster', compact('ledgers'));
    }
}
