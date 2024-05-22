@extends('layouts.app')
@section('title', $title)
@section('main_body')
<h1 class="mTit">Редактирование данных</h1>
<ul id="menuReg" class="clear">
	<li class="menuRegAct">Дневник</li>
</ul>
<p class="pad1"></p>
<h4 class="reg_title">Редактировать дневник</h4>
<p class="pad3">Если у Вас есть чем поделиться с другими каким-либо событием, произошедшим в Вашей жизни, или просто интересными мыслями. Напишите здесь в <strong>Вашем дневнике</strong>.</p>
@if (!empty($diaries))
<table class="ankDnevnik">
@foreach ($diaries as $item)
@include('blocks.diary', ['item' => $item])
@endforeach
	</table>
@endif
<x-pagination :items="$diaries" :pagination="$pagination" />
	<div class="pad5">
<script type="text/javascript"><!--
google_ad_client = "ca-pub-6379140164632940";
/* Дневники 468x60 */
google_ad_slot = "5785131254";
google_ad_width = 468;
google_ad_height = 60;
//-->
</script>
<script type="text/javascript" async src="http://pagead2.googlesyndication.com/pagead/show_ads.js"></script>
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
</div>
<h2 class="mTit">Добавить новую запись</h2>
@if (!empty ($errors->comment->all()))
<p class="pad3 error">Ошибка:
@foreach ($errors->comment->all() as $item)
{{ $item }}
@endforeach
</p>
@endif
<form name="anketa" class="addFile" action="{{ route ('ank.diary.add') }}" method="post" enctype="multipart/form-data">
	{{ csrf_field() }}
	<table>
		<tr>
			<td width="50%" align="right">Тема:</td>

			<td width="50%"><input type="text" class="input3" name="title" value="" /></td>
		</tr>
		<tr>
			<td colspan="2">
				<textarea class="textarea2" name="description" wrap="virtual"></textarea>
			</td>
		</tr>
		<tr>

			<td width="50%" align="right">
				<a class="screpka" href="javascript:addfile('file')"></a></td>
			<td width="50%"><input type="hidden" name="otsil" value="1" />
				<input type="hidden" name="add" value="1" />
				<input type="submit" name="send" onclick="find_otsil()" value="Добавить запись" />
			</td>
		</tr>
	</table>
	<div id="file">
		<input type="file" class="login" size="45" name="photo_link" />
	</div>
</form>
@overwrite