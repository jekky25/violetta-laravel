<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\DB;

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

	public function getOp($count = 0, $sex)
	{
		$items = self::select(['user_id', 'user_active', 'user_name', 'user_sex', 'user_birth_date', 'user_make_date_t', 'user_city', 'user_fotos', 'user_sex_orient', 'user_partner_age_min', 'user_partner_age_max'])
		->where('user_sex', $sex)
		->with('city')
		->with('photo')
		->orderBy('user_id', 'desc')
		->paginate($count);

		$items = self::addProps($items);

		return $items;
	}

	public function anketVisit()
	{
    	return $this->hasOne(AnketVisit::class, 'user_id_prosm', 'user_id');
	}
}