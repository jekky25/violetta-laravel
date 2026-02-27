@extends('layouts.app')
@section('title', $title)
@section('main_body')
<h1 class="mTit">Редактирование данных</h1>
<ul id="menuReg" class="clear">
	<li class="menuRegAct">Настройки</li>
</ul>
@if(session('success'))
	<h4 class="reg_title2">информация сохранена</h4>
@endif
<form name="anketa" class="pad2" action="{{ route ('registration.edit.settings.post') }}" method="post">
	@method('PUT')
	{{ csrf_field() }}
	<x-checkbox-container name="dont_send_email" :obj="$fields->settings($fields->user()->dont_send_email)" topClass="pt-20"></x-checkbox-container>
	<input type="hidden" name="otsil" value="1" />
	<p class="pad3">
		<x-submit name="sent" value="изменить данные" />
	</p>
</form>
@overwrite