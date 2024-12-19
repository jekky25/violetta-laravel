<?php
class Curl
{
	public $params;

	/**
	* Send CURL request to server
	* @param  Illuminate\Contracts\Validation\ValidationRule $params
	*/
	public function submit($params) :string
	{
			$ch = curl_init();
			curl_setopt_array($ch, [
			CURLOPT_URL => $params->getUrl(),
			CURLOPT_POST => true,
			CURLOPT_POSTFIELDS => $params->prepareParams(),
			CURLOPT_RETURNTRANSFER => true
		]);
		$output = curl_exec($ch);
		curl_close($ch);
		return $output;
	}
}