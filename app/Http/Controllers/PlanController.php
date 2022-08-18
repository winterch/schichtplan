<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePlanRequest;
use App\Http\Requests\UpdatePlanRequest;
use App\Models\Plan;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

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
        $plan = Plan::create($data);
        // redirect with success message
        Session::flash('info', __('plan.successfullyCreated'));
        return redirect()->route('plan.admin', ['plan' => $plan]);
    }

    /**
     * Show a plan with all subscriptions
     *
     * @param  \App\Models\Plan  $plan
     * @return \Illuminate\Http\Response
     */
    public function show(Plan $plan)
    {
        return view('plan.show', ['plan' => $plan]);
    }

    /**
     * Display an overview of shifts for a specified plan.
     *
     * @param Plan $plan
     * @return Response
     */
    public function admin(Plan $plan)
    {
        $this->auth($plan);
        return view('plan.admin')->with(['plan' => $plan]);
    }

    /**
     * Show the form for editing the plan
     *
     * @param  \App\Models\Plan  $plan
     * @return \Illuminate\Http\Response
     */
    public function edit(Plan $plan)
    {
        $this->auth($plan);
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
        $this->auth($plan);
        $data = $request->validated();
        // prevent to be overridden
        unset($data['edit_id']);
        unset($data['view_id']);
        unset($data['id']);
        $plan->update($data);
        // redirect to shifts overview
        Session::flash('info', __('plan.successfullyUpdated'));
        return redirect()->route('plan.admin', ['plan' => $plan]);
    }

    /**
     * Remove the plan from storage.
     *
     * @param  \App\Models\Plan  $plan
     * @return \Illuminate\Http\Response
     */
    public function destroy(Plan $plan)
    {
        $this->auth($plan);
        $plan->forceDelete();
        Session::flash('info', __('plan.successfullyDestroyed'));
        return \redirect()->route('home');
    }
}
