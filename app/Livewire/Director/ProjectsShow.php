<?php

namespace App\Livewire\Director;

use Livewire\Component;
use Livewire\Attributes\Layout; 

#[Layout('layouts.madya-template')]
class ProjectsShow extends Component
{
    public $projectId;
    public $project;

    public function mount($id)
    {
        $this->projectId = $id;

        // Simulated Data Fetch
        $this->project = [
            'id' => $id,
            'title' => 'Project Akay: Community Pantry',
            'cat' => 'Community Outreach',
            'status' => 'Completed',
            'date' => 'October 15, 2024',
            'location' => 'Daraga, Albay',
            'beneficiaries' => '150 Families',
            
            // 👇 NEW FIELDS ADDED
            'proponent' => 'Committee on Strategic Initiatives', // Who led this?
            'partners' => [
                'LGU Daraga',
                'Bicol University CSC',
                'Rotary Club of Legazpi'
            ],
            'sdgs' => [
                ['id' => 1, 'color' => 'bg-red-500', 'label' => 'No Poverty'],
                ['id' => 2, 'color' => 'bg-yellow-500', 'label' => 'Zero Hunger'],
                ['id' => 17, 'color' => 'bg-blue-800', 'label' => 'Partnerships'],
            ],
            
            'img' => 'https://images.unsplash.com/photo-1593113598332-cd288d649433?q=80&w=1000',
            'description' => 'Project Akay was a grassroots initiative...',
            'objectives' => [
                'Provide immediate food relief to 150 households.',
                'Distribute hygiene kits to prevent post-disaster diseases.',
                'Foster spirit of volunteerism among BU students.'
            ],
            'impact_stats' => [
                ['label' => 'Volunteers', 'value' => '45'],
                ['label' => 'Funds Raised', 'value' => '₱25,000'],
                ['label' => 'Packs Distributed', 'value' => '150'],
            ],
            'gallery' => [
                'https://images.unsplash.com/photo-1488521787991-ed7bbaae773c?q=80&w=1000',
                'https://images.unsplash.com/photo-1469571486292-0ba58a3f068b?q=80&w=1000',
                'https://images.unsplash.com/photo-1593113646773-028c64a8d1b8?q=80&w=1000',
            ]
        ];
    }

    public function render()
    {
        return view('livewire.director.projects-show');
    }
}
