@extends('layout.app')
@section('body')
    <h1 class="text-3xl mb-2">{{ $plan->title }}</h1>
    <div class="text-lg italic">{{ $plan->description }}</div>
    @if(count($plan->shifts) === 0)
        <div>{{__('shift.noshifts')}}</div>
    @else
        <ul>
        @foreach($plan->shifts as $shift)
            <li>{{$shift->title}}</li>
        @endforeach
        </ul>
    @endif
    <a class="bg-green-800 hover:bg-green-600 py-2 px-4 rounded mb-4 inline-block text-white font-bold align-middle" href="{{route('plan.shift.create', ['plan' => $plan])}}">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
        </svg>
        {{__('shift.add')}}</a>
@endsection
