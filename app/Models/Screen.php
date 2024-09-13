<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Services\FileService;

class Screen extends Model
{
	use HasFactory;
	protected $table 	= 'screensavers';
	public $timestamps 	= false;

	public function getSizeScrFormatAttribute ()
	{
		return FileService::formatFileSize($this->size_scr);
	}

	public function getSizeRarFormatAttribute ()
	{
		return FileService::formatFileSize($this->size_rar);
	}
}