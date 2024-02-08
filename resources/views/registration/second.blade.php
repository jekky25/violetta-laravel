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
<p class="pad1"></p>
<form name="anketa" action="{{route('registration.edit.second')}}" method="post">
{{ csrf_field() }}
	<h4 class="menu_registration"><div>Базовая информация</div></h4>
	<p class="pad1 pad2">По умолчанию ваша ориентация указана как <strong>гетеросексуал</strong>, но вы можете ее изменить в любой момент. Также вам следует указать <strong>цели вашего знакомства</strong>,
чтобы узнать для чего вы, собственно говоря, здесь. Ну и про <strong>языки</strong> не забудьте. А то вдруг окажется, что ваш спутник или спутница говорит на совершенно непонятном для вас языке.</p>
	<p class="pad3"><strong>Сексуальная ориентация</strong></p>
	<p class="pad3">
		<select name="sex_orient">
			<option value="0">-выберите-</option>
			@foreach ($sexOrient as $item)
			<option value="{{ $item->id }}"@if (!empty($item->selected)) selected="selected"@endif>{{ $item->name }}</option>
			@endforeach
		</select>
	</p>
	<table width="100%">
		<tr>
			<td><p class="pad3"><strong>Цели знакомства</strong></p></td>
			<td><p class="pad3"><strong>Знание языков</strong></p></td>
		</tr>
		<tr>
			<td class="valign1" align="center">
				<table class="pad13">
				@foreach ($meetTarget as $item)
					<tr>
						<td><input type="checkbox" name="target_meet[]" value="{{ $item->id }}"@if (!empty($item->selected)) checked="checked"@endif /></td>
						<td class="left1">{{ $item->name }}</td>
					</tr>
				@endforeach
				</table>		
			</td>
			<td class="valign1" align="center">
				<table class="pad13">
					<tr>
						<td><input type="checkbox" name="speak_lang[]" value="1" @if (!empty($userSpeakLang['rus']['selected'])) checked="checked"@endif /></td>
						<td class="left1"><span>Русский</span></td>
						<td><input type="checkbox" name="speak_lang[]" value="2" @if (!empty($userSpeakLang['ukr']['selected'])) checked="checked"@endif /></td>
						<td class="left1"><span>Украинский</span></td>
					</tr>
					<tr>
						<td><input type="checkbox" name="speak_lang[]" value="3" @if (!empty($userSpeakLang['bel']['selected'])) checked="checked"@endif /></td>
						<td class="left1"><span>Белорусский</span></td>
						<td><input type="checkbox" name="speak_lang[]" value="4" @if (!empty($userSpeakLang['gru']['selected'])) checked="checked"@endif /></td>
						<td class="left1"><span>Грузинский</span></td>
					</tr>
					<tr>
						<td><input type="checkbox" name="speak_lang[]" value="5" @if (!empty($userSpeakLang['eng']['selected'])) checked="checked"@endif /></td>
						<td class="left1"><span>Английский</span></td>
						<td><input type="checkbox" name="speak_lang[]" value="6" @if (!empty($userSpeakLang['ger']['selected'])) checked="checked"@endif /></td>
						<td class="left1"><span>Немецкий</span></td>
					</tr>
					<tr>
						<td><input type="checkbox" name="speak_lang[]" value="7" @if (!empty($userSpeakLang['fra']['selected'])) checked="checked"@endif /></td>
						<td class="left1"><span>Французский</span></td>
						<td><input type="checkbox" name="speak_lang[]" value="8" @if (!empty($userSpeakLang['spa']['selected'])) checked="checked"@endif /></td>
						<td class="left1"><span>Испанский</span></td>
					</tr>
					<tr>
						<td><input type="checkbox" name="speak_lang[]" value="9" @if (!empty($userSpeakLang['ita']['selected'])) checked="checked"@endif /></td>
						<td class="left1"><span>Итальянский</span></td>
						<td><input type="checkbox" name="speak_lang[]" value="10" @if (!empty($userSpeakLang['chi']['selected'])) checked="checked"@endif /></td>
						<td class="left1"><span>Китайский</span></td>
					</tr>
					<tr>
						<td><input type="checkbox" name="speak_lang[]" value="11" @if (!empty($userSpeakLang['jap']['selected'])) checked="checked"@endif /></td>
						<td class="left1"><span>Японский</span></td>
						<td><input type="checkbox" name="speak_lang[]" value="12" @if (!empty($userSpeakLang['arm']['selected'])) checked="checked"@endif /></td>
						<td class="left1"><span>Армянский</span></td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
	<p class="pad2"></p>
	<h4 class="menu_registration"><div>Внешность</div></h4>
	<p class="pad1 pad2">Постарайтесь заполнить все поля, касающиеся <strong>вашей внешности</strong>. Это особенно поможет, если у вас нет возможности разместить свою фотографию на нашем сайте знакомств.</p>
	<table class="mrg6" width="100%">
		<tr>
			<td align="right" width="50%">Телосложение:</td>
			<td width="50%">
				<select class="select1" name="body" autocomplete="off">
					<option value="0">-не важно-</option>
					@foreach ($body as $item)
					<option value="{{ $item->id }}"@if (!empty($item->selected)) selected="selected"@endif>{{ $item->name }}</option>
					@endforeach
				</select>
			</td>
		</tr>
		<tr>
			<td align="right">Рост:</td>
			<td>
				<select class="select1" name="height" autocomplete="off">
					<option value="150">-не важно-</option>
					@foreach ($heights as $item)
					<option value="{{ $item }}" @if ($item == $userData->user_height) selected="selected"@endif>{{ $item }}</option>
					@endforeach
				</select>
			</td>
		</tr>
		<tr>
			<td align="right">Вес:</td>
			<td>
				<select class="select1" name="weight" autocomplete="off">
					<option value="30" >-не важно-</option>
					@foreach ($weights as $item)
					<option value="{{ $item }}"@if ($item == $userData->user_weight) selected="selected"@endif>{{ $item }}</option>
					@endforeach
				</select>
			</td>
		</tr>
		<tr>
			<td align="right">Цвет волос:</td>
			<td>
				<select class="select1" name="hair_color" autocomplete="off">
					<option value="0">-не важно-</option>
					@foreach ($hairColor as $item)
					<option value="{{ $item->id }}"@if (!empty($item->selected)) selected="selected"@endif>{{ $item->name }}</option>
					@endforeach
				</select>
			</td>
		</tr>
		<tr>
			<td	align="right">Тип волос:</td>
			<td>
				<select class="select1" name="hair_type" autocomplete="off">
					<option value="0">-не важно-</option>
					@foreach ($hairType as $item)
					<option value="{{ $item->id }}"@if (!empty($item->selected)) selected="selected"@endif>{{ $item->name }}</option>
					@endforeach
				</select>
			</td>
		</tr>
		<tr>
			<td align="right">Глаза:</td>
			<td>
				<select class="select1" name="eyes" autocomplete="off">
					<option value="0">-не важно-</option>
					@foreach ($eyes as $item)
					<option value="{{ $item->id }}"@if (!empty($item->selected)) selected="selected"@endif>{{ $item->name }}</option>
					@endforeach
				</select>
			</td>
		</tr>
	</table>
	<p class="pad2"></p>
	<h4 class="menu_registration"><div>Дополнительная информация</div></h4>
	<p class="pad1 pad2">Опишите свои <strong>привычки, социальный статус</strong>. Какие у вас <strong>интересы</strong> и чем вы любите заниматься.</p>
	<table class="mrg6" width="100%">
		<tr>
			<td align="right" width="50%">Образование:</td>
			<td width="50%">
				<select class="select1" name="education" autocomplete="off">
					<option value="0">-не важно-</option>
					@foreach ($education as $item)
					<option value="{{ $item->id }}"@if (!empty($item->selected)) selected="selected"@endif>{{ $item->name }}</option>
					@endforeach
				</select>
			</td>
		</tr>
		<tr>
			<td align="right">Отношение к сигаретам:</td>
			<td>
				<select class="select1" name="smoke" autocomplete="off">
					<option value="0">-не важно-</option>
					@foreach ($smoke as $item)
					<option value="{{ $item->id }}"@if (!empty($item->selected)) selected="selected"@endif>{{ $item->name }}</option>
					@endforeach
				</select>
			</td>
		</tr>
		<tr>
			<td align="right">Отношение к спиртному:</td>
			<td>
				<select class="select1" name="spirt" autocomplete="off">
					<option value="0">-не важно-</option>
					@foreach ($spirt as $item)
					<option value="{{ $item->id }}"@if (!empty($item->selected)) selected="selected"@endif>{{ $item->name }}</option>
					@endforeach
				</select>
			</td>
		</tr>
		<tr>
			<td align="right">Семейное положение:</td>
			<td>
				<select class="select1" name="family_status" autocomplete="off">
				<option value="0">-не важно-</option>
					@foreach ($familyStatus as $item)
					<option value="{{ $item->id }}"@if (!empty($item->selected)) selected="selected"@endif>{{ $item->name }}</option>
					@endforeach
				</select>
			</td>
		</tr>
		<tr>
			<td align="right">Дети:</td>
			<td>
				<select class="select1" name="children" autocomplete="off">
					<option value="0" selected>-не важно-</option>
					@foreach ($children as $item)
					<option value="{{ $item->id }}"@if (!empty($item->selected)) selected="selected"@endif>{{ $item->name }}</option>
					@endforeach
				</select>
			</td>
		</tr>
		<tr>
			<td align="right">Материальная поддержка:</td>
			<td>
				<select class="select1" name="help_money" autocomplete="off">
					<option value="0" selected>-не важно-</option>
					@foreach ($helpMoney as $item)
					<option value="{{ $item->id }}"@if (!empty($item->selected)) selected="selected"@endif>{{ $item->name }}</option>
					@endforeach
				</select>
			</td>
		</tr>
		<tr>
			<td align="right">Интересы:</td>
			<td>
				<select class="select1" name="interest[]" multiple size="10" autocomplete="off">
					<option value="0" >-не важно-</option>
					@foreach ($interest as $item)
						<option value="{{ $item->id }}"@if (!empty($item->selected)) selected="selected"@endif>{{ $item->name }}</option>
					@endforeach
				</select>
				<br />
				<p class="txt1">В Windows используйте клавишу <strong>ctrl</strong>,<br /> чтобы выделить несколько элементов списка.</p>
			</td>
		</tr>
	</table>
	<p class="pad2"></p>
	<h4 class="menu_registration"><div>Контактная информация</div></h4>
	<p class="pad1 pad2">То, что вы здесь напишите будет доступно <strong>только зарегистрированным пользователям</strong>. Поэтому убедительная просьба указывать только свои контактные данные.</p>
	<table class="mrg6" width="100%">
		<tr>
			<td align="right" width="50%">Домашняя страничка:</td>
			<td width="50%"><input class="select1" type="text" maxLength="70" size="30" name="url" value="{{ old('url', $userData->user_url) }}" /></td>
		</tr>
		<tr>
			<td align="right">Телефон:</td>
			<td><input class="select1" type="text" maxLength="70" size="30" name="phone" value="{{ old('phone', $userData->user_phone) }}" /></td>
		</tr>
		<tr>
			<td align="right">ICQ:</td>
			<td><input class="select1" type="text" maxLength="70" size="30" name="icq" value="{{ old('icq', $userData->user_icq) }}" /></td>
		</tr>
	</table>
	<p class="pad2"></p>
	<h4 class="menu_registration"><div>Несколько слов о вас</div></h4>
	<p class="pad1 pad2">Это самая важная часть анкеты. От того, как вы заполните это поле, очень сильно зависит <strong>успешность вашего знакомства</strong>. Отнеситесь к этому ответственно.  Опишите особенности, делающие вашу <strong>личность отличной от других</strong>. Постарайтесь <strong>избегать банальностей</strong>. Если вы любите кошек или вам нравится гулять под дождем - напишите об этом. Поверьте,
найдется со временем тот кто ищет именно вас.</p>
	<div>
		<textarea class="textarea2" name="description" wrap="virtual">{{ old('description', $userData->user_description) }}</textarea>
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