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

		foreach ($items as &$_item)
		{
			$_item->user_age 		= Helper::age($_item->user_birth_date);
			$_item->user_age_type 	= Helper::ageType($_item->user_age);
			$_item->photo = $_item->photo[0];

			$findSexOrient = '';

			switch ($_item->user_sex_orient) 
			{
				case GOMOSEXUAL:
					$findSexOrient .= $_item->user_sex == MEN 	? 'парня' 				: 'девушку';
					break;
				case BISEXUAL:
					$findSexOrient .= $_item->user_sex == MEN 	? 'девушку или парня' 	: 'парня или девушку';
					break;
				default:
					$findSexOrient .= $_item->user_sex == MEN 	? 'девушку' 			: 'парня';
					break;
			}

			if ($_item->user_partner_age_min > PARTNER_AGE_MIN && $_item->user_partner_age_max > PARTNER_AGE_MAX)
			{
				$findSexOrient .= ' ' . $_item->user_partner_age_min . '-' . $_item->user_partner_age_max;
				$findSexOrient .= ' ' . Helper::ageType($_item->user_partner_age_max);
			} else if ($_item->user_partner_age_min > PARTNER_AGE_MIN && $_item->user_partner_age_max <= PARTNER_AGE_MAX)
			{
				$findSexOrient .= ' от ' . $_item->user_partner_age_min;
				$findSexOrient .= ' ' . Helper::ageType2($_item->user_partner_age_min);
			} else if ($_item->user_partner_age_min <= PARTNER_AGE_MIN && $_item->user_partner_age_max > PARTNER_AGE_MAX)
			{
				$findSexOrient .= ' до ' . $_item->user_partner_age_max;
				$findSexOrient .= ' ' . Helper::ageType2($_item->user_partner_age_max);
			}
			$_item->find_sex_orient = $findSexOrient;

		}

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

		foreach ($items as &$_item)
		{
			$_item->user_age 		= Helper::age($_item->user_birth_date);
			$_item->user_age_type 	= Helper::ageType($_item->user_age);
			if (count ($_item->photo) > 0)
				$_item->photo 		= $_item->photo[0];
		}

		return $items;
	}
}