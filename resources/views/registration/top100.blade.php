@extends('layouts.app')
@section('title', $title)
@section('main_body')
<h1 class="mTit">Поднять анкету</h1>
@if (!empty($textTop100))
{!! $textTop100 !!}
@endif
@if (!empty($formTotop))
{!! $formTotop !!}
@endif
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
@overwrite