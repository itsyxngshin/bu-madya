<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\MembershipWave;
use App\Models\AcademicYear;
use Livewire\Attributes\Layout;

#[Layout('layouts.madya-template')]
class MembershipSetting extends Component
{
    public $academic_year_id;
    public $name, $start_date, $end_date;
    public $editingWaveId = null;

    public function render()
    {
        return view('livewire.admin.membership-setting', [
            'waves' => MembershipWave::with('academicYear')->latest()->get(),
            'years' => AcademicYear::where('is_active', true)->get(),
        ]);
    }

    public function editWave($id)
    {
        $wave = MembershipWave::find($id);
        
        if ($wave) {
            $this->editingWaveId = $wave->id;
            $this->academic_year_id = $wave->academic_year_id;
            $this->name = $wave->name;
            $this->start_date = $wave->start_date->format('Y-m-d');
            $this->end_date = $wave->end_date->format('Y-m-d');
        }
    }

    // 2. CANCEL EDITING
    public function cancelEdit()
    {
        $this->reset(['editingWaveId', 'academic_year_id', 'name', 'start_date', 'end_date']);
    }

    // 3. SAVE (Create or Update logic combined)
    public function saveWave()
    {
        $this->validate([
            'academic_year_id' => 'required|exists:academic_years,id',
            'name' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        if ($this->editingWaveId) {
            // UPDATE LOGIC
            $wave = MembershipWave::find($this->editingWaveId);
            $wave->update([
                'academic_year_id' => $this->academic_year_id,
                'name' => $this->name,
                'start_date' => $this->start_date,
                'end_date' => $this->end_date,
            ]);
            session()->flash('message', 'Wave updated successfully!');
        } else {
            // CREATE LOGIC
            MembershipWave::create([
                'academic_year_id' => $this->academic_year_id,
                'name' => $this->name,
                'start_date' => $this->start_date,
                'end_date' => $this->end_date,
                'is_active' => false,
            ]);
            session()->flash('message', 'New Wave created!');
        }

        $this->cancelEdit(); // Reset form
    }

    public function toggleWave($id)
    {
        $wave = MembershipWave::find($id);
        
        // If we are turning this ON, turn all others OFF (One active wave at a time)
        if (!$wave->is_active) {
            MembershipWave::query()->update(['is_active' => false]);
        }

        $wave->update(['is_active' => !$wave->is_active]);
    }
} 
