@extends('layouts.app')
@section('title', $title)
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
						<input type="hidden" name="send" value="1" />
						<div>Я ищу&nbsp; 
							<select name="find_sex">
								<option value="0" selected="selected">не важно&nbsp;</option>
								<option value="1">мужчину</option>
								<option value="2">женщину</option>
							</select>
						</div>
						<div>
							<input type="checkbox" name="photo" />&nbsp;&nbsp;только с фото
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
					<script type="text/javascript" src="{{ asset('js/functions_search.js') }}"></script>
				</td>
			</tr>
		</table>
		<div id="mDnev">
			<div class="mDnevTr">
				<div class="wth1 mDnevTd">
					<new-faces :women="{{WOMEN}}" :men="{{MEN}}"></new-faces>
				</div>
							<div class="wth1 mDnevTd">
								<h3>Последние записи в дневниках</h3>
								@if (!empty($diaries))
								@foreach ($diaries as $item)
								<div class="dnevnik">
									<h4 class="{{ $item->name_class }}">
										<a href="{{route('ank.id', $item->user->id)}}">{{ $item->user->name }}</a>
										<p>{{ $item->create_time }}</p>
									</h4>
									<h3>
										<a href="{{route('ank.diary.id', $item->user->id)}}" class="{{ $item->name_class }}">{!! $item->title !!}</a>
									</h3>
									@if (!empty($item->dnevnik_foto))
									<div class="dnevPict">
										<a href="{{route('ank.diary.id', $item->user->id)}}"><img class="b-lazy" data-src="{{ $item->diaryImg }}" src="{{ asset('image/zero.gif') }}" alt="" /></a>
									</div>
									@endif
									<p class="dnevText">{!! \Illuminate\Support\Str::limit($item->description, 300, $end='...') !!}</p>
								</div>
								<a class="comLink" href="{{route('ank.diary.comments', $item->id)}}">комментарии ({{count ($item->comments)}})</a>
								@endforeach
								<a class="comLink left1 all-dnev-link" href="{{route('diaries')}}">все дневники >></a>
								@endif
							</div>
						</div>
					</div>

@overwrite