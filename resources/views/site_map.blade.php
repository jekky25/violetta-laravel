@extends('layouts.app')
@section('title', $title)
@section('main_body')
    <h1 class="mTit">Карта сайта</h1>
	<p class="pad3">Если вы заблудились в разделах и не смогли найти то,
что вас интересует. То либо этого вообще не существует, либо вы найдете это здесь.
Как говорится <strong>бог в помощь</strong>!</p>
	<table class="mapSite">
		<tr>
			<td><a href="{{route('home')}}">Главная страница</a></td>
			<td><span>Это то место откуда начинаются все сайты</span></td>
		</tr>
		<tr><td>&nbsp;</td></tr>
		<tr>
			<td><a href="{{route('names')}}">Значение имени</a></td>
			<td><span>Хотите узнать, что означает ваше имя?</span></td>
		</tr>
		<tr>
			<td><span>&gt;&gt;</span><a href="{{route('names.sex','men')}}">Мужские имена</a></td>
			<td><span>&nbsp;</span></td>
		</tr>
		<tr>
			<td><span>&gt;&gt;&gt;
		    	<a href="{{route('names.subop', ['men',1])}}">А</a>
				<a href="{{route('names.subop', ['men',2])}}">Б</a>
				<a href="{{route('names.subop', ['men',3])}}" >В</a> . . .
				<a href="{{route('names.subop', ['men',21])}}">Э</a>
				<a href="{{route('names.subop', ['men',23])}}">Ю</a>
				<a href="{{route('names.subop', ['men',24])}}">Я</a>
				</span>
			</td>
			<td><span>&nbsp;</span></td>
		</tr>
		<tr>
			<td><span>&gt;&gt;</span><a href="{{route('names.sex','women')}}">Женские имена</a></td>
			<td><span>&nbsp;</span></td>
		</tr>
		<tr>
			<td><span>&gt;&gt;&gt;
				<a href="{{route('names.subop', ['women',1])}}">А</a>
				<a href="{{route('names.subop', ['women',2])}}">Б</a>
				<a href="{{route('names.subop', ['women',3])}}">В</a> . . .
				<a href="{{route('names.subop', ['women',21])}}">Э</a>
				<a href="{{route('names.subop', ['women',23])}}">Ю</a>
				<a href="{{route('names.subop', ['women',24])}}">Я</a>
		    	</span>
			</td>
			<td><span>&nbsp;</span></td>
		</tr>
		<tr><td colspan="2">&nbsp;</td></tr>
		<tr>
			<td><a href="{{route('population_search')}}">Популярные анкеты</a></td>
			<td><span>Узнай свою популярность</span></td>
		</tr>
		<tr valign="middle">
			<td><span>&gt;&gt;</span><a href="{{route('population_search.sex','men')}}">Мужчины</a></td>
			<td><span>&nbsp;</span></td>
		</tr>
		<tr>
			<td><span>&gt;&gt;</span><a href="{{route('population_search.sex','women')}}">Женщины</a></td>
			<td><span>&nbsp;</span></td>
		</tr>
		<tr><td colspan="2">&nbsp;</td></tr>
		<tr>
			<td><a href="{{route('birthday_search')}}">Дни рождения</a></td>
			<td><span>Пользователи, отмечающие сегодня день рождения</span></td>
		</tr>
		<tr><td colspan="2">&nbsp;</td></tr>
		<tr>
			<td><a href="{{route('screensavers')}}">Скринсейверы</a></td>
			<td><span>Коллекция отличных заставок на водную тему</span></td>
		</tr>
		<tr>
			<td><span>&gt;&gt;</span><a href="{{route('screensavers.id',7)}}">Дом на скале</a></td>
			<td><span>&nbsp;</span></td>
		</tr>
		<tr>
			<td><span>&gt;&gt;</span><a href="{{route('screensavers.id',10)}}">Темный лес</a></td>
			<td><span>&nbsp;</span></td>
		</tr>
		<tr>
			<td><span>&gt;&gt;</span><a href="{{route('screensavers.id',12)}}">Горная вершина</a></td>
			<td><span>&nbsp;</span></td>
		</tr>
		<tr><td><span>&gt;&gt;</span><a href="{{route('screensavers.id',11)}}">Живой водопад</a></td>
			<td><span>&nbsp;</span></td>
		</tr>
		<tr><td colspan="2">&nbsp;</td></tr>
		<tr><td><a href="{{route('review')}}">Отзывы</a></td>
			<td><span>Любое пожелание или мысли вслух по поводу работы нашего сайта</span></td></tr>
		<tr><td>&nbsp;</td></tr>
		<tr><td><a href="{{route('ankets')}}">Анкеты</a></td>
			<td><span>Поиск анкет</span></td>
		</tr>
		<tr><td><span>&gt;&gt;</span><a href="{{route('ankets.sex','men')}}">Мужские анкеты</a></td>
			<td><span>&nbsp;</span></td>
		</tr>
		<tr><td><span>&gt;&gt;&gt;
			<a href="{{route('ankets.sex.age',['men',20])}}">до 20 лет</a></span></td>
			<td><span>&nbsp;</span></td>
		</tr>
		<tr><td><span>&gt;&gt;&gt;
			<a href="{{route('ankets.sex.age',['men',2025])}}">20 - 25 лет</a></span></td>
			<td><span>&nbsp;</span></td></tr>
		<tr><td><span>&gt;&gt;&gt;
			<a href="{{route('ankets.sex.age',['men',2535])}}">25 - 35 лет</a></span></td>
			<td><span>&nbsp;</span></td></tr>
		<tr><td><span>&gt;&gt;&gt;
			<a href="{{route('ankets.sex.age',['men',3550])}}">35 - 50 лет</a></span></td>
			<td><span>&nbsp;</span></td></tr>
		<tr><td><span>&gt;&gt;&gt;
			<a href="{{route('ankets.sex.age',['men',50])}}">от 50 лет</a></span></td>
			<td><span>&nbsp;</span></td></tr>
		<tr><td><span>&gt;&gt;</span><a href="{{route('ankets.sex','women')}}">Женские анкеты</a></td>
			<td><span>&nbsp;</span></td></tr>
		<tr><td><span>&gt;&gt;&gt;
			<a href="{{route('ankets.sex.age',['women',20])}}">до 20 лет</a></span></td>
			<td><span>&nbsp;</span></td></tr>
		<tr><td><span>&gt;&gt;&gt;
			<a href="{{route('ankets.sex.age',['women',2025])}}">20 - 25 лет</a></span></td>
			<td><span>&nbsp;</span></td></tr>
		<tr><td><span>&gt;&gt;&gt;
			<a href="{{route('ankets.sex.age',['women',2535])}}">25 - 35 лет</a></span></td>
			<td><span>&nbsp;</span></td></tr>
		<tr><td><span>&gt;&gt;&gt;
			<a href="{{route('ankets.sex.age',['women',3550])}}">35 - 50 лет</a></span></td>
			<td><span>&nbsp;</span></td></tr>
		<tr><td><span>&gt;&gt;&gt;
			<a href="{{route('ankets.sex.age',['women',50])}}">от 50 лет</a></span></td>
			<td><span>&nbsp;</span></td></tr>
		<tr><td colspan="2">&nbsp;</td></tr>
		<tr><td><a href="{{route('search',['foto' => 1, 'send' => 1])}}">Анкеты с фото</a></td>
			<td><span>Поиск анкет с фотографиями</span></td></tr>
		<tr><td colspan="2">&nbsp;</td></tr>
		<tr><td><a href="{{route('search')}}">Поиск</a></td>
			<td><span>Расширенный поиск анкет</span></td></tr>
		<tr><td colspan="2">&nbsp;</td></tr>
		<tr><td><a href="{{ asset('forum/') }}">Форум</a></td>
			<td><span>Хотите пообщаться на тему межличностных отношений, тогда вам сюда</span></td></tr>
		<tr><td colspan="2">&nbsp;</td></tr>
		<tr><td><a href="{{route('bestankets.sex','men')}}">Лучшие парни</a><br />
				<a href="{{route('bestankets.sex','women')}}">Лучшие девушки</a></td>
			<td><span>Фактически ТОП 100 лучших девушек и парней</span></td></tr>
		<tr><td colspan="2">&nbsp;</td></tr>
		<tr><td><a href="{{route('contacts')}}">Контакты</a></td>
			<td><span>Это для того, чтобы с нами связаться</span></td></tr>
		<tr><td colspan="2">&nbsp;</td></tr>
		<tr><td><a href="{{route('dreambook')}}">Сонник</a></td>
			<td><span>Сонник</span></td></tr>
	</table>
	<p class="pad2"></p>
	@if (!empty($dreamBook))
	<table class="mapSite mapSite2">
		<tr>
			<td>
			@foreach ($dreamBook as $item)
			<a href="{{route('dreambook.id',$item->id)}}">{{ $item->name }}</a>
			@endforeach
			</td>
		</tr>
	</table>
	@endif
@overwrite