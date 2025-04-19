<dl>
	<dt>
		<a href="{{route('ank.id', $item->user_id)}}">
		<img alt="{{$item->name}},{{$item->user_age}}{{$item->user_age_type}},{{$item->city->name}}" data-src="{{ $photoUrl }}" src="{{ asset('image/zero.gif') }}" /></a>
	</dt>
	<dd>
		<p>{{--if $ankets[j].user_reg_is}<img title="на сайте" class="online" alt="на сайте" src="../../image/on_line.gif" />{/if--}}
		<a href="{{route('ank.id', $item->user_id)}}" @if ($item->user_sex == MEN)class="name_man" @else class="name_woman"@endif>{{$item->name}}</a>
		@if ($item->user_sex == MEN)<img alt="Мужчина" src="{{ asset('image/sex_men.jpg') }}" />@else<img alt="Женщина" src="{{ asset('image/sex_women.jpg') }}" />@endif
		<span>({{$item->photos_count}} фото)</span></p>
		<p><strong>{{$item->user_age}} {{$item->user_age_type}}</strong>, {{$item->city->name}}</p>
		@if (!empty ($best))
		@if (isset($item->rating_str))
		<table class="topReit">
		<tr>
			<td>
				<p>Рейтинг:</p>
			</td>
			<td>
				<div class="divUn1">
					<div>
						<ul>
							<li class="current-rating2" style="width:{{$item->rating_str}}px;">&nbsp;</li>
						</ul>
					</div>
				</div>
			</td>
		</tr>
		</table>
		@endif
		<p><i>{!! $item->onTop !!}</i></p>
		@else
		<p><strong>Ищу:</strong> {{$item->find_sex_orient}}</p>
		@endif
	</dd>
</dl>