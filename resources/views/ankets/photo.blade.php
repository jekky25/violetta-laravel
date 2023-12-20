@extends('layouts.app')
@section('title', $title)
@section('main_body')
<h1 class="mTit">{{ $userData->user_name }}, {{ $userData->user_age_str }}, {{ $userData->city->name }}</h1>
<ul id="menuReg" class="clear">
	@if (Route:: currentRouteName() == 'ank.id')
	<li class="menuRegAct">Основное</li>
	@else
	<li><a href="{{route('ank.id', $userData->user_id)}}">Основное</a></li>
	@endif
	@if (Route:: currentRouteName() == 'ank.full.id')
	<li class="menuRegAct">Подробно</li>
	@else
	<li><a href="{{route('ank.full.id', $userData->user_id)}}">Подробно</a></li>
	@endif
	@if ($userData->user_fotos > 0)
		@if (Route:: currentRouteName() == 'ank.photo.id')
		<li class="menuRegAct">Фотоальбом ({{ $userData->user_fotos }} фото)</li>
		@else
		<li><a href="{{route('ank.photo.id', $userData->user_id)}}">Фотоальбом ({{ $userData->user_fotos }} фото)</a></li>
		@endif		
	@endif
	@if ($userData->number_diary > 0)
		<li><a href="{{route('ank.diary.id', $userData->user_id)}}">Дневник ({{ $userData->number_diary_str }})</a></li>
    @endif
</ul>
@if (!empty($userData->mainPhoto->fotos_id))
<div id="mainAnkFoto">
	<div style="width:{{ $userData->mainPhoto->width }}px;"><img width="{{ $userData->mainPhoto->width }}px" src="{{ App\Helpers\Helper::outPicture($userData->mainPhoto->fotos_id, $userData->user_sex) }}" /></div>
</div>
@endif
@if (count ($userData->photo) > 1)
<table id="mainSecondFoto">
	<tr>
	@foreach ($userData->photo as $item)
		<td>
			<a href="{{route('ank.photo.photo_id', $item->fotos_id)}}"><img src="{{ App\Helpers\Helper::outPicture($item->fotos_id, $userData->user_sex) }}" /></a>
		</td>		
    @endforeach
	</tr>
</table>
@endif
@if (!empty ($userData->mainPhoto->comment))
<h2 class="mTit">Комментарии</h2>
<table class="fotoComments">
	@foreach ($userData->mainPhoto->comment as $item)
	@if ($loop->index  == 4)
	<tr>
		<td colspan="2">						
			<div class="banerFoto">
	<script type="text/javascript"><!--
google_ad_client = "ca-pub-6379140164632940";
/* Анкета страница Фотографии */
google_ad_slot = "9078791059";
google_ad_width = 468;
google_ad_height = 60;
//-->
</script>
<script type="text/javascript" async src="http://pagead2.googlesyndication.com/pagead/show_ads.js"></script>
			</div>
		</td>
	</tr>
@php
$secondBaner = 1;
@endphp
	@endif
	<tr>
		<td class="fotoCommPics"><a href="{{route('ank.id', $item->user_id)}}">
			<img alt="{{ $item->user->user_name }},{{ $item->user->user_age }} {{ $item->user->user_age_type }},{{ $item->user->city->name }}" src="{{ App\Helpers\Helper::outPicture($item->user_photo_id, $item->user->user_sex) }}" /></a></td>
		<td>
			<h4>
			<a href="{{route('ank.id', $item->user_id)}}" class="{{ $item->user->user_name_class }}">{{ $item->user->user_name }}</a> <strong>{{ $item->user->user_age }} {{ $item->user->user_age_type }}</strong><span class="postData">{{ $item->add_time }}</span></h4>
			<div>{{ $item->comments_description }}</div>
		</td>
	</tr>
	@endforeach
</table>
@endif
@if (!empty ($userData->mainPhoto))
<h2 class="mTit">Оставить комментарий</h2>
<form name="anketa" action="{{route('ank.photo.id', $userData->mainPhoto->fotos_id)}}" method="post">
@if(session('success'))
  <div class="success">{{session('success')}}</div>
@else
@if (!empty ($errors->comment->all()))
<div class="error">
@foreach ($errors->comment->all() as $message)
<p>{{ $message }}</p>
@endforeach
	</div>
@endif
{{ csrf_field() }}
	<div>
		<textarea class="textarea2" name="description" wrap="virtual"></textarea>
	</div>
	<p class="pad7"><input class="input2" type="submit" name="otsil" value="Отправить" /></p>
@endif
</form>
@endif
@if (isset($secondBaner) && $secondBaner == 1)
	<div class="banerFoto">
<script type="text/javascript"><!--
google_ad_client = "ca-pub-6379140164632940";
/* Анкета страница Фотографии */
google_ad_slot = "9078791059";
google_ad_width = 468;
google_ad_height = 60;
//-->
</script>
<script type="text/javascript" async src="http://pagead2.googlesyndication.com/pagead/show_ads.js"></script>
</div>
@endif
@overwrite