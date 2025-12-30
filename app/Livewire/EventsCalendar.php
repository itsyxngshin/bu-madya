<?php

namespace App\Livewire;

use Livewire\Component;
use Carbon\Carbon;
use App\Models\Project; // Assuming this is your model
use Livewire\Attributes\Layout;

#[Layout('layouts.madya-template')]
class EventsCalendar extends Component
{
    public $currentMonth;
    public $currentYear;
    public $events = [];

    public function mount()
    {
        $this->currentMonth = now()->month;
        $this->currentYear = now()->year;
        $this->loadEvents();
    }

    public function loadEvents()
    {
        // Fetch projects for the current month to optimize performance
        // Adjust 'implementation_date' to your actual column name
        $this->events = Project::query()
            ->whereMonth('implementation_date', $this->currentMonth)
            ->whereYear('implementation_date', $this->currentYear)
            ->get()
            ->groupBy(function ($event) {
                return Carbon::parse($event->implementation_date)->format('j'); // Group by Day of Month (1-31)
            });
    }

    public function previousMonth()
    {
        $date = Carbon::createFromDate($this->currentYear, $this->currentMonth, 1)->subMonth();
        $this->currentMonth = $date->month;
        $this->currentYear = $date->year;
        $this->loadEvents();
    }

    public function nextMonth()
    {
        $date = Carbon::createFromDate($this->currentYear, $this->currentMonth, 1)->addMonth();
        $this->currentMonth = $date->month;
        $this->currentYear = $date->year;
        $this->loadEvents();
    }

    public function render()
    {
        $date = Carbon::createFromDate($this->currentYear, $this->currentMonth, 1);
        
        return view('livewire.events-calendar', [
            'daysInMonth' => $date->daysInMonth,
            'startDayOfWeek' => $date->dayOfWeek, // 0 (Sunday) to 6 (Saturday)
            'monthName' => $date->format('F'),
            'year' => $date->format('Y'),
        ]);
    }
}