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
<a title="Поделиться ВКонтакте" onclick="window.open('http://vkontakte.ru/share.php?url='+encodeURIComponent(location.href));return false;" rel="nofollow" href="http://vkontakte.ru/"><img title="Поделиться ВКонтакте" src="{{ asset('image/vkontakte2.png') }}" border="0" alt="" /></a>
<a title="Опубликовать в своем блоге livejournal.com" onclick="window.open('http://www.livejournal.com/update.bml?event='+encodeURIComponent(location.href)+'&amp;subject='+encodeURIComponent(document.title));return false;" rel="nofollow" href="http://www.livejournal.com/"><img title="Опубликовать в своем блоге livejournal.com" src="{{ asset('image/livejournal2.png') }}" border="0" alt="" /></a>
<a title="Поделиться В Моем Мире" onclick="window.open('http://connect.mail.ru/share?share_url='+encodeURIComponent(location.href));return false;" rel="nofollow" href="http://connect.mail.ru/"><img title="Поделиться В Моем Мире" src="{{ asset('image/mail2.png') }}" border="0" alt="" /></a>
<a onclick="window.open('http://www.odnoklassniki.ru/dk?st.cmd=addShare&amp;st.s=1&amp;st._surl='+encodeURIComponent(location.href), 'odkl', 'width=626, height=436'); return false;" rel="nofollow" href="http://www.odnoklassniki.ru/dk?st.cmd=addShare&amp;st.s=1&amp;st._surl=+encodeURIComponent(location.href)"><img src="{{ asset('image/odnoklassniki2.png') }}" title="Поделиться с друзьями в Одноклассниках"></a>
</p></td>
<td>
</td></tr></table>
</div>
@else
<p class="pad2">Сонника не существует</p>
@endif
@overwrite