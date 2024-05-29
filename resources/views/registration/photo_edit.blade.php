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
@if (!empty ($errors->comment->all()))
<h4 class="reg_title2">данные не сохранены, т. к. не все поля правильно заполнены</h4>
@endif
<p class="pad3">Вы в любой момент на место этой фотографии можете закачать новую.</p>
@if (!empty($photo))
<table class="dnevBodyPic3">
	<tr>
		<td><div class="photo-block"><img class="photo-block-img" alt="" src="{{ App\Helpers\Helper::outPicture($photo->fotos_id, $user->user_sex) }}" /></div>
		</td>
	</tr>
</table>
@endif
<p class="pad2"></p>
<x-error errName=photo_link />
<table class="wth3">
	<tr>
		<td class="pad3">
			<form name="anketa" onsubmit="return (find_otsil())" action="{{ route('registration.edit.photo.edit.post', $photo->fotos_id) }}" enctype="multipart/form-data" method="post">
				{{ csrf_field() }}
				<p><input type="file" size="25" name="photo_link" /></p>
				<p>
					<input type="hidden" name="otsil" value="1" />
					<input type="submit" name="sent" value="Заменить фото">
				</p>
			</form>
		</td>
		<td class="pad3">
			<form name="anketa2" action="{{route('registration.edit.photo.delete', $photo->fotos_id)}}" method="get">
				<p><input type="hidden" name="otsil" value="1" /><input type="submit" name="sent" value="Удалить фото" />
			</form>
		</td>
	</tr>
</table>
<div class="pad5">					
	<script type="text/javascript"><!--
google_ad_client = "ca-pub-6379140164632940";
/* 468x60 редактирование анкеты */
google_ad_slot = "8757413983";
google_ad_width = 468;
google_ad_height = 60;
//-->
	</script>
	<script type="text/javascript" async src="http://pagead2.googlesyndication.com/pagead/show_ads.js"></script>
</div>
<p class="pad2"></p>
<script language=JavaScript>
function find_otsil() {
document.anketa.sent.value = 'Подождите, идет обновление данных...';
document.anketa.sent.disabled = true;
document.anketa.submit();
}
</script>
@overwrite