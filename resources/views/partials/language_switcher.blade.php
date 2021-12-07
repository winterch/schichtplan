<div class="flex justify-center pt-8 md:justify-end sm:pt-0">
    @if(!\Illuminate\Support\Facades\Auth::guest())
        <a class="bg-green-800 hover:bg-green-600 py-2 px-4 rounded mr-4 text-white font-bold"
           href="{{route('logout')}}">
                <span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                    {{ __('auth.logout') }}
                </span>
        </a>
    @endif

@foreach($available_locales as $locale_name => $available_locale)
        @if($available_locale !== $current_locale)
            <a class="bg-green-800 hover:bg-green-600 py-2 px-4 rounded mr-4 text-white font-bold"
               href="/language/{{ $available_locale }}">
                <span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"/>
                    </svg>
                    {{ $locale_name }}
                </span>
            </a>
        @endif
    @endforeach
</div>
