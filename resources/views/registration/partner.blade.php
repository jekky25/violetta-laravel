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
	<table class="mrg6" width="100%">
		<tr>
			<td align="right" width="50%">Возраст:</td>
			<td width="50%">
				от&nbsp;
				<x-select name=partner_age_min :obj="$fields['age']" type=I :userProp="old('partner_age_min', $userData->partner_age_min)" />
				&nbsp;до&nbsp;
				<x-select name=partner_age_max :obj="$fields['age']" type=I :userProp="old('partner_age_max', $userData->partner_age_max)" />
			</td>
		</tr>
		<tr>
			<td align="right" width="50%">Рост:</td>
			<td width="50%">
				от&nbsp;
				<x-select name=partner_height_min :obj="$fields['height']" type=I :userProp="old('partner_height_min', $userData->partner_height_min)" measure="см" />
				&nbsp;до&nbsp;
				<x-select name=partner_height_max :obj="$fields['height']" type=I :userProp="old('partner_height_max', $userData->partner_height_max)" measure="см" />
			</td>
		</tr>
		<tr>
			<td align="right" width="50%">Вес:</td>
			<td width="50%">
				от&nbsp;
				<x-select name=partner_weight_min :obj="$fields['weight']" type=I :userProp="old('partner_weight_min', $userData->partner_weight_min)" measure="кг" />	
				&nbsp;до&nbsp;
				<x-select name=partner_weight_max :obj="$fields['weight']" type=I :userProp="old('partner_weight_max', $userData->partner_weight_max)" measure="кг" />
			</td>
		</tr>
	</table>
	<table width="100%">
		<tr>
			<td><p class="pad3"><strong>Телосложение:</strong></p></td>
		</tr>
		<tr>
			<td class="valign1" align="center">
				<input type="hidden" name="partner_body[]" value="0">
				<table class="pad13">
					@foreach($partnerBody as $item)
					<tr>
						<td><input type="checkbox" name="partner_body[]" value="{{ $item->id }}"@if (!empty($item->selected)) checked="checked"@endif /></td>
						<td class="left1">{{ $item->name }}</td>
					</tr>
					@endforeach
				</table>
			</td>
		</tr>
	</table>
	<p class="pad2"></p>
	<h4 class="menu_registration"><div>Дополнительная информация</div></h4>
	<p class="pad1 pad2">Опишите <strong>привычки</strong> партнера. Какими он должен владеть <strong>языками</strong>.</p>
	<table width="100%">
		<tr>
			<td class="valign1" align="center">
				<input type="hidden" name="partner_languages[]" value="0">
				<table class="pad13">
					<tr>
						<td colspan="4"><p class="pad3"><strong>Знание языков:</strong></p></td>
					</tr>
					<x-checkbox name="partner_languages[]" :obj="$partnerLanguages" />
					<tr>
						<td colspan="4"><p class="pad5 pad3"><strong>Отношение к спиртному:</strong></p></td>
					</tr>
					<x-checkbox name="partner_alcohol[]" :obj="$partnerAlcohol" />
					<tr>
						<td colspan="4"><p class="pad5 pad3"><strong>Отношение к сигаретам:</strong></p></td>
					</tr>
					<x-checkbox name="partner_smoke[]" :obj="$partnerSmoke" />
					<tr>
						<td colspan="4"><p class="pad5 pad3"><strong>Образование:</strong></p></td>
					</tr>
					<x-checkbox name="partner_education[]" :obj="$partnerEducation" />
				</table>
			</td>
		</tr>
	</table>
	<p class="pad2"></p>
	<h4 class="menu_registration"><div>Место жительства</div></h4>
	<p class="pad1 pad2">Укажите <strong>город, регион и страну</strong>, где живет ваш партнер. Если хотите найти партнера, например, только из России, то укажите только Россию. Если интересует конкретный
регион, укажите страну и регион соответственно.</p>
	<table class="cityRegForm">
		<tr>
			<td width="150">страна</td>
			<td>
				<x-select name="partner_country" id="country" :obj="$fields['country']" userProp="{{ old('partner_country', $userData->partner_country) }}">
					<x-slot:firstInList><option value="141">Россия</option></x-slot>
					<x-slot:addition>onchange="updateSelect('region', this.value, 'reg');"</x-slot:addition>
				</x-select>
			</td>
		</tr>
		<tr>
			<td width="150">регион</td>
			<td>
				<x-select name="partner_region" id="region" :obj="$fields['region']" userProp="{{ old('partner_region', $userData->partner_region) }}">
					<x-slot:addition>onchange="updateSelect('city', this.value, 'cities');"</x-slot:addition>
				</x-select>
			</td>
		</tr>
		<tr>
			<td width="150">город</td>
			<td>
				<x-select name="partner_city" id="city" :obj="$fields['city']"  userProp="{{ old('partner_city', $userData->partner_city) }}" />
			</td>
		</tr>
	</table>
	<script type="text/javascript" src="{{ asset('js/functions_search.js') }}"></script>
	<p class="pad2"></p>						
	<h4 class="menu_registration"><div>Несколько слов о партнере</div></h4>
	<p class="pad1 pad2">Опишите человека, которого вы хотите встретить. Какие его черты для вас особенно важны? Что вы хотите найти в нем? Каковы цели вашего знакомства и планы на будущее? Чем
больше вы напишите, тем больше вероятность найти именно того, кто вам нужен.</p>
	<div>
		<x-textarea name="partner_description" value="{{ old('partner_description', $userData->partner_description) }}" />
	</div>
	<p class="pad2"></p>
	<input type="hidden" name="otsil" value="1" />
	<p class="pad2"></p>
	<p class="pad3"><x-submit name=sent value="изменить данные" /></p>
</form>
<p class="pad2"></p>
@overwrite