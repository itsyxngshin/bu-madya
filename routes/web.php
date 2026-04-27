<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Open\LandingPage;
use App\Livewire\About;
use App\Livewire\EventsCalendar;
use App\Livewire\RegistrationForm;
use App\Livewire\Open\Directory;
use App\Livewire\Open\Committees;
use App\Livewire\Open\CommitteeMembers;
use App\Livewire\Open\RoundtableIndex;
use App\Livewire\Open\RoundtableShow;
use App\Livewire\Open\News\Index as NewsIndex;
use App\Livewire\Open\News\Show as NewsShow;
use App\Livewire\Open\ThePillars;
use App\Livewire\Open\EventsIndex;
use App\Livewire\Open\EventShow;
use App\Livewire\Admin\EvaluationBuilder;
use App\Livewire\Open\EvaluationList;
use App\Livewire\Open\EvaluationForm;
use App\Livewire\Open\EventRsvp;
use App\Livewire\Open\FrameBuilder;
use App\Livewire\Open\EventDiscovery;
use App\Livewire\Open\PrivacyPolicy;
use App\Livewire\Open\CandidateProfile;

use App\Livewire\Auth\RegisterOrganization;

use App\Livewire\Director\NewsCreate;
use App\Livewire\Director\NewsEdit;
use App\Livewire\Director\ProjectsIndex;
use App\Livewire\Director\ProjectsShow;
use App\Livewire\Director\ProjectsCreate;
use App\Livewire\Director\ProjectsEdit;
use App\Livewire\Director\LinkagesIndex;
use App\Livewire\Director\LinkagesShow;
use App\Livewire\Director\LinkagesCreate;
use App\Livewire\Director\LinkagesEdit;
use App\Livewire\Director\LinkagesProposal;
use App\Livewire\Director\UserProfile;
use App\Livewire\Director\EditProfile;
use App\Livewire\Director\Dashboard;
use App\Livewire\Director\ThePillarsManager;
use App\Livewire\Open\ProposalsCreate;

use App\Livewire\Admin\ProposalsShow;
use App\Livewire\Admin\ProposalsIndex;
use App\Livewire\Admin\LinkagesRoster;
use App\Livewire\Admin\UserRoster;
use App\Livewire\Admin\ProjectRoster;
use App\Livewire\Admin\NewsRoster;
use App\Livewire\Admin\AdminDashboard;
use App\Livewire\Admin\Settings;
use App\Livewire\Admin\MembershipSetting;
use App\Livewire\Admin\MembershipRequests;
use App\Livewire\Admin\EventIndex as AdminEventIndex;
use App\Livewire\Admin\CreateEvent;
use App\Livewire\Admin\EditEvent;
use App\Livewire\Admin\Transparency\DocumentForm;
use App\Livewire\Admin\Transparency\DocumentIndex;
use App\Livewire\Open\TransparencyIndex;
use App\Livewire\Open\CampaignView;
use App\Livewire\Admin\EvaluationResults;
use App\Livewire\Admin\EvaluationList as AdminEvaluationIndex;
use App\Livewire\Admin\EventScanner;
use App\Livewire\Admin\EventRegistrants;
use App\Livewire\Admin\FrameManager;
use App\Livewire\Admin\EventRaffle;
use App\Livewire\Admin\LinkagesManager;
use App\Livewire\Admin\CampaignList;
use App\Livewire\Admin\CampaignBuilder;
use App\Livewire\Admin\CampaignAnalytics;
use App\Livewire\Admin\ElectionVoterLogs;


use App\Models\MembershipApplication;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;

use \App\Livewire\Partner\SubmitFrame;
use \App\Livewire\Partner\PartnerDashboard;

// ==========================================
// WELFARE & GRIEVANCE SUBDOMAIN
// ==========================================
$welfareDomain = 'straw.' . parse_url(config('app.url'), PHP_URL_HOST);

Route::domain($welfareDomain)->middleware(['web', 'throttle:5,1'])->group(function () {

    // The Submit Form
    Route::get('/submit', \App\Livewire\Welfare\SubmitTicket::class)->name('welfare.submit');

    // The Secure Tracker
    Route::get('/track', \App\Livewire\Welfare\TrackTicket::class)->name('welfare.track');

});

Route::get('/', function () {
    return view('welcome');
});

