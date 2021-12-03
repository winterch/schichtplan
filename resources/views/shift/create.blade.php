@extends('layout.app')
@section('body')
    <div class="container p-2 mr-auto ml-auto">
        <h2>{{ __('shift.createHeading') }}</h2>
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="post" action="{{route('plan.shift.store', ['plan' => $plan])}}">
            @csrf
            <label for="type" class="block text-gray-700 text-sm font-bold mb-2">{{__("shift.type")}}</label>
            <input id="type" name="type" type="text" class="@error('type') is-invalid @enderror block text-gray-700 text-sm font-bold mb-2 border" value="{{old('type')}}">
            @error('type')
            <div class="text-red-500 text-xs italic">{{ $message }}</div>
            @enderror

            <label for="title" class="block text-gray-700 text-sm font-bold mb-2">{{__("shift.title")}}</label>
            <input id="title" name="title" type="text" class="@error('title') is-invalid @enderror block text-gray-700 text-sm font-bold mb-2 border" value="{{old('title')}}">
            @error('title')
            <div class="text-red-500 text-xs italic">{{ $message }}</div>
            @enderror

            <label for="description" class="block text-gray-700 text-sm font-bold mb-2">{{__("shift.description")}}</label>
            <textarea id="description" name="description" class="@error('description') is-invalid @enderror block text-gray-700 text-sm font-bold mb-2 border">{{old('description')}}</textarea>
            @error('description')
            <div class="text-red-500 text-xs italic">{{ $message }}</div>
            @enderror

            <label for="group" class="block text-gray-700 text-sm font-bold mb-2">{{__("shift.group")}}</label>
            <select id="group" name="group" class="@error('group') is-invalid @enderror block text-gray-700 text-sm font-bold mb-2 border">{
                <option value="1">1</option>
            </select>
            @error('group')
            <div class="text-red-500 text-xs italic">{{ $message }}</div>
            @enderror


            <label for="start" class="block text-gray-700 text-sm font-bold mb-2">{{__("shift.startDesc")}}</label>
            <input id="start" name="start" type="date" class="@error('start') is-invalid @enderror block text-gray-700 text-sm font-bold mb-2 border" value="{{old('start')}}">
            @error('start')
            <div class="text-red-500 text-xs italic">{{ $message }}</div>
            @enderror

            <label for="end" class="block text-gray-700 text-sm font-bold mb-2">{{__("shift.endDesc")}}</label>
            <input id="end" name="end" type="date" class="@error('end') is-invalid @enderror block text-gray-700 text-sm font-bold mb-2 border" value="{{old('end')}}">
            @error('end')
            <div class="text-red-500 text-xs italic">{{ $message }}</div>
            @enderror

            <label for="team_size" class="block text-gray-700 text-sm font-bold mb-2">{{__("shift.team_sizeDesc")}}</label>
            <input id="team_size" name="team_size" type="number" class="@error('team_size') is-invalid @enderror block text-gray-700 text-sm font-bold mb-2 border" value="{{old('team_size')}}">
            @error('team_size')
            <div class="text-red-500 text-xs italic">{{ $message }}</div>
            @enderror


            <input type="submit" value="{{__('shift.save')}}">
        </form>
    </div>

@endsection
