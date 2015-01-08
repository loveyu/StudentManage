<?php
namespace Core;
if(!defined('_CorePath_')){
	exit;
}

/**
 * 请求处理类
 */
class Request{

	/**
	 * 请求类的模式标记
	 * @var int
	 */
	private $_mode;

	private static $_htmlspecialchars_mode = ENT_QUOTES;

	/**
	 * more为0时为明文模式
	 * @param int $mode
	 */
	function __construct($mode = 0){
		$this->_mode = $mode;
	}

	public function set_htmlspecialchars_mode($mode = ENT_QUOTES){
		switch($mode){
			case ENT_COMPAT:
			case ENT_QUOTES:
			case ENT_NOQUOTES:
			case ENT_IGNORE:
			case ENT_SUBSTITUTE:
			case ENT_DISALLOWED:
			case ENT_HTML401:
			case ENT_XML1:
			case ENT_XHTML:
			case ENT_HTML5:
				self::$_htmlspecialchars_mode = $mode;
				return;
		}
		self::$_htmlspecialchars_mode = ENT_QUOTES;
	}

	/**
	 * 转义模式，使用addslashes()效果
	 * @return Request
	 */
	public function _escape(){
		return new Request(1);
	}

	/**
	 * 明文模式，将所有Html标签转义
	 * @return Request
	 */
	public function _plain(){
		return new Request(2);
	}

	/**
	 * 对数据进行转换或明文处理
	 * @param mixed $data 输入默认数据null
	 * @return mixed 根据相应模式进行转换
	 */
	private function data_convert($data = ''){
		if(NULL === $data){
			return NULL;
		}
		if(MAGIC_QUOTES_GPC){
			switch($this->_mode){
				case 0:
					$data = self::stripslashes_deep($data);
					break;
				case 2:
					//先转换为非转义字符，然后进行HTML标签替换
					$data = self::htmlspecialchars_deep(self::stripslashes_deep($data));
					break;
			}
		} else{
			switch($this->_mode){
				case 1:
					$data = self::addslashes_deep($data);
					break;
				case 2:
					$data = self::htmlspecialchars_deep($data);
					break;
			}
		}
		return $data;
	}

	/**
	 * 对数组进行递归反转义操作
	 * @param string|array $value
	 * @return array|string
	 */
	public static function stripslashes_deep($value){
		return is_array($value) ? array_map(array(
			'Core\\Request',
			'stripslashes_deep'
		), $value) : stripslashes($value);
	}

	/**
	 * 对数组进行递归HTML标签转换为HTML符号
	 * @param string|array $value
	 * @return array|string
	 */
	public static function htmlspecialchars_deep($value){
		return is_array($value) ? array_map(array(
			'Core\\Request',
			'htmlspecialchars_deep'
		), $value) : htmlspecialchars($value, self::$_htmlspecialchars_mode);
	}

	/**
	 * 对数组进行递归转义
	 * @param string|array $value
	 * @return array|string
	 */
	public static function addslashes_deep($value){
		return is_array($value) ? array_map(array(
			'Core\\Request',
			'addslashes_deep'
		), $value) : addslashes($value);
	}


	/**
	 * 获取$_GET变量中的内容，参数为连续参数
	 * @return string|array|null
	 */
	public function get(){
		return hook()->apply('Request_get', $this->data_convert(self::_get($_GET, func_get_args())));
	}

	/**
	 * 获取$_POST变量中的内容，参数为连续参数
	 * @return string|array|null
	 */
	public function post(){
		return hook()->apply('Request_post', $this->data_convert(self::_get($_POST, func_get_args())));
	}

	/**
	 * 获取$_COOKIE变量中的内容，参数为连续参数
	 * @return string|array|null
	 */
	public function cookie(){
		$p = func_get_args();
		if(isset($p[0])){
			$p[0] = COOKIE_PREFIX . $p[0];
		}
		return hook()->apply('Request_cookie', $this->data_convert(self::_get($_COOKIE, $p)));
	}

	/**
	 * 获取$_REQUEST变量中的内容，参数为连续参数
	 * @return string|array|null
	 */
	public function req(){
		return hook()->apply('Request_req', $this->data_convert(self::_get($_REQUEST, func_get_args())));
	}

	/**
	 * 获取某一数组中的对应层次的元素
	 * @param array $data 原始数据
	 * @param array $list 键值列表
	 * @return mixed 对应的数据
	 */
	public static function _get($data, $list){
		foreach($list as $v){
			if(isset($data[$v])){
				$data = $data[$v];
			} else{
				return NULL;
			}
		}
		return $data;
	}

	/**
	 * 是否为GET请求方式
	 * @return bool
	 */
	public function is_get(){
		return strtolower($_SERVER['REQUEST_METHOD']) === 'get';
	}

	/**
	 * 是否为POST请求方式
	 * @return bool
	 */
	public function is_post(){
		return strtolower($_SERVER['REQUEST_METHOD']) === 'post';
	}

	function is_ajax(){
		return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
	}

}