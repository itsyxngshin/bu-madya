<?php

namespace App\Livewire\Partner;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\Organization;
use App\Models\Meeting;
use App\Models\MeetingAttendee;
use Livewire\Attributes\Layout;

#[Layout('layouts.madya-admin-deck')] 

class MeetingManager extends Component
{
    public $organization;
    public $activeMeetingId = null;
    
    // New Meeting Form
    public $title, $meeting_date, $start_time, $location, $agenda;
    public $isCreateModalOpen = false;

    // Active Meeting Data
    public $minutes;
    public $manualStudentId, $manualName;

    public function mount()
    {
        $this->organization = Organization::where('user_id', Auth::id())->first();
        $this->meeting_date = date('Y-m-d');
        $this->start_time = date('H:i');
    }

    public function createMeeting()
    {
        $this->validate([
            'title' => 'required|string|max:255',
            'meeting_date' => 'required|date',
            'start_time' => 'required',
            'location' => 'nullable|string',
            'agenda' => 'nullable|string',
        ]);

        Meeting::create([
            'organization_id' => $this->organization->id,
            'title' => $this->title,
            'meeting_date' => $this->meeting_date,
            'start_time' => $this->start_time,
            'location' => $this->location,
            'agenda' => $this->agenda,
        ]);

        $this->isCreateModalOpen = false;
        $this->reset(['title', 'location', 'agenda']);
        session()->flash('success', 'Meeting scheduled successfully.');
    }

    public function openMeeting($id)
    {
        $this->activeMeetingId = $id;
        $meeting = Meeting::find($id);
        $this->minutes = $meeting->minutes;
    }

    public function closeMeeting()
    {
        $this->activeMeetingId = null;
    }

    public function saveMinutes()
    {
        if ($this->activeMeetingId) {
            Meeting::where('id', $this->activeMeetingId)->update(['minutes' => $this->minutes]);
            session()->flash('minutes_success', 'Minutes saved successfully.');
        }
    }

    public function markCompleted()
    {
        if ($this->activeMeetingId) {
            Meeting::where('id', $this->activeMeetingId)->update(['status' => 'completed']);
            session()->flash('minutes_success', 'Meeting marked as completed.');
        }
    }

    // Handles both QR Scans and Manual Entry
    public function recordAttendance($scannedText, $name = 'Scanned Member')
    {
        if (!$this->activeMeetingId) return;

        // Prevent duplicate scans for the same meeting
        $exists = MeetingAttendee::where('meeting_id', $this->activeMeetingId)
                                 ->where('student_id', $scannedText)
                                 ->exists();

        if (!$exists) {
            MeetingAttendee::create([
                'meeting_id' => $this->activeMeetingId,
                'student_id' => $scannedText,
                'name' => $name,
                'time_in' => now(),
            ]);
            $this->dispatch('attendance-recorded', ['student' => $scannedText]);
        } else {
            $this->dispatch('attendance-duplicate', ['student' => $scannedText]);
        }

        $this->reset(['manualStudentId', 'manualName']);
    }

    public function render()
    {
        $meetings = $this->organization ? $this->organization->meetings()->orderBy('meeting_date', 'desc')->get() : [];
        $activeMeeting = $this->activeMeetingId ? Meeting::with('attendees')->find($this->activeMeetingId) : null;
        $layoutFile = in_array(auth()->user()->role?->role_name, ['administrator', 'organization', 'director'])
            ? 'layouts.madya-admin-deck'
            : 'layouts.madya-admin';

        return view('livewire.partner.meeting-manager', [
            'meetings' => $meetings,
            'activeMeeting' => $activeMeeting,
        ])->layout($layoutFile);
    }
}
