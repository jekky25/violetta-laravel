@extends('layouts.app')
@section('title', $title)
@section('main_body')
<h1 class="mTit">Поиск</h1>
<form name="anketa" action="{{route('search')}}" method="get" class="formSearch">
<input type="hidden" name="mod" value="search" />
<input type="hidden" name="op" value="search" />
<table class="searchTable">
	<tr>
		<td class="right1">я</td>
		<td>
			<select name="sex"><option value="0" selected>не важно</option>
				<option value="{$smarty.const.MEN}">мужчина</option>
				<option value="{$smarty.const.WOMEN}">женщина</option>
			</select>
		</td>
	</tr>
	<tr>
		<td class="right1">ищу</td>
		<td>
			<select name="find_sex"><option value="0" selected>не важно</option>
				<option value="{$smarty.const.MEN}">мужчину</option>
				<option value="{$smarty.const.WOMEN}">женщину</option>
			</select>
		</td>
	</tr>
	<tr>
		<td class="right1">в возрасте</td>
		<td>от&nbsp; 
			<select name="age_min">
				<option value="15">не важно&nbsp;</option>
				@if (!empty($ages))
					@foreach ($ages as $item)
					<option value="{{ $item }}">{{ $item }}</option>
					@endforeach
				@endif
				</select>&nbsp;&nbsp;до&nbsp;
				<select name="age_max">
					<option value="15">не важно&nbsp;</option>
					@if (!empty($ages))
						@foreach ($ages as $item)
						<option value="{{ $item }}">{{ $item }}</option>
						@endforeach
					@endif
				</select>
		</td>
	</tr>
	<tr>
		<td class="right1">страна</td>
		<td>
			<select name="country" id="country" onchange="updateSelect('region', this.value, 'reg');">
				<option value="0">не важно&nbsp;</option>
				<option value="141">Россия</option>
				@if (!empty($countries))
					@foreach ($countries as $item)
					<option value="{{ $item->id }}">{{ $item->name }}</option>
					@endforeach
				@endif
			</select>
		</td>
	</tr>
	<tr>
		<td class="right1">регион</td>
		<td>
			<select name="region" id="region" onchange="updateSelect('city', this.value, 'cities');" >
				<option value="0">не важно</option>
			</select>
		</td>
	</tr>
	<tr>
		<td class="right1">город</td>
		<td>
			<select id="city" name="city">
				<option value="0">не важно</option>
			</select>
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
			от&nbsp; <select name="height_min">
				<option value="{{ PARTNER_HEIGHT_MIN }}">не важно&nbsp;</option>
				@if (!empty($heights))
					@foreach ($heights as $item)
					<option value="{{ $item }}">{{ $item }}</option>
					@endforeach
				@endif
			</select>
			&nbsp;&nbsp;до&nbsp;
			<select name="height_max">
				<option value="{{ PARTNER_HEIGHT_MAX }}">не важно&nbsp;</option>
				@if (!empty($heights))
					@foreach ($heights as $item)
					<option value="{{ $item }}">{{ $item }}</option>
					@endforeach
				@endif
			</select>
		</td>
	</tr>
	<tr>
		<td class="right1">вес</td>
		<td>
			от&nbsp; <select name="weight_min">
				<option value="{{ PARTNER_WEIGHT_MIN }}">не важно&nbsp;</option>
				@if (!empty($weights))
					@foreach ($weights as $item)
					<option value="{{ $item }}">{{ $item }}</option>
					@endforeach
				@endif
			</select>
			&nbsp;&nbsp;до&nbsp;
			<select name="weight_max">
				<option value="{{ PARTNER_WEIGHT_MIN }}">не важно&nbsp;</option>
				@if (!empty($weights))
					@foreach ($weights as $item)
					<option value="{{ $item }}">{{ $item }}</option>
					@endforeach
				@endif
			</select>
		</td>
	</tr>
	<tr>
		<td class="right1">телосложение</td>
		<td>@if (!empty($body)){!! $body !!}@endif</td>
	</tr>
	<tr>
		<td class="right1">тип волос</td>
		<td>@if (!empty($hairType)){!! $hairType !!}@endif</td>
	</tr>
	<tr >
		<td class="right1">глаза</td>
		<td>@if (!empty($eyes)){!! $eyes !!}@endif</td>
	</tr>
	<tr>
		<td class="right1">только с фото</td>
		<td><input type="checkbox" name="foto" /></td>
	</tr>
	<tr>
		<td class="right1">на сайте</td>
		<td><input type="checkbox" name="online" /></td>
	</tr>
	<tr>
		<td class="right1">анкет на странице</td>
		<td>
			<select name="anket_per_page">
				<option value="5">5</option>
				<option value="10" selected>10</option>
				<option value="20">20</option>
				<option value="30">30</option>
			</select>
		</td>
	</tr>
	<tr><td align="center" colspan="2"><input type="submit" name="sent" id="otsil" class="bgBut3" value="" /></td></tr>	
</table>
</form>
<script type="text/javascript" src="{{ asset('js/functions_search.js') }}"></script>
<div class="pad5">
<script type="text/javascript"><!--
google_ad_client = "ca-pub-6379140164632940";
/* 468x60 поиск внизу */
google_ad_slot = "1069383205";
google_ad_width = 468;
google_ad_height = 60;
//-->
</script>
<script type="text/javascript" async src="http://pagead2.googlesyndication.com/pagead/show_ads.js"></script>
</div>
@overwrite