@extends('layouts.app')
@section('title', $title)
@section('main_body')
<h1 class="mTit">Поднять анкету</h1>
@if(session('success'))
<h4 class="reg_title2">{{ session('success') }}</h4>
@else
@if(!session('textTop100'))
@if(session('conditions') === true)
    <p style="color:#f00">Вы не можете попасть в ТОП, т.к. не выполнено одно из условий</p>
    <p>Чтобы стать участником ТОПа нашего сайта, Вам необходимо выполнить <strong>всего 3 условия:</strong></p>
    <p>1. Иметь регистрацию на нашем сайте;</p>
    <p style="color:#f00">2. У вас должна быть загружена хотя бы одна фотография;</p>
    <p>3. Вам необходимо подтвердить желание участвовать в ТОПе.</p>
	<p><strong>Перейти в раздел <a class="name" href="{{ route('registration.edit.photo') }}">Мои фото</a></strong></p>
	<p><br></p>
@else
    <p>Чтобы поднять анкету в ТОПе нашего сайта, Вам необходимо выполнить <strong>всего 3 условия:</strong></p>
	<p>1. Иметь регистрацию на нашем сайте;</p>
	<p>2. У вас должна быть загружена хотя бы одна фотография;</p>
	<p>3. Вам необходимо подтвердить желание участвовать в ТОПе.</p>
	<p><br /></p>
@endif
@endif
@if(!session('formToTop'))
<form name="anketa" action="{{ route('registration.top100.post') }}" method="post">
{{ csrf_field() }}
<center>
@if(session('conditions') === true)
<x-submit name="sent" value="Попасть в ТОП" />
@else
<x-submit name="sent" value="Поднять анкету" />
@endif
</center>
</form>
@endif
@endif
@overwrite