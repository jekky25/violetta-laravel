@extends('layouts.app')
@section('title', $title)
@section('main_body')
<h1 class="mTit">Редактирование данных</h1>
<ul id="menuReg" class="clear mb-5">
	<li class="menuRegAct">Дневник</li>
</ul>
<h4 class="reg_title">Редактировать дневник</h4>
<p class="pad3">Если у Вас есть чем поделиться с другими каким-либо событием, произошедшим в Вашей жизни, или просто интересными мыслями. Напишите здесь в <strong>Вашем дневнике</strong>.</p>
@if (!empty($diaries))
<table class="ankDnevnik">
@foreach ($diaries as $item)
<x-blocks-diary :item="$item" />
@endforeach
	</table>
@endif
<x-pagination :items="$diaries" />
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
<p class="pad3 error">Ошибка:
@foreach ($errors->all() as $item)
{{ $item }}
@endforeach
</p>
@endif
<form name="anketa" class="addFile" action="{{ route ('ank.diary.add') }}" method="post" enctype="multipart/form-data">
	{{ csrf_field() }}
	<table class="add-comment-table">
		<tr>
			<td width="50%" align="right">Тема:</td>
			<td width="50%">
				<x-input name="title" value="" />
			</td>
		</tr>
		<tr>
			<td colspan="2">
				<x-textarea name="description" />
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
		<input type="file" class="login" size="45" name="photo" />
	</div>
</form>
@overwrite