<?php

namespace App\Livewire\Ibalong\Admin;

use Livewire\Component;
use Carbon\Carbon;
use App\Models\IbalongHackathon;
use App\Models\IbalongActivity;
use App\Models\IbalongActivityTrack;
use App\Models\IbalongActivitySlot;
use App\Models\IbalongUser;
use App\Models\IbalongRegistration;
use App\Models\IbalongAppointment;

class SchedulerManager extends Component
{
    public $activeHackathon;

    // Activity Form State
    public $activityTitle = '';
    public $activityType = 'mentorship';
    public $activityDescription = '';

    // Slot Generator State
    public $selectedActivityId = null;
    public $trackName = '';
    public $mentorId = '';
    public $location = '';
    public $slotDate = '';
    public $startTime = '';
    public $endTime = '';
    public $durationMinutes = 30;

    // Hub Editing & Extension State
    public $editingTrackId = null;
    public $editTrackName = '';
    public $editMentorId = '';
    public $editLocation = '';

    public $appendSlotDate = '';
    public $appendStartTime = '';
    public $appendEndTime = '';
    public $appendDurationMinutes = 30;

    // Manual Assignment State
    public $assigningSlotId = null;
    public $assignTeamId = '';

    public function mount()
    {
        $role = auth('ibalong')->user()->role_id ?? 0;
        if (!in_array($role, [1, 2, 4])) { abort(403); }

        $this->activeHackathon = IbalongHackathon::firstOrCreate(
            ['status' => 'active'],
            ['name' => 'Heroes of Innovation 2026']
        );
    }

    // --- ACTIVITY PROTOCOLS ---
    public function createActivity()
    {
        $this->validate([
            'activityTitle' => 'required|string|max:255',
            'activityType' => 'required|string',
        ]);

        $this->activeHackathon->activities()->create([
            'title' => $this->activityTitle,
            'type' => $this->activityType,
            'description' => $this->activityDescription,
            'is_published' => false
        ]);

        $this->reset(['activityTitle', 'activityDescription']);
        session()->flash('success', 'Activity framework established.');
    }

    public function togglePublish($id)
    {
        $activity = IbalongActivity::findOrFail($id);
        $activity->update(['is_published' => !$activity->is_published]);
    }

    // --- HUB GENERATION PROTOCOLS ---
    public function openTrackGenerator($activityId)
    {
        $this->selectedActivityId = $activityId;
        $this->reset(['trackName', 'mentorId', 'location', 'slotDate', 'startTime', 'endTime', 'durationMinutes']);
    }

    public function generateTrackAndSlots()
    {
        $this->validate([
            'trackName' => 'required|string',
            'mentorId' => 'nullable',
            'slotDate' => 'required|date',
            'startTime' => 'required',
            'endTime' => 'required|after:startTime',
            'durationMinutes' => 'required|integer|min:10',
        ]);

        $track = IbalongActivityTrack::create([
            'activity_id' => $this->selectedActivityId,
            'name' => $this->trackName,
            'location' => $this->location,
            'mentor_id' => $this->mentorId ?: null,
        ]);

        $start = Carbon::parse($this->slotDate . ' ' . $this->startTime);
        $end = Carbon::parse($this->slotDate . ' ' . $this->endTime);
        $duration = (int) $this->durationMinutes;

        $current = $start->copy();

        while ($current < $end) {
            $slotEnd = $current->copy()->addMinutes($duration);
            if ($slotEnd > $end) { break; }

            IbalongActivitySlot::create([
                'track_id' => $track->id,
                'start_time' => $current,
                'end_time' => $slotEnd,
                'capacity' => 1
            ]);
            $current = $slotEnd;
        }

        $this->selectedActivityId = null;
        session()->flash('success', 'Hub forged and time blocks successfully generated.');
    }

    // --- HUB EDIT & EXTENSION PROTOCOLS ---
    public function openEditTrack($trackId)
    {
        $track = IbalongActivityTrack::findOrFail($trackId);
        $this->editingTrackId = $track->id;
        $this->editTrackName = $track->name;
        $this->editLocation = $track->location;
        $this->editMentorId = $track->mentor_id;

        // Reset the extension fields so they are blank every time you open the modal
        $this->reset(['appendSlotDate', 'appendStartTime', 'appendEndTime']);
        $this->appendDurationMinutes = 30;
    }

