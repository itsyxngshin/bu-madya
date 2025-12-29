<?php

namespace App\Livewire\Open;

use Livewire\Component;
use App\Models\Poll;
use App\Models\PollVote;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Livewire\Attributes\Layout;

#[Layout('layouts.madya-template')] 
class ThePillars extends Component 
{
    public function vote($pollId, $optionId)
    {
        $user = Auth::user();
        $sessionId = Session::getId();
        $ip = request()->ip();

        // 1. CHECK FOR EXISTING VOTE
        $query = PollVote::where('poll_id', $pollId);

        if ($user) {
            // If logged in, check by User ID
            $query->where('user_id', $user->id);
        } else {
            // If guest, check by Session ID (and optionally IP)
            $query->where('session_id', $sessionId);
        }

        if ($query->exists()) {
            session()->flash('message', 'You have already voted on this Pillar.');
            return;
        }

        // 2. CAST THE VOTE
        PollVote::create([
            'poll_id' => $pollId,
            'poll_option_id' => $optionId,
            'user_id' => $user ? $user->id : null, // Null if guest
            'session_id' => $sessionId,
            'ip_address' => $ip,
        ]);

        session()->flash('message', 'Your voice has been recorded.');
    }

    public function render()
    {
        $currentSession = Session::getId();
        $currentUser = Auth::id();

        $polls = Poll::with(['options.votes'])
            ->where('is_active', true)
            ->latest()
            ->get()
            ->map(function ($poll) use ($currentUser, $currentSession) {
                
                $totalVotes = $poll->options->sum(fn($o) => $o->votes->count());
                
                // 3. CHECK IF THIS VIEWER HAS VOTED
                // We check the "votes" relation to find a match for User ID OR Session ID
                $userVoteRecord = $poll->options->flatMap->votes->first(function($vote) use ($currentUser, $currentSession) {
                    if ($currentUser && $vote->user_id === $currentUser) {
                        return true;
                    }
                    return $vote->session_id === $currentSession;
                });

                return [
                    'id' => $poll->id,
                    'title' => $poll->title,
                    'question' => $poll->question,
                    'description' => $poll->description,
                    'image' => $poll->image_path,
                    'total_votes' => $totalVotes,
                    'has_voted' => (bool) $userVoteRecord,
                    'voted_option_id' => $userVoteRecord?->poll_option_id,
                    'options' => $poll->options->map(function ($option) use ($totalVotes) {
                        return [
                            'id' => $option->id,
                            'label' => $option->label,
                            'color' => $option->color,
                            'count' => $option->votes->count(),
                            'percent' => $totalVotes > 0 ? round(($option->votes->count() / $totalVotes) * 100) : 0,
                        ];
                    }),
                ];
            });

        return view('livewire.open.the-pillars', ['polls' => $polls]);
    }
}