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
    public $teamEvents = [];

    public function mount()
    {
        $user = auth('ibalong')->user();

        // Anyone who is NOT a participating team (Role 3) sees the Admin view
        $this->isAdmin = ($user->role_id !== 3);

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

    private function loadTeamData()
    {
        // Safely fetch the team without the clone keyword
        $this->team = IbalongRegistration::where('user_id', auth('ibalong')->id())
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
        // Only Super Admins (1) and Admins (2) should be allowed to lock the portal
        $user = auth('ibalong')->user();
        if (!in_array($user->role_id, [1, 2])) {
            session()->flash('error', 'UNAUTHORIZED: You do not have clearance to lock the portal.');
            return;
        }

        $setting = IbalongSetting::find(1);
        $setting->update(['is_registration_open' => !$setting->is_registration_open]);
        $this->isRegistrationOpen = $setting->is_registration_open;

        $statusMessage = $this->isRegistrationOpen ? 'Registration portal is now OPEN.' : 'Registration portal is now LOCKED.';
        session()->flash('success', $statusMessage);
    }

    // --- Base64 Cropped Image Handlers ---
    public function saveCroppedLogo($base64Image)
    {
        if (!$this->team) return;

        $imageParts = explode(";base64,", $base64Image);
        $imageBase64 = base64_decode($imageParts[1]);
        $fileName = 'team_logos/' . uniqid() . '.jpg';

        if ($this->team->logo_path) {
            Storage::disk('public')->delete($this->team->logo_path);
        }

        Storage::disk('public')->put($fileName, $imageBase64);
        $this->team->update(['logo_path' => $fileName]);

        $this->loadTeamData();
        session()->flash('success', 'Team logo perfectly cropped and updated!');
    }

    public function saveCroppedMemberPhoto($base64Image, $memberId)
    {
        $member = IbalongTeamMember::find($memberId);

        if ($member && $member->team_id === $this->team->id) {
            $imageParts = explode(";base64,", $base64Image);
            $imageBase64 = base64_decode($imageParts[1]);
            $fileName = 'member_photos/' . uniqid() . '.jpg';

            if ($member->photo_path) {
                Storage::disk('public')->delete($member->photo_path);
            }

            Storage::disk('public')->put($fileName, $imageBase64);
            $member->update(['photo_path' => $fileName]);

            $this->loadTeamData();
            session()->flash('success', 'Member photo cropped and updated!');
        }
    }

    public function render()
    {
        return view('livewire.ibalong.admin.dashboard-overview')->layout('layouts.dashboard');
    }
}
