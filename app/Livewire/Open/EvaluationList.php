<?php

namespace App\Livewire\Open;

use Livewire\Component;
use App\Models\Evaluation;
use App\Models\EvaluationResponse;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;

#[Layout('layouts.madya-template')] // Ensure this matches your public layout name
class EvaluationList extends Component
{
    public function render()
    {
        // 1. Fetch only ACTIVE evaluations
        $evaluations = Evaluation::where('is_active', true)
            ->latest()
            ->get()
            ->map(function ($evaluation) {
                // 2. Check if the logged-in user has already submitted a response
                $hasResponded = false;
                
                if (Auth::check()) {
                    $hasResponded = EvaluationResponse::where('evaluation_id', $evaluation->id)
                        ->where('user_id', Auth::id())
                        ->exists();
                }

                // Add a temporary 'status' property to the object for the view
                $evaluation->status = $hasResponded ? 'Completed' : 'Pending';
                
                return $evaluation;
            });

        // 3. Pass '$evaluations' to the view
        return view('livewire.open.evaluation-list', [
            'evaluations' => $evaluations
        ]);
    }
}