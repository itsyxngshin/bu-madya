<?php

namespace App\Livewire\Ibalong;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\IbalongPost;
use App\Models\IbalongPostImage;
use App\Models\IbalongPostLike;
use App\Models\IbalongPostComment;
use App\Models\IbalongRegistration; // Needed to fetch the roster

class CommunityLogs extends Component
{
    use WithFileUploads;

    public $content = '';
    public $photos = [];

    // Identity State
    public $availableIdentities = [];
    public $postingAs = ''; // Identity for creating a new post
    public $commentIdentities = []; // Stores the selected identity for each comment box
    public $newComments = [];

    public function mount()
    {
        $user = auth('ibalong')->user();
        $this->availableIdentities = [];

        // Check if the logged-in user is tied to a Cohort Registration
        $team = IbalongRegistration::where('user_id', $user->id)->with('members')->first();

        if ($team) {
            // Add the collective Team identity
            $this->availableIdentities[$team->team_name] = $team->team_name . ' (Entire Team)';

            // Add individual roster members
            foreach ($team->members as $member) {
                $this->availableIdentities[$member->full_name] = $member->full_name . ' (' . $member->team_role . ')';
            }

            // Set default posting identity to the Team Name
            $this->postingAs = $team->team_name;
        } else {
            // For Admins and Facilitators, default to their user name and designation
            $identity = $user->name . ($user->designation ? ' - ' . $user->designation : '');
            $this->availableIdentities[$identity] = $identity . ' (System Account)';
            $this->postingAs = $identity;
        }
    }

    public function createPost()
    {
        $this->validate([
            'content' => 'required|string|max:2000',
            'photos.*' => 'image|max:5120',
            'postingAs' => 'required|string'
        ]);

        $user = auth('ibalong')->user();
        $isAnnouncement = in_array($user->role_id, [1, 2]);

        $post = IbalongPost::create([
            'user_id' => $user->id,
            'author_display' => $this->postingAs, // Save the selected identity
            'content' => $this->content,
            'is_announcement' => $isAnnouncement,
        ]);

        if (!empty($this->photos)) {
            foreach ($this->photos as $photo) {
                $path = $photo->store('community_logs', 'public');
                IbalongPostImage::create([
                    'post_id' => $post->id,
                    'image_path' => $path,
                ]);
            }
        }

        $this->reset(['content', 'photos']);
        session()->flash('success', 'Log published to the community feed.');
    }

    public function toggleLike($postId)
    {
        $userId = auth('ibalong')->id();
        $like = IbalongPostLike::where('post_id', $postId)->where('user_id', $userId)->first();

        if ($like) {
            $like->delete();
        } else {
            IbalongPostLike::create(['post_id' => $postId, 'user_id' => $userId]);
        }
    }

    public function addComment($postId)
    {
        if (empty(trim($this->newComments[$postId] ?? ''))) {
            return;
        }

        // Get the selected identity for this specific comment box, fallback to the main posting identity
        $identity = $this->commentIdentities[$postId] ?? $this->postingAs;

        IbalongPostComment::create([
            'post_id' => $postId,
            'user_id' => auth('ibalong')->id(),
            'author_display' => $identity, // Save the selected identity
            'content' => $this->newComments[$postId],
        ]);

        $this->newComments[$postId] = '';
    }

    public function render()
    {
        $posts = IbalongPost::with(['user', 'images', 'likes', 'comments.user'])
            ->latest()
            ->get();

        return view('livewire.ibalong.community-logs', compact('posts'))
            ->layout('layouts.dashboard');
    }
}