Route::get('/secure-file/{application}', function (\App\Models\MembershipApplication $application) {

    // 1. Security Check: Only allow if user is logged in & authorized
    if (!auth()->check() || !in_array(auth()->user()->role->role_name, ['administrator', 'director'])) {
        abort(403);
    }

    // 2. Fetch the path
    $path = $application->signature_path;

    // 3. Check if file exists in the PRIVATE storage
    if (!Storage::disk('local')->exists($path)) {
        abort(404);
    }

    // 4. Return the file securely
    $file = Storage::disk('local')->path($path);
    return response()->file($file);

})->name('secure.signature');

/*
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});
*/

Route::get('/events/{event:slug}/scan', EventScanner::class)->name('admin.events.scan');

// Middleware accessible to both members and directors
Route::middleware(['auth', 'role:director'])->prefix('director')->name('director.')
    ->group(function () {
    Route::get('/dashboard', Dashboard::class)->name('dashboard');
    Route::get('/project/create', ProjectsCreate::class)->name('projects.create');
    Route::get('/projects/{project:slug}/edit', ProjectsEdit::class)->name('projects.edit');
    Route::get('/profile/edit', EditProfile::class)->name('profile.edit');
    Route::get('/news/create', NewsCreate::class)->name('news.create');
    Route::get('/linkage/create', LinkagesCreate::class)->name('linkages.create');
    Route::get('/director/the-pillars', ThePillarsManager::class)->name('pillars.index');
    Route::get('/proposals/{proposal}', ProposalsShow::class)->name('admin.proposals.show');
    Route::get('/proposals', ProposalsIndex::class)->name('proposals.index');
    Route::get('/news/{slug}/edit', NewsEdit::class)->name('news.edit');
    Route::get('/linkage/{linkage:slug}/edit', LinkagesEdit::class)->name('linkages.edit');
    Route::get('/projects', ProjectRoster::class)->name('projects.index');
    Route::get('/linkages', LinkagesRoster::class)->name('linkages.index');
    Route::get('/news', NewsRoster::class)->name('news.index');
    Route::get('/user', UserRoster::class)->name('user.index');
    Route::get('/evaluations/{evaluation}/edit', EvaluationBuilder::class)->name('evaluations.edit');
    Route::get('/evaluations/{evaluation}/results', EvaluationResults::class)->name('evaluations.results');
    Route::get('/evaluations', AdminEvaluationIndex::class)->name('evaluations.index');
    Route::get('/evaluations/create', EvaluationBuilder::class)->name('evaluations.create');
    Route::get('/campaigns', CampaignList::class)->name('campaigns.index');
    Route::get('/campaigns/create', CampaignBuilder::class)->name('campaigns.create');
    Route::get('/campaigns/{slug}/edit', CampaignBuilder::class)->name('campaigns.edit'); 
    Route::get('/campaigns/{slug}/results', CampaignAnalytics::class)->name('campaigns.results');

});

Route::middleware(['auth'])
    ->group(function () {
    Route::get('/roundtable', RoundtableIndex::class)->name('roundtable.index');
    Route::get('/roundtable/{id}', RoundtableShow::class)->name('roundtable.show');
    Route::get('/evaluations', EvaluationList::class)->name('evaluations.index');
    Route::get('/elections/{election:slug}/apply', \App\Livewire\Open\CandidateApplicationForm::class)->name('elections.apply');
    Route::get('/elections/{election:slug}/results', \App\Livewire\Open\PublicElectionResults::class)->name('elections.public-results');

});

Route::get('/elections/{election:slug}/vote', \App\Livewire\Open\VotingBooth::class)->name('elections.vote');
Route::get('/candidate/{candidate}', CandidateProfile::class)->name('candidate.profile');



