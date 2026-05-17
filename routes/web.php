<?php

use App\Http\Controllers\Admin\LogoutController;
use App\Livewire\Admin\ComponentDocs;
use App\Livewire\Admin\Dashboard;
use App\Livewire\Admin\JuriManager;
use App\Livewire\Admin\MahasiswaManager;
use App\Livewire\Admin\PresentasiControl;
use App\Livewire\Admin\Profile;
use App\Livewire\Admin\TemaManager;
use App\Livewire\Admin\UrutanManager;
use App\Livewire\Admin\UserManagement;
use App\Livewire\Auth\JuriLogin;
use App\Livewire\Auth\Login;
use App\Livewire\Auth\MahasiswaLogin;
use App\Livewire\Auth\Register;
use App\Livewire\Guest\LandingPage;
use App\Livewire\Guest\PresentasiPublic;
use App\Livewire\Juri\Presentasi;
use App\Livewire\Mahasiswa\SlideManager;
use Illuminate\Support\Facades\Route;

// Guest Routes
Route::get('/', LandingPage::class)->name('home');
Route::get('/presentasi', PresentasiPublic::class)->name('presentasi.public');

// Auth Routes
Route::get('/login', Login::class)->name('login');
Route::get('/register', Register::class)->name('register');

// Mahasiswa Routes
Route::get('/mahasiswa/login', MahasiswaLogin::class)->name('mahasiswa.login');
Route::prefix('mahasiswa')->middleware('auth:mahasiswa')->group(function () {
    Route::get('/dashboard', App\Livewire\Mahasiswa\Dashboard::class)->name('mahasiswa.dashboard');
    Route::get('/slides', SlideManager::class)->name('mahasiswa.slides');
    Route::get('/profile', App\Livewire\Mahasiswa\Profile::class)->name('mahasiswa.profile');
    Route::post('/logout', [App\Http\Controllers\Mahasiswa\LogoutController::class, '__invoke'])->name('mahasiswa.logout');
});

// Juri Routes
Route::get('/juri/login', JuriLogin::class)->name('juri.login');
Route::prefix('juri')->middleware('auth:juri')->group(function () {
    Route::get('/presentasi', Presentasi::class)->name('juri.presentasi');
    Route::post('/logout', [App\Http\Controllers\Juri\LogoutController::class, '__invoke'])->name('juri.logout');
});

Route::prefix('admin')->middleware('auth')->group(function () {
    Route::get('/dashboard', Dashboard::class)->name('dashboard');
    Route::get('/users', UserManagement::class)->name('admin.users');
    Route::get('/mahasiswa', MahasiswaManager::class)->name('admin.mahasiswa');
    Route::get('/mahasiswa/urutan', UrutanManager::class)->name('admin.urutan');
    Route::get('/tema', TemaManager::class)->name('admin.tema');
    Route::get('/juri', JuriManager::class)->name('admin.juri');
    Route::get('/presentasi', PresentasiControl::class)->name('admin.presentasi');
    Route::get('/profile', Profile::class)->name('admin.profile');
    Route::get('/components', ComponentDocs::class)->name('admin.components');
    Route::post('/logout', [LogoutController::class, '__invoke'])->name('logout');
});
