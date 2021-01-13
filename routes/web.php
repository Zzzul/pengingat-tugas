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

Route::get('/', function () {
    return view('home');
});

Route::view('home', 'home');

Route::get('semester', Semester::class);
Route::get('matkul', Matkul::class);
Route::get('tugas', Tugas::class);
