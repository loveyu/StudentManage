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
	return md5($str . "生活如此多娇");
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


/**
 * 返回登陆类
 * @return \ULib\Login
 */
function login_class(){
	static $login = NULL;
	if($login !== NULL){
		return $login;
	}
	$lib = lib();
	$login = $lib->using('ULogin');
	if($login === false){
		$lib->load('Login');
		$login = new \ULib\Login();
		$lib->add("ULogin", $login);
	}
	return $login;
}

/**
 * 获取数据库类
 * @return \ULib\DB
 */
function db_class(){
	static $db = NULL;
	if($db !== NULL){
		return $db;
	}
	$lib = lib();
	$db = $lib->using('UDB');
	if($db === false){
		$lib->load('DB');
		$db = new \ULib\DB();
		$lib->add("UDB", $db);
	}
	return $db;
}

/**
 * 获取权限操作
 * @return \ULib\Access
 */
function access_class(){
	static $access = NULL;
	if($access !== NULL){
		return $access;
	}
	$lib = lib();
	$access = $lib->using('UAccess');
	if($access === false){
		$lib->load('Access');
		$access = new \ULib\Access();
		$lib->add("UAccess", $access);
	}
	return $access;
}

/**
 * 获取SESSION对象实例
 * @return \CLib\Session
 */
function session_class(){
	static $session = NULL;
	if($session !== NULL){
		return $session;
	}
	$lib = c_lib();
	$session = $lib->using('CSession');
	if($session === false){
		$lib->load('session')->add("CSession", new \CLib\Session());
		$session = $lib->using("CSession");
	}
	return $session;
}

/**
 * 获取QueryList对象实例
 * @return \ULib\QueryList
 */
function query_class(){
	static $query = NULL;
	if($query !== NULL){
		return $query;
	}
	$lib = lib();
	$query = $lib->using('UQueryList');
	if($query === false){
		$lib->load('QueryList')->add("UQueryList", new \ULib\QueryList());
		$query = $lib->using("UQueryList");
	}
	return $query;
}
