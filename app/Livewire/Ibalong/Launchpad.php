<? php

namespace App\Livewire\Ibalong;

use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.ibalong-layout')]
class Launchpad extends Component
{
    public function render()
    {
        return view('livewire.ibalong.launchpad');
    }
}