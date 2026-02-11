<!DOCTYPE html>
<html lang="ru">
<head>@include('layouts.head')</head>
<body id="app">
<div class="overHtml">
	<header>
		<a class="logo" href="{{route('home')}}"></a>
		<a class="logo-title" href="{{route('home')}}"></a>
		<div class="head-text for-pc">Сайт знакомств</div>
		<div class="mob-menu-icon"></div>
		<div class="mob-menu">
			<div class="close-menu"></div>
			@auth
			<h3>Рабочее меню</h3>
			<div class="mob-menu-first-block">
				<h4>{{ $user->name }}!</h4>
				<ul>
					<li class="first-item-menu"><a href="{{route('privmsg')}}"><span>Мои сообщения</span> <span @if ($user->user_new_message > 0) class="first-item-menu-num red_mark" @else class="first-item-menu-num green_mark" @endif>({{ $user->user_new_message }})</span></a></li>
					<li><a href="{{route('registration.edit')}}">Мой профиль</a></li>
					<li><a href="{{route('ank.id', $user->id)}}">Моя анкета</a></li>
					<li><a href="{{route('registration.edit.photo')}}">Мои фото</a></li>
					<li><a href="{{route('registration.edit.diary')}}">Мой дневник</a></li>
					<li><a href="{{route('registration.edit.settings')}}">Мои настройки</a></li>
					<li><a class="inTop" href="{{route('registration.top100')}}">@if ($user->photos_count > 0  &&  $user->top100 > 0)Поднять анкету @else попасть в топ @endif</a></li>
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
					<p class="pad1 mob-menu-subm">
						<x-submit name=sent value="войти" />
					</p>
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
	</header>
	<main>
		<div id="center">
			<div id="cent_cont">
				<div id="content">
					<div class="fLine mrg1"></div>
					<nav class="top-menu" aria-label="Основное меню хедер">
						<ul>
							<li><a href="{{route('home')}}">Главная</a></li>
							<li><a href="{{route('search')}}">Поиск</a></li>
							<li><a href="{{route('ankets')}}">Анкеты</a></li>
							<li><a href="{{route('diaries')}}">Дневники</a></li>
							<li><a href="{{route('forum')}}">Форум</a></li>
						</ul>
					</nav>
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
			<forum-top></forum-top>
			<div class="blFoot"></div>
			<statistics></statistics>
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
			<login-profile></login-profile>
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
	</main>
	<footer>
		<div>
			<nav class="bottom-menu" aria-label="Основное меню футер">
				<ul>
					<li class="for-pc"><a href="{{route('home')}}">Главная</a></li>
					<li><a href="{{route('search')}}">Поиск</a></li>
					<li><a href="{{route('ankets')}}">Анкеты</a></li>
					<li><a href="{{route('forum')}}">Форум</a></li>
					<li><a href="{{route('sitemap')}}">Карта&nbsp;сайта</a></li>
					<li><a href="{{route('contacts')}}">Контакты</a></li>
    			</ul>
    		</nav>
		</div>
		<div class="for-pc">{{ $copyright }}</div>
		<div class="mob-copyrights">{{ $copyright }}</div>
	</footer>
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