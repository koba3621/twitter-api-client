<?php
namespace Koba\Tool;

use Koba\Tool\OperateString;

class Config
{
	public static function getConfig(string $search_string)
	{
		if (file_exists(__DIR__ . '/../../.env')) {
			$configs = file(__DIR__ . '/../../.env');
			foreach ($configs as $config) {
				// 改行を削除
				$config = OperateString::deleteLastString($config);
				$settings = explode('=', $config);
				$config_name = $settings[0];
				$config_value = $settings[1];

				if ($search_string == $config_name) {
					return $config_value;
				}
			}
		}
		return false;
	}
}