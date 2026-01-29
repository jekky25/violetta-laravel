<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Route as BaseRoute;

class Route
{

	/**
	 * Get all routes for JavaScript
	 * @return string
	 */
	public static function routes()
	{
		$routeCollection = BaseRoute::getRoutes();
		$routes = [];
		foreach ($routeCollection->getRoutes() as $value) {
			$action = $value->action;
			if (!isset($action['as'])) continue;
			$routes[$action['as']] = $value->uri();
		}
		return base64_encode(collect($routes)->toJson());
	}
}
