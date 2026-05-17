<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Admin\Dashboard;
use App\Livewire\Admin\UserManagement;
use App\Livewire\Admin\Profile;
use App\Livewire\Admin\ComponentDocs;
use App\Livewire\Auth\Login;
use App\Livewire\Auth\Register;
use App\Livewire\Guest\LandingPage;
use App\Http\Controllers\Admin\LogoutController;

// Guest Routes
Route::get('/', LandingPage::class)->name('home');

// Auth Routes
Route::get('/login', Login::class)->name('login');
Route::get('/register', Register::class)->name('register');

// Mahasiswa Routes
Route::get('/mahasiswa/login', \App\Livewire\Auth\MahasiswaLogin::class)->name('mahasiswa.login');
Route::prefix('mahasiswa')->middleware('auth:mahasiswa')->group(function () {
    Route::get('/dashboard', \App\Livewire\Mahasiswa\Dashboard::class)->name('mahasiswa.dashboard');
    Route::get('/slides', \App\Livewire\Mahasiswa\SlideManager::class)->name('mahasiswa.slides');
    Route::get('/profile', \App\Livewire\Mahasiswa\Profile::class)->name('mahasiswa.profile');
    Route::post('/logout', [\App\Http\Controllers\Mahasiswa\LogoutController::class, '__invoke'])->name('mahasiswa.logout');
});

Route::prefix('admin')->middleware('auth')->group(function () {
    Route::get('/dashboard', Dashboard::class)->name('dashboard');
    Route::get('/users', UserManagement::class)->name('admin.users');
    Route::get('/mahasiswa', \App\Livewire\Admin\MahasiswaManager::class)->name('admin.mahasiswa');
    Route::get('/mahasiswa/urutan', \App\Livewire\Admin\UrutanManager::class)->name('admin.urutan');
    Route::get('/tema', \App\Livewire\Admin\TemaManager::class)->name('admin.tema');
    Route::get('/juri', \App\Livewire\Admin\JuriManager::class)->name('admin.juri');
    Route::get('/profile', Profile::class)->name('admin.profile');
    Route::get('/components', ComponentDocs::class)->name('admin.components');
    Route::post('/logout', [LogoutController::class, '__invoke'])->name('logout');
});