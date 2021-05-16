<?php
require_once('../vendor/autoload.php');

use Koba\Twitter\TwitterApiByBearerToken;
use Koba\Twitter\TwitterApiByAccessToken;

// Bearer Tokenを利用して、公開ユーザの情報取得(以下例ではTweet検索)
$twitter_api_by_bearer_token = new TwitterApiByBearerToken();
$request_url = 'https://api.twitter.com/1.1/search/tweets.json';

$request_param = array(
	'q' => '検索ワード',
	'lang' => 'ja',
	'locale' => 'ja',
	'result_type' => 'recent',
	'count' => 1,
	'include_entities' => false,
);

// 結果は配列形式で得られます。具体的な取得されるレスポンスの内容は以下公式サイトをご確認ください。
// https://developer.twitter.com/en/docs/twitter-api/v1/tweets/search/api-reference/get-search-tweets
$response = $twitter_api_by_bearer_token->getTwitterApi($request_url, $request_param);


// Access Tokenを利用して、Postを行う例 (favoriteする)
// GET系のAPIを実行する場合には、$request_method = 'GET' としてください。
$request_url = 'https://api.twitter.com/1.1/favorites/create.json';
$request_method = 'POST';
$params = array(
	'id' => 'favoriteしたいtweetのid 前出の$responseから取得できます id_strを取得しましょう',
);

$twitter_api_by_access_token = new TwitterApiByAccessToken();

$response = $twitter_api_by_access_token->executeTwitterApi($request_url, $request_method, $params);
