<?php

namespace App\Livewire\Open;

use Livewire\Component;
use Livewire\Attributes\Layout; 

#[Layout('layouts.madya-template')]
class Directory extends Component
{
    public function render()
    {
        return view('livewire.open.directory');
    }
}
