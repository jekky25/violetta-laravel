<?php

namespace App\Repositories;

use App\Interfaces\SmileInterface;
use App\Models\Smile;

class SmileRepository implements SmileInterface {

	/**
	* get smiles
	* @return \Illuminate\Database\Eloquent\Collection
	*/
	public function getAll()
	{
		$items = Smile::select('*')
		->orderBy('sort', 'asc')
		->orderBy('id', 'asc')
		->get();
		return $items;
	}

	/**
	 * translate smiles from codes to html tages
	 * 
	 * @param string $str
	 *
	 * @return string
	 */
	public function transformSmiles($str)
	{
		$smiles = $this->getAll();
		if (empty($smiles)) return $str;
		foreach ($smiles as $_smile) {
			$str = str_replace($_smile->code, '<img class="messBSmile" src="' . asset('image/smiles/' . $_smile->img) . '" alt="" />', $str);
		}
		return $str;
	}
}