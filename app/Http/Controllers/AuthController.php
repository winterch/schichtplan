<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthLoginRequest;
use App\Models\Plan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
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
            return \redirect()->route('home');
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
        // todo: implement attempt() method instead of own logic
        // check if the emails are the same
        if($plan->owner_email !== $data['email']) {
            Log::Debug('Email does not match');
            Session::flash('loginFailed', __('auth.failed'));
            return view('auth.login', ['plan' => $plan]);
        }
        // Compare password hashes
        if(!Hash::check($data['password'], $plan->password)) {
            Log::Debug('Password does not match');
            Session::flash('loginFailed', __('auth.failed'));
            return view('auth.login', ['plan' => $plan]);
        }
        // login user
        Auth::Login($plan);
        return redirect()->route('plan.shift.index', ['plan' => $plan]);
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
