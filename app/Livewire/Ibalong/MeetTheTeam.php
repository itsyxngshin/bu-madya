<?php

namespace App\Livewire\Ibalong;

use Livewire\Component;
use App\Models\IbalongCommittee;

class MeetTheTeam extends Component
{
    public function render()
    {
        // Fetch committees that are active, eager loading their active members
        $committees = IbalongCommittee::with(['members' => function ($query) {
                $query->where('is_active', true)
                      // Alphabetical sort places 'Head' (H) before 'Member' (M)
                      ->orderBy('role', 'asc')
                      ->orderBy('display_order', 'asc');
            }])
            ->where('is_active', true)
            ->orderBy('display_order', 'asc')
            ->get()
            // Filter out committees that currently have no active members
            ->filter(function ($committee) {
                return $committee->members->count() > 0;
            });

        return view('livewire.ibalong.meet-the-team', [
            'committees' => $committees
        ])->layout('layouts.ibalong-layout');
    }
}
