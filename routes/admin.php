<?php

use App\Http\Controllers\Admin\Auth\LoginController;
use App\Http\Controllers\Admin\CountryController;
use App\Http\Controllers\Admin\IndexController;
use App\Http\Controllers\Admin\SourceController;
use App\Http\Controllers\Admin\IndicatorController;
use Illuminate\Support\Facades\Route;

Route::middleware(['guest'])->group(function () {
    //Authentication
    Route::get('/login', [LoginController::class,'login'])->name('login');
    Route::post('/login', [LoginController::class,'loginSubmit'])->name('login');
    //Authentication
});


Route::middleware(['auth'])->group(function () {
    //Dashboard
    Route::get('/', [IndexController::class,'dashboard'])->name('home');

    //Logout
    Route::post('/logout', [LoginController::class,'logout'])->name('logout');

    //Country Management
    Route::resource('country', CountryController::class);

    //Indicator
    Route::resource('indicator', IndicatorController::class);

    //Source
    Route::resource('source',SourceController::class);
});
