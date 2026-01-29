<?php

namespace App\Repositories;

use App\Interfaces\ForumInterface;
use Illuminate\Support\Facades\DB;

class ForumRepository implements ForumInterface
{

	/**
	 * get top list
	 * @return \Illuminate\Database\Eloquent\Collection
	 */
	public static function getTop()
	{
		$sql = 'SELECT t.topic_title, t.topic_id, t.forum_id, p.topic_id
		FROM phpbb3_topics t, phpbb3_posts p  
		WHERE t.topic_id = p.topic_id && t.forum_id <> 7 
		GROUP BY t.topic_id, t.topic_title, t.forum_id, p.topic_id ORDER BY p.post_id DESC LIMIT 0 , 5;';

		return DB::connection('mysql_for')->select($sql);
	}
}
