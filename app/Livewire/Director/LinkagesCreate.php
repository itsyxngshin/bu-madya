<?php

namespace App\Livewire\Director;

use Livewire\Component;
use Livewire\Attributes\Layout; 

#[Layout('layouts.madya-template')]
class LinkagesCreate extends Component
{
    // 1. Identity & Meta
    public $name = 'New Partner Name';
    public $type = 'Government';
    public $status = 'MOU Signed';
    public $since;
    public $logo = 'https://ui-avatars.com/api/?name=Partner&background=0D47A1&color=fff&size=256';
    public $cover_img = 'https://images.unsplash.com/photo-1577962917302-cd874c4e31d2?q=80&w=1000';
    
    // 2. About & Scope
    public $description = '';
    public $scope = 'Youth Welfare, Policy';
    
    // 3. Contact Info
    public $email = '';
    public $website = '';
    public $address = '';

    // 4. Dynamic Lists
    public $engagements = [
        ['title' => 'Initial Meeting', 'date' => 'Jan 2025', 'type' => 'Meeting', 'desc' => '']
    ];
    
    public $joint_projects = [
        ['title' => 'Project Name', 'img' => '']
    ];

    // 5. SDGs
    public $selectedSdgs = [];
    public $allSdgs = [
        1 => ['label' => 'No Poverty', 'color' => 'bg-red-500'],
        2 => ['label' => 'Zero Hunger', 'color' => 'bg-yellow-500'],
        // ... (You can copy the full list from Project Builder if needed) ...
        17 => ['label' => 'Partnerships', 'color' => 'bg-blue-900'],
    ];

    public function mount()
    {
        $this->since = now()->format('F Y');
    }

    // --- Actions ---

    public function addEngagement()
    {
        $this->engagements[] = ['title' => '', 'date' => '', 'type' => '', 'desc' => ''];
    }

    public function removeEngagement($index)
    {
        unset($this->engagements[$index]);
        $this->engagements = array_values($this->engagements);
    }

    public function addProject()
    {
        $this->joint_projects[] = ['title' => '', 'img' => ''];
    }

    public function removeProject($index)
    {
        unset($this->joint_projects[$index]);
        $this->joint_projects = array_values($this->joint_projects);
    }

    public function toggleSdg($id)
    {
        if (in_array($id, $this->selectedSdgs)) {
            $this->selectedSdgs = array_diff($this->selectedSdgs, [$id]);
        } else {
            $this->selectedSdgs[] = $id;
        }
    }

    public function save()
    {
        // Save logic here
        session()->flash('message', 'Partner profile saved!');
    }

    public function render()
    {
        return view('livewire.director.linkages-create');
    }
}
