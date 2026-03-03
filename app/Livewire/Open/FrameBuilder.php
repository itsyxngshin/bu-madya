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
        $this->frame = EventFrame::where('slug', $slug)->firstOrFail();

        // Security Check
        $isAdmin = auth()->check() && in_array(auth()->user()->role?->role_name, ['administrator', 'director']);
        $isCreator = auth()->check() && auth()->id() === $this->frame->user_id;

        // If it's NOT approved, AND they aren't an Admin, AND they aren't the creator, block them.
        if (!$this->frame->is_approved && !$isAdmin && !$isCreator) {
            abort(404, 'This frame is either pending approval or does not exist.');
        }
    }

    public function incrementUsage()
    {
        $this->frame->increment('usage_count');
    }

    public function render()
    {
        return view('livewire.open.frame-builder');
    }
}