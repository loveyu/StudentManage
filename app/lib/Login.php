<?php
/**
 * User: loveyu
 * Date: 2015/1/8
 * Time: 22:55
 */

namespace ULib;


class Login{
	/**
	 * @var bool
	 */
	private $status;
	/**
	 * @var DB
	 */
	private $db;

	/**
	 * @var \CLib\Session
	 */
	private $session;

	/**
	 * @var array
	 */
	private $user_info;

	function __construct(){
		$this->status = false;
		$this->db = db_class();
		$this->session = session_class();
		$this->auto_login();
	}

	public function auto_login(){
		$this->user_info = $this->session->get('admin');
		foreach([
			'name',
			'role_id',
			'ip',
			'ip_list',
			'status'
		] as $v){
			if(!isset($this->user_info[$v])){
				return;
			}
		}
		$ip = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : NULL;
		if($ip !== $this->user_info['ip']){
			return;
		}
		if(!$this->ip_filter($this->user_info['ip_list'], $ip)){
			return;
		}
		if($this->status == 1){
			return;
		}
		$this->status = true;
	}

	public function edit_pwd($old, $new){
		$this->db = db_class();
		$info = $this->db->get_admin_info($this->user_info['name']);
		if(salt_hash(md5_xx($old), $info['a_salt']) != $info['a_pwd']){
			return "原密码错误";
		}
		$update = ['a_salt' => salt(32)];
		$update['a_pwd'] = salt_hash(md5_xx($new), $update['a_salt']);
		if($this->db->update_user_info($this->user_info['name'], $update) == 1){
			return true;
		}
		return "修改密码失败";
	}

	public function check_role($id){
		return isset($this->user_info['role_id']) && $this->user_info['role_id'] == $id;
	}

	public function is_login(){
		return $this->status;
	}

	public function detail(){
		if($this->status){
			$this->db = db_class();
			$access = $this->db->get_access($this->user_info['name']);
			return array_merge($access, ['user' => $this->user_info]);
		}
		return NULL;
	}

	public function login($user, $password){
		$user = trim($user);
		$password = trim($password);
		$info = $this->db->get_admin_info($user);
		if(isset($info['a_name']) && $info['a_name'] === $user){
			if(salt_hash(md5_xx($password), $info['a_salt']) == $info['a_pwd']){
				if($info['a_status'] == 1){
					return "账户被禁用";
				} else{
					$ip = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : NULL;
					if($this->ip_filter($info['a_ip'], $ip)){
						$this->status = true;
						$this->set_session($info, $ip);
						return true;
					} else{
						return "当前IP{" . ($ip ? $ip : "{NULL}") . "}禁止登陆";
					}
				}
			} else{
				return "用户名或密码错误";
			}
		} else{
			return "用户名不存在";
		}
	}

	private function set_session($info, $ip){
		$this->user_info = [
			'name' => $info['a_name'],
			'role_id' => $info['r_id'],
			'status' => $info['a_status'],
			'ip' => $ip,
			'ip_list' => $info['a_ip']
		];
		$this->session->set('admin', $this->user_info);
	}

	private function ip_filter(&$list, $ip){
		if(!is_array($list)){
			$list = explode("|", $list);
		}
		$list = array_map('trim', $list);
		$list = array_unique($list);
		if(empty($ip)){
			if(in_array("*", $list)){
				return true;
			} else{
				return false;
			}
		}
		foreach($list as $v){
			if($v == "*"){
				return true;
			}
			$v = str_replace(".", "\\.", $v);
			$v = str_replace("*", "[\\S]+?", $v);
			if(preg_match("/^$v$/", $ip) == 1){
				return true;
			}
		}
		return false;
	}
}