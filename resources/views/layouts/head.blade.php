<title>@yield('title')</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<meta name='yandex-verification' content='4b22b31a32d44adc' />
@vite(['resources/js/app.js'])
{!! $pageMeta !!}
@stack('scripts')
<script>
	window._asset = '{{ asset('') }}';
	window._routes = '{!! \App\Helpers\Route::routes()!!}';
</script>