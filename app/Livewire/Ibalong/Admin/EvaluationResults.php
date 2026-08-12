<?php
namespace App\Livewire\Ibalong\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Evaluation;
use App\Models\EvaluationResponse;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use App\Mail\CertificateMail;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class EvaluationResults extends Component
{
    use WithPagination;

    public Evaluation $evaluation;
    public $stats = [];

    // Tab & Individual Response Tracking
    public $tab = 'summary';
    public $currentIndex = 0;

    // Synthesis & AI Reports
    public $synthesisReport = null;
    public $aiReport = null;

    // Manual Issue Modal State
    public $issueModalOpen = false;
    public $issueResponseId = null;
    public $issueName = '';
    public $issueEmail = '';
    public $issueSubject = '';
    public $issueBody = '';

    public function mount(Evaluation $evaluation)
    {
        $this->evaluation = $evaluation;
        $user = auth()->user();

        // 1. Check if user is collaborator
        $isCollaborator = false;
        if ($user && $this->evaluation->exists) {
            $isCollaborator = $this->evaluation->collaborators()->where('user_id', $user->id)->exists();
        }

        // 2. Public Access Bypass Logic
        $isPublic = $this->evaluation->is_public_results ?? false;
        $isAdminOrCreator = $user && ($user->role?->role_name === 'administrator' || $this->evaluation->created_by === $user->id);

        if ($this->evaluation->exists && !$isPublic && !$isAdminOrCreator && !$isCollaborator) {
            abort(403, 'SYSTEM REJECT: You do not have permission to access this evaluation.');
        }

        $this->calculateStats();
    }

    public function togglePublicAccess()
    {
        $user = auth()->user();

        // Only Admins or Creators can toggle public access
        if (!$user || ($user->role?->role_name !== 'administrator' && $this->evaluation->created_by !== $user->id)) {
            abort(403, 'Unauthorized to modify broadcast settings.');
        }

        $this->evaluation->update([
            'is_public_results' => !$this->evaluation->is_public_results
        ]);

        session()->flash('success', 'Public broadcast status successfully updated.');
    }

    public function setTab($tabName)
    {
        $this->tab = $tabName;
        $this->currentIndex = 0;
        $this->resetPage();
    }

    // ... [KEEP ALL OTHER EXISTING METHODS: nextResponse, exportToCsv, calculateStats, generateSynthesis, generateAIInsights, openIssueModal, etc.] ...

    public function render()
    {
        $totalResponsesCount = $this->evaluation->responses()->count();
        $currentResponse = null;
        $allResponses = null;

        if ($this->tab === 'individual' && $totalResponsesCount > 0) {
            $currentResponse = EvaluationResponse::with(['answers', 'user'])
                ->where('evaluation_id', $this->evaluation->id)
                ->orderBy('created_at')
                ->skip($this->currentIndex)
                ->first();
        }
        elseif ($this->tab === 'table' && $totalResponsesCount > 0) {
            $allResponses = EvaluationResponse::with(['answers', 'user'])
                ->where('evaluation_id', $this->evaluation->id)
                ->orderBy('created_at', 'desc')
                ->paginate(15);
        }

        // 3. Dynamic Layout Fallback for Public Users
        $layoutFile = 'layouts.guest'; // Default for public access
        if (auth()->check()) {
            $layoutFile = in_array(auth()->user()->role?->role_name, ['administrator', 'organization'])
                ? 'layouts.madya-admin-deck'
                : 'layouts.madya-admin';
        }

        return view('livewire.admin.evaluation-results', [
            'totalResponsesCount' => $totalResponsesCount,
            'currentResponse' => $currentResponse,
            'allResponses' => $allResponses,
        ])->layout($layoutFile);
    }
}
