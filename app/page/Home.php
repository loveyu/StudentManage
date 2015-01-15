<?php
namespace UView;

use ULib\Page;

class Home extends Page{

	function __construct(){
		parent::__construct();
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
		$cgf = cfg();
		$this->setTitle("管理");
		switch(login_class()->getLoginType()){
			case "teacher":
				$cgf->load(_RootPath_ . "/config/base_info.php");
				$filed = $cgf->get('teacher_info','filed');
				break;
			case "student":
				$cgf->load(_RootPath_ . "/config/base_info.php");
				$filed = $cgf->get('student_info','filed');
				break;
		}
		$role_access = list2keymap(db_class()->get_role_access_and_name(login_class()->role_id()), "p_id", [
			'ac_w',
			'p_name',
			'p_alias',
			'ac_r'
		]);
		$this->__view("home/main.php", compact('role_access', 'filed'));
	}

	/**
	 * 登陆页面
	 */
	public function login(){
		$msg = "";
		if($this->__req->is_post()){
			$user = $this->__req->post('user_name');
			$pwd = $this->__req->post('user_pwd');
			switch($this->__req->post('login_type')){
				case "student":
					$msg = login_class()->student_login($user, $pwd);
					break;
				case "teacher":
					$msg = login_class()->teacher_login($user, $pwd);
					break;
				case "admin":
					$msg = login_class()->login($user, $pwd);
					break;
				default:
					$msg = "未知登录类型";
					break;
			}
			if($msg === true){
				redirect('');
			}
		}
		$this->setTitle("后台登录");
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