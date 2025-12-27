<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Proposal;
use Livewire\Attributes\Layout;

#[Layout('layouts.madya-template')] 
class ProposalsIndex extends Component
{
    use WithPagination;

    // Search & Filter
    public $search = '';
    public $statusFilter = ''; // 'pending review', 'approved', etc.

    // Reset pagination when searching
    public function updatedSearch() { $this->resetPage(); }
    public function updatedStatusFilter() { $this->resetPage(); }

    public function render()
    {
        $proposals = Proposal::query()
            ->with(['user', 'college']) // Eager load for performance
            ->when($this->search, function ($q) {
                $q->where('title', 'like', '%' . $this->search . '%')
                  ->orWhere('name', 'like', '%' . $this->search . '%') // Guest Name
                  ->orWhereHas('user', function ($u) {                 // Registered User Name
                      $u->where('name', 'like', '%' . $this->search . '%');
                  });
            })
            ->when($this->statusFilter, function ($q) {
                $q->where('status', $this->statusFilter);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.admin.proposals-index', [
            'proposals' => $proposals
        ]);
    }
}