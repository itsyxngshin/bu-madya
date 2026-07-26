<?php

namespace App\Livewire\Ibalong\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail; // <-- Add Mail Facade
use App\Models\IbalongRegistration;
use App\Models\IbalongUser;
use App\Mail\TeamCredentialsMail; // <-- Add your new Mailable

class RegistrantManager extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = 'pending';

    // Modal State
    public $showModal = false;
    public $viewingTeam = null;

    // Extracted Address Data
    public $fullAddress = '';
    public $mapQuery = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function viewTeamDetails($id)
    {
        $this->viewingTeam = IbalongRegistration::with([
            'members.skills',
            'skills',
            'communityAreas',
            'experiences',
            'onlineActivities'
        ])->findOrFail($id);

        $provCode = $this->viewingTeam->provCode ?? $this->viewingTeam->province_id;
        $cityCode = $this->viewingTeam->citymunCode ?? $this->viewingTeam->citymun_id;
        $brgyCode = $this->viewingTeam->brgyCode ?? $this->viewingTeam->barangay_id;

        $prov = DB::table('refprovince')->where('provCode', $provCode)->first();
        $city = DB::table('refcitymun')->where('citymunCode', $cityCode)->first();
        $brgy = DB::table('refbrgy')->where('brgyCode', $brgyCode)->first();

        $addressParts = array_filter([
            $brgy ? $brgy->brgyDesc : '',
            $city ? $city->citymunDesc : '',
            $prov ? $prov->provDesc : '',
            'Philippines'
        ]);

        $this->fullAddress = implode(', ', $addressParts);
        $this->mapQuery = urlencode($this->fullAddress);

        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->viewingTeam = null;
        $this->fullAddress = '';
        $this->mapQuery = '';
    }

    public function approveTeam($id)
    {
        $registration = IbalongRegistration::with('members')->findOrFail($id);

        if ($registration->status === 'approved') return;

        $teamLeader = $registration->members->where('team_role', 'Team Leader')->first();
        $email = $teamLeader ? $teamLeader->email_address : 'team'.$id.'@bumadya.org';
        $leaderName = $teamLeader ? $teamLeader->full_name : 'Team Leader';
        $rawPassword = strtoupper(Str::random(8));

        $user = IbalongUser::create([
            'role_id' => 3,
            'name' => $registration->team_name,
            'slug' => Str::slug($registration->team_name) . '-' . strtolower(Str::random(5)),
            'email' => $email,
            'password' => Hash::make($rawPassword),
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        $registration->update([
            'status' => 'approved',
            'user_id' => $user->id,
            'account_creation_status' => 'Created'
        ]);

        // --- DISPATCH THE EMAIL ---
        Mail::to($email)->send(new TeamCredentialsMail(
            $registration->team_name,
            $leaderName,
            $email,
            $rawPassword
        ));

        session()->flash('message', "APPROVED! 🚀 Credentials successfully emailed to {$email}");

        $this->closeModal();
    }

    public function rejectTeam($id)
    {
        $registration = IbalongRegistration::findOrFail($id);
        $registration->update(['status' => 'rejected']);

        session()->flash('message', "Cohort '{$registration->team_name}' was rejected.");
        $this->closeModal();
    }

    public function render()
    {
        $registrations = IbalongRegistration::with('members')
            ->where('status', 'like', '%' . $this->statusFilter . '%')
            ->where('team_name', 'like', '%' . $this->search . '%')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.ibalong.admin.registrant-manager', [
            'registrations' => $registrations
        ])->layout('layouts.dashboard');
    }
}
