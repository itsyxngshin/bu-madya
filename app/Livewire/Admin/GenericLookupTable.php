<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Str;

class GenericLookupTable extends Component
{
    use WithPagination;

    public $modelClass; // e.g., "App\Models\Committee"
    public $title;      // e.g., "Committee"
    
    // Form Inputs
    public $name, $description, $color, $selectedId;
    public $isModalOpen = false;
    public $isDeleteModalOpen = false;

    // Called when component initializes
    public function mount($model, $title)
    {
        $this->modelClass = $model;
        $this->title = $title;
    }

    public function render()
    {
        // Dynamically query the passed model class
        $items = $this->modelClass::orderBy('created_at', 'desc')->paginate(10);

        return view('livewire.admin.generic-lookup-table', [
            'items' => $items
        ]);
    }

    public function create()
    {
        $this->reset(['name', 'description', 'color', 'selectedId']);
        $this->isModalOpen = true;
    }

    public function edit($id)
    {
        $item = $this->modelClass::find($id);
        $this->selectedId = $id;
        $this->name = $item->name;
        $this->description = $item->description ?? ''; 
        $this->color = $item->color ?? ''; 
        
        $this->isModalOpen = true;
    }

    public function store()
    {
        $this->validate([
            'name' => 'required|string|max:255',
        ]);

        $data = [
            'name' => $this->name,
            'description' => $this->description,
            'color' => $this->color,
            'slug' => Str::slug($this->name), // Auto-generate slug
        ];
        
        if ($this->selectedId) {
            $this->modelClass::find($this->selectedId)->update($data);
            session()->flash('message', $this->title . ' updated successfully.');
        } else {
            $this->modelClass::create($data);
            session()->flash('message', $this->title . ' created successfully.');
        }

        $this->isModalOpen = false;
        $this->reset(['name', 'description', 'color', 'selectedId']);
    }

    public function confirmDelete($id)
    {
        $this->selectedId = $id;
        $this->isDeleteModalOpen = true; // You can implement a simple delete modal in the view
    }
    
    // Simplistic delete (call this from your delete confirmation modal)
    public function delete() 
    {
        $item = $this->modelClass::find($this->selectedId);
        if($item) $item->delete();
        $this->isDeleteModalOpen = false;
    }
}