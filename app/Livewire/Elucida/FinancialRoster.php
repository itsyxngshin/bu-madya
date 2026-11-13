<?php

namespace App\Livewire\Elucida;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use App\Models\ElucidaLedger;
use App\Models\User;

#[Layout('layouts.madya-admin-deck')]
class FinancialRoster extends Component
{
    use WithPagination;

    public $search = '';

    // Modal State
    public $showCreateModal = false;
    public $editMode = false;
    public $editingLedgerId = null;

    // Form Fields
    public $period_name = '';
    public $target_user_id = null;
    public $beginning_bank = 0;
    public $beginning_cash = 0;

    public function mount()
    {
        if (auth()->user()->role?->role_name === 'organization') {
            $this->target_user_id = auth()->id();
        }
    }

    public function updatingSearch() { $this->resetPage(); }

    public function openCreateModal()
    {
        $this->resetForm();
        $this->showCreateModal = true;
    }

    public function openEditModal($ledgerId)
    {
        $this->resetForm();
        $ledger = ElucidaLedger::findOrFail($ledgerId);

        $this->editingLedgerId = $ledger->id;
        $this->period_name = $ledger->period_name;
        $this->target_user_id = $ledger->user_id;
        $this->beginning_bank = $ledger->beginning_bank;
        $this->beginning_cash = $ledger->beginning_cash;

        $this->editMode = true;
        $this->showCreateModal = true;
    }

    public function resetForm()
    {
        $this->reset(['editingLedgerId', 'period_name', 'beginning_bank', 'beginning_cash', 'editMode']);
        if (auth()->user()->role?->role_name === 'organization') {
            $this->target_user_id = auth()->id();
        } else {
            $this->target_user_id = null;
        }
    }

    public function saveLedger()
    {
        $this->validate([
            'period_name' => 'required|string|max:255',
            'target_user_id' => 'required|exists:users,id',
            'beginning_bank' => 'required|numeric|min:0',
            'beginning_cash' => 'required|numeric|min:0',
        ]);

        if ($this->editMode) {
            $ledger = ElucidaLedger::findOrFail($this->editingLedgerId);
            $ledger->update([
                'period_name' => $this->period_name,
                'user_id' => $this->target_user_id,
                'beginning_bank' => $this->beginning_bank,
                'beginning_cash' => $this->beginning_cash,
            ]);
            session()->flash('success', 'Financial Ledger updated successfully.');
        } else {
            $ledger = ElucidaLedger::create([
                'period_name' => $this->period_name,
                'user_id' => $this->target_user_id,
                'beginning_bank' => $this->beginning_bank,
                'beginning_cash' => $this->beginning_cash,
                'status' => 'draft'
            ]);
            session()->flash('success', 'New Financial Ledger successfully forged.');
            return redirect()->route('elucida.board', $ledger);
        }

        $this->showCreateModal = false;
        $this->resetForm();
    }

    public function deleteLedger($ledgerId)
    {
        $ledger = ElucidaLedger::findOrFail($ledgerId);

        // RBAC: Only admins or the owner can delete
        if (auth()->user()->role?->role_name !== 'administrator' && $ledger->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        $ledger->delete();
        session()->flash('success', 'Ledger permanently deleted.');
    }

    public function render()
    {
        $query = ElucidaLedger::with('user');

        if (auth()->user()->role?->role_name === 'organization') {
            $query->where('user_id', auth()->id());
        }

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('period_name', 'like', '%' . $this->search . '%')
                  ->orWhereHas('user', function($userQuery) {
                      $userQuery->where('name', 'ILIKE', '%' . $this->search . '%');
                  });
            });
        }

        $organizations = User::whereHas('role', function($q) {
            $q->where('role_name', 'organization');
        })->orderBy('name')->get();

        $ledgers = $query->latest()->paginate(10);

        return view('livewire.elucida.financial-roster', compact('ledgers', 'organizations'));
    }
}
