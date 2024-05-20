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
<p class="pad1"></p>
<form name="anketa" action="{{route('registration.edit.second')}}" method="post">
{{ csrf_field() }}
	<h4 class="menu_registration"><div>Базовая информация</div></h4>
	<p class="pad1 pad2">По умолчанию ваша ориентация указана как <strong>гетеросексуал</strong>, но вы можете ее изменить в любой момент. Также вам следует указать <strong>цели вашего знакомства</strong>,
чтобы узнать для чего вы, собственно говоря, здесь. Ну и про <strong>языки</strong> не забудьте. А то вдруг окажется, что ваш спутник или спутница говорит на совершенно непонятном для вас языке.</p>
	<p class="pad3"><strong>Сексуальная ориентация</strong></p>
	<p class="pad3">
		@include('blocks.select', ['name' => 'sex_orient', 'obj' => $sexOrient])
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
			<td width="50%">@include('blocks.select', ['name' => 'body', 'obj' => $body])</td>
		</tr>
		<tr>
			<td align="right">Рост:</td>
			<td>@include('blocks.select', ['name' => 'height', 'obj' => $heights, 'type' => 'I', 'UserProp' => $userData->user_height, 'measure' => 'см'])</td>
		</tr>
		<tr>
			<td align="right">Вес:</td>
			<td>@include('blocks.select', ['name' => 'weight', 'obj' => $weights, 'type' => 'I', 'UserProp' => $userData->user_weight, 'measure' => 'кг'])</td>
		</tr>
		<tr>
			<td align="right">Цвет волос:</td>
			<td>@include('blocks.select', ['name' => 'hair_color', 'obj' => $hairColor])</td>
		</tr>
		<tr>
			<td	align="right">Тип волос:</td>
			<td>@include('blocks.select', ['name' => 'hair_type', 'obj' => $hairType])</td>
		</tr>
		<tr>
			<td align="right">Глаза:</td>
			<td>@include('blocks.select', ['name' => 'eyes', 'obj' => $eyes])</td>
		</tr>
	</table>
	<p class="pad2"></p>
	<h4 class="menu_registration"><div>Дополнительная информация</div></h4>
	<p class="pad1 pad2">Опишите свои <strong>привычки, социальный статус</strong>. Какие у вас <strong>интересы</strong> и чем вы любите заниматься.</p>
	<table class="mrg6" width="100%">
		<tr>
			<td align="right" width="50%">Образование:</td>
			<td>@include('blocks.select', ['name' => 'education', 'obj' => $education])</td>
		</tr>
		<tr>
			<td align="right">Отношение к сигаретам:</td>
			<td>@include('blocks.select', ['name' => 'smoke', 'obj' => $smoke])</td>
		</tr>
		<tr>
			<td align="right">Отношение к спиртному:</td>
			<td>@include('blocks.select', ['name' => 'spirt', 'obj' => $spirt])</td>
		</tr>
		<tr>
			<td align="right">Семейное положение:</td>
			<td>@include('blocks.select', ['name' => 'family_status', 'obj' => $familyStatus])</td>
		</tr>
		<tr>
			<td align="right">Дети:</td>
			<td>@include('blocks.select', ['name' => 'children', 'obj' => $children])</td>
		</tr>
		<tr>
			<td align="right">Материальная поддержка:</td>
			<td>@include('blocks.select', ['name' => 'help_money', 'obj' => $helpMoney])</td>
		</tr>
		<tr>
			<td align="right">Интересы:</td>
			<td>@include('blocks.select', ['name' => 'interest[]', 'obj' => $interest, 'multiple' => true, 'size' => 10])</td>
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