<?php

use App\Http\Controllers\CindiqueController;
use App\Http\Controllers\HOAController;
use App\Http\Controllers\MaintenancesController;
use App\Http\Controllers\ResedenceController;
use App\Http\Middleware\CheckAdmin;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Middleware\CheckCindik;
use App\Http\Middleware\CheckHOA;
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

Auth::routes();
//Language Translation
Route::get('index/{locale}', [App\Http\Controllers\HomeController::class, 'lang']);

Route::get('/', [App\Http\Controllers\HomeController::class, 'root'])->name('root');

//Update User Details
Route::post('/update-profile/{id}', [App\Http\Controllers\HomeController::class, 'updateProfile'])->name('updateProfile');
Route::post('/update-password/{id}', [App\Http\Controllers\HomeController::class, 'updatePassword'])->name('updatePassword');








Route::middleware(['auth', CheckCindik::class])->group(function () {
  Route::get('/getviewHoaFormCreate', [HOAController::class, 'getviewHoaFormCreate'])->name('get.hoa.form.create');
});

Route::post('/hoa/store', [HoaController::class, 'store'])->name('hoa.store')->middleware('auth');
Route::get('/hoa', [HOAController::class, 'index'])->name('cre')->middleware('auth');

// Route::get('/getCreateResedence', [ResedenceController::class, 'getCreate'])->name('getCreate.Resedonce')->middleware('auth');
// Route::get('/getAllResedence', [ResedenceController::class, 'index'])->name('get.all.Resedonce')->middleware('auth');
// Route::post('/resdence/store', [ResedenceController::class, 'store'])->name('resdence.store')->middleware('auth');
// Route::post('/residents/{id}', [ResedenceController::class, 'update'])->name('residents.update')->middleware('auth');
// Route::delete('/getdeleteResedence/{id}', [ResedenceController::class, 'destroy'])->name('get.delete.Resedonce')->middleware('auth');



Route::middleware(['auth', CheckCindik::class, CheckHOA::class])->group(function () {

  // Resedence

  Route::get('/getViewResedence', [ResedenceController::class, 'getViewResedence'])->name('getView.Resedence');
  Route::get('/getAllResedence', [ResedenceController::class, 'index'])->name('get.all.Resedonce');
  Route::post('/resdence/store', [ResedenceController::class, 'store'])->name('resdence.store');
  Route::post('/residents/{id}', [ResedenceController::class, 'update'])->name('residents.update');
  Route::delete('/getdeleteResedence/{id}', [ResedenceController::class, 'destroy'])->name('get.delete.Resedonce');

  // HOA
  // Route::get('/getCreateHoa', [HOAController::class, 'create'])->name('getCreate.hoa');
  Route::get('/getHOAInformation', [HOAController::class, 'getHOA'])->name('get.hoa.information');
  Route::post('/hoa/update', [HoaController::class, 'update'])->name('hoa.update');
  Route::get('/getHOAView', [HOAController::class, 'getviewHoa'])->name('get.hoa.view');

  // Maintenances
  Route::get('/getViewMantenace', [MaintenancesController::class, 'getViewMantenace'])->name('maintenances.view');
  Route::post('/maintenances/store', [MaintenancesController::class, 'store'])->name('maintenances.store');
  Route::get('/getAllMaintenances', [MaintenancesController::class, 'index'])->name('get.all.maintenances');
  Route::delete('/getdeleteMaintenances/{id}', [MaintenancesController::class, 'destroy'])->name('get.delete.Maintenances');
  Route::post('/maintenances/update/{id}', [MaintenancesController::class, 'update'])->name('maintenances.update');
});

Route::middleware(['auth', CheckAdmin::class])->group(function () {
  Route::get('/getAllCindiques', [CindiqueController::class, 'index'])->name('get.all.Cindique');
  Route::get('/getAllCindiquesView', [CindiqueController::class, 'getCindikView'])->name('get.all.Cindique.view');
  Route::post('/cindique/store', [CindiqueController::class, 'store'])->name('cindique.store');
  Route::post('/cindique/{id}', [CindiqueController::class, 'update'])->name('cindique.update');
  Route::delete('/getdeleteCindique/{id}', [CindiqueController::class, 'destroy'])->name('get.delete.Cindique');
});


// Maintenances router




// Cindeque Router




Route::get('{any}', [App\Http\Controllers\HomeController::class, 'index'])->name('index');
