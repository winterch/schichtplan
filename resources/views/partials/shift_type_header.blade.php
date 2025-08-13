<thead class="">
    <tr><td colspan="6">
    <div class="mt-10">
        <span class="font-bold text-2xl border-b-2 p-4 block {{ $shift->type == '' ? 'italic' : '' }}">
            @if($shift->type !== "")
            {{ $shift->type }}
            @else
            {{ __('shift.noType') }}
            @endif
        </span>                      
    </div>
    </td></tr>
</thead>