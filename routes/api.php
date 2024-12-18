<?php

use App\Http\Controllers\ATI\Admin\CountryData\VoiceOfPeopleController;
use App\Http\Controllers\ATI\API\AllAPIController;
use App\Http\Controllers\ATI\API\MapAPIController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//Check Voice of People
Route::get('/check-voice-people',[VoiceOfPeopleController::class,'checkCountryYearWise'])->name('check.voice.people');

//ATI API
//Map
Route::get('map',[AllAPIController::class,'mapAPI']);

//Domain Result and Voice Of People API
Route::get('domain-voice',[AllAPIController::class,'domainVoiceAPI']);

//Radar Chart Domain and Indicator Compare Africa and Country and Indicator Trend Chart
Route::get('radar-trend-chart',[AllAPIController::class,'radarTrendChartDomainIndicator']);

//Domain Score and Governance Vs Enabling Graph
Route::get('domain-governance-compare',[AllAPIController::class,'domainGovernanceCompare']);

//Risk Outlook
Route::get('risk-outlook',[AllAPIController::class,'riskOutlookAPI']);