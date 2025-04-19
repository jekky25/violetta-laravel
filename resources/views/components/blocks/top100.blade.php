@if ($top100->count() > 0)
<h2>{{ ($sex == WOMEN ? 'Лучшая девушка' : 'Лучший парень') }}</h2>
<div class="bl">
	@php($item = $top100)
	<h3>{{ $item->name }}, {{ $item->user_age }} {{ $item->user_age_type }}</h3>
	<!--noindex-->
		<div class="foto">
			<a href="{{route('ank.id', $item->user_id )}}" rel="nofollow">
				<img alt="{{ $item->name }},{{ $item->user_age }},{{ $item->city->name }}" class="b-lazy" data-src="{{ asset('fotos_new/' . $item->photo->id) . '.jpg' }}" src="{{ asset("image/zero.gif") }}" />
			</a>
		</div>
		<p class="links1"><a href="{{route('ank.id', $item->user_id )}}" rel="nofollow">смотреть анкету</a></p>
		<p class="links1"><a href="{{route('bestankets.sex', ($sex == WOMEN ? 'women' : 'men'))}}">{{ ($sex == WOMEN ? 'лучшие девушки' : 'лучшие парни') }}</a></p>
	<!--/noindex-->
</div>
<div class="blFoot"></div>
@endif