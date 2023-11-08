<!DOCTYPE html>
<html lang="ru">
<head>
<title>@yield('title')</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<meta name='yandex-verification' content='4b22b31a32d44adc' />
<link rel="Stylesheet"  href="{{ asset('css/reset.css?1') }}" type="text/css" />
<link rel="Stylesheet"  href="{{ asset('css/style.css?5') }}" type="text/css" />
<script type="text/javascript" src="{{ asset('js/jquery-1.9.0.min.js?1') }}"></script>
<script async type="text/javascript" src="{{ asset('js/frame_script.js?2') }}"></script>
{!! $pageMeta !!}
@stack('scripts')
</head>
<body>
<div id="ovHtm">
	<div id="head">
		<a id="chuvaki" href="{{route('home')}}"></a>
		<a class="logo-face-mob" href="{{route('home')}}"></a>
		<a id="logo" href="{{route('home')}}"></a>
		<a class="logo-mob" href="{{route('home')}}"><span><img class="b-lazy" data-src="{{ asset('image/logo_mob2.png') }}" src="{{ asset('image/zero.gif') }}" alt="" /></span></a>
		<h1 class="for-pc">Сайт знакомств</h1>
		<div class="mob-menu-icon"></div>
		<div class="mob-menu">
			<div class="close-menu"></div>
			@auth
			<h3>Рабочее меню</h3>
			<div class="mob-menu-first-block">
				<h4>{{ $user->user_name }}!</h4>
				<ul>
					<li class="first-item-menu"><a href="{{route('privmsg')}}"><span>Мои сообщения</span> <span @if ($user->user_new_message > 0) class="first-item-menu-num red_mark" @else class="first-item-menu-num green_mark" @endif>({{ $user->user_new_message }})</span></a></li>
					<li><a href="{{route('registration.edit')}}">Мой профиль</a></li>
					<li><a href="{{route('ank.id', $user->user_id)}}">Моя анкета</a></li>
					<li><a href="{{route('registration.edit.photo')}}">Мои фото</a></li>
					<li><a href="{{route('registration.edit.diary')}}">Мой дневник</a></li>
					<li><a href="{{route('registration.edit.settings')}}">Мои настройки</a></li>
					<li><a class="inTop" href="{{route('registration.top100')}}">@if ($user->user_fotos > 0  &&  $user->user_top100 > 0)Поднять анкету @else попасть в топ @endif</a></li>
					<li><a href="{{route('logout')}}">Выход</a></li>
				</ul>
			</div>
			@else
			<h3>Вход</h3>
			<div class="mob-menu-first-block">
				<form name="login" action="{{route('login')}}" method="post">
					<dl>
						<dt>Ваш логин:</dt>
						<dd><input type="text" name="username_template" /></dd>
					</dl>
					<dl>
						<dt>Пароль:</dt>
						<dd><input type="password" name="pass_template" /></dd>
					</dl>					
					<p class="pad1 mob-menu-subm"><input class="bgBut2" class="submit" type="submit" value="" /></p>
				</form>
				<p class="mob-menu-p-name"><a class="name" href="{{route('forget_pass')}}">Забыли пароль?</a></p>
				<p class="mob-menu-p-name"><a class="name" href="{{route('registration')}}">Зарегистрироваться</a></p>				
			</div>
			@endauth
			<div class="mob-menu-second-block">
				<ul>
					<li><a href="{{route('goroskop')}}">Гороскопы</a></li>
					<li><a href="{{route('names')}}">Значение имени</a></li>
					<li><a href="{{route('population_search')}}">Популярные анкеты</a></li>
					<li><a href="{{route('birthday_search')}}">Дни рождения</a></li>
					<li><a href="{{route('screensavers')}}">Скринсейверы</a></li>
					<li><a href="{{route('dreambook')}}">Сонник</a></li>
					@if (Route::is('contacts') || Route::is('review'))
					<li><a href="{{route('review')}}">Оставить отзыв</a></li>
					@endif
				</ul>
			</div>
		</div>
		<div id="hBaner">
