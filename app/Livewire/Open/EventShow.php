<?php

namespace App\Livewire\Open;

use Livewire\Component;
use App\Models\Event;
use App\Models\SiteStat;
use App\Models\User;
use App\Models\DirectorAssignment;
use Illuminate\Support\Facades\Session;
class EventShow extends Component
{
    public Event $event;
    public $visitorCount = 1;

    // The router passes the {slug} parameter here
    public function mount($slug)
    {
        // Find the active event or show 404
        $this->event = Event::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        if (!Session::has('has_visited_site')) {
            SiteStat::where('key', 'visitor_count')->increment('value');
            Session::put('has_visited_site', true);
        }
        $this->visitorCount = SiteStat::where('key', 'visitor_count')->value('value');


    }

    public function render()
    {
        return view('livewire.open.event-show')
            ->layout('layouts.madya-template', [
                'title' => $this->event->title
            ]);
    }
}
