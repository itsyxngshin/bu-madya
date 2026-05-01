<?php

namespace App\Livewire\Community;

use Livewire\Component;
use App\Models\Post;
use App\Models\Element;
use App\Models\PostComment;

class FeedInteraction extends Component
{
    public Post $post;
    public $newComment = '';

    public $availableElements = [
        'solidarity' => ['label' => 'Solidarity', 'icon' => '✊', 'color' => 'text-blue-600'],
        'insight'    => ['label' => 'Insight',    'icon' => '💡', 'color' => 'text-yellow-600'],
        'ignite'     => ['label' => 'Ignite',     'icon' => '🔥', 'color' => 'text-orange-600'],
        'oragon'     => ['label' => 'Oragon',     'icon' => '🌶️', 'color' => 'text-red-600'],
        'resonance'  => ['label' => 'Resonance',  'icon' => '🌻', 'color' => 'text-green-600'],
    ];

    public function mount(Post $post)
    {
        $this->post = $post;
    }

    public function toggleElement($type)
    {
        if (!auth()->check()) return redirect()->route('login');
        if (!array_key_exists($type, $this->availableElements)) return;

        $existing = Element::where('post_id', $this->post->id)->where('user_id', auth()->id())->first();

        if ($existing) {
            if ($existing->type === $type) { $existing->delete(); } 
            else { $existing->update(['type' => $type]); }
        } else {
            Element::create(['post_id' => $this->post->id, 'user_id' => auth()->id(), 'type' => $type]);
        }
    }

    public function postComment()
    {
        if (!auth()->check()) return redirect()->route('login');
        $this->validate(['newComment' => 'required|string|max:500']);

        PostComment::create([
            'post_id' => $this->post->id,
            'user_id' => auth()->id(),
            'content' => $this->newComment
        ]);

        $this->newComment = ''; 
    }

    public function render()
    {
        $elementCounts = $this->post->elements()->selectRaw('type, count(*) as count')->groupBy('type')->pluck('count', 'type')->toArray();
        $userElement = auth()->check() ? Element::where('post_id', $this->post->id)->where('user_id', auth()->id())->value('type') : null;
        
        // Fetch only the latest 3 comments for the feed, ordered chronologically
        $recentComments = $this->post->comments()->with('user')->latest()->take(3)->get()->reverse();
        $totalComments = $this->post->comments()->count();

        return view('livewire.community.feed-interaction', [
            'elementCounts' => $elementCounts,
            'userElement' => $userElement,
            'recentComments' => $recentComments,
            'totalComments' => $totalComments
        ]);
    }
}
