<?php
namespace Core;
if(!defined('_CorePath_')){
	exit;
}

/**
 * 系统钩子
 * @author loveyu
 */
class Hook{
	/**
	 * 用于存放对应的构造列表
	 * @var array
	 */
	private $_hook_list;

	/**
	 * 构造方法
	 */
	public function __construct(){
		// TODO: Implement __construct() method.
		$this->_hook_list = array();
	}


	/**
	 * 添加一个钩子到系统
	 * @param string   $name
	 * @param callback $func
	 * @throws \Exception
	 */
	public function add($name, $func){
		if(!is_callable($func)){
			Log::write(_("Error Callback function") . "Name:" . $name . ",Func:" . gettype($func), Log::ALERT);
			throw new \Exception(_("Error Callback function"));
		}
		if(!isset($this->_hook_list[$name])){
			$this->_hook_list[$name] = array();
		}
		if(is_array($func)){
			$this->_hook_list[$name][get_class($func[0]) . ":" . $func[1]] = $func;
		} else if(is_string($func)){
			$this->_hook_list[$name][$func] = $func;
		} else{
			$this->_hook_list[$name]["_v_" . count($this->_hook_list[$name])] = $func;
		}
	}

	/**
	 * 检测一个钩子是否有信息被注册
	 * @param string $name
	 * @return bool
	 */
	public function check($name){
		return isset($this->_hook_list[$name]);
	}


	/**
	 * 应用钩子,至少包含一个参数名
	 * @param string $name   对应名称
	 * @param mixed  $param1 第一个参数
	 * @return mixed 返回调用的第一个参数
	 */
	public function apply($name, $param1){
		//必须存在$param1对$args[0]进行约束
		if(isset($this->_hook_list[$name])){
			$args = array_slice(func_get_args(), 1);
			foreach($this->_hook_list[$name] as $v){
				$param1 = call_user_func_array($v, $args);
				$args[0] = $param1;
			}
		}
		return $param1;
	}

	/**
	 * 移除对应的钩子
	 * @param string          $name 钩子名称
	 * @param callback|string $func 钩子对应的调用方法
	 */
	public function remove($name, $func = ''){
		if(isset($this->_hook_list[$name])){
			if(empty($func)){
				unset($this->_hook_list[$name]);
			} else{
				if(is_array($func)){
					if(isset($this->_hook_list[$name][get_class($func[0]) . ":" . $func[1]])){
						unset($this->_hook_list[$name][get_class($func[0]) . ":" . $func[1]]);
					}
				} else{
					if(isset($this->_hook_list[$name][$func])){
						unset($this->_hook_list[$name][$func]);
					}
				}
			}
		}
	}
}