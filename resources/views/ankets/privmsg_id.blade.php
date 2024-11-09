@extends('layouts.app')
@section('title', $title)
@section('main_body')
<h1 class="mTit">Мои сообщения</h1>
@if (!empty($anketUserData))
<script language="JavaScript" type="text/javascript">
function vote(score)
{
	var url = '{{route(Route:: currentRouteName(), [$anketUserData->user_id, 'send_golos' => 1 ])}}';
	url += '&golos='+score;
	window.location = url;
	return false;
}
</script>
<ul id="ankFotos" class="clear">
    @if ($anketUserData->user_fotos > 0)
        @foreach ($anketUserData->photo as $item)
		@if ($loop->iteration > 1) @continue @endif
		<li><a class="ankFotosPics" href="{{route('ank.id', $anketUserData->user_id)}}"><img src="{{ (new FileService)->outPicture($item->fotos_id, $anketUserData->user_sex) }}" /></a></li>
        @endforeach
	@else
		<li><a class="ankFotosPics" href="{{route('ank.id', $anketUserData->user_id)}}">@if ($anketUserData->user_sex == MEN)<img src="{{ asset('image/no_foto_m_vip.jpg') }}" />@else<img src="{{ asset('image/no_foto_w_vip.jpg') }}" />@endif</a></li>
	@endif
		<li>
			<p><strong>Город:</strong> {{ $anketUserData->city->name }} ({{ $anketUserData->country->name }})</p>
			<p><strong>Возраст:</strong> {{ $anketUserData->user_age_str }}</p>
			<p><strong>Знак зодиака:</strong> <a href="{{route('goroskop.id', $anketUserData->zodiac['zodiac_id'])}}" title="Узнайте свой Зодиак">{{$anketUserData->zodiac['zodiac_text']}}</a></p>
			<p>{{ $anketUserData->user_last_visit }}</p>
			<p>Просмотров за месяц: {{ $anketUserData->ankVisits }}</p>
			<table>
				<tr>
					<td><p>Рейтинг:</p></td>
					<td class="wth4">
						<div class="div-rating2">
							<ul class="div-rating">
								<li class="current-rating" style="width:{{ $anketUserData->user_reiting_str }}px;">&nbsp;</li>
								@auth
									@if ($userData->user_id != $anketUserData->user_id && !$ankEvaluationed)
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
	<div class="pad2"></div>
	@if (!empty($messages))
	<table class="UsermessID">
	@foreach ($messages as $item)
		<tr>
			<td>
			@if ($item->user_otprav == $userData->user_id)
				<h4 class="outMeMess"><a href="{{route('ank.id', $userData->user_id)}}">{{ $userData->user_name }}</a>
			@else
				<h4 class="inMeMess"><a href="{{route('ank.id', $anketUserData->user_id)}}">{{ $anketUserData->user_name }}</a>
			@endif
					<p>{{ $item->last_date }}<a class="delBut2" title="удалить" href="{{route('privmsg.post.delete', $item->message_id)}}"></a></p>
				</h4>
				<div class="messBody clear">{!! $item->privmess_text !!}</div>
			</td>
		</tr>
	@endforeach
	</table>
	<x-pagination :items="$messages" />
	@endif
	<div class="pad2"></div>
	<h2 class="mTit">Добавить сообщение</h2>
	@if(session('success'))
	<p class="mess">{{session('success')}}</p>
@endif
@if (!empty ($errors->comment->all()))
	<div class="error">
@foreach ($errors->comment->all() as $message)
		<p>{!! $message !!}</p>
@endforeach
	</div>
@endif
	<div id="smileBox">
	@if (!empty($smiles))
		<table id="smilesTable">
		@foreach ($smiles as $item)
		@if ($loop->index % 10 == 0)<tr>@endif
			<td><a href="javascript://" onClick="checkSmile('{{ $item->smile_code }}'); return false;"><img src="{{ asset('image/smiles/' . $item->smile_img) }}" alt="" /></a></td>
		@if (($loop->index + 1) % 10 == 0 or $loop->last)</tr>@endif
		@endforeach
		</table>
	@endif
	</div>
	<form name="anketa" action="{{route('privmsg.post.add', $anketUserData->user_id)}}" method="post">
		{{ csrf_field() }}
		<table width="100%">
			<tr>
				<td>
					<textarea class="textarea2" id="textMessage" name="message_text" wrap="virtual">{{ old('message_text') }}</textarea>
				</td>
			</tr>
			<tr>
				<td>
					<div class="pad7 pos1"><input type="submit" name="send" class="bgBut7" value="" /> <a href="javascript://" class="smileIcon" onClick="openFrame1({idFrame : 'smileBox', winWidth : '500', winHeight : '300'}); return false;"></a></div>
				</td>
			</tr>
		</table>
	</form>
<script type="text/javascript">
function checkSmile (x) 
{
	var e = document.getElementById('textMessage').value;
	$('#textMessage').val(e + x);
	closeButfunk();
}
</script>
@else
<p>Пользователь не найден</p>
@endif
@overwrite