<?php

namespace App\Policies;

use App\Models\Plan;
use Illuminate\Auth\Access\HandlesAuthorization;

class PlanPolicy
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
        return false;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\Plan  $plan
     * @param  \App\Models\Plan  $plan
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(Plan $user, Plan $plan)
    {
        return $user->id === $plan->id;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\Plan  $plan
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(Plan $plan)
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\Plan  $plan
     * @param  \App\Models\Plan  $plan
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(Plan $user, Plan $plan)
    {
        return $user->id === $plan->id;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\Plan  $plan
     * @param  \App\Models\Plan  $plan
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(Plan $user, Plan $plan)
    {
        return $user->id === $plan->id;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\Plan  $plan
     * @param  \App\Models\Plan  $plan
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(Plan $user, Plan $plan)
    {
        return $user->id === $plan->id;
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\Plan  $plan
     * @param  \App\Models\Plan  $plan
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(Plan $user, Plan $plan)
    {
        return $user->id === $plan->id;
    }
}
