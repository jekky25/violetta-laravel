@extends('layouts.app')
@section('title', $title)
@section('main_body')
<h1 class="mTit">{{ $titleId }} сайта знакомств "Виолетта"</h1>
<h3 class="titleSAnkets mrg4">{{ $countSearchAnkStr }}</h3>
@auth
	@if ($user->top100 > 0 && $user->photos_count > 0)
	@elseif ($user->top100 == 0 && $user->photos_count > 0)
	<p align="center" class="blue inTop">Стань участником ТОПа</p>
	@else
		<p align="center" class="blue inTop">Стань участником ТОПа</p>
		<p align="center" class="blue">тебе нужно всего лишь разместить фотографию</p>
	@endif
@else
	<p align="center" class="blue inTop">Стань участником ТОПа</p>
	<p align="center" class="blue">тебе нужно всего лишь заполнить анкету и разместить фотографию</p>
@endauth
<br />
@if (!empty($ankets))
	<div class="profile-list-container">
    @foreach ($ankets as $item)
		<x-profile-brief :item="$item" :best="1" />
    @endforeach
	</div>
	<x-pagination :items="$ankets" />
@else
<p class="pad5"><strong>анкет не найдено</strong></p>
@endif
@overwrite