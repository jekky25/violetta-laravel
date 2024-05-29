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
{{ csrf_field() }}
	<h4 class="menu_registration"><div>Внешние данные</div></h4>
	<p class="pad1 pad2">Если у вас есть конкретные пожелания к внешности вашего партнера - укажите их здесь.</p>
	<x-error errName=age />
	<table class="mrg6" width="100%">
		<tr>
			<td align="right" width="50%">Возраст:</td>
			<td width="50%">
				от&nbsp;
				@include('blocks.select', ['name' => 'partner_age_min', 'obj' => $age, 'type' => 'I', 'UserProp' => old('partner_age_min', $userData->user_partner_age_min)])
				&nbsp;до&nbsp;
				@include('blocks.select', ['name' => 'partner_age_max', 'obj' => $age, 'type' => 'I', 'UserProp' => old('partner_age_max', $userData->user_partner_age_max)])
			</td>
		</tr>
		<tr>
			<td align="right" width="50%">Рост:</td>
			<td width="50%">
				от&nbsp;
				@include('blocks.select', ['name' => 'partner_height_min', 'obj' => $heights, 'type' => 'I', 'UserProp' => old('partner_height_min', $userData->user_partner_height_min), 'measure' => 'см'])
				&nbsp;до&nbsp;
				@include('blocks.select', ['name' => 'partner_height_max', 'obj' => $heights, 'type' => 'I', 'UserProp' => old('partner_height_max', $userData->user_partner_height_max), 'measure' => 'см'])
			</td>
		</tr>
		<tr>
			<td align="right" width="50%">Вес:</td>
			<td width="50%">
				от&nbsp;
				@include('blocks.select', ['name' => 'partner_weight_min', 'obj' => $weights, 'type' => 'I', 'UserProp' => old('partner_weight_min', $userData->user_partner_weight_min), 'measure' => 'кг'])
				&nbsp;до&nbsp;
				@include('blocks.select', ['name' => 'partner_weight_max', 'obj' => $weights, 'type' => 'I', 'UserProp' => old('partner_weight_max', $userData->user_partner_weight_max), 'measure' => 'кг'])
			</td>
		</tr>
	</table>
	<table width="100%">
		<tr>
			<td><p class="pad3"><strong>Телосложение:</strong></p></td>
		</tr>
		<tr>
			<td class="valign1" align="center">
				<table class="pad13">
					@foreach ($partnerBody as $item)
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
				<table class="pad13">
					<tr>
						<td colspan="4"><p class="pad3"><strong>Знание языков:</strong></p></td>
					</tr>
					@include('blocks.checkbox', ['name' => 'partner_speak_lang[]', 'obj' => $partnerSpeakLang])
					<tr>
						<td colspan="4"><p class="pad5 pad3"><strong>Отношение к спиртному:</strong></p></td>
					</tr>
					@include('blocks.checkbox', ['name' => 'partner_spirt[]', 'obj' => $partnerSpirt])
					<tr>
						<td colspan="4"><p class="pad5 pad3"><strong>Отношение к сигаретам:</strong></p></td>
					</tr>
					@include('blocks.checkbox', ['name' => 'partner_smoke[]', 'obj' => $partnerSmoke])
					<tr>
						<td colspan="4"><p class="pad5 pad3"><strong>Образование:</strong></p></td>
					</tr>
					@include('blocks.checkbox', ['name' => 'partner_education[]', 'obj' => $partnerEducation])
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
				<select name="country" id="country" onchange="updateSelect('region', this.value, 'reg');" autocomplete="off">
					<option value="0" @if (old('country', $userData->user_partner_country) == 0) selected="selected"@endif>выберите&nbsp;</option>
					<option value="141" @if (old('country', $userData->user_partner_country) == COUNTRY_ID_RUSSIA) selected="selected"@endif>Россия</option>
					@foreach ($countries as $item)
					<option value="{{ $item->id }}" @if (old('country', $userData->user_partner_country) == $item->id && old('country', $userData->user_partner_country) != COUNTRY_ID_RUSSIA) selected="selected"@endif>{{ $item->name }}</option>
					@endforeach
				</select>
			</td>
		</tr>
		<tr>
			<td width="150">регион</td>
			<td>
				<select name="region" id="region" onchange="updateSelect('city', this.value, 'cities');">
					<option value="0">не важно</option>
					@if (!empty($regions))
					@foreach ($regions as $item)
					<option value="{{ $item->id }}"@if (old('region', $userData->user_partner_region) == $item->id) selected="selected"@endif>{{ $item->name }}</option>
					@endforeach
					@endif
				</select>
			</td>
		</tr>
		<tr>
			<td width="150">город</td>
			<td>
				<select id="city" name="city">
					<option value="0">не важно</option>
					@if (!empty($cities))
					@foreach ($cities as $item)
					<option value="{{ $item->id }}"@if (old('city', $userData->user_partner_city) == $item->id) selected="selected"@endif>{{ $item->name }}</option>
					@endforeach
					@endif
				</select>
			</td>
		</tr>
	</table>
	<script type="text/javascript" src="{{ asset('js/functions_search.js') }}"></script>
	<p class="pad2"></p>						
	<h4 class="menu_registration"><div>Несколько слов о партнере</div></h4>
	<p class="pad1 pad2">Опишите человека, которого вы хотите встретить. Какие его черты для вас особенно важны? Что вы хотите найти в нем? Каковы цели вашего знакомства и планы на будущее? Чем
больше вы напишите, тем больше вероятность найти именно того, кто вам нужен.</p>
	<div>
		<textarea class="textarea2" name="description" wrap="virtual">{{ old('description', $userData->user_partner_description) }}</textarea>
	</div>
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
<p class="pad2"></p>
@overwrite