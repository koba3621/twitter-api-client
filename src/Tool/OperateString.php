<?php
namespace Koba\Tool;

class OperateString
{
	public static function deleteLastString(string $string)
	{
		return mb_strcut($string, 0, strlen($string) - 1);
	}
}