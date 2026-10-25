<?php

namespace App\Livewire\Open;

use Livewire\Component;
use App\Models\Evaluation;
use App\Models\EvaluationResponse;
use App\Models\EvaluationAnswer;
use Illuminate\Support\Facades\Auth;
use Livewire\WithFileUploads;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use App\Mail\CertificateMail;
use Livewire\Attributes\Layout;

#[Layout('layouts.madya-template')]
class EvaluationForm extends Component
{
    use WithFileUploads;

    public Evaluation $evaluation;
    public $project_id = null;
    public $answers = [];
    public $isSubmitted = false;
    public $connect_account = false;
    public $currentPage = 0;

    protected $queryString = ['project_id'];

    // --- AUTOSAVE LOGIC ---
    private function getDraftKey()
    {
        $identifier = Auth::check() ? 'user_' . Auth::id() : 'ip_' . request()->ip();
        return 'evaluation_draft_' . $this->evaluation->id . '_' . $identifier;
    }

    public function mount(Evaluation $evaluation)
    {
        $this->evaluation = $evaluation;

        if (request()->has('project_id')) {
            $this->project_id = request()->query('project_id');
        }

        foreach($evaluation->questions as $q) {
            $this->answers[$q->id] = ($q->type === 'checkbox') ? [] : '';
        }

        $draft = cache()->get($this->getDraftKey());
        if ($draft) {
            foreach ($draft as $qId => $val) {
                if (isset($this->answers[$qId])) {
                    $this->answers[$qId] = $val;
                }
            }
        }
    }

    public function updatedAnswers()
    {
        $cacheableAnswers = collect($this->answers)->filter(function ($value) {
            return !($value instanceof \Illuminate\Http\UploadedFile);
        })->toArray();

        cache()->put($this->getDraftKey(), $cacheableAnswers, now()->addDays(7));
        $this->dispatch('draft-autosaved');
    }

    private function getPages()
        {
            $pages = [];
            $pageIndex = 0;
            
            $sortedQuestions = $this->evaluation->questions->sortBy('order')->values()->all();
            $totalQuestions = count($sortedQuestions);
            
            $i = 0;
            
            while ($i < $totalQuestions) {
                $question = $sortedQuestions[$i];
                $jumpTargetId = null;
    
                if ($question->type === 'page_break') {
                    $pageIndex++;
                    $jumpTargetId = is_array($question->options) ? ($question->options[0]['jump'] ?? null) : null;
                } else {
                    $pages[$pageIndex][] = $question;
    
                    // Check for conditional jumps on user input (Radio / Dropdown)
                    if (isset($this->answers[$question->id]) && in_array($question->type, ['radio', 'dropdown'])) {
                        $selectedAnswer = $this->answers[$question->id];
                        if (is_array($question->options)) {
                            foreach ($question->options as $opt) {
                                $optText = is_array($opt) ? ($opt['text'] ?? '') : $opt;
                                if ($optText == $selectedAnswer) {
                                    $jumpTargetId = is_array($opt) ? ($opt['jump'] ?? null) : null;
                                    break;
                                }
                            }
                        }
                    }
                }
    
                // Execute the Jump Logic
                if (!empty($jumpTargetId)) {
                    if ($jumpTargetId === 'submit') {
                        // If target is submit, break the loop entirely. The remaining pages will be empty/ignored.
                        break;
                    } else {
                        // Find the exact index of the target section within the sorted array
                        $foundIndex = -1;
                        foreach ($sortedQuestions as $searchIndex => $q) {
                            // Support both old DB ids and new uuid temp_ids
                            if ($q->id == $jumpTargetId || $q->temp_id == $jumpTargetId) {
                                $foundIndex = $searchIndex;
                                break;
                            }
                        }
    
                        if ($foundIndex !== -1) {
                            // Set the loop index to the target. 
                            // Because the loop will increment $i at the end of the block, we subtract 1.
                            $i = $foundIndex - 1; 
                            
                            // We must also increment the page index when a major jump occurs so it renders properly as the 'next' step
                            $pageIndex++; 
                        }
                    }
                }
                
                $i++;
            }
    
            return array_values(array_filter($pages));
        }

    public function nextPage()
    {
        $pages = $this->getPages();
        $this->validateCurrentPage($pages[$this->currentPage] ?? []);

        if ($this->currentPage < count($pages) - 1) {
            $this->currentPage++;
            $this->dispatch('scroll-to-top');
        }
    }

    public function previousPage()
    {
        if ($this->currentPage > 0) {
            $this->currentPage--;
            $this->dispatch('scroll-to-top');
        }
    }

