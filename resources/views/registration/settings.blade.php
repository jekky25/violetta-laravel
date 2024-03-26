@extends('layouts.app')
@section('title', $title)
@section('main_body')
<h1 class="mTit">Редактирование данных</h1>
<ul id="menuReg" class="clear">
	<li class="menuRegAct">Настройки</li>
</ul>
<form name="anketa" action="{{ route ('registration.edit.settings') }}" method="post">
	{{ csrf_field() }}
	@if(session('success'))
		<h4 class="reg_title2">информация сохранена</h4>
	@endif
	<p class="pad2"></p>
	<table class="mrg6" width="100%">
		<tr>
			<td width="50%" align="right">Не получать сообщения с сайта</td>
			<td width="50%"><input type="checkbox" name="dont_send_email" value="1" @if ($user->dont_send_email == 1) checked="checked"@endif /></td>
		</tr>
	</table>	
	<input type="hidden" name="otsil" value="1" />
	<p class="pad2"></p>
	<p class="pad3"><input type="submit" name="sent" class="bgBut8" value="" /></p>
</form>
<p class="pad2"></p>

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
@overwrite