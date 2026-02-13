@extends('layouts.app')
@section('title', $title)
@section('main_body')
<h1 class="mTit">{{ $userData->name }}, {{ $userData->age_str }}, {{ $userData->city->name }}</h1>
<x-ank-menu :user-data="$userData" />
@if (!empty($userData->mainPhoto->id))
<div id="mainAnkFoto">
	<div style="width:{{ $userData->mainPhoto->width }}px;"><img width="{{ $userData->mainPhoto->width }}px" src="{{ (new FileService)->outPicture($userData->mainPhoto->id, $userData->sex) }}" /></div>
</div>
@endif
@if (count ($userData->photo) > 1)
<table id="mainSecondFoto">
	<tr>
	@foreach ($userData->photo as $item)
		<td>
			<a href="{{route('ank.photo.photo_id', $item->id)}}"><img src="{{ (new FileService)->outPicture($item->id, $userData->sex) }}" /></a>
		</td>		
    @endforeach
	</tr>
</table>
@endif
@if (!empty ($userData->mainPhoto->comment))
<h2 class="mTit">Комментарии</h2>
<table class="fotoComments">
	@foreach ($userData->mainPhoto->comment as $item)
	<tr>
		<td class="fotoCommPics"><a href="{{route('ank.id', $item->user_id)}}">
			<img alt="{{ $item->user->name }},{{ $item->user->age }} {{ $item->user->age_type }},{{ $item->user->city->name }}" src="{{ (new FileService)->outPicture($item->user_photo_id, $item->user->sex) }}" /></a></td>
		<td>
			<h4>
			<a href="{{route('ank.id', $item->user_id)}}" class="{{ $item->user->name_class }}">{{ $item->user->name }}</a> <strong>{{ $item->user->age }} {{ $item->user->age_type }}</strong><span class="postData">{{ $item->add_time }}</span></h4>
			<div>{!! $item->description !!}</div>
		</td>
	</tr>
	@endforeach
</table>
@endif
@if (!empty ($userData->mainPhoto))
<h2 class="mTit">Оставить комментарий</h2>
<form name="anketa" action="{{route('ank.photo.id', $userData->mainPhoto->id)}}" method="post">
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
		<x-textarea name="description" value="{{ old('description') }}" />
	</div>
	<p class="pad7"><x-submit name="otsil" value="отправить" /></p>
@endif
</form>
@endif
@overwrite