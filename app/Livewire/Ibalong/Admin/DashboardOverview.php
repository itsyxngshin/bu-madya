<?php

namespace App\Livewire\Ibalong\Admin;

use Livewire\Component;
use App\Models\IbalongRegistration;
use App\Models\IbalongSetting;

class DashboardOverview extends Component
{
    public $stats = [];
    public $isRegistrationOpen = false;

    public function mount()
    {
        // Fetch or create the initial setting record
        $setting = IbalongSetting::firstOrCreate(
            ['id' => 1],
            ['is_registration_open' => true]
        );
        $this->isRegistrationOpen = $setting->is_registration_open;

        // Fetch your existing stats
        $this->stats = [
            'total' => IbalongRegistration::count(),
            'pending' => IbalongRegistration::where('status', 'pending')->count(),
            'approved' => IbalongRegistration::where('status', 'approved')->count(),
            'rejected' => IbalongRegistration::where('status', 'rejected')->count(),
        ];
    }

    public function toggleRegistration()
    {
        // Security Check: Only Super Admins (1) or Admins (2) can toggle this
        if (!in_array(auth('ibalong')->user()->role_id, [1, 2])) {
            session()->flash('error', 'UNAUTHORIZED: You do not have clearance to lock the portal.');
            return;
        }

        $setting = IbalongSetting::find(1);
        $setting->update([
            'is_registration_open' => !$setting->is_registration_open
        ]);

        $this->isRegistrationOpen = $setting->is_registration_open;

        $statusMessage = $this->isRegistrationOpen ? 'Registration portal is now OPEN.' : 'Registration portal is now LOCKED.';
        session()->flash('success', $statusMessage);
    }

    public function render()
    {
        return view('livewire.ibalong.admin.dashboard-overview')->layout('layouts.dashboard');
    }
}
