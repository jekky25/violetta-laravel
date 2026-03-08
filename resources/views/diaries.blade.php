@extends('layouts.app')
@section('title', $title)
@section('main_body')
<h1 class="mTit">Дневники пользователей</h1>
<p class="pad3">Хотите поделиться событиями своей жизни? Напишите об этом <strong>в своем дневнике</strong></p>
@if (!empty ($diaries))
	@foreach ($diaries as $item)
	<x-activity-item :item="$item" type="diary" />
	@endforeach
@endif
<x-pagination :items="$diaries"/>
@overwrite