<?php

namespace App\Livewire\Open;

use Livewire\Component;
use Illuminate\Support\Facades\Cache;
use Livewire\Attributes\Layout; 

#[Layout('layouts.madya-template')]
class LandingPage extends Component
{
    public $visitorCount = 1;
    public function mount()
    {
        #$this->visitorCount = Cache::increment('website_visitor_count');
    }

    public function render()
    {
        return view('livewire.open.landing-page');
    }
}