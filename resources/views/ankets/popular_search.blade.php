@extends('layouts.app')
@section('title', $title)
@section('main_body')
<h1 class="mTit">Самые популярные участники -- <strong>{{$popSex}}</strong></h1>
					<table id="mScreen">
						<tr>
							<td class="wth1">
								<div class="ankets">
								<ul>
									<li><a href="{{route('population_search.sex','men')}}">Мужчины</a></li>
									<li><a href="{{route('population_search.sex','women')}}">Женщины</a></li>
								</ul>
								<div class="clear"></div>
@if (!empty($ankets))
    @foreach ($ankets as $item)
		@if (!isset ($item->photo->fotos_id)) @php $item->photo->fotos_id = 0 @endphp @endif
		<dl>
			<dt>
				<a href="{{route('ank.id', $item->user_id)}}">
				<img alt="{{$item->user_name}},{{$item->user_age}}{{$item->user_age_type}},{{$item->city->name}}" data-src="{{ App\Helpers\Helper::outPicture($item->photo->fotos_id, $item->user_sex) }}" src="{{ asset('image/zero.gif') }}" /></a>
			</dt>
			<dd>
				<p>{{--if $ankets[j].user_reg_is}<img title="на сайте" class="online" alt="на сайте" src="../../image/on_line.gif" />{/if--}}
                <a href="{{route('ank.id', $item->user_id)}}" @if ($item->user_sex == MEN)class="name_man" @else class="name_woman"@endif>{{$item->user_name}}</a>
                @if ($item->user_sex == MEN)<img alt="Мужчина" src="{{ asset('image/sex_men.jpg') }}" />@else<img alt="Женщина" src="{{ asset('image/sex_women.jpg') }}" />@endif
				<span>({{$item->user_fotos}} фото)</span></p>
				<p><strong>{{$item->user_age}} {{$item->user_age_type}}</strong>, {{$item->city->name}}</p>
				<p><strong>Ищу:</strong> {{$item->find_sex_orient}}</p>
			</dd>
		</dl>
    @endforeach
@else
<p class="pad5"><strong>анкет не найдено</strong></p>
@endif
								</div>
							</td>
							<td>
<p class="pad2"><strong>Самые популярные участники</strong> -- это статистика анкет, где первое место
занимают пользователи с наибольшим просмотром анкет в течении месяца.<br /><br />

Как стать самым популярным на нашем <strong>сайте знакомств</strong>? Ответ на этот вопрос очень простой.<br /><br />
Почаще бывайте на сайте. Общайтесь с другими пользоателями системы.
Пишите на форуме. Тогда вашу анкету будут просматривать чаще.</p>
<script type="text/javascript"><!--
google_ad_client = "ca-pub-6379140164632940";
/* 160x600 добавлен на списки анкет */
google_ad_slot = "3632942762";
google_ad_width = 160;
google_ad_height = 600;
//-->
</script>
<script type="text/javascript"
src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
</script>
							</td>
						</tr>
					</table>
@include('pagination')
@overwrite