@extends('layouts.app')
@section('title', $title)
@section('main_body')
<h1 class="mTit">{{ $popSex }}</h1>
					<ul class="anketSubSearch">
						<li><a class="name_my_mess" href="{{route('ankets.sex', 'men')}}">Мужчины</a></li>
						<li><a href="{{route('ankets.sex.age', ['men',20])}}">до 20 лет</a></li>
						<li><a href="{{route('ankets.sex.age', ['men',2025])}}">20 - 25 лет</a></li>
						<li><a href="{{route('ankets.sex.age', ['men',2535])}}">25 - 35 лет</a></li>
						<li><a href="{{route('ankets.sex.age', ['men',3550])}}">35 - 50 лет</a></li>
						<li><a href="{{route('ankets.sex.age', ['men',50])}}">от 50 лет</a></li>
					</ul>
					<ul class="anketSubSearch">
						<li><a class="name_my_mess" href="{{route('ankets.sex', 'women')}}">Женщины</a></li>
						<li><a href="{{route('ankets.sex.age', ['women',20])}}">до 20 лет</a></li>
						<li><a href="{{route('ankets.sex.age', ['women',2025])}}">20 - 25 лет</a></li>
						<li><a href="{{route('ankets.sex.age', ['women',2535])}}">25 - 35 лет</a></li>
						<li><a href="{{route('ankets.sex.age', ['women',3550])}}">35 - 50 лет</a></li>
						<li><a href="{{route('ankets.sex.age', ['women',50])}}">от 50 лет</a></li>
					</ul>
					<div class="clear"></div>
					<h3 class="titleSAnkets">{{ $countSearchAnkStr }}</h3>
					<table id="mScreen">
						<tr>
							<td class="wth1">
								<div class="ankets">
@if (!empty($ankets))
    @foreach ($ankets as $item)
	<x-profile-brief :item="$item" />
    @endforeach
@else
<p class="pad5"><strong>по вашему запросу ничего не найдено</strong></p>
@endif									
									</div>
							</td>
							<td>
<script type="text/javascript"><!--
google_ad_client = "ca-pub-6379140164632940";
/* 160x600 добавлен на списки анкет */
google_ad_slot = "3632942762";
google_ad_width = 160;
google_ad_height = 600;
//-->
</script>
<script type="text/javascript" async src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
</script>
							</td>
						</tr>
					</table>
@include('pagination', ['items' => $ankets])
@overwrite