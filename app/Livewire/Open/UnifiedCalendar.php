<?php

namespace App\Livewire\Public;

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
        // 2. PLOT CORE EVENTS[cite: 12]
        // ---------------------------------------------------------
        $allEvents = Event::where('is_active', true)->get();[cite: 12]

        foreach ($allEvents as $ev) {[cite: 12]
            if ($ev->start_date) {[cite: 12]
                $date = Carbon::parse($ev->start_date)->format('Y-m-d');[cite: 12]

                if (!isset($calendarData[$date])) {[cite: 12]
                    $calendarData[$date] = [];[cite: 12]
                }

                $calendarData[$date][] = [
                    'id' => 'evt_' . $ev->id,
                    'title' => $ev->title,[cite: 12]
                    'category' => 'Event',[cite: 12]
                    'organizer' => 'BU MADYA', // Default internal organizer
                    'link' => route('events.show', $ev->slug ?? $ev->id),[cite: 12]
                    'description' => Str::limit(strip_tags($ev->description), 150),
                ];
            }
        }

        // ---------------------------------------------------------
        // 3. PLOT PROJECTS[cite: 12]
        // ---------------------------------------------------------
        $allProjects = Project::whereIn('status', ['active', 'completed'])->get();[cite: 12]
        $today = now()->format('Y-m-d');[cite: 12]

        foreach ($allProjects as $proj) {[cite: 12]

            // UX TRICK: Pin 'active' projects to today.[cite: 12]
            // Map 'completed' projects to their actual creation/launch date.[cite: 12]
            $date = $proj->status === 'active' ? $today : Carbon::parse($proj->created_at)->format('Y-m-d');[cite: 12]

            if (!isset($calendarData[$date])) {[cite: 12]
                $calendarData[$date] = [];[cite: 12]
            }

            // Differentiate the label based on status[cite: 12]
            $typeLabel = $proj->status === 'active' ? 'Active Project' : 'Completed Project';[cite: 12]

            $calendarData[$date][] = [
                'id' => 'proj_' . $proj->id,
                'title' => $proj->title,[cite: 12]
                'category' => $typeLabel,[cite: 12]
                'organizer' => 'BU MADYA',
                'link' => route('projects.show', $proj->slug ?? $proj->id),[cite: 12]
                'description' => Str::limit(strip_tags($proj->description), 150),
            ];
        }

        return view('livewire.public.unified-calendar', [
            'calendarData' => $calendarData
        ])->layout('layouts.madya-template'); // Adjusted to match your public template
    }
}
