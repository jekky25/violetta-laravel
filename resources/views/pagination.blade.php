@if (!empty ($pagination) && count ($pagination) > 3)
	<table class="pagination"><tr><td>
	@foreach ($pagination as $k => $_pagination)
		@if ($ankets->currentPage() == 1 && $k == 0) @continue @endif
		@if ($ankets->currentPage() == $ankets->lastPage() && ($k - 1) == $ankets->lastPage()) @continue @endif
		<a href="{{$_pagination['url']}}" @if ($_pagination['active'] == 1)class="active"@endif>{!!$_pagination['label']!!}</a>
	@endforeach
	</td></tr></table>
@endif