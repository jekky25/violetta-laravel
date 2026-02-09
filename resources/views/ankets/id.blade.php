@extends('layouts.app')
@section('title', $title)
@section('main_body')
<h1 class="mTit">{{ $popSex }}</h1>
<div class="sub-search-container">
	<ul class="sub-search">
		<li><a class="name_my_mess" href="{{route('ankets.sex', 'men')}}">Мужчины</a></li>
		<li><a href="{{route('ankets.sex.age', ['men',20])}}">до 20 лет</a></li>
		<li><a href="{{route('ankets.sex.age', ['men',2025])}}">20 - 25 лет</a></li>
		<li><a href="{{route('ankets.sex.age', ['men',2535])}}">25 - 35 лет</a></li>
		<li><a href="{{route('ankets.sex.age', ['men',3550])}}">35 - 50 лет</a></li>
		<li><a href="{{route('ankets.sex.age', ['men',50])}}">от 50 лет</a></li>
	</ul>
	<ul class="sub-search">
		<li><a class="name_my_mess" href="{{route('ankets.sex', 'women')}}">Женщины</a></li>
		<li><a href="{{route('ankets.sex.age', ['women',20])}}">до 20 лет</a></li>
		<li><a href="{{route('ankets.sex.age', ['women',2025])}}">20 - 25 лет</a></li>
		<li><a href="{{route('ankets.sex.age', ['women',2535])}}">25 - 35 лет</a></li>
		<li><a href="{{route('ankets.sex.age', ['women',3550])}}">35 - 50 лет</a></li>
		<li><a href="{{route('ankets.sex.age', ['women',50])}}">от 50 лет</a></li>
	</ul>
</div>
<div class="clear"></div>
<h3 class="titleSAnkets">{{ $countSearchAnkStr }}</h3>
@if (!empty($ankets))
					<div class="profile-list-container">
    @foreach ($ankets as $item)
	<x-profile-brief :item="$item" />
    @endforeach
					</div>
					<x-pagination :items="$ankets" />					
@else
<p class="pad5"><strong>по вашему запросу ничего не найдено</strong></p>
@endif
@overwrite