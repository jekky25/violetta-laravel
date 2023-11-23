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
<form name="anketa" action="screensaver_id{$screensaver.id}.html" method="post">
<table class="scrDown">
<tr>
<td><input type="radio" name="f_download" value="1" id="scr" /></td>
<td><label for="scr">скачать как есть {{ $screen->size_scr }}</label></td>
</tr>
<tr>
<td><input type="radio" name="f_download" value="2" id="scr_arch" /></td>
<td><label for="scr_arch">скачать в rar архиве {{ $screen->size_rar }}</label></td>
</tr>
</table>
<p>Подтвердите, что вы не являетесь <strong>роботом</strong>. Для этого вам необходимо всего лишь ввести сегодняшнее число (2 знака).</p>
{if isset($err_antirobot)}{$err_antirobot}{/if}
<div class="pad2"><input type="text" class="input1" name="nerob" value="" /></div>
<p><input class="input2" type="submit" name="download" value="Скачать" /></p>
</form>
<table class="scrComments">
<tr>
<td {if !empty($comments)}class="valign1"{/if}>
@if (!empty($comments))
@foreach ($comments as $item)
<h3>{{ $item->name}}<p class="commTime">{{ $item->time }}</p></h3>
<p class="commDescr">{{ $item->description }}</p>
@endforeach
@else
<p class="pad3">Здесь вы можете оставить свои комментарии</p>			
@endif
</td>
<td>
<h4>Оставить комментарий</h4>
<form name="anketa2" action="screensaver_id{$screensaver.id}.html" method="post">
<div class="pad2"><textarea name="description" wrap="virtual" class="textarea1" ></textarea></div>
<p><input class="input2" type="submit" name="send" value="Отправить" /></p>
</form>
</td>
<tr>
</table>
@overwrite