<?php

namespace App\Livewire\Director;

use Livewire\Component;
use Livewire\Attributes\Layout; 

#[Layout('layouts.madya-template')]
class ProjectsIndex extends Component
{
    public $category = 'All';

    public function setCategory($cat)
    {
        $this->category = $cat;
    }

    public function render()
    {
        // Simulated Database Data
        $allProjects = [
            [
                'id' => 1,
                'title' => 'Project Akay: Community Pantry',
                'cat' => 'Community Outreach',
                'status' => 'Completed',
                'date' => 'Oct 2024',
                'location' => 'Daraga, Albay',
                'beneficiaries' => '150 Families',
                'img' => 'https://images.unsplash.com/photo-1593113598332-cd288d649433?q=80&w=1000',
                'desc' => 'A grassroots initiative providing food packs and hygiene kits to marginalized families affected by recent typhoons.'
            ],
            [
                'id' => 2,
                'title' => 'Green Bicol: Mangrove Planting',
                'cat' => 'Environmental',
                'status' => 'Ongoing',
                'date' => 'Dec 2024 - Feb 2025',
                'location' => 'Bacacay, Albay',
                'beneficiaries' => 'Coastal Areas',
                'img' => 'https://images.unsplash.com/photo-1542601906990-b4d3fb7d5763?q=80&w=1000',
                'desc' => 'A long-term environmental rehabilitation program aimed at restoring mangrove forests to protect coastal communities.'
            ],
            [
                'id' => 3,
                'title' => 'Youth Policy Forum 2025',
                'cat' => 'Policy Advocacy',
                'status' => 'Upcoming',
                'date' => 'March 15, 2025',
                'location' => 'BU Amphitheater',
                'beneficiaries' => '300 Students',
                'img' => 'https://images.unsplash.com/photo-1544531586-fde5298cdd40?q=80&w=1000',
                'desc' => 'A university-wide convention discussing the role of student leaders in crafting local youth ordinances.'
            ],
            [
                'id' => 4,
                'title' => 'Digital Literacy for Kids',
                'cat' => 'Capacity Building',
                'status' => 'Ongoing',
                'date' => 'Every Saturday',
                'location' => 'Guinobatan, Albay',
                'beneficiaries' => '50 Children',
                'img' => 'https://images.unsplash.com/photo-1516321318423-f06f85e504b3?q=80&w=1000',
                'desc' => 'Teaching basic computer skills and internet safety to elementary students in remote barangays.'
            ],
            [
                'id' => 5,
                'title' => 'LGU Legazpi MOU Signing',
                'cat' => 'Partnership',
                'status' => 'Completed',
                'date' => 'Sep 2024',
                'location' => 'Legazpi City Hall',
                'beneficiaries' => 'Organization Wide',
                'img' => 'https://images.unsplash.com/photo-1577962917302-cd874c4e31d2?q=80&w=1000',
                'desc' => 'Formalizing the partnership between BU MADYA and the local government for future youth development projects.'
            ],
            [
                'id' => 6,
                'title' => 'Vote w/o Regrets Campaign',
                'cat' => 'Policy Advocacy',
                'status' => 'Upcoming',
                'date' => 'April 2025',
                'location' => 'Bicol Region',
                'beneficiaries' => 'General Public',
                'img' => 'https://images.unsplash.com/photo-1540910419868-474947cebacb?q=80&w=1000',
                'desc' => 'An information drive focused on voter education and registration for the upcoming midterm elections.'
            ],
        ];

        // Filter Logic
        $projects = collect($allProjects)->filter(function ($item) {
            return $this->category === 'All' || $item['cat'] === $this->category;
        });

        return view('livewire.director.projects-index', [
            'projects' => $projects
        ]);
    }
}
