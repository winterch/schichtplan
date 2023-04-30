<?php

namespace App\Http\Controllers;

use App\Http\Requests\ImportPlanRequest;
use App\Http\Requests\StorePlanRequest;
use App\Http\Requests\RecoverPlanRequest;
use App\Http\Requests\StoreShiftRequest;
use App\Http\Requests\StoreSubscriptionRequest;
use App\Http\Requests\UpdatePlanRequest;
use App\Models\Plan;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Validator;

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
     * Import a plan from a csv file.
     * 
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Plan $plan
     */
    public function import(ImportPlanRequest $request, Plan $plan) {
        if(!$request->file('import')->isValid()) {
            return abort(500, "Can't upload the file");
        }
        if ($plan->id) {
            $this->auth($plan);
            $this->authorize('update', $plan);
        } else {
            $plan = new Plan;
        }
        $file = $request->file('import');
        $in = fopen($file->getRealPath(), 'r');
        // reset an existing plan if you update
        $plan->title = '';
        $plan->description = '';
        $plan->owner_email = '';
        $plan->save();
        $shift = null;
        $planData = [];
        // go over all lines and import the data
        while(($data = fgetcsv($in)) !== FALSE) {
            if (preg_match("/^shift$/", $data[0])) {
                // remove the identifier field
                array_shift($data);
                // we use empty fields to separte. Find the start of the data
                $key = 0;
                foreach($data as $key => $field) {
                    if(!empty($field)) {
                        break;
                    }
                }
                $d = [
                    "type" => $data[$key]  || '',
                    "title" => $data[$key+1],
                    "description" => $data[$key+2],
                    "start" => $data[$key+3],
                    "end" => $data[$key+4],
                    "team_size" => $data[$key+5],
                    "group" => 0,
                ];
                $validator = Validator::make($d, (new StoreShiftRequest())->rules(), (new StoreShiftRequest())->messages());
                $validData = $validator->validated();
                $shift = $plan->shifts()->create($validData);
            } else if (preg_match("/^subscribed$/", $data[0])) {
                // the csv is malformated. We first eed a shift, before we can have a subscriber
                if($shift === null) {
                    return abort(400, "Invalid csv input");
                }
                // remove the identifier field
                array_shift($data);
                // we use empty fields to separte. Find the start of the data
                $key = 0;
                foreach($data as $key => $field) {
                    if(!empty($field)) {
                        break;
                    }
                }
                $d = [
                    "name" => $data[$key],
                    "phone" => $data[$key+1],
                    "email" => $data[$key+2],
                    "comment" => $data[$key+3],
                    "notification" => $data[$key+4],
                ];
                $validator = Validator::make($d, (new StoreSubscriptionRequest())->rules(), (new StoreSubscriptionRequest())->messages());
                $validData = $validator->validated();
                $shift->subscriptions()->create($validData);
            } else {
                // guess the fields from the input!
                $planData[$data[0]] = $data[1];
            }
        }
        // Fill the plan and save it later
        if(count($planData) > 0 ) {
            $validator = Validator::make($planData, (new UpdatePlanRequest())->rules(), (new UpdatePlanRequest())->messages());
            $validData = $validator->validated();
            $plan->fill($validData);
        }

        // delete the file
        File::delete($file->getRealPath());
        $plan->save();
        return redirect()->route('plan.admin', ['plan' => $plan]);
    }

    /**
     * Exprt a plan
     * 
     * The format of the csv s for humans, and not primary for machines
     * We try to visually separate thigs, so people can use some excell-fu 
     * to update a plan.
     * 
     * @param \App\Models\Plan $plan
     */
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

        // export a plan in the csv format
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
     */
    public function cron(Request $request)
    {
        $cronKey = $request->get('key', '');
        $confKey = env('API_KEY', false);
        if(isset($confKey) && $cronKey === $confKey) {
            Artisan::call('schichtplan:cleanup');
            Artisan::call('schichtplan:notify-subscribers');
        } else {
            return abort(403);
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
