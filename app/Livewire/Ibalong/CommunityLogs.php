<?php

namespace App\Livewire\Ibalong;

use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Storage;
use App\Models\IbalongPost;
use App\Models\IbalongPostImage;
use App\Models\IbalongPostLike;
use App\Models\IbalongPostComment;
use App\Models\IbalongRegistration;
use App\Models\IbalongNotification;
use App\Models\User;

class CommunityLogs extends Component
{
    use WithFileUploads, WithPagination;

    // --- Create Post State ---
    public $content = '';
    public $photos = [];
    
    // --- Identity, Comment & Mention State ---
    public $availableIdentities = [];
    public $mentionables = []; // Holds the list of taggable users/teams
    public $postingAs = ''; 
    public $commentIdentities = []; 
    public $newComments = []; 
    public $replyingTo = null;

    // --- Algorithm State ---
    public $filterType = 'latest'; 

    // --- Edit & Delete State ---
    public $editingPostId = null;
    public $editContent = '';
    public $postToDelete = null;

    public function mount()
    {
        $user = auth('ibalong')->user();
        $this->availableIdentities = [];

        // 1. Establish Posting Identities
        $team = IbalongRegistration::where('user_id', $user->id)->with('members')->first();
        if ($team) {
            $this->availableIdentities[$team->team_name] = $team->team_name . ' (Entire Team)';
            foreach ($team->members as $member) {
                $this->availableIdentities[$member->full_name] = $member->full_name . ' (' . $member->team_role . ')';
            }
            $this->postingAs = $team->team_name;
        } else {
            $identity = $user->name . ($user->designation ? ' - ' . $user->designation : '');
            $this->availableIdentities[$identity] = $identity . ' (System Account)';
            $this->postingAs = $identity;
        }

        // 2. Load Mentionable Entities (Teams & Organizers)
        $teams = IbalongRegistration::select('team_name')->get();
        $teamMentions = $teams->map(function($t) {
            return ['tag' => str_replace(' ', '', $t->team_name), 'display' => $t->team_name];
        })->toArray();

        $admins = User::whereIn('role_id', [1, 2])->select('name')->get();
        $adminMentions = $admins->map(function($a) {
            return ['tag' => str_replace(' ', '', $a->name), 'display' => $a->name . ' (Organizer)'];
        })->toArray();

        $this->mentionables = array_merge($teamMentions, $adminMentions);
    }

    public function updatingFilterType()
    {
        $this->resetPage();
    }

    public function setReply($commentId)
    {
        $this->replyingTo = $this->replyingTo === $commentId ? null : $commentId;
    }

