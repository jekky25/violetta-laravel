@extends('layouts.app')
@section('title', $title)
@section('main_body')
<h1 class="mTit">Редактирование данных</h1>
<x-menu />
<p class="pad1"></p>
<h4 class="line-title-pink mb-1">Введите пожалуйста свои данные</h4>
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
		<div class="form-main-data-container pad2">
			<div class="form-main-data-block1">
				<x-input name="name" value="{{ old('name', $fields->user()->name) }}" />
			</div>
			<div class="form-main-data-block2">
				<div class="form-row">
					<input type="radio" id="sex-man" name="sex" value="1"@if (old('sex', $fields->user()->sex) == 1) checked="checked"@endif />
					<label class="string-man" for="sex-man">Я мужчина</label>
				</div>
				<div class="form-row">
					<input type="radio" id="sex-woman" name="sex" value="2"@if (old('sex', $fields->user()->sex) == 2) checked="checked"@endif />
					<label class="string-woman" for="sex-woman">Я женщина</label>
				</div>
			</div>
			<div class="form-main-data-block3">
				<div>дата рождения</div>
				<div class="form-row">
					<x-select name="birth_day" :obj="$fields->day()" userProp="{{ old('birth_day', $fields->user()->birth_day) }}" fieldZero="---" />
					<x-select name="birth_month" :obj="$fields->month()" userProp="{{ old('birth_month', $fields->user()->birth_month) }}" />
					<x-select name="birth_year" :obj="$fields->year()" userProp="{{ old('birth_year', $fields->user()->birth_year) }}" fieldZero="---" />
				</div>
			</div>
		</div>
		<h4 class="menu_registration"><div>Место жительства</div></h4>
		<p class="pad1 pad2">Укажите <strong>город, регион и страну</strong>, в которой вы живете. Это поможет другим пользователям сайта, которые тоже живут рядом с вами, быстрее вас найти.</p>
		<x-error errName="city" />
		<div class="form-row">
			<label for="country">страна</label>
			<x-select name="country_id" id="country" :obj="$fields->country()" userProp="{{ old('country_id', $fields->user()->country_id) }}">
				<x-slot:firstInList><option value="141">Россия</option></x-slot>
				<x-slot:addition>onchange="updateSelect('region', this.value, 'reg');"</x-slot:addition>
			</x-select>
			</div>
		<div class="form-row">
			<label for="region">регион</label>
			<x-select name="region_id" id="region" :obj="$fields->region(old('country_id', $fields->user()->country_id))" userProp="{{ old('region_id', $fields->user()->region_id) }}">
				<x-slot:addition>onchange="updateSelect('city', this.value, 'cities');"</x-slot:addition>
			</x-select>
		</div>
		<div class="form-row">
			<label for="city">город</label>
			<x-select name="city_id" id="city" :obj="$fields->city(old('region_id', $fields->user()->region_id))" userProp="{{ old('city_id', $fields->user()->city_id) }}" />
		</div>
	<script type="text/javascript" src="{{ asset('js/functions_search.js') }}"></script>
	<p class="pad2"></p>
	<input type="hidden" name="otsil" value="1" />
	<p class="pad3">
		<x-submit name=sent value="изменить данные" />
	</p>
</form>
@overwrite