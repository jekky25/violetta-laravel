<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;
use Config;

class DontEndSlash
{
  /**
  * Handle an incoming request.
  *
  * @param  \Illuminate\Http\Request  $request
  * @param  \Closure  $next
  * @return mixed
  */
  public function handle($request, Closure $next)
  {
		//do it for phpUnit tests
		$requestUri = $this->getUri($request);
		$trailingSlash 	= (Str::contains($requestUri, ['#', '.html', '/?']) ? '' : '/');

		//check url for the presence of a trailing slash
		if (!empty($trailingSlash) && !preg_match('/.+\/$/', $requestUri))
		{
			$base_url = Config::get('app.url');
			return Redirect::to($base_url.$request->getRequestUri().$trailingSlash);
		}
		return $next($request);
    }

	/**
	* get uri by request
	*
	* @param  \Illuminate\Http\Request  $request
	* @return string
	*/
	public function getUri($request)
	{
		$uri = $request->getRequestUri();
		return $uri != $_SERVER['REQUEST_URI'] && !empty($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : $uri;
	}
}

