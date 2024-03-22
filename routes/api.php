<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CompteController;
use App\Http\Controllers\PersonnelController;
use App\Http\Controllers\RaisonVisiteController;
use App\Http\Controllers\StatutController;
use App\Http\Controllers\TypeVisiteController;
use App\Http\Controllers\TypeVisiteurController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VisiteController;
use App\Http\Controllers\VisiteurController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::middleware('auth:sanctum')->group(function () {
});
Route::resource('typevisiteurs', TypeVisiteurController::class);
Route::resource('visiteurs', VisiteurController::class);
Route::resource('raisonvisites', RaisonVisiteController::class);
Route::resource('statuts', StatutController::class);
Route::resource('typevisites', TypeVisiteController::class);
Route::resource('personnels', PersonnelController::class);
// Route::resource('comptes', CompteController::class);
Route::resource('comptes', UserController::class);
Route::resource('visites', VisiteController::class);


Route::post('/login', [AuthController::class, 'login']);

// Vous pouvez également ajouter une route pour le logout si nécessaire
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
