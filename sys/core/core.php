<?php
namespace Core;
if(!defined('_CorePath_'))
	exit;
/**
 * 控制核心
 */
class Core{
	/**
	 * 路由信息
	 * @var Uri
	 */
	private $_uri;

	/**
	 * 钩子类
	 * @var Hook
	 */
	private $_hook;

	/**
	 * 配置类
	 * @var Config
	 */
	private $_config;

	/**
	 * 默认计时器
	 * @var Timer
	 */
	private $_timer;

	/**
	 * 用户类库
	 * @var Lib
	 */
	private $_lib;

	/**
	 * 核心类库
	 * @var Lib
	 */
	private $_core_lib;

	/**
	 * 字符串请求类库
	 * @var Request
	 */

	private $_request;

	/**
	 * 核心实例
	 * @var Core
	 */
	private static $_instance = null;

	/**
	 * 构造函数
	 */
	private function __construct(){
		$this->_timer = new Timer();
		$this->_lib = new Lib(_LibPath_);
		$this->_core_lib = new Lib(_CorePath_ . "/lib");
		$this->_config = new Config();
		$this->_hook = new Hook();
		$this->_uri = new Uri();
		$this->_request = new Request();

		//加载系统帮助类
		require(_CorePath_ . "/helper/system.php");
	}


	/**
	 * 获取核心实例
	 * @return Core
	 */
	public static function &getInstance(){
		if(is_null(self::$_instance)){
			self::$_instance = new Core();
		}
		return self::$_instance;
	}

	/**
	 * 禁止克隆对象
	 */
	private function __clone(){
		$this->error(_("Clone is forbidden."));
	}

	/**
	 * 获取钩子类
	 * @return Hook
	 */
	public function &getHook(){
		return $this->_hook;
	}

	/**
	 * 获取请求类
	 * @return Request
	 */
	public function &getRequest(){
		return $this->_request;
	}

	/**
	 * 获取URI类
	 *
	 * @return Uri
	 */
	public function &getUri(){
		return $this->_uri;
	}


	/**
	 * 获取配置类
	 * @return Config
	 */
	public function &getConfig(){
		return $this->_config;
	}

	/**
	 * 获取LIB类
	 *
	 * @return Lib
	 */
	public function &getLib(){
		return $this->_lib;
	}

	/**
	 * 获取系统计时器类
	 * @return Timer
	 */
	public function &getTimer(){
		return $this->_timer;
	}

	/**
	 * 获取核心LIB类
	 *
	 * @return Lib
	 */
	public function &getCoreLib(){
		return $this->_core_lib;
	}

	/**
	 * 输出错误信息，并结束程序
	 * @param $err
	 */
	private function error($err){
		die($err . E_USER_ERROR);
	}
}

?>