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
<table class="searchTable">
	<tr>
		<td class="right1">я</td>
		<td>
			<x-select name=sex :obj="$fields['sex']" :userProp="old('sex')" />
		</td>
	</tr>
	<tr>
		<td class="right1">ищу</td>
		<td>
			<x-select name=find_sex :obj="$fields['findSex']" :userProp="old('find_sex')" />
		</td>
	</tr>
	<tr>
		<td class="right1">в возрасте</td>
		<td>от&nbsp; 
			<x-select name=age_min :obj="$fields['age']" :userProp="old('age_min')" />
			&nbsp;&nbsp;до&nbsp;
			<x-select name=age_max :obj="$fields['age']" :userProp="old('age_max')" />
		</td>
	</tr>
	<tr>
		<td class="right1">страна</td>
		<td>
			<x-select name=country id="country" :obj="$fields['country']" :userProp="old('country')">
				<x-slot:firstInList><option value="141">Россия</option></x-slot>
				<x-slot:addition>onchange="updateSelect('region', this.value, 'reg');"</x-slot:addition>
			</x-select>
		</td>
	</tr>
	<tr>
		<td class="right1">регион</td>
		<td>
			<x-select name=region id="region" :obj="[]" :userProp="old('region')">
				<x-slot:addition>onchange="updateSelect('city', this.value, 'cities');"</x-slot:addition>
			</x-select>
		</td>
	</tr>
	<tr>
		<td class="right1">город</td>
		<td>
			<x-select name=city id="city" :obj="[]" />
		</td>
	</tr>
</table>
<p class="pad3"></p>
<table class="searchTable wth3 pad6">
	<tr><td class="otobr">Отобрать только</td></tr>	
</table>
<table class="searchTable">
	<tr>
		<td class="right1">рост</td>
		<td>
			от&nbsp; 
			<x-select name=height_min :obj="$fields['height']" :userProp="old('height_min')" measure="см" />
			&nbsp;&nbsp;до&nbsp;
			<x-select name=height_max :obj="$fields['height']" :userProp="old('height_max')" measure="см" />
		</td>
	</tr>
	<tr>
		<td class="right1">вес</td>
		<td>
			от&nbsp; 
			<x-select name=weight_min :obj="$fields['weight']" :userProp="old('weight_min')" measure="кг" />
			&nbsp;&nbsp;до&nbsp;
			<x-select name=weight_max :obj="$fields['weight']" :userProp="old('weight_max')" measure="кг" />
		</td>
	</tr>
	<tr>
		<td class="right1">телосложение</td>
		<td><x-select name=body :obj="$fields['body']" :userProp="old('body')" /></td>
	</tr>
	<tr>
		<td class="right1">тип волос</td>
		<td><x-select name=hair_type :obj="$fields['hairType']" :userProp="old('hair_type')"  /></td>
	</tr>
	<tr >
		<td class="right1">глаза</td>
		<td><x-select name=eyes :obj="$fields['eyes']" :userProp="old('eyes')"  /></td>
	</tr>
	<tr>
		<td class="right1">только с фото</td>
		<td><input type="checkbox" name="photo" value="1" /></td>
	</tr>
	<tr>
		<td class="right1">на сайте</td>
		<td><input type="checkbox" name="online" value="1" /></td>
	</tr>
	<tr>
		<td class="right1">анкет на странице</td>
		<td>
			<x-select name=per_page :obj="$fields['perPage']" :userProp="old('per_page')"  />
		</td>
	</tr>
	<tr><td align="center" colspan="2">
		<x-submit name=sent value="найти" />
	</td></tr>
</table>
</form>
<script type="text/javascript" src="{{ asset('js/functions_search.js') }}"></script>
@endif
@overwrite