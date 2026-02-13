@extends('layouts.app')
@section('title', $title)
@section('main_body')
<h1 class="mTit">{{ $screen->name }}</h1>
<p class="pad3"><strong>Скачано раз: {{ $screen->zakachka }}</strong></p>
<div class="scrPic2"><img alt="{{ $screen->name }}, хранитель экрана (скринсейвер)" src="{{ asset('screensavers/big_foto/' . $screen->path_jpg) }}" /></div>
<p class="pad4">Важно</p>
<p>Если вы не знаете как установить хранитель экрана на ваш компьютер, то: <br />1. Скопируйте файл с заставкой в папку windows или windows/system32 <br />
2. Кликните правой кнопкой мыши на рабочем столе и выберите меню "Свойства"<br />
3. В меню "Заставка" выберите вашу заставку и нажмите ОК</p>
<p>Мы пытались адаптировать хранитель экрана под большинство разрешений монитора. Но т.к. угодить всем
мы одновременно не можем, то в случае, если ваша заставка не располагается по всей площади экрана.<br />
Вы можете отрегулировать в подменю "параметры" меню установки заставки. Там же регулируется и скорость
проигрывания самой заставки.</p>
<p class="pad3">Приятного вам просмотра</p>
<form name="anketa" class="form-block" action="{{route('screensavers.id.download',$screen->id)}}" method="post">
{{ csrf_field() }}
@if (!empty ($errors->download->all()))
<div class="error">
@foreach ($errors->download->all() as $message)
<p>{{ $message }}</p>
@endforeach
	</div>
@endif
<table class="scrDown">
<tr>
<td><input type="radio" name="f_download" value="1" id="scr" /></td>
<td><label for="scr">скачать как есть {{ $screen->size_scr_format }}</label></td>
</tr>
<tr>
<td><input type="radio" name="f_download" value="2" id="scr_arch" /></td>
<td><label for="scr_arch">скачать в rar архиве {{ $screen->size_rar_format }}</label></td>
</tr>
</table>
<x-google-captcha />
<p class="text-align-center"><x-submit name="download" value="скачать" /></p>
</form>
<table class="scrComments">
<tr>
<td @if (!empty($comments))class="valign1"@endif>
@if (!empty($comments))
@foreach ($comments as $item)
<h3>{{ $item->name}}<p class="commTime">{{ $item->create_time }}</p></h3>
<p class="commDescr">{{ $item->description }}</p>
@endforeach
@else
<p class="pad3">Здесь вы можете оставить свои комментарии</p>			
@endif
</td>
<td class="valign1">
<h4>Оставить комментарий</h4>
<form name="anketa2" action="{{route('screensavers.id.store',$screen->id)}}" method="post">
{{ csrf_field() }}
@if(session('success'))
  <div class="success">{{session('success')}}</div>
@else
@if (!empty ($errors->comment->all()))
<div class="error">
@foreach ($errors->comment->all() as $message)
<p>{{ $message }}</p>
@endforeach
	</div>
@endif
<div class="pad2">
	<x-textarea name="description" />
</div>
<p class="text-align-center"><x-submit name="send" value="отправить" /></p>
@endif
</form>
</td>
<tr>
</table>
@overwrite