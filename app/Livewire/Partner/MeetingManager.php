<?php

namespace App\Livewire\Partner;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\Meeting;
use App\Models\MeetingAttendee;
use App\Models\AcademicYear;
use App\Models\User;
use Illuminate\Support\Str;

class MeetingManager extends Component
{
    public $activeMeetingId = null;

    // New Meeting Form
    public $academic_year_id;
    public $title, $meeting_date, $start_time, $location, $agenda;
    public $isCreateModalOpen = false;

    // Active Meeting Data
    public $minutes;

    // Manual Search State
    public $searchQuery = '';
    public $searchResults = [];

    public function mount()
    {
        $this->meeting_date = date('Y-m-d');
        $this->start_time = date('H:i');

        $latestAy = AcademicYear::latest('id')->first();
        if ($latestAy) {
            $this->academic_year_id = $latestAy->id;
        }
    }

    public function updatedSearchQuery()
    {
        if (strlen($this->searchQuery) >= 2) {
            $this->searchResults = User::where('name', 'like', '%' . $this->searchQuery . '%')
                ->orWhere('student_id', 'like', '%' . $this->searchQuery . '%')
                ->orWhere('username', 'like', '%' . $this->searchQuery . '%')
                ->take(5)
                ->get()
                ->toArray();
        } else {
            $this->searchResults = [];
        }
    }

    public function createMeeting()
    {
        $this->validate([
            'academic_year_id' => 'required|exists:academic_years,id',
            'title' => 'required|string|max:255',
            'meeting_date' => 'required|date',
            'start_time' => 'required',
            'location' => 'nullable|string',
            'agenda' => 'nullable|string',
        ]);

        Meeting::create([
            'user_id' => Auth::id(), // Directly tie to the authenticated Org user!
            'academic_year_id' => $this->academic_year_id,
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
        $this->reset(['searchQuery', 'searchResults']);
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

    public function addManualAttendee($userId)
    {
        $user = User::find($userId);
        if ($user) {
            $identifier = $user->student_id ?? $user->username;
            $this->recordAttendance($identifier, $user->name);

            $this->searchQuery = '';
            $this->searchResults = [];
        }
    }

    public function recordAttendance($scannedText, $name = null)
    {
        if (!$this->activeMeetingId) return;

        $exists = MeetingAttendee::where('meeting_id', $this->activeMeetingId)
                                 ->where('student_id', $scannedText)
                                 ->exists();

        if (!$exists) {
            if (!$name) {
                $user = clone User::where('student_id', $scannedText)
                                  ->orWhere('username', $scannedText)
                                  ->first();
                $actualName = $user ? $user->name : 'Scanned Member';
            } else {
                $actualName = $name;
            }

            MeetingAttendee::create([
                'meeting_id' => $this->activeMeetingId,
                'student_id' => $scannedText,
                'name' => $actualName,
                'time_in' => now(),
            ]);

            $this->dispatch('attendance-recorded', ['student' => $actualName]);
        } else {
            $this->dispatch('attendance-duplicate', ['student' => $scannedText]);
        }
    }

    public function removeAttendee($attendeeId)
    {
        if (!$this->activeMeetingId) return;

        $attendee = MeetingAttendee::where('id', $attendeeId)
                                   ->where('meeting_id', $this->activeMeetingId)
                                   ->first();
        if ($attendee) {
            $name = $attendee->name;
            $attendee->delete();
            $this->dispatch('attendance-removed', ['student' => $name]);
        }
    }

    public function render()
    {
        // Fetch meetings directly belonging to this logged-in organization user
        $meetings = Meeting::where('user_id', Auth::id())
                           ->with('academicYear')
                           ->orderBy('meeting_date', 'desc')
                           ->get();

        $activeMeeting = $this->activeMeetingId ? Meeting::with('attendees')->find($this->activeMeetingId) : null;
        $academicYears = AcademicYear::orderBy('id', 'desc')->get();
        $layoutFile = in_array(auth()->user()->role?->role_name, ['administrator', 'organization', 'director'])
            ? 'layouts.madya-admin-deck'
            : 'layouts.madya-admin';

        return view('livewire.partner.meeting-manager', [
            'meetings' => $meetings,
            'activeMeeting' => $activeMeeting,
            'academicYears' => $academicYears,
        ])->layout($layoutFile);
    }
}
