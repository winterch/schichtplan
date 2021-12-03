<?php

namespace App\Policies;

use App\Models\Plan;
use App\Models\Shift;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ShiftPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param Plan $user
     * @param \App\Models\Plan $plan
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(Plan $user, Plan $plan)
    {
        die();
        Log::info($user->id);
        Log::info($plan->id);
        return $user->id === $plan->id;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\Plan  $plan
     * @param  \App\Models\Shift  $shift
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(Plan $plan, Shift $shift)
    {
        return $plan->id == $shift->plan()->id;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\Plan  $plan
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(Plan $user, Plan $plan)
    {
        return $user->id == $plan->id;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\Plan  $plan
     * @param  \App\Models\Shift  $shift
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(Plan $plan, Shift $shift)
    {
        return $plan->id == $shift->plan()->id;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\Plan  $plan
     * @param  \App\Models\Shift  $shift
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(Plan $plan, Shift $shift)
    {
        return $plan->id == $shift->plan()->id;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\Plan  $plan
     * @param  \App\Models\Shift  $shift
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(Plan $plan, Shift $shift)
    {
        return $plan->id == $shift->plan()->id;
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\Plan  $plan
     * @param  \App\Models\Shift  $shift
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(Plan $plan, Shift $shift)
    {
        return $plan->id == $shift->plan()->id;
    }
}
