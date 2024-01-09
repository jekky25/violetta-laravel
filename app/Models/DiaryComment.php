<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class DiaryComment extends Model
{
	use HasFactory;

	protected $table = 'dnevniki_comments';

	public $timestamps 		= false;
	protected $primaryKey 	= 'comment_id';



	public function diary()
    {
        return $this->belongsTo(Diary::class, 'dnevniki_id', 'comment_dnevnik_id');
    }
}
