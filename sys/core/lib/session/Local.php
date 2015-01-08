<?php
/**
 * Created by PhpStorm.
 * User: hzy
 * Date: 14-2-8
 * Time: 下午10:07
 */

namespace CLib\Session;

c_lib()->load('session');

use CLib\SessionInterface;

/**
 * 使用自带的本地存储Session
 * Class Local
 * @package CLib\Session
 */
class Local implements SessionInterface
{

	/**
	 * 启动Session
	 */
	function __construct() {
		session_start();
	}

	/**
	 * GET操作
	 * @param $name string 数组键名
	 * @return mixed
	 */
	public function get($name) {
		if(isset($_SESSION[$name])) {
			return $_SESSION[$name];
		} else {
			if($name === null) {
				return $_SESSION;
			}
			return null;
		}
	}

	/**
	 * 设置操作
	 * @param $name string 数组键名
	 * @param $value string 对应的值
	 * @return bool
	 */
	public function set($name, $value) {
		$_SESSION[$name] = $value;
		return true;
	}

	/**
	 * 删除操作
	 * @param $name string 数组键名
	 * @return bool
	 */
	public function delete($name) {
		if(isset($_SESSION[$name])) {
			unset($_SESSION[$name]);
		}
		return isset($_SESSION[$name]);
	}

	/**
	 * 彻底删除SESSION
	 * @return void
	 */
	public function destroy() {
		session_destroy();
	}

}