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
<x-liter-menu :alphabet="$alphabet" :sex="$sex"></x-liter-menu>
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
<x-liter-menu :alphabet="$alphabet" :sex="$sex"></x-liter-menu>
@endif
<div class="pad2"></div>
@else
<p class="pad5">имен не найдено</p>
@endif
@overwrite