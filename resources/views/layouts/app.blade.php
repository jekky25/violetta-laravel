<!DOCTYPE html>
<html lang="ru">
<head>@include('layouts.head')</head>
<body id="app">
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
					<li><a class="inTop" href="{{route('registration.top100')}}">@if ($user->user_fotos > 0  &&  $user->top100 > 0)Поднять анкету @else попасть в топ @endif</a></li>
					<li><a href="{{route('logout')}}">Выход</a></li>
				</ul>
			</div>
			@else
			<h3>Вход</h3>
			<div class="mob-menu-first-block">
				<form name="login" action="{{route('login')}}" method="post">
					{{ csrf_field() }}
					<dl>
						<dt>Ваш логин:</dt>
						<dd><input type="text" name="username_template" /></dd>
					</dl>
					<dl>
						<dt>Пароль:</dt>
						<dd><input type="password" name="pass_template" /></dd>
					</dl>					
					<p class="pad1 mob-menu-subm"><input class="bgBut2 submit" type="submit" value="" /></p>
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
						<td><a href="{{route('forum')}}">Форум</a></td>
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
						<li><a href="{{ route ('forum.topic', [$item->forum_id, $item->topic_id]) }}" rel="nofollow">{!! \Illuminate\Support\Str::limit($item->topic_title, 28, $end='...') !!}</a></li>
						@endforeach
					</ul>
				<!--/noindex-->
			</div>
			@endif
			<div class="blFoot"></div>
			<h3>Статистика</h3>
			<div id="static">
				<p>Всего женщин:<a href="{{route('search', ['find_sex' => 2, 'send' => '1'])}}">{{ $statAnkets['total_women'] }}</a>({{ $statAnkets['total_women_percent'] }})</p>
				<p>Всего мужчин:<a href="{{route('search', ['find_sex' => 1, 'send' => '1'])}}">{{ $statAnkets['total_men'] }}</a>({{ $statAnkets['total_men_percent'] }})</p>
				<p>Всего фотографий:<a href="{{route('search', ['photo' => 1, 'send' => '1'])}}">{{ $statAnkets['total_fotos'] }}</a></p>
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
			</div>
		</div>
		<div id="rightcol">
			@auth
			<h2>Рабочее меню</h2>			
			<div class="bl AccMenu">
				<h3>{{ $user->user_name }}!</h3>
				<ul>
					<li><a class="name_my_mess" href="{{route('privmsg')}}">Мои сообщения</a> <span @if ($user->new_messages > 0) class="red_mark" @else class="green_mark" @endif>({{ $user->new_messages }})</span></li>
					<li><a href="{{route('registration.edit')}}">Мой профиль</a></li>
					<li><a href="{{route('ank.id', $user->user_id)}}">Моя анкета</a></li>
					<li><a href="{{route('registration.edit.photo')}}">Мои фото</a></li>
					<li><a href="{{route('registration.edit.diary')}}">Мой дневник</a></li>
					<li><a href="{{route('registration.edit.settings')}}">Мои настройки</a></li>
					<li><a class="inTop" href="{{route('registration.top100')}}">@if ($user->user_fotos > 0  &&  $user->top100 > 0)Поднять анкету @else попасть в топ @endif</a></li>
				</ul>
				<p>Последний визит: {{ $user->lastvisit_format }}</p>
				<p>Просмотров за месяц: @if ($user->monthVisits > 0)<a href="{{route('registration.views')}}" class="views_l">{{ $user->monthVisits }}</a>@else{{ $user->monthVisits }}@endif @if ($user->monthVisitsNew > 0) <span class="views_l_new"> + <a href="{{route('registration.views')}}">{{ $user->monthVisitsNew }}</a></span>@endif</p>
				<p class="logOutBut"><a href="{{route('logout')}}">Выход</a></p>
			</div>
			@else
			<h2>Вход для пользователей</h2>
			<div class="bl logForm">
				<form name="login" action="{{route('login')}}" method="post">
					{{ csrf_field() }}
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
			<best-profile :sex="`{{ WOMEN }}`" :route="`{{ route('profile.get.top100', WOMEN) }}`"></best-profile>
			<best-profile :sex="`{{ MEN }}`"  :route="`{{ route('profile.get.top100', MEN) }}`"></best-profile>
			<div class="counter">
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
				<td><a href="{{route('forum')}}">Форум</a></td>
				<td><a href="{{route('sitemap')}}">Карта&nbsp;сайта</a></td>
				<td class="map-site-link"><a href="{{route('contacts')}}">Контакты</a></td>
				<td class="wth2 for-pc">{{ $copyright }}</td>
				<td class="fLast"></td>
			</tr>
		</table>
	</div>
	<div class="mob-copyrights">{{ $copyright }}</div>
{{--
{if $s_link4}
<div class="drugi">	
{$s_link4}
</div>
{/if}
--}}
</div>
<div id="mask"></div>
<div id="prodblock"><div id="prodblockIn"><div class="bgFame1Cnt2"></div></div></div>
<!-- Yandex.Metrika counter -->
<script src="//mc.yandex.ru/metrika/watch.js" type="text/javascript"></script>
<div style="display:none;"><script type="text/javascript">
try { var yaCounter161110 = new Ya.Metrika({id:161110, enableAll: true, webvisor:true});}
catch(e) { }
</script></div>
<noscript><div><img src="//mc.yandex.ru/watch/161110" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
<!-- /Yandex.Metrika counter -->
<link rel="icon" type="image/x-icon" href="{{ asset("icon1.ico") }}" />
<link rel="shortcut icon" type="image/x-icon" href="{{ asset("icon1.ico") }}" />
<script async src="{{ asset("js/lazyload.min.js?t=3") }}" type="text/javascript"></script>
</body>
</html>