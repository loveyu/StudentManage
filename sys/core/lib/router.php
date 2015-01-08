<?php
namespace CLib;
if(!defined('_CorePath_')){
	exit;
}

/**
 * 核心路由类库
 */
class Router{
	/**
	 * 简单重定向
	 * @var array
	 */
	private $_redirect;
	/**
	 * 正则重定向
	 * @var array
	 */
	private $_preg;

	/**
	 * 构造器
	 */
	function __construct(){
		$this->_redirect = array();
		$this->_preg = array();
	}

	/**
	 * 添加重定向路由
	 * @param string $form
	 * @param string $to
	 */
	public function add_redirect($form, $to){
		$this->_redirect[$form] = $to;
	}

	/**
	 * 根据正则表达式进行替换路由
	 * @param string|array $list 单个匹配时使用字符串非数组
	 * @param null|string  $set  使用单个匹配
	 */
	public function add_preg($list, $set = NULL){
		if(!is_null($set) && is_string($list)){
			$this->_preg[$list] = $set;
			return;
		}
		if(is_array($list)){
			foreach($list as $id => $v){
				if(!is_int($id) && is_string($v)){
					$this->_preg[$id] = $v;
				}
			}
		}
	}

	/**
	 * 执行路由替换工作，并返回对应的路由
	 * @param array $uri_list 传入的信息表
	 * @return array 返回的信息
	 */
	public function result($uri_list){
		$str = implode(ROUTER_SPLIT_CHAR, $uri_list);
		if(isset($this->_redirect[$str])){
			return $this->to_list($this->_redirect[$str]);
		}
		//支持正则表达式,使用preg_match_all();匹配，对仅有一次的匹配结果的进行替换，对应的值为数组的序号，用[0]表示完全匹配的结果
		//"/Topic-([0-9]+)-([0-9]+)\.html/"	=> "Topic/main/[1]/[2]",测试实例
		foreach($this->_preg as $rul => $page){
			//忽略错误的正则表达式
			if(@preg_match_all($rul, $str, $match, PREG_SET_ORDER) === 1){
				if($match[0][0] == $str){
					foreach($match[0] as $id => $val){
						$page = str_replace("[$id]", $val, $page);
					}
					return $this->to_list($page);
				}
			}
		}
		return $uri_list;
	}

	/**
	 * 对字符串进行路由转换
	 * @param $str
	 * @return array
	 */
	private function to_list($str){
		$list = explode(ROUTER_SPLIT_CHAR, trim($str));
		$rt = array();
		foreach($list as $v){
			if(trim($v) !== ""){
				$rt[] = trim($v);
			}
		}
		return $rt;
	}
}