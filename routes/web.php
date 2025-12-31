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
use App\Models\MembershipApplication; 
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;


#use App\Livewire\Admin\AdminProposalsIndex;
// ADMIN ROUTE (Protect this group!)


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

// Middleware accessible to both members and directors
Route::middleware(['auth', 'role:director']) 
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
});

Route::middleware(['auth']) 
    ->group(function () {
    Route::get('/roundtable', RoundtableIndex::class)->name('roundtable.index');
    Route::get('/roundtable/{id}', RoundtableShow::class)->name('roundtable.show');
});



Route::middleware(['auth', 'role:administrator'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/projects', ProjectRoster::class)->name('projects.index');
    Route::get('/linkages', LinkagesRoster::class)->name('linkages.index');
    Route::get('/news', NewsRoster::class)->name('news.index');
    Route::get('/user', UserRoster::class)->name('user.index');
    Route::get('/settings', Settings::class)->name('settings');
    Route::get('/membership/settings', MembershipSetting::class)->name('membership-settings');
    Route::get('/membership/requests', MembershipRequests::class)->name('membership-requests'); 
    Route::get('/dashboard', AdminDashboard::class)->name('dashboard');
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
});

// Public view blades with access control on parts of the navigation
Route::get('/', LandingPage::class)->name('open.home');  
Route::get('/about', About::class)->name('about'); 
Route::get('/directory', Directory::class)->name('open.directory');  
Route::get('/committees', Committees::class)->name('open.committees'); 
Route::get('/committees/{slug}', CommitteeMembers::class)->name('open.committees.show');
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
// Ensure the RegistrationForm class exists in the correct namespace
Route::get('/membership-form', RegistrationForm::class)->name('membership-form');



