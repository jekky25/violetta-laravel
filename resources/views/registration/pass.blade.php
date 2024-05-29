@extends('layouts.app')
@section('title', $title)
@section('main_body')
<h1 class="mTit">Редактирование данных</h1>
<x-menu />
<p class="pad1"></p>
<h4 class="reg_title">Ваш пароль</h4>
@if(session('success'))
<h4 class="reg_title2">информация сохранена</h4>
@endif
@if (!empty ($errors->comment->all()))
<h4 class="reg_title2">данные не сохранены, т. к. не все поля правильно заполнены</h4>
@endif
<x-error errName=password />
<p class="pad1"></p>
<form name="anketa" action="{{ route('registration.edit.password') }}" method="post">
{{ csrf_field() }}
@if ($errors->comment->all())
	@foreach ($errors->comment->all() as $item)
	<p class="blue2">{{ $item }}</p>
	@endforeach
@endif
	<h4 class="menu_registration"><div>Пароль</div></h4>
	<p class="pad1 pad2">Для того, чтобы поменять <strong>пароль</strong> вам необходимо ввести старый, а затем два раза ввести новый пароль. Это необходимо для того, чтобы вы не ошиблись случайно при вводе пароля. <strong>Напоминаем</strong>, придумайте сложный пароль, чтобы его невозможно было бы подобрать и не используйте русские буквы.</p>
	<table class="mrg6" width="100%">
		<tr>
			<td align="right" width="50%">Старый пароль:</td>
			<td width="50%"><input type="password" maxLength="70" size="30" name="pass_old" value="" /></td>
		</tr>
		<tr>
			<td align="right" width="50%">Новый пароль:</td>
			<td width="50%"><input type="password" maxLength="70" size="30" name="pass" value="" /></td>
		</tr>
		<tr>
			<td align="right" width="50%">Подтвердите новый пароль:</td>
			<td width="50%"><input type="password" maxLength="70" size="30" name="pass_confirm" value="" /></td>
		</tr>
	</table>
	<input type="hidden" name="otsil" value="1" />
	<p class="pad2"></p>
	<p class="pad3"><input type="submit" name="sent" class="bgBut8" value="" /></p>
</form>
<p class="pad2"></p>
<div class="pad5">					
	<script type="text/javascript"><!--
google_ad_client = "ca-pub-6379140164632940";
/* 468x60 редактирование анкеты */
google_ad_slot = "8757413983";
google_ad_width = 468;
google_ad_height = 60;
//-->
	</script>
	<script type="text/javascript" async src="http://pagead2.googlesyndication.com/pagead/show_ads.js"></script>
</div>
<p class="pad2"></p>
@overwrite