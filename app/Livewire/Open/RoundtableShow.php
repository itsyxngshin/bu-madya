<?php

namespace App\Livewire\Open;

use Livewire\Component;
use App\Models\RoundtableTopic;
use App\Models\RoundtableReply;

class RoundtableShow extends Component
{
    public $topic;
    public $newReply = '';

    public function mount($id)
    {
        $this->topic = RoundtableTopic::with(['user', 'roundtable_replies.user'])->findOrFail($id);
    }

    public function postReply()
    {
        $this->validate(['newReply' => 'required|min:2']);

        RoundtableReply::create([
            'user_id' => auth()->id(),
            'topic_id' => $this->topic->id,
            'content' => $this->newReply
        ]);

        $this->newReply = '';
        $this->topic->load('roundtable_replies.user');
    }

    public function render()
    {
        return view('livewire.open.roundtable-show');
    }
}
