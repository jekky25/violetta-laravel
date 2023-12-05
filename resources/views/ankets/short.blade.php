@extends('layouts.app')
@section('title', $title)
@section('main_body')
<script language="JavaScript" type="text/javascript">
function vote(score)
{
	var url = window.location.href;
	url += '&send_golos=1&golos='+score;
	window.location = url;
	return false;
}
</script>				
<h1 class="mTit">{{ $userData->user_name }}, {{ $userData->user_age_str }}, {{ $userData->city->name }}</h1>
						{{--if !empty($smarty.get.succgolos)}<p class="mess">Спасибо. Ваш голос учтен.</p>{/if--}}
<ul id="menuReg" class="clear">
	<li class="menuRegAct">Основное</li>
	<li><a href="{{route('ank.full.id', $userData->user_id)}}">Подробно</a></li>
	@if ($userData->user_fotos > 0)
		<li><a href="{{route('ank.photo.id', $userData->user_id)}}">Фотоальбом ({{ $userData->user_fotos }} фото)</a></li>
	@endif
	@if ($userData->number_diary > 0)
		<li><a href="{{route('ank.diary.id', $userData->user_id)}}">Дневник ({{ $userData->number_diary_str }})</a></li>
    @endif
</ul>
<ul id="ankFotos" class="clear">
    @if ($userData->user_fotos > 0)
        @foreach ($userData->photo as $item)
		<li><a class="ankFotosPics" href="{{route('ank.photo.f.id', $item->fotos_id)}}"><img src="{{ App\Helpers\Helper::outPicture($item->fotos_id, $userData->user_sex) }}" /></a></li>
        @endforeach                    
	@else
		<li><p class="ankFotosPics">@if ($userData->user_sex == MEN)<img src="{{ asset('image/no_foto_m_vip.jpg') }}" />@else<img src="{{ asset('image/no_foto_w_vip.jpg') }}" /></a></li>@endif
	@endif
		<li>
			<p><strong>Город:</strong> {{ $userData->city->name }} ({{ $userData->country->name }})</p>
			<p><strong>Возраст:</strong> {{ $userData->user_age_str }}</p>
			<p><strong>Знак зодиака:</strong> <a href="{{route('goroskop.id', $userData->zodiac['zodiac_id'])}}" title="Узнайте свой Зодиак">{{$userData->zodiac['zodiac_text']}}</a></p>
			<table>
				<tr>
					<td class="pad11">
						<p class="pad1">
							<a href="{{route('privmsg.post.id', $userData->user_id)}}" title="Написать сообщение"><img alt="Написать сообщение" src="{{ asset('image/mail.png') }}" /></a>
							@include('social')
						</p>
					</td>
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
					</td>
				</tr>
			</table>
			<p>{{ $userData->user_last_visit }}</p>
			<p>Просмотров за месяц: {{ $userData->ankVisits }}</p>
			<table>
				<tr>
					<td><p>Рейтинг:</p></td>
					<td class="wth4">
						<div class="div-rating2">
							<ul class="div-rating">
								<li class="current-rating" style="width:{{ $userData->user_reiting_str }}px;">&nbsp;</li>
								@auth
									@if ($user->user_id != $userData->user_id && !$ankEvaluationed)
										<li><a rel="nofollow" href='javascript:void(0)' onclick='javascript:vote("1");' title='Очень плохо' class="r1-unit rater">Очень плохо</a></li>
										<li><a rel="nofollow" href='javascript:void(0)' onclick='javascript:vote("2");' title='Плохо' class="r2-unit rater">Плохо</a></li>
										<li><a rel="nofollow" href='javascript:void(0)' onclick='javascript:vote("3");' title='Средне' class="r3-unit rater">Средне</a></li>
										<li><a rel="nofollow" href='javascript:void(0)' onclick='javascript:vote("4");' title='Хорошо' class="r4-unit rater">Хорошо</a></li>
										<li><a rel="nofollow" href='javascript:void(0)' onclick='javascript:vote("5");' title='Отлично' class="r5-unit rater">Отлично</a></li>
									@endif
								@endauth
							</ul>
						</div>
					</td>
				</tr>
			</table>
		</li>
</ul>
<div class="ankData">
	@if (!empty($userData->user_description))
		<fieldset class="mono">
			<dl>
				<dt>Обо мне:</dt>
				<dd>{{ $userData->user_description }}
				</dd>
			</dl>
		</fieldset>
	@endif
	<fieldset>
		<dl>
			<dt>Пол:</dt>
			<dd>{{ $userData->user_sex_str }}</dd>
		</dl>
		@if ($userData->user_height > HEIGHT_MIN)
		<dl>
			<dt>Рост:</dt>
			<dd>{{ $userData->user_height }} см</dd>
		</dl>
		@endif
		@if ($userData->user_weight > WEIGHT_MIN)
		<dl>
			<dt>Вес:</dt>
			<dd>{{ $userData->user_weight }} кг</dd>
		</dl>
		@endif
	</fieldset>
	<fieldset>
		<dl>
			<dt>Дата создания:</dt>
			<dd>{{ $userData->date_make }}</dd>
		</dl>
		@if (!empty($userData->date_refresh))
		<dl>
			<dt>Дата обновления:</dt>
			<dd>{{ $userData->date_refresh }}</dd>
		</dl>
		@endif
	</fieldset>
	<div class="clear"></div>
	@if (!empty($userData->target_meet_out))
	<fieldset class="mono mar1"><dl><dt>Цели знакомства:</dt><dd>{{ $userData->target_meet_out }}</dd></dl></fieldset>
	@endif
	@if (!empty($userData->interests_out))
	<fieldset class="mono"><dl><dt>Интересы:</dt><dd>{{ $userData->interests_out }}</dd></dl></fieldset>
	@endif
	@if (!empty($userData->user_partner_description))
	<fieldset class="mono"><dl><dt>Хочу найти:</dt><dd>{{ $userData->user_partner_description }}</dd></dl></fieldset>
	@endif
	<div class="clear"></div>
	@if (!empty($userData->user_icq) || !empty($userData->user_phone) || !empty($userData->user_url))
	<fieldset>
		@if (!empty($userData->user_icq))<dl><dt>Моя Аська:</dt><dd>@auth {{ $userData->user_icq }}@else вы не зарегистрированы@endauth</dd></dl>@endif
		@if (!empty($userData->user_phone))<dl><dt>Моя телефон:</dt><dd>@auth {{$userData->user_phone }}@else вы не зарегистрированы@endauth</dd></dl>@endif
		@if (!empty($userData->user_url))<dl><dt>Моя сайт:</dt><dd>@auth {{ $userData->user_url }}@else вы не зарегистрированы@endauth</dd></dl>@endif
	</fieldset>
	<div class="clear"></div>
	@endif
</div>
<div class="pad5">
<script type="text/javascript"><!--
	google_ad_client = "ca-pub-6379140164632940";
	/* Анкета Внизу */
	google_ad_slot = "2723724227";
	google_ad_width = 468;
	google_ad_height = 60;
	//-->
	</script>
	<script type="text/javascript" async src="http://pagead2.googlesyndication.com/pagead/show_ads.js"></script>
</div>
@overwrite