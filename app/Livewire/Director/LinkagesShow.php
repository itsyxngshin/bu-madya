<?php

namespace App\Livewire\Director;

use Livewire\Component;
use App\Models\Linkage;
use Livewire\Attributes\Layout;

#[Layout('layouts.madya-template')] // Ensure this matches your layout name
class LinkagesShow extends Component
{
    public Linkage $linkage;

    public function mount(Linkage $linkage)
    {
        // Eager load all relationships to minimize database queries
        $this->linkage = $linkage->load([
            'type', 
            'status', 
            'activities' => function($q) {
                $q->latest('activity_date');
            },
            'sdgs',
            // Assuming you have a 'projects' relationship defined in Linkage model
            // 'projects' 
        ]);
    }

    public function render()
    {
        return view('livewire.director.linkages-show');
    }
}