<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSubscriptionRequest;
use App\Models\Plan;
use App\Models\Shift;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class SubscriptionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param Plan $plan
     * @param Shift $shift
     * @return \Illuminate\Http\Response
     */
    public function create(Plan $plan, Shift $shift)   {
        $subscription = new Subscription();
        return view('subscription.create', ['plan' => $plan, 'shift' => $shift, 'subscription' => $subscription]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreSubscriptionRequest $request
     * @param Plan $plan
     * @param Shift $shift
     * @return \Illuminate\Http\Response
     */
    public function store(StoreSubscriptionRequest $request, Plan $plan, Shift $shift)
    {
        $data = $request->validated();
        $subscription = $shift->subscriptions()->create($data);
        // a anonymous user can edit a subscription as long as he/she use the same session
        Session::push('subscriptions', $subscription->id);
        Session::flash('message', __('subscription.successfullyCreated'));
        return redirect()->route('plan.subscription.show', ['plan' => $plan]);

    }

    /**
     * Display the specified resource.
     * Plans are identified by a unique_link. If a user nows the unique_link she/he can access the subscription page
     *
     * @param Plan $plan
     * @return \Illuminate\Http\Response
     */
    public function show(Plan $plan)
    {
        $subscriptions = Session::get('subscriptions', []);
        return view('subscription.plan', ['plan' => $plan, 'subscriptions' => $subscriptions]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Plan $plan
     * @param Shift $shift
     * @param Subscription $subscription
     * @return \Illuminate\Http\Response
     */
    public function edit(Plan $plan, Shift $shift, Subscription $subscription)
    {
        // anonymous users owning the subscription and plan owners can update
        Log::info($subscription);
        $this->authorize('update', $subscription);
        return view('subscription.create', ['plan' => $plan, 'shift' => $shift, 'subscription' => $subscription]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param Plan $plan
     * @param Shift $shift
     * @param Subscription $subscription
     * @return \Illuminate\Http\Response
     */
    public function update(StoreSubscriptionRequest $request, Plan $plan, Shift $shift, Subscription $subscription)
    {
        $this->authorize('update', $subscription);
        $data = $request->validated();
        $subscription->update($data);
        return view('subscription.plan', ['plan' => $plan, 'subscriptions' => Session::get('subscriptions', [])]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Plan $plan
     * @param Shift $shift
     * @return \Illuminate\Http\Response
     */
    public function destroy(Plan $plan, Shift $shift, Subscription $subscription)
    {
        $subscription->forceDelete();
        // todo: check if this is working
        Session::forget('subscriptions.'.$subscription->id);
        return view('subscription.plan', ['plan' => $plan, 'subscriptions' => Session::get('subscriptions', [])]);
    }
}
