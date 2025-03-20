<?php

use App\Http\Controllers\FrontEndController;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', [FrontEndController::class, 'showLoginForm']);
Route::post('login', [FrontEndController::class, 'postLogin'])->name('login');

Route::get('/dashboard', [FrontEndController::class, 'dashboard'])->middleware('auth');
