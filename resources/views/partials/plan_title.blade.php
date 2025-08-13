<h1 class="text-3xl mb-2">{{ $plan->title }}</h1>
<br>
<div class="text-lg italic w-1/2">{!! nl2br(e($plan->description)) !!}</div>
@if (!empty($plan->contact))
  <br>
  <div class="text-lg w-1/2">{{ $plan->contact }}</div>
@endif
