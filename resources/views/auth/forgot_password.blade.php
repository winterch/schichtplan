@extends('layout.app')
@section('body')
    <div class="w-full h-full flex justify-center items-center ">
        <div class="md:w-1/2 lg:w-1/4 bg-green-50 p-4 rounded mt-4 md:mt-12 lg:mt-32">
            <h1 class="text-3xl mb-2">{{ __('auth.forgotPasswordHeader') }}</h1>
            <form method="post" action="{{route('password.email', ['plan' => $plan])}}">
                @csrf
                <div>
                    <label for="email" class="block text-gray-700 font-bold mb-1">{{__("auth.email")}}</label>
                    <input id="email" name="owner_email" type="text" class="@error('owner_email') border-red-500 @enderror w-full block text-black p-1 text-lg mb-2 border rounded" value="{{old('owner_email', $plan->email)}}">
                    @error('owner_email')
                    <div class="text-red-500 text-xs italic">{{ $message }}</div>
                    @enderror
                </div>
                <button type="submit" class="bg-green-800 hover:bg-green-600 py-2 px-4 rounded mb-4 inline-block text-white font-bold">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                    </svg>
                    {{__('auth.forgotPassword')}}
                </button>
            </form>
        </div>
    </div>
@endsection
