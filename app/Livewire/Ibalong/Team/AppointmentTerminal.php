<?php

namespace App\Livewire\Ibalong\Team;

use Livewire\Component;
use App\Models\IbalongHackathon;
use App\Models\IbalongActivity;
use App\Models\IbalongActivitySlot;
use App\Models\IbalongAppointment;

class AppointmentTerminal extends Component
{
    public $activeHackathon;
    public $teamId;

    public function mount()
    {
        $this->teamId = auth('ibalong')->user()->registration->id ?? null;

        if (!$this->teamId) {
            abort(403, 'UNAUTHORIZED: Timetable access is restricted to official cohorts.');
        }

        // Fetch the active hackathon container
        $this->activeHackathon = IbalongHackathon::where('status', 'active')->first();
    }

    public function bookSlot($slotId)
    {
        $slot = IbalongActivitySlot::with('appointments')->findOrFail($slotId);

        // 1. Security Check: Is the slot full?
        if ($slot->appointments->count() >= $slot->capacity) {
            session()->flash('error', 'SYSTEM LOCK: This time block is already at maximum capacity.');
            return;
        }

        // 2. Security Check: Did this team already book this exact slot?
        $alreadyBooked = IbalongAppointment::where('slot_id', $slotId)
            ->where('team_id', $this->teamId)
            ->exists();

        if ($alreadyBooked) {
            session()->flash('error', 'You have already secured this specific time block.');
            return;
        }

        // 3. Optional Rule: Prevent team from booking multiple slots in the same Hub/Track
        // Uncomment the block below if a team should only get ONE slot per Hub.
        /*
        $hasSlotInHub = IbalongAppointment::where('team_id', $this->teamId)
            ->whereHas('slot', function($query) use ($slot) {
                $query->where('track_id', $slot->track_id);
            })->exists();

        if ($hasSlotInHub) {
            session()->flash('error', 'SYSTEM LOCK: Your cohort already has a secured block in this Hub.');
            return;
        }
        */

        // Execute Booking
        IbalongAppointment::create([
            'slot_id' => $slotId,
            'team_id' => $this->teamId,
            'status' => 'booked'
        ]);

        session()->flash('success', 'Time block successfully secured!');
    }

    public function relinquishSlot($appointmentId)
    {
        // Ensure they can only delete their OWN appointment
        $appointment = IbalongAppointment::where('id', $appointmentId)
            ->where('team_id', $this->teamId)
            ->firstOrFail();

        $appointment->delete();
        session()->flash('success', 'Appointment relinquished. The block is now open for other cohorts.');
    }

    public function render()
    {
        // Fetch only PUBLISHED activities for the active hackathon
        $activities = collect();

        if ($this->activeHackathon) {
            $activities = IbalongActivity::with(['tracks.slots' => function($query) {
                $query->orderBy('start_time', 'asc');
            }, 'tracks.slots.appointments'])
            ->where('hackathon_id', $this->activeHackathon->id)
            ->where('is_published', true)
            ->get();
        }

        return view('livewire.ibalong.team.appointment-terminal', compact('activities'))
            ->layout('layouts.dashboard');
    }
}
