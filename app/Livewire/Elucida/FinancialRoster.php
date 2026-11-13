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

    // Modal state
    public $showCreateModal = false;
    public $period_name = '';
    public $target_user_id = null; // Changed from organization_name
    public $beginning_bank = 0;
    public $beginning_cash = 0;

    public function mount()
    {
        // Auto-lock to the logged-in org
        if (auth()->user()->role?->role_name === 'organization') {
            $this->target_user_id = auth()->id();
        }
    }

    public function updatingSearch() { $this->resetPage(); }

    public function createLedger()
    {
        $this->validate([
            'period_name' => 'required|string|max:255',
            'target_user_id' => 'required|exists:users,id',
            'beginning_bank' => 'required|numeric|min:0',
            'beginning_cash' => 'required|numeric|min:0',
        ]);

        $ledger = ElucidaLedger::create([
            'period_name' => $this->period_name,
            'user_id' => $this->target_user_id,
            'beginning_bank' => $this->beginning_bank,
            'beginning_cash' => $this->beginning_cash,
            'status' => 'draft'
        ]);

        $this->reset(['showCreateModal', 'period_name', 'beginning_bank', 'beginning_cash']);

        session()->flash('success', 'New Financial Ledger successfully forged.');
        return redirect()->route('elucida.board', $ledger); // Uses UUID binding
    }

    public function render()
    {
        // Eager load the user relation to prevent N+1 queries
        $query = ElucidaLedger::with('user');

        // RBAC Protocol: Organizations only see their own ledgers
        if (auth()->user()->role?->role_name === 'organization') {
            $query->where('user_id', auth()->id());
        }

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('period_name', 'like', '%' . $this->search . '%')
                  // Search through the relationship using ILIKE for Postgres safety
                  ->orWhereHas('user', function($userQuery) {
                      $userQuery->where('name', 'ILIKE', '%' . $this->search . '%');
                  });
            });
        }

        // Fetch organizations for the admin dropdown
        $organizations = User::whereHas('role', function($q) {
            $q->where('role_name', 'organization');
        })->orderBy('name')->get();

        $ledgers = $query->latest()->paginate(10);

        return view('livewire.elucida.financial-roster', compact('ledgers', 'organizations'));
    }
}
