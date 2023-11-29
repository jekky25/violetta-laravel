<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Diary extends Model
{
	use HasFactory;

	protected $table = 'dnevniki';

	public function get($count)
    {
		$items = self::select('*')
		->whereHas('user', function ($query) {
			$query->where('user_active', 1);
		})
		->with('user')
		->with('comments')
		->limit ($count)
        ->orderBy('dnevniki_time', 'desc')
        ->get();

		Diary::changeParams($items);

		return $items;
	}

	public function getAll($count)
    {
		$items = self::select('*')
		->whereHas('user', function ($query) {
			$query->where('user_active', 1);
		})
		->with('user')
		->with('comments')
        ->orderBy('dnevniki_time', 'desc')
        ->paginate($count);

		Diary::changeParams($items);

		return $items;
	}

	public static function changeParams(&$items)
	{
		foreach ($items as &$_item)
		{
			if (!empty($_item->user) && isset($_item->user->user_sex))
				$_item->user_sex = $_item->user->user_sex;

			$_item->name_class 		= $_item->user_sex == MEN ? 'name_man' : 'name_woman';
			$_item->dnevniki_time 	= date("d.m.y H:i", $_item->dnevniki_time);
			$_item->dnevniki_title 	= stripslashes($_item->dnevniki_title);
			$_item->dnevniki_title 	= $_item->dnevniki_title == '' ? 'Тема без названия' : $_item->dnevniki_title;
			$_item->dnevniki_text 	= stripslashes($_item->dnevniki_text);
			$_item->dnevniki_text 	= str_replace("\n", "\n<br />\n", $_item->dnevniki_text);

			if ($_item->dnevniki_picture !== "0")
			{
				$img = './img/dnevnik/' . $_item->dnevniki_picture . '.jpg';
			
				if (is_file($img))
				{
					$_item->dnevnik_foto 	= $_item->dnevniki_id;
					$_item->diaryImg 		= $img;
				}
			}
		}
	}

	public function user()
    {
        return $this->belongsTo(User::class, 'dnevniki_user_id', 'user_id');
    }

	public function comments ()
	{
		return $this->hasMany(Comment::class, 'comment_dnevnik_id', 'dnevniki_id');
	}
}
