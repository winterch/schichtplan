<?php

namespace App\Policies;

use App\Models\Plan;
use App\Models\Subscription;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class SubscriptionPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\Plan  $plan
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(Plan $plan)
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\Plan  $plan
     * @param  \App\Models\Subscription  $subscription
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(Plan $plan, Subscription $subscription)
    {
        //
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\Plan  $plan
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(Plan $plan)
    {
        die;
        return true;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\Plan  $plan
     * @param  \App\Models\Subscription  $subscription
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(Plan $plan, Subscription $subscription)
    {
        // Read from the session if a user can update a session
        $subscriptions = Session::get('subscriptions', []);
        Log::warning("Subscriptions");
        Log::warning($subscriptions);
        return $plan == $subscription->shift->plan || in_array($subscription->id, $subscriptions);
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\Plan  $plan
     * @param  \App\Models\Subscription  $subscription
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(Plan $plan, Subscription $subscription)
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\Plan  $plan
     * @param  \App\Models\Subscription  $subscription
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(Plan $plan, Subscription $subscription)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\Plan  $plan
     * @param  \App\Models\Subscription  $subscription
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(Plan $plan, Subscription $subscription)
    {
        //
    }
}
