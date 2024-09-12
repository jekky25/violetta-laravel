@extends('layouts.app')
@section('title', $title)
@section('main_body')
<h1 class="mTit">{{$nameTitle}}</h1>
@if (!empty($nameText))
	<p class="namesMW3">{!!$namesGender!!}</p>
<div class="google_banner">
<script type="text/javascript"><!--
google_ad_client = "ca-pub-6379140164632940";
/* 300X250 имена */
google_ad_slot = "6112326239";
google_ad_width = 300;
google_ad_height = 250;
//-->
</script>
<script type="text/javascript"
src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
</script>
</div>
<p class="pad8">
{!!$nameText!!}
</p>
<table>
<tr>
<td class="pad11">
<p class="pad1"><x-social /></p>
</td>
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
<h2 class="mTit pad5">{{$nameTitleGender}}</h2>
@if (!empty($namesGender))
	<p class="namesMW3">{!!$namesGender!!}</p>
@endif
<ul class="namesMW">
	<li><a href="{{route('names.sex','men')}}">Мужские имена</a></li>
	<li><a href="{{route('names.sex','women')}}">Женские имена</a></li>
</ul>
<div class="clear"></div>
@endif
@if (!empty($bannerNames))<p class="pad1 pad2">{!!$bannerNames!!}</p>@endif
@overwrite