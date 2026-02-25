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
<form name="anketa" action="{{route('registration.edit.partner')}}" method="post">
@method('PUT')
{{ csrf_field() }}
	<h4 class="menu_registration"><div>Внешние данные</div></h4>
	<p class="pad1 pad2">Если у вас есть конкретные пожелания к внешности вашего партнера - укажите их здесь.</p>
	<x-error errName=age />
	<div class="form-row">
		<label>Возраст:</label>
		<div class="range">
			<label for="age-min">от</label>
			<x-select name="partner_age_min" :obj="$fields->age()" type="I" userProp="{{ old('partner_age_min', $fields->user()->partner_age_min) }}" />
			<label for="age-max">до</label>
			<x-select name="partner_age_max" :obj="$fields->age()" type="I" userProp="{{ old('partner_age_max', $fields->user()->partner_age_max) }}" />
		</div>
	</div>
	<div class="form-row">
		<label>Рост:</label>
		<div class="range">
			<label for="height-min">от</label>
			<x-select name="partner_height_min" :obj="$fields->height()" type="I" userProp="{{ old('partner_height_min', $fields->user()->partner_height_min) }}" measure="см" />
			<label for="height-max">до</label>
			<x-select name="partner_height_max" :obj="$fields->height()" type="I" userProp="{{ old('partner_height_max', $fields->user()->partner_height_max) }}" measure="см" />
		</div>
	</div>
	<div class="form-row">
		<label>Вес:</label>
		<div class="range">
			<label for="weight_min">от</label>
			<x-select name="partner_weight_min" :obj="$fields->weight()" type="I" userProp="{{ old('partner_weight_min', $fields->user()->partner_weight_min) }}" measure="кг" />
			<label for="weight-max">до</label>
			<x-select name="partner_weight_max" :obj="$fields->weight()" type="I" userProp="{{ old('partner_weight_max', $fields->user()->partner_weight_max) }}" measure="кг" />
		</div>
	</div>
	<x-checkbox-container name="partner_body[]" :obj="$fields->body(old('body', $fields->user()->partner_body))" title="Телосложение:" topClass="pad2"></x-checkbox-container>
	<h4 class="menu_registration"><div>Дополнительная информация</div></h4>
	<p class="pad1 pad2">Опишите <strong>привычки</strong> партнера. Какими он должен владеть <strong>языками</strong>.</p>
	<x-checkbox-container name="partner_languages[]" :obj="$fields->languages($fields->user()->partner_languages)" title="Знание языков:" colums="2"></x-checkbox-container>
	<x-checkbox-container name="partner_alcohol[]" :obj="$fields->alcohol(old('alcohol', $fields->user()->partner_alcohol))" title="Отношение к спиртному:" colums="2"></x-checkbox-container>
	<x-checkbox-container name="partner_smoke[]" :obj="$fields->smoke(old('smoke', $fields->user()->partner_smoke))" title="Отношение к сигаретам:" colums="2"></x-checkbox-container>
	<x-checkbox-container name="partner_education[]" :obj="$fields->education(old('education', $fields->user()->partner_education))" title="Образование:" colums="2" topClass="pad2"></x-checkbox-container>
	<h4 class="menu_registration"><div>Место жительства</div></h4>
	<p class="pad1 pad2">Укажите <strong>город, регион и страну</strong>, где живет ваш партнер. Если хотите найти партнера, например, только из России, то укажите только Россию. Если интересует конкретный
регион, укажите страну и регион соответственно.</p>
	<div class="form-row">
		<label for="country">страна</label>
		<x-select name="partner_country" id="country" :obj="$fields->country()" userProp="{{ old('partner_country', $fields->user()->partner_country) }}">
			<x-slot:firstInList><option value="141">Россия</option></x-slot>
			<x-slot:addition>onchange="updateSelect('region', this.value, 'reg');"</x-slot:addition>
		</x-select>
	</div>
	<div class="form-row">
		<label for="region">регион</label>
		<x-select name="partner_region" id="region" :obj="$fields->region(old('partner_country', $fields->user()->partner_country))" userProp="{{ old('partner_region', $fields->user()->partner_region) }}">
			<x-slot:addition>onchange="updateSelect('city', this.value, 'cities');"</x-slot:addition>
		</x-select>
	</div>
	<div class="form-row pad2">
		<label for="city">город</label>
		<x-select name="partner_city" id="city" :obj="$fields->city(old('partner_region', $fields->user()->partner_region))" userProp="{{ old('partner_city', $fields->user()->partner_city) }}" />
	</div>	
	<script type="text/javascript" src="{{ asset('js/functions_search.js') }}"></script>
	<h4 class="menu_registration"><div>Несколько слов о партнере</div></h4>
	<p class="pad1 pad2">Опишите человека, которого вы хотите встретить. Какие его черты для вас особенно важны? Что вы хотите найти в нем? Каковы цели вашего знакомства и планы на будущее? Чем
больше вы напишите, тем больше вероятность найти именно того, кто вам нужен.</p>
	<div class="pad2">
		<x-textarea name="partner_description" value="{{ old('partner_description', $fields->user()->partner_description) }}" />
	</div>
	<input type="hidden" name="otsil" value="1" />
	<p class="pad2 text-align-center"><x-submit name="sent" value="изменить данные" /></p>
</form>
@overwrite