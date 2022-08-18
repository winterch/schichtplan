<h1 class="text-3xl mb-2">{{ $plan->title }}</h1>
<br>
<div class="text-lg italic">{{ $plan->description }}</div>
@if (!empty($plan->contact))
  <br>
  <div class="text-lg">{{ __('plan.responsible') }}: {{ $plan->contact }}</div>
@endif
