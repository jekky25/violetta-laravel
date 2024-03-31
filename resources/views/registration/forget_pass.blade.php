@extends('layouts.app')
@section('title', $title)
@section('main_body')
<h1 class="mTit">Забыли пароль?</h1>
<table id="mScreen">
	<tr>
		<td class="wth4 left1">
		@if(session('success'))
		{!! session('success') !!}
		@else
			<p>Вы <strong>не помните свой пароль</strong>? Введите пожалуйста Е-майл, который вы оставили при регистрации. Если он в нашей базе, мы пришлем вам письмо с вашими данными для входа.</p>
			@if (!empty ($errors->comment->all()))
				<p class="pad3 error left1">Ошибка: 
				@foreach ($errors->comment->all() as $item)
				<span>{{ $item }}</span>
				@endforeach
				</p>
				@endif
			<form name="anketa" action="{{ route('forget_pass.post') }}" method="post">
			{{ csrf_field() }}
				<table>
					<tr>
						<td class="valign3">
							<input class="input3" type="text" name="mail" value="{{ old('mail') }}" />
							<input type="hidden" name="otsil" value="1" />
						</td>
						<td>	
							<input type="submit" name="sent" class="bgBut4" value="" />
						</td>
					</tr>		
				</table>
			</form>
		@endif
		</td>
		<td>
<script type="text/javascript"><!--
google_ad_client = "ca-pub-6379140164632940";
/* 160x600 добавлен на списки анкет */
google_ad_slot = "3632942762";
google_ad_width = 160;
google_ad_height = 600;
//-->
</script>
<script type="text/javascript" async src="http://pagead2.googlesyndication.com/pagead/show_ads.js"></script>
		</td>
	</tr>
</table>
@overwrite