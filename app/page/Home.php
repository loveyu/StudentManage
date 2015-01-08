<?php
namespace UView;

use ULib\Page;

class Home extends Page{

	function __construct(){

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
		$this->__view("home/login.php");
	}

	/**
	 * 404
	 */
	public function not_found(){
		send_http_status(404);
		echo 404;
	}
}