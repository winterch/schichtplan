@extends('layout.app')
@section('body')
    @include('partials.flash')
    @include('partials.plan_title')
    <br>
    @if(count($plan->shifts) === 0)
        <div>{{__('shift.noshifts')}}</div>
    @else
    <div class="flex flex-col">
      <div class="overflow-x-auto sm:-mx-6 lg:-mx-8">
        <div class="py-2 inline-block min-w-full sm:px-6 lg:px-8">
          <div class="overflow-x-auto">
          <table class="min-w-full table-stripe">

        @foreach($plan->shifts as $index => $shift)
            {{--  Header of a new group --}}
            @if($loop->first || ($plan->shifts[$index - 1]->type !== $shift->type))
              @include('partials/shift_type_header')

                <thead class="">
                <tr class="border-b">
                    <th scope="col" class="text-sm p-4 text-left" style="width: 140px;">{{__('shift.action')}}</th>
                    <th scope="col" class="text-sm p-4 text-left" style="width: 300px;">{{__('shift.title')}}</th>
                    <th scope="col" class="text-sm max-w-sm p-4 text-left" style="min-width: 250px;">{{__('shift.description')}}</th>
                    <th scope="col" class="text-sm p-4" style="width: 220px;">{{__('shift.durationDesc')}}</th>
                    <th scope="col" class="text-sm p-4 text-left" style="width: 180px;">{{__('shift.subscriptionsDesc')}}</th>
                </tr>
                </thead>
                <tbody>
                @endif
                @if ($loop->index % 2 == 0)
                  <tr class="bg-gray-200">
                @else
                  <tr class="bg-gray-100">
                @endif
                <td style="border-radius: 10px 0 0 10px" class="text-sm px-4 py-4 align-top">
                        @if ($shift->team_size > $shift->subscriptions->count())
                          <a href="{{route('plan.subscription.create', ['plan' => $shift->plan->view_id, 'shift'=> $shift])}}" class="my-button whitespace-nowrap">
                              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline" viewBox="0 0 20 20" fill="currentColor">
                                  <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM6.293 6.707a1 1 0 010-1.414l3-3a1 1 0 011.414 0l3 3a1 1 0 01-1.414 1.414L11 5.414V13a1 1 0 11-2 0V5.414L7.707 6.707a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                              </svg>
                              {{__('plan.subscribe')}}
                          </a>
                        @endif
                        @if ($plan->allow_unsubscribe && strtotime($shift->start) > strtotime('+2 day') && $shift->subscriptions->count() > 0)
                          <a href="{{route('plan.subscription.remove', ['plan' => $shift->plan->view_id, 'shift'=> $shift])}}" class="my-button">
                              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline" viewBox="0 0 20 20" fill="currentColor">
                                  <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM6.293 6.707a1 1 0 010-1.414l3-3a1 1 0 011.414 0l3 3a1 1 0 01-1.414 1.414L11 5.414V13a1 1 0 11-2 0V5.414L7.707 6.707a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                              </svg>
                              {{__('subscription.unsubscribe')}}
                          </a>
                        @endif
                    </td>
                    <td  class="text-sm p-4 align-top font-bold">{{$shift->title}}</td>
                    <td class="text-sm max-w-xs p-4 align-top" >{{$shift->description}}</td>
                    <td class="text-sm text-center p-4 whitespace-nowrap align-top">{!! \App\Http\Controllers\PlanController::buildDateString($shift->start, $shift->end) !!}</td>
                    <td class="text-sm p-4 align-top" style="border-radius: 0 10px 10px 0;">
                        {{ $shift->subscriptions->count() }}&nbsp;/&nbsp;{{ $shift->team_size }}
                        <br>
                        
                        @foreach($shift->subscriptions as $subscription)
                          <i>{{$subscription->name}}</i>
                          @if($subscription != $shift->subscriptions->last())<br> @endif
                        @endforeach
                        
                    </td>
                </tr>
                {{--  Footer of a group--}}
                @if((isset($plan->shifts[$index + 1]) && $plan->shifts[$index + 1]->group !== $shift->group) || $loop->last)
                </tbody>
              </table>
            @endif
        @endforeach
        </div>
      </div>
    </div>
  </div>

    @endif
    <div class="py-20">
    <a href="{{route('plan.recover', [$plan->view_id])}}">{{__('plan.show_subscriptions')}}</a>
    </div>
@endsection
