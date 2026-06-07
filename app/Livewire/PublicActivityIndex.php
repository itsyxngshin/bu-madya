<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Activity;

class PublicActivityIndex extends Component
{
    use WithPagination;

    public $search = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $activities = Activity::with(['user', 'sdgs', 'focals'])
            ->where('status', '!=', 'draft') // Optional: if you add a draft status later
            ->when($this->search, function ($query) {
                $query->where('title', 'like', '%' . $this->search . '%')
                      ->orWhere('nature_of_activity', 'like', '%' . $this->search . '%');
            })
            ->orderBy('start_date', 'desc')
            ->paginate(12);

        return view('livewire.public-activity-index', [
            'activities' => $activities
        ])->layout('layouts.madya-template'); // Ensure this matches your public layout
    }
}