    public function updateTrack()
    {
        // 1. Validate Base Hub Info
        $this->validate([
            'editTrackName' => 'required|string',
            'editMentorId' => 'nullable',
            'editLocation' => 'nullable|string',
        ]);

        // 2. Validate Extension Info (Only if they typed something in)
        if ($this->appendSlotDate || $this->appendStartTime || $this->appendEndTime) {
             $this->validate([
                 'appendSlotDate' => 'required|date',
                 'appendStartTime' => 'required',
                 'appendEndTime' => 'required|after:appendStartTime',
                 'appendDurationMinutes' => 'required|integer|min:10',
             ], [
                 'appendSlotDate.required' => 'A date is required to extend the hub.',
                 'appendStartTime.required' => 'A start time is required for the extension.',
                 'appendEndTime.required' => 'An end time is required for the extension.',
                 'appendEndTime.after' => 'The extension end time must be after the start time.',
             ]);
        }

        $track = IbalongActivityTrack::findOrFail($this->editingTrackId);

        // Update Core Details
        $track->update([
            'name' => $this->editTrackName,
            'location' => $this->editLocation,
            'mentor_id' => $this->editMentorId ?: null,
        ]);

        // Process Time Extension (If requested)
        if ($this->appendSlotDate && $this->appendStartTime && $this->appendEndTime) {
            $start = Carbon::parse($this->appendSlotDate . ' ' . $this->appendStartTime);
            $end = Carbon::parse($this->appendSlotDate . ' ' . $this->appendEndTime);
            $duration = (int) $this->appendDurationMinutes;

            $current = $start->copy();
            $blocksAdded = 0;

            while ($current < $end) {
                $slotEnd = $current->copy()->addMinutes($duration);
                if ($slotEnd > $end) { break; }

                // Check for overlaps to prevent duplicate blocks at the exact same time
                $exists = IbalongActivitySlot::where('track_id', $track->id)
                    ->where('start_time', $current)
                    ->exists();

                if (!$exists) {
                    IbalongActivitySlot::create([
                        'track_id' => $track->id,
                        'start_time' => $current,
                        'end_time' => $slotEnd,
                        'capacity' => 1
                    ]);
                    $blocksAdded++;
                }

                $current = $slotEnd;
            }

            session()->flash('success', "Hub updated and {$blocksAdded} new time block(s) successfully appended.");
        } else {
            session()->flash('success', 'Hub parameters have been updated.');
        }

        $this->editingTrackId = null;
    }

    // --- DELETION PROTOCOLS ---
    public function deleteTrack($trackId)
    {
        IbalongActivityTrack::findOrFail($trackId)->delete();
        session()->flash('success', 'Hub and all associated time blocks have been purged.');
    }

    public function removeSlot($slotId)
    {
        $slot = IbalongActivitySlot::with('appointments')->findOrFail($slotId);

        // Safety lock: Cannot delete a block that a cohort already booked
        if ($slot->appointments->count() > 0) {
            session()->flash('error', 'SYSTEM LOCK: Cannot purge a time block that contains assigned cohorts.');
            return;
        }

        $slot->delete();
        session()->flash('success', 'Empty time block successfully purged.');
    }

    public function removeAppointment($appointmentId)
    {
        IbalongAppointment::findOrFail($appointmentId)->delete();
        session()->flash('success', 'Appointment successfully wiped from the hub.');
    }

    // --- OVERRIDE PROTOCOLS (Manual Assignment) ---
    public function openAssignModal($slotId)
    {
        $this->assigningSlotId = $slotId;
        $this->assignTeamId = '';
    }

    public function assignTeamToSlot()
    {
        $this->validate([
            'assignTeamId' => 'required',
        ]);

        $slot = IbalongActivitySlot::with('appointments')->findOrFail($this->assigningSlotId);

        if ($slot->appointments->count() >= $slot->capacity) {
            session()->flash('error', 'SYSTEM ALERT: This time block is already at maximum capacity.');
            return;
        }

        $alreadyAssigned = IbalongAppointment::where('slot_id', $this->assigningSlotId)
            ->where('team_id', $this->assignTeamId)
            ->exists();

        if ($alreadyAssigned) {
            session()->flash('error', 'SYSTEM ALERT: Cohort is already assigned to this specific block.');
            return;
        }

        IbalongAppointment::create([
            'slot_id' => $this->assigningSlotId,
            'team_id' => $this->assignTeamId,
            'status' => 'booked'
        ]);

        $this->assigningSlotId = null;
        $this->assignTeamId = '';
        session()->flash('success', 'Command Override Successful: Cohort forcibly injected into time block.');
    }

    public function render()
    {
        $activities = IbalongActivity::with(['tracks.mentor', 'tracks.slots' => function($q){
                $q->orderBy('start_time', 'asc');
            }, 'tracks.slots.appointments.team'])
            ->where('hackathon_id', $this->activeHackathon->id)
            ->latest()
            ->get();

        $mentors = IbalongUser::whereIn('role_id', [1, 2, 4, 5])
            ->orderBy('name', 'asc')
            ->get();

        $teams = IbalongRegistration::where('status', 'approved')
            ->orderBy('team_name', 'asc')
            ->get();

        return view('livewire.ibalong.admin.scheduler-manager', compact('activities', 'mentors', 'teams'))
            ->layout('layouts.dashboard');
    }
}
