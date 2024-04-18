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
<h1 class="mTit">Контактная информация</h1>
<p class="pad3">Для того, чтобы связаться с нами, заполните пожалуйста</p>
<form name="anketa" action="{{ route ('contacts.post') }}" method="post" class="formSearch">
{{ csrf_field() }}
@if (!empty ($errors->comment->all()))
	<div class="pad3 error">
@foreach ($errors->comment->all() as $message)
	<p>{{ $message }}</p>
@endforeach
	</div>
@endif
	<table class="searchTable">
		<tr>
			<td class="right1">ваше имя</td>
			<td><input class="login" type="text" name="name" value="{{ old('name') }}" /></td>
		</tr>
		<tr>
			<td class="right1">название организации (если есть)</td>
			<td><input class="login" type="text" name="organization" value="{{ old('organization') }}" /></td>
		</tr>
		<tr>
			<td class="right1">е-майл</td>
			<td><input class="login" type="text" name="mail" value="{{ old('mail') }}" /></td>
		</tr>
		<tr><td colspan="2" align="center"><textarea cols="60" rows="10" class="login" name="description">{{ old('description', 'Оставьте здесь Ваше сообщение и мы обязательно с Вами свяжемся') }}</textarea></td></tr>
	</table>
	<input type="hidden" name="recaptcha_response" id="recaptchaResponse">
<script language=JavaScript>
function find_otsil()
{
	document.anketa.sent.value = 'Подождите, идет отправка данных...';
	document.anketa.sent.disabled = true;
	document.anketa.submit();
}
</script>
	<p class="pad4">
		<input type="hidden" name="otsil" value="1" />
		<input type="submit" name="sent" onclick="find_otsil()" value="Отправить" />
	</p>
</form>
@overwrite