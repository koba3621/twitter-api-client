<?php
namespace Koba\Twitter;

use Koba\Tool\Config;

class TwitterApiByBearerToken
{
	// Bearer Tokenを利用したTwitter APIはApplication認証なので、GETしか実行できません。
	public function getTwitterApi(string $request_url, array $params): array
	{
		if ($params !== array()) {
				$request_url .= '?' . http_build_query($params);
		}
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $request_url);
		curl_setopt($curl, CURLOPT_HEADER, true);
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET');
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true) ;
		curl_setopt($curl, CURLOPT_HTTPHEADER, array('Authorization: Bearer ' . Config::getConfig('TWITTER_BEARER_TOKEN')));
		curl_setopt($curl, CURLOPT_TIMEOUT, 15);

		$response_1 = curl_exec($curl);
		$response_2 = curl_getinfo($curl);

		curl_close($curl);

		$response_body = substr($response_1, $response_2['header_size']);
		//$response_header = substr($response_1, 0, $response_2['header_size']);

		$response_array = json_decode($response_body, true);

		return $response_array;
	}
}