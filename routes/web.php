<?php

use App\Http\Livewire\Auth\Login;
use App\Http\Livewire\Auth\Logout;
use App\Http\Livewire\Auth\Register;
use App\Http\Livewire\Matkul;
use App\Http\Livewire\Profile\Lainnya;
use App\Http\Livewire\Profile\Password;
use App\Http\Livewire\Profile\Setting;
use App\Http\Livewire\Profile\UserInformation;
use App\Http\Livewire\Semester;
use App\Http\Livewire\Tugas;
use App\Http\Livewire\UserList;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

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
