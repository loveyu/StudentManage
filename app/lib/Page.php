<?php
/**
 * User: loveyu
 * Date: 2015/1/8
 * Time: 21:14
 */

namespace ULib;


class Page extends \Core\Page{

	private $title;

	function __construct(){
		parent::__construct();
	}

	public function get_header(){
		$this->__view("common/header.php");
	}

	public function get_footer(){
		$this->__view("common/footer.php");
	}

	/**
	 * 设置标题
	 * @param $title string
	 * @param $_     string 多级标题
	 */
	public function setTitle($title, $_ = NULL){
		$this->title = implode(" - ", func_get_args());
	}

	public function getTitle(){
		if(empty($this->title)){
			return "学籍管理";
		} else{
			return $this->title . " | 学籍管理";
		}
	}

	public function is_login(){
		return false;
	}

	public function get_bootstrap($file, $version = '3.3.1', $cache_code = ''){
		return get_file_url([
			'asset',
			'bootstrap',
			$version,
			$file
		]) . ((!empty($cache_code) && is_string($cache_code)) ? "?_v=" . $cache_code : "");
	}

	public static function __un_register(){
		return get_class_methods("ULib\\Page");
	}

}