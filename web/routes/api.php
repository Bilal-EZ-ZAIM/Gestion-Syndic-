<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CindiqueController;
use App\Http\Controllers\HOAController;
use App\Http\Controllers\LoginController as ControllersLoginController;
use App\Http\Controllers\MaintenancesController;
use App\Http\Controllers\ResedenceController;
use App\Http\Middleware\CheckAdmin;
use App\Http\Middleware\CheckCindik;
use App\Http\Middleware\CheckHOA;


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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//   return $request->user();
// });

Route::get("/ge", function (Request $request) {

  return   response()->json([
    "name" => "bilal"
  ]);
});


Route::post('/logins', [ControllersLoginController::class, 'loginUser']);

Route::middleware("auth:sanctum")->get("/getUserFromToken", [ControllersLoginController::class, 'getUserFromToken']);


Route::middleware(['auth:sanctum', CheckCindik::class])->group(function () {
  Route::post('/hoas', [HOAController::class, 'store'])->name('hoas.store');
});



Route::middleware(['auth:sanctum', CheckCindik::class, CheckHOA::class])->group(function () {

  // Resedence
  Route::get('/resedences', [ResedenceController::class, 'index']);
  Route::get('/resedences/{id}', [ResedenceController::class, 'show'])->name('resedences.show');
  Route::post('/resedences', [ResedenceController::class, 'store'])->name('resedences.store');
  Route::put('/resedences/{id}', [ResedenceController::class, 'update'])->name('resedences.update');
  Route::delete('/resedences/{id}', [ResedenceController::class, 'destroy'])->name('resedences.destroy');

  // HOA
  // Route::get('/hoas', [HOAController::class, 'index'])->name('hoas.index');
  Route::get('/getHOA', [HOAController::class, 'getHOA']);
  // Route::get('/hoas/{id}', [HOAController::class, 'show'])->name('hoas.show');

  Route::put('/hoas/{id}', [HOAController::class, 'update'])->name('hoas.update');

  // Maintenances
  Route::get('/maintenances', [MaintenancesController::class, 'index'])->name('maintenances.index');
  Route::get('/maintenances/{id}', [MaintenancesController::class, 'show'])->name('maintenances.show');
  Route::post('/maintenances', [MaintenancesController::class, 'store'])->name('maintenances.store');
  Route::put('/maintenances/{id}', [MaintenancesController::class, 'update'])->name('maintenances.update');
  Route::delete('/maintenances/{id}', [MaintenancesController::class, 'destroy'])->name('maintenances.destroy');
});

Route::middleware(['auth:sanctum', CheckAdmin::class])->group(function () {
  Route::get('/cindiques', [CindiqueController::class, 'index'])->name('cindiques.index');
  Route::get('/cindiques/{id}', [CindiqueController::class, 'show'])->name('cindiques.show');
  Route::post('/cindiques', [CindiqueController::class, 'store'])->name('cindiques.store');
  Route::put('/cindiques/{id}', [CindiqueController::class, 'update'])->name('cindiques.update');
  Route::delete('/cindiques/{id}', [CindiqueController::class, 'destroy'])->name('cindiques.destroy');
});
