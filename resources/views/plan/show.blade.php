@extends('layout.app')
@section('body')
    @include('partials.flash')
    @include('partials.plan_title')
    <br>
    @if(count($plan->shifts) === 0)
      <div>{{__('shift.noshifts')}}</div>
    @else
      <style>
        @media (min-width: 640px) {
          table {
            display: inline-table !important;
          }
          .table-header {
            display: table-header-group !important;
          }
          tbody tr:nth-child(even), thead tr {
            background-color: #eee;
          }
        }
        @media (max-width: 639px) {
          .shift-entry-desc {
            display: inline !important;
            font-weight: bold;
          }
          tbody tr:nth-child(odd) {
            background-color: #eee;
          }
          tbody tr:nth-child(even) {
            background-color: #bbb;
          }
        }
      </style>
      <div class="flex items-center justify-center">
        <div class="container">
          @php
            $types = [];
            foreach ($plan->shifts as $shift) {
              $types[$shift->type] = 1;
            }
          @endphp
          @foreach($types as $type => $unused)
            @if($type !== "")
              <div class="m-10 flex flex-row font-bold rounded bg-green-100 p-5">{{ $type }}</div>
            @endif
            <table class="w-full flex flex-row flex-no-wrap sm:bg-white rounded-lg overflow-hidden sm:shadow-lg my-5">
              <thead class="flex-1 sm:flex-none table-header" style="display:none">
                <tr class="flex flex-col flex-no wrap sm:table-row rounded-l-lg sm:rounded-none mb-2 sm:mb-0">
                    <th class="p-3 text-left">{{__('shift.title')}}</th>
                    <th class="p-3 text-left">{{__('shift.description')}}</th>
                    <th class="p-3 text-left">{{__('shift.startDesc')}}</th>
                    <th class="p-3 text-left">&nbsp;</th>
                    <th class="p-3 text-left">{{__('shift.action')}}&nbsp;</th>
                </tr>
              </thead>
              <tbody class="flex-1 sm:flex-none">
                @foreach($plan->shifts as $index => $shift)
                  @continue ($shift->type != $type)
                  <tr class="shift-entry flex flex-col flex-no wrap sm:table-row sm:mb-0">
                    <td class="p-3">
                      <div class="shift-entry-desc" style="display:none">{{__('shift.title')}}</div>
                      {{$shift->title}}
                    </td>
                    <td class="p-3">
                      <div class="shift-entry-desc" style="display:none">{{__('shift.description')}}</div>
                      {{$shift->description}}
                    </td>
                    <td class="p-3">
                      <div class="shift-entry-desc" style="display:none">{{__('shift.startDesc')}}</div>
                      {!! \App\Http\Controllers\PlanController::buildDateString($shift->start, $shift->end) !!}
                    </td>
                    <td class="p-3">
                              {{ $shift->subscriptions->count() }}&nbsp;/&nbsp;{{ $shift->team_size }}
                              @if($shift->subscriptions->count() > 0)
                                :
                              @endif
                              <i>
                              @foreach($shift->subscriptions as $subscription)
                                {{$subscription->name}}@if($subscription != $shift->subscriptions->last()), @endif
                              @endforeach
                              </i>
                    </td>
                    <td class="pt-4 p-3 text-center">
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
                  </tr>
                @endforeach
              </tbody>
            </table>
          @endforeach
        </div>
      </div>
    @endif
    <div class="py-20">
    <a href="{{route('plan.recover', [$plan->view_id])}}">{{__('plan.show_subscriptions')}}</a>
    </div>
@endsection
