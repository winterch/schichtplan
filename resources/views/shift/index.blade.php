@extends('layout.app')
@section('body')
    <h1 class="text-3xl mb-2">{{ $plan->title }}</h1>
    @include('partials.flash')
    <div class="text-lg italic">{{ $plan->description }}</div>
    <div class="text-lg">Public link <a href="{{route("plan.subscription.show", ['plan' => $plan])}}">{{route("plan.subscription.show", ['plan' => $plan])}}</a></div>
    <div class="text-lg">Admin link <a href="{{route("login", ['plan' => $plan])}}">{{route("login", ['plan' => $plan])}}</a></div>
    @if(count($plan->shifts) === 0)
        <div>{{__('shift.noshifts')}}</div>
    @else
        @foreach($plan->shifts as $index => $shift)
            {{--  Header of a new group --}}
            @if($loop->first || ($plan->shifts[$index - 1]->group !== $shift->group))
                <div class="p-4 bg-green-50 rounded mb-4">
                <table class="table-fixed w-full">
                    <thead>
                    <tr>
                        <th class="w-1/8 px-4 py-2">{{__('shift.type')}}</th>
                        <th class="w-1/8 px-4 py-2">{{__('shift.title')}}</th>
                        <th class="w-2/8 px-4 py-2">{{__('shift.description')}}</th>
                        <th class="w-1/8 px-4 py-2">{{__('shift.startDesc')}}</th>
                        <th class="w-1/8 px-4 py-2">{{__('shift.endDesc')}}</th>
                        <th class="w-1/8 px-4 py-2">{{__('shift.team_size')}}</th>
                        <th class="w-1/8 px-4 py-2">{{__('shift.action')}}</th>
                    </tr>
                    </thead>
                    <tbody>
            @endif
                    <tr>
                        <td class="border px-4 py-2 border-black">{{$shift->type}}</td>
                        <td class="border px-4 py-2 border-black">{{$shift->title}}</td>
                        <td class="border px-4 py-2 border-black">{{$shift->description}}</td>
                        <td class="border px-4 py-2 border-black">{{$shift->start}}</td>
                        <td class="border px-4 py-2 border-black">{{$shift->end}}</td>
                        <td class="border px-4 py-2 border-black">{{$shift->team_size}}</td>
                        <td class="border px-4 py-2 border-black">
                            <a href="{{route('plan.shift.edit', ['plan' => $shift->plan, 'shift'=> $shift])}}">{{__('shift.edit')}}</a>
                            <form method="post" action="{{route('plan.shift.destroy', ['plan' => $shift->plan, 'shift'=>$shift])}}">
                                @method('delete')
                                @csrf
                                <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                            </form>
                        </td>
                    </tr>
            {{--  Footer of a group--}}
            @if((isset($plan->shifts[$index + 1]) && $plan->shifts[$index + 1]->group !== $shift->group) || $loop->last)
                    </tbody>
                </table>
                </div>
            @endif
        @endforeach
    @endif
    <a class="bg-green-800 hover:bg-green-600 py-2 px-4 rounded mb-4 inline-block text-white font-bold align-middle" href="{{route('plan.shift.create', ['plan' => $plan])}}">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
        </svg>
        {{__('shift.add')}}
    </a>
    <a class="bg-green-800 hover:bg-green-600 py-2 px-4 rounded mb-4 inline-block text-white font-bold align-middle" href="{{route('plan.show', ['plan' => $plan])}}">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
        </svg>
        {{__('shift.manageSubscription')}}
    </a>
    <a class="bg-green-800 hover:bg-green-600 py-2 px-4 rounded mb-4 inline-block text-white font-bold align-middle" href="{{route('plan.edit', ['plan' => $plan])}}">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
        </svg>
        {{__('shift.editPlan')}}
    </a>
@endsection
