<?php

namespace App\Livewire\Ibalong\Admin;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use App\Models\IbalongRegistration;
use App\Models\IbalongSetting;
use App\Models\IbalongTeamMember;
use App\Models\IbalongEventRegistration;

class DashboardOverview extends Component
{
    use WithFileUploads;

    public $stats = [];
    public $isRegistrationOpen = false;
    public $isAdmin = false;

    // --- Team View State ---
    public $team;
    public $teamLogo;
    public $memberPhotos = [];
    public $teamEvents = [];

    public function mount()
    {
        $user = auth('ibalong')->user();
        $this->isAdmin = in_array($user->role_id, [1, 2]);

        if ($this->isAdmin) {
            $setting = IbalongSetting::firstOrCreate(
                ['id' => 1],
                ['is_registration_open' => true]
            );
            $this->isRegistrationOpen = $setting->is_registration_open;

            $this->stats = [
                'total' => IbalongRegistration::count(),
                'pending' => IbalongRegistration::where('status', 'pending')->count(),
                'approved' => IbalongRegistration::where('status', 'approved')->count(),
                'rejected' => IbalongRegistration::where('status', 'rejected')->count(),
            ];
        } else {
            $this->loadTeamData();
        }
    }

    // Helper to fetch fresh data after an update
    private function loadTeamData()
    {
        $this->team = clone IbalongRegistration::where('user_id', auth('ibalong')->id())
                        ->with(['skills', 'members.skills'])
                        ->first();

        if ($this->team) {
            $this->teamEvents = IbalongEventRegistration::with(['event', 'attendances'])
                                    ->where('team_id', $this->team->id)
                                    ->get();
        }
    }

    public function toggleRegistration()
    {
        if (!$this->isAdmin) {
            session()->flash('error', 'UNAUTHORIZED: You do not have clearance to lock the portal.');
            return;
        }

        $setting = IbalongSetting::find(1);
        $setting->update(['is_registration_open' => !$setting->is_registration_open]);
        $this->isRegistrationOpen = $setting->is_registration_open;
        
        $statusMessage = $this->isRegistrationOpen ? 'Registration portal is now OPEN.' : 'Registration portal is now LOCKED.';
        session()->flash('success', $statusMessage);
    }

    // --- Image Upload Handlers ---
    public function updatedTeamLogo()
    {
        $this->validate(['teamLogo' => 'image|max:2048']); 
        
        if ($this->team->logo_path) {
            Storage::disk('public')->delete($this->team->logo_path);
        }
        
        $path = $this->teamLogo->store('team_logos', 'public');
        $this->team->update(['logo_path' => $path]);
        
        $this->teamLogo = null; // Clear the temp upload state
        $this->loadTeamData();  // Force refresh
        
        session()->flash('success', 'Team logo updated successfully!');
    }

    public function updatedMemberPhotos($value, $memberId)
    {
        $this->validate(['memberPhotos.'.$memberId => 'image|max:2048']);
        
        $member = IbalongTeamMember::find($memberId);
        
        if ($member && $member->team_id === $this->team->id) {
            if ($member->photo_path) {
                Storage::disk('public')->delete($member->photo_path);
            }
            
            $path = $this->memberPhotos[$memberId]->store('member_photos', 'public');
            $member->update(['photo_path' => $path]);
            
            unset($this->memberPhotos[$memberId]); // Clear temp upload state
            $this->loadTeamData(); // Force refresh
            
            session()->flash('success', 'Member photo updated!');
        }
    }

    public function render()
    {
        return view('livewire.ibalong.admin.dashboard-overview')->layout('layouts.dashboard');
    }
}