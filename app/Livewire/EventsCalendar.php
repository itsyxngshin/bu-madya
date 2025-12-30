<?php

namespace App\Livewire;

use Livewire\Component;
use Carbon\Carbon;
use App\Models\Project; // Assuming this is your model
use Livewire\Attributes\Layout;
use App\Services\HolidayService;

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
        // 1. Fetch Projects
        // FIX: Add ->toBase() after get() to convert Eloquent Collection to a standard Collection
        $projects = Project::query()
            ->whereMonth('implementation_date', $this->currentMonth)
            ->whereYear('implementation_date', $this->currentYear)
            ->get()
            ->toBase() // <--- CRITICAL FIX: Drops Eloquent behavior so merge() works with arrays
            ->map(function ($project) {
                return [
                    'id' => $project->id,
                    'title' => $project->title,
                    'slug' => $project->slug,
                    'status' => $project->status,
                    'date' => \Carbon\Carbon::parse($project->implementation_date),
                    'is_holiday' => false,
                ];
            });

        // 2. Fetch Holidays
        $allHolidays = \App\Services\HolidayService::getHolidaysForYear($this->currentYear);
        
        $monthHolidays = collect($allHolidays)
            ->filter(function ($details, $dateString) {
                return \Carbon\Carbon::parse($dateString)->month == $this->currentMonth;
            })
            ->map(function ($details, $dateString) {
                return [
                    'id' => 'holiday-' . $dateString,
                    'title' => $details['title'],
                    'slug' => '#',
                    'status' => $details['type'],
                    'date' => \Carbon\Carbon::parse($dateString),
                    'is_holiday' => true,
                ];
            })->values();

        // 3. Merge & Group
        // Now both $projects and $monthHolidays are standard Collections of arrays.
        $this->events = $projects->merge($monthHolidays)
            ->groupBy(function ($event) {
                return (int) $event['date']->format('j');
            })
            ->toArray();
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