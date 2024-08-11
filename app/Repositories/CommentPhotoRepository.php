<?php

namespace App\Repositories;

use App\Interfaces\CommentPhotoInterface;
use App\Models\CommentPhoto;

class CommentPhotoRepository implements CommentPhotoInterface {
	/**
	* create a comment for the photo
	* @param  array $request
	* @return void
	*/	
	public function create($request) {
		try {
			CommentPhoto::create($request);
		} catch (\Exception $e) {
			throw new \Exception('Failed to create a Photo Comment '.$e->getMessage());
		}
	}
}