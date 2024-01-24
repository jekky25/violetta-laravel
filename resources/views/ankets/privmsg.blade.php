@extends('layouts.app')
@section('title', $title)
@section('main_body')
<h1 class="mTit">Мои сообщения</h1>
@if (!empty ($messages))
<form name="anketa" action="{{route('privmsg.delete')}}" method="post">
	{{ csrf_field() }}
	<table class="Usermess">
		<thead>
			<td class="UsermessCol1"><input type="checkbox" onclick="selectCheckBoxs(this, 'mark');" name="selectall" /></td>
			<td class="UsermessCol2">Пользователь</td>
			<td class="UsermessCol3">&nbsp;</td>
			<td class="UsermessCol4">&nbsp;</td>
			<td class="UsermessCol5">Последнее сообщение</td>
		</thead>
		@foreach ($messages as $item)
		@if (!empty ($item->user_mes))
		<tr @if ($loop->index % 2 == 0)class="pinkRow"@endif>
			<td class="UsermessCol1"><input type="checkbox" name="mark[]" value="{{ $item->user_id }}" /></td>
			<td class="UsermessCol2"><a class="ankFotosPics" href="{{route('ank.id', $item->user_id)}}"><img alt="{{ $item->user_mes->user_name }},{{ $item->user_mes->user_age_str }},{{ $item->user_mes->city->name }}" src="{{ App\Helpers\Helper::outPicture($item->photo_main, $item->user_mes->user_sex) }}" /></a></td>
			<td class="UsermessCol3"><a class="{{ $item->user_mes->name_class }}" href="{{route('ank.id', $item->user_id)}}">{{ $item->user_mes->user_name }}</a></td>
			<td class="UsermessCol4"><img alt="Написать сообщение" src="{{ asset('image/mail.png') }}" /><a @if ($item->mess_new > 0) class="newMessage"@endif href="{{route('privmsg.post', $item->user_id)}}">сообщений: {{ $item->count_messages }}</a></td>
			<td class="UsermessCol5"><span>{{ $item->last_date }}</span></td>
		</tr>
		@endif
		@endforeach
	</table>
	@include('pagination', ['items' => $messages])
	<div class="right1 clear"><input type="submit" name="delete" class="bgBut6" value="" /></div>
	<div class="pad5">
<script type="text/javascript"><!--
google_ad_client = "ca-pub-6379140164632940";
/* 468x60 сообщения (В) */
google_ad_slot = "9620496837";
google_ad_width = 468;
google_ad_height = 60;
//-->
</script>
<script type="text/javascript" async src="http://pagead2.googlesyndication.com/pagead/show_ads.js"></script>
</div>
</form>
<script language="Javascript" type="text/javascript">
function selectCheckBoxs(mainbox, boxname)
{
	var elems = mainbox.form.elements;
	var elemnum = elems.length;

	for(i=0; i < elemnum; i++)
		if(elems[i].name.substr(0, boxname.length) == boxname)
			elems[i].checked = mainbox.checked;
}
</script>
@else
<p align="center">Сообщения отсутствуют</p>
@endif
@overwrite