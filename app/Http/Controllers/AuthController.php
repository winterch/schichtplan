<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthLoginRequest;
use App\Http\Requests\AuthPasswordForgotRequest;
use App\Http\Requests\AuthResetPasswordRequest;
use App\Models\Plan;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\RedirectResponse;
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
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|RedirectResponse
     */
    public function loginForm(Plan $plan)
    {
        // If user is logged in redirect her/him to the plan
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
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|RedirectResponse
     */
    public function login(AuthLoginRequest $request, Plan $plan)
    {
        $data = $request->validated();
        // A owner_email can own several plans. So we need to check the plans unique_link as well
        if (Auth::attempt(['owner_email' => $data['owner_email'], 'password' => $data['password'], 'unique_link' => $plan->unique_link])) {
            Log::Debug('Login successful');
            $request->session()->regenerate();
            return redirect()->route('plan.shift.index', ['plan' => $plan]);
        }
        // Login fail. Go back to login page
        Log::Debug('Login failed');
        return back()->withErrors([
            'owner_email' => __('passwords.credentials'),
        ])->onlyInput('owner_email');
    }

    /**
     * Logout a user and flush his/her session. Then redirect to the home site
     * @return RedirectResponse
     */
    public function logout()
    {
        Session::flush();
        Auth::logout();
        return redirect()->route('home')->with('info', __('auth.logoutSuccess'));
    }

    /**
     * Show forget PW form, so a user can request a new PW for a given plan
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
     * @return RedirectResponse
     */
    public function forgotPassword(AuthPasswordForgotRequest $request, Plan $plan): RedirectResponse
    {
        $data = $request->validated();
        $data['unique_link'] =  $plan->unique_link;
        // The sendResetLink fetch the user from DB based on the keys in the $data array
        // in our case we inject unique_link and email and should get one Plan or an error
        // @see DatabaseUserProvider
        $status = Password::sendResetLink(
            $data
        );

        return $status === Password::RESET_LINK_SENT
            ? back()->with(['info' => __($status)])
            : back()->withErrors(['owner_email' => __($status)]);
    }

    /**
     * Show form to enter new PW for a plan with a reset token
     * @param Plan $plan
     * @param string $token
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function resetPasswordForm(Plan $plan, string $token)
    {
        return view('auth.reset_password', ['token' => $token, 'plan' => $plan]);
    }

    /**
     * Reset the password for a plan or display an error
     * @param AuthResetPasswordRequest $request
     * @param Plan $plan
     * @return RedirectResponse
     */
    public function resetPassword(AuthResetPasswordRequest $request, Plan $plan)
    {
        $data = $request->validated();
        // inject the unique_link to get only one plan in the Password::reset function
        // @see DatabaseUserProvider
        $data['unique_link'] = $plan->unique_link;
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
        // Go to plan or redirect to form with error
        return $status === Password::PASSWORD_RESET
            ? redirect()->route('login', ['plan' => $plan])->with('info', __($status))
            : back()->withErrors(['owner_email' => [__($status)]]);
    }
}
