@extends('layout.app')
@section('body')
    <div class="container p-2 mr-auto ml-auto">
        <h2>{{ __('plan.heading') }}</h2>
        <form method="post" action="{{route('plan.store')}}">
                @csrf
                <label for="title" class="block text-gray-700 text-sm font-bold mb-2">{{__("plan.title")}}</label>
                <input id="title" name="title" type="text" class="@error('title') is-invalid @enderror block text-gray-700 text-sm font-bold mb-2 border" value="{{old('title')}}">
                @error('title')
                    <div class="text-red-500 text-xs italic">{{ $message }}</div>
                @enderror

                <label for="description" class="block text-gray-700 text-sm font-bold mb-2">{{__("plan.planDesc")}}</label>
                <input id="description" name="description" type="text" class="@error('description') is-invalid @enderror block text-gray-700 text-sm font-bold mb-2 border" value="{{old('description')}}">
                @error('description')
                    <div class="text-red-500 text-xs italic">{{ $message }}</div>
                @enderror

                <label for="contact" class="block text-gray-700 text-sm font-bold mb-2">{{__("plan.contactDesc")}}</label>
                <input id="contact" name="contact" type="text" class="@error('contact') is-invalid @enderror block text-gray-700 text-sm font-bold mb-2 border" value="{{old('contact')}}">
                @error('contact')
                    <div class="text-red-500 text-xs italic">{{ $message }}</div>
                @enderror

                <label for="owner_email" class="block text-gray-700 text-sm font-bold mb-2">{{__("plan.mailDesc")}}</label>
                <input id="owner_email" name="owner_email" type="email" class="@error('owner_email') is-invalid @enderror block text-gray-700 text-sm font-bold mb-2 border" value="{{old('owner_email')}}">
                @error('owner_email')
                    <div class="text-red-500 text-xs italic">{{ $message }}</div>
                @enderror

                <label for="password" class="block text-gray-700 text-sm font-bold mb-2">{{__("plan.passwordDesc")}}</label>
                <input id="password" name="password" type="password" class="@error('password') is-invalid @enderror block text-gray-700 text-sm font-bold mb-2 border">
                @error('password')
                    <div class="text-red-500 text-xs italic">{{ $message }}</div>
                @enderror

                <input type="submit" value="{{__('plan.save')}}">
        </form>
    </div>

@endsection
