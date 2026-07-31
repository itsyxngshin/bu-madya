<?php

namespace App\Livewire\Ibalong\Admin;

use Livewire\Component;
use App\Models\IbalongQuest;
use Illuminate\Support\Facades\DB;

class QuestBuilder extends Component
{
    public $title, $description, $deadline, $is_published = false;

    // Dynamic Arrays for the Builder
    public $tasks = [];
    public $criteria = [];

    public function mount()
    {
        // Initialize with one empty task and one criteria block by default
        $this->addTask();
        $this->addCriteria();
    }

    public function addTask()
    {
        $this->tasks[] = [
            'question' => '',
            'type' => 'short_text',
            'options' => '', // Comma-separated for dropdowns/checklists
            'max_file_size_mb' => 5,
            'is_required' => true,
        ];
    }

    public function removeTask($index)
    {
        unset($this->tasks[$index]);
        $this->tasks = array_values($this->tasks); // Re-index
    }

    public function addCriteria()
    {
        $this->criteria[] = [
            'name' => '',
            'max_score' => 10,
            'description' => '',
            'levels' => [
                ['degree' => 'Outstanding', 'range' => '', 'description' => ''],
                ['degree' => 'Strong', 'range' => '', 'description' => ''],
                ['degree' => 'Developing', 'range' => '', 'description' => ''],
                ['degree' => 'Emerging', 'range' => '', 'description' => ''],
            ]
        ];
    }

    public function removeCriteria($index)
    {
        unset($this->criteria[$index]);
        $this->criteria = array_values($this->criteria);
    }

    public function saveQuest()
    {
        $this->validate([
            'title' => 'required|string|max:255',
            'deadline' => 'required|date',
            'tasks.*.question' => 'required|string',
            'criteria.*.name' => 'required|string',
            'criteria.*.max_score' => 'required|numeric|min:1',
        ]);

        DB::transaction(function () {
            $quest = IbalongQuest::create([
                'title' => $this->title,
                'description' => $this->description,
                'deadline' => $this->deadline,
                'is_published' => $this->is_published,
            ]);

            foreach ($this->tasks as $index => $taskData) {
                $quest->tasks()->create([
                    'question' => $taskData['question'],
                    'type' => $taskData['type'],
                    'options' => $taskData['type'] === 'dropdown' || $taskData['type'] === 'checklist'
                                 ? explode(',', $taskData['options'])
                                 : null,
                    'max_file_size_mb' => $taskData['type'] === 'file' ? $taskData['max_file_size_mb'] : null,
                    'is_required' => $taskData['is_required'],
                    'order_index' => $index,
                ]);
            }

            foreach ($this->criteria as $critData) {
                $quest->criteria()->create([
                    'name' => $critData['name'],
                    'max_score' => $critData['max_score'],
                    'description' => $critData['description'],
                    'rubric_levels' => $critData['levels'],
                ]);
            }
        });

        session()->flash('success', 'Quest successfully forged and added to the logs!');
        return redirect()->route('admin.quests.index'); // Adjust to your route name
    }

    public function render()
    {
        return view('livewire.ibalong.admin.quest-builder')->layout('layouts.dashboard');
    }
}
