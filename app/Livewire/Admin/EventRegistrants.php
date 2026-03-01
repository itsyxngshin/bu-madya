<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Event;
use App\Models\EventRegistration;
use Livewire\Attributes\Layout;

#[Layout('layouts.madya-admin-deck')]
class EventRegistrants extends Component
{
    use WithPagination;

    public Event $event;
    public $search = '';
    public $statusFilter = '';

    public function mount(Event $event)
    {
        $this->event = $event;
    }

    public function updatingSearch() { $this->resetPage(); }
    public function updatingStatusFilter() { $this->resetPage(); }

    public function export()
    {
        // 1. Get the filtered query (ignoring pagination so we get ALL results)
        $query = $this->event->registrations()->latest();

        if ($this->search) {
            $query->where(function($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('email', 'like', '%' . $this->search . '%')
                  ->orWhere('ticket_code', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->statusFilter) {
            $query->where('status', $this->statusFilter);
        }

        $registrants = $query->get();

        // 2. Define the filename dynamically
        $csvFileName = 'BU-MADYA-' . $this->event->slug . '-registrants-' . date('Y-m-d') . '.csv';

        // 3. Stream the download back to the browser
        return response()->streamDownload(function() use ($registrants) {
            $handle = fopen('php://output', 'w');

            // Add the Header Row
            fputcsv($handle, [
                'Date Registered',
                'Status',
                'Name',
                'Email',
                'Classification',
                'BU College ID',
                'Program',
                'Year Level',
                'Organization Name',
                'Position',
                'Ticket Code'
            ]);

            // Add the Data Rows
            foreach ($registrants as $reg) {
                fputcsv($handle, [
                    $reg->created_at->format('Y-m-d H:i A'),
                    $reg->status,
                    $reg->name,
                    $reg->email,
                    $reg->classification,
                    $reg->college_id ?? 'N/A',
                    $reg->program ?? 'N/A',
                    $reg->year_level ?? 'N/A',
                    $reg->organization_name ?? 'N/A',
                    $reg->position ?? 'N/A',
                    $reg->ticket_code
                ]);
            }

            fclose($handle);
        }, $csvFileName);
    }

    public function render()
    {
        $query = $this->event->registrations()->latest();

        if ($this->search) {
            $query->where(function($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('email', 'like', '%' . $this->search . '%')
                  ->orWhere('ticket_code', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->statusFilter) {
            $query->where('status', $this->statusFilter);
        }

        return view('livewire.admin.event-registrants', [
            'registrants' => $query->paginate(20),
            'stats' => [
                'total' => $this->event->registrations()->count(),
                'attended' => $this->event->registrations()->where('status', 'Attended')->count(),
            ]
        ]);
    }
}