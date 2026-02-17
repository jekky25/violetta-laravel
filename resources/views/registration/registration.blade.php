@extends('layouts.app')
@section('title', $title)
@section('main_body')
<h1 class="mTit">Регистрация</h1>
<p class="pad2">Зарегистрировавшись на нашем <strong>сайте знакомств</strong> вы сможете:!</p>
<ul>
	<li class="decim2">добавить анкету в <strong>каталог знакомств</strong>.</li>
	<li class="decim2">осуществлять поиск в каталоге анкет.</li>
	<li class="decim2">без ограничений общаться со всеми пользователями сайта. </li>
</ul>
<p class="pad2"></p>
<p class="pad2">Регистрация на нашем сайте знакомств абсолютно бесплатна.</p>
<h4 class="reg_title">Введите пожалуйста свои данные</h4>
@if (!empty ($errors->comment->all()))
	<h4 class="reg_title2">вы не зарегистрированы, т. к. не все поля правильно заполнены</h4>
@endif
<p class="pad2"></p>
<form name="anketa" action="{{ route('registration.post') }}" method="post">
{{ csrf_field() }}
	<h4 class="menu_registration"><div>Login</div></h4>
		<p class="pad1 pad2"><strong>Login</strong> - это ваше входное имя на сайте. Постарайтесь использовать легкозапоминающийся login, который вам будет трудно забыть. Также желательно, чтобы он состоял только из букв латинского алфавита и(или) цифр и не содержал пробелов. Пример: "<strong>Kolya</strong>", "<strong>alla_15</strong>", "<strong>deniska</strong>" и т.д.</p>
		<x-error errName=login />
		<p><x-input name="login" value="{{ old('login') }}" /></p>
		<p class="pad2"></p>
		<h4 class="menu_registration"><div>Пароль</div></h4>
		<p class="pad1 pad2">Введите и подтвердите <strong>пароль</strong>. Придумайте сложный пароль, который нельзя подобрать. По возможности, используйте в нем <strong>большые буквы и цифры</strong>, и <strong>не используйте русские буквы</strong>. Пароль и логин чувствительны к регистру, т.е. "<strong>znakomstvo</strong>" и "<strong>ZnaKomStvo</strong>" - два разных пароля.</p>
		<x-error errName=password />
		<p>
			<x-input name="password" type="password" value="{{ old('password') }}" />
			&nbsp;&nbsp;&nbsp;
			<x-input name="password_second" type="password" value="{{ old('password_second') }}" />
		</p>
		<p class="pad2"></p>
		<h4 class="menu_registration"><div>Имя</div></h4>
		<p class="pad1 pad2">Укажите Ваше <strong>настоящее имя</strong>. Если вы его не помните, то придумайте псевдоним. Именно по нему (а не по Логину) вас будут узнавать на сайте.</p>
		<x-error errName=name />
		<x-error errName=sex />
		<table class="sexRegForm">
			<tr>
				<td rowspan="2">
					<x-input name="name" value="{{ old('name') }}" />
				</td>
				<td class="pad11"><input type="radio" name="sex" value="1"@if (old('sex') == 1) checked="checked"@endif /></td>
				<td><span class="menuMenReg">Я мужчина</span></td>
				<td><p class="pad12">дата рождения</p></td>
			</tr>
			<tr>
				<td class="pad11"><input type="radio" name="sex" value="2"@if (old('sex') == 2) checked="checked"@endif /></td>
				<td><span class="menuWomenReg">Я женщина</span></td>
				<td>
					<x-select name="birth_day" :obj="$fields['day']" userProp="{{ old('birth_day') }}" fieldZero="---" />
					<x-select name="birth_month" :obj="$fields['month']" userProp="{{ old('birth_month') }}" />
					<x-select name="birth_year" :obj="$fields['year']" userProp="{{ old('birth_year') }}" fieldZero="---" />
				</td>
			</tr>
		</table>
		<p class="pad2"></p>
		<h4 class="menu_registration"><div>E-mail</div></h4>
		<p class="pad1 pad2">Введите <strong>свой Е-мэйл</strong>. Обещаем, что мы не будем его показывать никому кроме вас. Этот адрес необходим для связи с вами.</p>
		<x-error errName=email />
		<p><x-input name="email" value="{{ old('email') }}" /></p>
		<p class="pad2"></p>
		<h4 class="menu_registration"><div>Место жительства</div></h4>
						<p class="pad1 pad2">Укажите <strong>город, регион и страну</strong>, в которой вы живете. Это поможет другим пользователям сайта, которые тоже живут рядом с вами, быстрее вас найти.</p>
						<x-error errName=country />
						<table class="cityRegForm">
							<tr>
								<td width="150">страна</td>
								<td>
									<x-select name="country_id" id="country" :obj="$fields['country']" userProp="{{ old('country_id') }}">
										<x-slot:firstInList><option value="141">Россия</option></x-slot>
										<x-slot:addition>onchange="updateSelect('region', this.value, 'reg');"</x-slot:addition>
									</x-select>
								</td>
							</tr>
							<tr>
								<td width="150">регион</td>
								<td>
									<x-select name="region_id" id="region" :obj="$fields['region']" userProp="{{ old('region_id') }}">
										<x-slot:addition>onchange="updateSelect('city', this.value, 'cities');"</x-slot:addition>
									</x-select>
								</td>
							</tr>
							<tr>
								<td width="150">город</td>
								<td>
									<x-select name="city_id" id="city" :obj="$fields['city']"  userProp="{{ old('city_id') }}" />
								</td>
							</tr>
						</table>
						<script type="text/javascript" src="{{ asset('js/functions_search.js') }}"></script>
						<p class="pad2"></p>
						<x-google-captcha />
						<x-error errName=recaptcha_response before='<h4 class="menu_registration"><div>Антиробот</div></h4>' after='<p class="pad2"></p>' />
						<h4 class="menu_registration"></h4>
						<x-error errName=conditions before='<p class="pad2"></p>' />
						<p class="pad1 pad3"><input type="checkbox" name="conditions" @if (!empty(old('conditions'))) checked="checked"@endif />&nbsp;&nbsp;&nbsp; я соглашаюсь с условиями, изложенными в <a href="{{ route('conditions') }}" class="lColor2">правилах регистрации анкет</a> и даю согласие на обработку персональных данных.</p>
						<input type="hidden" name="otsil" value="1" />
						<p class="pad2"></p>
						<p class="pad3">
							<x-submit name=sent value="зарегистрироваться" />
						</p>
					</form>
@overwrite