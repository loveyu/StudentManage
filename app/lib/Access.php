<?php
/**
 * User: loveyu
 * Date: 2015/1/11
 * Time: 8:36
 */

namespace ULib;


class Access{
	private $table = [];

	function __construct(){
		$login = login_class();
		if($login->is_login()){
			$list = [];
			switch($login->getLoginType()){
				case "admin":
					$list = db_class()->get_admin_allow_access($login->uid());
					break;
				case "teacher":
				case "student":
					$list=db_class()->get_role_allow_access($login->role_id());
					break;
			}
			$this->table = list2keymap($list, 'name', [
				'r',
				'w'
			]);
		}
	}

	public function has($name){
		return isset($this->table[$name]);
	}

	public function write($name){
		return isset($this->table[$name]) && isset($this->table[$name]['w']) && $this->table[$name]['w'];

	}

	public function read($name){
		return isset($this->table[$name]) && isset($this->table[$name]['r']) && $this->table[$name]['r'];
	}

	public function wr($name){
		return isset($this->table[$name]) && isset($this->table[$name]['r']) && $this->table[$name]['r'] && isset($this->table[$name]['w']) && $this->table[$name]['w'];
	}

	public function custom($name, $custom){
		return isset($this->table[$name]) && isset($this->table[$name][$custom]) && $this->table[$name][$custom];
	}

	public function check($name, $mode = ''){
		if(empty($mode)){
			return isset($this->table[$name]);
		}
		for($i = 0; $i < strlen($mode); $i++){
			if(!$this->custom($name, $mode[$i])){
				return false;
			}
		}
		return true;
	}
}