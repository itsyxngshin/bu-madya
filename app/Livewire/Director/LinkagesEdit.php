<?php

namespace App\Livewire\Director;

use Livewire\Component;
use Livewire\Attributes\Layout; 

#[Layout('layouts.madya-template')]
class LinkagesEdit extends Component
{
    public $partnerId;

    // Form Fields (Same as Create)
    public $name;
    public $type;
    public $status;
    public $since;
    public $logo;
    public $cover_img;
    public $description;
    public $scope;
    public $email;
    public $website;
    public $address;

    // Dynamic Lists
    public $engagements = [];
    public $joint_projects = [];

    // SDGs
    public $selectedSdgs = [];
    public $allSdgs = [
        1 => ['label' => 'No Poverty', 'color' => 'bg-red-500'],
        2 => ['label' => 'Zero Hunger', 'color' => 'bg-yellow-500'],
        3 => ['label' => 'Good Health', 'color' => 'bg-green-500'],
        4 => ['label' => 'Quality Education', 'color' => 'bg-red-700'],
        5 => ['label' => 'Gender Equality', 'color' => 'bg-orange-500'],
        6 => ['label' => 'Clean Water', 'color' => 'bg-cyan-500'],
        7 => ['label' => 'Clean Energy', 'color' => 'bg-yellow-400'],
        8 => ['label' => 'Decent Work', 'color' => 'bg-red-800'],
        9 => ['label' => 'Industry/Infras.', 'color' => 'bg-orange-600'],
        10 => ['label' => 'Reduced Inequal.', 'color' => 'bg-pink-500'],
        11 => ['label' => 'Sustainable Cities', 'color' => 'bg-orange-400'],
        12 => ['label' => 'Consumption', 'color' => 'bg-yellow-600'],
        13 => ['label' => 'Climate Action', 'color' => 'bg-green-700'],
        14 => ['label' => 'Life Below Water', 'color' => 'bg-blue-500'],
        15 => ['label' => 'Life on Land', 'color' => 'bg-green-600'],
        16 => ['label' => 'Peace & Justice', 'color' => 'bg-blue-700'],
        17 => ['label' => 'Partnerships', 'color' => 'bg-blue-900'],
    ];

    public function mount($id)
    {
        $this->partnerId = $id;
        $this->loadModel();
    }

    public function loadModel()
    {
        // SIMULATED DB FETCH
        // In real app: $partner = Partner::findOrFail($this->partnerId);
        
        $mockPartner = [
            'name' => 'LGU Legazpi City',
            'type' => 'Government',
            'status' => 'MOU Signed',
            'since' => 'September 2023',
            'logo' => 'https://ui-avatars.com/api/?name=Legazpi+City&background=0D47A1&color=fff&size=256',
            'cover_img' => 'https://images.unsplash.com/photo-1577962917302-cd874c4e31d2?q=80&w=1000',
            'description' => 'A strategic partnership focused on empowering the youth of Legazpi through leadership governance workshops.',
            'scope' => 'Local Policy, Youth Welfare',
            'email' => 'youth@legazpi.gov.ph',
            'website' => 'www.legazpi.gov.ph',
            'address' => 'City Hall Compound, Albay District, Legazpi City',
            'engagements' => [
                ['title' => 'MOU Signing', 'date' => 'Sept 15, 2024', 'type' => 'Formalization', 'desc' => 'Official signing ceremony.']
            ],
            'selectedSdgs' => [11, 17] // IDs of selected SDGs
        ];

        // Assign to properties
        $this->name = $mockPartner['name'];
        $this->type = $mockPartner['type'];
        $this->status = $mockPartner['status'];
        $this->since = $mockPartner['since'];
        $this->logo = $mockPartner['logo'];
        $this->cover_img = $mockPartner['cover_img'];
        $this->description = $mockPartner['description'];
        $this->scope = $mockPartner['scope'];
        $this->email = $mockPartner['email'];
        $this->website = $mockPartner['website'];
        $this->address = $mockPartner['address'];
        $this->engagements = $mockPartner['engagements'];
        $this->selectedSdgs = $mockPartner['selectedSdgs'];
    }

    // --- Dynamic List Actions (Same as Create) ---

    public function addEngagement()
    {
        $this->engagements[] = ['title' => '', 'date' => '', 'type' => '', 'desc' => ''];
    }

    public function removeEngagement($index)
    {
        unset($this->engagements[$index]);
        $this->engagements = array_values($this->engagements);
    }

    public function toggleSdg($id)
    {
        if (in_array($id, $this->selectedSdgs)) {
            $this->selectedSdgs = array_diff($this->selectedSdgs, [$id]);
        } else {
            $this->selectedSdgs[] = $id;
        }
    }

    public function update()
    {
        // DB Update Logic Here: Partner::find($id)->update(...)
        session()->flash('message', 'Partner profile updated successfully!');
    }

    public function render()
    {
        return view('livewire.director.linkages-edit');
    }
}
