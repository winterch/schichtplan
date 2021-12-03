@extends('layout.app')
@section('body')
    <h2>{{ __('home.Shiftplan') }}</h2>

    <p>{{ __('home.shiftplanInfo') }}</p>

    <p>
        <a href="{{route('plan.create')}}">{{__('home.createPlan')}}</a><br>
    </p>

    <p>
        <i><?php __('home.deleteInfo') ?></i><br>
    </p>

    <div style="font-size:small; float:right;">
        immerda.ch - <a href= "https://code.immerda.ch/immerda/apps/schichtplan">src</a><br>
    </div><br>

    <div style="position:absolute;bottom:0;right:0;">
        <img src="{{URL::to('/assets/images/agplv3-88x31.png')}}" alt="{{__('home.AGPLlogo')}}">
    </div>

@endsection
