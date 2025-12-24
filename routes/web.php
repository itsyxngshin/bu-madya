<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Open\LandingPage;
use App\Livewire\Open\Directory;
use App\Livewire\Open\Committees;
use App\Livewire\Open\News\Index as NewsIndex;
use App\Livewire\Open\News\Show as NewsShow;
use App\Livewire\Director\NewsCreate;
use App\Livewire\Director\ProjectsIndex;
use App\Livewire\Director\ProjectsShow;
use App\Livewire\Director\ProjectsCreate;

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

Route::get('/', LandingPage::class)->name('open.home');  
Route::get('/directory', Directory::class)->name('open.directory');  
Route::get('/committees', Committees::class)->name('open.committees'); 
Route::get('/news', NewsIndex::class)->name('news.index');
Route::get('/news/create', NewsCreate::class)->name('news.create');
Route::get('/news/{id}', NewsShow::class)->name('news.show');  
Route::get('/projects', ProjectsIndex::class)->name('projects.index'); 
Route::get('/projects/{id}', ProjectsShow::class)->name('projects.show');
Route::get('/projects/create', ProjectsCreate::class)->name('projects.create');
