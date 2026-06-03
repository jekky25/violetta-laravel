@extends('layouts.app')
@section('title', $title)
@section('main_body')
<h1 class="mTit">{{ $userData->name }}, {{ $userData->age_str }}, {{ $userData->city->name }}</h1>
<x-ank-menu :user-data="$userData" />
@if (!empty($photo))
<div id="mainAnkFoto">
	<div><img src="{{ $photo->url }}" /></div>
</div>
@endif
@if (count ($userData->photo) > 1)
<table id="mainSecondFoto">
	<tr>
	@foreach ($userData->photo as $item)
		<td>
			<a href="{{route('ank.photo.photo_id', $item->id)}}"><img src="{{ $item->url }}" /></a>
		</td>		
    @endforeach
	</tr>
</table>
@endif
@if (!empty ($photo->comment))
<h2 class="mTit">Комментарии</h2>
<table class="fotoComments">
	@foreach ($photo->comment as $item)
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
@if (!empty ($photo))
<h2 class="mTit">Оставить комментарий</h2>
<form name="anketa" action="{{route('ank.photo.id', $photo->id)}}" method="post">
@if(session('success'))
  <div class="success">{{session('success')}}</div>
@else
@if (!empty ($errors->all()))
<div class="error">
@foreach ($errors->all() as $message)
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