<?php

namespace App\Livewire\Ibalong\Admin;

use Livewire\Component;
use App\Models\IbalongEvent;
use App\Models\IbalongEventRegistration;
use Illuminate\Support\Str;
use chillerlan\QRCode\QRCode;

class EventManager extends Component
{
    public $events;

    // Event Modal State
    public $isModalOpen = false;
    public $isEditMode = false;
    public $edit_id;

    // Form Fields
    public $title, $slug, $description, $type = 'Physical', $venue_or_link;
    public $start_datetime, $end_datetime, $max_capacity;

    // Registrants Modal State
    public $isRegistrantsModalOpen = false;
    public $selectedEvent = null;
    public $registrants = [];

    // QR Code Modal State
    public $isQrModalOpen = false;
    public $activeQrUri = '';
    public $activeRegistrantName = '';
    public $activeTicketCode = '';

    protected $rules = [
        'title' => 'required|string|max:255',
        'slug' => 'required|string|max:255|unique:ibalong_events,slug',
        'description' => 'nullable|string',
        'type' => 'required|in:Online,Physical,Hybrid',
        'venue_or_link' => 'nullable|string|max:255',
        'start_datetime' => 'required|date',
        'end_datetime' => 'required|date|after_or_equal:start_datetime',
        'max_capacity' => 'nullable|integer|min:1',
    ];

    public function updatedTitle($value)
    {
        if (!$this->isEditMode) {
            $this->slug = Str::slug($value);
        }
    }

    // --- EVENT MANAGEMENT ---

    public function openModal()
    {
        $this->resetFields();
        $this->isEditMode = false;
        $this->isModalOpen = true;
    }

    public function edit($id)
    {
        $this->resetFields();
        $this->isEditMode = true;

        $event = IbalongEvent::findOrFail($id);
        $this->edit_id = $event->id;
        $this->title = $event->title;
        $this->slug = $event->slug;
        $this->description = $event->description;
        $this->type = $event->type;
        $this->venue_or_link = $event->venue_or_link;
        $this->start_datetime = $event->start_datetime->format('Y-m-d\TH:i');
        $this->end_datetime = $event->end_datetime->format('Y-m-d\TH:i');
        $this->max_capacity = $event->max_capacity;

        $this->isModalOpen = true;
    }

    public function store()
    {
        $rules = $this->rules;
        if ($this->isEditMode) {
            $rules['slug'] = 'required|string|max:255|unique:ibalong_events,slug,' . $this->edit_id;
        }

        $this->validate($rules);

        IbalongEvent::updateOrCreate(
            ['id' => $this->edit_id],
            [
                'title' => $this->title,
                'slug' => $this->slug,
                'description' => $this->description,
                'type' => $this->type,
                'venue_or_link' => $this->venue_or_link,
                'start_datetime' => $this->start_datetime,
                'end_datetime' => $this->end_datetime,
                'max_capacity' => $this->max_capacity,
            ]
        );

        session()->flash('success', $this->isEditMode ? 'Event updated successfully.' : 'Event created successfully.');
        $this->closeModal();
    }

    public function toggleStatus($id)
    {
        $event = IbalongEvent::findOrFail($id);
        $event->update(['is_active' => !$event->is_active]);
        session()->flash('success', 'Event visibility toggled.');
    }

    public function delete($id)
    {
        IbalongEvent::findOrFail($id)->delete();
        session()->flash('success', 'Event deleted successfully.');
    }

    // --- REGISTRANTS & QR MANAGEMENT ---

    public function viewRegistrants($eventId)
    {
        $this->selectedEvent = IbalongEvent::findOrFail($eventId);

        // Eager load the 'team' relationship to map to ibalong_registrations
        $this->registrants = IbalongEventRegistration::with('team')
            ->where('event_id', $eventId)
            ->orderBy('created_at', 'desc')
            ->get();

        $this->isRegistrantsModalOpen = true;
    }

    public function showQr($ticketCode, $name)
    {
        $this->activeTicketCode = $ticketCode;
        $this->activeRegistrantName = $name;
        // Instantly generate the QR Code URI for the requested ticket
        $this->activeQrUri = (new QRCode)->render($ticketCode);
        $this->isQrModalOpen = true;
    }

    public function closeQrModal()
    {
        $this->isQrModalOpen = false;
        $this->activeQrUri = '';
    }

    public function closeRegistrantsModal()
    {
        $this->isRegistrantsModalOpen = false;
        $this->selectedEvent = null;
        $this->registrants = [];
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
        $this->resetFields();
    }

    private function resetFields()
    {
        $this->reset([
            'edit_id', 'title', 'slug', 'description', 'type',
            'venue_or_link', 'start_datetime', 'end_datetime', 'max_capacity'
        ]);
        $this->type = 'Physical';
    }

    public function render()
    {
        $this->events = IbalongEvent::withCount('registrations')->orderBy('start_datetime', 'asc')->get();
        return view('livewire.ibalong.admin.event-manager')->layout('layouts.dashboard');
    }
}
