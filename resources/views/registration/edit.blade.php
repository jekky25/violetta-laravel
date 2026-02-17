@extends('layouts.app')
@section('title', $title)
@section('main_body')
<h1 class="mTit">Редактирование данных</h1>
<x-menu />
<p class="pad1"></p>
<h4 class="reg_title">Введите пожалуйста свои данные</h4>
@if(session('success'))
<h4 class="reg_title2">информация сохранена</h4>
@endif
@if (!empty ($errors->comment->all()))
<h4 class="reg_title2">данные не сохранены, т. к. не все поля правильно заполнены</h4>
@endif
<p class="pad1"></p>
<form name="anketa" action="{{route('registration.edit')}}" method="post">
@method('PUT')
{{ csrf_field() }}
	<h4 class="menu_registration"><div>Имя</div></h4>
		<p class="pad1 pad2">Укажите Ваше <strong>настоящее имя</strong>. Если вы его не помните, то придумайте псевдоним. Именно по нему (а не по Логину) вас будут узнавать на сайте.</p>
		<x-error errName=name />
		<x-error errName=sex />
		<table class="sexRegForm">
			<tr>
				<td rowspan="2">
					<x-input name="name" value="{{ old('name', $userData->name) }}" />
				</td>
				<td class="pad11"><input type="radio" name="sex" value="1"@if (old('sex', $userData->sex) == 1) checked="checked"@endif /></td>
				<td><span class="menuMenReg">Я мужчина</span></td>
				<td><p class="pad12">дата рождения</p></td>
			</tr>
			<tr>
				<td class="pad11"><input type="radio" name="sex" value="2"@if (old('sex', $userData->sex) == 2) checked="checked"@endif /></td>
				<td><span class="menuWomenReg">Я женщина</span></td>
				<td>
					<x-select name="birth_day" :obj="$fields['day']" userProp="{{ old('birth_day', $userData->birth_day) }}" fieldZero="---" />
					<x-select name="birth_month" :obj="$fields['month']" userProp="{{ old('birth_month', $userData->birth_month) }}" />
					<x-select name="birth_year" :obj="$fields['year']" userProp="{{ old('birth_year', $userData->birth_year) }}" fieldZero="---" />
				</td>
			</tr>
		</table>
		<p class="pad2"></p>
		<h4 class="menu_registration"><div>Место жительства</div></h4>
		<p class="pad1 pad2">Укажите <strong>город, регион и страну</strong>, в которой вы живете. Это поможет другим пользователям сайта, которые тоже живут рядом с вами, быстрее вас найти.</p>
		<x-error errName=city />
		<table class="cityRegForm">
			<tr>
				<td width="150">страна</td>
				<td>
					<x-select name="country_id" id="country" :obj="$fields['country']" userProp="{{ old('country_id', $userData->country_id) }}">
						<x-slot:firstInList><option value="141">Россия</option></x-slot>
						<x-slot:addition>onchange="updateSelect('region', this.value, 'reg');"</x-slot:addition>
					</x-select>
			</td>
		</tr>
		<tr>
			<td width="150">регион</td>
			<td>
				<x-select name="region_id" id="region" :obj="$fields['region']" userProp="{{ old('region_id', $userData->region_id) }}">
					<x-slot:addition>onchange="updateSelect('city', this.value, 'cities');"</x-slot:addition>
				</x-select>
			</td>
		</tr>
		<tr>
			<td width="150">город</td>
			<td>
				<x-select name="city_id" id="city" :obj="$fields['city']"  userProp="{{ old('city_id', $userData->city_id) }}" />
			</td>
		</tr>
	</table>
	<script type="text/javascript" src="{{ asset('js/functions_search.js') }}"></script>
	<p class="pad2"></p>
	<input type="hidden" name="otsil" value="1" />
	<p class="pad2"></p>
	<p class="pad3">
		<x-submit name=sent value="изменить данные" />
	</p>
</form>
@overwrite