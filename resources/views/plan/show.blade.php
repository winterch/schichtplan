@extends('layout.app')
@section('body')
    <h1 class="text-3xl mb-2">{{ $plan->title }}</h1>
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
                            <th class="w-1/10 px-4 py-2">{{__('shift.type')}}</th>
                            <th class="w-1/10 px-4 py-2">{{__('shift.title')}}</th>
                            <th class="w-1/10 px-4 py-2">{{__('shift.description')}}</th>
                            <th class="w-1/10 px-4 py-2">{{__('shift.startDesc')}}</th>
                            <th class="w-1/10 px-4 py-2">{{__('shift.endDesc')}}</th>
                            <th class="w-1/2 px-4 py-2">{{__('shift.action')}}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @endif
                        <tr class="align-top">
                            <td class="border px-4 py-2 border-black">{{$shift->type}}</td>
                            <td class="border px-4 py-2 border-black">{{$shift->title}}</td>
                            <td class="border px-4 py-2 border-black">{{$shift->description}}</td>
                            <td class="border px-4 py-2 border-black">{{$shift->start}}</td>
                            <td class="border px-4 py-2 border-black">{{$shift->end}}</td>
                            <td class="border px-4 py-2 border-black">
                                @foreach($shift->subscriptions as $subscription)
                                    <div>
                                        <table class="table-auto w-full">
                                            <tr class="align-top">
                                                <td class="border border-black px-2">
                                                    {{$subscription->name}}
                                                </td>
                                                <td class="border border-black px-2">
                                                    {{$subscription->phone}}
                                                </td>
                                                <td class="border border-black px-2">
                                                    {{$subscription->email}}
                                                </td>
                                                <td class="border border-black px-2">
                                                    {{$subscription->comment}}
                                                </td>
                                                <td class="border border-black px-2">
                                                    <a href="{{route('plan.shift.subscription.edit',  ['plan' => $plan, 'shift' => $shift, 'subscription' => $subscription])}}">Edit</a>
                                                    <form method="post" action="{{route('plan.shift.subscription.destroy', ['plan' => $plan, 'shift' => $shift, 'subscription' => $subscription])}}">
                                                        @method('delete')
                                                        @csrf
                                                        <button type="submit" class="btn btn-danger btn-sm">{{__('plan.unsubscribe')}}</button>
                                                    </form>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                @endforeach
                                @for($i = 0; $i < ($shift->team_size - $shift->subscriptions->count()); $i++)
                                        <div><a href="{{route('plan.subscription.create', ['plan' => $shift->plan, 'shift'=> $shift])}}">{{__('plan.subscribe')}}</a></div>
                                @endfor
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
    <a class="bg-green-800 hover:bg-green-600 py-2 px-4 rounded mb-4 inline-block text-white font-bold align-middle" href="{{route('plan.shift.index', ['plan' => $plan])}}">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
        </svg>
        {{__('plan.editShifts')}}
    </a>

@endsection
