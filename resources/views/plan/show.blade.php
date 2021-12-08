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
                                                    <a href="{{route('plan.shift.subscription.edit',  ['plan' => $plan, 'shift' => $shift, 'subscription' => $subscription])}}" class="bg-green-800 hover:bg-green-600 py-2 px-2 w-32 rounded mb-1 inline-block text-white text-sm font-bold">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline" viewBox="0 0 20 20" fill="currentColor">
                                                            <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                                        </svg>
                                                        {{__('plan.editShift')}}</a>
                                                    <form method="post" action="{{route('plan.shift.subscription.destroy', ['plan' => $plan, 'shift' => $shift, 'subscription' => $subscription])}}">
                                                        @method('delete')
                                                        @csrf
                                                        <button type="submit" class="w-32 bg-green-800 hover:bg-green-600 py-2 px-2 rounded mb-1 inline-block text-white text-sm font-bold">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline" viewBox="0 0 20 20" fill="currentColor">
                                                                <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                                            </svg>
                                                            {{__('plan.unsubscribe')}}
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                @endforeach
                                @for($i = 0; $i < ($shift->team_size - $shift->subscriptions->count()); $i++)
                                        <div class="flex justify-end px-2 pt-2 mt-2 border border-black">
                                            <a href="{{route('plan.subscription.create', ['plan' => $shift->plan, 'shift'=> $shift])}}" class="w-32 bg-green-800 hover:bg-green-600 py-2 px-2 rounded mb-1 inline-block text-white text-sm font-bold">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM6.293 6.707a1 1 0 010-1.414l3-3a1 1 0 011.414 0l3 3a1 1 0 01-1.414 1.414L11 5.414V13a1 1 0 11-2 0V5.414L7.707 6.707a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                                </svg>
                                                {{__('plan.subscribe')}}
                                            </a>
                                        </div>
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
