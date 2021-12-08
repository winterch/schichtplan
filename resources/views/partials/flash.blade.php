@if(Session::has('fail'))
    <div class="w-full bg-red-50 border border-red-500 p-4 rounded">
        {{Session::get('fail')}}
    </div>
@endif

@if(Session::has('info'))
    <div class="w-full bg-blue-50 border border-blue-500 p-4 rounded text-xl">
        {{Session::get('info')}}
    </div>
@endif
