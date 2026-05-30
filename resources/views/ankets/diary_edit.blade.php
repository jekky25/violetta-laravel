@extends('layouts.app')
@section('title', $title)
@section('main_body')
<h1 class="mTit">{{ $userData->name }}, {{ $userData->age_str }}, {{ $userData->city->name }}</h1>
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
@if (!empty($diary))
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
<form name="anketa" class="addFile" action="{{route('ank.diary.edit.id', $diary->id)}}" method="post" enctype="multipart/form-data">
	{{ csrf_field() }}
	@method('PUT')
	<table class="add-comment-table">
		<tr>
			<td width="50%" align="right"><div class="dnevTeemTitle"><p>{{ $diary->create_time }}</p>Тема:</div></td>
			<td width="50%">
				<x-input name="title" value="{{ old('title', $diary->title) }}" />
			</td>
		</tr>
		<tr>
			<td colspan="2">
				<x-textarea name="description" value="{{ old('description', $diary->description) }}" />
			</td>
		</tr>
		@if (!empty($diary->picture))
		<tr>
			<td>
				<img width="100" src="{{ (new FileService)->outDiaryPicture($diary->picture, $diary->user->sex) }}" alt="" style="vertical-align:middle; margin-right:20px;" />
				<a 
					class="delFoto open-modal"
					title="удалить"
					href="javascript:void(0);"
					data-url="{{route('ank.diary.delete.photo.id', $diary->id)}}"
					data-title="Удаление фото"
					data-text="Вы уверены, что хотите удалить фото?"
				>удалить</a>
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
				<input type="hidden" name="otsil" value="1" />
				<x-submit name="send" onclick="find_otsil()" value="обновить запись" />
			</td>
		</tr>
	</table>	
</form>
@endif
@overwrite