<script type="text/javascript"><!--
google_ad_client = "ca-pub-6379140164632940";
/* 728x90 шапка (В) */
google_ad_slot = "8276880722";
google_ad_width = 728;
google_ad_height = 90;
//-->
</script>
<script async type="text/javascript" src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
</script>
		</div>
	</div>
	<div id="center">
		<div id="cent_cont">
			<div id="content">
				<div class="fLine mrg1"></div>
				<table id="hMenu">
					<tr>
						<td class="fFirst"></td>
						<td class="top-menu-main"><a href="{{route('home')}}">Главная</a></td>
						<td><a href="{{route('search')}}">Поиск</a></td>
						<td><a href="{{route('ankets')}}">Анкеты</a></td>
						<td><a href="{{route('diaries')}}">Дневники</a></td>
						<td><a href="{{ asset('forum/') }}">Форум</a></td>
						<td class="fLast"></td>
					</tr>
				</table>
				<div id="cPad">
					@yield('main_body')
				</div>	
			</div>
		</div>
		<div id="leftcol">
			<h2>Знакомства</h2>
			<div class="bl">
				<ul>
					<li><a href="{{route('goroskop')}}">Гороскопы</a></li>
					<li><a href="{{route('names')}}">Значение имени</a></li>
					<li><a href="{{route('population_search')}}">Популярные анкеты</a></li>
					<li><a href="{{route('birthday_search')}}">Дни рождения</a></li>
					<li><a href="{{route('screensavers')}}">Скринсейверы</a></li>
					<li><a href="{{route('dreambook')}}">Сонник</a></li>
					@if (Route::is('contacts') || Route::is('review'))
					 <li><a href="{{route('review')}}">Оставить отзыв</a></li>
					@endif
				</ul>	
			</div>
			<div class="blFoot"></div>
			<h2>Анкеты</h2>
			<div class="bl">
				<table class="t_l2">
					<tr>
						<td>
							<ul>
								<li><a class="tit1" href="{{route('ankets.sex', 'men')}}">Мужчины</a></li>
								<li><a href="{{route('ankets.sex.age', ['men',20])}}">до 20 лет</a></li>
								<li><a href="{{route('ankets.sex.age', ['men',2025])}}">20 - 25 лет</a></li>
								<li><a href="{{route('ankets.sex.age', ['men',2535])}}">25 - 35 лет</a></li>
								<li><a href="{{route('ankets.sex.age', ['men',3550])}}">35 - 50 лет</a></li>
								<li><a href="{{route('ankets.sex.age', ['men',50])}}">от 50 лет</a></li>
							</ul>
						</td>
						<td>
							<ul>
								<li><a class="tit1" href="{{route('ankets.sex', 'women')}}">Женщины</a></li>
								<li><a href="{{route('ankets.sex.age', ['women',20])}}">до 20 лет</a></li>
								<li><a href="{{route('ankets.sex.age', ['women',2025])}}">20 - 25 лет</a></li>
								<li><a href="{{route('ankets.sex.age', ['women',2535])}}">25 - 35 лет</a></li>
								<li><a href="{{route('ankets.sex.age', ['women',3550])}}">35 - 50 лет</a></li>
								<li><a href="{{route('ankets.sex.age', ['women',50])}}">от 50 лет</a></li>
							</ul>
						</td>
					</tr>
				</table>					
			</div>
			@if ( !empty($forums))
			<div class="blFoot"></div>
			<h2>Последние темы форума</h2>
			<div class="bl">
				<!--noindex-->
					<ul>
						@foreach ($forums as $item)
						<li><a href="forum/topic_{{ $item->forum_id }}_{{ $item->topic_id }}.html" rel="nofollow">{!! \Illuminate\Support\Str::limit($item->topic_title, 28, $end='...') !!}</a></li>
						@endforeach
					</ul>
				<!--/noindex-->
			</div>
			@endif
			<div class="blFoot"></div>
			<h3>Статистика</h3>
			<div id="static">
				<p>Всего женщин:<a href="{{route('search', ['find_sex' => 2])}}">{{ $statAnkets['total_women'] }}</a>({{ $statAnkets['total_women_percent'] }})</p>
				<p>Всего мужчин:<a href="{{route('search', ['find_sex' => 1])}}">{{ $statAnkets['total_men'] }}</a>({{ $statAnkets['total_men_percent'] }})</p>
				<p>Всего фотографий:<a href="{{route('search', ['photo' => 1])}}">{{ $statAnkets['total_fotos'] }}</a></p>
			</div>
			<div class="counter">
