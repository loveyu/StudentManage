<?php
namespace Core;
if(!defined('_CorePath_'))
	exit;

/**
 * 配置文件操作类
 */
class Config{

	/**
	 * @var array 配置大数组
	 */
	private $_config;

	/**
	 * 构造函数
	 */
	function __construct(){
		$this->_config = array();
	}

	/**
	 * 读取配置文件并保存
	 *
	 * @param string $file_path 配置文件路径，无目录
	 * @return mixed|null
	 */
	public function load($file_path){
		if(is_file($file_path)){
			$config = require($file_path);
			if(is_array($config)){
				foreach($config as $id => $v){
					if(!is_numeric($id) && !isset($this->_config[$id])){
						$this->_config[$id] = $v;
					}
				}
			}
			return $config;
		}
		return null;
	}

	/**
	 * 获取配置
	 *
	 * @return array|string|null|bool|object
	 */
	public function get(){
		$rs = & $this->_config;
		foreach(func_get_args() as $v){
			if(isset($rs[$v])){
				$rs = & $rs[$v];
			} else{
				return null;
			}
		}
		return $rs;
	}


	/**
	 * 修改指定配置文件
	 *
	 * @param array|string $key
	 * @param mixed $value
	 */
	public function set($key, $value){
		$p = & $this->_config;
		if(!is_array($key)){
			$key = [$key];
		}
		$count = count($key);
		foreach($key as $v){
			$count--;
			if(isset($p[$v])){
				if(!$count){
					$p[$v] = $value;
					return;
				}
			} else{
				if($count){
					$p[$v] = array();
				} else{
					$p[$v] = $value;
					return;
				}
			}
			$p = & $p[$v];
		}
	}
}

?>