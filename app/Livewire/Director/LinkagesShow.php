<?php

namespace App\Livewire\Director;

use Livewire\Component;
use Livewire\Attributes\Layout; 

#[Layout('layouts.madya-template')]
class LinkagesShow extends Component
{
    public $partnerId;
    public $partner;

    public function mount($id)
    {
        $this->partnerId = $id;

        // Simulated Data Fetch
        $this->partner = [
            'id' => $id,
            'name' => 'LGU Legazpi City',
            'type' => 'Government Unit',
            'status' => 'MOU Signed',
            'since' => 'September 2023',
            'logo' => 'https://ui-avatars.com/api/?name=Legazpi+City&background=0D47A1&color=fff&size=256',
            'cover_img' => 'https://images.unsplash.com/photo-1577962917302-cd874c4e31d2?q=80&w=1000',
            'description' => 'A strategic partnership focused on empowering the youth of Legazpi through leadership governance workshops and local policy integration. This collaboration aims to bridge the gap between student leaders and local policy makers.',
            'scope' => 'Local Policy, Youth Welfare, Civic Engagement',
            'contact' => [
                'email' => 'youth@legazpi.gov.ph',
                'website' => 'www.legazpi.gov.ph',
                'address' => 'City Hall Compound, Albay District, Legazpi City'
            ],
            'engagements' => [
                [
                    'title' => 'Annual Youth Development Plan Planning',
                    'date' => 'Oct 2024',
                    'type' => 'Policy Workshop',
                    'desc' => 'Consultative meeting for the inclusion of student welfare in the 2025 budget.'
                ],
                [
                    'title' => 'Memorandum of Understanding Signing',
                    'date' => 'Sept 15, 2024',
                    'type' => 'Formalization',
                    'desc' => 'Official signing ceremony attended by the City Mayor and BU MADYA Executive Board.'
                ],
                [
                    'title' => 'Legazpi Youth Summit 2024',
                    'date' => 'Aug 10, 2024',
                    'type' => 'Co-Host',
                    'desc' => 'Provided 30 student facilitators for the city-wide leadership bootcamp.'
                ]
            ],
            'joint_projects' => [
                ['title' => 'Vote w/o Regrets', 'img' => 'https://images.unsplash.com/photo-1540910419868-474947cebacb?q=80&w=1000'],
                ['title' => 'Green Bicol Initiative', 'img' => 'https://images.unsplash.com/photo-1542601906990-b4d3fb7d5763?q=80&w=1000']
            ],
            'sdgs' => [
                ['id' => 11, 'color' => 'bg-orange-500', 'label' => 'Sustainable Cities'],
                ['id' => 17, 'color' => 'bg-blue-900', 'label' => 'Partnerships'],
            ]
        ];
    }

    public function render()
    {
        return view('livewire.director.linkages-show');
    }
}
