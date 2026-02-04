<title>@yield('title')</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<meta name='yandex-verification' content='4b22b31a32d44adc' />
<meta name="csrf-token" content="{{ csrf_token() }}">
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="viewport" content="user-scalable=no, initial-scale=1.0, maximum-scale=1.0, width=device-width">
@vite(['resources/js/app.js'])
{!! $pageMeta !!}
@stack('scripts')
<script>
	window._asset = '{{ asset('') }}';
	window._routes = '{!! \App\Helpers\Route::routes()!!}';
</script>