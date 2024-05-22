@extends('layouts.app')
@section('title', $title)
@section('main_body')
<h1 class="mTit">Дневники пользователей</h1>
<p class="pad3">Хотите поделиться событиями своей жизни? Напишите об этом <strong>в своем дневнике</strong></p>
@if (!empty ($diaries))
		<table class="ankDnevnik">
	@foreach ($diaries as $item)
	@include('blocks.diary', ['item' => $item])
	@endforeach
	</table>
@endif
	<div class="pad5">
<script type="text/javascript"><!--
google_ad_client = "ca-pub-6379140164632940";
/* Дневники 468x60 */
google_ad_slot = "5785131254";
google_ad_width = 468;
google_ad_height = 60;
//-->
</script>
<script type="text/javascript" async src="http://pagead2.googlesyndication.com/pagead/show_ads.js">

</script>
</div>
<x-pagination :items="$diaries" :pagination="$pagination" />
@overwrite