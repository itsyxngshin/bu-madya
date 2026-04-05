<?php

namespace App\Livewire\Open;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Evaluation;
use App\Models\Visitor;
use Livewire\Attributes\Layout;

#[Layout('layouts.madya-template')] // Uses your public layout
class EvaluationList extends Component
{
    use WithPagination;

    public $visitorCount = 0;

    public function mount()
    {
        // Example: If you are tracking visitors, you can load it here
        // $this->visitorCount = Visitor::count();
    }

    public function render()
    {
        // 1. Fetch only ACTIVE evaluations for the public to see
        // 2. Eager load the project relationship if you use it in the blade
        $evaluations = Evaluation::with('project')
            ->where('is_active', true)
            ->latest()
            ->paginate(12);

        // Add a dynamic 'status' property for the UI (Pending vs Completed)
        $evaluations->getCollection()->transform(function ($eval) {
            // Check if the currently logged-in user has already submitted this form
            if (auth()->check() && $eval->responses()->where('user_id', auth()->id())->exists()) {
                $eval->status = 'Completed';
            } else {
                $eval->status = 'Pending';
            }
            return $eval;
        });

        return view('livewire.open.evaluation-list', compact('evaluations'));
    }
}
