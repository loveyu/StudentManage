<?php
/**
 * User: loveyu
 * Date: 2015/1/9
 * Time: 13:42
 */

namespace UView;


use ULib\Page;

class Profile extends Page{
	function __construct(){
		parent::__construct();
		if(!login_class()->is_login()){
			redirect([
				'Home',
				'login'
			]);
		}
	}

	function edit_pwd(){
		if($this->__req->is_post()){
			header("Content-Type: application/json; charset=utf-8");
			$login = login_class();
			$rt = [
				'status' => false,
				'msg' => NULL
			];
			if(!$login->is_login()){
				$rt['msg'] = '未登录';
			} else{
				$old = trim($this->__req->post('old_pwd'));
				$new_pwd = trim($this->__req->post('new_pwd'));
				$new_c_pwd = trim($this->__req->post('new_c_pwd'));
				if(empty($old) || empty($new_pwd) || empty($new_c_pwd)){
					$rt['msg'] = '表单有空值';
				} else{
					if($new_pwd !== $new_c_pwd){
						$rt['msg'] = "密码确认不一致";
					} else{
						if(strlen($new_c_pwd)<6){
							$rt['msg'] = '新密码长度不得小于六位';
						}else{
							$rt['msg'] = $login->edit_pwd($old, $new_pwd);
							if($rt['msg'] === true){
								$rt['status'] = true;
							}
						}
					}
				}
			}
			echo json_encode($rt);
		} else{
			$this->__view('home/profile_edit_pwd.php');
		}
	}

}