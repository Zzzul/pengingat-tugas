<?php

use App\Http\Livewire\Auth\Login;
use App\Http\Livewire\Auth\Logout;
use App\Http\Livewire\Auth\Register;
use App\Http\Livewire\Matkul;
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

Route::group(['middleware' => ['auth']], function () {
    Route::get('semester', Semester::class)->name('semester');
    Route::get('mata-kuliah', Matkul::class)->name('matkul');
    Route::get('tugas', Tugas::class)->name('tugas');


    Route::get('user-list', UserList::class)->name('user-list');

    // Profile
    Route::get('setting', Setting::class)->name('setting');
    Route::get('profile', UserInformation::class)->name('user-profile');
    Route::get('change-password', Password::class)->name('change-password');
});

Route::group(['middleware' => ['guest']], function () {
    Route::get('register', Register::class)->name('register');
    Route::get('login', Login::class)->name('login');
    Route::get('logout', Logout::class)->name('logout');
});
