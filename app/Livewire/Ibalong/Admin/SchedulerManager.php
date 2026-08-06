<?php

namespace App\Livewire\Ibalong\Admin;

use Livewire\Component;
use Carbon\Carbon;
use App\Models\IbalongHackathon;
use App\Models\IbalongActivity;
use App\Models\IbalongActivityTrack;
use App\Models\IbalongActivitySlot;

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
    public $mentorId = null;
    public $location = '';
    public $slotDate = '';
    public $startTime = '';
    public $endTime = '';
    public $durationMinutes = 30; // Default to 30 min blocks

    public function mount()
    {
        // Enforce Command Center Clearance
        $role = auth('ibalong')->user()->role_id ?? 0;
        if (!in_array($role, [1, 2, 4])) { abort(403); }

        // Future-proofing check: Ensure a default hackathon exists
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
        // This will cascade delete all slots and appointments attached to it
        IbalongActivityTrack::findOrFail($trackId)->delete();
        session()->flash('success', 'Hub and all associated time blocks have been purged.');
    }

    public function openTrackGenerator($activityId)
    {
        $this->selectedActivityId = $activityId;
        $this->reset(['trackName', 'location', 'slotDate', 'startTime', 'endTime', 'durationMinutes']);
    }

    public function generateTrackAndSlots()
    {
        $this->validate([
            'trackName' => 'required|string',
            'slotDate' => 'required|date',
            'startTime' => 'required',
            'endTime' => 'required|after:startTime',
            'durationMinutes' => 'required|integer|min:10',
        ]);

        // 1. Forge the Hub/Track
        $track = IbalongActivityTrack::create([
            'activity_id' => $this->selectedActivityId,
            'name' => $this->trackName,
            'location' => $this->location,
        ]);

        // 2. The Slot Generation Engine
        $start = Carbon::parse($this->slotDate . ' ' . $this->startTime);
        $end = Carbon::parse($this->slotDate . ' ' . $this->endTime);
        $duration = (int) $this->durationMinutes;

        $current = $start->copy();

        while ($current < $end) {
            $slotEnd = $current->copy()->addMinutes($duration);

            // Prevent a 30-min slot from overlapping the end time if only 15 mins remain
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

    public function render()
    {
        $activities = IbalongActivity::with(['tracks.slots.appointments'])
            ->where('hackathon_id', $this->activeHackathon->id)
            ->latest()
            ->get();

        return view('livewire.ibalong.admin.scheduler-manager', compact('activities'))->layout('layouts.dashboard');
    }
}
