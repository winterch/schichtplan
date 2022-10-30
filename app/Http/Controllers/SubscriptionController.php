<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSubscriptionRequest;
use App\Models\Plan;
use App\Models\Shift;
use App\Models\Subscription;
use Illuminate\Http\Response;
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
        // anonymous users can just add a shift for the authorized plan
        $this->authSubscriber($plan, $shift);
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
        // anonymous users can just add a shift for the authorized plan
        $this->authSubscriber($plan, $shift);
        // check if there are already enough subscriptions
        if($shift->team_size <= $shift->subscriptions()->count()) {
            Session::flash('fail', __('subscription.enoughSubscription'));
            return redirect()->route('plan.show', ['plan' => $plan]);
        }
        $data = $request->validated();
        $shift->subscriptions()->create($data);
        Session::flash('info', __('subscription.successfullyCreated'));
        return redirect()->route('plan.show', ['plan' => $plan]);
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
        $this->auth($plan);
        $this->authorize('update', $subscription);
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
        $this->auth($plan);
        $this->authorize('update', $subscription);
        $data = $request->validated();
        $subscription->update($data);
        Session::flash('info', __('subscription.successfullyUpdated'));
        return redirect()->route('plan.admin', ['plan' => $plan]);
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
        $this->auth($plan);
        $this->authorize('forceDelete', $subscription);
        $subscription->forceDelete();
        Session::flash('info', __('subscription.successfullyDestroyed'));
        return redirect()->route('plan.admin', ['plan' => $plan]);
    }
}
