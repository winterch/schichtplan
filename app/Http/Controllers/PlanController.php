<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePlanRequest;
use App\Http\Requests\RecoverPlanRequest;
use App\Http\Requests\UpdatePlanRequest;
use App\Models\Plan;
use App\Models\Shift;
use App\Models\Subscription;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;

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

    public function import(Request $request, Plan $plan) {
        if ($plan->id) {
            $this->auth($plan);
            $this->authorize('update', $plan);
        } else {
            $plan = new Plan;
        }
        $file = $request->file('import');
        $in = fopen($file->getRealPath(), 'r');
        $plan->title = '';
        $plan->description = '';
        $plan->owner_email = '';
        $plan->save();
        $shift = null;
        while(($data = fgetcsv($in)) !== FALSE) {
            if (preg_match("/^[ ]*shift$/", $data[0])) {
                $shift = new Shift();
                $shift->plan_id = $plan->id;
                $shift->import($data);
                $shift->save();
            } else if (preg_match("/^[ ]*subscribed$/", $data[0])) {
                $sub = new Subscription();
                $sub->shift_id = $shift->id;
                $sub->import($data);
                $sub->save();
            } else {
                $plan->fill([$data[0] => $data[1]]);
            }
        }
        $plan->save();
        return redirect()->route('plan.admin', ['plan' => $plan]);
    }

    public function export(Plan $plan)
    {
        $fileName = 'shift-plan-'.$plan->title.'.csv';
        $headers = array(
              "Content-type"        => "text/csv",
              "Content-Disposition" => "attachment; filename=$fileName",
              "Pragma"              => "no-cache",
              "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
              "Expires"             => "0"
        );

        $callback = function() use($plan) {
            $file = fopen('php://output', 'w');
            $plan->export($file);
            foreach ($plan->shifts()->get() as $shift) {
                fputcsv($file, $shift->export());
                foreach ($shift->subscriptions()->get() as $sub) {
                    fputcsv($file, $sub->export());
                }
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Recover plans
     *
     * @return \Illuminate\Http\Response
     */
    public function recover(Plan $plan)
    {
        return view('plan.recover', ['plan' => $plan]);
    }
    /**
     * Recover plans
     *
     * @param RecoverPlanRequest $request
     * @return \Illuminate\Http\Response
     */
    public function doRecover(RecoverPlanRequest $request, Plan $plan)
    {
        $email = $request->validated()['owner_email'];
        if ($plan->id) {
            $plan->sendLinksNotification();
        } else {
            $plans = Plan::where('owner_email', $email)->get();
            if(count($plans) > 0) {
                $plans[0]->sendAllLinksNotification($plans);
            }
        }
        // Show message anyway. So is not possible to check if an address has a plan
        Session::flash('info', __('plan.successfullyRecovered'));
        return view('plan.do_recover');
    }

    /**
     * Cleanup old plans and notify.
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
        $done = array();
        $toNotify = DB::table('shifts')
          ->whereDate('start', '=', date('Y-m-d', strtotime('+1 day')))
          ->where('notified', '<>', '1')->get();
        foreach ($toNotify as $n) {
            $shift = Shift::findOrFail($n->id);
            $planid = $shift->plan()->get()[0]->id;
            if (!isset($done[$planid]))
                $done[$planid] = array();
            foreach ($shift->subscriptions()->get() as $sub) {
                if ($sub->notification) {
                    if (!isset($done[$planid][$sub->email])) {
                        $sub->sendReminder();
                        $done[$planid][$sub->email] = true;
                    }
                }
            }
            $shift->notified = true;
            $shift->save();
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
        if (!isset($data['allow_unsubscribe'])) {
          $data['allow_unsubscribe'] = false;
        }
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
