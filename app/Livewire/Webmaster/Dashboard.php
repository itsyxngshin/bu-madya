<?php

namespace App\Livewire\Webmaster;

use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.madya-template')]
class Dashboard extends Component
{
    public function render()
    {
        return view('livewire.webmaster.dashboard');
    }
}
