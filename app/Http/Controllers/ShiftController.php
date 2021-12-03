<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreShiftRequest;
use App\Http\Requests\UpdateShiftRequest;
use App\Models\Plan;
use App\Models\Shift;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class ShiftController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Plan $plan
     * @return \Illuminate\Http\Response
     */
    public function index(Plan $plan)
    {
        $this->authorize('view', $plan);
        return view('shift.index')->with(['plan' => $plan]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Plan $plan)
    {
        $this->authorize('update', $plan);
        return view('shift.create', ['plan' => $plan]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\StoreShiftRequest $request
     * @param int $plan
     * @return \Illuminate\Http\Response
     */
    public function store(StoreShiftRequest $request, Plan $plan)
    {
        $this->authorize('update', $plan);
        $data = $request->validated();
        $shift = $plan->shifts()->create($data);
        Session::flash('info', 'Successfully created shift');
        return redirect()->route('plan.shift.index', ['plan' => $plan]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Shift  $shift
     * @return \Illuminate\Http\Response
     */
    public function show(Shift $shift)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Shift  $shift
     * @return \Illuminate\Http\Response
     */
    public function edit(Shift $shift)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateShiftRequest  $request
     * @param  \App\Models\Shift  $shift
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateShiftRequest $request, Shift $shift)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Shift  $shift
     * @return \Illuminate\Http\Response
     */
    public function destroy(Shift $shift)
    {
        //
    }
}
