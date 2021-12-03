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
    'index', 'create', 'store', 'update', 'destroy',
])->middleware('auth');

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
