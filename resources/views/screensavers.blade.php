@extends('layouts.app')
@section('title', $title)
@section('main_body')
<h1 class="mTit">Хранители экрана (скринсейверы)</h1>
<p class="pad2">В этом разделе собрана коллекция экранных заставок на водную тему. Которые порой радуют глаз и слух. Коллекция постоянно пополняется новыми скринсейверами.</p>
<p class="pad3">Приятного вам просмотра!</p>
@if (!empty ($screens))
<p class="pad3">Найдено заставок: <strong>{{$screens->total()}}</strong></p>
<table id="mScreen">
@foreach ($screens as $item)
@if ($loop->index % 2 == 0)<tr>@endif
	<td>
		<h2>{{ $item->name }}</h2>
		<p class="pad3"><strong>Добавлен: {{ $item->add_date }}</strong></p>
		<a class="scrPic" href="{{route('screensavers.id',$item->id)}}"><img alt="{{ $item->name }}, скринсейвер" src="{{ asset('screensavers/foto/' . $item->path_jpg) }}" /></a>
		<a class="comLink" href="{{route('screensavers.id',$item->id)}}">Скачать</a>
	</td>
	@if ($loop->iteration % 2 == 0)</tr>@else 
	@if ($loop->last)</tr>@endif 
	@endif
@endforeach
</table>
@endif
<x-pagination :items="$screens" />
@overwrite