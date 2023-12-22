@extends('layouts.app')
@section('title', $title)
@section('main_body')
<h1 class="mTit">{{ $userData->user_name }}, {{ $userData->user_age_str }}, {{ $userData->city->name }}</h1>
@include('ankets.menu', ['userData' => $userData])
@if (!empty($diaries))
<table class="ankDnevnik">
@foreach ($diaries as $item)
	@if ($loop->index == 5)
	<tr>
		<td colspan="2">						
			<div class="banerFoto">
				<script type="text/javascript"><!--
google_ad_client = "ca-pub-6379140164632940";
/* Дневники 468x60 */
google_ad_slot = "5785131254";
google_ad_width = 468;
google_ad_height = 60;
//-->
</script>
				<script type="text/javascript" async src="http://pagead2.googlesyndication.com/pagead/show_ads.js"></script>
			</div>
		</td>
	</tr>
	@endif
	<tr>
		<td>
			<h4 class="{{ $item->name_class }}">{{ $item->dnevniki_title }}
				<p>
					@if (!empty($user) && $user->user_id == $item->dnevniki_user_id)
					<a class="editBut" title="редактировать" href="{{route('ank.diary.edit.id', $item->dnevniki_id)}}"></a>
					<a class="delBut" title="удалить" href="{{route('ank.diary.delete.id', $item->dnevniki_id)}}"></a>
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
				<div class="mrg2">{!! $item->dnevniki_text !!}</div>
			@elseif (!empty($item->foto_url))
				<div class="mrg3">{!! $item->dnevniki_text !!}</div>
			@else
				{!! $item->dnevniki_text !!}
			@endif
			</div>
			<p class="dnevKomm"><a href="{{route('ank.diary.comments', $item->dnevniki_id)}}">комментарии ({{count ($item->comments)}})</a></p>
		</td>
	</tr>
@endforeach
</table>
@endif
@include('pagination', ['items' => $diaries])
@auth
@if ($user->user_id == $userData->user_id)
<script language=JavaScript>
{literal}
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
{/literal}
</script>
<h2 class="mTit">Добавить новую запись</h2>
{if !empty($error)}<p class="pad3 error">Ошибка: {$error}{/if}
<form name="anketa" class="addFile" action="{$smarty.const.SITE_URL}ank/dnevnik_{$anketUserData.user_id}.html" method="post" enctype="multipart/form-data">
	<table>
		<tr>
			<td width="50%" align="right">Тема:</td>
			<td width="50%"><input type="text" class="input3" name="title" value="{if !empty($smarty.post.title)}{$smarty.post.title}{/if}" /></td>
		</tr>
		<tr>
			<td colspan="2">
				<textarea class="textarea2" name="description" wrap="virtual">{if !empty($smarty.post.description)}{$smarty.post.description}{/if}</textarea>
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
@endif
@endauth
@overwrite