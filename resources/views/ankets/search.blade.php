@extends('layouts.app')
@section('title', $title)
@section('main_body')
@if ($isSend == 'Y')
<h1 class="mTit">Результаты поиска</h1>
					<p>{!! $critsSearch !!}</p>
					<p><a class="lColor1" href="{{route('search')}}">изменить критерии поиска</a></p>
					<h3 class="titleSAnkets">{{ $countSearchAnkStr }}</h3>
@if (!empty($ankets))
	<div class="profile-list-container">
    @foreach ($ankets as $item)
	<x-profile-brief :item="$item" />
    @endforeach
	</div>
	<x-pagination :items="$ankets" />
@else
<p class="pad5"><strong>по вашему запросу ничего не найдено</strong></p>
@endif
@else
<h1 class="mTit">Поиск</h1>
<form name="anketa" action="{{route('search')}}" method="get" class="formSearch">
	<input type="hidden" name="send" value="1" />
	<div class="form-row">
		<label for="sex">я</label>
		<x-select name=sex :obj="$fields['sex']" :userProp="old('sex')" id="sex" />
	</div>
	<div class="form-row">
		<label for="find_sex">ищу</label>
		<x-select id="find_sex" name="find_sex" :obj="$fields['findSex']" :userProp="old('find_sex')" />
	</div>
	<div class="form-row">
		<label>в возрасте</label>
		<div class="range">
			<label for="age-min">от</label>
			<x-select id="age-min" name="age_min" :obj="$fields['age']" :userProp="old('age_min')" />
			<label for="age-max">до</label>
			<x-select id="age-max" name="age_max" :obj="$fields['age']" :userProp="old('age_max')" />
		</div>
	</div>
	<div class="form-row">
		<label for="country">страна</label>
		<x-select name="country" id="country" :obj="$fields['country']" :userProp="old('country')">
			<x-slot:firstInList><option value="141">Россия</option></x-slot>
			<x-slot:addition>onchange="updateSelect('region', this.value, 'reg');"</x-slot:addition>
		</x-select>
	</div>
	<div class="form-row">
		<label for="region">регион</label>
		<x-select name="region" id="region" :obj="[]" :userProp="old('region')">
			<x-slot:addition>onchange="updateSelect('city', this.value, 'cities');"</x-slot:addition>
		</x-select>
	</div>
	<div class="form-row">
		<label for="city">город</label>
		<x-select name="city" id="city" :obj="[]" />
	</div>
	<div class="line-title-pink mb-1">Отобрать только</div>
	<div class="form-row">
		<label>рост</label>
		<div class="range">
			<label for="height-min">от</label>
			<x-select id="height-min" name="height_min" :obj="$fields['height']" :userProp="old('height_min')" measure="см" />
			<label for="height-max">до</label>
			<x-select id="height-max" name="height_max" :obj="$fields['height']" :userProp="old('height_max')" measure="см" />
		</div>
	</div>
	<div class="form-row">
		<label>вес</label>
		<div class="range">
			<label for="weight_min">от</label>
			<x-select id="weight_min" name="weight_min" :obj="$fields['weight']" :userProp="old('weight_min')" measure="кг" />
			<label for="weight-max">до</label>
			<x-select id="weight_max" name="weight_max" :obj="$fields['weight']" :userProp="old('weight_max')" measure="кг" />
		</div>
	</div>
	<div class="form-row">
		<label for="body">телосложение</label>
		<x-select id="body" name="body" :obj="$fields['body']" :userProp="old('body')" />
	</div>
	<div class="form-row">
		<label for="hair_type">тип волос</label>
		<x-select id="hair_type" name="hair_type" :obj="$fields['hairType']" :userProp="old('hair_type')"  />
	</div>
	<div class="form-row">
		<label for="eyes">глаза</label>
		<x-select id="eyes" name="eyes" :obj="$fields['eyes']" :userProp="old('eyes')"  />
	</div>
	<div class="form-row form-row-checkbox">
		<label for="photo">только с фото</label>
		<input type="checkbox" id="photo" name="photo" value="1" />
	</div>
	<div class="form-row form-row-checkbox">
		<label for="online">на сайте</label>
		<input type="checkbox" id="online" name="online" value="1" />
	</div>
	<div class="form-row">
		<label for="per_page">анкет на странице</label>
		<x-select id="per_page" name="per_page" :obj="$fields['perPage']" :userProp="old('per_page')"  />
	</div>
	<div class="form-row">
		<x-submit name=sent value="найти" />
	</div>
</form>
<script type="text/javascript" src="{{ asset('js/functions_search.js') }}"></script>
@endif
@overwrite