@extends('layouts.app')
@section('title', $title)
@section('main_body')
<h1 class="mTit">{{ $data->horoscope_title }}</h1>
<div id="partincut">
@if ($data->horoscopes)
	<ul>
	@foreach ($data->horoscopes as $item)
	<li><a href="{{route('horoscope.id', $item->id)}}">{{ $item->name }}</a></li>
	@endforeach
	</ul>
	<h3>Другие гороскопы</h3>
@endif
@if ($data->horoscopes_type)
	<ul>
  @foreach ($data->horoscopes_type as $item)
<li><a href="{{route('horoscope.op', $item->id)}}">{{ $item->name }}</a></li>
@endforeach
	</ul>
@endif
</div>
<p class="pad2">{!! $data->zodiak_text !!}</p>
<table>
<tr>
<td class="pad11">
<p class="pad1">
<x-social />
</p></td>
<td>
<!-- Place this tag where you want the +1 button to render. -->
<div class="g-plusone" data-size="medium"></div>
<!-- Place this tag after the last +1 button tag. -->
<script type="text/javascript">
	window.___gcfg = {lang: 'ru'};

  (function() {
    var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
    po.src = 'https://apis.google.com/js/plusone.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
  })();
</script>
</td></tr></table>
@overwrite