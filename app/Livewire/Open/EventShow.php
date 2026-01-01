<?php

namespace App\Livewire\Public;

use Livewire\Component;
use App\Models\Event;

class EventShow extends Component
{
    public Event $event;

    // The router passes the {slug} parameter here
    public function mount($slug)
    {
        // Find the active event or show 404
        $this->event = Event::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();
    }

    public function render()
    {
        return view('livewire.open.event-show')
            ->layout('layouts.madya-template', [
                'title' => $this->event->title
            ]);
    }
}
