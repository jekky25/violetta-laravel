@extends('layouts.app')
@section('title', $title)
@section('main_body')
<h1 class="mTit">Редактирование данных</h1>
<ul id="menuReg" class="clear">
	<li class="menuRegAct">Дневник</li>
</ul>
<p class="pad1"></p>
<h4 class="reg_title">Редактировать дневник</h4>
<p class="pad3">Если у Вас есть чем поделиться с другими каким-либо событием, произошедшим в Вашей жизни, или просто интересными мыслями. Напишите здесь в <strong>Вашем дневнике</strong>.</p>
@if (!empty($diaries))
<table class="ankDnevnik">
@foreach ($diaries as $item)
	<tr>
		<td>
			<h4 class="{{ $item->name_class }}"><a href="{{route('ank.id', $item->user->user_id)}}">{{ $item->user->user_name }}</a>
				<div class="titDnev"><a href="{{route('ank.id', $item->user->user_id)}}">{!! \Illuminate\Support\Str::limit($item->dnevniki_title, 40, $end='...') !!}</a></div>
				<p>
				@if (!empty($user) && $user->user_id == $item->user_id)
				<a class="editBut" title="редактировать" href="{{route('registration.edit.diary.id', $item->dnevniki_id)}}"></a>
				<a class="delBut" title="удалить" href="{{route('registration.delete.diary.id', $item->dnevniki_id)}}"></a>
				@endif
				{{ $item->add_time }}</p>
			</h4>
			<div class="dnevBody clear">
	@if (!empty($item->dnevniki_picture))
				<a class="dnevBodyPic1" href="{{route('ank.id', $item->user->user_id)}}"><img src="{{ App\Helpers\Helper::outDiaryPicture($item->dnevniki_picture, $item->user->user_sex) }}" /></a>
	@elseif (!empty($item->foto_user_id))
				<a class="dnevBodyPic2" href="{{route('ank.id', $item->user->user_id)}}"><img src="{{ App\Helpers\Helper::outPicture($item->foto_user_id, $item->user->user_sex) }}" /></a>
	@endif
	@if (!empty($item->dnevniki_picture))
				<div class="mrg2">
	{!! \Illuminate\Support\Str::limit($item->dnevniki_text, 500, $end='...') !!}
				</div>
	@elseif (!empty($item->foto_url))
				<div class="mrg3">
	{!! \Illuminate\Support\Str::limit($item->dnevniki_text, 500, $end='...') !!}
				</div>
	@else
	{!! \Illuminate\Support\Str::limit($item->dnevniki_text, 500, $end='...') !!}
	@endif
			</div>
			<p class="dnevKomm"><a href="{{route('ank.diary.comments', $item->dnevniki_id)}}">комментарии ({{count ($item->comments)}})</a></p>
		</td>
	</tr>
@endforeach
	</table>
@endif
@include('pagination', ['items' => $diaries])					
	<div class="pad5">
<script type="text/javascript"><!--
google_ad_client = "ca-pub-6379140164632940";
/* Дневники 468x60 */
google_ad_slot = "5785131254";
google_ad_width = 468;
google_ad_height = 60;
//-->
</script>
<script type="text/javascript" async src="http://pagead2.googlesyndication.com/pagead/show_ads.js"></script>
<script language=JavaScript>
function find_otsil()
{
	document.anketa.send.value = 'Подождите, идет отправка данных...';
	document.anketa.send.disabled = true;
	document.anketa.submit();
}
function addfile(id)
{
	if (document.getElementById(id).style.display == 'block') {
		document.getElementById(id).style.display = 'none';
	} else {
		document.getElementById(id).style.display = 'block';
	}
}
</script>
</div>
<h2 class="mTit">Добавить новую запись</h2>
@if (!empty ($errors->comment->all()))
<p class="pad3 error">Ошибка:
@foreach ($errors->comment->all() as $item)
{{ $item }}
@endforeach
</p>
@endif
<form name="anketa" class="addFile" action="{{ route ('ank.diary.add') }}" method="post" enctype="multipart/form-data">
	{{ csrf_field() }}
	<table>
		<tr>
			<td width="50%" align="right">Тема:</td>

			<td width="50%"><input type="text" class="input3" name="title" value="" /></td>
		</tr>
		<tr>
			<td colspan="2">
				<textarea class="textarea2" name="description" wrap="virtual"></textarea>
			</td>
		</tr>
		<tr>

			<td width="50%" align="right">
				<a class="screpka" href="javascript:addfile('file')"></a></td>
			<td width="50%"><input type="hidden" name="otsil" value="1" />
				<input type="hidden" name="add" value="1" />
				<input type="submit" name="send" onclick="find_otsil()" value="Добавить запись" />
			</td>
		</tr>
	</table>
	<div id="file">
		<input type="file" class="login" size="45" name="photo_link" />
	</div>
</form>
@overwrite