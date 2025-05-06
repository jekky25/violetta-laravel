@extends('layouts.app')
@section('title', $title)
@section('main_body')
<h1 class="mTit">{{ $userData->name }}, {{ $userData->age_str }}, {{ $userData->city->name }}</h1>
<x-ank-menu :user-data="$userData" />
<h3 class="kommentTitle">{{ $diary->title }}@if ($comments->total() > 0) - комментарии ({{ $comments->total() }})@else - комментарии (нет)@endif</h3>
<table class="ankDnevnik">
	<tr>
		<td>
			<h4 class="@isset($userData->class_a){{ $userData->class_a }}@endisset"><a href="{{route('ank.id', $userData->id)}}">{{ $userData->name }}</a>
				<p>
				@if (!empty($user) && $user->id == $diary->user_id)
					<a class="editBut" title="редактировать" href="{{route('ank.diary.edit.id', $diary->id)}}"></a>
					<a class="delBut" title="удалить" href="{{route('ank.diary.delete.id', $diary->id)}}"></a>
				@endif
				{{ $diary->add_time }}</p>
			</h4>
			<div class="dnevBody clear">
			@if (!empty($diary->picture))
				<a class="dnevBodyPic1" href="{{route('ank.id', $userData->id)}}"><img src="{{ (new FileService)->outDiaryPicture($diary->picture, $userData->sex) }}" /></a>
			@elseif (!empty($userData->foto_user_id))
				<a class="dnevBodyPic2" href="{{route('ank.id', $userData->id)}}"><img src="{{ (new FileService)->outPicture($diary->foto_user_id, $userData->sex) }}" /></a>
			@endif
			@if (!empty($diary->picture))
				<div class="mrg2">{{ $diary->description }}</div>
			@elseif ( !empty($diary->foto_url ))
				<div class="mrg3">{{ $diary->description }}</div>
			@else
			{{ $diary->description }}
			@endif
			</div>
		</td>
	</tr>
</table>
<div class="bord1"></div>
@if (!empty($comments))
<table class="ankDnevnik">
@foreach ($comments as $item)
	<tr>
		<td>
			<h4 class="@isset($item->user->class_a){{ $item->user->class_a }}@endisset"><a href="{{route('ank.id', $item->user->id)}}">{{ $item->user->name }}</a>@if (!empty($item->title)) - {{ $item->title }}@endif
				<p>
					@if (!empty($user) && $user->id == $item->user->id)
					<a class="editBut" title="редактировать" href="{{route('ank.diary.comment.edit.id', $item->id)}}"></a>
					<a class="delBut" title="удалить" href="{{route('ank.diary.comment.delete.id', $item->id)}}"></a>
					@endif
					{{ $item->add_time }}
				</p>
			</h4>
			<div class="dnevBody clear">
			@if (!empty($item->picture))
				<a class="dnevBodyPic1" href="{{route('ank.id', $item->user->id)}}"><img src="{{ (new FileService)->outDiaryCommentPicture($item->picture, $item->user->sex) }}" /></a>
			@elseif (!empty($item->foto_url))
				<a class="dnevBodyPic2" href="{{route('ank.id', $item->user->id)}}"><img src="{{ (new FileService)->outPicture($item->foto_url, $item->user->sex) }}" /></a>
			@endif
			@if (!empty($item->picture))
				<div class="mrg2">{!! $item->description !!}</div>
			@elseif (!empty($item->foto_url))
				<div class="mrg3">{!! $item->description !!}</div>
			@else
				{!! $item->description !!}
			@endif
			</div>
		</td>
	</tr>
@endforeach
</table>
@endif
<x-pagination :items="$comments" />
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
<form name="anketa" class="addFile" action="{{route('ank.diary.comment.add', $diary->id)}}" method="post" enctype="multipart/form-data">
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