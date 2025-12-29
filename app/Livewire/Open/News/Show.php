<?php

namespace App\Livewire\Open\News;

use Livewire\Component;
use App\Models\News;
use App\Models\NewsVote;
use App\Models\Comment;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout; 

#[Layout('layouts.madya-template')]
class Show extends Component
{
    public News $article;
    
    // Comment Form
    public $newComment;

    // Vote State
    public $likesCount;
    public $isLiked = false;

    public function mount($slug)
    {
        // Eager load relationships for performance
        $this->article = News::with(['category', 'authors', 'comments.user', 'sdgs'])
                             ->where('slug', $slug)
                             ->firstOrFail();

        // Restriction of access to the article
        if ($this->article->status !== 'active') {
            
            // If user is not logged in, strictly deny access
            if (!Auth::check()) {
                abort(404); // Return 404 so guests don't know the slug exists
            }

            $user = Auth::user();

            // Allow access ONLY if:
            // A. User is a Director
            // B. User is the Creator (user_id)
            // C. User is listed as a Co-Author in the pivot table
            $checkDir = $user->role->role_name === 'director';
            $checkCreator  = $user->id === $this->article->user_id;
            $checkAuthor = $this->article->authors->contains('user_id', $user->id);

            if (! ($checkDir || $checkCreator || $checkAuthor)) {
                abort(404); // Unauthorized users also get 404
            }
        }

        $this->refreshVoteState();
    }

    public function refreshVoteState()
    {
        $this->likesCount = $this->article->votes()->where('is_like', true)->count();
        
        if (Auth::check()) {
            $this->isLiked = $this->article->votes()
                ->where('user_id', Auth::id())
                ->where('is_like', true)
                ->exists();
        }
    }

    public function toggleLike()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $vote = NewsVote::where('user_id', Auth::id())
                        ->where('news_id', $this->article->id)
                        ->first();

        if ($vote) {
            // If already voted, toggle it (Remove vote implies unlike)
            $vote->delete();
        } else {
            // Create vote
            NewsVote::create([
                'user_id' => Auth::id(),
                'news_id' => $this->article->id,
                'is_like' => true
            ]);
        }

        $this->refreshVoteState();
    }

    public function postComment()
    {
        $this->validate(['newComment' => 'required|min:2|max:500']);

        if (!Auth::check()) {
            return redirect()->route('login');
        }

        Comment::create([
            'user_id' => Auth::id(),
            'news_id' => $this->article->id,
            'content' => $this->newComment
        ]);

        $this->newComment = ''; // Reset input
        $this->article->refresh(); // Refresh comments list
    }

    public function render()
    {
        return view('livewire.open.news.show'); // Adjust layout name as needed
    }
} 