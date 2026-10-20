<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Event;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;

#[Layout('layouts.madya-admin-deck')]
class EditEvent extends Component
{
    use WithFileUploads;

    public Event $event;

    public $title;
    public $description;
    public $cover_image;
    public $old_cover_image;
    public $is_internal_rsvp;
    public $registration_link;
    public $registration_button_text;
    public $location;
    public $capacity;
    public $allow_self_checkin;

    public $custom_questions = [];

    public $start_date;
    public $end_date;
    public $is_active;
    public $photo_upload;
    public $slug;
    public $checkin_start;
    public $checkin_end;

    protected $rules = [
        'title' => 'required|string|max:255',
        'registration_link' => 'nullable|url',
        'cover_image' => 'nullable|image|max:8192',
        'location' => 'nullable|string|max:255',
        'capacity' => 'nullable|integer|min:1',
        'start_date' => 'nullable|date',
        'end_date' => 'nullable|date|after_or_equal:start_date',
        'checkin_start' => 'nullable|date',
        'checkin_end' => 'nullable|date|after_or_equal:checkin_start',
        'custom_questions.*.question_text' => 'required|string|max:255',
        'custom_questions.*.type' => 'required|in:text,checkbox',
    ];

    public function mount($id)
    {
        $this->event = Event::with('questions')->findOrFail($id);

        $userRole = auth()->user()->role?->role_name;
        if (!in_array($userRole, ['administrator', 'director']) && $this->event->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action. You do not own this event.');
        }

        $this->title = $this->event->title;
        $this->slug = $this->event->slug;
        $this->description = $this->event->description;
        $this->old_cover_image = $this->event->cover_image;

        $this->is_internal_rsvp = (bool) $this->event->is_internal_rsvp;
        $this->registration_link = $this->event->registration_link;
        $this->registration_button_text = $this->event->registration_button_text;

        $this->location = $this->event->location;
        $this->capacity = $this->event->capacity;
        $this->allow_self_checkin = (bool) $this->event->allow_self_checkin;

        $this->start_date = $this->event->start_date ? $this->event->start_date->format('Y-m-d\TH:i') : null;
        $this->end_date = $this->event->end_date ? $this->event->end_date->format('Y-m-d\TH:i') : null;
        $this->is_active = (bool) $this->event->is_active;
        $this->checkin_start = $this->event->checkin_start ? $this->event->checkin_start->format('Y-m-d\TH:i') : null;
        $this->checkin_end = $this->event->checkin_end ? $this->event->checkin_end->format('Y-m-d\TH:i') : null;

        $this->custom_questions = $this->event->questions->map(function($q) {
            return [
                'id' => $q->id,
                'question_text' => $q->question_text,
                'type' => $q->type,
                'is_required' => (bool) $q->is_required
            ];
        })->toArray();
    }

    public function addQuestion()
    {
        $this->custom_questions[] = [
            'id' => null,
            'question_text' => '',
            'type' => 'text',
            'is_required' => true
        ];
    }

    public function removeQuestion($index)
    {
        if (isset($this->custom_questions[$index]['id'])) {
            \App\Models\EventQuestion::find($this->custom_questions[$index]['id'])->delete();
        }
        unset($this->custom_questions[$index]);
        $this->custom_questions = array_values($this->custom_questions);
    }

    public function updatedPhotoUpload()
    {
        $this->validate(['photo_upload' => 'image|max:8192']);
        $this->dispatch('photo-inserted', url: $this->photo_upload->temporaryUrl());
    }

    public function update()
    {
        // 1. Sanitize empty strings to strictly null so nullable validation passes
        $this->capacity = $this->capacity === '' ? null : $this->capacity;
        $this->checkin_start = $this->checkin_start === '' ? null : $this->checkin_start;
        $this->checkin_end = $this->checkin_end === '' ? null : $this->checkin_end;
        $this->location = $this->location === '' ? null : $this->location;
        $this->registration_link = $this->registration_link === '' ? null : $this->registration_link;
        $this->start_date = $this->start_date === '' ? null : $this->start_date;
        $this->end_date = $this->end_date === '' ? null : $this->end_date;

        // 2. Execute Validation
        $this->validate();

        $imagePath = $this->old_cover_image;

        if ($this->cover_image) {
            if ($this->old_cover_image) {
                Storage::disk('public')->delete($this->old_cover_image);
            }
            $imagePath = $this->cover_image->store('events', 'public');
        }

        $this->event->update([
            'title' => $this->title,
            'slug' => Str::slug($this->slug),
            'description' => $this->description,
            'cover_image' => $imagePath,
            'is_internal_rsvp' => $this->is_internal_rsvp,
            'registration_link' => $this->is_internal_rsvp ? null : $this->registration_link,
            'registration_button_text' => $this->registration_button_text,
            'location' => $this->location,
            'capacity' => $this->capacity,
            'allow_self_checkin' => $this->allow_self_checkin,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'is_active' => $this->is_active,
            'checkin_start' => $this->checkin_start,
            'checkin_end' => $this->checkin_end,

            'custom_questions.*.id' => 'nullable',
                    'custom_questions.*.question_text' => 'required|string|max:255',
                    'custom_questions.*.type' => 'required|in:text,checkbox',
                    'custom_questions.*.is_required' => 'boolean',
        ]);

        if ($this->is_internal_rsvp) {
            foreach ($this->custom_questions as $qData) {
                $this->event->questions()->updateOrCreate(
                    ['id' => $qData['id'] ?? null],
                    [
                        'question_text' => $qData['question_text'],
                        'type' => $qData['type'],
                        'is_required' => $qData['is_required']
                    ]
                );
            }
        }

        if (auth()->user()->role?->role_name === 'organization') {
            return redirect()->route('partner.events.index')->with('message', 'Event updated successfully!');
        }

        return redirect()->route('admin.events.index')->with('message', 'Event updated successfully!');
    }

    public function render()
    {
        return view('livewire.admin.edit-event');
    }
}
