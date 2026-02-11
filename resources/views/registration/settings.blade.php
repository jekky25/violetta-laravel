@extends('layouts.app')
@section('title', $title)
@section('main_body')
<h1 class="mTit">Редактирование данных</h1>
<ul id="menuReg" class="clear">
	<li class="menuRegAct">Настройки</li>
</ul>
<form name="anketa" action="{{ route ('registration.edit.settings.post') }}" method="post">
	@method('PUT')
	{{ csrf_field() }}
	@if(session('success'))
		<h4 class="reg_title2">информация сохранена</h4>
	@endif
	<p class="pad2"></p>
	<table class="mrg6" width="100%">
		<tr>
			<td width="50%" align="right">Не получать сообщения с сайта</td>
			<td width="50%"><input type="checkbox" name="dont_send_email" value="1" @if ($user->dont_send_email == 1) checked="checked"@endif /></td>
		</tr>
	</table>	
	<input type="hidden" name="otsil" value="1" />
	<p class="pad2"></p>
	<p class="pad3">
		<x-submit name=sent value="изменить данные" />
	</p>
</form>
<p class="pad2"></p>
@overwrite