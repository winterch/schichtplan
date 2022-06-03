<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthLoginRequest;
use App\Models\Plan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use \Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    /**
     * Display a login form
     * @param Plan $plan
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function loginForm(Plan $plan) {
        // If user is logged in redirect her/him to the plan.show route
        $user = Auth::user();
        if($user) {
            // user is of type plan
            return redirect()->route('plan.show', ['plan' => $user]);
        }
        // Check if we know the plan. If not go to home
        if(!isset($plan->id) || ($plan->id === 0)) {
            Session::flash('error', __('auth.planNotFound'));
            return redirect()->route('home');
        }
        return view('auth.login', ['plan' => $plan]);
    }

    /**
     * Preform a login for a user on her/his plan
     * hint: There is no real user, just plans with email and password information
     * @param AuthLoginRequest $request
     * @param Plan $plan
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function login(AuthLoginRequest $request, Plan $plan) {
        $data = $request->validated();
        if (Auth::attempt($data)) {
            Log::Debug('Login successful');
            $request->session()->regenerate();
            return redirect()->route('plan.shift.index', ['plan' => $plan]);
        }
        Log::Debug('Login failed');
        return back()->withErrors([
            'owner_email' => 'The provided credentials do not match our records.',
        ])->onlyInput('owner_email');
    }

    /**
     * Logout a user and flush his/her session. Then redirect to the home site
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout()
    {
        Session::flush();
        Auth::logout();
        return redirect()->route('home');
    }
}
