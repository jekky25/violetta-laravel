@extends('layouts.app')
@section('title', $title)
@section('main_body')
<h1 class="mTit">{{ $popSex }}</h1>
<div class="sub-search-container">
	<ul class="sub-search">
		<li><a class="name_my_mess" href="{{route('ankets', 'men')}}">Мужчины</a></li>
		<li><a href="{{route('ankets', ['men',20])}}">до 20 лет</a></li>
		<li><a href="{{route('ankets', ['men',2025])}}">20 - 25 лет</a></li>
		<li><a href="{{route('ankets', ['men',2535])}}">25 - 35 лет</a></li>
		<li><a href="{{route('ankets', ['men',3550])}}">35 - 50 лет</a></li>
		<li><a href="{{route('ankets', ['men',50])}}">от 50 лет</a></li>
	</ul>
	<ul class="sub-search">
		<li><a class="name_my_mess" href="{{route('ankets', 'women')}}">Женщины</a></li>
		<li><a href="{{route('ankets', ['women',20])}}">до 20 лет</a></li>
		<li><a href="{{route('ankets', ['women',2025])}}">20 - 25 лет</a></li>
		<li><a href="{{route('ankets', ['women',2535])}}">25 - 35 лет</a></li>
		<li><a href="{{route('ankets', ['women',3550])}}">35 - 50 лет</a></li>
		<li><a href="{{route('ankets', ['women',50])}}">от 50 лет</a></li>
	</ul>
</div>
<div class="clear"></div>
<h3 class="titleSAnkets">
@if ($ankets->isEmpty())
	Найдено анкет: 0
@elseif ($ankets instanceof \Illuminate\Pagination\LengthAwarePaginator)
	Найдено анкет: {{ $ankets->firstItem() }} - {{ $ankets->lastItem() }} из {{ $ankets->total() }}
@endif</h3>
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