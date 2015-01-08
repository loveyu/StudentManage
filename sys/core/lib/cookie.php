<?php
namespace CLib;

/**
 * Cookie操作类
 * Class Cookie
 * @package CLib
 */
class Cookie{
	/**
	 * 设置是否加密COOKIE
	 * @var bool
	 */
	private $encode;

	/**
	 * @var string cookie加密密钥
	 */
	private $key;

	/**
	 * @var Safe
	 */
	private $safe;

	/**
	 * 构造
	 * @param bool   $encode
	 * @param string $key
	 */
	public function __construct($encode = false, $key = ''){
		$this->encode = $encode;
		if($encode){
			c_lib()->load('safe');
			$this->safe = new Safe();
			hook()->add('Request_cookie', array(
				$this,
				'de'
			));
			hook()->add('Cookie_set', array(
				$this,
				'en'
			));
		}
		$this->setKey($key);
	}

	/**
	 * 设置加密密钥
	 * @param $key string
	 */
	public function setKey($key){
		$this->key = _hash($key . @$_SERVER['HTTP_USER_AGENT'] . COOKIE_KEY, true);
	}

	/**
	 * 通过Cookie_Key加密数据或数组
	 * @param $data
	 * @return array|string
	 */
	public function en($data){
		if($data === NULL){
			return $data;
		}
		return is_array($data) ? array_map(array(
			$this,
			'de'
		), $data) : $this->safe->encrypt($data, $this->key);
	}

	/**
	 * 通过Cookie_Key解密数据或数组
	 * @param $data
	 * @return array|string
	 */
	public function de($data){
		if($data === NULL){
			return $data;
		}
		return is_array($data) ? array_map(array(
			$this,
			'de'
		), $data) : $this->safe->decrypt($data, $this->key);
	}

	/**
	 * 设置COOKIE的值
	 * @param        $name
	 * @param        $value
	 * @param int    $expire
	 * @param string $path
	 * @param string $domain
	 * @param bool   $secure
	 * @param bool   $httponly
	 */
	public function set($name, $value, $expire = 0, $path = '', $domain = '', $secure = false, $httponly = true){
		setcookie(COOKIE_PREFIX . $name, hook()->apply('Cookie_set', $value), $expire, $this->path($path), $this->domain($domain), $secure, $httponly);
	}

	/**
	 * 删除COOKIE的值
	 * @param        $name
	 * @param string $path
	 * @param string $domain
	 */
	public function del($name, $path = '', $domain = ''){
		setcookie(COOKIE_PREFIX . $name, "", 0, $this->path($path), $this->domain($domain), '');
	}

	/**
	 * 修正COOKIE设置的路径
	 * @param $path
	 * @return mixed
	 */
	private function path($path){
		if('' == $path){
			$path = URL_PATH;
		}
		return hook()->apply('Cookie_path', $path);
	}

	/**
	 * 修改域名路径
	 * @param $domain
	 * @return string
	 */
	private function domain($domain){
		if('' == $domain){
			$domain = u()->getUriInfo()->getHttpHost();
		}
		return hook()->apply('Cookie_domain', $domain);
	}

}