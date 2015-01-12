<?php
namespace UView;

use ULib\Page;

class Home extends Page{

	function __construct(){
		parent::__construct();
	}

	public function test(){
		$db = db_class()->getDriver();
		for($i = 2; $i < 40; $i++){
			$k = sprintf("%04d", $i);
			//			$db->insert("info_college", [
			//				'ico_id' => $k,
			//				'ic_name' => '东校区',
			//				'ico_tel' => '123456',
			//				'ico_name' => salt(15),
			//				'ico_teacher' => salt(10)
			//			]);
		}

		$str = "`is_id`, `is_name`, `is_sex`, `is_birthday`, `is_province`, `is_city`, `is_county`, `is_zone`, `is_address`, `is_id_card`, `is_politics`, `ic_name`, `ico_id`, `id_id`, `icl_id`, `is_password`, `is_email`, `is_tel`, `is_room`, `is_room_number`, `is_study_date`, `is_grade`";
		$str = str_replace("`", "", $str);
		$list = array_map("trim", explode(",", $str));
		foreach($list as $v){
			echo $rr = <<<PHP
'{$v}' => [
	'name' => '',
	'type' => 'text',
	'vt' => 'text',
],

PHP;

		}
	}

	/**
	 * Home page
	 */
	public function main(){
		if(!$this->is_login()){
			redirect([
				'Home',
				'login'
			]);
		}
		$this->setTitle("管理");
		$this->__view("home/main.php", [
			'role_access' => list2keymap(db_class()->get_role_access_and_name(login_class()->role_id()), "p_id", [
				'ac_w',
				'p_name',
				'p_alias',
				'ac_r'
			])
		]);
	}

	/**
	 * 登陆页面
	 */
	public function login(){
		$msg = "";
		if($this->__req->is_post()){
			$msg = login_class()->login($this->__req->post('user_name'), $this->__req->post('user_pwd'));
			if($msg === true){
				redirect('');
			}
		}
		$this->setTitle("管理员登录");
		$this->__view("home/login.php", ['msg' => $msg]);
	}

	public function logout(){
		session_class()->destroy();
		redirect('Home', 'login');
	}

	/**
	 * 404
	 */
	public function not_found(){
		send_http_status(404);
		echo 404;
	}
}