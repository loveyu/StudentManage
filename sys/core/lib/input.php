<?php
/**
 * Created by Loveyu.
 * User: loveyu
 * Date: 14-3-13
 * Time: 下午7:45
 * Filename: input.php
 */

namespace CLib;

c_lib()->load('ip');

/**
 * Class Input
 * @package CLib
 */
class Input{
	/**
	 * IP类
	 * @var Ip
	 */
	private $ip;

	/**
	 * 构造器
	 */
	function __construct(){
		$this->ip = Ip::getInstance();
	}

	/**
	 * 获取IP类
	 * @return Ip
	 */
	public function getIp(){
		return $this->ip;
	}

	/**
	 * 获取真实IP地址
	 * @return string
	 */
	public function  getRealIP(){
		return $this->ip->realip();
	}

	/**
	 * 获取浏览器UA
	 * @return string
	 */
	public function getUA(){
		static $ua = NULL;
		if($ua !== NULL){
			return $ua;
		}
		$ua = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';
		$ua = hook()->apply("Input_getUA", $ua);
		return $ua;
	}

	public function time($format = "Y-m-d H:i:s"){
		if(isset($_SERVER['REQUEST_TIME'])){
			return date($format, $_SERVER['REQUEST_TIME']);
		} else{
			return date($format);
		}
	}
} 