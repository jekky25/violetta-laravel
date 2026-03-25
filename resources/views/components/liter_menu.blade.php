<p class="namesMW3">
	@foreach ($alphabet as $id => $liter)
	@if ($sex == 'men' && $id == 7) @continue; @endif
	<a href="{{route('names.subop', [$sex, $id])}}">{{$liter}}</a>
	@endforeach
</p>