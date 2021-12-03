<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreShiftRequest;
use App\Http\Requests\UpdateShiftRequest;
use App\Models\Plan;
use App\Models\Shift;
use Illuminate\Support\Facades\Session;

class ShiftController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param int $plan
     * @return \Illuminate\Http\Response
     */
    public function index(int $plan)
    {
        if ($plan) {
            $plan = Plan::find($plan);
        }
        return view('shift.index')->with(['plan' => $plan, 'bla' => "DEfv"]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(int $plan)
    {
        if ($plan) {
            $plan = Plan::find($plan);
        }

        return view('shift.create', ['plan' => $plan]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreShiftRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreShiftRequest $request, $plan)
    {
        $data = $request->validated();
        if ($plan) {
            $plan = Plan::find($plan);
        }
        $shift = $plan->shifts()->create($data);

        // $shift = Shift::create($data);
        Session::flash('info', 'Successfully created shift');
        return redirect()->route('plan.shift.index', ['plan' => $shift->plan]);
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
