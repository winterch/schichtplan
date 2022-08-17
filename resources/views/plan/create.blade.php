@extends('layout.app')
@section('body')
        <h1 class="text-3xl mb-2">{{ __('plan.heading') }}</h1>
        @if(isset($plan->id) && $plan->id > 0)
            <form method="post" action="{{route('plan.update', ['plan' => $plan])}}">
                @method("put")
        @else
            <form method="post" action="{{route('plan.store')}}">
        @endif
            @csrf
            <div class="grid grid-rows-3 grid-flow-col gap-4">
                <div>
                    <label for="title" class="block text-gray-700 font-bold mb-1">{{__("plan.title")}}</label>
                    <input id="title" name="title" type="text" class="@error('title') border-red-500 @enderror w-full block text-black p-1 text-lg mb-2 border rounded" value="{{old('title', $plan->title)}}">
                    @error('title')
                    <div class="text-red-500 text-xs italic">{{ $message }}</div>
                    @enderror
                </div>
            <div class="row-span-2 flex flex-col">
                <label for="description" class="block text-gray-700 font-bold mb-1">{{__("plan.planDesc")}}</label>
                <textarea id="description" name="description" class="@error('description') border-red-500 @enderror w-full h-full block text-gray-700 p-1 mb-2 border">{{old('description', $plan->description)}}</textarea>
                @error('description')
                    <div class="text-red-500 text-xs italic">{{ $message }}</div>
                @enderror
            </div>
            <div>
                <label for="contact" class="block text-gray-700 font-bold mb-1">{{__("plan.contactDesc")}}</label>
                <input id="contact" name="contact" type="text" class="@error('contact') border-red-500 @enderror w-full block text-black p-1 text-lg mb-2 border rounded" value="{{old('contact', $plan->contact)}}">
                @error('contact')
                    <div class="text-red-500 text-xs italic">{{ $message }}</div>
                @enderror
            </div>
            <div>
                <label for="owner_email" class="block text-gray-700 font-bold mb-1">{{__("plan.mailDesc")}}</label>
                <input id="owner_email" name="owner_email" type="email" class="@error('owner_email') border-red-500 @enderror w-full block text-black p-1 text-lg mb-2 border rounded" value="{{old('owner_email', $plan->owner_email)}}">
                @error('owner_email')
                    <div class="text-red-500 text-xs italic">{{ $message }}</div>
                @enderror
            </div>
            </div>
            <button type="submit" class="bg-green-800 hover:bg-green-600 py-2 px-4 rounded mb-4 inline-block text-white font-bold">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                </svg>
                {{__('plan.save')}}
            </button>
        </form>
    </div>
@endsection
