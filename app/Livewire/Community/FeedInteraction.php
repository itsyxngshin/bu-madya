<?php

namespace App\Livewire\Community;

use Livewire\Component;
use App\Models\Post;
use App\Models\Element;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class FeedInteraction extends Component
{
    public Post $post;
    public $userElement = null;
    public $elementCounts = [];
    public $newComment = '';

    // Define your community's available reactions
    public $availableElements = [
        'like' => ['icon' => '👍', 'label' => 'Like', 'color' => 'text-blue-600'],
        'love' => ['icon' => '❤️', 'label' => 'Love', 'color' => 'text-red-600'],
        'fire' => ['icon' => '🔥', 'label' => 'Fire', 'color' => 'text-orange-500'],
        'lightbulb' => ['icon' => '💡', 'label' => 'Idea', 'color' => 'text-yellow-500'],
        'clap' => ['icon' => '👏', 'label' => 'Clap', 'color' => 'text-green-600'],
    ];

    public function mount(Post $post)
    {
        $this->post = $post;
        if (auth()->check()) {
            $this->userElement = Element::where('post_id', $post->id)
                ->where('user_id', auth()->id())
                ->value('type');
        }
        $this->refreshElementCounts();
    }

    public function refreshElementCounts()
    {
        $this->elementCounts = Element::where('post_id', $this->post->id)
            ->select('type', DB::raw('count(*) as total'))
            ->groupBy('type')
            ->pluck('total', 'type')
            ->toArray();
    }

    public function toggleElement($elementKey)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $existingReaction = Element::where('post_id', $this->post->id)
            ->where('user_id', auth()->id())
            ->first();

        // THE UN-REACT LOGIC
        if ($existingReaction) {
            if ($existingReaction->type === $elementKey) {
                $existingReaction->delete();
                $this->userElement = null;
            } else {
                $existingReaction->update(['type' => $elementKey]);
                $this->userElement = $elementKey;
            }
        } else {
            Element::create([
                'post_id' => $this->post->id,
                'user_id' => auth()->id(),
                'type' => $elementKey
            ]);
            $this->userElement = $elementKey;
        }

        $this->refreshElementCounts();
    }

    public function requirkPost()
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $alreadyRequirked = Post::where('user_id', auth()->id())
            ->where('reposted_post_id', $this->post->id)
            ->exists();

        if ($alreadyRequirked) {
            session()->flash('error', 'You already Re-quirked this post!');
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

        session()->flash('success', 'Successfully Re-quirked to your feed!');
    }

    public function postComment()
    {
        $this->validate(['newComment' => 'required|string|max:1000']);

        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $this->post->comments()->create([
            'user_id' => auth()->id(),
            'content' => $this->newComment
        ]);

        $this->newComment = '';
    }

    public function render()
    {
        $totalComments = $this->post->comments()->count();
        $recentComments = $this->post->comments()->with('user')->latest()->take(3)->get();

        return view('livewire.community.feed-interaction', [
            'totalComments' => $totalComments,
            'recentComments' => $recentComments
        ]);
    }
}