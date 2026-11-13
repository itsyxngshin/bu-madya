<?php

namespace App\Livewire\Elucida;

use Livewire\Component;
use App\Models\ElucidaSignatory;
use Illuminate\Database\Eloquent\Model;

class SignatoryManager extends Component
{
    // The polymorphic target (either an ElucidaLedger or ElucidaLiquidation instance)
    public Model $targetModel;

    public $signatories = [];

    // Form Fields
    public $role = 'Prepared by';
    public $name = '';
    public $position = '';
    public $order_sequence = 1;

    // Standard pre-defined roles for fast entry
    public $standardRoles = [
        'Prepared by',
        'Certified True and Correct',
        'Attested By',
        'Noted by',
        'Recommending Approval',
        'Approved'
    ];

    public function mount(Model $targetModel)
    {
        $this->targetModel = $targetModel;
        $this->refreshSignatories();
    }

    public function refreshSignatories()
    {
        $this->signatories = $this->targetModel->signatories()->orderBy('order_sequence')->get();
        // Auto-increment the order sequence for the next entry
        $this->order_sequence = $this->signatories->count() + 1;
    }

    public function addSignatory()
    {
        $this->validate([
            'role' => 'required|string',
            'name' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'order_sequence' => 'required|integer|min:1'
        ]);

        $this->targetModel->signatories()->create([
            'role' => $this->role,
            'name' => mb_strtoupper($this->name), // Force uppercase for official docs
            'position' => $this->position,
            'order_sequence' => $this->order_sequence,
        ]);

        $this->reset(['name', 'position']);
        $this->refreshSignatories();

        session()->flash('success', 'Signatory forged into the document hierarchy.');
    }

    public function removeSignatory($id)
    {
        ElucidaSignatory::findOrFail($id)->delete();
        $this->refreshSignatories();
    }

    public function render()
    {
        return view('livewire.elucida.signatory-manager');
    }
}
