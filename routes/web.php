<?php

use App\Http\Controllers\AdminController\AdminController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LogoutController;
use App\Livewire\AllCategory;
use App\Livewire\AddCategory;
use App\Livewire\AdminDashboard;

use Livewire\Livewire;




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
Route::middleware(['auth'])->group(function () {
Route::get('/allcategory', [AllCategory::class, 'render'])->name('allcategory');
Route::get('/addcategory', [AdminController::class, 'addcategory'])->name('addcategory');
Route::get('/admindashboard', [AdminDashboard::class, 'render'])->name('admindashboard');
});
Route::post('/logout', [LogoutController::class, 'logout'])->name('logout');


