<?php

namespace App\Livewire\Ibalong\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\IbalongEvaluation;
use App\Models\IbalongEvaluationResponse;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use App\Mail\CertificateMail;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class EvaluationResults extends Component
{
    use WithPagination;

    public IbalongEvaluation $evaluation;
    public $stats = [];

    public $tab = 'summary';
    public $currentIndex = 0;

    public $synthesisReport = null;
    public $aiReport = null;

    public $issueModalOpen = false;
    public $issueResponseId = null;
    public $issueName = '';
    public $issueEmail = '';
    public $issueSubject = '';
    public $issueBody = '';

    public function mount($evaluation = null)
    {
        // 1. BULLETPROOF MODEL RETRIEVAL
        // Automatically hunts down the correct evaluation regardless of route parameter mismatches
        if ($evaluation instanceof IbalongEvaluation && $evaluation->exists) {
            $this->evaluation = $evaluation;
        } elseif (is_string($evaluation)) {
            $this->evaluation = IbalongEvaluation::where('slug', $evaluation)->firstOrFail();
        } elseif (is_object($evaluation) && isset($evaluation->slug)) {
            $this->evaluation = IbalongEvaluation::where('slug', $evaluation->slug)->firstOrFail();
        } else {
            // Absolute Fallback: Grab the slug directly from the URL (Segment 3)
            $slug = request()->segment(3);
            $this->evaluation = IbalongEvaluation::where('slug', $slug)->firstOrFail();
        }

        // 2. AUTHORIZATION & ACCESS LOGIC
        $user = auth()->user() ?? auth('ibalong')->user();

        $isCollaborator = false;
        if ($user && method_exists($this->evaluation, 'collaborators')) {
            $isCollaborator = $this->evaluation->collaborators()->where('user_id', $user->id)->exists();
        }

        $isPublic = $this->evaluation->is_public_results ?? false;

        $isAdminOrCreator = false;
        if ($user) {
            $isAdmin = isset($user->role_id) ? in_array($user->role_id, [1, 2]) : ($user->role?->role_name === 'administrator');
            $isAdminOrCreator = $isAdmin || $this->evaluation->created_by === $user->id;
        }

        if (!$isPublic && !$isAdminOrCreator && !$isCollaborator) {
            abort(403, 'SYSTEM REJECT: You do not have permission to access this evaluation.');
        }

        // 3. TRIGGER DATA CALCULATIONS
        $this->calculateStats();
    }

    public function togglePublicAccess()
    {
        $user = auth()->user() ?? auth('ibalong')->user();
        $isAdmin = isset($user->role_id) ? in_array($user->role_id, [1, 2]) : ($user->role?->role_name === 'administrator');

        if (!$user || (!$isAdmin && $this->evaluation->created_by !== $user->id)) {
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

    public function calculateStats()
    {
        $this->evaluation->load(['questions' => function ($query) {
            $query->orderBy('id');
        }, 'questions.answers']);

        foreach ($this->evaluation->questions as $question) {

            if (in_array($question->type, ['section', 'page_break'])) {
                continue;
            }

            $totalResponses = $question->answers->count();

            if ($totalResponses === 0) {
                $this->stats[$question->id] = [
                    'count' => 0, 'average' => 0, 'breakdown' => []
                ];
                continue;
            }

            $flatOptions = is_array($question->options) ? collect($question->options)->map(function($opt) {
                return is_array($opt) ? ($opt['text'] ?? '') : $opt;
            })->toArray() : [];

            if ($question->type === 'likert') {
                $sum = 0;
                $counts = array_fill(0, count($flatOptions), 0);

                foreach ($question->answers as $answer) {
                    $ansVal = $answer->answer_value ?? $answer->value;
                    $index = array_search($ansVal, $flatOptions);
                    if ($index !== false) {
                        $sum += ($index + 1);
                        $counts[$index]++;
                    }
                }

                $this->stats[$question->id] = [
                    'count' => $totalResponses,
                    'average' => round($sum / $totalResponses, 2),
                    'breakdown' => $counts
                ];
            }
            elseif (in_array($question->type, ['radio', 'dropdown'])) {
                $counts = array_fill_keys($flatOptions, 0);

                foreach ($question->answers as $answer) {
                    $val = $answer->answer_value ?? $answer->value;
                    if (isset($counts[$val])) {
                        $counts[$val]++;
                    }
                }

                $this->stats[$question->id] = [
                    'count' => $totalResponses,
                    'breakdown' => $counts
                ];
            }
            elseif ($question->type === 'checkbox') {
                $counts = array_fill_keys($flatOptions, 0);

                foreach ($question->answers as $answer) {
                    $ansVal = $answer->answer_value ?? $answer->value;
                    $selections = is_string($ansVal) ? json_decode($ansVal, true) : $ansVal;

                    if (is_array($selections)) {
                        foreach ($selections as $selected) {
                            if (array_key_exists($selected, $counts)) {
                                $counts[$selected]++;
                            }
                        }
                    }
                }

                $this->stats[$question->id] = [
                    'count' => $totalResponses,
                    'breakdown' => $counts
                ];
            }
            else {
                $this->stats[$question->id] = [
                    'count' => $totalResponses,
                ];
            }
        }
    }

    public function render()
    {
        // This will now correctly count the 35 responses because the model is loaded!
        $totalResponsesCount = $this->evaluation->responses()->count();
        $currentResponse = null;
        $allResponses = null;

        if ($this->tab === 'individual' && $totalResponsesCount > 0) {
            $currentResponse = IbalongEvaluationResponse::with(['answers', 'user'])
                ->where('evaluation_id', $this->evaluation->id)
                ->orderBy('created_at')
                ->skip($this->currentIndex)
                ->first();
        }
        elseif ($this->tab === 'table' && $totalResponsesCount > 0) {
            $allResponses = IbalongEvaluationResponse::with(['answers', 'user'])
                ->where('evaluation_id', $this->evaluation->id)
                ->orderBy('created_at', 'desc')
                ->paginate(15);
        }

        $layoutFile = 'layouts.guest';
        $user = auth()->user() ?? auth('ibalong')->user();

        if ($user) {
            $isAdmin = isset($user->role_id) ? in_array($user->role_id, [1, 2]) : ($user->role?->name === 'administrator');
            $layoutFile = $isAdmin ? 'layouts.dashboard' : 'layouts.guest';
        }

        return view('livewire.ibalong.admin.evaluation-results', [
            'totalResponsesCount' => $totalResponsesCount,
            'currentResponse' => $currentResponse,
            'allResponses' => $allResponses,
        ])->layout($layoutFile);
    }
}
