@extends('layouts.app')
@section('title', $title)
@section('main_body')
<h1 class="mTit">Участники, отмечающие сегодня день Рождения!<br /> <span class="red">ПОЗДРАВЛЯЕМ!!!</span></h1>
<table id="mScreen">
	<tr>
		<td class="wth1">
			<div class="ankets">
	@if (!empty($ankets))
    @foreach ($ankets as $item)
		@include('ankets.brief')
    @endforeach
@else
<p class="pad5"><strong>анкет не найдено</strong></p>
@endif
			</div>
		</td>
		<td>
<script type="text/javascript"><!--
google_ad_client = "ca-pub-6379140164632940";
/* 120x600 добавлен на  списки анкет */
google_ad_slot = "6116632811";
google_ad_width = 120;
google_ad_height = 600;
//-->
</script>
<script type="text/javascript" async src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
</script>
		</td>
	</tr>
</table>
@include('pagination')
@overwrite