<?php

namespace App\Livewire\Admin\Content;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\AnnouncementType;
use App\Models\SpotlightCategory;
use Illuminate\Support\Str;

#[Layout('layouts.madya-admin-deck')]
class ContentReferences extends Component
{
    public $activeTab = 'announcements'; // 'announcements' or 'spotlights'

    // Form fields for Announcement Type
    public $type_id = null;
    public $type_name = '';
    public $color_theme = 'bg-gray-800 text-gray-200';
    public $icon_key = 'info';

    // Form fields for Spotlight Category
    public $category_id = null;
    public $category_name = '';

    // Predefined safe options for Admins
    public $availableColors = [
        'bg-red-600 text-white' => 'Red (Emergency / Critical)',
        'bg-orange-500 text-white' => 'Orange (Urgent / Warning)',
        'bg-blue-600 text-white' => 'Blue (Information / DRR)',
        'bg-green-600 text-white' => 'Green (Success / Go)',
        'bg-purple-600 text-white' => 'Purple (Special / Royal)',
        'bg-gray-800 text-gray-200' => 'Dark (General / Default)',
    ];

    public $availableIcons = [
        'alert' => '<path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />',
        'info' => '<path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />',
        'shield' => '<path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />',
        'megaphone' => '<path stroke-linecap="round" stroke-linejoin="round" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" />',
        'lightning' => '<path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" />',
        'calendar' => '<path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />',
        'star' => '<path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />',
    ];

    public function setTab($tab)
    {
        $this->activeTab = $tab;
        $this->resetValidation();
    }

    public function editType($id)
    {
        $type = AnnouncementType::findOrFail($id);
        $this->type_id = $type->id;
        $this->type_name = $type->name;
        $this->color_theme = $type->color_theme;

        // Find the icon key from the stored SVG
        $this->icon_key = array_search($type->icon_svg, $this->availableIcons) ?: 'info';
    }

    public function saveType()
    {
        $this->validate([
            'type_name' => 'required|string|max:255',
            'color_theme' => 'required|string',
            'icon_key' => 'required|string|in:' . implode(',', array_keys($this->availableIcons)),
        ]);

        AnnouncementType::updateOrCreate(
            ['id' => $this->type_id],
            [
                'name' => $this->type_name,
                'slug' => Str::slug($this->type_name),
                'color_theme' => $this->color_theme,
                'icon_svg' => $this->availableIcons[$this->icon_key],
            ]
        );

        $this->reset(['type_id', 'type_name', 'color_theme', 'icon_key']);
        session()->flash('success', 'Announcement type saved successfully.');
    }

    public function deleteType($id)
    {
        // Prevent deletion if announcements are using this type
        $type = AnnouncementType::withCount('announcements')->findOrFail($id);
        if ($type->announcements_count > 0) {
            session()->flash('error', 'Cannot delete this type because it is being used by ' . $type->announcements_count . ' announcement(s).');
            return;
        }

        $type->delete();
        session()->flash('success', 'Announcement type deleted.');
    }

    public function editCategory($id)
    {
        $category = SpotlightCategory::findOrFail($id);
        $this->category_id = $category->id;
        $this->category_name = $category->name;
    }

    public function saveCategory()
    {
        $this->validate([
            'category_name' => 'required|string|max:255',
        ]);

        SpotlightCategory::updateOrCreate(
            ['id' => $this->category_id],
            [
                'name' => $this->category_name,
                'slug' => Str::slug($this->category_name),
            ]
        );

        $this->reset(['category_id', 'category_name']);
        session()->flash('success', 'Spotlight category saved successfully.');
    }

    public function deleteCategory($id)
    {
        $category = SpotlightCategory::withCount('spotlights')->findOrFail($id);
        if ($category->spotlights_count > 0) {
            session()->flash('error', 'Cannot delete this category because it is being used by ' . $category->spotlights_count . ' spotlight(s).');
            return;
        }

        $category->delete();
        session()->flash('success', 'Spotlight category deleted.');
    }

    public function cancelEdit()
    {
        $this->reset(['type_id', 'type_name', 'color_theme', 'icon_key', 'category_id', 'category_name']);
        $this->resetValidation();
    }

    public function render()
    {
        return view('livewire.admin.content.content-references', [
            'types' => AnnouncementType::withCount('announcements')->get(),
            'categories' => SpotlightCategory::withCount('spotlights')->get(),
        ]);
    }
}
