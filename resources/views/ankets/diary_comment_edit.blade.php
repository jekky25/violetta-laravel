@extends('layouts.app')
@section('title', $title)
@section('main_body')
<h1 class="mTit">{{ $userData->name }}@if (!empty($userData->age_str)), {{ $userData->age_str }}@endif, {{ $userData->city->name }}</h1>
<x-ank-menu :user-data="$userData" />
<h4 class="pinkLine">Редактировать запись</h4>
	<script language=JavaScript>
		function find_otsil()
		{
			document.anketa.send.value = 'Подождите, идет отправка данных...';
			document.anketa.send.disabled = true;
			document.anketa.submit();
		}
	</script>
@if (!empty($comment))
<form name="anketa" class="addFile" action="{{route('ank.diary.comment.edit.id', $comment->id)}}" method="post" enctype="multipart/form-data">
@method('PUT')
{{ csrf_field() }}
@if(session('success'))
<p class="mess">{{session('success')}}</p>
@endif
@if (!empty ($errors->all()))
<div class="pad3 error">
@foreach ($errors->all() as $message)
<p>{{ $message }}</p>
@endforeach
	</div>
@endif
	<table class="add-comment-table">
		<tr>
			<td width="50%" align="right"><div class="dnevTeemTitle"><p>{{ $comment->create_time }}</p>Тема:</div></td>
			<td width="50%">
				<x-input name="title" value="{{ old('title', $comment->title) }}" />
			</td>
		</tr>
		<tr>
			<td colspan="2">
				<x-textarea name="description" value="{{ old('description', $comment->description) }}" />
			</td>
		</tr>
		@if (!empty($comment->picture))
		<tr>
			<td>
				<img width="100" src="{{ (new FileService)->outDiaryCommentPicture($comment->picture, $userData->sex) }}" alt="" style="vertical-align:middle; margin-right:20px;" />
				<a class="delFoto" href="{{route('ank.diary.comment.delete.photo.id', $comment->id)}}">удалить</a>
			</td>
			<td>
				<input type="file" class="login" size="25" name="photo"  />
			</td>
		</tr>
		@else
		<tr>
			<td cplspan="2">
				<input type="file" class="login" size="25" name="photo"  />
			</td>
		</tr>														
		@endif
		<tr>
			<td colspan="2" align="center">
				<x-submit name="send" onclick="find_otsil()" value="обновить запись" />
			</td>
		</tr>
	</table>	
</form>
@endif
@overwrite