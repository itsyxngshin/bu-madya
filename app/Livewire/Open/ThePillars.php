<?php

namespace App\Livewire\Open;

use Livewire\Component;
use App\Models\Pillar;
use App\Models\PillarVote;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Livewire\Attributes\Layout;

#[Layout('layouts.madya-template')] 
class ThePillars extends Component 
{
    public function vote($questionId, $optionId)
    {
        $userId = Auth::id();
        $sessionId = Session::getId();

        // 1. Check Vote Existence (User OR Session)
        $exists = PillarVote::where('pillar_question_id', $questionId)
            ->where(function($q) use ($userId, $sessionId) {
                if ($userId) {
                    $q->where('user_id', $userId);
                } else {
                    $q->where('session_id', $sessionId);
                }
            })->exists();

        if ($exists) {
            $this->dispatch('notify', message: 'You already voted on this question.'); // Assume you have a toaster
            return;
        }

        // 2. Cast Vote
        PillarVote::create([
            'pillar_question_id' => $questionId,
            'pillar_option_id' => $optionId,
            'user_id' => $userId,
            'session_id' => $sessionId,
        ]);
        
        // No flash message needed to avoid reloading layout, just reactive UI update
    }

    public function render()
    {
        $userId = Auth::id();
        $sessionId = Session::getId();

        $pillars = Pillar::with(['questions.options.votes'])
            ->where('is_active', true)
            ->latest()
            ->get()
            ->map(function ($pillar) use ($userId, $sessionId) {
                
                // Map Questions
                $pillar->mapped_questions = $pillar->questions->map(function($q) use ($userId, $sessionId) {
                    
                    $totalVotes = $q->options->sum(fn($o) => $o->votes->count());
                    
                    // Check if voted
                    $userVote = $q->options->flatMap->votes->first(function($v) use ($userId, $sessionId) {
                        if ($userId && $v->user_id === $userId) return true;
                        return $v->session_id === $sessionId;
                    });

                    return [
                        'id' => $q->id,
                        'text' => $q->question_text,
                        'total_votes' => $totalVotes,
                        'has_voted' => (bool) $userVote,
                        'voted_option_id' => $userVote?->pillar_option_id,
                        'options' => $q->options->map(function($opt) use ($totalVotes) {
                             $isDirector = Auth::check() && Auth::user()->role->role_name === 'director';

                            return [
                                'id' => $opt->id,
                                'label' => $opt->label,
                                'color' => $opt->color,
                                'count' => $opt->votes->count(),
                                'percent' => $totalVotes > 0 ? round(($opt->votes->count() / $totalVotes) * 100) : 0,
                                
                                // SECURITY FIX: Only fetch names if Admin. Otherwise, send empty list.
                                'voters' => $isDirector
                                    ? $opt->votes->whereNotNull('user_id')->map(function($vote) {
                                        return [
                                            'name' => $vote->user->name ?? 'Unknown',
                                            'avatar' => $vote->user->profile_photo_url ?? null,
                                            'date' => $vote->created_at->diffForHumans()
                                        ];
                                    }) 
                                    : collect([]), 
                            ];
                        })
                    ];
                });
                return $pillar;
            });
        return view('livewire.open.the-pillars', ['pillars' => $pillars]);
    }
}