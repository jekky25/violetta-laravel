@extends('layouts.app')
@section('title', $title)
@section('main_body')
@push('scripts')
<script type="text/javascript" src="https://www.google.com/recaptcha/api.js?render={{ RE_SITE_KEY }}"></script>
@endpush
<script>
        grecaptcha.ready(function () {
            grecaptcha.execute('{{ RE_SITE_KEY }}', { action: 'contact' }).then(function (token) {
                var recaptchaResponse = document.getElementById('recaptchaResponse');
                recaptchaResponse.value = token;
            });
        });
</script>
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
		@if ($errors->comment->has('login'))
			@foreach ($errors->comment->get('login') as $item)
			<p class="blue2">{{ $item }}</p>
			@endforeach
		@endif
		<p><input class="input3" type="text" name="login" value="{{ old('login') }}" /></p>
		<p class="pad2"></p>
		<h4 class="menu_registration"><div>Пароль</div></h4>
		<p class="pad1 pad2">Введите и подтвердите <strong>пароль</strong>. Придумайте сложный пароль, который нельзя подобрать. По возможности, используйте в нем <strong>большые буквы и цифры</strong>, и <strong>не используйте русские буквы</strong>. Пароль и логин чувствительны к регистру, т.е. "<strong>znakomstvo</strong>" и "<strong>ZnaKomStvo</strong>" - два разных пароля.</p>
		@if ($errors->comment->has('password'))
			@foreach ($errors->comment->get('password') as $item)
			<p class="blue2">{{ $item }}</p>
			@endforeach
		@endif
		<p><input class="input3" type="password" name="password" value="{{ old('password') }}" />&nbsp;&nbsp;&nbsp;<input class="input3" type="password" name="password_second" value="{{ old('password_second') }}" /></p>
		<p class="pad2"></p>
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
				<td rowspan="2"><input class="input3" type="text" name="name" value="{{ old('name') }}" /></td>
				<td class="pad11"><input type="radio" name="sex" value="1"@if (old('sex') == 1) checked="checked"@endif /></td>
				<td><span class="menuMenReg">Я мужчина</span></td>
				<td><p class="pad12">дата рождения</p></td>
			</tr>
			<tr>
				<td class="pad11"><input type="radio" name="sex" value="2"@if (old('sex') == 2) checked="checked"@endif /></td>
				<td><span class="menuWomenReg">Я женщина</span></td>
				<td>
					<select name="birth_day" style="width:40px;">
						@foreach ($days as $item)
						@if ($item == 0)
							<option value="0"@if (old('birth_day') == 0) selected="selected"@endif>---</option>
						@else
							<option value="{{ $item }}"@if (old('birth_day') == $item) selected="selected"@endif>{{ $item }}</option>
						@endif
						@endforeach
					</select>
					<select name="birth_month" style="width:80px;">
						@foreach ($months as $id => $item)
							<option value="{{ $id }}"@if (old('birth_month') == $id) selected="selected"@endif>{{ $item }}</option>
						@endforeach
					</select>
					<select name="birth_year" style="width:50px;">
					@foreach ($years as $id => $item)
					@if ($item == 1900)
						<option value="{{ $item }}"@if (old('birth_year') == 1990) selected="selected"@endif>---</option>
					@else
						<option value="{{ $item }}"@if (old('birth_year') == $item) selected="selected"@endif>{{ $item }}</option>
					@endif
					@endforeach
					</select>
				</td>
			</tr>
		</table>
		<p class="pad2"></p>
		<h4 class="menu_registration"><div>E-mail</div></h4>
		<p class="pad1 pad2">Введите <strong>свой Е-мэйл</strong>. Обещаем, что мы не будем его показывать никому кроме вас. Этот адрес необходим для связи с вами.</p>
		@if ($errors->comment->has('mail'))
			@foreach ($errors->comment->get('mail') as $item)
			<p class="blue2">{{ $item }}</p>
			@endforeach
		@endif
		<p><input class="input3" type="text" name="mail" value="{{ old('mail') }}" /></p>
		<p class="pad2"></p>
		<h4 class="menu_registration"><div>Место жительства</div></h4>
						<p class="pad1 pad2">Укажите <strong>город, регион и страну</strong>, в которой вы живете. Это поможет другим пользователям сайта, которые тоже живут рядом с вами, быстрее вас найти.</p>
						@if ($errors->comment->has('country'))
						@foreach ($errors->comment->get('country') as $item)
						<p class="blue2">{{ $item }}</p>
						@endforeach
						@endif
						<table class="cityRegForm">
							<tr>
								<td width="150">страна</td>
								<td>
									<select name="country" id="country" onchange="updateSelect('region', this.value, 'reg');" autocomplete="off">
										<option value="0" @if (old('country') == 0) selected="selected"@endif>выберите&nbsp;</option>
										<option value="141" @if (old('country') == COUNTRY_ID_RUSSIA) selected="selected"@endif>Россия</option>
										@foreach ($countries as $item)
										<option value="{{ $item->id }}" @if (old('country') == $item->id && old('country') != COUNTRY_ID_RUSSIA) selected="selected"@endif>{{ $item->name }}</option>
										@endforeach
									</select>
								</td>
							</tr>
							<tr>
								<td width="150">регион</td>
								<td><select name="region" id="region" onchange="updateSelect('city', this.value, 'cities');">
										<option value="0">не важно</option>
										@if (!empty($regions))
										@foreach ($regions as $item)
										<option value="{{ $item->id }}"@if (old('region') == $item->id) selected="selected"@endif>{{ $item->name }}</option>
										@endforeach
										@endif
									</select></td>
							</tr>
							<tr>
								<td width="150">город</td>
								<td><select id="city" name="city">
										<option value="0">не важно</option>
										@if (!empty($cities))
										@foreach ($cities as $item)
										<option value="{{ $item->id }}"@if (old('city') == $item->id) selected="selected"@endif>{{ $item->name }}</option>
										@endforeach
										@endif
									</select>
								</td>
							</tr>
						</table>
						<script type="text/javascript" src="{{ asset('js/functions_search.js') }}"></script>
						<p class="pad2"></p>
						<input type="hidden" name="recaptcha_response" id="recaptchaResponse">
						@if ($errors->comment->has('recaptcha_response'))
						<h4 class="menu_registration"><div>Антиробот</div></h4>
						@foreach ($errors->comment->get('recaptcha_response') as $item)
						<p class="blue2">{{ $item }}</p>
						@endforeach
						<p class="pad2"></p>
						@endif
						<h4 class="menu_registration"></h4>
						@if ($errors->comment->has('conditions'))
						<p class="pad2"></p>
						@foreach ($errors->comment->get('conditions') as $item)
						<p class="blue2">{{ $item }}</p>
						@endforeach
						@endif
						<p class="pad1 pad3"><input type="checkbox" name="conditions" @if (!empty(old('conditions'))) checked="checked"@endif />&nbsp;&nbsp;&nbsp; я соглашаюсь с условиями, изложенными в <a href="{{ route('conditions') }}" class="lColor2">правилах регистрации анкет</a> и даю согласие на обработку персональных данных.</p>
						<input type="hidden" name="otsil" value="1" />
						<p class="pad2"></p>
						<p class="pad3"><input type="submit" name="sent" class="bgBut5" value="" /></p>
					</form>
@overwrite