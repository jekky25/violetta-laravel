@extends('layouts.app')
@section('title', $title)
@section('main_body')
<h1 class="mTit">Дневники пользователей</h1>
<p class="pad3">Хотите поделиться событиями своей жизни? Напишите об этом <strong>в своем дневнике</strong></p>
@if (!empty ($diaries))
	<table class="ankDnevnik">
	@foreach ($diaries as $item)
	<x-blocks-diary :item="$item" />
	@endforeach
	</table>
@endif
<x-pagination :items="$diaries"/>
@overwrite