<!--noindex-->
<!--Rating@Mail.ru COUNTEr--><script language="JavaScript" type="text/javascript"><!--
d=document;var a='';a+=';r='+escape(d.referrer)
js=10//--></script><script language="JavaScript1.1" type="text/javascript"><!--
a+=';j='+navigator.javaEnabled()
js=11//--></script><script language="JavaScript1.2" type="text/javascript"><!--
s=screen;a+=';s='+s.width+'*'+s.height
a+=';d='+(s.colorDepth?s.colorDepth:s.pixelDepth)
js=12//--></script><script language="JavaScript1.3" type="text/javascript"><!--
js=13//--></script><script language="JavaScript" type="text/javascript"><!--
d.write('<a href="http://top.mail.ru/jump?from=1298829"'+
' target=_top rel=nofollow><img src="http://d1.cd.b3.a1.top.list.ru/counter'+
'?id=1298829;t=133;js='+js+a+';rand='+Math.random()+
'" alt="Рейтинг@Mail.ru"'+' border=0 height=40 width=88/><\/a>')
if(11<js)d.write('<'+'!-- ')//--></script><noscript><a
target=_top href="http://top.mail.ru/jump?from=1298829" rel="nofollow"><img
src="http://d1.cd.b3.a1.top.list.ru/counter?js=na;id=1298829;t=133"
border=0 height=40 width=88
alt="Рейтинг@Mail.ru"/></a></noscript><script language="JavaScript" type="text/javascript"><!--
if(11<js)d.write('--'+'>')//--></script><!--/COUNTER--><!--/noindex--><br /><br />

{if $s_link1}
{$s_link1}
{/if}<br /><br />
<script type="text/javascript"><!--
google_ad_client = "pub-6379140164632940";
/* Морда слева сквозняк */
google_ad_slot = "0076736043";
google_ad_width = 160;
google_ad_height = 600;
//-->
</script>
<script type="text/javascript" async src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
</script>
			</div>
		</div>
		<div id="rightcol">
			@auth
			<h2>Рабочее меню</h2>			
			<div class="bl AccMenu">
				<h3>{{ $user->user_name }}!</h3>
				<ul>
					<li><a class="name_my_mess" href="{{route('privmsg')}}">Мои сообщения</a> <span @if ($user->user_new_message > 0) class="red_mark" @else class="green_mark" @endif>({{ $user->user_new_message }})</span></li>
					<li><a href="{{route('registration.edit')}}">Мой профиль</a></li>
					<li><a href="{{route('ank.id', $user->user_id)}}">Моя анкета</a></li>
					<li><a href="{{route('registration.edit.photo')}}">Мои фото</a></li>
					<li><a href="{{route('registration.edit.diary')}}">Мой дневник</a></li>
					<li><a href="{{route('registration.edit.settings')}}">Мои настройки</a></li>
					<li><a class="inTop" href="{{route('registration.top100')}}">@if ($user->user_fotos > 0  &&  $user->user_top100 > 0)Поднять анкету @else попасть в топ @endif</a></li>
				</ul>
				<p>Последний визит: {{ $user->user_lastvisit_format }}</p>
				<p>Просмотров за месяц: @if ($user->monthVisits > 0)<a href="{{route('registration.views')}}" class="views_l">{{ $user->monthVisits }}</a>@else{{ $user->monthVisits }}}@endif @if ($user->monthVisitsNew > 0) <span class="views_l_new"> + <a href="{{route('registration.views')}}">{{ $user->monthVisitsNew }}</a></span>@endif</p>
				<p class="logOutBut"><a href="{{route('logout')}}">Выход</a></p>
			</div>
			@else
			<h2>Вход для пользователей</h2>			
			<div class="bl logForm">
				<form name="login" action="{{route('login')}}" method="post">
					<dl>
						<dt>Ваш логин:</dt>
						<dd><input type="text" name="username_template" /></dd>
					</dl>
					<dl>
						<dt>Пароль:</dt>
						<dd><input type="password" name="pass_template" /></dd>
					</dl>					
					<p class="pad1"><input class="bgBut2" class="submit" type="submit" value="" /></p>
				</form>
				<p><a class="name" style="padding-right: 20px;" href="{{route('forget_pass')}}">Забыли пароль?</a></p>
				<p><a class="name" style="padding-right: 20px;" href="{{route('registration')}}">Зарегистрироваться</a></p>				
			</div>
			@endauth
			<div class="blFoot"></div>
				<h2>Лучшая девушка</h2>
				<div class="bl">
					@php($item = $top100['women'])
					<h3>{{ $item->user_name }}, {{ $item->user_age }} {{ $item->user_age_type }}</h3>
					<!--noindex-->
						<div class="foto">
							<a href="{{route('ank.id', $item->user_id )}}" rel="nofollow">
								<img alt="{{ $item->user_name }},{{ $item->user_age }},{{ $item->city->name }}" class="b-lazy" data-src="{{ asset('fotos_new/' . $item->photo->fotos_id) . '.jpg' }}" src="{{ asset("image/zero.gif") }}" />
							</a>
						</div>
						<p class="links1"><a href="{{route('ank.id', $item->user_id )}}" rel="nofollow">смотреть анкету</a></p>
						<p class="links1"><a href="{{route('bestankets.sex', 'women')}}">лучшие девушки</a></p>
					<!--/noindex-->
				</div>
				<div class="blFoot"></div>
				<h2>Лучший парень</h2>
				<div class="bl">
					@php($item = $top100['men'])
					<h3>{{ $item->user_name }}, {{ $item->user_age }} {{ $item->user_age_type }}</h3>
					<!--noindex-->
						<div class="foto">
							<a href="{{route('ank.id', $item->user_id )}}" rel="nofollow">
							<img alt="{{ $item->user_name }},{{ $item->user_age }},{{ $item->city->name }}" class="b-lazy" data-src="{{ asset('fotos_new/' . $item->photo->fotos_id) . '.jpg' }}" src="{{ asset("image/zero.gif") }}" />
							</a>
						</div>
						<p class="links1"><a href="{{route('ank.id', $item->user_id )}}" rel="nofollow">смотреть анкету</a></p>
						<p class="links1"><a href="{{route('bestankets.sex', 'men')}}">лучшие парни</a></p>
					<!--/noindex-->
				</div>
				<div class="blFoot"></div>
				<div class="counter">
{if $s_link2}
{$s_link2}
{/if}<br />
{if $s_link3}
{$s_link3}
{/if}<br /><br />
<!--noindex-->
<br />
<!--LiveInternet counter--><script type="text/javascript"><!--
document.write("<a href='http://www.liveinternet.ru/click' "+
"target=_blank rel=nofollow><img src='http://counter.yadro.ru/hit?t11.6;r"+
escape(document.referrer)+((typeof(screen)=="undefined")?"":
";s"+screen.width+"*"+screen.height+"*"+(screen.colorDepth?
screen.colorDepth:screen.pixelDepth))+";u"+escape(document.URL)+
";"+Math.random()+
"' alt='' title='LiveInternet: показано число просмотров за 24 часа, посетителей за 24 часа и за сегодн\я' "+
"border='0' width='88' height='31'><\/a>")//--></script><!--/LiveInternet-->
<br />
<!--begin of Rambler's Top100 code -->
<a href="http://top100.rambler.ru/top100/" rel="nofollow">

