<?php
namespace CLib;

class Char
{
	private static $_CharSetList = array(
		'UTF-8',
		'GBK',
		'GB2312',
		'ASCII',
		'UNICODE',
		'BIG5',
		'UCS-2',
		'UCS-2LE',
		'UCS-2BE'
	);

	public static function GetCoding($data) {
		foreach (self::$_CharSetList as $v) {
			if (iconv("UTF-8//IGNORE", $v, iconv($v, "UTF-8//IGNORE", $data)) == $data) {
				return $v;
			}
		}
		return "unknown";
	}

	public static function Convert($in, $out, $data) {
		$in = strtoupper(trim($in));
		$out = strtoupper(trim($out));
		if ($in == 'UNKNOWN' || $out == 'UNKNOWN' || empty($in) || empty($out)) {
			return $data;
		}
		return iconv($in, $out . "//IGNORE", $data);
	}
}