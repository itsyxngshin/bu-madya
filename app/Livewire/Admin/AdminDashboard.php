<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\User;
use App\Models\Proposal;
use App\Models\Project;
use App\Models\Event;
use Illuminate\Support\Facades\Schema;

#[Layout('layouts.madya-admin-deck')]
class AdminDashboard extends Component
{
    public function render()
    {
        // 1. Organization & Regular Users
        try {
            $orgsCount = User::whereHas('role', function($q) {
                $q->where('role_name', 'organization');
            })->count();

            $regularUsersCount = User::whereHas('role', function($q) {
                $q->whereIn('role_name', ['user', 'regular']);
            })->count();
        } catch (\Exception $e) {
            $orgsCount = 0;
            $regularUsersCount = 0;
        }

        // 2. Platform Modules
        $eventsCount = class_exists(Event::class) ? Event::count() : 0;
        $evaluationsCount = class_exists(\App\Models\Evaluation::class) ? \App\Models\Evaluation::count() : (class_exists(\App\Models\EvaluationForm::class) ? \App\Models\EvaluationForm::count() : 0);
        $framesCount = class_exists(\App\Models\EventFrame::class) ? \App\Models\EventFrame::count() : 0;

        return view('livewire.admin.admin-dashboard', [
            // Old Key Counts (Preserved)
            'totalUsers'       => User::count(),
            'pendingProposals' => Proposal::where('status', 'pending review')->count(),
            'activeProjects'   => Project::where('status', 'Ongoing')->count(),

            // New Key Counts
            'orgsCount'         => $orgsCount,
            'regularUsersCount' => $regularUsersCount,
            'eventsCount'       => $eventsCount,
            'projectsCount'     => Project::count(),
            'evaluationsCount'  => $evaluationsCount,
            'framesCount'       => $framesCount,

            // Newest Registered Users (for User Management context)
            'newUsers'         => User::latest()->take(5)->get(),

            // Recent System Activity
            'recentProposals'  => Proposal::where('status', 'pending review')->latest()->take(4)->get(),
            'latestEvents'     => class_exists(Event::class) ? Event::latest()->take(4)->get() : collect(),
            'latestProjects'   => Project::latest()->take(4)->get()
        ]);
    }
}