Route::middleware(['auth', 'role:administrator'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/projects', ProjectRoster::class)->name('projects.index');
    Route::get('/linkages', LinkagesRoster::class)->name('linkages.index');
    Route::get('/news', NewsRoster::class)->name('news.index');
    Route::get('/user', UserRoster::class)->name('user.index');
    Route::get('/settings', Settings::class)->name('settings');
    Route::get('/profile/edit', EditProfile::class)->name('profile.edit');
    Route::get('/campaigns', CampaignList::class)->name('campaigns.index');
    Route::get('/campaigns/create', CampaignBuilder::class)->name('campaigns.create');
    Route::get('/campaigns/{slug}/edit', CampaignBuilder::class)->name('campaigns.edit');
    Route::get('/membership/settings', MembershipSetting::class)->name('membership-settings');
    Route::get('/membership/requests', MembershipRequests::class)->name('membership-requests');
    Route::get('/dashboard', AdminDashboard::class)->name('dashboard');
    Route::get('/events', AdminEventIndex::class)->name('events.index');
    Route::get('/events/create', CreateEvent::class)->name('events.create');
    Route::get('/events/{id}/edit', EditEvent::class)->name('events.edit');
    Route::get('/transparency', DocumentIndex::class)->name('transparency.index');
    Route::get('/transparency/create', DocumentForm::class)->name('transparency.create');
    Route::get('/transparency/{document}/edit', DocumentForm::class)->name('transparency.edit');
    Route::get('/evaluations/create', EvaluationBuilder::class)->name('evaluations.create');
    Route::get('/frames', FrameManager::class)->name('frames.index');
    Route::get('/events/{event:slug}/raffle', EventRaffle::class)->name('events.raffle');
    Route::get('/submit-frame', SubmitFrame::class)->name('frames.submit');
    Route::get('/linkages-proposals', LinkagesManager::class)->name('linkages.proposals');
    Route::get('/welfare', \App\Livewire\Admin\WelfareManager::class)->name('welfare.index');
    Route::get('/the-pillars', ThePillarsManager::class)->name('pillars.index');
    Route::get('/campaigns/{slug}/results', CampaignAnalytics::class)->name('campaigns.results'); 
    Route::get('/elections', \App\Livewire\Admin\ElectionList::class)->name('elections.index');
    Route::get('/elections/manage', \App\Livewire\Admin\ElectionManager::class)->name('elections.manage');
    Route::get('/elections/{election:slug}/vetting', \App\Livewire\Admin\ElectionDashboard::class)->name('elections.vetting');
    Route::get('/elections/{election:slug}/results', \App\Livewire\Admin\ElectionResults::class)->name('elections.results');
    Route::get('/elections/{election:slug}/edit', \App\Livewire\Admin\ElectionEditor::class)->name('elections.edit');
    Route::get('/elections/{election:slug}/logs', ElectionVoterLogs::class)->name('elections.logs');
});

Route::middleware(['auth', 'role:organization'])->prefix('partner')->name('partner.')
    ->group(function () {
    Route::get('/dashboard', PartnerDashboard::class)->name('dashboard');
    Route::get('/submit-frame', SubmitFrame::class)->name('frames.submit');
    Route::get('/event/{event:slug}/registrants', EventRegistrants::class)->name('events.registrants');
    Route::get('/events', AdminEventIndex::class)->name('events.index');
    Route::get('/event/create', CreateEvent::class)->name('events.create');
    Route::get('/events/{id}/edit', EditEvent::class)->name('events.edit');
    Route::get('/events/{event:slug}/raffle', EventRaffle::class)->name('events.raffle');
    Route::get('/profile/edit', EditProfile::class)->name('profile.edit');
    Route::get('/events/{event:slug}/registrants', EventRegistrants::class)->name('events.registrants');
    Route::get('/events/{event:slug}/raffle', EventRaffle::class)->name('events.raffle');
    Route::get('/welfare', \App\Livewire\Admin\WelfareManager::class)->name('welfare.index');
    Route::get('/evaluations/create', EvaluationBuilder::class)->name('evaluations.create');
    Route::get('/manage/evaluations/{evaluation}/edit', EvaluationBuilder::class)->name('evaluations.edit');
    Route::get('/manage/evaluations/{evaluation}/results', EvaluationResults::class)->name('evaluations.results');
    Route::get('/manage/evaluations', AdminEvaluationIndex::class)->name('evaluations.index');
    Route::get('/campaigns', CampaignList::class)->name('campaigns.index');
    Route::get('/campaigns/create', CampaignBuilder::class)->name('campaigns.create');
    Route::get('/campaigns/{slug}/edit', CampaignBuilder::class)->name('campaigns.edit');
    Route::get('/campaigns/{slug}/results', CampaignAnalytics::class)->name('campaigns.results');
    Route::get('/elections', \App\Livewire\Admin\ElectionList::class)->name('elections.index');
    Route::get('/elections/manage', \App\Livewire\Admin\ElectionManager::class)->name('elections.manage');
    Route::get('/elections/{election:slug}/vetting', \App\Livewire\Admin\ElectionDashboard::class)->name('elections.vetting');
    Route::get('/elections/{election:slug}/results', \App\Livewire\Admin\ElectionResults::class)->name('elections.results');
    Route::get('/elections/{election:slug}/edit', \App\Livewire\Admin\ElectionEditor::class)->name('elections.edit');
    
});

