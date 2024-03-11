<?php

use App\Http\Controllers\AdminController\AdminController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LogoutController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('Auth.login');
})->name('login');

Route::get('/admindashboard', function () {
    return view('Admin.Dashboard');
})->middleware('auth');

Route::get('/admindashboard',  [AdminController::class, 'login'])->middleware('auth');


Route::post('/logout', [LogoutController::class, 'logout'])->name('logout');

