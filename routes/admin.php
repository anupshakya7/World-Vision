<?php

use App\Http\Controllers\Admin\Auth\LoginController;
use App\Http\Controllers\Admin\Authorize\PermissionController;
use App\Http\Controllers\Admin\Authorize\RoleController;
use App\Http\Controllers\Admin\CategoryColorController;
use App\Http\Controllers\Admin\CountryController;
use App\Http\Controllers\Admin\CountryDataController;
use App\Http\Controllers\Admin\IndexController;
use App\Http\Controllers\Admin\SourceController;
use App\Http\Controllers\Admin\IndicatorController;
use App\Http\Controllers\Admin\SubCountryController;
use App\Http\Controllers\Admin\SubCountryDataController;
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

    //User Management
    //Role
    Route::resource('roles',RoleController::class);
    
    //Permission
    Route::resource('permissions',PermissionController::class);

    //Category Colors
    Route::resource('category-color',CategoryColorController::class);

    //Country Management
    //Country
    Route::resource('country', CountryController::class);

    //Country Data
    Route::resource('country-data',CountryDataController::class);
    Route::prefix('country-data')->name('country-data.')->group(function(){
        //Export
        Route::get('generate/csv',[CountryDataController::class,'generateCSV'])->name('generate.csv');

        //Import
        Route::get('bulk/insert',[CountryDataController::class,'bulk'])->name('bulk');
        Route::post('bulk/insert',[CountryDataController::class,'bulkInsert'])->name('bulk.insert');
    });

    //Sub Country Management
    //Sub Country
    Route::resource('sub-country', SubCountryController::class);

    //Sub Country Data
    Route::resource('sub-country-data',SubCountryDataController::class);
    Route::prefix('sub-country-data')->name('sub-country-data.')->group(function(){
        //Export
        Route::get('generate/csv',[SubCountryDataController::class,'generateCSV'])->name('generate.csv');

        //Import
        Route::get('bulk/insert',[SubCountryDataController::class,'bulk'])->name('bulk');
        Route::post('bulk/insert',[SubCountryDataController::class,'bulkInsert'])->name('bulk.insert');
    });

    //Indicator
    Route::resource('indicator', IndicatorController::class);

    //Source
    Route::resource('source',SourceController::class);
});
