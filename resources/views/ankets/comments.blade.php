@extends('layouts.app')
@section('title', $title)
@section('main_body')
<h1 class="mTit">{{ $diary->user->name }}, {{ $diary->user->age_str }}, {{ $diary->user->city->name }}</h1>
<x-ank-menu :user-data="$diary->user" />
<h3 class="kommentTitle">{{ $diary->title }}@if ($comments->total() > 0) - комментарии ({{ $comments->total() }})@else - комментарии (нет)@endif</h3>
<table class="ankDnevnik">
	<tr>
		<td>
			<h4 class="@isset($diary->user->class_a){{ $diary->user->class_a }}@endisset"><a href="{{route('ank.id', $diary->user->id)}}">{{ $diary->user->name }}</a>
				<p>
				@if (!empty($user) && $user->id == $diary->user_id)
					<a class="editBut" title="редактировать" href="{{route('ank.diary.edit.id', $diary->id)}}"></a>
					<a 
						class="delBut open-modal"
						title="удалить"
						href="javascript:void(0);"
						data-url="{{route('ank.diary.delete.id', $diary->id)}}"
						data-title="Удаление записи"
						data-text="Вы уверены, что хотите удалить запись?"
						></a>
				@endif
				{{ $diary->add_time }}</p>
			</h4>
			<div class="dnevBody clear">
			@if (!empty($diary->picture))
				<a class="dnevBodyPic1" href="{{route('ank.id', $diary->user->id)}}"><img src="{{ (new FileService)->outDiaryPicture($diary->picture, $diary->user->sex) }}" /></a>
			@elseif (!empty($diary->user->foto_user_id))
				<a class="dnevBodyPic2" href="{{route('ank.id', $diary->user->id)}}"><img src="{{ (new FileService)->outPicture($diary->foto_user_id, $diary->user->sex) }}" /></a>
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
@foreach ($comments as $item)
<x-activity-item :item="$item" type="comment" />
@endforeach
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
@if (!empty ($errors->all()))
<div class="pad3 error">
@foreach ($errors->all() as $message)
<p>{{ $message }}</p>
@endforeach
	</div>
@endif
<form name="anketa" class="addFile" action="{{route('ank.diary.comment.add', $diary->id)}}" method="post" enctype="multipart/form-data">
{{ csrf_field() }}
	<table class="add-comment-table">
		<tr>
			<td width="50%" align="right">Тема:</td>
			<td width="50%">
				<x-input name="title" value="{{ old('title') }}" />
			</td>
		</tr>
		<tr>
			<td colspan="2">
				<x-textarea name="description" value="{{ old('description') }}" />
			</td>
		</tr>
		<tr>
			<td width="50%" align="right">
				<a class="screpka" href="javascript:addfile('file')"></a></td>
			<td width="50%">
				<input type="hidden" name="add" value="1" />
				<input type="hidden" name="start" value="{{ old('start') }}" />
				<x-submit name="send" onclick="find_otsil()" value="добавить запись" />
			</td>
		</tr>
	</table>	
	<div id="file">
		<input type="file" class="login" size="45" name="photo" />
	</div>
</form>
@endauth
@overwrite