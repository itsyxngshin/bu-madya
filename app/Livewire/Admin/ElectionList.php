<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Election;

class ElectionList extends Component
{
    use WithPagination;

    public $search = '';

    // Reset pagination when searching
    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function deleteElection($id)
    {
        $election = Election::withCount('votes')->findOrFail($id);

        // SAFETY LOCK: Prevent deletion if votes exist
        if ($election->votes_count > 0) {
            session()->flash('error', "Cannot delete '{$election->title}'. Ballots have already been cast.");
            return;
        }

        $election->delete();
        session()->flash('success', 'Election deleted successfully.');
    }

    public function render()
    {
        // Fetch elections. Include the count of candidates and voters for the UI.
        $query = Election::query()
            ->withCount(['candidates', 'voterLogs'])
            ->where('title', 'like', '%' . $this->search . '%')
            ->latest();

        // Multi-tenant check: If they aren't a super-admin, only show THEIR elections
        if (auth()->user()->role?->role_name !== 'administrator') {
            $query->where('user_id', auth()->id());
        }

        return view('livewire.admin.election-list', [
            'elections' => $query->paginate(9)
        ])->layout('layouts.madya-admin-deck');
    }
}