@extends('layouts.app')
@section('title', $title)
@section('main_body')
<h1 class="mTit">Подтверждение Е-майл</h1>
@if ($confirmed)
<p class="mess"><strong>Ваш e-mail успешно подтвержден!</strong></p>
@else
<p class="error"><strong>Неверный код подтверждения!</strong></p>
@endif
@overwrite