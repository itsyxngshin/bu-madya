<?php

namespace App\Livewire\Open;

use Livewire\Component;
use App\Models\EventFrame;
use Livewire\Attributes\Layout;

#[Layout('layouts.madya-template')]
class FrameBuilder extends Component
{
    public EventFrame $frame;

    public function mount($slug)
    {
        // Only allow viewing if it is approved, unless the viewer is the creator or an admin
        $this->frame = EventFrame::where('slug', $slug)->firstOrFail();

        $isAdminOrOwner = auth()->check() && (
            in_array(auth()->user()->role?->role_name, ['administrator', 'director']) ||
            auth()->id() === $this->frame->user_id
        );

        if (!$this->frame->is_approved && !$isAdminOrOwner) {
            abort(404, 'This frame is pending approval.');
        }
    }

    public function render()
    {
        return view('livewire.open.frame-builder');
    }
}
