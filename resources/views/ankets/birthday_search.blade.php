@extends('layouts.app')
@section('title', $title)
@section('main_body')
<h1 class="mTit">Участники, отмечающие сегодня день Рождения!<br /> <span class="red">ПОЗДРАВЛЯЕМ!!!</span></h1>
@if (!empty($ankets))
	<div class="profile-list-container">
    @foreach ($ankets as $item)
		<x-profile-brief :item="$item" />
    @endforeach
	</div>
	<x-pagination :items="$ankets" />
@else
<p class="pad5"><strong>анкет не найдено</strong></p>
@endif
@overwrite