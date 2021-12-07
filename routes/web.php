<?php

use Illuminate\Support\Facades\Redirect;
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

Route::get('/', function () {
    return view('home');
});

/**
 * BC: old url for create a plan
 * redirect to plan.create
 */
Route::get('/plan/add', function () {
    return Redirect::route('plan.create');
});

/**
 * Plan resource controller
 */
Route::resource('plan', \App\Http\Controllers\PlanController::class)->only([
    'create', 'store', 'update', 'destroy',
]);

/**
 * Shift resource controller
 * Hint: just for auth users
 */
Route::resource('plan.shift', \App\Http\Controllers\ShiftController::class)->only([
    'index', 'create', 'store', 'update', 'destroy','edit',
])->middleware('auth');


/**
 * Route to view a plan with available shifts
 */
Route::get('/subscription/{plan:unique_link}', [\App\Http\Controllers\SubscriptionController::class, 'show'])
    ->name('plan.subscription.show');

/**
 * Subscribe to a shift
 */
Route::get('/subscription/{plan:unique_link}/shift/{shift}', [\App\Http\Controllers\SubscriptionController::class, 'create'])
    ->name('plan.subscription.create');

/**
 * Store new subscription
 */
Route::post('/subscription/{plan:unique_link}/shift/{shift}', [\App\Http\Controllers\SubscriptionController::class, 'store'])
    ->name('plan.shift.subscription.store');

/**
 * Update subscription
 * Hint: just owner of the plan can update a subscription
 */
Route::put('/subscription/{plan:unique_link}/shift/{shift}/{subscription}', [\App\Http\Controllers\SubscriptionController::class, 'update'])
    ->name('plan.shift.subscription.update');

/**
 * Delete subscription
 * Hint: just owner of the plan can update a subscription
 */
Route::delete('/subscription/{plan:unique_link}/shift/{shift}/{subscription}', [\App\Http\Controllers\SubscriptionController::class, 'destroy'])
    ->name('plan.shift.subscription.destroy');

/**
 * Edit subscription
 * Hint: just owner of the plan can update a subscription
 */
Route::get('/subscription/{plan:unique_link}/shift/{shift}/{subscription}/edit', [\App\Http\Controllers\SubscriptionController::class, 'edit'])
    ->name('plan.shift.subscription.edit');


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
