<?php

use App\Http\Livewire\Matkul;
use App\Http\Livewire\Semester;
use App\Http\Livewire\Tugas;
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

// Route::get('/', function () {
//     return view('home')->name('home');
// });

Route::view('/', 'home')->name('home');
// Route::view('home', 'home')->name('home');

Route::group(['middleware' => ['auth']], function () {
    Route::get('semester', Semester::class)->name('semester');
    Route::get('mata-kuliah', Matkul::class)->name('matkul');
    Route::get('tugas', Tugas::class)->name('tugas');

    Route::view('/profile', 'profile.user-profile-information-form')->name('user-profile-information.edit');
    Route::view('change-password', 'auth.passwords.change-password')->name('password.edit');

    Route::view('setting', 'profile.setting')->name('setting');
});
