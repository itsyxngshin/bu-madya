<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Meeting;

class MeetingViewer extends Component
{
    public $slug;

    public function mount($slug)
    {
        $this->slug = $slug;
        // Verify the meeting exists
        Meeting::where('slug', $this->slug)->firstOrFail();
    }

    public function render()
    {
        // We fetch it fresh inside render() so wire:poll always gets the latest data
        $meeting = Meeting::where('slug', $this->slug)->with('user', 'attendees')->firstOrFail();
        $layoutFile = in_array(auth()->user()->role?->role_name, ['administrator', 'organization', 'director'])
            ? 'layouts.madya-admin-deck'
            : 'layouts.madya-template';

        return view('livewire.meeting-viewer', [
            'meeting' => $meeting
        ])->layout($layoutFile);
    }
}