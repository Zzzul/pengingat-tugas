<?php

use App\Http\Controllers\PdfController;
use App\Http\Livewire\Auth\{Login, Register, Logout};
use App\Http\Livewire\Profile\{Lainnya, Password, Setting, UserInformation};
use App\Http\Livewire\{Semester, Matkul, UserList, Tugas};
use Illuminate\Support\Facades\Route;

Route::view('/', 'home')->name('home');
Route::view('/about', 'about')->name('about');

Route::middleware('auth')
    ->get('lainnya', Lainnya::class)
    ->name('lainnya');

Route::group(['middleware' => 'guest'], function () {
    Route::get('register', Register::class)->name('register');
    Route::get('login', Login::class)->name('login');
});

Route::middleware(['auth', 'permission:semester'])
    ->get('semester', Semester::class)
    ->name('semester');

Route::middleware(['auth', 'permission:tugas'])
    ->get('tugas', Tugas::class)
    ->name('tugas');

Route::middleware(['auth', 'permission:ganti password'])
    ->get('change-password', Password::class)
    ->name('change-password');

Route::middleware(['auth', 'permission:mata kuliah'])
    ->get('mata-kuliah', Matkul::class)
    ->name('matkul');

Route::middleware(['auth', 'permission:edit profile'])
    ->get('profile', UserInformation::class)
    ->name('user-profile');

Route::middleware(['auth', 'role:admin'])
    ->get('user-list', UserList::class)
    ->name('user-list');

Route::middleware('auth')->group(function () {
    Route::get('mata-kuliah/pdf', [PdfController::class, 'downloadAllMatkul'])->name('pdf.matkul.all');

    Route::get('matkul-kuliah-aktif/pdf', [PdfController::class, 'downloadMatkulActive'])->name('pdf.matkul.aktif');

    Route::get('tugas/pdf', [PdfController::class, 'downloadAllTugas'])->name('pdf.tugas.all');
    Route::get('tugas-blom-dikerjakan/pdf', [PdfController::class, 'downladTugas'])->name('pdf.tugas');

    Route::get('semester/pdf', [PdfController::class, 'downloadAllSemester'])->name('pdf.semester.all');
});
