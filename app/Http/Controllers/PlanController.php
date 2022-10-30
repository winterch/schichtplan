<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePlanRequest;
use App\Http\Requests\RecoverPlanRequest;
use App\Http\Requests\UpdatePlanRequest;
use App\Models\Plan;
use App\Models\Shift;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Session;


use Illuminate\Support\Facades\DB;

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
        // If user enables notification, she/he will get the links to edit and view the plan into the inbox
        if((bool)$request->input('notification', 0)) {
            $plan->sendLinksNotification();
        }
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
     * Recover plans
     *
     * @return \Illuminate\Http\Response
     */
    public function recover()
    {
        return view('plan.recover');
    }
    /**
     * Recover plans
     *
     * @param RecoverPlanRequest $request
     * @return \Illuminate\Http\Response
     */
    public function doRecover(RecoverPlanRequest $request)
    {
        $email = $request->validated()['owner_email'];
        $plans = Plan::where('owner_email', $email)->get();
        if(count($plans) > 0) {
            foreach ($plans as $plan) {
                // todo: send just one email
                $plan->sendLinksNotification();
            }
        }
        // Show message anyway. So is not possible to check if an address has a plan
        Session::flash('info', __('plan.successfullyRecovered'));
        return view('plan.do_recover');
    }

    /**
     * Cleanup old plans.
     * todo: we may want to delete this
     */
    public function cron()
    {
        $old = DB::table('shifts')->whereDate('end', '<=', date('Y-m-d', strtotime('-30 days')))->get();
        foreach ($old as $shift) {
          Shift::findOrFail($shift->id)->delete();
        }
        $old = DB::table('plans')->whereDate('created_at', '<=', date('Y-m-d', strtotime('+30 days')))->get();
        foreach ($old as $p) {
          $plan = Plan::findOrFail($p->id);
          if ($plan->shifts->count() === 0)
            $plan->delete();
        }
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
        $this->authorize("view", $plan);
        return view('plan.admin')->with(['plan' => $plan]);
    }

    /**
     * Display an overview of shifts for a specified plan.
     *
     * @param Plan $plan
     * @return Response
     */
    public function admin_subscriptions(Plan $plan)
    {
        $this->auth($plan);
        $this->authorize("view", $plan);
        return view('plan.admin_subscriptions')->with(['plan' => $plan]);
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
        $this->authorize("update", $plan);
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
        $this->authorize("update", $plan);
        $data = $request->validated();
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
        $this->authorize("forceDelete", $plan);
        $plan->forceDelete();
        Session::flash('info', __('plan.successfullyDestroyed'));
        return \redirect()->route('home');
    }
}
