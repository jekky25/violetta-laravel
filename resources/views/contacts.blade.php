@extends('layouts.app')
@section('title', $title)
@section('main_body')
@if(session('success'))
<p class="mess pad5">{{session('success')}}</p>
@else
<h1 class="mTit">Контактная информация</h1>
<p class="pad3">Для того, чтобы связаться с нами, заполните пожалуйста</p>
<form name="anketa" action="{{ route ('contacts.post') }}" method="post" class="formSearch">
<x-google-captcha />
{{ csrf_field() }}
@if (!empty ($errors->all()))
	<div class="pad3 error">
@foreach ($errors->all() as $message)
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
			<td><input class="login" type="text" name="email" value="{{ old('email') }}" /></td>
		</tr>
		<tr><td colspan="2" align="center"><textarea cols="60" rows="10" class="login" placeholder="Оставьте здесь Ваше сообщение и мы обязательно с Вами свяжемся" name="description">{{ old('description', '') }}</textarea></td></tr>
	</table>
<script language=JavaScript>
function find_otsil()
{
	document.anketa.sent.value = 'Подождите, идет отправка данных...';
	document.anketa.sent.disabled = true;
	document.anketa.submit();
}
</script>
	<p class="pad4">
		<x-submit name="sent" onclick="find_otsil()" value="Отправить" />
	</p>
</form>
@endif
@overwrite