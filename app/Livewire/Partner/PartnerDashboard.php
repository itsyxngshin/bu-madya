<?php

namespace App\Livewire\Partner;

use Livewire\Component;
use App\Models\Event;
use App\Models\EventFrame;
use Livewire\Attributes\Layout;

#[Layout('layouts.madya-admin-deck')] // Use the same sleek dark layout!
class PartnerDashboard extends Component
{
    public function render()
    {
        $userId = auth()->id();

        // Get their specific stats
        $myEventsCount = Event::where('user_id', $userId)->count();
        $myFramesCount = EventFrame::where('user_id', $userId)->count();
        
        // Sum total attendees across ALL of their events
        $totalAttendees = Event::where('user_id', $userId)
            ->withCount(['registrations' => function ($query) {
                $query->where('status', 'Attended');
            }])
            ->get()
            ->sum('registrations_count');

        // Fetch their latest 3 upcoming events for the quick-view panel
        $upcomingEvents = Event::where('user_id', $userId)
            ->where('start_date', '>=', now())
            ->orderBy('start_date', 'asc')
            ->take(3)
            ->get();

        return view('livewire.partner.partner-dashboard', [
            'stats' => [
                'events' => $myEventsCount,
                'frames' => $myFramesCount,
                'attendees' => $totalAttendees
            ],
            'upcomingEvents' => $upcomingEvents
        ]);
    }
}