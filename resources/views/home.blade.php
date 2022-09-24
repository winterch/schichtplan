@extends('layout.app')
@section('body')
    <h1 class="text-3xl mb-2">{{ __('home.Shiftplan') }}</h1>
    @include('partials.flash')

    <p class="block mb-4">{{ __('home.shiftplanInfo') }}</p>

    <a href="{{route('plan.create')}}" class="bg-green-800 hover:bg-green-600 py-2 px-4 rounded mb-4 inline-block text-white font-bold sm:w-auto">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
        </svg>
        {{__('home.createPlan')}}
    </a>

    <p class="italic">{{__('home.deleteInfo') }}</p>

    <div class="py-4">
    <a href="{{route('plan.recover')}}">{{__('plan.recover')}}</a>
    </div>

    <div class="flex md:justify-end md:text-sm text-xs justify-start">
        {!! __('home.copyleft') !!}
    </div>
@endsection
