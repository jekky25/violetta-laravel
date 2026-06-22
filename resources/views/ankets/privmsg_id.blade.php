@extends('layouts.app')
@section('title', $title)
@section('main_body')
<h1 class="mTit">Мои сообщения</h1>
@if (!empty($anketUserData))
<script language="JavaScript" type="text/javascript">
function vote(score)
{
	var url = '{{route(Route:: currentRouteName(), [$anketUserData->id, 'send_golos' => 1 ])}}';
	url += '&golos='+score;
	window.location = url;
	return false;
}
</script>
<ul id="ankFotos" class="clear pad2">
    @if ($anketUserData->photos_count > 0)
        @foreach ($anketUserData->photo as $item)
		@if ($loop->iteration > 1) @continue @endif
		<li><a class="ankFotosPics" href="{{route('ank.id', $anketUserData->id)}}"><img src="{{ (new FileService)->outPicture($item->id, $anketUserData->sex) }}" /></a></li>
        @endforeach
	@else
		<li><a class="ankFotosPics" href="{{route('ank.id', $anketUserData->id)}}">@if ($anketUserData->sex == MEN)<img src="{{ asset('image/no_foto_m_vip.jpg') }}" />@else<img src="{{ asset('image/no_foto_w_vip.jpg') }}" />@endif</a></li>
	@endif
		<li>
			<p><strong>Город:</strong> {{ $anketUserData->city->name }} ({{ $anketUserData->country->name }})</p>
			<p><strong>Возраст:</strong> {{ $anketUserData->age_str }}</p>
			<p><strong>Знак зодиака:</strong> <a href="{{route('horoscope.id', $anketUserData->zodiac['zodiac_id'])}}" title="Узнайте свой Зодиак">{{$anketUserData->zodiac['zodiac_text']}}</a></p>
			<p>{{ $anketUserData->user_last_visit }}</p>
			<p>Просмотров за месяц: {{ $anketUserData->ankVisits }}</p>
			<rating :user='@json($anketUserData)' :auth-user='@json(auth()->check() ? auth()->user() : null)' :evaluationed='@json($ankEvaluationed)'></rating>
		</li>
	</ul>
	@if (!empty($messages))
		@foreach ($messages as $item)
		<div class="active-item-container">
			<div class="active-item-title line-{{ $item->name_class }}">
				<a class="active-item-name" href="{{route('ank.id', $item->user->id)}}">{{ $item->user->name }}</a>
				<div class="active-item-right">
					<a 
						class="delete open-modal"
						title="удалить"
						href="javascript:void(0);"
						data-url="{{route('privmsg.post.delete', $item->id)}}"
						data-title="Удаление сообщения"
						data-text="Вы уверены, что хотите удалить сообщение?"
					></a>
					<span class="active-item-time">{{ $item->add_time }}</span>
				</div>
			</div>
			<div class="active-item-body">
				<div class="active-item-content clear">
					{!! $item->description !!}
				</div>
			</div>
		</div>
@endforeach
	<x-pagination :items="$messages" />
	@endif
	<div class="pad2"></div>
	<h2 class="mTit">Добавить сообщение</h2>
	@if(session('success'))
	<p class="mess">{{session('success')}}</p>
@endif
@if (!empty ($errors->all()))
	<div class="error">
@foreach ($errors->all() as $message)
		<p>{!! $message !!}</p>
@endforeach
	</div>
@endif
	<div id="smileBox">
	@if (!empty($smiles))
		<table id="smilesTable">
		@foreach ($smiles as $item)
		@if ($loop->index % 10 == 0)<tr>@endif
			<td><a href="javascript://" class="icon-smile" data-code="{{ $item->code }}"><img src="{{ asset('image/smiles/' . $item->img) }}" alt="" /></a></td>
		@if (($loop->index + 1) % 10 == 0 or $loop->last)</tr>@endif
		@endforeach
		</table>
	@endif
	</div>
	<form name="anketa" action="{{route('privmsg.post.add', $anketUserData->id)}}" method="post">
		{{ csrf_field() }}
		<table width="100%">
			<tr>
				<td>
					<x-textarea name="description" class="textMessage" value="{{ old('description') }}" />
				</td>
			</tr>
			<tr>
				<td>
					<div class="pad7 pos1">
						<x-submit name=send value="отправить сообщение" />
						<a href="javascript://" 
						class="smileIcon smile-modal"
						data-title="Смайлы"
						data-content-id="smileBox"
						></a></div>
				</td>
			</tr>
		</table>
	</form>
@else
<p>Пользователь не найден</p>
@endif
@overwrite