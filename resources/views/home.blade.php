@extends('layouts.app')
@section('title', '$title')
@section('main_body')
<h1 class="mTit">Бесплатные знакомства с девушками и парнями на сайте знакомств Виолетта</h1>
	<table id="mDescr">
		<tr>
			<td>
				<p>Добро пожаловать на наш <strong>сайт бесплатных знакомств</strong>.
				<br /><br />Это молодой, но динамично развивающийся ресурс интернета. Каждый день мы работаем над тем, чтобы Вам было удобнее <strong>общаться и находить новых друзей</strong>.<br /><br />
				Мы не являемся клоном другого сайта знакомств, мы уникальны!<br /><br />
				На нашем сайте Вы можете за 5 минут зарегистрировать свою анкету абсолютно бесплатно и общаться,
				общаться и еще раз общаться, находить новых друзей и заводить самые интригующие в Вашей жизни знакомства.
				Или (если конечно Вы захотите) посетите наш <a href="{{ asset('forum/') }}">форум</a> и получите на нем ответы на интересующие Вас вопросы.</p>
			</td>
			<td>
				<h3>Быстрый поиск</h3>
					<form name="anketa" action="{{route('search')}}" method="get" class="formSearch">
						<input type="hidden" name="mod" value="search" />
						<input type="hidden" name="op" value="search" />
						<div>Я ищу&nbsp; 
							<select name="find_sex">
								<option value="0" selected="selected">не важно&nbsp;</option>
								<option value="1">мужчину</option>
								<option value="2">женщину</option>
							</select>
						</div>
						<div>
							<input type="checkbox" name="foto" />&nbsp;&nbsp;только с фото
						</div>
						<div>в возрасте от&nbsp; 
							<select name="age_min">
								<option value="15">не важно&nbsp;</option>
								@if (!empty($ages))
								@foreach ($ages as $item)
								<option value="{{ $item }}">{{ $item }}</option>
								@endforeach
								@endif
							</select> до 
							<select name="age_max">
								<option value="15">не важно&nbsp;</option>
								@if (!empty($ages))
								@foreach ($ages as $item)
								<option value="{{ $item }}">{{ $item }}</option>
								@endforeach
								@endif
							</select>
						</div>
						<div>страна</div>
						<div>
							<select name="country" id="country" onchange="updateSelect('region', this.value, 'reg');">
								<option value="0">не важно&nbsp;</option>
								<option value="141">Россия</option>
								@if (!empty($countries))
								@foreach ($countries as $item)
								<option value="{{ $item->id }}">{{ $item->name }}</option>
								@endforeach
								@endif
							</select>
						</div>
						<div>регион</div>
						<div>
							<select name="region" id="region" onchange="updateSelect('city', this.value, 'cities');" >
								<option value="0">не важно</option>
							</select>
						</div>
						<div>город</div>
						<div>
							<select id="city" name="city">
								<option value="0">не важно</option>
							</select>
						</div>
						<div>
							<input type="submit" name="sent" id="otsil" class="bgBut3" value="" />
						</div>
					</form>
@push('scripts')
<script type="text/javascript" src="{{ asset('js/functions_search.js') }}"></script>
@endpush
				</td>
			</tr>
		</table>
		<div id="mDnev">
			<div class="mDnevTr">
				<div class="wth1 mDnevTd">
					<h3 class="for-pc">Новые лица на сайте знакомств</h3>		
					@if (!empty($newFaces))
						@foreach ($newFaces as $item)
						<dl @if ($loop->index >= 4) class="for-pc"@endif>
							<dt>
								<!--noindex-->
								<a href="{{route('ank.id', $item->user_id)}}" rel="nofollow">
									<img class="b-lazy" alt="{{ $item->user_name }},{{ $item->user_age }}{{ $item->user_age_type }},{{ $item->city->name }}"  data-src="{{ asset('fotos_new/' . $item->photo->fotos_id) . '.jpg' }}" src="{{ asset('image/zero.gif') }}" />
								</a>
								<!--/noindex-->
							</dt>
							<dd>
							<p><!--noindex-->@if (!empty($item->user_reg_is))<img title="на сайте" class="online" alt="на сайте" src="{{ asset('image/on_line.gif') }}" />@endif<a href="{{route('ank.id', $item->user_id)}}" @if ($item->user_sex == MEN)class="name_man" @else class="name_woman" @endif rel="nofollow">{{ $item->user_name }}</a><!--/noindex-->
							@if ($item->user_sex == MEN)<img class="b-lazy" alt="Мужчина" data-src="{{ asset('image/sex_men.jpg') }}" src="{{ asset('image/zero.gif') }}" />@else<img class="b-lazy" alt="Женщина" data-src="{{ asset('image/sex_women.jpg') }}" src="{{ asset('image/zero.gif') }}" />@endif
							<span>({{ $item->user_fotos }} фото)</span></p>
								<p><span class="st1">{{ $item->user_age }} {{ $item->user_age_type }}</span>, {{ $item->city->name }}</p>
								<p><span class="st1">Ищу:</span> {{ $item->find_sex_orient }}</p>
							</dd>
						</dl>
						@endforeach
					@endif
							</div>
							<div class="wth1 mDnevTd">
								<div class="counter3 for-pc">
									<script type="text/javascript"><!--
google_ad_client = "pub-6379140164632940";
/* 200x200, создано 26.12.08 */
google_ad_slot = "0480668500";
google_ad_width = 200;
google_ad_height = 200;
//-->
</script>
@push('scripts')
<script async type="text/javascript" src="http://pagead2.googlesyndication.com/pagead/show_ads.js"></script>
@endpush
								</div>
								<h3>Последние записи в дневниках</h3>
								@if (!empty($diaries))
								@foreach ($diaries as $item)
								<div class="dnevnik">
									<h4 class="{{ $item->name_class }}">
										<a href="{{route('ank.id', $item->user->user_id)}}">{{ $item->user->user_name }}</a>
										<p>{{ $item->dnevniki_time }}</p>
									</h4>
									<h3>
										<a href="{{route('ank.diary.id', $item->user->user_id)}}" class="{{ $item->name_class }}">{!! $item->dnevniki_title !!}</a>
									</h3>
									@if (!empty($item->dnevnik_foto))
									<div class="dnevPict">
										<a href="{{route('ank.diary.id', $item->user->user_id)}}"><img class="b-lazy" data-src="{{ $item->diaryImg }}" src="{{ asset('image/zero.gif') }}" alt="" /></a>
									</div>
									@endif
									<p class="dnevText">{!! \Illuminate\Support\Str::limit($item->dnevniki_text, 300, $end='...') !!}</p>
								</div>
								<a class="comLink" href="{{route('ank.diary.comments', $item->dnevniki_id)}}">комментарии ({{count ($item->comments)}})</a>
								@endforeach
								<a class="comLink left1 all-dnev-link" href="{{route('diaries')}}">все дневники >></a>
								@endif
							</div>
						</div>
					</div>

@overwrite