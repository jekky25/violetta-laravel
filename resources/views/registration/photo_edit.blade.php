@extends('layouts.app')
@section('title', $title)
@section('main_body')
<h1 class="mTit">Редактирование данных</h1>
<x-menu />
<p class="pad1"></p>
<h4 class="reg_title">Ваши фотографии</h4>
<p class="pad1"></p>
@if(session('success'))
<h4 class="reg_title2">информация сохранена</h4>
@endif
@if (!empty ($errors->all()))
<h4 class="reg_title2">данные не сохранены, т. к. не все поля правильно заполнены</h4>
@endif
<p class="pad3">Вы в любой момент на место этой фотографии можете закачать новую.</p>
@if (!empty($photo))
<table class="dnevBodyPic3">
	<tr>
		<td><div class="photo-block"><img class="photo-block-img" alt="" src="{{ $photo->url }}" /></div></td>
	</tr>
</table>
@endif
<p class="pad2"></p>
<x-error errName="photo" />
<table class="wth3">
	<tr>
		<td class="pad3">
			<form name="anketa" onsubmit="return (find_otsil())" action="{{ route('registration.edit.photo.edit.post', $photo->id) }}" enctype="multipart/form-data" method="post">
				{{ csrf_field() }}
				@method('PUT')
				<p><input type="file" size="25" name="photo" /></p>
				<p>
					<x-submit name="sent" value="заменить фото" />
				</p>
			</form>
		</td>
		<td class="pad3">
			<form name="anketa2" action="{{route('registration.edit.photo.delete', $photo->id)}}" method="get">
				<p><!--x-submit name="sent" value="удалить фото" /-->
					<a 
						class="button open-modal" 
						title="удалить" 
						href="javascript:void(0);"
						data-url="{{route('registration.edit.photo.delete.action', $photo->id)}}"
						data-title="удалить фото"
						data-text="Вы уверены, что хотите удалить фото?"
					>удалить фото</a>
				</p>
			</form>
		</td>
	</tr>
</table>
<script language=JavaScript>
function find_otsil() {
document.anketa.sent.value = 'Подождите, идет обновление данных...';
document.anketa.sent.disabled = true;
document.anketa.submit();
}
</script>
@overwrite