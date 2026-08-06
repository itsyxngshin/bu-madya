<?php

namespace App\Livewire\Ibalong\Mentor;

use Livewire\Component;
use App\Models\IbalongActivityTrack;
use App\Models\IbalongAppointment;

class MentorHub extends Component
{
    // Modal State
    public $showModal = false;
    public $selectedAppointmentId = null;

    // Assessment State
    public $teamName = '';
    public $status = 'booked';
    public $notes = '';

    public function openAssessment($appointmentId)
    {
        $appointment = IbalongAppointment::with('team')->findOrFail($appointmentId);

        $this->selectedAppointmentId = $appointment->id;
        $this->teamName = $appointment->team->team_name ?? 'Unknown Cohort';
        $this->status = $appointment->status;
        $this->notes = $appointment->notes;

        $this->showModal = true;
    }

    public function saveAssessment()
    {
        $this->validate([
            'status' => 'required|in:booked,attended,no_show,cancelled',
            'notes' => 'nullable|string'
        ]);

        $appointment = IbalongAppointment::findOrFail($this->selectedAppointmentId);

        $appointment->update([
            'status' => $this->status,
            'notes' => $this->notes
        ]);

        $this->showModal = false;
        $this->selectedAppointmentId = null;

        session()->flash('success', "Assessment logs for {$this->teamName} have been secured to the mainframe.");
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->selectedAppointmentId = null;
    }

    public function render()
    {
        // Identify the logged-in mentor
        $mentorId = auth('ibalong')->user()->id;

        // Fetch ONLY the hubs/tracks explicitly assigned to this specific mentor
        $assignedHubs = IbalongActivityTrack::with(['activity', 'slots' => function($q) {
                $q->orderBy('start_time', 'asc');
            }, 'slots.appointments.team'])
            ->where('mentor_id', $mentorId)
            ->get();

        return view('livewire.ibalong.mentor.mentor-hub', compact('assignedHubs'))
            ->layout('layouts.dashboard');
    }
}