    public function createPost()
    {
        $this->validate([
            'content' => 'required|string|max:5000',
            'photos.*' => 'image|max:5120',
            'postingAs' => 'required|string'
        ]);

        $user = auth('ibalong')->user();
        $isAnnouncement = in_array($user->role_id, [1, 2]);

        $post = IbalongPost::create([
            'user_id' => $user->id,
            'author_display' => $this->postingAs,
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

        if ($isAnnouncement) {
            $this->notifyAllUsers('announcement', 'New Official Announcement: ' . mb_strimwidth($this->content, 0, 40, '...'), route('ibalong.community-logs.show', $post->id));
        }

        $this->parseMentionsAndNotify($this->content, $post->id, $this->postingAs);

        $this->reset(['content', 'photos']);
        session()->flash('success', 'Log published to the community feed.');
    }

    public function addComment($postId, $parentId = null)
    {
        $contentKey = $parentId ? 'reply_'.$parentId : $postId;

        if (empty(trim($this->newComments[$contentKey] ?? ''))) {
            return;
        }

        $identity = $this->commentIdentities[$postId] ?? $this->postingAs;
        $content = $this->newComments[$contentKey];

        $comment = IbalongPostComment::create([
            'post_id' => $postId,
            'user_id' => auth('ibalong')->id(),
            'author_display' => $identity,
            'parent_id' => $parentId,
            'content' => $content,
        ]);

        $post = IbalongPost::find($postId);
        if ($post->user_id !== auth('ibalong')->id()) {
            IbalongNotification::create([
                'user_id' => $post->user_id,
                'type' => 'reply',
                'message' => $identity . ' commented on your log.',
                'link' => route('ibalong.community-logs.show', $postId),
            ]);
        }

        $this->parseMentionsAndNotify($content, $postId, $identity);

        $this->newComments[$contentKey] = ''; 
        $this->replyingTo = null;
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

    // --- EDIT & DELETE METHODS ---
    public function editPost($postId)
    {
        $post = IbalongPost::findOrFail($postId);
        
        if ($post->user_id !== auth('ibalong')->id() && !in_array(auth('ibalong')->user()->role_id, [1, 2])) {
            return;
        }

        $this->editingPostId = $postId;
        $this->editContent = $post->content;
    }

    public function cancelEdit()
    {
        $this->editingPostId = null;
        $this->editContent = '';
    }

    public function updatePost()
    {
        $this->validate(['editContent' => 'required|string|max:5000']);
        
        $post = IbalongPost::findOrFail($this->editingPostId);
        
        if ($post->user_id !== auth('ibalong')->id() && !in_array(auth('ibalong')->user()->role_id, [1, 2])) {
            return;
        }

        $post->update(['content' => $this->editContent]);
        
        $this->cancelEdit();
        session()->flash('success', 'Log updated successfully.');
    }

    public function confirmDelete($postId)
    {
        $this->postToDelete = $postId;
    }

    public function cancelDelete()
    {
        $this->postToDelete = null;
    }

    public function deletePost()
    {
        if (!$this->postToDelete) return;

        $post = IbalongPost::findOrFail($this->postToDelete);
        
        if ($post->user_id !== auth('ibalong')->id() && !in_array(auth('ibalong')->user()->role_id, [1, 2])) {
            return;
        }

        foreach($post->images as $image) {
            Storage::disk('public')->delete($image->image_path);
        }

        $post->delete();
        $this->postToDelete = null;
        session()->flash('success', 'Log successfully deleted.');
    }

    // --- NOTIFICATION HELPERS ---
    private function notifyAllUsers($type, $message, $link)
    {
        $userIds = User::pluck('id'); 
        $notifications = [];
        foreach ($userIds as $id) {
            if ($id !== auth('ibalong')->id()) {
                $notifications[] = [
                    'user_id' => $id, 'type' => $type, 'message' => $message, 
                    'link' => $link, 'created_at' => now(), 'updated_at' => now()
                ];
            }
        }
        IbalongNotification::insert($notifications);
    }

    private function parseMentionsAndNotify($text, $postId, $author)
    {
        preg_match_all('/@([A-Za-z0-9_]+)/', $text, $matches);
        $mentions = array_unique($matches[1]);

        foreach ($mentions as $mention) {
            // 1. Check if a team was mentioned
            $team = IbalongRegistration::whereRaw("REPLACE(team_name, ' ', '') LIKE ?", ['%'.$mention.'%'])->first();
            if ($team && $team->user_id && $team->user_id !== auth('ibalong')->id()) {
                IbalongNotification::create([
                    'user_id' => $team->user_id,
                    'type' => 'mention',
                    'message' => $author . ' mentioned your team.',
                    'link' => route('ibalong.community-logs.show', $postId),
                ]);
                continue; // Move to next mention if team was found
            }

            // 2. Check if a specific Organizer/Facilitator was mentioned
            $user = User::whereRaw("REPLACE(name, ' ', '') LIKE ?", ['%'.$mention.'%'])->first();
            if ($user && $user->id !== auth('ibalong')->id()) {
                IbalongNotification::create([
                    'user_id' => $user->id,
                    'type' => 'mention',
                    'message' => $author . ' mentioned you.',
                    'link' => route('ibalong.community-logs.show', $postId),
                ]);
            }
        }
    }

    public function render()
    {
        $query = IbalongPost::with(['user', 'images', 'likes', 'comments.user', 'comments.replies.user'])
            ->withCount(['likes', 'comments']);

        // Algorithm Logic
        if ($this->filterType === 'trending') {
            $query->orderByRaw('(likes_count + comments_count) DESC')->latest();
        } else {
            $query->latest();
        }

        $posts = $query->paginate(15);

        return view('livewire.ibalong.community-logs', compact('posts'))
            ->layout('layouts.dashboard');
    }
}