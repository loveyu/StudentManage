<?php
namespace Core;
if(!defined('_CorePath_')){
	exit;
}

/**
 * Lib管理类
 */
class Lib{
	/**
	 * 类列表
	 * @var array
	 */
	private $lib_list;


	/**
	 * 类库存放的路径
	 * @var string
	 */
	private $lib_path;

	/**
	 * 存储已加载文件列表名
	 * @var array
	 */
	private $file_list = [];

	/**
	 * 构造函数
	 */
	function __construct($path){
		$this->lib_list = array();
		if(is_dir($path)){
			$this->lib_path = $path;
		} else{
			$this->lib_path = _LibPath_;
		}
	}

	/**
	 * 加载Lib类文件
	 * @return Lib
	 * @throws \Exception
	 */
	public function load(){
		$list = func_get_args();
		foreach($list as $name){
			if(!in_array($name, $this->file_list)){
				if(is_file($this->lib_path . "/" . $name . '.php')){
					require($this->lib_path . "/" . $name . '.php');
					$this->file_list[] = $name;
				} else{
					Log::write(_("Can't reload lib:") . $this->lib_path . "/" . $name . '.php', Log::ERR);
					if(_Debug_){
						throw new \Exception(_("Can't reload lib:") . $name);
					}
				}
			}
		}
		return $this;
	}


	/**
	 * 添加一个类的引用到数组中
	 * @param string $name
	 * @param object $obj
	 * @return object
	 */
	public function add($name, $obj){
		$this->lib_list[$name] = $obj;
		return $obj;
	}

	/**
	 * 使用一个类，即从数组中返回
	 * @param string $name
	 * @return bool|object
	 */
	public function using($name){
		if(isset($this->lib_list[$name])){
			return $this->lib_list[$name];
		} else{
			return false;
		}
	}

	/**
	 * 删除一个对应的类
	 * @param string $name
	 */
	public function del($name){
		if(isset($this->lib_list[$name])){
			unset($this->lib_list[$name]);
		}
	}
}