<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSubscriptionRequest;
use App\Models\Plan;
use App\Models\Shift;
use App\Models\Subscription;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class SubscriptionController extends Controller
{

    /**
     * Show the form for creating a new subscription.
     *
     * @param Plan $plan
     * @param Shift $shift
     * @return Response
     */
    public function create(Plan $plan, Shift $shift)   {
        $subscription = new Subscription();
        return view('subscription.create', ['plan' => $plan, 'shift' => $shift, 'subscription' => $subscription]);
    }

    /**
     * Store a newly created subscription in storage.
     *
     * @param StoreSubscriptionRequest $request
     * @param Plan $plan
     * @param Shift $shift
     * @return Response
     */
    public function store(StoreSubscriptionRequest $request, Plan $plan, Shift $shift)
    {
        // no specific authorization - everybody with the link can create a subscription
        $data = $request->validated();
        $subscription = $shift->subscriptions()->create($data);
        // An anonymous user can edit her/his subscription as long as he/she use the same session
        // For that reason we save those subscription in a session
        Session::push('subscriptions', $subscription->id);
        Session::flash('info', __('subscription.successfullyCreated'));
        return redirect()->route('plan.show', ['plan' => $plan->view_id]);
    }

    /**
     * Display the specified resource.
     * Plans are identified by a unique_link. This is the shared secrete between users and owners of the plan
     *
     * @param Plan $plan
     * @return Response
     */
    public function show(Plan $plan)
    {
        // no specific authorization - everybody with the link can create a subscription
        return view('subscription.plan', ['plan' => $plan, 'subscriptions' => Session::get('subscriptions', [])]);
    }

    /**
     * Show the form for editing the specified subscription.
     *
     * @param Plan $plan
     * @param Shift $shift
     * @param Subscription $subscription
     * @return Response
     */
    public function edit(Plan $plan, Shift $shift, Subscription $subscription)
    {
        return view('subscription.create', ['plan' => $plan, 'shift' => $shift, 'subscription' => $subscription]);
    }

    /**
     * Update the specified subscription in storage.
     *
     * @param StoreSubscriptionRequest $request
     * @param Plan $plan
     * @param Shift $shift
     * @param Subscription $subscription
     * @return Response
     */
    public function update(StoreSubscriptionRequest $request, Plan $plan, Shift $shift, Subscription $subscription)
    {
        $data = $request->validated();
        $subscription->update($data);
        Session::flash('info', __('subscription.successfullyUpdated'));
        return redirect()->route('plan.shift.index', ['plan' => $plan]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Plan $plan
     * @param Shift $shift
     * @param Subscription $subscription
     * @return Response
     */
    public function destroy(Plan $plan, Shift $shift, Subscription $subscription)
    {
        $subscription->forceDelete();
        // todo: check if this is working
        Session::forget('subscriptions.'.$subscription->id);
        Session::flash('info', __('subscription.successfullyDestroyed'));
        return redirect()->route('plan.shift.index', ['plan' => $plan]);
    }
}
