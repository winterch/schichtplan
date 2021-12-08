@extends('layout.app')
@section('body')
    <h1 class="text-3xl mb-2">{{ __('shift.createHeading') }}</h1>

    @if(isset($shift->id) && $shift->id > 0)
        <form method="post" action="{{route('plan.shift.update', ['plan' => $plan, 'shift' => $shift])}}">
        @method("put")
    @else
        <form method="post" action="{{route('plan.shift.store', ['plan' => $plan])}}">
    @endif
        @csrf
        <div class="grid grid-rows-4 grid-flow-row gap-4 md:grid-flow-col">
            <div>
                <label for="type" class="block text-gray-700 font-bold mb-1">{{__("shift.type")}}</label>
                <input id="type" name="type" type="text" class="@error('type') border-red-500 @enderror w-full block text-black p-1 text-lg mb-2 border rounded" value="{{old('type', $shift->type)}}">
                @error('type')
                <div class="text-red-500 text-xs italic">{{ $message }}</div>
                @enderror
            </div>
            <div>
                <label for="title" class="block text-gray-700 font-bold mb-1">{{__("shift.title")}}</label>
                <input id="title" name="title" type="text" class="@error('title') border-red-500 @enderror w-full block text-black p-1 text-lg mb-2 border rounded" value="{{old('title', $shift->title)}}">
                @error('title')
                <div class="text-red-500 text-xs italic">{{ $message }}</div>
                @enderror
            </div>
            <div class="row-span-2 flex flex-col">
                <label for="description" class="block text-gray-700 font-bold mb-1">{{__("shift.description")}}</label>
                <textarea id="description" name="description" class="@error('description') border-red-500 @enderror w-full h-full block text-black p-1 text-lg mb-2 border rounded">{{old('description', $shift->description)}}</textarea>
                @error('description')
                <div class="text-red-500 text-xs italic">{{ $message }}</div>
                @enderror
            </div>
            <div>
                <label for="group" class="block text-gray-700 font-bold mb-1">{{__("shift.group")}}</label>
                <select id="group" name="group" class="@error('group') border-red-500 @enderror w-full block text-black p-1 text-lg mb-2 border rounded" >
                    @for($i = 0; $i <= $groups; $i++)
                        <option value="{{$i}}" @if($i == $shift->group) selected @endif>{{$i}}</option>
                    @endfor
                </select>
                @error('group')
                <div class="text-red-500 text-xs italic">{{ $message }}</div>
                @enderror
            </div>
            <div>
                <label for="start" class="block text-gray-700 font-bold mb-1">{{__("shift.startDesc")}}</label>
                <input id="start" name="start" type="date" class="@error('start') border-red-500 @enderror datepicker w-full block text-black p-1 text-lg mb-2 border rounded" value="{{old('start', $shift->start)}}">
                @error('start')
                <div class="text-red-500 text-xs italic">{{ $message }}</div>
                @enderror
            </div>
            <div>
                <label for="end" class="block text-gray-700 font-bold mb-1">{{__("shift.endDesc")}}</label>
                <input id="end" name="end" type="date" class="@error('end') border-red-500 @enderror datepicker w-full block text-black p-1 text-lg mb-2 border rounded" value="{{old('end', $shift->end)}}">
                @error('end')
                <div class="text-red-500 text-xs italic">{{ $message }}</div>
                @enderror
            </div>
            <div>
                <label for="team_size" class="block text-gray-700 font-bold mb-1">{{__("shift.team_sizeDesc")}}</label>
                <input id="team_size" name="team_size" type="number" class="@error('team_size') border-red-500 @enderror w-full block text-black p-1 text-lg mb-2 border rounded" value="{{old('team_size', $shift->team_size)}}">
                @error('team_size')
                <div class="text-red-500 text-xs italic">{{ $message }}</div>
                @enderror
            </div>
        </div>
        <button type="submit" class="bg-green-800 hover:bg-green-600 py-2 px-4 rounded mb-4 inline-block text-white font-bold">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
            </svg>
            {{__('shift.save')}}
        </button>
        <a class="bg-green-800 hover:bg-green-600 py-2 px-4 rounded mb-4 inline-block text-white font-bold" href="{{route('plan.shift.index', ['plan' => $plan])}}">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
            {{__('shift.cancel')}}
        </a>
    </form>

@endsection
