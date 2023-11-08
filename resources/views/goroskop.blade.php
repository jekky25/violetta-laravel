@extends('layouts.app')
@section('title', $title)
@section('main_body')
<h1 class="mTit">{{ $goroskopsTitle }}</h1>
<div id="partincut">
@if ($goroskops)
	<ul>
	@foreach ($goroskops as $item)
	<li><a href="{{route('goroskop.id', $item->gor_id)}}">{{ $item->gor_name }}</a></li>
	@endforeach
	</ul>
	<h3>Другие гороскопы</h3>
@endif
@if ($goroskops_type)
	<ul>
  @foreach ($goroskops_type as $item)    
<li><a href="{{route('goroskop.op', $item->gor_type_id)}}">{{ $item->gor_type_name }}</a></li>
@endforeach
	</ul>
@endif
</div>
<p class="pad2">{!! $zodiak_text !!}</p>
<table>
<tr>
<td class="pad11">
<p class="pad1">
<a title="Поделиться ВКонтакте" onclick="window.open('http://vkontakte.ru/share.php?url='+encodeURIComponent(location.href));return false;" rel="nofollow" href="http://vkontakte.ru/"><img title="Поделиться ВКонтакте" src="{{ asset('image/vkontakte2.png') }}" border="0" alt="" /></a>
<a title="Опубликовать в своем блоге livejournal.com" onclick="window.open('http://www.livejournal.com/update.bml?event='+encodeURIComponent(location.href)+'&amp;subject='+encodeURIComponent(document.title));return false;" rel="nofollow" href="http://www.livejournal.com/"><img title="Опубликовать в своем блоге livejournal.com" src="{{ asset('image/livejournal2.png') }}" border="0" alt="" /></a>
<a title="Поделиться В Моем Мире" onclick="window.open('http://connect.mail.ru/share?share_url='+encodeURIComponent(location.href));return false;" rel="nofollow" href="http://connect.mail.ru/"><img title="Поделиться В Моем Мире" src="{{ asset('image/mail2.png') }}" border="0" alt="" /></a>
<a onclick="window.open('http://www.odnoklassniki.ru/dk?st.cmd=addShare&amp;st.s=1&amp;st._surl='+encodeURIComponent(location.href), 'odkl', 'width=626, height=436'); return false;" rel="nofollow" href="http://www.odnoklassniki.ru/dk?st.cmd=addShare&amp;st.s=1&amp;st._surl=+encodeURIComponent(location.href)"><img src="{{ asset('image/odnoklassniki2.png') }}" title="Поделиться с друзьями в Одноклассниках"></a>
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