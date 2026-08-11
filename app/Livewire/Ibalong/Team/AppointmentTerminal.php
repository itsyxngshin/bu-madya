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

        $this->activeHackathon = IbalongHackathon::where('status', 'active')->first();
    }

    public function bookSlot($slotId)
    {
        $slot = IbalongActivitySlot::with(['track.activity', 'appointments'])->findOrFail($slotId);

        // Security Check 1: Is Booking Locked?
        if (!$slot->track->activity->allow_booking) {
            session()->flash('error', 'SYSTEM REJECT: The Command Center has locked the matrix. No new appointments can be made.');
            return;
        }

        if ($slot->appointments->count() >= $slot->capacity) {
            session()->flash('error', 'SYSTEM LOCK: This time block is already at maximum capacity.');
            return;
        }

        $alreadyBooked = IbalongAppointment::where('slot_id', $slotId)
            ->where('team_id', $this->teamId)
            ->exists();

        if ($alreadyBooked) {
            session()->flash('error', 'You have already secured this specific time block.');
            return;
        }

        IbalongAppointment::create([
            'slot_id' => $slotId,
            'team_id' => $this->teamId,
            'status' => 'booked'
        ]);

        session()->flash('success', 'Time block successfully secured!');
    }

    public function relinquishSlot($appointmentId)
    {
        $appointment = IbalongAppointment::with('slot.track.activity')
            ->where('id', $appointmentId)
            ->where('team_id', $this->teamId)
            ->firstOrFail();

        // Security Check 1: Is Booking Locked?
        if (!$appointment->slot->track->activity->allow_booking) {
            session()->flash('error', 'SYSTEM REJECT: The Command Center has locked the matrix. You cannot drop a finalized schedule.');
            return;
        }

        $appointment->delete();
        session()->flash('success', 'Appointment relinquished. The block is now open for other cohorts.');
    }

    public function render()
    {
        $activities = collect();

        if ($this->activeHackathon) {
            // Added 'tracks.mentor' to eagerly load the mentor details
            $activities = IbalongActivity::with(['tracks.mentor', 'tracks.slots' => function($query) {
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