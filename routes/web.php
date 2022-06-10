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
 * Display login form with plan id in url
 */
Route::get('/auth/login/{plan:unique_link?}', [AuthController::class, 'loginForm'])
->name('login');

/**
 * Logout an authenticated user
 */
Route::get('/auth/logout', [AuthController::class, 'logout'])
    ->name('logout');

/**
 * Preform a login for a user
 */
Route::post('/auth/login/{plan:unique_link}', [AuthController::class, 'login'])
    ->name('auth.authenticate');

/**
 * Display a password forgot form
 */
Route::get('/auth/forgot-password/{plan:unique_link}', [AuthController::class, 'forgotPasswordForm'])
    ->middleware('guest')
    ->name('password.request');

/**
 * Handle password forgot submission. Send the reset link to the user
 */
Route::post('/auth/forgot-password/{plan:unique_link}', [AuthController::class, 'forgotPassword'])
    ->middleware('guest')
    ->name('password.email');

/**
 * Show form to set new password
 */
Route::get('/auth/reset-password/{plan:unique_link}/{token}', [AuthController::class, 'resetPasswordForm'])
    ->middleware('guest')
    ->name('password.reset');

/**
 * Handle the reset password request
 */
Route::post('/auth/reset-password/{plan:unique_link}', [AuthController::class, "resetPassword"])
    ->middleware('guest')
    ->name('password.update');

/**
 * Create a new plan.
 * Hint: This doesn't need any authentication. Everybody can do this
 */
Route::get('/plan/create', [PlanController::class, 'create'])->name('plan.create');

/**
 * Store a new plan.
 * Hint: This doesn't need any authentication. Everybody can do this
 */
Route::post('/plan', [PlanController::class, 'store'])->name('plan.store');

/**
 * Plan resource controller
 * Hint: From here on a user have to be logged in
 */
Route::resource('plan', PlanController::class)->only([
    'edit', 'update', 'destroy','show',
])->middleware('auth')->scoped(["plan" => "unique_link"]);

/**
 * Shift resource controller
 * Hint: Just for authenticated users
 */
Route::resource('plan.shift', ShiftController::class)->only([
    'index', 'create', 'store', 'update', 'destroy','edit', 'show',
])->middleware('auth')->scoped(["plan" => "unique_link"]);


/**
 * Route to view a plan with available shifts
 * Hint: No authentication needed. If a user knows the unique_link she/he is allowed to subscribe
 */
Route::get('/subscription/{plan:unique_link}', [SubscriptionController::class, 'show'])
    ->name('plan.subscription.show');

/**
 * Subscribe to a shift
 * Hint: No authentication needed. If a user knows the unique_link she/he is allowed to subscribe
 */
Route::get('/subscription/{plan:unique_link}/shift/{shift}', [SubscriptionController::class, 'create'])
    ->name('plan.subscription.create');

/**
 * Store new subscription
 * Hint: No authentication needed. If a user knows the unique_link she/he is allowed to subscribe
 */
Route::post('/subscription/{plan:unique_link}/shift/{shift}', [SubscriptionController::class, 'store'])
    ->name('plan.shift.subscription.store');
/**
 * Edit subscription
 * Hint: Just owner of the plan and the owner of the subscription can update a subscription
 */
Route::get('/subscription/{plan:unique_link}/shift/{shift}/{subscription}/edit', [SubscriptionController::class, 'edit'])
    ->name('plan.shift.subscription.edit');

/**
 * Update subscription
 * Hint: Just owner of the plan and the owner of the subscription can update a subscription
 */
Route::put('/subscription/{plan:unique_link}/shift/{shift}/{subscription}', [SubscriptionController::class, 'update'])
    ->name('plan.shift.subscription.update');

/**
 * Delete subscription
 * Hint: Just owner of the plan and the owner of the subscription can update a subscription
 */
Route::delete('/subscription/{plan:unique_link}/shift/{shift}/{subscription}', [SubscriptionController::class, 'destroy'])
    ->name('plan.shift.subscription.destroy');

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
 * BC: old url for create a plan form
 */
Route::get('/plan/add', function () {
    return Redirect::route('plan.create');
});

/**
 * BC: old url for editing plan
 */
Route::get('/plans/edit/{plan}', function (Plan $plan) {
    return Redirect::route('plan.shift.index', ['plan' => $plan ]);
});

/**
 * BC: old url for adding shifts
 */
Route::get('/shifts/add/{plan}', function (Plan $plan) {
    return Redirect::route('plan.shift.create', ['plan' => $plan ]);
});

/**
 * BC: old url for editing shifts
 */
Route::get('/shifts/edit/{shift}', function (Shift $shift) {
    return Redirect::route('plan.shift.edit', ['plan' => $shift->plan, 'shift' => $shift ]);
});

/**
 * BC: old url for subscribing to shifts
 */
Route::get('/plans/show/{plan:unique_link}', function (Plan $plan) {
    return Redirect::route('plan.subscription.show', ['plan' => $plan ]);
});

/**
 * BC: old url for subscribe to a shift
 */
Route::get('/subscriptions/add/{shift}', function (Request $request, Shift $shift) {
    $plan = Plan::Where('unique_link', '=', $request->query('unique_link'))->first();
    if(!$plan) {
        return abort(404);
    }
    return Redirect::route('plan.subscription.create', ['plan' => $plan, 'shift' => $shift ]);
});
/**
 * BC: old url for subscribe to a shift
 */
Route::get('/plans/authenticate/{plan:unique_link}', function (Plan $plan) {
    return Redirect::route('login', ['plan' => $plan]);
});
