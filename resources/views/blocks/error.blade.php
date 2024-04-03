@if ($errors->comment->has($errName))
	@if (!empty($before)){!! $before !!}@endif
	@foreach ($errors->comment->get($errName) as $item)
	<p class="blue2">{{ $item }}</p>
	@endforeach
	@if (!empty($after)){!! $after !!}@endif
@endif