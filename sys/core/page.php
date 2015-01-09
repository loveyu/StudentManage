<?php
namespace Core;
if(!defined('_CorePath_')){
	exit;
}

/**
 * 页面基础类
 */
class Page{
	/**
	 * @var Core
	 */
	protected $__core;
	/**
	 * @var Lib
	 */
	protected $__lib;

	/**
	 * @var Uri
	 */
	protected $__uri;

	/**
	 * @var Request
	 */
	protected $__req;

	/* 默认构造器 */
	function __construct(){
		$this->__core = Core::getInstance();
		$this->__lib = $this->__core->getLib();
		$this->__uri = $this->__core->getUri();
		$this->__req = $this->__core->getRequest();
	}

	/**
	 * 加载页面
	 * @return bool
	 */
	protected function __load(){
		return call_user_func_array(array(
			$this->__uri,
			'load'
		), func_get_args());
	}

	/**
	 * 加载404页面
	 */
	protected function __load_404(){
		$this->__uri->load_404();
	}

	/**
	 * 加载类库
	 * @return \Core\Lib
	 */
	protected function __lib(){
		return call_user_func_array(array(
			$this->__lib,
			'load'
		), func_get_args());
	}

	/**
	 * 加载视图
	 * @param string $file  文件名
	 * @param array  $param 参数列表
	 */
	protected function __view($file, $param = NULL){
		if(is_array($file)){
			foreach($file as $v){
				if(is_file(_ViewPath_ . "/$v")){
					$this->__view_f($v, $param);
				} else{
					trigger_error(_("Can't load view file:") . $file, E_USER_WARNING);
					//Log::write(_("Can't load view file:") . $file, Log::NOTICE);
				}
			}
		} else{
			if(is_file(_ViewPath_ . "/$file")){
				$this->__view_f($file, $param);
			} else{
				trigger_error(_("Can't load view file:") . $file, E_USER_WARNING);
				//Log::write(_("Can't load view file:") . $file, Log::NOTICE);
			}
		}
	}

	/**
	 * 包含存在的视图文件
	 * @param $file
	 * @param $param
	 */
	private function __view_f($file, $param){
		if(is_array($param)){
			//自动将解析并添加前缀
			extract($param, EXTR_PREFIX_ALL, "_");//短下划线不是合法的变量名，会自动添加一个下划线
		}
		unset($param);
		include(_ViewPath_ . "/$file");
	}

	/**
	 * 获取调用该方法的类名，判断唯一性
	 * @return string
	 */
	public static function __class_name(){
		return get_called_class();
	}

	/**
	 * 返回禁止使用的方法名称
	 * @return array
	 */
	public static function __un_register(){
		return get_class_methods("Core\\Page");
	}
}

?>