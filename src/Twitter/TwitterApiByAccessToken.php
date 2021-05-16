<?php
namespace Koba\Twitter;

use Koba\Tool\Config;

class TwitterApiByAccessToken
{
	public function executeTwitterApi(string $request_url, string $request_method, array $params = array())
	{
		$signature_key = $this->createSignatureKey();
		$parameter_for_request = $this->getParameterForRequest($params);
		$signature_data = $this->createSignatureData($parameter_for_request, $request_method, $request_url);
		$signature = $this->createSignature($signature_data, $signature_key);

		$parameter_for_request['oauth_signature'] = $signature;

		$header = array('Authorization: OAuth ' . http_build_query($parameter_for_request, '', ','));

		if ($params !== array()) {
			switch ($request_method) {
				case 'GET':
					$request_url .= '?' . http_build_query($params);
					break;
				case 'POST':
					$post_body = http_build_query($params);
					break;
			}
		}

		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $request_url);
		curl_setopt($curl, CURLOPT_HEADER, true);
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $request_method);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true) ;
		curl_setopt($curl, CURLOPT_HTTPHEADER, $header);

		if ($request_method === 'POST' && isset($post_body)) {
			curl_setopt($curl, CURLOPT_POSTFIELDS, $post_body);
		}
		curl_setopt($curl, CURLOPT_TIMEOUT, 15); 

		$response_1 = curl_exec($curl);
		$response_2 = curl_getinfo($curl);
		curl_close($curl);

		$response_json = substr($response_1, $response_2['header_size']);
		//$response_header = substr($response_1, 0, $response_2['header_size']);

		$response = json_decode($response_json, true);

		return $response;
	}

	public function createSignatureKey()
	{
			return rawurlencode(Config::getConfig('TWITTER_API_SECRET_KEY')) . '&' . rawurlencode(Config::getConfig('TWITTER_ACCESS_TOKEN_SECRET'));
	}

	public function createSignature($signature_data, $signature_key)
	{
			return base64_encode(hash_hmac('sha1', $signature_data, $signature_key, true));
	}

	public function getParameterForRequest($request_option_params)
	{
			$parameter_for_signature = array(
					'oauth_token' => Config::getConfig('TWITTER_ACCESS_TOKEN'),
					'oauth_consumer_key' => Config::getConfig('TWITTER_API_KEY'),
					'oauth_signature_method' => 'HMAC-SHA1',
					'oauth_timestamp' => time(),
					'oauth_nonce' => microtime(),
					'oauth_version' => '1.0',
			);
			$parameter_for_request = array_merge($request_option_params, $parameter_for_signature);
			ksort($parameter_for_request);
			return $parameter_for_request;
	}

	public function createSignatureData($parameter_for_request, $request_method, $request_url)
	{
			$request_params = http_build_query($parameter_for_request, '', '&');
			$request_params = str_replace(array('+', '%7E'), array('%20', '~'), $request_params);
			$request_params = rawurlencode($request_params);

			$encoded_request_method = rawurlencode($request_method);
			$encoded_request_url = rawurlencode($request_url);

			return $encoded_request_method . '&' . $encoded_request_url . '&' . $request_params;
	}

}