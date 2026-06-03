<?php

namespace App\Livewire\Partner;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\Meeting;
use App\Models\MeetingAttendee;
use App\Models\AcademicYear;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class MeetingManager extends Component
{
    public $activeMeetingId = null;
    
    // Form & Modal State
    public $isCreateModalOpen = false;
    public $isEditMode = false;
    public $editingMeetingId = null;

    // Meeting Fields
    public $academic_year_id;
    public $title, $slug, $meeting_date, $start_time, $location, $agenda;

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

    public function updatedTitle()
    {
        if (!$this->isEditMode) {
            $this->slug = Str::slug($this->title);
        }
    }

    public function updatedSearchQuery()
    {
        if (strlen($this->searchQuery) >= 2) {
            // FIXED: Removed toArray() so we can use Eloquent properties like profile_photo_url
            $this->searchResults = User::where('name', 'like', '%' . $this->searchQuery . '%')
                ->orWhere('id', 'like', '%' . $this->searchQuery . '%')
                ->orWhere('username', 'like', '%' . $this->searchQuery . '%')
                ->take(5)
                ->get();
        } else {
            $this->searchResults = [];
        }
    }

    public function openCreateModal()
    {
        $this->resetValidation();
        $this->reset(['title', 'slug', 'location', 'agenda', 'editingMeetingId']);
        $this->isEditMode = false;
        $this->meeting_date = date('Y-m-d');
        $this->start_time = date('H:i');
        
        $latestAy = AcademicYear::latest('id')->first();
        if ($latestAy) {
            $this->academic_year_id = $latestAy->id;
        }

        $this->isCreateModalOpen = true;
    }

    public function openEditModal($id)
    {
        $this->resetValidation();
        $meeting = Meeting::where('user_id', Auth::id())->findOrFail($id);
        
        $this->editingMeetingId = $meeting->id;
        $this->academic_year_id = $meeting->academic_year_id;
        $this->title = $meeting->title;
        $this->slug = $meeting->slug;
        $this->meeting_date = $meeting->meeting_date->format('Y-m-d');
        $this->start_time = $meeting->start_time->format('H:i');
        $this->location = $meeting->location;
        $this->agenda = $meeting->agenda;
        
        $this->isEditMode = true;
        $this->isCreateModalOpen = true;
    }

    public function saveMeeting()
    {
        $this->slug = Str::slug($this->slug);

        $this->validate([
            'academic_year_id' => 'required|exists:academic_years,id',
            'title' => 'required|string|max:255',
            'slug' => ['required', 'string', 'max:255', Rule::unique('meetings', 'slug')->ignore($this->editingMeetingId)],
            'meeting_date' => 'required|date',
            'start_time' => 'required',
            'location' => 'nullable|string',
            'agenda' => 'nullable|string',
        ]);

        if ($this->isEditMode) {
            $meeting = Meeting::where('user_id', Auth::id())->findOrFail($this->editingMeetingId);
            $meeting->update([
                'academic_year_id' => $this->academic_year_id,
                'title' => $this->title,
                'slug' => $this->slug,
                'meeting_date' => $this->meeting_date,
                'start_time' => $this->start_time,
                'location' => $this->location,
                'agenda' => $this->agenda,
            ]);
            session()->flash('success', 'Meeting updated successfully.');
        } else {
            Meeting::create([
                'user_id' => Auth::id(),
                'academic_year_id' => $this->academic_year_id,
                'title' => $this->title,
                'slug' => $this->slug,
                'meeting_date' => $this->meeting_date,
                'start_time' => $this->start_time,
                'location' => $this->location,
                'agenda' => $this->agenda,
            ]);
            session()->flash('success', 'Meeting scheduled successfully.');
        }

        $this->isCreateModalOpen = false;
        $this->reset(['title', 'slug', 'location', 'agenda', 'isEditMode', 'editingMeetingId']); 
    }

    public function deleteMeeting($id)
    {
        $meeting = Meeting::where('user_id', Auth::id())->findOrFail($id);
        $meeting->delete(); 
        
        session()->flash('success', 'Meeting deleted successfully.');
        if ($this->activeMeetingId == $id) {
            $this->activeMeetingId = null;
        }
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
            $identifier = $user->id ?? $user->username;
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
                $user = clone User::where('id', $scannedText)
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
