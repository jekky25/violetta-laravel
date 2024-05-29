@extends('layouts.app')
@section('title', $title)
@section('main_body')
<h1 class="mTit">{{ $userData->user_name }}, {{ $userData->user_age_str }}, {{ $userData->city->name }}</h1>
<x-ank-menu :user-data="$userData" />
<h3 class="kommentTitle">{{ $diary->dnevniki_title }}@if ($comments->total() > 0) - комментарии ({{ $comments->total() }})@else - комментарии (нет)@endif</h3>
<table class="ankDnevnik">
	<tr>
		<td>
			<h4 class="@if (isset($userData->user_class_a)){{ $userData->user_class_a }}@endif"><a href="{{route('ank.id', $userData->user_id)}}">{{ $userData->user_name }}</a>
				<p>
				@if (!empty($user) && $user->user_id == $diary->dnevniki_user_id)
					<a class="editBut" title="редактировать" href="{{route('ank.diary.edit.id', $diary->dnevniki_id)}}"></a>
					<a class="delBut" title="удалить" href="{{route('ank.diary.delete.id', $diary->dnevniki_id)}}"></a>
				@endif
				{{ $diary->add_time }}</p>
			</h4>
			<div class="dnevBody clear">
			@if (!empty($diary->dnevniki_picture))
				<a class="dnevBodyPic1" href="{{route('ank.id', $userData->user_id)}}"><img src="{{ App\Helpers\Helper::outDiaryPicture($diary->dnevniki_picture, $userData->user_sex) }}" /></a>
			@elseif (!empty($userData->foto_user_id))
				<a class="dnevBodyPic2" href="{{route('ank.id', $userData->user_id)}}"><img src="{{ App\Helpers\Helper::outPicture($diary->foto_user_id, $userData->user_sex) }}" /></a>
			@endif
			@if (!empty($diary->dnevniki_picture))
				<div class="mrg2">{{ $diary->dnevniki_text }}</div>
			@elseif ( !empty($diary->foto_url ))
				<div class="mrg3">{{ $diary->dnevniki_text }}</div>
			@else
			{{ $diary->dnevniki_text }}
			@endif
			</div>
		</td>
	</tr>
</table>
<div class="bord1"></div>
@if (!empty($comments))
<table class="ankDnevnik">
@foreach ($comments as $item)
	@if ($loop->index == 11)
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
			<h4 class="{{ $item->user->user_class_a }}"><a href="{{route('ank.id', $item->user->user_id)}}">{{ $item->user->user_name }}</a>@if (!empty($item->comment_title)) - {{ $item->comment_title }}@endif
				<p>
					@if (!empty($user) && $user->user_id == $item->user->user_id)
					<a class="editBut" title="редактировать" href="{{route('ank.diary.comment.edit.id', $item->comment_id)}}"></a>
					<a class="delBut" title="удалить" href="{{route('ank.diary.comment.delete.id', $item->comment_id)}}"></a>
					@endif
					{{ $item->add_time }}
				</p>
			</h4>
			<div class="dnevBody clear">
			@if (!empty($item->comment_picture))
				<a class="dnevBodyPic1" href="{{route('ank.id', $item->user->user_id)}}"><img src="{{ App\Helpers\Helper::outDiaryCommentPicture($item->comment_picture, $item->user->user_sex) }}" /></a>
			@elseif (!empty($item->foto_url))
				<a class="dnevBodyPic2" href="{{route('ank.id', $item->user->user_id)}}"><img src="{{ App\Helpers\Helper::outPicture($item->foto_url, $item->user->user_sex) }}" /></a>
			@endif
			@if (!empty($item->comment_picture))
				<div class="mrg2">{!! $item->comment_text !!}</div>
			@elseif (!empty($item->foto_url))
				<div class="mrg3">{!! $item->comment_text !!}</div>
			@else
				{!! $item->comment_text !!}
			@endif
			</div>
		</td>
	</tr>
@endforeach
</table>
@endif
<x-pagination :items="$comments" :pagination="$pagination" />
@auth
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
<h2 class="mTit">Добавить новую запись</h2>
@if(session('success'))
<p class="mess">{{session('success')}}</p>
@endif
@if (!empty ($errors->comment->all()))
<div class="pad3 error">
@foreach ($errors->comment->all() as $message)
<p>{{ $message }}</p>
@endforeach
	</div>
@endif
<form name="anketa" class="addFile" action="{{route('ank.diary.comment.add', $diary->dnevniki_id)}}" method="post" enctype="multipart/form-data">
{{ csrf_field() }}
	<table>
		<tr>
			<td width="50%" align="right">Тема:</td>
			<td width="50%"><input type="text" class="input3" name="title" value="{{ old('title') }}" /></td>
		</tr>
		<tr>
			<td colspan="2">
				<textarea class="textarea2" name="description" wrap="virtual">{{ old('description') }}</textarea>
			</td>
		</tr>
		<tr>
			<td width="50%" align="right">
				<a class="screpka" href="javascript:addfile('file')"></a></td>
			<td width="50%"><input type="hidden" name="otsil" value="1" />
				<input type="hidden" name="add" value="1" />
				<input type="hidden" name="start" value="{{ old('start') }}" />
				<input type="submit" name="send" onclick="find_otsil()" value="Добавить запись" />
			</td>
		</tr>
	</table>	
	<div id="file">
		<input type="file" class="login" size="45" name="photo_link" />
	</div>
</form>
@endauth
@overwrite