<img src="http://counter.rambler.ru/top100.cnt?1220117" alt="" width="1" height="1" border="0"></a><!--end of Top100 code--><!--begin of Top100 logo--><a href="http://top100.rambler.ru/top100/" rel="nofollow"><img src="http://top100-images.rambler.ru/top100/w9.gif" alt="Rambler's Top100" width=88 height=31 border=0></a><!--end of Top100 logo -->
<!--/noindex--><br /><br /></div>
		</div>
	</div>
	<div class="fLine"></div>
	<div id="footer">
		<table>
			<tr>
				<td class="fFirst"></td>
				<td class="for-pc"><a href="{{route('home')}}">Главная</a></td>
				<td><a href="{{route('search')}}">Поиск</a></td>
				<td><a href="{{route('ankets')}}">Анкеты</a></td>
				<td><a href="{{ asset('forum/') }}">Форум</a></td>
				<td><a href="{{route('sitemap')}}">Карта&nbsp;сайта</a></td>
				<td class="map-site-link"><a href="{{route('contacts')}}">Контакты</a></td>
				<td class="wth2 for-pc">{$copyright}</td>
				<td class="fLast"></td>
			</tr>
		</table>
	</div>
	<div class="mob-copyrights">{$copyright}</div>
{if $s_link4}
<div class="drugi">	
{$s_link4}
</div>
{/if}
</div>
<div id="mask"></div>
<div id="prodblock"><div id="prodblockIn"><div class="bgFame1Cnt2"></div></div></div>
<script defer src="{{ asset("js/metrika.js?1") }}" type="text/javascript"></script>
<link rel="icon" type="image/x-icon" href="{{ asset("icon1.ico") }}" />
<link rel="shortcut icon" type="image/x-icon" href="{{ asset("icon1.ico") }}" />
<script async src="{{ asset("js/lazyload.min.js?t=3") }}" type="text/javascript"></script>
</body>
</html>