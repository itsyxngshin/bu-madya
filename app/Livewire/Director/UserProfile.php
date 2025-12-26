<?php

namespace App\Livewire\Director;

use Livewire\Component;
use App\Models\User;
use Livewire\Attributes\Layout; 

#[Layout('layouts.madya-template')]
class UserProfile extends Component
{
    public $user;
    public $profile;
    public $engagements;
    public $portfolios;

    public function mount($username)
    {
        // Find user by username, or fail (404 page)
        $this->user = User::where('username', $username)
            ->with(['profile.college', 'profile.portfolios', 'engagements', 'directorAssignment.director'])
            ->firstOrFail();

        $this->profile = $this->user->profile;
        $this->engagements = $this->user->engagements;
        $this->portfolios = $this->profile->portfolios;
    }

    public function render()
    {
        return view('livewire.director.user-profile');
    }
}