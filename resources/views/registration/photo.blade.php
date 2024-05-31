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
<form name="anketa" action="{{ route('registration.edit.photo') }}" enctype="multipart/form-data" method="post">
{{ csrf_field() }}
@if (!empty($photos))
	<h4 class="menu_registration"><div>Главное фото</div></h4>
	<p class="pad1 pad2">Это фото <strong>будет отображаться первым</strong> при поиске анкет. Лучше всего, если это будет <strong>ваш портрет</strong>, тогда на фото вас будет легче увидеть. Эту фотографию вы можете в любой момент поменять в разделе редактирование. Если вы ее удалите, то следующая фотография (если загружено более 1 фото) станет главной.</p>
	@foreach ($photos as $item)
	@if ($loop->first)
	<x-photo :photo="$item" />
	@endif
	@endforeach
@endif
@if (count($photos) > 1)
	<h4 class="menu_registration"><div>Дополнительные фото</div></h4>
	<p class="pad1 pad2">Кроме главной фотографии вы можете закачать на сайт еще <strong>{{(5-count($photos))}} дополнительных фото</strong>.</p>
	@foreach ($photos as $item)
	@if (!$loop->first)
	<x-photo :photo="$item" />
	@endif
	@endforeach
@endif
	<p class="pad2"></p>
	@if (count($photos) < 5)
	<div class="bord1"></div>
	<p class="pad1 pad2">Мы принимаем фотографии только <strong>jpg</strong>, <strong>gif</strong> и <strong>png</strong> форматов размером не более <strong>500 кб</strong>. 
	Убедительная просьба <strong>размещать только свои фотографии</strong>, а также <strong>не размещать порно фото (половые органы крупным планом)</strong>. Все фотографии модерируются и нарушители безжалостно удаляются.</p>
	<x-error errName=photo_link />
	<p class="pad3"><input type="file" size="25" name="photo_link"></p>
	<input type="hidden" name="otsil" value="1" />
	<p class="pad2"></p>
	<p class="pad3"><input type="submit" name="sent" onclick="find_otsil()" value="Добавить фото" /></p>
	@endif
</form>
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