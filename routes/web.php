<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Open\LandingPage;
use App\Livewire\Open\Directory;
use App\Livewire\Open\Committees;
use App\Livewire\Open\CommitteeMembers;
use App\Livewire\Open\News\Index as NewsIndex;
use App\Livewire\Open\News\Show as NewsShow;
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

Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

// Middleware accessible to both members and directors
Route::middleware(['auth', 'role:director'])
    ->group(function () {
    Route::get('/project/create', ProjectsCreate::class)->name('projects.create');
    Route::get('/projects/{project:slug}/edit', ProjectsEdit::class)->name('projects.edit');
    Route::get('/profile/edit', EditProfile::class)->name('profile.edit');
    Route::get('/news/create', NewsCreate::class)->name('news.create');
    Route::get('/linkage/create', LinkagesCreate::class)->name('linkages.create');
    Route::get('/news/{slug}', NewsShow::class)->name('news.show');  
    Route::get('/news/{slug}/edit', NewsEdit::class)->name('news.edit');  
    Route::get('/linkage/{linkage:slug}/edit', LinkagesEdit::class)->name('linkages.edit');
});

Route::middleware(['auth', 'role:director'])
    ->group(function () {
    Route::get('/project/create', ProjectsCreate::class)->name('projects.create');
    Route::get('/projects/{project:slug}/edit', ProjectsEdit::class)->name('projects.edit');
    Route::get('/profile/edit', EditProfile::class)->name('profile.edit');
    Route::get('/news/create', NewsCreate::class)->name('news.create');
    Route::get('/linkage/create', LinkagesCreate::class)->name('linkages.create');
    Route::get('/news/{slug}', NewsShow::class)->name('news.show');  
    Route::get('/news/{slug}/edit', NewsEdit::class)->name('news.edit');  
    Route::get('/linkage/{linkage:slug}/edit', LinkagesEdit::class)->name('linkages.edit');
});

Route::middleware(['auth', 'role:director'])
    ->prefix('admin')
    ->group(function () {
   
});

// Public view blades with access control on parts of the navigation
Route::get('/', LandingPage::class)->name('open.home');  
Route::get('/directory', Directory::class)->name('open.directory');  
Route::get('/committees', Committees::class)->name('open.committees'); 
Route::get('/committees/{slug}', CommitteeMembers::class)->name('open.committees.show');
Route::get('/news', NewsIndex::class)->name('news.index');
Route::get('/projects', ProjectsIndex::class)->name('projects.index'); 
Route::get('/projects/{project:slug}', ProjectsShow::class)->name('projects.show');
Route::get('/linkages', LinkagesIndex::class)->name('linkages.index');
Route::get('/linkages/{linkage:slug}', LinkagesShow::class)->name('linkages.show');
Route::get('/partner-with-us', LinkagesProposal::class)->name('linkages.proposal'); 
Route::get('/profile/{username}', UserProfile::class)->name('profile.public');



