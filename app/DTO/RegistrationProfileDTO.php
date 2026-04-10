<?

namespace App\DTO;

class RegistrationProfileDTO
{
	public function __construct(
		public readonly string $login,
		public readonly string $password,
		public readonly string $name,
		public readonly int $sex,
		public readonly string $email,
		public readonly int $country_id,
		public readonly int $region_id,
		public readonly int $city_id,
		public readonly string $recaptcha_response,
		public readonly string $conditions,
		public readonly string $birth_date

	) {}

	public static function fromRequest($request): self
	{
		return new self(
			login: $request->login,
			password: $request->password,
			name: $request->name,
			sex: $request->sex,
			email: $request->email,
			country_id: $request->country_id,
			region_id: $request->region_id,
			city_id: $request->city_id,
			recaptcha_response: $request->recaptcha_response,
			conditions: $request->conditions,
			birth_date: $request->birth_date
		);
	}

	public function toArray(): array
    {
        return [
			'login' => $this->login,
			'password' => $this->password,
			'name' => $this->name,
			'sex' => $this->sex,
			'email' => $this->email,
			'country_id' => $this->country_id,
			'region_id' => $this->region_id,
			'city_id' => $this->city_id,
			'recaptcha_response' => $this->recaptcha_response,
			'conditions' => $this->conditions,
			'birth_date' => $this->birth_date
        ];
    }
}