Route::middleware(['auth', 'role:member'])->prefix('member')->name('member.')
    ->group(function () {
    Route::get('/profile/edit', EditProfile::class)->name('profile.edit');

});

Route::middleware(['auth', 'role:administrator,director'])
    ->group(function () {
    Route::get('/dashboard', Dashboard::class)->name('dashboard');
    Route::get('/project/create', ProjectsCreate::class)->name('projects.create');
    Route::get('/projects/{project:slug}/edit', ProjectsEdit::class)->name('projects.edit');
    Route::get('/profile/edit', EditProfile::class)->name('profile.edit');
    Route::get('/news/create', NewsCreate::class)->name('news.create');
    Route::get('/linkage/create', LinkagesCreate::class)->name('linkages.create');
    Route::get('/director/the-pillars', ThePillarsManager::class)->name('director.pillars.index');
    Route::get('/proposals/{proposal}', ProposalsShow::class)->name('admin.proposals.show');
    Route::get('/proposals', ProposalsIndex::class)->name('admin.proposals.index');
    Route::get('/news/{slug}/edit', NewsEdit::class)->name('news.edit');
    Route::get('/linkage/{linkage:slug}/edit', LinkagesEdit::class)->name('linkages.edit');
    Route::get('/manage/evaluations/{evaluation}/edit', EvaluationBuilder::class)->name('admin.evaluations.edit');
    Route::get('/manage/evaluations/{evaluation}/results', EvaluationResults::class)->name('admin.evaluations.results');
    Route::get('/manage/evaluations', AdminEvaluationIndex::class)->name('admin.evaluations.index');
    Route::get('/events/{event:slug}/registrants', EventRegistrants::class)->name('admin.events.registrants');
    Route::get('/director/submit-frame', SubmitFrame::class)->name('frames.submit');

});

// Public view blades with access control on parts of the navigation
Route::get('/', LandingPage::class)->name('open.home');
Route::get('/about', About::class)->name('about');
Route::get('/directory', Directory::class)->name('open.directory');
Route::get('/committees', Committees::class)->name('open.committees');
Route::get('/committees/{committee:slug}', CommitteeMembers::class)->name('open.committees.show');
Route::get('/news', NewsIndex::class)->name('news.index');
Route::get('/news/{slug}', NewsShow::class)->name('news.show');
Route::get('/projects', ProjectsIndex::class)->name('projects.index');
Route::get('/projects/{project:slug}', ProjectsShow::class)->name('projects.show');
Route::get('/linkages', LinkagesIndex::class)->name('linkages.index');
Route::get('/linkages/{linkage:slug}', LinkagesShow::class)->name('linkages.show');
Route::get('/partner-with-us', LinkagesProposal::class)->name('linkages.proposal');
Route::get('/profile/{username}', UserProfile::class)->name('profile.public');
Route::get('/submit-proposal', ProposalsCreate::class)->name('proposals.create');
Route::get('/the-pillars', ThePillars::class)->name('pillars.index');
Route::get('/calendar', EventsCalendar::class)->name('event-calendar');
Route::get('/membership-form', RegistrationForm::class)->name('membership-form');
Route::get('/events', EventsIndex::class)->name('events.index');
Route::get('/events/{slug}', EventShow::class)->name('events.show');
Route::get('/transparency', TransparencyIndex::class)->name('transparency.index');
Route::get('/events/{event:slug}/register', EventRsvp::class)->name('events.rsvp');
Route::get('/frames/{slug}', FrameBuilder::class)->name('open.frames.show');
Route::get('/partners/register', RegisterOrganization::class)->name('register.partner');
Route::get('/discover', EventDiscovery::class)->name('open.events.index');
Route::get('/privacy', PrivacyPolicy::class)->name('privacy');
Route::get('/campaigns/{slug}', CampaignView::class)->name('campaigns.show');
Route::get('/evaluations/{evaluation}', EvaluationForm::class)->name('evaluations.show');




