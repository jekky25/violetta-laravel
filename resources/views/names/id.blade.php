@extends('layouts.app')
@section('title', $title)
@section('main_body')
<h1 class="mTit">{{$nameTitle}}</h1>
@if (!empty($nameText))
<x-liter-menu :alphabet="$alphabet" :sex="$sex"></x-liter-menu>
<p class="pad8">
{!!$nameText!!}
</p>
<table>
<tr>
<td class="pad11">
<p class="pad1"><x-social /></p>
</td>
<td>
<!-- Place this tag where you want the +1 button to render. -->
<div class="g-plusone" data-size="medium"></div>

<!-- Place this tag after the last +1 button tag. -->
<script type="text/javascript">
  window.___gcfg = {lang: 'ru'};

  (function() {
    var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
    po.src = 'https://apis.google.com/js/plusone.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
  })();
</script>

</td></tr></table>
<h2 class="mTit pad5">{{$nameTitleGender}}</h2>
<x-liter-menu :alphabet="$alphabet" :sex="$sex"></x-liter-menu>
<ul class="namesMW">
	<li><a href="{{route('names.sex','men')}}">Мужские имена</a></li>
	<li><a href="{{route('names.sex','women')}}">Женские имена</a></li>
</ul>
<div class="clear"></div>
@endif
@if (!empty($bannerNames))<p class="pad1 pad2">{!!$bannerNames!!}</p>@endif
@if ($name->id == 8)<a href="http://www.russiamore.ru" class="name">Знакомства с иностранцами</a> - Хотите завязать романтические отношения, выйти замуж за иностранца, жить в другой стране? Тогда добро пожаловать на международный сайт знакомств Russiamore.
@endif
@if ($name->id == 9)<a href="http://www.lovevolna.ru" style="padding:0px;" class="name">Служба знакомств на сайте</a>. Приглашаем мужчин и женщин на наши популярные знакомства, ведь именно у нас вы можете общаться и
	знакомиться быстро и бесплатно!@endif
@overwrite