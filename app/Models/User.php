<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use App\Helpers\Helper;
use App\Models\Photo;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $table 		= 'users_news';
	protected $primaryKey 	= 'user_id';

    public function newFaces($count)
    {
		$items = self::select(['user_id', 'user_active', 'user_name', 'user_sex', 'user_birth_date', 'user_make_date_t', 'user_city', 'user_fotos', 'user_sex_orient', 'user_partner_age_min', 'user_partner_age_max'])
		->where('user_fotos', '>', 0)
		->where('user_confirm_email', 1)
		->where('user_active', 1)
		->whereHas('photo', function ($query) {
			$query->where('fotos_portret', 1);
		})
		->with('city') 
		->with('photo')
		->limit ($count)
        ->orderBy('user_make_date_t', 'desc')
        ->get();

		$items = self::addProps($items);

		return $items;
    }

	public function getTop100($sex, $count)
    {
		$items = self::select(['user_id', 'user_reiting', 'user_name', 'user_birth_date', 'user_city'])
		->where('user_sex', $sex)
		->where('user_active', 1)
		->where('user_fotos', '>', 0)
		->where('user_confirm_email', 1)
		->with('city') 
		->with('photo')
		->limit ($count)
        ->orderBy('user_top100', 'desc')
        ->get();
		
		foreach ($items as &$_item)
		{
			$_item->user_age 		= Helper::age($_item->user_birth_date);
			$_item->user_age_type 	= Helper::ageType($_item->user_age);
			$_item->photo = $_item->photo[0];
		}
		$items = count ($items) > 1 ? $items : $items[0];

		return ($items);
	}


	public function getCountAnkets ($sex)
	{
		$count = self::select('user_id')
		->where('user_sex', $sex)
		->where('user_active', 1)
        ->count();
		return $count > 0 ? $count : 0;
	}

	public function getBirthday($count = 0)
	{
		$tDay 	= \Carbon\Carbon::now()->format('d');
		$tMonth = \Carbon\Carbon::now()->format('m');
		$tDate	= '____-'. $tMonth . '-' .$tDay;

		$items = self::select(['user_id', 'user_active', 'user_name', 'user_sex', 'user_birth_date', 'user_make_date_t', 'user_city', 'user_fotos', 'user_sex_orient', 'user_partner_age_min', 'user_partner_age_max'])
		->where('user_active', 1)
		->where('user_birth_date', 'LIKE', $tDate)
		->with('city')
		->with('photo')
		->paginate($count);
		
		$items = self::addProps($items);

		return $items;
	}

	public function getBest($count = 0, $sex)
	{

		$items = self::select(['*'])
		->where('user_active', 1)
		->where('user_sex', $sex)
		->where('user_fotos', '>', 0)
		->where('user_confirm_email', 1)
		->where('user_top100', '>', 0)
		->with('city')
		->with('photo')
		->orderBy('user_top100', 'desc')
		->paginate($count);
		$items = self::addProps($items);

		return $items;
	}

	public function getMaxReiting($sex)
	{
		$item = self::select(['*'])
		->where('user_active', 1)
		->where('user_sex', $sex)
		->max('user_reiting');
		return $item;
	}

	public function getPopul($count = 0, $sex)
	{
		$items = self::select(['user_id', 'user_active', 'user_name', 'user_sex', 'user_birth_date', 'user_make_date_t', 'user_city', 'user_fotos', 'user_sex_orient', 'user_partner_age_min', 'user_partner_age_max'])
		->where('user_sex', $sex)
		->with('city')
		->with('photo')
		->with('anketVisit')
		->whereExists(function ($query) {
			$query->select(DB::raw(1))
				  ->from('anket_visit')
				  ->whereRaw('users_news.user_id = anket_visit.user_id_prosm');
		})
		->orderBy('user_id', 'desc')
		->paginate($count);

		$items = self::addProps($items);

		return $items;
	}

	public function addProps($items)
	{
		foreach ($items as &$_item)
		{
			$_item->user_age 		= Helper::age($_item->user_birth_date);
			$_item->user_age_type 	= Helper::ageType($_item->user_age);
			if (count ($_item->photo) > 0)
				$_item->photo 		= $_item->photo[0];

			$findSOrient = '';
			if ($_item->user_sex_orient == GOMOSEXUAL) 
				$findSOrient .= $_item->user_sex == MEN ? 'парня' : 'девушку';
			elseif ($_item->user_sex_orient == BISEXUAL) 
				$findSOrient .= $_item->user_sex == MEN ? 'девушку или парня' : 'парня или девушку';
			else
				$findSOrient .= $_item->user_sex == MEN ? 'девушку' : 'парня';
		
			if ($_item->user_partner_age_min > PARTNER_AGE_MIN && $_item->user_partner_age_max > PARTNER_AGE_MAX) 
			{
				$findSOrient .= ' ' . $_item->user_partner_age_min . '-' . $_item->user_partner_age_max;
				$findSOrient .= ' ' . Helper::ageType($_item->user_partner_age_max);
			} elseif ($_item->user_partner_age_min > PARTNER_AGE_MIN && $_item->user_partner_age_max <= PARTNER_AGE_MAX) 
			{
				$findSOrient .= ' от ' . $_item->user_partner_age_min;
				$findSOrient .= ' ' . Helper::ageType2($_item->user_partner_age_min);
			} elseif ($_item->user_partner_age_min <= PARTNER_AGE_MIN && $_item->user_partner_age_max > PARTNER_AGE_MAX) 
			{
				$findSOrient .= ' до ' . $_item->user_partner_age_max;
				$findSOrient .= ' ' . Helper::ageType2($_item->user_partner_age_max);
			}
			$_item->find_sex_orient = $findSOrient;	
		}

		return $items;
	}

	public function getOp($count = 0, $sex, $op)
	{
		$items = self::select(['user_id', 'user_active', 'user_name', 'user_sex', 'user_birth_date', 'user_make_date_t', 'user_city', 'user_fotos', 'user_sex_orient', 'user_partner_age_min', 'user_partner_age_max'])
		->where('user_sex', $sex)
		->with('city')
		->with('photo');
		if (!empty ($op['birthDate']))
			$items = $items->where('user_birth_date', '>', $op['birthDate']);
		if (!empty ($op['birthDate2']))
			$items = $items->where('user_birth_date', '<', $op['birthDate2']);
		$items = $items->orderBy('user_id', 'desc')->paginate($count);

		$items = self::addProps($items);

		return $items;
	}

	public function getById($id)
	{
		$user = Auth::user()->load(['visits']);

		$item = self::select('*')
		->where ('user_id', $id)
		->where ('user_active', 1);
		if (empty ($user))
		{
			$item->where ('user_confirm_email', 1);
		}
		$item->with('diary')
			 ->with('photo')
			 ->with('city')
			 ->with('country');
		$item = $item->first();

		if (empty ($item)) abort (404);

		$item->zodiac = Helper::zodiac($item->user_birth_date);

		$item->user_age 			= Helper::age($item->user_birth_date);
		$item->user_age_type 		= Helper::ageType($item->user_age);
		$item->user_age_str			= $item->user_age .' ' . $item->user_age_type;
		$item->number_diary 		= count($item->diary);
		$item->number_diary_str 	= $item->number_diary . ' ' . Helper::caseDiaryType ($item->number_diary);

		$maxReit 			= User::getMaxReiting($item->user_sex);

		$reiting = round(($item->user_reiting / $maxReit ) * 1000);
		$reitingStr = $reiting / 100;
		if ($reitingStr > 7) 
			$item->user_reiting_str = 70;
		elseif ($reitingStr > 5) 
			$item->user_reiting_str = 56;
		elseif ($reitingStr > 3) 
			$item->user_reiting_str = 42;
		elseif ($reitingStr > 2) 
			$item->user_reiting_str = 28;
		elseif ($reitingStr > 1) 
			$item->user_reiting_str = 14;
		else 
			$item->user_reiting_str = 7;

		
		$item->user_description 			= stripslashes($item->user_description);
		$item->user_description 			= str_replace("\n", "\n<br />\n", $item->user_description);
		$item->user_sex_str 				= $item->user_sex == MEN ? 'Мужской' : 'Женский';
		$item->date_make 					=  Helper::dateFormat($item->user_make_date);

		if ($item->user_make_date !== $item->user_refresh_date) 
			$item->date_refresh 			= Helper::dateFormat($item->user_refresh_date);
		
		$item->user_partner_description 	= stripslashes(trim($item->user_partner_description));
		$item->user_partner_description 	= str_replace("\n", "\n<br />\n", $item->user_partner_description);

		return $item;
	}

	public function country()
	{
    	return $this->hasOne(Country::class, 'id', 'user_country');
	}

	public function city()
	{
    	return $this->hasOne(City::class, 'id', 'user_city');
	}

	public function photo()
	{
    	return $this->hasMany(Photo::class, 'user_id', 'user_id');
	}

	public function visits()
	{
		$t = time() - 60*60*24*30;
    	return $this->hasMany(AnketVisit::class, 'user_id_prosm', 'user_id')->where('ank_time', '>', $t);
	}

	public function diary()
	{
    	return $this->hasMany(Diary::class, 'dnevniki_user_id', 'user_id')->orderBy('dnevniki_time', 'desc');
	}

	public function anketVisit()
	{
    	return $this->hasOne(AnketVisit::class, 'user_id_prosm', 'user_id');
	}
}