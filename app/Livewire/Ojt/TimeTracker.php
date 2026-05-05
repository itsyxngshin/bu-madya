<?php

namespace App\Livewire\Ojt;

use Livewire\Component;
use App\Models\TimeLog;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class TimeTracker extends Component
{
    public $todayLog;
    public $currentStatus = 'Not Clocked In';

    // New states for Backtracking
    public $selectedDate;
    public $manualMode = false;

    // Holds the string values for the <input type="time"> fields
    public $m_in, $m_out, $a_in, $a_out;

    public function mount()
    {
        $this->selectedDate = Carbon::today()->format('Y-m-d');
        $this->loadLogForDate();
    }

    // Livewire hook: Runs automatically when $selectedDate changes
    public function updatedSelectedDate()
    {
        $this->loadLogForDate();
    }

    public function loadLogForDate()
    {
        $this->todayLog = TimeLog::firstOrCreate(
            ['user_id' => Auth::id(), 'log_date' => $this->selectedDate],
            ['status' => 'present', 'total_minutes_rendered' => 0]
        );

        // Populate manual inputs for editing (Format: HH:MM for HTML time inputs)
        $this->m_in = $this->todayLog->morning_in ? Carbon::parse($this->todayLog->morning_in)->format('H:i') : null;
        $this->m_out = $this->todayLog->morning_out ? Carbon::parse($this->todayLog->morning_out)->format('H:i') : null;
        $this->a_in = $this->todayLog->afternoon_in ? Carbon::parse($this->todayLog->afternoon_in)->format('H:i') : null;
        $this->a_out = $this->todayLog->afternoon_out ? Carbon::parse($this->todayLog->afternoon_out)->format('H:i') : null;

        $this->determineStatus();
    }

    public function toggleManualMode()
    {
        $this->manualMode = !$this->manualMode;
    }

    // Handles the manual save from the form
    public function saveManualTimes()
    {
        // Helper to safely combine the selected date with the inputted time
        $parseTime = fn($time) => $time ? Carbon::parse($this->selectedDate . ' ' . $time) : null;

        $this->todayLog->morning_in = $parseTime($this->m_in);
        $this->todayLog->morning_out = $parseTime($this->m_out);
        $this->todayLog->afternoon_in = $parseTime($this->a_in);
        $this->todayLog->afternoon_out = $parseTime($this->a_out);

        $this->calculateTotalMinutes();
        $this->todayLog->save();

        $this->determineStatus();
        $this->manualMode = false; // Close manual mode on save
        session()->flash('message', 'Time log manually updated for ' . Carbon::parse($this->selectedDate)->format('M d, Y'));
    }

    // Original Real-time Punch (only allows punching if the date is actually today)
    public function punchTime()
    {
        if ($this->selectedDate !== Carbon::today()->format('Y-m-d')) {
            session()->flash('error', 'You can only use the real-time punch clock for today. Please use manual entry for past dates.');
            return;
        }

        $now = Carbon::now();

        if (!$this->todayLog->morning_in) { $this->todayLog->morning_in = $now; }
        elseif (!$this->todayLog->morning_out) { $this->todayLog->morning_out = $now; }
        elseif (!$this->todayLog->afternoon_in) { $this->todayLog->afternoon_in = $now; }
        elseif (!$this->todayLog->afternoon_out) {
            $this->todayLog->afternoon_out = $now;
            $this->calculateTotalMinutes();
        }

        $this->todayLog->save();
        $this->loadLogForDate(); // Reload to refresh formatting
        session()->flash('message', 'Time logged successfully at ' . $now->format('h:i A'));
    }

    private function calculateTotalMinutes()
    {
        $morningMinutes = 0;
        $afternoonMinutes = 0;

        if ($this->todayLog->morning_in && $this->todayLog->morning_out) {
            $morningMinutes = Carbon::parse($this->todayLog->morning_in)->diffInMinutes(Carbon::parse($this->todayLog->morning_out));
        }

        if ($this->todayLog->afternoon_in && $this->todayLog->afternoon_out) {
            $afternoonMinutes = Carbon::parse($this->todayLog->afternoon_in)->diffInMinutes(Carbon::parse($this->todayLog->afternoon_out));
        }

        $this->todayLog->total_minutes_rendered = $morningMinutes + $afternoonMinutes;
    }

    public function determineStatus()
    {
        if ($this->todayLog->afternoon_out) { $this->currentStatus = 'Shift Completed'; }
        elseif ($this->todayLog->afternoon_in) { $this->currentStatus = 'Clocked In (Afternoon)'; }
        elseif ($this->todayLog->morning_out) { $this->currentStatus = 'On Lunch Break'; }
        elseif ($this->todayLog->morning_in) { $this->currentStatus = 'Clocked In (Morning)'; }
        else { $this->currentStatus = 'Not Clocked In'; }
    }

    public function render()
    {
        return view('livewire.ojt.time-tracker');
    }
}
