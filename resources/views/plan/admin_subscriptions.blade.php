@extends('layout.app')
@section('body')
    @include('partials.flash')

    <h1 class="text-3xl mb-2">{{ $plan->title }}</h1>

    <div class="py-4">
      {{ __('plan.admin_subscriptions_help') }}
    </div>

    @if(count($plan->shifts) > 0)
    <div class="py-4">
    <a class="my-button" href="{{route('plan.admin', ['plan' => $plan])}}">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
        </svg>
        {{__('plan.admin')}}
    </a>
    </div>
    @endif

    <br>
    @if(count($plan->shifts) === 0)
        <br>
        <div>{{__('shift.noshifts')}}</div>
        <br>
    @else
      <div class="flex flex-col">
        <div class="overflow-x-auto sm:-mx-6 lg:-mx-8">
          <div class="py-2 inline-block min-w-full sm:px-6 lg:px-8">
            <div class="">
              <table class="min-w-full">
        @php($x = 0)
        @foreach($plan->shifts as $index => $shift)
            {{--  Header of a new group --}}
            
              @if($loop->first || ($plan->shifts[$index - 1]->type !== $shift->type))
                @include('partials/shift_type_header')
              @endif
              
                <thead class="">
                  <tr>
                    <td colspan="6">
                      <div class="mt-10">
                        <span class="rounded font-bold p-3 bg-{{ ($shift->getSubscriptionsPercentageColor()) }}-100">
                          {{$shift->subscriptions->count()}} / {{$shift->team_size}}
                        </span>
                        <span class="font-bold ml-4">{{ $shift->title }}</span>
                        <span class="">({!! \App\Http\Controllers\PlanController::buildDateString($shift->start, $shift->end, true) !!})</span>
                      </div>
                    </td>
                  </tr>
                </thead>

                @if($shift->subscriptions->count() > 0)
                  <thead class="">
                    <tr>
                        <th class="text-sm px-0 py-4 pl-4 text-left">{{__('subscription.name')}}</th>
                        <th class="text-sm px-0 py-4 text-left">{{__('subscription.phone')}}</th>
                        <th class="text-sm px-0 py-4 text-left">{{__('subscription.email')}}</th>
                        <th class="text-sm px-0 py-4 text-left">{{__('subscription.comment')}}</th>
                        <th class="text-sm px-0 py-4 text-left" colspan="2">{{__('shift.action')}}</th>
                    </tr>
                  </thead>
                @endif
            
              <tbody>
              @foreach($shift->subscriptions as $subscription)
                      <tr class="bg-gray-{{ $loop->index % 2 == 0 ? '200' : '100' }} {{ $loop->last ? 'border-b-2 border-gray-300' : '' }}">

                        <td class="px-4">
                            {{$subscription->name}}
                        </td>
                        <td class="px-2">
                            {{$subscription->phone}}
                        </td>
                        <td class="px-2">
                            {{$subscription->email}}
                        </td>
                        <td class="px-2">
                            {{$subscription->comment}}
                        </td>
                        <td class="w-0">
                            <a href="{{route('plan.shift.subscription.edit',  ['plan' => $plan, 'shift' => $shift, 'subscription' => $subscription])}}" class="m-4 my-button">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                </svg></a>
                        </td>
                        <td class="w-0">
                            <form method="post" action="{{route('plan.shift.subscription.destroy', ['plan' => $plan, 'shift' => $shift, 'subscription' => $subscription])}}">
                                @method('delete')
                                @csrf
                                <button type="submit" class="m-4 ml-0 my-button">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </form>
                        </td>
                    </tr>
            @endforeach
        @endforeach
               </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    @endif

@endsection
