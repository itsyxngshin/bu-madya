<?php

namespace App\Livewire\Director;

use Livewire\Component;
use Livewire\Attributes\Layout; 

#[Layout('layouts.madya-template')]
class ProjectCreate extends Component
{
   // Basic Info
    public $title = 'New Project Title';
    public $cat = 'Community Outreach';
    public $status = 'Upcoming';
    public $date;
    public $location = '';
    public $beneficiaries = '';
    public $proponent = '';
    public $description = '';
    public $coverImg = 'https://images.unsplash.com/photo-1559027615-cd4628902d4a?q=80&w=2074';

    // Dynamic Lists
    public $partners = [''];
    public $objectives = [''];
    public $impact_stats = [
        ['label' => 'Beneficiaries', 'value' => '0']
    ];
    
    // SDGs (Selected IDs)
    public $selectedSdgs = [];

    // Static Data for SDG Selector
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

    public function mount()
    {
        $this->date = now()->format('F Y');
    }

    // --- Dynamic List Logic ---

    public function addPartner() { $this->partners[] = ''; }
    public function removePartner($index) { unset($this->partners[$index]); $this->partners = array_values($this->partners); }

    public function addObjective() { $this->objectives[] = ''; }
    public function removeObjective($index) { unset($this->objectives[$index]); $this->objectives = array_values($this->objectives); }

    public function addStat() { $this->impact_stats[] = ['label' => '', 'value' => '']; }
    public function removeStat($index) { unset($this->impact_stats[$index]); $this->impact_stats = array_values($this->impact_stats); }

    public function toggleSdg($id)
    {
        if (in_array($id, $this->selectedSdgs)) {
            $this->selectedSdgs = array_diff($this->selectedSdgs, [$id]);
        } else {
            $this->selectedSdgs[] = $id;
        }
    }

    public function render()
    {
        return view('livewire.director.projects-create');
    }
}
