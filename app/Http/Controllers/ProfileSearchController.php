<?php

namespace App\Http\Controllers;

use App\Requests\SearchRequest;
use App\Filters\UserFilter;
use App\Fields\SearchField;
use App\Services\ProfileSearchService;
use Illuminate\View\View;

class ProfileSearchController extends Controller
{
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct(
		protected ProfileSearchService $service
	) {}

	/**
	 * show the page with profiles by filter
	 * @param  SearchRequest  $request
	 * @param  UserFilter  $filter
	 * @param  SearchField $fields
	 * @return View
	 */
	public function search(SearchRequest $request, UserFilter $filter, SearchField $fields): View
	{
		$data = $this->service->search($request->validated(), $filter, $fields);
		$data['isSearch'] = collect(request()->query())
    		->except(['page'])
		    ->isNotEmpty();
		return view('ankets.search', $data);
	}
}