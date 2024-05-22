@extends('layouts.app')
@section('title', $title)
@section('main_body')
<h1 class="mTit">{{ $titleId }} сайта знакомств "Виолетта"</h1>
<h3 class="titleSAnkets mrg4">{{ $countSearchAnkStr }}</h3>
@auth
	@if ($user->user_top100 > 0 && $user->user_fotos > 0)
	@elseif ($user->user_top100 == 0 && $user->user_fotos > 0)
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
<table id="mScreen">
	<tr>
		<td class="wth6">
			<div class="ankets2">
@if (!empty($ankets))
    @foreach ($ankets as $item)
		<x-profile-brief :item="$item" :best="1" />
    @endforeach
@else
<p class="pad5"><strong>анкет не найдено</strong></p>
@endif
				</div>
		</td>
		<td>
<script type="text/javascript"><!--
google_ad_client = "ca-pub-6379140164632940";
/* 160x600 топ  100 (В) */
google_ad_slot = "1016034439";
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