@extends('layouts.app')
@section('title', $title)
@section('main_body')
@if(session('success'))
<p class="error pt-20">{{ session('success') }}</p>
@endif
<h1 class="mTit">Бесплатные знакомства с девушками и парнями на сайте знакомств Виолетта</h1>
	<table id="mDescr">
		<tr>
			<td>
				<p>Добро пожаловать на наш <strong>сайт бесплатных знакомств</strong>.
				<br /><br />Это молодой, но динамично развивающийся ресурс интернета. Каждый день мы работаем над тем, чтобы Вам было удобнее <strong>общаться и находить новых друзей</strong>.<br /><br />
				Мы не являемся клоном другого сайта знакомств, мы уникальны!<br /><br />
				На нашем сайте Вы можете за 5 минут зарегистрировать свою анкету абсолютно бесплатно и общаться,
				общаться и еще раз общаться, находить новых друзей и заводить самые интригующие в Вашей жизни знакомства.
				Или (если конечно Вы захотите) посетите наш <a href="{{ asset('forum/') }}">форум</a> и получите на нем ответы на интересующие Вас вопросы.</p>
			</td>
			<td>
				<h3>Быстрый поиск</h3>
					<form name="anketa" action="{{route('search')}}" method="get" class="formSearch">
						<input type="hidden" name="send" value="1" />
						<div>Я ищу&nbsp;
							<x-select name="find_sex" :obj="$fields['sex']" :userProp="old('sex')" />
						</div>
						<div>
							<input type="checkbox" name="photo" />&nbsp;&nbsp;только с фото
						</div>
						<div>в возрасте от&nbsp;
							<x-select name=age_min :obj="$fields['age']" :userProp="old('age_min')" />
							до
							<x-select name="age_max" :obj="$fields['age']" :userProp="old('age_max')" />
						</div>
						<div>страна</div>
						<div>
							<x-select name="country" id="country" :obj="$fields['country']" :userProp="old('country')">
								<x-slot:firstInList><option value="141">Россия</option></x-slot>
								<x-slot:addition>onchange="updateSelect('region', this.value, 'reg');"</x-slot:addition>
							</x-select>
						</div>
						<div>регион</div>
						<div>
							<x-select name="region" id="region" :obj="[]" :userProp="old('region')">
								<x-slot:addition>onchange="updateSelect('city', this.value, 'cities');"</x-slot:addition>
							</x-select>
						</div>
						<div>город</div>
						<div>
							<x-select name="city" id="city" :obj="[]" />
						</div>
						<div>
							<x-submit name="sent" value="найти" />
						</div>
					</form>
					<script type="text/javascript" src="{{ asset('js/functions_search.js') }}"></script>
				</td>
			</tr>
		</table>
		<div id="mDnev">
			<div class="mDnevTr">
				<div class="wth1 mDnevTd">
					<new-faces :women="{{WOMEN}}" :men="{{MEN}}"></new-faces>
				</div>
				<div class="wth1 mDnevTd">
					<diaries-home></diaries-home>
				</div>
			</div>
		</div>
@overwrite