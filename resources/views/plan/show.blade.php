@extends('layout.app')
@section('body')
    @include('partials.flash')
    @include('partials.plan_title')
    <br>
    @if(count($plan->shifts) === 0)
        <div>{{__('shift.noshifts')}}</div>
    @else
        @foreach($plan->shifts as $index => $shift)
            {{--  Header of a new group --}}
            @if($loop->first || ($plan->shifts[$index - 1]->group !== $shift->group))
             <div class="flex flex-col">
               <div class="overflow-x-auto sm:-mx-6 lg:-mx-8">
                 <div class="py-2 inline-block min-w-full sm:px-6 lg:px-8">
                   <div class="overflow-x-auto">
                     <table class="min-w-full">
                       <thead class="bg-green-50">
                        <tr>
                            <th scope="col" class="text-sm px-2 py-4 text-left">{{__('shift.type')}}</th>
                            <th scope="col" class="text-sm px-4 py-4 text-left">{{__('shift.title')}}</th>
                            <th scope="col" class="text-sm max-w-sm px-4 py-4 text-left">{{__('shift.description')}}</th>
                            <th scope="col" class="text-sm px-6 py-4 text-left">{{__('shift.startDesc')}}</th>
                            <th scope="col" class="text-sm px-4 py-4 text-left">{{__('shift.endDesc')}}</th>
                            <th scope="col" class="text-sm px-4 py-4 text-left">{{__('shift.action')}}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @endif
                        <tr class="border-b">
                            <td class="text-sm px-0 py-4">{{$shift->type}}</td>
                            <td class="text-sm px-4 py-4">{{$shift->title}}</td>
                            <td class="text-sm max-w-xs px-6 py-4">{{$shift->description}}</td>
                            <td class="text-sm px-4 py-4">{{$shift->start}}</td>
                            <td class="text-sm px-4 py-4">{{$shift->end}}</td>
                            <td class="text-sm px-4 py-4">
                                @if ($shift->team_size > $shift->subscriptions->count())
                                  <a href="{{route('plan.subscription.create', ['plan' => $shift->plan->view_id, 'shift'=> $shift])}}" class="w-32 bg-green-800 hover:bg-green-600 py-2 px-2 rounded mb-1 inline-block text-white text-sm font-bold">
                                      <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline" viewBox="0 0 20 20" fill="currentColor">
                                          <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM6.293 6.707a1 1 0 010-1.414l3-3a1 1 0 011.414 0l3 3a1 1 0 01-1.414 1.414L11 5.414V13a1 1 0 11-2 0V5.414L7.707 6.707a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                      </svg>
                                      {{__('plan.subscribe')}}
                                  </a>
                                @endif

                                {{ $shift->subscriptions->count() }}&nbsp;/&nbsp;{{ $shift->team_size }}
                            </td>
                        </tr>
                        {{--  Footer of a group--}}
                        @if((isset($plan->shifts[$index + 1]) && $plan->shifts[$index + 1]->group !== $shift->group) || $loop->last)
                       </tbody>
                     </table>
                   </div>
                 </div>
               </div>
             </div>
            @endif
        @endforeach
    @endif
@endsection
