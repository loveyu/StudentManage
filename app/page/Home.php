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
		$this->__view("home/main.php");
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
		$this->__view("home/login.php", ['msg' => $msg]);
	}

	/**
	 * 404
	 */
	public function not_found(){
		send_http_status(404);
		echo 404;
	}
}