@extends('layout.app')
@section('body')
    <h1 class="text-3xl mb-2">{{ $plan->title }}</h1>
    <div class="text-lg italic">{{ $plan->description }}</div>
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
                        <tr class="align-top">
                            <td class="border px-4 py-2 border-black">{{$shift->type}}</td>
                            <td class="border px-4 py-2 border-black">{{$shift->title}}</td>
                            <td class="border px-4 py-2 border-black">{{$shift->description}}</td>
                            <td class="border px-4 py-2 border-black">{{$shift->start}}</td>
                            <td class="border px-4 py-2 border-black">{{$shift->end}}</td>
                            <td class="border px-4 py-2 border-black">{{$shift->team_size}}</td>
                            <td class="border px-4 py-2 border-black">
                                @foreach($shift->subscriptions as $subscription)
                                    <div>
                                        <span class="font-bold">{{$subscription->name}}</span>

                                        @if(in_array($subscription->id, $subscriptions))
                                            <a href="{{route('plan.shift.subscription.edit',  ['plan' => $plan, 'shift' => $shift, 'subscription' => $subscription])}}">Edit</a>
                                            <form method="post" action="{{route('plan.shift.subscription.destroy', ['plan' => $plan, 'shift' => $shift, 'subscription' => $subscription])}}">
                                                @method('delete')
                                                @csrf
                                                <button type="submit" class="btn btn-danger btn-sm">{{__('subscription.unsubscribe')}}</button>
                                            </form>
                                        @endif
                                    </div>
                                @endforeach
                                @for($i = 0; $i < ($shift->team_size - $shift->subscriptions->count()); $i++)
                                    <a href="{{route('plan.subscription.create', ['plan' => $shift->plan, 'shift'=> $shift])}}">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM6.293 6.707a1 1 0 010-1.414l3-3a1 1 0 011.414 0l3 3a1 1 0 01-1.414 1.414L11 5.414V13a1 1 0 11-2 0V5.414L7.707 6.707a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                        </svg>
                                        {{__('subscription.subscribe')}}</a>
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
@endsection
