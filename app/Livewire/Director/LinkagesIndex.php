<?php

namespace App\Livewire\Director;

use Livewire\Component;
use Livewire\Attributes\Layout; 

#[Layout('layouts.madya-template')]
class LinkagesIndex extends Component
{
    public $category = 'All';

    public function setCategory($cat)
    {
        $this->category = $cat;
    }

    public function render()
    {
        // 1. LINKAGES (The Partners)
        $allPartners = [
            [
                'name' => 'LGU Legazpi City',
                'type' => 'Government',
                'status' => 'MOU Signed',
                'logo' => 'https://ui-avatars.com/api/?name=Legazpi+City&background=0D47A1&color=fff&size=128', // Replace with real logo
                'desc' => 'Strategic partnership for youth development programs and city-wide leadership summits.',
                'scope' => 'Local Policy, Youth Welfare'
            ],
            [
                'name' => 'National Youth Commission',
                'type' => 'Government',
                'status' => 'Accredited',
                'logo' => 'https://ui-avatars.com/api/?name=NYC&background=ef4444&color=fff&size=128',
                'desc' => 'Official accreditation ensuring BU MADYA\'s alignment with national youth development goals.',
                'scope' => 'Policy Advocacy, Leadership'
            ],
            [
                'name' => 'Angat Buhay NGO',
                'type' => 'NGO',
                'status' => 'Formal Partner',
                'logo' => 'https://ui-avatars.com/api/?name=Angat+Buhay&background=ec4899&color=fff&size=128',
                'desc' => 'Collaboration on nutritional programs and community pantry initiatives in Albay.',
                'scope' => 'Community Outreach'
            ],
            [
                'name' => 'DepEd Region V',
                'type' => 'Government',
                'status' => 'Project-based',
                'logo' => 'https://ui-avatars.com/api/?name=DepEd&background=ea580c&color=fff&size=128',
                'desc' => 'Joint implementation of the "Digital Literacy for Kids" program in public elementary schools.',
                'scope' => 'Education, Capacity Building'
            ],
            [
                'name' => 'UNESCO Club Philippines',
                'type' => 'International',
                'status' => 'Affiliate',
                'logo' => 'https://ui-avatars.com/api/?name=UNESCO&background=0284c7&color=fff&size=128',
                'desc' => 'Partnership focused on cultural heritage preservation and promotion of sustainable development goals.',
                'scope' => 'Culture, SDGs'
            ],
            [
                'name' => 'Bicol University CSC',
                'type' => 'Academic',
                'status' => 'Internal Partner',
                'logo' => 'https://ui-avatars.com/api/?name=BU+CSC&background=f59e0b&color=fff&size=128',
                'desc' => 'Coordination with the University Student Council for university-wide student welfare policies.',
                'scope' => 'Student Rights, Campus Policy'
            ],
        ];

        // 2. ENGAGEMENTS (The Activities)
        $engagements = [
            [
                'title' => 'MOU Signing Ceremony',
                'partner' => 'LGU Legazpi City',
                'date' => 'Sept 15, 2024',
                'type' => 'Formalization',
                'desc' => 'Official signing of the Memorandum of Understanding to solidify the partnership for A.Y. 2024-2025.'
            ],
            [
                'title' => 'Bicol Youth Summit',
                'partner' => 'National Youth Commission',
                'date' => 'Aug 10, 2024',
                'type' => 'Co-Host',
                'desc' => 'Co-hosted the regional summit attended by 500+ youth leaders discussing local governance.'
            ],
            [
                'title' => 'Brigada Eskwela 2024',
                'partner' => 'DepEd Region V',
                'date' => 'July 2024',
                'type' => 'Volunteering',
                'desc' => 'Mobilized 50 volunteers to assist in the cleaning and repainting of classrooms in Daraga.'
            ]
        ];

        // Filter Logic
        $partners = collect($allPartners)->filter(function ($item) {
            return $this->category === 'All' || $item['type'] === $this->category;
        });

        return view('livewire.director.linkages-index', [
            'partners' => $partners,
            'engagements' => $engagements
        ]);
    }
}
