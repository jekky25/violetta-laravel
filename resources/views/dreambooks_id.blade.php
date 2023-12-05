@extends('layouts.app')
@section('title', $title)
@section('main_body')
@if (!empty ($dreambook))
<h1 class="mTit">Толкование снов: {{ $dreambook->name }}</h1>
@if (!empty ($dreamBookLiterals))
					<ul class="sonnikMenu">
@foreach ($dreamBookLiterals as $item)
	<li><a href="{{route('dreambook.literal',$item->sonnik_id)}}">{{ $item->first_bukva }}</a></li>
@endforeach
					</ul>
@endif
					<div class="sonnik">
						<div class="google_banner">
<script type="text/javascript"><!--
google_ad_client = "pub-6379140164632940";
/* 300x250, создано 11.10.09 */
google_ad_slot = "3773839921";
google_ad_width = 300;
google_ad_height = 250;
//-->
</script>
<script type="text/javascript" async src="http://pagead2.googlesyndication.com/pagead/show_ads.js"></script>
</div>
<p>{!! $dreambook->description !!}</p>
<table>
<tr>
<td class="pad11">
<p class="pad1">
@include('social')
</p></td>
<td>
</td></tr></table>
</div>
@else
<p class="pad2">Сонника не существует</p>
@endif
@overwrite