@extends('layouts.app')
@section('title', $title)
@section('main_body')
<h1 class="mTit">Значение имени</h1>
<ul class="namesMW">
	<li><a href="{{route('names.sex','men')}}">Мужские имена</a></li>
	<li><a href="{{route('names.sex','women')}}">Женские имена</a></li>
</ul>
<div class="clear"></div>
<h2 class="mTit pad5">Тайная гармония между человеком и его именем</h2>
<div class="google_banner">
{{--<script type="text/javascript"><!--
google_ad_client = "ca-pub-6379140164632940";
/* 300X250 имена */
google_ad_slot = "6112326239";
google_ad_width = 300;
google_ad_height = 250;
//-->
</script>--}}
@push('scripts')
<script type="text/javascript" async src="http://pagead2.googlesyndication.com/pagead/show_ads.js"></script>
@endpush
</div>
<p class="pad8">{!! $nameText !!}</p>
<p class="pad2"></p>
<ul class="namesMW">
	<li><a href="{{route('names.sex','men')}}">Мужские имена</a></li>
	<li><a href="{{route('names.sex','women')}}">Женские имена</a></li>
</ul>
<div class="clear"></div>
<div class="clear">
<ul class="namesMW2">
@if (!empty($names['m']))
	@foreach ($names['m'] as $k => $item)
		@if (!empty ($alphabet[$k]) && count($item) > 0)
			<li class="namesMW2B"><a href="{{route('names.subop', ['men',$k])}}">{{$alphabet[$k]}}</a></li>
			<li>
				@foreach ($item as $i => $_item)
					<a class="name" href="{{route('names.id', $_item->id)}}">{{ $_item->name }}</a>
					@if($loop->last)...@else, @endif
				@endforeach
			</li>
		@endif
	@endforeach
@endif
</ul>
<ul class="namesMW2">
@if (!empty($names['w']))
	@foreach ($names['w'] as $k => $item)
		@if (!empty ($alphabet[$k]) && count($item) > 0)
			<li class="namesMW2B"><a href="{{route('names.subop', ['women',$k])}}">{{$alphabet[$k]}}</a></li>
			<li>
				@foreach ($item as $i => $_item)
					<a class="name" href="{{route('names.id', $_item->id)}}">{{ $_item->name }}</a>
					@if($loop->last)...@else, @endif
				@endforeach
			</li>
		@endif
	@endforeach
@endif
</ul>
</div>
<div class="clear"></div>
<div class="pad2"></div>
@overwrite