    private function validateCurrentPage($currentQuestions)
    {
        $rules = [];
        foreach ($currentQuestions as $q) {
            if ($q->is_required && $q->type !== 'section') {
                $rules["answers.{$q->id}"] = 'required';
            }
        }
        if (!empty($rules)) {
            $this->validate($rules, ['required' => 'This question is required.']);
        }
    }

    public function submit()
    {
        $pages = $this->getPages();
        $this->validateCurrentPage($pages[$this->currentPage] ?? []);

        $userId = ($this->connect_account && Auth::check()) ? Auth::id() : null;

        $response = EvaluationResponse::create([
            'evaluation_id' => $this->evaluation->id,
            'user_id' => $userId,
            'ip_address' => request()->ip(),
        ]);

        $visibleQuestionIds = collect($pages)->flatten()->pluck('id')->toArray();

        foreach ($this->answers as $questionId => $value) {
            if (!in_array($questionId, $visibleQuestionIds)) continue;
            if ($value === '' || $value === null || $value === []) continue;

            $finalValue = $value;

            if ($value instanceof \Illuminate\Http\UploadedFile) {
                $finalValue = $value->store('evaluation-uploads', 'public');
            } elseif (is_array($value)) {
                $finalValue = json_encode($value);
            }

            EvaluationAnswer::create([
                'evaluation_response_id' => $response->id,
                'evaluation_question_id' => $questionId,
                'answer_value' => $finalValue
            ]);
        }

        // 2. EXTRACT THE MAPPED DATA
        $nameAnswer = $response->answers()->where('evaluation_question_id', $this->evaluation->cert_name_question_id)->first();
        $respondentName = $nameAnswer && !empty($nameAnswer->answer_value)
            ? $nameAnswer->answer_value
            : (auth()->user()->name ?? 'Valued Participant');

        $emailAnswer = $response->answers()->where('evaluation_question_id', $this->evaluation->cert_email_question_id)->first();
        $respondentEmail = $emailAnswer && !empty($emailAnswer->answer_value)
            ? $emailAnswer->answer_value
            : (auth()->user()->email ?? null);

        // CLEAR DRAFT CACHE IMMEDIATELY UPON SUCCESSFUL SAVE
        cache()->forget($this->getDraftKey());
        $this->isSubmitted = true;

        // 3. E-CERTIFICATE GENERATION
        if ($this->evaluation->certificate_template && $this->evaluation->cert_delivery_mode === 'automatic') {
            $manager = new ImageManager(new Driver());
            $image = $manager->read(storage_path('app/public/' . $this->evaluation->certificate_template));

            $pixelX = $image->width() * ($this->evaluation->cert_pos_x / 100);
            $pixelY = $image->height() * ($this->evaluation->cert_pos_y / 100);

            // [FIXED] Use local $respondentName variable
            $image->text($respondentName, $pixelX, $pixelY, function($font) {
                $font->file(public_path('fonts/Montserrat-Bold.ttf'));
                $font->size($this->evaluation->cert_font_size);
                $font->color($this->evaluation->cert_text_color);
                $font->align('center');
                $font->valign('middle');
            });

            // Save temporary image for the email attachment
            $tempPath = storage_path('app/public/temp_cert_' . time() . '.png');
            $image->toPng()->save($tempPath);

            $eventName = $this->evaluation->title;
            $subject = $this->evaluation->cert_use_custom_email
                ? $this->evaluation->cert_email_subject
                : 'Your Certificate of Participation - BU MADYA';

            // [FIXED] Formulate the email body
            if ($this->evaluation->cert_use_custom_email) {
                $body = str_replace(['[Name]', '[Event]'], [$respondentName, $eventName], $this->evaluation->cert_email_body);
            } else {
                $body = "Hi {$respondentName},\n\nThank you for participating in {$eventName}. Please find your official certificate attached below.\n\nBest regards,\nBU MADYA";
            }

            // Send Email
            if ($respondentEmail) {
                Mail::to($respondentEmail)->send(new CertificateMail($subject, $body, $tempPath));
            }

            $response->update(['certificate_issued_at' => now()]);

            // Trigger Instant Download and clean up the file
            return response()->download($tempPath, 'Certificate-' . str_replace(' ', '-', $respondentName) . '.png')->deleteFileAfterSend(true);
        }

        $this->dispatch('scroll-to-top');
    }

    public function render()
    {
        $pages = $this->getPages();
        $totalPages = count($pages);
        $progress = $totalPages > 0 ? round((($this->currentPage + 1) / $totalPages) * 100) : 0;

        return view('livewire.open.evaluation-form', [
            'pages' => $pages,
            'totalPages' => $totalPages,
            'progress' => $progress
        ]);
    }
}
