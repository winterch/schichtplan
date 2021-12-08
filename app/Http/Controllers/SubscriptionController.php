<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSubscriptionRequest;
use App\Models\Plan;
use App\Models\Shift;
use App\Models\Subscription;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
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
        // no specific authorization - everybody with the link can create a subscription
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
        // A logged in user return to the plan.show route
        $user = Auth::user();
        if($user) {
            return redirect()->route('plan.show', ['plan' => $plan]);
        }
        return redirect()->route('plan.subscription.show', ['plan' => $plan]);
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
        // anonymous users owning the subscription (in the session) and the plan owners can update a subscription
        $this->checkAuthorize( $subscription);
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
        $this->checkAuthorize( $subscription);
        $data = $request->validated();
        $subscription->update($data);
        Session::flash('info', __('subscription.successfullyUpdated'));
        // A logged in user return to the plan.show route
        $user = Auth::user();
        if($user) {
            return redirect()->route('plan.show', ['plan' => $plan]);
        }
        return view('subscription.plan', ['plan' => $plan, 'subscriptions' => Session::get('subscriptions', [])]);
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
        $this->checkAuthorize($subscription);
        $subscription->forceDelete();
        // todo: check if this is working
        Session::forget('subscriptions.'.$subscription->id);
        Session::flash('info', __('subscription.successfullyDestroyed'));
        return view('subscription.plan', ['plan' => $plan, 'subscriptions' => Session::get('subscriptions', [])]);
    }

    /**
     * This will chek if an authorized user or an anonymous user are legit to modify/delete the subscription
     * @param Subscription $subscription
     */
    private function checkAuthorize(Subscription $subscription) {
        // check if a user if logged in
        $userPlan = Auth::user();
        Log::info($userPlan);
        if($userPlan) {
            // check if the plan belongs to the user
            if($userPlan->id === $subscription->shift->plan->id) {
                return;
            };
        }
        // check if the anonymous user owns the subscription
        $subscriptions = Session::get('subscriptions', []);
        if(in_array($subscription->id, $subscriptions)) {
            return;
        }
        abort(403);
    }
}
