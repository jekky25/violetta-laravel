@extends('layouts.app')
@section('title', $title)
@section('main_body')
<h1 class="mTit">Сонник</h1>
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
@if (!empty($words))
@foreach ($words as $item)
<p>
	<a href="{{route('dreambook.id',$item->id)}}">{{ $item->name }}</a>
	<br />{!! \Illuminate\Support\Str::limit(strip_tags($item->description), 120, $end='...') !!}
	<a class="strelki" href="{{route('dreambook.id',$item->id)}}">»»</a>
</p>
@endforeach
@endif
</div>
<x-pagination :items="$words" />
@overwrite