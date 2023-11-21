@extends('layouts.app')
@section('title', $title)
@section('main_body')
<h1 class="mTit">{{$nameTitle}}</h1>
	<ul class="namesMW">
		<li><a href="{{route('names.sex','men')}}">Мужские имена</a></li>
		<li><a href="{{route('names.sex','women')}}">Женские имена</a></li>
	</ul>
	<div class="clear"></div>

@if (!empty($names))
	<p class="namesMW3">{!!$namesGender!!}</p>
<div class="google_banner">
{{--
<script type="text/javascript"><!--
google_ad_client = "ca-pub-6379140164632940";
/* 300X250 имена */
google_ad_slot = "6112326239";
google_ad_width = 300;
google_ad_height = 250;
//-->
</script>
<script type="text/javascript" src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
</script>--}}
</div>
<div>
	<ul class="namesMW4">
	@foreach ($names as $item)
		<li>
			<p><a href="{{route('names.id', $item->id)}}">{{$item->name}}</a></p>
			<p>{!! \Illuminate\Support\Str::limit($item->description, 120, $end='...') !!} <a class="under1" href="{{route('names.id', $item->id)}}"><strong>»»</strong></a></p>
		</li>
	@endforeach
	</ul>
</div>
@if (count($names) > 3)
	<p class="namesMW3">{!!$namesGender!!}</p>
@endif
<div class="pad2"></div>
@else
<p class="pad5">имен не найдено</p>
@endif
@overwrite