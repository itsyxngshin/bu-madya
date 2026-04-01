<?php

namespace App\Livewire\Open;

use Livewire\Component;
use App\Models\Committee;
use App\Models\CommitteeMember;
use Livewire\Attributes\Layout;

#[Layout('layouts.madya-template')]
class CommitteeMembers extends Component
{
    public Committee $committee;
    public $search = '';

    public function mount(Committee $committee)
    {
        // Eager load directly to college
        $this->committee = $committee->load([
            'directorAssignments.director',
            'directorAssignments.user.profile.college' 
        ]);
    }

    public function render()
    {
        // Fetch members and eager load directly to college
        $members = CommitteeMember::with(['user.profile.college'])
            ->where('committee_id', $this->committee->id)
            ->when($this->search, function($query) {
                $query->whereHas('user', function($q) {
                    $q->where('name', 'like', '%' . $this->search . '%');
                });
            })
            ->get();

        return view('livewire.open.committee-members', compact('members'));
    }
}