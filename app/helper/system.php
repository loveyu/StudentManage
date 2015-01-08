<?php
/**
 * User: loveyu
 * Date: 2015/1/8
 * Time: 16:53
 */

/**
 * 生成随机字符
 * @param int $len
 * @return string
 */
function salt($len = 40){
	$output = '';
	for($a = 0; $a < $len; $a++){
		$output .= chr(mt_rand(33, 126)); //生成php随机数
	}
	return $output;
}

function md5_xx($str){
	return md5($str."生活如此多娇");
}

/**
 * 通过加盐生成hash值
 * @param $hash
 * @param $salt
 * @return string
 */
function salt_hash($hash, $salt){
	$count = count($salt);
	return _hash(substr($salt, 0, $count / 2) . $hash . $salt);
}

/**
 * 单独封装hash函数
 * @param      $str
 * @param bool $raw_output 为true时返回二进制数据
 * @return string
 */
function _hash($str, $raw_output = false){
	return hash("sha256", $str, $raw_output);
}