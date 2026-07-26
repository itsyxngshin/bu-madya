<?php

namespace App\Livewire\Ibalong;

use Livewire\Component;
use App\Models\IbalongPost;
use App\Models\IbalongPostLike;
use App\Models\IbalongPostComment;
use App\Models\IbalongRegistration;
use App\Models\IbalongNotification;

class CommunityLogSingle extends Component
{
    public $postId;
    
    public $availableIdentities = [];
    public $postingAs = ''; 
    public $newComments = []; 
    public $replyingTo = null;

    public function mount($id)
    {
        $this->postId = $id;

        $user = auth('ibalong')->user();
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

        $content = $this->newComments[$contentKey];

        IbalongPostComment::create([
            'post_id' => $this->postId,
            'user_id' => auth('ibalong')->id(),
            'author_display' => $this->postingAs,
            'parent_id' => $parentId,
            'content' => $content,
        ]);

        $this->newComments[$contentKey] = ''; 
        $this->replyingTo = null;
    }

    public function render()
    {
        $post = IbalongPost::with(['user', 'images', 'likes', 'comments.user', 'comments.replies.user'])
            ->findOrFail($this->postId);

        return view('livewire.ibalong.community-log-single', compact('post'))
            ->layout('layouts.dashboard');
    }
}