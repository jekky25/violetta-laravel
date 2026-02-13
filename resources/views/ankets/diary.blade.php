@extends('layouts.app')
@section('title', $title)
@section('main_body')
<h1 class="mTit">{{ $userData->name }}, {{ $userData->age_str }}, {{ $userData->city->name }}</h1>
<x-ank-menu :user-data="$userData" />
@if (!empty($diaries))
<table class="ankDnevnik">
@foreach ($diaries as $item)
	<x-blocks-diary :item="$item" />
@endforeach
</table>
@endif
<x-pagination :items="$diaries" />
@auth
@if ($user->id == $userData->id)
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
<div class="error">
@foreach ($errors->comment->all() as $message)
<p>{{ $message }}</p>
@endforeach
	</div>
@endif
<form name="anketa" class="addFile" action="{{route('ank.diary.add')}}" method="post" enctype="multipart/form-data">
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
			<td width="50%"><input type="hidden" name="otsil" value="1" />
				<input type="hidden" name="add" value="1" />
				<x-submit name="send" onclick="find_otsil()" value="добавить запись" />
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