@extends('layouts.app')
@section('title', $title)
@section('main_body')
<h1 class="mTit">Самые популярные участники -- <strong>{{$popSex}}</strong></h1>
<div class="ankets">
	<ul>
		<li><a href="{{route('population_search.sex','men')}}">Мужчины</a></li>
		<li><a href="{{route('population_search.sex','women')}}">Женщины</a></li>
	</ul>
	<div class="clear"></div>
</div>
<div>
	<p class="pad2"><strong>Самые популярные участники</strong> -- это статистика анкет, где первое место
занимают пользователи с наибольшим просмотром анкет в течении месяца.<br /><br />

Как стать самым популярным на нашем <strong>сайте знакомств</strong>? Ответ на этот вопрос очень простой.<br /><br />
Почаще бывайте на сайте. Общайтесь с другими пользоателями системы.
Пишите на форуме. Тогда вашу анкету будут просматривать чаще.</p>
</div>
@if (!empty($ankets))
    @foreach ($ankets as $item)
	<x-profile-brief :item="$item" />
    @endforeach
	<x-pagination :items="$ankets" />
@else
<p class="pad5"><strong>анкет не найдено</strong></p>
@endif					
@overwrite