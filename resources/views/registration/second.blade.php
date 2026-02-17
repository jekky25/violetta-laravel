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
@method('PUT')
{{ csrf_field() }}
	<h4 class="menu_registration"><div>Базовая информация</div></h4>
	<p class="pad1 pad2">По умолчанию ваша ориентация указана как <strong>гетеросексуал</strong>, но вы можете ее изменить в любой момент. Также вам следует указать <strong>цели вашего знакомства</strong>,
чтобы узнать для чего вы, собственно говоря, здесь. Ну и про <strong>языки</strong> не забудьте. А то вдруг окажется, что ваш спутник или спутница говорит на совершенно непонятном для вас языке.</p>
	<p class="pad3"><strong>Сексуальная ориентация</strong></p>
	<p class="pad3">
		<x-select name="sex_orient" :obj="$sexOrient" userProp="{{ old('sex_orient', $userData->sex_orient) }}" />
	</p>
	<table width="100%">
		<tr>
			<td><p class="pad3"><strong>Цели знакомства</strong></p></td>
			<td><p class="pad3"><strong>Знание языков</strong></p></td>
		</tr>
		<tr>
			<td class="valign1" align="center">
				<table class="pad13">
					<input type="hidden" name="targets[]" value="0">
				@foreach ($targets as $item)
					<tr>
						<td><input type="checkbox" name="targets[]" value="{{ $item->id }}"@if (!empty($item->selected)) checked="checked"@endif /></td>
						<td class="left1">{{ $item->name }}</td>
					</tr>
				@endforeach
				</table>		
			</td>
			<td class="valign1" align="center">
				<input type="hidden" name="speak_lang[]" value="0">
				<table class="pad13">
					<tr>
						<td>
							<input type="checkbox" name="speak_lang[]" value="1" @if (!empty($userSpeakLang['rus']['selected'])) checked="checked"@endif />
						</td>
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
			<td width="50%"><x-select name="body" :obj="$body" userProp="{{ old('body', $userData->body) }}" /></td>
		</tr>
		<tr>
			<td align="right">Рост:</td>
			<td><x-select name="height" :obj="$heights" type=I :userProp="$userData->height" measure="см" /></td>
		</tr>
		<tr>
			<td align="right">Вес:</td>
			<td><x-select name="weight" :obj="$weights" type=I :userProp="$userData->weight" measure="кг" /></td>
		</tr>
		<tr>
			<td align="right">Цвет волос:</td>
			<td><x-select name="hair_color" :obj="$hairColor" userProp="{{ old('hair_color', $userData->hair_color) }}" /></td>
		</tr>
		<tr>
			<td	align="right">Тип волос:</td>
			<td><x-select name="hair_type" :obj="$hairType" userProp="{{ old('hair_type', $userData->hair_type) }}" /></td>
		</tr>
		<tr>
			<td align="right">Глаза:</td>
			<td><x-select name="eyes" :obj="$eyes" userProp="{{ old('eyes', $userData->eyes) }}" /></td>
		</tr>
	</table>
	<p class="pad2"></p>
	<h4 class="menu_registration"><div>Дополнительная информация</div></h4>
	<p class="pad1 pad2">Опишите свои <strong>привычки, социальный статус</strong>. Какие у вас <strong>интересы</strong> и чем вы любите заниматься.</p>
	<table class="mrg6" width="100%">
		<tr>
			<td align="right" width="50%">Образование:</td>
			<td><x-select name="education" :obj="$education" userProp="{{ old('education', $userData->education) }}" /></td>
		</tr>
		<tr>
			<td align="right">Отношение к сигаретам:</td>
			<td><x-select name="smoke" :obj="$smoke" userProp="{{ old('smoke', $userData->smoke) }}" /></td>
		</tr>
		<tr>
			<td align="right">Отношение к спиртному:</td>
			<td><x-select name="alcohol" :obj="$alcohol" userProp="{{ old('alcohol', $userData->alcohol) }}" /></td>
		</tr>
		<tr>
			<td align="right">Семейное положение:</td>
			<td><x-select name="family_status" :obj="$familyStatus" userProp="{{ old('family_status', $userData->family_status) }}" /></td>
		</tr>
		<tr>
			<td align="right">Дети:</td>
			<td><x-select name="children" :obj="$children" userProp="{{ old('children', $userData->children) }}" /></td>
		</tr>
		<tr>
			<td align="right">Материальная поддержка:</td>
			<td><x-select name="help_money" :obj="$helpMoney" userProp="{{ old('help_money', $userData->help_money) }}" /></td>
		</tr>
		<tr>
			<td align="right">Интересы:</td>
			<td><x-select name="interests[]" :obj="$interests" multiple="true" size="10" userProp="{{ old('interests', $userData->interests) }}" /></td>
		</tr>
	</table>
	<p class="pad2"></p>
	<h4 class="menu_registration"><div>Контактная информация</div></h4>
	<p class="pad1 pad2">То, что вы здесь напишите будет доступно <strong>только зарегистрированным пользователям</strong>. Поэтому убедительная просьба указывать только свои контактные данные.</p>
	<table class="mrg6" width="100%">
		<tr>
			<td align="right" width="50%">Домашняя страничка:</td>
			<td width="50%">
				<x-input name="url" value="{{ old('url', $userData->url) }}" />
			</td>
		</tr>
		<tr>
			<td align="right">Телефон:</td>
			<td>
				<x-input name="phone" value="{{ old('phone', $userData->phone) }}" />
			</td>
		</tr>
		<tr>
			<td align="right">ICQ:</td>
			<td>
				<x-input name="icq" value="{{ old('icq', $userData->icq) }}" />
			</td>
		</tr>
	</table>
	<p class="pad2"></p>
	<h4 class="menu_registration"><div>Несколько слов о вас</div></h4>
	<p class="pad1 pad2">Это самая важная часть анкеты. От того, как вы заполните это поле, очень сильно зависит <strong>успешность вашего знакомства</strong>. Отнеситесь к этому ответственно.  Опишите особенности, делающие вашу <strong>личность отличной от других</strong>. Постарайтесь <strong>избегать банальностей</strong>. Если вы любите кошек или вам нравится гулять под дождем - напишите об этом. Поверьте,
найдется со временем тот кто ищет именно вас.</p>
	<div>
		<x-textarea name="description" value="{{ old('description', $userData->description) }}" />
	</div>
	<p class="pad2"></p>
	<input type="hidden" name="otsil" value="1" />
	<p class="pad2"></p>
	<p class="pad3">
		<x-submit name=sent value="изменить данные" />
	</p>
</form>
<p class="pad2"></p>
@overwrite