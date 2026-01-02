<?php

namespace App\Livewire\Admin\Transparency;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\TransparencyDocument;
use App\Models\TransparencyCategory;
use App\Traits\SmartCompress; 
use Livewire\Attributes\Layout;

#[Layout('layouts.madya-admin-deck')] 
class DocumentForm extends Component
{
    use WithFileUploads, SmartCompress;

    public TransparencyDocument $document;
    public $file_upload;
    
    // Form Fields
    public $title;
    public $category_id;
    public $academic_year;
    public $description;
    public $published_date;
    public $is_edit = false;
    public $visibility = 'public';

    // Academic Year Generator (Last 5 years)
    public function getYearsProperty()
    {
        $current = now()->year;
        $years = [];
        for ($i = 0; $i < 5; $i++) {
            $start = $current - $i;
            $end = $start + 1;
            $years[] = "$start-$end";
        }
        return $years;
    }

    public function mount(TransparencyDocument $document = null)
    {
        if ($document && $document->exists) {
            $this->document = $document;
            $this->title = $document->title;
            $this->category_id = $document->category_id;
            $this->academic_year = $document->academic_year;
            $this->description = $document->description;
            $this->published_date = $document->published_date->format('Y-m-d');
            $this->is_edit = true;
        } 
        else {
            $this->document = new TransparencyDocument();
            $this->published_date = now()->format('Y-m-d');
            // Default Academic Year logic (e.g. current month > June ? This year : Last year)
            $this->academic_year = (now()->month > 6) 
                ? now()->year . '-' . (now()->year + 1) 
                : (now()->year - 1) . '-' . now()->year;
        }
    }

    public function save()
    {
        $this->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:transparency_categories,id',
            'academic_year' => 'required|string',
            'description' => 'nullable|string',
            'published_date' => 'required|date',
            'visibility' => 'required|in:public,auth,admin,director',
            // File is required only on Create
            'file_upload' => $this->is_edit ? 'nullable|file|max:20480' : 'required|file|max:20480', 
        ]);

        $data = [
            'title' => $this->title,
            'category_id' => $this->category_id,
            'visibility' => $this->visibility,
            'academic_year' => $this->academic_year,
            'description' => $this->description,
            'published_date' => $this->published_date,
        ];

        // Handle File Upload & Compression
        if ($this->file_upload) {
            // Use our SmartCompress Trait
            $path = $this->compressAndStore($this->file_upload, 'transparency-files');
            $data['file_path'] = $path;
        }

        if ($this->is_edit) {
            $this->document->update($data);
            $message = 'Document updated successfully.';
        } else {
            TransparencyDocument::create($data);
            $message = 'Document uploaded successfully.';
        }

        return redirect()->route('admin.transparency.index')->with('message', $message);
    }

    public function render()
    {
        return view('livewire.admin.transparency.document-form', [
            'categories' => TransparencyCategory::all()
        ]);
    }
}