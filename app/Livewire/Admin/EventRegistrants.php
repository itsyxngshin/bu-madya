<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Event;
use App\Models\EventRegistration;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;

class EventRegistrants extends Component
{
    use WithPagination;

    public Event $event;
    public $search = '';
    public $statusFilter = '';

    // --- Modal State ---
    public $viewingRegistrant = null;
    public $showModal = false;

    public function mount(Event $event)
    {
        $this->event = $event;
    }

    public function updatingSearch() { $this->resetPage(); }
    public function updatingStatusFilter() { $this->resetPage(); }

    // --- Modal Protocols ---
    public function viewRegistrant($id)
    {
        // Eager load the college and the dynamic answers
        $this->viewingRegistrant = EventRegistration::with(['college', 'answers.question'])->findOrFail($id);
        $this->showModal = true;
    }

    public function updateStatus($id, $status)
    {
        $reg = EventRegistration::findOrFail($id);
        $reg->update(['status' => $status]);

        $this->showModal = false;
        $this->viewingRegistrant = null;

        session()->flash('message', 'Registrant status successfully updated.');
    }

    // ==========================================
    // EXPORT 1: Bulk Event Roster to Excel (No Questions)
    // ==========================================
    public function exportRosterXls()
    {
        $query = $this->event->registrations()->with('college')->latest();

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
        $filename = "BUMADYA-" . $this->event->slug . "-Roster-" . date('Y-m-d') . ".xls";

        return response()->streamDownload(function () use ($registrants) {
            echo '<html xmlns:x="urn:schemas-microsoft-com:office:excel">';
            echo '<head><meta charset="utf-8"></head><body>';
            echo '<table border="1" style="font-family: Arial, sans-serif;">';

            // Header Row
            echo '<tr style="background-color: #0f172a; color: #ffffff; font-weight: bold;">';
            echo '<th>Date Registered</th>';
            echo '<th>Ticket Code</th>';
            echo '<th>Status</th>';
            echo '<th>Full Name</th>';
            echo '<th>Email Address</th>';
            echo '<th>Contact Number</th>';
            echo '<th>Classification</th>';
            echo '<th>School / University</th>';
            echo '<th>College / Unit</th>';
            echo '<th>Program</th>';
            echo '<th>Year Level</th>';
            echo '<th>Organization Name</th>';
            echo '<th>Position</th>';
            echo '</tr>';

            // Data Rows
            foreach ($registrants as $reg) {
                echo '<tr>';
                echo '<td>' . $reg->created_at->format('Y-m-d H:i A') . '</td>';
                echo '<td>' . htmlspecialchars($reg->ticket_code) . '</td>';
                echo '<td>' . strtoupper($reg->status ?? 'Registered') . '</td>';
                echo '<td>' . htmlspecialchars($reg->name) . '</td>';
                echo '<td>' . htmlspecialchars($reg->email) . '</td>';
                echo '<td style="mso-number-format:\'@\';">' . htmlspecialchars($reg->contact_number ?? 'N/A') . '</td>';
                echo '<td>' . htmlspecialchars($reg->classification ?? 'N/A') . '</td>';
                echo '<td>' . htmlspecialchars($reg->school ?? 'N/A') . '</td>';
                echo '<td>' . htmlspecialchars($reg->college ? $reg->college->name : 'N/A') . '</td>';
                echo '<td>' . htmlspecialchars($reg->program ?? 'N/A') . '</td>';
                echo '<td>' . htmlspecialchars($reg->year_level ?? 'N/A') . '</td>';
                echo '<td>' . htmlspecialchars($reg->organization_name ?? 'N/A') . '</td>';
                echo '<td>' . htmlspecialchars($reg->position ?? 'N/A') . '</td>';
                echo '</tr>';
            }

            echo '</table></body></html>';
        }, $filename, [
            'Content-Type' => 'application/vnd.ms-excel',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    // ==========================================
    // EXPORT 2: Individual Profile to Word (With Dynamic Answers)
    // ==========================================
    public function exportProfileDoc($id)
    {
        $reg = EventRegistration::with(['college', 'answers.question'])->findOrFail($id);
        $filename = "Attendee-Profile-" . Str::slug($reg->name) . ".doc";

        return response()->streamDownload(function () use ($reg) {
            echo "<html xmlns:w='urn:schemas-microsoft-com:office:word'>";
            echo "<head><meta charset='utf-8'></head>";
            echo "<body style='font-family: Helvetica, Arial, sans-serif; line-height: 1.6;'>";

            // Header
            echo "<h1 style='text-align:center; color: #0f172a; margin-bottom: 5px;'>" . htmlspecialchars($this->event->title) . "</h1>";
            echo "<h2 style='text-align:center; color: #dc2626; margin-top: 0;'>Attendee Profile</h2>";
            echo "<p style='text-align:center; color: #64748b; margin-top: -10px;'>Generated on " . date('F j, Y') . "</p>";
            echo "<hr style='border: 1px solid #e2e8f0; margin-bottom: 20px;'>";

            // Demographic Data Table
            echo "<h3 style='color: #0f172a; border-bottom: 2px solid #dc2626; padding-bottom: 5px;'>Registration Details</h3>";
            echo "<table style='width:100%; border-collapse: collapse; margin-bottom: 30px;' border='1' cellpadding='8'>";
            echo "<tr style='background-color: #f8fafc;'><td style='width:35%; font-weight:bold;'>Ticket Code</td><td style='font-family: monospace; font-weight: bold; letter-spacing: 2px;'>" . htmlspecialchars($reg->ticket_code) . "</td></tr>";
            echo "<tr><td style='font-weight:bold;'>Full Name</td><td>" . htmlspecialchars($reg->name) . "</td></tr>";
            echo "<tr style='background-color: #f8fafc;'><td style='font-weight:bold;'>Email Address</td><td>" . htmlspecialchars($reg->email) . "</td></tr>";
            echo "<tr><td style='font-weight:bold;'>Contact Number</td><td>" . htmlspecialchars($reg->contact_number ?? 'N/A') . "</td></tr>";
            echo "<tr style='background-color: #f8fafc;'><td style='font-weight:bold;'>Classification</td><td>" . htmlspecialchars($reg->classification ?? 'N/A') . "</td></tr>";
            echo "<tr><td style='font-weight:bold;'>School / University</td><td>" . htmlspecialchars($reg->school ?? 'N/A') . "</td></tr>";
            echo "<tr style='background-color: #f8fafc;'><td style='font-weight:bold;'>College / Unit</td><td>" . htmlspecialchars($reg->college ? $reg->college->name : 'N/A') . "</td></tr>";
            echo "<tr><td style='font-weight:bold;'>Program</td><td>" . htmlspecialchars($reg->program ?? 'N/A') . "</td></tr>";
            echo "<tr style='background-color: #f8fafc;'><td style='font-weight:bold;'>Year Level</td><td>" . htmlspecialchars($reg->year_level ?? 'N/A') . "</td></tr>";
            echo "<tr><td style='font-weight:bold;'>Organization Affiliation</td><td>" . htmlspecialchars($reg->organization_name ?? 'N/A') . "</td></tr>";
            echo "</table>";

            // Custom Questionnaire Answers
            if ($reg->answers && $reg->answers->count() > 0) {
                echo "<h3 style='color: #0f172a; border-bottom: 2px solid #dc2626; padding-bottom: 5px;'>Custom Questionnaire Responses</h3>";
                foreach ($reg->answers as $answerRecord) {
                    $questionText = $answerRecord->question ? $answerRecord->question->question_text : 'Unknown Question';
                    echo "<div style='margin-bottom: 20px; padding: 15px; background-color: #f8fafc; border: 1px solid #e2e8f0;'>";
                    echo "<p style='font-weight:bold; color: #0f172a; margin-top: 0; margin-bottom: 10px; font-size: 11pt;'>" . htmlspecialchars($questionText) . "</p>";

                    // Format boolean checkboxes cleanly
                    $ansVal = $answerRecord->answer;
                    if ($answerRecord->question && $answerRecord->question->type === 'checkbox') {
                        $ansVal = ($ansVal === 'true' || $ansVal === '1' || $ansVal === 'Yes' || $ansVal === 'on') ? 'Yes / Agreed' : 'No';
                    }

                    echo "<p style='margin-top: 0; margin-bottom: 0; color: #334155;'>" . nl2br(htmlspecialchars($ansVal ?: 'No response provided')) . "</p>";
                    echo "</div>";
                }
            } else {
                echo "<p style='color: #64748b; font-style: italic;'>No custom questionnaire responses recorded.</p>";
            }

            echo "</body></html>";
        }, $filename, [
            'Content-Type' => 'application/msword',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
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

        $layoutFile = in_array(auth()->user()->role?->role_name, ['administrator', 'organization'])
            ? 'layouts.madya-admin-deck'
            : 'layouts.madya-admin';

        return view('livewire.admin.event-registrants', [
            'registrants' => $query->paginate(20),
            'stats' => [
                'total' => $this->event->registrations()->count(),
                'attended' => $this->event->registrations()->where('status', 'Attended')->count(),
            ]
        ])->layout($layoutFile);
    }
}
