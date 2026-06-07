<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Activity;

class PublicActivityShow extends Component
{
    public $activity;

    public function mount($slug)
    {
        $this->activity = Activity::with(['user', 'sdgs', 'focals', 'participants'])
                                  ->where('slug', $slug)
                                  ->firstOrFail();
    }

    public function render()
    {
        return view('livewire.public-activity-show')->layout('layouts.madya-template');
    }
}
