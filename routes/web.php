<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\ShiftController;
use App\Http\Controllers\SubscriptionController;
use App\Models\Plan;
use App\Models\Shift;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

/**
 * Display home view, where users can create a plan
 */
Route::get('/', function () {
    return view('home');
})->name('home');


/**
 * This route will change the local of the current user and save selection in the session
 * After this it will redirect back to the origin url
 */
Route::get('/language/{locale}', function ($locale) {
    app()->setLocale($locale);
    session()->put('locale', $locale);
    // go back
    return redirect()->back();
});

/**
 * Create a new plan.
 */
Route::get('/plan/create', [PlanController::class, 'create'])
  ->name('plan.create');
Route::post('/plan/create', [PlanController::class, 'store'])
  ->name('plan.store');

/**
 * Unauthenticated show plan.
 */
Route::get('/s/{plan:view_id}', [PlanController::class, 'show'])
  ->name('plan.show');


/*****************************************
 *  Routes for Plan owners (need edit_id)
 *****************************************  */

/**
 * Edit plan details.
 */
Route::get('/plan/{plan:edit_id}/edit', [PlanController::class, 'edit'])
  ->name('plan.edit');
Route::put('/plan/{plan:edit_id}/update', [PlanController::class, 'update'])
  ->name('plan.update');

/**
 * Edit plan shifts.
 */
Route::get('/plan/{plan:edit_id}', [ShiftController::class, 'index'])
  ->name('plan.shift.index');
Route::get('/plan/{plan:edit_id}/shift/create', [ShiftController::class, 'create'])
  ->name('plan.shift.create');
Route::post('/plan/{plan:edit_id}/shift/store', [ShiftController::class, 'store'])
  ->name('plan.shift.store');
Route::get('/plan/{plan:edit_id}/shift/{shift}/edit', [ShiftController::class, 'edit'])
  ->name('plan.shift.edit');
Route::post('/plan/{plan:edit_id}/shift/{shift}/destroy', [ShiftController::class, 'destroy'])
  ->name('plan.shift.destroy');
Route::put('/plan/{plan:edit_id}/shift/{shift}/update', [ShiftController::class, 'update'])
  ->name('plan.shift.update');

/**
 * Edit subscription
 */
Route::get('/plan/{plan:edit_id}/shift/{shift}/{subscription}/edit', [SubscriptionController::class, 'edit'])
    ->name('plan.shift.subscription.edit');
Route::put('/plan/{plan:edit_id}/shift/{shift}/{subscription}', [SubscriptionController::class, 'update'])
    ->name('plan.shift.subscription.update');
Route::delete('/plan/{plan:edit_id}/shift/{shift}/{subscription}', [SubscriptionController::class, 'destroy'])
    ->name('plan.shift.subscription.destroy');


/*****************************************
 *  Routes for everybody (need view_id)
 *****************************************  */

/**
 * Subscribe to a shift
 */
Route::get('/s/{plan:view_id}/shift/{shift}', [SubscriptionController::class, 'create'])
    ->name('plan.subscription.create');
Route::post('/s/{plan:view_id}/shift/{shift}', [SubscriptionController::class, 'store'])
    ->name('plan.shift.subscription.store');


