<div class="profile-brief">
	<div class="profile-brief-container">
		<div class="profile-brief-image">
			<a href="{{route('ank.id', $item->id)}}">
				<img alt="{{$item->name}},{{$item->age}}{{$item->age_type}},{{$item->city->name}}" data-src="{{ $photoUrl }}" src="{{ asset('image/zero.gif') }}" />
			</a>
		</div>
		<div class="profile-brief-content">
			<p>{{--if $ankets[j].user_reg_is}<img title="на сайте" class="online" alt="на сайте" src="../../image/on_line.gif" />{/if--}}
				<a href="{{route('ank.id', $item->id)}}" @if ($item->sex == MEN)class="name_man" @else class="name_woman"@endif>{{$item->name}}</a>
				@if ($item->sex == MEN)<img alt="Мужчина" src="{{ asset('image/sex_men.jpg') }}" />@else<img alt="Женщина" src="{{ asset('image/sex_women.jpg') }}" />@endif
				<span>({{$item->photos_count}} фото)</span>
			</p>
			<p><strong>{{$item->age}} {{$item->age_type}}</strong>, {{$item->city->name}}</p>
		@if (!empty ($best))
		@if (isset($item->rating_str))
		<div class="topReit">
			<div>Рейтинг:</div>
			<div class="divUn1">
				<div>
					<ul>
						<li class="current-rating2" style="width:{{$item->rating_str}}px;">&nbsp;</li>
					</ul>
				</div>
			</div>
		</div>
		@endif
		<p><i>{!! $item->onTop !!}</i></p>
		@else
		<p><strong>Ищу:</strong> {{$item->find_sex_orient}}</p>
		@endif
		</div>
	</div>
</div>