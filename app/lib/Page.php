<?php
/**
 * User: loveyu
 * Date: 2015/1/8
 * Time: 21:14
 */

namespace ULib;


class Page extends \Core\Page{

	/**
	 * @var string
	 */
	private $title;

	/**
	 * @var \ULib\Login
	 */
	private $login = NULL;

	function __construct(){
		parent::__construct();
		ob_start();
	}

	public function get_header(){
		$this->__view("common/header.php");
	}

	public function get_footer(){
		$this->__view("common/footer.php");
	}

	public function get_user_detail(){
		if($this->is_login()){
			return $this->login->detail();
		}
		return NULL;
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
		if($this->login === NULL){
			$this->login = login_class();
		}
		return $this->login->is_login();
	}

	public function get_bootstrap($file, $version = '3.3.1', $cache_code = ''){
		return $this->get_asset("bootstrap/{$version}/{$file}", $cache_code);
	}

	public function get_asset($file, $cache_code = ''){
		return get_file_url([
			'asset',
			$file
		]) . ((!empty($cache_code) && is_string($cache_code)) ? "?_v=" . $cache_code : "");
	}


	/**
	 * @param string $class
	 * @return string
	 */
	public function get_user_menu($class = "active"){
		$list = cfg()->get('menu');
		$rt = "";
		$ui = implode("/", $this->__uri->getUriInfo()->getUrlList());
		foreach($list as $v){
			$flag = $ui == implode("/", $v['url']);
			if((!isset($v['role']) || login_class()->check_role($v['role'])) && (!isset($v['hide']) || !$v['hide'] || ($v['hide'] && $flag[0]))){
				$rt .= "<li role=\"presentation\"" . ($flag ? " class=\"$class\"" : '') . "><a href='" . get_url($v['url']) . "'>" . $v['name'] . "</a></li>\n";
			}
		}
		return $rt;
	}

	public static function __un_register(){
		return get_class_methods("ULib\\Page");
	}

	function __destruct(){
		$content = ob_get_contents();
		ob_get_clean();
		if(defined('HAS_RUN_ERROR')){
			echo cfg()->get('HAS_RUN_ERROR');
		} else{
			echo $content;
		}
		@ob_flush();
		@flush();
		@ob_end_flush();
	}

}