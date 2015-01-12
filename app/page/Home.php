<?php
namespace UView;

use ULib\Page;

class Home extends Page{

	function __construct(){
		parent::__construct();
	}

	public function test(){
		header("Content-Type: text/plain; charset=utf-8");
		$content = file_get_contents("https://gist.githubusercontent.com/denzeljiang/5ae88a5ebb261570aad7/raw/87b8bf86ecbcae18565a1c73286f643e454d90e8/Districts_in_China");
		$s = json_decode($content, true);
		$obj = [];
		foreach($s as $v){
			foreach($v as $x){
				$obj[$x['name']] = [];
				if(isset($x['city']) && is_array($x['city'])){
					foreach($x['city'] as $xx){
						$obj[$x['name']][] = $xx['name'];
					}
				}
			}
		}
		echo json_encode($obj,JSON_UNESCAPED_UNICODE);
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