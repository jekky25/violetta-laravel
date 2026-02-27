@extends('layouts.app')
@section('title', $title)
@section('main_body')
<h1 class="mTit">Редактирование данных</h1>
<x-menu />
<h4 class="reg_title">Введите пожалуйста свои данные</h4>
@if(session('success'))
<h4 class="reg_title2">информация сохранена</h4>
@endif
<form name="anketa" class="pad2" action="{{route('registration.edit.second')}}" method="post">
@method('PUT')
{{ csrf_field() }}
	<h4 class="menu_registration"><div>Базовая информация</div></h4>
	<p class="pad1 pad2">По умолчанию ваша ориентация указана как <strong>гетеросексуал</strong>, но вы можете ее изменить в любой момент. Также вам следует указать <strong>цели вашего знакомства</strong>,
чтобы узнать для чего вы, собственно говоря, здесь. Ну и про <strong>языки</strong> не забудьте. А то вдруг окажется, что ваш спутник или спутница говорит на совершенно непонятном для вас языке.</p>
	<p class="pad3"><strong>Сексуальная ориентация</strong></p>
	<p class="pad3">
		<x-select name="sex_orient" :obj="$fields->sexOrient(old('sex_orient', $fields->user()->sex_orient))" userProp="{{ old('sex_orient', $fields->user()->sex_orient) }}" />
	</p>
	<div class="row-flex pad2">
		<div class="col-2 sm-col-1 mb-1">
				<x-checkbox-container name="targets[]" :obj="$fields->targets($fields->user()->targets)" title="Цели знакомства"></x-checkbox-container>
		</div>
		<div class="col-2 sm-col-1">
			<x-checkbox-container name="speak_lang[]" :obj="$fields->languages($fields->user()->speak_lang)" title="Знание языков" colums="2"></x-checkbox-container>
		</div>
	</div>
	<h4 class="menu_registration"><div>Внешность</div></h4>
	<p class="pad1 pad2">Постарайтесь заполнить все поля, касающиеся <strong>вашей внешности</strong>. Это особенно поможет, если у вас нет возможности разместить свою фотографию на нашем сайте знакомств.</p>
	<div class="form-row">
		<label for="body">Телосложение:</label>
		<x-select id="body" name="body" :obj="$fields->body(old('body', $fields->user()->body))" userProp="{{ old('body', $fields->user()->body) }}" />
	</div>
	<div class="form-row">
		<label for="height">Рост:</label>
		<x-select id="height" name="height" :obj="$fields->height()" type=I userProp="{{ $fields->user()->height }}" measure="см" />
	</div>
	<div class="form-row">
		<label for="weight">Вес:</label>
		<x-select id="weight" name="weight" :obj="$fields->weight()" type=I userProp="{{ $fields->user()->weight }}" measure="кг" />
	</div>
	<div class="form-row">
		<label for="hair_color">Цвет волос:</label>
		<x-select id="hair_color" name="hair_color" :obj="$fields->hairColor(old('hair_color', $fields->user()->hair_color))" userProp="{{ old('hair_color', $fields->user()->hair_color) }}" />
	</div>
	<div class="form-row">
		<label for="hair_type">Тип волос:</label>
		<x-select id="hair_type" name="hair_type" :obj="$fields->hairType(old('hair_type', $fields->user()->hair_type))" userProp="{{ old('hair_type', $fields->user()->hair_type) }}" />
	</div>
	<div class="form-row pad2">
		<label for="eyes">Глаза:</label>
		<x-select id="eyes" name="eyes" :obj="$fields->eyes(old('eyes', $fields->user()->eyes))" userProp="{{ old('eyes', $fields->user()->eyes) }}" />
	</div>
	<h4 class="menu_registration"><div>Дополнительная информация</div></h4>
	<p class="pad1 pad2">Опишите свои <strong>привычки, социальный статус</strong>. Какие у вас <strong>интересы</strong> и чем вы любите заниматься.</p>
	<div class="form-row">
		<label for="education">Образование:</label>
		<x-select id="education" name="education" :obj="$fields->education(old('education', $fields->user()->education))" userProp="{{ old('education', $fields->user()->education) }}" />
	</div>
	<div class="form-row">
		<label for="smoke">Отношение к сигаретам:</label>
		<x-select id="smoke" name="smoke" :obj="$fields->smoke(old('smoke', $fields->user()->smoke))" userProp="{{ old('smoke', $fields->user()->smoke) }}" />
	</div>
	<div class="form-row">
		<label for="alcohol">Отношение к спиртному:</label>
		<x-select id="alcohol" name="alcohol" :obj="$fields->alcohol(old('alcohol', $fields->user()->alcohol))" userProp="{{ old('alcohol', $fields->user()->alcohol) }}" />
	</div>
	<div class="form-row">
		<label for="family_status">Семейное положение:</label>
		<x-select id="family_status" name="family_status" :obj="$fields->familyStatus(old('family_status', $fields->user()->family_status))" userProp="{{ old('family_status', $fields->user()->family_status) }}" />
	</div>
	<div class="form-row">
		<label for="children">Дети:</label>
		<x-select id="children" name="children" :obj="$fields->children(old('children', $fields->user()->children))" userProp="{{ old('children', $fields->user()->children) }}" />
	</div>
	<div class="form-row">
		<label for="help_money">Материальная поддержка:</label>
		<x-select id="help_money" name="help_money" :obj="$fields->helpMoney(old('help_money', $fields->user()->help_money))" userProp="{{ old('help_money', $fields->user()->help_money) }}" />
	</div>
	<div class="form-row pad2">
		<label for="interests">Интересы:</label>
		<x-select id="interests" name="interests[]" :obj="$fields->interests(old('interests', $fields->user()->interests))" multiple="true" size="10" userProp="{{ old('interests', $fields->user()->interests) }}" />
	</div>
	<h4 class="menu_registration"><div>Контактная информация</div></h4>
	<p class="pad1 pad2">То, что вы здесь напишите будет доступно <strong>только зарегистрированным пользователям</strong>. Поэтому убедительная просьба указывать только свои контактные данные.</p>
	<div class="form-row">
		<label for="url">Домашняя страничка:</label>
		<x-input id="url" name="url" value="{{ old('url', $fields->user()->url) }}" />
	</div>
	<div class="form-row">
		<label for="phone">Телефон:</label>
		<x-input id="phone" name="phone" value="{{ old('phone', $fields->user()->phone) }}" />
	</div>
	<div class="form-row pad2">
		<label for="icq">ICQ:</label>
		<x-input id="icq" name="icq" value="{{ old('icq', $fields->user()->icq) }}" />
	</div>
	<h4 class="menu_registration"><div>Несколько слов о вас</div></h4>
	<p class="pad1 pad2">Это самая важная часть анкеты. От того, как вы заполните это поле, очень сильно зависит <strong>успешность вашего знакомства</strong>. Отнеситесь к этому ответственно.  Опишите особенности, делающие вашу <strong>личность отличной от других</strong>. Постарайтесь <strong>избегать банальностей</strong>. Если вы любите кошек или вам нравится гулять под дождем - напишите об этом. Поверьте,
найдется со временем тот кто ищет именно вас.</p>
	<div class="pad2">
		<x-textarea name="description" value="{{ old('description', $fields->user()->description) }}" />
	</div>
	<input type="hidden" name="otsil" value="1" />
	<p class="pad3">
		<x-submit name="sent" value="изменить данные" />
	</p>
</form>
@overwrite