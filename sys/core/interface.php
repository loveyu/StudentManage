<?php
/**
 * 核心接口获取
 */

if(!defined('_CorePath_')){
	exit;
}

/**
 * 获取核心
 * @return Core\Core
 */
function c(){
	return Core\Core::getInstance();
}

/**
 * 获取URI
 * @return Core\Uri
 */
function u(){
	return Core\Core::getInstance()->getUri();
}

/**
 * 获取钩子
 * @return Core\Hook
 */
function hook(){
	return Core\Core::getInstance()->getHook();
}

/**
 * 获取LIB
 * @return Core\Lib
 */
function lib(){
	return Core\Core::getInstance()->getLib();
}

/**
 * 获取系统LIB
 * @return Core\Lib
 */
function c_lib(){
	return Core\Core::getInstance()->getCoreLib();
}

/**
 * 获取系统配置
 * @return Core\Config
 */
function cfg(){
	return Core\Core::getInstance()->getConfig();
}

/**
 * 加载一个助手文件
 * @param string $helper_file 文件名，或者相对助手文件夹路径
 * @return mixed
 */
function l_h($helper_file){
	if(func_num_args() > 1){
		foreach(func_get_args() as $path){
			require(_HelperPath_ . "/" . $path);
		}
		return true;
	}
	return require(_HelperPath_ . "/" . $helper_file);
}

/**
 * 加载一个系统助手文件
 * @param string $helper_file 文件名，或者相对助手文件夹路径
 * @return mixed
 */
function c_h($helper_file){
	return require(_CorePath_ . "/helper/" . $helper_file);
}

/**
 * 获取请求类
 * @return Core\Request
 */
function req(){
	return \Core\Core::getInstance()->getRequest();
}


/**
 * 关于调试信息
 * @param      $str array|string
 * @param bool $out
 * @return mixed
 */
function debug($str, $out = false){
	if(_Debug_){
		if(is_array($str)){
			$str = join(", ", $str);
		}
		if($out){
			echo $str;
		}
		return $str;
	}
	return '';
}


require(_CorePath_ . "/core.php");
require(_CorePath_ . "/timer.php");
require(_CorePath_ . "/hook.php");
require(_CorePath_ . "/config.php");
require(_CorePath_ . "/log.php");
require(_CorePath_ . "/uri.php");
require(_CorePath_ . "/lib.php");
require(_CorePath_ . "/page.php");
require(_CorePath_ . "/request.php");

define('URL_NOW', ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') ? 'https' : 'http') . "://" . @$_SERVER["HTTP_HOST"] . @$_SERVER['REQUEST_URI']);
define('URL_PATH', str_replace("\\", "/", dirname($_SERVER['SCRIPT_NAME'])) . "");
define('NOW_TIME', time());
//if(!_Debug_){
//调整错误记录为全部运行模式，调试输出改在记录函数中输出
set_error_handler('\Core\Log::phpErrorLog');
register_shutdown_function('\Core\Log::phpShowdownLog');
//}