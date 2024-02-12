@extends('layouts.app')
@section('title', $title)
@section('main_body')
<h1 class="mTit">Редактирование данных</h1>
@include('registration.menu')
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
{{ csrf_field() }}
	<h4 class="menu_registration"><div>Имя</div></h4>
		<p class="pad1 pad2">Укажите Ваше <strong>настоящее имя</strong>. Если вы его не помните, то придумайте псевдоним. Именно по нему (а не по Логину) вас будут узнавать на сайте.</p>
@if ($errors->comment->has('name'))
	@foreach ($errors->comment->get('name') as $item)
	<p class="blue2">{{ $item }}</p>
	@endforeach
@endif
@if ($errors->comment->has('sex'))
	@foreach ($errors->comment->get('sex') as $item)
	<p class="blue2">{{ $item }}</p>
	@endforeach
@endif
		<table class="sexRegForm">
			<tr>
				<td rowspan="2"><input class="input3" type="text" name="name" value="{{ old('name', $userData->user_name) }}" /></td>
				<td class="pad11"><input type="radio" name="sex" value="1"@if (old('sex', $userData->user_sex) == 1) checked="checked"@endif /></td>
				<td><span class="menuMenReg">Я мужчина</span></td>
				<td><p class="pad12">дата рождения</p></td>
			</tr>
			<tr>
				<td class="pad11"><input type="radio" name="sex" value="2"@if (old('sex', $userData->user_sex) == 2) checked="checked"@endif /></td>
				<td><span class="menuWomenReg">Я женщина</span></td>
				<td>
					<select name="birth_day" style="width:40px;">
						@foreach ($days as $item)
						@if ($item == 0)
							<option value="0"@if (old('birth_day', $userData->birth_day) == 0) selected="selected"@endif>---</option>
						@else
							<option value="{{ $item }}"@if (old('birth_day', $userData->birth_day) == $item) selected="selected"@endif>{{ $item }}</option>
						@endif
						@endforeach
					</select>
					<select name="birth_month" style="width:80px;">
						@foreach ($months as $id => $item)
							<option value="{{ $id }}"@if (old('birth_month', $userData->birth_month) == $id) selected="selected"@endif>{{ $item }}</option>
						@endforeach
					</select>
					<select name="birth_year" style="width:50px;">
					@foreach ($years as $id => $item)
					@if ($item == 1900)
						<option value="{{ $item }}"@if (old('birth_year', $userData->birth_year) == 1990) selected="selected"@endif>---</option>
					@else
						<option value="{{ $item }}"@if (old('birth_year', $userData->birth_year) == $item) selected="selected"@endif>{{ $item }}</option>
					@endif
					@endforeach
					</select>
				</td>
			</tr>
		</table>
		<p class="pad2"></p>
		<h4 class="menu_registration"><div>Место жительства</div></h4>
		<p class="pad1 pad2">Укажите <strong>город, регион и страну</strong>, в которой вы живете. Это поможет другим пользователям сайта, которые тоже живут рядом с вами, быстрее вас найти.</p>
		@if ($errors->comment->has('city'))
		@foreach ($errors->comment->get('city') as $item)
		<p class="blue2">{{ $item }}</p>
		@endforeach
		@endif
		<table class="cityRegForm">
			<tr>
				<td width="150">страна</td>
				<td><select name="country" id="country" onchange="updateSelect('region', this.value, 'reg');" autocomplete="off">
					<option value="0" @if (old('country', $userData->user_country) == 0) selected="selected"@endif>выберите&nbsp;</option>
					<option value="141" @if (old('country', $userData->user_country) == COUNTRY_ID_RUSSIA) selected="selected"@endif>Россия</option>
					@foreach ($countries as $item)
					<option value="{{ $item->id }}" @if (old('country', $userData->user_country) == $item->id && old('country', $userData->user_country) != COUNTRY_ID_RUSSIA) selected="selected"@endif>{{ $item->name }}</option>
					@endforeach
				</select>
			</td>
		</tr>
		<tr>
			<td width="150">регион</td>
			<td><select name="region" id="region" onchange="updateSelect('city', this.value, 'cities');" autocomplete="off">
					<option value="0">не важно</option>
					@if (!empty($regions))
					@foreach ($regions as $item)
						<option value="{{ $item->id }}"@if (old('region', $userData->user_region) == $item->id) selected="selected"@endif>{{ $item->name }}</option>
					@endforeach
					@endif
				</select></td>
		</tr>
		<tr>
			<td width="150">город</td>
			<td><select id="city" name="city" autocomplete="off">
					<option value="0">не важно</option>
					@if (!empty($cities))
					@foreach ($cities as $item)
						<option value="{{ $item->id }}"@if (old('city', $userData->user_city) == $item->id) selected="selected"@endif>{{ $item->name }}</option>
					@endforeach
					@endif
				</select>
			</td>
		</tr>
	</table>
	<script type="text/javascript" src="{{ asset('js/functions_search.js') }}"></script>
	<p class="pad2"></p>
	<input type="hidden" name="otsil" value="1" />
	<p class="pad2"></p>
	<p class="pad3"><input type="submit" name="sent" class="bgBut8" value="" /></p>
</form>
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
@overwrite