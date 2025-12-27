<?php

namespace App\Livewire\Director;

use Livewire\Component;
use Livewire\Attributes\Layout; 
use Illuminate\Support\Facades\Auth;
use App\Models\Project;
use App\Models\Proposal;
use App\Models\User;
use App\Models\Linkage; 

#[Layout('layouts.madya-admin')]  
class Dashboard extends Component
{
    public function render()
    {
        return view('dashboard', [
            'pendingProposals' => Proposal::where('status', 'pending review')->count(),
            'activeProjects'   => Project::where('status', 'Ongoing')->count(),
            'totalPartners'    => class_exists(Linkage::class) ? Linkage::count() : 0, 
            'totalUsers'       => User::count(),
            'recentProposals'  => Proposal::where('status', 'pending review')->latest()->take(5)->get(),
            'upcomingProjects' => Project::where('status', 'Upcoming')->orderBy('implementation_date', 'asc')->take(3)->get(),
            'myProjects'       => Project::whereHas('proponents', function($q) {
                                        $q->where('user_id', Auth::id());
                                  })
                                  ->orderBy('implementation_date', 'desc') // Most recent first
                                  ->get()
        ]);
    }
}