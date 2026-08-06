<?php

namespace App\Livewire\Ibalong\Admin;

use Livewire\Component;
use Carbon\Carbon;
use App\Models\IbalongHackathon;
use App\Models\IbalongActivity;
use App\Models\IbalongActivityTrack;
use App\Models\IbalongActivitySlot;
use App\Models\IbalongUser;

class SchedulerManager extends Component
{
    public $activeHackathon;

    // Activity Form State
    public $activityTitle = '';
    public $activityType = 'mentorship';
    public $activityDescription = '';

    // Slot Generator State (Create)
    public $selectedActivityId = null;
    public $trackName = '';
    public $mentorId = '';
    public $location = '';
    public $slotDate = '';
    public $startTime = '';
    public $endTime = '';
    public $durationMinutes = 30;

    // Hub Editing State
    public $editingTrackId = null;
    public $editTrackName = '';
    public $editMentorId = '';
    public $editLocation = '';

    public function mount()
    {
        $role = auth('ibalong')->user()->role_id ?? 0;
        if (!in_array($role, [1, 2, 4])) { abort(403); }

        $this->activeHackathon = IbalongHackathon::firstOrCreate(
            ['status' => 'active'],
            ['name' => 'Heroes of Innovation 2026']
        );
    }

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

    public function deleteTrack($trackId)
    {
        IbalongActivityTrack::findOrFail($trackId)->delete();
        session()->flash('success', 'Hub and all associated time blocks have been purged.');
    }

    // --- HUB CREATION ---
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

    // --- HUB EDITING ---
    public function openEditTrack($trackId)
    {
        $track = IbalongActivityTrack::findOrFail($trackId);
        $this->editingTrackId = $track->id;
        $this->editTrackName = $track->name;
        $this->editLocation = $track->location;
        $this->editMentorId = $track->mentor_id;
    }

    public function updateTrack()
    {
        $this->validate([
            'editTrackName' => 'required|string',
            'editMentorId' => 'nullable',
            'editLocation' => 'nullable|string',
        ]);

        IbalongActivityTrack::findOrFail($this->editingTrackId)->update([
            'name' => $this->editTrackName,
            'location' => $this->editLocation,
            'mentor_id' => $this->editMentorId ?: null,
        ]);

        $this->editingTrackId = null;
        session()->flash('success', 'Hub parameters have been updated.');
    }

    public function render()
    {
        $activities = IbalongActivity::with(['tracks.mentor', 'tracks.slots.appointments'])
            ->where('hackathon_id', $this->activeHackathon->id)
            ->latest()
            ->get();

        $mentors = IbalongUser::whereIn('role_id', [1, 2, 4, 5])
            ->orderBy('name', 'asc')
            ->get();

        return view('livewire.ibalong.admin.scheduler-manager', compact('activities', 'mentors'))
            ->layout('layouts.dashboard');
    }
}
