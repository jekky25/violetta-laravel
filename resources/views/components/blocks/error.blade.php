@if ($errors->comment->has($errName) || $errors->has($errName))
	@if (!empty($before)){!! $before !!}@endif
	@foreach ($errors->comment->get($errName) as $item)
	<p class="blue2">{{ $item }}</p>
	@endforeach
	@if ($errors->has($errName))
	@foreach ($errors->get($errName) as $item)
	<p class="blue2">{{ $item }}</p>
	@endforeach
	@endif
	@if (!empty($after)){!! $after !!}@endif
@endif