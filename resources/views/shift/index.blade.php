@extends('layout.app')
@section('body')
    <div class="container p-2 mr-auto ml-auto">
        <h2>{{ $plan->title }}</h2>
        <div>{{ $plan->description }}</div>
        @foreach($plan->shifts() as $shift)
            {{$shift->title}}
        @endforeach
        @if(empty($plan->shifts()))
            <div>{{__('shift.noshifts')}}</div>
        @endif
        <a class="" href="{{route('plan.shift.create', ['plan' => $plan])}}">{{__('shift.add')}}</a>
    </div>
@endsection
