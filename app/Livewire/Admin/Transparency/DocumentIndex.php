<?php

namespace App\Livewire\Admin\Transparency;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\TransparencyDocument;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;

#[Layout('layouts.madya-admin-deck')]
class DocumentIndex extends Component
{
    use WithPagination;

    public $search = '';

    public function delete($id)
    {
        $doc = TransparencyDocument::findOrFail($id);
        
        // Delete physical file
        if (Storage::disk('public')->exists($doc->file_path)) {
            Storage::disk('public')->delete($doc->file_path);
        }
        
        $doc->delete();
        session()->flash('message', 'Document deleted successfully.');
    }

    public function render()
    {
        $documents = TransparencyDocument::with('category')
            ->where('title', 'like', '%' . $this->search . '%')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.admin.transparency.document-index', [
            'documents' => $documents
        ]); // Assuming you have an admin layout
    }
}