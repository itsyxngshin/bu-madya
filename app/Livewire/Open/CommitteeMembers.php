<?php

namespace App\Livewire\Open;

use Livewire\Component;
use App\Models\Committee;
use App\Models\CommitteeMember;

class CommitteeMembers extends Component
{
    public Committee $committee;
    public $search = '';

    public function mount(Committee $committee)
    {
        // Eager load the leadership to prevent N+1 queries in the Hero
        $this->committee = $committee->load('directorAssignments.user', 'directorAssignments.director');
    }

    public function render()
    {
        // Fetch members and filter by the related User's name if a search exists
        $members = CommitteeMember::with('user')
            ->where('committee_id', $this->committee->id)
            ->when($this->search, function($query) {
                $query->whereHas('user', function($q) {
                    $q->where('name', 'like', '%' . $this->search . '%');
                });
            })
            ->get();

        return view('livewire.open.committee-members', compact('members'))
            ->layout('layouts.madya-template');
    }
}