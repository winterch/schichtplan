<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthLoginRequest;
use App\Http\Requests\AuthPasswordForgotRequest;
use App\Http\Requests\AuthResetPasswordRequest;
use App\Models\Plan;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Password;
use \Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    /**
     * Display a login form
     * @param Plan $plan
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function loginForm(Plan $plan)
    {
        // If user is logged in redirect her/him to the plan.show route
        $user = Auth::user();
        if ($user) {
            // user is of type plan
            return redirect()->route('plan.show', ['plan' => $user]);
        }
        // Check if we know the plan. If not go to home
        if (!isset($plan->id) || ($plan->id === 0)) {
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
    public function login(AuthLoginRequest $request, Plan $plan)
    {
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

    /**
     * Show forget PW form, so a user can request a new PW for a plan
     * @param Plan $plan
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function forgotPasswordForm(Plan $plan)
    {
        return view('auth.forgot_password')->with('plan', $plan);
    }

    /**
     * Handle PW forget request and send an email to the user with a reset link
     * @param AuthPasswordForgotRequest $request
     * @param Plan $plan
     * @return \Illuminate\Http\RedirectResponse
     */
    public function forgotPassword(AuthPasswordForgotRequest $request, Plan $plan): \Illuminate\Http\RedirectResponse
    {
        $data = $request->validated();
        // todo: add validation for the plan
        $status = Password::sendResetLink(
            $data
        );

        return $status === Password::RESET_LINK_SENT
            ? back()->with(['status' => __($status)])
            : back()->withErrors(['owner_email' => __($status)]);
    }

    /**
     * Show form to enter new PW for a plan
     * @param $token
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function resetPasswordForm(string $token)
    {
        return view('auth.reset_password', ['token' => $token]);
    }

    /**
     * Reset the password or display an error
     * @param AuthResetPasswordRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function resetPassword(AuthResetPasswordRequest $request)
    {
        $data = $request->validated();
        $status = Password::reset(
            $data,
            function ($plan, $password) {
                $plan->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));

                $plan->save();
                event(new PasswordReset($plan));
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('login')->with('status', __($status))
            : back()->withErrors(['owner_email' => [__($status)]]);
    }
}
