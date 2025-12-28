<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\User;
use App\Models\Proposal;
use App\Models\Project;
use Illuminate\Support\Facades\DB;

#[Layout('layouts.madya-admin-deck')]
class AdminDashboard extends Component
{
    public function render()
    {
        return view('livewire.admin.admin-dashboard', [
            // 1. Key Counts
            'totalUsers'       => User::count(),
            'pendingProposals' => Proposal::where('status', 'pending review')->count(),
            'activeProjects'   => Project::where('status', 'Ongoing')->count(),
            
            // 2. Newest Registered Users (for User Management context)
            'newUsers'         => User::latest()->take(5)->get(),

            // 3. Recent System Activity (Proposals needing attention)
            'recentProposals'  => Proposal::where('status', 'pending review')
                                    ->latest()
                                    ->take(4)
                                    ->get()
        ]);
    }
}