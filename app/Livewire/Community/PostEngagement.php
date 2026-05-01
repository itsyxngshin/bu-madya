<?php

namespace App\Livewire\Community;

use Livewire\Component;
use App\Models\Post;
use App\Models\Element;
use App\Models\PostComment;

class PostEngagement extends Component
{
    public Post $post;
    public $newComment = '';

    // The available elements
    public $availableElements = [
        'solidarity' => ['label' => 'Solidarity', 'icon' => '✊', 'color' => 'text-blue-600', 'bg' => 'bg-blue-50 border-blue-200'],
        'insight'    => ['label' => 'Insight',    'icon' => '💡', 'color' => 'text-yellow-600', 'bg' => 'bg-yellow-50 border-yellow-200'],
        'ignite'     => ['label' => 'Ignite',     'icon' => '🔥', 'color' => 'text-orange-600', 'bg' => 'bg-orange-50 border-orange-200'],
        'oragon'     => ['label' => 'Oragon',     'icon' => '🌶️', 'color' => 'text-red-600', 'bg' => 'bg-red-50 border-red-200'],
        'resonance'  => ['label' => 'Resonance',  'icon' => '🌻', 'color' => 'text-green-600', 'bg' => 'bg-green-50 border-green-200'],
    ];

    public function mount(Post $post)
    {
        $this->post = $post;
    }

    public function toggleElement($type)
    {
        if (!auth()->check()) return redirect()->route('login');

        if (!array_key_exists($type, $this->availableElements)) return;

        $existing = Element::where('post_id', $this->post->id)
                           ->where('user_id', auth()->id())
                           ->first();

        if ($existing) {
            if ($existing->type === $type) {
                // If they clicked the same element, remove it (unlike)
                $existing->delete();
            } else {
                // If they clicked a different element, update it
                $existing->update(['type' => $type]);
            }
        } else {
            // Create new element
            Element::create([
                'post_id' => $this->post->id,
                'user_id' => auth()->id(),
                'type' => $type
            ]);
        }
    }

    public function postComment()
    {
        if (!auth()->check()) return redirect()->route('login');

        $this->validate(['newComment' => 'required|string|max:1000']);

        // <-- UPDATED CREATION CALL
        PostComment::create([
            'post_id' => $this->post->id,
            'user_id' => auth()->id(),
            'content' => $this->newComment
        ]);

        $this->newComment = ''; 
    }

    public function render()
    {
        // Fetch fresh counts and the user's current element
        $elementCounts = $this->post->elements()
            ->selectRaw('type, count(*) as count')
            ->groupBy('type')
            ->pluck('count', 'type')
            ->toArray();

        $userElement = auth()->check()
            ? Element::where('post_id', $this->post->id)->where('user_id', auth()->id())->value('type')
            : null;

        return view('livewire.community.post-engagement', [
            'elementCounts' => $elementCounts,
            'userElement' => $userElement,
            'comments' => $this->post->comments()->with('user')->get(),
        ]);
    }
}
