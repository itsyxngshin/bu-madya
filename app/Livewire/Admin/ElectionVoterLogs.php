<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Election;
use App\Models\VoterLog;

class ElectionVoterLogs extends Component
{
    use WithPagination;

    public Election $election;

    // Search & Filtering State
    public $search = '';
    public $filterType = 'all'; // 'all', 'registered', 'guest'

    public function mount(Election $election)
    {
        // Security check: Only admins or the election creator can view logs
        if (auth()->user()->role?->role_name !== 'administrator' && $election->user_id !== auth()->id()) {
            abort(403, 'Unauthorized access.');
        }

        $this->election = $election;
    }

    // Reset pagination when searching or filtering
    public function updatingSearch() { $this->resetPage(); }
    public function updatingFilterType() { $this->resetPage(); }

    public function render()
    {
        $logs = VoterLog::with(['user', 'college'])
            ->where('election_id', $this->election->id)
            ->when($this->search, function($query) {
                $query->where(function($q) {
                    // Search Guest fields
                    $q->where('guest_name', 'like', '%'.$this->search.'%')
                      ->orWhere('guest_email', 'like', '%'.$this->search.'%')
                      ->orWhere('program', 'like', '%'.$this->search.'%')
                      // Search Registered User fields
                      ->orWhereHas('user', function($uq) {
                          $uq->where('name', 'like', '%'.$this->search.'%')
                             ->orWhere('email', 'like', '%'.$this->search.'%');
                      });
                });
            })
            ->when($this->filterType === 'registered', fn($q) => $q->whereNotNull('user_id'))
            ->when($this->filterType === 'guest', fn($q) => $q->whereNull('user_id'))
            ->latest('voted_at') // Newest votes first
            ->paginate(15);

        $totalTurnout = VoterLog::where('election_id', $this->election->id)->count();

        return view('livewire.admin.election-voter-logs', [
            'logs' => $logs,
            'totalTurnout' => $totalTurnout
        ])->layout('layouts.madya-admin-deck');
    }
}
