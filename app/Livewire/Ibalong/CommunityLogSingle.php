<?php

namespace App\Livewire\Ibalong;

use Livewire\Component;
use Illuminate\Support\Facades\Storage;
use App\Models\IbalongPost;
use App\Models\IbalongPostLike;
use App\Models\IbalongPostComment;
use App\Models\IbalongRegistration;
use App\Models\IbalongNotification;
use App\Models\IbalongUser;

class CommunityLogSingle extends Component
{
    public $postId;
    
    // --- Identity, Comment & Mention State ---
    public $availableIdentities = [];
    public $mentionables = [];
    public $postingAs = ''; 
    public $commentIdentities = [];
    public $newComments = []; 
    public $replyingTo = null;

    // --- Post Edit & Delete State ---
    public $editingPostId = null;
    public $editContent = '';
    public $postToDelete = null;

    // --- Comment Edit & Delete State ---
    public $editingCommentId = null;
    public $editCommentContent = '';
    public $commentToDelete = null;

    public function mount($id)
    {
        $this->postId = $id;

        $user = auth('ibalong')->user();
        $this->availableIdentities = [];

        // 1. Set Identities
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

        // 2. Load Mentionables
        $teams = IbalongRegistration::select('team_name')->get();
        $teamMentions = $teams->map(function($t) {
            return ['tag' => str_replace(' ', '', $t->team_name), 'display' => $t->team_name];
        })->toArray();

        $admins = IbalongUser::whereIn('role_id', [1, 2])->select('name')->get();
        $adminMentions = $admins->map(function($a) {
            return ['tag' => str_replace(' ', '', $a->name), 'display' => $a->name . ' (Organizer)'];
        })->toArray();

        $this->mentionables = array_merge($teamMentions, $adminMentions);
    }

    public function setReply($commentId)
    {
        $this->replyingTo = $this->replyingTo === $commentId ? null : $commentId;
    }

    public function toggleLike()
    {
        $userId = auth('ibalong')->id();
        $like = IbalongPostLike::where('post_id', $this->postId)->where('user_id', $userId)->first();

        if ($like) {
            $like->delete();
        } else {
            IbalongPostLike::create(['post_id' => $this->postId, 'user_id' => $userId]);
        }
    }

    public function addComment($parentId = null)
    {
        $contentKey = $parentId ? 'reply_'.$parentId : 'main';

        if (empty(trim($this->newComments[$contentKey] ?? ''))) {
            return;
        }

        // Check if there's a specific identity selected for this form, otherwise fallback to main
        $identity = $this->commentIdentities[$parentId ?? 'main'] ?? $this->postingAs;
        $content = $this->newComments[$contentKey];

        $comment = IbalongPostComment::create([
            'post_id' => $this->postId,
            'user_id' => auth('ibalong')->id(),
            'author_display' => $identity,
            'parent_id' => $parentId,
            'content' => $content,
        ]);

        $post = IbalongPost::find($this->postId);
        if ($post->user_id !== auth('ibalong')->id()) {
            IbalongNotification::create([
                'user_id' => $post->user_id,
                'type' => 'reply',
                'message' => $identity . ' commented on your log.',
                'link' => route('ibalong.community-logs.show', $this->postId),
            ]);
        }

        $this->parseMentionsAndNotify($content, $this->postId, $identity);

        $this->newComments[$contentKey] = ''; 
        $this->replyingTo = null;
    }

    // --- POST EDIT & DELETE ---
    public function editPost()
    {
        $post = IbalongPost::findOrFail($this->postId);
        if ($post->user_id !== auth('ibalong')->id() && !in_array(auth('ibalong')->user()->role_id, [1, 2])) {
            return;
        }
        $this->editingPostId = $this->postId;
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
        $post = IbalongPost::findOrFail($this->postId);
        
        if ($post->user_id !== auth('ibalong')->id() && !in_array(auth('ibalong')->user()->role_id, [1, 2])) {
            return;
        }

        $post->update(['content' => $this->editContent]);
        $this->cancelEdit();
        session()->flash('success', 'Log updated successfully.');
    }

    public function confirmDeletePost()
    {
        $this->postToDelete = $this->postId;
    }

    public function cancelDeletePost()
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
        session()->flash('success', 'Log successfully deleted.');
        
        // Redirect to main feed since the post no longer exists
        return redirect()->route('ibalong.community-logs');
    }

    // --- COMMENT EDIT & DELETE ---
    public function editComment($commentId)
    {
        $comment = IbalongPostComment::findOrFail($commentId);
        if ($comment->user_id !== auth('ibalong')->id() && !in_array(auth('ibalong')->user()->role_id, [1, 2])) {
            return;
        }
        $this->editingCommentId = $commentId;
        $this->editCommentContent = $comment->content;
    }

    public function cancelEditComment()
    {
        $this->editingCommentId = null;
        $this->editCommentContent = '';
    }

    public function updateComment()
    {
        $this->validate(['editCommentContent' => 'required|string|max:2000']);
        $comment = IbalongPostComment::findOrFail($this->editingCommentId);
        
        if ($comment->user_id !== auth('ibalong')->id() && !in_array(auth('ibalong')->user()->role_id, [1, 2])) {
            return;
        }

        $comment->update(['content' => $this->editCommentContent]);
        $this->cancelEditComment();
    }

    public function confirmDeleteComment($commentId)
    {
        $this->commentToDelete = $commentId;
    }

    public function cancelDeleteComment()
    {
        $this->commentToDelete = null;
    }

    public function deleteComment()
    {
        if (!$this->commentToDelete) return;

        $comment = IbalongPostComment::findOrFail($this->commentToDelete);
        if ($comment->user_id !== auth('ibalong')->id() && !in_array(auth('ibalong')->user()->role_id, [1, 2])) {
            return;
        }

        $comment->delete();
        $this->commentToDelete = null;
    }

    // --- NOTIFICATION PARSER ---
    private function parseMentionsAndNotify($text, $postId, $author)
    {
        preg_match_all('/@([A-Za-z0-9_]+)/', $text, $matches);
        $mentions = array_unique($matches[1]);

        foreach ($mentions as $mention) {
            $team = IbalongRegistration::whereRaw("REPLACE(team_name, ' ', '') LIKE ?", ['%'.$mention.'%'])->first();
            if ($team && $team->user_id && $team->user_id !== auth('ibalong')->id()) {
                IbalongNotification::create([
                    'user_id' => $team->user_id,
                    'type' => 'mention',
                    'message' => $author . ' mentioned your team.',
                    'link' => route('ibalong.community-logs.show', $postId),
                ]);
                continue; 
            }

            $user = IbalongUser::whereRaw("REPLACE(name, ' ', '') LIKE ?", ['%'.$mention.'%'])->first();
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
        $post = IbalongPost::with(['user', 'images', 'likes', 'comments.user', 'comments.replies.user'])
            ->findOrFail($this->postId);

        return view('livewire.ibalong.community-log-single', compact('post'))
            ->layout('layouts.dashboard');
    }
}