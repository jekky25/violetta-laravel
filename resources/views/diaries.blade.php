@extends('layouts.app')
@section('title', $title)
@section('main_body')
<h1 class="mTit">Дневники пользователей</h1>
<p class="pad3">Хотите поделиться событиями своей жизни? Напишите об этом <strong>в своем дневнике</strong></p>
@if (!empty ($diaries))
		<table class="ankDnevnik">
	@foreach ($diaries as $item)
			<tr>
				<td>
					<h4 class="{{ $item->name_class }}"><a href="{{route('ank.id', $item->user->user_id)}}">{{ $item->user->user_name }}</a>
					<div class="titDnev"><a href="{{route('ank.id', $item->user->user_id)}}">{!! \Illuminate\Support\Str::limit($item->dnevniki_title, 40, $end='...') !!}</a></div>
						<p>
						@if (!empty($user) && $user->user_id == $item->user_id)
					<a class="editBut" title="редактировать" href="{{route('registration.edit.diary.id', $item->dnevniki_id)}}"></a>
					<a class="delBut" title="удалить" href="{{route('registration.delete.diary.id', $item->dnevniki_id)}}"></a>
						@endif
					{{ $item->add_time }}</p>
					</h4>
						<div class="dnevBody clear">
					@if (!empty($item->dnevniki_picture))
			<a class="dnevBodyPic1" href="{{route('ank.id', $item->user->user_id)}}"><img src="{{ App\Helpers\Helper::outDiaryPicture($item->dnevniki_picture, $item->user->user_sex) }}" /></a>
			@elseif (!empty($item->foto_url))
			<a class="dnevBodyPic2" href="{{route('ank.id', $item->user->user_id)}}"><img src="{$smarty.const.SITE_URL}{$dnevniki[f].foto_url}" /></a>
			@endif
			@if (!empty($item->dnevniki_picture))
			<div class="mrg2">
			{!! \Illuminate\Support\Str::limit($item->dnevniki_text, 500, $end='...') !!}
			</div>
			@elseif (!empty($item->foto_url))
			<div class="mrg3">
			{!! \Illuminate\Support\Str::limit($item->dnevniki_text, 500, $end='...') !!}
			</div>
			@else
			{!! \Illuminate\Support\Str::limit($item->dnevniki_text, 500, $end='...') !!}
			@endif
			</div>
			<p class="dnevKomm"><a href="{{route('ank.diary.comments', $item->dnevniki_id)}}">комментарии ({{count ($item->comments)}})</a></p>
		</td>
	</tr>
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
@include('pagination', ['items' => $diaries])
@overwrite