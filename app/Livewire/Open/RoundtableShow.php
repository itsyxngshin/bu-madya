<?php

namespace App\Livewire\Open;

use Livewire\Component;
use App\Models\RoundtableTopic;
use App\Models\RoundtableReply;
use App\Models\RoundtableVote;
use Livewire\Attributes\Layout;

#[Layout('layouts.madya-template')]
class RoundtableShow extends Component
{
    public $topic;
    public $newReply = '';
    protected $bannedWords = ['fuck', 'dick', 'damn', 'fuckkk', 'putanginamo','shit'];
    public $editingReplyId = null;
    public $editingContent = '';

    // Trigger Edit Mode
    public function editReply($replyId)
    {
        $reply = $this->topic->roundtable_replies()->find($replyId);
        
        // Authorization: Only owner or admin
        if ($reply->user_id !== auth()->id()) {
            abort(403);
        }

        $this->editingReplyId = $replyId;
        $this->editingContent = $reply->content;
    }

    // Cancel Edit
    public function cancelEdit()
    {
        $this->reset(['editingReplyId', 'editingContent']);
    }

    // Save Changes
    public function updateReply()
    {
        $reply = $this->topic->roundtable_replies()->find($this->editingReplyId);
        
        // Re-authorize
        if ($reply->user_id !== auth()->id()) {
            abort(403);
        }

        // Sanitize again!
        $cleanContent = $this->sanitize($this->editingContent);

        $reply->update(['content' => $cleanContent]);
        $this->reset(['editingReplyId', 'editingContent']);
    }

    // Delete Reply
    public function deleteReply($replyId)
    {
        $reply = $this->topic->roundtable_replies()->find($replyId);

        // Authorization
        if ($reply->user_id !== auth()->id()) {
            abort(403);
        }

        $reply->delete();
    }

    // 2. Create a helper function to clean text
    protected function sanitize($text)
    {
        // Option A: Mask the words (r******)
        foreach ($this->bannedWords as $word) {
            $replacement = str_repeat('*', strlen($word));
            $text = str_ireplace($word, $replacement, $text);
        }
        return $text;
    }

    public function mount($id)
    {
        $this->topic = RoundtableTopic::with(['user', 'roundtable_replies.user'])->findOrFail($id);
    }

    public function postReply()
    {
        $this->validate(['newReply' => 'required|min:2']);
        $cleanContent = $this->sanitize($this->newReply);

        RoundtableReply::create([
            'user_id' => auth()->id(),
            'roundtable_topic_id' => $this->topic->id,
            'content' => $cleanContent
        ]);

        $this->newReply = '';
        $this->topic->load('roundtable_replies.user');
    }

    public function vote($replyId, $direction)
    {
        $reply = RoundtableReply::find($replyId);
        
        // 1. Check if user already voted on this reply
        $existingVote = $reply->votes()->where('user_id', auth()->id())->first();

        if ($existingVote) {
            // If clicking the same button again, remove the vote (toggle off)
            if ($existingVote->vote == $direction) {
                $existingVote->delete();
            } else {
                // Otherwise, switch the vote (e.g., from -1 to 1)
                $existingVote->update(['vote' => $direction]);
            }
        } else {
            // Create new vote
            $reply->votes()->create([
                'user_id' => auth()->id(),
                'vote' => $direction
            ]);
        }
    }
    public function render()
    {
        return view('livewire.open.roundtable-show');
    }
}
