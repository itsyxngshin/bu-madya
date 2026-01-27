<?php

namespace App\Livewire\Open;

use Livewire\Component;
use Livewire\WithPagination; // <--- REQUIRED for ->links() to work
use App\Models\Evaluation;
use App\Models\EvaluationResponse;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;

#[Layout('layouts.madya-template')]
class EvaluationList extends Component
{
    use WithPagination; // <--- Don't forget to include this trait

    public function render()
    {
        $evaluations = Evaluation::where('is_active', true)
            ->latest()
            ->paginate(9) // <--- Returns a Paginator object
            ->through(function ($evaluation) { // <--- 'through' keeps pagination working while editing data
                
                $hasResponded = false;
                
                // Check if user already submitted
                if (Auth::check()) {
                    $hasResponded = EvaluationResponse::where('evaluation_id', $evaluation->id)
                        ->where('user_id', Auth::id())
                        ->exists();
                }

                // Append status dynamically
                $evaluation->status = $hasResponded ? 'Completed' : 'Pending';
                
                return $evaluation;
            });

        return view('livewire.open.evaluation-list', [
            'evaluations' => $evaluations
        ]);
    }
}