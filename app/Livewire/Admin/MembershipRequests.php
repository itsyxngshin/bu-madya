<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\MembershipApplication;
use App\Models\MembershipWave;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Mail; 
use App\Mail\ApplicationApproved;

#[Layout('layouts.madya-admin-deck')]
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
    public $assign_committee_id = null;
    // Reset pagination when filtering
    public function updatedSearch() { $this->resetPage(); }
    public function updatedStatusFilter() { $this->resetPage(); }
    public function updatedWaveFilter() { $this->resetPage(); }

    public function viewDetails($id)
    {
        $this->selectedApplication = MembershipApplication::find($id);
        $this->assign_committee_id = $this->selectedApplication->committee_1_id;
        $this->showDetailsModal = true;
    }

    public function approve($id)
    {
        $app = MembershipApplication::find($id);
        
        if ($app) {
            $app->update(['status' => 'approved',
        'assigned_committee_id' => $this->assign_committee_id]);
            
            try {
                Mail::to($app->email)->send(new ApplicationApproved($app));
                
                // SUCCESS TOAST
                $this->dispatch('swal:toast', [
                    'icon' => 'success',
                    'title' => 'Approved & Email Sent!'
                ]);

            } catch (\Exception $e) {
                // WARNING TOAST (Email failed)
                $this->dispatch('swal:toast', [
                    'icon' => 'warning',
                    'title' => 'Approved, but Email Failed.'
                ]);
            }
        }
        $this->showDetailsModal = false;
    }

    public function reject($id)
    {
        MembershipApplication::where('id', $id)->update(['status' => 'rejected']);
        $this->showDetailsModal = false;
        $this->dispatch('swal:toast', [
            'icon' => 'info',
            'title' => 'Application Rejected'
        ]);  
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
            'waves' => $waves, 
            'all_committees' => \App\Models\Committee::orderBy('name')->get()
        ]);
    }
}