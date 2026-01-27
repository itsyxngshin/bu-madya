<?php

namespace App\Livewire\Open;

use Livewire\Component;
use App\Models\Evaluation;
use App\Models\EvaluationResponse;
use Illuminate\Support\Facades\Auth;

class EvaluationList extends Component
{
    public function render()
    {
        // 1. Get all active evaluations
        $evaluations = Evaluation::where('is_active', true)
            ->latest()
            ->get()
            ->map(function ($evaluation) {
                // 2. Check if user has already responded
                $hasResponded = EvaluationResponse::where('evaluation_id', $evaluation->id)
                    ->where('user_id', Auth::id())
                    ->exists();

                $evaluation->status = $hasResponded ? 'Completed' : 'Pending';
                return $evaluation;
            });

        // 3. Separate into groups for the UI
        $pending = $evaluations->where('status', 'Pending');
        $completed = $evaluations->where('status', 'Completed');

        return view('livewire.open.evaluation-list', [
            'pending' => $pending,
            'completed' => $completed
        ]);
    }
}