<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\AccreditationApplication;
use App\Models\Organization;
use App\Models\AccreditationDeadline;
use App\Models\Advisory;
use App\Models\AcademicYear;

class AccreditationDashboard extends Component
{
    // Advisory Form
    public $adv_title, $adv_message, $adv_type = 'info';
    
    // Deadline Form
    public $dl_academic_year_id, $dl_type = 'both', $dl_start, $dl_end;

    // Review Actions
    public $reviewingApplicationId = null;
    public $adminRemarks = '';

    public function postAdvisory()
    {
        $this->validate([
            'adv_title' => 'required|string|max:255',
            'adv_message' => 'required|string',
            'adv_type' => 'required|in:info,warning,urgent'
        ]);

        Advisory::create([
            'title' => $this->adv_title,
            'message' => $this->adv_message,
            'type' => $this->adv_type,
        ]);

        $this->reset(['adv_title', 'adv_message', 'adv_type']);
        session()->flash('success', 'Advisory broadcasted to all organizations.');
    }

    public function setDeadline()
    {
        $this->validate([
            'dl_academic_year_id' => 'required|exists:academic_years,id',
            'dl_type' => 'required|in:accreditation,reaccreditation,both',
            'dl_start' => 'required|date',
            'dl_end' => 'required|date|after:dl_start',
        ]);

        AccreditationDeadline::updateOrCreate(
            ['academic_year_id' => $this->dl_academic_year_id, 'application_type' => $this->dl_type],
            ['start_date' => $this->dl_start, 'end_date' => $this->dl_end, 'is_active' => true]
        );

        $this->reset(['dl_start', 'dl_end']);
        session()->flash('success', 'Deadline activated successfully.');
    }

    public function updateApplicationStatus($id, $status)
    {
        $application = AccreditationApplication::findOrFail($id);
        
        $rules = ['adminRemarks' => 'nullable|string'];
        if ($status === 'returned') {
            $rules['adminRemarks'] = 'required|string|min:10'; // Force them to explain why it was returned
        }
        $this->validate($rules);

        $application->update([
            'status' => $status,
            'admin_remarks' => $this->adminRemarks
        ]);

        $this->reviewingApplicationId = null;
        $this->adminRemarks = '';
        session()->flash('success', "Application marked as {$status}.");
    }

    public function render()
    {
        return view('livewire.admin.accreditation-dashboard', [
            'totalOrgs' => Organization::count(),
            'pendingApps' => AccreditationApplication::where('status', 'pending')->count(),
            'approvedApps' => AccreditationApplication::where('status', 'approved')->count(),
            
            'pendingList' => AccreditationApplication::with('organization')
                                ->where('status', 'pending')
                                ->orderBy('created_at', 'asc')
                                ->get(),
                                
            'activeDeadlines' => AccreditationDeadline::with('academicYear')->where('is_active', true)->get(),
            'advisories' => Advisory::latest()->take(5)->get(),
            'academicYears' => AcademicYear::orderBy('id', 'desc')->get(),
        ]);
    }
}
