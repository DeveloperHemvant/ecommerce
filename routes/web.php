<?php

use App\Http\Controllers\AdminController\AdminController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Livewire\AddCategory;
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



Route::post('/logout', [LogoutController::class, 'logout'])->name('logout');
    Route::middleware(['auth'])->group(function () {
        Route::controller(AdminController::class) ->group(function () {
                Route::get('/admindashboard', 'login');
                Route::get('/addcategory', 'addcategory');
                
                
            
            });

    });

