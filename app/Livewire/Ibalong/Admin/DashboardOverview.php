<?php

namespace App\Livewire\Ibalong\Admin;

use Livewire\Component;
use App\Models\IbalongRegistration;

class DashboardOverview extends Component
{
    public function render()
    {
        $stats = [
            'pending' => IbalongRegistration::where('status', 'pending')->count(),
            'approved' => IbalongRegistration::where('status', 'approved')->count(),
            'rejected' => IbalongRegistration::where('status', 'rejected')->count(),
            'total' => IbalongRegistration::count(),
        ];

        return view('livewire.ibalong.admin.dashboard-overview', [
            'stats' => $stats
        ])->layout('layouts.dashboard');
    }
}