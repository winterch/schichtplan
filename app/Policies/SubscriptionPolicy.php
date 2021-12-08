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
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\Plan  $plan
     * @param  \App\Models\Subscription  $subscription
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(Plan $plan, Subscription $subscription)
    {
        // Read from the subscriptions from the session. During a session a user can update her/his own subscriptions
        $subscriptions = Session::get('subscriptions', []);
        return $plan == $subscription->shift->plan || in_array($subscription->id, $subscriptions);
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
        // Read from the subscriptions from the session. During a session a user can delete his/her own subscriptions
        $subscriptions = Session::get('subscriptions', []);
        return $plan == $subscription->shift->plan || in_array($subscription->id, $subscriptions);
    }
}
