<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePlanRequest;
use App\Http\Requests\UpdatePlanRequest;
use App\Models\Plan;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;

class PlanController extends Controller
{


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $plan = new Plan();
        return view('plan.create', ['plan' => $plan]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StorePlanRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StorePlanRequest $request)
    {
        // no specific authorization
        // validate the request
        $data = $request->validated();
        // Hash password with aragon2
        $data['password'] = Hash::make($data['password'], config('hashing.bcrypt'));
        $plan = Plan::create($data);
        // auth user with provided details
        Auth::login($plan);
        // redirect
        Session::flash('message', __('plan.successfullyCreated'));
        return redirect()->route('plan.shift.index', ['plan' => $plan->id]);
    }

    /**
     * Display the specified resource.
     * Show a plan for subscribers
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
     * Show the form for editing the specified resource.
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
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdatePlanRequest  $request
     * @param  \App\Models\Plan  $plan
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePlanRequest $request, Plan $plan)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Plan  $plan
     * @return \Illuminate\Http\Response
     */
    public function destroy(Plan $plan)
    {
        //
    }
}
