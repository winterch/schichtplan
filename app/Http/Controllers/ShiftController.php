<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreShiftRequest;
use App\Http\Requests\UpdateShiftRequest;
use App\Models\Plan;
use App\Models\Shift;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Session;

class ShiftController extends Controller
{
    /**
     * Show the form for creating a new shift.
     *
     * @return Response
     */
    public function create(Plan $plan)
    {
        $this->auth($plan);
        // user needs access to plan to create a new shift
        $groups = $this->getGroups($plan);
        $shift = new Shift();
        return view('shift.create', ['plan' => $plan, 'shift' => $shift, 'groups' => $groups]);
    }

    /**
     * Store a newly created shift in storage.
     *
     * @param StoreShiftRequest $request
     * @param Plan $plan
     * @return Response
     */
    public function store(StoreShiftRequest $request, Plan $plan)
    {
        $this->auth($plan);
        $data = $request->validated();
        $plan->shifts()->create($data);
        Session::flash('info', __('shift.successfullyCreated'));
        return redirect()->route('plan.admin', ['plan' => $plan]);
    }

    /**
     * Show the form for editing a shift.
     *
     * @param Plan $plan
     * @param Shift $shift
     * @return Response
     */
    public function edit(Plan $plan, Shift $shift)
    {
        $this->auth($plan);
        $groups = $this->getGroups($plan);
        return view('shift.create', ['shift' => $shift, 'plan' => $plan, 'groups' => $groups]);
    }

    /**
     * Update the a shift in storage.
     *
     * @param StoreShiftRequest $request
     * @param Plan $plan
     * @param Shift $shift
     * @return Response
     */
    public function update(StoreShiftRequest $request, Plan $plan, Shift $shift)
    {
        $this->auth($plan);
        $data = $request->validated();
        $shift->update($data);
        Session::flash('info', __('shift.successfullyUpdated'));
        return redirect()->route('plan.admin', ['plan' => $plan]);

    }

    /**
     * Remove the specified shift from storage.
     *
     * @param Shift $shift
     * @return Response
     */
    public function destroy(Plan $plan, Shift $shift)
    {
        $this->auth($plan);
        $shift->forceDelete();
        Session::flash('info', __('shift.successfullyDestroyed'));
        return redirect()->route('plan.admin', ['plan' => $plan]);
    }

    /**
     * Return the number of shift groups present for the plan
     * A group is an addition order option for shifts
     * @param Plan $plan
     * @return int
     */
    private function getGroups(Plan $plan): int
    {
        return Shift::select('group')
            ->distinct()
            ->whereBelongsTo($plan)
            ->get()
            ->count();
    }
}
