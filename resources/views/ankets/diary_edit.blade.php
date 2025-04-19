@extends('layouts.app')
@section('title', $title)
@section('main_body')
<h1 class="mTit">{{ $userData->name }}, {{ $userData->user_age_str }}, {{ $userData->city->name }}</h1>
<x-ank-menu :user-data="$userData" />
<h4 class="pinkLine">Редактировать запись</h4>
{{--<div class="banerFoto">
<script type="text/javascript"><!--
google_ad_client = "ca-pub-6379140164632940";
/* Дневники 468x60 */
google_ad_slot = "5785131254";
google_ad_width = 468;
google_ad_height = 60;
//-->
</script>
<script type="text/javascript" async src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
</script>
</div>--}}
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
@if (!empty ($errors->comment->all()))
<div class="pad3 error">
@foreach ($errors->comment->all() as $message)
<p>{{ $message }}</p>
@endforeach
	</div>
@endif
<form name="anketa" class="addFile" action="{{route('ank.diary.edit.id', $diary->id)}}" method="post" enctype="multipart/form-data">
	{{ csrf_field() }}
	@method('PUT')
	<table style="width:100px;">
		<tr>
			<td width="50%" align="right"><div class="dnevTeemTitle"><p>{{ $diary->create_time }}</p>Тема:</div></td>
			<td width="50%"><input type="text" class="input3" name="title" value="{{ $diary->user_dnevnik_title }}" /></td>
		</tr>
		<tr>
			<td colspan="2">
				<textarea class="textarea2" name="description" wrap="virtual">{{ $diary->user_dnevnik_text }}</textarea>
			</td>
		</tr>
		@if (!empty($diary->picture))
		<tr>
			<td>
				<img width="100" src="{{ (new FileService)->outDiaryPicture($diary->picture, $diary->user->user_sex) }}" alt="" style="vertical-align:middle; margin-right:20px;" />
				<a class="delFoto" href="{{route('ank.diary.delete.photo.id', $diary->id)}}">удалить</a>
			</td>
			<td>
				<input type="file" class="login" size="25" name="photo_link"  />
			</td>
		</tr>
		@else
		<tr>
			<td cplspan="2">
				<input type="file" class="login" size="25" name="photo_link"  />
			</td>
		</tr>														
		@endif
		<tr>
			<td colspan="2" align="center">
				<input type="hidden" name="otsil" value="1" />
				<input type="submit" name="send" onclick="find_otsil()" value="Обновить запись" />
			</td>
		</tr>
	</table>	
</form>
@endif
@overwrite