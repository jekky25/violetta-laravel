<!DOCTYPE html>
<html lang="ru">
<head>
	<title>@yield('title')</title>
	<base href="{{route('home')}}" />
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<link href="{{ asset("favicon.ico") }}" rel="shortcut icon" type="image/x-icon" />
	<link rel="stylesheet" href="{{ asset("css/jcomments/style.css?v=12") }}" type="text/css" />
	<script type="text/javascript" src="{{ asset("js/system/mootools.js") }}"></script>
	<script type="text/javascript" src="{{ asset("js/system/caption.js") }}"></script>
	<script type="text/javascript" src="{{ asset("js/jcomments/jcomments-v2.1.js?t=5") }}"></script>
	<script type="text/javascript" src="{{ asset("js/jcomments/ajax.js?v=1") }}"></script>
	@stack('scripts')
	<link rel="stylesheet" href="{{ asset("css/style.css?t=4") }}" type="text/css" />
</head>
<body>
wrewrrwrrer
</body>
</html>