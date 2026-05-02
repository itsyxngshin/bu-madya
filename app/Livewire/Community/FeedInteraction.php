<?php

namespace App\Livewire\Community;

use Livewire\Component;
use App\Models\Post;
use App\Models\Element;
use App\Models\PostComment;
use Illuminate\Support\Str;

class FeedInteraction extends Component
{
    public Post $post;
    public $newComment = '';

    // Restored your exact BU MADYA branding elements!
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

        // The Un-react Logic
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

    public function requirkPost()
    {
        if (!auth()->check()) return redirect()->route('login');

        $alreadyRequirked = Post::where('user_id', auth()->id())
            ->where('reposted_post_id', $this->post->id)
            ->exists();

        if ($alreadyRequirked) {
            session()->flash('error', 'You already Re-quirked this!');
            return;
        }

        Post::create([
            'user_id' => auth()->id(),
            'reposted_post_id' => $this->post->id,
            'title' => 'Re-quirk: ' . $this->post->title,
            'content' => '', 
            'slug' => Str::slug('requirk-' . uniqid()),
            'is_published' => true,
            'published_at' => now(),
        ]);

        session()->flash('success', 'Successfully Re-quirked!');
    }

    public function render()
    {
        $elementCounts = $this->post->elements()->selectRaw('type, count(*) as count')->groupBy('type')->pluck('count', 'type')->toArray();
        $userElement = auth()->check() ? Element::where('post_id', $this->post->id)->where('user_id', auth()->id())->value('type') : null;
        
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