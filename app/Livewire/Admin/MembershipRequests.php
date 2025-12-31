<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\MembershipApplication;
use App\Models\MembershipWave;
use Illuminate\Support\Facades\Mail;
use App\Mail\ApplicationApproved;
use Livewire\Attributes\Layout;

#[Layout('layouts.madya-template')]
class MembershipRequests extends Component
{
    use WithPagination;

    // Properties for Filters & Search
    public $search = '';
    public $statusFilter = 'pending';
    public $waveFilter = ''; // Filter by Wave ID
    
    // Properties for Modal
    public $selectedApplication = null;
    public $showDetailsModal = false;

    // Reset pagination when filtering
    public function updatedSearch() { $this->resetPage(); }
    public function updatedStatusFilter() { $this->resetPage(); }
    public function updatedWaveFilter() { $this->resetPage(); }

    public function viewDetails($id)
    {
        $this->selectedApplication = MembershipApplication::find($id);
        $this->showDetailsModal = true;
    }

    public function approve($id)
    {
        $app = MembershipApplication::find($id);
        
        if ($app) {
            $app->update(['status' => 'approved']);
            
            // SEND EMAIL
            try {
                Mail::to($app->email)->send(new ApplicationApproved($app));
                session()->flash('message', 'Application approved and welcome email sent!');
            } catch (\Exception $e) {
                session()->flash('error', 'Approved, but failed to send email: ' . $e->getMessage());
            }
        }
        $this->showDetailsModal = false;
    }

    public function reject($id)
    {
        MembershipApplication::where('id', $id)->update(['status' => 'rejected']);
        $this->showDetailsModal = false;
        session()->flash('message', 'Application rejected.');
    }

    public function render()
    {
        // 1. Build Query
        $applications = MembershipApplication::query()
            ->with('membershipWave') // Eager load the wave relationship
            ->where(function($q) {
                $q->where('last_name', 'like', '%'.$this->search.'%')
                  ->orWhere('first_name', 'like', '%'.$this->search.'%')
                  ->orWhere('email', 'like', '%'.$this->search.'%');
            })
            ->when($this->statusFilter, function($q) {
                return $q->where('status', $this->statusFilter);
            })
            ->when($this->waveFilter, function($q) {
                return $q->where('membership_wave_id', $this->waveFilter);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // 2. Fetch Waves for the Dropdown
        $waves = MembershipWave::orderBy('created_at', 'desc')->get();

        return view('livewire.admin.membership-requests', [
            'applications' => $applications,
            'waves' => $waves
        ]);
    }
}