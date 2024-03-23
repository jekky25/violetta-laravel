<!DOCTYPE html>
<html lang="ru">
<head>
<title>@yield('title')</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<meta name='yandex-verification' content='4b22b31a32d44adc' />
<link rel="Stylesheet"  href="{{ asset('css/reset.css?1') }}" type="text/css" />
<link rel="Stylesheet"  href="{{ asset('css/style.css?19') }}" type="text/css" />
<script type="text/javascript" src="{{ asset('js/jquery-1.9.0.min.js?1') }}"></script>
<script async type="text/javascript" src="{{ asset('js/frame_script.js?2') }}"></script>
{!! $pageMeta !!}
@stack('scripts')
</head>
<body>
	@yield('main_body')
</body>
</html>