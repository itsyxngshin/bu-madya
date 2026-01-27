<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Evaluation;
use Livewire\Attributes\Layout;

#[Layout('layouts.madya-admin-deck')]
class EvaluationList extends Component
{
    use WithPagination;

    public $search = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function toggleStatus($id)
    {
        $evaluation = Evaluation::find($id);
        $evaluation->is_active = !$evaluation->is_active;
        $evaluation->save();
        
        session()->flash('success', $evaluation->is_active ? 'Form published!' : 'Form unpublished.');
    }

    public function delete($id)
    {
        Evaluation::find($id)->delete();
        session()->flash('success', 'Evaluation form deleted.');
    }

    public function render()
    {
        $evaluations = Evaluation::query()
            ->withCount('responses') // Efficiently count responses
            ->where('title', 'like', '%' . $this->search . '%')
            ->latest()
            ->paginate(9);

        return view('livewire.admin.evaluation-list', [
            'evaluations' => $evaluations
        ]);
    }
}