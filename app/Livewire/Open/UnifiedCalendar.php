<?php

namespace App\Livewire\Open;

use Livewire\Component;
use App\Models\MadyaCalendarActivity;
use App\Models\Event;
use App\Models\Project;
use App\Models\SiteStat;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Str;

class UnifiedCalendar extends Component
{
    public $visitorCount = 0;

    // --- Admin Modal State -----
    public $isModalOpen = false;
    public $title, $start_date, $end_date, $category = 'Activity', $organizer, $external_link, $description;

    public function mount()
    {
        $this->visitorCount = SiteStat::where('key', 'visitor_count')->value('value') ?? 0;
    }

    // --- Admin Functions ---
    public function openModal()
    {
        $this->reset(['title', 'start_date', 'end_date', 'category', 'organizer', 'external_link', 'description']);
        $this->start_date = now()->format('Y-m-d');
        $this->end_date = now()->format('Y-m-d');
        $this->isModalOpen = true;
    }

    public function saveActivity()
    {
        $this->validate([
            'title' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'category' => 'required|string',
            'organizer' => 'nullable|string|max:255',
            'external_link' => 'nullable|url',
            'description' => 'nullable|string',
        ]);

        MadyaCalendarActivity::create([
            'title' => $this->title,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'category' => $this->category,
            'organizer' => $this->organizer,
            'external_link' => $this->external_link,
            'description' => $this->description,
        ]);

        $this->isModalOpen = false;
        session()->flash('success', 'Calendar activity published successfully.');
    }

    // --- Render & Plotting ---
    public function render()
    {
        $calendarData = [];

        // 1. PLOT CUSTOM ACTIVITIES (With Multi-day support)
        $activities = MadyaCalendarActivity::where('is_active', true)->get();

        foreach ($activities as $act) {
            if ($act->start_date) {
                $endDate = $act->end_date ?? $act->start_date;
                $period = CarbonPeriod::create($act->start_date, $endDate);

                $isMultiDay = $act->start_date->notEqualTo($endDate);

                foreach ($period as $dateObj) {
                    $date = $dateObj->format('Y-m-d');
                    if (!isset($calendarData[$date])) $calendarData[$date] = [];

                    $calendarData[$date][] = [
                        'id' => 'act_' . $act->id,
                        'title' => $act->title,
                        'category' => $act->category ?: 'Activity',
                        'organizer' => $act->organizer,
                        'link' => $act->external_link,
                        'description' => $act->description,
                        // UI Helpers for merging highlights
                        'is_multi' => $isMultiDay,
                        'is_start' => $date === $act->start_date->format('Y-m-d'),
                        'is_end' => $date === $endDate->format('Y-m-d'),
                    ];
                }
            }
        }

        // 2. PLOT CORE EVENTS (With Multi-day support)
        $allEvents = Event::where('is_active', true)->get();

        foreach ($allEvents as $ev) {
            if ($ev->start_date) {
                $endDate = $ev->end_date ?? $ev->start_date;
                $period = CarbonPeriod::create($ev->start_date, $endDate);

                $isMultiDay = Carbon::parse($ev->start_date)->notEqualTo($endDate);

                foreach ($period as $dateObj) {
                    $date = $dateObj->format('Y-m-d');
                    if (!isset($calendarData[$date])) $calendarData[$date] = [];

                    $calendarData[$date][] = [
                        'id' => 'evt_' . $ev->id,
                        'title' => $ev->title,
                        'category' => 'Event',
                        'organizer' => 'BU MADYA',
                        'link' => route('events.show', $ev->slug ?? $ev->id),
                        'description' => Str::limit(strip_tags($ev->description), 150),
                        'is_multi' => $isMultiDay,
                        'is_start' => $date === Carbon::parse($ev->start_date)->format('Y-m-d'),
                        'is_end' => $date === Carbon::parse($endDate)->format('Y-m-d'),
                    ];
                }
            }
        }

        // 3. PLOT PROJECTS (Single Day Pin)
        $allProjects = Project::whereIn('status', ['active', 'completed'])->get();
        $today = now()->format('Y-m-d');

        foreach ($allProjects as $proj) {
            $date = $proj->status === 'active' ? $today : Carbon::parse($proj->created_at)->format('Y-m-d');
            if (!isset($calendarData[$date])) $calendarData[$date] = [];

            $calendarData[$date][] = [
                'id' => 'proj_' . $proj->id,
                'title' => $proj->title,
                'category' => $proj->status === 'active' ? 'Active Project' : 'Completed Project',
                'organizer' => 'BU MADYA',
                'link' => route('projects.show', $proj->slug ?? $proj->id),
                'description' => Str::limit(strip_tags($proj->description), 150),
                'is_multi' => false,
                'is_start' => true,
                'is_end' => true,
            ];
        }

        return view('livewire.open.unified-calendar', [
            'calendarData' => $calendarData
        ])->layout('layouts.madya-template');
    }
}
