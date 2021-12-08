<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePlanRequest;
use App\Http\Requests\UpdatePlanRequest;
use App\Models\Plan;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class PlanController extends Controller
{


    /**
     *  Display a form to create a plan
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        // no specific authorization - everybody can create a plan
        $plan = new Plan();
        return view('plan.create', ['plan' => $plan]);
    }

    /**
     * Store a newly created plan in the db
     *
     * @param StorePlanRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StorePlanRequest $request)
    {
        // no specific authorization - everybody can create a plan
        // validate the request
        $data = $request->validated();
        // Hash password with aragon2
        // Todo: implement auth provider correctly
        $data['password'] = Hash::make($data['password'], config('hashing.bcrypt'));
        $plan = Plan::create($data);
        // manually auth user with provided details
        Auth::login($plan, true);
        // redirect with success message
        Session::flash('info', __('plan.successfullyCreated'));
        return redirect()->route('plan.shift.index', ['plan' => $plan->id]);
    }

    /**
     * Show a plan with all subscriptions
     *
     * @param  \App\Models\Plan  $plan
     * @return \Illuminate\Http\Response
     */
    public function show(Plan $plan)
    {
        $this->authorize('view', $plan);
        return view('plan.show', ['plan' => $plan]);
    }

    /**
     * Show the form for editing the plan
     *
     * @param  \App\Models\Plan  $plan
     * @return \Illuminate\Http\Response
     */
    public function edit(Plan $plan)
    {
        $this->authorize('update', $plan);
        return view('plan.create', ['plan' => $plan]);
    }

    /**
     * Update the specified plan in storage.
     *
     * @param  \App\Http\Requests\UpdatePlanRequest  $request
     * @param  \App\Models\Plan  $plan
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePlanRequest $request, Plan $plan)
    {
        $this->authorize('update', $plan);
        $data = $request->validated();
        // prevent password to be overridden
        unset($data['password']);
        $plan->update($data);
        // redirect to shifts overview
        Session::flash('info', __('plan.successfullyUpdated'));
        return redirect()->route('plan.shift.index', ['plan' => $plan->id]);
    }

    /**
     * Remove the plan from storage.
     *
     * @param  \App\Models\Plan  $plan
     * @return \Illuminate\Http\Response
     */
    public function destroy(Plan $plan)
    {
        $this->authorize('forceDelete', $plan);
        $plan->forceDelete();
        Session::flash('info', __('plan.successfullyDestroyed'));
        return \redirect()->route('home');
    }
}
