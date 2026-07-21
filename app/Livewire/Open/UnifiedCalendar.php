<?php

namespace App\Livewire\Open;

use Livewire\Component;
use App\Models\MadyaCalendarActivity;
use App\Models\Event;
use App\Models\Project;
use App\Models\SiteStat;
use Carbon\Carbon;
use Illuminate\Support\Str;

class UnifiedCalendar extends Component
{
    public $visitorCount = 0;

    public function mount()
    {
        // Load the visitor count for the footer
        $this->visitorCount = SiteStat::where('key', 'visitor_count')->value('value') ?? 0;
    }

    public function render()
    {
        $calendarData = [];

        // ---------------------------------------------------------
        // 1. PLOT CUSTOM ACTIVITIES (New Table)
        // ---------------------------------------------------------
        $activities = MadyaCalendarActivity::where('is_active', true)->get();

        foreach ($activities as $act) {
            if ($act->activity_date) {
                $date = $act->activity_date->format('Y-m-d');

                if (!isset($calendarData[$date])) {
                    $calendarData[$date] = [];
                }

                $calendarData[$date][] = [
                    'id' => 'act_' . $act->id,
                    'title' => $act->title,
                    'category' => $act->category ?: 'Activity',
                    'organizer' => $act->organizer,
                    'link' => $act->external_link,
                    'description' => $act->description,
                ];
            }
        }

        // ---------------------------------------------------------
        // 2. PLOT CORE EVENTS
        // ---------------------------------------------------------
        $allEvents = Event::where('is_active', true)->get();

        foreach ($allEvents as $ev) {
            if ($ev->start_date) {
                $date = Carbon::parse($ev->start_date)->format('Y-m-d');

                if (!isset($calendarData[$date])) {
                    $calendarData[$date] = [];
                }

                $calendarData[$date][] = [
                    'id' => 'evt_' . $ev->id,
                    'title' => $ev->title,
                    'category' => 'Event',
                    'organizer' => 'BU MADYA', // Default internal organizer
                    'link' => route('events.show', $ev->slug ?? $ev->id),
                    'description' => Str::limit(strip_tags($ev->description), 150),
                ];
            }
        }

        // ---------------------------------------------------------
        // 3. PLOT PROJECTS
        // ---------------------------------------------------------
        $allProjects = Project::whereIn('status', ['active', 'completed'])->get();
        $today = now()->format('Y-m-d');

        foreach ($allProjects as $proj) {

            // UX TRICK: Pin 'active' projects to today.
            // Map 'completed' projects to their actual creation/launch date.
            $date = $proj->status === 'active' ? $today : Carbon::parse($proj->created_at)->format('Y-m-d');

            if (!isset($calendarData[$date])) {
                $calendarData[$date] = [];
            }

            // Differentiate the label based on status
            $typeLabel = $proj->status === 'active' ? 'Active Project' : 'Completed Project';

            $calendarData[$date][] = [
                'id' => 'proj_' . $proj->id,
                'title' => $proj->title,
                'category' => $typeLabel,
                'organizer' => 'BU MADYA',
                'link' => route('projects.show', $proj->slug ?? $proj->id),
                'description' => Str::limit(strip_tags($proj->description), 150),
            ];
        }

        return view('livewire.open.unified-calendar', [
            'calendarData' => $calendarData
        ])->layout('layouts.madya-template');
    }
}
