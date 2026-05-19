<?php

namespace App\Livewire\Open;

use Livewire\Component;
use App\Models\SiteStat;
use App\Models\News;
use App\Models\Project;
use App\Models\Event;
use App\Models\AcademicYear;
use App\Models\Director;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout; 

#[Layout('layouts.madya-template')]
class LandingPage extends Component
{
    public $visitorCount = 1;

    public function mount()
    {
        if (!Session::has('has_visited_site')) {
            SiteStat::where('key', 'visitor_count')->increment('value');
            Session::put('has_visited_site', true);
        }
        $this->visitorCount = SiteStat::where('key', 'visitor_count')->value('value');
    }

    public function render()
    {
        $latestNews = News::where('status', 'active')->orderBy('created_at', 'desc')->take(2)->get();
        $featuredProjects = Project::where('status', 'Completed')->orderBy('created_at', 'desc')->take(5)->get();
        $upcomingEvents = Event::where('is_active', true)
            ->where(function ($query) {
                $query->whereNull('end_date')->orWhere('end_date', '>=', now());
            })->orderBy('start_date', 'asc')->take(5)->get();

        // ---------------------------------------------------------
        // DIRECTORY LOGIC: Build the Meet the Team Marquee
        // ---------------------------------------------------------
        $activeYear = AcademicYear::where('is_active', true)->first();
        if (!$activeYear) $activeYear = AcademicYear::latest('id')->first();

        $teamMarquee = collect();
        if ($activeYear) {
            $directors = Director::with(['assignments' => function ($q) use ($activeYear) {
                $q->where('academic_year_id', $activeYear->id)->with('user');
            }])->orderBy('order', 'asc')->get();

            foreach ($directors as $director) {
                if ($director->assignments->isNotEmpty() && $director->assignments->first()->user) {
                    $user = $director->assignments->first()->user;
                    $teamMarquee->push((object)[
                        'director_name' => $director->name,
                        'user_name'     => $user->name,
                        'photo'         => $user->profile_photo_path,
                    ]);
                }
            }
        }

        // ---------------------------------------------------------
        // CALENDAR LOGIC: Group active activities by Date for Alpine
        // ---------------------------------------------------------
        $calendarData = [];

        // 1. Plot Events
        $allEvents = Event::where('is_active', true)->get();

        foreach ($allEvents as $ev) {
            if ($ev->start_date) {
                $date = \Carbon\Carbon::parse($ev->start_date)->format('Y-m-d');
                
                if (!isset($calendarData[$date])) {
                    $calendarData[$date] = [];
                }
                
                $calendarData[$date][] = [
                    'title' => $ev->title,
                    'type'  => 'Event',
                    'url'   => route('events.show', $ev->slug ?? $ev->id)
                ];
            }
        }

        // 2. Plot Projects (Both Active and Completed)
        $allProjects = Project::whereIn('status', ['active', 'completed'])->get();
        $today = now()->format('Y-m-d');

        foreach ($allProjects as $proj) {
            
            // UX TRICK: Pin 'active' projects to today. 
            // Map 'completed' projects to their actual creation/launch date.
            $date = $proj->status === 'active' 
                ? $today 
                : \Carbon\Carbon::parse($proj->created_at)->format('Y-m-d');
            
            if (!isset($calendarData[$date])) {
                $calendarData[$date] = [];
            }
            
            // Differentiate the label based on status
            $typeLabel = $proj->status === 'active' ? 'Active Project' : 'Completed Project';

            $calendarData[$date][] = [
                'title' => $proj->title,
                'type'  => $typeLabel,
                'url'   => route('projects.show', $proj->slug ?? $proj->id)
            ];
        }

        // Fetch ALL active projects 
        $allProjects = Project::where('status', 'active')->get();

        foreach ($allProjects as $proj) {
            if ($proj->created_at) {
                $date = $proj->created_at->format('Y-m-d');
                if (!isset($calendarData[$date])) $calendarData[$date] = [];
                $calendarData[$date][] = [
                    'title' => $proj->title,
                    'type'  => 'Project',
                    'url'   => route('projects.show', $proj->slug ?? $proj->id)
                ];
            }
        }

        return view('livewire.open.landing-page', [
            'latestNews'       => $latestNews,
            'featuredProjects' => $featuredProjects,
            'upcomingEvents'   => $upcomingEvents,
            'teamMarquee'      => $teamMarquee,
            'activeYearLabel'  => $activeYear?->name ?? 'Current',
            'calendarData'     => $calendarData // <-- Pass to view
        ]); 
    }
}